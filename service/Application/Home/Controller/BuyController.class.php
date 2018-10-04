<?php

namespace Home\Controller;

use Service\GoodsService;
use Service\UserService;
use Service\ResourcesService;
use Service\BuyService;

/**
 * 购买
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BuyController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

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
        if(IS_POST)
        {
            // 获取商品列表
            $params = $_POST;
            $params['user'] = $this->user;
            $ret = BuyService::BuyTypeGoodsList($params);

            // 商品校验
            if(isset($ret['code']) && $ret['code'] == 0)
            {
                // 用户地址
                $this->assign('user_address_list', UserService::UserAddressList(['user'=>$this->user])['data']);

                // 快递
                $this->assign('express_list', ResourcesService::ExpressList(['is_enable'=>1, 'is_open_user'=>1]));

                // 支付方式
                $this->assign('payment_list', ResourcesService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]));
                
                // 商品/基础信息
                $base = [
                    'total_price'   => empty($ret['data']) ? 0 : array_sum(array_column($ret['data'], 'total_price')),
                    'total_stock'   => empty($ret['data']) ? 0 : array_sum(array_column($ret['data'], 'stock')),
                    'address'       => UserService::UserDefaultAddress(['user'=>$this->user])['data'],
                ];
                $this->assign('base', $base);
                $this->assign('goods_list', $ret['data']);
                
                $this->assign('params', $params);
                $this->display('Index');
            } else {
                $this->assign('msg', isset($ret['msg']) ? $ret['msg'] : L('common_param_error'));
                $this->display('/Public/TipsError');
            }
        } else {
            $this->assign('msg', L('common_unauthorized_access'));
            $this->display('/Public/TipsError');
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
        if(IS_POST)
        {
            $params = $_POST;
            $params['user'] = $this->user;
            $ret = BuyService::OrderAdd($params);
            $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
        } else {
            $this->assign('msg', L('common_unauthorized_access'));
            $this->display('/Public/TipsError');
        }
    }
}
?>