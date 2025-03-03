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
namespace app\service;

use think\facade\Db;
use app\service\ResourcesService;
use app\service\QuickNavService;
use app\service\PluginsService;
use app\service\AppMiniUserService;
use app\service\AppTabbarService;

/**
 * 系统基础公共信息服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-12
 * @desc    description
 */
class SystemBaseService
{
    // 商品优惠使用记录key
    public static $plugins_goods_discount_record_key = 'plugins_use_goods_discount_record_';

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
            'common_site_type'                                   => self::SiteTypeValue(),
            'common_shop_notice'                                 => MyC('common_shop_notice', null, true),
            'common_is_cart_show_guess_you_like'                 => (int) MyC('common_is_cart_show_guess_you_like', 0),

            // 协议、注册协议、隐私协议、注销协议
            'agreement_userregister_url'                         => MyUrl('index/agreement/index', ['document'=>'userregister', 'is_content'=>1]),
            'agreement_userprivacy_url'                          => MyUrl('index/agreement/index', ['document'=>'userprivacy', 'is_content'  =>1]),
            'agreement_userlogout_url'                           => MyUrl('index/agreement/index', ['document'=>'userlogout', 'is_content'   =>1]),

            // 手机端相关配置
            'common_app_is_enable_search'                        => (int) MyC('common_app_is_enable_search', 1),
            'common_app_is_header_nav_fixed'                     => (int) MyC('common_app_is_header_nav_fixed', 0),
            'common_app_is_online_service'                       => (int) MyC('common_app_is_online_service', 0),
            'common_app_customer_service_tel'                    => MyC('common_app_customer_service_tel', null, true),
            'common_app_customer_service_custom'                 => MyC('common_app_customer_service_custom', null),
            'common_app_customer_service_company_weixin_corpid'  => MyC('common_app_customer_service_company_weixin_corpid', null),
            'common_app_customer_service_company_weixin_url'     => MyC('common_app_customer_service_company_weixin_url', null),
            'common_app_h5_url'                                  => MyC('common_app_h5_url', null, true),
            
            // 基础-站点域名和CDN
            'common_domain_host'                                 => MyC('common_domain_host', null, true),
            'common_cdn_attachment_host'                         => MyC('common_cdn_attachment_host', null, true),
            'common_cdn_public_host'                             => MyC('common_cdn_public_host', null, true),
            
            // 扩展项-订单
            'common_order_close_limit_time'                      => (int) MyC('common_order_close_limit_time', 30, true),
            'common_order_success_limit_time'                    => (int) MyC('common_order_success_limit_time', 21600, true),
            'common_pay_log_order_close_limit_time'              => (int) MyC('common_pay_log_order_close_limit_time', 30, true),
            
            // 验证码
            'common_verify_expire_time'                          => (int) MyC('common_verify_expire_time', 600, true),
            'common_verify_interval_time'                        => (int) MyC('common_verify_interval_time', 60, true),
            'common_img_verify_state'                            => (int) MyC('common_img_verify_state', 0, true),
            
            // 密码找回
            'home_site_user_forgetpwd_ad_images'                 => self::SiteAdConfigImagesMgerge('home_site_user_forgetpwd'),
            
            // 用户登录
            'home_user_login_type'                               => MyC('home_user_login_type', [], true),
            'home_user_login_img_verify_state'                   => (int) MyC('home_user_login_img_verify_state', 0, true),
            'home_site_user_login_ad_images'                     => self::SiteAdConfigImagesMgerge('home_site_user_login'),
            
            // 注册
            'home_user_reg_type'                                 => MyC('home_user_reg_type', [], true),
            'home_user_register_img_verify_state'                => (int) MyC('home_user_register_img_verify_state', 0, true),
            'common_register_is_enable_audit'                    => (int) MyC('common_register_is_enable_audit', 0, true),
            'home_is_enable_userregister_agreement'              => (int) MyC('home_is_enable_userregister_agreement', 0, true),
            'home_site_user_register_bg_images'                  => ResourcesService::AttachmentPathViewHandle(MyC('home_site_user_register_bg_images')),
            
