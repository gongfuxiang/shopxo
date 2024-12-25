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
use app\service\RefundLogService;

/**
 * 退款日志动态表格
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-06-27
 * @desc    description
 */
class RefundLog
{
    // 基础条件
    public $condition_base = [];

    /**
     * 入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Run($params = [])
    {
        $lang = MyLang('refundlog.form_table');
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
                    'label'         => $lang['payment'],
                    'view_type'     => 'module',
                    'view_key'      => 'refundlog/module/payment',
                    'grid_size'     => 'sm',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'payment',
                        'where_type'        => 'in',
                        'data'              => $this->RefundLogTypeList(),
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
                        'data'              => $this->RefundLogBusinessTypeList(),
                        'data_key'          => 'name',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
                    ],
                ],
                [
                    'label'         => $lang['business_id'],
                    'view_type'     => 'field',
                    'view_key'      => 'business_id',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'             => 'input',
                        'where_type'            => '=',
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
                        'where_type'        => 'like',
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
                    'label'         => $lang['refundment_text'],
                    'view_type'     => 'field',
                    'view_key'      => 'refundment_text',
                    'is_sort'       => 1,
                    'search_config' => [
                        'form_type'         => 'select',
                        'form_name'         => 'refundment',
                        'where_type'        => 'in',
                        'data'              => MyConst('common_order_aftersale_refundment_list'),
                        'data_key'          => 'id',
                        'data_name'         => 'name',
                        'is_multiple'       => 1,
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
                    'label'         => $lang['request_params'],
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'refundlog/module/request_params',
                    'align'         => 'left',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'request_params',
                        'where_type'        => 'like',
                    ],
                ],
                [
                    'label'         => $lang['return_params'],
                    'view_type'     => 'field',
                    'view_type'     => 'module',
                    'view_key'      => 'refundlog/module/return_params',
                    'align'         => 'left',
                    'grid_size'     => 'sm',
                    'search_config' => [
                        'form_type'         => 'input',
                        'form_name'         => 'return_params',
                        'where_type'        => 'like',
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
                    'view_key'      => 'refundlog/module/operate',
                    'align'         => 'center',
                    'fixed'         => 'right',
                    'width'         => 80,
                ],
            ],
            // 数据配置
            'data'  => [
                'table_name'    => 'RefundLog',
                'data_handle'   => 'RefundLogService::RefundLogListHandle',
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
     * @date    2020-06-27
     * @desc    description
     */
    public function RefundLogTypeList()
    {
        $data = [];
        $ret = RefundLogService::RefundLogTypeList();
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
     * 业务类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-26
     * @desc    description
     */
    public function RefundLogBusinessTypeList()
    {
        return Db::name('RefundLog')->field('business_type as name')->group('business_type')->select()->toArray();
    }
}
?>