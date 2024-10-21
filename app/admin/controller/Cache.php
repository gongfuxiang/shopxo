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

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\CacheService;
use app\service\AdminPowerService;

/**
 * 缓存管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Cache extends Base
{
    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-26T19:13:29+0800
     */
    public function Index()
    {
        // 缓存类型
        MyViewAssign('cache_type_list', CacheService::AdminCacheTypeList());
        return MyView();
    }

    /**
     * 站点缓存更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-26T19:53:14+0800
     */
    public function StatusUpdate()
    {
        try {
            // 模板 cache
            // 系统配置缓存 data
            \base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'cache');
            \base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'data');

            // 缓存操作清除
            \think\facade\Cache::clear();
        } catch(\Exception $e) {}

        // 初始化菜单
        AdminPowerService::PowerMenuInit($this->admin);

        return ApiService::ApiDataReturn(DataReturn(MyLang('update_success'), 0));
    }

    /**
     * 模板缓存更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-26T19:53:14+0800
     */
    public function TemplateUpdate()
    {
        try {
            // 模板 cache
            \base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'index'.DS.'temp');
            \base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'api'.DS.'temp');
        } catch(\Exception $e) {}

        return ApiService::ApiDataReturn(DataReturn(MyLang('update_success'), 0));
    }

    /**
     * 模块缓存更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-26T19:53:14+0800
     */
    public function ModuleUpdate()
    {
        return ApiService::ApiDataReturn(DataReturn(MyLang('update_success'), 0));
    }

    /**
     * 日志删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-26T19:53:14+0800
     */
    public function LogDelete()
    {
        try {
            \base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'admin'.DS.'log');
            \base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'index'.DS.'log');
            \base\FileUtil::UnlinkDir(ROOT.'runtime'.DS.'api'.DS.'log');
        } catch(\Exception $e) {}

        return ApiService::ApiDataReturn(DataReturn(MyLang('update_success'), 0));
    }
}
?>