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
        if(empty($params['id']))
        {
            return DataReturn('订单id有误', -1);
        }

        $m = M('Order');
        $where = ['id'=>intval($params['id']), 'user_id' => $params['user']['id']];
        $data = $m->where($where)->field('id,status,total_price,payment_id')->find();
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
        $notify_url = __MY_URL__.'Notify/order.php';
        $pay_data = array(
            'out_user'      =>  md5($params['user']['id']),
            'order_sn'      =>  date('YmdHis').$data['id'],
            'name'          =>  '订单支付',
            'total_price'   =>  $data['total_price'],
            'notify_url'    =>  $notify_url,
        );
        $pay_name = '\Library\Payment\\'.$payment[0]['payment'];
        $pay = (new $pay_name($payment[0]['config']))->Pay($pay_data);
        if(empty($pay))
        {
            return DataReturn('支付接口异常', -1);
        }
        return DataReturn(L('common_operation_success'), 0, $pay);
    }
}
?>