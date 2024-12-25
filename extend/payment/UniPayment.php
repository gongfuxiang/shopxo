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
 * UniPayment支付
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2023-01-27
 * @desc    description
 */
class UniPayment
{
    // 插件配置参数
    private $config;

    // 订单信息存储缓存key
    private $pay_data_cache_key = 'payment_unipayment_pay_data_';

    // 返回地址存储缓存key
    private $respond_url_cache_key = 'payment_unipayment_respond_url_cache_key_';

    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
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
     * @date    2023-01-27
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => 'UniPayment',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适配PC+H5、使用 UniPayment 接受和管理加密货币付款<a href="https://www.unipayment.io/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'app_id',
                'placeholder'   => 'AppId',
                'title'         => 'AppId',
                'is_required'   => 0,
                'message'       => '请填写AppId',
            ],
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
                'element'       => 'select',
                'title'         => '价格货币（默认 USD）',
                'message'       => '请选择价格货币',
                'name'          => 'price_currency',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>'BTC', 'name'=>'Bitcoin'],
                    ['value'=>'SGD', 'name'=>'Singapore Dollar'],
                    ['value'=>'ETH', 'name'=>'Ethereum'],
                    ['value'=>'USDT', 'name'=>'USDT'],
                    ['value'=>'USDC', 'name'=>'USDC'],
                    ['value'=>'CNY', 'name'=>'Yuan Renminbi'],
                    ['value'=>'USD', 'name'=>'US Dollar'],
                    ['value'=>'HKD', 'name'=>'Hong Kong Dollar'],
                ],
            ],
            [
                'element'       => 'select',
                'title'         => '支付货币（默认 USDT）',
                'message'       => '请选择支付货币',
                'name'          => 'pay_currency',
                'is_multiple'   => 0,
                'element_data'  => [
                    ['value'=>'BTC', 'name'=>'Bitcoin'],
                    ['value'=>'SGD', 'name'=>'Singapore Dollar'],
                    ['value'=>'ETH', 'name'=>'Ethereum'],
                    ['value'=>'USDT', 'name'=>'USDT'],
                    ['value'=>'USDC', 'name'=>'USDC'],
                    ['value'=>'CNY', 'name'=>'Yuan Renminbi'],
                    ['value'=>'USD', 'name'=>'US Dollar'],
                    ['value'=>'HKD', 'name'=>'Hong Kong Dollar'],
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
     * @date    2023-01-27
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
        if(empty($this->config) || empty($this->config['app_id']) || empty($this->config['client_id']) || empty($this->config['client_secret']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 是否存在返回地址
        $respond_url = (!empty($params['params']) && !empty($params['params']['respond_url'])) ? base64_decode(urldecode($params['params']['respond_url'])) : null;
        MyCache($this->respond_url_cache_key.$params['order_no'], $respond_url, 3600);

        // 请求数据
        $parameter = [
            'app_id'          => $this->config['app_id'],
            'title'           => ChinesePinyin($params['name'], true),
            'lang'            => 'en',
            'price_amount'    => $params['total_price'],
            'price_currency'  => empty($this->config['price_currency']) ? 'USD' : $this->config['price_currency'],
            'pay_currency'    => empty($this->config['pay_currency']) ? 'USDT' : $this->config['pay_currency'],
            'notify_url'      => $params['notify_url'],
            'redirect_url'    => $params['call_back_url'],
            'order_id'        => $params['order_no'],
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求接口
        $ret = $this->HttpRequest('invoices', $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        if(!empty($ret['data']['data']) && !empty($ret['data']['data']['invoice_url']))
        {
            // 订单信息存储缓存
            $key = $this->pay_data_cache_key.$parameter['order_id'];
            MyCache($key, [
                'params'       => $params,
                'data'         => $parameter,
                'respond'      => $ret['data'],
                'client_type'  => APPLICATION_CLIENT_TYPE,
            ], 3600);

            // 直接返回支付url地址
            return DataReturn('success', 0, $ret['data']['data']['invoice_url']);
        }
        return DataReturn('支付单创建失败', -1);
    }

    /**
     * 同步回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        // 回调处理
        $ret = $this->RespondHandle($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 是否自定义返回地址
        $key = $this->respond_url_cache_key.$ret['data']['out_trade_no'];
        $respond_url = MyCache($key);
        if(!empty($respond_url))
        {
            MyCache($key, null);
            MyRedirect($respond_url, true);
        }

        // 返回回调处理数据
        return $ret;
    }

    /**
     * 异步回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Notify($params = [])
    {
        return $this->RespondHandle($params);
    }

    /**
     * 回调数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function RespondHandle($params = [])
    {
        // 回调订单id
        $order_id = empty($params['orderId']) ? (empty($params['order_id']) ? '' : $params['order_id']) : $params['orderId'];
        if(empty($order_id))
        {
            return DataReturn('回调订单id为空', -1);
        }
        $pay_data = MyCache($this->pay_data_cache_key.$order_id);
        if(empty($pay_data))
        {
            return DataReturn('支付数据失效、请重新发起支付', -1);
        }

        // 创建事件则存储缓存
        if(!empty($params['event']))
        {
            $pay_data['invoice_created_data'] = $params;
            MyCache($this->pay_data_cache_key.$order_id, $pay_data);
        }

        // 仅一个参数则使用创建存储的数据作为参数
        if(count($params) == 1 && !empty($pay_data['invoice_created_data']))
        {
            $params = $pay_data['invoice_created_data'];
        }

        // 事件
        $event = empty($params['event']) ? '' : $params['event'];

        // 非完成和则不处理
        if(!in_array($event, ['invoice_markedComplete', 'invoice_completed']))
        {
            return DataReturn('未支付、事件('.$event.')', -1);
        }

        // 验证订单
        $ret = $this->HttpRequest('ipn', $params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 返回数据固定基础参数
        $data = $params;
        $data['trade_no']       = isset($params['invoice_id']) ? $params['invoice_id'] : $pay_data['respond']['data']['invoice_id']; // 支付平台 - 订单号
        $data['buyer_user']     = '';       // 支付平台 - 用户
        $data['out_trade_no']   = $order_id; // 本系统发起支付的 - 订单号
        $data['subject']        = $pay_data['data']['title']; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = isset($params['price_amount']) ? $params['price_amount'] : $pay_data['respond']['data']['price_amount']; // 本系统发起支付的 - 总价
        return DataReturn('支付成功', 0, $data);
    }

    /**
     * 支付回调处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Refund($params = [])
    {
        return DataReturn('UniPayment不支持接口退款、请到UniPayment商户平台处理！', -1);
    }

    /**
     * 网络请求
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
     * @desc    description
     * @param   [string]      $query  [请求方法]
     * @param   [string]      $data   [请求数据]
     * @param   [int]         $second [超时时间、秒]
     */
    private function HttpRequest($query, $data, $second = 30)
    {
        // url地址
        $url = $this->RequestUrl($query);

        // 数据为数组则转json
        $data = is_array($data) ? json_encode($data) : $data;

        // 头信息
        $header = [
            'Content-Length: '.strlen($data),
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Hmac '.$this->RequestSign($url, $data),
        ];

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
        $result = curl_exec($ch);
        if($result)
        {
            curl_close($ch);
            $result = json_decode($result, true);
            if(empty($result))
            {
                return DataReturn('返回数据有误:'.$result, -1);
            }
            $code = isset($result['Code']) ? $result['Code'] : $result['code'];
            if($code != 'OK')
            {
                $msg = empty($result['Msg']) ? $result['msg'] : $result['Msg'];
                return DataReturn('返回失败:'.$msg.'('.$code.')', -1, $code);
            }
            return DataReturn('success', 0, $result);
        }
        $error = curl_errno($ch);
        curl_close($ch);
        return DataReturn('curl出错，错误码:'.$error, -1);
    }

    /**
     * 请求签名
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
     * @desc    description
     * @param   [string]          $url    [请求url]
     * @param   [string]          $body   [请求内容]
     * @param   [string]          $method [请求类型]
     */
    protected function RequestSign($url, $body, $method = 'POST')
    {
        $request_url = urlencode(strtolower($url));
        $content_string = '';
        if($body !== '')
        {
            $hashed_body = md5($body, true);
            $content_string = base64_encode($hashed_body);
        }
        $time = time();
        $nonce = str_replace('-', '', uniqid(32));
        $signature_raw_data = $this->config['client_id'] . $method = 'POST' . $request_url . $time . $nonce
            . $content_string;
        $signature = hash_hmac('sha256', $signature_raw_data, $this->config['client_secret'], true);
        return $this->config['client_id'] . ':' . base64_encode($signature) . ':' . $nonce . ':' . $time;
    }

    /**
     * 请求地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-27
     * @desc    description
     */
    public function RequestUrl($query)
    {
        return in_array(substr($query, 0, 6), ['https:', 'http:/']) ? $query : 'https://api.unipayment.io/v1.0/'.$query;
    }
}
?>