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
namespace app\api\controller;

use app\service\ApiService;
use app\service\SystemBaseService;
use app\service\UserService;
use app\service\OrderService;
use app\service\GoodsService;
use app\service\MessageService;
use app\service\AppCenterNavService;
use app\service\BuyService;
use app\service\GoodsFavorService;
use app\service\GoodsBrowseService;
use app\service\IntegralService;

/**
 * 用户
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class User extends Common
{
    /**
     * [__construct 构造方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

    /**
     * 用户登录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function Login()
    {
        return ApiService::ApiDataReturn(UserService::Login($this->data_post));
    }

    /**
     * 用户登录-验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function LoginVerifySend()
    {
        return ApiService::ApiDataReturn(UserService::LoginVerifySend($this->data_post));
    }

    /**
     * 用户注册
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function Reg()
    {
        return ApiService::ApiDataReturn(UserService::Reg($this->data_post));
    }

    /**
     * 用户注册-验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function RegVerifySend()
    {
        return ApiService::ApiDataReturn(UserService::RegVerifySend($this->data_post));
    }

    /**
     * 密码找回
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function ForgetPwd()
    {
        return ApiService::ApiDataReturn(UserService::ForgetPwd($this->data_post));
    }

    /**
     * 密码找回-验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function ForgetPwdVerifySend()
    {
        return ApiService::ApiDataReturn(UserService::ForgetPwdVerifySend($this->data_post));
    }

    /**
     * 用户-验证码显示
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function UserVerifyEntry()
    {
        $params = [
                'width'         => 100,
                'height'        => 28,
                'key_prefix'    => input('type', 'user_reg'),
            ];
        $verify = new \base\Verify($params);
        $verify->Entry();
    }

    /**
     * app用户手机绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function AppMobileBind()
    {
        return ApiService::ApiDataReturn(UserService::AppMobileBind($this->data_post));
    }

    /**
     * app用户手机绑定-验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function AppMobileBindVerifySend()
    {
        return ApiService::ApiDataReturn(UserService::AppMobileBindVerifySend($this->data_post));
    }

    /**
     * 支付宝用户授权
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function AlipayUserAuth()
    {
        // 参数
        if(!empty($this->data_post['authcode']))
        {
            // 授权
            $result = (new \base\Alipay())->GetAuthSessionKey(MyC('common_app_mini_alipay_appid'), $this->data_post['authcode']);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 支付宝小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     */
    public function AlipayUserInfo()
    {
        // 参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'openid',
                'error_msg'         => 'openid为空',
            ],
        ];
        $ret = ParamsChecked($this->data_post, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'alipay_openid', $this->data_post['openid']);
            if(empty($user))
            {
                $this->data_post['nickname'] = isset($this->data_post['nickName']) ? $this->data_post['nickName'] : '';
                $this->data_post['gender'] = empty($this->data_post['gender']) ? 0 : (($this->data_post['gender'] == 'f') ? 1 : 2);
                $ret = UserService::AuthUserProgram($this->data_post, 'alipay_openid');
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 微信小程序获取用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     */
    public function WechatUserAuth()
    {
        // 授权
        $result = (new \base\Wechat(MyC('common_app_mini_weixin_appid'), MyC('common_app_mini_weixin_appsecret')))->GetAuthSessionKey($this->data_post);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 微信小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     */
    public function WechatUserInfo()
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
            ],
        ];
        $ret = ParamsChecked($this->data_post, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'weixin_openid', $this->data_post['openid']);
            if(empty($user))
            {
                // 字段名称不一样参数处理
                $auth_data = is_array($this->data_post['auth_data']) ? $this->data_post['auth_data'] : json_decode(htmlspecialchars_decode($this->data_post['auth_data']), true);
                $auth_data['nickname'] = isset($auth_data['nickName']) ? $auth_data['nickName'] : '';
                $auth_data['avatar'] = isset($auth_data['avatarUrl']) ? $auth_data['avatarUrl'] : '';
                $auth_data['gender'] = empty($auth_data['gender']) ? 0 : (($auth_data['gender'] == 2) ? 1 : 2);

                // 公共参数处理
                $auth_data['weixin_unionid'] = isset($this->data_post['unionid']) ? $this->data_post['unionid'] : '';
                $auth_data['openid'] = $this->data_post['openid'];
                $auth_data['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 百度小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     */
    public function BaiduUserAuth()
    {
        $config = [
            'appid'     => MyC('common_app_mini_baidu_appid'),
            'key'       => MyC('common_app_mini_baidu_appkey'),
            'secret'    => MyC('common_app_mini_baidu_appsecret'),
        ];
        $result = (new \base\Baidu($config))->GetAuthSessionKey($this->data_post);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 百度小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     */
    public function BaiduUserInfo()
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
                'key_name'          => 'encrypted_data',
                'error_msg'         => '解密数据为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'iv',
                'error_msg'         => 'iv为空,请重试',
            ]
        ];
        $ret = ParamsChecked($this->data_post, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'baidu_openid', $this->data_post['openid']);
            if(empty($user))
            {
                $config = [
                    'appid'     => MyC('common_app_mini_baidu_appid'),
                    'key'       => MyC('common_app_mini_baidu_appkey'),
                    'secret'    => MyC('common_app_mini_baidu_appsecret'),
                ];
                $result = (new \base\Baidu($config))->DecryptData($this->data_post['encrypted_data'], $this->data_post['iv'], $this->data_post['openid']);

                if($result['status'] == 0 && !empty($result['data']))
                {
                    $result['nickname'] = isset($result['data']['nickname']) ? $result['data']['nickname'] : '';
                    $result['avatar'] = isset($result['data']['headimgurl']) ? $result['data']['headimgurl'] : '';
                    $result['gender'] = empty($result['data']['sex']) ? 0 : (($result['data']['sex'] == 2) ? 1 : 2);
                    $result['openid'] = $result['data']['openid'];
                    $result['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
                    $ret = UserService::AuthUserProgram($result, 'baidu_openid');
                } else {
                    $ret = DataReturn($result['msg'], -1);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 头条小程序用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-27
     * @desc    description
     */
    public function ToutiaoUserAuth()
    {
        $config = [
            'appid'     => MyC('common_app_mini_toutiao_appid'),
            'secret'    => MyC('common_app_mini_toutiao_appsecret'),
        ];
        $result = (new \base\Toutiao($config))->GetAuthSessionKey($this->data_post);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 头条小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-27
     * @desc    description
     */
    public function ToutiaoUserInfo()
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
                'key_name'          => 'userinfo',
                'error_msg'         => '用户信息为空',
            ],
        ];
        $ret = ParamsChecked($this->data_post, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'toutiao_openid', $this->data_post['openid']);
            if(empty($user))
            {
                $result = json_decode(htmlspecialchars_decode($this->data_post['userinfo']), true);
                if(is_array($result))
                {
                    $result['nickname'] = isset($result['nickName']) ? $result['nickName'] : '';
                    $result['avatar'] = isset($result['avatarUrl']) ? $result['avatarUrl'] : '';
                    $result['gender'] = empty($result['gender']) ? 0 : (($result['gender'] == 2) ? 1 : 2);
                    $result['openid'] = $this->data_post['openid'];
                    $result['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
                    $ret = UserService::AuthUserProgram($result, 'toutiao_openid');
                } else {
                    $ret = DataReturn(empty($result) ? '获取用户信息失败' : $result, -1);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * QQ小程序获取用户授权
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-31
     * @desc    description
     */
    public function QQUserAuth()
    {
        // 参数
        if(!empty($this->data_post['authcode']))
        {
            // 授权
            $result = (new \base\QQ(MyC('common_app_mini_qq_appid'), MyC('common_app_mini_qq_appsecret')))->GetAuthSessionKey($this->data_post['authcode']);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * QQ小程序获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-31
     * @desc    description
     */
    public function QQUserInfo()
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
                'key_name'          => 'encrypted_data',
                'error_msg'         => '解密数据为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'iv',
                'error_msg'         => 'iv为空,请重试',
            ]
        ];
        $ret = ParamsChecked($this->data_post, $p);
        if($ret === true)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'qq_openid', $this->data_post['openid']);
            if(empty($user))
            {
                $result = (new \base\QQ(MyC('common_app_mini_qq_appid'), MyC('common_app_mini_qq_appsecret')))->DecryptData($this->data_post['encrypted_data'], $this->data_post['iv'], $this->data_post['openid']);
                if(is_array($result))
                {
                    $result['nickname'] = isset($result['nickName']) ? $result['nickName'] : '';
                    $result['avatar'] = isset($result['avatarUrl']) ? $result['avatarUrl'] : '';
                    $result['gender'] = empty($result['gender']) ? 0 : (($result['gender'] == 2) ? 1 : 2);
                    $result['qq_unionid'] = isset($result['unionId']) ? $result['unionId'] : '';
                    $result['openid'] = $result['openId'];
                    $result['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
                    $ret = UserService::AuthUserProgram($result, 'qq_openid');
                } else {
                    $ret = DataReturn(empty($result) ? '获取用户信息失败' : $result, -1);
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
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * [ClientCenter 用户中心]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T15:21:52+0800
     */
    public function Center()
    {
        // 登录校验
        $this->IsLogin();

        // 订单总数
        $where = ['user_id'=>$this->user['id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $user_order_count = OrderService::OrderTotal($where);

        // 商品收藏总数
        $where = ['user_id'=>$this->user['id']];
        $user_goods_favor_count = GoodsFavorService::GoodsFavorTotal($where);

        // 商品浏览总数
        $where = ['user_id'=>$this->user['id']];
        $user_goods_browse_count = GoodsBrowseService::GoodsBrowseTotal($where);

        // 未读消息总数
        $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0];
        $common_message_total = MessageService::UserMessageTotal($params);

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1, 'is_aftersale'=>1]);

        // 用户积分
        $integral = IntegralService::UserIntegral($params['user']['id']);
        $user_integral = (!empty($integral) && !empty($integral['integral'])) ? $integral['integral'] : 0;

        // 初始化数据
        $result = array(
            'integral'                          => $user_integral,
            'avatar'                            => $this->user['avatar'],
            'nickname'                          => $this->user['nickname'],
            'username'                          => $this->user['username'],
            'user_name_view'                    => $this->user['user_name_view'],
            'user_order_status'                 => $user_order_status['data'],
            'user_order_count'                  => $user_order_count,
            'user_goods_favor_count'            => $user_goods_favor_count,
            'user_goods_browse_count'           => $user_goods_browse_count,
            'common_message_total'              => $common_message_total,
            'navigation'                        => AppCenterNavService::AppCenterNav(),
            'common_cart_total'                 => BuyService::UserCartTotal(['user'=>$this->user]),
        );

        // 返回数据
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }

    /**
     * 小程序用户手机一键绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-20
     * @desc    description
     */
    public function OnekeyUserMobileBind()
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
                'key_name'          => 'encrypted_data',
                'error_msg'         => '解密数据为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'iv',
                'error_msg'         => 'iv为空,请重试',
            ]
        ];
        $ret = ParamsChecked($this->data_post, $p);
        if($ret === true)
        {
            // 根据不同平台处理数据解密逻辑
            $mobile = '';
            $error_msg = '';
            switch(APPLICATION_CLIENT_TYPE)
            {
                // 微信
                case 'weixin' :
                    $result = (new \base\Wechat(MyC('common_app_mini_weixin_appid'), MyC('common_app_mini_weixin_appsecret')))->DecryptData($this->data_post['encrypted_data'], $this->data_post['iv'], $this->data_post['openid']);
                    if($result['status'] == 0 && !empty($result['data']) && !empty($result['data']['purePhoneNumber']))
                    {
                        $mobile = $result['data']['purePhoneNumber'];
                    } else {
                        $error_msg = $result['msg'];
                    }
                    break;

                // 百度
                case 'baidu' :
                    $config = [
                        'appid'     => MyC('common_app_mini_baidu_appid'),
                        'key'       => MyC('common_app_mini_baidu_appkey'),
                        'secret'    => MyC('common_app_mini_baidu_appsecret'),
                    ];
                    $result = (new \base\Baidu($config))->DecryptData($this->data_post['encrypted_data'], $this->data_post['iv'], $this->data_post['openid'], 'mobile_bind');
                    if($result['status'] == 0 && !empty($result['data']) && !empty($result['data']['mobile']))
                    {
                        $mobile = $result['data']['mobile'];
                    } else {
                        $error_msg = $result['msg'];
                    }
                    break;

                // 默认
                default :
                    $error_msg = APPLICATION_CLIENT_TYPE.'平台还未开发手机一键登录';
            }
            if(empty($mobile) || !empty($error_msg))
            {
                $ret = DataReturn(empty($error_msg) ? '数据解密失败' : $error_msg, -1);
            } else {
                // 用户信息处理
                $this->data_post['mobile'] = $mobile;
                $this->data_post['is_onekey_mobile_bind'] = 1;
                $ret = UserService::AuthUserProgram($this->data_post, APPLICATION_CLIENT_TYPE.'_openid');
            }
        } else {
            $ret = DataReturn($ret, -1);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>