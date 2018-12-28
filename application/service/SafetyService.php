<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

use think\Db;
use app\service\UserService;

/**
 * 安全服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SafetyService
{
    /**
     * 登录密码修改
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-28T10:38:23+0800
     * @param    [array]          $params [输入参数]
     */
    public static function LoginPwdUpdate($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'length',
                'checked_data'      => '6,18',
                'key_name'          => 'my_pwd',
                'error_msg'         => '当前密码格式 6~18 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'checked_data'      => '6,18',
                'key_name'          => 'new_pwd',
                'error_msg'         => '新密码格式 6~18 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'checked_data'      => '6,18',
                'key_name'          => 'confirm_new_pwd',
                'error_msg'         => '确认密码格式 6~18 个字符之间，与新密码一致',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取用户账户信息
        $user = Db::name('User')->field('id,pwd,salt')->find($params['user']['id']);

        // 原密码校验
        if(LoginPwdEncryption($params['my_pwd'], $user['salt']) != $user['pwd'])
        {
            return DataReturn('当前密码错误', -4);
        }

        // 确认密码是否相等
        if($params['new_pwd'] != $params['confirm_new_pwd'])
        {
            return DataReturn('确认密码与新密码不一致', -5);
        }

        // 更新用户密码
        $salt = GetNumberCode(6);
        $data = [
            'pwd'       =>  LoginPwdEncryption(trim($params['new_pwd']), $salt),
            'salt'      =>  $salt,
            'upd_time'  =>  time(),
        ];

        // 更新数据库
        if(Db::name('User')->where(['id'=>$params['user']['id']])->update($data) !== false)
        {
            return DataReturn('操作成功', 0);
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 帐号是否已存在
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-28T18:01:43+0800
     * @param    [string]       $accounts [帐号, 手机|邮箱]
     * @param    [string]       $type     [帐号类型, sms|email]
     */
    private static function IsExistAccounts($accounts, $type)
    {
        $field = ($type == 'sms') ? 'mobile' : 'email';
        $user = Db::name('User')->where([$field=>$accounts])->value('id');
        if(!empty($user))
        {
            $msg = ($type == 'sms') ? '手机号码已存在' : '电子邮箱已存在';
            return DataReturn($msg, -10);
        }
        return DataReturn('账户不存在', 0);
    }

    /**
     * 是否开启图片验证码校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-22T15:48:31+0800
     * @param    [array]    $params         [输入参数]
     * @param    [array]    $verify_params  [配置参数]
     * @return   [object]                   [图片验证码类对象]
     */
    private static function IsImaVerify($params, $verify_params)
    {
        if(MyC('home_img_verify_state') == 1)
        {
            if(empty($params['verify']))
            {
                return DataReturn('参数错误', -10);
            }
            $verify = new \base\Verify($verify_params);
            if(!$verify->CheckExpire())
            {
                return DataReturn('验证码已过期', -11);
            }
            if(!$verify->CheckCorrect($params['verify']))
            {
                return DataReturn('验证码错误', -12);
            }
            return DataReturn('操作成功', 0, $verify);
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 验证码发送
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-05T19:17:10+0800
     * @param    [array]          $params [输入参数]
     */
    public static function VerifySend($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '账户类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 账户
        if(empty($params['accounts']))
        {
            $accounts = ($params['type'] == 'sms') ? $params['user']['mobile'] : $params['user']['email'];
        } else {
            $accounts = $params['accounts'];

            // 帐号是否已存在
            $ret = self::IsExistAccounts($accounts, $params['type']);
            if($ret['code'] != 0)
            {
                return $ret;
            }
        }

        // 验证码基础参数
        $img_verify_params = array(
                'key_prefix' => 'safety',
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' =>  MyC('common_verify_time_interval'),
            );

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $img_verify_params);
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 发送验证码
        $verify_params = array(
                'key_prefix' => md5('safety_'.$accounts),
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' =>  MyC('common_verify_time_interval'),
            );
        $code = GetNumberCode(6);
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_params);
            $status = $obj->SendCode($accounts, $code, MyC('home_sms_user_mobile_binding'));
        } else {
            $obj = new \base\Email($verify_params);
            $email_params = array(
                    'email'     =>  $accounts,
                    'content'   =>  MyC('home_email_user_email_binding'),
                    'title'     =>  MyC('home_site_name').' - 电子邮箱绑定',
                    'code'      =>  $code,
                );
            $status = $obj->SendHtml($email_params);
        }
        
        // 状态
        if($status)
        {
            // 清除验证码
            if(isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return DataReturn('发送成功', 0);
        }
        return DataReturn('发送失败'.'['.$obj->error.']', -100);
    }

    /**
     * 原账户验证码校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-28T15:57:19+0800
     * @param    [array]          $params [输入参数]
     */
    public static function VerifyCheck($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '账户类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => '验证码不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 账户
        if(empty($params['accounts']))
        {
            $accounts = ($params['type'] == 'sms') ? $params['user']['mobile'] : $params['user']['email'];
        } else {
            $accounts = $params['accounts'];
        }

        // 验证码校验
        $verify_params = array(
                'key_prefix' => md5('safety_'.$accounts),
                'expire_time' => MyC('common_verify_expire_time')
            );
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_params);
        } else {
            $obj = new \base\Email($verify_params);
        }
        // 是否已过期
        if(!$obj->CheckExpire())
        {
            return DataReturn('验证码已过期', -10);
        }
        // 是否正确
        if($obj->CheckCorrect($params['verify']))
        {
            // 校验成功标记
            session('safety_'.$params['type'], true);

            // 清除验证码
            $obj->Remove();

            return DataReturn('正确', 0);
        }
        return DataReturn('验证码错误', -11);
    }

    /**
     * 账户更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-28T17:04:36+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AccountsUpdate($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '账户类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => '账户不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => '验证码不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 帐号是否已存在
        $ret = self::IsExistAccounts($params['accounts'], $params['type']);
         if($ret['code'] != 0)
            {
                return $ret;
            }

        // 验证码校验
        $verify_params = array(
                'key_prefix' => md5('safety_'.$params['accounts']),
                'expire_time' => MyC('common_verify_expire_time')
            );
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_params);
        } else {
            $obj = new \base\Email($verify_params);
        }
        // 是否已过期
        if(!$obj->CheckExpire())
        {
            return DataReturn('验证码已过期', -10);
        }
        // 是否正确
        if(!$obj->CheckCorrect($params['verify']))
        {
            return DataReturn('验证码错误', -11);
        }

        // 更新帐号
        $field = ($params['type'] == 'sms') ? 'mobile' : 'email';
        $data = array(
                $field      =>  $params['accounts'],
                'upd_time'  =>  time(),
            );
        // 更新数据库
        if(Db::name('User')->where(array('id'=>$params['user']['id']))->update($data) !== false)
        {
            // 更新用户session数据
            UserService::UserLoginRecord($params['user']['id']);

            // 校验成功标记
            session('safety_'.$params['type'], null);

            // 清除验证码
            $obj->Remove();

            return DataReturn('操作成功', 0);
        }
        return DataReturn('操作失败', -100);
    }
}
?>