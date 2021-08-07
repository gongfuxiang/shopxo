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
use app\service\AdminPowerService;

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
     * 管理员列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function AdminList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取管理员列表
        $data = Db::name('Admin')->where($where)->field($field)->order($order_by)->limit($m, $n)->select()->toArray();
        if(!empty($data))
        {
            // 获取当前用户角色名称
            $roles =  Db::name('Role')->where('id', 'in', array_column($data, 'role_id'))->column('name', 'id');

            // 数据处理
            $common_gender_list = lang('common_gender_list');
            $common_admin_status_list = lang('common_admin_status_list');
            foreach($data as &$v)
            {
                // 所在角色组
                $v['role_name'] = isset($roles[$v['role_id']]) ? $roles[$v['role_id']] : '';

                // 性别
                $v['gender_text'] = isset($common_gender_list[$v['gender']]) ? $common_gender_list[$v['gender']]['name'] : '';

                // 状态
                $v['status_text'] = isset($common_admin_status_list[$v['status']]) ? $common_admin_status_list[$v['status']]['name'] : '';

                // 时间
                $v['login_time'] = empty($v['login_time']) ? '' : date('Y-m-d H:i:s', $v['login_time']);
                $v['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 管理员总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $where [条件]
     */
    public static function AdminTotal($where)
    {
        return (int) Db::name('Admin')->where($where)->count();
    }

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
        return DataReturn('处理成功', 0, $data);
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
                'error_msg'         => '用户信息有误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'mobile',
                'checked_data'      => 'CheckMobile',
                'is_checked'        => 1,
                'error_msg'         => '手机号码格式错误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'email',
                'checked_data'      => 'CheckEmail',
                'is_checked'        => 1,
                'error_msg'         => '电子邮箱格式错误、最多60个字符',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'gender',
                'checked_data'      => [0,1,2],
                'error_msg'         => '性别值范围不正确',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(lang('common_admin_status_list'), 'value'),
                'error_msg'         => '状态值范围不正确',
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'mobile',
                'checked_data'      => 'Admin',
                'checked_key'       => 'id',
                'is_checked'        => 1,
                'error_msg'         => '手机号码已存在[{$var}]',
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'email',
                'checked_data'      => 'Admin',
                'checked_key'       => 'id',
                'is_checked'        => 1,
                'error_msg'         => '电子邮箱已存在[{$var}]',
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
                'error_msg'         => '用户名不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'login_pwd',
                'error_msg'         => '密码不能为空',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'username',
                'checked_data'      => 'CheckUserName',
                'error_msg'         => '用户名格式 5~18 个字符（可以是字母数字下划线）',
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'username',
                'checked_data'      => 'Admin',
                'checked_key'       => 'id',
                'error_msg'         => '管理员已存在[{$var}]',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'login_pwd',
                'checked_data'      => 'CheckLoginPwd',
                'error_msg'         => '密码格式 6~18 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'role_id',
                'error_msg'         => '角色组有误',
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
            return DataReturn('新增成功', 0);
        }
        return DataReturn('新增失败', -100);
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
                'error_msg'         => '密码格式 6~18 个字符',
            ],
        ];
        if($params['id'] != $params['admin']['id'])
        {
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'role_id',
                'error_msg'         => '角色组有误',
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
            return DataReturn('非法操作', -1);
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
            
            return DataReturn('编辑成功', 0);
        }
        return DataReturn('编辑失败或数据未改变', -100);
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
            return DataReturn('管理员id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 是否包含删除超级管理员
        if(in_array(1, $params['ids']))
        {
            return DataReturn('超级管理员不可删除', -1);
        }
           
        // 删除操作
        if(Db::name('Admin')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn('删除成功');
        }
        return DataReturn('删除失败', -100);
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
                'checked_data'      => array_column(lang('common_login_type_list'), 'value'),
                'error_msg'         => '登录类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => '登录账号不能为空',
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
            return DataReturn('暂时关闭登录', -1);
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
            $p = [
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'pwd',
                    'error_msg'         => '密码格式 6~18 个字符之间',
                ],
                [
                    'checked_type'      => 'fun',
                    'key_name'          => 'pwd',
                    'checked_data'      => 'CheckLoginPwd',
                    'error_msg'         => '密码格式 6~18 个字符',
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
                    return DataReturn('验证类型有误', -1);
            }

            // 验证码校验
            // sms, email
            if(isset($obj) && is_object($obj))
            {
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
            }
        }

        // 获取管理员
        $admin = Db::name('Admin')->field('id,username,mobile,email,login_pwd,login_salt,login_total,role_id')->where([$ac['data']=>$params['accounts'], 'status'=>0])->find();
        if(empty($admin))
        {
            return DataReturn('账户异常', -2);
        }

        // 密码校验
        // 帐号密码登录需要校验密码
        if($params['type'] == 'username')
        {
            $pwd = LoginPwdEncryption($params['pwd'], $admin['login_salt']);
            if($pwd != $admin['login_pwd'])
            {
                return DataReturn('密码错误', -3);
            }
        }

        // 种session
        self::LoginSession($admin);

        // 返回数据,更新数据库
        if(self::LoginInfo())
        {
            $data = [
                    'login_total'   =>  $admin['login_total']+1,
                    'login_time'    =>  time(),
                ];
            if($params['type'] == 'username')
            {
                $login_salt = GetNumberCode(6);
                $data['login_salt'] = $login_salt;
                $data['login_pwd'] = LoginPwdEncryption($params['pwd'], $login_salt);
            }
            if(Db::name('Admin')->where(['id'=>$admin['id']])->update($data))
            {
                // 清空权限缓存数据
                MyCache(MyConfig('shopxo.cache_admin_left_menu_key').$admin['id'], null);
                MyCache(MyConfig('shopxo.cache_admin_power_key').$admin['id'], null);

                // 权限菜单初始化
                AdminPowerService::PowerMenuInit();

                return DataReturn('登录成功');
            }
        }

        // 失败
        self::LoginLogout();
        return DataReturn('登录失败，请稍后再试！', -100);
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
        return MySession(self::$admin_login_key);
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
        unset($admin['login_pwd'], $admin['login_salt']);
        return MySession(self::$admin_login_key, $admin);
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
                'error_msg'         => '账号不能为空',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(lang('common_login_type_list'), 'value'),
                'error_msg'         => '登录类型有误',
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
            return DataReturn('暂时关闭登录', -1);
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
                $status = $obj->SendCode($params['accounts'], $code, MyC('admin_sms_login_template'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                        'email'     =>  $params['accounts'],
                        'content'   =>  MyC('admin_email_login_template'),
                        'title'     =>  MyC('home_site_name').' - 管理员登录',
                        'code'      =>  $code,
                    ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn('该类型不支持验证码发送', -2);
        }
        
        // 状态
        if($status)
        {
            // 清除验证码
            if(isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return DataReturn('发送成功'.$code, 0);
        }
        return DataReturn('发送失败'.'['.$obj->error.']', -100);
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
                     return DataReturn('手机号码格式错误', -2);
                }

                // 手机号码是否存在
                if(!self::IsExistAccounts($params['accounts'], 'mobile'))
                {
                     return DataReturn('手机号码不存在', -3);
                }
                $field = 'mobile';
                break;

            // 邮箱
            case 'email' :
                // 电子邮箱格式
                if(!CheckEmail($params['accounts']))
                {
                     return DataReturn('电子邮箱格式错误', -2);
                }

                // 电子邮箱是否存在
                if(!self::IsExistAccounts($params['accounts'], 'email'))
                {
                     return DataReturn('电子邮箱不存在', -3);
                }
                $field = 'email';
                break;

            // 用户名
            case 'username' :
                // 用户名格式
                if(!CheckUserName($params['accounts']))
                {
                     return DataReturn('用户名格式由 字母数字下划线 2~18 个字符', -2);
                }

                // 用户名是否存在
                if(!self::IsExistAccounts($params['accounts'], 'username'))
                {
                     return DataReturn('帐号不存在', -3);
                }
                $field = 'username';
                break;
        }
        return DataReturn('操作成功', 0, $field);
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
                return DataReturn('图片验证码为空', -10);
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
            return DataReturn('验证成功', 0, $verify);
        }
        return DataReturn('验证成功', 0);
    }
}
?>