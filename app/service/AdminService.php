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
use app\service\SystemService;
use app\service\ApiService;
use app\service\AdminPowerService;
use app\service\ConfigService;

/**
 * 管理员服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AdminService
{
    // admin登录session key
    public static $admin_login_key = 'admin_login_info';

    /**
     * 角色列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RoleList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $data = Db::name('Role')->field($field)->where($where)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, $data);
    }

    /**
     * 管理员保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AdminSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'admin',
                'error_msg'         => MyLang('common_service.admin.save_admin_info_error_tips'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'mobile',
                'checked_data'      => 'CheckMobile',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.admin.form_item_mobile_message'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'email',
                'checked_data'      => 'CheckEmail',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.admin.form_item_email_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'gender',
                'checked_data'      => [0,1,2],
                'error_msg'         => MyLang('common_service.admin.save_gender_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(MyConst('common_admin_status_list'), 'value'),
                'error_msg'         => MyLang('common_service.admin.save_status_tips'),
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'mobile',
                'checked_data'      => 'Admin',
                'checked_key'       => 'id',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.admin.save_mobile_already_exist_tips'),
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'email',
                'checked_data'      => 'Admin',
                'checked_key'       => 'id',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.admin.save_email_already_exist_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        return empty($params['id']) ? self::AdminInsert($params) : self::AdminUpdate($params);
    }

    /**
     * 管理员添加
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AdminInsert($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'username',
                'error_msg'         => MyLang('common_service.admin.form_item_username_placeholder'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'username',
                'checked_data'      => 'CheckUserName',
                'error_msg'         => MyLang('common_service.admin.form_item_username_message'),
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'username',
                'checked_data'      => 'Admin',
                'checked_key'       => 'id',
                'error_msg'         => MyLang('common_service.admin.save_admin_already_exist_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'login_pwd',
                'error_msg'         => MyLang('common_service.admin.form_item_password_placeholder'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'login_pwd',
                'checked_data'      => 'CheckLoginPwd',
                'error_msg'         => MyLang('common_service.admin.form_item_password_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'role_id',
                'error_msg'         => MyLang('common_service.admin.form_item_role_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 添加账号
        $salt = GetNumberCode(6);
        $data = [
            'username'      => $params['username'],
            'login_salt'    => $salt,
            'login_pwd'     => LoginPwdEncryption($params['login_pwd'], $salt),
            'mobile'        => empty($params['mobile']) ? '' : $params['mobile'],
            'email'         => empty($params['email']) ? '' : $params['email'],
            'gender'        => intval($params['gender']),
            'status'        => intval($params['status']),
            'role_id'       => intval($params['role_id']),
            'add_time'      => time(),
        ];

        // 添加
        if(Db::name('Admin')->insert($data) > 0)
        {
            return DataReturn(MyLang('insert_success'), 0);
        }
        return DataReturn(MyLang('insert_fail'), -100);
    }

    /**
     * 管理员更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AdminUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'fun',
                'key_name'          => 'login_pwd',
                'checked_data'      => 'CheckLoginPwd',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.admin.form_item_password_message'),
            ],
        ];
        if($params['id'] != $params['admin']['id'])
        {
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'role_id',
                'error_msg'         => MyLang('common_service.admin.form_item_role_message'),
            ];
        }
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否非法修改超管
        if($params['id'] == 1 && $params['id'] != $params['admin']['id'])
        {
            return DataReturn(MyLang('illegal_operate_tips'), -1);
        }

        // 数据
        $data = [
            'mobile'        => empty($params['mobile']) ? '' : $params['mobile'],
            'email'         => empty($params['email']) ? '' : $params['email'],
            'gender'        => intval($params['gender']),
            'status'        => intval($params['status']),
            'upd_time'      => time(),
        ];

        // 密码
        if(!empty($params['login_pwd']))
        {
            $data['login_salt'] = GetNumberCode(6);
            $data['login_pwd'] = LoginPwdEncryption($params['login_pwd'], $data['login_salt']);
        }
        // 不能修改自身所属角色组
        if($params['id'] != $params['admin']['id'])
        {
            $data['role_id'] = intval($params['role_id']);
        }

        // 更新
        if(Db::name('Admin')->where(['id'=>intval($params['id'])])->update($data))
        {
            // 自己修改密码则重新登录
            if(!empty($params['login_pwd']) && $params['id'] == $params['admin']['id'])
            {
                self::LoginLogout();
            }
            
            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
    }

    /**
     * 管理员删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AdminDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 是否包含删除超级管理员
        if(in_array(1, $params['ids']))
        {
            return DataReturn(MyLang('common_service.admin.delete_super_admin_not_tips'), -1);
        }
           
        // 删除操作
        if(Db::name('Admin')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 管理员登录
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function Login($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(MyConst('common_login_type_list'), 'value'),
                'error_msg'         => MyLang('login_type_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => MyLang('accounts_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('admin_login_type', [], true)))
        {
            return DataReturn(MyLang('login_close_tips'), -1);
        }

        // 账户校验
        $ac = self::LoginAccountsCheck($params);
        if($ac['code'] != 0)
        {
            return $ac;
        }

        // 验证参数
        $verify_params = [
            'key_prefix'    => 'admin_login_'.md5($params['accounts']),
            'expire_time'   => MyC('common_verify_expire_time'),
        ];

        // 帐号密码登录需要校验密码
        if($params['type'] == 'username')
        {
            // 请求参数
            $password_message = MyLang('common_service.admin.form_item_password_message');
            $p = [
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'pwd',
                    'error_msg'         => $password_message,
                ],
                [
                    'checked_type'      => 'fun',
                    'key_name'          => 'pwd',
                    'checked_data'      => 'CheckLoginPwd',
                    'error_msg'         => $password_message,
                ],
            ];
            $ret = ParamsChecked($params, $p);
            if($ret !== true)
            {
                return DataReturn($ret, -1);
            }

            // 帐号密码登录是否开启图片验证码
            $verify_params['key_prefix'] = 'admin_login';
            $verify = self::IsImaVerify($params, $verify_params, MyC('admin_login_img_verify_state'));
            if($verify['code'] != 0)
            {
                return $verify;
            }
        } else {
            // 账户类型
            $obj = null;
            switch($params['type'])
            {
                // 短信
                case 'sms' :
                    $obj = new \base\Sms($verify_params);
                    break;

                // 邮箱
                case 'email' :
                    $obj = new \base\Email($verify_params);
                    break;

                // 未知的字段
                 default :
                    return DataReturn(MyLang('verify_type_error_tips'), -1);
            }

            // 验证码校验
            // sms, email
            if(isset($obj) && is_object($obj))
            {
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
            }
        }

        // 获取管理员
        $admin = Db::name('Admin')->field('id,token,username,mobile,email,login_pwd,login_salt,login_total,role_id')->where([$ac['data']=>$params['accounts'], 'status'=>0])->find();
        if(empty($admin))
        {
            return DataReturn(MyLang('account_abnormal_tips'), -2);
        }

        // 密码校验
        // 帐号密码登录需要校验密码
        if($params['type'] == 'username')
        {
            $pwd = LoginPwdEncryption($params['pwd'], $admin['login_salt']);
            if($pwd != $admin['login_pwd'])
            {
                return DataReturn(MyLang('password_error_tips'), -3);
            }
        }

        // 登录前钩子
        $hook_name = 'plugins_service_admin_login_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'admin_id'      => $admin['id'],
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 种session,更新数据库
        $login_salt = GetNumberCode(6);
        $admin['token'] = ApiService::CreatedUserToken($admin['id'], $login_salt);
        if(self::LoginSession($admin))
        {
            $data = [
                'token'        => $admin['token'],
                'login_total'  => $admin['login_total']+1,
                'login_time'   => time(),
            ];
            if($params['type'] == 'username')
            {
                $data['login_salt'] = $login_salt;
                $data['login_pwd'] = LoginPwdEncryption($params['pwd'], $login_salt);
            }
            if(Db::name('Admin')->where(['id'=>$admin['id']])->update($data))
            {
                // 清空权限缓存数据
                MyCache(SystemService::CacheKey('shopxo.cache_admin_left_menu_key').$admin['id'], null);
                MyCache(SystemService::CacheKey('shopxo.cache_admin_power_key').$admin['id'], null);
                MyCache(SystemService::CacheKey('shopxo.cache_admin_power_plugins_key').$admin['id'], null);

                // 权限菜单初始化
                AdminPowerService::PowerMenuInit($admin);

                // 成功并返回管理员信息
                $admin['login_total'] = $data['login_total'];
                unset($admin['login_pwd'], $admin['login_salt']);

                // 登录后钩子
                $hook_name = 'plugins_service_admin_login_end';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'admin_id'      => $admin['id'],
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }

                // 返回成功数据
                return DataReturn(MyLang('login_success'), 0, $admin);
            }
        }

        // 失败
        self::LoginLogout();
        return DataReturn(MyLang('login_fail_tips'), -100);
    }

    /**
     * 登录信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-26
     * @desc    description
     */
    public static function LoginInfo()
    {
        // 管理员信息
        $admin = null;

        // 是否有token
        $params = input();
        if(empty($params['token']))
        {
            // 获取管理员信息
            $admin = MySession(self::$admin_login_key);
            // 非退出则重新设置管理员信息
            if(RequestAction() != 'logout')
            {
                self::LoginSession($admin);
            }
        } else {
            // 获取管理员信息
            $info = Db::name('Admin')->field('id,token,username,mobile,email,login_total,role_id,login_total,login_salt')->where(['token'=>$params['token'], 'status'=>0])->find();
            if(!empty($info) && ApiService::CreatedUserToken($info['id'], $info['login_salt']) == $info['token'])
            {
                unset($info['login_salt']);
                $admin = $info;
            }
        }
        return $admin;
    }

    /**
     * 登录种session
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-26
     * @desc    description
     * @param   [array]          $admin [管理员登录信息]
     */
    public static function LoginSession($admin)
    {
        // 移除私密数据
        unset($admin['login_pwd'], $admin['login_salt']);
        // 存储session
        MySession(self::$admin_login_key, $admin);
        // 设置cookie数据
        MyCookie('admin_info', json_encode($admin, JSON_UNESCAPED_UNICODE), false);
        return true;
    }

    /**
     * 登录退出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-26
     * @desc    description
     */
    public static function LoginLogout()
    {
        return MySession(self::$admin_login_key, null);
    }

    /**
     * 管理员登录验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function LoginVerifySend($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => MyLang('accounts_empty_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(MyConst('common_login_type_list'), 'value'),
                'error_msg'         => MyLang('login_type_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('admin_login_type', [], true)))
        {
            return DataReturn(MyLang('login_close_tips'), -1);
        }

        // 验证码基础参数
        $verify_params = [
            'key_prefix'    => 'admin_login',
            'expire_time'   => MyC('common_verify_expire_time'),
            'interval_time' => MyC('common_verify_interval_time'),
        ];

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $verify_params, MyC('common_img_verify_state'));
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 账户校验
        $ac = self::LoginAccountsCheck($params);
        if($ac['code'] != 0)
        {
            return $ac;
        }

        // 验证码基础参数 key
        $verify_params['key_prefix'] = 'admin_login_'.md5($params['accounts']);

        // 发送验证码
        $code = GetNumberCode(4);
        switch($params['type'])
        {
            // 短信
            case 'sms' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params['accounts'], $code, ConfigService::SmsTemplateValue('admin_sms_login_template'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                        'email'     =>  $params['accounts'],
                        'content'   =>  MyC('admin_email_login_template'),
                        'title'     =>  MyC('home_site_name').MyLang('common_service.admin.login_verify_send_last_title'),
                        'code'      =>  $code,
                    ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn(MyLang('verify_code_not_support_send_error_tips'), -2);
        }
        
        // 状态
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
     * 登录账户校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private static function LoginAccountsCheck($params = [])
    {
        $field = '';
        switch($params['type'])
        {
            // 手机
            case 'sms' :
                // 手机号码格式
                if(!CheckMobile($params['accounts']))
                {
                     return DataReturn(MyLang('mobile_format_error_tips'), -2);
                }

                // 手机号码是否存在
                if(!self::IsExistAccounts($params['accounts'], 'mobile'))
                {
                     return DataReturn(MyLang('mobile_no_exist_error_tips'), -3);
                }
                $field = 'mobile';
                break;

            // 邮箱
            case 'email' :
                // 电子邮箱格式
                if(!CheckEmail($params['accounts']))
                {
                     return DataReturn(MyLang('email_format_error_tips'), -2);
                }

                // 电子邮箱是否存在
                if(!self::IsExistAccounts($params['accounts'], 'email'))
                {
                     return DataReturn(MyLang('email_no_exist_error_tips'), -3);
                }
                $field = 'email';
                break;

            // 用户名
            case 'username' :
                // 用户名格式
                if(!CheckUserName($params['accounts']))
                {
                     return DataReturn(MyLang('common_service.admin.form_item_username_message'), -2);
                }

                // 用户名是否存在
                if(!self::IsExistAccounts($params['accounts'], 'username'))
                {
                     return DataReturn(MyLang('accounts_error_tips'), -3);
                }
                $field = 'username';
                break;
        }
        return DataReturn(MyLang('operate_success'), 0, $field);
    }

    /**
     * 
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-08T10:27:14+0800
     * @param    [string] $accounts     [账户名称]
     * @param    [string] $field        [字段名称]
     * @return   [boolean]              [存在true, 不存在false]
     */
    
    /**
     * 账户是否存在
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     * @param   [string] $accounts     [账户名称]
     * @param   [string] $field        [字段名称]
     * @return  [boolean]              [存在true, 不存在false]
     */
    private static function IsExistAccounts($accounts, $field = 'username')
    {
        $id = Db::name('Admin')->where(array($field=>$accounts))->value('id');
        return !empty($id);
    }
    
    /**
     * 是否开启图片验证码校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-03
     * @desc    description
     * @param   [array]    $params         [输入参数]
     * @param   [array]    $verify_params  [配置参数]
     * @param   [int]      $status         [状态 0未开启, 1已开启]
     * @return  [object]                   [图片验证码类对象]
     */
    private static function IsImaVerify($params, $verify_params, $status = 0)
    {
        if($status == 1)
        {
            if(empty($params['verify']))
            {
                return DataReturn(MyLang('verify_images_empty_tips'), -10);
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
            return DataReturn(MyLang('check_success'), 0, $verify);
        }
        return DataReturn(MyLang('check_success'), 0);
    }
}
?>