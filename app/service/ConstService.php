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

/**
 * 公共常量数据
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-08-13
 * @desc    description
 */
class ConstService
{
    /**
     * 获取数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-13
     * @desc    description
     * @param   [string]           $key     [数据key]
     * @param   [mixed]            $default [默认数据]
     */
    public static function Run($key = '', $default = null)
    {
        // 数据定义
        $container = self::ConstData();

        // 是否读取全部
        if(empty($key))
        {
            $data = $container;
        } else {
            // 是否存在多级
            $arr = explode('.', $key);
            if(count($arr) == 1)
            {
                $data = array_key_exists($key, $container) ? $container[$key] : $default;
            } else {
                $data = $container;
                foreach($arr as $v)
                {
                    if(isset($data[$v]))
                    {
                        $data = $data[$v];
                    } else {
                        $data = $default;
                        break;
                    }
                }
            }
        }

        // 常量数据读取钩子
        $hook_name = 'plugins_service_const_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'key'           => $key,
            'default'       => $default,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 数据定义容器
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-14
     * @desc    description
     */
    public static function ConstData()
    {
        return [
            // -------------------- 公共 --------------------
            // 系统版本列表
            'common_system_version_list'          => [
                '1.1.0' => ['value' => '1.1.0', 'name' => 'v1.1.0'],
                '1.2.0' => ['value' => '1.2.0', 'name' => 'v1.2.0'],
                '1.3.0' => ['value' => '1.3.0', 'name' => 'v1.3.0'],
                '1.4.0' => ['value' => '1.4.0', 'name' => 'v1.4.0'],
                '1.5.0' => ['value' => '1.5.0', 'name' => 'v1.5.0'],
                '1.6.0' => ['value' => '1.6.0', 'name' => 'v1.6.0'],
                '1.7.0' => ['value' => '1.7.0', 'name' => 'v1.7.0'],
                '1.8.0' => ['value' => '1.8.0', 'name' => 'v1.8.0'],
                '1.8.1' => ['value' => '1.8.1', 'name' => 'v1.8.1'],
                '1.9.0' => ['value' => '1.9.0', 'name' => 'v1.9.0'],
                '1.9.1' => ['value' => '1.9.1', 'name' => 'v1.9.1'],
                '1.9.2' => ['value' => '1.9.2', 'name' => 'v1.9.2'],
                '1.9.3' => ['value' => '1.9.3', 'name' => 'v1.9.3'],
                '2.0.0' => ['value' => '2.0.0', 'name' => 'v2.0.0'],
                '2.0.1' => ['value' => '2.0.1', 'name' => 'v2.0.1'],
                '2.0.2' => ['value' => '2.0.2', 'name' => 'v2.0.2'],
                '2.0.3' => ['value' => '2.0.3', 'name' => 'v2.0.3'],
                '2.1.0' => ['value' => '2.1.0', 'name' => 'v2.1.0'],
                '2.2.0' => ['value' => '2.2.0', 'name' => 'v2.2.0'],
                '2.2.1' => ['value' => '2.2.1', 'name' => 'v2.2.1'],
                '2.2.2' => ['value' => '2.2.2', 'name' => 'v2.2.2'],
                '2.2.3' => ['value' => '2.2.3', 'name' => 'v2.2.3'],
                '2.2.4' => ['value' => '2.2.4', 'name' => 'v2.2.4'],
                '2.2.5' => ['value' => '2.2.5', 'name' => 'v2.2.5'],
                '2.2.6' => ['value' => '2.2.6', 'name' => 'v2.2.6'],
                '2.2.7' => ['value' => '2.2.7', 'name' => 'v2.2.7'],
                '2.2.8' => ['value' => '2.2.8', 'name' => 'v2.2.8'],
                '2.2.9' => ['value' => '2.2.9', 'name' => 'v2.2.9'],
                '2.3.0' => ['value' => '2.3.0', 'name' => 'v2.3.0'],
                '2.3.1' => ['value' => '2.3.1', 'name' => 'v2.3.1'],
                '2.3.2' => ['value' => '2.3.2', 'name' => 'v2.3.2'],
                '2.3.3' => ['value' => '2.3.3', 'name' => 'v2.3.3'],
                '3.0.0' => ['value' => '3.0.0', 'name' => 'v3.0.0'],
                '3.0.1' => ['value' => '3.0.1', 'name' => 'v3.0.1'],
                '3.0.2' => ['value' => '3.0.2', 'name' => 'v3.0.2'],
                '3.0.3' => ['value' => '3.0.3', 'name' => 'v3.0.3'],
                '4.0.0' => ['value' => '4.0.0', 'name' => 'v4.0.0'],
            ],

            // 搜索排序方式
            'common_search_order_by_list' => [
                ['name' => MyLang('common_search_order_by_list.default'), 'type' => 'default', 'value' => 'desc'],
                ['name' => MyLang('common_search_order_by_list.sales'), 'type' => 'sales', 'value' => 'desc'],
                ['name' => MyLang('common_search_order_by_list.access'), 'type' => 'access', 'value' => 'desc'],
                ['name' => MyLang('common_search_order_by_list.price'), 'type' => 'price', 'value' => 'desc'],
                ['name' => MyLang('common_search_order_by_list.new'), 'type' => 'new', 'value' => 'desc'],
            ],
            // 用户注册类型列表
            'common_user_reg_type_list' => [
                ['value' => 'username', 'name' => MyLang('common_user_reg_type_list.username')],
                ['value' => 'sms', 'name' => MyLang('common_user_reg_type_list.sms')],
                ['value' => 'email', 'name' => MyLang('common_user_reg_type_list.email')],
            ],
            // 登录方式
            'common_login_type_list' => [
                ['value' => 'username', 'name' => MyLang('common_login_type_list.username'), 'checked' => true],
                ['value' => 'sms', 'name' => MyLang('common_login_type_list.sms')],
                ['value' => 'email', 'name' => MyLang('common_login_type_list.email')],
            ],
            // 性别
            'common_gender_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_gender_list.0'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_gender_list.1')],
                2 => ['id' => 2, 'name' => MyLang('common_gender_list.2')],
            ],
            // 关闭开启状态
            'common_close_open_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_close_open_list.0')],
                1 => ['value' => 1, 'name' => MyLang('common_close_open_list.1')],
            ],
            // 是否启用
            'common_is_enable_tips' => [
                0 => ['id' => 0, 'name' => MyLang('common_is_enable_tips.0')],
                1 => ['id' => 1, 'name' => MyLang('common_is_enable_tips.1')],
            ],
            'common_is_enable_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_is_enable_list.0')],
                1 => ['id' => 1, 'name' => MyLang('common_is_enable_list.1'), 'checked' => true],
            ],
            // 是否显示
            'common_is_show_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_is_show_list.0')],
                1 => ['id' => 1, 'name' => MyLang('common_is_show_list.1'), 'checked' => true],
            ],
            // excel编码列表
            'common_excel_charset_list' => [
                0 => ['id' => 0, 'value' => 'utf-8', 'name' => MyLang('common_excel_charset_list.0'), 'checked' => true],
                1 => ['id' => 1, 'value' => 'gbk', 'name' => MyLang('common_excel_charset_list.1')],
            ],
            // excel导出类型列表
            'common_excel_export_type_list'     => [
                0 => ['id' => 0, 'name' => MyLang('common_excel_export_type_list.0'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_excel_export_type_list.1')],
            ],
            // 地图类型列表
            'common_map_type_list' => [
                'baidu'     => ['id' => 'baidu', 'name' => MyLang('common_map_type_list.baidu'), 'checked' => true],
                'amap'      => ['id' => 'amap', 'name' => MyLang('common_map_type_list.amap')],
                'tencent'   => ['id' => 'tencent', 'name' => MyLang('common_map_type_list.tencent')],
                'tianditu'  => ['id' => 'tianditu', 'name' => MyLang('common_map_type_list.tianditu')],
            ],
            // 支付支付状态
            'common_order_pay_status' => [
                0 => ['id' => 0, 'name' => MyLang('common_order_pay_status.0'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_order_pay_status.1')],
                2 => ['id' => 2, 'name' => MyLang('common_order_pay_status.2')],
                3 => ['id' => 3, 'name' => MyLang('common_order_pay_status.3')],
            ],
            // 订单状态
            'common_order_status' => [
                0 => ['id' => 0, 'name' => MyLang('common_order_status.0'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_order_status.1')],
                2 => ['id' => 2, 'name' => MyLang('common_order_status.2')],
                3 => ['id' => 3, 'name' => MyLang('common_order_status.3')],
                4 => ['id' => 4, 'name' => MyLang('common_order_status.4')],
                5 => ['id' => 5, 'name' => MyLang('common_order_status.5')],
                6 => ['id' => 6, 'name' => MyLang('common_order_status.6')],
            ],
            // 所属平台
            'common_platform_type' => [
                'pc'        => ['value' => 'pc', 'name' => MyLang('common_platform_type.pc')],
                'h5'        => ['value' => 'h5', 'name' => MyLang('common_platform_type.h5')],
                'ios'       => ['value' => 'ios', 'name' => MyLang('common_platform_type.ios')],
                'android'   => ['value' => 'android', 'name' => MyLang('common_platform_type.android')],
                'weixin'    => ['value' => 'weixin', 'name' => MyLang('common_platform_type.weixin')],
                'alipay'    => ['value' => 'alipay', 'name' => MyLang('common_platform_type.alipay')],
                'baidu'     => ['value' => 'baidu', 'name' => MyLang('common_platform_type.baidu')],
                'toutiao'   => ['value' => 'toutiao', 'name' => MyLang('common_platform_type.toutiao')],
                'qq'        => ['value' => 'qq', 'name' => MyLang('common_platform_type.qq')],
                'kuaishou'  => ['value' => 'kuaishou', 'name' => MyLang('common_platform_type.kuaishou')],
            ],
            // app平台
            'common_app_type' => [
                'ios'       => ['value' => 'ios', 'name' => MyLang('common_app_type.ios')],
                'android'   => ['value' => 'android', 'name' => MyLang('common_app_type.android')],
            ],
            // 小程序平台
            'common_appmini_type' => [
                'weixin'    => ['value' => 'weixin', 'name' => MyLang('common_appmini_type.weixin')],
                'alipay'    => ['value' => 'alipay', 'name' => MyLang('common_appmini_type.alipay')],
                'baidu'     => ['value' => 'baidu', 'name' => MyLang('common_appmini_type.baidu')],
                'toutiao'   => ['value' => 'toutiao', 'name' => MyLang('common_appmini_type.toutiao')],
                'qq'        => ['value' => 'qq', 'name' => MyLang('common_appmini_type.qq')],
                'kuaishou'  => ['value' => 'kuaishou', 'name' => MyLang('common_appmini_type.kuaishou')],
            ],
            // 扣除库存规则
            'common_deduction_inventory_rules_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_deduction_inventory_rules_list.0')],
                1 => ['id' => 1, 'name' => MyLang('common_deduction_inventory_rules_list.1')],
                2 => ['id' => 2, 'name' => MyLang('common_deduction_inventory_rules_list.2')],
            ],
            // 商品增加销量规则
            'common_sales_count_inc_rules_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_sales_count_inc_rules_list.0')],
                1 => ['id' => 1, 'name' => MyLang('common_sales_count_inc_rules_list.1')],
            ],
            // 是否已读
            'common_is_read_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_is_read_list.0'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_is_read_list.1')],
            ],
            // 消息类型
            'common_message_type_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_message_type_list.0'), 'checked' => true],
            ],
            // 用户积分 - 操作类型
            'common_integral_log_type_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_integral_log_type_list.0'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_integral_log_type_list.1')],
            ],
            // 是否上架/下架
            'common_is_shelves_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_is_shelves_list.0')],
                1 => ['id' => 1, 'name' => MyLang('common_is_shelves_list.1'), 'checked' => true],
            ],
            // 是否
            'common_is_text_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_is_text_list.0'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_is_text_list.1')],
            ],
            // 用户状态
            'common_user_status_list' => [
                0 => ['id' => 0, 'name' => MyLang('common_user_status_list.0.name'), 'checked' => true],
                1 => ['id' => 1, 'name' => MyLang('common_user_status_list.1.name'), 'tips' => MyLang('common_user_status_list.1.tips')],
                2 => ['id' => 2, 'name' => MyLang('common_user_status_list.2.name'), 'tips' => MyLang('common_user_status_list.2.tips')],
                3 => ['id' => 3, 'name' => MyLang('common_user_status_list.3.name'), 'tips' => MyLang('common_user_status_list.3.tips')],
            ],
            // 导航数据类型
            'common_nav_type_list' => [
                'custom'          => ['value' => 'custom', 'name' => MyLang('common_nav_type_list.custom')],
                'article'         => ['value' => 'article', 'name' => MyLang('common_nav_type_list.article')],
                'customview'      => ['value' => 'customview', 'name' => MyLang('common_nav_type_list.customview')],
                'goods_category'  => ['value' => 'goods_category', 'name' => MyLang('common_nav_type_list.goods_category')],
            ],
            // 搜索框下热门关键字类型
            'common_search_keywords_type_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_search_keywords_type_list.0')],
                1 => ['value' => 1, 'name' => MyLang('common_search_keywords_type_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_search_keywords_type_list.2')],
            ],
            // app事件类型
            'common_app_event_type' => [
                0 => ['value' => 0, 'name' => MyLang('common_app_event_type.0')],
                1 => ['value' => 1, 'name' => MyLang('common_app_event_type.1')],
                2 => ['value' => 2, 'name' => MyLang('common_app_event_type.2')],
                3 => ['value' => 3, 'name' => MyLang('common_app_event_type.3')],
                4 => ['value' => 4, 'name' => MyLang('common_app_event_type.4')],
            ],
            // 订单售后类型
            'common_order_aftersale_type_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_order_aftersale_type_list.0.name'), 'desc' => MyLang('common_order_aftersale_type_list.0.desc'), 'icon' => 'am-icon-random'],
                1 => ['value' => 1, 'name' => MyLang('common_order_aftersale_type_list.1.name'), 'desc' => MyLang('common_order_aftersale_type_list.1.desc'), 'icon' => 'am-icon-retweet'],
            ],
            // 订单售后状态
            'common_order_aftersale_status_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_order_aftersale_status_list.0')],
                1 => ['value' => 1, 'name' => MyLang('common_order_aftersale_status_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_order_aftersale_status_list.2')],
                3 => ['value' => 3, 'name' => MyLang('common_order_aftersale_status_list.3')],
                4 => ['value' => 4, 'name' => MyLang('common_order_aftersale_status_list.4')],
                5 => ['value' => 5, 'name' => MyLang('common_order_aftersale_status_list.5')],
            ],
            // 订单售后退款方式
            'common_order_aftersale_refundment_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_order_aftersale_refundment_list.0')],
                1 => ['value' => 1, 'name' => MyLang('common_order_aftersale_refundment_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_order_aftersale_refundment_list.2')],
            ],
            // 商品评分
            'common_goods_comments_rating_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_goods_comments_rating_list.0'), 'badge' => ''],
                1 => ['value' => 1, 'name' => MyLang('common_goods_comments_rating_list.1'), 'badge' => 'danger'],
                2 => ['value' => 2, 'name' => MyLang('common_goods_comments_rating_list.2'), 'badge' => 'warning'],
                3 => ['value' => 3, 'name' => MyLang('common_goods_comments_rating_list.3'), 'badge' => 'secondary'],
                4 => ['value' => 4, 'name' => MyLang('common_goods_comments_rating_list.4'), 'badge' => 'primary'],
                5 => ['value' => 5, 'name' => MyLang('common_goods_comments_rating_list.5'), 'badge' => 'success'],
            ],
            // 商品评论业务类型
            'common_goods_comments_business_type_list' => [
                'order' => ['value' => 'order', 'name' => MyLang('common_goods_comments_business_type_list.order')],
            ],
            // 站点类型
            'common_site_type_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_site_type_list.0')],
                1 => ['value' => 1, 'name' => MyLang('common_site_type_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_site_type_list.2')],
                3 => ['value' => 3, 'name' => MyLang('common_site_type_list.3')],
                4 => ['value' => 4, 'name' => MyLang('common_site_type_list.4'), 'is_ext' => 1],
            ],
            // 订单类型
            'common_order_type_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_order_type_list.0')],
                1 => ['value' => 1, 'name' => MyLang('common_order_type_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_order_type_list.2')],
                3 => ['value' => 3, 'name' => MyLang('common_order_type_list.3')],
            ],
            // 下单站点类型列表
            'common_buy_site_model_list' => [
                ['value' => 0, 'name' => MyLang('common_buy_site_model_list.0')],
                ['value' => 2, 'name' => MyLang('common_buy_site_model_list.2')],
            ],
            // 管理员状态
            'common_admin_status_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_admin_status_list.0'), 'checked' => true],
                1 => ['value' => 1, 'name' => MyLang('common_admin_status_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_admin_status_list.2')],
            ],
            // 支付日志状态
            'common_pay_log_status_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_pay_log_status_list.0'), 'checked' => true],
                1 => ['value' => 1, 'name' => MyLang('common_pay_log_status_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_pay_log_status_list.2')],
            ],
            // 支付日志业务类型
            'common_pay_log_business_type_list' => [
                'order'   => ['value' => 'order', 'name' => MyLang('common_pay_log_business_type_list.order')],
            ],
            // 商品参数组件类型
            'common_goods_parameters_type_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_goods_parameters_type_list.0')],
                1 => ['value' => 1, 'name' => MyLang('common_goods_parameters_type_list.1'), 'checked' => true],
                2 => ['value' => 2, 'name' => MyLang('common_goods_parameters_type_list.2')],
            ],
            // 商品关联排序类型
            'common_goods_order_by_type_list' => [
                0 => ['value' => 'g.access_count,g.sales_count,g.id', 'name' => MyLang('common_goods_order_by_type_list.0'), 'checked' => true],
                1 => ['value' => 'g.sales_count', 'name' => MyLang('common_goods_order_by_type_list.1')],
                2 => ['value' => 'g.access_count', 'name' => MyLang('common_goods_order_by_type_list.2')],
                3 => ['value' => 'g.min_price', 'name' => MyLang('common_goods_order_by_type_list.3')],
                4 => ['value' => 'g.id', 'name' => MyLang('common_goods_order_by_type_list.4')],
            ],
            // 商品关联排序规则
            'common_goods_order_by_rule_list' => [
                0 => ['value' => 'desc', 'name' => MyLang('common_goods_order_by_rule_list.0'), 'checked' => true],
                1 => ['value' => 'asc', 'name' => MyLang('common_goods_order_by_rule_list.1')],
            ],
            // 首页数据类型
            'common_site_floor_data_type_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_site_floor_data_type_list.0'), 'checked' => true],
                1 => ['value' => 1, 'name' => MyLang('common_site_floor_data_type_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_site_floor_data_type_list.2')],
            ],
            // 文件上传错误码
            'common_file_upload_error_list' => [
                1 => MyLang('common_file_upload_error_list.1'),
                2 => MyLang('common_file_upload_error_list.2'),
                3 => MyLang('common_file_upload_error_list.3'),
                4 => MyLang('common_file_upload_error_list.4'),
                5 => MyLang('common_file_upload_error_list.5'),
                6 => MyLang('common_file_upload_error_list.6'),
                7 => MyLang('common_file_upload_error_list.7'),
                8 => MyLang('common_file_upload_error_list.8'),
            ],
            // 用户基础信息提示页面（index 首页、goods-category 商品分类、cart 购物车、 user 用户中心、share 分享）
            'common_user_base_popup_pages_list' => [
                'index'           => ['value' => 'index', 'name' => MyLang('common_user_base_popup_pages_list.index')],
                'goods-category'  => ['value' => 'goods-category', 'name' => MyLang('common_user_base_popup_pages_list.goods-category')],
                'cart'            => ['value' => 'cart', 'name' => MyLang('common_user_base_popup_pages_list.cart')],
                'user'            => ['value' => 'user', 'name' => MyLang('common_user_base_popup_pages_list.user')],
                'share'           => ['value' => 'share', 'name' => MyLang('common_user_base_popup_pages_list.share')],
            ],
            // 多语言code 语言编码 => name 语言名称）
            'common_multilingual_list' => [
                'zh'   => MyLang('common_multilingual_list.zh'),
                'cht'  => MyLang('common_multilingual_list.cht'),
                'en'   => MyLang('common_multilingual_list.en'),
                'ru'   => MyLang('common_multilingual_list.ru'),
                'kor'  => MyLang('common_multilingual_list.kor'),
                'th'   => MyLang('common_multilingual_list.th'),
                'jp'   => MyLang('common_multilingual_list.jp'),
                'de'   => MyLang('common_multilingual_list.de'),
                'nl'   => MyLang('common_multilingual_list.nl'),
                'vie'  => MyLang('common_multilingual_list.vie'),
                'it'   => MyLang('common_multilingual_list.it'),
                'spa'  => MyLang('common_multilingual_list.spa'),
                'fra'  => MyLang('common_multilingual_list.fra'),
                'swe'  => MyLang('common_multilingual_list.swe'),
            ],
            // 图片验证码
            'common_site_images_verify_rules_list' => [
                0 => ['value' => 'bgcolor', 'name' => MyLang('common_site_images_verify_rules_list.0')],
                1 => ['value' => 'textcolor', 'name' => MyLang('common_site_images_verify_rules_list.1')],
                2 => ['value' => 'point', 'name' => MyLang('common_site_images_verify_rules_list.2')],
                3 => ['value' => 'line', 'name' => MyLang('common_site_images_verify_rules_list.3')],
            ],
            // url模式列表
            'common_seo_url_model_list' => [
                0 => ['value' => 0, 'name' => MyLang('common_seo_url_model_list.0'), 'checked' => true],
                1 => ['value' => 1, 'name' => MyLang('common_seo_url_model_list.1')],
                2 => ['value' => 2, 'name' => MyLang('common_seo_url_model_list.2')],
            ],
            // 订单状态总数导航
            'common_order_status_step_total_list' =>  [
                ['value' => 0, 'name' => MyLang('common_order_status_step_total_list.0')],
                ['value' => 1, 'name' => MyLang('common_order_status_step_total_list.1')],
                ['value' => 2, 'name' => MyLang('common_order_status_step_total_list.2')],
                ['value' => 3, 'name' => MyLang('common_order_status_step_total_list.3')],
                ['value' => 4, 'name' => MyLang('common_order_status_step_total_list.4')],
                ['value' => 5, 'name' => MyLang('common_order_status_step_total_list.5')],
                ['value' => 6, 'name' => MyLang('common_order_status_step_total_list.6')],
                ['value' => 100, 'name' => MyLang('common_order_status_step_total_list.100')],
                ['value' => 101, 'name' => MyLang('common_order_status_step_total_list.101')],
            ],

            // -------------------- 正则 --------------------
            // 用户名
            'common_regex_username'             =>  '^[A-Za-z0-9_]{2,18}$',
            // 密码
            'common_regex_pwd'                  =>  '^.{6,18}$',
            // 包含字母和数字、6~16个字符
            'common_regex_alpha_number'         => '^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$',
            // 手机号码
            'common_regex_mobile'               =>  '^1((3|4|5|6|7|8|9){1}\d{1})\d{8}$',
            // 座机号码
            'common_regex_tel'                  =>  '^\d{3,4}-?\d{8}$',
            // 电子邮箱
            'common_regex_email'                =>  '^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$',
            // 身份证号码
            'common_regex_id_card'              =>  '^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$',
            // 价格格式
            'common_regex_price'                =>  '^([0-9]{1}\d{0,7})(\.\d{1,2})?$',
            // ip
            'common_regex_ip'                   =>  '^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$',
            // url
            'common_regex_url'                  =>  '^http[s]?:\/\/[A-Za-z0-9-]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$',
            // 控制器名称
            'common_regex_control'              =>  '^[A-Za-z]{1}[A-Za-z0-9_]{0,29}$',
            // 方法名称
            'common_regex_action'               =>  '^[A-Za-z]{1}[A-Za-z0-9_]{0,29}$',
            // 顺序
            'common_regex_sort'                 =>  '^[0-9]{1,3}$',
            // 日期
            'common_regex_date'                 =>  '^\d{4}-\d{2}-\d{2}$',
            // 分数
            'common_regex_score'                =>  '^[0-9]{1,3}$',
            // 分页
            'common_regex_page_number'          =>  '^[1-9]{1}[0-9]{0,2}$',
            // 时段格式 10:00-10:45
            'common_regex_interval'             =>  '^\d{2}\:\d{2}\-\d{2}\:\d{2}$',
            // 颜色
            'common_regex_color'                =>  '^(#([a-fA-F0-9]{6}|[a-fA-F0-9]{3}))?$',
            // id逗号隔开
            'common_regex_id_comma_split'       =>  '^\d(\d|,?)*\d$',
            // url伪静态后缀
            'common_regex_url_html_suffix'      =>  '^[a-z]{0,8}$',
            // 图片比例值
            'common_regex_image_proportion'     =>  '^([1-9]{1}[0-9]?|[1-9]{1}[0-9]?\.{1}[0-9]{1,2}|100|0)?$',
            // 版本号
            'common_regex_version'              => '^[0-9]{1,6}\.[0-9]{1,6}\.[0-9]{1,6}$',
        ];
    }
}
?>