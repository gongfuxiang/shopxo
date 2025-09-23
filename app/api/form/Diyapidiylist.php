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

/**
 * DiyApiDIY装修动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class DiyApiDiyList
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
     * @date    2020-05-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('diy_form_table');
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