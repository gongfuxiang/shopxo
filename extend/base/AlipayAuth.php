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
namespace base;

/**
 * 支付宝授权驱动
 * @author  Devil
 * @version V_1.0.0
 */
class AlipayAuth
{
	/**
	 * [__construct 构造方法]
	 */
	public function __construct(){}

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
        	'param'	=>	substr($param, 0, -1),
            'value' =>  substr($sign, 0, -1),
        );
        if(!empty($config['key']))
        {
            $result['sign'] = $result['value'].$config['key'];
        }
        return $result;
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
        $res .= wordwrap(MyC('common_app_mini_alipay_rsa_private'), 64, "\n", true);
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
        $res .= wordwrap(MyC('common_app_mini_alipay_rsa_public'), 64, "\n", true);
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
        $res .= wordwrap(MyC('common_app_mini_alipay_out_rsa_public'), 64, "\n", true);
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
     * @param    [string]  $describe [二维码描述（默认：ShopXO）]
     */
    public function AlipayQrcodeCreate($query, $describe = 'ShopXO')
    {
        // 请求参数
        $params = [
            'app_id'            =>  MyC('common_app_mini_alipay_appid'),
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