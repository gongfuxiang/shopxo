<?php

namespace Api\Controller;

use Service\ResourcesService;
use Service\BuyService;
use Service\UserService;

/**
 * 购买确认
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class BuyController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 登录校验
        $this->Is_Login();
    }

    /**
     * 购买确认
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-20
     * @desc    description
     */
    public function Index()
    {
        // 获取商品列表
        $params = $_POST;
        $params['user'] = $this->user;
        $ret = BuyService::BuyTypeGoodsList($params);

        // 商品校验
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 商品/基础信息
            $base = [
                'total_price'   => empty($ret['data']) ? 0 : array_sum(array_column($ret['data'], 'total_price')),
                'total_stock'   => empty($ret['data']) ? 0 : array_sum(array_column($ret['data'], 'stock')),
                'address'       => UserService::UserDefaultAddress(['user'=>$this->user])['data'],
            ];

            // 支付方式
            $payment_list = ResourcesService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]);

            // 扩展展示数据
            $extension_list = [
                // ['name'=>'感恩节9折', 'tips'=>'-￥23元'],
                // ['name'=>'运费', 'tips'=>'+￥10元'],
            ];

            // 数据返回组装
            $result = [
                'goods_list'            => $ret['data'],
                'payment_list'          => $payment_list,
                'base'                  => $base,
                'extension_list'        => $extension_list,
            ];
            $this->ajaxReturn(L('common_operation_success'), 0, $result);
        } else {
            $this->ajaxReturn(isset($ret['msg']) ? $ret['msg'] : L('common_param_error'), -100);
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
        $params = $_POST;
        $params['user'] = $this->user;
        $ret = BuyService::OrderAdd($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>