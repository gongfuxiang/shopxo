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
 * 用户积分动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-28
 * @desc    description
 */
class Userintegral
{
    // 基础条件
    public $condition_base = [];

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
     * @date    2020-06-28
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
                'search_url'    => MyUrl('index/userintegral/index'),
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '操作类型',
                    'view_type'     => 'field',
                    'view_key'      => 'type_text',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'type',
                        'where_type'        => 'in',
                        'data'              => lang('common_integral_log_type_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '操作积分',
                    'view_type'     => 'field',
                    'view_key'      => 'operation_integral',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '原始积分',
                    'view_type'     => 'field',
                    'view_key'      => 'original_integral',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '最新积分',
                    'view_type'     => 'field',
                    'view_key'      => 'new_integral',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '描述',
                    'view_type'     => 'field',
                    'view_key'      => 'msg',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
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
                    'view_key'      => 'userintegral/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }
}
?>