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
namespace app\index\controller;

use think\facade\Hook;
use app\service\OrderService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\BuyService;
use app\service\SeoService;
use app\service\MessageService;
use app\service\NavigationService;

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
     * [GetrefererUrl 获取上一个页面地址]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T15:46:16+0800
     */
    private function GetrefererUrl()
    {
        // 上一个页面, 空则用户中心
        $referer_url = empty($_SERVER['HTTP_REFERER']) ? MyUrl('index/user/index') : $_SERVER['HTTP_REFERER'];
        if(!empty($_SERVER['HTTP_REFERER']))
        {
            $all = ['login', 'regster', 'forget', 'logininfo', 'reginfo', 'smsreginfo', 'emailreginfo', 'forgetpwdinfo'];
            foreach($all as $v)
            {
                if(strpos($_SERVER['HTTP_REFERER'], $v) !== false)
                {
                    $referer_url = MyUrl('index/user/index');
                    break;
                }
            }
        }
        return $referer_url;
    }

    /**
     * [Index 用户中心]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function Index()
    {
        // 登录校验
        $this->IsLogin();

        // 用户中心基础信息 mini 导航
        $mini_navigation = NavigationService::UserCenterMiniNavigation(['user'=>$this->user]);
        $this->assign('mini_navigation', $mini_navigation);

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1, 'is_aftersale'=>1]);
        $this->assign('user_order_status', $user_order_status['data']);

        // 未读消息总数
        $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0, 'user_type'=>'user'];
        $common_message_total = MessageService::UserMessageTotal($params);
        $this->assign('common_message_total', $common_message_total);

        // 获取进行中的订单列表
        $params = array_merge($_POST, $_GET);
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
        $this->assign('order_list', $order['data']);

        // 获取购物车
        $cart_list = BuyService::CartList(['user'=>$this->user]);
        $this->assign('cart_list', $cart_list['data']);

        // 收藏商品
        $params = array_merge($_POST, $_GET);
        $params['user'] = $this->user;
        $where = GoodsService::UserGoodsFavorListWhere($params);
        $favor_params = array(
            'm'         => 0,
            'n'         => 8,
            'where'     => $where,
        );
        $favor = GoodsService::GoodsFavorList($favor_params);
        $this->assign('goods_favor_list', $favor['data']);

        // 我的足迹
        $params = array_merge($_POST, $_GET);
        $params['user'] = $this->user;
        $where = GoodsService::UserGoodsBrowseListWhere($params);
        $browse_params = array(
            'm'         => 0,
            'n'         => 6,
            'where'     => $where,
        );
        $data = GoodsService::GoodsBrowseList($browse_params);
        $this->assign('goods_browse_list', $data['data']);

        // 钩子
        $this->PluginsHook();

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('用户中心', 1));

        return $this->fetch();
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
        // 顶部钩子
        $this->assign('plugins_view_user_center_top_data', Hook::listen('plugins_view_user_center_top', ['hook_name'=>'plugins_view_user_center_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 基础信息底部钩子
        $this->assign('plugins_view_user_base_bottom_data', Hook::listen('plugins_view_user_base_bottom', ['hook_name'=>'plugins_view_user_base_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 聚合内容顶部钩子
        $this->assign('plugins_view_user_various_top_data', Hook::listen('plugins_view_user_various_top', ['hook_name'=>'plugins_view_user_various_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 聚合内容底部钩子
        $this->assign('plugins_view_user_various_bottom_data', Hook::listen('plugins_view_user_various_bottom', ['hook_name'=>'plugins_view_user_various_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 聚合内容里面顶部钩子
        $this->assign('plugins_view_user_various_inside_top_data', Hook::listen('plugins_view_user_various_inside_top', ['hook_name'=>'plugins_view_user_various_inside_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 聚合内容里面底部钩子
        $this->assign('plugins_view_user_various_bottom_data', Hook::listen('plugins_view_user_various_bottom', ['hook_name'=>'plugins_view_user_various_bottom', 'is_backend'=>false, 'user'=>$this->user]));
    }

    /**
     * [ForgetPwdInfo 密码找回]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:06:47+0800
     */
    public function ForgetPwdInfo()
    {
        if(empty($this->user))
        {
            // 浏览器名称
            $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('密码找回', 1));

            // 左侧图片,随机其中一个
            $left_data = UserService::UserEntranceLeftData(['left_key'=>'forgetpwd', 'cache_key'=>config('shopxo.cache_user_forgetpwd_left_key')]);
            $this->assign('user_forgetpwd_left_data', empty($left_data['data']) ? [] : $left_data['data'][array_rand($left_data['data'], 1)]);

            return $this->fetch();
        } else {
            $this->assign('msg', '已经登录了，如要重置密码，请先退出当前账户');
            return $this->fetch('public/tips_error');
        }
    }

    /**
     * [RegInfo 用户注册页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function RegInfo()
    {
        $reg_all = MyC('home_user_reg_state');
        if(!empty($reg_all))
        {
            if(empty($this->user))
            {
                // 浏览器名称
                $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('用户注册', 1));

                // 返回地址
                $this->assign('referer_url', $this->GetrefererUrl());

                // 注册背景图片
                $this->assign('user_register_bg_images', MyC('home_site_user_register_bg_images'));

                return $this->fetch();
            } else {
                $this->assign('msg', '已经登录了，如要注册新账户，请先退出当前账户');
                return $this->fetch('public/tips_error');
            }
        } else {
            $this->assign('msg', '暂时关闭用户注册');
            return $this->fetch('public/tips_error');
        }
    }

    /**
     * [LoginInfo 用户登录页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function LoginInfo()
    {
        if(MyC('home_user_login_state') == 1)
        {
            if(empty($this->user))
            {
                // 浏览器名称
                $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('用户登录', 1));

                // 返回地址
                $this->assign('referer_url', $this->GetrefererUrl());

                // 左侧图片,随机其中一个
                $left_data = UserService::UserEntranceLeftData(['left_key'=>'login', 'cache_key'=>config('shopxo.cache_user_login_left_key')]);
                $this->assign('user_login_left_data', empty($left_data['data']) ? [] : $left_data['data'][array_rand($left_data['data'], 1)]);

                return $this->fetch();
            } else {
                $this->assign('msg', '已经登录了，请勿重复登录');
                return $this->fetch('public/tips_error');
            }
        } else {
            $this->assign('msg', '暂时关闭用户登录');
            return $this->fetch('public/tips_error');
        }
    }

    /**
     * modal弹窗登录
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-13
     * @desc    description
     */
    public function ModalLoginInfo()
    {
        $this->assign('is_header', 0);
        $this->assign('is_footer', 0);

        if(MyC('home_user_login_state') == 1)
        {
            if(empty($this->user))
            {
                // 浏览器名称
                $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('用户登录', 1));
                
                $this->assign('referer_url', $this->GetrefererUrl());
                return $this->fetch();
            } else {
                $this->assign('msg', '已经登录了，请勿重复登录');
                return $this->fetch('public/tips_error');
            }
        } else {
            $this->assign('msg', '暂时关闭用户登录');
            return $this->fetch('public/tips_error');
        }
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
        return UserService::Reg(input('post.'));
    }

    /**
     * [Login 用户登录]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T10:57:31+0800
     */
    public function Login()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::Login(input('post.'));
    }

    /**
     * [UserVerifyEntry 用户-验证码显示]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-05T15:10:21+0800
     */
    public function UserVerifyEntry()
    {
        $params = array(
                'width' => 100,
                'height' => 26,
                'key_prefix' => input('type', 'reg'),
            );
        $verify = new \base\Verify($params);
        $verify->Entry();
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
        return UserService::RegVerifySend(input('post.'));
    }

    /**
     * [ForgetPwdVerifySend 密码找回验证码发送]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:35:03+0800
     */
    public function ForgetPwdVerifySend()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::ForgetPwdVerifySend(input('post.'));
    }

    /**
     * [ForgetPwd 密码找回]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:55:42+0800
     */
    public function ForgetPwd()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 调用服务层
        return UserService::ForgetPwd(input('post.'));
    }

    /**
     * [Logout 退出]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-05T14:31:23+0800
     */
    public function Logout()
    {
        // 调用服务层
        $ret = UserService::Logout();

        // 登录返回
        $body_html = (!empty($ret['data']['body_html']) && is_array($ret['data']['body_html'])) ? implode(' ', $ret['data']['body_html']) : $ret['data']['body_html'];
        $this->assign('body_html', $body_html);
        $this->assign('msg', $ret['msg']);

        return $this->fetch();
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