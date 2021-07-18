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

use app\service\SeoService;
use app\service\UserService;
use app\service\NavigationService;

/**
 * 个人资料
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class Personal extends Common
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
        // 用户展示数据
		MyViewAssign('personal_show_list', NavigationService::UsersPersonalShowFieldList());

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('个人资料', 1));

		return MyView();
	}

	/**
	 * [SaveInfo 编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-26T14:26:01+0800
	 */
	public function SaveInfo()
	{
		// 性别
		MyViewAssign('common_gender_list', lang('common_gender_list'));

		// 数据
		MyViewAssign('data', $this->user);

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('个人资料编辑', 1));

		return MyView();
	}

	/**
	 * [Save 数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-26T14:26:34+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
        $params['user'] = $this->user;
        return UserService::PersonalSave($params);
	}
}
?>