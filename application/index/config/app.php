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

/**
 * 模块配置信息
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 默认输出类型
    'default_return_type'       => 'html',

    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'       => 'json',

    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'     => 'jsonpReturn',

    // 伪静态后缀
    'url_html_suffix'           => MyC('home_seo_url_html_suffix', 'html', true),
];
?>