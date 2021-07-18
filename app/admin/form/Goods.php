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
namespace app\admin\form;

use think\facade\Db;
use app\service\GoodsService;
use app\service\RegionService;
use app\service\BrandService;

/**
 * 商品动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class Goods
{
    // 基础条件
    public $condition_base = [
        ['is_delete_time', '=', 0],
    ];

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
                'is_delete'     => 1,
                'delete_url'    => MyUrl('admin/goods/delete'),
                'delete_key'    => 'ids',
                'detail_title'  => '基础信息',
            ],
            // 表单配置
            'form' => [
                [
                    'view_type'         => 'checkbox',
                    'is_checked'        => 0,
                    'checked_text'      => '反选',
                    'not_checked_text'  => '全选',
                    'align'             => 'center',
                    'width'             => 80,
                ],
                [
                    'label'         => '商品ID',
                    'view_type'     => 'field',
                    'view_key'      => 'id',
                    'width'         => 105,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => '商品信息',
                    'view_type'     => 'module',
                    'view_key'      => 'goods/module/info',
                    'grid_size'     => 'lg',
                    'is_sort'       => 1,
                    'sort_field'    => 'title',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'title|simple_desc|seo_title|seo_keywords|seo_keywords',
                        'where_type'        => 'like',
                        'placeholder'       => '请输入商品名称/简述/SEO信息'
                    ],
                ],
                [
                    'label'         => '商品分类',
                    'view_type'     => 'field',
                    'view_key'      => 'category_text',
                    'search_config' => [
                        'form_type'             => 'module',
                        'template'              => 'lib/module/goods_category',
                        'form_name'             => 'id',
                        'where_type'            => 'in',
                        'where_value_custom'    => 'WhereValueGoodsCategory',
                        'data'                  => GoodsService::GoodsCategoryAll(),
                    ],
                ],
                [
                    'label'         => '品牌',
                    'view_type'     => 'field',
                    'view_key'      => 'brand_name',
                    'is_sort'       => 1,
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
                    'label'         => '销售价格(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'is_sort'       => 1,
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
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'min_original_price',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '库存总量',
                    'view_type'     => 'field',
                    'view_key'      => ['inventory', 'inventory_unit'],
                    'view_key_join' => ' ',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                        'form_name'         => 'inventory',
                    ],
                ],
                [
                    'label'         => '上下架',
                    'view_type'     => 'status',
                    'view_key'      => 'is_shelves',
                    'post_url'      => MyUrl('admin/goods/statusupdate'),
                    'is_form_su'    => 1,
                    'align'         => 'center',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_is_shelves_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '扣减库存',
                    'view_type'     => 'status',
                    'view_key'      => 'is_deduction_inventory',
                    'post_url'      => MyUrl('admin/goods/statusupdate'),
                    'align'         => 'center',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_is_text_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '商品类型',
                    'view_type'     => 'field',
                    'view_key'      => 'site_type',
                    'view_data_key' => 'name',
                    'view_data'     => lang('common_site_type_list'),
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_site_type_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '商品型号',
                    'view_type'     => 'field',
                    'view_key'      => 'model',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '生产地',
                    'view_type'     => 'field',
                    'view_key'      => 'place_origin_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'place_origin',
                        'data'              => RegionService::RegionItems(['pid'=>0]),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'where_type'        => 'in',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '购买赠送积分比例',
                    'view_type'     => 'field',
                    'view_key'      => 'give_integral',
                    'view_join_last'=> '%',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '单次最低起购数量',
                    'view_type'     => 'field',
                    'view_key'      => 'buy_min_number',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '单次最大购买数量',
                    'view_type'     => 'field',
                    'view_key'      => 'buy_max_number',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '销量',
                    'view_type'     => 'field',
                    'view_key'      => 'sales_count',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '访问次数',
                    'view_type'     => 'field',
                    'view_key'      => 'access_count',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '创建时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '更新时间',
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
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
     * 商品分类条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-03
     * @desc    description
     * @param   [array]           $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueGoodsCategory($value, $params = [])
    {
        if(!empty($value))
        {
            // 是否为数组
            if(!is_array($value))
            {
                $value = [$value];
            }

            // 获取分类下的所有分类 id
            $cids = GoodsService::GoodsCategoryItemsIds($value, 1);

            // 获取商品 id
            $ids = Db::name('GoodsCategoryJoin')->where(['category_id'=>$cids])->column('goods_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>