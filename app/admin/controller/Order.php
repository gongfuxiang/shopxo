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
namespace app\admin\controller;

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\OrderService;
use app\service\PaymentService;
use app\service\ExpressService;

/**
 * 订单管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Order extends Base
{
    /**
     * 订单列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 默认不加载视频扫码组件
            'is_load_video_scan'    => 1,
        ];

        // 发起支付 - 支付方式
        $pay_wparams = [
            'where' => [
                ['is_enable', '=', 1],
                ['payment', 'in', MyConfig('shopxo.under_line_list')],
            ],
        ];
        $assign['buy_payment_list'] = PaymentService::BuyPaymentList($pay_wparams);

        // 数据赋值
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Detail()
    {
        return MyView();
    }

    /**
     * 发货页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function DeliveryInfo()
    {
        // 快递公司
        MyViewAssign('express_list', ExpressService::ExpressList());
        return MyView();
    }

    /**
     * 服务页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function ServiceInfo()
    {
        return MyView();
    }

    /**
     * 订单删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Delete()
    {
        // 删除操作
        $params = $this->data_request;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $params['user_type'] = 'admin';
        return ApiService::ApiDataReturn(OrderService::OrderDelete($params));
    }

    /**
     * 订单取消
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Cancel()
    {
        // 取消操作
        $params = $this->data_request;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $params['user_type'] = 'admin';
        return ApiService::ApiDataReturn(OrderService::OrderCancel($params));
    }

    /**
     * 订单发货、取货、服务
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Delivery()
    {
        // 发货操作
        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $params['user_type'] = 'admin';
        return ApiService::ApiDataReturn(OrderService::OrderDelivery($params));
    }

    /**
     * 订单收货
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Collect()
    {
        // 收货操作
        $params = $this->data_request;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $params['user_type'] = 'admin';
        return ApiService::ApiDataReturn(OrderService::OrderCollect($params));
    }

    /**
     * 订单确认
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Confirm()
    {
        // 订单确认
        $params = $this->data_request;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $params['user_type'] = 'admin';
        return ApiService::ApiDataReturn(OrderService::OrderConfirm($params));
    }

    /**
     * 订单支付
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Pay()
    {
        $params = $this->data_request;
        $params['admin'] = $this->admin;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $params['user_type'] = 'admin';
        return ApiService::ApiDataReturn(OrderService::OrderPaymentUnderLinePay($params));
    }
}
?>