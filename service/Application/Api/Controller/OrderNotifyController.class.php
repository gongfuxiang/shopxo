<?php

namespace Api\Controller;

/**
 * 订单支付异步通知
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2018-05-21T10:48:48+0800
 */
class OrderNotifyController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2018-05-21T10:48:48+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();
    }

    /**
     * [PayNotify 支付异步处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-03-04T14:35:38+0800
     */
    public function PayNotify()
    {
        $data = (new \Library\Alipay())->Respond(C("alipay_key_secret"));
        if($data == 'no') exit('error');

        // 开始处理支付信息
        $m = M('Order');
        $out_trade_no = substr($data['out_trade_no'], 0, -6);

        // 获取订单记录
        $pay_order = $m->field('id,total_price,status,user_id,shop_id')->find($out_trade_no);

        // 不存在或已经支付则不处理
        if(empty($pay_order) || $pay_order['status'] != 1)
        {
            exit('success');
        }

        // 兼容web版本支付参数
        $buyer_email = isset($data['buyer_logon_id']) ? $data['buyer_logon_id'] : (isset($data['buyer_email']) ? $data['buyer_email'] : '');
        $total_amount = isset($data['total_amount']) ? $data['total_amount'] : (isset($data['total_fee']) ? $data['total_fee'] : '');

        // 写入支付日志
        $pay_log_data = [
            'user_id'       => $pay_order['user_id'],
            'order_id'      => $out_trade_no,
            'trade_no'      => $data['trade_no'],
            'user'          => $buyer_email,
            'total_fee'     => $total_amount,
            'amount'        => $pay_order['total_price'],
            'subject'       => $data['subject'],
            'pay_type'      => 0,
            'business_type' => 0,
            'add_time'      => time(),
        ];
        M('PayLog')->add($pay_log_data);

        // 消息通知
        $detail = '订单支付成功，金额'.PriceBeautify($pay_order['total_price']).'元';
        CommonMessageAdd('订单支付', $detail, $pay_order['user_id']);

        // 开启事务
        $m->startTrans();

        // 更新支付状态
        $where = array('id' => $out_trade_no);
        $upd_data = array(
            'status'    => 2,
            'pay_status'=> 1,
            'pay_price' => $total_amount,
            'pay_time'  => time(),
            'upd_time'  => time(),
        );
        if($m->where($where)->save($upd_data))
        {
            // 积分赠送
            if($this->IntegralGiving($out_trade_no, $pay_order['user_id']) === true)
            {
                // 提交事务
                $m->commit();

                // 成功
                exit('success');
            }
        }
        // 事务回滚
        $m->rollback();

        // 处理失败
        exit('error');
    }

    /**
     * 积分赠送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-06-20
     * @desc    description
     * @param   [int]          $order_id    [订单id]
     * @param   [int]          $user_id     [用户id]
     * @return  [boolean]                   [true成功, false失败]
     */
    private function IntegralGiving($order_id, $user_id)
    {
        return true;
    }
}
?>