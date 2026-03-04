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
    ];

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
        $lang = MyLang('order.form_table');
        $lang_detail = MyLang('order.detail_form_list');
        $lang_stats = MyLang('order.form_table_stats');
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
                    'label'              => $lang['system_type'],
                    'view_type'          => 'field',
                    'view_key'           => 'system_type',
                    'params_where_name'  => 'system_type_value',
                    'is_copy'            => 1,
                    'width'              => 130,
                    'search_config'      => [
                        'form_type'         => 'input',
                    ],
                ],
                [
                    'label'         => $lang['id'],
                    'view_type'     => 'field',
                    'view_key'      => 'id',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['order_no'],
                    'view_type'     => 'field',
                    'view_key'      => 'order_no',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['items_count'],
                    'view_type'     => 'field',
                    'view_key'      => 'items_count',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['describe'],
                    'view_type'     => 'field',
                    'view_key'      => 'describe',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['goods'],
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/goods',
                    'grid_size'     => 'xl',
                    'is_detail'     => 0,
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
                    'label'                         => $lang['status'],
                    'view_type'                     => 'field',
                    'view_key'                      => 'status_name',
                    'is_bottom_text_tips'           => 1,
                    'bottom_text_tips_where_key'    => 'user_is_delete_time',
                    'bottom_text_tips_where_type'   => 'notin',
                    'bottom_text_tips_where_value'  => [0],
                    'bottom_text_tips_data'         => MyLang('user_delete_operate_tips'),
                    'bottom_text_tips_style'        => 'warning',
                    'is_sort'                       => 1,
                    'search_config'                 => [
                        'form_type'         => 'select',
                        'form_name'         => 'status',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_order_status'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'                   => $lang['pay_status'],
                    'view_type'               => 'field',
                    'view_key'                => 'pay_status_name',
                    'is_color'                => 1,
                    'color_key'               => 'pay_status',
                    'color_style'             => [1=>'success', 2=>'danger', 3=>'danger'],
                    'is_bottom_text_tips'     => 1,
                    'bottom_text_tips_key'    => 'is_under_line_text',
                    'bottom_text_tips_style'  => 'warning',
                    'is_sort'                 => 1,
                    'search_config'           => [
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
                    'label'         => $lang['warehouse_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'warehouse_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'warehouse_id',
                        'where_type'        => 'in',
                        'data'              => $this->OrderWarehouseList(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['order_model'],
                    'view_type'     => 'field',
                    'view_key'      => 'order_model_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'order_model',
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
                    'view_key'      => 'client_type_name',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'client_type',
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
                    'is_detail'     => 0,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'WhereValueAddressInfo',
                    ],
                ],
                [
                    'label'         => $lang['address_contact_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'address_contact_name',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['address_contact_tel'],
                    'view_type'     => 'field',
                    'view_key'      => 'address_contact_tel',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['address_extraction_contact_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'address_extraction_contact_name',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['address_extraction_contact_tel'],
                    'view_type'     => 'field',
                    'view_key'      => 'address_extraction_contact_tel',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['address_info'],
                    'view_type'     => 'field',
                    'view_key'      => 'address_info',
                    'is_list'       => 0,
                    'is_detail'     => 0,
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
                    'label'         => $lang['service'],
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/service',
                    'grid_size'     => 'sm',
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
                    'is_detail'     => 0,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
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
                        'data'              => PaymentService::PaymentList(['field'=>'id,name']),
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
                    'label'         => $lang['currency_name'],
                    'view_type'     => 'field',
                    'view_key'      => 'currency_name',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['currency_code'],
                    'view_type'     => 'field',
                    'view_key'      => 'currency_code',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['currency_symbol'],
                    'view_type'     => 'field',
                    'view_key'      => 'currency_symbol',
                    'is_list'       => 0,
                    'is_detail'     => 0,
                ],
                [
                    'label'         => $lang['currency_rate'],
                    'view_type'     => 'field',
                    'view_key'      => 'currency_rate',
                    'is_list'       => 0,
                    'is_detail'     => 0,
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
                    'label'         => $lang['aftersale'],
                    'view_type'     => 'module',
                    'view_key'      => 'order/module/aftersale',
                    'grid_size'     => 'sm',
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
                    'width'         => IsMobile() ? 120 : '',
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'        => 'Order',
                'data_handle'       => 'OrderService::OrderListHandle',
                'detail_action'     => ['detail', 'saveinfo', 'deliveryinfo', 'serviceinfo', 'tracesourceinfo'],
                'is_page_stats'     => 1,
                'page_stats_data'   => [
                    ['name'=>$lang_stats['total_price'], 'field'=>'total_price'],
                    ['name'=>$lang_stats['pay_price'], 'field'=>'pay_price'],
                    ['name'=>$lang_stats['buy_number_count'], 'field'=>'buy_number_count'],
                    ['name'=>$lang_stats['refund_price'], 'field'=>'refund_price'],
                    ['name'=>$lang_stats['returned_quantity'], 'field'=>'returned_quantity'],
                ],
                'data_params'       => [
                    'is_public' => 0,
                    'is_operate'=> 1,
                    'user_type' => 'admin',
                ],
                'is_field_level_merge'          => 1,
                'handle_field_level_merge_data' => [
                    'currency_name'                    => 'currency_data.currency_name',
                    'currency_code'                    => 'currency_data.currency_code',
                    'currency_symbol'                  => 'currency_data.currency_symbol',
                    'currency_rate'                    => 'currency_data.currency_rate',
                    'address_contact_name'             => 'address_data.name',
                    'address_contact_tel'              => 'address_data.tel',
                    'address_extraction_contact_name'  => 'address_data.extraction_contact_name',
                    'address_extraction_contact_tel'   => 'address_data.extraction_contact_tel',
                    'address_info'                     => ['address_data.province_name', 'address_data.city_name', 'address_data.county_name', 'address_data.address'],
                ],
            ],
            // 详情列表字段数据定义
            'detail_form_list'  => [
                [
                    'label'     => $lang_detail['items']['name'],
                    'field'     => 'items',
                    'data'      => [
                        'id'                      => $lang_detail['items']['data']['id'],
                        'user_id'                 => $lang_detail['items']['data']['user_id'],
                        'order_id'                => $lang_detail['items']['data']['order_id'],
                        'goods_id'                => $lang_detail['items']['data']['goods_id'],
                        'title'                   => $lang_detail['items']['data']['title'],
                        'brand_name'              => $lang_detail['items']['data']['brand_name'],
                        'simple_desc'             => $lang_detail['items']['data']['simple_desc'],
                        'spec_desc'               => $lang_detail['items']['data']['spec_desc'],
                        'images'                  => $lang_detail['items']['data']['images'],
                        'original_price'          => $lang_detail['items']['data']['original_price'],
                        'price'                   => $lang_detail['items']['data']['price'],
                        'total_price'             => $lang_detail['items']['data']['total_price'],
                        'spec_text'               => $lang_detail['items']['data']['spec_text'],
                        'buy_number'              => $lang_detail['items']['data']['buy_number'],
                        'inventory_unit'          => $lang_detail['items']['data']['inventory_unit'],
                        'approval_number'         => $lang_detail['items']['data']['approval_number'],
                        'approval_number_expire'  => $lang_detail['items']['data']['approval_number_expire'],
                        'batch_number'            => $lang_detail['items']['data']['batch_number'],
                        'batch_number_expire'     => $lang_detail['items']['data']['batch_number_expire'],
                        'coding'                  => $lang_detail['items']['data']['coding'],
                        'model'                   => $lang_detail['items']['data']['model'],
                        'produce_company'         => $lang_detail['items']['data']['produce_company'],
                        'produce_region_name'     => $lang_detail['items']['data']['produce_region_name'],
                        'spec_weight'             => $lang_detail['items']['data']['spec_weight'],
                        'spec_volume'             => $lang_detail['items']['data']['spec_volume'],
                        'spec_coding'             => $lang_detail['items']['data']['spec_coding'],
                        'spec_barcode'            => $lang_detail['items']['data']['spec_barcode'],
                        'refund_price'            => $lang_detail['items']['data']['refund_price'],
                        'returned_quantity'       => $lang_detail['items']['data']['returned_quantity'],
                    ],
                ],
                [
                    'label'     => $lang_detail['address_data']['name'],
                    'field'     => 'address_data',
                    'data'      => [
                        'id'                       => $lang_detail['address_data']['data']['id'],
                        'order_id'                 => $lang_detail['address_data']['data']['order_id'],
                        'user_id'                  => $lang_detail['address_data']['data']['user_id'],
                        'address_id'               => $lang_detail['address_data']['data']['address_id'],
                        'alias'                    => $lang_detail['address_data']['data']['alias'],
                        'name'                     => $lang_detail['address_data']['data']['name'],
                        'tel'                      => $lang_detail['address_data']['data']['tel'],
                        'province_name'            => $lang_detail['address_data']['data']['province_name'],
                        'city_name'                => $lang_detail['address_data']['data']['city_name'],
                        'county_name'              => $lang_detail['address_data']['data']['county_name'],
                        'address'                  => $lang_detail['address_data']['data']['address'],
                        'lng'                      => $lang_detail['address_data']['data']['lng'],
                        'lat'                      => $lang_detail['address_data']['data']['lat'],
                        'appoint_time'             => $lang_detail['address_data']['data']['appoint_time'],
                        'extraction_contact_name'  => $lang_detail['address_data']['data']['extraction_contact_name'],
                        'extraction_contact_tel'   => $lang_detail['address_data']['data']['extraction_contact_tel'],
                        'idcard_name'              => $lang_detail['address_data']['data']['idcard_name'],
                        'idcard_number'            => $lang_detail['address_data']['data']['idcard_number'],
                        'idcard_front'             => $lang_detail['address_data']['data']['idcard_front'],
                        'idcard_back'              => $lang_detail['address_data']['data']['idcard_back'],
                    ],
                ],
                [
                    'label'     => $lang_detail['express_data']['name'],
                    'field'     => 'express_data',
                    'data'      => [
                        'id'                   => $lang_detail['express_data']['data']['id'],
                        'order_id'             => $lang_detail['express_data']['data']['order_id'],
                        'user_id'              => $lang_detail['express_data']['data']['user_id'],
                        'express_id'           => $lang_detail['express_data']['data']['express_id'],
                        'express_name'         => $lang_detail['express_data']['data']['express_name'],
                        'express_number'       => $lang_detail['express_data']['data']['express_number'],
                        'express_icon'         => $lang_detail['express_data']['data']['express_icon'],
                        'express_website_url'  => $lang_detail['express_data']['data']['express_website_url'],
                    ],
                ],
                [
                    'label'     => $lang_detail['service_data']['name'],
                    'field'     => 'service_data',
                    'data'      => [
                        'id'                            => $lang_detail['service_data']['data']['id'],
                        'order_id'                      => $lang_detail['service_data']['data']['order_id'],
                        'user_id'                       => $lang_detail['service_data']['data']['user_id'],
                        'service_name'                  => $lang_detail['service_data']['data']['service_name'],
                        'service_mobile'                => $lang_detail['service_data']['data']['service_mobile'],
                        'service_start_time'            => $lang_detail['service_data']['data']['service_start_time'],
                        'service_end_time'              => $lang_detail['service_data']['data']['service_end_time'],
                        'service_duration_minute_text'  => $lang_detail['service_data']['data']['service_duration_minute_text'],
                        'note'                          => $lang_detail['service_data']['data']['note'],
                    ],
                ],
                [
                    'label'     => $lang_detail['extension_data']['name'],
                    'field'     => 'extension_data',
                    'data'      => [
                        'name'      => $lang_detail['extension_data']['data']['name'],
                        'tips'      => $lang_detail['extension_data']['data']['tips'],
                        'business'  => $lang_detail['extension_data']['data']['business'],
                    ],
                ],
            ],
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
     * 订单仓库列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-29
     * @desc    description
     */
    public function OrderWarehouseList()
    {
        $data = [];
        $wids = Db::name('Order')->column('warehouse_id');
        if(!empty($wids))
        {
            $where = ['id'=>$wids];
            $order_by = 'level desc, id desc';
            $data = Db::name('Warehouse')->field('id,name')->where($where)->order($order_by)->select()->toArray();
        }
        return $data;
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
            // 订单ID、订单号
            $ids = Db::name('Order')->where(['id|order_no'=>$value])->column('id');

            // 获取订单详情搜索的订单 id
            if(empty($ids))
            {
                $ids = Db::name('OrderDetail')->where('title|spec|simple_desc|spec_desc|approval_number|batch_number|coding|model|produce_company|produce_region|goods_params|goods_content_app|goods_content_web|spec_coding|spec_barcode', 'like', '%'.$value.'%')->column('order_id');
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