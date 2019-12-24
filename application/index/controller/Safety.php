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
namespace app\index\controller;

use app\service\SeoService;
use app\service\SafetyService;
use app\service\NavigationService;

/**
 * 安全
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class Safety extends Common
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

        // 是否登录
        $this->IsLogin();
    }

	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{
		// 安全信息列表
		$this->assign('safety_panel_list', NavigationService::UsersSafetyPanelList());

		// 数据列表
		$data = array(
				'mobile'	=>	$this->user['mobile_security'],
				'email'		=>	$this->user['email_security'],
			);
		$this->assign('data', $data);

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('安全设置', 1));

		return $this->fetch();
	}

	/**
	 * [LoginPwdInfo 登录密码修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function LoginPwdInfo()
	{
        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('登录密码修改 - 安全设置', 1));

		return $this->fetch();
	}

	/**
	 * [MobileInfo 原手机号码修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function MobileInfo()
	{
		if(empty($this->user['mobile']))
		{
			return redirect(MyUrl('index/safety/newmobileinfo'));
		}

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('手机号码修改 - 安全设置', 1));

		return $this->fetch();
	}

	/**
	 * [NewMobileInfo 新手机号码修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function NewMobileInfo()
	{
		if(session('safety_sms') == null && !empty($this->user['mobile']))
		{
			return $this->error('原帐号校验失败', MyUrl('index/safety/mobileinfo'));
		}

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('手机号码修改 - 安全设置', 1));

		return $this->fetch();
	}

	/**
	 * [EmailInfo 电子邮箱修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function EmailInfo()
	{
		if(empty($this->user['email']))
		{
			return redirect(MyUrl('index/safety/newemailinfo'));
		}

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('电子邮箱修改 - 安全设置', 1));

		return $this->fetch();
	}

	/**
	 * [NewEmailInfo 新电子邮箱修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function NewEmailInfo()
	{
		if(session('safety_email') == null && !empty($this->user['email']))
		{
			return $this->error('原帐号校验失败', MyUrl('index/safety/emailinfo'));
		}

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('电子邮箱修改 - 安全设置', 1));
        
		return $this->fetch();
	}

	/**
	 * [VerifyEntry 验证码显示]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T15:10:21+0800
	 */
	public function VerifyEntry()
	{
		$params = array(
                'width' => 100,
                'height' => 28,
                'use_point_back' => false,
                'key_prefix' => 'safety',
            );
        $verify = new \base\Verify($params);
        $verify->Entry();
	}

	/**
	 * [LoginPwdUpdate 登录密码修改]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:38:23+0800
	 */
	public function LoginPwdUpdate()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始处理
        $params = input('post.');
        $params['user'] = $this->user;
        return SafetyService::LoginPwdUpdate($params);
	}

	/**
	 * [VerifySend 验证码发送]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T19:17:10+0800
	 */
	public function VerifySend()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }

        // 开始处理
        $params = input('post.');
        $params['user'] = $this->user;
        return SafetyService::VerifySend($params);
	}


	/**
	 * [VerifyCheck 原账户验证码校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T15:57:19+0800
	 */
	public function VerifyCheck()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }

        // 开始处理
        $params = input('post.');
        $params['user'] = $this->user;
        return SafetyService::VerifyCheck($params);
	}

	/**
	 * [AccountsUpdate 账户更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T17:04:36+0800
	 */
	public function AccountsUpdate()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }

        // 开始处理
        $params = input('post.');
        $params['user'] = $this->user;
        return SafetyService::AccountsUpdate($params);
	}
}
?>