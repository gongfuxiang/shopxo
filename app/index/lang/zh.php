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
        'login_title'                           => '登录',
        'register_title'                        => '注册',
        'logout_title'                          => '退出',
        'cancel_text'                           => '取消',
        'save_text'                             => '保存',
        'more_text'                             => '更多',
        'processing_in_text'                    => '处理中...',
        'upload_in_text'                        => '上传中...',
        'navigation_main_quick_name'            => '更多入口',
        'no_relevant_data_tips'                 => '没有相关数据',
        'avatar_upload_title'                   => '头像上传',
        'choice_images_text'                    => '选择图片',
        'choice_images_error_tips'              => '请选择需要上传的图片',
        'confirm_upload_title'                  => '确认上传',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => '欢迎您',
        'header_top_nav_left_login_first'       => '您好',
        'header_top_nav_left_login_last'        => '，欢迎来到',
        // 搜索
        'search_input_placeholder'              => '其实搜索很简单^_^ !',
        'search_button_text'                    => '搜索',
        // 用户
        'avatar_upload_tips'                    => [
            '请在工作区域放大缩小及移动选取框，选择要裁剪的范围，裁切宽高比例固定；',
            '裁切后的效果为右侧预览图所显示，确认提交后生效；',
        ],
        'close_user_register_tips'              => '暂时关闭用户注册',
        'close_user_login_tips'                 => '暂时关闭用户登录',
        // 底部
        'footer_icp_filing_text'                => 'ICP备案',
        'footer_public_security_filing_text'    => '公安备案',
        'footer_business_license_text'          => '电子营业执照亮照',
        // 购物车
        'user_cart_success_modal_tips'          => '商品已成功加入购物车！',
        'user_cart_success_modal_text_first'    => '购物车共',
        'user_cart_success_modal_text_last'     => '件商品',
        'user_cart_success_modal_cart_title'    => '去购物车结算',
        'user_cart_success_modal_buy_title'     => '继续购物',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => '您好，欢迎来到',
        'banner_right_article_title'            => '资讯头条',
        'design_base_nav_title'                 => '首页设计',
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
        'qrcode_mobile_buy_name'                => '手机购买',
    ],

    // 商品搜索
    'search'            => [
        'base_nav_title'                        => '商品搜索',
        'filter_out_first_text'                 => '筛选出',
        'filter_out_last_data_text'             => '条数据',
    ],

    // 商品分类
    'category'          => [
        'base_nav_title'                        => '商品分类',
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
        'base_nav_title'                        => '购物车',
        'goods_list_thead_base'                 => '商品信息',
        'goods_list_thead_price'                => '单价',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '合计',
        'goods_item_total_name'                 => '总价',
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
        'base_nav_title'                        => '订单确认',
        'exhibition_not_allow_submit_tips'      => '展示型不允许提交订单',
        'buy_item_order_title'                  => '订单信息',
        'buy_item_payment_title'                => '选择支付',
        'confirm_delivery_address_name'         => '确认收货地址',
        'use_new_address_name'                  => '添加新地址',
        'no_address_info_tips'                  => '没有地址信息！',
        'confirm_extraction_address_name'       => '确认自提点地址',
        'choice_take_address_name'              => '选择取货地址',
        'no_take_address_tips'                  => '请联系管理员配置自提点地址',
        'no_address_tips'                       => '没有地址',
        'extraction_list_choice_title'          => '自提点选择',
        'goods_list_thead_base'                 => '商品信息',
        'goods_list_thead_price'                => '单价',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '合计',
        'goods_item_total_name'                 => '总价',
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
        'extraction_contact_name'               => '我的姓名',
        'extraction_contact_tel'                => '我的电话',
        'extraction_contact_tel_placeholder'    => '我的手机或座机',
    ],

    // 文章
    'article'            => [
        'category_base_nav_title'               => '所有文章',
        'article_no_data_tips'                  => '文章不存在或已删除',
        'article_id_params_tips'                => '文章ID有误',
        'release_time'                          => '发布时间：',
        'view_number'                           => '浏览次数：',
        'prev_article'                          => '上一篇：',
        'next_article'                          => '下一篇：',
        'article_category_name'                 => '文章分类',
        'recommended_article_name'              => '推荐文章',
        'article_nav_text'                      => '侧栏导航',
        'article_search_placeholder'            => '输入关键字搜索',
    ],

    // 自定义页面
    'customview'        => [
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
        'base_nav_title'                        => '我的订单',
        'detail_base_nav_title'                 => '订单详情',
        'detail_take_title'                     => '取货地址',
        'detail_shipping_address_title'         => '收货地址',
        'detail_service_title'                  => '服务信息',
        'comments_base_nav_title'               => '订单评论',
        'batch_payment_name'                    => '批量支付',
        'comments_goods_list_thead_base'        => '商品信息',
        'comments_goods_list_thead_price'       => '单价',
        'comments_goods_list_thead_content'     => '评论内容',
        'form_you_have_commented_tips'          => '你已进行过评论',
        'form_payment_title'                    => '支付',
        'form_payment_no_data_tips'             => '没有支付方式',
        'order_base_title'                      => '订单信息',
        'order_status_title'                    => '订单状态',
        'order_contact_title'                   => '联系人',
        'order_consignee_title'                 => '收货人',
        'order_phone_title'                     => '手机号',
        'order_base_warehouse_title'            => '出货服务：',
        'order_base_model_title'                => '订单模式：',
        'order_base_order_no_title'             => '订单编号：',
        'order_base_status_title'               => '订单状态：',
        'order_base_pay_status_title'           => '支付状态：',
        'order_base_payment_title'              => '支付方式：',
        'order_base_total_price_title'          => '订单总价：',
        'order_base_buy_number_title'           => '购买数量：',
        'order_base_returned_quantity_title'    => '退货数量：',
        'order_base_user_note_title'            => '用户留言：',
        'order_base_add_time_title'             => '下单时间：',
        'order_base_confirm_time_title'         => '确认时间：',
        'order_base_pay_time_title'             => '付款时间：',
        'order_base_collect_time_title'         => '收货时间：',
        'order_base_user_comments_time_title'   => '评论时间：',
        'order_base_cancel_time_title'          => '取消时间：',
        'order_base_close_time_title'           => '关闭时间：',
        'order_base_price_title'                => '商品总价：',
        'order_base_increase_price_title'       => '增加金额：',
        'order_base_preferential_price_title'   => '优惠金额：',
        'order_base_refund_price_title'         => '退款金额：',
        'order_base_pay_price_title'            => '支付金额：',
        'order_base_take_code_title'            => '取货码：',
        'order_base_take_code_no_exist_tips'    => '取货码不存在、请联系管理员',
        'order_under_line_tips'                 => '当前为线下支付方式[ {:payment} ]、需管理员确认后方可生效，如需其它支付可以切换支付重新发起支付。',
        'order_delivery_tips'                   => '货物正在仓库打包、出库中...',
        'order_goods_no_data_tips'              => '没有订单商品数据',
        'order_base_service_name'               => '服务人员姓名',
        'order_base_service_mobile'             => '服务人员手机',
        'order_base_service_time'               => '服务时间',
        'order_status_operate_first_tips'       => '您可以',
        'goods_list_thead_base'                 => '商品信息',
        'goods_list_thead_price'                => '单价',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '合计',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '基础信息',
            'goods_placeholder'     => '请输入订单号/商品名称/型号',
            'status'                => '订单状态',
            'pay_status'            => '支付状态',
            'total_price'           => '总价',
            'pay_price'             => '支付金额',
            'price'                 => '单价',
            'order_model'           => '订单模式',
            'client_type'           => '下单平台',
            'address'               => '地址信息',
            'service'               => '服务信息',
            'take'                  => '取货信息',
            'refund_price'          => '退款金额',
            'returned_quantity'     => '退货数量',
            'buy_number_count'      => '购买总数',
            'increase_price'        => '增加金额',
            'preferential_price'    => '优惠金额',
            'payment_name'          => '支付方式',
            'user_note'             => '留言信息',
            'extension'             => '扩展信息',
            'express'               => '快递信息',
            'express_placeholder'   => '请输入快递单号',
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
        'form_table_stats'                      => [
            'total_price'           => '订单总额',
            'pay_price'             => '支付总额',
            'buy_number_count'      => '商品总数',
            'refund_price'          => '退款金额',
            'returned_quantity'     => '退货数量',
        ],
        // 快递表格
        'form_table_express'                    => [
            'name'    => '快递公司',
            'number'  => '快递单号',
            'note'    => '发货备注',
            'time'    => '发货时间',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => '退款原因数据为空',
        ],
        // 基础
        'base_nav_title'                        => '订单售后',
        'detail_base_nav_title'                 => '订单售后详情',
        'view_orderaftersale_enter_name'        => '查看售后订单',
        'orderaftersale_apply_name'             => '申请售后',
        'operate_delivery_name'                 => '立即退货',
        'goods_list_thead_base'                 => '商品信息',
        'goods_list_thead_after_base_title'     => '售后商品',
        'goods_list_thead_price'                => '单价',
        'goods_list_thead_number'               => '数量',
        'goods_list_thead_total'                => '合计',
        'goods_base_price_title'                => '商品总价：',
        'goods_base_increase_price_title'       => '增加金额：',
        'goods_base_preferential_price_title'   => '优惠金额：',
        'goods_base_refund_price_title'         => '退款金额：',
        'goods_base_pay_price_title'            => '支付金额：',
        'goods_base_total_price_title'          => '订单总价：',
        'base_apply_title'                      => '申请信息',
        'goods_after_status_title'              => '售后状态',
        'withdraw_title'                        => '取消申请',
        're_apply_title'                        => '再次申请',
        'select_service_type_title'             => '选择服务类型',
        'goods_pay_price_title'                 => '商品实付金额：',
        'aftersale_service_title'               => '售后客服',
        'problems_contact_service_tips'         => '遇到问题请联系客服',
        'base_apply_type_title'                 => '退款类型：',
        'base_apply_status_title'               => '当前状态：',
        'base_apply_reason_title'               => '申请原因：',
        'base_apply_number_title'               => '退货数量：',
        'base_apply_price_title'                => '退款金额：',
        'base_apply_msg_title'                  => '退款说明：',
        'base_apply_refundment_title'           => '退款方式：',
        'base_apply_refuse_reason_title'        => '拒绝原因：',
        'base_apply_apply_time_title'           => '申请时间：',
        'base_apply_confirm_time_title'         => '确认时间：',
        'base_apply_delivery_time_title'        => '退货时间：',
        'base_apply_audit_time_title'           => '审核时间：',
        'base_apply_cancel_time_title'          => '取消时间：',
        'base_apply_add_time_title'             => '添加时间：',
        'base_apply_upd_time_title'             => '更新时间：',
        'base_item_express_title'               => '快递信息',
        'base_item_express_name'                => '快递：',
        'base_item_express_number'              => '单号：',
        'base_item_express_time'                => '时间：',
        'base_item_voucher_title'               => '凭证',
        // 表单
        'form_delivery_title'                   => '退货操作',
        'form_delivery_address_name'            => '退货地址',
        // 动态表格
        'form_table'                            => [
            'goods'                 => '基础信息',
            'goods_placeholder'     => '请输入订单号/商品名称/型号',
            'status'                => '状态',
            'type'                  => '申请类型',
            'reason'                => '原因',
            'price'                 => '退款金额',
            'number'                => '退货数量',
            'msg'                   => '退款说明',
            'refundment'            => '退款类型',
            'express_name'          => '快递公司',
            'express_number'        => '快递单号',
            'apply_time'            => '申请时间',
            'confirm_time'          => '确认时间',
            'delivery_time'         => '退货时间',
            'audit_time'            => '审核时间',
            'add_time'              => '创建时间',
            'upd_time'              => '更新时间',
        ],
        // 动态表格统计字段
        'form_table_stats'  => [
            'price'   => '退款总额',
            'number'  => '退货总数',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'base_nav_title'                        => '用户中心',
        'forget_password_base_nav_title'        => '密码找回',
        'user_register_base_nav_title'          => '用户注册',
        'user_login_base_nav_title'             => '用户登录',
        'password_reset_illegal_error_tips'     => '已经登录了，如要重置密码，请先退出当前账户',
        'register_illegal_error_tips'           => '已经登录了，如要注册新账户，请先退出当前账户',
        'login_illegal_error_tips'              => '已经登录了，请勿重复登录',
        // 页面
        // 登录
        'login_nav_title'                       => '欢迎登录',
        'login_top_register_tips'               => '还没有帐号？',
        'login_close_tips'                      => '暂时关闭了登录',
        'login_type_username_title'             => '账号密码',
        'login_type_mobile_title'               => '手机验证码',
        'login_type_email_title'                => '邮箱验证码',
        'login_ahora_login_title'               => '立即登录',
        // 注册
        'register_nav_title'                    => '欢迎注册',
        'register_top_login_tips'               => '我已经注册，去',
        'register_close_tips'                   => '暂时关闭了注册',
        'register_type_username_title'          => '账号注册',
        'register_type_mobile_title'            => '手机注册',
        'register_type_email_title'             => '邮箱注册',
        'register_ahora_register_title'         => '立即注册',
        // 忘记密码
        'forget_password_nav_title'             => '找回密码',
        'forget_password_reset_title'           => '重置密码',
        'forget_password_top_login_tips'        => '已有帐号？',
        // 表单
        'form_item_agreement'                   => '阅读并同意',
        'form_item_agreement_message'           => '请勾选同意协议',
        'form_item_service'                     => '《服务协议》',
        'form_item_privacy'                     => '《隐私政策》',
        'form_item_username'                    => '用户名',
        'form_item_username_message'            => '请使用字母、数字、下划线2~18个字符',
        'form_item_password'                    => '登录密码',
        'form_item_password_placeholder'        => '请输入登录密码',
        'form_item_password_message'            => '密码格式6~18个字符之间',
        'form_item_mobile'                      => '手机号码',
        'form_item_mobile_placeholder'          => '请输入手机号码',
        'form_item_mobile_message'              => '手机号码格式错误',
        'form_item_email'                       => '电子邮箱',
        'form_item_email_placeholder'           => '请输入电子邮箱',
        'form_item_email_message'               => '电子邮箱格式错误',
        'form_item_account'                     => '登录账号',
        'form_item_account_placeholder'         => '请输入用户名/手机/邮箱',
        'form_item_account_message'             => '请输入登录账号',
        'form_item_mobile_email'                => '手机/邮箱',
        'form_item_mobile_email_message'        => '请输入有效的手机/邮箱格式',
        // 个人中心
        'base_avatar_title'                     => '修改头像',
        'base_personal_title'                   => '修改资料',
        'base_address_title'                    => '我的地址',
        'base_message_title'                    => '消息',
        'order_nav_title'                       => '我的订单',
        'order_nav_angle_title'                 => '查看全部订单',
        'various_transaction_title'             => '交易提醒',
        'various_transaction_tips'              => '交易提醒可帮助您了解订单状态和物流情况',
        'various_cart_title'                    => '购物车',
        'various_cart_empty_title'              => '您的购物车还是空的',
        'various_cart_tips'                     => '将想买的商品放进购物车，一起结算更轻松',
        'various_favor_title'                   => '商品收藏',
        'various_favor_empty_title'             => '您还没有收藏商品',
        'various_favor_tips'                    => '收藏的商品将显示最新的促销活动和降价情况',
        'various_browse_title'                  => '我的足迹',
        'various_browse_empty_title'            => '您的商品浏览记录为空',
        'various_browse_tips'                   => '赶紧去商城看看促销活动吧',
    ],

    // 用户地址
    'useraddress'       => [
        'base_nav_title'                        => '我的地址',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'base_nav_title'                        => '我的足迹',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '商品信息',
            'goods_placeholder'     => '请输入商品名称/简述/SEO信息',
            'price'                 => '销售价格',
            'original_price'        => '原价',
            'add_time'              => '创建时间',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'base_nav_title'                        => '商品收藏',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => '商品信息',
            'goods_placeholder'     => '请输入商品名称/简述/SEO信息',
            'price'                 => '销售价格',
            'original_price'        => '原价',
            'add_time'              => '创建时间',
        ],
    ],

    // 用户商品评论
    'usergoodscomments' => [
        'base_nav_title'                        => '商品评论',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基础信息',
            'goods_placeholder'  => '请输入商品名称/型号',
            'business_type'      => '业务类型',
            'content'            => '评论内容',
            'images'             => '评论图片',
            'rating'             => '评分',
            'reply'              => '回复内容',
            'is_show'            => '是否显示',
            'is_anonymous'       => '是否匿名',
            'is_reply'           => '是否回复',
            'reply_time_time'    => '回复时间',
            'add_time_time'      => '创建时间',
            'upd_time_time'      => '更新时间',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'base_nav_title'                        => '我的积分',
        // 页面
        'base_normal_title'                     => '正常可用',
        'base_normal_tips'                      => '可以正常使用的积分',
        'base_locking_title'                    => '当前锁定',
        'base_locking_tips'                     => '一般积分交易中，交易并未完成、锁定相应的积分',
        'base_integral_unit'                    => '积分',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => '操作类型',
            'operation_integral'    => '操作积分',
            'original_integral'     => '原始积分',
            'new_integral'          => '最新积分',
            'msg'                   => '描述',
            'add_time_time'         => '时间',
        ],
    ],

    // 个人资料
    'personal'          => [
        'base_nav_title'                        => '个人资料',
        'edit_base_nav_title'                   => '个人资料编辑',
        'form_item_nickname'                    => '昵称',
        'form_item_nickname_message'            => '昵称2~16个字符之间',
        'form_item_birthday'                    => '生日',
        'form_item_birthday_message'            => '生日格式有误',
        'form_item_province'                    => '所在省',
        'form_item_province_message'            => '所在省最多30个字符',
        'form_item_city'                        => '所在市',
        'form_item_city_message'                => '所在市最多30个字符',
        'form_item_county'                      => '所在区/县',
        'form_item_county_message'              => '所在区/县最多30个字符',
        'form_item_address'                     => '详细地址',
        'form_item_address_message'             => '详细地址2~30个字符',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'base_nav_title'                        => '我的消息',
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

    // 安全
    'safety'            => [
        // 基础
        'base_nav_title'                        => '安全设置',
        'password_update_base_nav_title'        => '登录密码修改 - 安全设置',
        'mobile_update_base_nav_title'          => '手机号码修改 - 安全设置',
        'email_update_base_nav_title'           => '电子邮箱修改 - 安全设置',
        'logout_base_nav_title'                 => '账号注销 - 安全设置',
        'original_account_check_error_tips'     => '原帐号校验失败',
        // 页面
        'logout_title'                          => '账号注销',
        'logout_confirm_title'                  => '确认注销',
        'logout_confirm_tips'                   => '账号注销后不可恢复、确定继续吗？',
        'email_title'                           => '原电子邮箱校验',
        'email_new_title'                       => '新电子邮箱校验',
        'mobile_title'                          => '原手机号码校验',
        'mobile_new_title'                      => '新手机号码校验',
        'login_password_title'                  => '登录密码修改',
    ],

    // 上传组件
    'ueditor' => [
        'base_nav_title'                        => '扫码上传'
    ],
];
?>