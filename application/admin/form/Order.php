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
use app\service\GoodsService;
use app\service\RegionService;
use app\service\BrandService;
use app\service\PaymentService;

/**
 * 订单动态表单
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-16
 * @desc    description
 */
class Order
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
     * @date    2020-05-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        return [
            // 基础配置
            'base' => [
                'key_field'     => 'id',
                'status_field'  => 'is_shelves',
                'is_search'     => 1,
                'search_url'    => MyUrl('admin/order/index'),
            ],
            // 表单配置
            'form' => [
                [
                    'label'         => '订单ID',
                    'view_type'     => 'field',
                    'view_key'      => 'id',
                    'width'         => 105,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
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
                    'view_key'      => 'order/module/info',
                    'grid_size'     => 'lg',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'id',
                        'where_type'        => 'in',
                        'placeholder'       => '请输入商品名称/型号',
                        'where_custom'      => 'WhereValueBaseInfo',
                    ],
                ],
                [
                    'label'         => '用户信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/user',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'user_id',
                        'where_type'        => 'in',
                        'placeholder'       => '请输入用户名/昵称/手机/邮箱',
                        'where_custom'      => 'WhereValueUserInfo',
                    ],
                ],
                [
                    'label'         => '地址信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/address',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'user_id',
                        'where_type'        => 'in',
                        'placeholder'       => '请输入收件姓名/电话/地址',
                        'where_custom'      => 'WhereValueAddressInfo',
                    ],
                ],
                [
                    'label'         => '取货信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/take',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'id',
                        'where_type'        => '=',
                        'placeholder'       => '请输入取货码',
                        'where_custom'      => 'WhereValueAddressInfo',
                    ],
                ],
                [
                    'label'         => '订单状态',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/status',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'status',
                        'where_type'        => 'in',
                        'data'              => lang('common_order_admin_status'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '支付状态',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/pay_status',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'pay_status',
                        'where_type'        => 'in',
                        'data'              => lang('common_order_pay_status'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '订单模式',
                    'view_type'     => 'field',
                    'view_key'      => 'order_model_name',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'order_model',
                        'where_type'        => 'in',
                        'data'              => lang('common_site_type_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '来源',
                    'view_type'     => 'field',
                    'view_key'      => 'client_type_name',
                    'width'         => 120,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'client_type',
                        'where_type'        => 'in',
                        'data'              => lang('common_platform_type'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '单价(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '总价(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'total_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '支付金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'pay_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '退款金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'refund_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '退货数量',
                    'view_type'     => 'field',
                    'view_key'      => 'returned_quantity',
                    'search_config' => [
                        'form_type'         => 'section',
                    ],
                ],
                [
                    'label'         => '增加金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'increase_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '优惠金额(元)',
                    'view_type'     => 'field',
                    'view_key'      => 'preferential_price',
                    'search_config' => [
                        'form_type'         => 'section',
                        'is_point'          => 1,
                    ],
                ],
                [
                    'label'         => '支付方式',
                    'view_type'     => 'field',
                    'view_key'      => 'payment_name',
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'payment_id',
                        'where_type'        => 'in',
                        'data'              => PaymentService::PaymentList(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => '扩展信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/extension',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'extension_data',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => '快递信息',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/express',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'express_number',
                        'where_type'        => 'like',
                        'placeholder'       => '请输入快递单号',
                    ],
                ],
                [
                    'label'         => '最新售后',
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/aftersale',
                    'grid_size'     => 'sm',
                ],
                [
                    'label'         => '操作',
                    'view_type'     => 'operate',
                    'view_key'      => 'order/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                ],
            ],
        ];
    }

    /**
     * 商品分类条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-03
     * @desc    description
     * @param   [string]          $name     [字段名称]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueGoodsCategory($value, $params = [])
    {
        if(!empty($value))
        {
            // 是否为数组
            if(!is_array($value))
            {
                $value = [$value];
            }

            // 获取分类下的所有分类 id
            $category_ids = GoodsService::GoodsCategoryItemsIds($value, 1);

            // 获取商品 id
            $goods_ids = Db::name('GoodsCategoryJoin')->where(['category_id'=>$category_ids])->column('goods_id');

            // 避免空条件造成无效的错觉
            return empty($goods_ids) ? [0] : $goods_ids;
        }
        return $value;
    }
}