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
namespace app\plugins\userloginrewardintegral;

use think\Db;
use app\service\PluginsService;
use app\service\IntegralService;
use app\service\UserService;

/**
 * 登录奖励积分 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook
{
    /**
     * 应用响应入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function run($params = [])
    {
        // 是否控制器钩子
        if(isset($params['is_control']) && $params['is_control'] === true && !empty($params['hook_name']))
        {
            if(!empty($params['user_id']))
            {
                switch($params['hook_name'])
                {
                    // 用户登录成功后赠送积分
                    case 'plugins_control_user_login_end' :
                        $ret = $this->LoginGiveIntegral($params);
                        break;

                    default :
                        $ret = DataReturn('无需处理', 0);
                }
                return $ret;
            } else {
                return DataReturn('钩子传入参数有误', -600);
            }

        // 默认返回视图
        } else {
            return '';
        }
    }

    /**
     * 赠送积分
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-14T22:19:08+0800
     * @param    [array]                   $params [参数]
     */
    private function LoginGiveIntegral($params)
    {
        // 获取应用数据
        $ret = PluginsService::PluginsData('userloginrewardintegral');
        if($ret['code'] == 0)
        {
            // 限制时间是否已结束
            if(!empty($ret['data']['time_start']))
            {
                // 是否已开始
                if(strtotime($ret['data']['time_start']) > time())
                {
                    return DataReturn('不在限制时间范围、无需处理', 0);
                }
            }
            if(!empty($ret['data']['time_end']))
            {
                // 是否已结束
                if(strtotime($ret['data']['time_end']) < time())
                {
                    return DataReturn('不在限制时间范围、无需处理', 0);
                }
            }

            // 是否日一次限制
            if(isset($ret['data']['is_day_once']) && $ret['data']['is_day_once'] == 1)
            {
                $where = [
                    ['user_id', '=', $params['user_id']],
                    ['add_time', '>=', strtotime(date('Y-m-d 00:00:00'))],
                    ['type', '=', 1],
                    ['msg', '=', '登录奖励积分'],
                ];
                $log = Db::name('UserIntegralLog')->where($where)->find();
                if(!empty($log))
                {
                    return DataReturn('今日已赠送、无需处理', 0);
                }
            }

            // 获取用户积分
            $give_integral = empty($ret['data']['give_integral']) ? 0 : intval($ret['data']['give_integral']);
            if(!empty($give_integral))
            {
                // 用户积分添加
                $user_integral = Db::name('User')->where(['id'=>$params['user_id']])->value('integral');
                if(!Db::name('User')->where(['id'=>$params['user_id']])->setInc('integral', $give_integral))
                {
                    return DataReturn('登录奖励积分失败', -10);
                }

                // 积分日志
                IntegralService::UserIntegralLogAdd($params['user_id'], $user_integral, $user_integral+$give_integral, '登录奖励积分', 1);

                // 更新用户登录缓存数据
                UserService::UserLoginRecord($params['user_id']);

                return DataReturn('登录奖励积分成功', 0);
            } else {
                return DataReturn('登录奖励积分应用配置有误', -600);
            }
        } else {
            return $ret;
        }
    }
}
?>