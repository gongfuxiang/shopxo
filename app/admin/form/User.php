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
        $lang = MyLang('user.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'             => 'id',
                'is_search'             => 1,
                'is_delete'             => 1,
                'is_middle'             => 0,
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
                    'label'         => $lang['number_code'],
                    'view_type'     => 'qrcode',
                    'view_key'      => 'number_code',
                    'width'         => 150,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => $lang['system_type'],
                    'view_type'     => 'field',
                    'view_key'      => 'system_type_text',
                    'text_truncate' => 2,
                    'is_popover'    => 1,
                    'search_config' => [
                        'form_type'           => 'select',
                        'form_name'           => 'id',
                        'where_type_custom'   => 'in',
                        'where_value_custom'  => 'WhereValueSystemType',
                        'data'                => $this->SystemTypeList(),
                        'is_multiple'         => 1,
                    ],
                ],
                [
                    'label'         => $lang['platform'],
                    'view_type'     => 'field',
                    'view_key'      => 'platform_text',
                    'text_truncate' => 2,
                    'is_popover'    => 1,
                    'search_config' => [
                        'form_type'           => 'select',
                        'form_name'           => 'id',
                        'where_type_custom'   => 'in',
                        'where_value_custom'  => 'WhereValuePlatform',
                        'data'                => MyConst('common_platform_type'),
                        'data_key'            => 'value',
                        'data_name'           => 'name',
                        'is_multiple'         => 1,
                    ],
                ],
                [
                    'label'         => $lang['avatar'],
                    'view_type'     => 'images',
                    'view_key'      => 'avatar',
                    'images_width'  => 40,
                    'images_height' => 40,
                    'width'         => 65,
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
                    'label'         => $lang['nickname'],
                    'view_type'     => 'field',
                    'view_key'      => 'nickname',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
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
                    'label'              => $lang['status_name'],
                    'view_type'          => 'field',
                    'view_key'           => 'status_name',
                    'is_round_point'     => 1,
                    'round_point_key'    => 'status',
                    'round_point_style'  => [0=>'success', 1=>'warning', 2=>'danger'],
                    'is_sort'            => 1,
                    'search_config'      => [
                        'form_type'         => 'select',
                        'form_name'         => 'status',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_user_status_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['gender_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'gender',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_gender_list'),
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'gender',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_gender_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['province'],
                    'view_type'     => 'field',
                    'view_key'      => 'province',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['city'],
                    'view_type'     => 'field',
                    'view_key'      => 'city',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['county'],
                    'view_type'     => 'field',
                    'view_key'      => 'county',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['address'],
                    'view_type'     => 'field',
                    'view_key'      => 'address',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['birthday'],
                    'view_type'     => 'field',
                    'view_key'      => 'birthday',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'date',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => $lang['integral'],
                    'view_type'     => 'field',
                    'view_key'      => 'integral',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['locking_integral'],
                    'view_type'     => 'field',
                    'view_key'      => 'locking_integral',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['referrer'],
                    'view_type'     => 'module',
                    'view_key'      => 'user/module/referrer',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'referrer',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'SystemModuleUserWhereHandle',
                        'placeholder'           => $lang['referrer_placeholder'],
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
                    'view_key'      => 'user/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'           => 'User',
                'data_handle'          => 'UserService::UserListHandle',
                'is_fixed_name_field'  => 1,
                'fixed_name_data'      => [
                    'status'  => [
                        'data'  => MyConst('common_user_status_list'),
                    ],
                ],
            ],
        ];
    }

    /**
     * 系统类型条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueSystemType($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取用户 id
            $ids = Db::name('UserPlatform')->where('system_type', 'in', $value)->column('user_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 用户平台条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValuePlatform($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取用户 id
            $ids = Db::name('UserPlatform')->where('platform', 'in', $value)->column('user_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 系统类型列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-14
     * @desc    description
     */
    public function SystemTypeList()
    {
        return Db::name('UserPlatform')->group('system_type')->column('system_type', 'system_type');
    }
}
?>