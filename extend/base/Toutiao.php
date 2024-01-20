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
 * 头条驱动
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-10-31
 */
class Toutiao
{
    // 配置信息
    private $config;

    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2019-10-27
     * @param    [array]     $config         [应用配置信息]
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * 检验数据的真实性，并且获取解密后的明文
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
        $login_key = 'toutiao_user_login_'.$openid;
        $session_data = MyCache($login_key);
        if(empty($session_data))
        {
            return DataReturn(MyLang('common_extend.base.common.session_key_empty_tips'), -1);
        }

        // iv长度
        if(strlen($iv) != 24)
        {
            return DataReturn(MyLang('common_extend.base.common.iv_error_tips'), -1);
        }

        // 加密函数
        if(!function_exists('openssl_decrypt'))
        {
            return DataReturn(MyLang('openssl_no_support_tips'), -1);
        }

        $result = openssl_decrypt(base64_decode($encrypted_data), "AES-128-CBC", base64_decode($session_data['session_key']), 1, base64_decode($iv));
        $data = json_decode($result, true);
        if($data == NULL)
        {
            return DataReturn(MyLang('common_extend.base.common.please_try_again_tips'), -1);
        }
        if($data['watermark']['appid'] != $this->config['appid'])
        {
            return DataReturn(MyLang('appid_mismatch_tips'), -1);
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 用户授权
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public function GetAuthSessionKey($params = [])
    {
        if(empty($params['authcode']))
        {
            return DataReturn(MyLang('common_extend.base.common.auth_code_empty_tips'), -1);
        }
        if(empty($this->config['appid']) || empty($this->config['secret']))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
        }

        // 获取授权
        $url = 'https://developer.toutiao.com/api/apps/jscode2session?appid='.$this->config['appid'].'&secret='.$this->config['secret'].'&code='.$params['authcode'];
        $result = json_decode(RequestGet($url), true);
        if(empty($result))
        {
            return DataReturn(MyLang('common_extend.base.common.auth_api_request_fail_tips'), -1);
        }
        if(!empty($result['openid']))
        {
            // 缓存SessionKey
            $key = 'toutiao_user_login_'.$result['openid'];

            // 缓存存储
            MyCache($key, $result);
            return DataReturn(MyLang('auth_success'), 0, $result);
        }
        $msg = empty($result['errmsg']) ? MyLang('common_extend.base.common.auth_api_request_error_tips') : $result['errmsg'];
        return DataReturn($msg, -1);
    }

    /**
     * 二维码创建
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
                'error_msg'         => MyLang('common_extend.base.common.page_empty_tips'),
            ],
            [
                'checked_type'      => 'length',
                'checked_data'      => '1,32',
                'key_name'          => 'scene',
                'error_msg'         => MyLang('common_extend.base.common.scene_empty_tips'),
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
            return DataReturn(MyLang('common_extend.base.common.access_token_request_fail_tips'), -1);
        }

        // 获取二维码
        $url = 'https://developer.toutiao.com/api/apps/qrcode';
        $path = $params['page'].'?'.$params['scene'];
        $data = [
            'access_token'  => $access_token,
            'appname'       => 'toutiao',
            'path'          => urlencode($path),
            'width'         => empty($params['width']) ? 1000 : intval($params['width']),
        ];
        $res = $this->HttpRequestPost($url, $data, true);
        if(!empty($res))
        {
            if(stripos($res, 'errcode') === false)
            {
                return DataReturn(MyLang('get_success'), 0, $res);
            }
            $res = json_decode($res, true);
            $msg = isset($res['errmsg']) ? $res['errmsg'] : MyLang('common_extend.base.common.get_qrcode_fail_tips');
        } else {
            $msg = MyLang('common_extend.base.common.get_qrcode_fail_tips');
        }
        return DataReturn($msg, -1);
    }

    /**
     * 获取access_token
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-02T19:53:42+0800
     */
    public function GetMiniAccessToken()
    {
        // 缓存key
        $key = $this->config['appid'].'_access_token';
        $result = MyCache($key);
        if(!empty($result))
        {
            if($result['expires_in'] > time())
            {
                return $result['access_token'];
            }
        }

        // 网络请求
        $url = 'https://developer.toutiao.com/api/apps/token?grant_type=client_credential&appid='.$this->config['appid'].'&secret='.$this->config['secret'];
        $result = $this->HttpRequestGet($url);
        if(!empty($result['access_token']))
        {
            // 缓存存储
            $result['expires_in'] += time();
            MyCache($key, $result);
            return $result['access_token'];
        }
        return false;
    }

    /**
     * 获取client_token
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-01-02T19:53:42+0800
     */
    public function GetMiniClientToken()
    {
        // 缓存key
        $key = $this->config['appid'].'_client_token';
        $result = MyCache($key);
        if(!empty($result))
        {
            if($result['expires_in'] > time())
            {
                return $result['access_token'];
            }
        }

        // 网络请求
        $params = [
            'client_key'     => $this->config['appid'],
            'client_secret'  => $this->config['secret'],
            'grant_type'     => 'client_credential',
        ];
        $result = json_decode($this->HttpRequestPost('https://open.douyin.com/oauth/client_token/', $params, true, ['Content-Type: application/json']), true);
        if(!empty($result['data']) && !empty($result['data']['access_token']))
        {
            // 缓存存储
            $result['data']['expires_in'] += time();
            MyCache($key, $result['data']);
            return $result['data']['access_token'];
        }
        return false;
    }

    /**
     * get请求
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
     * curl模拟post
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T21:58:54+0800
     * @param    [string]   $url        [请求地址]
     * @param    [array]    $post       [发送的post数据]
     * @param    [boolean]  $is_json    [是否使用 json 数据发送]
     * @return   [mixed]                [请求返回的数据]
     */
    private function HttpRequestPost($url, $post, $is_json = false, $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_URL, $url);

        // 是否 json
        if($is_json)
        {
            $data_string = json_encode($post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            if(empty($header))
            {
                $header = [
                    "Content-Type: application/json; charset=utf-8",
                    "Content-Length: " . strlen($data_string)
                ];
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            if(empty($header))
            {
                $header = [
                    "Content-Type: application/x-www-form-urlencoded",
                    "cache-control: no-cache"
                ];
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
?>