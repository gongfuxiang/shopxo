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
 * 微信支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Weixin
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
            'name'          => '微信',  // 插件名称
            'version'       => '0.0.1',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5', 'weixin'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用微信web/h5(非微信环境)/小程序，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://pay.weixin.qq.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'placeholder'   => '公众号ID',
                'title'         => '公众号ID (用于web/h5)',
                'is_required'   => 0,
                'message'       => '请填写微信分配的公众号ID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mini_appid',
                'placeholder'   => '小程序ID',
                'title'         => '小程序ID',
                'is_required'   => 0,
                'message'       => '请填写微信分配的小程序ID',
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
                'is_required'   => 0,
                'message'       => '请填写密钥',
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

        // 获取支付参数
        $ret = $this->GetPayParams($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // xml
        $xml = $this->ArrayToXml($ret['data']);
        $result = $this->XmlToArray($this->HttpRequest('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml));
        if(!empty($result['return_code']) && $result['return_code'] == 'SUCCESS' && !empty($result['prepay_id']))
        {
            return $this->PayHandleReturn($ret['data'], $result, $params);
        }
        $msg = empty($result['return_msg']) ? '支付异常' : $result['return_msg'];
        if(!empty($result['err_code_des']))
        {
            $msg .= '-'.$result['err_code_des'];
        }
        return DataReturn($msg, -1);
    }

    /**
     * 支付返回处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-08
     * @desc    description
     * @param   [array]           $pay_params   [支付参数]
     * @param   [array]           $data         [支付返回数据]
     * @param   [array]           $params       [输入参数]
     */
    private function PayHandleReturn($pay_params = [], $data = [], $params = [])
    {
        $result = DataReturn('支付接口异常', -1);
        switch($pay_params['trade_type'])
        {
            // web支付
            case 'NATIVE' :
                $pay_params = [
                    'url'       => urlencode(base64_encode($data['code_url'])),
                    'order_no'  => $params['order_no'],
                    'name'      => urlencode('微信支付'),
                    'msg'       => urlencode('打开微信APP扫一扫进行支付'),
                ];
                $url = MyUrl('index/order/qrcodepay', $pay_params);
                $result = DataReturn('success', 0, $url);
                break;

            // h5支付
            case 'MWEB' :
                $result = DataReturn('success', 0, $data['mweb_url']);
                break;

            // 微信中/小程序支付
            case 'JSAPI' :
                $pay_data = array(
                    'appId'         => $pay_params['appid'],
                    'package'       => 'prepay_id='.$data['prepay_id'],
                    'nonceStr'      => md5(time().rand()),
                    'signType'      => $pay_params['sign_type'],
                    'timeStamp'     => (string) time(),
                );
                $pay_data['paySign'] = $this->GetSign($pay_data);
                $result = DataReturn('success', 0, $pay_data);
                break;

            // APP支付
            case 'APP' :
                $result = DataReturn('APP支付暂未开放', -1);
                break;
        }
        return $result;
    }

    /**
     * 获取支付参数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-07
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private function GetPayParams($params = [])
    {
        $trade_type = empty($params['trade_type']) ? $this->GetTradeType() : $params['trade_type'];
        if(empty($trade_type))
        {
            return DataReturn('支付类型不匹配', -1);
        }

        // appid
        $appid = (APPLICATION_CLIENT_TYPE == 'weixin') ? $this->config['mini_appid'] :  $this->config['appid'];
        $data = [
            'appid'             => $appid,
            'mch_id'            => $this->config['mch_id'],
            'body'              => $params['site_name'].'-'.$params['name'],
            'nonce_str'         => md5(time().rand().$params['order_no']),
            'notify_url'        => $params['notify_url'],
            'openid'            => ($trade_type == 'JSAPI') ? $params['user']['weixin_openid'] : '',
            'out_trade_no'      => $params['order_no'].GetNumberCode(6),
            'spbill_create_ip'  => GetClientIP(),
            'total_fee'         => intval($params['total_price']*100),
            'trade_type'        => $trade_type,
            'attach'            => empty($params['attach']) ? $params['site_name'].'-'.$params['name'] : $params['attach'],
            'sign_type'         => 'MD5',
        ];
        $data['sign'] = $this->GetSign($data);
        return DataReturn('success', 0, $data);
    }

    /**
     * 获取支付交易类型
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-08
     * @desc    description
     */
    private function GetTradeType()
    {
        $type_all = [
            'pc'        => 'NATIVE',
            'weixin'    => 'JSAPI',
            'h5'        => 'MWEB',
            'app'       => 'APP'
        ];

        // 手机中打开pc版本
        if(APPLICATION_CLIENT_TYPE == 'pc' && IsMobile())
        {
            $type_all['pc'] = $type_all['h5'];
        }

        // 微信中打开
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if(!empty($user_agent) && strpos($user_agent, 'MicroMessenger') !== false)
        {
            $type_all['pc'] = $type_all['h5'];
        }
        return isset($type_all[APPLICATION_CLIENT_TYPE]) ? $type_all[APPLICATION_CLIENT_TYPE] : '';
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

        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS' && $result['sign'] == $this->GetSign($result))
        {
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
        // 参数处理
        $out_trade_no = substr($data['out_trade_no'], 0, strlen($data['out_trade_no'])-6);

        // 返回数据固定基础参数
        $data['trade_no']       = $data['transaction_id'];  // 支付平台 - 订单号
        $data['buyer_user']     = $data['openid'];          // 支付平台 - 用户
        $data['out_trade_no']   = $out_trade_no;            // 本系统发起支付的 - 订单号
        $data['subject']        = $data['attach'];          // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['total_fee']/100;   // 本系统发起支付的 - 总价
        return $data;
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
        if(!$this->XmlParser($xml)) return '';

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
            // 退款 取消使用
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            // $options[CURLOPT_SSLCERTTYPE] = 'PEM';
            // $options[CURLOPT_SSLCERT] = WEB_ROOT.'cert/wechat_app_apiclient_cert.pem';
            // $options[CURLOPT_SSLKEYTYPE] = 'PEM';
            // $options[CURLOPT_SSLKEY] = WEB_ROOT.'cert/wechat_app_apiclient_key.pem';
        }
 
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
?>