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
namespace app\plugins\wallet\index;

use app\plugins\wallet\service\PayService;

/**
 * 钱包 - 充值异步处理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Rechargenotify
{
    /**
     * 支付异步处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-03-04T14:35:38+0800
     * @param   [array]          $params [输入参数]
     */
    public function notify($params = [])
    {
        $ret = PayService::Notify($params);
        if($ret['code'] == 0)
        {
            exit('success');
        }
        exit('error');
    }
}
?>