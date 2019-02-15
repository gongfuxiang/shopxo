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
 * 右侧导航 - 钩子入口
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
                    case 'plugins_view_common_bottom' :
                        $ret = $this->html($params);
                        break;

                    case 'plugins_view_common_page_bottom' :
                        $ret = $this->js($params);
                        break;

                    case 'plugins_view_common_header' :
                        $ret = $this->css($params);
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
     * css
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function css($params = [])
    {
        return '<style type="text/css">
                    .commonrightnavigation-right-nav{width: 35px;height: 100vh;background: #000;position: fixed; top: 0; right: 0; font-size: 16px; z-index: 100;}
                    .commonrightnavigation-right-nav .base-nav{text-align: center; position: absolute; width: 100%; color: #ccc; cursor: pointer;}
                    .commonrightnavigation-right-nav .base-nav [class*="am-icon-"]:before{line-height: 35px;}
                    .commonrightnavigation-right-nav .mui-mbar-tab-tip{position: absolute; left: -90px; top: 0; width: 90px; height: 35px; line-height: 35px; text-align: center; color: #fff; background-color: #494949; -webkit-box-shadow: 0 0 5px rgba(0,0,0,.2); -moz-box-shadow: 0 0 5px rgba(0,0,0,.2); box-shadow: 0 0 5px rgba(0,0,0,.2); font-size: 12px; font-weight: 700; display: none;}
                    .mui-mbar-popup{height: auto; position: absolute; right: 36px; display: none; box-shadow: -5px 5px 15px 0px rgba(0,0,0,.4);}
                    .commonrightnavigation-right-nav .base-nav:hover{background: #d2364c; color: #fff;}
                    .commonrightnavigation-right-nav .base-nav:hover .mui-mbar-tab-tip, .commonrightnavigation-right-nav .base-nav:hover .mui-mbar-popup{display: block;}
                    .commonrightnavigation-right-nav .mui-mbar-tab-tip-arr{top: 10px; right: -8px; color: #494949;}
                    .commonrightnavigation-right-nav .mui-mbar-arr{position: absolute; width: 16px; height: 16px; line-height: 16px; text-align: center; font-size: 16px; font-family: "\5b8b\4f53";}
                    .commonrightnavigation-right-nav .go-top{bottom: 0; left: 0; height: 35px; display: none;}
                    .commonrightnavigation-right-nav .qrcode{bottom: 35px; left: 0; height: 35px;}
                    .commonrightnavigation-right-nav .cart{bottom: 430px; left: 0; height: 140px; border-top: 1px solid #444; border-bottom: 1px solid #444; padding: 10px 0;}
                    .commonrightnavigation-right-nav .cart:hover{border-top: 1px solid #d2364c; border-bottom: 1px solid #d2364c;}
                    .commonrightnavigation-right-nav .favor{bottom: 585px; left: 0; height: 35px;}
                    .commonrightnavigation-right-nav .browse{bottom: 620px; left: 0; height: 35px;}
                    .commonrightnavigation-right-nav .user-center{bottom: 655px; left: 0; height: 35px;}
                    .commonrightnavigation-right-nav .cart .cart-text{width: 12px; font-size: 12px; margin-left: 11.5px;}
                    .commonrightnavigation-right-nav .cart .cart-text .cart-count{background: #d2364c; color: #fff; padding: 0 3px; min-width: 14px; width: 20px; height: 20px; line-height: 20px; border-radius: 10px; font-size: 12px; margin-left: -4px; overflow: hidden;}
                    .commonrightnavigation-right-nav .qrcode-content{bottom: 0; width: 150px;}
                    .commonrightnavigation-right-nav .qrcode-content ul{padding: 0; border: 1px solid #eee; padding: 10px 20px; background: #f5f5f5;}
                    .commonrightnavigation-right-nav .qrcode-content li{list-style: none;}
                    .commonrightnavigation-right-nav .qrcode-content li:not(:first-child){margin-top: 10px;}
                    .commonrightnavigation-right-nav .qrcode-content p{font-size: 14px; font-weight: 700; color: #666; padding: 0; margin: 0; line-height: 24px;}
                    .commonrightnavigation-right-nav .qrcode-content img{width: 100px;}
                </style>';
    }

    /**
     * js
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function js($params = [])
    {
        return '<script type="text/javascript">
                    // 回顶部监测
                    $(window).scroll(function()
                    {
                      if($(window).scrollTop() > 100)
                      {
                        $("#plugins-commonrightnavigation").fadeIn(1000);
                      } else {
                        $("#plugins-commonrightnavigation").fadeOut(1000);
                      }
                    });
                </script>';
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

            // 内容
            $content .= '<!-- 用户中心 -->
                <a href="'.MyUrl('index/user/index').'" '.$is_new_window_open.'>
                    <div class="base-nav user-center">
                        <i class="am-icon-user"></i>
                        <div class="mui-mbar-tab-tip am-animation-slide-left">
                            用户中心
                            <div class="mui-mbar-arr mui-mbar-tab-tip-arr">◆</div>
                        </div>
                    </div>
                </a>

                <!-- 我的足迹 -->
                <a href="'.MyUrl('index/usergoodsbrowse/index').'" '.$is_new_window_open.'>
                    <div class="base-nav browse">
                        <i class="am-icon-lastfm"></i>
                        <div class="mui-mbar-tab-tip am-animation-slide-left">
                            我的足迹
                            <div class="mui-mbar-arr mui-mbar-tab-tip-arr">◆</div>
                        </div>
                    </div>
                </a>

                <!-- 我的收藏 -->
                <a href="'.MyUrl('index/userfavor/goods').'" '.$is_new_window_open.'>
                    <div class="base-nav favor">
                        <i class="am-icon-star-o"></i>
                        <div class="mui-mbar-tab-tip am-animation-slide-left">
                            我的收藏
                            <div class="mui-mbar-arr mui-mbar-tab-tip-arr">◆</div>
                        </div>
                    </div>
                </a>

                <!-- 购物车 -->
                <a href="'.MyUrl('index/cart/index').'" '.$is_new_window_open.'>
                    <div class="base-nav cart">
                        <i class="am-icon-opencart"></i>
                        <div class="cart-text">
                            购物车
                            <div class="cart-count common-cart-total">'.$cart_total.'</div>
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
                    <div class="base-nav qrcode">
                        <i class="am-icon-qrcode"></i>
                        <div class="mui-mbar-popup qrcode-content am-animation-slide-left">
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