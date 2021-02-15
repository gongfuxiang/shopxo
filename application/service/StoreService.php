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
 * 应用商店服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2019-06-16T00:33:28+0800
 */
class StoreService
{
    /**
     * 应用商店地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StoreUrl($params = [])
    {
        return config('shopxo.store_url').self::RequestParamsString($params);
    }

    /**
     * 应用商店支付插件地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StorePaymentUrl($params = [])
    {
        return config('shopxo.store_payment_url').self::RequestParamsString($params);
    }
    
    /**
     * 应用商店主题地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StoreThemeUrl($params = [])
    {
        return config('shopxo.store_theme_url').self::RequestParamsString($params);
    }

    /**
     * 请求参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function RequestParamsString($params = [])
    {
        return '?ver='.APPLICATION_VERSION.'&url='.urlencode(__MY_URL__).'&host='.urlencode(__MY_HOST__).'&ip='.urlencode(__MY_ADDR__);
    }
}
?>