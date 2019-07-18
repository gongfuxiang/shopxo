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
 * 百度支付 - 新版本接口
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Baidu
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
            'version'       => '0.0.1',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['baidu'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '2.0版本，适用PC+H5，即时到帐支付方式，买家的交易资金直接打入卖家百度账户，快速回笼交易资金。 <a href="https://smartprogram.baidu.com/docs/introduction/pay/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'name'          => 'appid',
                'placeholder'   => 'APP ID',
                'title'         => 'APP ID',
                'is_required'   => 0,
                'message'       => '请填写APP ID',
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

        $data = [
            'dealId'            => $this->config['dealid'],
            'appKey'            => $this->config['appkey'],
            'totalAmount'       => intval($params['total_price']*100),
            'tpOrderId'         => $params['order_no'],
            'dealTitle'         => $params['name'],
            'signFieldsRange'   => 1,
        ];
        $biz_info = [
            'tpData'    => [
                'appKey'        => $this->config['appkey'],
                'dealId'        => $this->config['dealid'],
                'tpOrderId'     => $params['order_no'],
                'totalAmount'   => intval($params['total_price']*100),
                'returnData'    => (object) [],
                'displayData'   => (object) [],
            ],
        ];
        $data['bizInfo'] = json_encode($biz_info, JSON_UNESCAPED_UNICODE);
        $data['rsaSign'] = $this->SignWithRsa($data);

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
        $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);
        ksort($data);

        // 参数字符串
        $prestr = '';
        foreach($data AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
            {
                $prestr .= "$key=$val&";
            }
        }
        $prestr = substr($prestr, 0, -1);

        // 签名
        if(!$this->OutRsaVerify($prestr, $data['sign']))
        {
            return DataReturn('签名校验失败', -1);
        }

        // 支付状态
        if(!empty($data['trade_no']) || (isset($data['trade_status']) && in_array($data['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED'])))
        {
            return DataReturn('支付成功', 0, $this->ReturnData($data));
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
        $data['trade_no']       = $data['trade_no'];        // 支付平台 - 订单号
        $data['buyer_user']     = $data['seller_id'];       // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = isset($data['subject']) ? $data['subject'] : ''; // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['total_amount'];    // 本系统发起支付的 - 总价

        return $data;
    }

    /**
     * @desc 私钥生成签名字符串
     * @param array $assocArr
     * @param $rsaPriKeyStr
     * @return bool|string
     * @throws Exception
     */
    public function SignWithRsa(array $assocArr)
    {
        $sign = '';
        if (empty($assocArr)) {
            return $sign;
        }

        if (!function_exists('openssl_pkey_get_private') || !function_exists('openssl_sign')) {
            throw new Exception("openssl扩展不存在");
        }

        $res = "-----BEGIN RSA PRIVATE KEY-----\n";
        $res .= wordwrap($this->config['rsa_private'], 64, "\n", true);
        $res .= "\n-----END RSA PRIVATE KEY-----";
        $priKey = openssl_pkey_get_private($res);

        if (isset($assocArr['sign'])) {
            unset($assocArr['sign']);
        }

        ksort($assocArr); //按字母升序排序

        $parts = array();
        foreach ($assocArr as $k => $v) {
            $parts[] = $k . '=' . $v;
        }
        $str = implode('&', $parts);
        openssl_sign($str, $sign, $priKey);
        openssl_free_key($priKey);

        return base64_encode($sign);
    }

    /**
     * @desc 公钥校验签名
     * @param array $assocArr
     * @param $rsaPubKeyStr
     * @return bool
     * @throws Exception
     */
    public function checkSignWithRsa(array $assocArr)
    {
        if (!isset($assocArr['sign']) || empty($assocArr)) {
            return false;
        }

        if (!function_exists('openssl_pkey_get_public') || !function_exists('openssl_verify')) {
            throw new Exception("openssl扩展不存在");
        }

        $sign = $assocArr['sign'];
        unset($assocArr['sign']);

        if (empty($assocArr)) {
            return false;
        }
        ksort($assocArr); //按字母升序排序
        $parts = array();
        foreach ($assocArr as $k => $v) {
            $parts[] = $k . '=' . $v;
        }
        $str = implode('&', $parts);

        $sign = base64_decode($sign);

        $res = "-----BEGIN PUBLIC KEY-----\n";
        $res .= wordwrap($this->config['out_rsa_public'], 64, "\n", true);
        $res .= "\n-----END PUBLIC KEY-----";
        $pubKey = openssl_pkey_get_public($res);
        $result = (bool)openssl_verify($str, $sign, $pubKey);
        openssl_free_key($pubKey);

        return $result;
    }
}
?>