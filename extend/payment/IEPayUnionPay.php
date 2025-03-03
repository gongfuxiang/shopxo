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

use app\service\PayLogService;

/**
 * IEPay - 银联
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-22
 * @desc    description
 */
class IEPayUnionPay
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
            'name'          => 'IEPay银联',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适用PC+H5，即时到帐支付方式，买家的交易资金直接打入卖家账户，新西兰货币支付。 <a href="https://www.mypaynz.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mid',
                'placeholder'   => '商户ID',
                'title'         => '商户ID',
                'is_required'   => 0,
                'message'       => '请填写商户ID',
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

        // 支付参数
        $parameter = [
            'mid'               =>  $this->config['mid'],
            'total_fee'         => (int) (($params['total_price']*1000)/10),
            'goods'             => $params['name'],
            'out_trade_no'      => $params['order_no'],
            'return_url'        => $params['call_back_url'].'?out_trade_no='.$params['order_no'],
            'notify_url'        => $params['notify_url'],
            'pay_type'          => $this->GetPayType(),
            'version'           => 'v1',
        ];

        // 生成签名
        $parameter['sign'] = $this->SignCreated($parameter);

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求post
        $result = $this->HttpRequest('https://a.mypaynz.com/api/online', $parameter);
        if(isset($result['is_success']) && $result['is_success'] == 'TRUE' && !empty($result['extra']) && !empty($result['extra']['pay_url']))
        {
            return DataReturn('处理成功', 0, $result['extra']['pay_url']);
        }
        return DataReturn(empty($result['message']) ? '支付错误' : $result['message'], -100);
    }

    /**
     * 获取支付平台类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-22
     * @desc    description
     * @param   [string]          $client_type [订单客户端类型]
     */
    private function GetPayType($client_type = '')
    {
        return 'IE0031';
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
        // 支付参数
        $data = input();
        if(empty($data['out_trade_no']))
        {
            return DataReturn('支付平台返回的订单号为空', -1);
        }

        // 远程查询支付状态
        $parameter = [
            'mid'               => $this->config['mid'],
            'pay_type'          => $this->GetPayType(),
            'out_trade_no'      => $data['out_trade_no'],
            'version'           => 'v1',
        ];

        // 生成签名
        $parameter['sign'] = $this->SignCreated($parameter);

        // 请求post
        $result = $this->HttpRequest('https://a.mypaynz.com/api/check_order_status', $parameter);
        if(isset($result['is_success']) && $result['is_success'] == 'TRUE' && !empty($result['extra']))
        {
            if(isset($result['extra']['order_status']) && $result['extra']['order_status'] == 1)
            {
                return DataReturn('支付成功', 0, $this->ReturnData($result['extra']));
            } else {
                $arr = [
                    0 => '未付款',
                    1 => '已付款',
                    2 => '已退款',
                    3 => '已关闭',
                ];
                $msg = isset($arr[$result['extra']['order_status']]) ? $arr[$result['extra']['order_status']] : '支付失败';
                return DataReturn('平台订单'.$msg, -1);
            }
        }
        return DataReturn(empty($result['message']) ? '支付失败' : $result['message'], -100);
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
        $data['trade_no']       = $data['trade_no'];        // 支付平台 - 订单号
        $data['buyer_user']     = $data['pay_type'];       // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['order_status']) ? '状态:'.$data['order_status'] : ''; // 本系统发起支付的 - 商品名称
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
                'key_name'          => 'refund_price',
                'error_msg'         => '退款金额不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'client_type',
                'error_msg'         => '订单客户端类型不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 远程查询支付状态
        $parameter = [
            'mid'               => $this->config['mid'],
            'pay_type'          => $this->GetPayType($params['client_type']),
            'out_trade_no'      => $params['order_no'],
            'refund_amount'     => (int) (($params['refund_price']*1000)/10),
            'refund_charge_fee' => 'TRUE',
            'version'           => 'v1',
        ];

        // 生成签名
        $parameter['sign'] = $this->SignCreated($parameter);

        // 请求post
        $result = $this->HttpRequest('https://a.mypaynz.com/api/refund', $parameter);
        if(isset($result['is_success']) && $result['is_success'] == 'TRUE')
        {
            // 统一返回格式
            $data = [
                'out_trade_no'  => isset($result['out_trade_no']) ? $result['out_trade_no'] : '',
                'trade_no'      => isset($result['trade_no']) ? $result['trade_no'] : '',
                'buyer_user'    => isset($result['buyer_user_id']) ? $result['buyer_user_id'] : '',
                'refund_price'  => isset($result['refund_fee']) ? $result['refund_fee']/100 : $params['refund_price'],
                'return_params' => $result,
            ];
            return DataReturn('退款成功', 0, $data);
        }
        return DataReturn(empty($result['message']) ? '退款失败' : $result['message'], -100);
    }

    /**
     * [HttpRequest 网络请求]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $url  [请求url]
     * @param    [array]           $data [发送数据]
     * @return   [mixed]                 [请求返回数据]
     */
    private function HttpRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $body_string = '';
        if(is_array($data) && 0 < count($data))
        {
            foreach($data as $k => $v)
            {
                $body_string .= $k.'='.urlencode($v).'&';
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body_string);
        }
        $headers = array('content-type: application/x-www-form-urlencoded;charset=UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if(curl_errno($ch))
        {
            return false;
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode)
            {
                return false;
            }
        }
        curl_close($ch);
        return json_decode($reponse, true);
    }

    /**
     * 签名生成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]          $params [需要签名的参数]
     */
    public function SignCreated($params)
    {
        ksort($params);
        $sign  = '';
        foreach($params as $k=>$v)
        {
            if(!in_array($k, ['sign', 'sign_type']) && $v != '' && $v != null)
            {
                $sign .= "$k=$v&";
            }
        }
        return strtolower(md5(substr($sign, 0, -1).$this->config['key']));
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
        return 'SUCCESS';
    }
}
?>