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

/**
 * 易联支付 - 快捷支付插件
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class PayecoQuick
{
    // 插件配置参数
    private $config;

    // 接口地址
    private $url = [
        0 => 'https://tmobile.payeco.com/',
        1 => 'https://testmobile.payeco.com/',
    ];

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
            'name'          => '易联快捷支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适用PC端，储蓄卡+信用卡支付，即时到帐支付方式，买家的交易资金直接打入卖家易联账户，快速回笼交易资金。 <a href="https://www.payeco.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'merchant_no',
                'placeholder'   => '商户号',
                'title'         => '商户号',
                'is_required'   => 0,
                'message'       => '请填写商户号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'industry_id',
                'placeholder'   => '商户行业编号',
                'title'         => '商户行业编号',
                'is_required'   => 0,
                'message'       => '请填写商户行业编号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'industry_name',
                'placeholder'   => '商户行业参数',
                'title'         => '商户行业参数',
                'is_required'   => 0,
                'message'       => '请填写商户行业参数',
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
                'placeholder'   => '易联公钥',
                'title'         => '易联公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写易联公钥',
            ],
            [
                'element'       => 'select',
                'title'         => '是否测试环境',
                'message'       => '请选择是否测试环境',
                'name'          => 'is_dev_env',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>0, 'name'=>'否'],
                    ['value'=>1, 'name'=>'是'],
                ],
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
        if(empty($params['user']))
        {
            return DataReturn('订单用户信息为空', -1);
        }
        MyCache('plugins_payecoquick_pay_key_'.$params['user']['id'], $params, 600);
        MyRedirect(PluginsHomeUrl('payecoquick', 'index', 'index'), true);
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

        // 请求参数
        $parameter = [
            'TradeCode'         => 'RefundOrder',
            'Version'           => '2.0.0',
            'MerchantId'        => $this->config['merchant_no'],
            'MerchOrderId'      => $params['order_no'],
            'MerchRefundId'     => md5(time().GetNumberCode(6)),
            'Amount'            => $params['refund_price'],
            'TradeTime'         => date('YmdHis'),
        ];

        // 签名字符串
        $str = $this->SignContent($parameter);

        // 私钥签名
        $sign = $this->MyRsaSign($str['str1']);

        //通讯报文
        $query = "?TradeCode=".$parameter['TradeCode']."&".$str['str2']."&Sign=".$sign;

        // 请求接口
        $ret = $this->HttpResponseGET($query);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 数据是否正确
        if(isset($ret['data']['head']['retCode']) && $ret['data']['head']['retCode'] == '0000')
        {
            // 统一返回格式
            $body = $ret['data']['body'];
            $data = [
                'out_trade_no'  => isset($body['MerchOrderId']) ? $body['MerchOrderId'] : '',
                'trade_no'      => isset($body['TsNo']) ? $body['TsNo'] : '',
                'buyer_user'    => '',
                'refund_price'  => isset($body['Amount']) ? $body['Amount'] : 0.00,
                'return_params' => $ret['data'],
            ];
            return DataReturn('退款成功', 0, $data);
        }
        return DataReturn($ret['data']['head']['retMsg'].'('.$ret['data']['head']['retCode'].')', -1);
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
        // 请求参数
        $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);

        // 签名验证
        if(empty($data['Sign']))
        {
            return DataReturn('签名为空', -1);
        }
        $str = $this->SignContent($data);
        if($this->OutRsaVerify($str['str1'], base64_decode($data['Sign'])))
        {
            return DataReturn('签名验证失败', -1);
        }
        return DataReturn('支付成功', 0, $this->ReturnData($params));
    }

    /**
     * 返回数据统一格式
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-06T16:54:24+0800
     * @param    [array]                   $data [返回数据]
     */
    public function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = $data['OrderId'];  // 支付平台 - 订单号
        $data['buyer_user']     = '';  // 支付平台 - 用户
        $data['out_trade_no']   = $data['MerchOrderId'];  // 本系统发起支付的 - 订单号
        $data['subject']        = '';  // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['Amount'];  // 本系统发起支付的 - 总价

        return $data;
    }

    /**
     * get请求
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-28
     * @desc    description
     * @param   [string]          $query    [请求参数]
     * @param   [int]             $second   [超时]
     */
    public function HttpResponseGET($query, $second = 60)
    {
        $is_dev_env = isset($this->config['is_dev_env']) ? $this->config['is_dev_env'] : 0;
        $url = $this->url[$is_dev_env].'ppi/merchant/itf.do'.$query;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, $second);
        $reponse = curl_exec($curl);
        curl_close($curl); 
        
        // 返回处理
        if(!empty($reponse))
        {
            // 数据解析
            $reponse = $this->XmlToArray($reponse);
            if(!is_array($reponse))
            {
                return DataReturn($reponse, -1);
            }

            // 签名验证
            if(!empty($reponse['body']['sign']))
            {
                $str = $this->SignContent($reponse['body']);
                if(!$this->OutRsaVerify($str['str1'], $reponse['body']['sign']))
                {
                    return DataReturn('签名验证失败', -1);
                }
            }
            return DataReturn('success', 0, $reponse);
        }
        return DataReturn('支付接口请求失败', -1);
    }

    /**
     * xml转数组
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [string]          $xml [xm数据]
     */
    private function XmlToArray($xml)
    {
        if(!$this->XmlParser($xml))
        {
            return is_string($xml) ? $xml : '接口返回数据有误';
        }

        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 判断字符串是否为xml格式
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [string]          $string [字符串]
     */
    function XmlParser($string)
    {
        $xml_parser = xml_parser_create();
        if(!xml_parse($xml_parser, $string, true))
        {
            xml_parser_free($xml_parser);
            return false;
        } else {
            return (json_decode(json_encode(simplexml_load_string($string)),true));
        }
    }

    /**
     * 签名字符串
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function SignContent($params = [])
    {
        $str1  = '';
        $str2 = '';
        foreach($params as $k=>$v)
        {
            if(!in_array($k, ['TradeCode', 'sign']))
            {
                $str1 .= "$k=$v&";
                if(in_array($k, ['OrderDesc', 'ExtData', 'MiscData', 'SmParam']))
                {
                    $str2 .= "$k=".base64_encode($v)."&";
                } else if(in_array($k, ['NotifyUrl'])) {
                    $str2 .= "$k=".urlencode($v)."&";
                } else {
                    $str2 .= "$k=$v&";
                }
            }
        }
        $str1 = substr($str1, 0, -1);
        $str2 = substr($str2, 0, -1);
        return [
            'str1'   => $str1,
            'str2'   => $str2,
        ];
    }

    /**
     * 签名生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-26
     * @desc    description
     * @param   [string]          $prestr [需要签名的字符串]
     */
    public function MyRsaSign($prestr)
    {
        if(stripos($this->config['rsa_private'], '-----') === false)
        {
            $res = "-----BEGIN RSA PRIVATE KEY-----\n";
            $res .= wordwrap($this->config['rsa_private'], 64, "\n", true);
            $res .= "\n-----END RSA PRIVATE KEY-----";
        } else {
            $res = $this->config['rsa_private'];
        }
        $key = openssl_get_privatekey($res);
        openssl_sign($prestr, $sign, $key, OPENSSL_ALGO_MD5);
        unset($key);
        return base64_encode($sign);
    }

    /**
     * 验证签名
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-26
     * @desc    description
     * @param   [string]          $prestr [需要签名的字符串]
     * @param   [string]          $sign   [签名]
     */
    public function OutRsaVerify($prestr, $sign)
    {
        if(stripos($this->config['out_rsa_public'], '-----') === false)
        {
            $res = "-----BEGIN PUBLIC KEY-----\n";
            $res .= wordwrap($this->config['out_rsa_public'], 64, "\n", true);
            $res .= "\n-----END PUBLIC KEY-----";
        } else {
            $res = $this->config['out_rsa_public'];
        }
        $key = openssl_pkey_get_public($res);
        if($key)
        {
            $verify = openssl_verify($prestr, base64_decode($sign), $key, OPENSSL_ALGO_MD5);
            unset($key);
        }
        return (isset($verify) && $verify == 1) ? true : false;
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
        return '0000';
    }
}
?>