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
namespace app\index\form;

use think\facade\Db;
use app\service\PaymentService;

/**
 * 订单动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-08
 * @desc    description
 */
class Order
{
    // 基础条件
    public $condition_base = [
        ['is_delete_time', '=', 0],
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
     * @date    2020-06-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        // 基础配置
        $base = [
            'key_field'     => 'id',
            'is_search'     => 1,
            'detail_title'  => MyLang('form_table_base_detail_title'),
            'is_middle'     => 0,
        ];

        // 表单配置
        $lang = MyLang('order.form_table');
        $lang_stats = MyLang('order.form_table_stats');
        $form = [
            [
                'label'         => $lang['goods'],
                'view_type'     => 'module',
                'view_key'      => 'order/module/goods',
                'grid_size'     => 'xl',
                'search_config' => [
                    'form_type'             => 'input',
                    'form_name'             => 'id',
                    'where_type'            => 'like',
                    'where_type_custom'     => 'in',
                    'where_value_custom'    => 'WhereBaseGoodsInfo',
                    'placeholder'           => $lang['goods_placeholder'],
                ],
            ],
            [
                'label'         => $lang['status'],
                'view_type'     => 'field',
                'view_key'      => 'status',
                'view_data_key' => 'name',
                'view_data'     => MyConst('common_order_status'),
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_order_status'),
                    'data_key'          => 'id',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => $lang['pay_status'],
                'view_type'     => 'module',
                'view_key'      => 'order/module/pay_status',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'form_name'         => 'pay_status',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_order_pay_status'),
                    'data_key'          => 'id',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => $lang['total_price'],
                'view_type'     => 'field',
                'view_key'      => 'total_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => $lang['pay_price'],
                'view_type'     => 'field',
                'view_key'      => 'pay_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
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
                'label'         => $lang['order_model'],
                'view_type'     => 'field',
                'view_key'      => 'order_model',
                'view_data_key' => 'name',
                'view_data'     => MyConst('common_order_type_list'),
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_order_type_list'),
                    'data_key'          => 'value',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => $lang['client_type'],
                'view_type'     => 'field',
                'view_key'      => 'client_type',
                'view_data_key' => 'name',
                'view_data'     => MyConst('common_platform_type'),
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'select',
                    'where_type'        => 'in',
                    'data'              => MyConst('common_platform_type'),
                    'data_key'          => 'value',
                    'data_name'         => 'name',
                    'is_multiple'       => 1,
                ],
            ],
            [
                'label'         => $lang['address'],
                'view_type'     => 'module',
                'view_key'      => 'order/module/address',
                'width'         => 400,
                'search_config' => [
                    'form_type'             => 'input',
                    'form_name'             => 'id',
                    'where_type'            => 'like',
                    'where_type_custom'     => 'in',
                    'where_value_custom'    => 'WhereValueAddressInfo',
                ],
            ],
            [
                    'label'         => $lang['service'],
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/service',
                    'width'         => 460,
                    'is_detail'     => 0,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'WhereValueServiceInfo',
                    ],
                ],
            [
                'label'         => $lang['take'],
                'view_type'     => 'module',
                'view_key'      => 'order/module/take',
                'width'         => 130,
                'search_config' => [
                    'form_type'             => 'input',
                    'form_name'             => 'id',
                    'where_type'            => 'like',
                    'where_type_custom'     => 'in',
                    'where_value_custom'    => 'WhereValueTakeInfo',
                ],
            ],
            [
                'label'         => $lang['refund_price'],
                'view_type'     => 'field',
                'view_key'      => 'refund_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => $lang['returned_quantity'],
                'view_type'     => 'field',
                'view_key'      => 'returned_quantity',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                ],
            ],
            [
                'label'         => $lang['buy_number_count'],
                'view_type'     => 'field',
                'view_key'      => 'buy_number_count',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                ],
            ],
            [
                'label'         => $lang['increase_price'],
                'view_type'     => 'field',
                'view_key'      => 'increase_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => $lang['preferential_price'],
                'view_type'     => 'field',
                'view_key'      => 'preferential_price',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'section',
                    'is_point'          => 1,
                ],
            ],
            [
                'label'         => $lang['payment_name'],
                'view_type'     => 'field',
                'view_key'      => 'payment_name',
                'is_sort'       => 1,
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
                'label'         => $lang['user_note'],
                'view_type'     => 'field',
                'view_key'      => 'user_note',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'input',
                    'where_type'        => 'like',
                ],
            ],
            [
                'label'         => $lang['extension'],
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
                'label'         => $lang['express'],
                'view_type'     => 'module',
                'view_key'      => 'order/module/express',
                'grid_size'     => 'sm',
                'is_detail'     => 0,
                'search_config' => [
                    'form_type'             => 'input',
                    'form_name'             => 'id',
                    'where_type_custom'     => 'in',
                    'where_value_custom'    => 'WhereExpressInfo',
                    'placeholder'           => $lang['express_placeholder'],
                ],
            ],
            [
                'label'         => $lang['is_comments'],
                'view_type'     => 'module',
                'view_key'      => 'order/module/is_comments',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'             => 'select',
                    'where_type'            => 'in',
                    'form_name'             => 'user_is_comments',
                    'data'                  => MyConst('common_is_text_list'),
                    'data_key'              => 'id',
                    'data_name'             => 'name',
                    'where_type_custom'     => 'WhereTypyUserIsComments',
                    'where_value_custom'    => 'WhereValueUserIsComments',
                    'is_multiple'           => 1,
                ],
            ],
            [
                'label'         => $lang['confirm_time'],
                'view_type'     => 'field',
                'view_key'      => 'confirm_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => $lang['pay_time'],
                'view_type'     => 'field',
                'view_key'      => 'pay_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                    'label'         => $lang['delivery_time'],
                    'view_type'     => 'field',
                    'view_key'      => 'delivery_time',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'datetime',
                    ],
                ],
            [
                'label'         => $lang['collect_time'],
                'view_type'     => 'field',
                'view_key'      => 'collect_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => $lang['cancel_time'],
                'view_type'     => 'field',
                'view_key'      => 'cancel_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
                ],
            ],
            [
                'label'         => $lang['close_time'],
                'view_type'     => 'field',
                'view_key'      => 'close_time',
                'is_sort'       => 1,
                'search_config' => [
                    'form_type'         => 'datetime',
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
                'view_key'      => 'order/module/operate',
                'align'         => 'center',
                'fixed'         => 'right',
            ],
        ];

        // 是否启用订单批量支付
        if(MyC('home_is_enable_order_bulk_pay') == 1)
        {
            array_unshift($form, [
                'view_type'         => 'checkbox',
                'is_checked'        => 0,
                'checked_text'      => MyLang('reverse_select_title'),
                'not_checked_text'  => MyLang('select_all_title'),
                'not_show_key'      => 'status',
                'not_show_data'     => [0,2,3,4,5,6],
                'align'             => 'center',
                'width'             => 80,
                'view_key'          => 'order_form_checkbox_value',
            ]);
        }

        // 数据配置
        $data = [
            'table_name'        => 'Order',
            'data_handle'       => 'OrderService::OrderListHandle',
            'detail_where'      => $this->condition_base,
            'is_page_stats'     => 1,
            'page_stats_data'   => [
                ['name'=>$lang_stats['total_price'], 'field'=>'total_price'],
                ['name'=>$lang_stats['pay_price'], 'field'=>'pay_price'],
                ['name'=>$lang_stats['buy_number_count'], 'field'=>'buy_number_count'],
                ['name'=>$lang_stats['refund_price'], 'field'=>'refund_price'],
                ['name'=>$lang_stats['returned_quantity'], 'field'=>'returned_quantity'],
            ],
            'data_params'       => [
                'is_operate'        => 1,
                'is_orderaftersale' => 1,
                'user_type'         => 'user',
            ],
        ];

        return [
            'base'  => $base,
            'form'  => $form,
            'data'  => $data,
        ];
    }

    /**
     * 评论条件符号处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $form_key     [表单数据key]
     * @param   [array]           $params       [输入参数]
     */
    public function WhereTypyUserIsComments($form_key, $params = [])
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
     * 评论条件值处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $form_key     [表单数据key]
     * @param   [array]           $params       [输入参数]
     */
    public function WhereValueUserIsComments($value, $params = [])
    {
        return (count($value) == 2) ? null : 0;
    }

    /**
     * 取货码条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueTakeInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单 id
            $ids = Db::name('OrderExtractionCode')->where('code', '=', $value)->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 服务信息条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueServiceInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单 id
            $ids = Db::name('OrderService')->where('service_name|service_mobile|note', 'like', '%'.$value.'%')->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 地址条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueAddressInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取订单 id
            $ids = Db::name('OrderAddress')->where('name|tel|province_name|city_name|county_name|address|extraction_contact_name|extraction_contact_tel', 'like', '%'.$value.'%')->column('order_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
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
            // 订单号
            $ids = Db::name('Order')->where(['order_no'=>$value])->column('id');

            // 获取订单详情搜索的订单 id
            if(empty($ids))
            {
                $ids = Db::name('OrderDetail')->where('title|model', 'like', '%'.$value.'%')->column('order_id');
            }

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }

    /**
     * 快递条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-08
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereExpressInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 订单ID
            $ids = Db::name('OrderExpress')->where(['express_number'=>$value])->column('order_id');
            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>