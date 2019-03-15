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
namespace app\plugins\touristbuy;

use think\Controller;
use app\service\PluginsService;
use app\service\UserService;

/**
 * 游客购买 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    /**
     * 钩子入口
     * @author   Guoguo
     * @blog     http://gadmin.cojz8.com
     * @version  1.0.0
     * @datetime 2019年3月14日
     */
    public function run($params = [])
    {
        // 是否后端钩子
        if(!empty($params['hook_name']))
        {
            switch($params['hook_name'])
            {
                // 顶部登录入口/登录信息
                case 'plugins_view_header_navigation_top_left' :
                    $ret = $this->LoginNavTopHtml($params);
                    break;

                // 用户登录页面顶部
                case 'plugins_view_user_login_info_top' :
                case 'plugins_view_user_sms_reg_info' :
                case 'plugins_view_user_email_reg_info' :
                    $ret = $this->UserLoginInfoHtml($params);
                    break;

                // header代码
                case 'plugins_common_header' :
                    $ret = $this->Style($params);
                    break;

                // 导航链接
                case 'plugins_service_navigation_header_handle' :
                $ret = $this->NavTitle($params);
                    break;

                default :
                    $ret = DataReturn('无需处理', 0);
            }
            return $ret;

        // 默认返回视图
        } else {
            return '';
        }
    }

    /**
     * css
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function NavTitle($params = [])
    {
        if(!empty($params['header']) && is_array($params['header']))
        {
            // 获取应用数据
            $ret = PluginsService::PluginsData('touristbuy');
            $ret['data']['application_name'] = '订单查询';
            if($ret['code'] == 0 && !empty($ret['data']['application_name']))
            {
                $params['header'][] = [
                    'id'                    => 0,
                    'pid'                   => 0,
                    'name'                  => $ret['data']['application_name'],
                    'url'                   => PluginsHomeUrl('touristbuy', 'index', 'index'),
                    'data_type'             => 'custom',
                    'is_show'               => 1,
                    'is_new_window_open'    => 0,
                    'items'                 => [],
                ];
            }
        }
    }

    /**
     * css
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function Style($params = [])
    {
        return '<style type="text/css">
                    .plugins-touristbuy-nav-top, .plugins-touristbuy-login-info-btn { margin-left: 10px; }
                    .plugins-touristbuy-nav-top {  color: #FF5722; }
                    .plugins-touristbuy-index { margin-top: 5%; }
                    .plugins-touristbuy-index form.am-form { margin-top: 20px; }
                    .plugins-touristbuy-index form.am-form .am-form-group { padding: 0 !important; }
                </style>';
    }

    /**
     * 前端顶部小导航展示登入
     * @author   Guoguo
     * @blog     http://gadmin.cojz8.com
     * @version  1.0.0
     * @datetime 2019年3月14日
     * @param    [array]          $params [输入参数]
     */
    public function UserLoginInfoHtml($params = [])
    {
        // html
        $html = '';

        // 获取已登录用户信息，已登录则不展示入口
        $user = UserService::LoginUserInfo();
        if(empty($user))
        {
            // 获取插件信息
            $ret = PluginsService::PluginsData('touristbuy');
            $html = '<a href="'.PluginsHomeUrl('touristbuy', 'index', 'login').'" target="_blank" class="am-btn am-btn-secondary am-btn-xs am-radius plugins-touristbuy-login-info-btn">游客登录</a>';
        }
        return $html;
    }

    /**
     * 前端顶部小导航展示登入
     * @author   Guoguo
     * @blog     http://gadmin.cojz8.com
     * @version  1.0.0
     * @datetime 2019年3月14日
     * @param    [array]          $params [输入参数]
     */
    public function LoginNavTopHtml($params = [])
    {
        // html
        $html = '';

        // 获取已登录用户信息，已登录则不展示入口
        $user = UserService::LoginUserInfo();
        if(empty($user))
        {
            // 获取插件信息
            $ret = PluginsService::PluginsData('touristbuy');
            $html = '<a href="'.PluginsHomeUrl('touristbuy', 'index', 'login').'" class="plugins-touristbuy-nav-top">游客登录</a>';
        }
        return $html;
    }
}
?>