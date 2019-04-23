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
namespace app\plugins\ucenter;

use think\Db;
use app\service\UserService;
use app\service\SafetyService;

/**
 * UCenter - Api
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Api
{
    /**
     * 密码修改
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function LoginPwdUpdate($params = [])
    {
        $user = empty($params['data']) ? '' : json_decode(htmlspecialchars_decode($params['data']), true);
        $data = empty($params['params']) ? '' : json_decode(htmlspecialchars_decode($params['params']), true);

        if(empty($user))
        {
            return json(DataReturn('用户信息为空', -400));
        }
        if(empty($data))
        {
            return json(DataReturn('参数为空', -401));
        }
        if(empty($data['accounts']) || empty($data['my_pwd']) || empty($data['new_pwd']) || empty($data['confirm_new_pwd']))
        {
            return json(DataReturn('账号或密码为空', -402));
        }

        // 用户信息
        $where = array('username|mobile|email' => $data['accounts'], 'is_delete_time'=>0);
        $temp = Db::name('User')->where($where)->find();
        if(empty($temp))
        {
            return json(DataReturn('用户信息不存在', -402));
        }

        // 调用服务层
        $salt = GetNumberCode(6);
        $user_data = [
            'accounts'          => $data['accounts'],
            'my_pwd'            => $data['my_pwd'],
            'new_pwd'           => $data['new_pwd'],
            'confirm_new_pwd'   => $data['confirm_new_pwd'],
            'user'              => $temp,
        ];
        $ret = SafetyService::LoginPwdUpdate($user_data);
        return json(DataReturn($ret['msg'], $ret['code']));
    }

    /**
     * 登录
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function Login($params = [])
    {
        $user = empty($params['data']) ? '' : json_decode(htmlspecialchars_decode($params['data']), true);
        $data = empty($params['params']) ? '' : json_decode(htmlspecialchars_decode($params['params']), true);

        if(empty($user))
        {
            return json(DataReturn('用户信息为空', -400));
        }
        if(empty($data))
        {
            return json(DataReturn('参数为空', -401));
        }
        if(empty($data['accounts']) || empty($data['pwd']))
        {
            return json(DataReturn('账号或密码为空', -402));
        }

        // 调用服务层
        $ret = UserService::Login($data);
        return json(DataReturn($ret['msg'], $ret['code']));
    }

    /**
     * 注册
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function Register($params = [])
    {
        $user = empty($params['data']) ? '' : json_decode(htmlspecialchars_decode($params['data']), true);
        $data = empty($params['params']) ? '' : json_decode(htmlspecialchars_decode($params['params']), true);

        if(empty($user))
        {
            return json(DataReturn('用户信息为空', -400));
        }
        if(empty($data))
        {
            return json(DataReturn('参数为空', -401));
        }
        if(empty($data['accounts']) || empty($data['pwd']))
        {
            return json(DataReturn('账号或密码为空', -402));
        }

        // 用户注册数据
        $salt = GetNumberCode(6);
        $user_data = [
            'username'      => $data['accounts'],
            'nickname'      => $data['accounts'],
            'email'         => CheckEmail($data['accounts']) ? $data['accounts'] : '',
            'mobile'        => CheckMobile($data['accounts']) ? $data['accounts'] : '',
            'salt'          => $salt,
            'pwd'           => LoginPwdEncryption($data['pwd'], $salt),
        ];

        // 调用服务层
        $ret = UserService::UserInsert($user_data, $data);
        if($ret['code'] == 0)
        {
            $ret = UserService::Login($data);
        }
        return json(DataReturn($ret['msg'], $ret['code']));
    }

    /**
     * 退出
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function Logout($params = [])
    {
        $user = empty($params['data']) ? '' : json_decode(htmlspecialchars_decode($params['data']), true);

        if(empty($user))
        {
            return json(DataReturn('用户信息为空', -400));
        }

        // 调用服务层
        $ret = UserService::Logout();
        return json(DataReturn($ret['msg'], $ret['code']));
    }

}
?>