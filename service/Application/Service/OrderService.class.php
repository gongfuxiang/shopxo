<?php

namespace Service;

use Service\GoodsService;
use Service\ResourcesService;

/**
 * 订单服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OrderService
{
    /**
     * 订单支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Pay($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id' => $params['user']['id']];
        $data = M('Order')->where($where)->field('id,status,total_price,payment_id')->find();
        if(empty($data))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if($data['total_price'] <= 0.00)
        {
            return DataReturn('金额不能为0', -1);
        }
        if($data['status'] != 1)
        {
            $status_text = L('common_order_user_status')[$data['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 支付方式
        $payment = ResourcesService::PaymentList(['where'=>['id'=>$data['payment_id']]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 发起支付
        $pay_data = array(
            'out_user'      => md5($params['user']['id']),
            'order_sn'      => date('YmdHis').$data['id'],
            'name'          => '订单支付',
            'total_price'   => $data['total_price'],
            'notify_url'    => __MY_URL__.'notify_order.php',
            'call_back_url' => __MY_URL__.'respond_order.php',
        );
        $pay_name = '\Library\Payment\\'.$payment[0]['payment'];
        $ret = (new $pay_name($payment[0]['config']))->Pay($pay_data);
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 存储session订单id
            $_SESSION['payment_order_id'] = $data['id'];
            return $ret;
        }
        return DataReturn('支付接口异常', -1);
    }

    /**
     * 支付同步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Respond($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'out_trade_no',
                'error_msg'         => '支付回调参数缺失[out_trade_no]',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>self::OutTradeNoParsing($params['out_trade_no']), 'user_id' => $params['user']['id']];
        $data = M('Order')->where($where)->field('id,status,payment_id')->find();
        if(empty($data))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if($data['status'] > 1)
        {
            $status_text = L('common_order_user_status')[$data['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 支付方式
        $payment = ResourcesService::PaymentList(['where'=>['id'=>$data['payment_id']]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = '\Library\Payment\\'.$payment[0]['payment'];
        return (new $pay_name($payment[0]['config']))->Respond();
    }

    /**
     * 支付异步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Notify($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'out_trade_no',
                'error_msg'         => '支付回调参数缺失[out_trade_no]',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $m = M('Order');
        $where = ['id'=>self::OutTradeNoParsing($params['out_trade_no'])];
        $data = $m->where($where)->field('id,status,total_price,payment_id,user_id,shop_id')->find();
        file_put_contents('/data/www/project/shopxo/service/dddddd.txt', json_encode($data));
        if(empty($data))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if($data['status'] > 1)
        {
            $status_text = L('common_order_user_status')[$data['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', 0);
        }

        // 支付方式
        $payment = ResourcesService::PaymentList(['where'=>['id'=>$data['payment_id']]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = '\Library\Payment\\'.$payment[0]['payment'];
        $ret = (new $pay_name($payment[0]['config']))->Respond();
        if(!isset($ret['code']) || $ret['code'] != 0)
        {
            return $ret;
        }
        file_put_contents('/data/www/project/shopxo/service/rrrrrr.txt', json_encode($ret));

        // 兼容web版本支付参数
        $buyer_email = isset($ret['data']['buyer_logon_id']) ? $ret['data']['buyer_logon_id'] : (isset($ret['data']['buyer_email']) ? $ret['data']['buyer_email'] : '');
        $total_amount = isset($ret['data']['total_amount']) ? $ret['data']['total_amount'] : (isset($ret['data']['total_fee']) ? $ret['data']['total_fee'] : '');

        // 写入支付日志
        $pay_log_data = [
            'user_id'       => $data['user_id'],
            'order_id'      => $data['id'],
            'trade_no'      => $ret['data']['trade_no'],
            'user'          => $buyer_email,
            'total_fee'     => $total_amount,
            'amount'        => $data['total_price'],
            'subject'       => $ret['data']['subject'],
            'pay_type'      => 0,
            'business_type' => 0,
            'add_time'      => time(),
        ];
        file_put_contents('/data/www/project/shopxo/service/llllll.txt', json_encode($pay_log_data));
        M('PayLog')->add($pay_log_data);

        // 消息通知
        $detail = '订单支付成功，金额'.PriceBeautify($data['total_price']).'元';
        CommonMessageAdd('订单支付', $detail, $data['user_id']);

        // 开启事务
        $m->startTrans();

        // 更新订单状态
        $upd_data = array(
            'status'    => 2,
            'pay_status'=> 1,
            'pay_price' => $total_amount,
            'pay_time'  => time(),
            'upd_time'  => time(),
        );
        file_put_contents('/data/www/project/shopxo/service/uuuuuu.txt', json_encode($upd_data));
        if($m->where(['id'=>$data['id']])->save($upd_data))
        {
            // 提交事务
            $m->commit();

            // 成功
            return DataReturn('处理成功', 0);
        }
        // 事务回滚
        $m->rollback();

        // 处理失败
        return DataReturn('处理失败222', -100);
    }

    /**
     * 订单号解析
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [string]          $out_trade_no [支付回传订单号]
     */
    public static function OutTradeNoParsing($out_trade_no)
    {
        return substr($out_trade_no, 14);
    }
}
?>