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
 * 订单售后动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-08
 * @desc    description
 */
class OrderAftersale
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
        $lang = MyLang('orderaftersale.form_table');
        $lang_stats = MyLang('orderaftersale.form_table_stats');
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'is_search'     => 1,
                'is_middle'     => 0,
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => $lang['goods'],
                    'view_type'     => 'module',
                    'view_key'      => 'orderaftersale/module/goods',
                    'grid_size'     => 'lg',
                    'is_sort'       => 1,
                    'sort_field'    => 'goods_id',
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'WhereBaseGoodsInfo',
                        'placeholder'           => $lang['goods_placeholder'],
                    ],
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
                    'label'         => $lang['status'],
                    'view_type'     => 'field',
                    'view_key'      => 'status',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_order_aftersale_status_list'),
                    'width'         => 120,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_order_aftersale_status_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['type'],
                    'view_type'     => 'field',
                    'view_key'      => 'type',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_order_aftersale_type_list'),
                    'width'         => 120,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_order_aftersale_type_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['reason'],
                    'view_type'     => 'field',
                    'view_key'      => 'reason',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['price'],
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => $lang['number'],
                    'view_type'     => 'field',
                    'view_key'      => 'number',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => $lang['msg'],
                    'view_type'     => 'field',
                    'view_key'      => 'msg',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['refundment'],
                    'view_type'     => 'field',
                    'view_key'      => 'refundment',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_order_aftersale_refundment_list'),
                    'width'         => 120,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_order_aftersale_refundment_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['voucher'],
                    'view_type'     => 'many_images',
                    'view_key'      => 'images',
                ],
                [
                    'label'         => $lang['express_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'express_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['express_number'],
                    'view_type'     => 'field',
                    'view_key'      => 'express_number',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['refuse_reason'],
                    'view_type'     => 'field',
                    'view_key'      => 'refuse_reason',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['apply_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'apply_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'apply_time',
                    ],
                ],
                [
                    'label'         => $lang['confirm_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'confirm_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'confirm_time',
                    ],
                ],
                [
                    'label'         => $lang['delivery_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'delivery_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'delivery_time',
                    ],
                ],
                [
                    'label'         => $lang['audit_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'audit_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'audit_time',
                    ],
                ],
                [
                    'label'         => $lang['add_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'add_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'add_time',
                    ],
                ],
                [
                    'label'         => $lang['upd_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'upd_time',
                    ],
                ],
                [
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'orderaftersale/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'        => 'OrderAftersale',
                'data_handle'       => 'OrderAftersaleService::OrderAftersaleListHandle',
                'is_page'           => 1,
                'data_params'       => [
                    'is_public' => 0,
                    'user_type' => 'admin',
                ],
                'is_page_stats'     => 1,
                'page_stats_data'   => [
                    ['name'=>$lang_stats['price'], 'field'=>'price'],
                    ['name'=>$lang_stats['number'], 'field'=>'number'],
                ],
            ],
        ];
    }

    /**
     * 基础条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereBaseGoodsInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单详情搜索的订单售后 id
            $ids = Db::name('OrderAftersale')->alias('oa')->join('order_detail od', 'oa.order_detail_id=od.id')->whereOr('od.title|od.model', 'like', '%'.$value.'%')->whereOr('oa.order_id|oa.order_no', '=', $value)->column('oa.id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>