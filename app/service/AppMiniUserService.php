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

use app\service\UserService;

/**
 * 小程序用户服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppMiniUserService
{
    /**
     * 读取站点配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-13
     * @desc    description
     * @param   [string]    $key           [索引名称]
     * @return  [mixed]                    [配置信息值,没找到返回null]
     */
    public static function AppMiniConfig($key)
    {
        // 获取配置
        $value = MyC($key);

        // 小程序配置信息读取钩子
        $hook_name = 'plugins_service_appmini_config_value';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'key'           => $key,
            'value'         => &$value,
        ]);

        return $value;
    }

    /**
     * 支付宝用户授权
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AlipayUserAuth($params = [])
    {
        // 参数
        if(!empty($params['authcode']))
        {
            // 授权
            $ret = (new \base\Alipay())->GetAuthSessionKey(self::AppMiniConfig('common_app_mini_alipay_appid'), $params['authcode']);
            if($ret['code'] == 0)
            {
                // 先从数据库获取用户信息
                $user = UserService::AppUserInfoHandle(null, 'alipay_openid', $ret['data']['user_id']);
                if(empty($user))
                {
                    $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$ret['data']['user_id']]);
                } else {
                    // 用户状态
                    $ret = UserService::UserStatusCheck('id', $user['id']);
                    if($ret['code'] == 0)
                    {
                        // 标记用户存在
                        $user['is_user_exist'] = 1;
                        $ret = DataReturn('授权登录成功', 0, $user);
                    }
                }
            }
        } else {
            $ret = DataReturn('授权码为空', -1);
        }
        return $ret;
    }

    /**
     * 支付宝小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AlipayUserInfo($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'auth_data',
                'error_msg'         => '授权数据为空',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'alipay_openid', $params['openid']);
            if(empty($user))
            {
                // 字段名称不一样参数处理
                $auth_data = is_array($params['auth_data']) ? $params['auth_data'] : json_decode(htmlspecialchars_decode($params['auth_data']), true);
                $auth_data['nickname'] = isset($auth_data['nickName']) ? $auth_data['nickName'] : '';
                $auth_data['gender'] = empty($auth_data['gender']) ? 0 : (($auth_data['gender'] == 'f') ? 1 : 2);

                // 公共参数处理
                $auth_data['weixin_unionid'] = isset($params['unionid']) ? $params['unionid'] : '';
                $auth_data['openid'] = $params['openid'];
                $auth_data['referrer']= isset($params['referrer']) ? $params['referrer'] : 0;
                $ret = UserService::AuthUserProgram($auth_data, 'alipay_openid');
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    $ret = DataReturn('授权成功', 0, $user);
                }
            }
        } else {
            $ret = DataReturn($ret, -1);
        }
        return $ret;
    }

    /**
     * 微信小程序获取用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function WeixinUserAuth($params = [])
    {
        // 授权
        $ret = (new \base\Wechat(self::AppMiniConfig('common_app_mini_weixin_appid'), self::AppMiniConfig('common_app_mini_weixin_appsecret')))->GetAuthSessionKey($params);
        if($ret['code'] == 0)
        {
            // unionid
            $unionid = empty($ret['data']['unionid']) ? '' : $ret['data']['unionid'];

            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'weixin_openid', $ret['data']['openid']);
            if(empty($user) && !empty($unionid))
            {
                // 根据unionid获取数据
                $user = UserService::AppUserInfoHandle(null, 'weixin_unionid', $unionid);
            }
            if(empty($user))
            {
                $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$ret['data']['openid'], 'unionid'=>$unionid]);
            } else {
                // 如果用户openid为空则绑定到用户下面
                if(empty($user['weixin_openid']))
                {
                    if(UserService::UserOpenidBind($user['id'], $ret['data']['openid'], 'weixin_openid'))
                    {
                        // 登录数据更新
                        $user = UserService::AppUserInfoHandle($user['id']);
                    }
                }
            }

            // 用户状态
            if(!empty($user))
            {
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    // 标记用户存在
                    $user['is_user_exist'] = 1;
                    $ret = DataReturn('授权登录成功', 0, $user);
                }
            }
        }
        return $ret;
    }

    /**
     * 微信小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function WeixinUserInfo($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'auth_data',
                'error_msg'         => '授权数据为空',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'weixin_openid', $params['openid']);
            if(empty($user))
            {
                // 字段名称不一样参数处理
                $auth_data = is_array($params['auth_data']) ? $params['auth_data'] : json_decode(htmlspecialchars_decode($params['auth_data']), true);
                $auth_data['nickname'] = isset($auth_data['nickName']) ? $auth_data['nickName'] : '';
                $auth_data['avatar'] = isset($auth_data['avatarUrl']) ? $auth_data['avatarUrl'] : '';
                $auth_data['gender'] = empty($auth_data['gender']) ? 0 : (($auth_data['gender'] == 2) ? 1 : 2);

                // 公共参数处理
                $auth_data['weixin_unionid'] = isset($params['unionid']) ? $params['unionid'] : '';
                $auth_data['openid'] = $params['openid'];
                $auth_data['referrer']= isset($params['referrer']) ? $params['referrer'] : 0;
                $ret = UserService::AuthUserProgram($auth_data, 'weixin_openid');
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    $ret = DataReturn('授权成功', 0, $user);
                }
            }   
        } else {
            $ret = DataReturn($ret, -1);
        }
        return $ret;
    }

    /**
     * 百度小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BaiduUserAuth($params = [])
    {
        $config = [
            'appid'     => self::AppMiniConfig('common_app_mini_baidu_appid'),
            'key'       => self::AppMiniConfig('common_app_mini_baidu_appkey'),
            'secret'    => self::AppMiniConfig('common_app_mini_baidu_appsecret'),
        ];
        $ret = (new \base\Baidu($config))->GetAuthSessionKey($params);
        if($ret['code'] == 0)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'baidu_openid', $ret['data']['openid']);
            if(!empty($user))
            {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    // 标记用户存在
                    $user['is_user_exist'] = 1;
                    $ret = DataReturn('授权登录成功', 0, $user);
                }
            } else {
                $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$ret['data']['openid']]);
            }
        }
        return $ret;
    }

    /**
     * 百度小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BaiduUserInfo($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'auth_data',
                'error_msg'         => '授权数据为空',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'baidu_openid', $params['openid']);
            if(empty($user))
            {
                $auth_data = is_array($params['auth_data']) ? $params['auth_data'] : json_decode(htmlspecialchars_decode($params['auth_data']), true);
                // 加密数据
                $p = [
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'encrypted_data',
                        'error_msg'         => '解密数据为空',
                    ],
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'iv',
                        'error_msg'         => 'iv为空,请重试',
                    ]
                ];
                $ret = ParamsChecked($auth_data, $p);
                if($ret === true)
                {
                    $config = [
                        'appid'     => self::AppMiniConfig('common_app_mini_baidu_appid'),
                        'key'       => self::AppMiniConfig('common_app_mini_baidu_appkey'),
                        'secret'    => self::AppMiniConfig('common_app_mini_baidu_appsecret'),
                    ];
                    $ret = (new \base\Baidu($config))->DecryptData($auth_data['encrypted_data'], $auth_data['iv'], $params['openid']);

                    if($ret['code'] == 0 && !empty($ret['data']))
                    {
                        $data = [
                            'nickname'  => isset($ret['data']['nickname']) ? $ret['data']['nickname'] : '',
                            'avatar'    => isset($ret['data']['headimgurl']) ? $ret['data']['headimgurl'] : '',
                            'gender'    => empty($ret['data']['sex']) ? 0 : (($ret['data']['sex'] == 2) ? 1 : 2),
                            'openid'    => $ret['data']['openid'],
                            'referrer'  => isset($params['referrer']) ? $params['referrer'] : 0,
                        ];
                        $ret = UserService::AuthUserProgram($data, 'baidu_openid');
                    }
                } else {
                    $ret = DataReturn($ret, -1);
                }
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    $ret = DataReturn('授权成功', 0, $user);
                }
            }
        } else {
            $ret = DataReturn($ret, -1);
        }
        return $ret;
    }

    /**
     * 头条小程序用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ToutiaoUserAuth($params = [])
    {
        $config = [
            'appid'     => self::AppMiniConfig('common_app_mini_toutiao_appid'),
            'secret'    => self::AppMiniConfig('common_app_mini_toutiao_appsecret'),
        ];
        $ret = (new \base\Toutiao($config))->GetAuthSessionKey($params);
        if($ret['code'] == 0)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'toutiao_openid', $ret['data']['openid']);
            if(empty($user))
            {
                $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$ret['data']['openid']]);
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    // 标记用户存在
                    $user['is_user_exist'] = 1;
                    $ret = DataReturn('授权登录成功', 0, $user);
                }
            }
        }
        return $ret;
    }

    /**
     * 头条小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ToutiaoUserInfo($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'auth_data',
                'error_msg'         => '授权数据为空',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'toutiao_openid', $params['openid']);
            if(empty($user))
            {
                $auth_data = is_array($params['auth_data']) ? $params['auth_data'] : json_decode(htmlspecialchars_decode($params['auth_data']), true);
                $auth_data['nickname'] = isset($auth_data['nickName']) ? $auth_data['nickName'] : '';
                $auth_data['avatar'] = isset($auth_data['avatarUrl']) ? $auth_data['avatarUrl'] : '';
                $auth_data['gender'] = empty($auth_data['gender']) ? 0 : (($auth_data['gender'] == 2) ? 1 : 2);
                $auth_data['openid'] = $params['openid'];
                $auth_data['referrer']= isset($params['referrer']) ? $params['referrer'] : 0;
                $ret = UserService::AuthUserProgram($auth_data, 'toutiao_openid');
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    $ret = DataReturn('授权成功', 0, $user);
                }
            }
        } else {
            $ret = DataReturn($ret, -1);
        }
        return $ret;
    }

    /**
     * 快手小程序用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function KuaishouUserAuth($params = [])
    {
        $config = [
            'appid'     => self::AppMiniConfig('common_app_mini_kuaishou_appid'),
            'secret'    => self::AppMiniConfig('common_app_mini_kuaishou_appsecret'),
        ];
        $ret = (new \base\Kuaishou($config))->GetAuthSessionKey($params);
        if($ret['code'] == 0)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'toutiao_openid', $ret['data']['openid']);
            if(empty($user))
            {
                $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$ret['data']['openid']]);
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    // 标记用户存在
                    $user['is_user_exist'] = 1;
                    $ret = DataReturn('授权登录成功', 0, $user);
                }
            }
        }
        return $ret;
    }

    /**
     * 快手小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-27
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function KuaishouUserInfo($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'auth_data',
                'error_msg'         => '授权数据为空',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'kuaishou_openid', $params['openid']);
            if(empty($user))
            {
                $auth_data = is_array($params['auth_data']) ? $params['auth_data'] : json_decode(htmlspecialchars_decode($params['auth_data']), true);
                $auth_data['nickname'] = isset($auth_data['nickName']) ? $auth_data['nickName'] : '';
                $auth_data['avatar'] = isset($auth_data['avatarUrl']) ? $auth_data['avatarUrl'] : '';
                $auth_data['gender'] = 0;
                $auth_data['openid'] = $params['openid'];
                $auth_data['referrer']= isset($params['referrer']) ? $params['referrer'] : 0;
                $ret = UserService::AuthUserProgram($auth_data, 'kuaishou_openid');
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    $ret = DataReturn('授权成功', 0, $user);
                }
            }
        } else {
            $ret = DataReturn($ret, -1);
        }
        return $ret;
    }

    /**
     * QQ小程序获取用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-31
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function QQUserAuth($params = [])
    {
        // 参数
        if(!empty($params['authcode']))
        {
            // 授权
            $ret = (new \base\QQ(self::AppMiniConfig('common_app_mini_qq_appid'), self::AppMiniConfig('common_app_mini_qq_appsecret')))->GetAuthSessionKey($params['authcode']);
            if($ret['code'] == 0)
            {
                // 先从数据库获取用户信息
                $user = UserService::AppUserInfoHandle(null, 'qq_openid', $ret['data']['openid']);
                if(empty($user))
                {
                    $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$ret['data']['openid']]);
                } else {
                    // 用户状态
                    $ret = UserService::UserStatusCheck('id', $user['id']);
                    if($ret['code'] == 0)
                    {
                        // 标记用户存在
                        $user['is_user_exist'] = 1;
                        $ret = DataReturn('授权登录成功', 0, $user);
                    }
                }
            }
        } else {
            $ret = DataReturn('授权码为空', -1);
        }
        return $ret;
    }

    /**
     * QQ小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-31
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function QQUserInfo($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'auth_data',
                'error_msg'         => '授权数据为空',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'qq_openid', $params['openid']);
            if(empty($user))
            {
                $auth_data = is_array($params['auth_data']) ? $params['auth_data'] : json_decode(htmlspecialchars_decode($params['auth_data']), true);
                // 加密数据
                $p = [
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'encrypted_data',
                        'error_msg'         => '解密数据为空',
                    ],
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'iv',
                        'error_msg'         => 'iv为空,请重试',
                    ]
                ];
                $ret = ParamsChecked($auth_data, $p);
                if($ret === true)
                {
                    $ret = (new \base\QQ(self::AppMiniConfig('common_app_mini_qq_appid'), self::AppMiniConfig('common_app_mini_qq_appsecret')))->DecryptData($auth_data['encrypted_data'], $auth_data['iv'], $params['openid']);
                    if($ret['code'] == 0 && !empty($ret['data']))
                    {
                        $data = [
                            'nickname'  => isset($ret['data']['nickName']) ? $ret['data']['nickName'] : '',
                            'avatar'    => isset($ret['data']['avatarUrl']) ? $ret['data']['avatarUrl'] : '',
                            'gender'    => empty($ret['data']['gender']) ? 0 : (($ret['data']['gender'] == 2) ? 1 : 2),
                            'qq_unionid'=> isset($ret['data']['unionId']) ? $ret['data']['unionId'] : '',
                            'openid'    => $ret['data']['openId'],
                            'referrer'  => isset($params['referrer']) ? $params['referrer'] : 0,
                        ];
                        $ret = UserService::AuthUserProgram($data, 'qq_openid');
                    }
                } else {
                    $ret = DataReturn($ret, -1);
                }
            } else {
                // 用户状态
                $ret = UserService::UserStatusCheck('id', $user['id']);
                if($ret['code'] == 0)
                {
                    $ret = DataReturn('授权成功', 0, $user);
                }
            }   
        } else {
            $ret = DataReturn($ret, -1);
        }
        return $ret;
    }

    /**
     * 小程序用户手机一键绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-14
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AppMiniOnekeyUserMobileBind($params = [])
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 根据不同平台处理数据解密逻辑
        $mobile = '';
        switch(APPLICATION_CLIENT_TYPE)
        {
            // 微信
            case 'weixin' :
                // 参数校验
                $p = [
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'code',
                        'error_msg'         => '临时code为空',
                    ],
                ];
                $ret = ParamsChecked($params, $p);
                if($ret !== true)
                {
                    return DataReturn($ret, -1);
                }

                // 使用code换取手机号码
                $ret = (new \base\Wechat(self::AppMiniConfig('common_app_mini_weixin_appid'), self::AppMiniConfig('common_app_mini_weixin_appsecret')))->GetUserPhoneNumber($params['code']);
                if($ret['code'] == 0)
                {
                    $mobile = $ret['data'];
                } else {
                    return $ret;
                }
                break;

            // 百度
            case 'baidu' :
                // 参数校验
                $p = [
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'encrypted_data',
                        'error_msg'         => '解密数据为空',
                    ],
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'iv',
                        'error_msg'         => 'iv为空,请重试',
                    ]
                ];
                $ret = ParamsChecked($params, $p);
                if($ret !== true)
                {
                    return DataReturn($ret, -1);
                }

                // 数据解密
                $config = [
                    'appid'     => self::AppMiniConfig('common_app_mini_baidu_appid'),
                    'key'       => self::AppMiniConfig('common_app_mini_baidu_appkey'),
                    'secret'    => self::AppMiniConfig('common_app_mini_baidu_appsecret'),
                ];
                $ret = (new \base\Baidu($config))->DecryptData($params['encrypted_data'], $params['iv'], $params['openid'], 'mobile_bind');
                if($ret['code'] == 0 && !empty($ret['data']) && !empty($ret['data']['mobile']))
                {
                    $mobile = $ret['data']['mobile'];
                } else {
                    return $ret;
                }
                break;

            // 默认
            default :
                return DataReturn(APPLICATION_CLIENT_TYPE.'平台还未开发手机一键登录', -1);
        }
        if(empty($mobile))
        {
            return DataReturn('手机号码为空', -1);
        }

        // 用户信息处理
        $params['mobile'] = $mobile;
        $params['is_onekey_mobile_bind'] = 1;
        return UserService::AuthUserProgram($params, APPLICATION_CLIENT_TYPE.'_openid');
    }
}
?>