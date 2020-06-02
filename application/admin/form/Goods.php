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
namespace app\admin\form;

use think\Db;
use app\service\GoodsService;
use app\service\RegionService;
use app\service\BrandService;

/**
 * 商品动态表单
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class Goods
{
    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_shelves',
                'is_search'     => 1,
                'search_url'    => MyUrl('admin/goods/index'),
                
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
                        'where_type'        => 'like',
                        'placeholder'       => '请输入名称/简述/SEO信息'
                    ],
                ],
                [
                    'label'         => '销售价格(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'min_price',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '原价(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'original_price',
                    'search_config' => [
                        // 表单字段名称
                        'form_type'         => 'section',
                        'form_name'         => 'min_original_price',
                        'is_point'          => 1,
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
                        'where_type'        => 'in',
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
                        'where_type'        => 'in',
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
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '商品分类',
                    'view_type'     => 'field',
                    'view_key'      => 'category_text',
                    'search_config' => [
                        'form_type'         => 'module',
                        'template'          => 'lib/module/goods_category',
                        'form_name'         => 'category_id',
                        'where_type'        => 'in',
                        'data'              => GoodsService::GoodsCategoryAll(),
                    ],
                ],
                [
                    'label'         => '品牌',
                    'view_type'     => 'field',
                    'view_key'      => 'brand_name',
                    'search_config' => [
                        'form_type'         => 'module',
                        'template'          => 'lib/module/category_brand',
                        'form_name'         => 'brand_id',
                        'data'              => BrandService::CategoryBrand(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'where_type'        => 'in',
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
}