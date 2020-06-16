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
 * 导航动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-15
 * @desc    description
 */
class Navigation
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-15
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        // 导航类型
        $nav_type = empty($params['nav_type']) ? 'header' : trim($params['nav_type']);

        // 配置信息
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_show',
                'is_search'     => 1,
                'search_url'    => MyUrl('admin/navigation/index', ['nav_type'=>$nav_type]),
                'is_delete'     => 1,
                'delete_url'    => MyUrl('admin/navigation/delete'),
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
                    'label'         => '导航名称',
                    'view_type'     => 'module',
                    'view_key'      => 'navigation/module/info',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'name',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '导航数据类型',
                    'view_type'     => 'field',
                    'view_key'      => 'data_type_text',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'data_type',
                        'where_type'        => 'in',
                        'data'              => lang('common_nav_type_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '状态',
                    'view_type'     => 'status',
                    'view_key'      => 'is_show',
                    'post_url'      => MyUrl('admin/navigation/statusupdate'),
                    'is_form_su'    => 1,
                    'align'         => 'center',
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_is_show_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '新窗口打开',
                    'view_type'     => 'status',
                    'view_key'      => 'is_new_window_open',
                    'post_url'      => MyUrl('admin/navigation/statusupdate'),
                    'align'         => 'center',
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_is_new_window_open_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '排序',
                    'view_type'     => 'field',
                    'view_key'      => 'sort',
                ],
                [
                    'label'         => '创建时间',
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
                    'view_key'      => 'navigation/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }
}
?>