            // APP、小程序
            'common_user_is_mandatory_bind_mobile'               => (int) MyC('common_user_is_mandatory_bind_mobile', 0),
            'common_user_verify_bind_mobile_list'                => MyC('common_user_verify_bind_mobile_list', [], true),
            'common_user_onekey_bind_mobile_list'                => MyC('common_user_onekey_bind_mobile_list', [], true),
            'common_user_address_platform_import_list'           => MyC('common_user_address_platform_import_list', [], true),
            'common_app_is_weixin_force_user_base'               => (int) MyC('common_app_is_weixin_force_user_base', 0, true),
            'common_app_user_base_popup_pages'                   => MyC('common_app_user_base_popup_pages', [], true),
            'common_app_user_base_popup_client'                  => MyC('common_app_user_base_popup_client', [], true),
            'common_app_user_base_popup_interval_time'           => (int) MyC('common_app_user_base_popup_interval_time', 1800),
            
            // 站点信息
            'home_site_name'                                     => MyC('home_site_name', null, true),
            'home_site_logo'                                     => ResourcesService::AttachmentPathViewHandle(MyC('home_site_logo')),
            'home_site_logo_wap'                                 => ResourcesService::AttachmentPathViewHandle(MyC('home_site_logo_wap')),
            'home_site_logo_app'                                 => ResourcesService::AttachmentPathViewHandle(MyC('home_site_logo_app')),
            'home_site_logo_square'                              => ResourcesService::AttachmentPathViewHandle(MyC('home_site_logo_square')),
            'home_site_app_state'                                => MyC('home_site_app_state', [], true),
            'home_site_web_state'                                => (int) MyC('home_site_web_state', 1),
            'home_site_web_home_state'                           => (int) MyC('home_site_web_home_state', 1),
            'home_site_web_pc_state'                             => (int) MyC('home_site_web_pc_state', 1),
            'home_site_close_reason'                             => MyC('home_site_close_reason', null, true),
            
            // 备案信息
            'home_site_icp'                                      => MyC('home_site_icp', null, true),
            'home_site_security_record_name'                     => MyC('home_site_security_record_name', null, true),
            'home_site_security_record_url'                      => MyC('home_site_security_record_url', null, true),
            'home_site_company_license'                          => MyC('home_site_company_license', null, true),
            'home_site_telecom_license'                          => MyC('home_site_telecom_license', null, true),
            
            // css/js版本值
            'home_static_cache_version'                          => MyC('home_static_cache_version', null, true),
            
            // 底部代码
            'home_footer_info'                                   => MyC('home_footer_info', null, true),
            
            // 首页设置参数
            'home_index_floor_data_type'                         => (int) MyC('home_index_floor_data_type', 0, true),
            'home_index_banner_left_status'                      => (int) MyC('home_index_banner_left_status', 1),
            'home_index_banner_right_status'                     => (int) MyC('home_index_banner_right_status', 1),
            
            // 搜索相关
            'home_search_is_login_required'                      => (int) MyC('home_search_is_login_required', 0),
            'home_search_limit_number'                           => (int) MyC('home_search_limit_number', 20, true),
            'home_search_is_brand'                               => (int) MyC('home_search_is_brand', 1),
            'home_search_is_category'                            => (int) MyC('home_search_is_category', 1),
            'home_search_is_price'                               => (int) MyC('home_search_is_price', 1),
            'home_search_is_params'                              => (int) MyC('home_search_is_params', 1),
            'home_search_is_spec'                                => (int) MyC('home_search_is_spec', 1),
            'home_search_goods_show_type'                        => (int) MyC('home_search_goods_show_type', 0, true),
            
            // 站点设置-扩展-基础
            'home_index_friendship_link_status'                  => (int) MyC('home_index_friendship_link_status', 0, true),
            'home_header_top_is_home'                            => (int) MyC('home_header_top_is_home', 1),
            
