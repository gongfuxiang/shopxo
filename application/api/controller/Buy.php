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
        $ret = BuyService::BuyTypeGoodsList($params);

        // 商品校验
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 支付方式
            $payment_list = PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]);

            // 当前选中的优惠劵
            $coupon_id = isset($params['coupon_id']) ? intval($params['coupon_id']) : 0;

            // 数据返回组装
            $result = [
                'goods_list'                => $ret['data']['goods'],
                'payment_list'              => $payment_list,
                'base'                      => $ret['data']['base'],
                'extension_data'            => $ret['data']['extension_data'],
                'common_order_is_booking'   => (int) MyC('common_order_is_booking', 0),
                'common_site_type'          => (int) MyC('common_site_type', 0, true),
            ];

            // 优惠劵
            $ret = PluginsService::PluginsControlCall(
                    'coupon', 'coupon', 'buy', 'api', ['order_goods'=>$ret['data']['goods'], 'coupon_id'=>$coupon_id]);
            if($ret['code'] == 0 && isset($ret['data']['code']) && $ret['data']['code'] == 0)
            {
                $result['plugins_coupon_data'] = $ret['data']['data'];
            }

            return DataReturn('操作成功', 0, $result);
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
        return BuyService::OrderInsert($params);
    }
}
?>