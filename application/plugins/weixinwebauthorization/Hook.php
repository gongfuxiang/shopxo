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
namespace app\plugins\weixinwebauthorization;

use think\Controller;
use app\service\PluginsService;

/**
 * 微信登录 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    /**
     * 应用响应入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function run($params = [])
    {
        // 目前微信登录只针对手机端
        if(!empty($params['hook_name']) && IsMobile())
        {
            switch($params['hook_name'])
            {
                // 用户登录页面顶部钩子
                // 用户注册页面钩子
                case 'plugins_view_user_login_info_top' :
                case 'plugins_view_user_reg_info' :
                    $ret = $this->ButtonHtml($params);
                    break;

                // 公共顶部小导航钩子-左侧
                case 'plugins_view_header_navigation_top_left' :
                    $ret = $this->NavTextHtml($params);
                    break;

                // header代码
                case 'plugins_common_header' :
                    $ret = $this->Style($params);
                    break;

                default :
                    $ret = '';
            }
            return $ret;
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
                    .plugins-weixinwebauthorization-nav-top-text { color: #3db53d; }
                </style>';
    }

    /**
     * 登录登录html
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param   array           $params [description]
     */
    private function ButtonHtml($params = [])
    {
        $ret = PluginsService::PluginsData('weixinwebauthorization');
        $this->assign('plugins_data', $ret['data']);
        return $this->fetch('../../../plugins/view/weixinwebauthorization/index/public/auth_button');
    }

    /**
     * 文字登录html
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param   array           $params [description]
     */
    private function NavTextHtml($params = [])
    {
        $ret = PluginsService::PluginsData('weixinwebauthorization');
        $this->assign('plugins_data', $ret['data']);
        return $this->fetch('../../../plugins/view/weixinwebauthorization/index/public/auth_text');
    }
}
?>