            // 站点设置-扩展-快捷导航
            'home_navigation_main_quick_status'                  => (int) MyC('home_navigation_main_quick_status', 0),
            'home_navigation_main_quick_name'                    => MyC('home_navigation_main_quick_name', '更多入口', true),
            
            // 站点设置-扩展-用户地址
            'home_user_address_map_status'                       => (int) MyC('home_user_address_map_status', 0),
            'home_user_address_idcard_status'                    => (int) MyC('home_user_address_idcard_status', 0),
            'home_extraction_address_position'                   => (int) MyC('home_extraction_address_position', 0),
            
            // 站点设置-扩展-多语言
            'home_use_multilingual_status'                       => (int) MyC('home_use_multilingual_status', 0),
            
            // 订单相关
            'home_is_enable_order_bulk_pay'                      => (int) MyC('home_is_enable_order_bulk_pay', 0),
            'common_order_is_booking'                            => (int) MyC('common_order_is_booking'),
            
            // 用户中心相关
            'common_user_center_notice'                          => MyC('common_user_center_notice', null, true),
            'common_app_is_head_vice_nav'                        => (int) MyC('common_app_is_head_vice_nav', 0),
            
            // 商品分类相关
            'category_show_level'                                => MyC('common_show_goods_category_level', 0, true),
            
            // 商品相关
            'common_app_is_use_mobile_detail'                    => (int) MyC('common_app_is_use_mobile_detail'),
            'common_is_goods_detail_show_comments'               => (int) MyC('common_is_goods_detail_show_comments', 1),
            'common_is_goods_detail_show_seeing_you'             => (int) MyC('common_is_goods_detail_show_seeing_you', 1),
            'common_is_goods_detail_show_guess_you_like'         => (int) MyC('common_is_goods_detail_show_guess_you_like', 1),
            'common_is_goods_detail_show_left_more'              => (int) MyC('common_is_goods_detail_show_left_more', 1),
            'common_is_goods_detail_content_show_photo'          => (int) MyC('common_is_goods_detail_content_show_photo', 0, true),
            'common_is_exhibition_mode_btn_text'                 => MyC('common_is_exhibition_mode_btn_text', '立即咨询', true),
            
            // 地图密钥
            'common_map_type'                                    => MyC('common_map_type', 'baidu', true),
            'common_baidu_map_ak'                                => MyC('common_baidu_map_ak', null, true),
            'common_amap_map_ak'                                 => MyC('common_amap_map_ak', null, true),
            'common_amap_map_safety_ak'                          => MyC('common_amap_map_safety_ak', null, true),
            'common_tencent_map_ak'                              => MyC('common_tencent_map_ak', null, true),
            'common_tianditu_map_ak'                             => MyC('common_tianditu_map_ak', null, true),
            
            // 商店信息
            'common_customer_store_tel'                          => MyC('common_customer_store_tel', null, true),
            'common_customer_store_email'                        => MyC('common_customer_store_email', null, true),
            'common_customer_store_address'                      => MyC('common_customer_store_address', null, true),
            'common_customer_store_describe'                     => MyC('common_customer_store_describe', null, true),
            'common_customer_store_qrcode'                       => ResourcesService::AttachmentPathViewHandle(MyC('common_customer_store_qrcode')),
            
            // SEO信息
            'home_seo_site_title'                                => MyC('home_seo_site_title', null, true),
            'home_seo_site_keywords'                             => MyC('home_seo_site_keywords', null, true),
            'home_seo_site_description'                          => MyC('home_seo_site_description', null, true),
            
