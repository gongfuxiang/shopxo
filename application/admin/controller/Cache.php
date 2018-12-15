<?php

namespace app\admin\controller;

/**
 * 缓存管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Cache extends Common
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
		$this->assign('cache_type_list', lang('cache_type_list'));
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
		\base\FileUtil::UnlinkDir(TEMP_PATH);
		\base\FileUtil::UnlinkDir(DATA_PATH);
		\base\FileUtil::UnlinkFile(RUNTIME_PATH.'common~runtime.php');
		$this->success(lang('common_operation_update_success'));
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
		\base\FileUtil::UnlinkDir(CACHE_PATH);

		$this->success(lang('common_operation_update_success'));
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
		$this->success(lang('common_operation_update_success'));
	}
}
?>