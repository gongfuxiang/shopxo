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

/**
 * 模块语言包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 首页
    'page_index'            => [
        'order_transaction_amount_name'     => '订单成交金额走势',
        'order_trading_trend_name'          => '订单交易走势',
        'goods_hot_name'                    => '热销商品',
        'goods_hot_tips'                    => '仅显示前30条商品',
        'payment_name'                      => '支付方式',
        'order_region_name'                 => '订单地域分布',
        'order_region_tips'                 => '仅显示30条数据',
        'upgrade_check_loading_tips'        => '正在获取最新内容、请稍候...',
        'upgrade_version_name'              => '更新版本：',
        'upgrade_date_name'                 => '更新日期：',
    ],

    // 订单管理
    'page_order'            => [
        'order_id_empty'                    => '订单id有误',
        'express_choice_tips'               => '请选择快递方式',
        'payment_choice_tips'               => '请选择支付方式',
    ],

    // 插件管理
    'page_pluginsadmin'     => [
        'not_enable_tips'                   => '请先点击勾勾启用',
        'save_no_data_tips'                 => '没有可保存的插件数据',
    ],

    // 站点设置
    'page_site'             => [
        'remove_confirm_tips'               => '移除后保存生效、确认继续吗？',
        'address_no_data'                   => '地址数据为空',
        'address_not_exist'                 => '地址不存在',
        'address_logo_message'              => '请上传logo图片',
    ],

    // 仓库商品
    'page_warehousegoods'   => [
        'warehouse_choice_tips'             => '请选择仓库',
    ],
];
?>