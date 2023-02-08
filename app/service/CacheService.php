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
namespace app\service;

/**
 * 缓存服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CacheService
{
    /**
     * 后台缓存管理-缓存类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AdminCacheTypeList($params = [])
    {
        $lang = MyLang('admin_cache_type_list');
        return [
            [
                'is_enable'  => 1,
                'name'       => $lang['site']['name'],
                'desc'       => $lang['site']['desc'],
                'url'        => MyUrl('admin/cache/statusupdate'),
            ],
            [
                'is_enable'  => 1,
                'name'       => $lang['template']['name'],
                'desc'       => $lang['template']['desc'],
                'url'        => MyUrl('admin/cache/templateupdate'),
            ],
            [
                'is_enable'  => 0,
                'name'       => $lang['module']['name'],
                'desc'       => $lang['module']['desc'],
                'url'        => MyUrl('admin/cache/moduleupdate'),
            ],
            [
                'is_enable'  => 1,
                'name'       => $lang['log']['name'],
                'desc'       => $lang['log']['desc'],
                'url'        => MyUrl('admin/cache/logdelete'),
            ],
        ];
    }
}
?>