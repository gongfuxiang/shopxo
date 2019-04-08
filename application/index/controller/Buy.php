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
namespace app\index\controller;

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
        if(input('post.'))
        {
            session('buy_post_data', $_POST);
            return redirect(MyUrl('index/buy/index'));
        } else {
            // 获取商品列表
            $params = array_merge(input(), session('buy_post_data'));
            $params['user'] = $this->user;
            $ret = BuyService::BuyTypeGoodsList($params);

            // 商品校验
            if(isset($ret['code']) && $ret['code'] == 0)
            {
                // 用户地址
                $address = UserService::UserAddressList(['user'=>$this->user]);
                $this->assign('user_address_list', $address['data']);

                // 支付方式
                $this->assign('payment_list', PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]));
                
                $this->assign('base', $ret['data']['base']);
                $this->assign('goods_list', $ret['data']['goods']);
                $this->assign('extension_data', $ret['data']['extension_data']);
                $this->assign('params', $params);
                return $this->fetch();
            } else {
                $this->assign('msg', isset($ret['msg']) ? $ret['msg'] : '参数错误');
                return $this->fetch('public/tips_error');
            }
        }
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
        if(input('post.'))
        {
            $params = $_POST;
            $params['user'] = $this->user;
            return BuyService::OrderAdd($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
    }
}
?>