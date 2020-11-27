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
namespace app\service;

use think\Db;
use think\facade\Hook;
use app\service\ResourcesService;
use app\service\QuickNavService;

/**
 * 基础公共信息服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-12
 * @desc    description
 */
class BaseService
{
    /**
     * 公共配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Common($params = [])
    {
        // 配置信息
        $config = [
            // 基础
            'common_site_type'                  => (int) MyC('common_site_type', 0, true),
            'common_shop_notice'                => MyC('common_shop_notice', null, true),
            'common_app_is_enable_search'       => (int) MyC('common_app_is_enable_search', 1),
            'common_app_is_enable_answer'       => (int) MyC('common_app_is_enable_answer', 1),
            'common_app_is_header_nav_fixed'    => (int) MyC('common_app_is_header_nav_fixed', 0),
            'common_app_is_online_service'      => (int) MyC('common_app_is_online_service', 0),
            'common_app_customer_service_tel'   => MyC('common_app_customer_service_tel', null, true),
            'common_order_is_booking'           => (int) MyC('common_order_is_booking'),
            'common_is_exhibition_mode_btn_text'=> MyC('common_is_exhibition_mode_btn_text', '立即咨询', true),
            'common_user_is_mandatory_bind_mobile'=> (int) MyC('common_user_is_mandatory_bind_mobile', 0),
            'common_user_is_onekey_bind_mobile' => (int) MyC('common_user_is_onekey_bind_mobile', 0),
            'home_navigation_main_quick_status' => (int) MyC('home_navigation_main_quick_status', 0),
            'home_user_address_map_status'      => (int) MyC('home_user_address_map_status', 0),
            'home_user_address_idcard_status'   => (int) MyC('home_user_address_idcard_status', 0),

            // 订单相关
            'home_is_enable_order_bulk_pay'     => (int) MyC('home_is_enable_order_bulk_pay', 0),

            // 用户中心相关
            'common_user_center_notice'         => MyC('common_user_center_notice', null, true),
            'common_app_is_head_vice_nav'       => (int) MyC('common_app_is_head_vice_nav', 0),

            // 商品分类相关
            'category_show_level'               => MyC('common_show_goods_category_level', 3, true),

            // 商品相关
            'common_app_is_use_mobile_detail'   => (int) MyC('common_app_is_use_mobile_detail'),
            'common_app_is_good_thing'          => (int) MyC('common_app_is_good_thing'),
            'common_app_is_poster_share'        => (int) MyC('common_app_is_poster_share'),
            'common_is_goods_detail_show_photo' => (int) MyC('common_is_goods_detail_show_photo', 0, true),
        ];

        // 支付宝小程序在线客服
        if(APPLICATION_CLIENT_TYPE == 'alipay')
        {
            $config['common_app_mini_alipay_tnt_inst_id'] = MyC('common_app_mini_alipay_tnt_inst_id', null, true);
            $config['common_app_mini_alipay_scene'] = MyC('common_app_mini_alipay_scene', null, true);
        }

        // 数据集合
        $data = [
            // 全局状态值(1接口执行成功,用于前端校验接口请求完成状态,以后再加入其它状态)
            'status'            => 1,

            // 配置信息
            'config'            => $config,

            // 货币符号
            'currency_symbol'   => ResourcesService::CurrencyDataSymbol(),

            // 快捷入口信息
            'quick_nav'         => QuickNavService::QuickNav(),
        ];

        // 公共配置信息钩子
        $hook_name = 'plugins_service_base_commin';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'params'        => $params,
        ]);

        return $data;
    }
}
?>