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
 * seo服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SeoService
{
    /**
     * 获取浏览器seo标题
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-11
     * @desc    description
     * @param   [string]     $title [标题]
     * @param   [int]        $type  [模式0 使用站点名称, 模式1 使用SEO名称, 模式2 标题, ]
     * @return  [string]            [浏览器seo标题]
     */
    public static function BrowserSeoTitle($title, $type = 0)
    {
        // 标题为空则取seo标题
        if(empty($title))
        {
            return MyC('home_seo_site_title');
        }

        // 模式
        switch($type)
        {
            // 模式1 或 默认使用标题加seo名称
            case 1 :
                return $title.' - '.MyC('home_seo_site_title');
                break;

            // 模式2 或 默认使用标题加seo名称
            case 2 :
                return $title;
                break;

            // 模式0 使用站点名称
            // 默认标题
            case 0 :
            default :
                return $title.' - '.MyC('home_site_name');
        }
    }
}
?>