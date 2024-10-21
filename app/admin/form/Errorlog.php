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
 * 错误日志动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-26
 * @desc    description
 */
class ErrorLog
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
        $lang = MyLang('errorlog.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
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
                    'label'         => $lang['message'],
                    'view_type'     => 'field',
                    'view_key'      => 'message',
                    'text_truncate' => 2,
                    'is_popover'    => 1,
                    'is_sort'       => 1,
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['file'],
                    'view_type'     => 'field',
                    'view_key'      => 'file',
                    'text_truncate' => 2,
                    'is_popover'    => 1,
                    'is_sort'       => 1,
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['line'],
                    'view_type'     => 'field',
                    'view_key'      => 'line',
                    'is_sort'       => 1,
                    'width'         => 150,
                    'search_config' => [
                        'form_type'         => 'input',
                    ],
                ],
                [
                    'label'         => $lang['code'],
                    'view_type'     => 'field',
                    'view_key'      => 'code',
                    'is_sort'       => 1,
                    'width'         => 130,
                    'search_config' => [
                        'form_type'         => 'input',
                    ],
                ],
                [
                    'label'         => $lang['ip'],
                    'view_type'     => 'field',
                    'view_key'      => 'ip',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                    ],
                ],
                [
                    'label'         => $lang['uri'],
                    'view_type'     => 'field',
                    'view_key'      => 'uri',
                    'text_truncate' => 2,
                    'is_popover'    => 1,
                    'is_sort'       => 1,
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['request_params'],
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'errorlog/module/request_params',
                    'align'         => 'left',
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'request_params',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['memory_use'],
                    'view_type'     => 'field',
                    'view_key'      => 'memory_use',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['tsc'],
                    'view_type'     => 'field',
                    'view_key'      => 'tsc',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
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
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'errorlog/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => 120,
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'            => 'ErrorLog',
                'is_page'               => 1,
                'is_handle_time_field'  => 1,
            ],
        ];
    }
}
?>