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
use app\service\UserService;
use app\service\NavigationService;

/**
 * 个人资料
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class Personal extends Center
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
	 * 资料页
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{
		// 模板数据
		$assign = [
			// 用户展示数据
			'personal_show_list' 	=> NavigationService::UsersPersonalShowFieldList(),
	        // 浏览器名称
	        'home_seo_site_title'	=> SeoService::BrowserSeoTitle(MyLang('personal.base_nav_title'), 1),
		];
		MyViewAssign($assign);
		return MyView();
	}

	/**
	 * 编辑页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-26T14:26:01+0800
	 */
	public function SaveInfo()
	{
		// 模板数据
		$assign = [
			// 用户数据
			'data' 					=> UserService::UserHandle(UserService::UserInfo('id', $this->user['id'])),
			// 性别
			'common_gender_list' 	=> MyConst('common_gender_list'),
	        // 浏览器名称
	        'home_seo_site_title'	=> SeoService::BrowserSeoTitle(MyLang('personal.edit_base_nav_title'), 1),
		];
		MyViewAssign($assign);
		return MyView();
	}

	/**
	 * 保存
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-26T14:26:34+0800
	 */
	public function Save()
	{
		$params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(UserService::PersonalSave($params));
	}

	/**
     * 用户头像上传
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     */
    public function UserAvatarUpload()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        $params['img_field'] = 'file';
        return ApiService::ApiDataReturn(UserService::UserAvatarUpload($params));
    }
}
?>