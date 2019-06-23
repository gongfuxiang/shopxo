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

use app\service\ConfigService;

/**
 * 站点设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Site extends Common
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
		$this->IsLogin();

		// 权限校验
		$this->IsPower();
	}

	/**
     * [Index 配置列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 时区
		$this->assign('site_timezone_list', lang('site_timezone_list'));

		// 站点状态
		$this->assign('site_site_state_list', lang('site_site_state_list'));

		// 用户注册类型列表
		$this->assign('common_user_reg_state_list', lang('common_user_reg_state_list'));

		// 是否开启用户登录
		$this->assign('site_user_login_state_list', lang('site_user_login_state_list'));

		// 获取验证码-开启图片验证码
		$this->assign('site_img_verify_state_list', lang('site_img_verify_state_list'));

		// 图片验证码规则
		$this->assign('site_images_verify_rules_list', lang('site_images_verify_rules_list'));

		// 热门搜索关键字
		$this->assign('common_search_keywords_type_list', lang('common_search_keywords_type_list'));

		// 是否
		$this->assign('common_is_text_list', lang('common_is_text_list'));

		// 配置信息
		$this->assign('data', ConfigService::ConfigList());

		// 编辑器文件存放地址
        $this->assign('editor_path_type', 'common');

        // 导航/视图
        $nav_type = input('nav_type', 'base');
        $this->assign('nav_type', $nav_type);
        return $this->fetch($nav_type);
	}

	/**
	 * [Save 配置数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	public function Save()
	{
		// 导航
		$nav_type = input('nav_type', 'base');

		// 字段不存在赋空值
		$field_list = [];

		// 用户注册
		if($nav_type == 'register')
		{
			$field_list[] = 'home_user_reg_state';
			$field_list[] = 'home_site_user_register_bg_images';
		}

		// 用户登录
		if($nav_type == 'login')
		{
			$field_list[] = 'home_site_user_login_ad1_images';
			$field_list[] = 'home_site_user_login_ad2_images';
			$field_list[] = 'home_site_user_login_ad3_images';
		}

		// 密码找回
		if($nav_type == 'forgetpwd')
		{
			$field_list[] = 'home_site_user_forgetpwd_ad1_images';
			$field_list[] = 'home_site_user_forgetpwd_ad2_images';
			$field_list[] = 'home_site_user_forgetpwd_ad3_images';
		}

		// 图片验证码
		if($nav_type == 'imagesverify')
		{
			$field_list[] = 'common_images_verify_rules';
		}

		// 开始处理空值
		if(!empty($field_list))
		{
			foreach($field_list as $field)
			{
				if(!isset($_POST[$field]))
				{
					$_POST[$field] = '';
				}
			}
		}

		// 基础配置
		$ret = ConfigService::ConfigSave($_POST);

		// 清除缓存
		if($ret['code'] == 0)
		{
			switch($nav_type)
			{
				case 'login' :
					cache(config('shopxo.cache_user_login_left_key'), null);

				case 'forgetpwd' :
					cache(config('shopxo.cache_user_forgetpwd_left_key'), null);
					break;
			}
		}

		return $ret;
	}
}
?>