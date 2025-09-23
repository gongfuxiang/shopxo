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
namespace app\service;

use app\service\AppMiniUserService;
use app\service\PaymentService;

/**
 * 收银台服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-04
 * @desc    description
 */
class CashierService
{
    /**
     * 支付数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-01-02
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PayData($params = [])
    {
        // 配置参数验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'authcode',
                'error_msg'         => MyLang('common_service.appminiuser.auth_code_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否安装
        $class_path = '\payment\WeixinAppMini';
        if(!class_exists($class_path))
        {
            return DataReturn(MyLang('common_service.payment.not_install_weixin_payment_tips'), -1);
        }

        // 授权获取openid
        $appid = AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appid');
        $appsecret = AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appsecret');
        $ret = (new \base\Wechat($appid, $appsecret))->GetAuthSessionKey($params);
        if($ret['code'] == 0)
        {
            $payment = PaymentService::PaymentData(['where'=>['payment'=>'WeixinAppMini']]);
            $config = (empty($payment) || empty($payment['config'])) ? [] : $payment['config'];
            $params['weixin_openid'] = $ret['data']['openid'];
            $ret = (new $class_path($config))->Pay($params);
        }
        return $ret;
    }
}
?>