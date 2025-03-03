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
use app\service\PayLogService;
use app\service\PayRequestLogService;
use app\plugins\wallet\service\WalletService;
use app\plugins\scanpay\service\ScanpayLogService;
use app\plugins\membershiplevelvip\service\PayService as LevelPayService;
use app\plugins\givegift\service\PayService as GiftPayService;

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
            'version'       => '0.0.5',  // 插件版本
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
        // 根据业务类型
        $business_type = empty($params['business_type']) ? 'system-order' : $params['business_type'];

        // 支付请求日志添加
        $log_ret = PayRequestLogService::PayRequestLogInsert($business_type);

        // 捕获异常
        try {
            // 校验
            $ret = $this->Check($params);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 是否登录用户
            if(empty($params['user']) || empty($params['user']['id']))
            {
                throw new \Exception('请先登录后再使用钱包支付！', -1);
            }

            // 获取用户钱包校验
            $user_wallet = WalletService::UserWallet($params['user']['id']);
            if($user_wallet['code'] != 0)
            {
                throw new \Exception($user_wallet['msg']);
            }

            // 余额校验
            if($user_wallet['data']['normal_money'] < $params['total_price'])
            {
                throw new \Exception('钱包余额不足['.$user_wallet['data']['normal_money'].'元]', -10);
            }

            // 支付方式
            $payment = PaymentService::PaymentList(['where'=>['payment'=>'WalletPay']]);

            // 获取订单日志信息
            $pay_log_data = Db::name('PayLog')->find($params['order_id']);
            if(empty($pay_log_data))
            {
                throw new \Exception('日志订单有误', -1);
            }

            // 获取关联信息
            $pay_log_value = Db::name('PayLogValue')->where(['pay_log_id'=>$pay_log_data['id']])->column('business_id');
            if(empty($pay_log_value))
            {
                throw new \Exception('日志订单关联信息有误', -1);
            }

            // 获取对应订单信息
            $order_list = [];
            switch($business_type)
            {
                // 系统订单
                case 'system-order' :
                    $order_list = Db::name('Order')->where(['id'=>$pay_log_value, 'status'=>1])->select()->toArray();
                    break;

                // 扫码收款
                case 'plugins-scanpay' :
                    $order_list = Db::name('PluginsScanpayLog')->where(['id'=>$pay_log_value, 'status'=>0])->select()->toArray();
                    break;

                // 会员等级
                case 'plugins-membershiplevelvip' :
                    $order_list = Db::name('PluginsMembershiplevelvipPaymentUserOrder')->where(['id'=>$pay_log_value, 'status'=>0])->select()->toArray();
                    break;

                // 送礼
                case 'plugins-givegift' :
                    $order_list = Db::name('PluginsGivegiftOrder')->where(['id'=>$pay_log_value, 'status'=>0])->select()->toArray();
                    break;
            }
            if(empty($order_list))
            {
                throw new \Exception('订单信息有误', -1);
            }

            // 订单数量是否一致
            if(count($order_list) != count($pay_log_value))
            {
                throw new \Exception('订单与日志记录数量不一致', -1);
            }
            $ret = DataReturn('success', 0);
        } catch(\Exception $e) {
            $ret = DataReturn($e->getMessage(), -1);
        }

        // 处理支付
        if($ret['code'] == 0)
        {
            // 支付处理
            $parameter = [
                'order'         => $order_list,
                'payment'       => $payment[0],
                'pay_log_data'  => $pay_log_data,
                'pay'           => [
                    'trade_no'      => 'wallet',
                    'subject'       => $pay_log_data['subject'],
                    'buyer_user'    => (empty($params['user']) || empty($params['user']['user_name_view'])) ? '' : $params['user']['user_name_view'],
                    'pay_price'     => $pay_log_data['total_price'],
                ],
            ];

            // 支付请求记录
            PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

            // 处理支付
            $ret = WalletService::UserWalletMoneyUpdate($params['user']['id'], $params['total_price'], 0, 'normal_money', 3, $params['name'].'[订单'.$params['order_no'].']', ['is_consistent'=>1]);
            if($ret['code'] == 0)
            {
                // 调用支付处理方法
                switch($business_type)
                {
                    // 系统订单
                    case 'system-order' :
                        $ret = OrderService::OrderPayHandle($parameter);
                        if($ret['code'] == 0)
                        {
                            $ret = DataReturn('支付成功', 0, MyUrl('index/order/respond', ['appoint_status'=>0]));
                        }
                        break;

                    // 扫码收款
                    case 'plugins-scanpay' :
                        $parameter['order'] = $parameter['order'][0];
                        $ret = ScanpayLogService::ScanpayLogHandle($parameter);
                        if($ret['code'] == 0)
                        {
                            $ret = DataReturn('支付成功', 0, PluginsHomeUrl('scanpay', 'index', 'respond'));
                        }
                        break;

                    // 会员购买
                    case 'plugins-membershiplevelvip' :
                        $parameter['order'] = $parameter['order'][0];
                        $ret = LevelPayService::LevelPayHandle($parameter);
                        if($ret['code'] == 0)
                        {
                            $ret = DataReturn('支付成功', 0, PluginsHomeUrl('membershiplevelvip', 'buy', 'respond', ['appoint_status'=>0]));
                        }
                        break;

                    // 送礼
                    case 'plugins-givegift' :
                        $parameter['order'] = $parameter['order'][0];
                        $ret = GiftPayService::GiftPayHandle($parameter);
                        if($ret['code'] == 0)
                        {
                            $ret = DataReturn('支付成功', 0, PluginsHomeUrl('givegift', 'gift', 'respond', ['appoint_status'=>0]));
                        }
                        break;

                    // 默认
                    default :
                        $ret = DataReturn('支付业务未处理('.$business_type.')', -1);
                        break;
                }
            }
        }

        // 支付响应日志
        PayRequestLogService::PayRequestLogEnd($log_ret['data'], $ret, $ret['msg']);

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