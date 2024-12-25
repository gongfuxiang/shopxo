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

use think\facade\Db;

/**
 * 消息管理动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-26
 * @desc    description
 */
class Message
{
    // 基础条件
    public $condition_base = [
        ['is_delete_time', '=', 0],
    ];

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
        $lang = MyLang('message.form_table');
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
                    'label'         => $lang['type'],
                    'view_type'     => 'field',
                    'view_key'      => 'type',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_message_type_list'),
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_message_type_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['business_type'],
                    'view_type'     => 'field',
                    'view_key'      => 'business_type',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->MessageBusinessTypeList(),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['title'],
                    'view_type'     => 'field',
                    'view_key'      => 'title',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['detail'],
                    'view_type'     => 'field',
                    'view_key'      => 'detail',
                    'is_sort'       => 1,
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['is_read'],
                    'view_type'     => 'field',
                    'view_key'      => 'is_read',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_is_read_list'),
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_is_read_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['user_is_delete_time_text'],
                    'view_type'     => 'field',
                    'view_key'      => 'user_is_delete_time_text',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'             => 'select',
                        'where_type'            => 'in',
                        'form_name'             => 'user_is_delete_time',
                        'data'                  => MyConst('common_is_text_list'),
                        'data_key'              => 'id',
                        'data_name'             => 'name',
                        'where_type_custom'     => 'WhereTypeUserIsDelete',
                        'where_value_custom'    => 'WhereValueUserIsDelete',
                        'is_multiple'           => 1,
                    ],
                ],
                [
                    'label'         => $lang['add_time_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'add_time_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'add_time',
                    ],
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'message/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => 120,
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'    => 'Message',
                'data_handle'   => 'MessageService::MessageListHandle',
                'data_params'   => [
                    'is_public'     => 0,
                    'user_type'     => 'admin',
                ],
            ],
        ];
    }

    /**
     * 是否删除条件符号处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $form_key     [表单数据key]
     * @param   [array]           $params       [输入参数]
     */
    public function WhereTypeUserIsDelete($form_key, $params = [])
    {
        if(isset($params[$form_key]))
        {
            // 条件值是 0,1
            // 解析成数组，都存在则返回null，则1 >， 0 =
            $value = explode(',', urldecode($params[$form_key]));
            if(count($value) == 1)
            {
                return in_array(1, $value) ? '>' : '=';
            }
        }
        return null;
    }

    /**
     * 是否删除条件值处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $form_key     [表单数据key]
     * @param   [array]           $params       [输入参数]
     */
    public function WhereValueUserIsDelete($value, $params = [])
    {
        return (count($value) == 2) ? null : 0;
    }

    /**
     * 业务类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-26
     * @desc    description
     */
    public function MessageBusinessTypeList()
    {
        return Db::name('Message')->field('business_type as name')->group('business_type')->select()->toArray();
    }
}
?>