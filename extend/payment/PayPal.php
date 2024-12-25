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
use app\service\ResourcesService;

/**
 * PayPal支付
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-07
 * @desc    description
 */
class PayPal
{
    // 插件配置参数
    private $config;

    // access_token
    private $access_token;

    // 接口地址
    private $url = [
        0 => 'https://api.paypal.com/',
        1 => 'https://www.sandbox.paypal.com/',
    ];

    // 订单信息存储缓存key
    private $pay_data_cache_key = 'payment_paypal_pay_data_';

    // 返回地址存储缓存key
    private $respond_url_cache_key = 'payment_paypal_respond_url_cache_key_';

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
            'name'          => 'PayPal',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5', 'ios', 'android'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适配PC+H5+APP、国际主流支付方式，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://www.paypal.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'client_id',
                'placeholder'   => 'ClientID',
                'title'         => 'ClientID',
                'is_required'   => 0,
                'message'       => '请填写ClientID',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'client_secret',
                'placeholder'   => 'ClientSecret',
                'title'         => 'ClientSecret',
                'is_required'   => 0,
                'message'       => '请填写ClientSecret',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'webhook_id',
                'placeholder'   => '订单WebhookID',
                'title'         => '订单WebhookID',
                'is_required'   => 0,
                'message'       => '请填写订单WebhookID、配置异步通知地址后得到的id',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'wallet_webhook_id',
                'placeholder'   => '钱包充值WebhookID',
                'title'         => '钱包充值WebhookID',
                'is_required'   => 0,
                'message'       => '请填写钱包充值WebhookID、配置异步通知地址后得到的id',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'membershiplevelvip_webhook_id',
                'placeholder'   => '会员购买WebhookID',
                'title'         => '会员购买WebhookID',
                'is_required'   => 0,
                'message'       => '请填写会员购买WebhookID、配置异步通知地址后得到的id',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'scanpay_webhook_id',
                'placeholder'   => '扫码收款WebhookID',
                'title'         => '扫码收款WebhookID',
                'is_required'   => 0,
                'message'       => '请填写扫码收款WebhookID、配置异步通知地址后得到的id',
            ],
            [
                'element'       => 'select',
                'title'         => '货币',
                'message'       => '请选择货币',
                'name'          => 'currency_code',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>'', 'name'=>'Auto Match'],
                    ['value'=>'CNY', 'name'=>'Chinese Renmenbi( CNY )'],
                    ['value'=>'USD', 'name'=>'U.S. Dollar( USD )'],
                    ['value'=>'AUD', 'name'=>'Australian Dollar( AUD )'],
                    ['value'=>'BRL', 'name'=>'Brazilian Real( BRL )'],
                    ['value'=>'CAD', 'name'=>'Canadian Dollar( CAD )'],
                    ['value'=>'CZK', 'name'=>'Czech Koruna( CZK )'],
                    ['value'=>'DKK', 'name'=>'Danish Krone( DKK )'],
                    ['value'=>'EUR', 'name'=>'Euro( EUR )'],
                    ['value'=>'HKD', 'name'=>'Hong Kong Dollar( HKD )'],
                    ['value'=>'HUF', 'name'=>'Hungarian Forint( HUF )'],
                    ['value'=>'ILS', 'name'=>'Israeli New Sheqel( ILS )'],
                    ['value'=>'JPY', 'name'=>'Japanese Yen( JPY )'],
                    ['value'=>'MYR', 'name'=>'Malaysian Ringgit( MYR )'],
                    ['value'=>'MXN', 'name'=>'Mexican Peso( MXN )'],
                    ['value'=>'NOK', 'name'=>'Norwegian Krone( NOK )'],
                    ['value'=>'NZD', 'name'=>'New Zealand Dollar( NZD )'],
                    ['value'=>'PHP', 'name'=>'Philippine Peso( PHP )'],
                    ['value'=>'PLN', 'name'=>'Polish Zloty( PLN )'],
                    ['value'=>'GBP', 'name'=>'Pound Sterling( GBP )'],
                    ['value'=>'RUB', 'name'=>'Russian Ruble( RUB )'],
                    ['value'=>'SGD', 'name'=>'Singapore Dollar( SGD )'],
                    ['value'=>'SEK', 'name'=>'Swedish Krona( SEK )'],
                    ['value'=>'CHF', 'name'=>'Swiss Franc( CHF )'],
                    ['value'=>'TWD', 'name'=>'Taiwan New Dollar( TWD )'],
                    ['value'=>'THB', 'name'=>'Thai Baht( THB )'],
                ],
            ],
            [
                'element'       => 'select',
                'title'         => '是否沙盒环境',
                'message'       => '请选择是否沙盒环境',
                'name'          => 'is_dev_env',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>0, 'name'=>'否'],
                    ['value'=>1, 'name'=>'是'],
                ],
            ],
            [
                'element'       => 'message',
                'message'       => '1. 订单异步通知地址，将该地址配置到支付后台异步通知<br />'.__MY_URL__.'payment_default_order_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_notify.php<br /><br />2. 钱包充值异步通知地址，将该地址配置到支付后台异步通知<br />'.__MY_URL__.'payment_default_wallet_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_notify.php<br /><br />3. 会员等级购买异步通知地址，将该地址配置到支付后台异步通知<br />'.__MY_URL__.'payment_default_membershiplevelvip_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_notify.php<br /><br />4. 扫码收款异步通知地址，将该地址配置到支付后台异步通知<br />'.__MY_URL__.'payment_default_scanpay_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_notify.php<br /><br />异步通知类型勾选【Payments & Payouts 下面的 Payment capture completed】即可',
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
        if(empty($this->config) || empty($this->config['client_id']) || empty($this->config['client_secret']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // token
        $token = $this->HttpRequest('v1/oauth2/token', 'grant_type=client_credentials');
        if($token['code'] != 0)
        {
            return $token;
        }
        // 设置请求token
        $this->access_token = $token['data']['access_token'];

        // 创建订单
        $parameter = [
            'intent'            => 'CAPTURE',
            'purchase_units'    => [
                [
                    'amount'        => [
                        'currency_code' => $this->CurrencyCode(),
                        'value'         => PriceNumberFormat($params['total_price']),
                    ],
                    'description'   => $params['name'],
                    'custom_id'     => $params['order_no'],
                ],
            ],
            'application_context'=> [
                'cancel_url'    => $params['call_back_url'],
                'return_url'    => $params['call_back_url'],
            ],
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求接口
        $result = $this->HttpRequest('v2/checkout/orders', $parameter);
        if($result['code'] != 0)
        {
            return $result;
        }

        // 是否存在返回地址
        $respond_url = (!empty($params['params']) && !empty($params['params']['respond_url'])) ? base64_decode(urldecode($params['params']['respond_url'])) : null;
        MyCache($this->respond_url_cache_key.$params['order_no'], $respond_url, 3600);

        // 订单信息存储缓存
        $key = $this->pay_data_cache_key.$result['data']['id'];
        MyCache($key, [
            'params'        => $params,
            'token'         => $token['data'],
            'respond'       => $result['data'],
            'client_type'   => APPLICATION_CLIENT_TYPE,
        ], 3600);

        // APP仅返回id
        if(in_array(APPLICATION_CLIENT_TYPE, ['ios', 'android']))
        {
            // 是否沙盒模式
            $is_dev_env = isset($this->config['is_dev_env']) ? $this->config['is_dev_env'] : 0;
            // 拼接回调捕获接口
            $call_back_url = $params['call_back_url'].(stripos($params['call_back_url'], '?') == false ? '?' : '&').'payment_order_id='.$result['data']['id'];
            // 返回支付数据
            return DataReturn('success', 0, [
                'pay_data'      => [
                    'clientId'     => $this->config['client_id'],
                    'orderId'      => $result['data']['id'],
                    'userAction'   => 'paynow',
                    'currency'     => $this->CurrencyCode(),
                    'environment'  => ($is_dev_env == 1) ? 'sandbox' : 'live',
                ],
                'call_back_url' => $call_back_url,
            ]);
        }

        // 直接返回支付url地址
        return DataReturn('success', 0, $result['data']['links'][1]['href']);
    }

    /**
     * 同步回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        // 回调处理
        $ret = $this->RespondHandle($params);
        if($ret['code'] == 0)
        {
            // 是否自定义返回地址
            $respond_url = MyCache($this->respond_url_cache_key.$ret['data']['out_trade_no']);
            if(!empty($respond_url))
            {
                MyCache($this->respond_url_cache_key.$ret['data']['out_trade_no'], null);
                MyRedirect($respond_url, true);
            }
        }

        // 移除多余的数据
        if(!empty($ret['data']) && is_array($ret['data']) && isset($ret['data']['pay_data']))
        {
            unset($ret['data']['pay_data']);
        }
        return $ret;
    }

    /**
     * 货币code
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-11
     * @desc    description
     */
    public function CurrencyCode($params = [])
    {
        if(empty($this->config['currency_code']))
        {
            // 指定货币
            if(!empty($params['currency_data']) && !empty($params['currency_data']['currency_code']))
            {
                return $params['currency_data']['currency_code'];
            }

            // 当前默认货币
            $res = ResourcesService::CurrencyData();
            return (empty($res) || empty($res['currency_code'])) ? 'CNY' : $res['currency_code'];
        }
        return $this->config['currency_code'];
    }

    /**
     * 回调数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-07-16
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function RespondHandle($params = [])
    {
        // 回调token
        $order_id = empty($params['payment_order_id']) ? (empty($params['token']) ? '' : $params['token']) : $params['payment_order_id'];
        if(empty($order_id))
        {
            return DataReturn('回调token为空', -1);
        }
        $pay_data = MyCache($this->pay_data_cache_key.$order_id);
        if(empty($pay_data))
        {
            return DataReturn('支付数据失效、请重新发起支付', -1);
        }

        // 设置请求token
        $this->access_token = $pay_data['token']['access_token'];

        // 捕获订单
        $result = $this->HttpRequest($pay_data['respond']['links'][3]['href'], '');
        if($result['code'] != 0)
        {
            $result['data'] = ['pay_data'=>$pay_data];
            return $result;
        }

        // 返回数据固定基础参数
        $data = $result['data'];
        $captures = $data['purchase_units'][0]['payments']['captures'][0];
        $gross_amount = $captures['seller_receivable_breakdown']['gross_amount'];
        $data['trade_no']       = $captures['id'];        // 支付平台 - 订单号
        $data['buyer_user']     = $data['payer']['email_address'];       // 支付平台 - 用户
        $data['out_trade_no']   = $captures['custom_id'];    // 本系统发起支付的 - 订单号
        $data['subject']        = $data['id']; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $gross_amount['value'];    // 本系统发起支付的 - 总价

        // 当前支付插件需要返回的数据
        $data['pay_data'] = $pay_data;

        // 默认返回固定格式
        return DataReturn('支付成功', 0, $data);
    }

    /**
     * 异步回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Notify($params = [])
    {
        if(empty($params['event_type']))
        {
            return DataReturn('事件类型为空', -1);
        }
        if(!in_array($params['event_type'], ['PAYMENT.CAPTURE.COMPLETED']))
        {
            return DataReturn('其他事件('.$params['event_type'].':'.$params['summary'].')', -1);
        }

        // 判断webhookid、默认订单
        $pluginsname = MyInput('pluginsname');
        $arr = ['wallet'=>'wallet_webhook_id', 'membershiplevelvip'=>'membershiplevelvip_webhook_id', 'scanpay'=>'scanpay_webhook_id'];
        $webhook_field = empty($arr[$pluginsname]) ? 'webhook_id' : $arr[$pluginsname];

        // 签名验证
        if(empty($this->config[$webhook_field]))
        {
            return DataReturn('未配置webhookid', -1);
        }
        $crc32 = crc32(file_get_contents('php://input'));
        if (empty($_SERVER['HTTP_PAYPAL_TRANSMISSION_ID']) || empty($_SERVER['HTTP_PAYPAL_TRANSMISSION_TIME']) || empty($crc32))
        {
            return DataReturn('签名数据为空', -1);
        }
        $sign_string = $_SERVER['HTTP_PAYPAL_TRANSMISSION_ID'].'|'.$_SERVER['HTTP_PAYPAL_TRANSMISSION_TIME'].'|'.$this->config[$webhook_field].'|'.$crc32;

        // 通过PAYPAL-CERT-URL头信息去拿公钥
        $public_key = openssl_pkey_get_public($this->HttpCurlCert($_SERVER['HTTP_PAYPAL_CERT_URL']));
        $details = openssl_pkey_get_details($public_key);
        $verify = openssl_verify($sign_string, base64_decode($_SERVER['HTTP_PAYPAL_TRANSMISSION_SIG']), $details['key'], 'SHA256');
        if($verify != 1)
        {
            return DataReturn('签名验证失败', -1);
        }

        // 返回数据固定基础参数
        $resource = $params['resource'];
        $params['trade_no']       = $resource['id'];    // 支付平台 - 订单号
        $params['buyer_user']     = $params['id'];       // 支付平台 - 用户
        $params['out_trade_no']   = $resource['custom_id'];    // 本系统发起支付的 - 订单号
        $params['subject']        = $params['summary']; // 本系统发起支付的 - 商品名称
        $params['pay_price']      = $resource['amount']['value'];  // 本系统发起支付的 - 总价
        return DataReturn('支付成功', 0, $params);
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

        // token
        $token = $this->HttpRequest('v1/oauth2/token', 'grant_type=client_credentials');
        if($token['code'] != 0)
        {
            return $token;
        }
        // 设置请求token
        $this->access_token = $token['data']['access_token'];

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // 退款操作
        $parameter = [
            'amount'    => [
                'currency'  => $this->CurrencyCode($params),
                'total'     => $params['refund_price'],
            ],
            'description'   => $refund_reason,
        ];
        $result = $this->HttpRequest('v1/payments/sale/'.$params['trade_no'].'/refund', $parameter);
        if($result['code'] != 0)
        {
            // 是否已完成退款
            if($result['data'] == 'TRANSACTION_ALREADY_REFUNDED')
            {
                // 统一返回格式
                $data = [
                    'out_trade_no'    => $params['order_no'],
                    'trade_no'        => '',
                    'buyer_user'      => '',
                    'refund_price'    => $params['refund_price'],
                    'return_params'   => $result['data'],
                    'request_params'  => $parameter,
                ];
                return DataReturn('退款成功', 0, $data);
            }

            return $result;
        }
        if(isset($result['data']['state']) && $result['data']['state'] == 'completed')
        {
            // 统一返回格式
            $data = [
                'out_trade_no'    => $params['order_no'],
                'trade_no'        => $result['data']['id'],
                'buyer_user'      => $result['data']['sale_id'],
                'refund_price'    => $result['data']['total_refunded_amount']['value'],
                'return_params'   => $result['data'],
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        }
    }

    /**
     * 证书下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-07-13
     * @desc    description
     * @param   [string]          $url [url地址]
     */
    public static function HttpCurlCert($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 网络请求
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $query       [请求参数]
     * @param    [array|string]    $data        [发送数据]
     * @param    [int]             $second      [超时]
     * @return   [mixed]                        [请求返回数据]
     */
    private function HttpRequest($query, $data, $second = 30)
    {
        // 是否完整的url地址
        if(in_array(substr($query, 0, 6), ['https:', 'http:/']))
        {
            $url = $query;
        } else {
            // 是否测试环境
            $is_dev_env = isset($this->config['is_dev_env']) ? $this->config['is_dev_env'] : 0;
            $url = $this->url[$is_dev_env].$query;
        }

        // 头信息
        $header = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];
        // 是否存在access_token
        if(!empty($this->access_token))
        {
            $header[] = 'Authorization: Bearer '.$this->access_token;
        }
        // 数据为数组则转json
        $data = is_array($data) ? json_encode($data) : $data;

        // 初始化curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        // 获取token
        if($data == 'grant_type=client_credentials')
        {
            curl_setopt($ch, CURLOPT_USERPWD, $this->config['client_id'].':'.$this->config['client_secret']);
        }
        $result = curl_exec($ch);

        //返回结果
        if($result)
        {
            curl_close($ch);
            $result = json_decode($result, true);
            if(empty($result))
            {
                return DataReturn('返回数据有误:'.$result, -1);
            }
            if(!empty($result['error_description']))
            {
                return DataReturn('返回失败:'.$result['error_description'].'('.$result['error'].')', -1, $result['error']);
            }
            if(!empty($result['message']))
            {
                return DataReturn('异常错误:'.$result['message'].'('.$result['name'].')', -1, $result['name']);
            }
            return DataReturn('success', 0, $result);
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return DataReturn('curl出错，错误码:'.$error, -1);
        }
    }
}
?>