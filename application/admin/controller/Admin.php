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

		// 总数
		$total = AdminService::AdminTotal($this->form_where);

		// 分页
		$page_params = [
			'number'	=>	$this->page_size,
			'total'		=>	$total,
			'where'		=>	$this->data_request,
			'page'		=>	$this->page,
			'url'		=>	MyUrl('admin/admin/index'),
		];
		$page = new \base\Page($page_params);

		// 获取数据列表
		$data_params = [
            'where'         => $this->form_where,
            'm'             => $page->GetPageStarNumber(),
            'n'             => $this->page_size,
        ];
		$ret = AdminService::AdminList($data_params);

		// 基础参数赋值
		$this->assign('params', $this->data_request);
		$this->assign('page_html', $page->GetPageHtml());
		$this->assign('data_list', $ret['data']);
		return $this->fetch();
	}

	/**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-05T08:21:54+0800
     */
    public function Detail()
    {
        if(!empty($this->data_request['id']))
        {
            // 条件
            $where = [
                ['id', '=', intval($this->data_request['id'])],
            ];

            // 获取列表
            $data_params = [
                'm'             => 0,
                'n'             => 1,
                'where'         => $where,
            ];
            $ret = AdminService::AdminList($data_params);
            $data = (empty($ret['data']) || empty($ret['data'][0])) ? [] : $ret['data'][0];
            $this->assign('data', $data);
        }
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
		$params = $this->data_request;

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
		$this->assign('common_admin_status_list', lang('common_admin_status_list'));

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
		$params = $this->data_post;

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
		$params = $this->data_post;
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

        // 背景图片
        $host = config('shopxo.attachment_host');
        $bg_images_list = [
            $host.'/static/admin/default/images/login/1.jpg',
            $host.'/static/admin/default/images/login/2.jpg',
            $host.'/static/admin/default/images/login/3.jpg',
            $host.'/static/admin/default/images/login/4.jpg',
            $host.'/static/admin/default/images/login/5.jpg',
            $host.'/static/admin/default/images/login/6.jpg',
            $host.'/static/admin/default/images/login/7.jpg',
            $host.'/static/admin/default/images/login/8.jpg',
            $host.'/static/admin/default/images/login/9.jpg',
            $host.'/static/admin/default/images/login/10.jpg',
            $host.'/static/admin/default/images/login/11.jpg',
            $host.'/static/admin/default/images/login/12.jpg',
            $host.'/static/admin/default/images/login/13.jpg',
            $host.'/static/admin/default/images/login/14.jpg',
            $host.'/static/admin/default/images/login/15.jpg',
        ];
        $this->assign('bg_images_list', $bg_images_list);

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
		$params = $this->data_post;
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
		session('admin', null);
		return redirect(MyUrl('admin/admin/logininfo'));
	}
}
?>