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
use app\service\RegionService;
use app\service\SafetyService;
use app\service\ResourcesService;
use app\service\SystemBaseService;

/**
 * 用户服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserService
{
    // user登录session key
    public static $user_login_key = 'user_login_info';

    /**
     * 获取用户登录信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-27
     * @desc    description
     */
    public static function LoginUserInfo()
    {
        // 参数
        $params = input();

        // 用户数据处理
        $user = null;
        if(APPLICATION == 'web')
        {
            // web用户session
            $user = MySession(self::$user_login_key);

            // 用户信息为空，指定了token则设置登录信息
            if(empty($user) && !empty($params['token']))
            {
                $user = self::UserTokenData($params['token']);
                if($user !== null && isset($user['id']))
                {
                    self::UserLoginRecord($user['id']);
                }
            }
        } else {
            if(!empty($params['token']))
            {
                $user = self::UserTokenData($params['token']);
            }
        }

        return $user;
    }

    /**
     * 获取用户token用户数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T19:01:59+0800
     * @desc     description
     * @param    [string]                   $token [用户token]
     */
    private static function UserTokenData($token)
    {
        $user = MyCache(MyConfig('shopxo.cache_user_info').$token);
        if($user !== null && isset($user['id']))
        {
            return $user;
        }

        // 数据库校验
        return self::AppUserInfoHandle(null, 'token', $token);
    }

    /**
     * 用户状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-27
     * @desc    description
     * @param   [string]          $field [条件字段]
     * @param   [string]          $value [条件值]
     */
    public static function UserStatusCheck($field, $value)
    {
        // 查询用户状态是否正常
        $user = self::UserInfo($field, $value);
        if(empty($user))
        {
            return DataReturn('用户不存在或已删除', -110);
        }
        if(!in_array($user['status'], [0,1]))
        {
            $common_user_status_list = lang('common_user_status_list');
            if(isset($common_user_status_list[$user['status']]))
            {
                return DataReturn($common_user_status_list[$user['status']]['tips'], -110);
            } else {
                return DataReturn('用户状态有误', -110);
            }
        }
        return DataReturn('正常', 0);
    }

    /**
     * 用户列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取用户列表
        $data = Db::name('User')->where($where)->order($order_by)->field($field)->limit($m, $n)->select()->toArray();
        if(!empty($data))
        {
            $common_gender_list = lang('common_gender_list');
            $common_user_status_list = lang('common_user_status_list');
            foreach($data as &$v)
            {
                // 生日
                if(array_key_exists('birthday', $v))
                {
                    $v['birthday_text'] = empty($v['birthday']) ? '' : date('Y-m-d', $v['birthday']);
                }

                // 头像
                if(array_key_exists('avatar', $v))
                {
                    if(!empty($v['avatar']))
                    {
                        $v['avatar'] = ResourcesService::AttachmentPathViewHandle($v['avatar']);
                    } else {
                        $v['avatar'] = SystemBaseService::AttachmentHost().'/static/index/'.strtolower(MyC('common_default_theme', 'default', true)).'/images/default-user-avatar.jpg';
                    }
                }

                // 时间
                if(array_key_exists('add_time', $v))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(array_key_exists('upd_time', $v))
                {
                    $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                }

                // 性别
                if(array_key_exists('gender', $v))
                {
                    $v['gender_text'] = isset($common_gender_list[$v['gender']]) ? $common_gender_list[$v['gender']]['name'] : '未知';
                }

                // 状态
                if(array_key_exists('status', $v))
                {
                    $v['status_text'] = $common_user_status_list[$v['status']]['name'];
                }
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 用户总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $where [条件]
     */
    public static function UserTotal($where)
    {
        return (int) Db::name('User')->where($where)->count();
    }

    /**
     * 用户信息保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'admin',
                'error_msg'         => '管理员信息有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'username',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => '用户名格式最多 30 个字符之间',
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'username',
                'checked_data'      => 'User',
                'checked_key'       => 'id',
                'is_checked'        => 1,
                'error_msg'         => '用户已存在[{$var}]',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'nickname',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => '用户昵称格式最多 30 个字符之间',
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
                'error_msg'         => '邮箱格式错误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'gender',
                'checked_data'      => array_column(lang('common_gender_list'), 'id'),
                'error_msg'         => '性别值范围不正确',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(lang('common_user_status_list'), 'id'),
                'error_msg'         => '状态值范围不正确',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'address',
                'checked_data'      => '80',
                'is_checked'        => 1,
                'error_msg'         => '地址格式最多 80 个字符之间',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'pwd',
                'checked_data'      => 'CheckLoginPwd',
                'is_checked'        => 1,
                'error_msg'         => '密码格式 6~18 个字符之间',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 更新数据
        $data = [
            'username'              => isset($params['username']) ? $params['username'] :  '',
            'nickname'              => isset($params['nickname']) ? $params['nickname'] :  '',
            'mobile'                => isset($params['mobile']) ? $params['mobile'] :  '',
            'email'                 => isset($params['email']) ? $params['email'] :  '',
            'address'               => isset($params['address']) ? $params['address'] :  '',
            'gender'                => intval($params['gender']),
            'integral'              => intval($params['integral']),
            'status'                => intval($params['status']),
            'alipay_openid'         => isset($params['alipay_openid']) ? $params['alipay_openid'] :  '',
            'baidu_openid'          => isset($params['baidu_openid']) ? $params['baidu_openid'] :  '',
            'toutiao_openid'        => isset($params['toutiao_openid']) ? $params['toutiao_openid'] :  '',
            'qq_openid'             => isset($params['qq_openid']) ? $params['qq_openid'] :  '',
            'qq_unionid'            => isset($params['qq_unionid']) ? $params['qq_unionid'] :  '',
            'weixin_openid'         => isset($params['weixin_openid']) ? $params['weixin_openid'] :  '',
            'weixin_unionid'        => isset($params['weixin_unionid']) ? $params['weixin_unionid'] :  '',
            'weixin_web_openid'     => isset($params['weixin_web_openid']) ? $params['weixin_web_openid'] :  '',
            'birthday'              => empty($params['birthday']) ? 0 : strtotime($params['birthday']),
            'referrer'              => empty($params['referrer']) ? 0 : intval($params['referrer']),
            'upd_time'              => time(),
        ];

        // 用户保存处理钩子
        $hook_name = 'plugins_service_user_save_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'user_id'       => isset($params['id']) ? intval($params['id']) : 0,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 密码
        if(!empty($params['pwd']))
        {
            $data['salt'] = GetNumberCode(6);
            $data['pwd'] = LoginPwdEncryption(trim($params['pwd']), $data['salt']);
        }

        // 更新/添加
        if(!empty($params['id']))
        {
            // 获取用户信息
            $user = Db::name('User')->field('id,integral')->find($params['id']);
            if(empty($user))
            {
                return DataReturn('用户信息不存在', -10);
            }

            $data['upd_time'] = time();
            if(Db::name('User')->where(['id'=>$params['id']])->update($data))
            {
                $user_id = $params['id'];
            }
        } else {
            $data['add_time'] = time();
            $user_id = Db::name('User')->insertGetId($data);
        }
		
		// 添加用户后处理钩子
        $hook_name = 'plugins_service_user_save_success_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'user_id'       => &$user_id,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 状态
        if(isset($user_id))
        {
            if(($data['integral'] > 0 && empty($user)) || (isset($user['integral']) && $user['integral'] != $data['integral']))
            {
                $integral_type = 1;
                $old_integral = 0;
                $opt_integral = 0;
                if(!empty($params['id']))
                {
                    $old_integral = $user['integral'];
                    $integral_type = ($user['integral'] > $data['integral']) ? 0 : 1;
                    $opt_integral = ($integral_type == 1) ? $data['integral']-$user['integral'] : $user['integral']-$data['integral'];
                }
                IntegralService::UserIntegralLogAdd($user_id, $old_integral, $opt_integral, '管理员操作', $integral_type, $params['admin']['id']);
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 用户删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn('商品id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }
           
        // 删除操作
        if(Db::name('User')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn('删除成功');
        }
        return DataReturn('删除失败', -100);
    }

    /**
     * [UserLoginRecord 用户登录记录]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T11:37:43+0800
     * @param    [int]     $user_id [用户id]
     * @return   [boolean]          [记录成功true, 失败false]
     */
    public static function UserLoginRecord($user_id = 0)
    {
        if(!empty($user_id))
        {
            $user = Db::name('User')->find($user_id);
            if(!empty($user))
            {
                // 用户数据处理
                $user = self::UserHandle($user);

                // 用户登录成功信息纪录钩子
                $hook_name = 'plugins_service_user_login_success_record';
                MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'user'          => &$user,
                    'user_id'       => $user_id
                ]);

                // web端设置session
                if(APPLICATION == 'web')
                {
                    // 存储session
                    MySession(self::$user_login_key, $user);
                    return (MySession(self::$user_login_key) !== null);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * 用户数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-23
     * @desc    description
     * @param   [ array]          $user [用户数据]
     */
    public static function UserHandle($user)
    {
        // 基础数据处理
        if(isset($user['add_time']))
        {
            $user['add_time_text']  =   date('Y-m-d H:i:s', $user['add_time']);
        }
        if(isset($user['upd_time']))
        {
            $user['upd_time_text']  =   date('Y-m-d H:i:s', $user['upd_time']);
        }
        if(isset($user['gender']))
        {
            $user['gender_text']    =   lang('common_gender_list')[$user['gender']]['name'];
        }
        if(isset($user['birthday']))
        {
            $user['birthday_text']  =   empty($user['birthday']) ? '' : date('Y-m-d', $user['birthday']);
        }

        // 邮箱/手机
        if(isset($user['mobile']))
        {
            $user['mobile_security']=   empty($user['mobile']) ? '' : mb_substr($user['mobile'], 0, 3, 'utf-8').'***'.mb_substr($user['mobile'], -3, null, 'utf-8');
        }
        if(isset($user['email']))
        {
            $user['email_security'] =   empty($user['email']) ? '' : mb_substr($user['email'], 0, 3, 'utf-8').'***'.mb_substr($user['email'], -3, null, 'utf-8');
        }

        // 显示名称,根据规则优先展示
        $user['user_name_view'] = isset($user['username']) ? $user['username'] : '';
        if(empty($user['user_name_view']) && isset($user['nickname']))
        {
            $user['user_name_view'] = $user['nickname'];
        }
        if(empty($user['user_name_view']) && isset($user['mobile_security']))
        {
            $user['user_name_view'] = $user['mobile_security'];
        }
        if(empty($user['user_name_view']) && isset($user['email_security']))
        {
            $user['user_name_view'] = $user['email_security'];
        }

        // 头像
        if(!empty($user['avatar']))
        {
            $user['avatar'] = ResourcesService::AttachmentPathViewHandle($user['avatar']);
        } else {
            $user['avatar'] = SystemBaseService::AttachmentHost().'/static/index/'.strtolower(MyFileConfig('common_default_theme', '', 'default', true)).'/images/default-user-avatar.jpg';
        }

        // 移除特殊数据
        unset($user['pwd'], $user['salt']);

        return $user;
    }

    /**
     * 用户头像更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-16
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAvatarUpload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'img_width',
                'error_msg'         => '图片宽度不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'img_height',
                'error_msg'         => '图片高度不能为空',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'img_x',
                'error_msg'         => '图片裁剪x坐标有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'img_y',
                'error_msg'         => '图片裁剪y坐标有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'img_field',
                'error_msg'         => '图片name字段值不能为空',
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

        // 开始处理图片存储
        // 定义图片目录
        $root_path = ROOT.'public'.DS;
        $img_path = 'static'.DS.'upload'.DS.'images'.DS.'user_avatar'.DS;
        $date = DS.date('Y').DS.date('m').DS.date('d').DS;

        // 图像类库
        $images_obj = \base\Images::Instance(['is_new_name'=>false]);

        // 文件上传校验
        $error = FileUploadError($params['img_field']);
        if($error !== true)
        {
            return DataReturn($error, -2);
        }

        $original = $images_obj->GetCompressCut($_FILES[$params['img_field']], $root_path.$img_path.'original'.$date, 800, 800, $params['img_x'], $params['img_y'], $params['img_width'], $params['img_height']);
        if(!empty($original))
        {
            $compr = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'compr'.$date, 200, 200);
            $small = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'small'.$date, 50, 50);
        }
        if(empty($compr) || empty($small))
        {
            return DataReturn('图片有误，请换一张', -3);
        }

        // 更新用户头像
        $data = [
            'avatar'    => DS.$img_path.'compr'.$date.$compr,
            'upd_time'  => time(),
        ];
        if(Db::name('User')->where(['id'=>$params['user']['id']])->update($data))
        {
            // 设置session
            self::UserLoginRecord($params['user']['id']);
            return DataReturn('上传成功', 0);
        }
        return DataReturn('上传失败', -100);
    }

    /**
     * 用户登录
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Login($params = [])
    {
        // 用户登录前校验钩子
        $hook_name = 'plugins_service_user_login_begin_check';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

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
        if(!in_array($params['type'], MyC('home_user_login_type', [], true)))
        {
            return DataReturn('暂时关闭登录', -1);
        }

        // 账户校验
        $ac = self::UserLoginAccountsCheck($params);
        if($ac['code'] != 0)
        {
            return $ac;
        }

        // 验证参数
        $verify_params = [
            'key_prefix'    => 'user_login_'.md5($params['accounts']),
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
            $verify_params['key_prefix'] = 'user_login';
            $verify = self::IsImaVerify($params, $verify_params, MyC('home_user_login_img_verify_state'));
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

        // 获取用户账户信息
        $where = [$ac['data'] => $params['accounts'], 'is_delete_time'=>0];
        $user = Db::name('User')->field('id,pwd,salt,status')->where($where)->find();
        if(empty($user))
        {
            return DataReturn('帐号不存在', -3);
        }

        // 密码校验
        // 帐号密码登录需要校验密码
        if($params['type'] == 'username')
        {
            $pwd = LoginPwdEncryption($params['pwd'], $user['salt']);
            if($pwd != $user['pwd'])
            {
                return DataReturn('密码错误', -4);
            }
        }

        // 用户状态
        if(in_array($user['status'], [2,3]))
        {
            return DataReturn(lang('common_user_status_list')[$user['status']]['tips'], -10);
        }

        // 用户登录前钩子
        $hook_name = 'plugins_service_user_login_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'user_id'       => $user['id']
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 返回数据,更新数据库
        $data = [
                'upd_time'  =>  time(),
            ];
        if($params['type'] == 'username')
        {
            $salt = GetNumberCode(6);
            $data['salt'] = $salt;
            $data['pwd'] = LoginPwdEncryption($params['pwd'], $salt);
        }
        if(Db::name('User')->where(['id'=>$user['id']])->update($data) !== false)
        {
            // 清除图片验证码
            if(isset($verify) && isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return self::UserLoginHandle($user['id'], $params);
        }
        return DataReturn('登录失效，请重新登录', -100);
    }

    /**
     * 登录处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param   [int]          $user_id [用户id]
     * @param   [array]        $params  [输入参数]
     */
    public static function UserLoginHandle($user_id, $params = [])
    {
        // 登录记录
        if(self::UserLoginRecord($user_id))
        {
            // 返回前端html代码
            $body_html = [];

            // 用户登录后钩子
            $user = Db::name('User')->field('id,username,nickname,mobile,email,gender,avatar,province,city,birthday')->where(['id'=>$user_id])->find();
            $hook_name = 'plugins_service_user_login_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'user_id'       => $user_id,
                'user'          => $user,
                'body_html'     => &$body_html,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 成功返回
            if(APPLICATION == 'app')
            {
                $result = self::AppUserInfoHandle($user_id);
            } else {
                $result = [
                    'body_html'    => is_array($body_html) ? implode(' ', $body_html) : $body_html,
                ];
            }
            return DataReturn('登录成功', 0, $result);
        }
        return DataReturn('登录失效，请重新登录', -100);
    }

    /**
     * 用户注册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Reg($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => '账号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pwd',
                'error_msg'         => '密码不能为空',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(lang('common_user_reg_type_list'), 'value'),
                'error_msg'         => '注册类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'is_checked'        => 2,
                'error_msg'         => '验证码不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户注册前校验钩子
        $hook_name = 'plugins_service_user_register_begin_check';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('home_user_reg_type', [], true)))
        {
            return DataReturn('暂时关闭用户注册', -1);
        }

        // 账户校验
        $ret = self::UserRegAccountsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 是否需要审核
        $common_register_is_enable_audit = MyC('common_register_is_enable_audit', 0);

        // 用户数据
        $salt = GetNumberCode(6);
        $data = [
            'upd_time'      => time(),
            'salt'          => $salt,
            'pwd'           => LoginPwdEncryption($params['pwd'], $salt),
            'status'        => ($common_register_is_enable_audit == 1) ? 3 : 0,
        ];

        // 验证码校验
        $verify_params = [
            'key_prefix'    => 'user_reg_'.md5($params['accounts']),
            'expire_time'   => MyC('common_verify_expire_time'),
        ];

        // 账户类型
        $obj = null;
        switch($params['type'])
        {
            // 短信
            case 'sms' :
                $data['mobile'] = $params['accounts'];
                $obj = new \base\Sms($verify_params);
                break;

            // 邮箱
            case 'email' :
                $data['email'] = $params['accounts'];
                $obj = new \base\Email($verify_params);
                break;

            // 默认 账号
             default :
                $data['username'] = $params['accounts'];
                // 是否开启图片验证码
                // user_reg 由前端图片验证码传递的 type 一致
                $verify_params['key_prefix'] = 'user_reg';
                $verify = self::IsImaVerify($params, $verify_params, MyC('home_user_register_img_verify_state'));
                if($verify['code'] != 0)
                {
                    return $verify;
                }
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

        // 数据添加
        $user_ret = self::UserInsert($data, $params);
        if($user_ret['code'] == 0)
        {
            // 清除验证码
            if(isset($obj) && is_object($obj))
            {
                $obj->Remove();
            }

            // 是否需要审核
            if($common_register_is_enable_audit == 1)
            {
                return DataReturn('用户等待审核中', -110);
            }

            // 用户登录session纪录
            if(self::UserLoginRecord($user_ret['data']['user_id']))
            {
                // 成功返回
                if(APPLICATION == 'app')
                {
                    $result = self::AppUserInfoHandle($user_ret['data']['user_id']);
                } else {
                    $result = $user_ret['data'];
                }
                return DataReturn('注册成功', 0, $result);
            }
            return DataReturn('注册成功，请到登录页面登录帐号');
        } else {
            return $user_ret;
        }
        return DataReturn('注册失败', -100);
    }

    /**
     * 用户注册账户校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    private static function UserRegAccountsCheck($params = [])
    {
        switch($params['type'])
        {
            // 手机
            case 'sms' :
                // 手机号码格式
                if(!CheckMobile($params['accounts']))
                {
                     return DataReturn('手机号码格式错误', -2);
                }

                // 手机号码是否已存在
                if(self::IsExistAccounts($params['accounts'], 'mobile'))
                {
                     return DataReturn('手机号码已存在', -3);
                }
                break;

            // 邮箱
            case 'email' :
                // 电子邮箱格式
                if(!CheckEmail($params['accounts']))
                {
                     return DataReturn('电子邮箱格式错误', -2);
                }

                // 电子邮箱是否已存在
                if(self::IsExistAccounts($params['accounts'], 'email'))
                {
                     return DataReturn('电子邮箱已存在', -3);
                }
                break;

            // 用户名
            case 'username' :
                // 用户名格式
                if(!CheckUserName($params['accounts']))
                {
                     return DataReturn('用户名格式由 字母数字下划线 2~18 个字符', -2);
                }
                break;
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 账户是否存在
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-08T10:27:14+0800
     * @param    [string] $accounts     [账户名称]
     * @param    [string] $field        [字段名称]
     * @return   [boolean]              [存在true, 不存在false]
     */
    private static function IsExistAccounts($accounts, $field = 'mobile')
    {
        $id = Db::name('User')->where(array($field=>$accounts))->value('id');
        return !empty($id);
    }

    /**
     * 是否开启图片验证码校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-22T15:48:31+0800
     * @param    [array]    $params         [输入参数]
     * @param    [array]    $verify_params  [配置参数]
     * @param    [int]      $status         [状态 0未开启, 1已开启]
     * @return   [object]                   [图片验证码类对象]
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
            return DataReturn('操作成功', 0, $verify);
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 用户登录账户校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    private static function UserLoginAccountsCheck($params = [])
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

                // 手机号码是否不存在
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

                // 电子邮箱是否不存在
                if(!self::IsExistAccounts($params['accounts'], 'email'))
                {
                     return DataReturn('电子邮箱不存在', -3);
                }
                $field = 'email';
                break;

            // 用户名
            case 'username' :
                $field = 'username|mobile|email';

                // 帐号是否不存在
                if(!self::IsExistAccounts($params['accounts'], 'username|mobile|email'))
                {
                     return DataReturn('登录帐号不存在', -3);
                }
                break;
        }
        return DataReturn('操作成功', 0, $field);
    }

    /**
     * 用户登录-验证码发送
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
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
        if(!in_array($params['type'], MyC('home_user_login_type', [], true)))
        {
            return DataReturn('暂时关闭登录', -1);
        }

        // 验证码基础参数
        $verify_params = [
            'key_prefix'    => 'user_login',
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
        $ac = self::UserLoginAccountsCheck($params);
        if($ac['code'] != 0)
        {
            return $ac;
        }

        // 验证码基础参数 key
        $verify_params['key_prefix'] = 'user_login_'.md5($params['accounts']);

        // 发送验证码
        $code = GetNumberCode(4);
        switch($params['type'])
        {
            // 短信
            case 'sms' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params['accounts'], $code, MyC('home_sms_login_template'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = array(
                        'email'     =>  $params['accounts'],
                        'content'   =>  MyC('home_email_login_template'),
                        'title'     =>  MyC('home_site_name').' - 用户登录',
                        'code'      =>  $code,
                    );
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

            return DataReturn('发送成功', 0);
        } else {
            return DataReturn('发送失败'.'['.$obj->error.']', -100);
        }
    }

    /**
     * 用户注册-验证码发送
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    public static function RegVerifySend($params = [])
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
                'checked_data'      => array_column(lang('common_user_reg_type_list'), 'value'),
                'error_msg'         => '注册类型有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('home_user_reg_type', [], true)))
        {
            return DataReturn('暂时关闭用户注册');
        }

        // 验证码基础参数
        $verify_params = [
            'key_prefix'    => 'user_reg',
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
        $ret = self::UserRegAccountsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码基础参数 key
        $verify_params['key_prefix'] = 'user_reg_'.md5($params['accounts']);

        // 发送验证码
        $code = GetNumberCode(4);
        switch($params['type'])
        {
            // 短信
            case 'sms' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params['accounts'], $code, MyC('home_sms_user_reg'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = array(
                        'email'     =>  $params['accounts'],
                        'content'   =>  MyC('home_email_user_reg'),
                        'title'     =>  MyC('home_site_name').' - 用户注册',
                        'code'      =>  $code,
                    );
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

            return DataReturn('发送成功', 0);
        } else {
            return DataReturn('发送失败'.'['.$obj->error.']', -100);
        }
    }

    /**
     * 密码找回验证码发送
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:35:03+0800
     * @param   [array]          $params [输入参数]
     */
    public static function ForgetPwdVerifySend($params = [])
    {
        // 参数
        if(empty($params['accounts']))
        {
            return DataReturn('参数错误', -10);
        }

        // 验证码基础参数
        $verify_params = [
            'key_prefix'    => 'user_forget',
            'expire_time'   => MyC('common_verify_expire_time'),
            'interval_time' => MyC('common_verify_interval_time'),
        ];

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $verify_params, MyC('common_img_verify_state'));
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 账户是否存在，并返回账户格式类型
        $ret = self::UserForgetAccountsCheck($params['accounts']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码基础参数 key
        $verify_params['key_prefix'] = 'user_forget_'.md5($params['accounts']);

        // 验证码
        $code = GetNumberCode(4);

        // 账户字段类型
        switch($ret['data'])
        {
            // 手机
            case 'mobile' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params['accounts'], $code, MyC('home_sms_user_forget_pwd'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('home_email_user_forget_pwd'),
                    'title'     =>  MyC('home_site_name').' - '.'密码找回',
                    'code'      =>  $code,
                ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn('手机/邮箱格式有误', -1);
        }

        // 状态
        if($status)
        {
            // 清除图片验证码
            if(isset($verify) && isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return DataReturn('发送成功', 0);
        } else {
            return DataReturn('发送失败'.'['.$obj->error.']', -100);
        }
    }

    /**
     * [UserForgetAccountsCheck 帐号校验]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:59:53+0800
     * @param    [string]     $accounts [账户名称]
     * @return   [string]               [账户字段 mobile, email]
     */
    private static function UserForgetAccountsCheck($accounts)
    {
        if(CheckMobile($accounts))
        {
            if(!self::IsExistAccounts($accounts, 'mobile'))
            {
                return DataReturn('手机号码不存在', -3);
            }
            return DataReturn('操作成功', 0, 'mobile');
        } else if(CheckEmail($accounts))
        {
            if(!self::IsExistAccounts($accounts, 'email'))
            {
                return DataReturn('电子邮箱不存在', -3);
            }
            return DataReturn('操作成功', 0, 'email');
        }
        return DataReturn('手机/邮箱格式有误', -4);
    }

    /**
     * 密码找回
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:35:03+0800
     * @param   [array]          $params [输入参数]
     */
    public static function ForgetPwd($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => '账号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pwd',
                'error_msg'         => '密码不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => '验证码不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 账户是否存在
        $ret = self::UserForgetAccountsCheck($params['accounts']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码校验
        $verify_params = [
            'key_prefix'    => 'user_forget_'.md5($params['accounts']),
            'expire_time'   => MyC('common_verify_expire_time'),
            'interval_time' => MyC('common_verify_interval_time'),
        ];
        switch($ret['data'])
        {
            // 手机
            case 'mobile' :
                $obj = new \base\Sms($verify_params);
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                break;

            // 默认
            default :
                return DataReturn('手机/邮箱格式有误', -1);
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

        // 获取用户信息
        $user = Db::name('User')->where([$ret['data']=>$params['accounts']])->find();
        if(empty($user))
        {
            return DataReturn('用户信息不存在', -12);
        }

        // 密码修改
        $ret = SafetyService::UserLoginPwdUpdate($params['accounts'], $user['id'], $params['pwd']);
        if($ret['code'] == 0)
        {
            // 清除验证码
            if(isset($obj) && is_object($obj))
            {
                $obj->Remove();
            }
            return DataReturn('操作成功', 0);
        }
        return $ret;
    }

    /**
     * 用户资料保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-04
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PersonalSave($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'length',
                'checked_data'      => '2,16',
                'key_name'          => 'nickname',
                'error_msg'         => '昵称 2~16 个字符之间',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'birthday',
                'error_msg'         => '请填写生日',
            ],
            [
                'checked_type'      => 'in',
                'checked_data'      => [0,1,2],
                'key_name'          => 'gender',
                'error_msg'         => '性别选择有误',
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

        // 更新数据库
        $data = [
            'birthday'      => empty($params['birthday']) ? '' : strtotime($params['birthday']),
            'nickname'      => $params['nickname'],
            'gender'        => intval($params['gender']),
            'upd_time'      => time(),
        ];
        if(Db::name('User')->where(array('id'=>$params['user']['id']))->update($data))
        {
            // 更新用户session数据
            self::UserLoginRecord($params['user']['id']);

            return DataReturn('编辑成功', 0);
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }

    /**
     * 用户授权数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]          $params    [用户数据]
     * @param   [string]         $field     [平台字段名称]
     */
    public static function AuthUserProgram($params, $field)
    {
        // 用户信息
        $data = [
            $field              => $params['openid'],
            'nickname'          => empty($params['nickname']) ? '' : $params['nickname'],
            'avatar'            => empty($params['avatar']) ? '' : $params['avatar'],
            'gender'            => empty($params['gender']) ? 0 : intval($params['gender']),
            'province'          => empty($params['province']) ? '' : $params['province'],
            'city'              => empty($params['city']) ? '' : $params['city'],
            'mobile'            => empty($params['mobile']) ? '' : $params['mobile'],
            'referrer'          => isset($params['referrer']) ? $params['referrer'] : 0,
        ];

        // 是否一键登录
        $is_onekey_mobile_bind = isset($params['is_onekey_mobile_bind']) && $params['is_onekey_mobile_bind'] == 1 ? 1 : 0;

        // 用户信息处理
        $user = self::AppUserInfoHandle(null, $field, $params['openid']);
        if(!empty($user))
        {
            // 用户状态
            if($user['status'] != 0)
            {
                return DataReturn('用户待审核', -301);
            }

            // 如果是一键登录、如当前用户不存在手机号码则绑定
            if(empty($user['mobile']) && !empty($data['mobile']) && $is_onekey_mobile_bind == 1)
            {
                // 手机号码不存在则绑定到当前账号下
                $temp = self::AppUserInfoHandle(null, 'mobile', $data['mobile']);
                if(empty($temp))
                {
                    $upd_data = [
                        'mobile'    => $data['mobile'],
                        'upd_time'  => time(),
                    ];
                    if(Db::name('User')->where(['id'=>$user['id']])->update($upd_data))
                    {
                        return DataReturn('绑定成功', 0, self::AppUserInfoHandle($user['id']));
                    }
                } else {
                    if($user['id'] != $temp['id'])
                    {
                        return DataReturn('手机已绑定其他帐号', -1);
                    }
                }
            }

            return DataReturn('授权成功', 0, $user);
        } else {
            // 是否需要添加用户
            $is_insert_user = false;

            // 用户unionid
            $unionid = self::UserUnionidHandle($params);
            if(!empty($unionid['field']) && !empty($unionid['value']))
            {
                // unionid字段是否存在用户
                $user_unionid = self::AppUserInfoHandle(null, $unionid['field'], $unionid['value']);
                if(!empty($user_unionid))
                {
                    // 用户状态
                    if($user_unionid['status'] != 0)
                    {
                        return DataReturn('用户待审核', -301);
                    }

                    // openid绑定
                    $upd_data = [
                        $field      => $params['openid'],
                        'upd_time'  => time(),
                    ];

                    // 如果是一键登录、如当前用户不存在手机号码则绑定
                    if(empty($user_unionid['mobile']) && !empty($data['mobile']) && $is_onekey_mobile_bind == 1)
                    {
                        // 手机号码不存在则绑定到当前账号下
                        $temp = self::AppUserInfoHandle(null, 'mobile', $data['mobile']);
                        if(empty($temp))
                        {
                            $upd_data['mobile'] = $data['mobile'];
                        } else {
                            if($user_unionid['id'] != $temp['id'])
                            {
                                return DataReturn('手机已绑定其他帐号', -1);
                            }
                        }
                    }
                    if(Db::name('User')->where(['id'=>$user_unionid['id']])->update($upd_data))
                    {
                        return DataReturn('绑定成功', 0, self::AppUserInfoHandle($user_unionid['id']));
                    }
                }

                // 如果用户不存在数据库中，则unionid放入用户data中
                $data[$unionid['field']] = $unionid['value'];
            }

            // 不强制绑定手机则写入用户信息
            if(intval(MyC('common_user_is_mandatory_bind_mobile')) != 1)
            {
                $is_insert_user = true;
            } else {
                // 强制绑定手机号码、是否一键获取操作绑定
                if($is_onekey_mobile_bind == 1 && !empty($data['mobile']))
                {
                    // 如果手机号码存在则直接绑定openid
                    // 不存在添加，存在更新openid
                    $user = self::AppUserInfoHandle(null, 'mobile', $data['mobile']);
                    if(!empty($user))
                    {
                        $upd_data = [
                            $field      => $params['openid'],
                            'upd_time'  => time(),
                        ];
                        if(!empty($unionid['field']) && !empty($unionid['value']))
                        {
                            $upd_data[$unionid['field']] = $unionid['value'];
                        }
                        if(Db::name('User')->where(['id'=>$user['id']])->update($upd_data))
                        {
                            return DataReturn('绑定成功', 0, self::AppUserInfoHandle($user['id']));
                        }
                    } else {
                        $is_insert_user = true;
                    }
                }
            }

            // 添加用户
            if($is_insert_user)
            {
                // 是否需要审核
                $common_register_is_enable_audit = MyC('common_register_is_enable_audit', 0);
                $data['status'] = ($common_register_is_enable_audit == 1) ? 3 : 0;

                // 添加用户
                $ret = self::UserInsert($data, $params);
                if($ret['code'] == 0)
                {
                    // 是否需要审核
                    if($common_register_is_enable_audit == 1)
                    {
                        return DataReturn('用户等待审核中', -110);
                    }
                    return DataReturn('授权成功', 0, self::AppUserInfoHandle($ret['data']['user_id']));
                }
                return $ret;
            }
        }
        return DataReturn('授权成功', 0, self::AppUserInfoHandle(null, null, null, $data));
    }

    /**
     * 用户openid绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-21
     * @desc    description
     * @param   [int]             $user_id [用户id]
     * @param   [string]          $openid  [openid]
     * @param   [string]          $field   [openid 字段]
     */
    public static function UserOpenidBind($user_id, $openid, $field)
    {
        $data = [
            $field      => $openid,
            'upd_time'  => time(),
        ];
        return Db::name('User')->where(['id'=>$user_id])->update($data);
    }

    /**
     * 用户unionid处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-02-11
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserUnionidHandle($params = [])
    {
        // 用户unionid列表
        // 微信用户unionid
        // QQ用户unionid
        $field = null;
        $value = null;
        $unionid_all = ['weixin_unionid', 'qq_unionid'];
        foreach($unionid_all as $unionid)
        {
            if(!empty($params[$unionid]))
            {
                $field = $unionid;
                $value = $params[$unionid];
                break;
            }
        }
        return ['field'=>$field, 'value'=>$value];
    }

    /**
     * app用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [int]             $user_id          [指定用户id]
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [array]           $user             [用户信息]
     */
    public static function AppUserInfoHandle($user_id = null, $where_field = null, $where_value = null, $user = [])
    {
        // 获取用户信息
        if(!empty($user_id))
        {
            $user = self::UserInfo('id', $user_id);
        } elseif(!empty($where_field) && !empty($where_value) && empty($user))
        {
            $user = self::UserInfo($where_field, $where_value);
        }

        if(!empty($user))
        {
            // 用户信息处理
            $user = self::UserHandle($user);

            // 是否强制绑定手机号码
            $user['is_mandatory_bind_mobile'] = intval(MyC('common_user_is_mandatory_bind_mobile'));

            // 基础处理
            if(isset($user['id']))
            {
                // 非token数据库校验，则重新生成token更新到数据库
                if($where_field != 'token')
                {
                    $user = self::UserTokenUpdate($user['id'], $user);
                }
            }

            // 用户信息钩子
            $hook_name = 'plugins_service_user_app_info_handle';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'user_id'       => $user_id,
                'where_field'   => $where_field,
                'where_value'   => $where_value,
                'user'          => &$user,
            ]);
        }

        return $user;
    }

    /**
     * 用户 token更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-01
     * @desc    description
     * @param   [int]          $user_id [用户id]
     * @param   [array]        $user    [指定用户信息]
     */
    public static function UserTokenUpdate($user_id, $user = [])
    {
        // 未指定用户则读取用户信息、并处理数据
        if(empty($user))
        {
            $user = self::UserHandle(self::UserInfo('id', $user_id));
        }
        if(!empty($user))
        {
            // token生成并存储缓存
            $user['token'] = self::CreatedUserToken($user_id);
            if(Db::name('User')->where(['id'=>$user_id])->update(['token'=>$user['token'], 'upd_time'=>time()]))
            {
                MyCache(MyConfig('shopxo.cache_user_info').$user['token'], $user);
            }

            // web端用户登录纪录处理
            self::UserLoginRecord($user_id);
        }

        // 返回用户信息
        return $user;
    }

    /**
     * 用户token生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-26
     * @desc    description
     * @param   [int]          $user_id [用户id]
     */
    public static function CreatedUserToken($user_id)
    {
        return md5(md5($user_id.time()).rand(100, 1000000));
    }

    /**
     * 根据字段获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-25
     * @desc    description
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [string]          $field            [指定字段]
     */
    public static function UserInfo($where_field, $where_value, $field = '*')
    {
        if(empty($where_field) || empty($where_value))
        {
            return '';
        }
        
        return Db::name('User')->where([$where_field=>$where_value, 'is_delete_time'=>0])->field($field)->find();
    }

    /**
     * 用户添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-03
     * @desc    description
     * @param   [array]          $data   [用户添加数据]
     * @param   [array]          $params [输入参数]
     */
    public static function UserInsert($data, $params = [])
    {
        // 账号是否存在，以用户名 手机 邮箱 作为唯一
        if(!empty($data['username']))
        {
            $temp = Db::name('User')->where(['username'=>$data['username'], 'is_delete_time'=>0])->find();
        } else if(!empty($data['mobile']))
        {
            $temp = Db::name('User')->where(['mobile'=>$data['mobile'], 'is_delete_time'=>0])->find();
        } else if(!empty($data['email']))
        {
            $temp = Db::name('User')->where(['email'=>$data['email'], 'is_delete_time'=>0])->find();
        }
        if(!empty($temp))
        {
            return DataReturn('账号已存在', -10);
        }

        // 用户unionid
        $unionid = self::UserUnionidHandle($params);
        if(!empty($unionid['field']) && !empty($unionid['value']))
        {
            // unionid放入用户data中
            $data[$unionid['field']] = $unionid['value'];
        }

        // 推荐人id
        $data['referrer'] = self::UserReferrerDecrypt($params);

        // 添加用户
        $data['add_time'] = time();
        $user_id = Db::name('User')->insertGetId($data);
        if($user_id > 0)
        {
            // 清除推荐id
            if(isset($data['referrer']))
            {
                MySession('share_referrer_id', null);
            }

            // 返回前端html代码
            $body_html = [];

            // 注册成功后钩子
            $user = Db::name('User')->field('id,username,nickname,mobile,email,gender,avatar,province,city,birthday')->where(['id'=>$user_id])->find();
            $hook_name = 'plugins_service_user_register_end';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'user_id'       => $user_id,
                'user'          => $user,
                'body_html'     => &$body_html,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 登录返回
            $result = [
                'body_html'     => is_array($body_html) ? implode(' ', $body_html) : $body_html,
                'user_id'       => $user_id,
            ];

            return DataReturn('添加成功', 0, $result);
        }
        return DataReturn('添加失败', -100);
    }

    /**
     * app用户手机绑定
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppMobileBind($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'mobile',
                'error_msg'         => '手机号码不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => '验证码不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户手机绑定前校验钩子
        $hook_name = 'plugins_service_user_app_mobile_bind_begin_check';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 手机号码格式
        if(!CheckMobile($params['mobile']))
        {
             return DataReturn('手机号码格式错误', -2);
        }

        // 验证码校验
        $verify_params = [
            'key_prefix' => 'user_bind_'.md5($params['mobile']),
            'expire_time' => MyC('common_verify_expire_time')
        ];
        $obj = new \base\Sms($verify_params);

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

        // 用户更新数据
        $data = [
            'mobile'    => $params['mobile'],
        ];

        // 是否小程序请求
        $is_appmini = array_key_exists(APPLICATION_CLIENT_TYPE, lang('common_appmini_type'));

        // 手机号码获取用户信息
        $mobile_user = Db::name('User')->where([
            ['mobile', '=', $data['mobile']],
            ['is_delete_time', '=', 0],
        ])->find();

        // 额外信息
        if(empty($mobile_user))
        {
            if(empty($mobile_user['nickname']) && !empty($params['nickname']))
            {
                $data['nickname'] = $params['nickname'];
            }
            if(empty($mobile_user['avatar']) && !empty($params['avatar']))
            {
                $data['avatar'] = $params['avatar'];
            }
            if(empty($mobile_user['province']) && !empty($params['province']))
            {
                $data['province'] = $params['province'];
            }
            if(empty($mobile_user['city']) && !empty($params['city']))
            {
                $data['city'] = $params['city'];
            }
            if(empty($mobile_user) && isset($params['gender']))
            {
                $data['gender'] = intval($params['gender']);
            }
        }

        // 小程序请求处理
        if($is_appmini)
        {
            // openid必须存在
            $accounts_field = APPLICATION_CLIENT_TYPE.'_openid';
            if(empty($params[$accounts_field]))
            {
                return DataReturn('用户openid不能为空', -20);
            }

            // openid数据
            $data[$accounts_field] = $params[$accounts_field];

            // 小程序请求获取用户信息
            $open_user = Db::name('User')->where([
                [$accounts_field, '=', $params[$accounts_field]],
                ['is_delete_time', '=', 0],
            ])->find();

            // 如果手机号码存在，并且openid也已存在，则更新掉之前的openid
            if(!empty($mobile_user))
            {
                if(!empty($open_user))
                {
                    Db::name('User')->where(['id'=>$open_user['id']])->update([$accounts_field=>'', 'upd_time'=>time()]);
                }
            } else {
                $mobile_user = $open_user;
            }
        } else {
            // 获取当前登录用户
            // 如果手机号码已经存在帐号、当前用户已登录
            $user = self::LoginUserInfo();
            if(!empty($user))
            {
                // 手机帐号信息是否存在
                if(!empty($mobile_user))
                {
                    // id不一致则提示错误
                    if($user['id'] != $mobile_user['id'])
                    {
                        return DataReturn('手机已绑定、请换手机号重试', -50);
                    }
                    
                    // 是否与当前帐号的手机号码一致
                    if(!empty($user['mobile']) && $user['mobile'] == $mobile_user['mobile'])
                    {
                        return DataReturn('请使用新的手机号', -51);
                    }
                }

                // 当前用户赋值手机帐号信息
                $mobile_user = $user;
            }
        }

        // 不存在添加/则更新
        if(empty($mobile_user))
        {
            // 如果用户不存在则新增用户状态字段
            // 是否需要审核
            $common_register_is_enable_audit = MyC('common_register_is_enable_audit', 0);
            $data['status'] = ($common_register_is_enable_audit == 1) ? 3 : 0;

            // 新增用户
            $user_ret = self::UserInsert($data, $params);
            if($user_ret['code'] == 0)
            {
                $user_id = $user_ret['data']['user_id'];
            } else {
                return $user_ret;
            }
        } else {
            // 小程序请求处理
            if($is_appmini)
            {
                // 用户unionid
                $unionid = self::UserUnionidHandle($params);
                if(!empty($unionid['field']) && !empty($unionid['value']))
                {
                    if(empty($mobile_user[$unionid['field']]))
                    {
                        // unionid放入用户data中
                        $data[$unionid['field']] = $unionid['value'];
                    }
                }
            }

            // 帐号信息更新
            $data['upd_time'] = time();
            if(Db::name('User')->where(['id'=>$mobile_user['id']])->update($data))
            {
                $user_id = $mobile_user['id'];
            }
        }
        
        if(isset($user_id) && $user_id > 0)
        {
            // 清除验证码
            $obj->Remove();
            return DataReturn('绑定成功', 0, self::AppUserInfoHandle($user_id));
        }
        return DataReturn('绑定失败', -100);
    }

    /**
     * app用户手机绑定验证码发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppMobileBindVerifySend($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'mobile',
                'error_msg'         => '手机号码不能为空',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'mobile',
                'checked_data'      => 'CheckMobile',
                'error_msg'         => '手机号码格式错误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 验证码公共基础参数
        $verify_params = [
            'key_prefix'    => 'user_bind_'.md5($params['mobile']),
            'expire_time'   => MyC('common_verify_expire_time'),
            'interval_time' => MyC('common_verify_interval_time'),
        ];

        // 发送验证码
        $obj = new \base\Sms($verify_params);
        $code = GetNumberCode(4);
        $status = $obj->SendCode($params['mobile'], $code, MyC('home_sms_user_mobile_binding'));
        
        // 状态
        if($status)
        {
            return DataReturn('发送成功', 0);
        }
        return DataReturn('发送失败'.'['.$obj->error.']', -100);
    }

    /**
     * 用户退出
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-05T14:31:23+0800
     * @param   [array]          $params [输入参数]
     */
    public static function Logout($params = [])
    {
        // 用户信息
        $user = self::LoginUserInfo();

        // 清除session
        MySession(self::$user_login_key, null);

        // html代码
        $body_html = [];

        // 用户退出钩子
        $hook_name = 'plugins_service_user_logout_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => [],
            'user_id'       => isset($user['id']) ? $user['id'] : 0,
            'user'          => $user,
            'body_html'     => &$body_html,
        ]);

        // 数据返回
        $result = [
            'body_html'    => is_array($body_html) ? implode(' ', $body_html) : $body_html,
        ];

        return DataReturn('退出成功', 0, $result);
    }

    /**
     * 获取用户展示信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-05
     * @desc    description
     * @param   [array|int]    $user_ids    [用户id]
     * @param   [array]        $user        [指定用户信息]
     */
    public static function GetUserViewInfo($user_ids, $user = [])
    {
        // 是否指定用户信息
        if(empty($user) && !empty($user_ids))
        {
            if(is_array($user_ids))
            {
                $user_ids = array_filter(array_unique($user_ids));
            }
            if(!empty($user_ids))
            {
                $data = Db::name('User')->where(['id'=>$user_ids])->column('username,nickname,mobile,email,avatar,province,city', 'id');
            }

            // 数据处理
            if(!empty($data) && is_array($data))
            {
                foreach($data as &$v)
                {
                    $v = self::UserHandle($v);
                }
            }

            // 用户id是否数组
            if(is_array($user_ids))
            {
                $user = isset($data) ? $data : [];
            } else {
                $user = (!empty($data) && array_key_exists($user_ids, $data)) ? $data[$user_ids] : [];
            }
        } else {
            $user = self::UserHandle($user);
        }

        return $user;
    }

    /**
     * 用户登录,密码找回左侧数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserEntranceLeftData($params = [])
    {
        // 从缓存获取
        $data = empty($params['cache_key']) ? [] : MyCache($params['cache_key']);

        // 获取数据
        if(empty($data))
        {
            $data = [];
            if(!empty($params['left_key']))
            {
                for($i=1; $i<=3; $i++)
                {
                    $images_value = MyC('home_site_user_'.$params['left_key'].'_ad'.$i.'_images');
                    $url_value = MyC('home_site_user_'.$params['left_key'].'_ad'.$i.'_url');
                    $bg_color_value = MyC('home_site_user_'.$params['left_key'].'_ad'.$i.'_bg_color');
                    if(!empty($images_value))
                    {
                        $data[] = [
                            'images'    => ResourcesService::AttachmentPathViewHandle($images_value),
                            'url'       => empty($url_value) ? null : $url_value,
                            'bg_color'  => empty($bg_color_value) ? null : $bg_color_value,
                        ];
                    }
                }

                // 存储缓存
                if(!empty($params['cache_key']))
                {
                    MyCache($params['cache_key'], $data);
                }
            }
        }
        return DataReturn('操作成功', 0, $data);
    }

    /**
     * 用户推荐id加密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-21
     * @desc    description
     * @param   [int]           $user_id [用户id]
     */
    public static function UserReferrerEncryption($user_id)
    {
        return StrToAscii(base64_encode($user_id));
    }

    /**
     * 用户推荐id解密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-21
     * @desc    description
     * @param   [array]           $params [输入参数, referrer 参数用户推荐id]
     */
    public static function UserReferrerDecrypt($params = [])
    {
        // 推荐人
        $referrer = empty($params['referrer']) ? MySession('share_referrer_id') : $params['referrer'];

        // 查看用户id是否已加密
        if(preg_match('/[a-zA-Z]/', $referrer))
        {
            $referrer = base64_decode(AsciiToStr($referrer));
        }

        return intval($referrer);
    }
}
?>