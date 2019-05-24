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
namespace app\plugins\weixinwebauthorization\service;

use think\Db;
use app\service\UserService;
use app\service\PluginsService;

/**
 * 微信登录服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Service
{
    /**
     * 微信登录注册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function WeixinAuthReg($params = [])
    {
        // 获取登录用户
        $user = UserService::LoginUserInfo();
        if(!empty($user))
        {
            return DataReturn('已登录，请先退出', -1);
        }

        // 是否有登录纪录
        if(!empty($params['openid']))
        {
            $user = UserService::UserInfo('weixin_web_openid', $params['openid']);
            if(!empty($user))
            {
                // 用户登录
                return UserService::UserLoginHandle($user['id'], $params);
            }
        } else {
            return DataReturn('用户openid为空', -1);
        }

        // 用户名
        $username = empty($params['nickname']) ? '微信'.RandomString(6) : $params['nickname'].RandomString(6);
        if(mb_strlen($username, 'utf-8') > 18)
        {
            $username = mb_substr($username, 0, 18);
        }

        // 游客数据
        $salt = GetNumberCode(6);
        $data = [
            'weixin_web_openid' => $params['openid'],
            'username'          => $username,
            'nickname'          => empty($params['nickname']) ? '' : $params['nickname'],
            'gender'            => empty($params['sex']) ? 0 : (isset($params['sex']) && $params['sex'] == 1) ? 2 : 1,
            'province'          => empty($params['province']) ? '' : $params['province'],
            'city'              => empty($params['city']) ? '' : $params['city'],
            'avatar'            => empty($params['headimgurl']) ? '' : $params['headimgurl'],
            'status'            => 0,
            'salt'              => $salt,
            'pwd'               => LoginPwdEncryption($username, $salt),
            'add_time'          => time(),
            'upd_time'          => time(),
        ];

        // 数据添加
        $ret = UserService::UserInsert($data, $params);
        if($ret['code'] == 0)
        {
            // 用户登录session纪录
            if(UserService::UserLoginRecord($ret['data']['user_id']))
            {
                return DataReturn('登录成功', 0, $ret['data']);
            }
        }
        return DataReturn('登录失败', -100);
    }
}
?>