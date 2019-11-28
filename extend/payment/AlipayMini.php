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

/**
 * 支付宝小程序支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class AlipayMini
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
            'name'          => '支付宝',  // 插件名称
            'version'       => '1.1.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['alipay'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用支付宝小程序，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'message'       => '请填写小程序appid',
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

        // 支付参数
        $parameter = array(
            'app_id'                =>  $this->config['appid'],
            'method'                =>  'alipay.trade.create',
            'format'                =>  'JSON',
            'charset'               =>  'utf-8',
            'sign_type'             =>  'RSA2',
            'timestamp'             =>  date('Y-m-d H:i:s'),
            'version'               =>  '1.0',
            'notify_url'            =>  $params['notify_url'],
        );
        $biz_content = array(
            'subject'               =>  $params['name'],
            'out_trade_no'          =>  $params['order_no'],
            'total_amount'          =>  $params['total_price'],
            'buyer_id'              =>  $params['user']['alipay_openid'],
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $params = $this->GetParamSign($parameter);
        $parameter['sign'] = $this->MyRsaSign($params['value']);
        

        // 执行请求
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $parameter);
        $key = str_replace('.', '_', $parameter['method']).'_response';

        // 验证签名
        if(!$this->SyncRsaVerify($result, $key))
        {
            return DataReturn('签名验证错误', -1);
        }

        // 状态
        if(isset($result[$key]['code']) && $result[$key]['code'] == 10000)
        {
            return DataReturn('处理成功', 0, $result[$key]['trade_no']);
        }

        // 直接返回支付信息
        return DataReturn($result[$key]['sub_msg'].'['.$result[$key]['sub_code'].']', -1000);
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
        $buyer_user = isset($data['buyer_logon_id']) ? $data['buyer_logon_id'] : (isset($data['buyer_email']) ? $data['buyer_email'] : '');
        $pay_price = isset($data['total_amount']) ? $data['total_amount'] : (isset($data['total_fee']) ? $data['total_fee'] : '');

        // 返回数据固定基础参数
        $data['trade_no']       = $data['trade_no'];        // 支付平台 - 订单号
        $data['buyer_user']     = $buyer_user;              // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = $data['subject'];         // 本系统发起支付的 - 商品名称
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
        $parameter = array(
            'app_id'                =>  $this->config['appid'],
            'method'                =>  'alipay.trade.refund',
            'format'                =>  'JSON',
            'charset'               =>  'utf-8',
            'sign_type'             =>  'RSA2',
            'timestamp'             =>  date('Y-m-d H:i:s'),
            'version'               =>  '1.0',
        );
        $biz_content = array(
            'out_trade_no'          =>  $params['order_no'],
            'trade_no'              =>  $params['trade_no'],
            'refund_amount'         =>  $params['refund_price'],
            'refund_reason'         =>  $refund_reason,
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $params = $this->GetParamSign($parameter);
        $parameter['sign'] = $this->MyRsaSign($params['value']);

        // 执行请求
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $parameter);
        $key = str_replace('.', '_', $parameter['method']).'_response';

        // 验证签名
        if(!$this->SyncRsaVerify($result, $key))
        {
            return DataReturn('签名验证错误', -1);
        }

        // 状态
        if(isset($result[$key]['code']) && $result[$key]['code'] == 10000)
        {
            // 统一返回格式
            $data = [
                'out_trade_no'  => isset($result[$key]['out_trade_no']) ? $result[$key]['out_trade_no'] : '',
                'trade_no'      => isset($result[$key]['trade_no']) ? $result[$key]['trade_no'] : '',
                'buyer_user'    => isset($result[$key]['buyer_user_id']) ? $result[$key]['buyer_user_id'] : '',
                'refund_price'  => isset($result[$key]['refund_fee']) ? $result[$key]['refund_fee'] : 0.00,
                'return_params' => $result[$key],
            ];
            return DataReturn('退款成功', 0, $data);
        }

        // 直接返回支付信息
        return DataReturn($result[$key]['sub_msg'].'['.$result[$key]['sub_code'].']', -1000);
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
            return false;
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode)
            {
                return false;
            }
        }
        curl_close($ch);
        return json_decode($reponse, true);
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
        openssl_free_key($res);
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
            openssl_free_key($pkeyid);
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
     * @param    [boolean]                 $key  [数据key]
     */
    private function SyncRsaVerify($data, $key)
    {
        $string = json_encode($data[$key], JSON_UNESCAPED_UNICODE);
        return $this->OutRsaVerify($string, $data['sign']);
    }

}
?>