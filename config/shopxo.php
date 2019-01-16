<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 默认编码
    'default_charset'                       => 'utf-8',

    // 缓存key列表
    // 公共系统配置信息key
    'cache_common_my_config_key'            =>  'cache_common_my_config_data',

    // 前台顶部导航，后端菜单更新则删除缓存
    'cache_common_home_nav_header_key'      =>  'cache_common_home_nav_header_data',

    // 前台顶部导航
    'cache_common_home_nav_footer_key'      =>  'cache_common_home_nav_footer_data',

    // 附件host, 数据库图片地址以/static/...开头
    'attachment_host'                       =>  defined('__MY_PUBLIC_URL__') ? substr(__MY_PUBLIC_URL__, 0, -1) : '',

    // 开启U带域名
    'url_domain_deploy'                     =>  true,

    // 支付业务类型,支付插件根据业务类型自动生成支付入口文件
    'payment_business_type_all'             => [
        ['name' => 'Order', 'desc' => '订单'],
    ],

    // 不删除的支付方式
    'payment_cannot_deleted_list'           => [
        'DeliveryPayment',
        'CashPayment',
    ],

    // 线下支付方式
    'under_line_list'                       => ['CashPayment', 'DeliveryPayment'],
];
?>