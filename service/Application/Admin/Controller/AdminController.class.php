<?php

namespace Admin\Controller;

/**
 * 管理员
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AdminController extends CommonController
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
	}

	/**
     * [Index 管理员列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 登录校验
		$this->Is_Login();
		
		// 权限校验
		$this->Is_Power();

		// 参数
		$param = array_merge($_POST, $_GET);

		// 模型对象
		$m = M('Admin');

		// 条件
		$where = $this->GetIndexWhere();

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/Admin/Index'),
			);
		$page = new \Library\Page($page_param);

		// 获取管理员列表
		$list = $m->where($where)->limit($page->GetPageStarNumber(), $number)->select();
		if(!empty($list))
		{
			foreach($list as &$v)
			{
				$v['role_name'] = M('Role')->where(['id'=>$v['role_id']])->getField('name');
			}
		}
		
		// 角色
		$role = M('Role')->field(array('id', 'name'))->where(array('is_enable'=>1))->select();

		// 性别
		$this->assign('common_gender_list', L('common_gender_list'));

		$this->assign('role', $role);
		$this->assign('param', $param);
		$this->assign('page_html', $page->GetPageHtml());
		$this->assign('list', $list);
		$this->display('Index');
	}

	/**
	 * [GetIndexWhere 管理员列表条件]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T22:16:29+0800
	 */
	private function GetIndexWhere()
	{
		$where = array();
		if(!empty($_REQUEST['username']))
		{
			$where['username'] = array('like', '%'.I('username').'%');
		}
		if(I('role_id', -1) > -1)
		{
			$where['role_id'] = I('role_id');
		}

		// 等值
		if(I('gender', -1) > -1)
		{
			$where['gender'] = intval(I('gender', 0));
		}

		return $where;
	}

	/**
     * [SaveInfo 管理员添加/编辑页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function SaveInfo()
	{
		// 登录校验
		$this->Is_Login();

		// 不是操作自己的情况下
		if(I('id') != $this->admin['id'])
		{
			// 权限校验
			$this->Is_Power();
		}

		// 用户编辑
		$id = I('id');
		if($id > 0)
		{
			$user =  M('Admin')->where(array('id'=>$id))->find();
			if(empty($user))
			{
				$this->error(L('login_username_no_exist'), U('Admin/Index/Index'));
			}
			$this->assign('data', $user);
		}

		$role = M('Role')->field(array('id', 'name'))->where(array('is_enable'=>1))->select();
		$this->assign('role', $role);

		// 组织列表
		$organization_where = array('is_enable'=>1);
		$organization_list = M('Organization')->where($organization_where)->select();
		$this->assign('organization_list', $organization_list);

		$this->assign('id', $id);
		$this->assign('common_gender_list', L('common_gender_list'));
		$this->display('SaveInfo');
	}

	/**
     * [Save 管理员添加/编辑]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-07T21:58:19+0800
     */
	public function Save()
	{
		// 登录校验
		$this->Is_Login();

		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 不是操作自己的情况下
		if(I('id') != $this->admin['id'])
		{
			// 权限校验
			$this->Is_Power();
		}

		// id为空则表示是新增
		if(empty($_POST['id']))
		{
			$this->AdminAdd();
		} else {
			$this->AdminEdit();
		}
	}

	/**
	 * [AdminAdd 管理员添加]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-24T22:44:28+0800
	 */
	private function AdminAdd()
	{
		$m = D('Admin');
		if($m->create($_POST, 1))
		{
			// 额外数据处理
			$m->login_salt	=	GetNumberCode(6);
			$m->login_pwd 	=	LoginPwdEncryption($m->login_pwd, $m->login_salt);
			$m->add_time	=	time();
			
			// 写入数据库
			if($m->add())
			{
				$this->ajaxReturn(L('common_operation_add_success'));
			} else {
				$this->ajaxReturn(L('common_operation_add_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [AdminEdit 管理员编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-24T22:46:03+0800
	 */
	private function AdminEdit()
	{
		$m = D('Admin');
		// 移除username，不允许更新用户名
		unset($m->username);
		
		if($m->create($_POST, 2))
		{
			// 不能修改自身所属角色组
			if(I('id') == $this->admin['id'])
			{
				unset($m->role_id);
			}

			// 有密码，则更新密码
			if(!empty($_POST['login_pwd']))
			{
				$m->login_salt	=	GetNumberCode(6);
				$m->login_pwd 	=	LoginPwdEncryption($m->login_pwd, $m->login_salt);
			} else {
				unset($m->login_pwd);
			}

			// 附加数据
			$m->upd_time	=	time();

			// 更新数据库
			if($m->where(array('id'=>I('id')))->save())
			{
				// 编辑自身则退出重新登录
				if(!empty($_POST['login_pwd']) && I('id') == $this->admin['id'])
				{
					session_destroy();
				}

				$this->ajaxReturn(L('common_operation_edit_success'));
			} else {
				$this->ajaxReturn(L('common_operation_edit_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Delete 管理员删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-09T21:13:47+0800
	 */
	public function Delete()
	{
		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();

		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		$m = D('Admin');
		if($m->create($_POST, 5))
		{
			if($m->delete($id))
			{
				$this->ajaxReturn(L('common_operation_delete_success'));
			} else {
				$this->ajaxReturn(L('common_operation_delete_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [LoginInfo 登录页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:55:53+0800
     */
	public function LoginInfo()
	{
		// 是否已登录
		if(!empty($_SESSION['admin']))
		{
			redirect(U('Admin/Index/Index'));
		}

		$this->display('LoginInfo');
	}

	/**
	 * [Login 管理员登录]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T21:46:49+0800
	 */
	public function Login()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 登录业务处理
		$m = D('Admin');
		if($m->create($_POST, 4))
		{
			// 获取管理员
			$user = $m->field(array('id', 'username', 'login_pwd', 'login_salt', 'mobile', 'login_total', 'role_id'))->where(array('username'=>I('username')))->find();
			if(empty($user))
			{
				$this->ajaxReturn(L('login_username_no_exist'), -2);
			}

			// 密码校验
			$login_pwd = LoginPwdEncryption(I('login_pwd'), $user['login_salt']);
			if($login_pwd != $user['login_pwd'])
			{
				$this->ajaxReturn(L('login_login_pwd_error'), -3);
			}

			// 校验成功
			// session存储
			unset($user['login_pwd'], $user['login_salt']);
			$_SESSION['admin'] = $user;

			// 返回数据,更新数据库
			if(!empty($_SESSION['admin']))
			{
				$login_salt = GetNumberCode(6);
				$data = array(
						'login_salt'	=>	$login_salt,
						'login_pwd'		=>	LoginPwdEncryption(I('login_pwd'), $login_salt),
						'login_total'	=>	$user['login_total']+1,
						'login_time'	=>	time(),
					);
				if($m->where(array('id'=>$user['id']))->save($data))
				{
					// 清空缓存目录下的数据
					EmptyDir(C('DATA_CACHE_PATH'));

					$this->ajaxReturn(L('login_login_success'));
				}
			}

			// 失败
			unset($_SESSION['admin']);
			$this->ajaxReturn(L('login_login_error'), -100);
		} else {
			// 自动验证失败
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Logout 退出]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-05T14:31:23+0800
	 */
	public function Logout()
	{
		session_destroy();
		redirect(U('Admin/Admin/LoginInfo'));
	}
}
?>