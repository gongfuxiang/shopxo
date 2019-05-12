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

use think\Controller;
use app\service\UserService;
use app\service\PaymentService;
use app\service\PluginsService;
use app\plugins\wallet\service\WalletService;

/**
 * 钱包 - 公共
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Common extends Controller
{
    protected $user;
    protected $user_wallet;
    protected $plugins_base;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();

        // 用户信息
        $this->user = UserService::LoginUserInfo();

        // 登录校验
        if(empty($this->user))
        {
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
            } else {
                return $this->redirect('index/user/logininfo');
            }
        }

        // 发起支付 - 支付方式
        $this->assign('buy_payment_list', PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]));

        // 用户钱包
        $user_wallet = WalletService::UserWallet($this->user['id']);

        // 用户钱包错误信息
        $wallet_error = ($user_wallet['code'] == 0) ? '' : $user_wallet['msg'];
        $this->assign('wallet_error', $wallet_error);

        // 所有ajax请求校验用户钱包状态
        if(IS_AJAX && !empty($wallet_error))
        {
            exit(json_encode(DataReturn($wallet_error, -50)));
        }

        // 用户钱包信息
        $this->user_wallet = $user_wallet['data'];
        $this->assign('user_wallet', $user_wallet['data']);

        // 应用配置
        $plugins_base = PluginsService::PluginsData('wallet');
        $this->plugins_base = $plugins_base['data'];
        $this->assign('plugins_base_data', $this->plugins_base);
    }
}
?>