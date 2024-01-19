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
namespace app\service;

use think\facade\Db;
use app\service\UserService;
use app\service\ConfigService;

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
                'error_msg'         => MyLang('common_service.safety.form_item_current_password_message'),
            ],
            [
                'checked_type'      => 'length',
                'checked_data'      => '6,18',
                'key_name'          => 'new_pwd',
                'error_msg'         => MyLang('common_service.safety.form_item_new_password_message'),
            ],
            [
                'checked_type'      => 'length',
                'checked_data'      => '6,18',
                'key_name'          => 'confirm_new_pwd',
                'error_msg'         => MyLang('common_service.safety.form_item_confirm_password_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取用户账户信息
        $user = UserService::UserInfo('id', intval($params['user']['id']), 'id,pwd,salt,username,mobile,email');

        // 原密码校验
        if(LoginPwdEncryption($params['my_pwd'], $user['salt']) != $user['pwd'])
        {
            return DataReturn(MyLang('common_service.safety.current_password_error_tips'), -4);
        }

        // 确认密码是否相等
        if($params['new_pwd'] != $params['confirm_new_pwd'])
        {
            return DataReturn(MyLang('common_service.safety.confirm_new_password_atypism_tips'), -5);
        }

        // 密码修改
        $accounts = empty($user['mobile']) ? (empty($user['email']) ? $user['username'] : $user['email']) : $user['mobile'];
        $ret = self::UserLoginPwdUpdate($accounts, $user['id'], $params['new_pwd']);
        if($ret['code'] != 0)
        {
            return DataReturn(MyLang('operate_success'), 0);
        }
        return $ret;
    }

    /**
     * 用户密码修改
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-03
     * @desc    description
     * @param   [string]         $accounts  [账号]
     * @param   [int]            $user_id   [用户id]
     * @param   [string]         $pwd       [密码]
     */
    public static function UserLoginPwdUpdate($accounts, $user_id, $pwd)
    {
        $salt = GetNumberCode(6);
        $data = [
            'pwd'       =>  LoginPwdEncryption(trim($pwd), $salt),
            'salt'      =>  $salt,
            'upd_time'  =>  time(),
        ];
        if(Db::name('User')->where(['id'=>$user_id])->update($data) !== false)
        {
            // 用户登录密码修改钩子
            $hook_name = 'plugins_service_user_login_pwd_update';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => ['accounts'=>$accounts, 'pwd'=>$pwd],
                'user_id'       => $user_id,
                'user'          => UserService::UserInfo('id', $user_id, 'id,username,nickname,mobile,email,gender,avatar,province,city,birthday'),
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            return DataReturn(MyLang('change_success'), 0);
        }
        return DataReturn(MyLang('change_fail'), -100);
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
        $user = UserService::UserInfo($field, $accounts, 'id');
        if(!empty($user))
        {
            $msg = ($type == 'sms') ? MyLang('common_service.safety.mobile_already_exist_tips') : MyLang('common_service.safety.email_already_exist_tips');
            return DataReturn($msg, -10);
        }
        return DataReturn(MyLang('common_service.safety.accounts_no_exist_tips'), 0);
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
        if(MyC('common_img_verify_state') == 1)
        {
            if(empty($params['verify']))
            {
                return DataReturn(MyLang('params_error_tips'), -10);
            }
            $verify = new \base\Verify($verify_params);
            if(!$verify->CheckExpire())
            {
                return DataReturn(MyLang('verify_code_expire_tips'), -11);
            }
            if(!$verify->CheckCorrect($params['verify']))
            {
                return DataReturn(MyLang('verify_code_error_tips'), -12);
            }
            return DataReturn(MyLang('operate_success'), 0, $verify);
        }
        return DataReturn(MyLang('operate_success'), 0);
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
                'error_msg'         => MyLang('common_service.safety.accounts_type_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
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
        $img_verify_params = [
            'key_prefix'     => 'safety',
            'expire_time'    => MyC('common_verify_expire_time'),
            'interval_time'  => MyC('common_verify_interval_time'),
        ];

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $img_verify_params);
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 发送验证码
        $verify_params = [
            'key_prefix'    => md5('safety_'.$accounts),
            'expire_time'   => MyC('common_verify_expire_time'),
            'interval_time' => MyC('common_verify_interval_time'),
        ];
        $code = GetNumberCode(4);
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_params);
            $status = $obj->SendCode($accounts, $code, ConfigService::SmsTemplateValue('home_sms_user_mobile_binding_template'));
        } else {
            $obj = new \base\Email($verify_params);
            $email_params = [
                'email'     =>  $accounts,
                'content'   =>  MyC('home_email_user_email_binding_template'),
                'title'     =>  MyC('home_site_name').' - '.MyLang('common_service.safety.send_verify_email_title'),
                'code'      =>  $code,
            ];
            $status = $obj->SendHtml($email_params);
        }
        if($status)
        {
            // 清除验证码
            if(isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }
            return DataReturn(MyLang('send_success'), 0);
        }
        return DataReturn(MyLang('send_fail').'['.$obj->error.']', -100);
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
                'error_msg'         => MyLang('common_service.safety.accounts_type_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => MyLang('verify_code_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
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
        $verify_params = [
            'key_prefix'   => md5('safety_'.$accounts),
            'expire_time'  => MyC('common_verify_expire_time')
        ];
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_params);
        } else {
            $obj = new \base\Email($verify_params);
        }
        // 是否已过期
        if(!$obj->CheckExpire())
        {
            return DataReturn(MyLang('verify_code_expire_tips'), -10);
        }
        // 是否正确
        if($obj->CheckCorrect($params['verify']))
        {
            // 校验成功标记
            MySession('safety_'.$params['type'], true);

            // 清除验证码
            $obj->Remove();

            return DataReturn(MyLang('check_success'), 0);
        }
        return DataReturn(MyLang('verify_code_error_tips'), -11);
    }

    /**
     * 账号更新
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
                'error_msg'         => MyLang('common_service.safety.accounts_type_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => MyLang('common_service.safety.accounts_emptyr_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => MyLang('verify_code_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
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
        } else {
            $user = UserService::UserInfo('id', intval($params['user']['id']), 'id,username,nickname,mobile,email,gender,avatar,province,city,birthday');
        }

        // 验证码校验
        $verify_params = [
            'key_prefix' => md5('safety_'.$params['accounts']),
            'expire_time' => MyC('common_verify_expire_time')
        ];
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_params);
        } else {
            $obj = new \base\Email($verify_params);
        }

        // 是否已过期
        if(!$obj->CheckExpire())
        {
            return DataReturn(MyLang('verify_code_expire_tips'), -10);
        }
        // 是否正确
        if(!$obj->CheckCorrect($params['verify']))
        {
            return DataReturn(MyLang('verify_code_error_tips'), -11);
        }

        // 更新帐号
        $field = ($params['type'] == 'sms') ? 'mobile' : 'email';
        $data = [
            $field      =>  $params['accounts'],
            'upd_time'  =>  time(),
        ];
        // 更新数据库
        if(Db::name('User')->where(['id'=>intval($params['user']['id'])])->update($data) !== false)
        {
            // 更新用户session数据
            UserService::UserLoginRecord($params['user']['id']);

            // 校验成功标记
            MySession('safety_'.$params['type'], null);

            // 清除验证码
            $obj->Remove();

            // 账号修改钩子
            $hook_name = 'plugins_service_user_accounts_update';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => ['accounts'=>$user[$field], 'new_accounts'=>$params['accounts'], 'field'=>$field],
                'user_id'       => $user['id'],
                'user'          => UserService::UserInfo('id', $user['id'], 'id,username,nickname,mobile,email,gender,avatar,province,city,birthday'),
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 账号注销
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-11-20
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AccountsLogout($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否还有未完成的订单
        $where = [
            ['user_id', '=', $params['user']['id']],
            ['status', '<=', 3]
        ];
        $count = Db::name('Order')->where($where)->count();
        if($count > 0)
        {
            return DataReturn(MyLang('common_service.safety.accounts_logout_refuse_msg', ['count'=>$count]), -1);
        }

        // 账号注销
        $data = [
            'status'            => 2,
            'is_logout_time'    => time(),
            'upd_time'          => time(),
        ];
        if(Db::name('User')->where(['id'=>$params['user']['id']])->update($data))
        {
            UserService::Logout();
            return DataReturn(MyLang('logout_success'), 0);
        }
        return DataReturn(MyLang('logout_fail'), -1);
    }
}
?>