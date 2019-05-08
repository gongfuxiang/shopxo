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
namespace app\plugins\wallet\service;

use think\Db;
use app\service\PaymentService;
use app\service\PayLogService;
use app\service\MessageService;
use app\service\PluginsService;
use app\plugins\wallet\service\WalletService;
use app\plugins\wallet\service\RechargeService;

/**
 * 支付服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PayService
{
    /**
     * 支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-29
     * @desc    description
     * @param   array           $params [description]
     */
    public static function Pay($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'recharge_id',
                'error_msg'         => '充值日志id不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'payment_id',
                'error_msg'         => '请选择支付方式',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 支付方式;
        $payment = PaymentService::PaymentList(['where'=>['id'=>intval($params['payment_id']), 'is_enable'=>1, 'is_open_user'=>1]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付入口文件检查
        $pay_checked = PaymentService::EntranceFileChecked($payment[0]['payment'], 'wallet');
        if($pay_checked['code'] != 0)
        {
            // 入口文件不存在则创建
            $payment_params = [
                'payment'       => $payment[0]['payment'],
                'business'      => [
                    ['name' => 'Wallet', 'desc' => '钱包'],
                ],
                'respond'       => '/index/plugins/index/pluginsname/wallet/pluginscontrol/recharge/pluginsaction/respond',
                'notify'        => '/index/plugins/index/pluginsname/wallet/pluginscontrol/rechargenotify/pluginsaction/notify',
            ];
            $ret = PaymentService::PaymentEntranceCreated($payment_params);
            if($ret['code'] != 0)
            {
                return $ret;
            }
        }

        // 非线上支付方式不可用
        if(in_array($payment[0]['payment'], config('shopxo.under_line_list')))
        {
            return DataReturn('不能使用非线上支付方式进行充值', -10);
        }

        // 获取充值数据
        $recharge = Db::name('PluginsWalletRecharge')->where(['id'=>intval($params['recharge_id'])])->find();
        if(empty($recharge))
        {
            return DataReturn('充值数据不存在', -1);
        }
        if($recharge['status'] == 1)
        {
            return DataReturn('该数据已充值，请重新创建充值订单', -2);
        }

        // 回调地址
        $url = __MY_URL__.'payment_wallet_'.strtolower($payment[0]['payment']);

        // url模式, pathinfo模式下采用自带url生成url, 避免非index.php多余
        if(MyC('home_seo_url_model', 0) == 0)
        {
            $call_back_url = $url.'_respond.php';
        } else {
            $call_back_url = PluginsHomeUrl('wallet', 'recharge', 'respond', ['paymentname'=>$payment[0]['payment']]);
            if(stripos($call_back_url, '?') !== false)
            {
                $call_back_url = $url.'_respond.php';
            }
        }

        // 发起支付
        $pay_data = array(
            'user'          => $params['user'],
            'out_user'      => md5($params['user']['id']),
            'order_id'      => $recharge['id'],
            'order_no'      => $recharge['recharge_no'],
            'name'          => '账户充值',
            'total_price'   => $recharge['money'],
            'notify_url'    => $url.'_notify.php',
            'call_back_url' => $call_back_url,
            'site_name'     => MyC('home_site_name', 'ShopXO', true),
            'ajax_url'      => PluginsHomeUrl('wallet', 'recharge', 'paycheck')
        );
        $pay_name = 'payment\\'.$payment[0]['payment'];
        $ret = (new $pay_name($payment[0]['config']))->Pay($pay_data);
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            return $ret;
        }
        return DataReturn(empty($ret['msg']) ? '支付接口异常' : $ret['msg'], -1);
    }

    /**
     * 支付状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-08
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function RechargePayCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '充值单号有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单状态
        $where = ['recharge_no'=>$params['order_no'], 'user_id'=>$params['user']['id']];
        $recharge = Db::name('PluginsWalletRecharge')->where($where)->field('id,status')->find();
        if(empty($recharge))
        {
            return DataReturn('充值数据不存在', -400, ['url'=>__MY_URL__]);
        }
        if($recharge['status'] == 1)
        {
            return DataReturn('支付成功', 0, ['url'=>PluginsHomeUrl('wallet', 'recharge', 'index')]);
        }
        return DataReturn('支付中', -300);
    }

    /**
     * 支付同步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Respond($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 支付方式
        $payment_name = defined('PAYMENT_TYPE') ? PAYMENT_TYPE : (isset($params['paymentname']) ? $params['paymentname'] : '');
        if(empty($payment_name))
        {
            return DataReturn('支付方式标记异常', -1);
        }
        $payment = PaymentService::PaymentList(['where'=>['payment'=>$payment_name]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = 'payment\\'.$payment_name;
        $ret = (new $pay_name($payment[0]['config']))->Respond(array_merge($_GET, $_POST));
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            return DataReturn('支付成功', 0);
        }
        return DataReturn(empty($ret['msg']) ? '支付失败' : $ret['msg'], -100);
    }

    /**
     * 支付异步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Notify($params = [])
    {
        // 支付方式
        $payment = PaymentService::PaymentList(['where'=>['payment'=>PAYMENT_TYPE]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = 'payment\\'.PAYMENT_TYPE;
        $ret = (new $pay_name($payment[0]['config']))->Respond(array_merge($_GET, $_POST));
        if(!isset($ret['code']) || $ret['code'] != 0)
        {
            return $ret;
        }

        // 获取充值信息
        $recharge = Db::name('PluginsWalletRecharge')->where(['recharge_no'=>$ret['data']['out_trade_no']])->find();

        // 支付处理
        $pay_params = [
            'recharge'  => $recharge,
            'payment'   => $payment[0],
            'pay'       => [
                'trade_no'      => $ret['data']['trade_no'],
                'subject'       => $ret['data']['subject'],
                'buyer_user'    => $ret['data']['buyer_user'],
                'pay_price'     => $ret['data']['pay_price'],
            ],
        ];
        return self::RechargePayHandle($pay_params);
    }

    /**
     * 充值支付处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-05T23:02:14+0800
     * @param   [array]          $params [输入参数]
     */
    private static function RechargePayHandle($params = [])
    {
        // 订单信息
        if(empty($params['recharge']))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if($params['recharge']['status'] > 0)
        {
            $status_text = RechargeService::$recharge_status_list[$params['recharge']['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', 0);
        }

        // 支付方式
        if(empty($params['payment']))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付金额
        $pay_price = isset($params['pay']['pay_price']) ? $params['pay']['pay_price'] : 0;

        // 写入支付日志
        $pay_log_data = [
            'user_id'       => $params['recharge']['user_id'],
            'order_id'      => $params['recharge']['id'],
            'total_price'   => $params['recharge']['money'],
            'trade_no'      => isset($params['pay']['trade_no']) ? $params['pay']['trade_no'] : '',
            'buyer_user'    => isset($params['pay']['buyer_user']) ? $params['pay']['buyer_user'] : '',
            'pay_price'     => $pay_price,
            'subject'       => isset($params['pay']['subject']) ? $params['pay']['subject'] : '账户充值',
            'payment'       => $params['payment']['payment'],
            'payment_name'  => $params['payment']['name'],
            'business_type' => 2,
        ];
        PayLogService::PayLogInsert($pay_log_data);

        // 获取用户钱包校验
        $user_wallet = WalletService::UserWallet($params['recharge']['user_id']);
        if($user_wallet['code'] != 0)
        {
            return $user_wallet;
        } else {
            if($user_wallet['data']['id'] != $params['recharge']['wallet_id'])
            {
                return DataReturn('用户钱包不匹配', -1);
            }
        }

        // 开启事务
        Db::startTrans();

        // 更新充值状态
        $upd_data = array(
            'status'        => 1,
            'pay_money'     => $pay_price,
            'payment_id'    => $params['payment']['id'],
            'payment'       => $params['payment']['payment'],
            'payment_name'  => $params['payment']['name'],
            'pay_time'      => time(),
        );
        if(!Db::name('PluginsWalletRecharge')->where(['id'=>$params['recharge']['id']])->update($upd_data))
        {
            Db::rollback();
            return DataReturn('充值状态更新失败', -100);
        }

        // 是否有赠送金额
        $give_money = self::RechargeGiveMoneyHandle($pay_price);

        // 字段名称 金额类型 描述
        if($give_money > 0)
        {
            $money_field = [
                ['field' => 'normal_money', 'money_type' => 0, 'msg' => ' [ '.$pay_price.'元 , 赠送'.$give_money.'元 ]'],
                ['field' => 'give_money', 'money_type' => 2, 'msg' => ' [ 赠送'.$give_money.'元 ]'],
            ];
        } else {
            $money_field = [
                ['field' => 'normal_money', 'money_type' => 0, 'msg' => ' [ '.$pay_price.'元 ]'],
            ];
        }

        // 钱包更新数据
        $data = [
            'normal_money'      => PriceNumberFormat($user_wallet['data']['normal_money']+$pay_price+$give_money),
            'give_money'        => PriceNumberFormat($user_wallet['data']['give_money']+$give_money),
            'upd_time'          => time(),
        ];
        if(!Db::name('PluginsWallet')->where(['id'=>$user_wallet['data']['id']])->update($data))
        {
            Db::rollback();
            return DataReturn('钱包更新失败', -10);
        }

        // 有效金额和赠送金额字段数据处理
        foreach($money_field as $v)
        {
            // 有效金额
            if($user_wallet['data'][$v['field']] != $data[$v['field']])
            {
                $log_data = [
                    'user_id'           => $user_wallet['data']['user_id'],
                    'wallet_id'         => $user_wallet['data']['id'],
                    'business_type'     => 1,
                    'operation_type'    => 1,
                    'money_type'        => $v['money_type'],
                    'operation_money'   => ($user_wallet['data'][$v['field']] < $data[$v['field']]) ? PriceNumberFormat($data[$v['field']]-$user_wallet['data'][$v['field']]) : PriceNumberFormat($user_wallet['data'][$v['field']]-$data[$v['field']]),
                    'original_money'    => $user_wallet['data'][$v['field']],
                    'latest_money'      => $data[$v['field']],
                    'msg'               => '账户充值'.$v['msg'],
                ];
                if(!WalletService::WalletLogInsert($log_data))
                {
                    Db::rollback();
                    return DataReturn('日志添加失败', -101);
                }

                // 消息通知
                MessageService::MessageAdd($params['recharge']['user_id'], '账户余额变动', $log_data['msg'], 2, $params['recharge']['id']);
            }
        }

        // 提交事务
        Db::commit();
        return DataReturn('支付成功', 0);        
    }

    /**
     * 充值赠送金额计算
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-08T00:12:48+0800
     * @param    [float]                   $pay_price [支付金额]
     */
    private static function RechargeGiveMoneyHandle($pay_price)
    {
        $give_money = 0.00;
        $ret = PluginsService::PluginsData('wallet', '', false);
        if(!empty($ret['data']['recharge_give_value']) && isset($ret['data']['recharge_give_type']))
        {
            switch($ret['data']['recharge_give_type'])
            {
                // 固定金额
                case 0 :
                    $give_money = $ret['data']['recharge_give_value'];
                    break;

                // 比例
                case 1 :
                    $give_money = ($ret['data']['recharge_give_value']/100)*$pay_price;
                    break;
            }
        }
        return PriceNumberFormat($give_money);
    }
}
?>