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
 * 模块语言包-中文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => '商城首页',
        'back_to_the_home_title'                => '回到首页',
        'all_category_text'                     => '全部分类',
        'toggle_navigation_text'                => '导航切换',
        'login_title'                           => '登录',
        'register_title'                        => '注册',
        'logout_title'                          => '退出',
        'cancel_text'                           => '取消',
        'save_text'                             => '保存',
        'more_text'                             => '更多',
        'processing_in_text'                    => '处理中...',
        'upload_in_text'                        => '上传中...',
        'navigation_main_quick_name'            => '百宝箱',
        'no_relevant_data_tips'                 => '没有相关数据',
        'avatar_upload_title'                   => '头像上传',
        'choice_images_text'                    => '选择图片',
        'choice_images_error_tips'              => '请选择需要上传的图片',
        'confirm_upload_title'                  => '确认上传',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => '您好，欢迎来到',
        'header_top_nav_left_login_first'       => '您好',
        'header_top_nav_left_login_last'        => '，欢迎来到',
        // 搜索
        'search_input_placeholder'              => '其实搜索很简单^_^ !',
        'search_button_text'                    => '搜索',
        // 用户
        'avatar_upload_tips'                    => [
            '请在工作区域放大缩小及移动选取框，选择要裁剪的范围，裁切宽高比例固定；',
            '裁切后的效果为右侧预览图所显示，确认提交后生效；'
        ],
        'close_user_register_tips'              => '暂时关闭用户注册',
        'close_user_login_tips'                 => '暂时关闭用户登录',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => '您好，欢迎来到',
        'banner_right_article_title'            => '新闻头条',
        'design_browser_seo_title'              => '首页设计',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => '没有评论数据',
        ],
        // 基础
        'goods_no_data_tips'                    => '商品不存在或已删除',
        'panel_can_choice_spec_name'            => '可选规格',
        'recommend_goods_title'                 => '看了又看',
        'dynamic_scoring_name'                  => '动态评分',
        'no_scoring_data_tips'                  => '没有评分数据',
        'no_comments_data_tips'                 => '没有评价数据',
        'comments_first_name'                   => '评论于',
        'admin_reply_name'                      => '管理员回复：',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => '商品搜索',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => '商品分类',
        'no_category_data_tips'                 => '没有分类数据',
        'no_sub_category_data_tips'             => '没有子分类数据',
        'view_category_sub_goods_name'          => '查看分类下商品',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => '请选择商品',
        ],
        // 基础
        'browser_seo_title'                     => '购物车',
        'goods_list_thead_base'                 => '商品信息',
        'goods_list_thead_price'                => '单价',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '金额',
        'goods_item_total_name'                 => '合计',
        'summary_selected_goods_name'           => '已选商品',
        'summary_selected_goods_unit'           => '件',
        'summary_nav_goods_total'               => '合计：',
        'summary_nav_button_name'               => '结算',
        'no_cart_data_tips'                     => '您的购物车还是空的，您可以',
        'no_cart_data_my_favor_name'            => '我的收藏夹',
        'no_cart_data_my_order_name'            => '我的订单',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => '请选择地址',
            'payment_choice_tips'               => '请选择支付',
        ],
        // 基础
        'browser_seo_title'                     => '订单确认',
        'show_mode_not_allow_submit_order_tips' => '展示型不允许提交订单',
        'gooods_data_no_data_tips'              => '商品信息为空',
        'buy_item_order_title'                  => '订单信息',
        'buy_item_payment_title'                => '选择支付',
        'confirm_delivery_address_name'         => '确认收货地址',
        'use_new_address_name'                  => '使用新地址',
        'no_delivery_address_tips'              => '没有收货地址',
        'confirm_extraction_address_name'       => '确认自提点地址',
        'choice_take_address_name'              => '选择取货地址',
        'no_take_address_tips'                  => '请联系管理员配置自提点地址',
        'no_address_tips'                       => '没有地址',
        'extraction_list_choice_title'          => '自提点选择',
        'goods_list_thead_base'                 => '商品信息',
        'goods_list_thead_price'                => '单价',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '金额',
        'goods_item_total_name'                 => '合计',
        'not_goods_tips'                        => '没有商品',
        'not_payment_tips'                      => '没有支付方式',
        'user_message_title'                    => '买家留言',
        'user_message_placeholder'              => '选填、建议填写和卖家达成一致的说明',
        'summary_title'                         => '实付款：',
        'summary_contact_name'                  => '联系人：',
        'summary_address'                       => '地址：',
        'summary_submit_order_name'             => '提交订单',
        'payment_layer_title'                   => '支付跳转中、请勿关闭页面',
        'payment_layer_content'                 => '支付失败或长时间未响应',
        'payment_layer_order_button_text'       => '我的订单',
        'payment_layer_tips'                    => '后可以重新发起支付',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => '所有文章',
        'article_no_data_tips'                  => '文章不存在或已删除',
        'article_id_params_tips'                => '文章ID有误',
        'release_time'                          => '发布时间：',
        'view_number'                           => '浏览次数：',
        'prev_article'                          => '上一篇：',
        'next_article'                          => '下一篇：',
        'article_category_name'                 => '文章分类',
        'article_nav_text'                      => '侧栏导航',
    ],

    // 自定义页面
    'custom_view'       => [
        'custom_view_no_data_tips'              => '页面不存在或已删除',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => '页面不存在或已删除',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => '订单id有误',
            'payment_choice_tips'               => '请选择支付方式',
            'rating_string'                     => '非常差,差,一般,好,非常好',
            'not_choice_data_tips'              => '请先选中数据',
            'pay_url_empty_tips'                => '支付url地址有误',
        ],
        // 基础
        'browser_seo_title'                     => '我的订单',
        'detail_browser_seo_title'              => '订单详情',
        'comments_browser_seo_title'            => '订单评论',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '基础信息',
            'goods_placeholder'     => '请输入订单号/商品名称/型号',
            'status'                => '订单状态',
            'pay_status'            => '支付状态',
            'total_price'           => '总价(元)',
            'pay_price'             => '支付金额(元)',
            'price'                 => '单价(元)',
            'order_model'           => '订单模式',
            'client_type'           => '下单平台',
            'address'               => '地址信息',
            'take'                  => '取货信息',
            'refund_price'          => '退款金额(元)',
            'returned_quantity'     => '退货数量',
            'buy_number_count'      => '购买总数',
            'increase_price'        => '增加金额(元)',
            'preferential_price'    => '优惠金额(元)',
            'payment_name'          => '支付方式',
            'user_note'             => '留言信息',
            'extension'             => '扩展信息',
            'express_name'          => '快递公司',
            'express_number'        => '快递单号',
            'is_comments'           => '是否评论',
            'confirm_time'          => '确认时间',
            'pay_time'              => '支付时间',
            'delivery_time'         => '发货时间',
            'collect_time'          => '完成时间',
            'cancel_time'           => '取消时间',
            'close_time'            => '关闭时间',
            'add_time'              => '创建时间',
            'upd_time'              => '更新时间',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => '订单总额',
            'pay_price'             => '支付总额',
            'buy_number_count'      => '商品总数',
            'refund_price'          => '退款',
            'returned_quantity'     => '退货',
            'price_unit'            => '元',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => '退款原因数据为空',
        ],
        // 基础
        'browser_seo_title'                     => '订单售后',
        'detail_browser_seo_title'              => '订单售后详情',
    ],

    // 用户
    'user'              => [
        'browser_seo_title'                     => '用户中心',
        'forget_password_browser_seo_title'     => '密码找回',
        'user_register_browser_seo_title'       => '用户注册',
        'user_login_browser_seo_title'          => '用户登录',
        'password_reset_illegal_error_tips'     => '已经登录了，如要重置密码，请先退出当前账户',
        'register_illegal_error_tips'           => '已经登录了，如要注册新账户，请先退出当前账户',
        'login_illegal_error_tips'              => '已经登录了，请勿重复登录',
    ],

    // 用户地址
    'user_address'      => [
        'browser_seo_title'                     => '我的地址',
    ],

    // 用户足迹
    'user_goods_browse' => [
        'browser_seo_title'                     => '我的足迹',
    ],

    // 用户商品收藏
    'user_goods_favor'  => [
        'browser_seo_title'                     => '商品收藏',
    ],

    // 用户积分
    'user_integral'     => [
        'browser_seo_title'                     => '我的积分',
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => '个人资料',
        'edit_browser_seo_title'                => '个人资料编辑',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => '我的消息',
        // 动态表格
        'form_table'                => [
            'type'                  => '消息类型',
            'business_type'         => '业务类型',
            'title'                 => '标题',
            'detail'                => '详情',
            'is_read'               => '状态',
            'add_time_time'         => '时间',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => '问答/留言',
        // 表单
        'form_title'                            => '提问/留言',
        'form_item_name'                        => '昵称',
        'form_item_name_message'                => '昵称格式1~30个字符之间',
        'form_item_tel'                         => '电话',
        'form_item_tel_message'                 => '请填写电话',
        'form_item_title'                       => '标题',
        'form_item_title_message'               => '标题格式1~60个字符之间',
        'form_item_content'                     => '内容',
        'form_item_content_message'             => '内容格式5~1000个字符之间',
        // 动态表格
        'form_table'                            => [
            'name'                  => '联系人',
            'tel'                   => '联系电话',
            'content'               => '内容',
            'reply'                 => '回复内容',
            'reply_time_time'       => '回复时间',
            'add_time_time'         => '创建时间',
        ],
    ],

    // 安全
    'safety'            => [
        'browser_seo_title'                     => '安全设置',
        'password_update_browser_seo_title'     => '登录密码修改 - 安全设置',
        'mobile_update_browser_seo_title'       => '手机号码修改 - 安全设置',
        'email_update_browser_seo_title'        => '电子邮箱修改 - 安全设置',
        'logout_browser_seo_title'              => '账号注销 - 安全设置',
        'original_account_check_error_tips'     => '原帐号校验失败',
    ],
];
?>