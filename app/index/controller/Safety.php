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
namespace app\index\controller;

use app\index\controller\Center;
use app\service\ApiService;
use app\service\SeoService;
use app\service\SafetyService;
use app\service\NavigationService;
use app\service\AgreementService;

/**
 * 安全
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class Safety extends Center
{
	/**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }

	/**
	 * 首页
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{
		// 模板数据
		$assign = [
			// 基础数据
			'data' 					=> [
				'mobile'	=>	$this->user['mobile_security'],
				'email'		=>	$this->user['email_security'],
			],
			// 安全信息列表
			'safety_panel_list'		=> NavigationService::UserSafetyPanelList(),
	        // 浏览器名称
	        'home_seo_site_title'	=> SeoService::BrowserSeoTitle(MyLang('safety.base_nav_title'), 1),
        ];
        MyViewAssign($assign);
		return MyView();
	}

	/**
	 * 登录密码修改页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function LoginPwdInfo()
	{
        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(MyLang('safety.password_update_base_nav_title'), 1));
		return MyView();
	}

	/**
	 * 原手机号码修改页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function MobileInfo()
	{
		if(empty($this->user['mobile']))
		{
			return MyRedirect(MyUrl('index/safety/newmobileinfo'));
		}

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(MyLang('safety.mobile_update_base_nav_title'), 1));
		return MyView();
	}

	/**
	 * 新手机号码修改页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function NewMobileInfo()
	{
		if(MySession('safety_sms') == null && !empty($this->user['mobile']))
		{
			return ViewError(MyLang('safety.original_account_check_error_tips'), MyUrl('index/safety/mobileinfo'));
		}

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(MyLang('safety.mobile_update_base_nav_title'), 1));
		return MyView();
	}

	/**
	 * 电子邮箱修改页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function EmailInfo()
	{
		if(empty($this->user['email']))
		{
			return MyRedirect(MyUrl('index/safety/newemailinfo'));
		}

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(MyLang('safety.email_update_base_nav_title'), 1));
		return MyView();
	}

	/**
	 * 新电子邮箱修改页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function NewEmailInfo()
	{
		if(MySession('safety_email') == null && !empty($this->user['email']))
		{
			return ViewError(MyLang('safety.original_account_check_error_tips'), MyUrl('index/safety/emailinfo'));
		}

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(MyLang('safety.email_update_base_nav_title'), 1));
		return MyView();
	}

	/**
	 * 账号注销页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function LogoutInfo()
	{
		// 协议
		$document = AgreementService::AgreementData(['document'=>'userlogout']);

		// 模板数据
		$assign = [
			// 协议内容
			'document_data'			=> $document['data'],
	        // 浏览器名称
	        'home_seo_site_title'	=> SeoService::BrowserSeoTitle(MyLang('safety.logout_base_nav_title'), 1),
        ];
        MyViewAssign($assign);
		return MyView();
	}

	/**
	 * 验证码显示
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T15:10:21+0800
	 */
	public function VerifyEntry()
	{
		$params = [
            'width' 			=> 100,
            'height' 			=> 28,
            'use_point_back'	=> false,
            'key_prefix' 		=> 'safety',
            'expire_time'       => MyC('common_verify_expire_time'),
        ];
        $verify = new \base\Verify($params);
        $verify->Entry();
	}

	/**
	 * 登录密码修改
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:38:23+0800
	 */
	public function LoginPwdUpdate()
	{
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(SafetyService::LoginPwdUpdate($params));
	}

	/**
	 * 验证码发送
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T19:17:10+0800
	 */
	public function VerifySend()
	{
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(SafetyService::VerifySend($params));
	}

	/**
	 * 原账户验证码校验
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T15:57:19+0800
	 */
	public function VerifyCheck()
	{
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(SafetyService::VerifyCheck($params));
	}

	/**
	 * 账号更新
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T17:04:36+0800
	 */
	public function AccountsUpdate()
	{
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(SafetyService::AccountsUpdate($params));
	}

	/**
	 * 账号注销
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T17:04:36+0800
	 */
	public function Logout()
	{
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(SafetyService::AccountsLogout($params));
	}
}
?>