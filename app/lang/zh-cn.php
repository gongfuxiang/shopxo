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

/**
 * 公共语言包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
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
        ],

    // 用户注册类型列表
    'common_user_reg_type_list'          =>  [
            0 => ['value' => 'sms', 'name' => '短信'],
            1 => ['value' => 'email', 'name' => '邮箱'],
            2 => ['value' => 'username', 'name' => '用户名'],
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
    'common_state_list'             =>  [
            0 => ['id' => 0, 'name' => '不可用'],
            1 => ['id' => 1, 'name' => '可用', 'checked' => true],
        ],

    // excel编码列表
    'common_excel_charset_list'         =>  [
            0 => ['id' => 0, 'value' => 'utf-8', 'name' => 'utf-8', 'checked' => true],
            1 => ['id' => 1, 'value' => 'gbk', 'name' => 'gbk'],
        ],

    // 支付状态
    'common_order_pay_status'   => [
            0 => ['id' => 0, 'name' => '待支付', 'checked' => true],
            1 => ['id' => 1, 'name' => '已支付'],
            2 => ['id' => 2, 'name' => '已退款'],
            3 => ['id' => 3, 'name' => '部分退款'],
        ],

    // 用户端 - 订单管理
    'common_order_user_status'          =>  [
            0 => ['id' => 0, 'name' => '待确认', 'checked' => true],
            1 => ['id' => 1, 'name' => '待付款'],
            2 => ['id' => 2, 'name' => '待发货'],
            3 => ['id' => 3, 'name' => '待收货'],
            4 => ['id' => 4, 'name' => '已完成'],
            5 => ['id' => 5, 'name' => '已取消'],
            6 => ['id' => 6, 'name' => '已关闭'],
        ],

    // 后台管理 - 订单管理
    'common_order_admin_status'         =>  [
            0 => ['id' => 0, 'name' => '待确认', 'checked' => true],
            1 => ['id' => 1, 'name' => '已确认/待支付'],
            2 => ['id' => 2, 'name' => '待发货/待取货'],
            3 => ['id' => 3, 'name' => '已发货/待收货'],
            4 => ['id' => 4, 'name' => '已完成'],
            5 => ['id' => 5, 'name' => '已取消'],
            6 => ['id' => 6, 'name' => '已关闭'],
        ],

    // 优惠券类型
    'common_coupon_type'            =>  [
            0 => ['id' => 0, 'name' => '缤纷活动', 'checked' => true],
            1 => ['id' => 1, 'name' => '注册送'],
        ],

    // 用户优惠券状态
    'common_user_coupon_status'         =>  [
            0 => ['id' => 0, 'name' => '未使用', 'checked' => true],
            1 => ['id' => 1, 'name' => '已使用'],
            2 => ['id' => 2, 'name' => '已过期'],
        ],

    // 所属平台
    'common_platform_type'          =>  [
            'pc'     => ['value' => 'pc', 'name' => 'PC网站'],
            'h5'     => ['value' => 'h5', 'name' => 'H5手机网站'],
            'ios' => ['value' => 'ios', 'name' => '苹果APP'],
            'android' => ['value' => 'android', 'name' => '安卓APP'],
            'weixin' => ['value' => 'weixin', 'name' => '微信小程序'],
            'alipay' => ['value' => 'alipay', 'name' => '支付宝小程序'],
            'baidu' => ['value' => 'baidu', 'name' => '百度小程序'],
            'toutiao' => ['value' => 'toutiao', 'name' => '头条小程序'],
            'qq' => ['value' => 'qq', 'name' => 'QQ小程序'],
        ],

    // app平台
    'common_app_type'          =>  [
            'ios' => ['value' => 'ios', 'name' => '苹果APP'],
            'android' => ['value' => 'android', 'name' => '安卓APP'],
        ],

    // 小程序平台
    'common_appmini_type'          =>  [
            'weixin' => ['value' => 'weixin', 'name' => '微信小程序'],
            'alipay' => ['value' => 'alipay', 'name' => '支付宝小程序'],
            'baidu' => ['value' => 'baidu', 'name' => '百度小程序'],
            'toutiao' => ['value' => 'toutiao', 'name' => '头条小程序'],
            'qq' => ['value' => 'qq', 'name' => 'QQ小程序'],
        ],

    // 小程序url跳转类型
    'common_jump_url_type'  =>  [
            0 => ['value' => 0, 'name' => 'WEB页面'],
            1 => ['value' => 1, 'name' => '内部页面(小程序或APP内部地址)'],
            2 => ['value' => 2, 'name' => '外部小程序(同一个主体下的小程序appid)'],
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

    // 发送状态
    'common_send_status_list'       =>  [
            0 => ['value' => 0, 'name' => '未发送'],
            1 => ['value' => 1, 'name' => '发送中'],
            2 => ['value' => 2, 'name' => '发送成功'],
            3 => ['value' => 3, 'name' => '部分成功'],
            4 => ['value' => 4, 'name' => '发送失败'],
        ],

    // 发布状态
    'common_release_status_list'        =>  [
            0 => ['value' => 0, 'name' => '未发布'],
            1 => ['value' => 1, 'name' => '发布中'],
            2 => ['value' => 2, 'name' => '已发布'],
            3 => ['value' => 3, 'name' => '部分成功'],
            4 => ['value' => 4, 'name' => '发布失败'],
        ],

    // 处理状态
    'common_handle_status_list' =>  [
            0 => ['value' => 0, 'name' => '未处理'],
            1 => ['value' => 1, 'name' => '处理中'],
            2 => ['value' => 2, 'name' => '已处理'],
            3 => ['value' => 3, 'name' => '部分成功'],
            4 => ['value' => 4, 'name' => '处理失败'],
        ],

    // 支付宝生活号菜单事件类型
    'common_alipay_life_menu_action_type_list'  =>  [
            0 => ['value' => 0, 'out_value' => 'out', 'name' => '事件型菜单'],
            1 => ['value' => 1, 'out_value' => 'link', 'name' => '链接型菜单'],
            2 => ['value' => 2, 'out_value' => 'tel', 'name' => '点击拨打电话'],
            3 => ['value' => 3, 'out_value' => 'map', 'name' => '点击查看地图'],
            4 => ['value' => 4, 'out_value' => 'consumption', 'name' => '点击查看用户与生活号'],
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
            0 => ['value' => 0, 'name' => '销售'],
            1 => ['value' => 1, 'name' => '展示'],
            2 => ['value' => 2, 'name' => '自提'],
            3 => ['value' => 3, 'name' => '虚拟销售'],
            4 => ['value' => 4, 'name' => '销售+自提', 'is_ext' => 1],
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
    'goods_order_by_rule_list'           => [
            0 => ['value' => 'desc', 'name' => '降序(desc)', 'checked' => true],
            1 => ['value' => 'asc', 'name' => '升序(asc)'],
        ],

    // 首页数据类型
    'common_site_floor_data_type_list'           => [
            0 => ['value' => 0, 'name' => '自动模式', 'checked' => true],
            1 => ['value' => 1, 'name' => '手动模式'],
            2 => ['value' => 2, 'name' => '拖拽模式'],
        ],

    // 色彩值
    'common_color_list'                 =>  [
            '',
            'am-badge-primary',
            'am-badge-secondary',
            'am-badge-success',
            'am-badge-warning',
            'am-badge-danger',
        ],

    // 文件上传错误码
    'common_file_upload_error_list'     =>  [
            1 => '文件大小超过服务器允许上传的最大值',
            2 => '文件大小超出浏览器限制，查看是否超过[站点设置->附件最大限制]',
            3 => '文件仅部分被上传',
            4 => '没有找到要上传的文件',
            5 => '没有找到服务器临时文件夹',
            6 => '没有找到服务器临时文件夹',
            7 => '文件写入失败',
            8 => '文件上传扩展没有打开',
        ],

    // 正则
    // 用户名
    'common_regex_username'             =>  '^[A-Za-z0-9_]{2,18}$',

    // 用户名
    'common_regex_pwd'                  =>  '^.{6,18}$',

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
?>