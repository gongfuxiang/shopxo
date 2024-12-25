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
namespace payment;

use app\service\PayLogService;

/**
 * TrySeePayPay
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class TrySeePayPay
{
    // 插件配置参数
    private $config;

    // url地址
    private $url = 'https://pay.try-see.net';

    /**
     * 构造方法
     * @author   Devil
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
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => 'TrySeePayPay',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5', 'ios', 'android'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => 'TrySeePayPay支持（pc/h5/ios/android），即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="https://www.try-see.net/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mch_id',
                'placeholder'   => '会员店ID',
                'title'         => '会员店ID',
                'is_required'   => 0,
                'message'       => '请填写会员店ID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mch_key',
                'placeholder'   => '会员店秘钥',
                'title'         => '会员店秘钥',
                'is_required'   => 0,
                'message'       => '请填写会员店秘钥',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'rsa_private',
                'placeholder'   => '应用私钥',
                'title'         => '应用私钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写应用私钥',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'rsa_public',
                'placeholder'   => '应用公钥',
                'title'         => '应用公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写应用公钥',
            ],
            [
                'element'       => 'message',
                'message'       => '该支付插件【支付金额和退款金额】必须为整数，系统将自动转为整数去除小数点',
            ],
        ];

        return [
            'base'      => $base,
            'element'   => $element,
        ];
    }

    /**
     * 支付入口
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Pay($params = [])
    {
        // 配置信息
        if(empty($this->config) || empty($this->config['mch_id']) || empty($this->config['mch_key']) || empty($this->config['rsa_public']) || empty($this->config['rsa_private']))
        {
            return DataReturn('支付缺少配置', -1);
        }
        if($params['total_price'] < 1)
        {
            return DataReturn('金额必须大于1', -1);
        }

        // 基础参数
        $channel = 'PAYPAY_WAP';
        $redirect_type = 'WEB_LINK';
        $url_field = 'pay_url';
        // 是否APP
        if(in_array(APPLICATION_CLIENT_TYPE, ['ios', 'android']))
        {
            $channel = 'PAYPAY_MPM';
            $redirect_type = 'APP_DEEP_LINK';

            // app下 ios调起失败，仅android使用
            if(APPLICATION_CLIENT_TYPE == 'android')
            {
                $url_field = 'deeplink';
            }
        }

        // 支付参数
        $join = stripos($params['call_back_url'], '?') === false ? '?' : '&';
        $params['call_back_url'] .= $join.'mch_trade_no='.$params['order_no'];
        $params['name'] = 'goods title';
        $parameter = [
            'mch_trade_no'          => $params['order_no'],
            'channel'               => $channel,
            'pay_amount'            => intval($params['total_price']),
            'currency'              => 'JPY',
            'client_ip'             => GetClientIP(),
            'subject'               => $params['name'],
            'body'                  => $params['name'],
            'redirect_url'          => $params['call_back_url'],
            'notify_url'            => $params['notify_url'],
            'extra'                 => [
                'redirect_type' => $redirect_type,
            ]
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 执行请求
        $ret = $this->HttpRequest('/v2/payment/pay', $parameter);
        if($ret['code'] == 0)
        {
            if(empty($ret['data']['extra']) || empty($ret['data']['extra'][$url_field]))
            {
                return DataReturn('支付地址获取失败', -1);
            }
            MySession('plugins_trysee_pay_data', ['mch_trade_no'=>$params['order_no']]);
            return DataReturn('success', 0, $ret['data']['extra'][$url_field]);
        }
        return $ret;
    }

    /**
     * 支付回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        // 无参数则从session读取
        $params = empty($params['mch_trade_no']) ? MySession('plugins_trysee_pay_data') : $params;
        if(!empty($params['mch_trade_no']))
        {
            // 查询订单状态
            $parameter = [
                'mch_trade_no'  => $params['mch_trade_no'],
            ];
            if(!empty($params['trade_no']))
            {
                $parameter['trade_no'] = $params['trade_no'];
            }
            $ret = $this->HttpRequest('/v2/payment/inquire', $parameter);
            if($ret['code'] == 0)
            {
                if(isset($ret['data']['status']) && $ret['data']['status'] == 'PAID')
                {
                    $ret = DataReturn('支付成功', 0, $this->ReturnData($ret['data']));
                } else {
                    $ret = DataReturn('支付失败', -1);
                }
            }
        } else {
            $ret = DataReturn('处理异常错误', -100);
        }
        return $ret;
    }

    /**
     * 返回数据统一格式
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-06T16:54:24+0800
     * @param    [array]                   $data [返回数据]
     */
    private function ReturnData($data)
    {
        $data['trade_no']       = $data['trade_no'];        // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['wallet']) ? $data['wallet'] : '';              // 支付平台 - 用户
        $data['out_trade_no']   = $data['mch_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['channel']) ? $data['channel'] : '';         // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['pay_amount'];      // 本系统发起支付的 - 总价

        return $data;
    }

    /**
     * 退款处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-28
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Refund($params = [])
    {
        // 参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '订单号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'trade_no',
                'error_msg'         => '交易平台订单号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'refund_price',
                'error_msg'         => '退款金额不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 退款原因
        $refund_reason = $params['order_no'].ChinesePinyin('退款', true, '-').$params['refund_price'];

        // 退款参数
        $parameter = [
            'mch_trade_no'          => $params['order_no'],
            'mch_refund_no'         => $params['order_no'].GetNumberCode(6),
            'refund_amount'         => intval($params['refund_price']),
            'currency'              => 'JPY',
            'reason'                => $refund_reason,
        ];
        $ret = $this->HttpRequest('/v2/payment/refund', $parameter);
        if($ret['code'] == 0 && isset($ret['data']['status']) && $ret['data']['status'] == 'REFUNDED')
        {
            // 统一返回格式
            $data = [
                'out_trade_no'    => isset($ret['data']['mch_trade_no']) ? $ret['data']['mch_trade_no'] : '',
                'trade_no'        => isset($ret['data']['refund_no']) ? $ret['data']['refund_no'] : '',
                'buyer_user'      => '',
                'refund_price'    => isset($ret['data']['refund_amount']) ? $ret['data']['refund_amount'] : 0.00,
                'return_params'   => $ret['data'],
                'request_params'  => $parameter,
            ];
            $ret = DataReturn('退款成功', 0, $data);
        }
        return $ret;
    }

    /**
     * 网络请求
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $query   [请求参数]
     * @param    [array]           $data    [发送数据]
     * @return   [mixed]                    [请求返回数据]
     */
    private function HttpRequest($query, $data)
    {
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);
        $res = $this->SignatureCreate($query, $body);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.$query);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic mch_id='.$this->config['mch_id'].',signature='.$res['sign'].',req_time='.$res['time'],
            'Content-Length: '.strlen($body),
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if(curl_errno($ch))
        {
            return DataReturn(curl_error($ch), -1);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode)
            {
                return DataReturn('http错误('.$httpStatusCode.')', -1);
            }
        }
        curl_close($ch);
        $res = json_decode($reponse, true);
        if(empty($res) || !isset($res['code']))
        {
            return DataReturn('返回数据错误('.$reponse.')', -1);
        }
        if($res['code'] != 20000)
        {
            return DataReturn($res['msg'].'('.$res['code'].')', -1);
        }
        return DataReturn('success', 0, $res['data']);
    }

    /**
     * 签名生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-12-31
     * @desc    description
     * @param   [string]          $query [请求接口地址]
     * @param   [string]          $body  [请求数据]
     */
    private function SignatureCreate($query, $body)
    {
        $time = time();
        $param_arr = ['POST', $query, $this->config['mch_id'], $time, $body, $this->config['mch_key']];
        return [
            'sign'  => $this->MyRsaSign(implode("\n", $param_arr)),
            'time'  => $time,
        ];
    }

    /**
     * 签名字符串
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:38:28+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @return   [string]                           [签名结果]
     */
    private function MyRsaSign($prestr)
    {
        if(stripos($this->config['rsa_private'], '-----') === false)
        {
            $res = "-----BEGIN RSA PRIVATE KEY-----\n";
            $res .= wordwrap($this->config['rsa_private'], 64, "\n", true);
            $res .= "\n-----END RSA PRIVATE KEY-----";
        } else {
            $res = $this->config['rsa_private'];
        }
        return openssl_sign($prestr, $sign, $res, OPENSSL_ALGO_SHA256) ? urlencode(base64_encode($sign)) : null;
    }

    /**
     * 自定义成功返回内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-01
     * @desc    description
     */
    public function SuccessReturn()
    {
        return '{"result":"success"}';
    }

    /**
     * 自定义失败返回内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-01
     * @desc    description
     */
    public function ErrorReturn()
    {
        return '{"result":"error"}';
    }
}
?>