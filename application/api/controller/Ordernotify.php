<?php
namespace app\api\controller;

use app\service\OrderService;

/**
 * 订单支付异步通知
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2018-05-21T10:48:48+0800
 */
class OrderNotify extends Common
{
    /**
     * [__construct 构造方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

    /**
     * [Notify 支付异步处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-03-04T14:35:38+0800
     */
    public function Notify()
    {
        file_put_contents('./tttttt.txt', json_encode($this->data_request));
        $ret = OrderService::Notify($this->data_request);
        if($ret['code'] == 0)
        {
            exit('success');
        }
        exit('error');
    }
}
?>