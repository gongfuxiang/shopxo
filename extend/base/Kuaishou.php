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
 * 快手驱动
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-10-31
 */
class Kuaishou
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

        // 请求获取session_key
        $request_params = [
            'js_code'   => $params['authcode'],
            'app_id'    => $this->config['appid'],
            'app_secret'=> $this->config['secret'],
        ];
        $url = 'https://open.kuaishou.com/oauth2/mp/code2session';
        $ret = CurlPost($url, $request_params);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $result = json_decode($ret['data'], true);
        if(!empty($result['open_id']))
        {
            // 缓存SessionKey
            $key = 'kuaishou_user_login_'.$result['open_id'];

            // 缓存存储
            MyCache($key, $result);
            return DataReturn(MyLang('auth_success'), 0, ['openid'=>$result['open_id']]);
        }
        $msg = empty($result['error_msg']) ? MyLang('common_extend.base.common.auth_api_request_error_tips') : $result['error_msg'];
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
                return DataReturn('success', 0, $result['access_token']);
            }
        }

        // 网络请求
        $request_params = [
            'app_id'    => $this->config['appid'],
            'app_secret'=> $this->config['secret'],
            'grant_type'=> 'client_credentials',
        ];
        $url = 'https://open.kuaishou.com/oauth2/access_token';
        $ret = CurlPost($url, $request_params);
        if($ret['code'] != 0)
        {
            return $ret;
        }
        $result = json_decode($ret['data'], true);
        if(!empty($result['access_token']))
        {
            // 缓存存储
            $result['expires_in'] += time();
            MyCache($key, $result);
            return DataReturn(MyLang('auth_success'), 0, $result['access_token']);
        }
        $msg = empty($result['error_msg']) ? MyLang('common_extend.base.common.auth_api_request_error_tips') : $result['error_msg'];
        return DataReturn($msg, -1);
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
    private function HttpRequestPost($url, $post, $is_json = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_URL, $url);

        // 是否 json
        if($is_json)
        {
            $data_string = json_encode($post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json; charset=utf-8",
                    "Content-Length: " . strlen($data_string)
                )
            );
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "cache-control: no-cache"
                )
            );
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
?>