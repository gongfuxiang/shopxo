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
}
?>