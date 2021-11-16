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
            $result = (new \base\Alipay())->GetAuthSessionKey(MyC('common_app_mini_alipay_appid'), $params['authcode']);
            if($result['status'] == 0)
            {
                // 先从数据库获取用户信息
                $user = UserService::AppUserInfoHandle(null, 'alipay_openid', $result['data']['user_id']);
                if(empty($user))
                {
                    $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result['data']['user_id']]);
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
            } else {
                $ret = DataReturn($result['msg'], -100);
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
        $result = (new \base\Wechat(MyC('common_app_mini_weixin_appid'), MyC('common_app_mini_weixin_appsecret')))->GetAuthSessionKey($params);
        if($result['status'] == 0)
        {
            // unionid
            $unionid = empty($result['data']['unionid']) ? '' : $result['data']['unionid'];

            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'weixin_openid', $result['data']['openid']);
            if(empty($user) && !empty($unionid))
            {
                // 根据unionid获取数据
                $user = UserService::AppUserInfoHandle(null, 'weixin_unionid', $unionid);
            }
            if(empty($user))
            {
                $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result['data']['openid'], 'unionid'=>$unionid]);
            } else {
                // 如果用户openid为空则绑定到用户下面
                if(empty($user['weixin_openid']))
                {
                    if(UserService::UserOpenidBind($user['id'], $result['data']['openid'], 'weixin_openid'))
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
        } else {
            $ret = DataReturn($result['msg'], -10);
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
            'appid'     => MyC('common_app_mini_baidu_appid'),
            'key'       => MyC('common_app_mini_baidu_appkey'),
            'secret'    => MyC('common_app_mini_baidu_appsecret'),
        ];
        $result = (new \base\Baidu($config))->GetAuthSessionKey($params);
        if($result['status'] == 0)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'baidu_openid', $result);
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
                $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result['data']]);
            }
        } else {
            $ret = DataReturn($result['msg'], -10);
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
                        'appid'     => MyC('common_app_mini_baidu_appid'),
                        'key'       => MyC('common_app_mini_baidu_appkey'),
                        'secret'    => MyC('common_app_mini_baidu_appsecret'),
                    ];
                    $result = (new \base\Baidu($config))->DecryptData($auth_data['encrypted_data'], $auth_data['iv'], $params['openid']);

                    if($result['status'] == 0 && !empty($result['data']))
                    {
                        $result['nickname'] = isset($result['data']['nickname']) ? $result['data']['nickname'] : '';
                        $result['avatar'] = isset($result['data']['headimgurl']) ? $result['data']['headimgurl'] : '';
                        $result['gender'] = empty($result['data']['sex']) ? 0 : (($result['data']['sex'] == 2) ? 1 : 2);
                        $result['openid'] = $result['data']['openid'];
                        $result['referrer']= isset($params['referrer']) ? $params['referrer'] : 0;
                        $ret = UserService::AuthUserProgram($result, 'baidu_openid');
                    } else {
                        $ret = DataReturn($result['msg'], -1);
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
            'appid'     => MyC('common_app_mini_toutiao_appid'),
            'secret'    => MyC('common_app_mini_toutiao_appsecret'),
        ];
        $result = (new \base\Toutiao($config))->GetAuthSessionKey($params);
        if($result['status'] == 0)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'toutiao_openid', $result);
            if(empty($user))
            {
                $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result['data']]);
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
        } else {
            $ret = DataReturn($result['msg'], -10);
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
            $result = (new \base\QQ(MyC('common_app_mini_qq_appid'), MyC('common_app_mini_qq_appsecret')))->GetAuthSessionKey($params['authcode']);
            if($result !== false)
            {
                // 先从数据库获取用户信息
                $user = UserService::AppUserInfoHandle(null, 'qq_openid', $result);
                if(empty($user))
                {
                    $ret = DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result]);
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
            } else {
                $ret = DataReturn('授权登录失败', -100);
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
                    $result = (new \base\QQ(MyC('common_app_mini_qq_appid'), MyC('common_app_mini_qq_appsecret')))->DecryptData($auth_data['encrypted_data'], $auth_data['iv'], $params['openid']);
                    if(is_array($result))
                    {
                        $result['nickname'] = isset($result['nickName']) ? $result['nickName'] : '';
                        $result['avatar'] = isset($result['avatarUrl']) ? $result['avatarUrl'] : '';
                        $result['gender'] = empty($result['gender']) ? 0 : (($result['gender'] == 2) ? 1 : 2);
                        $result['qq_unionid'] = isset($result['unionId']) ? $result['unionId'] : '';
                        $result['openid'] = $result['openId'];
                        $result['referrer']= isset($params['referrer']) ? $params['referrer'] : 0;
                        $ret = UserService::AuthUserProgram($result, 'qq_openid');
                    } else {
                        $ret = DataReturn(empty($result) ? '获取用户信息失败' : $result, -1);
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
}
?>