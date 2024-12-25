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
 * 银联商务 - 微信
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class ChinaUmsWeixin
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
            'name'          => '银联商务-微信',  // 插件名称
            'version'       => '1.0.2',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['weixin'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用微信小程序，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://www.chinaums.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'msg_id',
                'placeholder'   => '来源编号',
                'title'         => '来源编号',
                'is_required'   => 0,
                'message'       => '请填写来源编号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'msg_src',
                'placeholder'   => '消息来源',
                'title'         => '消息来源',
                'is_required'   => 0,
                'message'       => '请填写消息来源',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'key',
                'placeholder'   => '通讯密钥',
                'title'         => '通讯密钥',
                'is_required'   => 0,
                'message'       => '请填写通讯密钥',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mid',
                'placeholder'   => '商户号',
                'title'         => '商户号',
                'is_required'   => 0,
                'message'       => '请填写商户号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'tid',
                'placeholder'   => '终端号',
                'title'         => '终端号',
                'is_required'   => 0,
                'message'       => '请填写终端号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'appid',
                'placeholder'   => '小程序appid',
                'title'         => '小程序appid',
                'is_required'   => 0,
                'message'       => '请填写小程序appid',
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
        $parameter = [
            'msgId'             => $this->config['msg_id'],
            'msgSrc'            => $this->config['msg_src'],
            'mid'               => $this->config['mid'],
            'tid'               => $this->config['tid'],
            'subAppId'          => $this->config['appid'],
            'msgType'           => 'wx.unifiedOrder',
            'notifyUrl'         => $params['notify_url'],
            'requestTimestamp'  => $this->OrderAutoCloseTime(),
            'merOrderId'        => $this->config['msg_id'].$params['order_no'],
            'totalAmount'       => (string) (($params['total_price']*1000)/10),
            'orderDesc'         => $params['name'],
            'subOpenId'         => $params['user']['weixin_openid'],
            'tradeType'         => 'MINI',
            'signType'          => 'SHA256',
        ];

        // 生成签名参数+签名
        $parameter['sign'] = $this->CreateSign($parameter);

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 下单
        $result = $this->HttpRequest('https://qr.chinaums.com/netpay-route-server/api/', $parameter);
        if(!empty($result) && isset($result['errCode']) && $result['errCode'] == 'SUCCESS' && !empty($result['miniPayRequest']))
        {
            return DataReturn('success', 0, $result['miniPayRequest']);
        }
        return DataReturn(empty($result) ? '支付接口请求失败' : (empty($result['errMsg']) ? $result : $result['errMsg']), -1);
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
        return date('Y-m-d H:i:s', time()+$time);
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
        // 参数
        if(empty($this->config))
        {
            return DataReturn('配置有误', -1);
        }
        if(empty($params) || empty($params['status']))
        {
            return DataReturn('支付参数有误', -1);
        }

        // 退款通直接成功结束
        if($params['status'] == 'TRADE_REFUND')
        {
            die('SUCCESS');
        }

        // 状态
        if($params['status'] == 'TRADE_SUCCESS')
        {
            if($params['sign'] != $this->CreateSign($params))
            {
                return DataReturn('签名验证失败', -1);
            }
            return DataReturn('支付成功', 0, $this->ReturnData($params));
        }
        return DataReturn('处理异常错误', -100);
    }

    /**
     * 返回数据统一格式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-28
     * @desc    description
     * @param   [array]           $data [返回数据]
     */
    private function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = $data['seqId'];  // 支付平台 - 订单号
        $data['buyer_user']     = isset($data['subBuyerId']) ? $data['subBuyerId'] : '';          // 支付平台 - 用户
        $data['out_trade_no']   = str_replace($this->config['msg_id'], '', $data['merOrderId']);    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['attachedData']) ? $data['attachedData'] : '';          // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['totalAmount']/100;   // 本系统发起支付的 - 总价
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

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // 请求参数
        $parameter = [
            'msgId'             => $this->config['msg_id'],
            'msgSrc'            => $this->config['msg_src'],
            'mid'               => $this->config['mid'],
            'tid'               => $this->config['tid'],
            'msgType'           => 'refund',
            'signType'          => 'SHA256',
            'requestTimestamp'  => date('Y-m-d H:i:s'),
            'targetOrderId'     => $params['trade_no'],
            'merOrderId'        => $this->config['msg_id'].$params['order_no'],
            'refundAmount'      => (int) (($params['refund_price']*1000)/10),
            'refundDesc'        => $refund_reason,
        ];
        $parameter['sign'] = $this->CreateSign($parameter);

        // 请求接口处理
        $result = $this->HttpRequest('https://qr.chinaums.com/netpay-route-server/api/', $parameter);
        if(!empty($result) && isset($result['errCode']) && $result['errCode'] == 'SUCCESS')
        {
            // 统一返回格式
            $data = [
                'out_trade_no'    => isset($result['merOrderId']) ? $result['merOrderId'] : '',
                'trade_no'        => isset($result['refundOrderId']) ? $result['refundOrderId'] : '',
                'buyer_user'      => '',
                'refund_price'    => isset($result['refundAmount']) ? $result['refundAmount']/100 : 0.00,
                'return_params'   => $result,
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        }
        return DataReturn(empty($result) ? '支付接口请求失败' : (empty($result['errMsg']) ? $result : $result['errMsg'].'['.$result['errCode'].']'), -1);
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
    private function CreateSign($params = [])
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
        return strtoupper(hash('sha256', substr($sign, 0, -1).$this->config['key']));
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
}
?>