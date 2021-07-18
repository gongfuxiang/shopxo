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

use app\service\OrderService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\BuyService;
use app\service\SeoService;
use app\service\MessageService;
use app\service\NavigationService;
use app\service\GoodsBrowseService;
use app\service\GoodsFavorService;

/**
 * 用户
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class User extends Common
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
     * 获取上一个页面地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    private function GetrefererUrl()
    {
        // 上一个页面, 空则用户中心
        $referer_url = empty($_SERVER['HTTP_REFERER']) ? MyUrl('index/user/index') : htmlentities($_SERVER['HTTP_REFERER']);
        if(!empty($_SERVER['HTTP_REFERER']))
        {
            // 是否是指定页面，则赋值用户中心
            $all = ['login', 'regster', 'forget', 'logininfo', 'reginfo', 'smsreginfo', 'emailreginfo', 'forgetpwdinfo'];
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
     * 用户中心
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function Index()
    {
        // 登录校验
        $this->IsLogin();

        // 用户中心基础信息 mini 导航
        $mini_navigation = NavigationService::UserCenterMiniNavigation(['user'=>$this->user]);
        MyViewAssign('mini_navigation', $mini_navigation);

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1, 'is_aftersale'=>1]);
        MyViewAssign('user_order_status', $user_order_status['data']);

        // 未读消息总数
        $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0, 'user_type'=>'user'];
        $common_message_total = MessageService::UserMessageTotal($params);
        MyViewAssign('common_message_total', $common_message_total);

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
        MyViewAssign('order_list', $order['data']);

        // 获取购物车
        $cart_list = BuyService::CartList(['user'=>$this->user]);
        MyViewAssign('cart_list', $cart_list['data']);

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
        MyViewAssign('goods_favor_list', $favor['data']);

        // 我的足迹
        $browse_params = array(
            'm'         => 0,
            'n'         => 6,
            'where'     => [
                ['g.is_delete_time', '=', 0],
                ['b.user_id', '=', $this->user['id']],
            ],
        );
        $data = GoodsBrowseService::GoodsBrowseList($browse_params);
        MyViewAssign('goods_browse_list', $data['data']);

        // 订单页面订单状态form key
        MyViewAssign('form_search_order_status_form_key', 'status');
        MyViewAssign('form_search_order_user_is_comments_form_key', 'user_is_comments');

        // 钩子
        $this->PluginsHook();

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('用户中心', 1));

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
        foreach($hook_arr as $hook_name)
        {
            MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
                [
                    'hook_name'     => $hook_name,
                    'is_backend'    => false,
                    'user'          => $this->user,
                ]));
        }
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
            // 浏览器名称
            MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('密码找回', 1));

            // 左侧图片,随机其中一个
            $left_data = UserService::UserEntranceLeftData(['left_key'=>'forgetpwd', 'cache_key'=>MyConfig('shopxo.cache_user_forgetpwd_left_key')]);
            MyViewAssign('user_forgetpwd_left_data', empty($left_data['data']) ? [] : $left_data['data'][array_rand($left_data['data'], 1)]);

            return MyView();
        } else {
            MyViewAssign('msg', '已经登录了，如要重置密码，请先退出当前账户');
            return MyView('public/tips_error');
        }
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
                // 浏览器名称
                MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('用户注册', 1));

                // 返回地址
                MyViewAssign('referer_url', $this->GetrefererUrl());

                // 注册背景图片
                MyViewAssign('user_register_bg_images', MyC('home_site_user_register_bg_images'));

                return MyView();
            } else {
                MyViewAssign('msg', '已经登录了，如要注册新账户，请先退出当前账户');
                return MyView('public/tips_error');
            }
        } else {
            MyViewAssign('msg', '暂时关闭用户注册');
            return MyView('public/tips_error');
        }
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
                // 浏览器名称
                MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('用户登录', 1));

                // 返回地址
                MyViewAssign('referer_url', $this->GetrefererUrl());

                // 左侧图片,随机其中一个
                $left_data = UserService::UserEntranceLeftData(['left_key'=>'login', 'cache_key'=>MyConfig('shopxo.cache_user_login_left_key')]);
                MyViewAssign('user_login_left_data', empty($left_data['data']) ? [] : $left_data['data'][array_rand($left_data['data'], 1)]);

                return MyView();
            } else {
                MyViewAssign('msg', '已经登录了，请勿重复登录');
                return MyView('public/tips_error');
            }
        } else {
            MyViewAssign('msg', '暂时关闭用户登录');
            return MyView('public/tips_error');
        }
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
        MyViewAssign('is_header', 0);
        MyViewAssign('is_footer', 0);

        if(count(MyC('home_user_login_type', [], true)) > 0)
        {
            if(empty($this->user))
            {
                // 浏览器名称
                MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('用户登录', 1));
                
                MyViewAssign('referer_url', $this->GetrefererUrl());
                return MyView();
            } else {
                MyViewAssign('msg', '已经登录了，请勿重复登录');
                return MyView('public/tips_error');
            }
        } else {
            MyViewAssign('msg', '暂时关闭用户登录');
            return MyView('public/tips_error');
        }
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::Reg($this->data_post);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::Login($this->data_post);
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
     * 用户登录-验证码发送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-04
     * @desc    description
     */
    public function LoginVerifySend()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::LoginVerifySend($this->data_post);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::RegVerifySend($this->data_post);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::ForgetPwdVerifySend($this->data_post);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::ForgetPwd($this->data_post);
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

        // 登录返回
        $body_html = (!empty($ret['data']['body_html']) && is_array($ret['data']['body_html'])) ? implode(' ', $ret['data']['body_html']) : $ret['data']['body_html'];
        MyViewAssign('body_html', $body_html);
        MyViewAssign('msg', $ret['msg']);

        return MyView();
    }

    /**
     * 用户头像上传
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     */
    public function UserAvatarUpload()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 登录校验
        $this->IsLogin();

        $params = $_POST;
        $params['user'] = $this->user;
        $params['img_field'] = 'file';
        return UserService::UserAvatarUpload($params);
    }
}
?>