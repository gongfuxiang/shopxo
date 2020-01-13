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
 * 百度驱动
 * @author  Devil
 * @version V_1.0.0
 */
class Baidu
{
    // appid
    private $_appid;

    // appkey
    private $_appkey;

    // appsecret
    private $_appsecret;

    /**
     * [__construct 构造方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-12-30T18:04:05+0800
     * @param   [string]     $app_id         [应用appid]
     * @param   [string]     $_appkey        [应用key]
     * @param   [string]     $app_secret     [应用密钥]
     */
    public function __construct($app_id, $app_key, $app_secret)
    {
        $this->_appid       = $app_id;
        $this->_appkey      = $app_key;
        $this->_appsecret   = $app_secret;
    }

    /**
     * [DecryptData 检验数据的真实性，并且获取解密后的明文]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-12-30T18:20:53+0800
     * @param    [string]  $encrypted_data     [加密的用户数据]
     * @param    [string]  $iv                 [与用户数据一同返回的初始向量]
     * @param    [string]  $openid             [解密后的原文]
     * @return   [array|string]                [成功返回用户信息数组, 失败返回错误信息]
     */
    public function DecryptData($encrypted_data, $iv, $openid)
    {
        // 登录授权session
        $login_key = 'baidu_user_login_'.$openid;
        $session_data = GS($login_key);
        if($session_data === false)
        {
            return ['status'=>-1, 'msg'=>'session key不存在'];
        }

        // iv长度
        if(strlen($iv) != 24)
        {
            return ['status'=>-1, 'msg'=>'iv长度错误'];
        }

        // 数据解密
        $session_key = base64_decode($session_data['session_key']);
        $iv = base64_decode($iv);
        $encrypted_data = base64_decode($encrypted_data);

        $plaintext = false;
        if (function_exists("openssl_decrypt")) {
            $plaintext = openssl_decrypt($encrypted_data, "AES-192-CBC", $session_key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        } else {
            if(!function_exists('mcrypt_module_open'))
            {
                return ['status'=>-1, 'msg'=>'mcrypt_module_open方法不支持'];
            }
            $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, null, MCRYPT_MODE_CBC, null);
            mcrypt_generic_init($td, $session_key, $iv);
            $plaintext = mdecrypt_generic($td, $encrypted_data);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
        }
        if ($plaintext == false) {
            return ['status'=>-1, 'msg'=>'解密失败'];
        }

        // trim pkcs#7 padding
        $pad = ord(substr($plaintext, -1));
        $pad = ($pad < 1 || $pad > 32) ? 0 : $pad;
        $plaintext = substr($plaintext, 0, strlen($plaintext) - $pad);

        // trim header
        $plaintext = substr($plaintext, 16);
        // get content length
        $unpack = unpack("Nlen/", substr($plaintext, 0, 4));
        // get content
        $data = json_decode(substr($plaintext, 4, $unpack['len']), true);
        // get app_key
        $app_key_decode = substr($plaintext, $unpack['len'] + 4);

        if($app_key_decode != $this->_appkey)
        {
            return ['status'=>-1, 'msg'=>'appkey不匹配'];
        }

        // 缓存存储
        $data_key = 'baidu_user_info_'.$openid;
        SS($data_key, $data);

        return ['status'=>0, 'data'=>$data];
    }

    /**
     * 用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function GetAuthSessionKey($params = [])
    {
        if(empty($params['authcode']))
        {
            return ['status'=>-1, 'msg'=>'授权码有误'];
        }

        $data = [
            'code'      => $params['authcode'],
            'client_id' => $this->_appkey,
            'sk'        => $this->_appsecret,
        ];
        $result = $this->HttpRequest('https://spapi.baidu.com/oauth/jscode2sessionkey', $data);
        if(!empty($result['openid']))
        {
            // 缓存SessionKey
            $key = 'baidu_user_login_'.$result['openid'];

            // 缓存存储
            SS($key, $result);
            return ['status'=>0, 'msg'=>'授权成功', 'data'=>$result['openid']];
        }
        return ['status'=>-1, 'msg'=>$result['error_description']];
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
}
?>