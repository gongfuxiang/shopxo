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
 * 建行聚合支付
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-07
 * @desc    description
 */
class CcbPay
{
    // 插件配置参数
    private $config;

    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-07
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
            'name'          => '建行聚合支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'weixin'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适用PC(支付宝/微信/建行APP扫码支付)+微信小程序支付，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://ibsbjstar.ccb.com.cn/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'placeholder'   => '小程序APPID',
                'title'         => '小程序APPID',
                'is_required'   => 0,
                'message'       => '请填写小程序APPID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'merchant_id',
                'placeholder'   => '商户号',
                'title'         => '商户号',
                'is_required'   => 0,
                'message'       => '请填写商户号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'pos_id',
                'placeholder'   => '柜台号',
                'title'         => '柜台号',
                'is_required'   => 0,
                'message'       => '请填写柜台号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'branch_id',
                'placeholder'   => '分行代码',
                'title'         => '分行代码',
                'is_required'   => 0,
                'message'       => '请填写分行代码',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'pub',
                'placeholder'   => '商户公钥后30位',
                'title'         => '商户公钥后30位',
                'is_required'   => 0,
                'message'       => '请填写商户公钥后30位',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'ccb_public',
                'placeholder'   => '商户公钥',
                'title'         => '商户公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写商户公钥',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'wlpt_url',
                'placeholder'   => '外联平台地址',
                'title'         => '外联平台地址',
                'is_required'   => 0,
                'message'       => '请填写外联平台地址',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'user_id',
                'placeholder'   => '操作员号',
                'title'         => '操作员号',
                'is_required'   => 0,
                'message'       => '请填写商户服务平台操作员号',
            ],
            [
                'element'       => 'input',
                'type'          => 'password',
                'default'       => '',
                'name'          => 'user_pwd',
                'placeholder'   => '操作员交易密码',
                'title'         => '操作员交易密码',
                'is_required'   => 0,
                'message'       => '请填写商户服务平台操作员交易密码',
            ],
            [
                'element'       => 'message',
                'message'       => '1. 同步跳转地址，将该地址配置到支付后台页面同步跳转<br />'.__MY_URL__.'payment_default_order_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_respond.php<br /><br />2. 异步通知地址，将该地址配置到支付后台异步通知<br />'.__MY_URL__.'payment_default_order_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_notify.php',
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
        if(empty($this->config) || empty($this->config['merchant_id']) || empty($this->config['pos_id']) || empty($this->config['branch_id']) || empty($this->config['pub']) || empty($this->config['ccb_public']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 微信小程序
        if(APPLICATION_CLIENT_TYPE == 'weixin' && empty($this->config['appid']))
        {
            return DataReturn('支付缺少appid配置', -1);
        }

        // 微信小程序端
        if(APPLICATION_CLIENT_TYPE == 'weixin')
        {
            return $this->WeixinMiniPay($params);
        }

        // 默认web端
        return $this->WebPay($params);
    }

    /**
     * 微信小程序端支付
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-29
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function WeixinMiniPay($params = [])
    {
        // 用户openid
        if(empty($params['user']) || empty($params['user']['weixin_openid']))
        {
            return DataReturn('用户openid为空', -1);
        }

        // 支付参数
        $parameter = [
            'MERCHANTID'    => $this->config['merchant_id'],
            'POSID'         => $this->config['pos_id'],
            'BRANCHID'      => $this->config['branch_id'],
            'ORDERID'       => $params['order_no'],
            'PAYMENT'       => $params['total_price'],
            'CURCODE'       => '01',
            'TXCODE'        => '530590',
            'REMARK1'       => '',
            'REMARK2'       => '',
            'TYPE'          => 1,
            'PUB'           => $this->config['pub'],
            'GATEWAY'       => 0,
            'CLIENTIP'      => GetClientIP(),
            'REGINFO'       => '',
            'PROINFO'       => '',
            'REFERER'       => '',
            'TIMEOUT'       => $this->OrderAutoCloseTime(),
            'TRADE_TYPE'    => 'MINIPRO',
            'SUB_APPID'     => $this->config['appid'],
            'SUB_OPENID'    => $params['params']['user']['weixin_openid'],
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求参数
        $str = http_build_query($parameter);
        $url = 'https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain?CCB_IBSVersion=V6&'.str_replace('&PUB='.$parameter['PUB'], '', $str).'&MAC='.md5($str);
        $result = $this->HttpRequest($url, []);
        if(!empty($result) && !empty($result['SUCCESS']) && $result['SUCCESS'] == 'true' && !empty($result['PAYURL']))
        {
            $result = $this->HttpRequest($result['PAYURL'], []);
            return DataReturn('success', 0, $result);
        }
        return DataReturn('支付单提交失败', -1);
    }

    /**
     * web端支付
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-29
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function WebPay($params = [])
    {
        // 支付参数
        $parameter = [
            'MERCHANTID'    => $this->config['merchant_id'],
            'POSID'         => $this->config['pos_id'],
            'BRANCHID'      => $this->config['branch_id'],
            'ORDERID'       => $params['order_no'],
            'PAYMENT'       => $params['total_price'],
            'CURCODE'       => '01',
            'TXCODE'        => '530550',
            'REMARK1'       => '',
            'REMARK2'       => '',
            'RETURNTYPE'    => 3,
            'TIMEOUT'       => $this->OrderAutoCloseTime(),
            'PUB'           => $this->config['pub'],
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求参数
        $str = http_build_query($parameter);
        $url = 'https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain?CCB_IBSVersion=V6&'.str_replace('&PUB='.$parameter['PUB'], '', $str).'&MAC='.md5($str);
        $result = $this->HttpRequest($url, []);
        if(!empty($result) && !empty($result['SUCCESS']) && $result['SUCCESS'] == 'true' && !empty($result['PAYURL']))
        {
            $result = $this->HttpRequest($result['PAYURL'], []);
            if(!empty($result['QRURL']))
            {
                // PC端返回扫码支付格式
                $pay_params = [
                    'type'      => 'scan',
                    'url'       => urldecode($result['QRURL']),
                    'order_no'  => $params['order_no'],
                    'name'      => '扫码支付',
                    'msg'       => '打开【支付宝、微信、建行APP】扫一扫进行支付',
                    'check_url' => $params['check_url'],
                ];
                MySession('payment_qrcode_data', $pay_params);
                return DataReturn('success', 0, MyUrl('index/pay/qrcode'));
            }
        }
        return DataReturn('支付单提交失败', -1);
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
                'error_msg'         => '支付订单号不能为空',
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

        // 请求参数
        $parameter = "<?xml version=\"1.0\" encoding=\"GB2312\" standalone=\"yes\" ?>";
        $parameter .="<TX><REQUEST_SN>" . date('YmdHis') . "</REQUEST_SN>";
        $parameter .="<CUST_ID>" . $this->config['merchant_id'] . "</CUST_ID>";
        $parameter .="<USER_ID>" . $this->config['user_id'] . "</USER_ID>";
        $parameter .="<PASSWORD>" . $this->config['user_pwd'] . "</PASSWORD><TX_CODE>5W1004</TX_CODE><LANGUAGE>CN</LANGUAGE>";
        $parameter .="<TX_INFO><MONEY>" . $params['refund_price'] . "</MONEY>";
        $parameter .="<ORDER>" . $params['order_no'] . "</ORDER>";
        $parameter .="<REFUND_CODE></REFUND_CODE></TX_INFO><SIGN_INFO></SIGN_INFO><SIGNCERT></SIGNCERT></TX>";

        // 请求接口处理
        $result = $this->HttpRequestWlpt($params['wlpt_url'], $parameter);
        $resultData = simplexml_load_string($result);
        if(!empty($result) && isset($result['RETURN_MSG']) && $result['RETURN_CODE'] == '000000')
        {
            // 统一返回格式
            $data = [
                'out_trade_no'    => isset($result['ORDER_NUM']) ? $result['ORDER_NUM'] : '',
                'trade_no'        => isset($params['ORDERID']) ? $params['ORDERID'] : '',
                'buyer_user'      => '',
                'refund_price'    => isset($result['AMOUNT']) ? $result['AMOUNT'] : 0.00,
                'return_params'   => $result,
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        }
        return DataReturn(empty($result) ? '支付接口请求失败' : (empty($result['errMsg']) ? $result : $result['errMsg'].'['.$result['errCode'].']'), -1);
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
        $time = intval(MyC('common_order_close_limit_time', 30, true))*60;
        return date('YmdHis', time()+$time);
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
        // 参数字符串
        $sign = '';
        foreach($params AS $key=>$val)
        {
            if ($key != 'SIGN')
            {
                $sign .= "$key=$val&";
            }
        }
        $sign = substr($sign, 0, -1);

        // 签名
        if(!$this->VerifySign($sign, $params['SIGN']))
        {
            return DataReturn('签名校验失败', -1);
        }
        if(isset($params['SUCCESS']) && $params['SUCCESS'] == 'Y')
        {
            return DataReturn('支付成功', 0, $this->ReturnData($params));
        }
        return DataReturn('支付失败', -1);
    }

    /**
     * 返回数据统一格式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-08
     * @desc    description
     * @param   [array]          $data [支付数据]
     */
    private function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = $data['ACCDATE'];       // 支付平台 - 订单号
        $data['buyer_user']     = $data['ACC_TYPE'];  // 支付平台 - 用户
        $data['out_trade_no']   = $data['ORDERID'];    // 本系统发起支付的 - 订单号
        $data['subject']        = '';       // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['PAYMENT'];  // 本系统发起支付的 - 总价
        return $data;
    }

    /**
     * 网络请求
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $url         [请求url]
     * @param    [array]           $data        [发送数据]
     * @param    [int]             $second      [超时]
     * @return   [mixed]                        [请求返回数据]
     */
    private function HttpRequest($url, $data, $second = 30)
    {
        $ch = curl_init();
        $header = ['Content-Type: application/json'];
        curl_setopt_array($ch, array(
                CURLOPT_URL                => $url,
                CURLOPT_HTTPHEADER         => $header,
                CURLOPT_POST               => true,
                CURLOPT_SSL_VERIFYPEER     => false,
                CURLOPT_SSL_VERIFYHOST     => false,
                CURLOPT_RETURNTRANSFER     => true,
                CURLOPT_POSTFIELDS         => json_encode($data),
                CURLOPT_TIMEOUT            => $second,
        ));
        $result = curl_exec($ch);

        //返回结果
        if($result)
        {
            curl_close($ch);
            return json_decode($result, true);
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return "curl出错，错误码:$error";
        }
    }

    /**
     * 退款外联平台请求
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-19
     * @desc    description
     * @param    [string]          $url         [请求url]
     * @param    [array]           $data        [发送数据]
     * @param    [int]             $second      [超时]
     * @return   [mixed]                        [请求返回数据]
     */
    private function HttpRequestWlpt($url, $data, $second = 30)
    {
        $ch = curl_init();
        $header = ['Content-Type: application/xml; charset="GB2312"'];
        curl_setopt_array($ch, array(
                CURLOPT_URL                => $url,
                CURLOPT_HTTPHEADER         => $header,
                CURLOPT_POST               => true,
                CURLOPT_RETURNTRANSFER     => true,
                CURLOPT_POSTFIELDS         => $data,
                CURLOPT_TIMEOUT            => $second,
        ));
        $result = curl_exec($ch);

        //返回结果
        if($result)
        {
            curl_close($ch);
            return $result;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return "curl出错，错误码:$error";
        }
    }

    /**
     * 签名验证
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-27
     * @desc    description
     * @param   [string]          $prestr [待验证字符串]
     * @param   [string]          $sign   [签名]
     */
    public function VerifySign($prestr, $sign)
    {
        //公钥串转成pem格式
        $public_key = $this->DerTopem($this->config['ccb_public']);
        $pkeyid = openssl_get_publickey($public_key);
        $verifyResult = openssl_verify($prestr,  pack("H" . strlen($sign), $sign), $pkeyid, OPENSSL_ALGO_MD5);
        unset($pkeyid);
        return $verifyResult == 1 ? true : false;
    }

    /**
     * 16进制的公钥转成PEM格式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-27
     * @desc    description
     * @param   [type]          $public_key [description]
     */
    public function DerTopem($public_key)
    {
        $pem = chunk_split(base64_encode(hex2bin($public_key)), 64, "\n");
        $pem = "-----BEGIN PUBLIC KEY-----\n" . $pem . "-----END PUBLIC KEY-----\n";
        return $pem;
    }
}
?>
