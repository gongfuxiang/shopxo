<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\service\ThemeService;
use app\service\ConfigService;

/**
 * 主题管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Theme extends Common
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
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();

		// 小导航
		$this->view_type = input('view_type', 'home');
	}

	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 导航参数
		$this->assign('view_type', $this->view_type);

		if($this->view_type == 'home')
		{
			// 模板列表
			$this->assign('data_list', ThemeService::ThemeList());

			// 默认主题
			$theme = MyC('common_default_theme', 'default', true);
			$this->assign('theme', empty($theme) ? 'default' : $theme);
			return $this->fetch('index');
		} else {
			return $this->fetch('upload');
		}
	}

	/**
	 * 模板切换保存
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-12-19T00:58:47+0800
	 */
	public function Save()
	{
		return ConfigService::ConfigSave(input());
	}

	/**
	 * [Delete 删除]
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

		// 开始处理
		$params = input();
		return ThemeService::ThemeDelete($params);
	}

	/**
	 * [Upload 模板上传安装]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-05-10T16:27:09+0800
	 */
	public function Upload()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始处理
		$params = input();
		return ThemeService::ThemeUpload($params);
	}
}
?>