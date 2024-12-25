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
 * 易票联 - 微信支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class EPayLinksWeixin
{
    // 插件配置参数
    private $config;

    // 证书路径
    private $private_key_dir_file = ROOT.'rsakeys'.DS.'payment_epaylinks'.DS.'private_key.pfx';
    private $efps_public_key_dir_file = ROOT.'rsakeys'.DS.'payment_epaylinks'.DS.'efps_public_key.cer';

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
            'name'          => '易票联-微信',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '支付宝支付、适用web端，支付链接生活、让生意更简单，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://www.epaylinks.cn/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'customer_code',
                'placeholder'   => '商户号',
                'title'         => '商户号',
                'is_required'   => 0,
                'message'       => '请填写商户号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'password',
                'placeholder'   => '证书密码',
                'title'         => '证书密码',
                'is_required'   => 0,
                'message'       => '请填写证书密码',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'sign_no',
                'placeholder'   => '证书号',
                'title'         => '证书号',
                'is_required'   => 0,
                'message'       => '请填写证书号',
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
            [
                'element'       => 'message',
                'message'       => '1. 将私钥文件证书按照[ private_key.pfx ]命名放入目录中['.$this->private_key_dir_file.']、如目录不存在自行创建即可<br />2. 将易票联公钥证书按照[ efps_public_key.cer ]命名放入目录中['.$this->efps_public_key_dir_file.']、如目录不存在自行创建即可',
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
        if(empty($this->config) || empty($this->config['customer_code']) || empty($this->config['password']) || empty($this->config['sign_no']))
        {
            return DataReturn('支付缺少配置', -1);
        }
        // 证书路径
        if(!file_exists($this->private_key_dir_file))
        {
            return DataReturn('私钥证书不存在', -1);
        }
        if(!file_exists($this->efps_public_key_dir_file))
        {
            return DataReturn('公钥证书不存在', -1);
        }

        // 订单信息
        if(!empty($params['business_data']) && !empty($params['business_data'][0]) && !empty($params['business_data'][0]['detail']))
        {
            $order_info = [
                'Id'            => $params['business_data'][0]['order_no'],
                'businessType'  => 100001,
                'goodsList'     => [],
            ];
            foreach($params['business_data'][0]['detail'] as $v)
            {
                $order_info['goodsList'][] = [
                    'goodsId'   => $v['goods_id'],
                    'name'      => $v['title'],
                    'price'     => (int) (($v['price']*1000)/10),
                    'number'    => $v['buy_number'],
                    'amount'    => (int) (($v['total_price']*1000)/10),
                ];
            }
        } else {
            $order_info = [
                'Id'            => $params['order_no'],
                'businessType'  => 100001,
                'goodsList'     => [['name'=>'支付','number'=>'one','amount'=>(int) (($params['total_price']*1000)/10)]],
            ];
        }

        // 支付参数
        $parameter = [
            'version'               => '3.0',
            'outTradeNo'            => $params['order_no'],
            'customerCode'          => $this->config['customer_code'],
            'clientIp'              => GetClientIP(),
            'orderInfo'             => $order_info,
            'payAmount'             => (int) (($params['total_price']*1000)/10),
            'payCurrency'           => 'CNY',
            'notifyUrl'             => $params['notify_url'],
            'redirectUrl'           => $params['call_back_url'],
            'attachData'            => $params['name'],
            'transactionStartTime'  => date('YmdHis'),
            'transactionEndTime'    => $this->OrderAutoCloseTime(),
            'payMethod'             => 6,
            'areaInfo'              => '350000',
            'nonceStr'              => md5(time().GetNumberCode()),
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 执行请求
        $ret = $this->HttpRequest('api/txs/pay/UnifiedPayment', $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        if(empty($ret['data']['casherUrl']))
        {
            return DataReturn('返回支付url地址为空', -1);
        }
        return DataReturn('success', 0, $ret['data']['casherUrl']);
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
        return date('YmdHis', time()+intval(MyC('common_order_close_limit_time', 30, true)));
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
        if(empty($params['outTradeNo']) || empty($params['transactionNo']))
        {
            return DataReturn('支付单号为空', -1);
        }

        // 查询参数
        $parameter = [
            'outTradeNo'     => $params['outTradeNo'],
            'transactionNo'  => $params['transactionNo'],
            'customerCode'   => $this->config['customer_code'],
            'nonceStr'       => md5(time().GetNumberCode()),
        ];

        // 执行请求
        $ret = $this->HttpRequest('api/txs/pay/PaymentQuery', $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 是否支付成功
        if(isset($ret['data']['payState']) && $ret['data']['payState'] == '00')
        {
            return DataReturn('支付成功', 0, $this->ReturnData($ret['data']));
        }
        $msg = empty($ret['data']['returnMsg']) ? '支付失败' : $ret['data']['returnMsg'].'('.$ret['data']['returnCode'].')';
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
        // 返回数据固定基础参数
        $buyer_user = empty($params['openId']) ? (empty($params['buyerId']) ? '' : $params['buyerId']) : $params['openId'];
        // 支付平台 - 订单号
        $data['trade_no']      = $data['transactionNo'];
        // 支付平台 - 用户
        $data['buyer_user']    = $buyer_user;
        // 本系统发起支付的 - 订单号
        $data['out_trade_no']  = $data['outTradeNo'];
        // 本系统发起支付的 - 商品名称
        $data['subject']       = empty($data['attachData']) ? '' : $data['attachData'];
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
            'customerCode'   => $this->config['customer_code'],
            'outRefundNo'    => $params['order_no'].GetNumberCode(),
            'outTradeNo'     => $params['order_no'],
            'transactionNo'  => $params['trade_no'],
            'amount'         => (int) (($params['pay_price']*1000)/10),
            'refundAmount'   => (int) (($params['refund_price']*1000)/10),
            'remark'         => $refund_reason,
            'nonceStr'       => md5(time().GetNumberCode()),
        ];

        // 执行请求
        $ret = $this->HttpRequest('api/txs/pay/Refund/V2', $parameter);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 统一返回格式
        return DataReturn('退款成功', 0, [
            'out_trade_no'    => isset($ret['data']['outTradeNo']) ? $ret['data']['outTradeNo'] : '',
            'trade_no'        => isset($ret['data']['transactionNo']) ? $ret['data']['transactionNo'] : '',
            'buyer_user'      => '',
            'refund_price'    => isset($ret['data']['refundAmount']) ? $ret['data']['refundAmount']/100 : 0.00,
            'return_params'   => $ret['data'],
            'request_params'  => $parameter,
        ]);
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
        $json_str = json_encode($data);
        $sign = $this->CreatedSign($json_str);
        if($sign['code'] != 0)
        {
            return $sign;
        }

        // url地址
        $url = 'https://efps.epaylinks.cn/';
        if(isset($this->config['is_dev_env']) && $this->config['is_dev_env'] == 1)
        {
            $url = 'http://test-efps.epaylinks.cn/';
        }
        $url .= $path;

        // curl请求
        $ch = curl_init();
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($json_str),
            'x-efps-sign-no:'.$this->config['sign_no'],
            'x-efps-sign-type:SHA256withRSA',
            'x-efps-sign:'.$sign['data'],
            'x-efps-timestamp:'.date('YmdHis'),
        ];
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        curl_close($ch);
        $reponse = json_decode($reponse, true);
        if(empty($reponse))
        {
            return DataReturn('请求失败、请稍后再试', -1);
        }
        if(isset($reponse['returnCode']) && $reponse['returnCode'] == '0000')
        {
            return DataReturn('success', 0, $reponse);
        }
        $msg = empty($reponse['returnMsg']) ? '返回数据格式错误' : $reponse['returnMsg'].'('.$reponse['returnCode'].')';
        return DataReturn($msg, -1);
    }

    /**
     * 签名验证
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:38:28+0800
     * @param    [string]                   $prestr [待验证的字符串]
     * @return   [string]                           [签名结果]
     */
    function VerifySign($prestr, $sign)
    {
        //读取公钥文件
        $pubKey = file_get_contents($this->efps_public_key_dir_file);
        $res = openssl_get_publickey($pubKey);
        if(empty($res))
        {
            return DataReturn('RSA公钥错误，请检查公钥文件格式是否正确', -1);
        }

        // 调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($prestr, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        //释放资源
        openssl_free_key($res);
        return $result;
    }

    /**
     * 签名字符串
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:38:28+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @return   [string]                           [签名结果]
     */
    private function CreatedSign($prestr)
    {
        $certs = [];
        openssl_pkcs12_read(file_get_contents($this->private_key_dir_file), $certs, $this->config['password']);
        if(empty($certs))
        {
            return DataReturn('请检查RSA私钥配置', -1);
        }

        openssl_sign($prestr, $sign, $certs['pkey'], OPENSSL_ALGO_SHA256);
        return DataReturn('success', 0, base64_encode($sign));
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
        return '{"returnCode":0000,"returnMsg":"success"}';
    }
}
?>