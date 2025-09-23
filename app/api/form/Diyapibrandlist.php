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

/**
 * DiyApi品牌动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-19
 * @desc    description
 */
class DiyApiBrandList
{
    // 基础条件
    public $condition_base = [
        ['is_enable', '=', 1],
    ];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('brand_form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_enable',
                'is_search'     => 1,
                'is_middle'     => 0,
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
                    'label'              => $lang['name'],
                    'view_type'          => 'field',
                    'view_key'           => 'name',
                    'is_sort'            => 1,
                    'width'              => 150,
                    'params_where_name'  => 'keywords',
                    'search_config'      => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['describe'],
                    'view_type'     => 'field',
                    'view_key'      => 'describe',
                    'grid_size'     => 'lg',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['logo'],
                    'view_type'     => 'images',
                    'view_key'      => 'logo',
                    'images_height' => 25,
                    'width'         => 100,
                ],
                [
                    'label'              => $lang['brand_category_text'],
                    'view_type'          => 'field',
                    'view_key'           => 'brand_category_text',
                    'width'              => 140,
                    'params_where_name'  => 'category_ids',
                    'search_config'      => [
                        'form_type'             => 'select',
                        'form_name'             => 'id',
                        'where_type'            => 'in',
                        'where_value_custom'    => 'WhereValueBrandCategory',
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
                'table_name'    => 'Brand',
                'data_handle'   => 'BrandService::BrandListHandle',
            ],
        ];
    }

    /**
     * 品牌分类条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-03
     * @desc    description
     * @param   [array]           $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueBrandCategory($value, $params = [])
    {
        if(!empty($value))
        {
            // 是否为数组
            if(!is_array($value))
            {
                $value = [$value];
            }

            // 获取品牌 id
            $ids = Db::name('BrandCategoryJoin')->where(['brand_category_id'=>$value])->column('brand_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>