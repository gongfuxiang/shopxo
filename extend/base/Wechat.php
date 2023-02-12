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
 * 微信驱动
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-06-28
 * @desc    支持所有文件存储到硬盘
 */
class Wechat
{
    // appid
    private $_appid;

    // appsecret
    private $_appsecret;

    /**
     * 构造方法
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
     * 小程序发送订阅消息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-06
     * @desc    description
     * @param    [string]  $params['page']      [页面地址]
     * @param    [string]  $params['scene']     [参数]
     * @return   [string]                       [成功返回文件流, 失败则空]
     */
    public function MiniSubscribeMessage($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'touser',
                'error_msg'         => MyLang('common_extend.base.wechat.touser_openid_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'template_id',
                'error_msg'         => MyLang('common_extend.base.wechat.template_id_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'data',
                'error_msg'         => MyLang('common_extend.base.wechat.data_empty_tips'),
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

        // 发送订阅消息
        $url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token='.$access_token;
        $data = [
            'touser'      => $params['touser'],
            'template_id' => $params['template_id'],
            'data'        => $params['data'],
        ];
        
        // 跳转页面，可以不传 仅限本小程序内的页面。支持带参数,（示例index?foo=bar）
        if(isset($params['page']) && !empty($params['page'])){
            $data['page'] = $params['page'];
        }
        
        // 跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
        if(isset($params['miniprogram_state']) && !empty($params['miniprogram_state'])){
            $data['miniprogram_state'] = $params['miniprogram_state'];
        }
        
        // 进入小程序查看的语言类型，支持zh_CN(简中)、en_US(英文)、zh_HK(繁中)、zh_TW(繁中)，默认为zh_CN
        if(isset($params['lang']) && !empty($params['lang'])){
            $data['lang'] = $params['lang'];
        }
        
        $res = $this->HttpRequestPost($url, $data, false);
        if(!empty($res))
        {
            if(stripos($res, 'errcode') === false)
            {
                return DataReturn(MyLang('send_success'), 0, $res);
            }
            $res = json_decode($res, true);
            $msg = isset($res['errmsg']) ? $res['errmsg'] : MyLang('send_fail');
            if($msg == 'ok'){
                return DataReturn(MyLang('send_success'), 0, $res);
            }else{
                $msg = isset($res['errcode']) ? $res['errcode'].':'.$msg : $msg;
            }
        } else {
            $msg = MyLang('send_fail').'2';
        }
        return DataReturn($msg, -1);
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
        $login_key = 'wechat_user_login_'.$openid;
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
        if($data['watermark']['appid'] != $this->_appid)
        {
            return DataReturn(MyLang('appid_mismatch_tips'), -1);
        }
        return DataReturn('success', 0, $data);
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
            return DataReturn(MyLang('common_extend.base.common.auth_code_empty_tips'), -1);
        }

        // 请求获取session_key
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$this->_appid.'&secret='.$this->_appsecret.'&js_code='.$params['authcode'].'&grant_type=authorization_code';
        $result = $this->HttpRequestGet($url);
        if(empty($result))
        {
            return DataReturn(MyLang('common_extend.base.common.auth_api_request_fail_tips'), -1);
        }
        if(!empty($result['openid']))
        {
            // 缓存SessionKey
            $key = 'wechat_user_login_'.$result['openid'];

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
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token;
        $data = [
            'page'  => $params['page'],
            'scene' => $params['scene'],
            'width' => empty($params['width']) ? 1000 : intval($params['width']),
        ];
        $res = $this->HttpRequestPost($url, $data, false);
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
     * 获取用户手机号码
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-26
     * @desc    description
     * @param   [string]          $code  [临时code]
     */
    public function GetUserPhoneNumber($code)
    {
        // 获取access_token
        $access_token = $this->GetMiniAccessToken();
        if($access_token === false)
        {
            return DataReturn(MyLang('common_extend.base.common.access_token_request_fail_tips'), -1);
        }

        // 获取手机
        $url = 'https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token='.$access_token;
        $data = [
            'code'  => $code,
        ];
        $res = $this->HttpRequestPost($url, $data);
        if(!empty($res))
        {
            if(isset($res['errcode']) && $res['errcode'] == 0 && !empty($res['phone_info']))
            {
                return DataReturn('success', 0, $res['phone_info']['purePhoneNumber']);
            }
            return DataReturn($res['errmsg'].'('.$res['errcode'].')', -1);
        }
        return DataReturn(MyLang('api_request_fail_tips'), -1);
    }

    /**
     * 小程序获取access_token
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     */
    public function GetMiniAccessToken()
    {
        return $this->GetAccessToken();
    }

    /**
     * 获取微信环境签名配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public function GetSignPackage($params = [])
    {
        $access_token = $this->GetAccessToken();
        if(!empty($access_token))
        {
            // 获取 ticket
            $ticket = $this->GetTicket($access_token);

            // 注意 URL 一定要动态获取，不能 hardcode.
            $url = empty($params['url']) ? __MY_VIEW_URL__ : urldecode(htmlspecialchars_decode($params['url']));

            $timestamp = time();
            $nonce_str = $this->CreateNonceStr();

            // 这里参数的顺序要按照 key 值 ASCII 码升序排序
            $string = "jsapi_ticket={$ticket}&noncestr={$nonce_str}&timestamp={$timestamp}&url={$url}";
            return [
              'appId'     => $this->_appid,
              'nonceStr'  => $nonce_str,
              'timestamp' => $timestamp,
              'url'       => $url,
              'signature' => sha1($string),
              'rawString' => $string
            ];
        }
        return [];
    }

    /**
     * 签名随机字符串创建
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     * @param   [int]         $length [长度]
     */
    public function CreateNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for($i = 0; $i < $length; $i++)
        {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
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
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_appsecret;
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
     * 获取授权页ticket
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-26
     * @desc    description
     * @param   [string]          $access_token [access_token]
     * @param   [string]          $type [类型(默认jsapi)]
     */
    public function GetTicket($access_token, $type = 'jsapi')
    {
        // 缓存key
        $key = $this->_appid.'_get_ticket';
        $result = MyCache($key);
        if(!empty($result))
        {
            if($result['expires_in'] > time())
            {
                return $result['ticket'];
            }
        }

        // 网络请求
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type='.$type;
        $result = $this->HttpRequestGet($url);
        if(!empty($result) && !empty($result['ticket']))
        {
            // 缓存存储
            $result['expires_in'] += time();
            MyCache($key, $result);
            return $result['ticket'];
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

    /**
     * curl模拟post
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    [string]   $url        [请求地址]
     * @param    [array]    $data       [发送的post数据]
     * @param    [array]    $is_parsing [是否需要解析数据]
     * @return   [array]                [返回的数据]
     */
    public function HttpRequestPost($url, $data, $is_parsing = true)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_POST, true);
        $res = curl_exec($curl);
        curl_close($curl);
        if($is_parsing === true)
        {
            return json_decode($res, true);
        }
        return $res;
    }
}
?>