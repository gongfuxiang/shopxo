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
 * XPay - XPay支付
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class XPay
{
    // 插件配置参数
    private $config;

    // 订单信息存储缓存key
    private $pay_data_cache_key = 'payment_xpay_pay_orderid_key';

    /**
     * 构造方法
     * @author  Devil
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
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => 'XPay支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => 'XPay支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://api.qfllqhi.cn/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mch_no',
                'placeholder'   => '商户号',
                'title'         => '商户号',
                'is_required'   => 0,
                'message'       => '请填写商户号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'product_name',
                'placeholder'   => '产品名称',
                'title'         => '产品名称',
                'is_required'   => 0,
                'message'       => '请填写产品名称',
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
     * @author  Devil
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
        if(empty($this->config) || empty($this->config['mch_no']) || empty($this->config['product_name']) || empty($this->config['key']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 支付参数
        $parameter = [
            'mid'           => $this->config['mch_no'],
            'orderid'       => $params['order_no'],
            'product_name'  => $this->config['product_name'],
            'timestamp'     => date('Y-m-d H:i:s'),
            'amount'        => $params['total_price'],
            'callback_url'  => $params['notify_url'],
            'result_url'    => $params['call_back_url'],
            'username'      => $params['user']['user_name_view'],
            'userip'        => GetClientIP(),
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 执行请求
        $ret = $this->HttpRequest('trans/order_create', $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        if(!empty($ret['data']['url']))
        {
            // 订单信息存储缓存
            MySession($this->pay_data_cache_key, $parameter['orderid'], 3600);
            return DataReturn('success', 0, $ret['data']['url']);
        }
        return DataReturn('返回支付url地址为空', -1);
    }

    /**
     * 支付回调处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function Respond($params = [])
    {
        // 参数
        if(empty($params['orderid']))
        {
            $params['orderid'] = MySession($this->pay_data_cache_key);
            if(empty($params['orderid']))
            {
                return DataReturn('通知订单id为空', -1);
            }
        }

        // 查询订单
        $request_params = [
            'mid'      => $this->config['mch_no'],
            'orderid'  => $params['orderid'],
        ];
        $ret = $this->HttpRequest('trans/order_query', $request_params);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $ret['data']['orderid'] = $params['orderid'];
        return DataReturn('支付成功', 0, $this->ReturnData($ret['data']));
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
        // 支付平台 - 订单号
        $data['trade_no']      = '';
        // 支付平台 - 用户
        $data['buyer_user']    = '';
        // 本系统发起支付的 - 订单号
        $data['out_trade_no']  = $data['orderid'];
        // 本系统发起支付的 - 商品名称
        $data['subject']       = '';
        // 本系统发起支付的 - 总价
        $data['pay_price']     = $data['pay_amount'];
        return $data;
    }

    /**
     * 网络请求
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $path [请求接口]
     * @param    [array]           $data [发送数据]
     * @return   [mixed]                 [请求返回数据]
     */
    private function HttpRequest($path, $data)
    {
        // 生成签名
        $sign = $this->CreatedSign($data);

        // url地址
        $url = 'https://api.qfllqhi.cn/'.$path.'?sign='.$sign;
        $ret = CurlPost($url, $data, 1);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $result = json_decode($ret['data'], true);
        if(isset($result['errcode']) && $result['errcode'] == 0)
        {
            $content = json_decode(base64_decode($result['data']), true);
            return DataReturn('success', 0, $content);
        }
        $msg = empty($result['errdesc']) ? '支付提交失败' : $result['errdesc'];
        return DataReturn($msg, -1);
    }

    /**
     * 签名字符串
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:38:28+0800
     * @param    [array]    $data [需要签名的数据]
     * @return   [string]         [签名结果]
     */
    private function CreatedSign($data)
    {
        return hash('sha256', $this->config['key'].json_encode($data));
    }
}
?>