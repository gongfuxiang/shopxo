<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
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
        $ret = OrderService::Notify($this->data_request);
        if($ret['code'] == 0)
        {
            $this->SuccessReturn();
        }
        $this->ErrorReturn();
    }

    /**
     * 成功返回
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-09-12
     * @desc    description
     */
    private function SuccessReturn()
    {
        // 根据支付方式处理成功返回结果
        $content = 'success';
        switch(PAYMENT_TYPE)
        {
            // 百度
            case 'BaiduMini' :
                $content = '{"errno":0,"msg":"success","data":{"isConsumed":2}}';
                break;
        }

        // 默认success
        exit($content);
    }

    /**
     * 失败返回
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-09-12
     * @desc    description
     */
    private function ErrorReturn()
    {
        // 根据支付方式处理异步返回结果
        $content = 'error';
        switch(PAYMENT_TYPE)
        {
            // 百度，当处理失败也处理成功消费，需管理员手工处理订单状态或者走其它方式进行处理退款操作
            case 'BaiduMini' :
                $content = '{"errno":0,"msg":"success","data":{"isConsumed":2}}';
                break;
        }

        // 默认error
        exit($content);
    }
}
?>