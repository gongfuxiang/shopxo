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
use app\service\UserService;

/**
 * 拉卡拉收银台
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class LakalaCashier
{
    // 插件配置参数
    private $config;
    // 签名方式
    private $schema = 'LKLAPI-SHA256withRSA';
    // 接口地址
    private $url = [
        0 => [
            'pay'     => 'https://s2.lakala.com/api/v3/ccss/counter/order/special_create',
            'refund'  => 'https://s2.lakala.com/api/v3/labs/relation/refund',
            'query'   => 'https://s2.lakala.com/api/v3/ccss/counter/order/query',
        ],
        1 => [
            'pay'     => 'https://test.wsmsd.cn/sit/api/v3/ccss/counter/order/special_create',
            'refund'  => 'https://test.wsmsd.cn/sit/api/v3/labs/relation/refund',
            'query'   => 'https://test.wsmsd.cn/sit/api/v3/ccss/counter/order/query',
        ],
    ];
    // 缓存key
    private $cache_key = 'plugins_payment_lakalacashier_pay_data';

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
            'name'          => '拉卡拉收银台',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适用v3.0版本，适用pc+h5端，即时到帐支付方式，买家的交易资金直接打入卖家拉卡拉账户，快速回笼交易资金。 <a href="https://www.lakala.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'message'       => '请填写appid',
            ],
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
                'name'          => 'term_no',
                'placeholder'   => '终端号',
                'title'         => '终端号',
                'is_required'   => 0,
                'message'       => '请填写终端号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'serial_no',
                'placeholder'   => '证书序列号',
                'title'         => '证书序列号',
                'is_required'   => 0,
                'message'       => '请填写证书序列号',
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
                'placeholder'   => '拉卡拉公钥',
                'title'         => '拉卡拉公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写拉卡拉公钥',
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
        // 请求参数
        $base_parameter = [
            'merchant_no'           => $this->config['merchant_no'],
            'out_order_no'          => $params['order_no'],
            'total_amount'          => (int) (($params['total_price']*1000)/10),
            'order_efficient_time'  => $this->OrderAutoCloseTime(),
            'notify_url'            => $params['notify_url'],
            'support_cancel'        => 1,
            'support_refund'        => 1,
            'support_repeat_pay'    => 1,
            'callback_url'          => $params['call_back_url'],
            'order_info'            => $params['name'],
        ];
        // 整体请求参数
        $parameter = [
            'req_time'      => date('YmdHis'),
            'version'       => '3.0',
            'req_data'      => $base_parameter,
        ];
        $body = json_encode($parameter, JSON_UNESCAPED_UNICODE);

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 生成签名
        $authorization = $this->AuthorizationCreated($body);
        $result = $this->HttpRequest('pay', $body, $authorization);
        if($result['code'] == 0)
        {
            if(isset($result['data']['code']) && $result['data']['code'] == '000000' && !empty($result['data']['resp_data']))
            {
                if(!empty($result['data']['resp_data']['counter_url']))
                {
                    // 存储单号缓存
                    MyCache($this->cache_key.$params['user']['id'], $params['order_no'], 3600);
                    // 返回支付链接
                    return DataReturn('success', 0, $result['data']['resp_data']['counter_url']);
                }
            }
            return DataReturn($result['data']['msg'].'('.$result['data']['code'].')', -1);
        }
        return $result;
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
        $base_parameter = [
            'merchant_no'           => $this->config['merchant_no'],
            'term_no'               => $this->config['term_no'],
            'out_trade_no'          => GetNumberCode(10),
            'origin_out_trade_no'   => $params['order_no'],
            'origin_trade_no'       => $params['trade_no'],
            'refund_amount'         => (int) (($params['refund_price']*1000)/10),
            'refund_reason'         => $refund_reason,
        ];
        // 地理位置信息
        $location_info = [
            'request_ip'    => GetClientIP(),
        ];
        $base_parameter['location_info'] = $location_info;
        // 整体请求参数
        $parameter = [
            'req_time'      => date('YmdHis'),
            'version'       => '3.0',
            'out_org_code'  => $this->config['appid'],
            'req_data'      => $base_parameter,
        ];
        $body = json_encode($parameter, JSON_UNESCAPED_UNICODE);

        // 生成签名
        $authorization = $this->AuthorizationCreated($body);
        $result = $this->HttpRequest('refund', $body, $authorization);
        if($result['code'] == 0)
        {
            if(isset($result['data']['code']) && $result['data']['code'] == 'BBS00000')
            {
                if(!empty($result['data']['resp_data']))
                {
                    // 统一返回格式
                    $res = $result['data']['resp_data'];
                    $data = [
                        'out_trade_no'    => isset($res['out_trade_no']) ? $res['out_trade_no'] : '',
                        'trade_no'        => isset($res['trade_no']) ? $res['trade_no'] : '',
                        'buyer_user'      => isset($res['log_no']) ? $res['log_no'] : '',
                        'refund_price'    => isset($result['refund_amount']) ? $result['refund_amount']/100 : 0.00,
                        'return_params'   => $result['data'],
                        'request_params'  => $parameter,
                    ];
                    return DataReturn('退款成功', 0, $data);
                }
            }
            return DataReturn($result['data']['msg'].'('.$result['data']['code'].')', -1);
        }
        return $result;
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
        // 同步返回
        $order_no = '';
        if(empty($params['out_order_no']))
        {
            // 用户信息
            $user = UserService::LoginUserInfo();
            if(empty($user))
            {
                return DataReturn('用户未登录', -1);
            }
            $order_no = MyCache($this->cache_key.$user['id']);
        } else {
            $order_no = $params['out_order_no'];

            // 签名
            if(empty($_SERVER['HTTP_AUTHORIZATION']))
            {
                return DataReturn('授权签名为空', -1);
            }
            if(!$this->SignatureVerification($_SERVER['HTTP_AUTHORIZATION'], file_get_contents('php://input')))
            {
                return DataReturn('签名验证失败', -1);
            }
        }
        if(empty($order_no))
        {
            return DataReturn('支付单号为空', -1);
        }

        // 请求参数
        $base_parameter = [
            'merchant_no'   => $this->config['merchant_no'],
            'out_order_no'  => $order_no,
        ];
        // 整体请求参数
        $parameter = [
            'req_time'      => date('YmdHis'),
            'version'       => '3.0',
            'req_data'      => $base_parameter,
        ];
        $body = json_encode($parameter, JSON_UNESCAPED_UNICODE);

        // 生成签名
        $authorization = $this->AuthorizationCreated($body);
        $result = $this->HttpRequest('query', $body, $authorization);
        if($result['code'] == 0)
        {
            if(isset($result['data']['code']) && $result['data']['code'] == '000000')
            {
                if(!empty($result['data']['resp_data']) && isset($result['data']['resp_data']['order_status']) && $result['data']['resp_data']['order_status'] == 2 && !empty($result['data']['resp_data']['order_trade_info_list']))
                {
                    $resp_data = $result['data']['resp_data'];
                    $resp_data['order_trade_info'] = $resp_data['order_trade_info_list'][0];
                    return DataReturn('支付成功', 0, $this->ReturnData($resp_data));
                }
            }
        }
        return DataReturn($result['data']['msg'].'('.$result['data']['code'].')', -1);
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
        // 返回数据固定基础参数
        $data['trade_no']       = $data['order_trade_info']['trade_no'];        // 支付平台 - 订单号
        $data['buyer_user']     = empty($data['order_trade_info']['user_id1']) ? (empty($data['order_trade_info']['user_id2']) ? '' : $data['order_trade_info']['user_id2']) : $data['order_trade_info']['user_id1'];  // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_order_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = ''; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['total_amount']/100;    // 本系统发起支付的 - 总价

        return $data;
    }

    /**
     * 网络请求
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $type   [请求类型]
     * @param    [array]           $data   [发送数据]
     * @param    [int]             $second [超时]
     * @return   [mixed]                   [请求返回数据]
     */
    private function HttpRequest($type, $data, $authorization, $second = 30)
    {
        $is_dev_env = isset($this->config['is_dev_env']) ? $this->config['is_dev_env'] : 0;
        $headers = [
            'Authorization: ' . $authorization,
            'Accept: application/json',
            'Content-Type:application/json',
        ];
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL                => $this->url[$is_dev_env][$type],
            CURLOPT_HTTPHEADER         => $headers,
            CURLOPT_POST               => true,
            CURLOPT_SSL_VERIFYPEER     => false,
            CURLOPT_SSL_VERIFYHOST     => false,
            CURLOPT_RETURNTRANSFER     => true,
            CURLOPT_POSTFIELDS         => $data,
            CURLOPT_TIMEOUT            => $second,
        ));
        $reponse = curl_exec($ch);
        if(curl_errno($ch))
        {
            return DataReturn(curl_error($ch), -1);
        }
        curl_close($ch);
        return DataReturn('success', 0, json_decode($reponse, true));
    }

    /**
     * 签名验证
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-03-30
     * @desc    description
     * @param   [string]          $authorization [授权参数信息]
     * @param   [string]          $body          [请求参数]
     */
    public function SignatureVerification($authorization, $body)
    {
        $authorization = str_replace($this->schema . " ", "", $authorization);
        $authorization = str_replace(",","&", $authorization);
        $authorization = str_replace("\"","", $authorization);
        $authorization = $this->ConvertUrlQuery($authorization);
        $authorization['signature'] = base64_decode($authorization['signature']);
        $sign_str = $authorization['timestamp'] . "\n" . $authorization['nonce_str'] . "\n" . $body . "\n";

        if(stripos($this->config['out_rsa_public'], '-----') === false)
        {
            $res = "-----BEGIN CERTIFICATE-----\n";
            $res .= wordwrap($this->config['out_rsa_public'], 64, "\n", true);
            $res .= "\n-----END CERTIFICATE-----";
        } else {
            $res = $this->config['out_rsa_public'];
        }

        $key = openssl_get_publickey($res);
        $flag = openssl_verify($sign_str, $authorization['signature'], $key, OPENSSL_ALGO_SHA256);
        unset($key);
        return ($flag == 1);
    }

    /**
     * 签名生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-03-30
     * @desc    description
     * @param   [string]          $body [请求参数]
     */
    public function AuthorizationCreated($body)
    {
        $nonce_str = GetNumberCode(12);
        $timestamp = time();
        $sign_str = $this->config['appid'] . "\n" . $this->config['serial_no'] . "\n" . $timestamp . "\n" . $nonce_str . "\n" . $body . "\n";

        if(stripos($this->config['rsa_private'], '-----') === false)
        {
            $res = "-----BEGIN CERTIFICATE-----\n";
            $res .= wordwrap($this->config['rsa_private'], 64, "\n", true);
            $res .= "\n-----END CERTIFICATE-----";
        } else {
            $res = $this->config['rsa_private'];
        }
        $key = openssl_get_privatekey($res);
        openssl_sign($sign_str, $signature, $key, OPENSSL_ALGO_SHA256);
        unset($key);

        return $this->schema.' appid="' . $this->config['appid'] . '", serial_no="' . $this->config['serial_no'] . '", timestamp="' . $timestamp . '", nonce_str="' . $nonce_str . '", signature="' . base64_encode($signature) . '"';
    }

    /**
     * 签名参数转数组
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-03-30
     * @desc    description
     * @param   [string]          $query [请求参数]
     */
    private function ConvertUrlQuery($query)
    { 
        $params = explode('&', $query); 
        $result = []; 
        foreach($params as $param)
        { 
            $item = explode('=', $param); 
            $result[$item[0]] = $item[1]; 
        }
        if($result['signature'])
        {
            $result['signature'] = substr($query, strrpos($query, 'signature=') + 10);
        }
        return $result; 
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
        return '{"code":"SUCCESS","message":"执行成功"}';
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
        return '{"code":"ERROR","message":"执行失败"}';
    }
}
?>