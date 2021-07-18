<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\service\AdminService;
use app\service\SystemBaseService;

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
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();
	}

	/**
     * 管理员列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
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
            'order_by'      => $this->form_order_by['data'],
        ];
		$ret = AdminService::AdminList($data_params);

		// 基础参数赋值
		MyViewAssign('params', $this->data_request);
		MyViewAssign('page_html', $page->GetPageHtml());
		MyViewAssign('data_list', $ret['data']);
		return MyView();
	}

	/**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
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
            MyViewAssign('data', $data);
        }
        return MyView();
    }

	/**
     * 管理员添加/编辑页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
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
		MyViewAssign('role_list', $role['data']);

		MyViewAssign('id', isset($params['id']) ? $params['id'] : 0);
		MyViewAssign('common_gender_list', lang('common_gender_list'));
		MyViewAssign('common_admin_status_list', lang('common_admin_status_list'));

		// 管理员编辑页面钩子
        $hook_name = 'plugins_view_admin_admin_save';
        MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'admin_id'      => isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'params'        => &$params,
        ]));

        // 数据
        unset($params['id']);
        MyViewAssign('data', $data);
        MyViewAssign('params', $params);
		return MyView();
	}

	/**
     * 管理员添加/编辑
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
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
     * 管理员删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
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
     * 登录页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     */
	public function LoginInfo()
	{
		// 是否已登录
		if(AdminService::LoginInfo() !== null)
		{
			return MyRedirect(MyUrl('admin/index/index'));
		}

        // 登录方式
        MyViewAssign('admin_login_type', MyC('admin_login_type', [], true));

        // 背景图片
        $host = SystemBaseService::AttachmentHost();
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
        MyViewAssign('bg_images_list', $bg_images_list);

		// 管理员登录页面钩子
        $hook_name = 'plugins_view_admin_login_info';
        MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
        ]));

		return MyView();
	}

	/**
     * 管理员登录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
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
     * 验证码显示
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     */
    public function AdminVerifyEntry()
    {
        $params = [
                'width'         => 100,
                'height'        => 28,
                'key_prefix'    => 'admin_login',
            ];
        $verify = new \base\Verify($params);
        $verify->Entry();
    }

    /**
     * 登录验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     */
    public function LoginVerifySend()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return AdminService::LoginVerifySend($this->data_post);
    }

	/**
     * 退出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     */
	public function Logout()
	{
        AdminService::LoginLogout();
		return MyRedirect(MyUrl('admin/admin/logininfo'));
	}
}
?>