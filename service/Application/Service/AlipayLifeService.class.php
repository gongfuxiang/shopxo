<?php

namespace Service;

/**
 * 支付宝生活号服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeService
{
    /**
     * 根据appid获取一条生活号事件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppidLifeRow($params = [])
    {
        if(!empty($params['appid']))
        {
            return M('AlipayLife')->where(['appid'=>$params['appid']])->find();
        }
        return null;
    }

    /**
     * 用户取消关注生活号
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [boolean]                [成功true, 失败false]
     */
    public static function UserUnfollow($params = [])
    {
        if(!empty($params['alipay_openid']))
        {
            $life = self::AppidLifeRow($params);
            $user = M('User')->where(['alipay_openid'=>$params['alipay_openid']])->find();
            if(!empty($life) && !empty($user))
            {
                return M('AlipayLifeUser')->where(['user_id'=>$user['id'], 'alipay_life_id'=>$life['id']])->delete() !== false;
            }
        }
        return false;
    }

    /**
     * 用户关注/进入生活号
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [boolean]                [成功true, 失败false]
     */
    public static function UserEnter($params = [])
    {
        $life = self::AppidLifeRow($params);
        if(!empty($params['alipay_openid']) && !empty($life))
        {
            $user = M('User')->where(['alipay_openid'=>$params['alipay_openid']])->find();
            if(empty($user))
            {
                $data = [
                    'alipay_openid'     => $params['alipay_openid'],
                    'nickname'          => isset($params['user_name']) ? $params['user_name'] : '',
                    'add_time'          => time(),
                ];
                $user_id = M('User')->add($data);
            } else {
                $user_id = $user['id'];
            }
            if(!empty($user_id))
            {
                $life_user_data = [
                    'user_id'       => $user_id,
                    'alipay_life_id'=> $life['id'],
                ];
                $life_user = M('AlipayLifeUser')->where($life_user_data)->find();
                if(empty($life_user))
                {
                    $life_user_data['add_time'] = time();
                    return M('AlipayLifeUser')->add($life_user_data) > 0;
                } else {
                    return M('AlipayLifeUser')->where($life_user_data)->save(['enter_count'=>$life_user['enter_count']+1, 'upd_time'=>time()]) !== false;
                }
            }
        }
        return false;
    }
}
?>