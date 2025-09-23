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
namespace app\api\form;

use think\facade\Db;
use app\service\GoodsCategoryService;

/**
 * DiyApi商品动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class DiyApiGoodsList
{
    // 基础条件
    public $condition_base = [
        ['is_delete_time', '=', 0],
        ['is_shelves', '=', 1],
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
        $lang = MyLang('goods_form_table');
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
                    'label'         => $lang['images'],
                    'view_type'     => 'images',
                    'view_key'      => 'images',
                    'images_width'  => 30,
                    'width'         => 80,
                ],
                [
                    'label'             => $lang['title'],
                    'view_type'         => 'field',
                    'view_key'          => 'title',
                    'grid_size'         => 'lg',
                    'is_sort'           => 1,
                    'params_where_name' => 'keywords',
                    'search_config'     => [
                        'form_type'           => 'input',
                        'form_name'           => 'id',
                        'where_type_custom'   => 'in',
                        'where_value_custom'  => 'SystemModuleGoodsWhereHandle',
                    ],
                ],
                [
                    'label'             => $lang['category_text'],
                    'view_type'         => 'field',
                    'view_key'          => 'category_text',
                    'params_where_name' => 'category_ids',
                    'search_config'     => [
                        'form_type'             => 'module',
                        'form_name'             => 'id',
                        'where_type'            => 'in',
                        'where_value_custom'    => 'WhereValueGoodsCategory',
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
            ],
            // 数据配置
            'data'  => [
                'table_name'    => 'Goods',
                'data_handle'   => 'GoodsService::GoodsDataHandle',
                'data_params'   => [
                    'is_category'  => 1,
                    'is_photo'     => 0,
                ],
                'detail_where'  => $this->condition_base,
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