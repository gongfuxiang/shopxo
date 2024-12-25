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
 * JeePay - 微信支付
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class JeePayWeixin
{
    // 插件配置参数
    private $config;

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
            'name'          => 'JeePay-微信',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '微信支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://pay.zhishouying.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'name'          => 'appid',
                'placeholder'   => '应用ID',
                'title'         => '应用ID',
                'is_required'   => 0,
                'message'       => '请填写应用ID',
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
        if(empty($this->config) || empty($this->config['mch_no']) || empty($this->config['appid']) || empty($this->config['key']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 支付参数
        $parameter = [
            'mchNo'        => $this->config['mch_no'],
            'appId'        => $this->config['appid'],
            'mchOrderNo'   => $params['order_no'],
            'wayCode'      => 'WX_NATIVE',
            'amount'       => (int) (($params['total_price']*1000)/10),
            'currency'     => 'cny',
            'clientIp'     => GetClientIP(),
            'subject'      => $params['name'],
            'body'         => $params['name'],
            'notifyUrl'    => $params['notify_url'],
            'returnUrl'    => $params['call_back_url'],
            'expiredTime'  => $this->OrderAutoCloseTime(),
            'reqTime'      => time(),
            'version'      => '1.0',
            'signType'     => 'MD5',
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 执行请求
        $ret = $this->HttpRequest('api/pay/unifiedOrder', $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        if(!empty($ret['data']['payData']))
        {
            $pay_params = [
                'type'      => 'weixin',
                'url'       => $ret['data']['payData'],
                'order_no'  => $params['order_no'],
                'name'      => '微信支付',
                'msg'       => '打开微信APP扫一扫进行支付',
                'check_url' => $params['check_url'],
            ];
            MySession('payment_qrcode_data', $pay_params);
            return DataReturn('success', 0, MyUrl('index/pay/qrcode'));
        }
        return DataReturn('返回支付url地址为空', -1);
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
        return intval(MyC('common_order_close_limit_time', 30, true));
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
        if(empty($params['sign']))
        {
            return DataReturn('签名或支付数据为空', -1);
        }
        if($this->CreatedSign($params) != $params['sign'])
        {
            return DataReturn('回签验证失败', -1);
        }

        // 是否支付成功
        if(isset($params['state']) && $params['state'] == 2)
        {
            return DataReturn('支付成功', 0, $this->ReturnData($params));
        }
        $msg = empty($params['errMsg']) ? '支付失败' : $params['errMsg'];
        return DataReturn($msg, -1);
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
        $data['trade_no']      = $data['payOrderId'];
        // 支付平台 - 用户
        $data['buyer_user']    = $data['mchName'];
        // 本系统发起支付的 - 订单号
        $data['out_trade_no']  = $data['mchOrderNo'];
        // 本系统发起支付的 - 商品名称
        $data['subject']       = $data['subject'];
        // 本系统发起支付的 - 总价
        $data['pay_price']     = $data['amount']/100;
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
            'mchNo'         => $this->config['mch_no'],
            'appId'         => $this->config['appid'],
            'mchOrderNo'    => $params['order_no'],
            'payOrderId'    => $params['trade_no'],
            'mchOrderNo'    => $params['order_no'],
            'mchRefundNo'   => $params['order_no'].GetNumberCode(),
            'refundAmount'  => (int) (($params['refund_price']*1000)/10),
            'currency'      => 'cny',
            'refundReason'  => $refund_reason,
            'clientIp'      => GetClientIP(),
            'reqTime'       => time(),
            'version'       => '1.0',
            'signType'      => 'MD5',
        ];

        // 执行请求
        $ret = $this->HttpRequest('api/refund/refundOrder', $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        if(isset($ret['data']['state']) && in_array($ret['data']['state'], [1,2]))
        {
            // 统一返回格式
            return DataReturn('退款成功', 0, [
                'out_trade_no'    => isset($ret['data']['mchRefundNo']) ? $ret['data']['mchRefundNo'] : '',
                'trade_no'        => isset($ret['data']['refundOrderId']) ? $ret['data']['refundOrderId'] : '',
                'buyer_user'      => '',
                'refund_price'    => isset($ret['data']['refundAmount']) ? $ret['data']['refundAmount']/100 : 0.00,
                'return_params'   => $ret['data'],
                'request_params'  => $parameter,
            ]);
        }
        return DataReturn('退款失败', -1);
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
        $data['sign'] = $this->CreatedSign($data);

        // url地址
        $url = 'https://pay.zhishouying.com/'.$path;
        $ret = CurlPost($url, $data);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $result = json_decode($ret['data'], true);
        if(isset($result['sign']) && isset($result['code']) && $result['code'] == 0 && !empty($result['data']))
        {
            if($this->CreatedSign($result['data']) != $result['sign'])
            {
                return DataReturn('回签验证失败', -1);
            }
            return DataReturn('success', 0, $result['data']);
        }
        $msg = (empty($result['data']) || empty($result['data']['errMsg'])) ? '支付提交失败' : $result['data']['errMsg'];
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
        ksort($data);
        $sign  = '';
        foreach($data as $k=>$v)
        {
            if($k != 'sign' && $v != '' && $v != null)
            {
                $sign .= "$k=$v&";
            }
        }
        return strtoupper(md5($sign.'key='.$this->config['key']));
    }
}
?>