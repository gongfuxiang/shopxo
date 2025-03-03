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

use app\service\ApiService;
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

		// 需要校验权限
		if(!in_array($this->action_name, ['logininfo', 'login', 'logout', 'adminverifyentry', 'loginverifysend', 'saveinfo', 'save']))
		{
			// 登录校验
            $this->IsLogin();

            // 权限校验
            $this->IsPower();
		}

        // 动态表格初始化
        if(in_array($this->action_name, ['index', 'detail', 'saveinfo']))
        {
            $this->FormTableInit();
        }
	}

	/**
     * 列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     */
	public function Index()
	{
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

		// 数据
		$data = $this->data_detail;
		if(!empty($params['id']))
		{
			if(empty($data))
			{
				return ViewError(MyLang('admin.admin_no_data_tips'), MyUrl('admin/index/index'));
			}
		}

		// 模板数据
		$assign = [
            'id'                        => isset($params['id']) ? $params['id'] : 0,
            'common_gender_list'        => MyConst('common_gender_list'),
            'common_admin_status_list'  => MyConst('common_admin_status_list'),
            'is_setup'                  => (isset($params['is_setup']) && $params['is_setup'] == 1) ? 1 : 0,
		];

		// 角色
		$role_params = [
			'where'		=> [
				['is_enable', '=', 1],
			],
			'field'		=> 'id,name',
		];
		$role = AdminService::RoleList($role_params);
		$assign['role_list'] = $role['data'];

		// 管理员编辑页面钩子
        $hook_name = 'plugins_view_admin_admin_save';
        $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'admin_id'      => isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'params'        => &$params,
        ]);

        // 数据
        unset($params['id']);
        $assign['data'] = $data;
        $assign['params'] = $params;

        // 数据赋值
        MyViewAssign($assign);
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

		// 开始操作
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(AdminService::AdminSave($params));
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
		$params = $this->data_request;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(AdminService::AdminDelete($params));
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
		if(!empty($this->admin))
		{
			return MyRedirect(MyUrl('admin/index/index'));
		}

		// 模板数据
		$assign = [
			// 登录方式
			'admin_login_type'	=> MyC('admin_login_type', [], true),
		];

        // 背景图片
        $bg_images_list = [];
        $host = SystemBaseService::AttachmentHost();
        for($i=1; $i<=50; $i++)
        {
            $path = 'static/admin/default/images/login/'.$i.'.png';
            if(file_exists(ROOT_PATH.$path))
            {
                $bg_images_list[] = $host.DS.$path;
            }
        }
        $assign['bg_images_list'] = $bg_images_list;

        // logo
        $assign['admin_login_logo'] = MyC('admin_login_logo');
        // 广告图片
        $assign['admin_login_ad_images'] = MyC('admin_login_ad_images');

        // 管理员登录页面钩子
        $hook_name = 'plugins_view_admin_login_info';
        $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
        ]);

        // 数据赋值
        MyViewAssign($assign);
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
		$params = $this->data_request;
		return ApiService::ApiDataReturn(AdminService::Login($params));
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
                'expire_time'   => MyC('common_verify_expire_time'),
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
        return ApiService::ApiDataReturn(AdminService::LoginVerifySend($this->data_request));
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