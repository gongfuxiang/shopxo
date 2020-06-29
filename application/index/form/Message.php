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
namespace app\index\form;

use think\Db;

/**
 * 用户消息动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-29
 * @desc    description
 */
class Message
{
    // 基础条件
    public $condition_base = [
        ['user_is_delete_time', '=', 0],
    ];

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
        // 用户信息
        if(!empty($params['system_user']))
        {
            $this->condition_base[] = ['user_id', '=', $params['system_user']['id']];
        }
    }

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-29
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
                'search_url'    => MyUrl('index/message/index'),
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '消息类型',
                    'view_type'     => 'field',
                    'view_key'      => 'type_text',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'type',
                        'where_type'        => 'in',
                        'data'              => lang('common_message_type_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '业务类型',
                    'view_type'     => 'field',
                    'view_key'      => 'business_type_text',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'business_type',
                        'where_type'        => 'in',
                        'data'              => lang('common_business_type_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '标题',
                    'view_type'     => 'field',
                    'view_key'      => 'title',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '详情',
                    'view_type'     => 'field',
                    'view_key'      => 'detail',
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '状态',
                    'view_type'     => 'field',
                    'view_key'      => 'is_read_text',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'is_read',
                        'where_type'        => 'in',
                        'data'              => lang('common_is_read_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'add_time',
                    ],
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'message/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }
}
?>