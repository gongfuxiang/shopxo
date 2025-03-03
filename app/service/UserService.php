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
use app\service\RegionService;
use app\service\SafetyService;
use app\service\ResourcesService;
use app\service\SystemBaseService;
use app\service\ConfigService;
use app\service\ApiService;

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
    public static $user_token_key = 'user_token_data';

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

        // 用户列表读取前钩子
        $hook_name = 'plugins_service_user_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
            'order_by'      => &$order_by,
            'm'             => &$m,
            'n'             => &$n,
        ]);

        // 获取用户列表
        $data = Db::name('User')->where($where)->order($order_by)->field($field)->limit($m, $n)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::UserListHandle($data, $params));
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function UserListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 用户列表钩子-前面
            $hook_name = 'plugins_service_user_list_handle_begin';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);

            // 字段列表
            $keys = ArrayKeys($data);

            // 邀请用户列表
            $referrer_data = [];
            if(in_array('referrer', $keys))
            {
                $referrer_data = self::GetUserViewInfo(array_column($data, 'referrer'));
            }

            // 用户平台信息
            $platform_data = [];
            if(in_array('id', $keys))
            {
                $platform = Db::name('UserPlatform')->where(['user_id'=>array_column($data, 'id')])->select()->toArray();
                if(!empty($platform))
                {
                    $common_platform_type = MyConst('common_platform_type');
                    foreach($platform as $v)
                    {
                        if(!array_key_exists($v['user_id'], $platform_data))
                        {
                            $platform_data[$v['user_id']] = ['data'=>[], 'system'=>[], 'platform'=>[]];
                        }
                        $v['platform_name'] = isset($common_platform_type[$v['platform']]) ? $common_platform_type[$v['platform']]['name'] : $v['platform'];
                        $platform_data[$v['user_id']]['data'][] = $v;
                        if(!in_array($v['system_type'], $platform_data[$v['user_id']]['system']))
                        {
                            $platform_data[$v['user_id']]['system'][] = $v['system_type'];
                        }
                        if(!in_array($v['platform_name'], $platform_data[$v['user_id']]['platform']))
                        {
                            $platform_data[$v['user_id']]['platform'][] = $v['platform_name'];
                        }
                    }
                }
            }

            // 开始处理数据
            $common_gender_list = MyConst('common_gender_list');
            $common_user_status_list = MyConst('common_user_status_list');
            foreach($data as &$v)
            {
                // 生日
                if(array_key_exists('birthday', $v))
                {
                    $v['birthday'] = empty($v['birthday']) ? '' : date('Y-m-d', $v['birthday']);
                }

                // 头像
                if(array_key_exists('avatar', $v))
                {
                    if(!empty($v['avatar']))
                    {
                        $v['avatar'] = ResourcesService::AttachmentPathViewHandle($v['avatar']);
                    } else {
                        $v['avatar'] = UserDefaultAvatar();
                    }
                }

                // 邀请用户信息
                if(array_key_exists('referrer', $v))
                {
                    $v['referrer_info'] = (!empty($referrer_data) && is_array($referrer_data) && array_key_exists($v['referrer'], $referrer_data)) ? $referrer_data[$v['referrer']] : [];
                }

                // 用户平台信息
                if(array_key_exists('id', $v))
                {
                    $temp = (empty($platform_data) || empty($platform_data[$v['id']])) ? [] : $platform_data[$v['id']];
                    $v['user_platform_data'] = empty($temp['data']) ? [] : $temp['data'];
                    $v['system_type_list'] = empty($temp['system']) ? [] : $temp['system'];
                    $v['platform_list'] = empty($temp['platform']) ? [] : $temp['platform'];
                    $v['system_type_text'] = empty($v['system_type_list']) ? '' : implode('，', $v['system_type_list']);
                    $v['platform_text'] = empty($v['platform_list']) ? '' : implode('，', $v['platform_list']);
                }

                // 时间
                if(array_key_exists('add_time', $v))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(array_key_exists('upd_time', $v))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }

                // 性别
                if(array_key_exists('gender', $v))
                {
                    $v['gender_text'] = isset($common_gender_list[$v['gender']]) ? $common_gender_list[$v['gender']]['name'] : '';
                }

                // 状态
                if(array_key_exists('status', $v))
                {
                    $v['status_text'] = $common_user_status_list[$v['status']]['name'];
                }
            }

            // 用户列表钩子-后面
            $hook_name = 'plugins_service_user_list_handle_end';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);
        }
        return $data;
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
        // 用户总数读取前钩子
        $hook_name = 'plugins_service_user_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'where'         => &$where,
        ]);

        // 获取总数
        return (int) Db::name('User')->where($where)->count();
    }

    /**
     * 获取用户登录信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-12-06
     * @desc    description
     * @param   [boolean]         $is_cache [是否缓存读取]
     */
    public static function LoginUserInfo($is_cache = true)
    {
        // 静态数据避免重复读取
        static $user_login_info = null;
        if($user_login_info === null && $is_cache)
        {
            $user_login_info = self::CacheLoginUserInfo();
        }

        // 缓存为空则重新读取
        if(empty($user_login_info))
        {
            if(APPLICATION == 'web')
            {
                // web用户session
                $user_login_info = MySession(self::$user_login_key);
            } else {
                $params = input();
                if(!empty($params['token']))
                {
                    $user_login_info = self::UserTokenData($params['token']);
                }
            }
        }
        return $user_login_info;
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
    public static function UserTokenData($token)
    {
        // token缓存数据
        $user = self::CacheUserTokenData($token);
        if(!empty($user) && isset($user['id']))
        {
            return $user;
        }

        // 数据库校验
        return self::AppUserInfoHandle(['where_field'=>'token', 'where_value'=>$token, 'is_refresh_token'=>0]);
    }

    /**
     * 用户登录缓存数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-28
     * @desc    description
     */
    public static function CacheLoginUserInfo()
    {
        // 静态数据避免重复读取
        static $user_cache_login_info = null;
        if($user_cache_login_info === null)
        {
            // 参数
            $params = input();

            // 用户数据处理
            if(APPLICATION == 'web')
            {
                // web用户session
                $user_cache_login_info = MySession(self::$user_login_key);

                // 用户信息为空，指定了token则设置登录信息
                if(empty($user_cache_login_info))
                {
                    $token = empty($params['token']) ? MyCookie(self::$user_token_key) : $params['token'];
                    if(!empty($token))
                    {
                        $user_cache_login_info = self::CacheUserTokenData($token);
                    }
                }
            } else {
                if(!empty($params['token']))
                {
                    $user_cache_login_info = self::CacheUserTokenData($params['token']);
                }
            }
        }
        return $user_cache_login_info;
    }

    /**
     * 获取用户token缓存用户数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T19:01:59+0800
     * @desc     description
     * @param    [string]                   $token [用户token]
     */
    public static function CacheUserTokenData($token)
    {
        return MyCache(SystemService::CacheKey('shopxo.cache_user_info').$token);
    }

    /**
     * 用户状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-27
     * @desc    description
     * @param   [int]          $user_id      [用户id]
     * @param   [array]        $valid_status [有效状态]
     */
    public static function UserStatusCheck($user_id, $valid_status = [0])
    {
        // 查询用户状态是否正常
        $user = self::UserBaseInfo('id', $user_id);
        if(empty($user))
        {
            return DataReturn(MyLang('common_service.user.user_no_exist_tips'), -110);
        }
        if(!is_array($valid_status))
        {
            $valid_status = explode(',', $valid_status);
        }
        if(!in_array($user['status'], $valid_status))
        {
            $common_user_status_list = MyConst('common_user_status_list');
            if(isset($common_user_status_list[$user['status']]))
            {
                return DataReturn($common_user_status_list[$user['status']]['tips'], -110);
            } else {
                return DataReturn(MyLang('common_service.user.user_status_error_tips'), -110);
            }
        }
        return DataReturn(MyLang('check_success'), 0);
    }

    /**
     * 根据字段获取用户基础信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-25
     * @desc    description
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [string]          $field            [指定字段]
     */
    public static function UserBaseInfo($where_field, $where_value, $field = '*')
    {
        $where = [
            [$where_field, '=', $where_value],
            ['is_delete_time', '=', 0],
            ['is_logout_time', '=', 0],
        ];
        return Db::name('User')->where($where)->field($field)->find();
    }

    /**
     * 根据字段获取用户平台信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-25
     * @desc    description
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [string]          $field            [指定字段]
     * @param   [array]           $params           [输入参数]
     */
    public static function UserPlatformInfo($where_field, $where_value, $field = '*', $params = [])
    {
        $system_type = empty($params['system_type_name']) ? SYSTEM_TYPE : $params['system_type_name'];
        $platform = empty($params['platform']) ? APPLICATION_CLIENT_TYPE : $params['platform'];
        $where = [
            [$where_field, '=', $where_value],
            ['system_type', '=', $system_type],
            ['platform', '=', $platform],
        ];
        return Db::name('UserPlatform')->where($where)->field($field)->find();
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
     * @param   [array]           $params           [输入参数]
     */
    public static function UserInfo($where_field, $where_value, $field = '*', $params = [])
    {
        // 用户平台表结构
        $platform_structure = ResourcesService::TableStructureData('UserPlatform');
        unset($platform_structure['id'], $platform_structure['user_id'], $platform_structure['add_time'], $platform_structure['upd_time']);
        $platform_structure = array_keys($platform_structure);

        // 用户基础和平台条件
        $where_field_arr = explode('|', $where_field);
        foreach($where_field_arr as $k=>$v)
        {
            $where_field_arr[$k] = (in_array($v, $platform_structure) ? 'up.' : 'u.').$v;
        }
        $where_field = implode('|', $where_field_arr);

        // 查询字段处理
        if($field == '*')
        {
            $field = 'u.*, up.'.implode(', up.', $platform_structure);
        } else {
            $field_arr = explode(',', $field);
            $u_arr = array_diff($field_arr, $platform_structure);
            $up_arr = array_intersect($field_arr, $platform_structure);
            $field = 'u.'.implode(', u.', $u_arr).(empty($up_arr) ? '' : ', up.'.implode(', up.', $up_arr));
        }

        // 查询用户信息
        $system_type = empty($params['system_type_name']) ? SYSTEM_TYPE : $params['system_type_name'];
        $platform = empty($params['platform']) ? APPLICATION_CLIENT_TYPE : $params['platform'];
        $where = [
            [$where_field, '=', $where_value],
            ['up.system_type', '=', $system_type],
            ['up.platform', '=', $platform],
            ['u.is_delete_time', '=', 0],
            ['u.is_logout_time', '=', 0],
        ];
        $user = Db::name('User')->alias('u')->join('user_platform up', 'u.id=up.user_id')->where($where)->field($field)->find();
        // 如果当前系统类型和平台 用户和平台表没有对应数据，则先读取用户基础信息在匹配平台数据
        if(empty($user))
        {
            // 是否存在条件字段.指定前缀，则去除
            if(stripos($where_field, '.') !== false)
            {
                $temp = explode('.', $where_field);
                $where_field = $temp[1];
            }
            // 如果当前条件字段是平台表字段则先从平台表读取数据
            if(in_array($where_field, $platform_structure))
            {
                $user_platform = self::MatchingUserPlatformData($where_field, $where_value, $params);
                if(!empty($user_platform))
                {
                    $user = self::UserBaseInfo('id', $user_platform['user_id'], '*', $params);
                    if(!empty($user))
                    {
                        $user = array_merge($user, $user_platform);
                    }
                }
            } else {
                $user = self::UserBaseInfo($where_field, $where_value, '*', $params);
                if(!empty($user))
                {
                    $user_platform = self::MatchingUserPlatformData('user_id', $user['id'], $params);
                    if(!empty($user_platform))
                    {
                        $user = array_merge($user, $user_platform);
                    }
                }
            }
        }
        return $user;
    }

    /**
     * 匹配用户平台数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-12-29
     * @desc    description
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [array]           $params           [输入参数]
     */
    public static function MatchingUserPlatformData($where_field, $where_value, $params = [])
    {
        $temp_platform = [];
        $user_platform = Db::name('UserPlatform')->where([$where_field=>$where_value])->select()->toArray();
        if(!empty($user_platform))
        {
            $platform = empty($params['platform']) ? APPLICATION_CLIENT_TYPE : $params['platform'];
            foreach($user_platform as $pv)
            {
                // 优先取当前平台类型的数据
                if($pv['platform'] == $platform)
                {
                    $temp_platform = $pv;
                    break;
                }
            }
            if(empty($temp_platform))
            {
                $temp_platform = $user_platform[0];
            }
            unset($temp_platform['id'], $temp_platform['add_time'], $temp_platform['upd_time']);
        }
        return $temp_platform;
    }

    /**
     * 根据字段获取用户系统信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-25
     * @desc    description
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [string]          $field            [指定字段]
     * @param   [array]           $params           [输入参数]
     */
    public static function UserSystemInfo($where_field, $where_value, $field = 'u.*', $params = [])
    {
        $system_type = empty($params['system_type_name']) ? SYSTEM_TYPE : $params['system_type_name'];
        $where = [
            [$where_field, '=', $where_value],
            ['up.system_type', '=', $system_type],
            ['u.is_delete_time', '=', 0],
            ['u.is_logout_time', '=', 0],
        ];
        return Db::name('User')->alias('u')->join('user_platform up', 'u.id=up.user_id')->where($where)->field($field)->find();
    }

    /**
     * 用户平台信息添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-15
     * @desc    description
     * @param   [array]          $data   [用户平台信息]
     * @param   [array]          $params [输入参数]
     */
    public static function UserPlatformInsert($data, $params = [])
    {
        // 系统标识
        if(empty($data['system_type']))
        {
            $data['system_type'] = empty($params['system_type_name']) ? SYSTEM_TYPE : $params['system_type_name'];
        }
        // 平台
        if(empty($data['platform']))
        {
            $data['platform'] = APPLICATION_CLIENT_TYPE;
        }
        $data['add_time'] = time();
        return Db::name('UserPlatform')->insertGetId($data) > 0;
    }

    /**
     * 用户平台信息更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-15
     * @desc    description
     * @param   [string]          $where_field [条件字段]
     * @param   [string]          $where_value [条件值]
     * @param   [array]           $data        [更新数据]
     * @param   [array]           $params      [输入参数]
     */
    public static function UserPlatformUpdate($where_field, $where_value, $data, $params = [])
    {
        $where = [
            [$where_field, '=', $where_value],
        ];
        // 非自增id则增加更多条件
        if($where_field != 'id')
        {
            $system_type = empty($params['system_type_name']) ? SYSTEM_TYPE : $params['system_type_name'];
            $platform = empty($params['platform']) ? APPLICATION_CLIENT_TYPE : $params['platform'];
            $where[] = ['system_type', '=', $system_type];
            $where[] = ['platform', '=', $platform];
        }
        $data['upd_time'] = time();
        return Db::name('UserPlatform')->where($where)->update($data);
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
                'error_msg'         => MyLang('common_service.user.save_admin_info_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'username',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.user.form_item_username_message'),
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'username',
                'checked_data'      => 'User',
                'checked_key'       => 'id',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.user.save_user_already_exist_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'nickname',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.user.form_item_nickname_message'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'mobile',
                'checked_data'      => 'CheckMobile',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.user.form_item_mobile_message'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'email',
                'checked_data'      => 'CheckEmail',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.user.form_item_email_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'gender',
                'checked_data'      => array_column(MyConst('common_gender_list'), 'id'),
                'error_msg'         => MyLang('common_service.user.save_gender_range_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(MyConst('common_user_status_list'), 'id'),
                'error_msg'         => MyLang('common_service.user.save_status_range_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'address',
                'checked_data'      => '80',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.user.form_item_address_message'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'pwd',
                'checked_data'      => 'CheckLoginPwd',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.user.form_item_pwd_message'),
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
            'province'              => empty($params['province']) ? '' : $params['province'],
            'city'                  => empty($params['city']) ? '' : $params['city'],
            'county'                => empty($params['county']) ? '' : $params['county'],
            'address'               => empty($params['address']) ? '' : $params['address'],
            'gender'                => intval($params['gender']),
            'integral'              => intval($params['integral']),
            'locking_integral'      => intval($params['locking_integral']),
            'status'                => intval($params['status']),
            'birthday'              => empty($params['birthday']) ? 0 : strtotime($params['birthday']),
            'referrer'              => empty($params['referrer']) ? 0 : intval($params['referrer']),
        ];
        // 邀请人不能为当前用户id、相同则去掉
        if(!empty($data['referrer']) && !empty($params['id']) && $data['referrer'] == $params['id'])
        {
            $data['referrer'] = 0;
        }

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
            $user = Db::name('User')->where(['id'=>intval($params['id'])])->field('id,integral')->find();
            if(empty($user))
            {
                return DataReturn(MyLang('common_service.user.save_user_info_no_exist_tips'), -10);
            }
            $ret = self::UserUpdateHandle($data, $params['id'], $params);
            if($ret['code'] == 0)
            {
                $user_id = $params['id'];
            }
        } else {
            $ret = self::UserInsert($data);
            if($ret['code'] != 0)
            {
                return $ret;
            }
            $user_id = $ret['data']['user_id'];
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
                $opt_integral = $data['integral'];
                if(!empty($params['id']))
                {
                    $old_integral = $user['integral'];
                    $integral_type = ($user['integral'] > $data['integral']) ? 0 : 1;
                    $opt_integral = ($integral_type == 1) ? $data['integral']-$user['integral'] : $user['integral']-$data['integral'];
                }
                IntegralService::UserIntegralLogAdd($user_id, $old_integral, $opt_integral, MyLang('common_service.user.admin_operate_name'), $integral_type, $params['admin']['id']);
            }
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 用户信息更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-02-13
     * @desc    description
     * @param   [array]        $data     [用户更新信息]
     * @param   [int]          $user_id  [用户id]
     * @param   [array]        $params   [输入参数]
     */
    public static function UserUpdateHandle($data, $user_id, $params = [])
    {
        // 注册数据分离处理
        // 用户平台表结构
        $structure = ResourcesService::TableStructureData('UserPlatform');
        unset($structure['id'], $structure['user_id'], $structure['add_time'], $structure['upd_time']);
        $user_base = [];
        $user_platform = [];
        foreach($data as $k=>$v)
        {
            if(array_key_exists($k, $structure))
            {
                $user_platform[$k] = $v;
            } else {
                $user_base[$k] = $v;
            }
        }

        // 用户信息更新
        $user_base['upd_time'] = time();
        if(Db::name('User')->where(['id'=>$user_id])->update($user_base) === false)
        {
            return DataReturn(MyLang('update_fail'), -100);
        }

        // 用户平台信息更新
        $user_platform['user_id'] = $user_id;
        if(self::UserPlatformUpdate('user_id', $user_id, $user_platform, $params) === false)
        {
            return DataReturn(MyLang('update_fail'), -100);
        }

        // 更新成功后钩子
        $hook_name = 'plugins_service_user_update_success';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'user_id'       => $user_id,
            'data'          => $data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }
        return DataReturn(MyLang('update_success'), 0);
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
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }
        // 用户表
        if(!Db::name('User')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_fail'), -100);
        }
        // 用户平台信息表
        if(Db::name('UserPlatform')->where(['user_id'=>$params['ids']])->delete() === false)
        {
            return DataReturn(MyLang('delete_fail'), -100);
        }
        return DataReturn(MyLang('delete_success'), 0);
    }

    /**
     * 用户登录记录
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T11:37:43+0800
     * @param    [int]     $user_id [用户id]
     * @param    [array]   $user    [用户信息]
     * @param    [array]   $params  [输入参数]
     * @return   [boolean]          [记录成功true, 失败false]
     */
    public static function UserLoginRecord($user_id = 0, $user = [], $params = [])
    {
        if(!empty($user_id) && empty($user))
        {
            $user = self::UserHandle(self::UserInfo('id', $user_id, '*', $params));
        }
        if(!empty($user))
        {
            // 用户id处理
            if(empty($user_id))
            {
                $user_id = $user['id'];
            }

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
            }
            return true;
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
        if(!empty($user))
        {
            // 基础数据处理
            if(isset($user['add_time']))
            {
                $user['add_time_text'] = date('Y-m-d H:i:s', $user['add_time']);
            }
            if(isset($user['upd_time']))
            {
                $user['upd_time_text'] = date('Y-m-d H:i:s', $user['upd_time']);
            }
            if(isset($user['gender']))
            {
                $user['gender_text'] = MyConst('common_gender_list')[$user['gender']]['name'];
            }
            if(isset($user['birthday']))
            {
                $user['birthday'] = empty($user['birthday']) ? '' : date('Y-m-d', $user['birthday']);
            }

            // 邮箱/手机
            if(isset($user['mobile']))
            {
                $user['mobile_security'] = empty($user['mobile']) ? '' : mb_substr($user['mobile'], 0, 3, 'utf-8').'***'.mb_substr($user['mobile'], -3, null, 'utf-8');
            }
            if(isset($user['email']))
            {
                $user['email_security'] = empty($user['email']) ? '' : mb_substr($user['email'], 0, 3, 'utf-8').'***'.mb_substr($user['email'], -3, null, 'utf-8');
            }

            // 地址信息
            if(isset($user['province']) && isset($user['city']) && isset($user['county']) && isset($user['address']))
            {
                $user['address_info'] = $user['province'].$user['city'].$user['county'].$user['address'];
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
            if(isset($user['avatar']))
            {
                if(!empty($user['avatar']))
                {
                    $user['avatar'] = ResourcesService::AttachmentPathViewHandle($user['avatar']);
                } else {
                    $user['avatar'] = UserDefaultAvatar();
                }
            }

            // 移除特殊数据
            unset($user['pwd'], $user['salt']);
        }
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
        // 用户id
        $user_id = (empty($params['user']) || empty($params['user']['id'])) ? 0 : $params['user']['id'];
        // 唯一标识
        $unique = md5(empty($user_id) ? ResourcesService::UserUniqueId() : $user_id);

        // 缓存key、是否操作频繁
        $cache_key = 'cache_user_avatar_upload_frequency_'.$unique;
        $cache_value = MyCache($cache_key);
        if(!empty($cache_value) && $cache_value['time']+3600 > time() && $cache_value['count'] >= 5)
        {
            return DataReturn(MyLang('operate_frequent_tips'), -1);
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

        // 头像处理前钩子
        $hook_name = 'plugins_service_user_avatar_upload_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'user_id'       => $user_id,
            'unique'        => $unique,
            'params'        => $params,
            'files'         => $_FILES,
            'root_path'     => $root_path,
            'img_path'      => $img_path,
            'date'          => $date,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 是否指定裁剪信息
        $original_dir = $root_path.$img_path.'original'.$date;
        if(!empty($params['img_width']) && !empty($params['img_height']) && isset($params['img_x']) && isset($params['img_y']))
        {
            $original = $images_obj->GetCompressCut($_FILES[$params['img_field']], $original_dir, 800, 800, $params['img_x'], $params['img_y'], $params['img_width'], $params['img_height']);
        } else {
            $original = $images_obj->GetOriginal($_FILES[$params['img_field']], $original_dir);
        }
        if(!empty($original))
        {
            $compr = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'compr'.$date, 200, 200);
            $small = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'small'.$date, 50, 50);
        }
        if(empty($compr) || empty($small))
        {
            return DataReturn(MyLang('images_format_error_tips'), -3);
        }
        $avatar = DS.$img_path.'compr'.$date.$compr;

        // 缓存记录
        if(empty($cache_value))
        {
            $cache_value = ['count'=>1, 'time'=>time()];
        } else {
            $cache_value['count']++;
        }
        MyCache($cache_key, $cache_value, 3600);

        // 头像处理后钩子
        $hook_name = 'plugins_service_user_avatar_upload_end';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'user_id'       => $user_id,
            'unique'        => $unique,
            'params'        => $params,
            'files'         => $_FILES,
            'root_path'     => $root_path,
            'img_path'      => $img_path,
            'date'          => $date,
            'avatar'        => $avatar,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // app则直接返回图片地址
        if(APPLICATION == 'app')
        {
            return DataReturn(MyLang('upload_success'), 0, ResourcesService::AttachmentPathViewHandle($avatar));
        }

        // 更新用户头像
        $data = [
            'avatar'    => $avatar,
            'upd_time'  => time(),
        ];
        if(Db::name('User')->where(['id'=>$user_id])->update($data))
        {
            // 头像处理成功钩子
            $hook_name = 'plugins_service_user_avatar_upload_success';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'user_id'       => $user_id,
                'unique'        => $unique,
                'params'        => $params,
                'files'         => $_FILES,
                'root_path'     => $root_path,
                'img_path'      => $img_path,
                'date'          => $date,
                'avatar'        => $avatar,
            ]);

            // web端用户登录纪录处理
            self::UserLoginRecord($params['user']['id']);
            return DataReturn(MyLang('upload_success'), 0);
        }
        return DataReturn(MyLang('upload_fail'), -100);
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

        // 是否开启用户登录
        if(!in_array($params['type'], MyC('home_user_login_type', [], true)))
        {
            return DataReturn(MyLang('login_close_tips'), -1);
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
                    'checked_type'      => 'fun',
                    'key_name'          => 'pwd',
                    'checked_data'      => 'CheckLoginPwd',
                    'error_msg'         => MyLang('common_service.user.form_item_pwd_message'),
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

        // 获取用户账户信息
        $method = self::UserUniqueMethod();
        $user = self::$method($ac['data'], $params['accounts']);
        if(empty($user))
        {
            return DataReturn(MyLang('accounts_error_tips'), -3);
        }

        // 密码校验
        // 帐号密码登录需要校验密码
        if($params['type'] == 'username')
        {
            $pwd = LoginPwdEncryption($params['pwd'], $user['salt']);
            if($pwd != $user['pwd'])
            {
                return DataReturn(MyLang('password_error_tips'), -4);
            }
        }

        // 用户平台信息、不存在则添加
        $user_platform = self::UserPlatformInfo('user_id', $user['id']);
        if(empty($user_platform))
        {
            if(!self::UserPlatformInsert(['user_id' => $user['id']], $params))
            {
                return DataReturn(MyLang('insert_fail'), -1);
            }
        }

        // 用户状态
        if(in_array($user['status'], [2,3]))
        {
            return DataReturn(MyConst('common_user_status_list')[$user['status']]['tips'], -10);
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
        $upd_data = [];
        if($params['type'] == 'username')
        {
            $salt = GetNumberCode(6);
            $upd_data['salt'] = $salt;
            $upd_data['pwd'] = LoginPwdEncryption($params['pwd'], $salt);
        }

        // 用户openid
        if(empty($user_platform[APPLICATION_CLIENT_TYPE.'_openid']))
        {
            $openid = self::UserOpenidHandle($params);
            if(!empty($openid['field']) && !empty($openid['value']))
            {
                // openid放入用户data中
                $upd_data[$openid['field']] = $openid['value'];
            }
        }

        // 用户unionid
        if(empty($user_platform[APPLICATION_CLIENT_TYPE.'_unionid']))
        {
            $unionid = self::UserUnionidHandle($params);
            if(!empty($unionid['field']) && !empty($unionid['value']))
            {
                // unionid放入用户data中
                $upd_data[$unionid['field']] = $unionid['value'];
            }
        }

        // 昵称和头像
        if(empty($user['nickname']) && !empty($params['nickname']))
        {
            $upd_data['nickname'] = $params['nickname'];
        }
        if((empty($user['avatar']) || stripos($user['avatar'], 'default-user-avatar') !== false) && !empty($params['avatar']))
        {
            $upd_data['avatar'] = $params['avatar'];
        }

        // 更新用户信息
        if(!empty($upd_data))
        {
            $ret = self::UserUpdateHandle($upd_data, $user['id'], $params);
            if($ret['code'] != 0)
            {
                return DataReturn(MyLang('login_failure_tips'), -100);
            }
        }

        // 清除图片验证码
        if(isset($verify) && isset($verify['data']) && is_object($verify['data']))
        {
            $verify['data']->Remove();
        }

        return self::UserLoginHandle($user['id'], $params);
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
        // 返回前端html代码
        $body_html = [];

        // 用户登录后钩子
        $user = self::UserHandle(self::UserInfo('id', $user_id, '*', $params));

        // 会员码生成处理
        if(empty($user['number_code']))
        {
            $user['number_code'] = self::UserNumberCodeCreatedHandle($user_id);
        }

        // 登录钩子
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
            $result = self::AppUserInfoHandle(['user'=>$user]);
        } else {
            // 登录记录
            if(!self::UserLoginRecord(0, $user))
            {
                return DataReturn(MyLang('login_failure_tips'), -100);
            }
            $result = [
                'body_html'    => is_array($body_html) ? implode(' ', $body_html) : $body_html,
            ];
        }
        return DataReturn(MyLang('login_success'), 0, $result);
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
                'error_msg'         => MyLang('accounts_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pwd',
                'error_msg'         => MyLang('password_empty_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(MyConst('common_user_reg_type_list'), 'value'),
                'error_msg'         => MyLang('register_type_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'is_checked'        => 2,
                'error_msg'         => MyLang('verify_code_empty_tips'),
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
            return DataReturn(MyLang('register_close_tips'), -1);
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
                return DataReturn(MyLang('verify_code_expire_tips'), -10);
            }
            // 是否正确
            if(!$obj->CheckCorrect($params['verify']))
            {
                return DataReturn(MyLang('verify_code_error_tips'), -11);
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
                return DataReturn(MyLang('common_service.user.user_not_audit_tips'), -110);
            }

            // 用户登录session纪录
            if(self::UserLoginRecord($user_ret['data']['user_id']))
            {
                // 成功返回
                if(APPLICATION == 'app')
                {
                    $result = self::AppUserInfoHandle(['where_field'=>'id', 'where_value'=>$user_ret['data']['user_id']]);
                } else {
                    $result = $user_ret['data'];
                }
                return DataReturn(MyLang('register_success'), 0, $result);
            }
            return DataReturn(MyLang('common_service.user.user_register_success_no_login_tips'));
        } else {
            return $user_ret;
        }
        return DataReturn(MyLang('register_fail'), -100);
    }

    /**
     * 用户注册账户校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    public static function UserRegAccountsCheck($params = [])
    {
        switch($params['type'])
        {
            // 手机
            case 'sms' :
                // 手机号码格式
                if(!CheckMobile($params['accounts']))
                {
                    return DataReturn(MyLang('mobile_format_error_tips'), -2);
                }
                // 手机号码是否已存在
                if(self::IsExistAccounts($params['accounts'], 'mobile'))
                {
                    return DataReturn(MyLang('common_service.user.mobile_already_exist_tips'), -3);
                }
                break;

            // 邮箱
            case 'email' :
                // 电子邮箱格式
                if(!CheckEmail($params['accounts']))
                {
                    return DataReturn(MyLang('email_format_error_tips'), -2);
                }
                // 电子邮箱是否已存在
                if(self::IsExistAccounts($params['accounts'], 'email'))
                {
                    return DataReturn(MyLang('common_service.user.email_already_exist_tips'), -3);
                }
                break;

            // 用户名
            case 'username' :
                // 用户名格式
                if(!CheckUserName($params['accounts']))
                {
                    return DataReturn(MyLang('common_service.user.username_format_error_tips'), -2);
                }
                // 用户名是否已存在
                if(self::IsExistAccounts($params['accounts'], 'username'))
                {
                    return DataReturn(str_replace('{$var}', $params['accounts'], MyLang('common_service.user.save_user_already_exist_tips')), -3);
                }
                break;
        }
        return DataReturn(MyLang('operate_success'), 0);
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
    public static function IsExistAccounts($accounts, $field = 'mobile')
    {
        $method = self::UserUniqueMethod();
        $temp = self::$method($field, $accounts);
        return !empty($temp);
    }

    /**
     * 用户校验唯一方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-22
     * @desc    description
     */
    public static function UserUniqueMethod()
    {
        return (MyC('common_user_unique_system_type_model') == 1) ? 'UserSystemInfo' : 'UserBaseInfo';
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
    public static function IsImaVerify($params, $verify_params, $status = 0)
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
            return DataReturn(MyLang('operate_success'), 0, $verify);
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 用户登录账户校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    public static function UserLoginAccountsCheck($params = [])
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
                // 手机号码是否不存在
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
                // 电子邮箱是否不存在
                if(!self::IsExistAccounts($params['accounts'], 'email'))
                {
                    return DataReturn(MyLang('email_no_exist_error_tips'), -3);
                }
                $field = 'email';
                break;

            // 用户名
            case 'username' :
                // 帐号是否不存在
                if(!self::IsExistAccounts($params['accounts'], 'username|mobile|email'))
                {
                    return DataReturn(MyLang('accounts_error_tips'), -3);
                }
                $field = 'username|mobile|email';
                break;
        }
        return DataReturn(MyLang('operate_success'), 0, $field);
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

        // 是否开启用户登录
        if(!in_array($params['type'], MyC('home_user_login_type', [], true)))
        {
            return DataReturn(MyLang('login_close_tips'), -1);
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
                $status = $obj->SendCode($params['accounts'], $code, ConfigService::SmsTemplateValue('home_sms_login_template'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('home_email_login_template'),
                    'title'     =>  MyC('home_site_name').' - '.MyLang('common_service.user.login_email_send_title'),
                    'code'      =>  $code,
                ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn(MyLang('verify_code_not_support_send_error_tips'), -2);
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
                'error_msg'         => MyLang('accounts_empty_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(MyConst('common_user_reg_type_list'), 'value'),
                'error_msg'         => MyLang('register_type_error_tips'),
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
            return DataReturn(MyLang('register_close_tips'), -1);
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
                $status = $obj->SendCode($params['accounts'], $code, ConfigService::SmsTemplateValue('home_sms_user_reg_template'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('home_email_user_reg_template'),
                    'title'     =>  MyC('home_site_name').' - '.MyLang('common_service.user.register_email_send_title'),
                    'code'      =>  $code,
                ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn(MyLang('verify_code_not_support_send_error_tips'), -2);
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
            return DataReturn(MyLang('params_error_tips'), -10);
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
                $status = $obj->SendCode($params['accounts'], $code, ConfigService::SmsTemplateValue('home_sms_user_forget_pwd_template'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('home_email_user_forget_pwd_template'),
                    'title'     =>  MyC('home_site_name').' - '.MyLang('common_service.user.forget_pwd_email_send_title'),
                    'code'      =>  $code,
                ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn(MyLang('common_service.user.mobile_or_email_format_error_tips'), -1);
        }
        if($status)
        {
            // 清除图片验证码
            if(isset($verify) && isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }
            return DataReturn(MyLang('send_success'), 0);
        }
        return DataReturn(MyLang('send_fail').'['.$obj->error.']', -100);
    }

    /**
     * 帐号校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:59:53+0800
     * @param    [string]     $accounts [账户名称]
     * @return   [string]               [账户字段 mobile, email]
     */
    public static function UserForgetAccountsCheck($accounts)
    {
        if(CheckMobile($accounts))
        {
            if(!self::IsExistAccounts($accounts, 'mobile'))
            {
                return DataReturn(MyLang('mobile_no_exist_error_tips'), -3);
            }
            return DataReturn(MyLang('operate_success'), 0, 'mobile');
        } else if(CheckEmail($accounts))
        {
            if(!self::IsExistAccounts($accounts, 'email'))
            {
                return DataReturn(MyLang('email_no_exist_error_tips'), -3);
            }
            return DataReturn(MyLang('operate_success'), 0, 'email');
        }
        return DataReturn(MyLang('common_service.user.mobile_or_email_format_error_tips'), -4);
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
                'error_msg'         => MyLang('accounts_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pwd',
                'error_msg'         => MyLang('password_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => MyLang('verify_code_empty_tips'),
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
                return DataReturn(MyLang('common_service.user.mobile_or_email_format_error_tips'), -1);
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

        // 获取用户信息
        $user = self::UserInfo($ret['data'], $params['accounts']);
        if(empty($user))
        {
            return DataReturn(MyLang('common_service.user.save_user_info_no_exist_tips'), -12);
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
            return DataReturn(MyLang('operate_success'), 0);
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
                'checked_data'      => '1,60',
                'key_name'          => 'nickname',
                'is_checked'        => 2,
                'error_msg'         => MyLang('common_service.user.save_nickname_format_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'checked_data'      => [0,1,2],
                'key_name'          => 'gender',
                'is_checked'        => 2,
                'error_msg'         => MyLang('common_service.user.save_gender_range_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'address',
                'checked_data'      => '80',
                'is_checked'        => 2,
                'error_msg'         => MyLang('common_service.user.form_item_address_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 更新的字段
        $fields = [
            'avatar',
            'birthday',
            'nickname',
            'gender',
            'province',
            'city',
            'county',
            'address',
            'mobile',
        ];
        $data = [];
        foreach($fields as $k)
        {
            if(array_key_exists($k, $params))
            {
                switch($k)
                {
                    // 头像
                    case 'avatar' :
                        $data[$k] = empty($params['avatar']) ? '' : ResourcesService::AttachmentPathHandle($params['avatar']);
                        break;
                    // 生日
                    case 'birthday' :
                        $data[$k] = empty($params['birthday']) ? '' : strtotime($params['birthday']);
                        break;
                    // 手机、用户基础信息填写一键授权手机号码
                    case 'mobile' :
                        if(!empty($params['mobile']))
                        {
                            // 手机号码不存在则绑定到当前账号下
                            $method = self::UserUniqueMethod();
                            $temp = self::$method('mobile', $params['mobile']);
                            if(empty($temp))
                            {
                                $data[$k] = $params['mobile'];
                            }
                        }
                        break;
                    default :
                        $data[$k] = empty($params[$k]) ? '' : $params[$k];
                }
            }
        }
        if(empty($data))
        {
            return DataReturn(MyLang('content_params_empty_tips'), -1);
        }

        // 更新用户信息
        if(self::UserUpdateHandle($data, $params['user']['id'], $params))
        {
            // 重新获取用户信息
            $user = self::UserHandle(self::UserInfo('id', $params['user']['id']));

            // 重新更新用户缓存
            self::UserLoginRecord(0, $user);
            if(!empty($user['token']))
            {
                MyCache(SystemService::CacheKey('shopxo.cache_user_info').$user['token'], $user);
            }
            return DataReturn(MyLang('change_success'), 0, $user);
        }
        return DataReturn(MyLang('change_fail'), -100);
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
            'county'            => empty($params['county']) ? '' : $params['county'],
            'mobile'            => empty($params['mobile']) ? '' : $params['mobile'],
            'referrer'          => isset($params['referrer']) ? $params['referrer'] : 0,
        ];

        // 用户唯一方法
        $method = self::UserUniqueMethod();

        // 是否需要添加用户
        $is_insert_user = false;

        // 用户信息处理
        $user_platform = self::UserPlatformInfo($field, $params['openid']);
        if(!empty($user_platform))
        {
            // 用户信息
            $user = self::UserBaseInfo('id', $user_platform['user_id']);
            if(empty($user))
            {
                $is_insert_user = true;
            } else {
                // 用户状态
                if($user['status'] != 0)
                {
                    return DataReturn(MyLang('common_service.user.user_not_audit_tips'), -301);
                }

                // 如果有手机号码、如当前用户不存在手机号码则绑定
                if(empty($user['mobile']) && !empty($data['mobile']))
                {
                    // 手机号码不存在则绑定到当前账号下
                    $temp = self::$method('mobile', $data['mobile']);
                    if(empty($temp))
                    {
                        // 是否被禁止
                        $ret = self::UserRegForbidCheck($data['mobile'], 'mobile');
                        if($ret['code'] != 0)
                        {
                            return $ret;
                        }
                        // 绑定手机
                        $upd_data = [
                            'mobile'    => $data['mobile'],
                            'upd_time'  => time(),
                        ];
                        if(Db::name('User')->where(['id'=>$user['id']])->update($upd_data))
                        {
                            return DataReturn(MyLang('bind_success'), 0, self::AppUserInfoHandle(['where_field'=>'id', 'where_value'=>$user['id']]));
                        }
                    } else {
                        if($user['id'] != $temp['id'])
                        {
                            return DataReturn(MyLang('common_service.user.mobile_already_bind_account_tips'), -1);
                        }
                    }
                }
                return DataReturn(MyLang('auth_success'), 0, $user);
            }
        } else {
            // 用户unionid
            $unionid = self::UserUnionidHandle($params);
            if(!empty($unionid['field']) && !empty($unionid['value']))
            {
                // unionid字段是否存在用户
                $unionid_user_platform = self::UserPlatformInfo($unionid['field'], $unionid['value']);
                if(!empty($unionid_user_platform))
                {
                    // 用户信息
                    $unionid_user_base = self::UserBaseInfo('id', $unionid_user_platform['user_id']);
                    if(empty($user))
                    {
                        $is_insert_user = true;
                    } else {
                        // 用户状态
                        if($unionid_user_base['status'] != 0)
                        {
                            return DataReturn(MyLang('common_service.user.user_not_audit_tips'), -301);
                        }

                        // openid绑定
                        if(!self::UserPlatformUpdate('id', $unionid_user_platform['id'], [$field => $params['openid']], $params))
                        {
                            return DataReturn(MyLang('bind_fail'), -1);
                        }

                        // 如果有手机号码、如当前用户不存在手机号码则绑定
                        if(empty($unionid_user_base['mobile']) && !empty($data['mobile']))
                        {
                            // 手机号码不存在则绑定到当前账号下
                            $temp = self::$method('mobile', $data['mobile']);
                            if(empty($temp))
                            {
                                // 是否被禁止
                                $ret = self::UserRegForbidCheck($data['mobile'], 'mobile');
                                if($ret['code'] != 0)
                                {
                                    return $ret;
                                }
                                // 绑定手机
                                $upd_data = [
                                    'mobile'    => $data['mobile'],
                                    'upd_time'  => time(),
                                ];
                                if(!Db::name('User')->where(['id'=>$unionid_user_base['id']])->update($upd_data))
                                {
                                    return DataReturn(MyLang('bind_fail'), -1);
                                }
                            } else {
                                if($unionid_user_base['id'] != $temp['id'])
                                {
                                    return DataReturn(MyLang('common_service.user.mobile_already_bind_account_tips'), -1);
                                }
                            }
                        }
                        return DataReturn(MyLang('bind_success'), 0, self::AppUserInfoHandle(['where_field'=>'id', 'where_value'=>$unionid_user_base['id']]));
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
                // 绑定手机号码
                if(!empty($data['mobile']))
                {
                    // 如果手机号码存在则直接绑定openid
                    // 不存在添加，存在更新openid
                    $user = self::$method('mobile', $data['mobile']);
                    if(!empty($user))
                    {
                        // 上面openid和unionid都没存在信息，但是存在手机号码信息则增加用户平台数据
                        $user_platform_insert = [
                            'user_id'      => $user['id'],
                            $field         => $params['openid'],
                        ];
                        if(!empty($unionid['field']) && !empty($unionid['value']))
                        {
                            $user_platform_insert[$unionid['field']] = $unionid['value'];
                        }
                        if(self::UserPlatformInsert($user_platform_insert, $params))
                        {
                            return DataReturn(MyLang('bind_success'), 0, self::AppUserInfoHandle(['where_field'=>'id', 'where_value'=>$user['id']]));
                        }
                    } else {
                        $is_insert_user = true;
                    }
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
                    return DataReturn(MyLang('common_service.user.user_not_audit_tips'), -110);
                }
                return DataReturn(MyLang('auth_success'), 0, self::AppUserInfoHandle(['where_field'=>'id', 'where_value'=>$ret['data']['user_id']]));
            }
            return $ret;
        }
        return DataReturn(MyLang('auth_success'), 0, self::AppUserInfoHandle(['user'=>$data]));
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
        return self::UserPlatformUpdate('user_id', $user_id, [$field => $openid]);
    }

    /**
     * 用户openid处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-02-11
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserOpenidHandle($params = [])
    {
        $field = null;
        $value = null;
        $fields_arr = array_column(MyConst('common_appmini_type'), 'value');
        foreach($fields_arr as $type)
        {
            $openid = $type.'_openid';
            if(!empty($params[$openid]))
            {
                $field = $openid;
                $value = $params[$openid];
                break;
            }
        }
        return ['field'=>$field, 'value'=>$value];
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
        // 头条用户unionid
        $field = null;
        $value = null;
        $fields_arr = ['weixin_unionid', 'qq_unionid', 'toutiao_unionid'];
        foreach($fields_arr as $unionid)
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
     * @param   [array]           $params             [输入参数]
     */
    public static function AppUserInfoHandle($params = [])
    {
        // 获取用户信息
        if(!empty($params['user_id']))
        {
            $params['user'] = self::UserHandle(self::UserInfo('id', $params['user_id']));
        } elseif(!empty($params['where_field']) && !empty($params['where_value']) && empty($params['user']))
        {
            $params['user'] = self::UserHandle(self::UserInfo($params['where_field'], $params['where_value']));
        }

        if(!empty($params['user']))
        {
            // 是否强制绑定手机号码
            $params['user']['is_mandatory_bind_mobile'] = intval(MyC('common_user_is_mandatory_bind_mobile'));

            // 基础处理
            if(!empty($params['user']['id']))
            {
                // 会员码生成处理
                if(empty($params['user']['number_code']))
                {
                    $params['user']['number_code'] = self::UserNumberCodeCreatedHandle($params['user']['id']);
                }

                // 重新生成token更新到数据库并缓存
                if(!isset($params['is_refresh_token']) || $params['is_refresh_token'] == 1)
                {
                    $params['user'] = self::UserTokenUpdate($params['user']['id'], $params['user']);
                }
            }

            // token读取没有缓存则记录缓存
            if(!empty($params['where_field']) && !empty($params['where_value']) && $params['where_field'] == 'token')
            {
                $user_cache_login_info = self::CacheUserTokenData($params['where_value']);
                if(empty($user_cache_login_info))
                {
                    MyCache(SystemService::CacheKey('shopxo.cache_user_info').$params['where_value'], $params['user']);
                }
            }

            // 用户信息钩子
            $hook_name = 'plugins_service_user_app_info_handle';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'user_id'       => (empty($params['user']) || empty($params['user']['id'])) ? 0 : $params['user']['id'],
                'user'          => &$params['user'],
                'params'        => $params,
            ]);
        }
        return $params['user'];
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
        if(empty($user) && !empty($user_id))
        {
            $user = self::UserHandle(self::UserInfo('id', $user_id));
        }
        if(!empty($user))
        {
            // token生成并存储缓存
            $user['token'] = ApiService::CreatedUserToken($user['id']);
            if(self::UserPlatformUpdate('user_id', $user['id'], ['token'=>$user['token'], 'upd_time'=>time()]) !== false)
            {
                MyCache(SystemService::CacheKey('shopxo.cache_user_info').$user['token'], $user);
            }

            // web端用户登录纪录处理
            self::UserLoginRecord($user_id, $user);
        }
        return $user;
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
        // 用户唯一方法
        $method = self::UserUniqueMethod();
        // 用户名、手机、邮箱不允许重复注册
        if(!empty($data['username']))
        {
            // 是否被禁止
            $ret = self::UserRegForbidCheck($data['username'], 'username');
            if($ret['code'] != 0)
            {
                return $ret;
            }

            // 唯一验证
            $temp = self::$method('username', $data['username']);
            if(!empty($temp))
            {
                return DataReturn(str_replace('{$var}', $data['username'], MyLang('common_service.user.save_user_already_exist_tips')), -10);
            }
        } else if(!empty($data['mobile']))
        {
            // 是否被禁止
            $ret = self::UserRegForbidCheck($data['mobile'], 'mobile');
            if($ret['code'] != 0)
            {
                return $ret;
            }

            // 唯一验证
            $temp = self::$method('mobile', $data['mobile']);
            if(!empty($temp))
            {
                return DataReturn(MyLang('common_service.user.mobile_already_exist_tips'), -10);
            }
        } else if(!empty($data['email']))
        {
            // 是否被禁止
            $ret = self::UserRegForbidCheck($data['email'], 'email');
            if($ret['code'] != 0)
            {
                return $ret;
            }

            // 唯一验证
            $temp = self::$method('email', $data['email']);
            if(!empty($temp))
            {
                return DataReturn(MyLang('common_service.user.email_already_exist_tips'), -10);
            }
        }

        // 用户基础信息处理
        $data = self::UserBaseHandle($data, $params);

        // 用户openid
        $openid = self::UserOpenidHandle($params);
        if(!empty($openid['field']) && !empty($openid['value']))
        {
            // openid放入用户data中
            $data[$openid['field']] = $openid['value'];
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

        // 注册数据分离处理
        // 用户平台表结构
        $structure = ResourcesService::TableStructureData('UserPlatform');
        unset($structure['id'], $structure['user_id'], $structure['add_time'], $structure['upd_time']);
        $user_base = [];
        $user_platform = [];
        foreach($data as $k=>$v)
        {
            if(array_key_exists($k, $structure))
            {
                $user_platform[$k] = $v;
            } else {
                $user_base[$k] = $v;
            }
        }

        // 注册添加之前钩子
        $hook_name = 'plugins_service_user_register_begin';
        MyEventTrigger($hook_name, [
            'hook_name'      => $hook_name,
            'is_backend'     => true,
            'params'         => &$params,
            'user_base'      => &$user_base,
            'user_platform'  => &$user_platform,
        ]);

        // 用户信息以手机或邮箱、不存在则添加
        $user_base['add_time'] = time();
        $user_id = Db::name('User')->insertGetId($user_base);
        if($user_id <= 0)
        {
            return DataReturn(MyLang('insert_fail'), -100);
        }

        // 用户平台信息添加
        $user_platform['user_id'] = $user_id;
        if(!self::UserPlatformInsert($user_platform, $params))
        {
            return DataReturn(MyLang('insert_fail'), -100);
        }

        // 会员码生成处理
        self::UserNumberCodeCreatedHandle($user_id);

        // 清除推荐id
        if(!empty($user_base['referrer']))
        {
            MySession('share_referrer_id', null);
            MyCookie('share_referrer_id', null);
        }

        // 返回前端html代码
        $body_html = [];

        // 注册成功后钩子
        $user = self::UserHandle(self::UserInfo('id', $user_id, 'id,number_code,system_type,status,username,nickname,mobile,email,gender,avatar,province,city,county,birthday,add_time'));
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
        return DataReturn(MyLang('insert_success'), 0, $result);
    }

    /**
     * 会员码生成处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-11-09
     * @desc    description
     * @param   [int]          $user_id [用户id]
     */
    public static function UserNumberCodeCreatedHandle($user_id)
    {
        // 会员码
        $max = 10;
        $len = strlen($user_id);
        $number_code = '8888'.(($len < $max) ? GetNumberCode($max-$len).$user_id : $user_id);

        // 更新数据库
        Db::name('User')->where(['id'=>$user_id])->update(['number_code'=>$number_code]);
        return $number_code;
    }

    /**
     * 用户基础信息处理、注册绑定的时候处理外部传入的基础信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-13
     * @desc    description
     * @param   [array]          $data   [用户信息]
     * @param   [array]          $params [输入参数]
     */
    public static function UserBaseHandle($data, $params = [])
    {
        // 系统类型
        if(empty($data['system_type']))
        {
            $data['system_type'] = empty($params['system_type_name']) ? SYSTEM_TYPE : $params['system_type_name'];
        }

        // 基础参数处理
        if(!empty($params) && is_array($params))
        {
            // 是否存在基信息
            // 参数key => dbkey
            $base_fields = [
                'nickname'      => [
                    'key'   => 'nickname',
                    'type'  => 'string'
                ],
                'avatar'        => [
                    'key'   => 'avatar',
                    'type'  => 'url'
                ],
                'province'      => [
                    'key'   => 'province',
                    'type'  => 'string'
                ],
                'city'          => [
                    'key'   => 'city',
                    'type'  => 'string'
                ],
                'county'          => [
                    'key'   => 'county',
                    'type'  => 'string'
                ],
                'gender'        => [
                    'key'   => 'gender',
                    'type'  => 'int',
                    'isset' => 1
                ],
            ];
            foreach($base_fields as $k=>$v)
            {
                if(!empty($params[$k]) || (isset($v['isset']) && isset($params[$k])))
                {
                    switch($v['type'])
                    {
                        // url处理
                        case 'url' :
                            $params[$k] = str_replace(['&amp;'], ['&'], $params[$k]);
                            // 头像如果是默认则置空
                            if($k == 'avatar' && !empty($params[$k]) && stripos($params[$k], 'default-user-avatar') !== false)
                            {
                                $params[$k] = '';
                            }
                            break;

                        // 整数
                        case 'int' :
                            $params[$k] = intval($params[$k]);
                            break;
                    }
                    $data[$v['key']] = $params[$k];
                }
            }
        }
        return $data;
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
        return self::AppAccountsBindhHandle('mobile', $params);
    }

    /**
     * app用户邮箱绑定
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppEmailBind($params = [])
    {
        return self::AppAccountsBindhHandle('email', $params);
    }

    /**
     * app用户手机或邮箱绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-11
     * @desc    description
     * @param   [string]          $type   [字段每次类型（mobile, email）]
     * @param   [array]           $params [输入参数]
     */
    public static function AppAccountsBindhHandle($type, $params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => $type,
                'error_msg'         => MyLang('common_service.user.'.$type.'_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => MyLang('verify_code_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户手机或邮箱绑定前校验钩子
        $hook_name = 'plugins_service_user_app_'.$type.'_bind_begin_check';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 格式验证
        $check_fun = 'Check'.ucfirst($type);
        if(!$check_fun($params[$type]))
        {
            return DataReturn(MyLang($type.'_format_error_tips'), -2);
        }

        // 验证码校验
        $verify_params = [
            'key_prefix'    => 'user_bind_'.md5($params[$type]),
            'expire_time'   => MyC('common_verify_expire_time')
        ];
        $obj = new \base\Sms($verify_params);

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

        // 用户更新数据
        $data = [
            $type    => $params[$type],
        ];

        // 是否小程序请求
        $is_appmini = array_key_exists(APPLICATION_CLIENT_TYPE, MyConst('common_appmini_type'));

        // 根据绑定账户类型【手机或邮箱】获取用户信息
        $method = self::UserUniqueMethod();
        $db_user = self::$method($type, $data[$type]);

        // 额外信息
        if(empty($db_user))
        {
            if(empty($db_user['nickname']) && !empty($params['nickname']))
            {
                $data['nickname'] = $params['nickname'];
            }
            if(empty($db_user['avatar']) && !empty($params['avatar']))
            {
                $data['avatar'] = $params['avatar'];
            }
            if(empty($db_user['province']) && !empty($params['province']))
            {
                $data['province'] = $params['province'];
            }
            if(empty($db_user['city']) && !empty($params['city']))
            {
                $data['city'] = $params['city'];
            }
            if(empty($db_user['county']) && !empty($params['county']))
            {
                $data['county'] = $params['county'];
            }
            if(empty($db_user) && isset($params['gender']))
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
                return DataReturn(MyLang('common_service.user.user_openid_empty_tips'), -20);
            }

            // openid数据
            $data[$accounts_field] = $params[$accounts_field];

            // 小程序请求获取用户信息
            $user_platform = self::UserPlatformInfo($accounts_field, $params[$accounts_field]);
            $current_user = empty($user_platform) ? [] : self::UserBaseInfo('id', $user_platform['user_id']);
        } else {
            // 当前登录用户
            $current_user = self::LoginUserInfo();
        }

        // 用户是否存在已登录
        if(!empty($current_user))
        {
            // 手机帐号信息是否存在
            if(!empty($db_user))
            {
                // id不一致则提示错误
                if($current_user['id'] != $db_user['id'])
                {
                    return DataReturn(MyLang('common_service.user.'.$type.'_already_bind_account_tips'), -50);
                }

                // 是否与当前帐号的手机号码一致
                if(!empty($current_user[$type]) && $current_user[$type] == $db_user[$type])
                {
                    return DataReturn(MyLang('common_service.user.'.$type.'_current_mobile_identical_tips'), -51);
                }
            }

            // 当前用户赋值手机帐号信息
            $db_user = $current_user;
        }

        // 不存在添加/则更新
        if(empty($db_user))
        {
            // 如果用户不存在则新增用户状态字段
            // 是否需要审核
            $common_register_is_enable_audit = MyC('common_register_is_enable_audit', 0);
            $data['status'] = ($common_register_is_enable_audit == 1) ? 3 : 0;

            // 新增用户
            $user_ret = self::UserInsert($data, $params);
            if($user_ret['code'] != 0)
            {
                return $user_ret;
            }
            $user_id = $user_ret['data']['user_id'];
        } else {
            // 小程序请求处理
            if($is_appmini)
            {
                // 用户unionid
                $unionid = self::UserUnionidHandle($params);
                if(!empty($unionid['field']) && !empty($unionid['value']))
                {
                    if(empty($db_user[$unionid['field']]))
                    {
                        // unionid放入用户data中
                        $data[$unionid['field']] = $unionid['value'];
                    }
                }
            }

            // 帐号信息更新
            $ret = self::UserUpdateHandle($data, $db_user['id'], $params);
            if($ret['code'] != 0)
            {
                return $ret;
            }
            $user_id = $db_user['id'];
        }
        if(isset($user_id) && $user_id > 0)
        {
            // 用户平台信息、不存在则添加
            $user_platform = self::UserPlatformInfo('user_id', $user_id);
            if(empty($user_platform))
            {
                if(!self::UserPlatformInsert(['user_id' => $user_id], $params))
                {
                    return DataReturn(MyLang('insert_fail'), -1);
                }
            }

            // 清除验证码
            $obj->Remove();
            return DataReturn(MyLang('bind_success'), 0, self::AppUserInfoHandle(['where_field'=>'id', 'where_value'=>$user_id]));
        }
        return DataReturn(MyLang('bind_fail'), -100);
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
        return self::AppAccountsBindVerifySendHandle('mobile', $params);
    }

    /**
     * app用户邮箱绑定验证码发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppEmailBindVerifySend($params = [])
    {
        return self::AppAccountsBindVerifySendHandle('email', $params);
    }

    /**
     * app用户手机或邮箱绑定验证码发送处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-11
     * @desc    description
     * @param   [string]          $type   [字段每次类型（mobile, email）]
     * @param   [array]           $params [输入参数]
     */
    public static function AppAccountsBindVerifySendHandle($type, $params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => $type,
                'error_msg'         => MyLang('common_service.user.'.$type.'_empty_tips'),
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => $type,
                'checked_data'      => 'Check'.ucfirst($type),
                'error_msg'         => MyLang($type.'_format_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 验证码公共基础参数
        $verify_params = [
            'key_prefix'    => 'user_bind_'.md5($params[$type]),
            'expire_time'   => MyC('common_verify_expire_time'),
            'interval_time' => MyC('common_verify_interval_time'),
        ];

        // 发送验证码
        $code = GetNumberCode(4);
        switch($type)
        {
            // 短信
            case 'mobile' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params[$type], $code, ConfigService::SmsTemplateValue('home_sms_user_mobile_binding_template'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                    'email'     =>  $params[$type],
                    'content'   =>  MyC('home_email_user_email_binding_template'),
                    'title'     =>  MyC('home_site_name').' - '.MyLang('common_service.safety.send_verify_email_title'),
                    'code'      =>  $code,
                ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn(MyLang('verify_code_not_support_send_error_tips'), -2);
        }
        if($status)
        {
            return DataReturn(MyLang('send_success'), 0);
        }
        return DataReturn(MyLang('send_fail').'['.$obj->error.']', -100);
    }

    /**
     * 根据token获取用户信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-12-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function TokenUserinfo($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'token',
                'error_msg'         => MyLang('common_service.user.token_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取用户信息并处理
        $user = self::UserHandle(self::UserInfo('token', $params['token']));
        if(empty($user))
        {
            return DataReturn(MyLang('common_service.user.save_user_info_no_exist_tips'), -1);
        }
        return DataReturn('success', 0, $user);
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
        $user = self::CacheLoginUserInfo();

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
        return DataReturn(MyLang('quit_success'), 0, $result);
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
            // 参数处理查询数据
            if(is_array($user_ids))
            {
                $user_ids = array_filter(array_unique($user_ids));
            }
            // 静态数据容器，确保每一个用户只读取一次，避免重复读取浪费资源
            static $user_view_info_static_data = [];
            if(!empty($user_ids))
            {
                $temp_user_ids = [];
                $params_user_ids = is_array($user_ids) ? $user_ids : explode(',', $user_ids);
                foreach($params_user_ids as $uid)
                {
                    if(empty($user_view_info_static_data) || !array_key_exists($uid, $user_view_info_static_data))
                    {
                        $temp_user_ids[] = $uid;
                    }
                }
                // 存在未读取的规格咋数据库读取
                if(!empty($temp_user_ids))
                {
                    $data = Db::name('User')->where(['id'=>$temp_user_ids])->column('id,number_code,username,nickname,mobile,email,avatar,gender,birthday,province,city,county,address,integral,locking_integral,add_time', 'id');
                    if(!empty($data))
                    {
                        foreach($data as $uid=>$uv)
                        {
                            $user_view_info_static_data[$uid] = self::UserHandle($uv);
                        }
                    }
                    // 空数据记录、避免重复查询
                    foreach($temp_user_ids as $uid)
                    {
                        if(!array_key_exists($uid, $user_view_info_static_data))
                        {
                            $user_view_info_static_data[$uid] = null;
                        }
                    }
                }
            }

            // 用户id是否数组
            if(is_array($user_ids))
            {
                $user = isset($user_view_info_static_data) ? $user_view_info_static_data : [];
            } else {
                $user = (!empty($user_view_info_static_data) && array_key_exists($user_ids, $user_view_info_static_data)) ? $user_view_info_static_data[$user_ids] : [];
            }
        } else {
            if(!empty($user))
            {
                $user = self::UserHandle($user);
            }
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
        return DataReturn(MyLang('operate_success'), 0, $data);
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
     * @param   [array]           $params [输入参数, referrer 参数用户推荐id, 会员码, 手机, 邮箱]
     */
    public static function UserReferrerDecrypt($params = [])
    {
        // 推荐人
        if(empty($params['referrer']))
        {
            $referrer = MySession('share_referrer_id');
            if(empty($referrer))
            {
                $referrer = MyCookie('share_referrer_id');
            }
        } else {
            $referrer = $params['referrer'];
        }
        if(!empty($referrer))
        {
            // 用户验证、默认用户id和会员码
            $field = 'id|number_code';
            // 是否手机号码
            if(CheckMobile($referrer))
            {
                $field = 'mobile';
            // 是否电子邮箱
            } else if(CheckEmail($referrer))
            {
                $field = 'email';
            } else {
                // 查看用户id是否已加密
                if(preg_match('/[a-zA-Z]/', $referrer))
                {
                    $referrer = intval(base64_decode(AsciiToStr($referrer)));
                }
            }
            $referrer = Db::name('User')->where([$field=>$referrer, 'status'=>0])->value('id');
        }
        return empty($referrer) ? 0 : intval($referrer);
    }

    /**
     * 用户登录注册后跳转页面地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public static function UserLoginOrRegBackRefererUrl()
    {
        // 上一个页面, 空则用户中心
        $referer_url = empty($_SERVER['HTTP_REFERER']) ? MyUrl('index/user/index') : htmlentities($_SERVER['HTTP_REFERER']);
        if(!empty($_SERVER['HTTP_REFERER']))
        {
            // 是否是指定页面，则赋值用户中心
            $all = ['login', 'regster', 'forget', 'logininfo', 'reginfo', 'smsreginfo', 'emailreginfo', 'forgetpwdinfo', 'logout'];
            $status = false;
            foreach($all as $v)
            {
                if(strpos($_SERVER['HTTP_REFERER'], $v) !== false)
                {
                    $referer_url = MyUrl('index/user/index');
                    $status = true;
                    break;
                }
            }

            // 未匹配到指定页面
            if(!$status)
            {
                // 非商城域名，则赋值用户中心
                if(GetUrlHost($referer_url) != GetUrlHost(__MY_URL__))
                {
                    $referer_url = MyUrl('index/user/index');
                }
            }
        }
        return $referer_url;
    }

    /**
     * 用户名、手机、邮箱注册是否禁止
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-12-18
     * @desc    description
     * @param   [string]          $value [用户名、手机、邮箱]
     * @param   [string]          $type  [类型 username用户名、mobile手机、email邮箱]
     */
    public static function UserRegForbidCheck($value, $type)
    {
        $forbid = MyC('home_userregister_unique_forbid_value');
        if(!empty($forbid))
        {
            if(!is_array($forbid))
            {
                $forbid = explode(',', $forbid);
            }
            if(in_array($value, $forbid))
            {
                return DataReturn(MyLang('register_'.$type.'_forbid_error_tips').'('.$value.')', -1);
            }
        }
        return DataReturn('success', 0);
    }
}
?>