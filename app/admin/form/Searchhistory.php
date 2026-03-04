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

/**
 * 搜索记录动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-26
 * @desc    description
 */
class SearchHistory
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-26
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('searchhistory.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'is_delete'     => AdminIsPower('searchhistory', 'delete') ? 1 : 0,
                'is_alldelete'  => AdminIsPower('searchhistory', 'alldelete') ? 1 : 0,
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
                    'label'         => $lang['user'],
                    'view_type'     => 'module',
                    'view_key'      => 'lib/module/user',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'user_id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'SystemModuleUserWhereHandle',
                        'placeholder'           => $lang['user_placeholder'],
                    ],
                ],
                [
                    'label'         => $lang['keywords'],
                    'view_type'     => 'field',
                    'view_key'      => 'keywords',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['brand_text'],
                    'view_type'     => 'field',
                    'view_key'      => 'brand_text',
                ],
                [
                    'label'         => $lang['category_text'],
                    'view_type'     => 'field',
                    'view_key'      => 'category_text',
                ],
                [
                    'label'         => $lang['screening_price_values'],
                    'view_type'     => 'field',
                    'view_key'      => 'screening_price_values',
                ],
                [
                    'label'         => $lang['produce_region_text'],
                    'view_type'     => 'field',
                    'view_key'      => 'produce_region_text',
                ],
                [
                    'label'         => $lang['goods_params_values'],
                    'view_type'     => 'field',
                    'view_key'      => 'goods_params_values',
                ],
                [
                    'label'         => $lang['goods_spec_values'],
                    'view_type'     => 'field',
                    'view_key'      => 'goods_spec_values',
                ],
                [
                    'label'         => $lang['order_by_field'],
                    'view_type'     => 'field',
                    'view_key'      => 'order_by_field_name',
                    'is_sort'       => 1,
                    'width'         => 130,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'order_by_field',
                        'where_type'        => 'in',
                        'data'              => array_column(MyConst('common_search_order_by_list'), 'name', 'type'),
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['order_by_type'],
                    'view_type'     => 'field',
                    'view_key'      => 'order_by_type_name',
                    'is_sort'       => 1,
                    'width'         => 145,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'order_by_type',
                        'where_type'        => 'in',
                        'data'              => array_column(MyConst('common_data_order_by_rule_list'), 'name', 'value'),
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['search_result'],
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'searchhistory/module/search_result',
                    'align'         => 'left',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'search_result',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['ip'],
                    'view_type'     => 'field',
                    'view_key'      => 'ip',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
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
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'searchhistory/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => 120,
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'            => 'SearchHistory',
                'data_handle'           => 'SearchService::DataHandle',
                'is_page'               => 1,
                'is_handle_time_field'  => 1,
                'is_handle_user_field'  => 1,
                'is_fixed_name_field'   => 1,
                'fixed_name_data'       => [
                    'order_by_field'    => [
                        'data'  => array_column(MyConst('common_search_order_by_list'), 'name', 'type'),
                    ],
                    'order_by_type'    => [
                        'data'  => array_column(MyConst('common_data_order_by_rule_list'), 'name', 'value'),
                    ],
                ],
            ],
        ];
    }
}
?>