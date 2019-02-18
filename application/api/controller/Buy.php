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
        $this->Is_Login();
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
        $params = $this->data_post;
        $params['user'] = $this->user;
        $ret = BuyService::BuyTypeGoodsList($params);

        // 商品校验
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 用户默认地址
            $address = UserService::UserDefaultAddress(['user'=>$this->user]);

            // 商品/基础信息
            $base = [
                'total_price'   => empty($ret['data']) ? 0 : array_sum(array_column($ret['data'], 'total_price')),
                'total_stock'   => empty($ret['data']) ? 0 : array_sum(array_column($ret['data'], 'stock')),
                'address'       => empty($address['data']) ? null : $address['data'],
            ];

            // 支付方式
            $payment_list = PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]);

            // 扩展展示数据
            $extension_list = [
                // ['name'=>'感恩节9折', 'tips'=>'-￥23元'],
                // ['name'=>'运费', 'tips'=>'+￥10元'],
            ];

            // 数据返回组装
            $result = [
                'goods_list'                => $ret['data'],
                'payment_list'              => $payment_list,
                'base'                      => $base,
                'extension_list'            => $extension_list,
                'common_order_is_booking'   => (int) MyC('common_order_is_booking', 0),
            ];
            return DataReturn('success', 0, $result);
        }
        return $ret;
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
        $params = $this->data_post;
        $params['user'] = $this->user;
        return BuyService::OrderAdd($params);
    }
}
?>