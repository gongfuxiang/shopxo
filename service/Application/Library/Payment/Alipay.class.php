<?php

namespace Library\Payment;

/**
 * 支付宝支付
 * @author  Devil
 * @version V_1.0.0
 */
class Alipay
{
    private $params;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * 配置信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Config()
    {
        // 基础信息
        $base = [
            'name'          => '支付宝',
            'version'       => '0.0.1',
            'apply_version' => '1.0~1.3',
            'desc'          => '即时到帐支付方式，买家的交易资金直接打入卖家支付宝账户，快速回笼交易资金。 <a href="http://www.alipay.com/" target="_blank">立即申请</a>',
            'author'        => 'Devil',
            'author_url'    => 'http://gong.gg/'
        ];

        // 配置信息
        $element = [
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'account',
                'placeholder'   => '支付宝账号',
                'title'         => '支付宝账号',
                'is_required'   => 1,
                'message'       => '请填写支付宝账号',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'key',
                'placeholder'   => '交易安全校验码 key',
                'title'         => '交易安全校验码 key',
                'is_required'   => 1,
                'message'       => '请填写交易安全校验码 key',
            ],
            [
                'element'       => 'input',
                'type'          => 'text',
                'default'       => '',
                'name'          => 'partner',
                'placeholder'   => '合作者身份 partner ID',
                'title'         => '合作者身份 partner ID',
                'is_required'   => 1,
                'message'       => '请填写合作者身份 partner ID',
            ],
            [
                'element'       => 'input',  // 表单标签
                'type'          => 'text',  // input类型
                'default'       => '',  // 默认值
                'name'          => 'testinput',  // name名称
                'placeholder'   => '测试输入框不需要验证',  // input默认显示文字
                'title'         => '测试输入框不需要验证',  // 展示title名称
                'is_required'   => 0,  // 是否需要强制填写/选择
                'message'       => '请填写测试输入框不需要验证', // 错误提示（is_required=1方可有效）
            ],
            [
                'element'       => 'input',
                'type'          => 'checkbox',
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1'],
                    ['value'=>2, 'name'=>'选项2', 'is_checked'=>1],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'is_block'      => 1,  // 是否每个选项行内展示（默认0）
                'minchecked'    => 2,  // 最小选项（默认以is_required=1至少一项，则0）
                'maxchecked'    => 3,  // 最大选项
                'name'          => 'checkbox',
                'title'         => '多选项测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择多选项测试选择 至少选择2项最多选择3项',  // 错误提示信息
            ],
            [
                'element'       => 'input',
                'type'          => 'radio',
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1', 'is_checked'=>1],
                    ['value'=>2, 'name'=>'选项2'],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'is_block'      => 1,  // 是否每个选项行内展示（默认0）
                'name'          => 'radio',
                'title'         => '单选项测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择单选项测试',
            ],
            [
                'element'       => 'select',
                'placeholder'   => '选一个撒1',
                'is_multiple'   => 0,  // 是否开启多选（默认0 关闭）
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1'],
                    ['value'=>2, 'name'=>'选项2'],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'name'          => 'select1',
                'title'         => '下拉单选测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择下拉单选测试',
            ],
            [
                'element'       => 'select',
                'placeholder'   => '选一个撒2',
                'is_multiple'   => 1,  // 是否开启多选（默认0 关闭）
                'element_data'  => [
                    ['value'=>1, 'name'=>'选项1'],
                    ['value'=>2, 'name'=>'选项2'],
                    ['value'=>3, 'name'=>'选项3'],
                    ['value'=>4, 'name'=>'选项4']
                ],
                'minchecked'    => 2,  // 最小选项（默认以is_required=1至少一项，则0）
                'maxchecked'    => 3,  // 最大选项
                'name'          => 'select2',
                'title'         => '下拉多选测试',  // 展示title名称
                'is_required'   => 1,  // 是否需要强制填写/选择
                'message'       => '请选择下拉多选测试 至少选择2项最多选择3项',
            ],
        ];

