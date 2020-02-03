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
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-16T00:33:28+0800
     * @param    [array]          $params [输入参数]
     */
    public static function StoreUrl($params = [])
    {
        return config('shopxo.store_url').'?ver='.APPLICATION_VERSION.'&url='.urlencode(__MY_URL__);
    }

    /**
     * 应用商店支付插件地址
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-16T00:33:28+0800
     * @param    [array]          $params [输入参数]
     */
    public static function StorePaymentUrl($params = [])
    {
        return config('shopxo.store_payment_url').'?ver='.APPLICATION_VERSION.'&url='.urlencode(__MY_URL__);
    }

    /**
     * 应用商店主题地址
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-16T00:33:28+0800
     * @param    [array]          $params [输入参数]
     */
    public static function StoreThemeUrl($params = [])
    {
        return config('shopxo.store_theme_url').'?ver='.APPLICATION_VERSION.'&url='.urlencode(__MY_URL__);
    }
}
?>