<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\form;

/**
 * 用户动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-08
 * @desc    description
 */
class User
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'search_url'    => MyUrl('admin/user/index'),
                'is_delete'     => 1,
                'delete_url'    => MyUrl('admin/user/delete'),
                'delete_key'    => 'ids',
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
                    'label'         => '用户ID',
                    'view_type'     => 'field',
                    'view_key'      => 'id',
                    'width'         => 105,
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'id',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => '头像',
                    'view_type'     => 'module',
                    'view_key'      => 'user/module/avatar',
                ],
                [
                    'label'         => '用户名',
                    'view_type'     => 'field',
                    'view_key'      => 'username',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '昵称',
                    'view_type'     => 'field',
                    'view_key'      => 'nickname',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '手机',
                    'view_type'     => 'field',
                    'view_key'      => 'mobile',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '邮箱',
                    'view_type'     => 'field',
                    'view_key'      => 'email',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '性别',
                    'view_type'     => 'field',
                    'view_key'      => 'gender_text',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'gender',
                        'where_type'        => 'in',
                        'data'              => lang('common_gender_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '状态',
                    'view_type'     => 'field',
                    'view_key'      => 'status_text',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'status',
                        'where_type'        => 'in',
                        'data'              => lang('common_user_status_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '所在省',
                    'view_type'     => 'field',
                    'view_key'      => 'province',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '所在市',
                    'view_type'     => 'field',
                    'view_key'      => 'city',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '详细地址',
                    'view_type'     => 'field',
                    'view_key'      => 'address',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '生日',
                    'view_type'     => 'field',
                    'view_key'      => 'birthday_text',
                    'search_config' => [
                        'form_type'         => 'date',
                        'form_name'         => 'birthday',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '可用积分',
                    'view_type'     => 'field',
                    'view_key'      => 'integral',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '锁定积分',
                    'view_type'     => 'field',
                    'view_key'      => 'locking_integral',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '注册时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '更新时间',
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'user/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }
}
?>