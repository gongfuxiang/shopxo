<?php
namespace app\admin\controller;

use app\service\AdminPowerService;

/**
 * 权限管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Power extends Common
{
	/**
	 * 构造方法
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

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
		$data_params = [
			'field'		=> 'id,pid,name,control,action,sort,is_show,icon',
			'order_by'	=> 'sort asc',
			'where'		=> ['pid'=>0],
		];
		$data = AdminPowerService::PowerList($data_params);

		$this->assign('data', $data);
		$this->assign('common_is_show_list', lang('common_is_show_list'));
		return $this->fetch();
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
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error(lang('common_unauthorized_access'));
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		$ret = AdminPowerService::PowerSave($params);
		return json($ret);
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
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error(lang('common_unauthorized_access'));
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		$ret = AdminPowerService::PowerDelete($params);
		return json($ret);
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
		$data_params = [
			'field'	=> 'id,name,is_enable,add_time',
		];
		$data = AdminPowerService::RoleList($data_params);

		$this->assign('data', $data);
		return $this->fetch();
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
		// 参数
		$params = input();

		// 角色组
		if(!empty($params['id']))
		{
			$data_params = [
				'where'	=> ['id'=>intval($params['id'])],
			];
			$data = AdminPowerService::RoleList($data_params);
			if(!empty($data[0]['id']))
			{
				$this->assign('data', $data[0]);

				// 权限关联数据
				$params['role_id'] =  $data[0]['id'];
			}
		}

		// 菜单列表
		$power = AdminPowerService::RolePowerEditData($params);

		$this->assign('common_is_enable_list', lang('common_is_enable_list'));
		$this->assign('power', $power);
		return $this->fetch();
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
			$this->error(lang('common_unauthorized_access'));
		}

		// 开始操作
		$ret = AdminPowerService::RoleSave(input('post.'));
		return json($ret);
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
			$this->error(lang('common_unauthorized_access'));
		}

		// 开始操作
		$ret = AdminPowerService::RoleDelete(input('post.'));
		return json($ret);
	}

	/**
	 * [RoleStatusUpdate 角色状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function RoleStatusUpdate()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error(lang('common_unauthorized_access'));
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		$ret = AdminPowerService::RoleStatusUpdate($params);
		return json($ret);
	}
}
?>