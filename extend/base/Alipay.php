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

use app\service\AppMiniUserService;

/**
 * 支付宝驱动
 * @author  Devil
 * @version V_1.0.0
 */
class Alipay
{    
    /**
     * 获取用户授权信息
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-24T21:55:45+0800
     * @param    [string]            $app_id        [应用appid]
     * @param    [string]            $authcode      [用户授权码]
     * @return   [array|boolean]                    [失败false, 用户授权信息]
     */
    public function GetAuthSessionKey($app_id, $authcode = '')
    {
        if(empty($app_id) || empty($authcode))
        {
            return DataReturn(MyLang('params_error_tips'), -1);
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
            'code'              =>  $authcode,
            'grant_type'        =>  'authorization_code',
            'biz_content'       =>  'mini-authcode',
        ];

        // 生成签名参数+签名
        $p = $this->GetParamSign($param);
        $param['sign'] = $this->MyRsaSign($p['value']);

        // 执行请求
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $param);

        // 存在user_id则表示信息正确，
        if(!empty($result['alipay_system_oauth_token_response']['user_id']))
        {
            // 结果正确则验证签名、存储缓存并返回数据
            if(!$this->SyncRsaVerify($result, 'alipay_system_oauth_token_response'))
            {
                return DataReturn(MyLang('common_extend.base.alipay.sign_error_tips'), -1);
            }
            return DataReturn(MyLang('auth_success'), 0, $result['alipay_system_oauth_token_response']);
        }
        $msg = empty($result['error_response']['sub_msg']) ? MyLang('auth_fail') : $result['error_response']['sub_msg'];
        return DataReturn($msg, -1);
    }

    /**
     * 生成参数和签名
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
     * 签名字符串
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
        $res .= wordwrap(AppMiniUserService::AppMiniConfig('common_app_mini_alipay_rsa_private'), 64, "\n", true);
        $res .= "\n-----END RSA PRIVATE KEY-----";
        return openssl_sign($prestr, $sign, $res, OPENSSL_ALGO_SHA256) ? base64_encode($sign) : null;
    }

    /**
     * RSA解密
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
        $res .= wordwrap(AppMiniUserService::AppMiniConfig('common_app_mini_alipay_rsa_public'), 64, "\n", true);
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
        unset($res);
        return $result;
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
        $res = "-----BEGIN PUBLIC KEY-----\n";
        $res .= wordwrap(AppMiniUserService::AppMiniConfig('common_app_mini_alipay_out_rsa_public'), 64, "\n", true);
        $res .= "\n-----END PUBLIC KEY-----";
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
     * 同步返回签名验证
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
     * 网络请求
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
     * 小程序二维码创建
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-28T21:31:41+0800
     * @param    [string]  $params['page']    [页面地址]
     * @param    [string]  $params['scene'] [参数]
     */
    public function MiniQrCodeCreate($params)
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'appid',
                'error_msg'         => MyLang('common_extend.base.alipay.appid_empty_tips'),
            ],
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

        // 请求参数
        $data = [
            'app_id'            =>  $params['appid'],
            'method'            =>  'alipay.open.app.qrcode.create',
            'charset'           =>  'utf-8',
            'format'            =>  'JSON',
            'sign_type'         =>  'RSA2',
            'timestamp'         =>  date('Y-m-d H:i:s'),
            'version'           =>  '1.0',
        ];
        $biz_content = [
            'url_param'     =>  $params['page'],
            'query_param'   =>  $params['scene'],
            'describe'      =>  empty($params['describe']) ? 'ShopXO' : $params['describe'],
        ];
        $data['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        // 生成签名参数+签名
        $p = $this->GetParamSign($data);
        $data['sign'] = $this->MyRsaSign($p['value']);

        // 执行请求
        $result = $this->HttpRequest('https://openapi.alipay.com/gateway.do', $data);

        // 结果正确则验证签名 并且 存储缓存返回access_token
        $key = 'alipay_open_app_qrcode_create_response';
        if(!empty($result[$key]['code']) && $result[$key]['code'] == 10000)
        {
            // 验证签名正确则存储缓存返回数据
            if(!$this->SyncRsaVerify($result, $key))
            {
                return DataReturn(MyLang('common_extend.base.alipay.sign_error_tips'), -1);
            }
            return DataReturn(MyLang('get_success'), 0, $result[$key]['qr_code_url']);
        }
        $msg = isset($res['sub_msg']) ? $res['sub_msg'] : MyLang('common_extend.base.common.get_qrcode_fail_tips');
        return DataReturn($msg, -1);
    }
}
?>