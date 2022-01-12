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
namespace payment;

use think\facade\Db;
use app\service\PaymentService;
use app\service\OrderService;
use app\plugins\wallet\service\WalletService;

/**
 * 钱包支付
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-06-16
 * @desc    description
 */
class WalletPay
{
    // 插件配置参数
    private $config;

    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]           $params [输入参数（支付配置参数）]
     */
    public function __construct($params = [])
    {
        $this->config = $params;
    }

    /**
     * 配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-16
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => '钱包支付',  // 插件名称
            'version'       => '0.0.3',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'desc'          => '钱包余额支付',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [];

        return [
            'base'      => $base,
            'element'   => $element,
        ];
    }

    /**
     * 钱包校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-16T17:17:58+0800
     * @param    [array]           $params [输入参数]
     */
    private function Check($params = [])
    {
        // 钱包校验
        $wallet = Db::name('Plugins')->where(['plugins'=>'wallet'])->find();
        if(empty($wallet))
        {
            return DataReturn('请先安装钱包插件[ Wallet ]', -1);
        }
        return DataReturn('钱包正常', 0);
    }

    /**
     * 支付入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Pay($params = [])
    {
        // 校验
        $ret = $this->Check($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 获取用户钱包校验
        $user_wallet = WalletService::UserWallet($params['user']['id']);
        if($user_wallet['code'] != 0)
        {
            return $user_wallet;
        }

        // 余额校验
        if($user_wallet['data']['normal_money'] < $params['total_price'])
        {
            return DataReturn('钱包余额不足['.$user_wallet['data']['normal_money'].'元]', -10);
        }

        // 处理支付
        $ret = WalletService::UserWalletMoneyUpdate($params['user']['id'], $params['total_price'], 0, 'normal_money', 3, '钱包余额支付[订单'.$params['order_no'].']');
        if($ret['code'] == 0)
        {
            // 支付方式
            $payment = PaymentService::PaymentList(['where'=>['payment'=>'WalletPay']]);

            // 获取订单日志信息
            $pay_log_data = Db::name('PayLog')->find($params['order_id']);
            if(empty($pay_log_data))
            {
                return DataReturn('日志订单有误', -1);
            }

            // 获取关联信息
            $pay_log_value = Db::name('PayLogValue')->where(['pay_log_id'=>$pay_log_data['id']])->column('business_id');
            if(empty($pay_log_value))
            {
                return DataReturn('日志订单关联信息有误', -1);
            }

            // 获取订单
            $order_list = Db::name('Order')->where(['id'=>$pay_log_value, 'status'=>1])->select()->toArray();
            if(empty($order_list))
            {
                return DataReturn('订单信息有误', -1);
            }

            // 订单数量是否一致
            if(count($order_list) != count($pay_log_value))
            {
                return DataReturn('订单与日志记录数量不一致', -1);
            }

            // 支付处理
            $pay_params = [
                'order'         => $order_list,
                'payment'       => $payment[0],
                'pay_log_data'  => $pay_log_data,
                'pay'       => [
                    'trade_no'      => '钱包支付',
                    'subject'       => $pay_log_data['subject'],
                    'buyer_user'    => (empty($params['user']) || empty($params['user']['user_name_view'])) ? '' : $params['user']['user_name_view'],
                    'pay_price'     => $pay_log_data['total_price'],
                ],
            ];
            $ret = OrderService::OrderPayHandle($pay_params);
            if($ret['code'] == 0)
            {
                return DataReturn('支付成功', 0, MyUrl('index/order/respond', ['appoint_status'=>0]));
            }
        }
        return $ret;
    }

    /**
     * 支付回调处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        return DataReturn('处理成功', 0, $params);
    }

    /**
     * 退款处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Refund($params = [])
    {
        return DataReturn('请选择退至钱包', -1);
    }
}
?>