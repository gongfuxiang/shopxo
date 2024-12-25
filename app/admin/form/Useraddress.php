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

use app\service\RegionService;

/**
 * 用户地址动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-19
 * @desc    description
 */
class UserAddress
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('useraddress.form_table');
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
                    'label'         => $lang['alias'],
                    'view_type'     => 'field',
                    'view_key'      => 'alias',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['name'],
                    'view_type'     => 'field',
                    'view_key'      => 'name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['tel'],
                    'view_type'     => 'field',
                    'view_key'      => 'tel',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['province_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'province_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'province',
                        'data'              => $this->RegionProvinceItems(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'where_type'        => 'in',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['city_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'city_name',
                    'is_sort'       => 1,
                    'sort_field'    => 'city',
                ],
                [
                    'label'         => $lang['county_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'county_name',
                    'is_sort'       => 1,
                    'sort_field'    => 'county',
                ],
                [
                    'label'         => $lang['address'],
                    'view_type'     => 'field',
                    'view_key'      => 'address',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['address_last_code'],
                    'view_type'     => 'field',
                    'view_key'      => 'address_last_code',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                    ],
                ],
                [
                    'label'         => $lang['position'],
                    'view_type'     => 'module',
                    'view_key'      => 'useraddress/module/position',
                    'width'         => 260,
                ],
                [
                    'label'         => $lang['idcard_info'],
                    'view_type'     => 'module',
                    'view_key'      => 'useraddress/module/idcard_info',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'sort_field'    => 'idcard_number',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'idcard_name|idcard_number',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['is_default'],
                    'view_type'     => 'field',
                    'view_key'      => 'is_default',
                    'view_data'     => MyConst('common_is_text_list'),
                    'view_data_key' => 'name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_is_text_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
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
                    'view_key'      => 'useraddress/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'    => 'UserAddress',
                'data_handle'   => 'UserAddressService::UserAddressListHandle',
                'data_params'   => [
                    'is_public'     => 0,
                ],
            ],
        ];
    }

    /**
     * 获取地区省份数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-08
     * @desc    description
     */
    public function RegionProvinceItems()
    {
        return RegionService::RegionNode(['field'=>'id,name', 'where'=>[['pid', '=', 0]]]);
    }
}
?>