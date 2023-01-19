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
 * 模块语言包-英文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'common'            => [
        // 公共
        'shop_home_title'                       => 'Mall homepage',
        'back_to_the_home_title'                => 'Back to the home',
        'all_category_text'                     => 'All Category',
        'toggle_navigation_text'                => 'Toggle navigation',
        'login_title'                           => 'Login',
        'register_title'                        => 'Register',
        'logout_title'                          => 'Logout',
        'cancel_text'                           => 'Cancel',
        'save_text'                             => 'Save',
        'more_text'                             => 'More',
        'processing_in_text'                    => 'Processing in...',
        'upload_in_text'                        => 'Upload in...',
        'navigation_main_quick_name'            => 'Treasure chest',
        'no_relevant_data_tips'                 => 'No relevant data',
        'avatar_upload_title'                   => 'Avatar Upload',
        'choice_images_text'                    => 'Choice Pic',
        'choice_images_error_tips'              => 'Please select the image to upload',
        'confirm_upload_title'                  => 'Confirm Upload',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Hello, welcome to',
        'header_top_nav_left_login_first'       => 'Hello!',
        'header_top_nav_left_login_last'        => ', Welcome to',
        // 搜索
        'search_input_placeholder'              => 'Actually, the search is very simple ^ _ ^!',
        'search_button_text'                    => 'Search',
        // 用户
        'avatar_upload_tips'                    => [
            'Please zoom in and out in the working area and move the selection box to select the range to be cut. The cutting width and height ratio are fixed;',
            'The effect after cutting is shown in the preview image on the right. It takes effect after confirmation of submission;'
        ],
        'close_user_register_tips'              => 'Temporarily close user registration',
        'close_user_login_tips'                 => 'Temporarily close user login',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Hello, welcome to',
        'banner_right_article_title'            => 'News headlines',
        'design_browser_seo_title'              => 'Home page design',
    ],

    // 商品
    'goods'             => [
        // 页面公共
        'page_common'       => [
            'comment_no_data_tips'              => 'No comment data',
        ],
        // 基础
        'goods_no_data_tips'                    => 'Goods does not exist or has been deleted',
        'panel_can_choice_spec_name'            => 'Optional specifications',
        'recommend_goods_title'                 => 'Look and see',
        'dynamic_scoring_name'                  => 'Scoring',
        'no_scoring_data_tips'                  => 'No scoring data',
        'no_comments_data_tips'                 => 'No evaluation data',
        'comments_first_name'                   => 'Comment on',
        'admin_reply_name'                      => 'Admin reply:',
    ],

    // 商品搜索
    'search'            => [
        'browser_seo_title'                     => 'Goods Search',
    ],

    // 商品分类
    'category'          => [
        'browser_seo_title'                     => 'Goods Category',
        'no_category_data_tips'                 => 'No classification data',
        'no_sub_category_data_tips'             => 'No subcategory data',
        'view_category_sub_goods_name'          => 'View products under category',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Please select an item',
        ],
        // 基础
        'browser_seo_title'                     => 'Cart',
        'goods_list_thead_base'                 => 'Goods Info',
        'goods_list_thead_price'                => 'Price',
        'goods_list_thead_number'               => 'Number',
        'goods_list_thead_total'                => 'Amount of Money',
        'goods_item_total_name'                 => 'Total',
        'summary_selected_goods_name'           => 'Selected Goods',
        'summary_selected_goods_unit'           => 'Piece',
        'summary_nav_goods_total'               => 'Total:',
        'summary_nav_button_name'               => 'Settlement',
        'no_cart_data_tips'                     => 'Your shopping cart is still empty. You can',
        'no_cart_data_my_favor_name'            => 'My Favor',
        'no_cart_data_my_order_name'            => 'My Order',
    ],

    // 订单确认
    'buy'               => [
        // 页面公共
        'page_common'       => [
            'address_choice_tips'               => 'Please select an address',
            'payment_choice_tips'               => 'Please select payment',
        ],
        // 基础
        'browser_seo_title'                     => 'Order Confirm',
        'show_mode_not_allow_submit_order_tips' => 'Order submission is not allowed for display type',
        'gooods_data_no_data_tips'              => 'Goods Info is empty',
        'buy_item_order_title'                  => 'Order Info',
        'buy_item_payment_title'                => 'Choice Payment',
        'confirm_delivery_address_name'         => 'Confirm delivery address',
        'use_new_address_name'                  => 'Use New Address',
        'no_delivery_address_tips'              => 'No shipping address',
        'confirm_extraction_address_name'       => 'Confirm the self-pickup address',
        'choice_take_address_name'              => 'Select pickup address',
        'no_take_address_tips'                  => 'Please contact the administrator to configure the self-service address',
        'no_address_tips'                       => 'No address',
        'extraction_list_choice_title'          => 'Self-service selection',
        'goods_list_thead_base'                 => 'Goods Info',
        'goods_list_thead_price'                => 'Price',
        'goods_list_thead_number'               => 'Number',
        'goods_list_thead_total'                => 'Amount of Money',
        'goods_item_total_name'                 => 'Total',
        'not_goods_tips'                        => 'No Goods',
        'not_payment_tips'                      => 'No payment method',
        'user_message_title'                    => 'Buyer message',
        'user_message_placeholder'              => 'Instructions agreed with the seller for optional and recommended filling',
        'summary_title'                         => 'Actual payment:',
        'summary_contact_name'                  => 'contacts:',
        'summary_address'                       => 'Address:',
        'summary_submit_order_name'             => 'Submit Order',
        'payment_layer_title'                   => 'Payment jump, please do not close the page',
        'payment_layer_content'                 => 'Payment failed or did not respond for a long time',
        'payment_layer_order_button_text'       => 'My Order',
        'payment_layer_tips'                    => 'Payment can be re-initiated after',
    ],

    // 文章
    'article'            => [
        'category_browser_seo_title'            => 'All Articles',
        'article_no_data_tips'                  => 'Article does not exist or has been deleted',
        'article_id_params_tips'                => 'Incorrect Article ID',
        'release_time'                          => 'Release Time：',
        'view_number'                           => 'View Number：',
        'prev_article'                          => 'Previous：',
        'next_article'                          => 'Next：',
        'article_category_name'                 => 'Article Category',
        'article_nav_text'                      => 'Sidebar Nav',
    ],

    // 自定义页面
    'custom_view'            => [
        'custom_view_no_data_tips'              => 'Page does not exist or has been deleted',
    ],

    // 页面设计
    'design'            => [
        'design_no_data_tips'                   => 'Page does not exist or has been deleted',
    ],

    // 订单管理
    'order'             => [
        // 页面公共
        'page_common'       => [
            'order_id_empty'                    => 'Wrong order ID',
            'payment_choice_tips'               => 'Please select payment method',
            'rating_string'                     => 'Very poor, poor, average, good, very good',
            'not_choice_data_tips'              => 'Please select data first',
            'pay_url_empty_tips'                => 'The payment url address is incorrect',
        ],
        // 基础
        'browser_seo_title'                     => 'My Order',
        'detail_browser_seo_title'              => 'Order Detail',
        'comments_browser_seo_title'            => 'Order Comments',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Base Info',
            'goods_placeholder'     => 'Please enter the order number/product name/model',
            'status'                => 'Order Status',
            'pay_status'            => 'Payment Status',
            'total_price'           => 'Total Price(yuan)',
            'pay_price'             => 'Payment Price(yuan)',
            'price'                 => 'Price(yuan)',
            'order_model'           => 'Order Mode',
            'client_type'           => 'Ordering Platform',
            'address'               => 'Address Info',
            'take'                  => 'Take Info',
            'refund_price'          => 'Refund Amount(yuan)',
            'returned_quantity'     => 'Refund Number',
            'buy_number_count'      => 'Buy Number',
            'increase_price'        => 'Increase Price(yuan)',
            'preferential_price'    => 'Discount Price(yuan)',
            'payment_name'          => 'Payment Type',
            'user_note'             => 'User Message',
            'extension'             => 'Extended Info',
            'express_name'          => 'Express Company',
            'express_number'        => 'Express Number',
            'is_comments'           => 'Whether Comment',
            'confirm_time'          => 'Confirm Time',
            'pay_time'              => 'Payment Time',
            'delivery_time'         => 'Delivery Time',
            'collect_time'          => 'Success Time',
            'cancel_time'           => 'Cancel Time',
            'close_time'            => 'Close Time',
            'add_time'              => 'Creation Time',
            'upd_time'              => 'Update Time',
        ],
        // 动态表格统计数据
        'form_table_page_stats'                 => [
            'total_price'           => 'TotalPrice',
            'pay_price'             => 'PaymentPrice',
            'buy_number_count'      => 'GoodsNumber',
            'refund_price'          => 'RefundAmount',
            'returned_quantity'     => 'RefundNumber',
            'price_unit'            => 'yuan',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Refund reason data is empty',
        ],
        // 基础
        'browser_seo_title'                     => 'Order AfterSales',
        'detail_browser_seo_title'              => 'Order AfterSales Detail',
    ],

    // 用户
    'user'              => [
        'browser_seo_title'                     => 'User Center',
        'forget_password_browser_seo_title'     => 'Password Recovery',
        'user_register_browser_seo_title'       => 'User Register',
        'user_login_browser_seo_title'          => 'User Login',
        'password_reset_illegal_error_tips'     => 'You have logged in. If you want to reset your password, please log out of your current account first',
        'register_illegal_error_tips'           => 'You have logged in. If you want to register a new account, please exit the current account first',
        'login_illegal_error_tips'              => 'Already logged in, please do not log in again',
    ],

    // 用户地址
    'user_address'      => [
        'browser_seo_title'                     => 'My Address',
    ],

    // 用户足迹
    'user_goods_browse' => [
        'browser_seo_title'                     => 'My Browse',
    ],

    // 用户商品收藏
    'user_goods_favor'  => [
        'browser_seo_title'                     => 'Goods Favor',
    ],

    // 用户积分
    'user_integral'     => [
        'browser_seo_title'                     => 'My Integral',
    ],

    // 个人资料
    'personal'          => [
        'browser_seo_title'                     => 'Personal Data',
        'edit_browser_seo_title'                => 'Personal Data Edit',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'browser_seo_title'                     => 'My Message',
        // 动态表格
        'form_table'                => [
            'type'                  => 'Message Type',
            'business_type'         => 'Business Type',
            'title'                 => 'Title',
            'detail'                => 'Detail',
            'is_read'               => 'Status',
            'add_time_time'         => 'Creation Time',
        ],
    ],

    // 问答/留言
    'answer'            => [
        // 基础
        'browser_seo_title'                     => 'Q&A/Message',
        // 表单
        'form_title'                            => 'Q&A/Message',
        'form_item_name'                        => 'Nickname',
        'form_item_name_message'                => 'Nickname format is between 1 and 30 characters',
        'form_item_tel'                         => 'Phone',
        'form_item_tel_message'                 => 'Please fill in the phone number',
        'form_item_title'                       => 'Title',
        'form_item_title_message'               => 'Title format is between 1 and 60 characters',
        'form_item_content'                     => 'Content',
        'form_item_content_message'             => 'Content format is between 5 and 1000 characters',
        // 动态表格
        'form_table'                            => [
            'name'                  => 'Contacts',
            'tel'                   => 'Contact Number',
            'content'               => 'Content',
            'reply'                 => 'Reply Content',
            'reply_time_time'       => 'Reply Time',
            'add_time_time'         => 'Creation Time',
        ],
    ],

    // 安全
    'safety'            => [
        'browser_seo_title'                     => 'Security settings',
        'password_update_browser_seo_title'     => 'Login password modification - security settings',
        'mobile_update_browser_seo_title'       => 'Mobile number modification - security settings',
        'email_update_browser_seo_title'        => 'E-mail modification - security settings',
        'logout_browser_seo_title'              => 'Account logout - security settings',
        'original_account_check_error_tips'     => 'Original account verification failed',
    ],
];
?>