            // 小程序基础信息
            // 微信小程序
            'common_app_mini_weixin_title'                       => AppMiniUserService::AppMiniConfig('common_app_mini_weixin_title'),
            'common_app_mini_weixin_describe'                    => AppMiniUserService::AppMiniConfig('common_app_mini_weixin_describe'),
            'common_app_mini_weixin_share_original_id'           => AppMiniUserService::AppMiniConfig('common_app_mini_weixin_share_original_id'),
            'common_app_mini_weixin_upload_shipping_status'      => AppMiniUserService::AppMiniConfig('common_app_mini_weixin_upload_shipping_status'),
            'common_app_mini_weixin_privacy_content'             => AppMiniUserService::AppMiniConfig('common_app_mini_weixin_privacy_content'),
            // 支付宝小程序
            'common_app_mini_alipay_title'                       => AppMiniUserService::AppMiniConfig('common_app_mini_alipay_title'),
            'common_app_mini_alipay_describe'                    => AppMiniUserService::AppMiniConfig('common_app_mini_alipay_describe'),
            // 百度小程序
            'common_app_mini_baidu_title'                        => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_title'),
            'common_app_mini_baidu_describe'                     => AppMiniUserService::AppMiniConfig('common_app_mini_baidu_describe'),
            // 头条小程序
            'common_app_mini_toutiao_title'                      => AppMiniUserService::AppMiniConfig('common_app_mini_toutiao_title'),
            'common_app_mini_toutiao_describe'                   => AppMiniUserService::AppMiniConfig('common_app_mini_toutiao_describe'),
            // QQ小程序
            'common_app_mini_qq_title'                           => AppMiniUserService::AppMiniConfig('common_app_mini_qq_title'),
            'common_app_mini_qq_describe'                        => AppMiniUserService::AppMiniConfig('common_app_mini_qq_describe'),
            // 快手小程序
            'common_app_mini_kuaishou_title'                     => AppMiniUserService::AppMiniConfig('common_app_mini_kuaishou_title'),
            'common_app_mini_kuaishou_describe'                  => AppMiniUserService::AppMiniConfig('common_app_mini_kuaishou_describe'),
        ];

        // 支付宝小程序在线客服
        if(APPLICATION_CLIENT_TYPE == 'alipay')
        {
            $config['common_app_mini_alipay_tnt_inst_id']  = AppMiniUserService::AppMiniConfig('common_app_mini_alipay_tnt_inst_id');
            $config['common_app_mini_alipay_scene']        = AppMiniUserService::AppMiniConfig('common_app_mini_alipay_scene');
        }

        // 数据集合
        $data = [
            // 全局状态值(1接口执行成功,用于前端校验接口请求完成状态,以后再加入其它状态)
            'status'            => 1,
            // 配置信息
            'config'            => $config,
            // 底部菜单
            'app_tabbar'        => AppTabbarService::AppTabbarConfigData('home'),
            // 货币符号
            'currency_symbol'   => ResourcesService::CurrencyDataSymbol(),
            // 快捷入口信息
            'quick_nav'         => QuickNavService::QuickNav($params),
            // 插件配置信息
            'plugins_base'      => PluginsService::PluginsBaseList($params),
        ];

        // 公共配置信息钩子
        $hook_name = 'plugins_service_base_common';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'params'        => $params,
        ]);

        return DataReturn('success', 0, $data);
    }

    /**
     * 站点相关多张广告图片配置信息合并
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-05-08
     * @desc    description
     * @param   [string]            $type   [类型]
     * @param   [int]               $count  [数量、默认3]
     */
    public static function SiteAdConfigImagesMgerge($type, $count = 3)
    {
        $data = [];
        $key = $type.'_ad';
        for($i=1; $i<=$count; $i++)
        {
            $img = ResourcesService::AttachmentPathViewHandle(MyC($key.$i.'_images'));
            if(!empty($img))
            {
                $data[] = [
                    'images'    => $img,
                    'url'       => MyC($key.$i.'_url', null, true),
                    'bg_color'  => MyC($key.$i.'_bg_color', null, true),
                ];
            }
        }
        return $data;
    }

    /**
     * 数据返回处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-06
     * @desc    description
     * @param   [array]           $data [返回数据]
     * @param   [string]          $msg  [提示信息]
     * @param   [int]             $code [状态码]
     */
    public static function DataReturn($data = [], $msg = 'success', $code = 0)
    {
        // 当前操作名称, 兼容插件模块名称
        $module_name = RequestModule();
        $controller_name = RequestController();
        $action_name = RequestAction();

        // 钩子
        $hook_name = 'plugins_service_base_data_return_'.$module_name.'_'.$controller_name.'_'.$action_name;
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'params'        => input(),
        ]);

        return DataReturn($msg, $code, $data);
    }

    /**
     * 站点类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-04
     * @desc    description
     */
    public static function SiteTypeValue()
    {
        // 当前站点类型、默认快递（0快递, 1同城, 2自提, 3虚拟, 4展示, 5快递+自提, 6同城+自提, 7快递+同城, 8快递+同城+自提）
        $site_type = MyC('common_site_type');
        $value = empty($site_type) ? 0 : (is_array($site_type) ? (array_key_exists(APPLICATION_CLIENT_TYPE, $site_type) ? $site_type[APPLICATION_CLIENT_TYPE] : 0) : $site_type);

        // 钩子
        $hook_name = 'plugins_service_base_site_type_value';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'value'         => &$value,
        ]);

        return $value;
    }

    /**
     * 是否使用商品优惠记录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-09
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @param   [string]       $plugins  [插件名称]
     */
    public static function IsGoodsDiscountRecord($goods_id, $plugins)
    {
        // 获取记录
        $data = self::GetGoodsDiscountRecord($goods_id);

        // 当前插件是否存在优惠记录
        return in_array($plugins, $data);
    }

    /**
     * 商品优惠记录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-09
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @param   [string]       $plugins  [插件名称]
     * @param   [int]          $is_use   [是否使用（0否, 1是）]
     */
    public static function GoodsDiscountRecord($goods_id, $plugins, $is_use = 0)
    {
        // 记录key
        $key = self::$plugins_goods_discount_record_key.$goods_id;

        // 获取记录
        $data = self::GetGoodsDiscountRecord($goods_id);

        // 是否存在
        $index = array_search($plugins, $data);

        // 是否使用优惠
        if($is_use == 1)
        {
            // 存储记录
            if($index === false)
            {
                $data[] = $plugins;
            }
            MySession($key, $data);
        } else {
            if($index !== false)
            {
                unset($data[$index]);
                sort($data);
            }
        }

        MySession($key, empty($data) ? null : $data);
        return true;
    }

    /**
     * 获取使用商品优化记录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-09
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GetGoodsDiscountRecord($goods_id)
    {
        $res = MySession(self::$plugins_goods_discount_record_key.$goods_id);
        return empty($res) ? [] : $res;
    }

    /**
     * 商品是否支持折扣
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-08
     * @desc    description
     * @param   [array]          $params     [输入参数]
     * @param   [string]         $plugins    [插件名称]
     */
    public static function IsGoodsDiscount($params = [], $plugins = '')
    {
        // 默认支持
        $status = 1;

        // 是否关闭商品优惠重叠
        // 采用钩子进行处理
        if(MyC('is_close_goods_discount_overlap', 0) == 1 && !empty($params) && !empty($params['hook_name']))
        {
            switch($params['hook_name'])
            {
                // 商品处理结束
                case 'plugins_service_goods_handle_end' :
                    if(!empty($params['goods']) && !empty($params['goods']['id']))
                    {
                        // 静态类似存储数据，避免重复读取浪费mysql
                        static $system_base_service_goods_handle_info_static_data = [];
                        if(array_key_exists($params['goods']['id'], $system_base_service_goods_handle_info_static_data))
                        {
                            $old = $system_base_service_goods_handle_info_static_data[$params['goods']['id']];
                        } else {
                            $old = Db::name('Goods')->field('price,min_price,max_price')->find($params['goods']['id']);
                            $system_base_service_goods_handle_info_static_data[$params['goods']['id']] = $old;
                        }
                        if(!empty($old))
                        {
                            // 展示销售价格
                            if($status == 1 && isset($params['goods']['price']))
                            {
                                $temp = explode('-', $params['goods']['price']);
                                $temp_old = explode('-', $old['price']);
                                if($temp[count($temp)-1] < $temp_old[count($temp_old)-1])
                                {
                                    $status = 0;
                                }
                            }

                            // 最低价
                            if($status == 1 && isset($params['goods']['min_price']))
                            {
                                if($params['goods']['min_price'] < $old['min_price'])
                                {
                                    $status = 0;
                                }
                            }

                            // 最高价
                            if($status == 1 && isset($params['goods']['max_price']))
                            {
                                if($params['goods']['max_price'] < $old['max_price'])
                                {
                                    $status = 0;
                                }
                            }
                        }
                    }
                    break;

                // 商品列表处理结束
                case 'plugins_service_goods_list_handle_end' :
                    $result = [];
                    if(!empty($params['data']))
                    {
                        // key字段
                        $key_field = empty($params['params']['data_key_field']) ? 'id' : $params['params']['data_key_field'];
                        $goods_ids = array_column($params['data'], $key_field);
                        // 静态类似存储数据，避免重复读取浪费mysql
                        $key = md5(implode('', $goods_ids));
                        static $system_base_service_goods_list_handle_column_static_data = [];
                        if(array_key_exists($key, $system_base_service_goods_list_handle_column_static_data))
                        {
                            $old = $system_base_service_goods_list_handle_column_static_data[$key];
                        } else {
                            $old = Db::name('Goods')->where(['id'=>$goods_ids])->column('id,price,min_price,max_price', 'id');
                            $system_base_service_goods_list_handle_column_static_data[$key] = $old;
                        }
                        if(!empty($old))
                        {
                            foreach($params['data'] as $goods)
                            {
                                if(array_key_exists($goods[$key_field], $old))
                                {
                                    $status = 1;
                                    $item_old = $old[$goods[$key_field]];
                                    // 展示销售价格
                                    if($status == 1 && isset($goods['price']))
                                    {
                                        $temp = explode('-', $goods['price']);
                                        $temp_old = explode('-', $item_old['price']);
                                        if($temp[count($temp)-1] < $temp_old[count($temp_old)-1])
                                        {
                                            $status = 0;
                                        }
                                    }

                                    // 最低价
                                    if($status == 1 && isset($goods['min_price']))
                                    {
                                        if($goods['min_price'] < $item_old['min_price'])
                                        {
                                            $status = 0;
                                        }
                                    }

                                    // 最高价
                                    if($status == 1 && isset($goods['max_price']))
                                    {
                                        if($goods['max_price'] < $item_old['max_price'])
                                        {
                                            $status = 0;
                                        }
                                    }

                                    $result[$goods[$key_field]] = $status;
                                }
                            }
                        }
                    }
                    return $result;
                    break;

                // 获取规格详情
                case 'plugins_service_goods_spec_base' :
                    if(!empty($params['data']) && !empty($params['data']['spec_base']) && !empty($params['data']['spec_base']['id']) && !empty($params['data']['spec_base']['goods_id']) && isset($params['data']['spec_base']['price']))
                    {
                        $base_id = $params['data']['spec_base']['id'];
                        // 静态类似存储数据，避免重复读取浪费mysql
                        static $system_base_service_goods_spec_base_price_static_data = [];
                        if(array_key_exists($base_id, $system_base_service_goods_spec_base_price_static_data))
                        {
                            $price_old = $system_base_service_goods_spec_base_price_static_data[$base_id];
                        } else {
                            $price_old = Db::name('GoodsSpecBase')->where(['id'=>$base_id])->value('price');
                            $system_base_service_goods_spec_base_price_static_data[$base_id] = $price_old;
                        }
                        if($params['data']['spec_base']['price'] < $price_old)
                        {
                            $status = 0;
                        }
                    }
                    break;
            }
        }

        // 返回状态、默认支持
        return $status;
    }

    /**
     * 附件地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-06
     * @desc    description
     */
    public static function AttachmentHost()
    {
        static $site_attachment_host_static_data = null;
        if($site_attachment_host_static_data === null)
        {
            $site_attachment_host_static_data = MyConfig('shopxo.attachment_host');
        }
        return $site_attachment_host_static_data;
    }
}
?>