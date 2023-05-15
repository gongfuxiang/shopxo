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
namespace app\api\controller;

use app\service\ApiService;
use app\service\SystemBaseService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\PaymentService;
use app\service\BuyService;

/**
 * 购买
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Buy extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();

        // 是否登录
        $this->IsLogin();
    }
    
    /**
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 获取商品列表
        $params = $this->data_request;
        $params['user'] = $this->user;

        // 默认支付方式
        $params['payment_id'] = PaymentService::BuyDefaultPayment($params);

        // 订单初始化
        $ret = BuyService::BuyOrderInit($params);
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 订单是否已提交、则直接进入订单支付
            if(isset($ret['data']['is_order_submit']) && $ret['data']['is_order_submit'] == 1)
            {
                return ApiService::ApiDataReturn($ret);
            }

            // 基础信息
            $buy_base = $ret['data']['base'];
            $buy_goods = $ret['data']['goods'];

            // 支付方式
            $payment_list = PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]);

            // 数据返回组装
            $result = [
                'goods_list'          => $buy_goods,
                'payment_list'        => $payment_list,
                'base'                => $buy_base,
                'common_site_type'    => (int) $buy_base['common_site_type'],
                'default_payment_id'  => $params['payment_id'],
            ];
            $ret = SystemBaseService::DataReturn($result);
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 订单添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-25
     * @desc    description
     */
    public function Add()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(BuyService::OrderInsert($params));
    }
}
?>