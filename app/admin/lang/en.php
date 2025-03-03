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
            'goods_hot_tips'                    => 'Show only the first 13 items',
            'payment_name'                      => 'Payment Method',
            'order_region_name'                 => 'Order geographical distribution',
            'order_region_tips'                 => 'Only 10 pieces of data are displayed',
            'new_user_name'                     => 'New Users',
            'buy_user_name'                     => 'Ordering Users',
            'upgrade_check_loading_tips'        => 'Getting the latest content, please wait...',
            'upgrade_version_name'              => 'Updated Version：',
            'upgrade_date_name'                 => 'Update Date：',
        ],
        // 页面基础
        'base_update_system_title'              => 'System updates',
        'base_update_button_title'              => 'Update Now',
        'base_item_base_stats_title'            => 'Shop Statistics',
        'base_item_base_stats_tips'             => 'Time filtering is only valid for totals',
        'base_item_user_title'                  => 'Total Users',
        'base_item_order_number_title'          => 'Total Orders',
        'base_item_order_complete_number_title' => 'Total Transaction Volume',
        'base_item_order_complete_title'        => 'Order Amount',
        'base_item_last_month_title'            => 'LastMonth',
        'base_item_same_month_title'            => 'SameMonth',
        'base_item_yesterday_title'             => 'Yesterday',
        'base_item_today_title'                 => 'Today',
        'base_item_order_profit_title'          => 'Trend of order transaction amount',
        'base_item_order_trading_title'         => 'Order trading trend',
        'base_item_order_tips'                  => 'All Orders',
        'base_item_hot_sales_goods_title'       => 'Hot Goods',
        'base_item_hot_sales_goods_tips'        => 'Orders without cancelling closing',
        'base_item_payment_type_title'          => 'Payment Type',
        'base_item_map_whole_country_title'     => 'Order geographical distribution',
        'base_item_map_whole_country_tips'      => 'Excluding orders and default dimensions (provinces) to cancel closing',
        'base_item_map_whole_country_province'  => 'Province',
        'base_item_map_whole_country_city'      => 'City',
        'base_item_map_whole_country_county'    => 'County',
        'base_item_new_user_title'              => 'New Users',
        'base_item_buy_user_title'              => 'Ordering Users',
        'system_info_title'                     => 'System Info',
        'system_ver_title'                      => 'Software Version',
        'system_os_ver_title'                   => 'Operating System',
        'system_php_ver_title'                  => 'PHP Version',
        'system_mysql_ver_title'                => 'MySQL Version',
        'system_server_ver_title'               => 'Server Info',
        'system_host_title'                     => 'Current Domain',
        'development_team_title'                => 'Development Team',
        'development_team_website_title'        => 'Website',
        'development_team_website_value'        => 'Shanghai Zongzhige Technology Co., Ltd',
        'development_team_support_title'        => 'Support',
        'development_team_support_value'        => 'ShopXO enterprise e-commerce system provider',
        'development_team_ask_title'            => 'Questions',
        'development_team_ask_value'            => 'ShopXO exchange questions',
        'development_team_agreement_title'      => 'Agreement',
        'development_team_agreement_value'      => 'View open source agreement',
        'development_team_update_log_title'     => 'Update Log',
        'development_team_update_log_value'     => 'View update log',
        'development_team_members_title'        => 'R&D members',
        'development_team_members_value'        => [
            ['name' => 'Brother Gong', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => 'User',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'User ID',
            'number_code'           => 'Number Code',
            'system_type'           => 'System Type',
            'platform'              => 'Platform',
            'avatar'                => 'Avatar',
            'username'              => 'Username',
            'nickname'              => 'Nickname',
            'mobile'                => 'Mobile',
            'email'                 => 'Email',
            'gender_name'           => 'Gender',
            'status_name'           => 'Status',
            'province'              => 'Province',
            'city'                  => 'City',
            'county'                => 'District/County',
            'address'               => 'Detail Address',
            'birthday'              => 'Birthday',
            'integral'              => 'Valid Integral',
            'locking_integral'      => 'Lock Integral',
            'referrer'              => 'Referrer',
            'referrer_placeholder'  => 'Please enter the invitation user name/nickname/mobile phone/email',
            'add_time'              => 'Creation Time',
            'upd_time'              => 'Update Time',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => 'User Address',
        // 详情
        'detail_user_address_idcard_name'       => 'Full Name',
        'detail_user_address_idcard_number'     => 'Number',
        'detail_user_address_idcard_pic'        => 'Photo',
        // 动态表格
        'form_table'                            => [
            'user'              => 'User Info',
            'user_placeholder'  => 'Please enter user name/nickname/mobile phone/email',
            'alias'             => 'Alias',
            'name'              => 'Name',
            'tel'               => 'Tel',
            'province_name'     => 'Province',
            'city_name'         => 'City',
            'county_name'       => 'County/County',
            'address'           => 'Detail Address',
            'address_last_code' => 'Address Last Level Encoding',
            'position'          => 'Position',
            'idcard_info'       => 'Idcard Info',
            'is_default'        => 'Default or not',
            'add_time'          => 'Creation Time',
            'upd_time'          => 'Update Time',
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
        'base_nav_list'                       => [
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
            ['name' => 'Search', 'type' => 'search'],
            ['name' => 'Order', 'type' => 'order'],
            ['name' => 'Goods', 'type' => 'goods'],
            ['name' => 'Cart', 'type' => 'cart'],
            ['name' => 'Extensions', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => 'Site Status',
        'base_item_site_domain_title'           => 'Site domain name address',
        'base_item_site_filing_title'           => 'Filing Info',
        'base_item_site_other_title'            => 'Other',
        'base_item_session_cache_title'         => 'Session Cache Config',
        'base_item_data_cache_title'            => 'Data Cache Config',
        'base_item_redis_cache_title'           => 'Redis Cache Config',
        'base_item_crontab_config_title'        => 'Timing Script Config',
        'base_item_regex_config_title'          => 'Regular configuration',
        'base_item_quick_nav_title'             => 'Quick Navigation',
        'base_item_user_base_title'             => 'User Base',
        'base_item_user_address_title'          => 'User Address',
        'base_item_multilingual_title'          => 'Multilingual',
        'base_item_site_auto_mode_title'        => 'Automatic Mode',
        'base_item_site_manual_mode_title'      => 'Manual Mode',
        'base_item_default_payment_title'       => 'Default Payment Method',
        'base_item_display_type_title'          => 'Display Type',
        'base_item_self_extraction_title'       => 'Self-promotion',
        'base_item_fictitious_title'            => 'Virtual Sales',
        'choice_upload_logo_title'              => 'Choice Logo',
        'add_goods_title'                       => 'Goods Add',
        'add_self_extractio_address_title'      => 'Add Address',
        'site_domain_tips_list'                 => [
            '1. If the site domain name is not set, the current site domain name and address will be used[ '.__MY_DOMAIN__.' ]',
            '2. If the attachment and static address are not set, the static domain name address of the current site will be used[ '.__MY_PUBLIC_URL__.' ]',
            '3. If public is not set as the root directory on the server side, the configuration of [attachment cdn domain name, css/js static file cdn domain name] needs to be followed by public, such as:'.__MY_PUBLIC_URL__.'public/',
            '4. When running the project in command line mode, the address of the zone must be configured, otherwise some addresses in the project will be missing the domain name information',
            '5. Do not randomly configure. The wrong address will cause the website to be inaccessible (the address configuration starts with http). If your own site is configured with https, it starts with https',
        ],
        'site_cache_tips_list'                  => [
            '1. The default file cache and Redis cache PHP need to be installed first',
            '2. Please ensure the stability of Redis service (after the session uses the cache, the unstable service may cause the background to be unable to log in)',
            '3. In case of Redis service exception, you cannot log in to the management background and modify the [session.php, cache. php] file in the [config] directory of the configuration file',
        ],
        'goods_tips_list'                       => [
            '1. By default, the WEB side displays 3 levels, the lowest level is 1 and the highest level is 3 (if set to 0, the default is 3)',
            '2. Mobile terminal default display level 0 (goods list mode), minimum level 0, and maximum level 3 (1~3 are classified display mode)',
            '3. The style of the front category page will be different if the level is different',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Configure the maximum number of goods displayed on each floor',
            '2. It is not recommended to modify the quantity too large, which will lead to too large blank area on the left side of the PC',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'It can be summarized as follows: popularity ->sales volume ->descending order (desc) of the latest',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. Click the title of the item to drag and sort it and display it in order',
            '2. It is not recommended to add many goods, which will lead to too large blank area on the left side of the PC',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. By default, [User Name, Mobile Phone, Email] is used as the unique user',
            '2. If enabled, add the [System ID] and act as the unique user',
        ],
        'extends_crontab_tips'                  => 'It is recommended that you add the script address to the timing request of the Linux scheduled task (the result is SUCS: 0, FAIL: 0, followed by the colon is the number of data processed, SUCS succeeded, FALI failed)',
        'left_images_random_tips'               => 'The left picture can upload up to 3 pictures, and one of them can be displayed randomly each time',
        'background_color_tips'                 => 'Customizable background image, default background gray',
        'site_setup_layout_tips'                => 'The drag mode needs to enter the homepage design page by yourself. Please save the selected configuration before',
        'site_setup_layout_button_name'         => 'design page',
        'site_setup_goods_category_tips'        => 'If you need more floor displays, please go to/Product Management ->Product Classification, Primary Classification Settings Home Page Recommendation',
        'site_setup_goods_category_no_data_tips'=> 'There is no data for the time being. Please go to/Product Management ->Product Classification, Primary Classification Settings Home Page for recommendation',
        'site_setup_order_default_payment_tips' => 'You can set the default payment method corresponding to different platforms. Please install the payment plug-in in [Website Management ->Payment Method] to enable and open it to users',
        'site_setup_choice_payment_message'     => 'Please select {:name} default payment method',
        'sitetype_top_tips_list'                => [
            '1. Express delivery: Conventional e-commerce process, where the user selects the delivery address to place an order and make payment ->the merchant distributes the shipment to a third-party logistics provider ->confirms receipt ->completes the order',
            '2. Same city: Same city rider or self delivery, users choose the delivery address to place an order and pay ->the merchant sends it to a third-party delivery in the same city or self delivery ->confirms receipt ->order completion',
            '3. Display: Only display products, inquiries can be initiated (orders cannot be placed)',
            '4. Self pickup: When placing an order, select the self pickup address, and the user places the order for payment ->confirms the pickup ->completes the order',
            '5. Virtual: Conventional e-commerce process, where users place orders and make payments ->automatic shipping ->confirm pick-up ->order completion',
        ],
        // 添加自提地址表单
        'form_take_address_title'                  => 'Self pickup address',
        'form_take_address_logo'                   => 'LOGO',
        'form_take_address_logo_tips'              => '300 * 300px proposal',
        'form_take_address_alias'                  => 'Alias',
        'form_take_address_alias_message'          => 'Alias format can be up to 16 characters',
        'form_take_address_name'                   => 'Contacts Name',
        'form_take_address_name_message'           => 'Contact name format is between 2 and 16 characters',
        'form_take_address_tel'                    => 'Contact Tel',
        'form_take_address_tel_message'            => 'Please fill in the contact number',
        'form_take_address_address'                => 'Detail Address',
        'form_take_address_address_message'        => 'Detailed address format is between 1 and 80 characters',
        // 域名绑定语言
        'form_domain_multilingual_domain_name'     => 'Domain Name',
        'form_domain_multilingual_domain_message'  => 'Please fill in the domain name',
        'form_domain_multilingual_select_message'  => 'Please select the corresponding language for the domain name',
        'form_domain_multilingual_add_title'       => 'Add domain name',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Background Login',
        'admin_login_info_bg_images_list_tips'  => [
            '1. The background image is located in the [public/static/admin/default/images/login] directory',
            '2. Naming rules for background pictures (1~50), such as 1.png',
        ],
        'map_type_tips'                         => 'Due to the different map standards of each company, do not switch maps at will, which will lead to inaccurate map coordinates.',
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
        // 动态表格
        'form_table'                            => [
            'id'                   => 'Brand ID',
            'name'                 => 'Name',
            'describe'             => 'Describe',
            'logo'                 => 'LOGO',
            'url'                  => 'Website Url',
            'brand_category_text'  => 'Category',
            'is_enable'            => 'Enable or not',
            'sort'                 => 'Sort',
            'add_time'             => 'Creation Time',
            'upd_time'             => 'Update Time',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => 'BrandCategory',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'Article',
        'detail_content_title'                  => 'DetailContent',
        'detail_images_title'                   => 'DetailImages',
        // 动态表格
        'form_table'                            => [
            'id'                     => 'ArticleID',
            'cover'                  => 'Cover',
            'info'                   => 'Title',
            'describe'               => 'Describe',
            'article_category_name'  => 'Category',
            'is_enable'              => 'Enable or not',
            'is_home_recommended'    => 'HomePage recommend',
            'jump_url'               => 'Jump Url',
            'images_count'           => 'Images Count',
            'access_count'           => 'Access Count',
            'add_time'               => 'Creation Time',
            'upd_time'               => 'Update Time',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => 'ArticleCategory',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => 'Custom Page',
        'detail_content_title'                  => 'Detail Content',
        'detail_images_title'                   => 'Detail Picture',
        'save_view_tips'                        => 'Please save before previewing the effect',
        // 动态表格
        'form_table'                            => [
            'id'              => 'Data ID',
            'logo'            => 'logo',
            'name'            => 'name',
            'is_enable'       => 'Enable or not',
            'is_header'       => 'Header or not',
            'is_footer'       => 'Footer or not',
            'is_full_screen'  => 'Full Screen',
            'images_count'    => 'Images Count',
            'access_count'    => 'Access Count',
            'add_time'        => 'Creation Time',
            'upd_time'        => 'Update Time',
        ],
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
        'form_logo_tips'                        => 'Recommended size 300 * 300px',
        // 动态表格
        'form_table'                            => [
            'id'                => 'Data ID',
            'logo'              => 'logo',
            'name'              => 'Name',
            'access_count'      => 'Access Count',
            'is_enable'         => 'Enable or not',
            'is_header'         => 'Header or not',
            'is_footer'         => 'Footer or not',
            'seo_title'         => 'SEO Title',
            'seo_keywords'      => 'SEO Keywords',
            'seo_desc'          => 'SEO Describe',
            'add_time'          => 'Creation Time',
            'upd_time'          => 'Update Time',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Warehouse',
        'top_tips_list'                         => [
            '1. The higher the weight value is, the higher the weight is. The inventory is deducted according to the weight.)',
            '2. The warehouse can only be soft-deleted, will not be available after deletion, and only the data in the database can be retained), and the associated goods data can be deleted by itself',
            '3. The warehouse will be deactivated and deleted, and the associated goods inventory will be released immediately',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => 'Name/Alias',
            'level'          => 'Level',
            'is_enable'      => 'Enable or not',
            'contacts_name'  => 'Contacts Name',
            'contacts_tel'   => 'Contacts Tel',
            'province_name'  => 'Province',
            'city_name'      => 'City',
            'county_name'    => 'District/County',
            'address'        => 'Detail Address',
            'position'       => 'Position',
            'add_time'       => 'Creation Time',
            'upd_time'       => 'Update Time',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => 'Please select a warehouse',
        ],
        // 基础
        'add_goods_title'                       => 'Goods Add',
        'no_spec_data_tips'                     => 'No specification data',
        'batch_setup_inventory_placeholder'     => 'Batch set value',
        'base_spec_inventory_title'             => 'Specification Inventory',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Base Info',
            'goods_placeholder'  => 'Please enter the goods name/model',
            'warehouse_name'     => 'Warehouse',
            'is_enable'          => 'Enable or not',
            'inventory'          => 'Total Inventory',
            'spec_inventory'     => 'Spec Inventory',
            'add_time'           => 'Creation Time',
            'upd_time'           => 'Update Time',
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
        'login_type_username_title'             => 'Account',
        'login_type_mobile_title'               => 'Mobile',
        'login_type_email_title'                => 'Email',
        'login_close_tips'                      => 'Temporarily closed login',
        // 忘记密码
        'form_forget_password_name'             => 'Forgot Password?',
        'form_forget_password_tips'             => 'Please contact the administrator to reset the password',
        // 动态表格
        'form_table'                            => [
            'username'              => 'Admin',
            'status'                => 'Status',
            'gender'                => 'Gender',
            'mobile'                => 'Mobile',
            'email'                 => 'Email',
            'role_name'             => 'Role Group',
            'login_total'           => 'Number of Logins',
            'login_time'            => 'Last Login Time',
            'add_time'              => 'Creation Time',
            'upd_time'              => 'Update Time',
        ],
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

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'BaseConfig', 'type' => 'index'],
            ['name' => 'APP/Applet', 'type' => 'app'],
        ],
        'home_diy_template_title'               => 'Home DIY Template',
        'online_service_title'                  => 'Online Service',
        'user_base_popup_title'                 => 'Pop up prompt for user basic information',
        'user_onekey_bind_mobile_tips_list'     => [
            '1. One click login binding for obtaining the current mini program platform account or mobile phone number of this device. Currently, only WeChat mini programs, Baidu mini programs, and Headline mini programs are supported',
            '2. Dependency requires the activation of Force Phone Binding to be effective',
            '3. Some mini program platforms may need to apply for permissions. Please apply according to the requirements of the mini program platform before corresponding activation',
        ],
        'user_address_platform_import_tips_list'=> [
            '1. Obtain the shipping address of the current mini program platform app account, currently only supports 【 mini program 】',
            '2. After confirming the import, directly add it as the system users shipping address',
            '3. Some mini program platforms may need to apply for permissions. Please apply according to the requirements of the mini program platform before corresponding activation',
        ],
        'user_base_popup_top_tips_list'         => [
            '1. Currently, only the WeChat mini program platform automatically authorizes login without user nickname and avatar information',
        ],
        'online_service_top_tips_list'          => [
            '1. Customize customer service HTTP protocol to open in webview mode',
            '2. Customer service priority order: [Customer service system ->Custom customer service ->Enterprise WeChat customer service (only effective for app+h5+WeChat mini program) ->Customer service on various platforms ->Telephone customer service]',
        ],
        'home_diy_template_tips'                => 'If DIY template is not selected, it will default to following the unified homepage configuration',
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Current Theme', 'type' => 'index'],
            ['name' => 'Theme Install', 'type' => 'upload'],
            ['name' => 'Source Package Download', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => 'More topic downloads',
        'nav_theme_download_name'               => 'View the applet packaging tutorial',
        'nav_theme_download_tips'               => 'The mobile theme is developed using Uniapp (supporting multiple mini programs, H5, and APP)',
        'form_alipay_extend_title'              => 'Customer service configuration',
        'form_alipay_extend_tips'               => 'PS: If [APP/applet] is enabled (online customer service is enabled), the following configuration must be filled in [Enterprise Code] and [Chat Window Code]',
        'form_theme_upload_tips'                => 'Upload a zip compressed installation package',
        'list_no_data_tips'                     => 'No related theme packs',
        'list_author_title'                     => 'Author',
        'list_version_title'                    => 'Applicable version',
        'package_generate_tips'                 => 'The generation time is relatively long, please do not close the browser window!',
        // 动态表格
        'form_table'                            => [
            'name'  => 'Package Name',
            'size'  => 'Size',
            'url'   => 'Download Address',
            'time'  => 'Creation Time',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Sms Settings', 'type' => 'index'],
            ['name' => 'Message Template', 'type' => 'message'],
        ],
        'top_tips'                              => 'AliCloud SMS management address',
        'top_to_aliyun_tips'                    => 'Click to buy SMS from AliCloud',
        'base_item_admin_title'                 => 'Admin',
        'base_item_index_title'                 => 'Home',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Email Settings', 'type' => 'index'],
            ['name' => 'Message Template', 'type' => 'message'],
        ],
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

    // seo设置
    'seo'                   => [
        'top_tips'                              => 'Configure corresponding pseudo-static rules according to different server environments [Nginx, Apache, IIS]',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => 'Goods',
        'goods_nav_list'                        => [
            'base'            => ['name' => 'Basic Info', 'type'=>'base'],
            'spec'            => ['name' => 'Goods Spec', 'type'=>'spec'],
            'spec_images'     => ['name' => 'Spec Images', 'type'=>'spec_images'],
            'parameters'      => ['name' => 'Goods Params', 'type'=>'parameters'],
            'photo'           => ['name' => 'Goods Photo', 'type'=>'photo'],
            'video'           => ['name' => 'Goods Video', 'type'=>'video'],
            'app'             => ['name' => 'Mobile Detail', 'type'=>'app'],
            'web'             => ['name' => 'Web Detail', 'type'=>'web'],
            'fictitious'      => ['name' => 'Fictitious', 'type'=>'fictitious'],
            'extends'         => ['name' => 'Extends', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO Info', 'type'=>'seo'],
        ],
        'delete_only_goods_text'                => 'Goods Only',
        'delete_goods_and_images_text'          => 'Goods and Images',
        // 动态表格
        'form_table'                            => [
            'id'                      => 'Goods ID',
            'info'                    => 'Goods Info',
            'info_placeholder'        => 'Please enter product name/brief description/code/barcode/SEO information',
            'category_text'           => 'Category',
            'brand_name'              => 'Brand',
            'price'                   => 'Sales Price(yuan)',
            'original_price'          => 'Original Price(yuan)',
            'inventory'               => 'Inventory Count',
            'is_shelves'              => 'Shelves or not',
            'is_deduction_inventory'  => 'Inventory Deduction',
            'site_type'               => 'Goods Type',
            'model'                   => 'Goods Model',
            'place_origin_name'       => 'Place',
            'give_integral'           => 'Give Integral',
            'buy_min_number'          => 'Buy Min Number',
            'buy_max_number'          => 'Buy Max Number',
            'sort_level'              => 'Sort Weight',
            'sales_count'             => 'Sales Count',
            'access_count'            => 'Access Count',
            'add_time'                => 'Creation Time',
            'upd_time'                => 'Update Time',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'GoodsCategory',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => 'GoodsComments',
        // 动态表格
        'form_table'                            => [
            'user'                       => 'User Info',
            'user_placeholder'           => 'Please enter user name/nickname/mobile phone/email',
            'goods'                      => 'Base Info',
            'goods_placeholder'          => 'Please enter the goods name/model',
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

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => 'GoodsParams',
        // 动态表格
        'form_table'                            => [
            'category_id'   => 'Goods Category',
            'name'          => 'Name',
            'is_enable'     => 'Enable or not',
            'config_count'  => 'Params Count',
            'add_time'      => 'Creation Time',
            'upd_time'      => 'Update Time',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => 'Goods Category',
            'name'         => 'Name',
            'is_enable'    => 'Enable or not',
            'content'      => 'Spec Value',
            'add_time'     => 'Creation Time',
            'upd_time'     => 'Update Time',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'User Info',
            'user_placeholder'   => 'Please enter user name/nickname/mobile phone/email',
            'goods'              => 'Goods Info',
            'goods_placeholder'  => 'Please enter the goods name/brief description/SEO information',
            'price'              => 'Sales Price(yuan)',
            'original_price'     => 'Original Price(yuan))',
            'add_time'           => 'Creation Time',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'User Info',
            'user_placeholder'   => 'Please enter user name/nickname/mobile phone/email',
            'goods'              => 'Goods Info',
            'goods_placeholder'  => 'Please enter the goods name/brief description/SEO information',
            'price'              => 'Sales Price(yuan)',
            'original_price'     => 'Original Price(yuan))',
            'add_time'           => 'Creation Time',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => 'User Info',
            'user_placeholder'   => 'Please enter user name/nickname/mobile phone/email',
            'goods'              => 'Goods Info',
            'goods_placeholder'  => 'Please enter the goods name/brief description/SEO information',
            'price'              => 'Sales Price(yuan)',
            'original_price'     => 'Original Price(yuan))',
            'add_time'           => 'Creation Time',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => 'Friendly link',
        // 动态表格
        'form_table'                            => [
            'info'                => 'Name',
            'url'                 => 'Url Address',
            'describe'            => 'Describe',
            'is_enable'           => 'Enable or not',
            'is_new_window_open'  => 'Whether to open a new Window',
            'sort'                => 'Sort',
            'add_time'            => 'Creation Time',
            'upd_time'            => 'Update Time',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'Header', 'type' => 'header'],
            ['name' => 'Footer', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => 'Custom',
            'article'           => 'Article',
            'customview'        => 'Custom Page',
            'goods_category'    => 'Goods Category',
            'design'            => 'Page Design',
            'plugins'           => 'Plugins Home',
        ],
        // 表单
        'form_item_pid'                         => 'Navigation Level',
        'form_item_pid_placeholder'             => 'Primary column...',
        'form_item_pid_message'                 => 'Please select the navigation level',
        'form_item_name'                        => 'Navigation Name',
        'form_item_name_tips'                   => 'Default {:type} name',
        'form_item_name_message'                => 'Navigation name format 2~16 characters',
        'form_item_url'                         => 'Url Address',
        'form_item_url_placeholder'             => 'Url address, starting with http://or https://',
        'form_item_url_message'                 => 'Incorrect format of url address',
        'form_item_value_article_message'       => 'Wrong article selection',
        'form_item_value_customview_message'    => 'Incorrect selection of custom page',
        'form_item_value_goods_category_message'=> 'Wrong selection of goods classification',
        'form_item_value_design_message'        => 'Incorrect selection of page design',
        // 动态表格
        'form_table'                            => [
            'info'                => 'Navigation Name',
            'data_type'           => 'Data Type',
            'is_show'             => 'Show or not',
            'is_new_window_open'  => 'New window open',
            'sort'                => 'Sort',
            'add_time'            => 'Creation Time',
            'upd_time'            => 'Update Time',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => 'Wrong order ID',
            'payment_choice_tips'               => 'Please select payment method',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Delivery Operation',
        'form_service_title'                    => 'Service Operation',
        'form_payment_title'                    => 'Payment Operation',
        'form_item_take'                        => 'Take Code',
        'form_item_take_message'                => 'Please fill in the 4-digit pickup code',
        'form_item_express_add_name'            => 'Add express delivery',
        'form_item_express_choice_win_name'     => 'Choose express delivery',
        'form_item_express_id'                  => 'Express delivery method',
        'form_item_express_number'              => 'Express Number',
        'form_item_service_name'                => 'Name of service personnel',
        'form_item_service_name_message'        => 'Please fill in the name of the service personnel',
        'form_item_service_mobile'              => 'Service personnels mobile phones',
        'form_item_service_mobile_message'      => 'Please fill in the service personnels mobile phone number',
        'form_item_service_time'                => 'Service Time',
        'form_item_service_start_time'          => 'Service start time',
        'form_item_service_start_time_message'  => 'Please select the service start time',
        'form_item_service_end_time'            => 'End time of service',
        'form_item_service_end_time_message'    => 'Please select the end time of the service',
        'form_item_note'                        => 'memo',
        'form_item_note_message'                => 'Note information can be up to 200 characters long',
        // 地址
        'detail_user_address_title'             => 'Shipping Address',
        'detail_user_address_name'              => 'Receiving Name',
        'detail_user_address_tel'               => 'Receiving Phone',
        'detail_user_address_value'             => 'Address',
        'detail_user_address_idcard'            => 'ID Card Info',
        'detail_user_address_idcard_name'       => 'Full Name',
        'detail_user_address_idcard_number'     => 'Number',
        'detail_user_address_idcard_pic'        => 'Photo',
        'detail_take_address_title'             => 'Take Address',
        'detail_take_address_contact'           => 'Contact Info',
        'detail_take_address_value'             => 'Detail Info',
        'detail_service_title'                  => 'Service Info',
        'detail_fictitious_title'               => 'Key Info',
        // 订单售后
        'detail_aftersale_status'               => 'Status',
        'detail_aftersale_type'                 => 'Type',
        'detail_aftersale_price'                => 'Price',
        'detail_aftersale_number'               => 'Number',
        'detail_aftersale_reason'               => 'Reason',
        // 商品
        'detail_goods_title'                    => 'Order Goods',
        'detail_payment_amount_less_tips'       => 'Please note that the order payment amount is less than the total amount',
        'detail_no_payment_tips'                => 'Please note that the order has not been paid',
        // 动态表格
        'form_table'                            => [
            'goods'               => 'Base Info',
            'goods_placeholder'   => 'Please enter order ID/order number/goods name/model',
            'user'                => 'User Info',
            'user_placeholder'    => 'Please enter user name/nickname/mobile phone/email',
            'status'              => 'Order Status',
            'pay_status'          => 'Payment Status',
            'total_price'         => 'Total Price (yuan)',
            'pay_price'           => 'Payment Amount (yuan)',
            'price'               => 'Price (yuan)',
            'warehouse_name'      => 'Shipping Warehouse',
            'order_model'         => 'Order Model',
            'client_type'         => 'Client Type',
            'address'             => 'Address Info',
            'service'             => 'Service Info',
            'take'                => 'Take Info',
            'refund_price'        => 'Refund Amount (yuan)',
            'returned_quantity'   => 'Return Quantity',
            'buy_number_count'    => 'Total Purchases',
            'increase_price'      => 'Increase Amount (yuan)',
            'preferential_price'  => 'Preferential Amount (yuan)',
            'payment_name'        => 'Payment Type',
            'user_note'           => 'User Note',
            'extension'           => 'Extendeds Info',
            'express'             => 'Express Info',
            'express_placeholder' => 'Please enter Express Number',
            'aftersale'           => 'New Aftersale',
            'is_comments'         => 'Whether the user comment',
            'confirm_time'        => 'Confirm Time',
            'pay_time'            => 'Payment Time',
            'delivery_time'       => 'Delivery Time',
            'collect_time'        => 'Collect Time',
            'cancel_time'         => 'Cancel Time',
            'close_time'          => 'Close Time',
            'add_time'            => 'Creation Time',
            'upd_time'            => 'Update Time',
        ],
        // 动态表格统计字段
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
    'orderaftersale'        => [
        'form_audit_title'                      => 'Audit Operation',
        'form_refuse_title'                     => 'Reject Operation',
        'form_user_info_title'                  => 'User Info',
        'form_apply_info_title'                 => 'Apply Info',
        'forn_apply_info_type'                  => 'Type',
        'forn_apply_info_price'                 => 'Price',
        'forn_apply_info_number'                => 'Number',
        'forn_apply_info_reason'                => 'Reason',
        'forn_apply_info_msg'                   => 'Describe',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Base Info',
            'goods_placeholder'  => 'Please enter order ID/order number/goods name/model',
            'user'               => 'User Info',
            'user_placeholder'   => 'Please enter user name/nickname/mobile phone/email',
            'status'             => 'Status',
            'type'               => 'Apply Type',
            'reason'             => 'Reason',
            'price'              => 'Refund Amount (yuan)',
            'number'             => 'Return quantity',
            'msg'                => 'Refund Explain',
            'refundment'         => 'Refund Type',
            'voucher'            => 'Voucher',
            'express_name'       => 'Express Name',
            'express_number'     => 'Express Number',
            'refuse_reason'      => 'Refuse Reason',
            'apply_time'         => 'Apply Time',
            'confirm_time'       => 'Confirm Time',
            'delivery_time'      => 'Delivery Time',
            'audit_time'         => 'Audit Time',
            'add_time'           => 'Creation Time',
            'upd_time'           => 'Update Time',
        ],
        // 动态表格统计字段
        'form_table_stats'  => [
            'price'   => 'Total refund amount',
            'number'  => 'Total number of returns',
        ],
    ],

    // 支付方式
    'payment'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => 'was installed', 'type' => 0],
            ['name' => 'Not Installed', 'type' => 1],
        ],
        'base_nav_title'                        => 'PaymentMethod',
        'base_upload_payment_name'              => 'Import payment',
        'base_nav_store_payment_name'           => 'More PaymentMethod downloads',
        'upload_top_list_tips'                  => [
            [
                'name'  => '1. The class name must be consistent with the file name (remove. php). If Alipay.php, Alipay is used'
            ],
            [
                'name'  => '2. The method that the class must define',
                'item'  => [
                    '2.1. Config configuration method',
                    '2.2. Pay payment method',
                    '2.3. Response callback method',
                    '2.4. Notify asynchronous callback method (optional, call Response method if not defined)',
                    '2.5. Refund refund method (optional, if not defined, the original refund cannot be initiated)',
                ],
            ],
            [
                'name'  => '3. Customizable output content method',
                'item'  => [
                    '3.1. SuccessReturn payment succeeded (optional)',
                    '3.2. ErrorReturn payment failed (optional)',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS: If the above conditions are not met, the plug-in cannot be viewed. Put the plug-in into the. zip compression package to upload, and support multiple payment plugins in one compression',
        // 动态表格
        'form_table'                            => [
            'name'            => 'Name',
            'logo'            => 'LOGO',
            'version'         => 'Plugins Version',
            'apply_version'   => 'Apply Version',
            'apply_terminal'  => 'Apply Terminal',
            'author'          => 'Author',
            'desc'            => 'Describe',
            'enable'          => 'Enable or not',
            'open_user'       => 'Open to user',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => 'Express',
    ],

    // 主题管理
    'themeadmin'            => [
        'base_nav_list'                         => [
            ['name' => 'Current Theme', 'type' => 'index'],
            ['name' => 'Theme Install', 'type' => 'upload'],
        ],
        'base_upload_theme_name'                => 'Import Theme',
        'base_nav_store_theme_name'             => 'More topic downloads',
        'list_author_title'                     => 'Author',
        'list_version_title'                    => 'Applicable Version',
        'form_theme_upload_tips'                => 'Upload a zip compressed theme installation package',
    ],

    // 主题数据
    'themedata'             => [
        'base_nav_title'                        => 'Theme data',
        'upload_list_tips'                      => [
            '1. Select the downloaded theme data zip package',
            '2. Importing will automatically add a new piece of data',
        ],
        // 动态表格
        'form_table'                            => [
            'unique'    => 'Unique',
            'name'      => 'Name',
            'type'      => 'Data type',
            'theme'     => 'Theme',
            'view'      => 'Page',
            'is_enable' => 'Enable or not',
            'add_time'  => 'Add time',
            'upd_time'  => 'Update time',
        ],
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => 'Mobile User Center Navigation',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Name',
            'platform'       => 'Platform',
            'images_url'     => 'Icon',
            'event_type'     => 'Event Tyoe',
            'event_value'    => 'Event Value',
            'desc'           => 'Desc',
            'is_enable'      => 'Enable or not',
            'is_need_login'  => 'Whether to log in',
            'sort'           => 'Sort',
            'add_time'       => 'Creation Time',
            'upd_time'       => 'Update Time',
        ],
    ],

    // 手机首页导航
    'apphomenav'          => [
        'base_nav_title'                        => 'Home Navigation',
        // 动态表格
        'form_table'                            => [
            'name'           => 'Name',
            'platform'       => 'Platform',
            'images'         => 'Icon',
            'event_type'     => 'Event Tyoe',
            'event_value'    => 'Event Value',
            'is_enable'      => 'Enable or not',
            'is_need_login'  => 'Whether to log in',
            'sort'           => 'Sort',
            'add_time'       => 'Creation Time',
            'upd_time'       => 'Update Time',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => 'Payment Request Log',
        // 动态表格
        'form_table'                            => [
            'user'              => 'User Info',
            'user_placeholder'  => 'Please enter user name/nickname/mobile phone/email',
            'log_no'            => 'Payment OrderNo',
            'payment'           => 'Payment Method',
            'status'            => 'Status',
            'total_price'       => 'Business Order Amount (yuan)',
            'pay_price'         => 'Payment Amount (yuan)',
            'business_type'     => 'Business Type',
            'business_list'     => 'Business ID/OrderNo',
            'trade_no'          => 'Transaction No. of payment platform',
            'buyer_user'        => 'Payment Platform UserAccount',
            'subject'           => 'Order Name',
            'request_params'    => 'Request Params',
            'pay_time'          => 'Payment Time',
            'close_time'        => 'Close Time',
            'add_time'          => 'Creation Time',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => 'Payment Request Log',
        // 动态表格
        'form_table'                            => [
            'business_type'    => 'Business Type',
            'request_params'   => 'Request Params',
            'response_data'    => 'Response Data',
            'business_handle'  => 'Business Processing Results',
            'request_url'      => 'Request Url',
            'server_port'      => 'Port Number',
            'server_ip'        => 'Server IP',
            'client_ip'        => 'Client IP',
            'os'               => 'Operate System',
            'browser'          => 'Browser',
            'method'           => 'Request Method',
            'scheme'           => 'Http Type',
            'version'          => 'Http Version',
            'client'           => 'Client Detail',
            'add_time'         => 'Creation Time',
            'upd_time'         => 'Update Time',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => 'User Info',
            'user_placeholder'  => 'Please enter user name/nickname/mobile phone/email',
            'payment'           => 'Payment Type',
            'business_type'     => 'Business Type',
            'business_id'       => 'Business ID',
            'trade_no'          => 'Transaction No. of payment platform',
            'buyer_user'        => 'Payment Platform UserAccount',
            'refundment_text'   => 'refundment Type',
            'refund_price'      => 'Refund Price',
            'pay_price'         => 'Order payment amount',
            'msg'               => 'Describe',
            'request_params'    => 'Request Params',
            'return_params'     => 'Response parameters',
            'add_time_time'     => 'Refund Time',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => 'Return to application management >>'
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => 'Please click tick to enable',
            'save_no_data_tips'                 => 'No plug-in data to save',
        ],
        // 基础导航
        'base_nav_title'                        => 'Plugins',
        'base_upload_application_name'          => 'Import application',
        'base_nav_more_plugins_download_name'   => 'More plug-in downloads',
        // 基础页面
        'base_search_input_placeholder'         => 'Please enter a name/description',
        'base_top_tips_one'                     => 'List sorting method [custom sorting ->earliest installation]',
        'base_top_tips_two'                     => 'Click and drag to adjust the order of plugin calls and displays',
        'base_open_setup_title'                 => 'Enable Setup',
        'data_list_author_title'                => 'Author',
        'data_list_author_url_title'            => 'HomePage',
        'data_list_version_title'               => 'Version',
        'data_list_second_domain_title'         => 'Secondary domain name',
        'data_list_second_domain_tips'          => 'Please configure the valid domain name and primary domain name of the cookie in the backend [System ->System Configuration ->Security]',
        'uninstall_confirm_tips'                => 'Uninstallation may lose the basic configuration data of the plug-in. Is it unrecoverable and confirm the operation?',
        'not_install_divide_title'              => 'The following plugins are not installed',
        'delete_plugins_text'                   => '1. Delete apps only',
        'delete_plugins_text_tips'              => '(Only delete the application code and keep the application data)',
        'delete_plugins_data_text'              => '2. Delete app and delete data',
        'delete_plugins_data_text_tips'         => '(Application code and application data will be deleted)',
        'delete_plugins_ps_tips'                => 'PS: None of the following operations can be recovered. Please operate carefully!',
        'delete_plugins_button_name'            => 'Delete apps only',
        'delete_plugins_data_button_name'       => 'Delete apps and data',
        'cancel_delete_plugins_button_name'     => 'Think again',
        'more_plugins_store_to_text'            => 'Go to the app store to select more plugins to enrich the site >>',
        'no_data_store_to_text'                 => 'Go to the app store to select plug-in rich sites >>',
        'plugins_category_title'                => 'Application Category',
        'plugins_category_admin_title'          => 'Category Management',
        'plugins_menu_control_title'            => 'LeftMenu',
    ],

    // 插件分类
    'pluginscategory'       => [
        'base_nav_title'                        => 'Application Category',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => 'Back to Admin',
        'get_loading_tips'                      => 'Getting...',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => 'Role',
        'admin_not_modify_tips'                 => 'The super administrator has all permissions by default and cannot be changed.',
        // 动态表格
        'form_table'                            => [
            'name'       => 'Role Name',
            'is_enable'  => 'Enable or not',
            'add_time'   => 'Creation Time',
            'upd_time'   => 'Update Time',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => 'Power',
        'top_tips_list'                         => [
            '1. Non-professional technical personnel should not operate the data on this page. The wrong operation may lead to confusion of the authority menu.',
            '2. The permission menu is divided into two types: [Use and Operation]. The use menu is generally displayed, and the operation menu must be hidden.',
            '3. If the permission menu is disordered, you can overwrite it again[ '.MyConfig('database.connections.mysql.prefix').'power ]Data recovery of data table.',
            '4. [Super administrator, admin account] has all permissions by default and cannot be changed.',
        ],
        'content_top_tips_list'                 => [
            '1. To fill in [controller name and method name], you need to create corresponding definitions of controller and method',
            '2. Controller file location [app/admin/controller], this operation is only used by developers',
            '3. One controller name/method name and user-defined url address must be filled in',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => 'QuickNavigation',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Name',
            'platform'     => 'Platform',
            'images'       => 'Icon',
            'event_type'   => 'Event Tyoe',
            'event_value'  => 'Event Value',
            'is_enable'    => 'Enable or not',
            'sort'         => 'Sort',
            'add_time'     => 'Creation Time',
            'upd_time'     => 'Update Time',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => 'Region',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'FilterPrice',
        'top_tips_list'                         => [
            'Minimum price 0 - maximum price 100 is less than 100',
            'Minimum price 1000 - maximum price 0 is greater than 1000',
            'The minimum price of 100 - the maximum price of 500 is greater than or equal to 100 and less than 500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Slide',
        // 动态表格
        'form_table'                            => [
            'name'         => 'Name',
            'describe'     => 'Describe',
            'platform'     => 'Platform',
            'images'       => 'Images',
            'event_type'   => 'Event Tyoe',
            'event_value'  => 'Event Value',
            'is_enable'    => 'Enable or not',
            'sort'         => 'Sort',
            'start_time'   => 'Start Time',
            'end_time'     => 'Edn Time',
            'add_time'     => 'Creation Time',
            'upd_time'     => 'Update Time',
        ],
    ],

    // diy装修
    'diy'                   => [
        'nav_store_diy_name'                    => 'More DIY template downloads',
        'nav_apptabbar_name'                    => 'Bottom menu',
        'upload_list_tips'                      => [
            '1. Select the downloaded DIY design zip file',
            '2. Importing will automatically add a new piece of data',
        ],
        // 动态表格
        'form_table'                            => [
            'id'            => 'Data ID',
            'md5_key'       => 'unique identification',
            'logo'          => 'logo',
            'name'          => 'name',
            'describe'      => 'describe',
            'access_count'  => 'Number of visits',
            'is_enable'     => 'Is it enabled',
            'add_time'      => 'Creation time',
            'upd_time'      => 'Update time',
        ],
    ],

    // 附件
    'attachment'                 => [
        'base_nav_title'                        => 'annex',
        'category_admin_title'                  => 'Category management',
        // 动态表格
        'form_table'                            => [
            'category_name'  => 'Category',
            'type_name'      => 'Type',
            'info'           => 'Info',
            'original'       => 'Original file name',
            'title'          => 'New file name',
            'size'           => 'Size',
            'ext'            => 'Suffix',
            'url'            => 'Url address ',
            'hash'           => 'Hash',
            'add_time'       => 'Creation time',
        ],
    ],

    // 附件分类
    'attachmentcategory'        => [
        'base_nav_title'                        => 'Attachment Category',
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => 'User Info',
            'user_placeholder'    => 'Please enter user name/nickname/mobile phone/email',
            'type'                => 'Operate Type',
            'operation_integral'  => 'Operate Integral',
            'original_integral'   => 'Oiginal Integral',
            'new_integral'        => 'New Integral',
            'msg'                 => 'Operate Reason',
            'operation_id'        => 'Operate ID',
            'add_time_time'       => 'Operate Time',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => 'User Info',
            'user_placeholder'          => 'Please enter user name/nickname/mobile phone/email',
            'type'                      => 'Message Type',
            'business_type'             => 'Business Type',
            'title'                     => 'Title',
            'detail'                    => 'Detail',
            'is_read'                   => 'Read or not',
            'user_is_delete_time_text'  => 'Delete user',
            'add_time_time'             => 'Send Time',
        ],
    ],

    // 短信日志
    'smslog'               => [
        // 动态表格
        'form_table'                            => [
            'platform'        => 'SMS platform',
            'status'          => 'Status',
            'mobile'          => 'Phone',
            'template_value'  => 'Template content',
            'template_var'    => 'Template variable',
            'sign_name'       => 'SMS Signature',
            'request_url'     => 'Request interface',
            'request_params'  => 'Request parameters',
            'response_data'   => 'Response data',
            'reason'          => 'Reason for failure',
            'tsc'             => 'Time taken (seconds)',
            'add_time'        => 'Add time',
            'upd_time'        => 'Update time',
        ],
    ],

    // 邮件日志
    'emaillog'               => [
        // 动态表格
        'form_table'                            => [
            'email'           => 'Recipient email',
            'status'          => 'Status',
            'title'           => 'Email title',
            'template_value'  => 'Email content',
            'template_var'    => 'Email var',
            'reason'          => 'Reason for failure',
            'smtp_host'       => 'Smtp server',
            'smtp_port'       => 'Smtp port',
            'smtp_name'       => 'Email username',
            'smtp_account'    => 'Senders email',
            'smtp_send_name'  => 'Senders name',
            'tsc'             => 'Time taken (seconds)',
            'add_time'        => 'Add time',
            'upd_time'        => 'Update time',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: Non-developers should not execute any SQL statements at will, which may cause the entire system database to be deleted.',
    ],

    // 应用商店
    'store'                 => [
        'to_store_name'                         => 'Go to the app store to select plugins',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Background management system',
        'remove_cache_title'                    => 'Clear Cache',
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
            '1. All (displayed under goods basic information and detail parameters)',
            '2. Details (only displayed under the goods details parameter)',
            '3. Basic (only displayed under goods basic information)',
            '4. The shortcut operation will clear the original data and reload the page to restore the original data (only effective after saving the goods)',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name' => 'System',
            'item' => [
                'config_index'                 => 'System config',
                'config_store'                 => 'Store info',
                'config_save'                  => 'Configuration save',
                'index_storeaccountsbind'      => 'App store account binding',
                'index_inspectupgrade'         => 'System update check',
                'index_inspectupgradeconfirm'  => 'System update confirmation',
                'index_stats'                  => 'Home page statistics',
                'index_income'                 => 'Home page Statistics (income statistics]',
                'shortcutmenu_index'           => 'Common functions',
                'shortcutmenu_save'            => 'Adding/Editing Common Functions',
                'shortcutmenu_sort'            => 'Common Function Sorting',
                'shortcutmenu_delete'          => 'Common function deletion',
            ]
        ],
        'site_index' => [
            'name' => 'Site',
            'item' => [
                'site_index'                  => 'Site setup',
                'site_save'                   => 'Site setup editing',
                'site_goodssearch'            => 'Site setup and goods search',
                'layout_layoutindexhomesave'  => 'Homepage layout admin',
                'sms_index'                   => 'SMS setup',
                'sms_save'                    => 'SMS setup editing',
                'email_index'                 => 'Email setup',
                'email_save'                  => 'Email setup / editing',
                'email_emailtest'             => 'Mail sending test',
                'seo_index'                   => 'SEO setup',
                'seo_save'                    => 'SEO setup editing',
                'agreement_index'             => 'Agreement admin',
                'agreement_save'              => 'Protocol setup editing',
            ]
        ],
        'power_index' => [
            'name' => 'Power',
            'item' => [
                'admin_index'        => 'Admin list',
                'admin_saveinfo'     => 'Admin add/edit page',
                'admin_save'         => 'Admin add/edit',
                'admin_delete'       => 'Admin delete',
                'admin_detail'       => 'Admin details',
                'role_index'         => 'Role admin',
                'role_saveinfo'      => 'Role group add/edit page',
                'role_save'          => 'Role group add/edit',
                'role_delete'        => 'Role delete',
                'role_statusupdate'  => 'Role status update',
                'role_detail'        => 'Role details',
                'power_index'        => 'Power divide',
                'power_save'         => 'Power add/edit',
                'power_statusupdate' => 'Power status update',
                'power_delete'       => 'Power delete',
            ]
        ],
        'user_index' => [
            'name' => 'User',
            'item' => [
                'user_index'            => 'User list',
                'user_saveinfo'         => 'User edit / add page',
                'user_save'             => 'User add/edit',
                'user_delete'           => 'User delete',
                'user_detail'           => 'User details',
                'useraddress_index'     => 'User address',
                'useraddress_saveinfo'  => 'User address edit page',
                'useraddress_save'      => 'User address editing',
                'useraddress_delete'    => 'User address delete',
                'useraddress_detail'    => 'User address details',
            ]
        ],
        'goods_index' => [
            'name' => 'Goods',
            'item' => [
                'goods_index'                       => 'Goods admin',
                'goods_saveinfo'                    => 'Goods add/edit page',
                'goods_save'                        => 'Item add/edit',
                'goods_delete'                      => 'Goods delete',
                'goods_statusupdate'                => 'Goods status update',
                'goods_basetemplate'                => 'Get goods base template',
                'goods_detail'                      => 'Goods details',
                'goodscategory_index'               => 'Goods category',
                'goodscategory_save'                => 'Goods category add/edit',
                'goodscategory_statusupdate'        => 'Goods category status update',
                'goodscategory_delete'              => 'Goods category delete',
                'goodsparamstemplate_index'         => 'Goods params',
                'goodsparamstemplate_delete'        => 'Delete goods params',
                'goodsparamstemplate_statusupdate'  => 'Goods params status update',
                'goodsparamstemplate_saveinfo'      => 'Goods params add/edit page',
                'goodsparamstemplate_save'          => 'Goods params add/edit ',
                'goodsparamstemplate_detail'        => 'Goods params details',
                'goodsspectemplate_index'           => 'Goods spec',
                'goodsspectemplate_delete'          => 'Item spec delete',
                'goodsspectemplate_statusupdate'    => 'Goods spec status update',
                'goodsspectemplate_saveinfo'        => 'Goods spec add/edit page',
                'goodsspectemplate_save'            => 'Goods spec add/edit',
                'goodsspectemplate_detail'          => 'Goods spec details',
                'goodscomments_detail'              => 'Goods comment details',
                'goodscomments_index'               => 'Goods comment',
                'goodscomments_reply'               => 'Goods comment reply',
                'goodscomments_delete'              => 'Goods comment delete',
                'goodscomments_statusupdate'        => 'Goods comment status update',
                'goodscomments_saveinfo'            => 'Goods comment add/edit page',
                'goodscomments_save'                => 'Goods comment add/edit',
                'goodsbrowse_index'                 => 'Goods browsing',
                'goodsbrowse_delete'                => 'Goods browse delete',
                'goodsbrowse_detail'                => 'Goods browsing details',
                'goodsfavor_index'                  => 'Goods favor',
                'goodsfavor_delete'                 => 'Delete item favor',
                'goodsfavor_detail'                 => 'Favor details',
                'goodscart_index'                   => 'Goods cart',
                'goodscart_delete'                  => 'Goods Cart Delete',
                'goodscart_detail'                  => 'Goods Cart Details',
            ]
        ],
        'order_index' => [
            'name' => 'Order',
            'item' => [
                'order_index'             => 'Order admin',
                'order_delete'            => 'Order delete',
                'order_cancel'            => 'Order cancellation',
                'order_delivery'          => 'Order delivery',
                'order_collect'           => 'Order receipt',
                'order_pay'               => 'Order payment',
                'order_confirm'           => 'acknowledgement of order',
                'order_detail'            => 'Order details',
                'order_deliveryinfo'      => 'Order shipping page',
                'order_serviceinfo'       => 'Order Service Page',
                'orderaftersale_index'    => 'Order aftersales',
                'orderaftersale_delete'   => 'After sales order delete',
                'orderaftersale_cancel'   => 'Order after-sales cancellation',
                'orderaftersale_audit'    => 'Order after sales review',
                'orderaftersale_confirm'  => 'Order after sales confirmation',
                'orderaftersale_refuse'   => 'After sales rejection of order',
                'orderaftersale_detail'   => 'Order after sales details',
            ]
        ],
        'navigation_index' => [
            'name' => 'Web',
            'item' => [
                'navigation_index'                 => 'Navigation admin',
                'navigation_save'                  => 'Navigation add/edit',
                'navigation_delete'                => 'Navigation delete',
                'navigation_statusupdate'          => 'Navigation status update',
                'customview_index'                 => 'Custom page',
                'customview_saveinfo'              => 'Custom page add/edit page',
                'customview_save'                  => 'Custom page add/edit',
                'customview_delete'                => 'Custom page delete',
                'customview_statusupdate'          => 'Custom page status update',
                'customview_detail'                => 'Custom page details',
                'link_index'                       => 'Links',
                'link_saveinfo'                    => 'Link add/edit page',
                'link_save'                        => 'Add / Edit Links',
                'link_delete'                      => 'Link delete',
                'link_statusupdate'                => 'Link status update',
                'link_detail'                      => 'Link details',
                'themeadmin_index'                 => 'Theme admin',
                'themeadmin_save'                  => 'Theme admin add/edit',
                'themeadmin_upload'                => 'Theme upload and installation',
                'themeadmin_delete'                => 'Theme delete',
                'themeadmin_download'              => 'Theme download',
                'themeadmin_market'                => 'Theme template market',
                'themeadmin_storeuploadinfo'       => 'Theme upload page',
                'themeadmin_storeupload'           => 'Theme upload',
                'themedata_index'                  => 'Theme data',
                'themedata_saveinfo'               => 'Theme data add/edit page',
                'themedata_save'                   => 'Theme data add/edit',
                'themedata_upload'                 => 'Theme data upload',
                'themedata_delete'                 => 'Theme data delete',
                'themedata_download'               => 'Theme data download',
                'slide_index'                      => 'HomePage Slide',
                'slide_saveinfo'                   => 'Carousel add/edit page',
                'slide_save'                       => 'Carousel add/edit',
                'slide_statusupdate'               => 'Rotation status update',
                'slide_delete'                     => 'Rotation delete',
                'slide_detail'                     => 'Rotation details',
                'screeningprice_index'             => 'Screening price',
                'screeningprice_save'              => 'Filter price add/edit',
                'screeningprice_delete'            => 'Filter price delete',
                'region_index'                     => 'Regional admin',
                'region_save'                      => 'Region add/edit',
                'region_statusupdate'              => 'Regional status update',
                'region_delete'                    => 'Region delete',
                'region_codedata'                  => 'Get area number data',
                'express_index'                    => 'Express Management',
                'express_save'                     => 'Express add/edit',
                'express_delete'                   => 'Express delete',
                'payment_index'                    => 'Payment method',
                'payment_saveinfo'                 => 'Payment method installation / editing page',
                'payment_save'                     => 'Payment method installation / editing',
                'payment_delete'                   => 'Payment method delete',
                'payment_install'                  => 'Payment method installation',
                'payment_statusupdate'             => 'Payment method status update',
                'payment_uninstall'                => 'Payment method unloading',
                'payment_upload'                   => 'Payment method upload',
                'payment_market'                   => 'Payment Plugin Market',
                'quicknav_index'                   => 'Quick navigation',
                'quicknav_saveinfo'                => 'Quick navigation add/edit page',
                'quicknav_save'                    => 'Quick navigation add/edit',
                'quicknav_statusupdate'            => 'Quick navigation status update',
                'quicknav_delete'                  => 'Quick navigation delete',
                'quicknav_detail'                  => 'Quick navigation details',
                'design_index'                     => 'Page design',
                'design_saveinfo'                  => 'Page design add/edit page',
                'design_save'                      => 'Page design add/edit',
                'design_statusupdate'              => 'Page design status update',
                'design_upload'                    => 'Page design import',
                'design_download'                  => 'Page Design Download',
                'design_sync'                      => 'Page design synchronization home page',
                'design_delete'                    => 'Page design delete',
                'design_market'                    => 'Page design template market',
                'attachment_index'                 => 'Annex manage',
                'attachment_detail'                => 'Annex manage Details',
                'attachment_saveinfo'              => 'Annex manage Add/Edit Page',
                'attachment_save'                  => 'Annex manage Add/Edit',
                'attachment_delete'                => 'Annex manage deletion',
                'attachmentcategory_index'         => 'Annex category',
                'attachmentcategory_save'          => 'Annex category add/edit',
                'attachmentcategory_statusupdate'  => 'Annex status update',
                'attachmentcategory_delete'        => 'Annex category deletion',
            ]
        ],
        'brand_index' => [
            'name' => 'Brand',
            'item' => [
                'brand_index'           => 'Brand admin',
                'brand_saveinfo'        => 'Brand add/edit page',
                'brand_save'            => 'Brand add/edit',
                'brand_statusupdate'    => 'Brand status update',
                'brand_delete'          => 'Brand delete',
                'brand_detail'          => 'Brand details',
                'brandcategory_index'   => 'Brand category',
                'brandcategory_save'    => 'Brand category add/edit',
                'brandcategory_delete'  => 'Brand category delete',
            ]
        ],
        'warehouse_index' => [
            'name' => 'Stock',
            'item' => [
                'warehouse_index'               => 'Warehouse admin',
                'warehouse_saveinfo'            => 'Warehouse add/edit page',
                'warehouse_save'                => 'Warehouse add/edit',
                'warehouse_delete'              => 'Warehouse delete',
                'warehouse_statusupdate'        => 'Warehouse status update',
                'warehouse_detail'              => 'Warehouse details',
                'warehousegoods_index'          => 'Warehouse goods admin',
                'warehousegoods_detail'         => 'Warehouse item details',
                'warehousegoods_delete'         => 'Warehouse Item Deletion',
                'warehousegoods_statusupdate'   => 'Warehouse goods status update',
                'warehousegoods_goodssearch'    => 'Warehouse item search',
                'warehousegoods_goodsadd'       => 'Warehouse item search add',
                'warehousegoods_goodsdel'       => 'Warehouse item search delete',
                'warehousegoods_inventoryinfo'  => 'Warehouse goods inventory editing page',
                'warehousegoods_inventorysave'  => 'Edit warehouse inventory',
            ]
        ],
        'app_index' => [
            'name' => 'Mobile',
            'item' => [
                'appconfig_index'                  => 'Basic config',
                'appconfig_save'                   => 'Basic config saving',
                'appmini_index'                    => 'Applet list',
                'appmini_created'                  => 'Applet package generation',
                'appmini_delete'                   => 'Applet package delete',
                'appmini_themeupload'              => 'Applet theme upload',
                'appmini_themesave'                => 'Applet theme switching',
                'appmini_themedelete'              => 'Applet theme switching',
                'appmini_themedownload'            => 'Applet theme download',
                'appmini_config'                   => 'Applet config',
                'appmini_save'                     => 'Applet config save',
                'diy_index'                        => 'DIY decoration',
                'diy_saveinfo'                     => 'DIY Decoration Add/Edit Page',
                'diy_save'                         => 'DIY decoration addition/editing',
                'diy_statusupdate'                 => 'DIY decoration status update',
                'diy_delete'                       => 'DIY Decoration Delete',
                'diy_download'                     => 'DIY decoration export',
                'diy_upload'                       => 'DIY decoration import',
                'diy_detail'                       => 'DIY Decoration Details',
                'diy_preview'                      => 'DIY Decoration Preview',
                'diy_market'                       => 'DIY decoration template market',
                'diy_apptabbar'                    => 'DIY Decoration Bottom Menu',
                'diy_storeuploadinfo'              => 'DIY decoration upload page',
                'diy_storeupload'                  => 'DIY decoration upload',
                'diyapi_init'                      => 'DIY Decoration - Public Initialization',
                'diyapi_attachmentcategory'        => 'DIY Decoration - Attachment Classification',
                'diyapi_attachmentlist'            => 'DIY Decoration - Attachment List',
                'diyapi_attachmentsave'            => 'DIY Decoration - Attachment Storage',
                'diyapi_attachmentdelete'          => 'DIY Decoration - Attachment Deletion',
                'diyapi_attachmentupload'          => 'DIY Decoration - Attachment Upload',
                'diyapi_attachmentcatch'           => 'DIY Decoration - Remote Download of Accessories',
                'diyapi_attachmentscanuploaddata'  => 'DIY Decoration - Scan the QR code for attachments and upload data',
                'diyapi_attachmentmovecategory'    => 'DIY Decoration - Attachment Mobile Classification',
                'diyapi_attachmentcategorysave'    => 'DIY Decoration - Attachment Classification and Storage',
                'diyapi_attachmentcategorydelete'  => 'DIY Decoration - Attachment Category Delete',
                'diyapi_goodslist'                 => 'DIY Decoration - Product List',
                'diyapi_customviewlist'            => 'DIY Decoration - Custom Page List',
                'diyapi_designlist'                => 'DIY Decoration - Page Design List',
                'diyapi_articlelist'               => 'DIY Decoration - Article List',
                'diyapi_brandlist'                 => 'DIY Decoration - Brand List',
                'diyapi_diylist'                   => 'DIY Decoration - DIY Decoration List',
                'diyapi_diydetail'                 => 'DIY Decoration - DIY Decoration Details',
                'diyapi_diysave'                   => 'DIY decoration - DIY decoration preservation',
                'diyapi_diyupload'                 => 'DIY Decoration - DIY Decoration Import',
                'diyapi_diydownload'               => 'DIY Decoration - DIY Decoration Export',
                'diyapi_diyinstall'                => 'DIY decoration - DIY decoration template installation',
                'diyapi_diymarket'                 => 'DIY Decoration - DIY Decoration Template Market',
                'diyapi_goodsappointdata'          => 'DIY Decoration - Product Specific Data',
                'diyapi_goodsautodata'             => 'DIY Decoration - Automated Product Data',
                'diyapi_articleappointdata'        => 'DIY Decoration - Article Specific Data',
                'diyapi_articleautodata'           => 'DIY Decoration - Automatic Article Data',
                'diyapi_brandappointdata'          => 'DIY Decoration - Brand Designated Data',
                'diyapi_brandautodata'             => 'DIY Decoration - Brand Automatic Data',
                'diyapi_userheaddata'              => 'DIY Decoration - User Head Data',
                'diyapi_custominit'                => 'DIY Decoration - Custom Initialization',
                'apphomenav_index'                 => 'Home page navigation',
                'apphomenav_saveinfo'              => 'Home navigation add/edit page',
                'apphomenav_save'                  => 'Home page navigation add/edit',
                'apphomenav_statusupdate'          => 'Homepage navigation status update',
                'apphomenav_delete'                => 'Home page navigation delete',
                'apphomenav_detail'                => 'Home page navigation details',
                'appcenternav_index'               => 'User center navigation',
                'appcenternav_saveinfo'            => 'User center navigation add/edit page',
                'appcenternav_save'                => 'User center navigation add/edit',
                'appcenternav_statusupdate'        => 'User center navigation status update',
                'appcenternav_delete'              => 'User center navigation delete',
                'appcenternav_detail'              => 'User center navigation details',
            ]
        ],
        'article_index' => [
            'name' => 'Article',
            'item' => [
                'article_index'           => 'Article admin',
                'article_saveinfo'        => 'Article add/edit page',
                'article_save'            => 'Article add/edit',
                'article_delete'          => 'Article delete',
                'article_statusupdate'    => 'Article status update',
                'article_detail'          => 'Article details',
                'articlecategory_index'   => 'Article classification',
                'articlecategory_save'    => 'Article classification editing / adding',
                'articlecategory_delete'  => 'Article classification delete',
            ]
        ],
        'data_index' => [
            'name' => 'Data',
            'item' => [
                'message_index'         => 'Message log',
                'message_delete'        => 'Message delete',
                'message_detail'        => 'Message details',
                'paylog_index'          => 'Payment log',
                'paylog_detail'         => 'Payment log details',
                'paylog_close'          => 'Payment log closed',
                'payrequestlog_index'   => 'Payment request log list',
                'payrequestlog_detail'  => 'Payment request log details',
                'refundlog_index'       => 'Refund log',
                'refundlog_detail'      => 'Refund log details',
                'integrallog_index'     => 'Integral log',
                'integrallog_detail'    => 'Points log details',
                'smslog_index'          => 'SMS log',
                'smslog_detail'         => 'SMS log details',
                'smslog_delete'         => 'Delete SMS logs',
                'smslog_alldelete'      => 'Delete all SMS logs',
                'emaillog_index'        => 'Email log',
                'emaillog_detail'       => 'Email log details',
                'emaillog_delete'       => 'Email log deletion',
                'emaillog_alldelete'    => 'Delete all email logs',
                'errorlog_index'        => 'Error log',
                'errorlog_detail'       => 'Error log details',
                'errorlog_delete'       => 'Error log deletion',
                'errorlog_alldelete'    => 'Delete all error logs',
            ]
        ],
        'store_index' => [
            'name' => 'Store',
            'item' => [
                'pluginsadmin_index'            => 'Application admin',
                'plugins_index'                 => 'Application call admin',
                'pluginsadmin_saveinfo'         => 'Application add/edit page',
                'pluginsadmin_save'             => 'Apply add/edit',
                'pluginsadmin_statusupdate'     => 'Application status update',
                'pluginsadmin_delete'           => 'Apply delete',
                'pluginsadmin_upload'           => 'Application upload',
                'pluginsadmin_download'         => 'Application packaging',
                'pluginsadmin_install'          => 'Application installation',
                'pluginsadmin_uninstall'        => 'Apps Uninstall',
                'pluginsadmin_sortsave'         => 'Apply sort save',
                'pluginsadmin_market'           => 'Application Plugin Market',
                'store_index'                   => 'App store',
                'packageinstall_index'          => 'Package installation page',
                'packageinstall_install'        => 'Package installation',
                'packageupgrade_upgrade'        => 'Package update',
                'pluginscategory_index'         => 'Application category',
                'pluginscategory_save'          => 'Add/edit application category',
                'pluginscategory_statusupdate'  => 'Application category status update',
                'pluginscategory_delete'        => 'Application category deletion',
                'store_market'                  => 'Application Market',
            ]
        ],
        'tool_index' => [
            'name' => 'Tools',
            'item' => [
                'cache_index'           => 'Cache admin',
                'cache_statusupdate'    => 'Site cache update',
                'cache_templateupdate'  => 'Template cache update',
                'cache_moduleupdate'    => 'Module cache update',
                'cache_logdelete'       => 'Log delete',
                'sqlconsole_index'      => 'SQL console',
                'sqlconsole_implement'  => 'SQL execution',
            ]
        ],
    ],
];
?>