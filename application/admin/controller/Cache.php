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

use app\service\CacheService;

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
		$this->IsLogin();

		// 权限校验
		$this->IsPower();
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
		// 缓存类型
		$this->assign('cache_type_list', CacheService::AdminCacheTypeList());

		return $this->fetch();
	}

	/**
	 * [StatusUpdate 站点缓存更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-26T19:53:14+0800
	 */
	public function StatusUpdate()
	{
		// 模板 cache
		// 数据 temp
		\base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'cache');
		\base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'temp');
		\base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'data');
		return $this->success('更新成功');
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
		// 模板 cache
		\base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'cache');

		return $this->success('更新成功');
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
		return $this->success('更新成功');
	}

	/**
	 * [LogDelete 日志删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-26T19:53:14+0800
	 */
	public function LogDelete()
	{
		\base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'log');

		return $this->success('更新成功');
	}
}
?>