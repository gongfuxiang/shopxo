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
 * 支付宝支付 - 新版本接口
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class Alipay
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
            'name'          => '支付宝',  // 插件名称
            'version'       => '0.0.2',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5'], // 适用终端 默认全部 ['pc', 'h5', 'app', 'alipay', 'weixin', 'baidu']
            'desc'          => '2.0版本，适用PC+H5，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
            'author'        => 'Devil',  // 开发者
            'author_url'    => 'http://shopxo.net/',  // 开发者主页
        ];

        // 配置信息
        $element = [
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
                'placeholder'   => '支付宝公钥',
                'title'         => '支付宝公钥',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写支付宝公钥',
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

        // 手机/PC
        if(IsMobile())
        {
            $ret = $this->PayMobile($params);
        } else {
            $ret = $this->PayWeb($params);
        }
        return $ret;
    }

    /**
     * [PayMobile wap手机支付]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:41:09+0800
     * @param   [array]           $params [输入参数]
     */
    private function PayMobile($params = [])
    {
        // 支付参数
        $parameter = array(
            'app_id'                =>  $this->config['appid'],
            'method'                =>  'alipay.trade.wap.pay',
            'format'                =>  'JSON',
            'charset'               =>  'utf-8',
            'sign_type'             =>  'RSA2',
            'timestamp'             =>  date('Y-m-d H:i:s'),
            'version'               =>  '1.0',
            'return_url'            =>  $params['call_back_url'],
            'notify_url'            =>  $params['notify_url'],
        );
        $biz_content = array(
            'product_code'          =>  'QUICK_WAP_WAY',
            'subject'               =>  $params['name'],
            'out_trade_no'          =>  $params['order_no'],
            'total_amount'          =>  $params['total_price'],
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $parameter['sign'] = $this->MyRsaSign($this->GetSignContent($parameter));
        
        // 输出执行form表单post提交
        exit($this->BuildRequestForm($parameter));
    }


    /**
     * [PayWeb PC支付]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-28T00:23:04+0800
     * @param   [array]           $params [输入参数]
     */
    private function PayWeb($params = [])
    {
        // 支付参数
        $parameter = array(
            'app_id'                =>  $this->config['appid'],
            'method'                =>  'alipay.trade.page.pay',
            'format'                =>  'JSON',
            'charset'               =>  'utf-8',
            'sign_type'             =>  'RSA2',
            'timestamp'             =>  date('Y-m-d H:i:s'),
            'version'               =>  '1.0',
            'return_url'            =>  $params['call_back_url'],
            'notify_url'            =>  $params['notify_url'],
        );
        $biz_content = array(
            'product_code'          =>  'FAST_INSTANT_TRADE_PAY',
            'subject'               =>  $params['name'],
            'out_trade_no'          =>  $params['order_no'],
            'total_amount'          =>  $params['total_price'],
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $parameter['sign'] = $this->MyRsaSign($this->GetSignContent($parameter));
        
        // 输出执行form表单post提交
        exit($this->BuildRequestForm($parameter));
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
     * 建立请求，以表单HTML形式构造（默认）
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]          $params [请求参数数组]
     * @return  [string]                 [提交表单HTML文本]
     */
    private function BuildRequestForm($params)
    {
        $html = "<form id='alipaysubmit' name='alipaysubmit' action='https://openapi.alipay.com/gateway.do?charset=utf-8' method='POST'>";
        foreach($params as $key=>$val)
        {
            if(!empty($val))
            {
                $val = str_replace("'", "&apos;", $val);
                $html .= "<input type='hidden' name='".$key."' value='".$val."'/>";
            }
        }

        //submit按钮控件请不要含有name属性
        $html .= "<input type='submit' value='ok' style='display:none;''></form>";
        
        $html .= "<script>document.forms['alipaysubmit'].submit();</script>";
        
        return $html;
    }

    /**
     * 获取签名内容
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
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
            if(!empty($v) && "@" != substr($v, 0, 1))
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
     * [MyRsaSign 签名字符串]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:38:28+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @return   [string]                           [签名结果]
     */
    private function MyRsaSign($prestr)
    {
        $res = "-----BEGIN RSA PRIVATE KEY-----\n";
        $res .= wordwrap($this->config['rsa_private'], 64, "\n", true);
        $res .= "\n-----END RSA PRIVATE KEY-----";
        return openssl_sign($prestr, $sign, $res, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
    }

    /**
     * [MyRsaDecrypt RSA解密]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T09:12:06+0800
     * @param    [string]                   $content [需要解密的内容，密文]
     * @return   [string]                            [解密后内容，明文]
     */
    private function MyRsaDecrypt($content)
    {
        $res = "-----BEGIN PUBLIC KEY-----\n";
        $res .= wordwrap($this->config['rsa_public'], 64, "\n", true);
        $res .= "\n-----END PUBLIC KEY-----";
        $res = openssl_get_privatekey($res);
        $content = base64_decode($content);
        $result  = '';
        for($i=0; $i<strlen($content)/128; $i++)
        {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res, OPENSSL_ALGO_SHA256);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * [OutRsaVerify 支付宝验证签名]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:39:50+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @param    [string]                   $sign   [签名结果]
     * @return   [boolean]                          [正确true, 错误false]
     */
    private function OutRsaVerify($prestr, $sign)
    {
        $res = "-----BEGIN PUBLIC KEY-----\n";
        $res .= wordwrap($this->config['out_rsa_public'], 64, "\n", true);
        $res .= "\n-----END PUBLIC KEY-----";
        $pkeyid = openssl_pkey_get_public($res);
        $sign = base64_decode($sign);
        if($pkeyid)
        {
            $verify = openssl_verify($prestr, $sign, $pkeyid, OPENSSL_ALGO_SHA256);
            openssl_free_key($pkeyid);
        }
        return (isset($verify) && $verify == 1) ? true : false;
    }

     /**
     * [SyncRsaVerify 同步返回签名验证]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T13:13:39+0800
     * @param    [array]                   $data [返回数据]
     * @param    [boolean]                 $key  [数据key]
     */
    private function SyncRsaVerify($data, $key)
    {
        $string = json_encode($data[$key], JSON_UNESCAPED_UNICODE);
        return $this->OutRsaVerify($string, $data['sign']);
    }
}
?>