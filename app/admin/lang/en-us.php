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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => 'Trend of order transaction amount',
            'order_trading_trend_name'          => 'Order trading trend',
            'goods_hot_name'                    => 'Hot Goods',
            'goods_hot_tips'                    => 'Show only the first 30 items',
            'payment_name'                      => 'Payment Method',
            'order_region_name'                 => 'Order geographical distribution',
            'order_region_tips'                 => 'Only 30 pieces of data are displayed',
            'upgrade_check_loading_tips'        => 'Getting the latest content, please wait...',
            'upgrade_version_name'              => 'Updated Version：',
            'upgrade_date_name'                 => 'Update Date：',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'Wrong order ID',
            'express_choice_tips'               => 'Please select express delivery method',
            'payment_choice_tips'               => 'Please select payment method',
        ],
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Please click tick to enable',
            'save_no_data_tips'                 => 'No plug-in data to save',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => 'Save and take effect after removal. Are you sure to continue?',
            'address_no_data'                   => 'Address data is empty',
            'address_not_exist'                 => 'Address does not exist',
            'address_logo_message'              => 'Please upload the logo image',
        ],
        // 主导航
        'second_nav_list'                       => [
            ['name' => 'Base Config', 'type' => 'base'],
            ['name' => 'Site Settings', 'type' => 'siteset'],
            ['name' => 'Site Type', 'type' => 'sitetype'],
            ['name' => 'User Register', 'type' => 'register'],
            ['name' => 'User Login', 'type' => 'login'],
            ['name' => 'Password Recovery', 'type' => 'forgetpwd'],
            ['name' => 'Verification Code', 'type' => 'verify'],
            ['name' => 'Order after-sales', 'type' => 'orderaftersale'],
            ['name' => 'Annex', 'type' => 'attachment'],
            ['name' => 'Cache', 'type' => 'cache'],
            ['name' => 'Extensions', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => 'Home', 'type' => 'index'],
            ['name' => 'Goods', 'type' => 'goods'],
            ['name' => 'Search', 'type' => 'search'],
            ['name' => 'Order', 'type' => 'order'],
            ['name' => 'Discount', 'type' => 'discount'],
            ['name' => 'Extensions', 'type' => 'extends'],
        ],
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Background Login',
        'admin_login_info_bg_images_list_tips'  => [
            '1. The background image is located in the [public/static/admin/default/images/login] directory',
            '2. Naming rules for background pictures (1~50), such as 1.jpg',
        ],
        'map_type_tips'                         => 'Due to the different map standards of each company, do not switch maps at will, which will lead to inaccurate map coordinates.',
        'view_config_course_name'               => 'View the configuration tutorial',
        'apply_map_baidu_name'                  => 'Please apply at Baidu Map Open Platform',
        'apply_map_amap_name'                   => 'Please apply at the open platform of Gaode Map',
        'apply_map_tencent_name'                => 'Please apply at Tencent Map Open Platform',
        'apply_map_tianditu_name'               => 'Please apply at Tiantu Open Platform',
        'cookie_domain_list_tips'               => [
            '1. If it is empty by default, it is only valid for the currently accessed domain name',
            '2. If you need a secondary domain name to share cookies, fill in the top-level domain name, such as baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => 'Brand',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'The name is 2~30 characters long',
        'form_item_brand_category_id'           => 'Brand Category',
        'form_item_brand_category_id_message'   => 'Please select brand category',
        'form_item_website_url'                 => 'Website address',
        'form_item_website_url_placeholder'     => 'Official website address, starting with http://or https://',
        'form_item_website_url_message'         => 'Incorrect format of official website address',
        'form_item_describe'                    => 'describe',
        'form_item_describe_message'            => 'Description can be up to 230 characters',
        'form_item_logo'                        => 'LOGO',
        'form_item_logo_tips'                   => 'Recommended 150*50px',
    ],

    // 品牌分类
    'brandcategory'       => [
        'base_nav_title'                        => 'BrandCategory',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'The name is 2~16 characters long',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'Article',
        'detail_content_title'                  => 'Detail Content',
        'detail_images_title'                   => 'Detail Picture',
        // 表单
        'form_item_title'                       => 'Title',
        'form_item_title_message'               => 'The title is 2~60 characters long',
        'form_item_article_category'            => 'Article Category',
        'form_item_article_category_message'    => 'Please select an article category',
        'form_item_jump_url_title'              => 'Jump url address',
        'form_item_jump_url_tips'               => 'With http://or https://, only valid on the web side',
        'form_item_jump_url_message'            => 'The jump url address format is incorrect',
        'form_item_is_home_recommended_title'   => 'Home page recommendation',
        'form_item_content_title'               => 'Content',
        'form_item_content_placeholder'         => 'The content format is between 10 and 105000 characters. For more editing functions, please use the computer to access',
        'form_item_content_message'             => 'Content format is between 10 and 105000 characters',
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'ArticleCategory',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'The name is 2~16 characters long',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Custom Page',
        'detail_content_title'                  => 'Detail Content',
        'detail_images_title'                   => 'Detail Picture',
        // 表单
        'form_item_title'                       => 'Title',
        'form_item_title_message'               => 'The title is 2~60 characters long',
        'form_item_is_header'                   => 'Including head',
        'form_item_is_footer'                   => 'Including tail',
        'form_item_is_full_screen'              => 'Full screen',
        'form_item_content_title'               => 'Content',
        'form_item_content_placeholder'         => 'The content format is between 10 and 105000 characters. For more editing functions, please use the computer to access',
        'form_item_content_message'             => 'Content format is between 10 and 105000 characters',
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => 'Download more design templates',
        'upload_list_tips'                      => [
            '1. Select the downloaded page design zip package',
            '2. Import will automatically add a new piece of data',
        ],
        'operate_sync_tips'                     => 'The data is synchronized to the homepage drag visualization, and the data is not affected after modification, but do not delete the relevant attachments',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'The name is 2~16 characters long',
        'form_item_is_header'                   => 'Including head',
        'form_item_is_footer'                   => 'Including tail',
        'form_logo_tips'                        => 'Recommended size 300 * 300px',
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Q&A',
        'user_info_title'                       => 'User information',
        'form_item_name'                        => 'Contacts',
        'form_item_name_message'                => 'Contact format can be up to 30 characters',
        'form_item_tel'                         => 'Telephone',
        'form_item_tel_message'                 => 'Please fill in a valid phone number',
        'form_item_title'                       => 'Title',
        'form_item_title_message'               => 'Header format can be up to 60 characters',
        'form_item_access_count'                => 'Number of visits',
        'form_item_access_count_message'        => 'Number of visits in the format of 0~9',
        'form_item_content'                     => 'Content',
        'form_item_content_message'             => 'Content format is between 5 and 1000 characters',
        'form_item_reply'                       => 'Reply Content',
        'form_item_reply_message'               => 'Reply content format can be up to 1000 characters',
        'form_is_reply'                         => 'Reply or not',
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Please select a warehouse',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => 'The administrator information does not exist',
        // 列表
        'top_tips_list'                         => [
            '1. admin The account has all permissions by default and cannot be changed.',
            '2. admin The account cannot be changed, but can be modified in the data table( '.MyConfig('database.connections.mysql.prefix').'admin ) field username',
        ],
        'base_nav_title'                        => 'Admin',
        // 登录
        'login_type_username_title'             => 'Account Password',
        'login_type_mobile_title'               => 'Mobile Verify Code',
        'login_type_email_title'                => 'Email Verify Code',
        'login_close_tips'                      => 'Temporarily closed login',
        // 忘记密码
        'form_forget_password_name'             => 'Forgot Password?',
        'form_forget_password_tips'             => 'Please contact the administrator to reset the password',
        // 表单
        'form_item_username'                    => 'Userame',
        'form_item_username_placeholder'        => 'enter one username',
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
        'form_item_username_created_tips'       => 'Cannot be changed after creation',
        'form_item_username_edit_tips'          => 'Not changeable',
        'form_item_role'                        => 'Permission group',
        'form_item_role_message'                => 'Please select the role group',
        'form_item_password_edit_tips'          => 'Enter to change the password',
        'form_item_status'                      => 'Status',
        'form_item_status_message'              => 'Please select user status',
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'User Register Agreement', 'type' => 'register'],
            ['name' => 'User Privacy Policy', 'type' => 'privacy'],
            ['name' => 'Account Cancellation Agreement', 'type' => 'logout']
        ],
        'top_tips'          => 'Add parameter is to front-end access protocol address_ Content=1, only protocol content is displayed',
        'view_detail_name'                      => 'View Details',
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['view_type' => 'index','name' => 'Current Theme'],
            ['view_type' => 'upload','name' => 'Theme Install'],
            ['view_type' => 'package','name' => 'Source Package Download'],
        ],
        'nav_store_theme_name'                  => 'More topic downloads',
        'nav_theme_download_name'               => 'View the applet packaging tutorial',
        'nav_theme_download_tips'               => 'The theme of mobile phone is developed by uniapp (supporting multi-terminal applet+H5), and APP is also in emergency adaptation。',
        'form_alipay_extend_title'              => 'Customer service configuration',
        'form_alipay_extend_tips'               => 'PS: If [APP/applet] is enabled (online customer service is enabled), the following configuration must be filled in [Enterprise Code] and [Chat Window Code]',
        'list_no_data_tips'                     => 'No related theme packs',
        'list_author_title'                     => 'Author：',
        'list_version_title'                    => 'Applicable version：',
        'package_generate_tips'                 => 'The generation time is relatively long, please do not close the browser window!',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Mailbox Settings', 'type' => 'index'],
            ['name' => 'Message Template', 'type' => 'message'],
        ],
        'view_config_course_name'               => 'View the configuration tutorial',
        'top_tips'                              => 'Due to some differences between different mailbox platforms and different configurations, the specific configuration of the mailbox platform is subject to the tutorial',
        // 基础
        'test_title'                            => 'Test',
        'test_content'                          => 'Mail configuration - send test content',
        'base_item_admin_title'                 => 'Admin',
        'base_item_index_title'                 => 'Home',
        // 表单
        'form_item_test'                        => 'Test the email address received',
        'form_item_test_tips'                   => 'Please save the configuration before testing',
        'form_item_test_button_title'           => 'Test',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'Goods',
        'nav_right_list'                        => [
            ['name' => 'Basic Info', 'type'=>'base'],
            ['name' => 'Goods Spec', 'type'=>'operations'],
            ['name' => 'Goods Params', 'type'=>'parameters'],
            ['name' => 'Goods Photo', 'type'=>'photo'],
            ['name' => 'Goods Video', 'type'=>'video'],
            ['name' => 'Mobile Detail', 'type'=>'app'],
            ['name' => 'Web Detail', 'type'=>'web'],
            ['name' => 'Fictitious', 'type'=>'fictitious'],
            ['name' => 'Extends', 'type'=>'extends'],
            ['name' => 'SEO Info', 'type'=>'seo'],
        ],
        // 表单
        'form_item_title'                       => 'Goods Title',
        'form_item_title_message'               => 'Goods name format 2~160 characters',
        'form_item_category_id'                 => 'Goods Category',
        'form_item_category_id_message'         => 'Please select at least one goods category',
        'form_item_simple_desc'                 => 'Goods description',
        'form_item_simple_desc_message'         => 'Goods description format can be up to 230 characters',
        'form_item_model'                       => 'Goods model',
        'form_item_model_message'               => 'Goods model format can be up to 30 characters',
        'form_item_brand'                       => 'Brand',
        'form_item_brand_message'               => 'Please select a brand',
        'form_item_place_origin'                => 'Place of production',
        'form_item_place_origin_message'        => 'Please select the place of production',
        'form_item_inventory_unit'              => 'Inventory unit',
        'form_item_inventory_unit_message'      => 'Inventory unit format 1~6 characters',
        'form_item_give_integral'               => 'Percentage of free points for purchase',
        'form_item_give_integral_tips'          => [
            '1. Distribute according to the proportion of goods amount multiplied by quantity',
            '2. When the order is completed, it will be automatically distributed to the user to lock the points',
            '3. Site settings ->script processing in extension',
        ],
        'form_item_give_integral_placeholder'   => 'Purchase free points',
        'form_item_give_integral_message'       => 'Purchase a number with a percentage of 0 to 100 points',
        'form_item_buy_min_number'              => 'Minimum purchase quantity',
        'form_item_buy_min_number_message'      => 'The minimum purchase quantity ranges from 1 to 100000000',
        'form_item_buy_max_number'              => 'Maximum quantity of single purchase',
        'form_item_buy_max_number_tips'         => [
            '1. Single maximum value 100000000',
            '2. No limit if less than or equal to 0 or empty',
        ],
        'form_item_buy_max_number_message'      => 'The maximum number of single purchase ranges from 1 to 100000000',
        'form_item_site_type'                   => 'Goods Type',
        'form_item_site_type_tips'              => [
            '1. The currently configured site type is ( 站点类型 )',
            '2. If the goods type is not configured, follow the site type configured by the system',
            '3. When the set goods type is not included in the site type set by the system, the function of adding products to the shopping cart will be invalid',
        ],
        'form_item_site_type_message'           => 'Please select goods type',
        'form_item_images'                      => 'cover photo',
        'form_item_images_tips'                 => 'If left blank, take the first picture of the album and suggest 800*800px',
        'form_item_is_deduction_inventory'      => 'Inventory deduction',
        'form_item_is_deduction_inventory_tips' => 'The deduction rules are determined according to the background configuration ->deduction inventory rules',
        'form_item_is_shelves'                  => 'Upper and lower shelves',
        'form_item_is_shelves_tips'             => 'Not visible to users after being removed from the shelf',
        'form_item_spec_title'                  => 'Goods Spec',
        'form_item_params_title'                => 'Goods Params',
        'form_item_photo_title'                 => 'Goods Photo',
        'form_item_video_title'                 => 'Goods Video',
        'form_item_app_title'                   => 'Mobile Detail',
        'form_item_web_title'                   => 'Web Detail',
        'form_item_fictitious_title'            => 'Fictitious',
        'form_item_extends_title'               => 'Extends',
        'form_item_extends_popup_title'         => 'Spec Extended Data',
        // 规格
        'form_spec_top_list_tips'               => [
            '1. Adding specifications in batches can quickly create commodity SKUs, greatly saving SKU editing time. Shortcut operation data does not affect SKU data, and only overwrites SKU when it is generated.',
            '2. You can configure the specification template in the background commodity management ->commodity specification, select the commodity specification module to quickly generate the corresponding specification data, and effectively provide efficiency',
            '3. After goods are added successfully, add and configure inventory in warehouse management ->warehouse goods',
        ],
        'form_spec_template_tips'               => 'Incorrect specification template data',
        'form_spec_template_name_exist_tips'    => 'The same specification name already exists',
        'form_spec_template_placeholder'        => 'Goods specification template...',
        'form_spec_template_message'            => 'Please select a product specification template',
        'form_spec_quick_add_title'             => 'Batch add specifications',
        'form_spec_quick_generate_title'        => 'Generate specifications',
        'form_spec_type_title'                  => 'Spec Name',
        'form_spec_type_message'                => 'Please fill in the specification name',
        'form_spec_value_title'                 => 'Spec Value',
        'form_spec_value_message'               => 'Please fill in the specification value',
        'form_spec_value_add_title'             => 'Add Spec Value',
        'form_spec_empty_data_tips'             => 'Please add specifications first',
        'form_spec_advanced_batch_setup_title'  => 'Advanced batch settings',
        'form_spec_list_content_tips'           => 'You can directly click the specification line to drag and sort or click up and down to move',
        'form_spec_max_error'                   => 'Add at most '.MyC('common_spec_add_max_number', 3, true).'Column specifications can be configured in background management [System Settings - Background Configuration]',
        'form_spec_empty_fill_tips'             => 'Please fill in the specification first',
        'form_spec_images_message'              => 'Please upload the specification image',
        'form_spec_min_tips_message'            => 'At least one line of specification value needs to be reserved',
        'form_spec_quick_error'                 => 'The shortcut specification is empty',
        'form_spec_quick_tips_msg'              => 'Generating specifications will empty existing specification data. Do you want to continue?',
        'form_spec_move_type_tips'              => 'Incorrect operation type configuration',
        'form_spec_move_top_tips'               => 'Reached the top',
        'form_spec_move_bottom_tips'            => 'Reached the bottom',
        'form_spec_thead_price_title'           => 'Sales price (yuan)',
        'form_spec_thead_price_message'         => 'Please fill in valid sales amount',
        'form_spec_thead_original_price_title'  => 'Original price (yuan)',
        'form_spec_thead_original_price_message'=> 'Please fill in valid original price',
        'form_spec_thead_inventory_title'       => 'Inventory',
        'form_spec_thead_weight_title'          => 'Weight (kg)',
        'form_spec_thead_weight_message'        => 'Specification and weight 0~100000000',
        'form_spec_thead_volume_title'          => 'Volume (m ³)',
        'form_spec_thead_volume_message'        => 'Specification volume 0~100000000',
        'form_spec_thead_coding_title'          => 'Coding',
        'form_spec_thead_coding_message'        => 'Specification code can be up to 60 characters',
        'form_spec_thead_barcode_title'         => 'Barcode',
        'form_spec_thead_barcode_message'       => 'Barcode can be up to 60 characters',
        'form_spec_row_add_title'               => 'Add a row',
        'form_spec_images_tips'                 => 'The specification name is consistent with the specification value. The same specification name can be added once, and repeated addition will cover the front. The order will not affect the front display effect.',
        'form_spec_images_title'                => 'Goods specification picture',
        'form_spec_images_add_title'            => 'Add specification picture',
        'form_spec_images_add_auto_first'       => 'No',
        'form_spec_images_add_auto_last'        => 'Automatic generation of column specifications',
        'form_spec_images_type_title'           => 'Spec Name',
        'form_spec_images_type_message'         => 'Please fill in the specification name',
        'form_spec_images_images_message'       => 'Please upload the specification image',
        'form_spec_all_operate_title'           => 'Batch operation',
        'form_spec_all_operate_placeholder'     => 'Batch set value',
        // 参数
        'form_params_select_title'              => 'Goods Params Template',
        'form_params_select_placeholder'        => 'Goods Params Template...',
        'form_params_select_message'            => 'Please select a product parameter template',
        'form_params_value_placeholder'         => 'Paste commodity parameter configuration information',
        'form_params_config_copy_title'         => 'Copy Config',
        'form_params_config_empty_title'        => 'Clear Params',
        'form_params_list_content_tips'         => 'You can directly click the parameter line to drag and sort or click up and down to move',
        // 相册
        'form_photo_top_tips'                   => 'You can drag and drop pictures to sort. It is recommended that the size of pictures be consistent with 800 * 800px, with a maximum of 30 pictures',
        'form_photo_button_add_name'            => 'Upload Photo',
        // 视频
        'form_video_top_tips'                   => 'Video has more sense of introduction than pictures and text, and only supports mp4 format',
        'form_video_button_add_name'            => 'Upload Video',
        // 手机详情
        'form_app_top_tips'                     => 'After setting the phone details, the phone details will be displayed in the phone mode, such as [App, APP]',
        'form_app_value_title'                  => 'Text Content',
        'form_app_value_message'                => 'Text content can be up to 105000 characters',
        'form_app_button_add_name'              => 'Add Phone Detail',
        // 电脑详情
        'form_web_content_message'              => 'The details of the computer terminal can be up to 105000 characters',
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'GoodsCategory',
        // 表单
        'form_item_icon'                        => 'Icon',
        'form_item_icon_tips'                   => '100 * 100px recommended',
        'form_item_big_images'                  => 'Large Picture',
        'form_item_big_images_tips'             => '360 * 360px recommended',
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~16 characters',
        'form_item_vice_name'                   => 'Sub Name',
        'form_item_vice_name_message'           => 'The secondary name can be up to 60 characters',
        'form_item_describe'                    => 'Describe',
        'form_item_describe_message'            => 'Description can be up to 200 characters',
        'form_item_is_home_recommended'         => 'HomePage Recommended',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'GoodsComments',
        // 表单
        'form_item_goods_info_title'            => 'Goods Info',
        'form_item_user_info_title'             => 'User Info',
        'form_item_business_type'               => 'Business Type',
        'form_item_business_type_placeholder'   => 'Business type...',
        'form_item_business_type_message'       => 'Please select a business type',
        'form_item_rating'                      => 'Score',
        'form_item_rating_placeholder'          => 'No score',
        'form_item_rating_message'              => 'Please select a score',
        'form_item_content'                     => 'Comment Content',
        'form_item_content_message'             => 'Comment content is between 6 and 230 characters',
        'form_item_reply'                       => 'Reply Content',
        'form_item_reply_message'               => 'Reply content can be up to 230 characters',
        'form_item_reply_time'                  => 'Reply Time',
        'form_item_reply_time_message'          => 'Incorrect format of reply time',
        'form_item_is_reply'                    => 'Reply or not',
        'form_item_is_anonymous'                => 'Anonymous or not',
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'GoodsParams',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~30 characters',
        'form_item_category_id'                 => 'Goods Category',
        'form_item_category_id_tips'            => 'Include children',
        'form_item_category_id_message'         => 'Please select product classification',
        'form_item_config_title'                => 'Params Config',
        'form_item_config_value_placeholder'    => 'Paste commodity parameter configuration information',
        'form_item_config_template_title'       => 'Goods Params template',
        'form_item_config_copy_title'           => 'Copy Config',
        'form_item_config_empty_title'          => 'Clear Params',
        'form_item_config_list_content_tips'    => 'You can directly click the parameter line to drag and sort or click up and down to move',
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 1~30 characters',
        'form_item_category_id'                 => 'Goods Category',
        'form_item_category_id_tips'            => 'Include children',
        'form_item_category_id_message'         => 'Please select product classification',
        'form_item_content'                     => 'Spec Value',
        'form_item_content_placeholder'         => 'Specification value (multiple values can be achieved by entering Enter)',
        'form_item_content_message'             => 'Specification value format: 1~1000 characters',
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'Express',
        // 表单
        'form_item_icon'                        => 'Icon',
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~16 characters',
        'form_item_website_url'                 => 'Website Address',
        'form_item_website_url_placeholder'     => 'Official website address, starting with http://or https://',
        'form_item_website_url_message'         => 'Incorrect format of official website address',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Mobile User Center Navigation',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format: 2~60 characters',
        'form_item_desc'                        => 'Describe',
        'form_item_desc_message'                => 'Description can be up to 18 characters',
        'form_item_images_url'                  => 'Navigation Icon',
    ],

    // 手机首页导航
    'apphomenav'          => [
        'base_nav_title'                        => 'Home Navigation',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format: 2~60 characters',
        'form_item_images_url'                  => 'Navigation Icon',
        'form_item_is_need_login'               => 'Whether login is required',
    ],

    // 公共
    'common'                => [
        // 商品参数
        'form_goods_params_config_error_tips'   => 'Goods parameter configuration information',
        'form_goods_params_copy_no_tips'        => 'Please paste the configuration information first',
        'form_goods_params_copy_error_tips'     => 'Configuration format error',
        'form_goods_params_type_message'        => 'Please select the display type of goods parameters',
        'form_goods_params_params_name'         => 'Parameter name',
        'form_goods_params_params_message'      => 'Please fill in the parameter name',
        'form_goods_params_value_name'          => 'Parameter value',
        'form_goods_params_value_message'       => 'Please fill in the parameter value',
        'form_goods_params_move_type_tips'      => 'Incorrect operation type configuration',
        'form_goods_params_move_top_tips'       => 'Reached the top',
        'form_goods_params_move_bottom_tips'    => 'Reached the bottom',
        'form_goods_params_thead_type_title'    => 'Display scope',
        'form_goods_params_thead_name_title'    => 'Parameter name',
        'form_goods_params_thead_value_title'   => 'Parameter value',
        'form_goods_params_row_add_title'       => 'Add a row',
        'form_goods_params_list_tips'           => [
            '1. All (displayed under commodity basic information and detail parameters)',
            '2. Details (only displayed under the commodity details parameter)',
            '3. Basic (only displayed under commodity basic information)',
            '4. The shortcut operation will clear the original data and reload the page to restore the original data (only effective after saving the product)',
        ],
    ],
];
?>