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
namespace app\index\controller;

/**
 * 支付
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Pay extends Common
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
    }

    /**
     * 二维码支付展示
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Qrcode()
    {
        $pay_data = MySession('payment_qrcode_data');
        if(empty($pay_data) || empty($pay_data['url']) || empty($pay_data['check_url']) || empty($pay_data['order_no']) || empty($pay_data['name']) || empty($pay_data['msg']))
        {
            return MyView('public/tips_error', ['msg'=>MyLang('params_error_tips')]);
        }
        MyViewAssign('pay_data', $pay_data);
        return MyView();
    }
}
?>