<?php

namespace Admin\Controller;

/**
 * 权限管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PowerController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 权限组列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 获取权限列表
		$m = M('Power');
		$field = array('id', 'pid', 'name', 'control', 'action', 'sort', 'is_show', 'icon');
		$list = $m->field($field)->where(array('pid'=>0))->order('sort')->select();
		if(!empty($list))
		{
			foreach($list as $k=>$v)
			{
				$item =  $m->field($field)->where(array('pid'=>$v['id']))->order('sort')->select();
				if(!empty($item))
				{
					$list[$k]['item'] = $item;
				}
			}
		}
		$this->assign('common_is_show_list', L('common_is_show_list'));
		$this->assign('list', $list);
		$this->display('Index');
	}

	/**
	 * [PowerSave 权限添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T21:41:03+0800
	 */
	public function PowerSave()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// id为空则表示是新增
		$m = D('Power');

		// 公共额外数据处理
		$m->sort 	=	intval(I('sort'));

		// 添加
		if(empty($_POST['id']))
		{
			if($m->create($_POST, 1))
			{
				// 额外数据处理
				$m->add_time	=	time();
				$m->name 		=	I('name');
				$m->control 	=	I('control');
				$m->action 		=	I('action');
				$m->icon 		=	I('icon');
				
				// 写入数据库
				if($m->add())
				{
					// 清除用户权限数据
					PowerCacheDelete();

					$this->ajaxReturn(L('common_operation_add_success'));
				} else {
					$this->ajaxReturn(L('common_operation_add_error'), -100);
				}
			}
		} else {
			// 编辑
			if($m->create($_POST, 2))
			{
				// 额外数据处理
				$m->name 		=	I('name');
				$m->control 	=	I('control');
				$m->action 		=	I('action');
				$m->icon 		=	I('icon');

				// 移除 id
				unset($m->id);

				// 更新数据库
				if($m->where(array('id'=>I('id')))->save())
				{
					// 清除用户权限数据
					PowerCacheDelete();

					$this->ajaxReturn(L('common_operation_edit_success'));
				} else {
					$this->ajaxReturn(L('common_operation_edit_error'), -100);
				}
			}
		}
		$this->ajaxReturn($m->getError(), -1);
	}

	/**
	 * [PowerDelete 权限删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:40:29+0800
	 */
	public function PowerDelete()
	{
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		$m = D('Power');
		if($m->create($_POST, 5))
		{
			if($m->delete(I('id')))
			{
				// 清除用户权限数据
				PowerCacheDelete();

				$this->ajaxReturn(L('common_operation_delete_success'));
			} else {
				$this->ajaxReturn(L('common_operation_delete_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Role 角色组列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function Role()
	{
		$m = M('Role');
		$list = $m->field(array('id', 'name', 'is_enable', 'add_time'))->select();
		if(!empty($list))
		{
			foreach($list as $k=>$v)
			{
				// 关联查询权限和角色数据
				if($v['id'] == 1)
				{
					$list[$k]['item'] = M('Power')->select();
				} else {
					$list[$k]['item'] = $m->alias('r')->join('__ROLE_POWER__ AS rp ON rp.role_id = r.id')->join('__POWER__ AS p ON rp.power_id = p.id')->where(array('r.id'=>$v['id']))->field(array('p.id', 'p.name'))->select();
				}
			}
		}
		$this->assign('list', $list);
		$this->display('Role');
	}

	/**
	 * [RoleSaveInfo 角色组添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function RoleSaveInfo()
	{
		// 角色组
		$role = M('Role')->field(array('id', 'name', 'is_enable'))->find(I('id'));
		$role_id = isset($role['id']) ? $role['id'] : $this->admin['role_id'];
		$power = array();
		if($role_id > 0)
		{
			// 权限关联数据
			$action = empty($_REQUEST['id']) ? array() : M('RolePower')->where(array('role_id'=>$role_id))->getField('power_id', true);

			// 权限列表
			$m = M('Power');
			$power_field = array('id', 'name', 'is_show');
			$power = $m->field($power_field)->where(array('pid'=>0))->order('sort')->select();
			if(!empty($power))
			{
				foreach($power as $k=>$v)
				{
					// 是否有权限
					$power[$k]['is_power'] = in_array($v['id'], $action) ? 'ok' : 'no';

					// 获取子权限
					$item =  $m->field($power_field)->where(array('pid'=>$v['id']))->order('sort')->select();
					if(!empty($item))
					{
						foreach($item as $ks=>$vs)
						{
							$item[$ks]['is_power'] = in_array($vs['id'], $action) ? 'ok' : 'no';
						}
						$power[$k]['item'] = $item;
					}
				}
			}
		}
		$this->assign('common_is_enable_list', L('common_is_enable_list'));
		$this->assign('data', $role);
		$this->assign('power', $power);
		$this->display('RoleSaveInfo');
	}

	/**
	 * [RoleSave 角色组添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function RoleSave()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 添加
		if(empty($_POST['id']))
		{
			$this->RoleAdd();

		// 编辑
		} else {
			if(I('id') == 1)
			{
				$this->error(L('common_do_not_operate'), -10);
			} else {
				$this->RoleEdit();
			}
		}
	}

	/**
	 * [RoleAdd 角色添加]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-18T16:20:59+0800
	 */
	private function RoleAdd()
	{
		// 角色对象
		$r = M('Role');

		// 数据自动校验
		if($r->create($_POST, 1))
		{
			// 开启事务
			$r->startTrans();

			// 角色添加
			$role_data = array(
					'name'		=>	I('name'),
					'is_enable'	=>	I('is_enable'),
					'add_time'	=>	time(),
				);
			$role_id = $r->add($role_data);

			// 角色权限关联添加
			$rp_state = true;
			if(!empty($_POST['power_id']) && is_array($_POST['power_id']))
			{
				// 角色权限关联对象
				$rp = M('RolePower');
				foreach($_POST['power_id'] as $power_id)
				{
					if(!empty($power_id))
					{
						$rp_data = array(
								'role_id'	=>	$role_id,
								'power_id'	=>	$power_id,
								'add_time'	=>	time(),
							);
						if(!$rp->add($rp_data))
						{
							$rp_state = false;
							break;
						}
					}
				}
			}
			if($role_id && $rp_state)
			{
				// 提交事务
				$r->commit();

				// 清除用户权限数据
				PowerCacheDelete();

				$this->ajaxReturn(L('common_operation_add_success'));
			} else {
				// 回滚事务
				$r->rollback();
				$this->ajaxReturn(L('common_operation_add_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [RoleEdit 角色和角色权限关联编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-17T22:13:40+0800
	 */
	private function RoleEdit()
	{
		// 角色对象
		$r = M('Role');

		// 数据自动校验
		if($r->create($_POST, 2))
		{
			// 开启事务
			$r->startTrans();

			// 角色数据更新
			$role_data = array(
					'name'		=>	I('name'),
					'is_enable'	=>	I('is_enable'),
				);
			$r_state = ($r->where(array('id'=>I('id')))->save($role_data) !== false);

			// 角色权限关联对象
			$rp = M('RolePower');

			// 角色id
			$role_id = I('id');

			// 权限关联数据删除
			$rp_del_state = $rp->where(array('role_id'=>$role_id))->delete();

			// 权限关联数据添加
			$rp_state = true;
			if(!empty($_POST['power_id']))
			{
				$power_id_list = explode(',', $_POST['power_id']);
				foreach($power_id_list as $power_id)
				{
					if(!empty($power_id))
					{
						$rp_data = array(
								'role_id'	=>	$role_id,
								'power_id'	=>	$power_id,
								'add_time'	=>	time(),
							);
						if(!$rp->add($rp_data))
						{
							$rp_state = false;
							break;
						}
					}
				}
			}
			if($r_state !== false && $rp_del_state !== false && $rp_state !== false)
			{
				// 提交事务
				$r->commit();

				// 清除用户权限数据
				PowerCacheDelete();

				$this->ajaxReturn(L('common_operation_edit_success'));
			} else {
				// 回滚事务
				$r->rollback();
				$this->ajaxReturn(L('common_operation_edit_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [RoleDelete 角色删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function RoleDelete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数是否有误
		if(empty($_POST['id']))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 角色模型
		$r = M('Role');

		// 开启事务
		$r->startTrans();

		// 删除角色
		$role_state = $r->delete(I('id'));
		$rp_state = M('RolePower')->where(array('role_id'=>I('id')))->delete();
		if($role_state !== false && $rp_state !== false)
		{
			// 提交事务
			$r->commit();

			// 清除用户权限数据
			PowerCacheDelete();

			$this->ajaxReturn(L('common_operation_delete_success'));
		} else {
			// 回滚事务
			$r->rollback();
			$this->ajaxReturn(L('common_operation_delete_error'), -100);
		}
	}
}
?>