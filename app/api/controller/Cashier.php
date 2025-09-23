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

use app\service\ApiService;
use app\service\CashierService;

/**
 * 收银台
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2022-08-23
 * @desc    description
 */
class Cashier extends Common
{
    /**
     * 支付数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-23
     * @desc    description
     */
    public function PayData()
    {
        return ApiService::ApiDataReturn(CashierService::PayData($this->data_request));
    }
}
?>