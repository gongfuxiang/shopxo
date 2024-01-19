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

use app\service\AdminService;

/**
 * 管理员动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-11
 * @desc    description
 */
class Admin
{
    // 基础条件
    public $condition_base = [];

    // 角色列表
    public $role_list;

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
        // 角色列表
        $res = AdminService::RoleList([
            'where'     => ['is_enable'=>1],
            'field'     => 'id,name',
        ]);
        $this->role_list = empty($res['data']) ? [] : array_column($res['data'], null, 'id');
    }

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-11
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('admin.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'             => 'id',
                'is_search'             => 1,
                'is_delete'             => 1,
                'is_data_export_excel'  => 1,
            ],
            // 表单配置
            'form' => [
                [
                    'view_type'         => 'checkbox',
                    'is_checked'        => 0,
                    'checked_text'      => MyLang('reverse_select_title'),
                    'not_checked_text'  => MyLang('select_all_title'),
                    'align'             => 'center',
                    'not_show_key'      => 'id',
                    'not_show_data'     => [1],
                    'width'             => 80,
                ],
                [
                    'label'         => $lang['username'],
                    'view_type'     => 'field',
                    'view_key'      => 'username',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'              => $lang['status'],
                    'view_type'          => 'field',
                    'view_key'           => 'status_name',
                    'is_round_point'     => 1,
                    'round_point_key'    => 'status',
                    'round_point_style'  => [0=>'success', 2=>'danger'],
                    'is_sort'            => 1,
                    'search_config'      => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_admin_status_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['gender'],
                    'view_type'     => 'field',
                    'view_key'      => 'gender',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_gender_list'),
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_gender_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['mobile'],
                    'view_type'     => 'field',
                    'view_key'      => 'mobile',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['email'],
                    'view_type'     => 'field',
                    'view_key'      => 'email',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['role_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'role_id',
                    'view_data_key' => 'name',
                    'view_data'     => $this->role_list,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->role_list,
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['login_total'],
                    'view_type'     => 'field',
                    'view_key'      => 'login_total',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['login_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'login_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
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
                    'view_key'      => 'admin/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'            => 'Admin',
                'is_page'               => 1,
                'is_handle_time_field'  => 1,
                'is_fixed_name_field'   => 1,
                'fixed_name_data'       => [
                    'status'  => [
                        'data'  => MyConst('common_admin_status_list'),
                    ],
                ],
            ],
        ];
    }
}
?>