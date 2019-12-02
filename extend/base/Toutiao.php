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
    /**
     * [__construct 构造方法]
     */
    public function __construct(){}

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
        if(empty($params['config']))
        {
            return ['status'=>-1, 'msg'=>'配置有误'];
        }

        // 获取授权
        $url = 'https://developer.toutiao.com/api/apps/jscode2session?appid='.$params['config']['appid'].'&secret='.$params['config']['secret'].'&code='.$params['authcode'];
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
}
?>