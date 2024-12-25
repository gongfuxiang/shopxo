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
namespace app\service;

use think\facade\Db;
use app\service\UserService;

/**
 * 退款日志服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class RefundLogService
{
    /**
     * 退款日志添加
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-07T00:57:36+0800
     * @param   [array]             $params         [输入参数]
     * @param   [int]               $pay_id         [支付id]
     * @param   [int]               $user_id        [用户id]
     * @param   [int]               $business_id    [业务订单id]
     * @param   [float]             $pay_price      [业务订单实际支付金额]
     * @param   [string]            $trade_no       [支付平台交易号]
     * @param   [string]            $buyer_user     [支付平台用户帐号]
     * @param   [float]             $refund_price   [退款金额]
     * @param   [string]            $msg            [描述]
     * @param   [string]            $payment        [支付方式标记]
     * @param   [string]            $payment_name   [支付方式名称]
     * @param   [int]               $refundment     [退款类型（0原路退回, 1退至钱包, 2手动处理）]
     * @param   [int]               $business_type  [业务类型，字符串（如：订单、钱包充值、会员购买、等...）]
     * @param   [string]            $request_params [请求参数]
     * @param   [string]            $return_params  [支付平台返回参数]
     * @return  [boolean]                           [成功true, 失败false]
     */
    public static function RefundLogInsert($params = [])
    {
        $data = [
            'pay_id'          => isset($params['pay_id']) ? intval($params['pay_id']) : 0,
            'user_id'         => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'business_id'     => isset($params['business_id']) ? intval($params['business_id']) : 0,
            'pay_price'       => isset($params['pay_price']) ? PriceNumberFormat($params['pay_price']) : 0.00,
            'trade_no'        => isset($params['trade_no']) ? $params['trade_no'] : '',
            'buyer_user'      => isset($params['buyer_user']) ? $params['buyer_user'] : '',
            'refund_price'    => isset($params['refund_price']) ? PriceNumberFormat($params['refund_price']) : 0.00,
            'msg'             => isset($params['msg']) ? $params['msg'] : '',
            'payment'         => isset($params['payment']) ? $params['payment'] : '',
            'payment_name'    => isset($params['payment_name']) ? $params['payment_name'] : '',
            'refundment'      => isset($params['refundment']) ? intval($params['refundment']) : 0,
            'business_type'   => isset($params['business_type']) ? trim($params['business_type']) : 0,
            'request_params'  => empty($params['request_params']) ? '' : (is_array($params['request_params']) ? json_encode($params['request_params'], JSON_UNESCAPED_UNICODE) : $params['request_params']),
            'return_params'   => empty($params['return_params']) ? '' : (is_array($params['return_params']) ? json_encode($params['return_params'], JSON_UNESCAPED_UNICODE) : $params['return_params']),
            'add_time'        => time(),
        ];
        return Db::name('RefundLog')->insertGetId($data) > 0;
    }

    /**
     * 获取退款日志类型
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-23T02:22:03+0800
     * @param   [array]          $params [输入参数]
     */
    public static function RefundLogTypeList($params = [])
    {
        $data = Db::name('RefundLog')->field('payment as id, payment_name as name')->group('payment,payment_name')->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, $data);
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function RefundLogListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            $refundment_list = MyConst('common_order_aftersale_refundment_list');
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']))
                {
                    if(isset($params['is_public']) && $params['is_public'] == 0)
                    {
                        $v['user'] = UserService::GetUserViewInfo($v['user_id']);
                    }
                }

                // 退款方式
                $v['refundment_text'] = $refundment_list[$v['refundment']]['name'];

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
            }
        }
        return $data;
    }
}
?>