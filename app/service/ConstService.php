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
            'common_system_version_list'          =>  [
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
            ],

            // 用户注册类型列表
            'common_user_reg_type_list'          =>  [
                0 => ['value' => 'username', 'name' => '账号'],
                1 => ['value' => 'sms', 'name' => '短信'],
                2 => ['value' => 'email', 'name' => '邮箱'],
            ],

            // 登录方式
            'common_login_type_list'     =>  [
                0 => ['value' => 'username', 'name' => '帐号密码', 'checked' => true],
                1 => ['value' => 'email', 'name' => '邮箱验证码'],
                2 => ['value' => 'sms', 'name' => '手机验证码'],
            ],
            
            // 性别
            'common_gender_list'                =>  [
                0 => ['id' => 0, 'name' => '保密', 'checked' => true],
                1 => ['id' => 1, 'name' => '女'],
                2 => ['id' => 2, 'name' => '男'],
            ],

            // 关闭开启状态
            'common_close_open_list'          =>  [
                0 => ['value' => 0, 'name' => '关闭'],
                1 => ['value' => 1, 'name' => '开启'],
            ],

            // 是否启用
            'common_is_enable_tips'             =>  [
                0 => ['id' => 0, 'name' => '未启用'],
                1 => ['id' => 1, 'name' => '已启用'],
            ],
            'common_is_enable_list'             =>  [
                0 => ['id' => 0, 'name' => '不启用'],
                1 => ['id' => 1, 'name' => '启用', 'checked' => true],
            ],

            // 是否显示
            'common_is_show_list'               =>  [
                0 => ['id' => 0, 'name' => '不显示'],
                1 => ['id' => 1, 'name' => '显示', 'checked' => true],
            ],

            // 状态
            'common_state_list'                 =>  [
                0 => ['id' => 0, 'name' => '不可用'],
                1 => ['id' => 1, 'name' => '可用', 'checked' => true],
            ],

            // excel编码列表
            'common_excel_charset_list'         =>  [
                0 => ['id' => 0, 'value' => 'utf-8', 'name' => 'utf-8', 'checked' => true],
                1 => ['id' => 1, 'value' => 'gbk', 'name' => 'gbk'],
            ],

            // excel导出类型列表
            'common_excel_export_type_list'     =>  [
                0 => ['id' => 0, 'name' => 'CSV', 'checked' => true],
                1 => ['id' => 1, 'name' => 'Excel'],
            ],

            // 地图类型列表
            'common_map_type_list'     =>  [
                'baidu'     => ['id' => 'baidu', 'name' => '百度地图', 'checked' => true],
                'amap'      => ['id' => 'amap', 'name' => '高德地图'],
                'tencent'   => ['id' => 'tencent', 'name' => '腾讯地图'],
                'tianditu'  => ['id' => 'tianditu', 'name' => '天地图'],
            ],

            // 支付支付状态
            'common_order_pay_status'   => [
                0 => ['id' => 0, 'name' => '待支付', 'checked' => true],
                1 => ['id' => 1, 'name' => '已支付'],
                2 => ['id' => 2, 'name' => '已退款'],
                3 => ['id' => 3, 'name' => '部分退款'],
            ],

            // 订单状态
            'common_order_status'          =>  [
                0 => ['id' => 0, 'name' => '待确认', 'checked' => true],
                1 => ['id' => 1, 'name' => '待付款'],
                2 => ['id' => 2, 'name' => '待发货'],
                3 => ['id' => 3, 'name' => '待收货'],
                4 => ['id' => 4, 'name' => '已完成'],
                5 => ['id' => 5, 'name' => '已取消'],
                6 => ['id' => 6, 'name' => '已关闭'],
            ],

            // 所属平台
            'common_platform_type'          =>  [
                'pc'        => ['value' => 'pc', 'name' => 'PC网站'],
                'h5'        => ['value' => 'h5', 'name' => 'H5手机网站'],
                'ios'       => ['value' => 'ios', 'name' => '苹果APP'],
                'android'   => ['value' => 'android', 'name' => '安卓APP'],
                'weixin'    => ['value' => 'weixin', 'name' => '微信小程序'],
                'alipay'    => ['value' => 'alipay', 'name' => '支付宝小程序'],
                'baidu'     => ['value' => 'baidu', 'name' => '百度小程序'],
                'toutiao'   => ['value' => 'toutiao', 'name' => '头条小程序'],
                'qq'        => ['value' => 'qq', 'name' => 'QQ小程序'],
                'kuaishou'  => ['value' => 'kuaishou', 'name' => '快手小程序'],
            ],

            // app平台
            'common_app_type'          =>  [
                'ios'       => ['value' => 'ios', 'name' => '苹果APP'],
                'android'   => ['value' => 'android', 'name' => '安卓APP'],
            ],

            // 小程序平台
            'common_appmini_type'          =>  [
                'weixin'    => ['value' => 'weixin', 'name' => '微信小程序'],
                'alipay'    => ['value' => 'alipay', 'name' => '支付宝小程序'],
                'baidu'     => ['value' => 'baidu', 'name' => '百度小程序'],
                'toutiao'   => ['value' => 'toutiao', 'name' => '头条小程序'],
                'qq'        => ['value' => 'qq', 'name' => 'QQ小程序'],
                'kuaishou'  => ['value' => 'kuaishou', 'name' => '快手小程序'],
            ],

            // 扣除库存规则
            'common_deduction_inventory_rules_list' =>  [
                0 => ['id' => 0, 'name' => '订单确认成功'],
                1 => ['id' => 1, 'name' => '订单支付成功'],
                2 => ['id' => 2, 'name' => '订单发货'],
            ],

            // 商品增加销量规则
            'common_sales_count_inc_rules_list'     =>  [
                0 => ['id' => 0, 'name' => '订单支付'],
                1 => ['id' => 1, 'name' => '订单收货'],
            ],

            // 是否已读
            'common_is_read_list'               =>  [
                0 => ['id' => 0, 'name' => '未读', 'checked' => true],
                1 => ['id' => 1, 'name' => '已读'],
            ],

            // 消息类型
            'common_message_type_list'          =>  [
                0 => ['id' => 0, 'name' => '默认', 'checked' => true],
            ],

            // 用户积分 - 操作类型
            'common_integral_log_type_list'             =>  [
                0 => ['id' => 0, 'name' => '减少', 'checked' => true],
                1 => ['id' => 1, 'name' => '增加'],
            ],

            // 是否上架/下架
            'common_is_shelves_list'                    =>  [
                0 => ['id' => 0, 'name' => '下架'],
                1 => ['id' => 1, 'name' => '上架', 'checked' => true],
            ],

            // 是否
            'common_is_text_list'   =>  [
                0 => ['id' => 0, 'name' => '否', 'checked' => true],
                1 => ['id' => 1, 'name' => '是'],
            ],

            // 用户状态
            'common_user_status_list'           =>  [
                0 => ['id' => 0, 'name' => '正常', 'checked' => true],
                1 => ['id' => 1, 'name' => '禁止发言', 'tips' => '用户被禁止发言'],
                2 => ['id' => 2, 'name' => '禁止登录', 'tips' => '用户被禁止登录'],
                3 => ['id' => 3, 'name' => '待审核', 'tips' => '用户等待审核中'],
            ],

            // 导航数据类型
            'common_nav_type_list'              =>  [
                'custom' => ['value'=>'custom', 'name'=>'自定义'],
                'article' => ['value'=>'article', 'name'=>'文章'],
                'customview' => ['value'=>'customview', 'name'=>'自定义页面'],
                'goods_category' => ['value'=>'goods_category', 'name'=>'商品分类'],
            ],

            // 搜索框下热门关键字类型
            'common_search_keywords_type_list'      =>  [
                0 => ['value' => 0, 'name' => '关闭'],
                1 => ['value' => 1, 'name' => '自动'],
                2 => ['value' => 2, 'name' => '自定义'],
            ],

            // app事件类型
            'common_app_event_type' =>  [
                0 => ['value' => 0, 'name' => 'WEB页面'],
                1 => ['value' => 1, 'name' => '内部页面(小程序/APP内部地址)'],
                2 => ['value' => 2, 'name' => '外部小程序(同一个主体下的小程序appid)'],
                3 => ['value' => 3, 'name' => '跳转原生地图查看指定位置'],
                4 => ['value' => 4, 'name' => '拨打电话'],
            ],

            // 订单售后类型
            'common_order_aftersale_type_list' =>  [
                0 => ['value' => 0, 'name' => '仅退款', 'desc' => '未收到货(未签收),协商同意前提下', 'icon' => 'am-icon-random', 'class' => 'am-fl'],
                1 => ['value' => 1, 'name' => '退款退货', 'desc' => '已收到货,需要退换已收到的货物', 'icon' => 'am-icon-retweet', 'class' => 'am-fr'],
            ],

            // 订单售后状态
            'common_order_aftersale_status_list' =>  [
                0 => ['value' => 0, 'name' => '待确认'],
                1 => ['value' => 1, 'name' => '待退货'],
                2 => ['value' => 2, 'name' => '待审核'],
                3 => ['value' => 3, 'name' => '已完成'],
                4 => ['value' => 4, 'name' => '已拒绝'],
                5 => ['value' => 5, 'name' => '已取消'],
            ],

            // 订单售后退款方式
            'common_order_aftersale_refundment_list' =>  [
                0 => ['value' => 0, 'name' => '原路退回'],
                1 => ['value' => 1, 'name' => '退至钱包'],
                2 => ['value' => 2, 'name' => '手动处理'],
            ],

            // 商品评分
            'common_goods_comments_rating_list' =>  [
                0 => ['value'=>0, 'name'=>'未评分', 'badge'=>''],
                1 => ['value'=>1, 'name'=>'1分', 'badge'=>'danger'],
                2 => ['value'=>2, 'name'=>'2分', 'badge'=>'warning'],
                3 => ['value'=>3, 'name'=>'3分', 'badge'=>'secondary'],
                4 => ['value'=>4, 'name'=>'4分', 'badge'=>'primary'],
                5 => ['value'=>5, 'name'=>'5分', 'badge'=>'success'],
            ],

            // 商品评论业务类型
            'common_goods_comments_business_type_list' =>  [
                'order' => ['value' => 'order', 'name' => '订单'],
            ],

            // 站点类型
            'common_site_type_list' =>  [
                0 => ['value' => 0, 'name' => '快递'],
                1 => ['value' => 1, 'name' => '展示'],
                2 => ['value' => 2, 'name' => '自提'],
                3 => ['value' => 3, 'name' => '虚拟售卖'],
                4 => ['value' => 4, 'name' => '快递+自提', 'is_ext' => 1],
            ],

            // 订单类型
            'common_order_type_list' =>  [
                0 => ['value' => 0, 'name' => '快递'],
                1 => ['value' => 1, 'name' => '展示'],
                2 => ['value' => 2, 'name' => '自提'],
                3 => ['value' => 3, 'name' => '虚拟销售'],
            ],

            // 管理员状态
            'common_admin_status_list'               =>  [
                0 => ['value' => 0, 'name' => '正常', 'checked' => true],
                1 => ['value' => 1, 'name' => '暂停'],
                2 => ['value' => 2, 'name' => '已离职'],
            ],

            // 支付日志状态
            'common_pay_log_status_list'            => [
                0 => ['value' => 0, 'name' => '待支付', 'checked' => true],
                1 => ['value' => 1, 'name' => '已支付'],
                2 => ['value' => 2, 'name' => '已关闭'],
            ],

            // 商品参数组件类型
            'common_goods_parameters_type_list'     => [
                0 => ['value' => 0, 'name' => '全部'],
                1 => ['value' => 1, 'name' => '详情', 'checked' => true],
                2 => ['value' => 2, 'name' => '基础'],
            ],

            // 商品关联排序类型
            'goods_order_by_type_list'              => [
                0 => ['value' => 'g.access_count,g.sales_count,g.id', 'name' => '综合', 'checked' => true],
                1 => ['value' => 'g.sales_count', 'name' => '销量'],
                2 => ['value' => 'g.access_count', 'name' => '热度'],
                3 => ['value' => 'g.min_price', 'name' => '价格'],
                4 => ['value' => 'g.id', 'name' => '最新'],
            ],

            // 商品关联排序规则
            'goods_order_by_rule_list'              => [
                0 => ['value' => 'desc', 'name' => '降序(desc)', 'checked' => true],
                1 => ['value' => 'asc', 'name' => '升序(asc)'],
            ],

            // 首页数据类型
            'common_site_floor_data_type_list'      => [
                0 => ['value' => 0, 'name' => '自动模式', 'checked' => true],
                1 => ['value' => 1, 'name' => '手动模式'],
                2 => ['value' => 2, 'name' => '拖拽模式'],
            ],

            // 文件上传错误码
            'common_file_upload_error_list'         =>  [
                1 => '文件大小超过服务器允许上传的最大值',
                2 => '文件大小超出浏览器限制，查看是否超过[站点设置->附件最大限制]',
                3 => '文件仅部分被上传',
                4 => '没有找到要上传的文件',
                5 => '没有找到服务器临时文件夹',
                6 => '没有找到服务器临时文件夹',
                7 => '文件写入失败',
                8 => '文件上传扩展没有打开',
            ],


            // -------------------- 正则 --------------------
            // 用户名
            'common_regex_username'             =>  '^[A-Za-z0-9_]{2,18}$',

            // 用户名
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


            // -------------------- 后端相关 --------------------
            // 图片验证码
            'site_images_verify_rules_list'  => [
                0 => ['value' => 'bgcolor', 'name' => '彩色背景'],
                1 => ['value' => 'textcolor', 'name' => '彩色文本'],
                2 => ['value' => 'point', 'name' => '干扰点'],
                3 => ['value' => 'line', 'name' => '干扰线'],
            ],

            // 时区
            'site_timezone_list' => [
                'Pacific/Pago_Pago' => '(标准时-11:00) 中途岛、萨摩亚群岛',
                'Pacific/Rarotonga' => '(标准时-10:00) 夏威夷',
                'Pacific/Gambier' => '(标准时-9:00) 阿拉斯加',
                'America/Dawson' => '(标准时-8:00) 太平洋时间(美国和加拿大)',
                'America/Creston' => '(标准时-7:00) 山地时间(美国和加拿大)',
                'America/Belize' => '(标准时-6:00) 中部时间(美国和加拿大)、墨西哥城',
                'America/Eirunepe' => '(标准时-5:00) 东部时间(美国和加拿大)、波哥大',
                'America/Antigua' => '(标准时-4:00) 大西洋时间(加拿大)、加拉加斯',
                'America/Argentina/Buenos_Aires' => '(标准时-3:00) 巴西、布宜诺斯艾利斯、乔治敦',
                'America/Noronha' => '(标准时-2:00) 中大西洋',
                'Atlantic/Cape_Verde' => '(标准时-1:00) 亚速尔群岛、佛得角群岛',
                'Africa/Ouagadougou' => '(格林尼治标准时) 西欧时间、伦敦、卡萨布兰卡',
                'Europe/Andorra' => '(标准时+1:00) 中欧时间、安哥拉、利比亚',
                'Europe/Mariehamn' => '(标准时+2:00) 东欧时间、开罗，雅典',
                'Asia/Bahrain' => '(标准时+3:00) 巴格达、科威特、莫斯科',
                'Asia/Dubai' => '(标准时+4:00) 阿布扎比、马斯喀特、巴库',
                'Asia/Kolkata' => '(标准时+5:00) 叶卡捷琳堡、伊斯兰堡、卡拉奇',
                'Asia/Dhaka' => '(标准时+6:00) 阿拉木图、 达卡、新亚伯利亚',
                'Indian/Christmas' => '(标准时+7:00) 曼谷、河内、雅加达',
                'Asia/Shanghai' => '(标准时+8:00)北京、重庆、香港、新加坡',
                'Australia/Darwin' => '(标准时+9:00) 东京、汉城、大阪、雅库茨克',
                'Australia/Adelaide' => '(标准时+10:00) 悉尼、关岛',
                'Australia/Currie' => '(标准时+11:00) 马加丹、索罗门群岛',
                'Pacific/Fiji' => '(标准时+12:00) 奥克兰、惠灵顿、堪察加半岛'
            ],

            // seo
            // url模式列表
            'seo_url_model_list'        =>  [
                0 => ['value' => 0, 'name' => '兼容模式', 'checked' => true],
                1 => ['value' => 1, 'name' => 'PATHINFO模式'],
                2 => ['value' => 2, 'name' => 'PATHINFO模式+短地址'],
            ],
        ];
    }
}
?>