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
 * 翼支付
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-02-07
 * @desc    description
 */
class Bestpay
{
    // 插件配置参数
    private $config;

    // 证书路径
    private $p12_dir_file = ROOT.'rsakeys'.DS.'payment_bestpay'.DS.'bestpay.P12';

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
            'name'          => '翼支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc', 'h5'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适用翼支付APP中子应用模式发起支付，即时到帐支付方式，买家的交易资金直接打入卖家账户，快速回笼交易资金。 <a href="https://www.bestpay.com.cn/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'mch_id',
                'placeholder'   => '支付商户号',
                'title'         => '支付商户号',
                'is_required'   => 0,
                'message'       => '请填写支付分配的商户号',
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
                'element'       => 'message',
                'message'       => '将p12证书按照[bestpay.P12]命名放入目录中['.$this->p12_dir_file.']、如目录不存在自行创建即可',
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
        if(empty($this->config) || empty($this->config['mch_id']) || empty($this->config['password']))
        {
            return DataReturn('支付缺少配置', -1);
        }
        if(!file_exists($this->p12_dir_file))
        {
            return DataReturn('p12证书不存在', -1);
        }

        // 支付参数
        $parameter = [
            'merchantNo'    => $this->config['mch_id'],
            'outTradeNo'    => $params['order_no'],
            'tradeAmt'      => (string) (($params['total_price']*1000)/10),
            'ccy'           => '156',
            'requestDate'   => date('Y-m-d H:i:s'),
            'tradeChannel'  => 'APP',
            'accessCode'    => 'CASHIER',
            'mediumType'    => 'WIRELESS',
            'subject'       => $params['name'],
            'goodsInfo'     => $params['name'],
            'operator'      => $this->config['mch_id'],
            'notifyUrl'     => $params['notify_url'],
        ];
        // 风控参数
        $risk_control_info = [
            'service_identify'  => $params['order_no'],
            'subject'           => $params['name'],
            'product_type'      => '1',
            'order_ip'          => GetClientIP(),
        ];
        $parameter['riskControlInfo'] = json_encode($risk_control_info, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $parameter['sign'] = $this->CreateSign($this->GetSignContent($parameter));

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 下单
        $result = $this->HttpRequest('https://mapi.bestpay.com.cn/mapi/uniformReceipt/proCreateOrder', $parameter);
        if(!empty($result) && isset($result['success']) && $result['success'] == 1 && !empty($result['result']))
        {
            // 根据当前环境判断支付方式
            if(!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'Bestpay') !== false)
            {
                // APP中子应用发起支付
                $this->AppLaunchPay($parameter, $result['result'], $params);
            } else {
                return DataReturn('非APP环境中正在开发中...', -1);
            }
        }
        $msg = empty($result) ? '支付异常错误' : ((empty($result['errorMsg']) || !is_array($result)) ? $result : $result['errorMsg']);
        return DataReturn($msg, -1);
    }

    /**
     * 发起支付
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-07
     * @desc    description
     * @param   [array]          $data      [支付参数]
     * @param   [array]          $result    [返回数据]
     * @param   [array]          $params    [支付参数]
     */
    public function AppLaunchPay($data, $result, $params)
    {
        // 支付参数
        $parameter = [
            'merchantNo'        => $data['merchantNo'],
            'institutionCode'   => $data['merchantNo'],
            'institutionType'   => 'MERCHANT',
            'signType'          => 'CA',
            'platform'          => $this->ClientType(),
            'tradeType'         => 'acquiring',
            'outTradeNo'        => $data['outTradeNo'],
            'tradeNo'           => $result['tradeNo'],
            'tradeAmt'          => $data['tradeAmt'],
            'tradeDesc'         => $data['subject'],
        ];
        $parameter['sign'] = $this->CreateSign($this->GetSignContent($parameter));

        // 拼接html
        $html = '<!DOCTYPE html>
                    <html>
                    <head>
                    <meta charset="utf-8">
                    <title>安全支付</title>
                    <script src="https://h5.bestpay.com.cn/common/js/bestpay-html5-3.0.js" type="text/javascript" charset="utf-8"></script>
                    <script type="text/javascript">
                        BestpayHtml5.config();
                        BestpayHtml5.Payment.payForSimpleCheckout('.json_encode($parameter, JSON_UNESCAPED_UNICODE).', function(res)
                        {
                            window.location.replace("'.$params['redirect_url'].'");
                        }, function (res) {
                            window.location.replace("'.$params['redirect_url'].'");
                        }, function (res) {
                            window.location.replace("'.$params['redirect_url'].'");
                        });
                    </script>
                    </head>
                    <body>
                    </body>
            </html>';
        die($html);
    }

    /**
     * 客户端
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-08
     * @desc    description
     */
    public function ClientType()
    {
        if(!empty($_SERVER['HTTP_USER_AGENT']))
        {
            // 安卓手机
            if(stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false)
            {
                return 'android_4.0';
            }

            // ios手机/ipad
            if(stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'ipad'))
            {
                return 'ios_4.0';
            }
        }
        return 'other';
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
        if(!empty($params['outTradeNo']))
        {
            // 查询订单
            $parameter = [
                'outTradeNo'    => $params['outTradeNo'],
                'merchantNo'    => $this->config['mch_id'],
                'tradeDate'     => date('Y-m-d H:i:s'),
            ];

            // 生成签名参数+签名
            $parameter['sign'] = $this->CreateSign($this->GetSignContent($parameter));

            // 查询订单
            $result = $this->HttpRequest('https://mapi.bestpay.com.cn/mapi/uniformReceipt/tradeQuery', $parameter);
            if(!empty($result) && isset($result['success']) && $result['success'] == 1 && !empty($result['result']) && isset($result['result']['tradeStatus']) && $result['result']['tradeStatus'] == 'SUCCESS')
            {
                return DataReturn('支付成功', 0, $this->ReturnData($result['result']));
            }
        }
        return DataReturn('处理异常错误', -100);
    }
    
    /**
     * 返回数据统一格式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-08
     * @desc    description
     * @param   [array]          $data [支付数据]
     */
    private function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = $data['tradeNo'];       // 支付平台 - 订单号
        $data['buyer_user']     = $data['buyerLoginNo'];  // 支付平台 - 用户
        $data['out_trade_no']   = $data['outTradeNo'];    // 本系统发起支付的 - 订单号
        $data['subject']        = $data['subject'];       // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['tradeAmt']/100;  // 本系统发起支付的 - 总价
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
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pay_time',
                'error_msg'         => '支付时间不能为空',
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
            'merchantNo'        => $this->config['mch_id'],
            'outTradeNo'        => $params['order_no'],
            'outRequestNo'      => $params['order_no'].GetNumberCode(),
            'originalTradeDate' => date('Y-m-d H:i:s', $params['pay_time']),
            'refundAmt'         => (int) (($params['refund_price']*1000)/10),
            'requestDate'       => date('Y-m-d H:i:s'),
            'operator'          => $this->config['mch_id'],
            'tradeChannel'      => 'APP',
            'ccy'               => '156',
            'accessCode'        => 'CASHIER',
            'remark'            => $refund_reason,
        ];

        // 生成签名参数+签名
        $parameter['sign'] = $this->CreateSign($this->GetSignContent($parameter));

        // 退款
        $result = $this->HttpRequest('https://mapi.bestpay.com.cn/mapi/uniformReceipt/tradeRefund', $parameter);
        if(!empty($result) && isset($result['success']) && $result['success'] == 1)
        {
            if(!empty($result['result']) && isset($result['result']['tradeStatus']) && $result['result']['tradeStatus'] == 'SUCCESS')
            {
                // 统一返回格式
                $data = [
                    'out_trade_no'    => isset($result['result']['outTradeNo']) ? $result['result']['outTradeNo'] : '',
                    'trade_no'        => isset($result['result']['tradeNo']) ? $result['result']['tradeNo'] : (isset($result['err_code_des']) ? $result['err_code_des'] : ''),
                    'buyer_user'      => isset($result['result']['buyerLoginNo']) ? $result['result']['buyerLoginNo'] : '',
                    'refund_price'    => isset($result['result']['refundAmt']) ? $result['result']['refundAmt']/100 : 0.00,
                    'return_params'   => $result['result'],
                    'request_params'  => $parameter,
                ];
                return DataReturn('退款成功', 0, $data);
            } else {
                return DataReturn($result['result']['tradeResultDesc'], -1);
            }
        }

        $msg = empty($result) ? '退款接口异常' : ((empty($result['errorMsg']) || !is_array($result)) ? $result : $result['errorMsg']);
        return DataReturn($msg, -1);
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

    /**
     * 获取签名内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-07
     * @desc    description
     * @param   [array]          $params [需要签名的参数]
     */
    public function GetSignContent($params)
    {
        ksort($params);
        $string = "";
        $i = 0;
        foreach($params as $k => $v)
        {
            if(!empty($v) && "@" != substr($v, 0, 1) && $k != 'sign')
            {
                if ($i == 0) {
                    $string .= "$k" . "=" . "$v";
                } else {
                    $string .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset($k, $v);
        return $string;
    }

    /**
     * 签名生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-07
     * @desc    description
     * @param   [string]          $params [需要签名的参数]
     */
    public function CreateSign($params)
    {
        $cer_key = file_get_contents($this->p12_dir_file);
        openssl_pkcs12_read($cer_key, $certs, $this->config['password']);
        openssl_sign($params, $sign_msg, $certs['pkey'], OPENSSL_ALGO_SHA256);
        return $sign_msg ? base64_encode($sign_msg) : '';
    }

    /**
     * 自定义成功返回内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-01
     * @desc    description
     * @param   [string]          $params [需要签名的参数]
     */
    public function SuccessReturn($params = [])
    {
        if(empty($params))
        {
            $params = input();
        }
        return '{"success":true,"result":{"statusCode": 200,"outTradeNo":"'.$params['outTradeNo'].'","tradeNo":"'.$params['tradeNo'].'"}}';
    }

    /**
     * 自定义失败返回内容
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-01
     * @desc    description
     * @param   [string]          $params [需要签名的参数]
     */
    public function ErrorReturn($params = [])
    {
        return '{"success":false,"result":{"statusCode":400,"outTradeNo":"","tradeNo":""}}';
    }
}
?>