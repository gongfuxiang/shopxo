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
 * 模块语言包-中文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 文章
    'article'           => [
        'article_no_data_tips'                  => '文章不存在或已删除',
        'article_id_params_tips'                => '文章ID有误',
    ],
    // 订单管理
    'order'             => [
        'form_you_have_commented_tips'          => '你已进行过评论',
    ],

    // 商品动态表格
    'goods_form_table'                  => [
        'id'              => '商品ID',
        'images'          => '封面',
        'title'           => '商品标题',
        'category_text'   => '商品分类',
        'brand_name'      => '品牌',
        'price'           => '售价',
        'original_price'  => '原价',
        'add_time'        => '创建时间',
        'upd_time'        => '更新时间',
    ],

    // 自定义页面动态表格
    'customview_form_table'             => [
        'id'              => '数据ID',
        'logo'            => 'logo',
        'name'            => '名称',
        'add_time'        => '创建时间',
        'upd_time'        => '更新时间',
    ],

    // 页面设计动态表格
    'design_form_table'                 => [
        'id'                => '数据ID',
        'logo'              => 'logo',
        'name'              => '名称',
        'access_count'      => '访问次数',
        'is_enable'         => '是否启用',
        'is_header'         => '是否含头部',
        'is_footer'         => '是否含尾部',
        'seo_title'         => 'SEO标题',
        'seo_keywords'      => 'SEO关键字',
        'seo_desc'          => 'SEO描述',
        'add_time'          => '创建时间',
        'upd_time'          => '更新时间',
    ],

    // 文章动态表格
    'article_form_table'                => [
        'id'                     => '文章ID',
        'cover'                  => '封面',
        'title'                  => '标题',
        'describe'               => '描述',
        'article_category_name'  => '分类',
        'add_time'               => '创建时间',
        'upd_time'               => '更新时间',
    ],

    // 品牌动态表格
    'brand_form_table'                  => [
        'id'                   => '品牌ID',
        'name'                 => '名称',
        'describe'             => '描述',
        'logo'                 => 'LOGO',
        'brand_category_text'  => '品牌分类',
        'add_time'             => '创建时间',
        'upd_time'             => '更新时间',
    ],

    // diy装修动态表格
    'diy_form_table'                    => [
        'id'            => '数据ID',
        'md5_key'       => '唯一标识',
        'logo'          => 'logo',
        'name'          => '名称',
        'describe'      => '描述',
        'add_time'      => '创建时间',
        'upd_time'      => '更新时间',
    ],

    // 附件动态表格
    'attachment_form_table'             => [
        'category_name'  => '分类',
        'type_name'      => '类型',
        'original'       => '原文件名',
        'title'          => '新文件名',
        'size'           => '大小',
        'ext'            => '后缀',
        'url'            => 'url地址',
        'hash'           => 'hash',
        'add_time'       => '创建时间',
    ],
];
?>