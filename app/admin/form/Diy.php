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
 * DIY装修动态表格-管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class Diy
{
    // 基础条件
    public $condition_base = [];

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
        $lang = MyLang('diy.form_table');
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
                    'label'         => $lang['md5_key'],
                    'view_type'     => 'field',
                    'view_key'      => 'md5_key',
                    'width'         => 300,
                    'is_copy'       => 1,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => $lang['logo'],
                    'view_type'     => 'images',
                    'view_key'      => 'logo',
                    'images_width'  => 30,
                    'width'         => 80,
                ],
                [
                    'label'             => $lang['name'],
                    'view_type'         => 'field',
                    'view_key'          => 'name',
                    'is_sort'           => 1,
                    'params_where_name' => 'keywords',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['describe'],
                    'view_type'     => 'field',
                    'view_key'      => 'describe',
                    'text_truncate' => 2,
                    'grid_size'     => 'lg',
                    'is_popover'    => 1,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'              => $lang['is_enable'],
                    'view_type'          => 'status',
                    'view_key'           => 'is_enable',
                    'post_url'           => MyUrl('admin/diy/statusupdate'),
                    'is_form_su'         => 1,
                    'align'              => 'center',
                    'is_sort'            => 1,
                    'width'              => 130,
                    'params_where_name'  => 'is_enable',
                    'search_config'      => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_is_text_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['access_count'],
                    'view_type'     => 'field',
                    'view_key'      => 'access_count',
                    'is_sort'       => 1,
                    'width'         => 160,
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
                    'view_key'      => 'diy/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'             => 'Diy',
                'data_handle'            => 'DiyService::DiyListHandle',
                'detail_action'          => ['detail', 'saveinfo', 'preview', 'storeuploadinfo'],
                'is_page'                => 1,
                'is_handle_annex_field'  => 1,
                'data_params'            => [
                    'is_config_handle' => 1,
                ],
                'detail_params'          => [
                    'is_config_data_handle' => 1
                ],
            ],
        ];
    }
}
?>