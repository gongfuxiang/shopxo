<?php

namespace app\plugins\shopoauth;

use app\plugins\shopoauth\ThinkOauth;

/**
 * 登入事件
 * @author   Guoguo
 * @blog     http://gadmin.cojz8.com
 * @version  1.0.0
 * @datetime 2019年3月14日
 */
class LoginEvent
{
    //登录成功，获取腾讯QQ用户信息
    public function qq($token)
    {
        $qq = ThinkOauth::getInstance('qq', $token);
        $data = $qq->call('user/get_user_info');
        if ($data['ret'] == 0) {
            $userInfo['type'] = 'QQ';
            $userInfo['name'] = $data['nickname'];
            $userInfo['nick'] = $data['nickname'];
            $userInfo['head'] = $data['figureurl_2'];
            $userInfo['openid'] = $qq->openid();
			$userInfo['token'] = $token;
			return $userInfo;
        } else {
            throw new \think\Exception("获取腾讯QQ用户信息失败：{$data['msg']}");
        }
    }
}