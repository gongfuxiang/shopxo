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

use app\service\ConfigService;
use app\layout\service\BaseLayout;

/**
 * 布局服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-22
 * @desc    description
 */
class LayoutService
{
    // 布局key
    public static $layout_key = [
        'home'  => 'layout_index_home_data',
    ];

    /**
     * 布局配置保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     * @param   [array]          $params    [输入参数]
     */
    public static function LayoutConfigSave($key, $params)
    {
        // key 值是否存在
        if(!array_key_exists($key, self::$layout_key))
        {
            return DataReturn('布局key值有', -1);
        }

        // 保存数据
        $config = empty($params['config']) ? '' : BaseLayout::ConfigSaveHandle($params['config']);
        $ret = ConfigService::ConfigSave([self::$layout_key[$key]=>$config]);
        if($ret['code'] == 0)
        {
            $ret['msg'] = '操作成功';
        }
        return $ret;
    }

    /**
     * 布局配置获取-管理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     */
    public static function LayoutConfigAdminData($key)
    {
        // key 值是否存在
        if(!array_key_exists($key, self::$layout_key))
        {
            return DataReturn('布局key值有', -1);
        }

        // 配置数据
        $config = BaseLayout::ConfigAdminHandle(MyC(self::$layout_key[$key]));
        return DataReturn('success', 0, $config);
    }

    /**
     * 布局配置获取-展示使用
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     */
    public static function LayoutConfigData($key)
    {
        // key 值是否存在
        if(!array_key_exists($key, self::$layout_key))
        {
            return DataReturn('布局key值有', -1);
        }

        // 配置数据
        $config = BaseLayout::ConfigHandle(MyC(self::$layout_key[$key]));
        return DataReturn('success', 0, $config);
    }
}
?>