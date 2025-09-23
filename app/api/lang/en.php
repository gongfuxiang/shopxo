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
 * 模块语言包-英文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 文章
    'article'           => [
        'article_no_data_tips'                  => 'Article does not exist or has been deleted',
        'article_id_params_tips'                => 'Incorrect article ID',
    ],
    // 订单管理
    'order'             => [
        'form_you_have_commented_tips'          => 'You have already commented',
    ],

    // 商品动态表格
    'goods_form_table'                  => [
        'id'              => 'Product ID',
        'images'          => 'cover',
        'title'           => 'Product title',
        'category_text'   => 'CATEGORY',
        'brand_name'      => 'brand',
        'price'           => 'price',
        'original_price'  => 'original price',
        'add_time'        => 'Creation time',
        'upd_time'        => 'update time',
    ],

    // 自定义页面动态表格
    'customview_form_table'             => [
        'id'              => 'Data ID',
        'logo'            => 'logo',
        'name'            => 'name',
        'add_time'        => 'Creation time',
        'upd_time'        => 'update time',
    ],

    // 页面设计动态表格
    'design_form_table'                 => [
        'id'                => 'Data ID',
        'logo'              => 'logo',
        'name'              => 'name',
        'access_count'      => 'visits',
        'is_enable'         => 'Is it enabled',
        'is_header'         => 'Does it include the head',
        'is_footer'         => 'Does it include the tail',
        'seo_title'         => 'SEO Title',
        'seo_keywords'      => 'SEO keywords',
        'seo_desc'          => 'SEO Description',
        'add_time'          => 'Creation time',
        'upd_time'          => 'update time',
    ],

    // 文章动态表格
    'article_form_table'                => [
        'id'                     => 'Article ID',
        'cover'                  => 'cover',
        'title'                  => 'title',
        'describe'               => 'describe',
        'article_category_name'  => 'classification',
        'add_time'               => 'Creation time',
        'upd_time'               => 'update time',
    ],

    // 品牌动态表格
    'brand_form_table'                  => [
        'id'                   => 'Brand ID',
        'name'                 => 'name',
        'describe'             => 'describe',
        'logo'                 => 'LOGO',
        'brand_category_text'  => 'Brand classification',
        'add_time'             => 'Creation time',
        'upd_time'             => 'update time',
    ],

    // diy装修动态表格
    'diy_form_table'                    => [
        'id'            => 'Data ID',
        'md5_key'       => 'unique identification',
        'logo'          => 'logo',
        'name'          => 'name',
        'describe'      => 'describe',
        'add_time'      => 'Creation time',
        'upd_time'      => 'update time',
    ],

    // 附件动态表格
    'attachment_form_table'             => [
        'category_name'  => 'classification',
        'type_name'      => 'type',
        'original'       => 'original file name',
        'title'          => 'new file name',
        'size'           => 'size',
        'ext'            => 'suffix',
        'url'            => 'url address',
        'hash'           => 'hash',
        'add_time'       => 'Creation time',
    ],
];
?>