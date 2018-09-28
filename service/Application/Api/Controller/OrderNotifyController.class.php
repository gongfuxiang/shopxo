<?php

namespace Api\Controller;

use Service\ResourcesService;

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
     * [Notify 支付异步处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-03-04T14:35:38+0800
     */
    public function Notify()
    {
        $params = $_REQUEST;
        $params['user'] = $this->user;
        $ret = OrderService::Respond($params);
        if($ret['code'] == 0)
        {
            exit('success');
        }
        exit('error');
    }
}
?>