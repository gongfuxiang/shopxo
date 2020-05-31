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
namespace app\form;

use think\Db;
use app\service\GoodsService;
use app\service\RegionService;
use app\service\BrandService;

/**
 * 商品表单
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class GoodsForm
{
    /**
     * 表单
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Table($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_shelves',
                'is_search'     => 1,
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '商品ID',
                    'view_type'     => 'field',
                    'view_key'      => 'id',
                ],
                [
                    'label'         => '商品信息',
                    'view_type'     => 'module',
                    'view_key'      => 'goods/module/info',
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'title|simple_desc|seo_title|seo_keywords|seo_keywords',
                        'placeholder'       => '请输入名称/简述/SEO信息'
                    ],

                    // 'search_config' => [
                    //     // input, select, datetime, date, time, section
                    //     'form_type'         => 'select',
                    //     // 表单字段名称
                    //     'form_name'         => 'category_id',
                    //     // 提示信息
                    //     'placeholder'       => '商品分类...',
                    //     // 是否开启占位选择框
                    //     'is_seat_select'    => 1,
                    //     // 选择占位值（默认空）
                    //     'seat_select_value' => '',
                    //     // 选择占位文本（默认 placeholder 值）
                    //     'seat_select_text'  => '商品分类...',
                    //     // 条件数据
                    //     'data'              => [],
                    //     // 数据 key 字段名称（默认取 id）
                    //     'data_key'          => 'id',
                    //     // 数据 name 字段名称（默认取 name）
                    //     'data_name'         => 'name',
                    //     // 数据默认选中值
                    //     'default'           => '',
                    // ],
                ],
                [
                    'label'         => '销售价格(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'min_price',
                    ],
                ],
                [
                    'label'         => '原价(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'original_price',
                    'search_config' => [
                        // 表单字段名称
                        'form_name'         => 'min_original_price',
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '库存数量',
                    'view_type'     => 'field',
                    'view_key'      => ['inventory', 'inventory_unit'],
                    'view_key_join' => ' ',
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'inventory',
                    ],
                ],
                [
                    'label'         => '上下架',
                    'view_type'     => 'status',
                    'view_key'      => 'is_shelves',
                    'key_field'     => 'id',
                    'post_url'      => MyUrl('admin/goods/statusshelves'),
                    'is_form_su'    => 1,
                    'align'         => 'center',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'is_shelves',
                        'data'              => lang('common_is_shelves_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '首页推荐',
                    'view_type'     => 'status',
                    'view_key'      => 'is_home_recommended',
                    'key_field'     => 'id',
                    'post_url'      => MyUrl('admin/goods/statushomerecommended'),
                    'align'         => 'center',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'is_home_recommended',
                        'data'              => lang('common_is_text_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '商品型号',
                    'view_type'     => 'field',
                    'view_key'      => 'model',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'model',
                    ],
                ],
                [
                    'label'         => '商品分类',
                    'view_type'     => 'field',
                    'view_key'      => 'category_text',
                    'search_config' => [
                        'form_type'         => 'module',
                        'form_name'         => 'lib/module/goods_category',
                        'data'              => GoodsService::GoodsCategoryAll(),
                    ],
                ],
                [
                    'label'         => '品牌',
                    'view_type'     => 'field',
                    'view_key'      => 'brand_name',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'brand_id',
                        'data'              => BrandService::CategoryBrand(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_seat_select'    => 1,
                    ],
                ],
                [
                    'label'         => '创建时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'add_time',
                    ],
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'goods/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }

    /**
     * 条件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Search($params = [])
    {
        return [
            // 基础配置
            'base'      => [
                'url'       => MyUrl('admin/goods/index'),
                'method'    => 'POST',
                'is_more'   => 1,
            ],

            // 大搜索框
            'search'    => [
                'placeholder'       => '标题/型号',
                'submit_text'       => '搜索一下',
                'loading_text'      => '搜索中哦...',
                'form_name'         => 'keywords',


            ],

            // 更多条件
            'more'  => [
                [
                    // 标题名称
                    'label'             => '分类',
                    // 表单字段名称
                    'form_name'         => 'category_id',
                    // select, input
                    'form_type'         => 'select',
                    // 提示信息
                    'placeholder'       => '商品分类...',
                    // 是否开启占位选择框
                    'is_seat_select'    => 1,
                    // 选择占位值
                    'seat_select_value' => '',
                    // 数据
                    'data'              => [],
                    // 数据 key 字段名称
                    'data_key'          => 'id',
                    // 数据 name 字段名称
                    'data_name'         => 'name',
                    // 数据默认选中值
                    'default'           => '',
                ],
            ],
        ];
    }
}