        return [
            'base'      => $base,
            'element'   => $element,
        ];
    }

    // 测试
    public function Test()
    {
        echo 'alipay - test()';
    }

    /**
     * [SoonPay 立即支付]
     * @param [array] $data [支付信息]
     */
    public function Pay($data, $config)
    {
        if(empty($data) || empty($config)) return false;

        if(APPLICATION == 'app')
        {
            return $this->SoonPayApp($data, $config);
        } else {
            if(IsMobile())
            {
                $this->SoonPayMobile($data, $config);
            } else {
                $this->SoonPayWeb($data, $config);
            }
        }
    }

    /**
     * [SoonPayApp app支付]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-25T16:07:06+0800
     * @param    [array]                   $data   [参数列表]
     * @param    [array]                   $config [配置信息]
     */
    private function SoonPayApp($data, $config)
    {
        $parameter = array(
            'app_id'                =>  C('alipay_mini_appid'),
            'method'                =>  'alipay.trade.app.pay',
            'format'                =>  'JSON',
            'charset'               =>  'utf-8',
            'sign_type'             =>  'RSA2',
            'timestamp'             =>  date('Y-m-d H:i:s'),
            'version'               =>  '1.0',
            'notify_url'            =>  $data['notify_url'],
        );
        $biz_content = array(
            'subject'               =>  $data['name'],
            'out_trade_no'          =>  $data['order_sn'],
            'total_amount'          =>  $data['total_price'],
            'product_code'          =>  'QUICK_MSECURITY_PAY',
        );

        // 收款账户集
        if(!empty($data['seller_id']))
        {
            $biz_content['seller_id'] = $data['seller_id'];
        }
        $parameter['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);


        // 生成签名参数+签名
        $params = $this->GetParamSign($parameter);
        $params['param'] .= '&sign='.urlencode($this->MyRsaSign($params['value']));

        // 直接返回支付信息
        return $params['param'];
    }

    /**
     * [SoonPayMobile wap支付]
     * @param  [array] $data   [参数列表]
     * @param  [array] $config [配置信息]   
     */
    private function SoonPayMobile($data, $config)
    {
        $request_token = $this->GetRequestToken($data, $config);

        $req_data = '<auth_and_execute_req><request_token>'.$request_token.'</request_token></auth_and_execute_req>';
        $parameter = array(
            'service'               =>  'alipay.wap.auth.authAndExecute',
            'format'                =>  'xml',
            'v'                     =>  '2.0',
            'partner'               =>  $config['id'],
            'sec_id'                =>  'MD5',
            'req_data'              =>  $req_data,
            'request_token'         =>  $request_token
        );

        $param = $this->GetParamSign($parameter, $config);
        header('location:http://wappaygw.alipay.com/service/rest.htm?'.$param['param']. '&sign='.md5($param['sign']));

    }

    /**
     * [GetRequestToken 获取临时token]
     * @param  [array] $data   [参数列表]
     * @param  [array] $config [配置信息]
     * @return [string]        [返回临时token]
     */
    private function GetRequestToken($data, $config)
    {
        $parameter = array(
            'service'               =>  'alipay.wap.trade.create.direct',
            'format'                =>  'xml',
            'v'                     =>  '2.0',
            'partner'               =>  $config['id'],
            'req_id'                =>  $data['order_sn'],
            'sec_id'                =>  'MD5',
            'req_data'              =>  $this->GetReqData($data, $config),
            'subject'               =>  $data['name'],
            'out_trade_no'          =>  $data['order_sn'],
            'total_fee'             =>  $data['total_price'],
            'seller_account_name'   =>  $config['name'],
            'call_back_url'         =>  $data['call_back_url'],
            'notify_url'            =>  $data['notify_url'],
            'out_user'              =>  $data['out_user'],
            'merchant_url'          =>  $data['merchant_url'],
        );
        
        $param = $this->GetParamSign($parameter, $config);
        $ret = urldecode(file_get_contents('http://wappaygw.alipay.com/service/rest.htm?'.$param['param'].'&sign='.md5($param['sign'])));

        $para_split = explode('&',$ret);
        //把切割后的字符串数组变成变量与数值组合的数组
        foreach ($para_split as $item) {
            //获得第一个=字符的位置
            $nPos = strpos($item,'=');
            //获得字符串长度
            $nLen = strlen($item);
            //获得变量名
            $key = substr($item,0,$nPos);
            //获得数值
            $value = substr($item,$nPos+1,$nLen-$nPos-1);
            //放入数组中
            $para_text[$key] = $value;
        }

        $req = Xml_Array($para_text['res_data']);
        if(empty($req['request_token']))
        {
            exit(header('location:'.__ROOT__.'index.php?g=Info&c=Prompt&f=PromptInfo&state=error&content=支付宝异常错误&url='.__ROOT__));
        }

        return $req['request_token'];
    }

    private function GetReqData($data, $config)
    {
        return '<direct_trade_create_req>
                    <subject>'.$data['name'].'</subject>
                    <out_trade_no>'.$data['order_sn'].'</out_trade_no>
                    <total_fee>'.$data['total_price'].'</total_fee>
                    <seller_account_name>'.$config['name'].'</seller_account_name>
                    <call_back_url>'.$data['call_back_url'].'</call_back_url>
                    <notify_url>'.$data['notify_url'].'</notify_url>
                    <out_user>'.$data['out_user'].'</out_user>
                    <merchant_url>'.$data['merchant_url'].'</merchant_url>
                    <pay_expire>3600</pay_expire>
                    <agent_id>0</agent_id>
                </direct_trade_create_req>';
    }

    /**
     * [SoonPayWeb web支付]
     * @param [array] $data   [订单信息]
     * @param [array] $config [配置信息]
     */
    private function SoonPayWeb($data, $config)
    {
        $parameter = array(
            'service'           => 'create_direct_pay_by_user',
            'partner'           => $config['id'],
            '_input_charset'    => ML_CHARSET,
            'notify_url'        => $data['notify_url'],
            'return_url'        => $data['call_back_url'],

            /* 业务参数 */
            'subject'           => $data['name'],
            'out_trade_no'      => $data['order_sn'],
            'price'             => $data['total_price'],

            'quantity'          => 1,
            'payment_type'      => 1,

            /* 物流参数 */
            'logistics_type'    => 'EXPRESS',
            'logistics_fee'     => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',

            /* 买卖双方信息 */
            'seller_email'      => $config['name']
        );

        $param = $this->GetParamSign($parameter, $config);
        header('location:https://mapi.alipay.com/gateway.do?'.$param['param']. '&sign='.md5($param['sign']).'&sign_type=MD5');
    }

    /**
     * [GetParamSign 生成参数和签名]
     * @param  [array] $data   [待生成的参数]
     * @param  [array] $config [配置信息]
     * @return [array]         [生成好的参数和签名]
     */
    private function GetParamSign($data, $config = [])
    {
        $param = '';
        $sign  = '';
        ksort($data);

        foreach($data AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $result = array(
            'param' =>  substr($param, 0, -1),
            'value' =>  substr($sign, 0, -1),
        );
        if(!empty($config['key']))
        {
            $result['sign'] = $result['value'].$config['key'];
        }
        return $result;
    }

    /**
     * [Respond 异步处理]
     * @param  [array] $config [配置信息]
     * @return [array|string] [成功返回数据列表，失败返回no]
     */
    public function Respond($config)
    {
        if(empty($config)) return 'no';

        $data = empty($_POST) ? $_GET :  array_merge($_GET, $_POST);
        ksort($data);

        $sign = '';
        if(isset($data['sec_id']) && $data['sec_id'] == 'MD5')
        {
            $data_xml = json_decode(json_encode((array) simplexml_load_string($data['notify_data'])), true);
            $data = array_merge($data, $data_xml);
            $sign = 'service='.$data['service'].'&v='.$data['v'].'&sec_id='.$data['sec_id'].'&notify_data='.$data['notify_data'];
        } else {
            foreach($data AS $key=>$val)
            {
                if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
                {
                    $sign .= "$key=$val&";
                }
            }
            $sign = substr($sign, 0, -1);
        }

        if(isset($data['sign_type']) && $data['sign_type'] == 'RSA2')
        {
            if(!$this->AlipayrsaVerify($sign, $data['sign']))
            {
                return 'no';
            }
        } else {
            if(!isset($data['sign']) || md5($sign.$config['key']) != $data['sign'])
            {
                return 'no';
            }
        }

        /* 支付状态 */
        $status = isset($data['trade_status']) ? $data['trade_status'] : $data['result'];
        switch($status)
        {
            case 'TRADE_SUCCESS':
            case 'TRADE_FINISHED':
            case 'success':
                return $data;
                break;
        }
        return 'no';
    }

    /**
     * [GetAlipayUserInfo 支付宝小程序获取用户信息]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-23T22:30:43+0800
     * @param  [string]     $authcode  [用户授权码]
     * @param  [string]     $app_id    [应用appid]
     * @return [array|boolean]         [成功返回用户数据, 则false]
     */
    public function GetAlipayUserInfo($authcode, $app_id)
    {
        // 从缓存获取用户信息
        $key = 'alipay_userinfo_'.$authcode;
        $result = GS($key);
        if($result !== false)
        {
            return $result;
        }

        // 获取授权信息并且获取用户信息
        $auth = $this->GetAuthAccessToken($authcode, $app_id);
        if($auth != false)
        {
            // 请求参数
            $param = [
                'app_id'            =>  $app_id,
                'method'            =>  'alipay.user.info.share',
                'charset'           =>  'utf-8',
                'format'            =>  'JSON',
                'sign_type'         =>  'RSA2',
                'timestamp'         =>  date('Y-m-d H:i:s'),
                'version'           =>  '1.0',
                'auth_token'        =>  $auth['access_token'],
            ];

            // 生成签名参数+签名
            $p = $this->GetParamSign($param);
            $param['sign'] = $this->MyRsaSign($p['value']);

            // 执行请求
            $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $param);

            if(!empty($result['alipay_user_info_share_response']['code']) && $result['alipay_user_info_share_response']['code'] == 10000)
            {
                // 验证签名正确则存储缓存返回数据
                if(!$this->SyncRsaVerify($result, 'alipay_user_info_share_response'))
                {
                    return false;
                }
                
                // 存储缓存
                SS($key, $result['alipay_user_info_share_response']);

                // 返回用户数据
                return $result['alipay_user_info_share_response'];
            }
        }
        return false;
    }

    /**
     * [GetAuthAccessToken 根据用户授权换取授权访问令牌]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-23T22:36:26+0800
     * @param  [string]     $authcode  [用户授权码]
     * @param  [string]     $app_id    [应用appid]
     * @return [array|boolean]         [失败false, 用户授权信息]
     */
    public function GetAuthAccessToken($authcode, $app_id)
    {
        // 获取用户授权信息
        $key = 'alipay_authcode_'.$authcode;
        $result = GS($key, 0, true);

        // 过期判断
        if($result == false || $result['filemtime']+$result['re_expires_in'] < time())
        {
            $result = $this->GetAuthCode($app_id, $key, $authcode);
        } else {
            if($result['filemtime']+$result['expires_in'] < time())
            {
                $result = $this->GetAuthCode($app_id, $key, '', $result['refresh_token']);
            }
        }
        return $result;
    }

    /**
     * [GetAuthCode 获取用户授权信息]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T21:55:45+0800
     * @param    [string]            $app_id        [应用appid]
     * @param    [string]            $key           [缓存key]
     * @param    [string]            $authcode      [用户授权码]
     * @param    [string]            $refresh_token [刷新授权token]
     * @return   [array|boolean]                    [失败false, 用户授权信息]
     */
    private function GetAuthCode($app_id, $key, $authcode = '', $refresh_token = '')
    {
        if(empty($app_id) || empty($key) || (empty($authcode) && empty($refresh_token)))
        {
            return false;
        }

        // 请求参数
        $param = [
            'app_id'            =>  $app_id,
            'method'            =>  'alipay.system.oauth.token',
            'charset'           =>  'utf-8',
            'format'            =>  'JSON',
            'sign_type'         =>  'RSA2',
            'timestamp'         =>  date('Y-m-d H:i:s'),
            'version'           =>  '1.0',
            'biz_content'       =>  'mini-authcode',
        ];
        if(!empty($authcode))
        {
            $param['code'] = $authcode;
            $param['grant_type'] = 'authorization_code';
        }
        if(!empty($refresh_token))
        {
            $param['refresh_token'] = $refresh_token;
            $param['grant_type'] = 'refresh_token';
        }

        // 生成签名参数+签名
        $p = $this->GetParamSign($param);
        $param['sign'] = $this->MyRsaSign($p['value']);

        // 执行请求
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $param);
        // 结果正确则验证签名 并且 存储缓存返回access_token
        if(!empty($result['alipay_system_oauth_token_response']['user_id']))
        {
            // 验证签名正确则存储缓存返回数据
            if(!$this->SyncRsaVerify($result, 'alipay_system_oauth_token_response'))
            {
                return false;
            }

            // 存储缓存
            SS($key, $result['alipay_system_oauth_token_response']);
            return $result['alipay_system_oauth_token_response'];
        }
        return false;
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
        return $this->AlipayRsaVerify($string, $data['sign']);
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
        $public_key = file_get_contents(ROOT_PATH.'Rsakeys/rsa_mini_private_key_pkcs8.pem');
        $pkeyid = openssl_pkey_get_private($public_key);
        return openssl_sign($prestr, $sign, $pkeyid, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
    }

    /**
     * [AlipayRsaVerify 支付宝验证签名]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T08:39:50+0800
     * @param    [string]                   $prestr [需要签名的字符串]
     * @param    [string]                   $sign   [签名结果]
     * @return   [boolean]                          [正确true, 错误false]
     */
    private function AlipayRsaVerify($prestr, $sign)
    {
        $sign = base64_decode($sign);
        $public_key = file_get_contents(ROOT_PATH.'Rsakeys/rsa_alipay_mini_public_key.pem');
        $pkeyid = openssl_pkey_get_public($public_key);
        if($pkeyid)
        {
            $verify = openssl_verify($prestr, $sign, $pkeyid, OPENSSL_ALGO_SHA256);
            openssl_free_key($pkeyid);
        }
        return (isset($verify) && $verify == 1) ? true : false;
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
        $priKey = file_get_contents(ROOT_PATH.'Rsakeys/mini_private_key.pem');
        $res = openssl_get_privatekey($priKey);
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
     * [AlipayQrcodeCreate 小程序二维码创建]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-28T21:31:41+0800
     * @param    [string]  $query    [生成小程序启动参数（如：type=page&page=shop&value=5）]
     * @param    [string]  $describe [二维码描述（默认：美啦网）]
     */
    public function AlipayQrcodeCreate($query, $describe = '美啦网')
    {
        // 请求参数
        $params = [
            'app_id'            =>  C('alipay_mini_appid'),
            'method'            =>  'alipay.open.app.qrcode.create',
            'charset'           =>  'utf-8',
            'format'            =>  'JSON',
            'sign_type'         =>  'RSA2',
            'timestamp'         =>  date('Y-m-d H:i:s'),
            'version'           =>  '1.0',
        ];
        $biz_content = [
            'url_param'     =>  C('alipay_mini_default_page'),
            'query_param'   =>  $query,
            'describe'      =>  $describe,
        ];
        $params['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $p = $this->GetParamSign($params);
        $params['sign'] = $this->MyRsaSign($p['value']);

        // 执行请求
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $params);

        // 结果正确则验证签名 并且 存储缓存返回access_token
        if(!empty($result['alipay_open_app_qrcode_create_response']['code']) && $result['alipay_open_app_qrcode_create_response']['code'] == 10000)
        {
            // 验证签名正确则存储缓存返回数据
            if(!$this->SyncRsaVerify($result, 'alipay_open_app_qrcode_create_response'))
            {
                return false;
            }
            return $result['alipay_open_app_qrcode_create_response'];
        }
        return false;        
    }

}
?>