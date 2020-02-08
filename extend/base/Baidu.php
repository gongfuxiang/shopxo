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
     * @param   [array]     $config         [配置信息]
     */
    public function __construct($config = [])
    {
        $this->_appid       = isset($config['appid']) ? $config['appid'] : '';
        $this->_appkey      = isset($config['key']) ? $config['key'] : '';
        $this->_appsecret   = isset($config['secret']) ? $config['secret'] : '';
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
        $session_data = cache($login_key);
        if(empty($session_data))
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
        cache($data_key, $data);

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
        $result = json_decode($this->HttpRequestPost('https://spapi.baidu.com/oauth/jscode2sessionkey', $data), true);
        if(!empty($result['openid']))
        {
            // 缓存SessionKey
            $key = 'baidu_user_login_'.$result['openid'];

            // 缓存存储
            cache($key, $result);
            return ['status'=>0, 'msg'=>'授权成功', 'data'=>$result['openid']];
        }
        return ['status'=>-1, 'msg'=>$result['error_description']];
    }

    /**
     * [MiniQrCodeCreate 二维码创建]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-02T19:53:10+0800
     * @param    [string]  $params['page']      [页面地址]
     * @param    [string]  $params['scene']     [参数]
     * @return   [string]                       [成功返回文件流, 失败则空]
     */
    public function MiniQrCodeCreate($params)
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'page',
                'error_msg'         => 'page地址不能为空',
            ],
            [
                'checked_type'      => 'length',
                'checked_data'      => '1,32',
                'key_name'          => 'scene',
                'error_msg'         => 'scene参数 1~32 个字符之间',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取access_token
        $access_token = $this->GetMiniAccessToken();
        if($access_token === false)
        {
            return DataReturn('access_token获取失败', -1);
        }

        // 获取二维码
        $url = 'https://openapi.baidu.com/rest/2.0/smartapp/qrcode/getunlimited?access_token='.$access_token;
        $path = $params['page'].'?'.$params['scene'];
        $data = [
            'path'  => $path,
            'width' => empty($params['width']) ? 1000 : intval($params['width']),
        ];
        $res = $this->HttpRequestPost($url, $data);
        if(!empty($res))
        {
            if(stripos($res, 'errno') === false)
            {
                return DataReturn('获取成功', 0, $res);
            }
            $res = json_decode($res, true);
            $msg = isset($res['errmsg']) ? $res['errmsg'] : '获取二维码失败';
        } else {
            $msg = '获取二维码失败';
        }
        return DataReturn($msg, -1);
    }

    /**
     * [GetMiniAccessToken 获取access_token]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-02T19:53:42+0800
     */
    private function GetMiniAccessToken()
    {
        // 缓存key
        $key = $this->_appid.'_access_token';
        $result = cache($key);
        if(!empty($result))
        {
            if($result['expires_in'] > time())
            {
                return $result['access_token'];
            }
        }

        // 网络请求
        $url = 'https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&scope=smartapp_snsapi_base&client_id='.$this->_appkey.'&client_secret='.$this->_appsecret;
        $result = $this->HttpRequestGet($url);
        if(!empty($result['access_token']))
        {
            // 缓存存储
            $result['expires_in'] += time();
            cache($key, $result);
            return $result['access_token'];
        }
        return false;
    }

    /**
     * [HttpRequestGet get请求]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-03T19:21:38+0800
     * @param    [string]           $url [url地址]
     * @return   [array]                 [返回数据]
     */
    private function HttpRequestGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res, true);
    }

    /**
     * [HttpRequestPost 网络请求]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]          $url  [请求url]
     * @param    [array]           $data [发送数据]
     * @return   [mixed]                 [请求返回数据]
     */
    private function HttpRequestPost($url, $data)
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
        return $reponse;
    }
}
?>