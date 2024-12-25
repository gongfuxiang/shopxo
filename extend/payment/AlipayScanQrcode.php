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
 * 支付宝扫码支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class AlipayScanQrcode
{
    // 插件配置参数
    private $config;

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
            'name'          => '支付宝扫码支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '支付宝扫码支付、适用web端，一般用于线下扫码枪或手机对准用户付款码扫码完成支付，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金，（不要对用户开放、该插件不适用于在线支付）。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'appid',
                'placeholder'   => 'appid',
                'title'         => 'appid',
                'is_required'   => 0,
                'message'       => '请填写应用appid',
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
                'name'          => 'out_rsa_public',
                'placeholder'   => '支付宝公钥',
                'title'         => '支付宝公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写支付宝公钥',
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
        // openssl
        if(!function_exists('openssl_sign'))
        {
            return DataReturn('当前环境不支持openssl', -1);
        }
        // 参数
        if(empty($params))
        {
            return DataReturn('参数不能为空', -1);
        }
        // 付款码
        if(empty($params['params']) || empty($params['params']['payment_code']))
        {
            return DataReturn('付款码为空', -1);
        }
        // 配置信息
        if(empty($this->config) || empty($this->config['appid']) || empty($this->config['rsa_public']) || empty($this->config['rsa_private']) || empty($this->config['out_rsa_public']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 支付参数
        $parameter = array(
            'app_id'                => $this->config['appid'],
            'method'                => 'alipay.trade.pay',
            'format'                => 'JSON',
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
            'notify_url'            => $params['notify_url'],
        );
        $biz_content = array(
            'subject'                   => $params['name'],
            'out_trade_no'              => $params['order_no'],
            'total_amount'              => (string) $params['total_price'],
            'scene'                     => 'bar_code',
            'auth_code'                 => $params['params']['payment_code'],
            'qr_code_timeout_express'   => $this->OrderAutoCloseTime(),
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $sp = $this->GetParamSign($parameter);
        $parameter['sign'] = $this->MyRsaSign($sp['value']);

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 执行请求
        $res = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $parameter);
        if($res['code'] != 0)
        {
            return $res;
        }
        $key = str_replace('.', '_', $parameter['method']).'_response';
        $result = $res['data'][$key];

        // 状态
        if(isset($result['code']))
        {
            // 验证签名
            if(!$this->SyncRsaVerify($result, $res['data']['sign']))
            {
                return DataReturn('签名验证错误', -1);
            }

            // 支付提示信息
            $msg = empty($result['sub_msg']) ? (empty($result['msg']) ? '支付失败' : $result['msg']) : $result['sub_msg'];

            // 是否支付成功
            if($result['code'] == 10000)
            {
                return DataReturn('支付成功', 0, $this->ReturnData($result));
            } else {
                // 是否需要等待用户处理
                if($result['code'] == 10003)
                {
                    return DataReturn('等待用户支付中...', -9999, [
                        'is_await'  => 1,
                        'payment'   => substr(self::class, strrpos(self::class, '\\') + 1),
                        'order_no'  => $params['order_no'],
                    ]);
                }
            }
        }
        $code = empty($result['sub_code']) ? (empty($result['code']) ? 0 : $result['code']) : $result['sub_code'];
        return DataReturn($msg.'['.$code.']', -1000);
    }

    /**
     * 支付查询
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Query($params = [])
    {
        // 参数
        if(empty($params))
        {
            return DataReturn('参数不能为空', -1);
        }
        // 配置信息
        if(empty($this->config))
        {
            return DataReturn('支付缺少配置', -1);
        }
        // 订单号
        if(empty($params['order_no']))
        {
            return DataReturn('订单号为空', -1);
        }

        // 支付参数
        $parameter = array(
            'app_id'                => $this->config['appid'],
            'method'                => 'alipay.trade.query',
            'format'                => 'JSON',
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
        );
        $biz_content = array(
            'out_trade_no'  => $params['order_no'],
        );
        // 是否撤销操作
        if(isset($params['is_reverse_pay']) && $params['is_reverse_pay'] == 1)
        {
            $parameter['method'] = 'alipay.trade.cancel';
            // 加入业务参数
            $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

            // 生成签名参数+签名
            $sp = $this->GetParamSign($parameter);
            $parameter['sign'] = $this->MyRsaSign($sp['value']);

            // 执行请求
            $res = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $parameter);
            if($res['code'] != 0)
            {
                return $res;
            }
            $key = str_replace('.', '_', $parameter['method']).'_response';
            $result = $res['data'][$key];

            // 验证签名
            if(!$this->SyncRsaVerify($result, $res['data']['sign']))
            {
                return DataReturn('签名验证错误', -1);
            }

            // 状态
            if(isset($result['code']) && $result['code'] == 10000)
            {
                return DataReturn('撤销成功', -55555, ['is_reverse_pay'=>1]);
            }

            // 直接返回支付信息
            $msg = empty($result['sub_msg']) ? (empty($result['msg']) ? '支付失败' : $result['msg']) : $result['sub_msg'];
            $code = empty($result['sub_code']) ? (empty($result['code']) ? '支付失败' : $result['code']) : $result['sub_code'];
            return DataReturn($msg, -1);
        } else {
            // 加入业务参数
            $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

            // 生成签名参数+签名
            $sp = $this->GetParamSign($parameter);
            $parameter['sign'] = $this->MyRsaSign($sp['value']);

            // 执行请求
            $res = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $parameter);
            if($res['code'] != 0)
            {
                return $res;
            }
            $key = str_replace('.', '_', $parameter['method']).'_response';
            $result = $res['data'][$key];

            // 验证签名
            if(!$this->SyncRsaVerify($result, $res['data']['sign']))
            {
                return DataReturn('签名验证错误', -1);
            }

            // 状态
            if(isset($result['code']) && $result['code'] == 10000 && isset($result['trade_status']) && $result['trade_status'] == 'TRADE_SUCCESS')
            {
                return DataReturn('支付成功', 0, $this->ReturnData($result));
            }

            // 直接返回支付信息
            $msg = empty($result['sub_msg']) ? (empty($result['msg']) ? '支付失败' : $result['msg']) : $result['sub_msg'];
            $code = empty($result['sub_code']) ? (empty($result['code']) ? '支付失败' : $result['code']) : $result['sub_code'];
            // 是否等待支付中
            if(isset($result['trade_status']) && $result['trade_status'] == 'WAIT_BUYER_PAY')
            {
                $msg = '等待用户支付中...';
            } else {
                $msg = $msg.'['.$code.']';
            }
            return DataReturn($msg, -1);
        }
    }

    /**
     * 订单自动关闭的时间
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-24
     * @desc    description
     */
    public function OrderAutoCloseTime()
    {
        return intval(MyC('common_order_close_limit_time', 30, true)).'m';
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
        $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);
        ksort($data);

        // 参数字符串
        $sign = '';
        foreach($data AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
            {
                $sign .= "$key=$val&";
            }
        }
        $sign = substr($sign, 0, -1);

        // 签名
        if(!$this->OutRsaVerify($sign, $data['sign']))
        {
            return DataReturn('签名校验失败', -1);
        }

        // 支付状态
        $status = isset($data['trade_status']) ? $data['trade_status'] : $data['result'];
        switch($status)
        {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
            case 'success':
                return DataReturn('支付成功', 0, $this->ReturnData($data));
                break;
        }
        return DataReturn('处理异常错误', -100);
    }

    /**
     * [ReturnData 返回数据统一格式]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-06T16:54:24+0800
     * @param    [array]                   $data [返回数据]
     */
    private function ReturnData($data)
    {
        // 兼容web版本支付参数
        $buyer_user = isset($data['buyer_logon_id']) ? $data['buyer_logon_id'] : (isset($data['buyer_user_id']) ? $data['buyer_user_id'] : '');
        $pay_price = isset($data['buyer_pay_amount']) ? $data['buyer_pay_amount'] : 0;
        $subject = empty($data['subject']) ? '' : $data['subject'];

        // 返回数据固定基础参数
        $data['trade_no']       = $data['trade_no'];        // 支付平台 - 订单号
        $data['buyer_user']     = $buyer_user;              // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = $subject;                 // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $pay_price;               // 本系统发起支付的 - 总价

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
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // 退款参数
        $parameter = [
            'app_id'                => $this->config['appid'],
            'method'                => 'alipay.trade.refund',
            'format'                => 'JSON',
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
        ];
        $biz_content = [
            'out_request_no'        => $params['order_no'].'JE'.str_replace('.', '', $params['refund_price']),
            'out_trade_no'          => $params['order_no'],
            'trade_no'              => $params['trade_no'],
            'refund_amount'         => (string) $params['refund_price'],
            'refund_reason'         => $refund_reason,
        ];
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $params = $this->GetParamSign($parameter);
        $parameter['sign'] = $this->MyRsaSign($params['value']);

        // 执行请求
        $res = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $parameter);
        if($res['code'] != 0)
        {
            return $res;
        }
        $key = str_replace('.', '_', $parameter['method']).'_response';
        $result = $res['data'][$key];

        // 状态
        if(isset($result['code']) && $result['code'] == 10000)
        {
            // 验证签名
            if(!$this->SyncRsaVerify($result, $res['data']['sign']))
            {
                return DataReturn('签名验证错误', -1);
            }

            // 统一返回格式
            $data = [
                'out_trade_no'    => isset($result['out_trade_no']) ? $result['out_trade_no'] : '',
                'trade_no'        => isset($result['trade_no']) ? $result['trade_no'] : '',
                'buyer_user'      => isset($result['buyer_user_id']) ? $result['buyer_user_id'] : '',
                'refund_price'    => isset($result['refund_fee']) ? $result['refund_fee'] : 0.00,
                'return_params'   => $result,
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        }

        // 直接返回支付信息
        $msg = empty($result['sub_msg']) ? (empty($result['msg']) ? '支付失败' : $result['msg']) : $result['sub_msg'];
        $code = empty($result['sub_code']) ? (empty($result['code']) ? '支付失败' : $result['code']) : $result['sub_code'];
    }

    /**
     * [GetParamSign 生成参数和签名]
     * @param  [array] $data   [待生成的参数]
     * @return [array]         [生成好的参数和签名]
     */
    private function GetParamSign($data)
    {
        $param = '';
        $sign  = '';
        ksort($data);

        foreach($data AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $result = array(
            'param' =>  substr($param, 0, -1),
            'value' =>  substr($sign, 0, -1),
        );
        return $result;
    }

    /**
     * [HttpRequest 网络请求]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $url  [请求url]
     * @param    [array]           $data [发送数据]
     * @return   [mixed]                 [请求返回数据]
     */
    private function HttpRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $body_string = '';
        if(is_array($data) && 0 < count($data))
        {
            foreach($data as $k => $v)
            {
                $body_string .= $k.'='.urlencode($v).'&';
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body_string);
        }
        $headers = array('content-type: application/x-www-form-urlencoded;charset=UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if(curl_errno($ch))
        {
            return DataReturn(curl_error($ch), -1);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode)
            {
                return DataReturn('http请求错误['.$httpStatusCode.']', -1);
            }
        }
        curl_close($ch);
        return DataReturn('success', 0, json_decode($reponse, true));
    }

    /**
     * [MyRsaSign 签名字符串]
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
        return openssl_sign($prestr, $sign, $res, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
    }

    /**
     * [MyRsaDecrypt RSA解密]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T09:12:06+0800
     * @param    [string]                   $content [需要解密的内容，密文]
     * @return   [string]                            [解密后内容，明文]
     */
    private function MyRsaDecrypt($content)
    {
        if(stripos($this->config['rsa_public'], '-----') === false)
        {
            $res = "-----BEGIN PUBLIC KEY-----\n";
            $res .= wordwrap($this->config['rsa_public'], 64, "\n", true);
            $res .= "\n-----END PUBLIC KEY-----";
        } else {
            $res = $this->config['rsa_public'];
        }
        $res = openssl_get_privatekey($res);
        $content = base64_decode($content);
        $result  = '';
        for($i=0; $i<strlen($content)/128; $i++)
        {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res, OPENSSL_ALGO_SHA256);
            $result .= $decrypt;
        }
        unset($res);
        return $result;
    }

    /**
     * [OutRsaVerify 支付宝验证签名]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:39:50+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @param    [string]                   $sign   [签名结果]
     * @return   [boolean]                          [正确true, 错误false]
     */
    private function OutRsaVerify($prestr, $sign)
    {
        if(stripos($this->config['out_rsa_public'], '-----') === false)
        {
            $res = "-----BEGIN PUBLIC KEY-----\n";
            $res .= wordwrap($this->config['out_rsa_public'], 64, "\n", true);
            $res .= "\n-----END PUBLIC KEY-----";
        } else {
            $res = $this->config['out_rsa_public'];
        }
        $pkeyid = openssl_pkey_get_public($res);
        $sign = base64_decode($sign);
        if($pkeyid)
        {
            $verify = openssl_verify($prestr, $sign, $pkeyid, OPENSSL_ALGO_SHA256);
            unset($pkeyid);
        }
        return (isset($verify) && $verify == 1) ? true : false;
    }

     /**
     * [SyncRsaVerify 同步返回签名验证]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T13:13:39+0800
     * @param    [array]                   $data [返回数据]
     * @param    [string]                  $sign [签名]
     */
    private function SyncRsaVerify($data, $sign)
    {
        $string = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this->OutRsaVerify($string, $sign);
    }
}
?>