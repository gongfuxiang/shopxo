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

use app\service\CrontabService;

/**
 * 定时任务
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2019-08-18T17:19:33+0800
 */
class Crontab extends Common
{
    /**
     * 订单关闭
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T17:19:33+0800
     */
    public function OrderClose()
    {
        $ret = CrontabService::OrderClose();
        return 'sucs:'.$ret['data']['sucs'].', fail:'.$ret['data']['fail'];
    }

    /**
     * 订单收货
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T17:19:33+0800
     */
    public function OrderSuccess()
    {
        $ret = CrontabService::OrderSuccess();
        return 'sucs:'.$ret['data']['sucs'].', fail:'.$ret['data']['fail'];
    }

    /**
     * 支付日志订单关闭
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T17:19:33+0800
     */
    public function PayLogOrderClose()
    {
        $ret = CrontabService::PayLogOrderClose();
        return 'count:'.$ret['data'];
    }

    /**
     * 商品赠送积分
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T17:19:33+0800
     */
    public function GoodsGiveIntegral()
    {
        $ret = CrontabService::GoodsGiveIntegral();
        if($ret['code'] == 0)
        {
            return 'sucs:'.$ret['data']['sucs'].', fail:'.$ret['data']['fail'];
        }
        return $ret['msg'];
    }
}
?>