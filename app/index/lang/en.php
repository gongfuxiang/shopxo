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
        'shop_home_title'                       => 'Mall Home',
        'back_to_the_home_title'                => 'Back to the home',
        'all_category_text'                     => 'All Category',
        'login_title'                           => 'Login',
        'register_title'                        => 'Register',
        'logout_title'                          => 'Logout',
        'cancel_text'                           => 'Cancel',
        'save_text'                             => 'Save',
        'more_text'                             => 'More',
        'processing_in_text'                    => 'Processing in...',
        'upload_in_text'                        => 'Upload in...',
        'navigation_main_quick_name'            => 'More entr',
        'no_relevant_data_tips'                 => 'No relevant data',
        'avatar_upload_title'                   => 'Avatar Upload',
        'choice_images_text'                    => 'Choice Pic',
        'choice_images_error_tips'              => 'Please select the image to upload',
        'confirm_upload_title'                  => 'Confirm Upload',
        // 公共顶部小导航-左侧导航
        'header_top_nav_left_not_login_first'   => 'Welcome',
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
        // 底部
        'footer_icp_filing_text'                => 'ICP Filing',
        'footer_public_security_filing_text'    => 'Public Security Filing',
        'footer_business_license_text'          => 'Electronic Business License Illumination',
        // 购物车
        'user_cart_success_modal_tips'          => 'The product has been successfully added to the shopping cart!',
        'user_cart_success_modal_text_first'    => 'Shopping Cart Total',
        'user_cart_success_modal_text_last'     => 'Items',
        'user_cart_success_modal_cart_title'    => 'GoCart',
        'user_cart_success_modal_buy_title'     => 'ContinueBuy',
    ],

    // 首页
    'index'             => [
        'banner_right_already_login_first'      => 'Hi,',
        'banner_right_not_login_first'          => 'Hello, welcome to',
        'banner_right_article_title'            => 'News headlines',
        'design_base_nav_title'                 => 'Home page design',
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
        'qrcode_mobile_buy_name'                => 'Mobile Buy',
    ],

    // 商品搜索
    'search'            => [
        'base_nav_title'                        => 'Goods Search',
        'filter_out_first_text'                 => 'Filter Out',
        'filter_out_last_data_text'             => 'Data',
    ],

    // 商品分类
    'category'          => [
        'base_nav_title'                        => 'Goods Category',
        'no_category_data_tips'                 => 'No classification data',
        'no_sub_category_data_tips'             => 'No subcategory data',
        'view_category_sub_goods_name'          => 'View goods under category',
    ],

    // 购物车
    'cart'              => [
        // 页面公共
        'page_common'       => [
            'goods_no_choice_tips'              => 'Please select an item',
        ],
        // 基础
        'base_nav_title'                        => 'Cart',
        'goods_list_thead_base'                 => 'Goods Info',
        'goods_list_thead_price'                => 'Price',
        'goods_list_thead_number'               => 'Number',
        'goods_list_thead_total'                => 'Amount',
        'goods_item_total_name'                 => 'Precio total',
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
        'base_nav_title'                        => 'Order Confirm',
        'exhibition_not_allow_submit_tips'      => 'Order submission is not allowed for display type',
        'buy_item_order_title'                  => 'Order Info',
        'buy_item_payment_title'                => 'Choice Payment',
        'confirm_delivery_address_name'         => 'Confirm delivery address',
        'use_new_address_name'                  => 'Add New Address',
        'no_address_info_tips'                  => 'No address information!',
        'confirm_extraction_address_name'       => 'Confirm the self-pickup address',
        'choice_take_address_name'              => 'Select pickup address',
        'no_take_address_tips'                  => 'Please contact the administrator to configure the self-service address',
        'no_address_tips'                       => 'No address',
        'extraction_list_choice_title'          => 'Self-service selection',
        'goods_list_thead_base'                 => 'Goods Info',
        'goods_list_thead_price'                => 'Price',
        'goods_list_thead_number'               => 'Number',
        'goods_list_thead_total'                => 'Amount',
        'goods_item_total_name'                 => 'Precio total',
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
        'extraction_contact_name'               => 'My name is',
        'extraction_contact_tel'                => 'My phone',
        'extraction_contact_tel_placeholder'    => 'My phone or landline',
    ],

    // 文章
    'article'            => [
        'category_base_nav_title'               => 'All Articles',
        'article_no_data_tips'                  => 'Article does not exist or has been deleted',
        'article_id_params_tips'                => 'Incorrect Article ID',
        'release_time'                          => 'Release Time:',
        'view_number'                           => 'View Number:',
        'prev_article'                          => 'Previous:',
        'next_article'                          => 'Next:',
        'article_category_name'                 => 'Article Category',
        'recommended_article_name'              => 'Recommended articles',
        'article_nav_text'                      => 'Sidebar Nav',
        'article_search_placeholder'            => 'Enter keyword search',
    ],

    // 自定义页面
    'customview'        => [
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
        'base_nav_title'                        => 'My Order',
        'detail_base_nav_title'                 => 'Order Detail',
        'detail_take_title'                     => 'Pickup address',
        'detail_shipping_address_title'         => 'Address',
        'comments_base_nav_title'               => 'Order Comments',
        'batch_payment_name'                    => 'Batch Payment',
        'comments_goods_list_thead_base'        => 'Goods Info',
        'comments_goods_list_thead_price'       => 'Price',
        'comments_goods_list_thead_content'     => 'Comment Content',
        'form_you_have_commented_tips'          => 'You have commented',
        'form_payment_title'                    => 'Payment',
        'form_payment_no_data_tips'             => 'No payment method',
        'order_base_title'                      => 'Order information',
        'order_status_title'                    => 'Order Status',
        'order_contact_title'                   => 'contacts',
        'order_consignee_title'                 => 'Consignee',
        'order_phone_title'                     => 'Mobile phone number',
        'order_base_warehouse_title'            => 'Shipping service:',
        'order_base_model_title'                => 'Order mode:',
        'order_base_order_no_title'             => 'Order No:',
        'order_base_status_title'               => 'Order status:',
        'order_base_pay_status_title'           => 'Payment status:',
        'order_base_payment_title'              => 'Payment method:',
        'order_base_total_price_title'          => 'Total order price:',
        'order_base_buy_number_title'           => 'Purchase quantity:',
        'order_base_returned_quantity_title'    => 'Return quantity:',
        'order_base_user_note_title'            => 'User message:',
        'order_base_add_time_title'             => 'Ordering time:',
        'order_base_confirm_time_title'         => 'Confirmation time:',
        'order_base_pay_time_title'             => 'Time of payment:',
        'order_base_collect_time_title'         => 'Receiving time:',
        'order_base_user_comments_time_title'   => 'Comment time:',
        'order_base_cancel_time_title'          => 'Cancellation time:',
        'order_base_close_time_title'           => 'Closing time:',
        'order_base_price_title'                => 'Goods TotalPrice:',
        'order_base_increase_price_title'       => 'Increase Amount:',
        'order_base_preferential_price_title'   => 'Discount Amount:',
        'order_base_refund_price_title'         => 'Refund Amount:',
        'order_base_pay_price_title'            => 'Payment Amount:',
        'order_base_take_code_title'            => 'Pickup Code:',
        'order_base_take_code_no_exist_tips'    => 'The pickup code does not exist. Please contact the administrator',
        'order_under_line_tips'                 => 'Currently, it is an offline payment method [ {:payment} ], which can take effect only after being confirmed by the administrator. If other payments are required, you can switch payments and restart payments.',
        'order_delivery_tips'                   => 'The goods are being packed and delivered from the warehouse...',
        'order_goods_no_data_tips'              => 'No order line item data',
        'order_base_service_name'               => 'Name of service personnel',
        'order_base_service_mobile'             => 'Service personnels mobile phones',
        'order_base_service_time'               => 'Service Time',
        'order_status_operate_first_tips'       => 'You can',
        'goods_list_thead_base'                 => 'Goods Info',
        'goods_list_thead_price'                => 'Price',
        'goods_list_thead_number'               => 'Number',
        'goods_list_thead_total'                => 'Amount',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Base Info',
            'goods_placeholder'     => 'Please enter the order number/goods name/model',
            'status'                => 'Order Status',
            'pay_status'            => 'Payment Status',
            'total_price'           => 'Total Price(yuan)',
            'pay_price'             => 'Payment Price(yuan)',
            'price'                 => 'Price(yuan)',
            'order_model'           => 'Order Mode',
            'client_type'           => 'Ordering Platform',
            'address'               => 'Address Info',
            'service'               => 'Service Info',
            'take'                  => 'Take Info',
            'refund_price'          => 'Refund Amount(yuan)',
            'returned_quantity'     => 'Refund Number',
            'buy_number_count'      => 'Buy Number',
            'increase_price'        => 'Increase Price(yuan)',
            'preferential_price'    => 'Discount Price(yuan)',
            'payment_name'          => 'Payment Type',
            'user_note'             => 'User Message',
            'extension'             => 'Extended Info',
            'express'               => 'Express Info',
            'express_placeholder'   => 'Please enter Express Number',
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
        'form_table_stats'                      => [
            'total_price'           => 'TotalPrice',
            'pay_price'             => 'PaymentPrice',
            'buy_number_count'      => 'GoodsNumber',
            'refund_price'          => 'RefundAmount',
            'returned_quantity'     => 'RefundNumber',
        ],
        // 快递表格
        'form_table_express'                    => [
            'name'    => 'express company',
            'number'  => 'express number',
            'note'    => 'express note',
            'time'    => 'delivery time',
        ],
    ],

    // 订单售后
    'orderaftersale'    => [
        // 页面公共
        'page_common'       => [
            'refund_reason_empty_tips'          => 'Refund reason data is empty',
        ],
        // 基础
        'base_nav_title'                        => 'Order AfterSales',
        'detail_base_nav_title'                 => 'Order AfterSales Detail',
        'view_orderaftersale_enter_name'        => 'View after-sales orders',
        'orderaftersale_apply_name'             => 'Apply for after-sales service',
        'operate_delivery_name'                 => 'Return immediately',
        'goods_list_thead_base'                 => 'Goods Info',
        'goods_list_thead_price'                => 'Price',
        'goods_base_price_title'                => 'Goods TotalPrice:',
        'goods_list_thead_number'               => 'Number',
        'goods_list_thead_total'                => 'Amount',
        'goods_base_price_title'                => 'Total price of goods:',
        'goods_base_increase_price_title'       => 'Increase Amount:',
        'goods_base_preferential_price_title'   => 'Discount Amount:',
        'goods_base_refund_price_title'         => 'Refund Amount:',
        'goods_base_pay_price_title'            => 'Payment Amount:',
        'goods_base_total_price_title'          => 'Total order price:',
        'base_apply_title'                      => 'Apply Information',
        'goods_after_status_title'              => 'After sales status',
        'withdraw_title'                        => 'Withdraw',
        're_apply_title'                        => 'Re apply',
        'select_service_type_title'             => 'Select Service Type',
        'goods_pay_price_title'                 => 'Actual amount paid for the product:',
        'aftersale_service_title'               => 'After sales customer service',
        'problems_contact_service_tips'         => 'If you encounter any problems, please contact customer service',
        'base_apply_type_title'                 => 'Refund type:',
        'base_apply_status_title'               => 'Current status:',
        'base_apply_reason_title'               => 'Apply Reason:',
        'base_apply_number_title'               => 'Return quantity:',
        'base_apply_price_title'                => 'Refund amount:',
        'base_apply_msg_title'                  => 'Refund instructions:',
        'base_apply_refundment_title'           => 'Refund method:',
        'base_apply_refuse_reason_title'        => 'Reason for rejection:',
        'base_apply_apply_time_title'           => 'Apply time:',
        'base_apply_confirm_time_title'         => 'Confirmation time:',
        'base_apply_delivery_time_title'        => 'Return time:',
        'base_apply_audit_time_title'           => 'Audit time:',
        'base_apply_cancel_time_title'          => 'Cancellation time:',
        'base_apply_add_time_title'             => 'Add time:',
        'base_apply_upd_time_title'             => 'Updated time:',
        'base_item_express_title'               => 'Express information',
        'base_item_express_name'                => 'Express:',
        'base_item_express_number'              => 'Number:',
        'base_item_express_time'                => 'Time:',
        'base_item_voucher_title'               => 'Voucher',
        // 表单
        'form_delivery_title'                   => 'Return Operate',
        'form_delivery_address_name'            => 'Return Address',
        // 动态表格
        'form_table'                            => [
            'goods'                 => 'Base Info',
            'goods_placeholder'     => 'Please enter the order number/goods name/model',
            'status'                => 'Status',
            'type'                  => 'Apply Type',
            'reason'                => 'Reason',
            'price'                 => 'Refund Amount (yuan)',
            'number'                => 'Return Quantity',
            'msg'                   => 'Refund instructions',
            'refundment'            => 'Refund Type',
            'express_name'          => 'Express Company',
            'express_number'        => 'Express Number',
            'apply_time'            => 'Apply Time',
            'confirm_time'          => 'Confirm Time',
            'delivery_time'         => 'Return Time',
            'audit_time'            => 'Audit Time',
            'add_time'              => 'Creation Time',
            'upd_time'              => 'Update Time',
        ],
        // 动态表格统计字段
        'form_table_stats'  => [
            'price'   => 'Total refund amount',
            'number'  => 'Total number of returns',
        ],
    ],

    // 用户
    'user'              => [
        // 基础
        'base_nav_title'                        => 'User Center',
        'forget_password_base_nav_title'        => 'Password Recovery',
        'user_register_base_nav_title'          => 'User Register',
        'user_login_base_nav_title'             => 'User Login',
        'password_reset_illegal_error_tips'     => 'You have logged in. If you want to reset your password, please log out of your current account first',
        'register_illegal_error_tips'           => 'You have logged in. If you want to register a new account, please exit the current account first',
        'login_illegal_error_tips'              => 'Already logged in, please do not log in again',
        // 页面
        // 登录
        'login_nav_title'                       => 'Welcome to login',
        'login_top_register_tips'               => 'Dont have an account yet?',
        'login_close_tips'                      => 'Temporarily closed login',
        'login_type_username_title'             => 'Account',
        'login_type_mobile_title'               => 'Mobile',
        'login_type_email_title'                => 'Email',
        'login_ahora_login_title'               => 'Log in now',
        // 注册
        'register_nav_title'                    => 'Welcome to register',
        'register_top_login_tips'               => 'I have registered, now',
        'register_close_tips'                   => 'Registration is temporarily closed',
        'register_type_username_title'          => 'Account',
        'register_type_mobile_title'            => 'Mobile',
        'register_type_email_title'             => 'Email',
        'register_ahora_register_title'         => 'Register Now',
        // 忘记密码
        'forget_password_nav_title'             => 'Retrieve Password',
        'forget_password_reset_title'           => 'Reset password',
        'forget_password_top_login_tips'        => 'Already have an account?',
        // 表单
        'form_item_agreement'                   => 'Read and agree',
        'form_item_agreement_message'           => 'Please check the agreement',
        'form_item_service'                     => '《Service Agreement》',
        'form_item_privacy'                     => '《Privacy Policy》',
        'form_item_username'                    => 'Username',
        'form_item_username_message'            => 'Please use letters, numbers and underscores for 2~18 characters',
        'form_item_password'                    => 'Login Password',
        'form_item_password_placeholder'        => 'Please enter the login password',
        'form_item_password_message'            => 'Password format is between 6 and 18 characters',
        'form_item_mobile'                      => 'Phone Number',
        'form_item_mobile_placeholder'          => 'Please enter your mobile number',
        'form_item_mobile_message'              => 'Mobile number format error',
        'form_item_email'                       => 'E-mail',
        'form_item_email_placeholder'           => 'Please enter email address',
        'form_item_email_message'               => 'Email format error',
        'form_item_account'                     => 'Login Account',
        'form_item_account_placeholder'         => 'Please enter user name/mobile phone/email',
        'form_item_account_message'             => 'Please enter the login account',
        'form_item_mobile_email'                => 'Mobile/Email',
        'form_item_mobile_email_message'        => 'Please enter a valid mobile phone/email format',
        // 个人中心
        'base_avatar_title'                     => 'Modify Avatar',
        'base_personal_title'                   => 'Modify Data',
        'base_address_title'                    => 'My Address',
        'base_message_title'                    => 'Message',
        'order_nav_title'                       => 'My Order',
        'order_nav_angle_title'                 => 'View All Orders',
        'various_transaction_title'             => 'Transaction Reminder',
        'various_transaction_tips'              => 'Transaction reminder can help you understand the order status and logistics',
        'various_cart_title'                    => 'Cart',
        'various_cart_empty_title'              => 'Your shopping cart is still empty',
        'various_cart_tips'                     => 'Put the goods you want to buy into the shopping cart and make it easier to settle together',
        'various_favor_title'                   => 'Goods Favor',
        'various_favor_empty_title'             => 'You havent collected any goods yet',
        'various_favor_tips'                    => 'Favorite goods will display the latest promotions and price reductions',
        'various_browse_title'                  => 'My Browse',
        'various_browse_empty_title'            => 'Your goods browse record is empty',
        'various_browse_tips'                   => 'Go to the mall to see the promotional activities',
    ],

    // 用户地址
    'useraddress'       => [
        'base_nav_title'                        => 'My Address',
    ],

    // 用户足迹
    'usergoodsbrowse'   => [
        'base_nav_title'                        => 'My Browse',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Goods Info',
            'goods_placeholder'     => 'Please enter the goods name/brief description/SEO information',
            'price'                 => 'Sales price (yuan)',
            'original_price'        => 'Original price (yuan)',
            'add_time'              => 'Creation time',
        ],
    ],

    // 用户商品收藏
    'usergoodsfavor'    => [
        'base_nav_title'                        => 'Goods Favor',
        // 动态表格统计数据
        'form_table'                            => [
            'goods'                 => 'Goods Info',
            'goods_placeholder'     => 'Please enter the goods name/brief description/SEO information',
            'price'                 => 'Sales price (yuan)',
            'original_price'        => 'Original price (yuan)',
            'add_time'              => 'Creation time',
        ],
    ],

    // 用户商品评论
    'usergoodscomments'         => [
        'base_nav_title'                        => 'GoodsComments',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Base Info',
            'goods_placeholder'  => 'Please enter the goods name/model',
            'business_type'      => 'Business Type',
            'content'            => 'Content',
            'images'             => 'Images',
            'rating'             => 'Rating',
            'reply'              => 'Reply',
            'is_show'            => 'Show or not',
            'is_anonymous'       => 'Anonymous or not',
            'is_reply'           => 'Reply or not',
            'reply_time_time'    => 'Reply Time',
            'add_time_time'      => 'Creation Time',
            'upd_time_time'      => 'Update Time',
        ],
    ],

    // 用户积分
    'userintegral'      => [
        'base_nav_title'                        => 'My Integral',
        // 页面
        'base_normal_title'                     => 'Normal Availability',
        'base_normal_tips'                      => 'Points that can be used normally',
        'base_locking_title'                    => 'Currently Locked',
        'base_locking_tips'                     => 'In general point transaction, the transaction is not completed and corresponding points are locked',
        'base_integral_unit'                    => 'integral',
        // 动态表格统计数据
        'form_table'                            => [
            'type'                  => 'Operation Type',
            'operation_integral'    => 'Operation Integral',
            'original_integral'     => 'Original Integral',
            'new_integral'          => 'New Integral',
            'msg'                   => 'Describe',
            'add_time_time'         => 'Time',
        ],
    ],

    // 个人资料
    'personal'          => [
        'base_nav_title'                        => 'Personal Data',
        'edit_base_nav_title'                   => 'Personal Data Edit',
        'form_item_nickname'                    => 'Nickname',
        'form_item_nickname_message'            => 'Nickname is between 2 and 16 characters',
        'form_item_birthday'                    => 'Birthday',
        'form_item_birthday_message'            => 'Incorrect birthday format',
        'form_item_province'                    => 'Province',
        'form_item_province_message'            => 'Maximum 30 characters in the province',
        'form_item_city'                        => 'City',
        'form_item_city_message'                => 'Maximum 30 characters in the city',
        'form_item_county'                      => 'District/County',
        'form_item_county_message'              => 'The district/county can contain up to 30 characters',
        'form_item_address'                     => 'Address',
        'form_item_address_message'             => 'Detailed address is 2~30 characters',
    ],

    // 消息管理
    'message'            => [
        // 基础
        'base_nav_title'                        => 'My Message',
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

    // 安全
    'safety'            => [
        // 基础
        'base_nav_title'                        => 'Security settings',
        'password_update_base_nav_title'        => 'Login password modification - security settings',
        'mobile_update_base_nav_title'          => 'Mobile number modification - security settings',
        'email_update_base_nav_title'           => 'E-mail modification - security settings',
        'logout_base_nav_title'                 => 'Account logout - security settings',
        'original_account_check_error_tips'     => 'Original account verification failed',
        // 页面
        'logout_title'                          => 'Account Logout',
        'logout_confirm_title'                  => 'Confirm Logout',
        'logout_confirm_tips'                   => 'The account cannot be recovered after cancellation. Are you sure to continue?',
        'email_title'                           => 'Original Email Verification',
        'email_new_title'                       => 'New email verification',
        'mobile_title'                          => 'Verification of original mobile phone number',
        'mobile_new_title'                      => 'New mobile phone number verification',
        'login_password_title'                  => 'Login password modification',
    ],

    // 上传组件
    'ueditor' => [
        'base_nav_title'                        => 'Scan the code to upload'
    ],
];
?>