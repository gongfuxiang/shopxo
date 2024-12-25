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
 * 支付宝支付 - 新版本证书通信方式接口
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-09-19
 * @desc    description
 */
class AlipayCert
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
            'name'          => '支付宝证书支付',  // 插件名称
            'version'       => '1.0.2',  // 插件版本
            'apply_version' => '不限',  // 适用系统版本描述
            'apply_terminal'=> ['pc','h5', 'ios', 'android'], // 适用终端 默认全部 ['pc', 'h5', 'ios', 'android', 'alipay', 'weixin', 'baidu', 'toutiao']
            'desc'          => '2.0证书通信版本，适用PC+H5+APP，即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',  // 插件描述（支持html）
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
                'name'          => 'cert_content',
                'placeholder'   => '应用证书',
                'title'         => '应用证书',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写应用证书',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'out_cert_content',
                'placeholder'   => '支付宝证书',
                'title'         => '支付宝证书',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写支付宝证书',
            ],
            [
                'element'       => 'textarea',
                'name'          => 'out_root_cert_content',
                'placeholder'   => '支付宝ROOT证书',
                'title'         => '支付宝ROOT证书',
                'is_required'   => 0,
                'rows'          => 6,
                'message'       => '请填写支付宝ROOT证书',
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
        if(empty($this->config) || empty($this->config['appid']) || empty($this->config['rsa_public']) || empty($this->config['rsa_private']) || empty($this->config['cert_content']) || empty($this->config['out_cert_content']) || empty($this->config['out_root_cert_content']))
        {
            return DataReturn('支付缺少配置', -1);
        }

        // 支付方式
        switch(APPLICATION_CLIENT_TYPE)
        {
            // web
            case 'pc' :
            case 'h5' :
                if(IsMobile())
                {
                    $ret = $this->PayMobile($params);
                } else {
                    $ret = $this->PayWeb($params);
                }
                break;

            // app,头条小程序
            case 'ios' :
            case 'android' :
            case 'toutiao' :
                $ret = $this->PayApp($params);
                break;

            default :
                $ret = DataReturn('没有相关支付模块['.APPLICATION_CLIENT_TYPE.']', -1);
        }
        
        return $ret;
    }

    /**
     * app支付
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-29
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private function PayApp($params = [])
    {
        // 支付参数
        $parameter = array(
            'app_id'                => $this->config['appid'],
            'method'                => 'alipay.trade.app.pay',
            'format'                => 'JSON',
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
            'return_url'            => $params['call_back_url'],
            'notify_url'            => $params['notify_url'],
            'app_cert_sn'           => $this->GetCertSNFromContent($this->config['cert_content']),
            'alipay_root_cert_sn'   => $this->GetRootCertSNFromContent($this->config['out_root_cert_content']),
        );
        $biz_content = array(
            'product_code'          => 'QUICK_MSECURITY_PAY',
            'subject'               => $params['name'],
            'out_trade_no'          => $params['order_no'],
            'total_amount'          => (string) $params['total_price'],
            'timeout_express'       => $this->OrderAutoCloseTime(),
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $parameter['sign'] = $this->MyRsaSign($this->GetSignContent($parameter));

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 生成支付参数
        $value = '';
        $i = 0;
        foreach($parameter as $k=>$v)
        {
            if($v != '' && $v != null && "@" != substr($v, 0, 1))
            {
                if($i == 0)
                {
                    $value .= $k.'='.urlencode($v);
                } else {
                    $value .= '&'.$k.'='.urlencode($v);
                }
                $i++;
            }
        }
        unset($k, $v);
        return DataReturn('处理成功', 0, $value);
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
            'app_id'                => $this->config['appid'],
            'method'                => 'alipay.trade.wap.pay',
            'format'                => 'JSON',
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
            'return_url'            => $params['call_back_url'],
            'notify_url'            => $params['notify_url'],
            'app_cert_sn'           => $this->GetCertSNFromContent($this->config['cert_content']),
            'alipay_root_cert_sn'   => $this->GetRootCertSNFromContent($this->config['out_root_cert_content']),
        );
        $biz_content = array(
            'product_code'          => 'QUICK_WAP_WAY',
            'subject'               => $params['name'],
            'out_trade_no'          => $params['order_no'],
            'total_amount'          => (string) $params['total_price'],
            'timeout_express'       => $this->OrderAutoCloseTime(),
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $parameter['sign'] = $this->MyRsaSign($this->GetSignContent($parameter));

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);

        // 接口则直接返回
        $html = $this->BuildRequestForm($parameter);
        if(APPLICATION == 'app')
        {
            $result = [
                'data'  => $parameter,
                'html'  => $html,
                'url'   => 'https://openapi.alipay.com/gateway.do?charset=utf-8',
            ];
            return DataReturn('success', 0, $result);
        }

        // web端输出执行form表单post提交
        exit($html);
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
            'app_id'                => $this->config['appid'],
            'method'                => 'alipay.trade.page.pay',
            'format'                => 'JSON',
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
            'return_url'            => $params['call_back_url'],
            'notify_url'            => $params['notify_url'],
            'app_cert_sn'           => $this->GetCertSNFromContent($this->config['cert_content']),
            'alipay_root_cert_sn'   => $this->GetRootCertSNFromContent($this->config['out_root_cert_content']),
        );
        $biz_content = array(
            'product_code'          => 'FAST_INSTANT_TRADE_PAY',
            'subject'               => $params['name'],
            'out_trade_no'          => $params['order_no'],
            'total_amount'          => (string) $params['total_price'],
            'timeout_express'       => $this->OrderAutoCloseTime(),
        );
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $parameter['sign'] = $this->MyRsaSign($this->GetSignContent($parameter));

        // 支付请求记录
        PayLogService::PayLogRequestRecord($params['order_no'], ['request_params'=>$parameter]);
        
        // 接口则直接返回
        $html = $this->BuildRequestForm($parameter);
        if(APPLICATION == 'app')
        {
            $result = [
                'data'  => $parameter,
                'html'  => $html,
                'url'   => 'https://openapi.alipay.com/gateway.do?charset=utf-8',
            ];
            return DataReturn('success', 0, $result);
        }

        // web端输出执行form表单post提交
        exit($html);
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
        return intval(MyC('common_order_close_limit_time', 30, true)).'m';
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
        if(!empty($data['trade_no']) && isset($data['total_amount']) && $data['total_amount'] > 0)
        {
            $status = false;
            if(isset($data['trade_status']))
            {
                if(in_array($data['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED']))
                {
                    $status = true;
                }
            } else {
                switch($data['method'])
                {
                    // pc、h5
                    case 'alipay.trade.wap.pay.return' :
                    case 'alipay.trade.page.pay.return' :
                        if(isset($data['seller_id']))
                        {
                            $status = true;
                        }
                        break;
                }
            }
            if($status)
            {
                return DataReturn('支付成功', 0, $this->ReturnData($data));
            }
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
            'app_id'                => $this->config['appid'],
            'method'                => 'alipay.trade.refund',
            'format'                => 'JSON',
            'charset'               => 'utf-8',
            'sign_type'             => 'RSA2',
            'timestamp'             => date('Y-m-d H:i:s'),
            'version'               => '1.0',
            'app_cert_sn'           => $this->GetCertSNFromContent($this->config['cert_content']),
            'alipay_root_cert_sn'   => $this->GetRootCertSNFromContent($this->config['out_root_cert_content']),
        ];
        $biz_content = [
            'out_request_no'        => $params['order_no'].'JE'.str_replace('.', '', $params['refund_price']),
            'out_trade_no'          => $params['order_no'],
            'trade_no'              => $params['trade_no'],
            'refund_amount'         => (string) $params['refund_price'],
            'refund_reason'         => $refund_reason,
        ];
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $parameter['sign'] = $this->MyRsaSign($this->GetSignContent($parameter));

        // 执行请求
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $parameter);
        $key = str_replace('.', '_', $parameter['method']).'_response';

        // 状态
        if(isset($result[$key]['code']) && $result[$key]['code'] == 10000)
        {
            // 验证签名
            if(!$this->SyncRsaVerify($result, $key))
            {
                return DataReturn('签名验证错误', -1);
            }

            // 统一返回格式
            $data = [
                'out_trade_no'    => isset($result[$key]['out_trade_no']) ? $result[$key]['out_trade_no'] : '',
                'trade_no'        => isset($result[$key]['trade_no']) ? $result[$key]['trade_no'] : '',
                'buyer_user'      => isset($result[$key]['buyer_user_id']) ? $result[$key]['buyer_user_id'] : '',
                'refund_price'    => isset($result[$key]['refund_fee']) ? $result[$key]['refund_fee'] : 0.00,
                'return_params'   => $result[$key],
                'request_params'  => $parameter,
            ];
            return DataReturn('退款成功', 0, $data);
        }

        // 直接返回支付信息
        return DataReturn($result[$key]['sub_msg'].'['.$result[$key]['sub_code'].']', -1000);
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
        $html .= "<input type='submit' value='ok' style='display:none;'></form>";
        
        $html .= "<script>document.forms['alipaysubmit'].submit();</script>";
        
        return $html;
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
            $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $http_status_code)
            {
                return false;
            }
        }
        curl_close($ch);
        return json_decode($reponse, true);
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
        if(stripos($this->config['rsa_private'], '-----') === false)
        {
            $res = "-----BEGIN RSA PRIVATE KEY-----\n";
            $res .= wordwrap($this->config['rsa_private'], 64, "\n", true);
            $res .= "\n-----END RSA PRIVATE KEY-----";
        } else {
            $res = $this->config['rsa_private'];
        }
        return openssl_sign($prestr, $sign, $res, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
    }

    /**
     * 从证书内容中提取序列号
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-09-15
     * @desc    description
     * @param   [string]          $cert_content [证书内容发]
     * @return  [string]                        [序列号]
     */
    public function GetCertSNFromContent($cert_content)
    {
        $ssl = openssl_x509_parse($cert_content);
        return md5($this->ArrayToString(array_reverse($ssl['issuer'])).$ssl['serialNumber']);
    }

    /**
     * 数组转字符串
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-09-15
     * @desc    description
     * @param   [array]          $array [数组]
     * @return  [string]                [字符串]
     */
    public function ArrayToString($array)
    {
        $string = [];
        if ($array && is_array($array))
        {
            foreach($array as $key=>$value)
            {
                $string[] = $key.'='.$value;
            }
        }
        return implode(',', $string);
    }

    /**
     * 提取根证书序列号
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-09-15
     * @desc    description
     * @param   [string]          $cert_content [证书]
     * @return  [string]                        [序列号]
     */
    public function GetRootCertSNFromContent($cert_content)
    {
        $array = explode("-----END CERTIFICATE-----", $cert_content);
        $sn = null;
        for($i = 0; $i < count($array) - 1; $i++)
        {
            $ssl[$i] = openssl_x509_parse($array[$i] . "-----END CERTIFICATE-----");
            if(strpos($ssl[$i]['serialNumber'],'0x') === 0){
                $ssl[$i]['serialNumber'] = $this->Hex2dec($ssl[$i]['serialNumberHex']);
            }
            if($ssl[$i]['signatureTypeLN'] == "sha1WithRSAEncryption" || $ssl[$i]['signatureTypeLN'] == "sha256WithRSAEncryption")
            {
                if($sn == null)
                {
                    $sn = md5($this->ArrayToString(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                } else {

                    $sn = $sn . "_" . md5($this->ArrayToString(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                }
            }
        }
        return $sn;
    }
    
    /**
     * 0x转高精度数字
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-09-15
     * @desc    description
     * @param   [int]          $hex [数字]
     * @return  [int|string]        [转换的数据]
     */
    public function Hex2dec($hex)
    {
        $dec = 0;
        $len = strlen($hex);
        for($i = 1; $i <= $len; $i++)
        {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }

    /**
     * 从证书中提取公钥
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-10-11
     * @desc    description
     * @return  [string]          [公钥]
     */
    public function GetPublicKey()
    {
        $pkey = openssl_pkey_get_public($this->config['out_cert_content']);
        $keyData = openssl_pkey_get_details($pkey);
        $public_key = str_replace('-----BEGIN PUBLIC KEY-----', '', $keyData['key']);
        $public_key = trim(str_replace('-----END PUBLIC KEY-----', '', $public_key));
        return $public_key;
    }

    /**
     * 支付宝验证签名
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
        $public_key = $this->GetPublicKey();
        if(stripos($public_key, '-----') === false)
        {
            $res = "-----BEGIN PUBLIC KEY-----\n";
            $res .= wordwrap($public_key, 64, "\n", true);
            $res .= "\n-----END PUBLIC KEY-----";
        } else {
            $res = $public_key;
        }
        $pkeyid = openssl_pkey_get_public($res);
        $sign = base64_decode($sign);
        if($pkeyid)
        {
            $verify = openssl_verify($prestr, $sign, $pkeyid, OPENSSL_ALGO_SHA256);
            unset($pkeyid);
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