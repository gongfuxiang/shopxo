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

/**
 * 优惠劵
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Coupon extends Common
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
     * 优惠劵首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-15
     * @desc    description
     */
    public function Index()
    {
        // 获取基础配置信息
        $base = CallPluginsData('coupon');

        // 优惠劵列表
        $coupon_params = [
            'where'             => [
                'is_enable'         => 1,
                'is_user_receive'   => 1,
            ],
            'm'                 => 0,
            'n'                 => 0,
            'is_sure_receive'   => 1,
            'user'              => $this->user,
        ];
        $ret = CallPluginsServiceMethod('coupon', 'CouponService', 'CouponList', $coupon_params);

        // 返回数据
        $result = [
            'base'  => $base['data'],
            'data'  => $ret['data'],
        ];
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 用户优惠劵列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-15
     * @desc    description
     */
    public function User()
    {
        // 是否登录
        $this->IsLogin();

        // 获取用户优惠劵
        $coupon_params = [
            'user'  => $this->user,
            'where' => [
                'user_id'   => $this->user['id'],
                'is_valid'  => 1,
            ],
        ];
        return CallPluginsServiceMethod('coupon', 'UserCouponService', 'CouponUserList', $coupon_params);
    }

    /**
     * 领取优惠劵
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-15
     * @desc    description
     */
    public function Receive()
    {
        // 是否登录
        $this->IsLogin();

        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 领取优惠劵
        return CallPluginsServiceMethod('coupon', 'CouponService', 'UserReceiveCoupon', $this->data_post);
    }
}
?>