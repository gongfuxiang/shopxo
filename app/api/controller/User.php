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
use app\service\AppMiniUserService;
use app\service\GoodsCartService;

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
     * 构造方法
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
        return ApiService::ApiDataReturn(UserService::Login($this->data_request));
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
        return ApiService::ApiDataReturn(UserService::LoginVerifySend($this->data_request));
    }

    /**
     * 用户基础信息手机
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function Reg()
    {
        return ApiService::ApiDataReturn(UserService::Reg($this->data_request));
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
        return ApiService::ApiDataReturn(UserService::RegVerifySend($this->data_request));
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
        return ApiService::ApiDataReturn(UserService::ForgetPwd($this->data_request));
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
        return ApiService::ApiDataReturn(UserService::ForgetPwdVerifySend($this->data_request));
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
        return ApiService::ApiDataReturn(UserService::AppMobileBind($this->data_request));
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
        return ApiService::ApiDataReturn(UserService::AppMobileBindVerifySend($this->data_request));
    }

    /**
     * app用户邮箱绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function AppEmailBind()
    {
        return ApiService::ApiDataReturn(UserService::AppEmailBind($this->data_request));
    }

    /**
     * app用户邮箱绑定-验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function AppEmailBindVerifySend()
    {
        return ApiService::ApiDataReturn(UserService::AppEmailBindVerifySend($this->data_request));
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
                'expire_time'   => MyC('common_verify_expire_time'),
            ];
        $verify = new \base\Verify($params);
        $verify->Entry();
    }
    
    /**
     * 根据token获取用户信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-15
     * @desc    description
     */
    public function TokenUserinfo()
    {
        return ApiService::ApiDataReturn(UserService::TokenUserinfo($this->data_request));
    }

    /**
     * 小程序用户授权
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-15
     * @desc    description
     */
    public function AppMiniUserAuth()
    {
        $module = '\app\service\AppMiniUserService';
        $action = ucfirst(APPLICATION_CLIENT_TYPE).'UserAuth';
        if(method_exists($module, $action))
        {
            $ret = AppMiniUserService::$action($this->data_request);
        } else {
            $ret = DataReturn('方法未定义['.$action.']', -1);
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 小程序用户信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-15
     * @desc    description
     */
    public function AppMiniUserInfo()
    {
        $module = '\app\service\AppMiniUserService';
        $action = ucfirst(APPLICATION_CLIENT_TYPE).'UserInfo';
        if(method_exists($module, $action))
        {
            $ret = AppMiniUserService::$action($this->data_request);
        } else {
            $ret = DataReturn('方法未定义['.$action.']', -1);
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 用户中心
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T15:21:52+0800
     */
    public function Center()
    {
        if(empty($this->user))
        {
            $user_order_count = 0;
            $user_goods_favor_count = 0;
            $user_goods_browse_count = 0;
            $message_total = 0;
            $user_integral = 0;
            $cart_total = [];
        } else {
            // 订单总数
            $where = [
                ['user_id', '=', $this->user['id']],
                ['is_delete_time', '=', 0],
                ['user_is_delete_time', '=', 0],
            ];
            $user_order_count = OrderService::OrderTotal($where);

            // 商品收藏总数
            $where = [
                ['user_id', '=', $this->user['id']],
            ];
            $user_goods_favor_count = GoodsFavorService::GoodsFavorTotal($where);

            // 商品浏览总数
            $where = [
                ['user_id', '=', $this->user['id']],
            ];
            $user_goods_browse_count = GoodsBrowseService::GoodsBrowseTotal($where);

            // 未读消息总数
            $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0];
            $message_total = MessageService::UserMessageTotal($params);

            // 用户积分
            $integral = IntegralService::UserIntegral($params['user']['id']);
            $user_integral = (!empty($integral) && !empty($integral['integral'])) ? $integral['integral'] : 0;
        }

        // 用户订单状态
        $uos_res = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>empty($this->user) ? '' : $this->user, 'is_comments'=>1, 'is_aftersale'=>1]);
        $user_order_status = $uos_res['data'];

        // 购物车计数数据
        $cart_total = GoodsCartService::UserGoodsCartTotal(empty($this->user) ? [] : ['user'=>$this->user]);

        // 初始化数据
        $result = [
            'integral'                  => $user_integral,
            'avatar'                    => empty($this->user['avatar']) ? '' : $this->user['avatar'],
            'nickname'                  => empty($this->user['nickname']) ? '' : $this->user['nickname'],
            'username'                  => empty($this->user['username']) ? '' : $this->user['username'],
            'user_name_view'            => empty($this->user['user_name_view']) ? '' : $this->user['user_name_view'],
            'user_order_status'         => $user_order_status,
            'user_order_count'          => $user_order_count,
            'user_goods_favor_count'    => $user_goods_favor_count,
            'user_goods_browse_count'   => $user_goods_browse_count,
            'message_total'             => $message_total,
            'navigation'                => AppCenterNavService::AppCenterNav(),
            'cart_total'                => $cart_total,
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }

    /**
     * 小程序用户手机并一键绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-20
     * @desc    description
     */
    public function OnekeyUserMobileBind()
    {
        return ApiService::ApiDataReturn(AppMiniUserService::AppMiniOnekeyUserMobileBind($this->data_request));
    }

    /**
     * 小程序用户手机解密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-20
     * @desc    description
     */
    public function OnekeyUserMobileDecrypt()
    {
        return ApiService::ApiDataReturn(AppMiniUserService::AppMiniOnekeyUserMobileDecrypt($this->data_request));
    }

    /**
     * 小程序用户基础信息注册
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-20
     * @desc    description
     */
    public function UserBaseReg()
    {
        return ApiService::ApiDataReturn(AppMiniUserService::AppMiniUserBaseReg($this->data_request));
    }
}
?>