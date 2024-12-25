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
use app\service\GoodsCategoryService;
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
        $lang = MyLang('goods.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_shelves',
                'is_search'     => 1,
                'is_delete'     => 1,
                'is_middle'     => 0,
            ],
            // 表单配置
            'form' => [
                [
                    'view_type'         => 'checkbox',
                    'is_checked'        => 0,
                    'checked_text'      => MyLang('reverse_select_title'),
                    'not_checked_text'  => MyLang('select_all_title'),
                    'align'             => 'center',
                    'width'             => 80,
                ],
                [
                    'label'         => $lang['id'],
                    'view_type'     => 'field',
                    'view_key'      => 'id',
                    'width'         => 110,
                    'is_copy'       => 1,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'             => $lang['info'],
                    'view_type'         => 'module',
                    'view_key'          => 'goods/module/info',
                    'grid_size'         => 'lg',
                    'is_sort'           => 1,
                    'sort_field'        => 'title',
                    'params_where_name' => 'keywords',
                    'search_config'     => [
                        'form_type'           => 'input',
                        'form_name'           => 'id',
                        'where_type_custom'   => 'in',
                        'where_value_custom'  => 'SystemModuleGoodsWhereHandle',
                        'placeholder'         => $lang['info_placeholder'],
                    ],
                ],
                [
                    'label'             => $lang['category_text'],
                    'view_type'         => 'field',
                    'view_key'          => 'category_text',
                    'params_where_name' => 'category_ids',
                    'search_config'     => [
                        'form_type'             => 'module',
                        'template'              => 'lib/module/multi_level_category',
                        'form_name'             => 'id',
                        'where_type'            => 'in',
                        'where_value_custom'    => 'WhereValueGoodsCategory',
                        'data'                  => GoodsCategoryService::GoodsCategoryAll(),
                    ],
                ],
                [
                    'label'             => $lang['brand_name'],
                    'view_type'         => 'field',
                    'view_key'          => 'brand_name',
                    'params_where_name' => 'brand_ids',
                    'is_sort'           => 1,
                    'width'             => 140,
                    'search_config'     => [
                        'form_type'         => 'select',
                        'form_name'         => 'brand_id',
                        'where_type'        => 'in',
                        'data'              => BrandService::CategoryBrand(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['price'],
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
                    'label'         => $lang['original_price'],
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
                    'label'         => $lang['inventory'],
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
                    'label'              => $lang['is_shelves'],
                    'view_type'          => 'status',
                    'view_key'           => 'is_shelves',
                    'post_url'           => MyUrl('admin/goods/statusupdate'),
                    'is_form_su'         => 1,
                    'align'              => 'center',
                    'is_sort'            => 1,
                    'width'              => 130,
                    'params_where_name'  => 'is_shelves',
                    'search_config'      => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_is_shelves_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['is_deduction_inventory'],
                    'view_type'     => 'status',
                    'view_key'      => 'is_deduction_inventory',
                    'post_url'      => MyUrl('admin/goods/statusupdate'),
                    'align'         => 'center',
                    'is_sort'       => 1,
                    'width'         => 130,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_is_text_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['site_type'],
                    'view_type'     => 'field',
                    'view_key'      => 'site_type',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_site_type_list'),
                    'is_sort'       => 1,
                    'width'         => 140,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_site_type_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['model'],
                    'view_type'     => 'field',
                    'view_key'      => 'model',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['place_origin_name'],
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
                    'label'         => $lang['give_integral'],
                    'view_type'     => 'field',
                    'view_key'      => 'give_integral',
                    'view_join_last'=> '%',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['buy_min_number'],
                    'view_type'     => 'field',
                    'view_key'      => 'buy_min_number',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['buy_max_number'],
                    'view_type'     => 'field',
                    'view_key'      => 'buy_max_number',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['sort_level'],
                    'view_type'     => 'field',
                    'view_key'      => 'sort_level',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['sales_count'],
                    'view_type'     => 'field',
                    'view_key'      => 'sales_count',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['access_count'],
                    'view_type'     => 'field',
                    'view_key'      => 'access_count',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['add_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => $lang['upd_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'goods/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => IsMobile() ? 120 : '',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'    => 'Goods',
                'data_handle'   => 'GoodsService::GoodsDataHandle',
                'data_params'   => [
                    'is_content_app'    => 1,
                    'is_category'       => 1,
                ],
                'detail_where'  => [
                    ['is_delete_time', '=', 0],
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
            $cids = GoodsCategoryService::GoodsCategoryItemsIds($value, 1);

            // 获取商品 id
            $ids = Db::name('GoodsCategoryJoin')->where(['category_id'=>$cids])->column('goods_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>