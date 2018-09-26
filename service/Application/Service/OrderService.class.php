<?php

namespace Service;

use Service\GoodsService;

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
            return DataReturn('请选择订单', -1);
        }

        $m = M('Order');
        $where = ['id'=>intval($params['id']), 'user_id' => $this->user['id']];
        $data = $m->where($where)->field('id,status,total_price')->find();
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

        // 发起支付
        $notify_url = __MY_URL__.'Notify/order.php';
        $pay_data = array(
            'out_user'      =>  md5($this->user['id']),
            'order_sn'      =>  $data['id'].GetNumberCode(6),
            'name'          =>  '订单支付',
            'total_price'   =>  $data['total_price'],
            'notify_url'    =>  $notify_url,
        );
        $pay = (new \Library\Alipay())->SoonPay($pay_data, C("alipay_key_secret"));
        if(empty($pay))
        {
            return DataReturn('支付接口异常', -1);
        }
        return DataReturn(L('common_operation_success'), 0, $pay);
    }
}
?>