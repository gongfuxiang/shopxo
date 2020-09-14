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
use app\service\PluginsService;

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
        $params = $this->data_post;
        $params['user'] = $this->user;
        $buy_ret = BuyService::BuyTypeGoodsList($params);

        // 商品校验
        if(isset($buy_ret['code']) && $buy_ret['code'] == 0)
        {
            // 基础信息
            $buy_base = $buy_ret['data']['base'];
            $buy_goods = $buy_ret['data']['goods'];

            // 支付方式
            $payment_list = PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]);

            // 数据返回组装
            $result = [
                'goods_list'                => $buy_goods,
                'payment_list'              => $payment_list,
                'base'                      => $buy_base,
                'common_site_type'          => (int) $buy_base['common_site_type'],
            ];

            // 优惠劵
            $ret = PluginsService::PluginsControlCall('coupon', 'coupon', 'buy', 'api', ['order_goods'=>$buy_goods, 'params'=>$params]);
            if($ret['code'] == 0 && isset($ret['data']['code']) && $ret['data']['code'] == 0)
            {
                $result['plugins_coupon_data'] = $ret['data']['data'];
            }

            return DataReturn('操作成功', 0, $result);
        }
        return $buy_ret;
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
        return BuyService::OrderInsert($params);
    }
}
?>