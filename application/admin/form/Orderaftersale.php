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

use think\Db;

/**
 * 订单售后动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-08
 * @desc    description
 */
class Orderaftersale
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
                'search_url'    => MyUrl('admin/orderaftersale/index'),
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '订单号',
                    'view_type'     => 'field',
                    'view_key'      => 'order_no',
                    'width'         => 170,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => '基础信息',
                    'view_type'     => 'module',
                    'view_key'      => 'orderaftersale/module/goods',
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type'            => 'like',
                        'where_type_custom'     => 'in',
                        'where_handle_custom'   => 'WhereValueBaseInfo',
                        'placeholder'           => '请输入商品名称/型号',
                    ],
                ],
                [
                    'label'         => '用户信息',
                    'view_type'     => 'module',
                    'view_key'      => 'lib/module/user',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'user_id',
                        'where_type'            => 'like',
                        'where_type_custom'     => 'in',
                        'where_handle_custom'   => 'WhereValueUserInfo',
                        'placeholder'           => '请输入用户名/昵称/手机/邮箱',
                    ],
                ],
                [
                    'label'         => '状态',
                    'view_type'     => 'field',
                    'view_key'      => 'status',
                    'view_data_key' => 'name',
                    'view_data'     => lang('common_order_aftersale_status_list'),
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_order_aftersale_status_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '申请类型',
                    'view_type'     => 'field',
                    'view_key'      => 'type',
                    'view_data_key' => 'name',
                    'view_data'     => lang('common_order_aftersale_type_list'),
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_order_aftersale_type_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '原因',
                    'view_type'     => 'field',
                    'view_key'      => 'reason',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '退款金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '退货数量',
                    'view_type'     => 'field',
                    'view_key'      => 'number',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '退款说明',
                    'view_type'     => 'field',
                    'view_key'      => 'msg',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '退款类型',
                    'view_type'     => 'field',
                    'view_key'      => 'refundment',
                    'view_data_key' => 'name',
                    'view_data'     => lang('common_order_aftersale_refundment_list'),
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => lang('common_order_aftersale_refundment_list'),
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '凭证',
                    'view_type'     => 'module',
                    'view_key'      => 'orderaftersale/module/voucher',
                    'is_list'        => 0,
                ],
                [
                    'label'         => '快递公司',
                    'view_type'     => 'field',
                    'view_key'      => 'express_name',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '快递单号',
                    'view_type'     => 'field',
                    'view_key'      => 'express_number',
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '申请时间',
                    'view_type'     => 'field',
                    'view_key'      => 'apply_time_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'apply_time',
                    ],
                ],
                [
                    'label'         => '确认时间',
                    'view_type'     => 'field',
                    'view_key'      => 'confirm_time_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'confirm_time',
                    ],
                ],
                [
                    'label'         => '退货时间',
                    'view_type'     => 'field',
                    'view_key'      => 'delivery_time_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'delivery_time',
                    ],
                ],
                [
                    'label'         => '审核时间',
                    'view_type'     => 'field',
                    'view_key'      => 'audit_time_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'audit_time',
                    ],
                ],
                [
                    'label'         => '创建时间',
                    'view_type'     => 'field',
                    'view_key'      => 'add_time_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'add_time',
                    ],
                ],
                [
                    'label'         => '更新时间',
                    'view_type'     => 'field',
                    'view_key'      => 'upd_time_time',
                    'search_config' => [
                        'form_type'         => 'datetime',
                        'form_name'         => 'upd_time',
                    ],
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'orderaftersale/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }

    /**
     * 用户信息条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueUserInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取用户 id
            $ids = Db::name('User')->where('username|nickname|mobile|email', 'like', '%'.$value.'%')->column('id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 基础信息条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueBaseInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单详情搜索的订单售后 id
            $ids = Db::name('OrderAftersale')->alias('oa')->join(['__ORDER_DETAIL__'=>'od'], 'oa.order_detail_id=od.id')->where('title|model', 'like', '%'.$value.'%')->column('oa.id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>