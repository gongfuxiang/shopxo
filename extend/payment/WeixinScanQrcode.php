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
 * 微信扫码支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class WeixinScanQrcode
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
            'name'          => '微信扫码支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '微信扫码支付、适用web端，一般用于线下扫码枪或手机对准用户付款码扫码完成支付，买家的交易资金直接打入卖家微信账户，快速回笼交易资金，（不要对用户开放、该插件不适用于在线支付）。 <a href="https://pay.weixin.qq.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'placeholder'   => '公众号/服务号AppID',
                'title'         => '公众号/服务号AppID',
                'is_required'   => 0,
                'message'       => '请填写微信分配的AppID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mch_id',
                'placeholder'   => '微信支付商户号',
                'title'         => '微信支付商户号',
                'is_required'   => 0,
                'message'       => '请填写微信支付分配的商户号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'key',
                'placeholder'   => '密钥',
                'title'         => '密钥',
                'desc'          => '微信支付商户平台API配置的密钥',
                'is_required'   => 0,
                'message'       => '请填写密钥',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'apiclient_cert',
                'placeholder'   => '证书(apiclient_cert.pem)',
                'title'         => '证书(apiclient_cert.pem)（退款操作必填项）',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写证书(apiclient_cert.pem)',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'apiclient_key',
                'placeholder'   => '证书密钥(apiclient_key.pem)',
                'title'         => '证书密钥(apiclient_key.pem)（退款操作必填项）',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写证书密钥(apiclient_key.pem)',
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
        // 付款码
        if(empty($params['params']) || empty($params['params']['payment_code']))
        {
            return DataReturn('付款码为空', -1);
        }

        // 支付参数
        $parameter = [
            'appid'             => $this->config['appid'],
            'mch_id'            => $this->config['mch_id'],
            'body'              => $params['site_name'].'-'.$params['name'],
            'nonce_str'         => md5(time().$params['order_no']),
            'out_trade_no'      => $params['order_no'],
            'spbill_create_ip'  => GetClientIP(),
            'total_fee'         => (int) (($params['total_price']*1000)/10),
            'auth_code'         => $params['params']['payment_code'],
            'attach'            => empty($params['attach']) ? $params['site_name'].'-'.$params['name'] : $params['attach'],
            'sign_type'         => 'MD5',
            'time_expire'       => $this->OrderAutoCloseTime(),
        ];
        $parameter['sign'] = $this->GetSign($parameter);

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求接口处理
        $request_url = 'https://api.mch.weixin.qq.com/pay/micropay';
        $result = $this->XmlToArray($this->HttpRequest($request_url, $this->ArrayToXml($parameter)));
        if(isset($result['return_code']) && $result['return_code'] == 'SUCCESS')
        {
            if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS')
            {
                $ret = DataReturn('支付成功', 0, $this->ReturnData($result));
            } else {
                // 是否需要等待用户处理
                if($result['err_code'] == 'USERPAYING')
                {
                    $ret = DataReturn($result['err_code_des'], -9999, [
                        'is_await'  => 1,
                        'payment'   => substr(self::class, strrpos(self::class, '\\') + 1),
                        'order_no'  => $params['order_no'],
                    ]);
                } else {
                    $ret = DataReturn($result['err_code_des'].'['.$result['err_code'].']', -1);
                }
            }
        } else {
            $ret = DataReturn(is_string($result) ? $result : $result['return_msg'], -1);
        }
        return $ret;
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

        // 请求参数
        $parameter = [
            'appid'             => $this->config['appid'],
            'mch_id'            => $this->config['mch_id'],
            'nonce_str'         => md5(time().$params['order_no']),
            'out_trade_no'      => $params['order_no'],
            'sign_type'         => 'MD5',
        ];
        $parameter['sign'] = $this->GetSign($parameter);

        // 是否撤销操作
        if(isset($params['is_reverse_pay']) && $params['is_reverse_pay'] == 1)
        {
            // 请求接口处理
            $request_url = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';
            $result = $this->XmlToArray($this->HttpRequest($request_url, $this->ArrayToXml($parameter), true));
            if(isset($result['return_code']) && $result['return_code'] == 'SUCCESS')
            {
                if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS')
                {
                    $ret = DataReturn('撤销成功', -55555, ['is_reverse_pay'=>1]);
                } else {
                    $ret = DataReturn($result['err_code_des'].'['.$result['err_code'].']', -1);
                }
            } else {
                $ret = DataReturn(is_string($result) ? $result : $result['return_msg'], -1);
            }
        } else {
            // 请求接口处理
            $request_url = 'https://api.mch.weixin.qq.com/pay/orderquery';
            $result = $this->XmlToArray($this->HttpRequest($request_url, $this->ArrayToXml($parameter)));
            if(isset($result['return_code']) && $result['return_code'] == 'SUCCESS')
            {
                if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS')
                {
                    if(!empty($result['transaction_id']))
                    {
                        $ret = DataReturn('支付成功', 0, $this->ReturnData($result));
                    } else {
                        $ret = DataReturn($result['trade_state_desc'], -1);
                    }
                } else {
                    $ret = DataReturn($result['err_code_des'].'['.$result['err_code'].']', -1);
                }
            } else {
                $ret = DataReturn(is_string($result) ? $result : $result['return_msg'], -1);
            }
        }
        return $ret;
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
        $result = empty($GLOBALS['HTTP_RAW_POST_DATA']) ? $this->XmlToArray(file_get_contents('php://input')) : $this->XmlToArray($GLOBALS['HTTP_RAW_POST_DATA']);
        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS')
        {
            if($result['sign'] != $this->GetSign($result))
            {
                return DataReturn('签名验证错误', -1);
            }
            return DataReturn('支付成功', 0, $this->ReturnData($result));
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
        // 返回数据固定基础参数
        $data['trade_no']       = $data['transaction_id'];  // 支付平台 - 订单号
        $data['buyer_user']     = $data['openid'];          // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = $data['attach'];          // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['total_fee']/100;   // 本系统发起支付的 - 总价
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
                'key_name'          => 'pay_price',
                'error_msg'         => '支付金额不能为空',
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

        // 证书是否配置
        if(empty($this->config['apiclient_cert']) || empty($this->config['apiclient_key']))
        {
            return DataReturn('证书未配置', -1);
        }

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // 请求参数
        $parameter = [
            'appid'             => $this->config['appid'],
            'mch_id'            => $this->config['mch_id'],
            'nonce_str'         => md5(time().rand().$params['order_no']),
            'sign_type'         => 'MD5',
            'transaction_id'    => $params['trade_no'],
            'out_trade_no'      => $params['order_no'],
            'out_refund_no'     => $params['order_no'].GetNumberCode(),
            'total_fee'         => (int) (($params['pay_price']*1000)/10),
            'refund_fee'        => (int) (($params['refund_price']*1000)/10),
            'refund_desc'       => $refund_reason,            
        ];
        $parameter['sign'] = $this->GetSign($parameter);

        // 请求接口处理
        $result = $this->XmlToArray($this->HttpRequest('https://api.mch.weixin.qq.com/secapi/pay/refund', $this->ArrayToXml($parameter), true));
        if(isset($result['return_code']) && $result['return_code'] == 'SUCCESS')
        {
            if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS')
            {
                // 统一返回格式
                $data = [
                    'out_trade_no'    => isset($result['out_trade_no']) ? $result['out_trade_no'] : '',
                    'trade_no'        => isset($result['transaction_id']) ? $result['transaction_id'] : '',
                    'buyer_user'      => isset($result['refund_id']) ? $result['refund_id'] : '',
                    'refund_price'    => isset($result['refund_fee']) ? $result['refund_fee']/100 : 0.00,
                    'return_params'   => $result,
                    'request_params'  => $parameter,
                ];
                return DataReturn('退款成功', 0, $data);
            }
            return DataReturn($result['err_code_des'].'['.$result['err_code'].']', -1);
        }
        $ret = DataReturn(is_string($result) ? $result : $result['return_msg'], -1);
    }

    /**
     * 签名生成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private function GetSign($params = [])
    {
        ksort($params);
        $sign  = '';
        foreach($params as $k=>$v)
        {
            if($k != 'sign' && $v != '' && $v != null)
            {
                $sign .= "$k=$v&";
            }
        }
        return strtoupper(md5($sign.'key='.$this->config['key']));
    }

    /**
     * 数组转xml
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [array]          $data [数组]
     */
    private function ArrayToXml($data)
    {
        $xml = '<xml>';
        foreach($data as $k=>$v)
        {
            $xml .= '<'.$k.'>'.$v.'</'.$k.'>';
        }
        $xml .= '</xml>';
        return $xml;
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
     * [HttpRequest 网络请求]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $url         [请求url]
     * @param    [array]           $data        [发送数据]
     * @param    [boolean]         $use_cert    [是否需要使用证书]
     * @param    [int]             $second      [超时]
     * @return   [mixed]                        [请求返回数据]
     */
    private function HttpRequest($url, $data, $use_cert = false, $second = 30)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_POST           => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_TIMEOUT        => $second,
        );

        if($use_cert == true)
        {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            $apiclient = $this->GetApiclientFile();
            $options[CURLOPT_SSLCERTTYPE] = 'PEM';
            $options[CURLOPT_SSLCERT] = $apiclient['cert'];
            $options[CURLOPT_SSLKEYTYPE] = 'PEM';
            $options[CURLOPT_SSLKEY] = $apiclient['key'];
        }
 
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
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
     * 获取证书文件路径
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-29
     * @desc    description
     */
    private function GetApiclientFile()
    {
        // 证书位置
        $apiclient_cert_file = ROOT.'runtime'.DS.'cache'.DS.'payment_weixin_pay_apiclient_cert.pem';
        $apiclient_key_file = ROOT.'runtime'.DS.'cache'.DS.'payment_weixin_pay_apiclient_key.pem';

        // 证书处理
        if(stripos($this->config['apiclient_cert'], '-----') === false)
        {
            $apiclient_cert = "-----BEGIN CERTIFICATE-----\n";
            $apiclient_cert .= wordwrap($this->config['apiclient_cert'], 64, "\n", true);
            $apiclient_cert .= "\n-----END CERTIFICATE-----";
        } else {
            $apiclient_cert = $this->config['apiclient_cert'];
        }
        file_put_contents($apiclient_cert_file, $apiclient_cert);

        if(stripos($this->config['apiclient_key'], '-----') === false)
        {
            $apiclient_key = "-----BEGIN PRIVATE KEY-----\n";
            $apiclient_key .= wordwrap($this->config['apiclient_key'], 64, "\n", true);
            $apiclient_key .= "\n-----END PRIVATE KEY-----";
        } else {
            $apiclient_key = $this->config['apiclient_key'];
        }
        file_put_contents($apiclient_key_file, $apiclient_key);

        return ['cert' => $apiclient_cert_file, 'key' => $apiclient_key_file];
    }
}
?>