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
namespace base;

/**
 * QQ驱动
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-10-31
 * @desc    支持所有文件存储到硬盘
 */
class QQ
{
    // appid
    private $_appid;

    // appsecret
    private $_appsecret;

    /**
     * [__construct 构造方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-12-30T18:04:05+0800
     * @param   [string]     $app_id         [应用appid]
     * @param   [string]     $app_secret     [应用密钥]
     */
    public function __construct($app_id, $app_secret)
    {
        $this->_appid       = $app_id;
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
        $login_key = 'qq_user_login_'.$openid;
        $session_data = MyCache($login_key);
        if(empty($session_data))
        {
            return 'session key不存在';
        }

        // iv长度
        if(strlen($iv) != 24)
        {
            return 'iv长度错误';
        }

        // 加密函数
        if(!function_exists('openssl_decrypt'))
        {
            return 'openssl不支持';
        }

        $aes_cipher = base64_decode($encrypted_data);
        $result = openssl_decrypt($aes_cipher, "AES-128-CBC", base64_decode($session_data['session_key']), 1, base64_decode($iv));
        $data = json_decode($result, true);
        if($data == NULL)
        {
            return '请重试！';
        }
        if($data['watermark']['appid'] != $this->_appid)
        {
            return 'appid不匹配';
        }

        // 缓存存储
        $data_key = 'qq_user_info_'.$openid;
        MyCache($data_key, $data);

        return $data;
    }

    /**
     * [GetAuthSessionKey 根据授权code获取 session_key 和 openid]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-12-30T18:20:53+0800
     * @param    [string]     $authcode       [用户授权码]
     * @return   [string|boolean]             [失败false, 成功返回appid|]
     */
    public function GetAuthSessionKey($authcode)
    {
        // 请求获取session_key
        $url = 'https://api.q.qq.com/sns/jscode2session?appid='.$this->_appid.'&secret='.$this->_appsecret.'&js_code='.$authcode.'&grant_type=authorization_code';
        $result = $this->HttpRequestGet($url);
        if(!empty($result) && !empty($result['openid']))
        {
            // 从缓存获取用户信息
            $key = 'qq_user_login_'.$result['openid'];

            // 缓存存储
            MyCache($key, $result);
            return $result['openid'];
        }
        return false;
    }

    /**
     * 公共获取access_token
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     */
    public function GetAccessToken()
    {
        // 缓存key
        $key = $this->_appid.'_access_token';
        $result = MyCache($key);
        if(!empty($result))
        {
            if($result['expires_in'] > time())
            {
                return $result['access_token'];
            }
        }

        // 网络请求
        $url = 'https://api.q.qq.com/api/getToken?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_appsecret;
        $result = $this->HttpRequestGet($url);
        if(!empty($result) && !empty($result['access_token']))
        {
            // 缓存存储
            $result['expires_in'] += time();
            MyCache($key, $result);
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
    public function HttpRequestGet($url)
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
}
?>