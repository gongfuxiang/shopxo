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
namespace app\plugins\weixinwebauthorization\index;

use think\Controller;
use app\service\PluginsService;

/**
 * 微信登录 - 登录授权
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Auth extends Controller
{
    /**
     * 授权
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function Index($params = [])
    {
        $ret = PluginsService::PluginsData('weixinwebauthorization');
        if($ret['code'] == 0)
        {
            // 参数校验
            if(empty($ret['data']['appid']))
            {
                $this->assign('msg', 'appid未配置');
                return $this->fetch('public/tips_error');
            }

            // 回调地址
            $redirect_uri = urlencode(PluginsHomeUrl('weixinwebauthorization', 'auth', 'callback'));

            // 授权方式
            $auth_type = (isset($ret['data']['auth_type']) && $ret['data']['auth_type'] == 1) ? 'snsapi_userinfo' : 'snsapi_base';

            // 授权code
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$ret['data']['appid'].'&redirect_uri='.$redirect_uri.'&response_type=code&scope='.$auth_type.'&state=login#wechat_redirect';
            return redirect($url);
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
        }
    }

    /**
     * 回调
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function Callback($params = [])
    {
        // 参数校验
        if(empty($params['code']))
        {
            $this->assign('msg', '授权code为空');
            return $this->fetch('public/tips_error');
        }

        // 本地获取access_token

        // 远程获取access_token
        $ret = $this->RemoteAccessToken($params);

        echo '<pre>';
        print_r($ret);
    }

    /**
     * 获取access_token
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param   array           $params [description]
     */
    private function AccessToken($params = [])
    {
        
        
    }

    /**
     * 远程获取access_token
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param   array           $params [description]
     */
    private function RemoteAccessToken($params = [])
    {
        $ret = PluginsService::PluginsData('weixinwebauthorization');
        if($ret['code'] == 0)
        {
            // 参数校验
            if(empty($ret['data']['appid']))
            {
                return DataReturn('appid未配置', -1);
            }
            if(empty($ret['data']['secret']))
            {
                return DataReturn('secret未配置', -1);
            }
            if(empty($params['code']))
            {
                return DataReturn('code授权码为空', -1);
            }

            // 获取access_token
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$ret['data']['appid'].'&secret='.$ret['data']['secret'].'&code='.$params['code'].'&grant_type=authorization_code';
            $data = json_decode(file_get_contents($url), true);

            echo '<pre>';
            print_r($data);die;

        } else {
            return DataReturn($ret['msg'], -1);
        }
    }

}
?>