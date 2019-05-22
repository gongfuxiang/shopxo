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
        // 缓存类型列表
        return [
            [
                'is_enable' => 1,
                'name' => '站点缓存',
                'url' => MyUrl('admin/cache/statusupdate'),
                'desc' => '数据转换后或前台不能正常访问时，可以使用此功能更新所有缓存'
            ],
            [
                'is_enable' => 1,
                'name' => '模板缓存',
                'url' => MyUrl('admin/cache/templateupdate'),
                'desc' => '当页面显示不正常，可尝试使用此功能修复'
            ],
            [
                'is_enable' => 0,
                'name' => '模块缓存',
                'url' => MyUrl('admin/cache/moduleupdate'),
                'desc' => '更新页面布局与模块后未生效，可尝试使用此功能修复'
            ],
            [
                'is_enable' => 1,
                'name' => '日志清除',
                'url' => MyUrl('admin/cache/logdelete'),
                'desc' => '清除站点日志'
            ],
        ];
    }
}
?>