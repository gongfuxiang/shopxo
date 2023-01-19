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
 * 公共语言包-英文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'page_common'           => [
        // 基础
        'chosen_select_no_results_text'     => 'No matching results',
        'error_text'                        => 'Abnormal error',
        'reminder_title'                    => 'Warm prompt',
        'operate_params_error'              => 'Incorrect operation parameters',
        'not_operate_error'                 => 'No related operation',
        'not_data_error'                    => 'No relevant data',
        'select_reverse_name'               => 'Reverse selection',
        'select_all_name'                   => 'Select All',
        'loading_tips'                      => 'Loading...',
        'goods_stock_max_tips'              => 'Maximum purchase limit',
        'goods_stock_min_tips'              => 'Minimum purchase quantity',
        'goods_inventory_number_tips'       => 'Inventory quantity',
        'goods_no_choice_spec_tips'         => 'Please select a specification',
        'goods_spec_empty_tips'             => 'No specification data',
        'goods_id_empty_tips'               => 'Item ID data',
        'input_empty_tips'                  => 'Please enter data',
        'store_enabled_tips'                => 'Your browser does not support local storage. Please disable Private Mode or upgrade to a modern browser.',
        // 上传下载
        'get_loading_tips'                  => 'Getting...',
        'download_loading_tips'             => 'Downloading...',
        'update_loading_tips'               => 'Updating...',
        'install_loading_tips'              => 'Installing...',
        'system_download_loading_tips'      => 'The system package is being downloaded...',
        'upgrade_download_loading_tips'     => 'The upgrade package is being downloaded...',
        // 公共common.js
        'select_not_chosen_tips'            => 'Please select an item',
        'select_chosen_min_tips'            => 'Select at least {value} items',
        'select_chosen_max_tips'            => 'Select at most {value} items',
        'upload_images_max_tips'            => 'Upload up to {value} pictures',
        'upload_video_max_tips'             => 'Upload {value} videos at most',
        'upload_annex_max_tips'             => 'Upload up to {value} attachments',
        'form_config_type_params_tips'      => 'The form [type] parameter configuration is incorrect',
        'form_config_value_params_tips'     => 'The form [type] parameter configuration is incorrect',
        'form_call_fun_not_exist_tips'      => 'The method defined by the form is not defined',
        'form_config_main_tips'             => 'The form [action or method] parameter configuration is incorrect',
        'max_input_vars_tips'               => 'The number of request parameters has exceeded the php.ini limit',
        'operate_add_name'                  => 'Add',
        'operate_edit_name'                 => 'Edit',
        'operate_delete_name'               => 'Delete',
        'upload_images_format_tips'         => 'Image format error, please upload again',
        'upload_video_format_tips'          => 'Video format error, please upload again',
        'ie_browser_tips'                   => 'ie browser is not available',
        'browser_api_error_tips'            => 'The browser does not support full-screen API or has been disabled',
        'request_handle_loading_tips'       => 'Processing, please wait...',
        'params_error_tips'                 => 'Parameter configuration error',
        'config_fun_not_exist_tips'         => 'Configuration method is not defined',
        'delete_confirm_tips'               => 'Cannot recover after deletion. Are you sure?',
        'operate_confirm_tips'              => 'Cannot recover after operation. Are you sure to continue?',
        'window_close_confirm_tips'         => 'Are you sure you want to close this page?',
        'fullscreen_open_name'              => 'Enable full screen',
        'fullscreen_exit_name'              => 'Exit full screen',
        'map_dragging_icon_tips'            => 'Drag the red icon to locate directly',
        'map_type_not_exist_tips'           => 'The map function is not defined',
        'map_address_analysis_tips'         => 'The address you selected did not resolve to the result!',
        'map_coordinate_tips'               => 'Incorrect coordinates',
        'before_choice_data_tips'           => 'Please select data first',
        'address_data_empty_tips'           => 'The address is empty',
        'assembly_not_init_tips'            => 'Component not initialized',
        'not_specified_container_tips'      => 'No container specified',
        'not_specified_assembly_tips'       => 'No load component specified',
        'not_specified_form_name_tips'      => 'No form name specified',
        'not_load_lib_hiprint_error'        => 'Please introduce the hiprint component library first',
    ],

    // 公共基础
    'error'                                                 => 'Exception Error',
    'operate_fail'                                          => 'Operation Failed',
    'operate_success'                                       => 'Operation Succeeded',
    'get_fail'                                              => 'Get Failed',
    'get_success'                                           => 'Success',
    'update_fail'                                           => 'Update Failed',
    'update_success'                                        => 'Update Succeeded',
    'insert_fail'                                           => 'Add Failed',
    'insert_success'                                        => 'Successfully added',
    'edit_fail'                                             => 'Edit Failed',
    'edit_success'                                          => 'Edit Succeeded',
    'change_fail'                                           => 'Modification Failed',
    'change_success'                                        => 'Modification Succeeded',
    'delete_fail'                                           => 'Delete Failed',
    'delete_success'                                        => 'Delete Succeeded',
    'cancel_fail'                                           => 'Cancel Failed',
    'cancel_success'                                        => 'Cancel successfully',
    'close_fail'                                            => 'Close Failed',
    'close_success'                                         => 'Close successfully',
    'send_fail'                                             => 'Sending Failed',
    'send_success'                                          => 'Sending Succeeded',
    'join_fail'                                             => 'Join Failed',
    'join_success'                                          => 'Join successfully',
    'created_fail'                                          => 'Generation Failed',
    'created_success'                                       => 'Generation Succeeded',
    'auth_fail'                                             => 'Authorization Failed',
    'auth_success'                                          => 'Authorization Succeeded',
    'upload_fail'                                           => 'Upload Failed',
    'upload_success'                                        => 'Upload Succeeded',
    'apply_fail'                                            => 'Application Failed',
    'apply_success'                                         => 'Application Succeeded',
    'handle_fail'                                           => 'Processing Failed',
    'handle_success'                                        => 'Processing Succeeded',
    'handle_none'                                           => 'No need to process',
    'loading_fail'                                          => 'Load Failed',
    'loading_success'                                       => 'Loading Succeeded',
    'request_fail'                                          => 'Request Failed',
    'request_success'                                       => 'Request Succeeded',
    'logout_fail'                                           => 'Logout Failed',
    'logout_success'                                        => 'Logoff Succeeded',
    'pay_fail'                                              => 'Pay Failed',
    'pay_success'                                           => 'Pay Succeeded',
    'no_data'                                               => 'No Relevant Data',
    'params_error_tips'                                     => 'Params Error',
    'content_params_empty_tips'                             => 'Content parameter is empty',
    'illegal_access_tips'                                   => 'illegal access',
    'login_failure_tips'                                    => 'Login Failed, please login again',
    'upgrading_tips'                                        => 'Upgrading...',
    'processing_tips'                                       => 'Processing...',
    'searching_tips'                                        => 'Searching...',
    'controller_not_exist_tips'                             => 'Controller does not exist',
    'plugins_name_tips'                                     => 'Plugins Name Wrong',
    // 常用
    'clear_search_where'                                    => 'Clear Search Where',
    'operate_delete_tips'                                   => 'Cant you confirm the operation after deletion?',
    'home_title'                                            => 'Home',
    'operate_title'                                         => 'operate',
    'select_all_title'                                      => 'Select All',
    'reverse_select_title'                                  => 'Reverse Select',
    'reset_title'                                           => 'Reset',
    'confirm_title'                                         => 'Confirm',
    'cancel_title'                                          => 'Cancel',
    'search_title'                                          => 'Search',
    'setup_title'                                           => 'Setup',
    'edit_title'                                            => 'Edit',
    'delete_title'                                          => 'Delete',
    'add_title'                                             => 'Add',
    'submit_title'                                          => 'Submit',
    'detail_title'                                          => 'Detail',
    'view_title'                                            => 'View',
    'choice_title'                                          => 'Choice',
    'already_choice_title'                                  => 'Already Choice',
    'enter_title'                                           => 'Enter',
    'map_title'                                             => 'Map',
    'view_map_title'                                        => 'View Map',
    'see_title'                                             => 'See',
    'close_title'                                           => 'Close',
    'open_title'                                            => 'Open',
    'number_title'                                          => 'Number',
    'spec_title'                                            => 'Spec',
    'inventory_title'                                       => 'Inventory',
    'sales_title'                                           => 'Sales',
    'access_title'                                          => 'Access',
    'hot_title'                                             => 'Hot',
    'favor_title'                                           => 'Favor',
    'already_favor_title'                                   => 'Already Favor',
    'comment_title'                                         => 'Comment',
    'default_title'                                         => 'Default',
    'setup_default_title'                                   => 'Setup Default',
    // 商品基础相关
    'goods_stop_sale_title'                                 => 'Stop Sale',
    'goods_buy_title'                                       => 'Buy',
    'goods_booking_title'                                   => 'Booking',
    'goods_show_title'                                      => 'Consult',
    'goods_cart_title'                                      => 'Add Cart',
    'goods_no_inventory_title'                              => 'Out of stock',
    'goods_already_shelves_title'                           => 'Off shelf',
    'goods_only_show_title'                                 => 'Show only',
    'goods_sales_price_title'                               => 'Sales Price',
    'goods_original_price_title'                            => 'Original Price',
    'goods_main_title'                                      => 'Goods',
    'goods_guess_you_like_title'                            => 'Guess you like it',
    'goods_category_title'                                  => 'Goods Category',
    'goods_inventory_insufficient_min_number_tips'          => 'Initial purchase quantity of insufficient inventory',
    // 用户基础相关
    'user_no_login_tips'                                    => 'Please log in first',
    // 分页
    'page_each_page_name'                                   => 'Each Page',
    'page_page_unit'                                        => 'Strip',
    'page_jump_to_text'                                     => 'Jump to',
    'page_data_total'                                       => 'Total {:total} Data',
    'page_page_total'                                       => 'Total {:total} Page',
    // 动态表格
    'form_table_search_first'                               => [
        'input'         => 'Please enter',
        'select'        => 'Please select',
        'section_min'   => 'minimum',
        'section_max'   => 'Maximum',
        'date_start'    => 'start',
        'date_end'      => 'end',
        'ym'            => 'Please select the month and year',
    ],
    'form_table_base_detail_title'                          => 'Base Info',    
    'form_table_config_error_tips'                          => 'Dynamic table configuration error',
    'form_table_column_sort_tips'                           => 'You can click drag to adjust the display order, and click reset if you need to restore',
    'form_table_nav_operate_data_print_name'                => 'DataPrint',
    'form_table_nav_operate_data_print_tips'                => 'Print Current Data',
    'form_table_nav_operate_data_export_pdf_name'           => 'Export PDF',
    'form_table_nav_operate_data_export_excel_name'         => 'Export Excel',
    'form_table_nav_operate_data_export_tips'               => 'Export Current Data',
    'form_table_nav_operate_data_list_print_tips'           => 'Select the data to be printed in the list (multiple choices are allowed)',
    'form_table_nav_operate_data_list_export_excel_tips'    => 'Export all data by search criteria',
    'form_table_nav_operate_data_list_export_pdf_tips'      => 'Select the data to be exported from the list (multiple choices are allowed)',
    'form_table_nav_operate_data_list_delete_tips'          => 'Select the data to be deleted from the list (multiple choices are allowed)',
    // 前端顶部小导航-右侧
    'header_top_nav_right'                                  => [
        'user_center'           => 'Personal Center',
        'user_shop'             => 'My Mall',
        'user_order'            => 'My Order',
        'favor'                 => 'My Favor',
        'goods_favor'           => 'Goods Favor',
        'cart'                  => 'Cart',
        'message'               => 'Message',
    ],
    // 用户注册类型列表
    'common_user_reg_type_list'          =>  [
        0 => ['value' => 'username', 'name' => 'account'],
        1 => ['value' => 'sms', 'name' => 'sms'],
        2 => ['value' => 'email', 'name' => 'email'],
    ],
    // 登录方式
    'common_login_type_list'     =>  [
        0 => ['value' => 'username', 'name' => 'Account password', 'checked' => true],
        1 => ['value' => 'email', 'name' => 'Email verification code'],
        2 => ['value' => 'sms', 'name' => 'Mobile verification code'],
    ],
    // 性别
    'common_gender_list'                =>  [
        0 => ['id' => 0, 'name' => 'Secrecy', 'checked' => true],
        1 => ['id' => 1, 'name' => 'Female'],
        2 => ['id' => 2, 'name' => 'Male'],
    ],
    // 关闭开启状态
    'common_close_open_list'          =>  [
        0 => ['value' => 0, 'name' => 'Close'],
        1 => ['value' => 1, 'name' => 'Open'],
    ],
    // 是否启用
    'common_is_enable_tips'             =>  [
        0 => ['id' => 0, 'name' => 'Not Enabled'],
        1 => ['id' => 1, 'name' => 'Enabled'],
    ],
    'common_is_enable_list'             =>  [
        0 => ['id' => 0, 'name' => 'No Enabled'],
        1 => ['id' => 1, 'name' => 'Enabled', 'checked' => true],
    ],
    // 是否显示
    'common_is_show_list'               =>  [
        0 => ['id' => 0, 'name' => 'No Show'],
        1 => ['id' => 1, 'name' => 'Show', 'checked' => true],
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
        'baidu'     => ['id' => 'baidu', 'name' => 'Baidu Map', 'checked' => true],
        'amap'      => ['id' => 'amap', 'name' => 'Gaud Map'],
        'tencent'   => ['id' => 'tencent', 'name' => 'Tencent Map'],
        'tianditu'  => ['id' => 'tianditu', 'name' => 'Sky Map'],
    ],
    // 支付支付状态
    'common_order_pay_status'   => [
        0 => ['id' => 0, 'name' => 'To be paid', 'checked' => true],
        1 => ['id' => 1, 'name' => 'Paid'],
        2 => ['id' => 2, 'name' => 'Refunded'],
        3 => ['id' => 3, 'name' => 'Partial refund'],
    ],
    // 订单状态
    'common_order_status'          =>  [
        0 => ['id' => 0, 'name' => 'To be confirmed', 'checked' => true],
        1 => ['id' => 1, 'name' => 'To be paid'],
        2 => ['id' => 2, 'name' => 'To be shipped'],
        3 => ['id' => 3, 'name' => 'Goods to be received'],
        4 => ['id' => 4, 'name' => 'Completed'],
        5 => ['id' => 5, 'name' => 'Canceled'],
        6 => ['id' => 6, 'name' => 'Closed'],
    ],
    // 所属平台
    'common_platform_type'          =>  [
        'pc'        => ['value' => 'pc', 'name' => 'PC Website'],
        'h5'        => ['value' => 'h5', 'name' => 'H5 Website'],
        'ios'       => ['value' => 'ios', 'name' => 'Apple APP'],
        'android'   => ['value' => 'android', 'name' => 'Android APP'],
        'weixin'    => ['value' => 'weixin', 'name' => 'WeChat applet'],
        'alipay'    => ['value' => 'alipay', 'name' => 'Alipay applet'],
        'baidu'     => ['value' => 'baidu', 'name' => 'Baidu applet'],
        'toutiao'   => ['value' => 'toutiao', 'name' => 'Toutiao applet'],
        'qq'        => ['value' => 'qq', 'name' => 'QQ applet'],
        'kuaishou'  => ['value' => 'kuaishou', 'name' => 'Kwai applet'],
    ],
    // app平台
    'common_app_type'          =>  [
        'ios'       => ['value' => 'ios', 'name' => 'Apple APP'],
        'android'   => ['value' => 'android', 'name' => 'Android APP'],
    ],
    // 小程序平台
    'common_appmini_type'          =>  [
        'weixin'    => ['value' => 'weixin', 'name' => 'WeChat applet'],
        'alipay'    => ['value' => 'alipay', 'name' => 'Alipay applet'],
        'baidu'     => ['value' => 'baidu', 'name' => 'Baidu applet'],
        'toutiao'   => ['value' => 'toutiao', 'name' => 'Toutiao applet'],
        'qq'        => ['value' => 'qq', 'name' => 'QQ applet'],
        'kuaishou'  => ['value' => 'kuaishou', 'name' => 'Kwai applet'],
    ],
    // 扣除库存规则
    'common_deduction_inventory_rules_list' =>  [
        0 => ['id' => 0, 'name' => 'Order confirmed successfully'],
        1 => ['id' => 1, 'name' => 'Order payment Succeeded'],
        2 => ['id' => 2, 'name' => 'Order shipment'],
    ],
    // 商品增加销量规则
    'common_sales_count_inc_rules_list'     =>  [
        0 => ['id' => 0, 'name' => 'Order payment'],
        1 => ['id' => 1, 'name' => 'Order receipt'],
    ],
    // 是否已读
    'common_is_read_list'               =>  [
        0 => ['id' => 0, 'name' => 'Unread', 'checked' => true],
        1 => ['id' => 1, 'name' => 'Read'],
    ],
    // 消息类型
    'common_message_type_list'          =>  [
        0 => ['id' => 0, 'name' => 'Default', 'checked' => true],
    ],
    // 用户积分 - 操作类型
    'common_integral_log_type_list'             =>  [
        0 => ['id' => 0, 'name' => 'Reduce', 'checked' => true],
        1 => ['id' => 1, 'name' => 'Increase'],
    ],
    // 是否上架/下架
    'common_is_shelves_list'                    =>  [
        0 => ['id' => 0, 'name' => 'Off shelf'],
        1 => ['id' => 1, 'name' => 'Put on the shelf', 'checked' => true],
    ],
    // 是否
    'common_is_text_list'   =>  [
        0 => ['id' => 0, 'name' => 'No', 'checked' => true],
        1 => ['id' => 1, 'name' => 'Yes'],
    ],
    // 用户状态
    'common_user_status_list'           =>  [
        0 => ['id' => 0, 'name' => 'normal', 'checked' => true],
        1 => ['id' => 1, 'name' => 'No speaking', 'tips' => 'User is forbidden to speak'],
        2 => ['id' => 2, 'name' => 'Disable login', 'tips' => 'User is prohibited from logging in'],
        3 => ['id' => 3, 'name' => 'To be reviewed', 'tips' => 'User waiting for approval'],
    ],
    // 导航数据类型
    'common_nav_type_list'              =>  [
        'custom' => ['value'=>'custom', 'name'=>'Custom'],
        'article' => ['value'=>'article', 'name'=>'Article'],
        'customview' => ['value'=>'customview', 'name'=>'Custom Page'],
        'goods_category' => ['value'=>'goods_category', 'name'=>'Goods Category'],
    ],
    // 搜索框下热门关键字类型
    'common_search_keywords_type_list'      =>  [
        0 => ['value' => 0, 'name' => 'Close'],
        1 => ['value' => 1, 'name' => 'Auto'],
        2 => ['value' => 2, 'name' => 'Custom'],
    ],
    // app事件类型
    'common_app_event_type' =>  [
        0 => ['value' => 0, 'name' => 'WEB Page'],
        1 => ['value' => 1, 'name' => 'Internal page (applet/APP internal address)'],
        2 => ['value' => 2, 'name' => 'External applet (applet appid under the same principal)'],
        3 => ['value' => 3, 'name' => 'Jump to the native map to view the specified location'],
        4 => ['value' => 4, 'name' => 'Make a call'],
    ],
    // 订单售后类型
    'common_order_aftersale_type_list' =>  [
        0 => ['value' => 0, 'name' => 'Refund only', 'desc' => 'If the goods are not received (not signed), it is agreed that', 'icon' => 'am-icon-random', 'class' => 'am-fl'],
        1 => ['value' => 1, 'name' => 'Refund return', 'desc' => 'Received goods, need to return and exchange the received goods', 'icon' => 'am-icon-retweet', 'class' => 'am-fr'],
    ],
    // 订单售后状态
    'common_order_aftersale_status_list' =>  [
        0 => ['value' => 0, 'name' => 'To be confirmed'],
        1 => ['value' => 1, 'name' => 'To be returned'],
        2 => ['value' => 2, 'name' => 'To be reviewed'],
        3 => ['value' => 3, 'name' => 'Completed'],
        4 => ['value' => 4, 'name' => 'Rejected'],
        5 => ['value' => 5, 'name' => 'Canceled'],
    ],
    // 订单售后退款方式
    'common_order_aftersale_refundment_list' =>  [
        0 => ['value' => 0, 'name' => 'Retrace'],
        1 => ['value' => 1, 'name' => 'Return to wallet'],
        2 => ['value' => 2, 'name' => 'Manual processing'],
    ],
    // 商品评分
    'common_goods_comments_rating_list' =>  [
        0 => ['value'=>0, 'name'=>'No Score', 'badge'=>''],
        1 => ['value'=>1, 'name'=>'1 Score', 'badge'=>'danger'],
        2 => ['value'=>2, 'name'=>'2 Score', 'badge'=>'warning'],
        3 => ['value'=>3, 'name'=>'3 Score', 'badge'=>'secondary'],
        4 => ['value'=>4, 'name'=>'4 Score', 'badge'=>'primary'],
        5 => ['value'=>5, 'name'=>'5 Score', 'badge'=>'success'],
    ],
    // 商品评论业务类型
    'common_goods_comments_business_type_list' =>  [
        'order' => ['value' => 'order', 'name' => 'Order'],
    ],
    // 站点类型
    'common_site_type_list' =>  [
        0 => ['value' => 0, 'name' => 'Express'],
        1 => ['value' => 1, 'name' => 'Exhibition'],
        2 => ['value' => 2, 'name' => 'SelfDelivery'],
        3 => ['value' => 3, 'name' => 'VirtualSales'],
        4 => ['value' => 4, 'name' => 'Express+SelfDelivery', 'is_ext' => 1],
    ],
    // 订单类型
    'common_order_type_list' =>  [
        0 => ['value' => 0, 'name' => 'Express'],
        1 => ['value' => 1, 'name' => 'Exhibition'],
        2 => ['value' => 2, 'name' => 'SelfDelivery'],
        3 => ['value' => 3, 'name' => 'VirtualSales'],
    ],
    // 下单站点类型列表
    'common_buy_site_model_list' =>  [
        ['value' => 0, 'name' => 'Express'],
        ['value' => 2, 'name' => 'Extraction'],
    ],
    // 管理员状态
    'common_admin_status_list'               =>  [
        0 => ['value' => 0, 'name' => 'Normal', 'checked' => true],
        1 => ['value' => 1, 'name' => 'Suspend'],
        2 => ['value' => 2, 'name' => 'Resigned'],
    ],
    // 支付日志状态
    'common_pay_log_status_list'            => [
        0 => ['value' => 0, 'name' => 'To be paid', 'checked' => true],
        1 => ['value' => 1, 'name' => 'Paid'],
        2 => ['value' => 2, 'name' => 'Closed'],
    ],
    // 商品参数组件类型
    'common_goods_parameters_type_list'     => [
        0 => ['value' => 0, 'name' => 'Whole'],
        1 => ['value' => 1, 'name' => 'Detail', 'checked' => true],
        2 => ['value' => 2, 'name' => 'Base'],
    ],
    // 商品关联排序类型
    'goods_order_by_type_list'              => [
        0 => ['value' => 'g.access_count,g.sales_count,g.id', 'name' => 'Synthesize', 'checked' => true],
        1 => ['value' => 'g.sales_count', 'name' => 'Sales'],
        2 => ['value' => 'g.access_count', 'name' => 'Hot'],
        3 => ['value' => 'g.min_price', 'name' => 'Price'],
        4 => ['value' => 'g.id', 'name' => 'New'],
    ],
    // 商品关联排序规则
    'goods_order_by_rule_list'              => [
        0 => ['value' => 'desc', 'name' => 'Desc(desc)', 'checked' => true],
        1 => ['value' => 'asc', 'name' => 'Asc(asc)'],
    ],
    // 首页数据类型
    'common_site_floor_data_type_list'      => [
        0 => ['value' => 0, 'name' => 'Auto Mode', 'checked' => true],
        1 => ['value' => 1, 'name' => 'Manual Mode'],
        2 => ['value' => 2, 'name' => 'Move Mode'],
    ],
    // 文件上传错误码
    'common_file_upload_error_list'         =>  [
        1 => 'The file size exceeds the maximum upload allowed by the server',
        2 => 'The file size exceeds the browser limit. Check whether it exceeds [Site Settings ->Maximum Attachment Limit]',
        3 => 'The file is only partially uploaded',
        4 => 'No file to upload found',
        5 => 'Server temporary folder not found',
        6 => 'Error writing file to temporary folder ',
        7 => 'File write Failed',
        8 => 'File upload extension is not opened',
    ],
    // -------------------- 后端相关 --------------------
    // 图片验证码
    'site_images_verify_rules_list'  => [
        0 => ['value' => 'bgcolor', 'name' => 'Color Background'],
        1 => ['value' => 'textcolor', 'name' => 'Colored Text'],
        2 => ['value' => 'point', 'name' => 'Jamming Point'],
        3 => ['value' => 'line', 'name' => 'Jamming Line'],
    ],
    // 时区
    'site_timezone_list' => [
        'Pacific/Pago_Pago' => '(Standard Time 11:00) Midway Island, Samoa',
        'Pacific/Rarotonga' => '(Standard Time 10:00) Hawaii',
        'Pacific/Gambier' => '(Standard Time 9:00) Alaska',
        'America/Dawson' => '(Standard Time -8:00) Pacific Time (US and Canada)',
        'America/Creston' => '(Standard Time -7:00) Mountain Time (US and Canada)',
        'America/Belize' => '(Standard Time -6:00) Central Time (US and Canada), Mexico City',
        'America/Eirunepe' => '(Standard Time -5:00) Eastern Time (United States and Canada), Bogota',
        'America/Antigua' => '(Standard Time -4:00) Atlantic Time (Canada), Caracas',
        'America/Argentina/Buenos_Aires' => '(Standard Time 3:00) Brazil, Buenos Aires, Georgetown',
        'America/Noronha' => '(Standard Time 2:00) Mid-Atlantic',
        'Atlantic/Cape_Verde' => '(Standard Time -1:00) Azores, Cape Verde',
        'Africa/Ouagadougou' => '(Greenwich Mean Time) Western European Time, London, Casablanca',
        'Europe/Andorra' => '(Standard Time +1:00) Central European Time, Angola, Libya',
        'Europe/Mariehamn' => '(Standard Time +2:00) Eastern European Time, Cairo, Athens',
        'Asia/Bahrain' => '(Standard Time +3:00) Baghdad, Kuwait, Moscow',
        'Asia/Dubai' => '(Standard time +4:00) Abu Dhabi, Muscat, Baku',
        'Asia/Kolkata' => '(Standard Time +5:00) Yekaterinburg, Islamabad, Karachi',
        'Asia/Dhaka' => '(Standard Time +6:00) Almaty, Dhaka, New Siberia',
        'Indian/Christmas' => '(Standard time +7:00) Bangkok, Hanoi, Jakarta',
        'Asia/Shanghai' => '(Standard time +8:00) Beijing, Chongqing, Hong Kong, Singapore',
        'Australia/Darwin' => '(Standard time +9:00) Tokyo, Seoul, Osaka, Yakutsk',
        'Australia/Adelaide' => '(Standard Time +10:00) Sydney, Guam',
        'Australia/Currie' => '(Standard time +11:00) Magadan, Solomon Islands',
        'Pacific/Fiji' => '(Standard Time +12:00) Auckland, Wellington, Kamchatka Peninsula'
    ],
    // seo
    // url模式列表
    'seo_url_model_list'        =>  [
        0 => ['value' => 0, 'name' => 'Compatibility Mode', 'checked' => true],
        1 => ['value' => 1, 'name' => 'PATHINFO Mode'],
        2 => ['value' => 2, 'name' => 'PATHINFO Mode+ShortAddress'],
    ],
];
?>