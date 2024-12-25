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
 * 杉德聚合支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class SandPayGather
{
    // 插件配置参数
    private $config;

    // 接口地址
    private $url = [
        0 => [
            'pay'     => 'https://openapi.sandpay.com.cn/v4/sd-receipts/api/trans/trans.order.create',
            'refund'  => 'https://openapi.sandpay.com.cn/v4/sd-receipts/api/trans/trans.order.refund',
            'query'   => 'https://openapi.sandpay.com.cn/v4/sd-receipts/api/trans/trans.order.query',
        ],
        1 => [
            'pay'     => 'https://openapi-uat01.sand.com.cn/v4/sd-receipts/api/trans/trans.order.create',
            'refund'  => 'https://openapi-uat01.sand.com.cn/v4/sd-receipts/api/trans/trans.order.refund',
            'query'   => 'https://openapi-uat01.sand.com.cn/v4/sd-receipts/api/trans/trans.order.query',
        ],
    ];

    // 证书路径
    private $private_dir_file = ROOT.'rsakeys'.DS.'payment_sandpaygather'.DS.'private.pfx';
    private $public_dir_file = ROOT.'rsakeys'.DS.'payment_sandpaygather'.DS.'public.cer';

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
            'name'          => '杉德聚合支付',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '适用pc端，即时到帐支付方式，买家的交易资金直接打入卖家杉德支付账户，快速回笼交易资金。 <a href="https://www.sandpay.com.cn/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'placeholder'   => '商户编号',
                'title'         => '商户编号',
                'is_required'   => 0,
                'message'       => '请填写商户编号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'access_mid',
                'placeholder'   => '私钥密码',
                'title'         => '私钥密码',
                'is_required'   => 0,
                'message'       => '请填写私钥密码',
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
                'message'       => '1. 私钥文件[private.pfx]命名放入目录中['.$this->private_dir_file.']、如目录不存在自行创建即可<br />2. 公钥文件[public.cer]命名放入目录中['.$this->public_dir_file.']、如目录不存在自行创建即可<br />3. 退款通知记录申报写当前站点地址[ '.__MY_DOMAIN__.' ]',
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
            'marketProduct'  => 'CSDB',
            'outReqTime'     => date('YmdHis'),
            'mid'            => $this->config['mid'],
            'outOrderNo'     => $params['order_no'],
            'description'    => $params['name'],
            'goodsClass'     => 99,
            'amount'         => $params['total_price'],
            'payType'        => 'CUPPAY',
            'payerInfo'     => [
                'payAccLimit' => ''
            ],
            'payMode'        => 'QR',
            'timeOut'        => $this->OrderAutoCloseTime(),
            'notifyUrl'      => $params['notify_url'],
            'riskmgtInfo'    => [
                'sourceIp' => GetClientIP(),
            ],
        ];

        // 随机数
        $rand = RandomString(16);

        // 报文加密
        $biz_data = $this->AESEncrypt($base_parameter, $rand);
        if($biz_data['code'] != 0)
        {
            return $biz_data;
        }

        // key
        $key = $this->RSAEncryptByPub($rand);
        if($key['code'] != 0)
        {
            return $key;
        }

        // 签名
        $sign = $this->SignCreated($biz_data['data']);
        if($sign['code'] != 0)
        {
            return $sign;
        }

        // 整体请求参数
        $parameter = [
            'accessMid'    => $this->config['mid'],
            'timestamp'    => date('Y-m-d H:i:s'),
            'version'      => '4.0.0',
            'signType'     => 'RSA',
            'sign'         => $sign['data'],
            'encryptType'  => 'AES',
            'encryptKey'   => $key['data'],
            'bizData'      => $biz_data['data'],
        ];

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 请求接口
        $ret = $this->HttpRequest('pay', $parameter);
        if($ret['code'] == 0)
        {
            if(!empty($ret['data']['credential']) && !empty($ret['data']['credential']['qrCode']))
            {
                $pay_params = [
                    'type'      => 'unionpay',
                    'url'       => $ret['data']['credential']['qrCode'],
                    'order_no'  => $params['order_no'],
                    'name'      => '银联支付',
                    'msg'       => '打开云闪付APP扫一扫进行支付',
                    'check_url' => $params['check_url'],
                ];
                MySession('payment_qrcode_data', $pay_params);
                return DataReturn('success', 0, MyUrl('index/pay/qrcode'));
            }
            return DataReturn('支付请求返回数据错误', -1);
        }
        return $ret;
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
            'marketProduct'  => 'CSDB',
            'outReqTime'     => date('YmdHis'),
            'mid'            => $this->config['mid'],
            'outOrderNo'     => GetNumberCode(10),
            'oriOutOrderNo'  => $params['order_no'],
            'amount'         => $params['refund_price'],
            'notifyUrl'      => __MY_DOMAIN__,
            'refundReason'   => $refund_reason,
        ];

        // 随机数
        $rand = RandomString(16);

        // 报文加密
        $biz_data = $this->AESEncrypt($base_parameter, $rand);
        if($biz_data['code'] != 0)
        {
            return $biz_data;
        }

        // key
        $key = $this->RSAEncryptByPub($rand);
        if($key['code'] != 0)
        {
            return $key;
        }

        // 签名
        $sign = $this->SignCreated($biz_data['data']);
        if($sign['code'] != 0)
        {
            return $sign;
        }

        // 整体请求参数
        $parameter = [
            'accessMid'    => $this->config['mid'],
            'timestamp'    => date('Y-m-d H:i:s'),
            'version'      => '4.0.0',
            'signType'     => 'RSA',
            'sign'         => $sign['data'],
            'encryptType'  => 'AES',
            'encryptKey'   => $key['data'],
            'bizData'      => $biz_data['data'],
        ];
        // 请求接口
        $ret = $this->HttpRequest('refund', $parameter);
        if($ret['code'] == 0)
        {
            // 统一返回格式
            $res = $ret['data'];
            $data = [
                'out_trade_no'    => $params['order_no'],
                'trade_no'        => isset($res['sandSerialNo']) ? $res['sandSerialNo'] : '',
                'buyer_user'      => '',
                'refund_price'    => isset($result['amount']) ? $result['amount'] : 0,
                'return_params'   => $res,
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        } else {
            // 是否没有金额可退了
            if(stripos($ret['msg'], 'P05012') !== false)
            {
                $data = [
                    'out_trade_no'    => $params['order_no'],
                    'trade_no'        => '',
                    'buyer_user'      => '',
                    'refund_price'    => $params['refund_price'],
                    'return_params'   => $ret['data'],
                    'request_params'  => $parameter,
                ];
                return DataReturn('退款成功', 0, $data);
            }
        }
        return $ret;
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
        if(empty($params['bizData']))
        {
            return DataReturn('支付数据为空', -1);
        }
        $temp_biz_data = json_decode(htmlspecialchars_decode($params['bizData']), true);

        // 请求参数
        $base_parameter = [
            'marketProduct'  => 'CSDB',
            'outReqTime'     => date('YmdHis'),
            'mid'            => $this->config['mid'],
            'outOrderNo'     => $temp_biz_data['outOrderNo'],
        ];

        // 随机数
        $rand = RandomString(16);

        // 报文加密
        $biz_data = $this->AESEncrypt($base_parameter, $rand);
        if($biz_data['code'] != 0)
        {
            return $biz_data;
        }

        // key
        $key = $this->RSAEncryptByPub($rand);
        if($key['code'] != 0)
        {
            return $key;
        }

        // 签名
        $sign = $this->SignCreated($biz_data['data']);
        if($sign['code'] != 0)
        {
            return $sign;
        }

        // 整体请求参数
        $parameter = [
            'accessMid'    => $this->config['mid'],
            'timestamp'    => date('Y-m-d H:i:s'),
            'version'      => '4.0.0',
            'signType'     => 'RSA',
            'sign'         => $sign['data'],
            'encryptType'  => 'AES',
            'encryptKey'   => $key['data'],
            'bizData'      => $biz_data['data'],
        ];
        // 请求接口
        $ret = $this->HttpRequest('query', $parameter);
        if($ret['code'] == 0)
        {
            return DataReturn('支付成功', 0, $this->ReturnData($ret['data']));
        }
        return $ret;
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
        $data['trade_no']       = $data['channelOrderNo'];        // 支付平台 - 订单号
        $data['buyer_user']     = (!empty($data['payer']) && !empty($data['payer']['payerLogonNo'])) ? $data['payer']['payerLogonNo'] : '';  // 支付平台 - 用户
        $data['out_trade_no']   = $data['outOrderNo'];    // 本系统发起支付的 - 订单号
        $data['subject']        = ''; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['amount'];    // 本系统发起支付的 - 总价

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
     * @return   [mixed]                   [请求返回数据]
     */
    private function HttpRequest($type, $data)
    {
        $is_dev_env = isset($this->config['is_dev_env']) ? $this->config['is_dev_env'] : 0;
        $ret = CurlPost($this->url[$is_dev_env][$type], $data, 1);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $result = json_decode($ret['data'], true);
        if(isset($result['respCode']) && $result['respCode'] == 'success')
        {
            // step6: 使用公钥验签报文$decryptPlainText
            $verify = $this->VerifyCheck($result['bizData'], $result['sign']);
            if($verify['code'] != 0)
            {
                return $verify;
            }
            // step7: 使用私钥解密AESKey
            $decrypt = $this->RSADecryptByPri($result['encryptKey']);
            if($decrypt['code'] != 0)
            {
                return $decrypt;
            }

            // step8: 使用解密后的AESKey解密报文
            $result_data = $this->AESDecrypt($result['bizData'], $decrypt['data']);
            if($result_data['code'] != 0)
            {
                return $result_data;
            }
            $result_data = json_decode($result_data['data'], true);
            if(!empty($result_data) && isset($result_data['resultStatus']) && in_array($result_data['resultStatus'], ['success', 'accept']))
            {
                return DataReturn('success', 0, $result_data);
            }
            $msg = (empty($result_data['errorDesc']) ? '解密数据有误' : $result_data['errorDesc']).(empty($result_data['errorCode']) ? '' : '('.$result_data['errorCode'].')');
            return DataReturn($msg, -1);
        }
        $msg = empty($result['message']) ? (empty($result['error']) ? (empty($result['respDesc']) ? '支付接口请求错误' : $result['respDesc']) : $result['error']) : $result['message'];
        return DataReturn($msg, -1);
    }

    /**
     * AES解密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-29
     * @desc    description
     * @param   [string]          $data [数据]
     * @param   [string]          $key  [key]
     */
    function AESDecrypt($data, $key)
    {
        $result = openssl_decrypt(base64_decode($data), 'AES-128-ECB', $key, 1);
        if($result)
        {
            return DataReturn('success', 0, $result);
        }
        return DataReturn('报文解密错误', -1);
    }

    /**
     * 私钥解密AESKey
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-29
     * @desc    description
     * @param   [string]          $data [数据]
     */
    function RSADecryptByPri($data)
    {
        $pri = $this->LoadPk12Cert();
        if($pri['code'] != 0)
        {
            return $pri;
        }

        if(openssl_private_decrypt(base64_decode($data), $result, $pri['data'], OPENSSL_PKCS1_PADDING))
        {
            return DataReturn('success', 0, $result);
        }
        return DataReturn('AESKey解密错误', -1);
    }

    /**
     * 公钥验签
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-29
     * @desc    description
     * @param   [string]          $data [数据]
     * @param   [string]          $sign [签名]
     */
    function VerifyCheck($data, $sign)
    {
        $puk = $this->LoadX509Cert();
        if($puk['code'] != 0)
        {
            return $puk;
        }

        $resource = openssl_pkey_get_public($puk['data']);
        $result = openssl_verify($data, base64_decode($sign), $resource, 'SHA256');
        if($result)
        {
            return DataReturn('success', 0, $result);
        }
        return DataReturn('签名验证未通过', -1);
    }

    /**
     * AES加密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-28
     * @desc    description
     * @param   [array]          $data [数据]
     * @param   [string]         $key  [随机key]
     */
    function AESEncrypt($data, $key)
    {
        ksort($data);
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-ECB");
        $iv =  $ivlen ? openssl_random_pseudo_bytes($ivlen) : '';
        $result = openssl_encrypt($data, 'AES-128-ECB', $key, OPENSSL_RAW_DATA, $iv);
        if(empty($result))
        {
            return DataReturn('报文加密错误', -1);
        }
        return DataReturn('success', 0, base64_encode($result));
    }

    /**
     * AES加密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-28
     * @desc    description
     * @param   [array]          $data [数据]
     * @param   [string]         $key  [随机key]
     */
    function RSAEncryptByPub($rand)
    {
        $puk = $this->LoadX509Cert();
        if($puk['code'] != 0)
        {
            return $puk;
        }
        if(!openssl_public_encrypt($rand, $result, $puk['data'], OPENSSL_PKCS1_PADDING))
        {
            return DataReturn('AESKey加密错误', -1);
        }
        return DataReturn('success', 0, base64_encode($result));
    }

    /**
     * 获取公钥
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-28
     * @desc    description
     */
    function LoadX509Cert()
    {
        $file = file_get_contents($this->public_dir_file);
        if(!$file)
        {
            return DataReturn('loadx509Cert::file_get_contents ERROR', -1);
        }

        $cert = chunk_split(base64_encode($file), 64, "\n");
        $cert = "-----BEGIN CERTIFICATE-----\n" . $cert . "-----END CERTIFICATE-----\n"; 
        $res = openssl_pkey_get_public($cert);
        $detail = openssl_pkey_get_details($res);
        if($detail)
        {
            return DataReturn('success', 0, $detail['key']);
        }
        return DataReturn('LoadX509Cert::openssl_pkey_get_details ERROR', -1);
    }

    /**
     * 获取私钥
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-28
     * @desc    description
     */
    function LoadPk12Cert()
    {
        $file = file_get_contents($this->private_dir_file);
        if(!$file)
        {
            return DataReturn('LoadPk12Cert::file_get_contents', -1);
        }

        if(openssl_pkcs12_read($file, $cert, $this->config['access_mid']))
        {
            return DataReturn('success', 0, $cert['pkey']);
        }
        return DataReturn('LoadPk12Cert::openssl_pkcs12_read ERROR', -1);
    }

    /**
     * 私钥签名
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-28
     * @desc    description
     * @param   [string]          $data [需要签名的数据]
     */
    function SignCreated($data)
    {
        $pri = $this->LoadPk12Cert();
        if($pri['code'] != 0)
        {
            return $pri;
        }

        $resource = openssl_pkey_get_private($pri['data']);
        $result = openssl_sign($data, $sign, $resource, OPENSSL_ALGO_SHA256);
        if($result)
        {
            return DataReturn('success', 0, base64_encode($sign));
        }
        return DataReturn('签名出错', -1);
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
        return 'respCode=000000';
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
        return 'respCode=-1';
    }
}
?>