<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免false费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

// 站点域名地址
$domain_url = MyFileConfig('common_domain_host', '', __MY_DOMAIN__, true);
if(substr($domain_url, -1) != DS)
{
    $domain_url .= DS;
}

// cdn地址
$cdn_attachment_host = MyFileConfig('common_cdn_attachment_host', '', __MY_PUBLIC_URL__, true);
$cdn_public_host = MyFileConfig('common_cdn_public_host', '', __MY_PUBLIC_URL__, true);
if(substr($cdn_attachment_host, -1) == DS)
{
    $cdn_attachment_host = substr($cdn_attachment_host, 0, -1);
}
if(substr($cdn_public_host, -1) != DS)
{
    $cdn_public_host .= DS;
}

// 连接商店是否采用https
$store_http = (MyFileConfig('common_is_https_connect_store') == 1) ? 'https://' : 'http://';

// 配置信息
return [
    // 开发模式
    'is_develop'                            => false,

    // 默认编码
    'default_charset'                       => 'utf-8',

    // 后端菜单缓存key列表
    // 菜单权限缓存存储key
    // 插件权限缓存存储key
    'cache_admin_left_menu_key'             =>  'cache_admin_left_menu_',
    'cache_admin_power_key'                 =>  'cache_admin_power_',
    'cache_admin_power_plugins_key'         =>  'cache_admin_power_plugins_',


    // 公共缓存key列表
    // 公共系统配置信息key
    'cache_common_my_config_key'            => 'cache_common_my_config_data',

    // 前台顶部导航，后端菜单更新则删除缓存
    'cache_common_home_nav_header_key'      => 'cache_common_home_nav_header_data',

    // 前台顶部导航
    'cache_common_home_nav_footer_key'      => 'cache_common_home_nav_footer_data',

    // 商品大分类缓存
    'cache_goods_category_key'              => 'cache_goods_category_key_data',

    // 商品详情中间导航
    'cache_goods_detail_middle_tabs_key'    => 'cache_goods_detail_middle_tabs_key_data',

    // 商品评论评分
    'cache_goods_comments_score_key'        => 'cache_goods_comments_score_key_data',

    // 应用数据缓存
    'cache_plugins_data_key'                => 'cache_plugins_data_key_data_',

    // 用户登录左侧数据
    'cache_user_login_left_key'             => 'cache_user_login_left_data',

    // 用户密码找回左侧数据
    'cache_user_forgetpwd_left_key'         => 'cache_user_forgetpwd_left_data',

    // 用户缓存信息
    'cache_user_info'                       => 'cache_user_info_',

    // 首页楼层缓存信息
    'cache_goods_floor_list_key'            => 'cache_goods_floor_list_data',

    // 轮播缓存信息
    'cache_banner_list_key'                 => 'cache_banner_list_data_',

    // 手机首页导航缓存信息
    'cache_app_home_navigation_key'         => 'cache_app_home_navigation_data_',

    // 手机用户中心导航缓存信息
    'cache_app_user_center_navigation_key'  => 'cache_app_user_center_navigation_data_',

    // 快捷导航缓存信息
    'cache_quick_navigation_key'            => 'cache_quick_navigation_data_',

    // 地区所有数据缓存、1~3级
    'cache_region_all_key'                  => 'cache_region_all_data',

    // 搜索热门关键字
    'cache_search_keywords_key'             => 'cache_search_keywords_data',

    // 搜索开始排行数据缓存
    'cache_search_start_ranking_key'        => 'cache_search_start_ranking_data',

    // 首页展示的文章列表数据
    'cache_home_article_list_key'           => 'cache_home_article_list_data',

    // 首页展示的友情链接列表数据
    'cache_home_link_list_key'              => 'cache_home_link_list_data',

    // 表结构缓存
    'cache_table_structure_key'             => 'cache_table_structure_key_data',

    // 站点域名地址
    'domain_url'                            => $domain_url,

    // 附件host、最后不要带/斜杠结尾, 数据库图片地址以/static/...开头
    'attachment_host'                       => $cdn_attachment_host,

    // css/js引入host地址、以/斜杠结尾
    'public_host'                           => $cdn_public_host,


    // 应用商店基础地址
    'website_url'                           => $store_http.'shopxo.net/',
    'store_url'                             => $store_http.'store.shopxo.net/',
    'store_payment_url'                     => $store_http.'store.shopxo.net/',
    'store_design_url'                      => $store_http.'store.shopxo.net/',
    'store_diy_url'                         => $store_http.'store.shopxo.net/',
    'store_theme_url'                       => $store_http.'store.shopxo.net/',
    'store_regster_url'                     => $store_http.'store.shopxo.net/regster.html',

    // 插件在线下载地址
    'store_download_url'                    => $store_http.'store.shopxo.net/index.php?s=store-package-onine.html',

    // 远程系统更新接口
    'store_system_upgrade_url'              => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=systemsupgradeurl',

    // 远程插件更新接口
    'store_plugins_upgrade_url'             => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=pluginsupgradeurl',

    // 远程信息接口
    'store_site_info_url'                   => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=siteinfo',

    // 远程检查更新接口
    'store_inspect_upgrade_url'             => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=inspectupgrade',

    // 远程插件安全合法校验接口
    'store_plugins_legal_check_url'         => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=pluginslegalcheck',

    // 远程插件更新信息接口
    'store_plugins_upgrade_info_url'        => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=pluginsupgradeinfo',

    // 插件上传到远程商店店铺
    'store_plugins_upload_url'              => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=pluginsupload',

    // DIY上传到远程商店店铺
    'store_diy_upload_url'                  => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=diyupload',

    // 主题上传到远程商店店铺
    'store_themeadmin_upload_url'           => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=themeadminupload',

    // 远程拍数据列表
    'store_package_data_list_url'           => $store_http.'store.shopxo.net/api.php?s=plugins/index&pluginsname=store&pluginscontrol=index&pluginsaction=packagedatalist',


    // 支付业务类型,支付插件根据业务类型自动生成支付入口文件
    'payment_business_type_all'             => [
        ['name' => 'Order', 'desc' => '订单'],
    ],

    // 不删除的支付方式
    'payment_cannot_deleted_list'           => [
        'CashPayment',
        'DeliveryPayment',
        'ChargePayment',
    ],

    // 线下支付方式
    'under_line_list'                       => [
        'CashPayment',
        'DeliveryPayment',
        'ChargePayment',
    ],

    // 小程序平台
    'mini_app_type_list'                    => ['weixin', 'alipay', 'baidu', 'toutiao', 'qq'],

    // 坐标需要转换的平台
    'coordinate_transformation'             => ['alipay', 'weixin', 'toutiao', 'baidu'],

    // 货币配置信息
    // 符号（默认 ￥）
    // 代码（默认 0.0000）
    // 汇率（默认 RMB）
    // 名称（默认 人民币）
    'currency_symbol'                       => '￥',
    'currency_code'                         => 'RMB',
    'currency_rate'                         => 0.0000,
    'currency_name'                         => '人民币',

    // 验证码最大验证次数,防止暴力破解
    'security_prevent_violence_max'         => 6,

    // 动态表格可加入钩子组
    'module_form_hook_group'                => ['admin', 'index', 'api'],
];
?>