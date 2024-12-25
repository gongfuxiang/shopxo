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
use app\service\WarehouseService;
use app\service\RegionService;

/**
 * 仓库动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-07
 * @desc    description
 */
class Warehouse
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
     * @date    2020-06-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('warehouse.form_table');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_enable',
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
                    'label'         => $lang['info'],
                    'view_type'     => 'module',
                    'view_key'      => 'warehouse/module/info',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'sort_field'    => 'name',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'name|alias',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['level'],
                    'view_type'     => 'field',
                    'view_key'      => 'level',
                    'is_sort'       => 1,
                    'width'         => 160,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['is_enable'],
                    'view_type'     => 'status',
                    'view_key'      => 'is_enable',
                    'post_url'      => MyUrl('admin/warehouse/statusupdate'),
                    'is_form_su'    => 1,
                    'align'         => 'center',
                    'is_sort'       => 1,
                    'width'         => 130,
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
                    'label'         => $lang['contacts_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'contacts_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['contacts_tel'],
                    'view_type'     => 'field',
                    'view_key'      => 'contacts_tel',
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
                        'data'              => $this->RegionItems('province'),
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
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'city',
                        'data'              => $this->RegionItems('city'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'where_type'        => 'in',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['county_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'county_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'county',
                        'data'              => $this->RegionItems('county'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'where_type'        => 'in',
                        'is_multiple'       => 1,
                    ],
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
                    'label'         => $lang['position'],
                    'view_type'     => 'module',
                    'view_key'      => 'warehouse/module/position',
                    'width'         => 260,
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
                    'view_key'      => 'warehouse/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'    => 'Warehouse',
                'data_handle'   => 'WarehouseService::WarehouseListHandle',
                'order_by'      => 'level desc, id desc',
            ],
        ];
    }

    /**
     * 获取地区数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-08
     * @desc    description
     * @param   [string]          $field [地区字段]
     */
    public function RegionItems($field)
    {
        $result = [];
        $ids = Db::name('Warehouse')->where($this->condition_base)->column($field);
        if(!empty($ids))
        {
            $result = RegionService::RegionNode(['field'=>'id,name', 'where'=>[['id', 'in', $ids]]]);
        }
        return $result;
    }
}
?>