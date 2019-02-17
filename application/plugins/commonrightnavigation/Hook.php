<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\plugins\commonrightnavigation;

use app\service\PluginsService;
use app\service\BuyService;

/**
 * 右侧快捷导航 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook
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

            // 是否新窗口打开
            $is_new_window_open = (isset($ret['data']['is_new_window_open']) && $ret['data']['is_new_window_open'] == 1) ? 'target="_blank"' : '';

            // 购物车总数
            $cart_total = BuyService::UserCartTotal(['user'=>$params['user']]);

            // 是否需要登录
            $login_event = empty($params['user']) ? 'login-event' : '';

            // 内容
            $content .= '<!-- 用户中心 -->
                <a href="'.(empty($params['user']) ? 'javascript:;' : MyUrl('index/user/index')).'" '.$is_new_window_open.' class="user-content '.$login_event.'">
                    <div class="base-nav user-center">
                        <img src="'.(!empty($user['avatar']) ? $params['user']['avatar'] : config('shopxo.attachment_host').'/static/index/default/images/default-user-avatar.jpg').'" class="user-avatar" />
                        <div class="mui-mbar-tab-tip am-animation-slide-left">
                            用户中心
                            <div class="mui-mbar-arr mui-mbar-tab-tip-arr">◆</div>
                        </div>
                    </div>
                </a>

                <!-- 我的足迹 -->
                <a href="'.(empty($params['user']) ? 'javascript:;' : MyUrl('index/usergoodsbrowse/index')).'" '.$is_new_window_open.' class="browse-content '.$login_event.'">
                    <div class="base-nav browse">
                        <i class="am-icon-lastfm"></i>
                        <div class="mui-mbar-tab-tip am-animation-slide-left">
                            我的足迹
                            <div class="mui-mbar-arr mui-mbar-tab-tip-arr">◆</div>
                        </div>
                    </div>
                </a>

                <!-- 我的收藏 -->
                <a href="'.(empty($params['user']) ? 'javascript:;' : MyUrl('index/userfavor/goods')).'" '.$is_new_window_open.' class="favor-content '.$login_event.'">
                    <div class="base-nav favor">
                        <i class="am-icon-star-o"></i>
                        <div class="mui-mbar-tab-tip am-animation-slide-left">
                            我的收藏
                            <div class="mui-mbar-arr mui-mbar-tab-tip-arr">◆</div>
                        </div>
                    </div>
                </a>

                <!-- 购物车 -->
                <a href="'.(empty($params['user']) ? 'javascript:;' : MyUrl('index/cart/index')).'" '.$is_new_window_open.' class="cart-content '.$login_event.' '.(($ret['data']['is_goods_page_show_cart'] == 1 && $module_name.$controller_name.$action_name == 'indexgoodsindex' ? 'cart-show' : '')).'">
                    <div class="base-nav cart">
                        <i class="am-icon-opencart"></i>
                        <div class="cart-text">
                            购物车
                            <div class="cart-count common-cart-total am-badge am-badge-danger">'.($cart_total > 9 ? '9+' : $cart_total).'</div>
                        </div>
                    </div>
                </a>';

            $qrcode = '';
            if(!empty($ret['data']['alipay_mini_qrcode_images']))
            {
                $qrcode .= '<li>
                                <p>支付宝小程序</p>
                                <img src="'.$ret['data']['alipay_mini_qrcode_images'].'" alt="支付宝小程序" /> 
                            </li>';
            }
            if(!empty($ret['data']['alipay_fuwu_qrcode_images']))
            {
                $qrcode .= '<li>
                                <p>支付宝生活号</p>
                                <img src="'.$ret['data']['alipay_fuwu_qrcode_images'].'" alt="支付宝生活号" /> 
                            </li>';
            }
            if(!empty($ret['data']['weixin_mini_qrcode_images']))
            {
                $qrcode .= '<li>
                                <p>微信小程序</p>
                                <img src="'.$ret['data']['weixin_mini_qrcode_images'].'" alt="微信小程序" /> 
                            </li>';
            }
            if(!empty($ret['data']['weixin_fuwu_qrcode_images']))
            {
                $qrcode .= '<li>
                                <p>微信公众号</p>
                                <img src="'.$ret['data']['weixin_fuwu_qrcode_images'].'" alt="微信公众号" /> 
                            </li>';
            }
            if(!empty($qrcode))
            {
                $content .= '<!-- 二维码 -->
                    <div class="base-nav qrcode-content">
                        <i class="am-icon-qrcode"></i>
                        <div class="mui-mbar-popup qrcode-items am-animation-slide-left">
                            <ul>'.$qrcode.'</ul>
                        </div>
                    </div>';
            }

            $content .= '<!-- 回顶部 -->
                <div class="base-nav go-top" data-am-smooth-scroll id="plugins-commonrightnavigation">
                    <i class="am-icon-arrow-up"></i>
                    <div class="mui-mbar-tab-tip am-animation-slide-left">
                        返回顶部
                        <div class="mui-mbar-arr mui-mbar-tab-tip-arr">◆</div>
                    </div>
                </div>';

        } else {
            $content = $ret['msg'];
        }
        $html .= $content;
        $html .= '</div>';

        return $html;
    }
}
?>