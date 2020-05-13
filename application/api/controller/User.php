<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\service\UserService;
use app\service\OrderService;
use app\service\GoodsService;
use app\service\MessageService;
use app\service\AppCenterNavService;
use app\service\BuyService;

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
     * [Reg 用户注册-数据添加]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-07T00:08:36+0800
     */
    public function Reg()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::AppReg($this->data_post);
    }

    /**
     * [RegVerifySend 用户注册-验证码发送]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-05T19:17:10+0800
     */
    public function RegVerifySend()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::AppUserBindVerifySend($this->data_post);
    }

    /**
     * [GetAlipayUserInfo 支付宝用户授权]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-23T21:52:49+0800
     */
    public function AlipayUserAuth()
    {
        // 参数
        if(empty($this->data_post['authcode']))
        {
            return DataReturn('授权码为空', -1);
        }

        // 授权
        $result = (new \base\Alipay())->GetAuthSessionKey(MyC('common_app_mini_alipay_appid'), $this->data_post['authcode']);
        if($result['status'] == 0)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'alipay_openid', $result['data']['user_id']);
            if(empty($user))
            {
                return DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result['data']['user_id']]);
            }

            // 标记用户存在
            $user['is_user_exist'] = 1;
            return DataReturn('授权成功', 0, $user);
        }
        return DataReturn($result['msg'], -100);
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
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 先从数据库获取用户信息
        $user = UserService::AppUserInfoHandle(null, 'alipay_openid', $this->data_post['openid']);
        if(empty($user))
        {
            $this->data_post['nick_name'] = isset($this->data_post['nickName']) ? $this->data_post['nickName'] : '';
            $this->data_post['gender'] = empty($this->data_post['gender']) ? 0 : (($this->data_post['gender'] == 'f') ? 1 : 2);
            return UserService::AuthUserProgram($this->data_post, 'alipay_openid');
        } else {
            return DataReturn('授权成功', 0, $user);
        }
        return DataReturn('获取用户信息失败', -100);
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
        // 参数
        if(empty($this->data_post['authcode']))
        {
            return DataReturn('授权码为空', -1);
        }

        // 授权
        $result = (new \base\Wechat(MyC('common_app_mini_weixin_appid'), MyC('common_app_mini_weixin_appsecret')))->GetAuthSessionKey($this->data_post['authcode']);
        if($result !== false)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'weixin_openid', $result);
            if(empty($user))
            {
                return DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result]);
            }

            // 标记用户存在
            $user['is_user_exist'] = 1;
            return DataReturn('授权登录成功', 0, $user);
        }
        return DataReturn('授权登录失败', -100);
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
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 先从数据库获取用户信息
        $user = UserService::AppUserInfoHandle(null, 'weixin_openid', $this->data_post['openid']);
        if(empty($user))
        {
            $result = (new \base\Wechat(MyC('common_app_mini_weixin_appid'), MyC('common_app_mini_weixin_appsecret')))->DecryptData($this->data_post['encrypted_data'], $this->data_post['iv'], $this->data_post['openid']);

            if(is_array($result))
            {
                $result['nick_name'] = isset($result['nickName']) ? $result['nickName'] : '';
                $result['avatar'] = isset($result['avatarUrl']) ? $result['avatarUrl'] : '';
                $result['gender'] = empty($result['gender']) ? 0 : (($result['gender'] == 2) ? 1 : 2);
                $result['weixin_unionid'] = isset($result['unionId']) ? $result['unionId'] : '';
                $result['openid'] = $result['openId'];
                $result['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
                return UserService::AuthUserProgram($result, 'weixin_openid');
            }
        } else {
            return DataReturn('授权成功', 0, $user);
        }
        return DataReturn(empty($result) ? '获取用户信息失败' : $result, -100);
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
            if(empty($user))
            {
                return DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result['data']]);
            }

            // 标记用户存在
            $user['is_user_exist'] = 1;
            return DataReturn('授权登录成功', 0, $user);
        }
        return DataReturn($result['msg'], -10);
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
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

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
                $result['nick_name'] = isset($result['data']['nickname']) ? $result['data']['nickname'] : '';
                $result['avatar'] = isset($result['data']['headimgurl']) ? $result['data']['headimgurl'] : '';
                $result['gender'] = empty($result['data']['sex']) ? 0 : (($result['data']['sex'] == 2) ? 1 : 2);
                $result['openid'] = $result['data']['openid'];
                $result['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
                return UserService::AuthUserProgram($result, 'baidu_openid');
            } else {
                return DataReturn($result['msg'], -1);
            }
        } else {
            return DataReturn('授权成功', 0, $user);
        }
        return DataReturn(empty($result) ? '获取用户信息失败' : $result, -100);
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
                return DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result['data']]);
            }

            // 标记用户存在
            $user['is_user_exist'] = 1;
            return DataReturn('授权登录成功', 0, $user);
        }
        return DataReturn($result['msg'], -10);
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
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 先从数据库获取用户信息
        $user = UserService::AppUserInfoHandle(null, 'toutiao_openid', $this->data_post['openid']);
        if(empty($user))
        {
            $result = json_decode(htmlspecialchars_decode($this->data_post['userinfo']), true);
            if(is_array($result))
            {
                $result['nick_name'] = isset($result['nickName']) ? $result['nickName'] : '';
                $result['avatar'] = isset($result['avatarUrl']) ? $result['avatarUrl'] : '';
                $result['gender'] = empty($result['gender']) ? 0 : (($result['gender'] == 2) ? 1 : 2);
                $result['openid'] = $this->data_post['openid'];
                $result['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
                return UserService::AuthUserProgram($result, 'toutiao_openid');
            }
        } else {
            return DataReturn('授权成功', 0, $user);
        }
        return DataReturn(empty($result) ? '获取用户信息失败' : $result, -100);
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
        if(empty($this->data_post['authcode']))
        {
            return DataReturn('授权码为空', -1);
        }

        // 授权
        $result = (new \base\QQ(MyC('common_app_mini_qq_appid'), MyC('common_app_mini_qq_appsecret')))->GetAuthSessionKey($this->data_post['authcode']);
        if($result !== false)
        {
            // 先从数据库获取用户信息
            $user = UserService::AppUserInfoHandle(null, 'qq_openid', $result);
            if(empty($user))
            {
                return DataReturn('授权登录成功', 0, ['is_user_exist'=>0, 'openid'=>$result]);
            }

            // 标记用户存在
            $user['is_user_exist'] = 1;
            return DataReturn('授权登录成功', 0, $user);
        }
        return DataReturn('授权登录失败', -100);
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
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 先从数据库获取用户信息
        $user = UserService::AppUserInfoHandle(null, 'qq_openid', $this->data_post['openid']);
        if(empty($user))
        {
            $result = (new \base\QQ(MyC('common_app_mini_qq_appid'), MyC('common_app_mini_qq_appsecret')))->DecryptData($this->data_post['encrypted_data'], $this->data_post['iv'], $this->data_post['openid']);
            if(is_array($result))
            {
                $result['nick_name'] = isset($result['nickName']) ? $result['nickName'] : '';
                $result['avatar'] = isset($result['avatarUrl']) ? $result['avatarUrl'] : '';
                $result['gender'] = empty($result['gender']) ? 0 : (($result['gender'] == 2) ? 1 : 2);
                $result['qq_unionid'] = isset($result['unionId']) ? $result['unionId'] : '';
                $result['openid'] = $result['openId'];
                $result['referrer']= isset($this->data_post['referrer']) ? $this->data_post['referrer'] : 0;
                return UserService::AuthUserProgram($result, 'qq_openid');
            }
        } else {
            return DataReturn('授权成功', 0, $user);
        }
        return DataReturn(empty($result) ? '获取用户信息失败' : $result, -100);
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
        $user_goods_favor_count = GoodsService::GoodsFavorTotal($where);

        // 商品浏览总数
        $where = ['user_id'=>$this->user['id']];
        $user_goods_browse_count = GoodsService::GoodsBrowseTotal($where);

        // 未读消息总数
        $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0];
        $common_message_total = MessageService::UserMessageTotal($params);

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1, 'is_aftersale'=>1]);

        // 初始化数据
        $result = array(
            'integral'                          => (int) $this->user['integral'],
            'avatar'                            => $this->user['avatar'],
            'nickname'                          => $this->user['nickname'],
            'username'                          => $this->user['username'],
            'customer_service_tel'              => MyC('common_app_customer_service_tel', null, true),
            'common_user_center_notice'         => MyC('common_user_center_notice', null, true),
            'user_order_status'                 => $user_order_status['data'],
            'user_order_count'                  => $user_order_count,
            'user_goods_favor_count'            => $user_goods_favor_count,
            'user_goods_browse_count'           => $user_goods_browse_count,
            'common_message_total'              => $common_message_total,
            'navigation'                        => AppCenterNavService::AppCenterNav(),
            'common_app_is_online_service'      => (int) MyC('common_app_is_online_service', 0),
            'common_app_is_head_vice_nav'       => (int) MyC('common_app_is_head_vice_nav', 0),
            'common_cart_total'                 => BuyService::UserCartTotal(['user'=>$this->user]),
        );

        // 支付宝小程序在线客服
        if(APPLICATION_CLIENT_TYPE == 'alipay')
        {
            $result['common_app_mini_alipay_tnt_inst_id'] = MyC('common_app_mini_alipay_tnt_inst_id', null, true);
            $result['common_app_mini_alipay_scene'] = MyC('common_app_mini_alipay_scene', null, true);
        }

        // 返回数据
        return DataReturn('success', 0, $result);
    }
}
?>