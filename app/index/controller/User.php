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
namespace app\index\controller;

use app\index\controller\Center;
use app\service\ApiService;
use app\service\SystemService;
use app\service\OrderService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\SeoService;
use app\service\MessageService;
use app\service\NavigationService;
use app\service\GoodsBrowseService;
use app\service\GoodsFavorService;
use app\service\GoodsCartService;

/**
 * 用户
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class User extends Center
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 用户中心
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 订单页面订单状态form key
            'form_search_order_status_form_key'             => 'status',
            'form_search_order_user_is_comments_form_key'   => 'user_is_comments',
            // 浏览器名称
            'home_seo_site_title'                           => SeoService::BrowserSeoTitle(MyLang('user.base_nav_title'), 1),
        ];

        // 用户中心基础信息 mini 导航
        $assign['mini_navigation'] = NavigationService::UserCenterMiniNavigationData(['user'=>$this->user]);

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1, 'is_aftersale'=>1]);
        $assign['user_order_status'] = $user_order_status['data'];

        // 未读消息总数
        $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0, 'user_type'=>'user'];
        $assign['common_message_total'] = MessageService::UserMessageTotal($params);

        // 获取进行中的订单列表
        $params = $this->data_request;
        $params['user'] = $this->user;
        $params['is_more'] = 1;
        $params['status'] = [1,2,3,4];
        $params['is_comments'] = 0;
        $params['user_type'] = 'user';
        $where = OrderService::OrderListWhere($params);
        $order_params = array(
            'm'         => 0,
            'n'         => 3,
            'where'     => $where,
        );
        $order = OrderService::OrderList($order_params);
        $assign['order_list'] = $order['data'];

        // 获取购物车
        $cart_list = GoodsCartService::GoodsCartList(['user'=>$this->user]);
        $assign['cart_list'] = $cart_list['data'];

        // 收藏商品
        $favor_params = array(
            'm'         => 0,
            'n'         => 6,
            'where'     => [
                ['g.is_delete_time', '=', 0],
                ['f.user_id', '=', $this->user['id']],
            ],
        );
        $favor = GoodsFavorService::GoodsFavorList($favor_params);
        $assign['goods_favor_list'] = $favor['data'];

        // 我的足迹
        $browse_params = array(
            'm'         => 0,
            'n'         => 6,
            'where'     => [
                ['g.is_delete_time', '=', 0],
                ['b.user_id', '=', $this->user['id']],
            ],
        );
        $browse = GoodsBrowseService::GoodsBrowseList($browse_params);
        $assign['goods_browse_list'] = $browse['data'];

        // 数据赋值
        MyViewAssign($assign);
        // 钩子
        $this->PluginsHook();
        return MyView();
    }

    /**
     * 钩子处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     */
    private function PluginsHook()
    {
        $hook_arr = [
            // 顶部钩子
            'plugins_view_user_center_top',

            // 基础信息底部钩子
            'plugins_view_user_base_bottom',

            // 聚合内容顶部钩子
            'plugins_view_user_various_top',

            // 聚合内容底部钩子
            'plugins_view_user_various_bottom',

            // 聚合内容里面顶部钩子
            'plugins_view_user_various_inside_top',

            // 聚合内容里面底部钩子
            'plugins_view_user_various_inside_bottom',
        ];
        $assign = [];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
                [
                    'hook_name'     => $hook_name,
                    'is_backend'    => false,
                    'user'          => $this->user,
                ]);
        }
        MyViewAssign($assign);
    }

    /**
     * 密码找回
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function ForgetPwdInfo()
    {
        if(empty($this->user))
        {
            // 左侧图片
            $left_data = UserService::UserEntranceLeftData(['left_key'=>'forgetpwd', 'cache_key'=>SystemService::CacheKey('shopxo.cache_user_forgetpwd_left_key')]);

            // 模板数据
            $assign = [
                // 左侧图片、随机其中一个
                'user_forgetpwd_left_data'  => empty($left_data['data']) ? [] : $left_data['data'][array_rand($left_data['data'], 1)],
                // 浏览器名称
                'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('user.forget_password_base_nav_title'), 1),
            ];
            MyViewAssign($assign);
            return MyView();
        }
        MyViewAssign('msg', MyLang('user.password_reset_illegal_error_tips'));
        return MyView('public/tips_error');
    }

    /**
     * 用户注册页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function RegInfo()
    {
        $reg_all = MyC('home_user_reg_type');
        if(!empty($reg_all))
        {
            if(empty($this->user))
            {
                // 模板数据
                $assign = [
                    // 返回地址
                    'referer_url'               => UserService::UserLoginOrRegBackRefererUrl(),
                    // 注册背景图片
                    'user_register_bg_images'   => MyC('home_site_user_register_bg_images'),
                    // 注册背景色
                    'user_register_bg_color'    => MyC('home_site_user_register_bg_color'),
                    // 浏览器名称
                    'home_seo_site_title'       => SeoService::BrowserSeoTitle(MyLang('user.user_register_base_nav_title'), 1),
                ];
                MyViewAssign($assign);
                return MyView();
            }
            MyViewAssign('msg', MyLang('user.register_illegal_error_tips'));
            return MyView('public/tips_error');
        }
        MyViewAssign('msg', MyLang('common.close_user_register_tips'));
        return MyView('public/tips_error');
    }

    /**
     * 用户登录页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function LoginInfo()
    {
        if(count(MyC('home_user_login_type', [], true)) > 0)
        {
            if(empty($this->user))
            {
                // 左侧图片
                $left_data = UserService::UserEntranceLeftData(['left_key'=>'login', 'cache_key'=>SystemService::CacheKey('shopxo.cache_user_login_left_key')]);
                
                // 模板数据
                $assign = [
                    // 返回地址
                    'referer_url'               => UserService::UserLoginOrRegBackRefererUrl(),
                    // 注册背景图片
                    'user_login_left_data'      => empty($left_data['data']) ? [] : $left_data['data'][array_rand($left_data['data'], 1)],
                    // 浏览器名称
                    'home_seo_site_title'       => SeoService::BrowserSeoTitle(MyLang('user.user_login_base_nav_title'), 1),
                ];
                MyViewAssign($assign);
                return MyView();
            }
            MyViewAssign('msg', MyLang('user.login_illegal_error_tips'));
            return MyView('public/tips_error');
        }
        MyViewAssign('msg', MyLang('common.close_user_login_tips'));
        return MyView('public/tips_error');
    }

    /**
     * modal弹窗登录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function ModalLoginInfo()
    {
        // 模板数据
        $assign = [
            'is_header'     => 0,
            'is_footer'     => 0,
            'is_to_home'    => 0,
        ];
        if(count(MyC('home_user_login_type', [], true)) > 0)
        {
            if(empty($this->user))
            {
                // 浏览器名称
                $assign['home_seo_site_title'] = SeoService::BrowserSeoTitle(MyLang('user.user_login_base_nav_title'), 1);

                // 返回地址
                $assign['referer_url'] = UserService::UserLoginOrRegBackRefererUrl();
                MyViewAssign($assign);
                return MyView();
            }
            $assign['msg'] = MyLang('user.login_illegal_error_tips');
            MyViewAssign($assign);
            return MyView('public/tips_error');
        }
        $assign['msg'] = MyLang('common.close_user_login_tips');
        MyViewAssign($assign);
        return MyView('public/tips_error');
    }

    /**
     * 用户注册-数据添加
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
            'width'        => empty($this->data_request['width']) ? 100 : intval($this->data_request['width']),
            'height'       => empty($this->data_request['height']) ? 24 : intval($this->data_request['height']),
            'key_prefix'   => empty($this->data_request['type']) ? 'user_reg' : $this->data_request['type'],
            'expire_time'  => MyC('common_verify_expire_time'),
        ];
        $verify = new \base\Verify($params);
        $verify->Entry();
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
     * 退出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function Logout()
    {
        // 调用服务层
        $ret = UserService::Logout();

        // 退出返回
        $body_html = (!empty($ret['data']['body_html']) && is_array($ret['data']['body_html'])) ? implode(' ', $ret['data']['body_html']) : $ret['data']['body_html'];
        MyViewAssign([
            'body_html' => $body_html,
            'msg'       => $ret['msg'],
        ]);
        return MyView();
    }
}
?>