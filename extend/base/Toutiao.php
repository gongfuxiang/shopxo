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
 * 头条驱动
 * @author  Devil
 * @version V_1.0.0
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
            return ['status'=>-1, 'msg'=>'授权码有误'];
        }
        if(empty($this->config['appid']) || empty($this->config['secret']))
        {
            return ['status'=>-1, 'msg'=>'配置有误'];
        }

        // 获取授权
        $url = 'https://developer.toutiao.com/api/apps/jscode2session?appid='.$this->config['appid'].'&secret='.$this->config['secret'].'&code='.$params['authcode'];
        $result = json_decode(file_get_contents($url), true);
        if(empty($result['openid']))
        {
            return ['status'=>-1, 'msg'=>$result['errmsg']];
        }
        return ['status'=>0, 'msg'=>'授权成功', 'data'=>$result['openid']];
    }

    /**
     * 支付签名生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-29
     * @desc    description
     * @param   [array]          $data   [需要生成签名的数据]
     * @param   [string]         $secret [密钥]
     */
    public function PaySignCreated($data, $secret)
    {
        ksort($data);
        $sign = '';
        foreach($data AS $key=>$val)
        {
            if($key != 'sign' && $key != 'risk_info' && $val != '')
            {
                $sign .= "$key=$val&";
            }
        }
        $sign = substr($sign, 0, -1);
        return md5($sign.$secret);
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
        $key = $this->config['appid'].'_access_token';
        $result = cache($key);
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
            cache($key, $result);
            return $result['access_token'];
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