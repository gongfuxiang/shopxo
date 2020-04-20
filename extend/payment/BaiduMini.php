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

/**
 * 百度小程序支付
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class BaiduMini
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
            'name'          => '百度',  // 插件名称
            'version'       => '1.0.0',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['baidu'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '适用百度小程序，百度收银台已集成度小满、支付宝、微信支付，即时到帐支付方式，买家的交易资金直接打入卖家百度账户，快速回笼交易资金。 <a href="https://smartprogram.baidu.com/docs/introduction/pay/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'dealid',
                'placeholder'   => 'dealId',
                'title'         => 'dealId',
                'is_required'   => 0,
                'message'       => '请填写dealId',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'appkey',
                'placeholder'   => 'APP KEY',
                'title'         => 'APP KEY',
                'is_required'   => 0,
                'message'       => '请填写APP KEY',
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
                'placeholder'   => '平台公钥',
                'title'         => '平台公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写平台公钥',
            ],
            [
                'element'       => 'message',
                'message'       => '异步通知地址，将该地址配置到百度小程序支付后台异步通知<br />'.__MY_URL__.'payment_order_'.strtolower(str_replace(['payment', '\\'], '', get_class($this))).'_notify.php',
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

        // opensssl校验
        if(!function_exists('openssl_pkey_get_private') || !function_exists('openssl_sign'))
        {
            return DataReturn('openssl扩展不存在', -1);
        }

        // 支付参数
        $data = [
            'dealId'            => $this->config['dealid'],
            'appKey'            => $this->config['appkey'],
            'totalAmount'       => (int) (($params['total_price']*1000)/10),
            'tpOrderId'         => $params['order_no'],
            'dealTitle'         => $params['name'],
            'signFieldsRange'   => 1,
        ];
        $data['rsaSign'] = $this->SignWithRsa($data);
        $biz_info = [
            'tpData'    => [
                'appKey'        => $data['appKey'],
                'dealId'        => $data['dealId'],
                'tpOrderId'     => $data['tpOrderId'],
                'rsaSign'       => $data['rsaSign'],
                'totalAmount'   => $data['totalAmount'],
                'returnData'    => (object) [],
                'displayData'   => (object) [],
            ],
        ];
        $data['bizInfo'] = json_encode($biz_info, JSON_UNESCAPED_UNICODE);

        return DataReturn('处理成功', 0, $data);
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
        // 签名
        if(!$this->CheckSignWithRsa($params))
        {
            return DataReturn('签名校验失败', -1);
        }

        // 支付状态
        if(isset($params['status']) && $params['status'] == 2)
        {
            return DataReturn('支付成功', 0, $this->ReturnData($params));
        }
        return DataReturn('处理异常错误', -100);
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
        $data['trade_no']       = $data['orderId'];        // 支付平台 - 订单号
        $data['buyer_user']     = $data['userId'];       // 支付平台 - 用户
        $data['out_trade_no']   = $data['tpOrderId'];    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['subject']) ? $data['subject'] : ''; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['totalMoney']/100;    // 本系统发起支付的 - 总价

        return $data;
    }

    /**
     * 私钥生成签名字符串
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-07-18
     * @desc    description
     * @param   [array]           $data [需要生成签名的数据]
     */
    public function SignWithRsa($data)
    {
        $sign = '';
        if(empty($data))
        {
            return $sign;
        }

        if(stripos($this->config['rsa_private'], '-----') === false)
        {
            $res = "-----BEGIN RSA PRIVATE KEY-----\n";
            $res .= wordwrap($this->config['rsa_private'], 64, "\n", true);
            $res .= "\n-----END RSA PRIVATE KEY-----";
        } else {
            $res = $this->config['rsa_private'];
        }
        $prikey = openssl_pkey_get_private($res);

        if(isset($data['sign']))
        {
            unset($data['sign']);
        }

        ksort($data); //按字母升序排序
        $parts = [];
        foreach ($data as $k => $v) {
            if(in_array($k, ['appKey', 'dealId', 'tpOrderId', 'totalAmount']))
            {
                $parts[] = $k . '=' . $v;
            }
        }
        $str = implode('&', $parts);
        openssl_sign($str, $sign, $prikey);
        openssl_free_key($prikey);
        return base64_encode($sign);
    }

    /**
     * 公钥校验签名
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-07-18
     * @desc    description
     * @param   [array]           $data [需要验签的数据]
     */
    public function CheckSignWithRsa($data)
    {
        if(!isset($data['rsaSign']) || empty($data))
        {
            return false;
        }

        $sign = $data['rsaSign'];
        unset($data['rsaSign']);

        if(empty($data))
        {
            return false;
        }
        ksort($data); //按字母升序排序
        $parts = [];
        foreach ($data as $k => $v) {
            $parts[] = $k . '=' . $v;
        }
        $str = implode('&', $parts);

        $sign = base64_decode($sign);

        if(stripos($this->config['out_rsa_public'], '-----') === false)
        {
            $res = "-----BEGIN PUBLIC KEY-----\n";
            $res .= wordwrap($this->config['out_rsa_public'], 64, "\n", true);
            $res .= "\n-----END PUBLIC KEY-----";
        } else {
            $res = $this->config['out_rsa_public'];
        }
        $pubkey = openssl_pkey_get_public($res);
        $result = (bool)openssl_verify($str, $sign, $pubkey);
        openssl_free_key($pubkey);
        return $result;
    }
}
?>