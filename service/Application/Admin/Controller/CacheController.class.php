<?php

namespace Admin\Controller;

/**
 * 缓存管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CacheController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-26T19:13:29+0800
	 */
	public function Index()
	{
		$this->assign('cache_type_list', L('cache_type_list'));
		$this->display('Index');
	}

	/**
	 * [SiteUpdate 站点缓存更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-26T19:53:14+0800
	 */
	public function SiteUpdate()
	{
		// 后台
		S(C('cache_common_my_config_key'), null);
		S(C('cache_common_timezone_key'), null);
		S(C('cache_common_home_nav_header_key'), null);
		S(C('cache_common_home_nav_footer_key'), null);
		S(C('cache_common_default_theme_key'), null);
		PowerCacheDelete();

		// 前台
		S(C('cache_home_channel_key'), null);

		$this->success(L('common_operation_update_success'));
	}

	/**
	 * [TemplateUpdate 模板缓存更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-26T19:53:14+0800
	 */
	public function TemplateUpdate()
	{
		// 模板 Cache
		DelDirFile(CACHE_PATH);

		$this->success(L('common_operation_update_success'));
	}

	/**
	 * [ModuleUpdate 模块缓存更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-26T19:53:14+0800
	 */
	public function ModuleUpdate()
	{
		$this->success(L('common_operation_update_success'));
	}
}
?>