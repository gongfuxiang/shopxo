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
namespace app\plugins\touristbuy;

use think\Db;
use app\service\UserService;

/**
 * 问答系统服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Service
{
    /**
     * 游客注册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function TouristReg($params = [])
    {
        // 获取登录用户
        $user = UserService::LoginUserInfo();
        if(!empty($user))
        {
            return DataReturn('已登录，请先退出', -1);
        }

        // 是否重复注册
        $tourist_user_id = session('tourist_user_id');
        if(!empty($tourist_user_id))
        {
            // 用户登录session纪录
            if(UserService::UserLoginRecord($tourist_user_id))
            {
                return DataReturn('游客登录成功', 0);
            } else {
                session('tourist_user_id', null);
            }
        }

        // 游客数据
        $data = [
            'username'      => '游客',
            'nickname'      => '游客',
            'status'        => 0,
            'add_time'      => time(),
            'upd_time'      => time(),
        ];

        // 数据添加
        $user_id = Db::name('User')->insertGetId($data);
        if($user_id > 0)
        {
            // 单独存储用户id
            session('tourist_user_id', $user_id);

            // 用户登录session纪录
            if(UserService::UserLoginRecord($user_id))
            {
                return DataReturn('游客登录成功', 0);
            }
        }
        return DataReturn('游客登录失败', -100);
    }
}
?>