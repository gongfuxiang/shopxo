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
namespace app\plugins\commonrightnavigation;

use think\Controller;
use app\service\PluginsService;
use app\service\BuyService;

/**
 * 右侧快捷导航 - 钩子入口
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
        // 是否控制器钩子
        // is_control 当前为控制器业务处理
        // hook_name 钩子名称
        if(isset($params['is_control']) && $params['is_control'] === true && !empty($params['hook_name']))
        {
            // 参数一   描述
            // 参数二   0 为处理成功, 负数为失败
            // 参数三   返回数据
            return DataReturn('返回描述', 0);

        // 默认返回视图
        } else {
            if(!empty($params['hook_name']))
            {
                switch($params['hook_name'])
                {
                    case 'plugins_css' :
                        $ret = __MY_ROOT_PUBLIC__.'static/plugins/css/commonrightnavigation/style.css';
                        break;

                    case 'plugins_js' :
                        $ret = __MY_ROOT_PUBLIC__.'static/plugins/js/commonrightnavigation/style.js';
                        break;

                    case 'plugins_view_common_bottom' :
                        $ret = $this->html($params);
                        break;

                    default :
                        $ret = '';
                }
                return $ret;
            } else {
                return '';
            }
        }
    }

    /**
     * 视图
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function html($params = [])
    {
        // 当前模块/控制器/方法
        $module_name = strtolower(request()->module());
        $controller_name = strtolower(request()->controller());
        $action_name = strtolower(request()->action());

        // 获取应用数据
        $ret = PluginsService::PluginsData('commonrightnavigation', ['alipay_mini_qrcode_images', 'alipay_fuwu_qrcode_images', 'weixin_mini_qrcode_images', 'weixin_fuwu_qrcode_images']);

        // html拼接
        $html = '<div class="commonrightnavigation-right-nav">';
        $content = '';
        if($ret['code'] == 0)
        {
            // 非全局
            if($ret['data']['is_overall'] != 1)
            {
                // 非首页则空
                if($module_name.$controller_name.$action_name != 'indexindexindex')
                {
                    return '';
                }
            }

            // 当前模块/控制器/方法
            $this->assign('module_controller_action', $module_name.$controller_name.$action_name);

            // 是否新窗口打开
            $is_new_window_open_html = (isset($ret['data']['is_new_window_open']) && $ret['data']['is_new_window_open'] == 1) ? 'target="_blank"' : '';
            $this->assign('is_new_window_open_html', $is_new_window_open_html);

            // 购物车总数
            $cart_total = BuyService::UserCartTotal(['user'=>$params['user']]);
            $this->assign('cart_total', $cart_total);

            // 是否需要登录
            $login_event_class = empty($params['user']) ? 'login-event' : '';
            $this->assign('login_event_class', $login_event_class);

            // 用户信息
            $this->assign('user', $params['user']);

            // 应用数据
            $this->assign('data', $ret['data']);

            // 购物车
            $cart_list = BuyService::CartList(['user'=>$params['user']]);
            $this->assign('cart_list', $cart_list['data']);
            $base = [
                'total_price'   => empty($cart_list['data']) ? 0 : array_sum(array_column($cart_list['data'], 'total_price')),
                'cart_count'   => empty($cart_list['data']) ? 0 : count($cart_list['data']),
                'ids'           => empty($cart_list['data']) ? '' : implode(',', array_column($cart_list['data'], 'id')),
            ];
            $this->assign('base', $base);

            return $this->fetch('../../../plugins/view/commonrightnavigation/index/content');
        } else {
            return $ret['msg'];
        }
    }
}
?>