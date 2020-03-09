<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\facade\Hook;
use app\service\AdminService;

/**
 * 管理员
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Admin extends Common
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
		$this->IsLogin();
		
		// 权限校验
		$this->IsPower();

		// 参数
		$params = input();

		// 条件
		$where = AdminService::AdminListWhere($params);

		// 总数
		$total = AdminService::AdminTotal($where);

		// 分页
		$number = MyC('admin_page_number', 10, true);
		$page_params = array(
				'number'	=>	$number,
				'total'		=>	$total,
				'where'		=>	$params,
				'page'		=>	isset($params['page']) ? intval($params['page']) : 1,
				'url'		=>	MyUrl('admin/admin/index'),
			);
		$page = new \base\Page($page_params);
		$this->assign('page_html', $page->GetPageHtml());

		// 获取管理员列表
		$data_params = [
			'where'		=> $where,
			'm'			=> $page->GetPageStarNumber(),
			'n'			=> $number,
		];
		$data = AdminService::AdminList($data_params);
		$this->assign('data_list', $data['data']);
		
		// 角色
		$role_params = [
			'where'		=> ['is_enable'=>1],
			'field'		=> 'id,name',
		];
		$role = AdminService::RoleList($role_params);
		$this->assign('role_list', $role['data']);

		// 性别
		$this->assign('common_gender_list', lang('common_gender_list'));

		$this->assign('params', $params);
		return $this->fetch();
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
		$this->IsLogin();

		// 参数
		$params = input();

		// 不是操作自己的情况下
		if(!isset($params['id']) || $params['id'] != $this->admin['id'])
		{
			// 权限校验
			$this->IsPower();
		}

		// 管理员编辑
		$data = [];
		if(!empty($params['id']))
		{
			$data_params = [
				'where'		=> ['id'=>$params['id']],
				'm'			=> 0,
				'n'			=> 1,
			];
			$ret = AdminService::AdminList($data_params);
			if(empty($ret['data'][0]))
			{
				return $this->error('管理员信息不存在', MyUrl('admin/index/index'));
			}
			$data = $ret['data'][0];
		}

		// 角色
		$role_params = [
			'where'		=> ['is_enable'=>1],
			'field'		=> 'id,name',
		];
		$role = AdminService::RoleList($role_params);
		$this->assign('role_list', $role['data']);

		$this->assign('id', isset($params['id']) ? $params['id'] : 0);
		$this->assign('common_gender_list', lang('common_gender_list'));

		// 管理员编辑页面钩子
        $hook_name = 'plugins_view_admin_admin_save';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'admin_id'      => isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'params'        => &$params,
        ]));

        // 数据
        $this->assign('data', $data);
        $this->assign('params', $params);
		return $this->fetch();
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
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 登录校验
		$this->IsLogin();

		// 参数
		$params = input('post.');

		// 不是操作自己的情况下
		if(!isset($params['id']) || $params['id'] != $this->admin['id'])
		{
			// 权限校验
			$this->IsPower();
		}

		// 开始操作
		$params['admin'] = $this->admin;
		return AdminService::AdminSave($params);
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
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 登录校验
		$this->IsLogin();

		// 权限校验
		$this->IsPower();

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return AdminService::AdminDelete($params);
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
		if(session('admin') !== null)
		{
			return redirect(MyUrl('admin/index/index'));
		}

		// 管理员登录页面钩子
        $hook_name = 'plugins_view_admin_login_info';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
        ]));

		return $this->fetch();
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
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		return AdminService::Login($params);
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
		return redirect(MyUrl('admin/admin/logininfo'));
	}
}
?>