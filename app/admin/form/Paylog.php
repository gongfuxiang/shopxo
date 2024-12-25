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
use app\service\PayLogService;

/**
 * 支付日志动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-26
 * @desc    description
 */
class PayLog
{
    // 基础条件
    public $condition_base = [];

    // 业务类型
    public $business_type_list;

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
        $list = MyConst('common_pay_log_business_type_list');
        $data = Db::name('PayLog')->group('business_type')->column('business_type');
        if(!empty($data))
        {
            foreach($data as $v)
            {
                if(!array_key_exists($v, $list))
                {
                    $list[$v] = ['value' => $v, 'name' => $v];
                }
            }
        }
        $this->business_type_list = $list;
    }

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
        $lang = MyLang('paylog.form_table');
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
                    'label'         => $lang['log_no'],
                    'view_type'     => 'field',
                    'view_key'      => 'log_no',
                    'width'         => 165,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => $lang['payment'],
                    'view_type'     => 'module',
                    'view_key'      => 'paylog/module/payment',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'payment',
                        'where_type'        => 'in',
                        'data'              => $this->PayLogPaymentTypeList(),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['status'],
                    'view_type'     => 'field',
                    'view_key'      => 'status',
                    'view_data_key' => 'name',
                    'view_data'     => MyConst('common_pay_log_status_list'),
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_pay_log_status_list'),
                        'data_key'          => 'value',
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
                    'label'         => $lang['business_type'],
                    'view_type'     => 'field',
                    'view_key'      => 'business_type',
                    'view_data_key' => 'name',
                    'view_data'     => $this->business_type_list,
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'where_type'        => 'in',
                        'data'              => $this->business_type_list,
                        'data_key'          => 'value',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['business_list'],
                    'view_type'     => 'module',
                    'view_key'      => 'paylog/module/business_list',
                    'width'         => 300,
                    'search_config' => [
                        'form_type'             => 'input',
                        'form_name'             => 'id',
                        'where_type_custom'     => 'in',
                        'where_value_custom'    => 'WhereValueBusinessInfo',
                    ],
                ],
                [
                    'label'         => $lang['trade_no'],
                    'view_type'     => 'field',
                    'view_key'      => 'trade_no',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => '=',
                    ],
                ],
                [
                    'label'         => $lang['buyer_user'],
                    'view_type'     => 'field',
                    'view_key'      => 'buyer_user',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['subject'],
                    'view_type'     => 'field',
                    'view_key'      => 'subject',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'input',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['request_params'],
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'paylog/module/request_params',
                    'align'         => 'left',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'request_params',
                        'where_type'        => 'like',
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
                    'label'         => MyLang('operate_title'),
                    'view_type'     => 'operate',
                    'view_key'      => 'paylog/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => 120,
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'    => 'PayLog',
                'data_handle'   => 'PayLogService::PayLogListHandle',
                'data_params'   => [
                    'is_public'     => 0,
                    'user_type'     => 'admin',
                ],
            ],
        ];
    }

    /**
     * 支付方式类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-26
     * @desc    description
     */
    public function PayLogPaymentTypeList()
    {
        $data = [];
        $ret = PayLogService::PayLogTypeList();
        if(!empty($ret['data']))
        {
            foreach($ret['data'] as $v)
            {
                $data[] = [
                    'id'    => $v['id'],
                    'name'  => $v['name'].'('.$v['id'].')',
                ];
            }
        }
        return $data;
    }

    /**
     * 关联业务条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-30
     * @desc    description
     * @param   [string]          $value    [条件值]
     * @param   [array]           $params   [输入参数]
     */
    public function WhereValueBusinessInfo($value, $params = [])
    {
        if(!empty($value))
        {
            // 获取业务id
            $ids = Db::name('PayLogValue')->where('business_id|business_no', '=', $value)->column('pay_log_id');

            // 避免空条件造成无效的错觉
            return empty($ids) ? [0] : $ids;
        }
        return $value;
    }
}
?>