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

use app\service\ThemeAdminService;

/**
 * 主题数据动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-26
 * @desc    description
 */
class ThemeData
{
    // 基础条件
    public $condition_base = [];

    // 主题列表
    public $theme_list = [];

    /**
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-29
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        // 主题列表
        $res = ThemeAdminService::ThemeAdminList();
        $this->theme_list = empty($res) ? [] : array_column($res, 'name', 'theme');
    }

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
        $lang = MyLang('themedata.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_enable',
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
                    'label'         => $lang['unique'],
                    'view_type'     => 'field',
                    'view_key'      => 'unique',
                    'width'         => 300,
                    'is_copy'       => 1,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                    ],
                ],
                [
                    'label'         => $lang['name'],
                    'view_type'     => 'field',
                    'view_key'      => 'name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['type'],
                    'view_type'     => 'field',
                    'view_key'      => 'type_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'type',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_theme_type_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['theme'],
                    'view_type'     => 'field',
                    'view_key'      => 'theme_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'theme',
                        'where_type'        => 'in',
                        'data'              => $this->theme_list,
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['is_enable'],
                    'view_type'     => 'status',
                    'view_key'      => 'is_enable',
                    'post_url'      => MyUrl('admin/themedata/statusupdate'),
                    'is_form_su'    => 1,
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
                    'label'         => $lang['view'],
                    'view_type'     => 'field',
                    'view_key'      => 'view_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'view',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_theme_view_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
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
                    'view_key'      => 'themedata/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => 120,
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'            => 'ThemeData',
                'data_handle'           => 'ThemeDataService::ThemeDataListHandle',
                'is_page'               => 1,
                'is_handle_time_field'  => 1,
                'is_json_data_handle'   => 1,
                'json_config_data'      => [
                    'data'  => []
                ],
                'is_fixed_name_field'   => 1,
                'fixed_name_data'       => [
                    'type'  => [
                        'data'  => MyConst('common_theme_type_list'),
                    ],
                    'theme' => [
                        'data'  => $this->theme_list,
                    ],
                    'view'  => [
                        'data'  => MyConst('common_theme_view_list'),
                    ],
                ],
            ],
        ];
    }
}
?>