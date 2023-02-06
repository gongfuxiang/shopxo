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
        // 页面基础
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
        // 表单
        'form_item_system_type'                 => 'System Type',
        'form_item_system_type_tips'            => 'default',
        'form_item_system_type_message'         => 'System type 2~60 characters',
        'form_item_username'                    => 'Username',
        'form_item_username_message'            => 'User name 2~30 characters',
        'form_item_nickname'                    => 'Nickname',
        'form_item_nickname_message'            => 'Nickname can be up to 30 characters',
        'form_item_mobile'                      => 'Phone Number',
        'form_item_mobile_message'              => 'Mobile number format error',
        'form_item_email'                       => 'E-mail',
        'form_item_email_message'               => 'Email format error',
        'form_item_alipay_openid'               => 'Alipay openid',
        'form_item_alipay_openid_message'       => 'Please fill in Alipay openid',
        'form_item_baidu_openid'                => 'Baidu openid',
        'form_item_baidu_openid_message'        => 'Please fill in Baidu openid',
        'form_item_toutiao_openid'              => 'Toutiao openid',
        'form_item_toutiao_openid_message'      => 'Please fill in the header openid',
        'form_item_toutiao_unionid'             => 'Toutiao unionid',
        'form_item_toutiao_unionid_message'     => 'Please fill in the headline unionid',
        'form_item_qq_openid'                   => 'QQopenid',
        'form_item_qq_openid_message'           => 'Please fill in QQ openid',
        'form_item_qq_unionid'                  => 'QQunionid',
        'form_item_qq_unionid_message'          => 'Please fill in QQuinonid',
        'form_item_weixin_openid'               => 'WeChat openid',
        'form_item_weixin_openid_message'       => 'Please fill in WeChat openid',
        'form_item_weixin_unionid'              => 'WeChat unionid',
        'form_item_weixin_unionid_message'      => 'Please fill in WeChat unionid',
        'form_item_web_weixin_openid'           => 'WeChat webopenid',
        'form_item_web_weixin_openid_message'   => 'Please fill in WeChat webopenid',
        'form_item_kuaishou_openid'             => 'Kwai openid',
        'form_item_kuaishou_openid_message'     => 'Please fill in the Kwai openid',
        'form_item_province'                    => 'Province',
        'form_item_province_message'            => 'Maximum 60 characters in the province',
        'form_item_city'                        => 'City',
        'form_item_city_message'                => 'Maximum 60 characters in the city',
        'form_item_county'                      => 'District/County',
        'form_item_county_message'              => '60 characters at most in the district/county',
        'form_item_address'                     => 'Detail Address',
        'form_item_address_message'             => 'Detail Address format is up to 80 characters',
        'form_item_integral'                    => 'Valid Integral',
        'form_item_integral_message'            => 'Please enter valid points',
        'form_item_locking_integral'            => 'Lock Integral',
        'form_item_locking_integral_message'    => 'Please enter locked points',
        'form_item_birthday'                    => 'Birthday',
        'form_item_birthday_message'            => 'Incorrect birthday format',
        'form_item_referrer'                    => 'Invite User ID',
        'form_item_referrer_message'            => 'Please enter the invitation user ID',
        'form_item_pwd'                         => 'Login Password',
        'form_item_pwd_tips'                    => 'Enter to change the password',
        'form_item_pwd_message'                 => 'Login password format is between 6 and 18 characters',
        // 动态表格
        'form_table'                            => [
            'id'                    => 'User ID',
            'number_code'           => 'Number Code',
            'system_type'           => 'System Type',
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
        // 表单
        'form_item_user_id'                     => 'User ID',
        'form_item_user_id_message'             => 'Please fill in user id',
        'form_item_name'                        => 'Full Name',
        'form_item_name_message'                => 'Name format is between 2 and 16 characters',
        'form_item_alias'                       => 'Alias',
        'form_item_alias_message'               => 'Alias format can be up to 16 characters',
        'form_item_tel'                         => 'Telephone',
        'form_item_tel_message'                 => 'Incorrect phone format',
        'form_item_address'                     => 'Detailed Address',
        'form_item_address_message'             => 'Detailed address format is between 1 and 80 characters',
        'form_item_idcard_name'                 => 'ID Card Name',
        'form_item_idcard_name_tips'            => 'Optional, please be consistent with the uploaded ID name',
        'form_item_idcard_name_message'         => 'The name format of ID card can be up to 16 characters',
        'form_item_idcard_number'               => 'ID No',
        'form_item_idcard_number_tips'          => 'Optional, please be consistent with the uploaded ID number',
        'form_item_idcard_number_message'       => 'ID card number format can be up to 18 characters',
        'form_item_idcard_images'               => 'ID Card Photo',
        'form_item_idcard_images_tips'          => 'For selective transmission, please use the original ID card to shoot, and the picture should be clear',
        'form_item_idcard_front_button_name'    => 'Upload IDCard Front Pictures',
        'form_item_idcard_back_button_name'     => 'Upload IDCard Back Pictures',
        'form_item_idcard_no_user_tips'         => 'Associate users before uploading ID card images',
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
        // 页面基础
        'base_item_site_status_title'           => 'Site Status',
        'base_item_site_domain_title'           => 'Site domain name address',
        'base_item_site_filing_title'           => 'Filing Info',
        'base_item_site_other_title'            => 'Other',
        'base_item_session_cache_title'         => 'Session Cache Config',
        'base_item_data_cache_title'            => 'Data Cache Config',
        'base_item_redis_cache_title'           => 'Redis Cache Config',
        'base_item_crontab_config_title'        => 'Timing Script Config',
        'base_item_quick_nav_title'             => 'Quick Navigation',
        'base_item_user_address_title'          => 'User Address',
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
            '2. Mobile terminal default display level 0 (commodity list mode), minimum level 0, and maximum level 3 (1~3 are classified display mode)',
            '3. The style of the front category page will be different if the level is different',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. Configure the maximum number of products displayed on each floor',
            '2. It is not recommended to modify the quantity too large, which will lead to too large blank area on the left side of the PC',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            'It can be summarized as follows: popularity ->sales volume ->descending order (desc) of the latest',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. Click the title of the item to drag and sort it and display it in order',
            '2. It is not recommended to add many products, which will lead to too large blank area on the left side of the PC',
        ],
        'extends_crontab_tips'                  => 'It is recommended that you add the script address to the timing request of the Linux scheduled task (the result is SUCS: 0, FAIL: 0, followed by the colon is the number of data processed, SUCS succeeded, FALI failed)',
        'left_images_random_tips'               => 'The left picture can upload up to 3 pictures, and one of them can be displayed randomly each time',
        'background_color_tips'                 => 'Customizable background image, default background gray',
        'site_setup_layout_tips'                => 'The drag mode needs to enter the homepage design page by yourself. Please save the selected configuration before',
        'site_setup_layout_button_name'         => 'Go to design page >>',
        'site_setup_goods_category_tips'        => 'If you need more floor displays, please go to/Product Management ->Product Classification, Primary Classification Settings Home Page Recommendation',
        'site_setup_goods_category_no_data_tips'=> 'There is no data for the time being. Please go to/Product Management ->Product Classification, Primary Classification Settings Home Page for recommendation',
        'site_setup_order_default_payment_tips' => 'You can set the default payment method corresponding to different platforms. Please install the payment plug-in in [Website Management ->Payment Method] to enable and open it to users',
        'site_setup_choice_payment_message'     => 'Please select {:name} default payment method',
        'sitetype_top_tips_list'                => [
            '1. Express delivery, conventional e-commerce process, user selects the receiving address to place an order for payment ->merchant shipment ->confirmation of receipt ->order completion',
            '2. Display type, only display products, can initiate consultation (cant place an order)',
            '3. Select the self-pickup address when placing the order, and the user places the order for payment ->confirm the delivery ->order completion',
            '4. Virtual sales, conventional e-commerce process, user order payment ->automatic delivery ->confirmation of delivery ->order completion',
        ],
        // 添加自提地址表单
        'form_take_address_logo'                => 'LOGO',
        'form_take_address_logo_tips'           => '300 * 300px proposal',
        'form_take_address_alias'               => 'Alias',
        'form_take_address_alias_message'       => 'Alias format can be up to 16 characters',
        'form_take_address_name'                => 'Contacts Name',
        'form_take_address_name_message'        => 'Contact name format is between 2 and 16 characters',
        'form_take_address_tel'                 => 'Contact Tel',
        'form_take_address_tel_message'         => 'Please fill in the contact number',
        'form_take_address_address'             => 'Detail Address',
        'form_take_address_address_message'     => 'Detailed address format is between 1 and 80 characters',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => 'Background Login',
        'admin_login_info_bg_images_list_tips'  => [
            '1. The background image is located in the [public/static/admin/default/images/login] directory',
            '2. Naming rules for background pictures (1~50), such as 1.jpg',
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
    'brandcategory'       => [
        'base_nav_title'                        => 'BrandCategory',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => 'Article',
        'detail_content_title'                  => 'Detail Content',
        'detail_images_title'                   => 'Detail Picture',
        // 动态表格
        'form_table'                            => [
            'info'                   => 'Title',
            'jump_url'               => 'Jump Url',
            'article_category_name'  => 'Category',
            'is_enable'              => 'Enable or not',
            'is_home_recommended'    => 'HomePage recommend',
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
        // 表单
        'form_item_title'                       => 'Title',
        'form_item_title_message'               => 'The title is 2~60 characters long',
        'form_item_content_title'               => 'Content',
        'form_item_content_placeholder'         => 'The content format is between 10 and 105000 characters. For more editing functions, please use the computer to access',
        'form_item_content_message'             => 'Content format is between 10 and 105000 characters',
        // 动态表格
        'form_table'                            => [
            'info'            => 'Title',
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
            'info'              => 'Base Info',
            'info_placeholder'  => 'Please enter a name',
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

    // 问答
    'answer'                => [
        'base_nav_title'                        => 'Q&A',
        'user_info_title'                       => 'User Info',
        // 动态表格
        'form_table'                            => [
            'user'              => 'User Info',
            'user_placeholder'  => 'Please enter user name/nickname/mobile phone/email',
            'name'              => 'Contacts',
            'tel'               => 'Contact Number',
            'content'           => 'Content',
            'reply'             => 'Reply Content',
            'is_show'           => 'Show or not',
            'is_reply'          => 'Reply or not',
            'reply_time_time'   => 'Reply Time',
            'access_count'      => 'Access Count',
            'add_time_time'     => 'Creation Time',
            'upd_time_time'     => 'Update Time',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => 'Warehouse',
        'top_tips_list'                         => [
            '1. The higher the weight value is, the higher the weight is. The inventory is deducted according to the weight.)',
            '2. The warehouse can only be soft-deleted, will not be available after deletion, and only the data in the database can be retained), and the associated commodity data can be deleted by itself',
            '3. The warehouse will be deactivated and deleted, and the associated commodity inventory will be released immediately',
        ],
        // 表单
        'form_item_name'                        => 'Full Name',
        'form_item_name_message'                => 'Name format is between 2 and 30 characters',
        'form_item_alias'                       => 'Alias',
        'form_item_alias_message'               => 'Alias format can be up to 16 characters',
        'form_item_level'                       => 'Weight',
        'form_item_level_tips'                  => 'The higher the weight value, the higher the weight',
        'form_item_level_message'               => 'Please enter a valid weight value',
        'form_item_contacts_name'               => 'Contacts Name',
        'form_item_contacts_name_message'       => 'Contact format is between 2 and 16 characters',
        'form_item_contacts_tel'                => 'Contact Tel',
        'form_item_contacts_tel_message'        => 'Please fill in the contact number',
        'form_item_address'                     => 'Detail Address',
        'form_item_address_message'             => 'Address format is between 1 and 80 characters',
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
        'no_spec_data_tips'                     => 'No specification data',
        'batch_setup_inventory_placeholder'     => 'Batch set value',
        'base_spec_inventory_title'             => 'Specification Inventory',
        // 表单
        'add_goods_title'                       => 'Goods Add',
        'form_item_warehouseg_placeholder'      => 'Warehouse...',
        'form_item_warehouseg_message'          => 'Please select a warehouse',
        'form_item_inventory_message'           => 'Inventory quantity 0~100000000',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Base Info',
            'goods_placeholder'  => 'Please enter the product name/model',
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
        'login_type_username_title'             => 'Account Password',
        'login_type_mobile_title'               => 'Mobile Verify Code',
        'login_type_email_title'                => 'Email Verify Code',
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
        'nav_theme_download_tips'               => 'The theme of mobile phone is developed by uniapp (supporting multi-terminal applet+H5), and APP is also in emergency adaptation。',
        'form_alipay_extend_title'              => 'Customer service configuration',
        'form_alipay_extend_tips'               => 'PS: If [APP/applet] is enabled (online customer service is enabled), the following configuration must be filled in [Enterprise Code] and [Chat Window Code]',
        'list_no_data_tips'                     => 'No related theme packs',
        'list_author_title'                     => 'Author：',
        'list_version_title'                    => 'Applicable version：',
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
        // 动态表格
        'form_table'                            => [
            'id'                      => 'Goods ID',
            'info'                    => 'Goods Info',
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
            'sales_count'             => 'Sales Count',
            'access_count'            => 'Access Count',
            'add_time'                => 'Creation Time',
            'upd_time'                => 'Update Time',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => 'GoodsCategory',
        // 表单
        'form_item_icon'                        => 'Icon',
        'form_item_icon_tips'                   => '100 * 100px recommend',
        'form_item_big_images'                  => 'Large Picture',
        'form_item_big_images_tips'             => '360 * 360px recommend',
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~16 characters',
        'form_item_vice_name'                   => 'Sub Name',
        'form_item_vice_name_message'           => 'The secondary name can be up to 60 characters',
        'form_item_describe'                    => 'Describe',
        'form_item_describe_message'            => 'Description can be up to 200 characters',
        'form_item_is_home_recommended'         => 'HomePage Recommend',
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
        // 动态表格
        'form_table'                            => [
            'user'                       => 'User Info',
            'user_placeholder'           => 'Please enter user name/nickname/mobile phone/email',
            'goods'                      => 'Base Info',
            'goods_placeholder'          => 'Please enter the product name/model',
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
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 1~30 characters',
        'form_item_category_id'                 => 'Goods Category',
        'form_item_category_id_tips'            => 'Include children',
        'form_item_category_id_message'         => 'Please select product classification',
        'form_item_content'                     => 'Spec Value',
        'form_item_content_placeholder'         => 'Specification value (multiple values can be achieved by entering Enter)',
        'form_item_content_message'             => 'Specification value format: 1~1000 characters',
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
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~16 characters',
        'form_item_url'                         => 'Link Address',
        'form_item_url_placeholder'             => 'Link address, starting with http://or https://',
        'form_item_url_message'                 => 'Incorrect format of link address',
        'form_item_desc'                        => 'Describe',
        'form_item_desc_message'                => 'Description can be up to 60 characters',
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
        'form_item_value_goods_category_message'=> 'Wrong selection of commodity classification',
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
            'express_choice_tips'               => 'Please select express delivery method',
            'payment_choice_tips'               => 'Please select payment method',
        ],
        // 页面基础
        'form_delivery_title'                   => 'Delivery Operation',
        'form_payment_title'                    => 'Payment Operation',
        'form_item_take'                        => 'Take Code',
        'form_item_take_message'                => 'Please fill in the 4-digit pickup code',
        'form_item_express_number'              => 'Express Number',
        'form_item_express_number_message'      => 'Please fill in the express bill number',
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
            'goods_placeholder'   => 'Please enter order ID/order number/product name/model',
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
            'take'                => 'Take Info',
            'refund_price'        => 'Refund Amount (yuan)',
            'returned_quantity'   => 'Return Quantity',
            'buy_number_count'    => 'Total Purchases',
            'increase_price'      => 'Increase Amount (yuan)',
            'preferential_price'  => 'Preferential Amount (yuan)',
            'payment_name'        => 'Payment Type',
            'user_note'           => 'User Note',
            'extension'           => 'Extendeds Info',
            'express_name'        => 'Express Name',
            'express_number'      => 'Express Number',
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
        'form_item_refundment'                  => 'Refund Method',
        'form_item_refundment_message'          => 'Please select a refund method',
        'form_item_refuse_reason'               => 'Reason for rejection',
        'form_item_refuse_reason_message'       => 'Rejection reason format: 2~230 characters',
        // 动态表格
        'form_table'                            => [
            'goods'              => 'Base Info',
            'goods_placeholder'  => 'Please enter the order number/product name/model',
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
    ],

    // 支付方式
    'payment'               => [
        'base_nav_title'                        => 'PaymentMethod',
        'nav_store_payment_name'                => 'More topic downloads',
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
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~30 characters',
        'form_item_apply_terminal'              => 'Applicable Terminal',
        'form_item_apply_terminal_message'      => 'Select at least one applicable terminal',
        'form_item_logo'                        => 'LOGO',
        'form_item_is_open_user'                => 'Open to Users',
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
        // 表单
        'form_item_icon'                        => 'Icon',
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~16 characters',
        'form_item_website_url'                 => 'Website Address',
        'form_item_website_url_placeholder'     => 'Official website address, starting with http://or https://',
        'form_item_website_url_message'         => 'Incorrect format of official website address',
    ],

    // 主题管理
    'theme'                 => [
        'base_nav_list'                         => [
            ['name' => 'Current Theme', 'type' => 'index'],
            ['name' => 'Theme Install', 'type' => 'upload'],
        ],
        'nav_store_theme_name'                  => 'More topic downloads',
        'list_author_title'                     => 'Author：',
        'list_version_title'                    => 'Applicable Version',
        'form_item_upload_tips'                 => 'Upload an application installation package in zip compression format',
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
        'base_nav_list'                         => [
            ['name' => 'Application Management', 'type' => 'index'],
            ['name' => 'Upload Application', 'type' => 'upload'],
        ],
        'base_nav_more_plugins_download_name'   => 'More plug-in downloads',
        // 基础页面
        'base_search_input_placeholder'         => 'Please enter a name/description',
        'base_top_tips_one'                     => 'List sorting method [custom sorting ->earliest installation]',
        'base_top_tips_two'                     => 'Click and drag icon button to adjust plug-in call and display order',
        'base_open_sort_title'                  => 'Enable sorting',
        'data_list_author_title'                => 'Author：',
        'data_list_author_url_title'            => 'HomePage：',
        'data_list_version_title'               => 'Version：',
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
        'plugins_no_data_tips'                  => 'There is no relevant application yet',
        // 表单
        'form_item_upload_tips'                 => 'Upload an application installation package in zip compression format',
        'form_create_error_tips'                => 'Please fill in again!',
        'form_create_first_step_button_name'    => 'Next Step',
        'form_item_plugins'                     => 'Apply unique tags',
        'form_item_plugins_tips'                => 'Use numbers, lowercase letters and underscores',
        'form_item_plugins_message'             => 'Apply unique tag format of 2~60 characters',
        'form_item_logo'                        => 'LOGO',
        'form_item_logo_tips'                   => '600 * 600px proposal',
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format: 2~30 characters',
        'form_item_author'                      => 'Author',
        'form_item_author_message'              => 'Author format 2~30 characters',
        'form_item_author_url'                  => 'Author URI',
        'form_item_author_url_tips'             => 'Start with http://or https://',
        'form_item_author_url_message'          => 'Please fill in the authors homepage',
        'form_item_version'                     => 'Version',
        'form_item_version_tips'                => 'Major version, minor version number and revision number, each segment shall not exceed 6 digits, such as 1.0.0',
        'form_item_version_message'             => 'Incorrect version format',
        'form_item_desc'                        => 'Describe',
        'form_item_desc_message'                => 'Description content format: 2~60 characters',
        'form_item_apply_terminal'              => 'Applicable Terminal',
        'form_item_apply_terminal_message'      => 'Select at least one applicable terminal',
        'form_item_apply_version'               => 'Applicable system version',
        'form_item_apply_version_message'       => 'Select at least one applicable system version',
        'form_item_is_home'                     => 'Whether there is front-end entrance',
        'form_item_is_home_tips'                => 'Front independent page entry',
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
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format: 2~60 characters',
        'form_item_images_url'                  => 'Navigation Icon',
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
        // 表单
        'form_item_id'                          => 'Unique Number',
        'form_item_id_tips'                     => [
            '1. If left blank, the system will automatically generate',
            '2. Do not modify at will to avoid data confusion',
        ],
        'form_item_id_message'                  => 'Please enter a unique number',
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~16 characters',
        'form_item_lng'                         => 'Longitude',
        'form_item_lng_message'                 => 'Please fill in the longitude',
        'form_item_lat'                         => 'Latitude',
        'form_item_lat_message'                 => 'Please fill in the latitude',
        'form_item_letters'                     => 'Initial',
        'form_item_letters_message'             => 'Please fill in the initials',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => 'FilterPrice',
        'top_tips_list'                         => [
            'Minimum price 0 - maximum price 100 is less than 100',
            'Minimum price 1000 - maximum price 0 is greater than 1000',
            'The minimum price of 100 - the maximum price of 500 is greater than or equal to 100 and less than 500',
        ],
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format 2~16 characters',
        'form_item_min_price'                   => 'Minimum Price',
        'form_item_min_price_message'           => 'Incorrect minimum price',
        'form_item_max_price'                   => 'Maximum Price',
        'form_item_max_price_message'           => 'Incorrect maximum price',
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => 'Slide',
        // 表单
        'form_item_name'                        => 'Name',
        'form_item_name_message'                => 'Name format: 2~60 characters',
        'form_item_images_url'                  => 'Slide Pictures',
        'form_item_images_url_tips'             => [
            '1. Recommended size of PC end: 1920 * 480px',
            '2. Recommended size of mobile terminal: 1200 * 360px',
        ],
        // 动态表格
        'form_table'                            => [
            'name'         => 'Name',
            'platform'     => 'Platform',
            'images'       => 'Images',
            'event_type'   => 'Event Tyoe',
            'event_value'  => 'Event Value',
            'is_enable'    => 'Enable or not',
            'sort'         => 'Sort',
            'add_time'     => 'Creation Time',
            'upd_time'     => 'Update Time',
        ],
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

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS: Non-developers should not execute any SQL statements at will, which may cause the entire system database to be deleted.',
        'form_sql_placeholder'                  => 'SQL Statement',
        'form_sql_message'                      => 'Please fill in the SQL statement to be executed',
        'form_dev_tips'                         => 'If you need to execute SQL statements, change the [is_develop] value in the [config/shopxo. php] file to [true] to enable developer mode.',
    ],

    // 应用商店
    'store'                 => [
        'top_tips'                              => 'ShopXO excellent application list, where the most senior, technically capable and reliable ShopXO developers are gathered to provide a comprehensive escort for your plug-in, style and template customization.',
        'to_store_name'                         => 'Go to the app store to select plugins >>',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => 'Background management system',
        'remove_cache_title'                    => 'Clear Cache',
        'user_status_title'                     => 'User Status',
        'user_status_message'                   => 'Please select user status',
        // 商店绑定
        'store_check_update_name'               => 'Check for update',
        'store_bind_accounts_name'              => 'Bind ShopXO store account',
        'store_bind_accounts_tips'              => 'Bind ShopXO App Store account, get the latest version information of plug-in, install and update online',
        'store_bind_authorized_subject_name'    => 'Authorized Subject',
        'store_bind_form_accounts'              => 'Accounts',
        'store_bind_form_accounts_placeholder'  => 'User name/mobile phone/email',
        'store_bind_form_accounts_message'      => 'Account format 1~30 characters',
        'store_bind_form_password'              => 'Password',
        'store_bind_form_password_placeholder'  => 'Login Password',
        'store_bind_form_password_message'      => 'Login password format 6~30 characters',
        'store_bind_form_regster_name'          => 'No account, go to register',
        'store_bind_form_tips'                  => 'One account supports binding to multiple ShopXO stores',
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

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name' => 'System Setup',
            'item' => [
                'config_index'                 => 'System config',
                'config_store'                 => 'Store info',
                'config_save'                  => 'Configuration save',
                'index_storeaccountsbind'      => 'App store account binding',
                'index_inspectupgrade'         => 'System update check',
                'index_inspectupgradeconfirm'  => 'System update confirmation',
                'index_stats'                  => 'Home page statistics',
                'index_income'                 => 'Home page Statistics (income statistics]',
            ]
        ],
        'site_index' => [
            'name' => 'Site Config',
            'item' => [
                'site_index'                  => 'Site setup',
                'site_save'                   => 'Site setup editing',
                'site_goodssearch'            => 'Site setup and product search',
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
            'name' => 'Power Control',
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
                'power_delete'       => 'Power delete',
            ]
        ],
        'user_index' => [
            'name' => 'User Admin',
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
            'name' => 'Goods Admin',
            'item' => [
                'goods_index'                       => 'Goods admin',
                'goods_saveinfo'                    => 'Goods add/edit page',
                'goods_save'                        => 'Item add/edit',
                'goods_delete'                      => 'Goods delete',
                'goods_statusupdate'                => 'Goods status update',
                'goods_basetemplate'                => 'Get commodity base template',
                'goods_detail'                      => 'Goods details',
                'goodscategory_index'               => 'Goods category',
                'goodscategory_save'                => 'Goods category add/edit',
                'goodscategory_delete'              => 'Goods category delete',
                'goodsparamstemplate_index'         => 'Goods params',
                'goodsparamstemplate_delete'        => 'Delete commodity params',
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
            'name' => 'Order Admin',
            'item' => [
                'order_index'             => 'Order admin',
                'order_delete'            => 'Order delete',
                'order_cancel'            => 'Order cancellation',
                'order_delivery'          => 'Order delivery',
                'order_collect'           => 'Order receipt',
                'order_pay'               => 'Order payment',
                'order_confirm'           => 'acknowledgement of order',
                'order_detail'            => 'Order details',
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
            'name' => 'Website Admin',
            'item' => [
                'navigation_index'         => 'Navigation admin',
                'navigation_save'          => 'Navigation add/edit',
                'navigation_delete'        => 'Navigation delete',
                'navigation_statusupdate'  => 'Navigation status update',
                'customview_index'         => 'Custom page',
                'customview_saveinfo'      => 'Custom page add/edit page',
                'customview_save'          => 'Custom page add/edit',
                'customview_delete'        => 'Custom page delete',
                'customview_statusupdate'  => 'Custom page status update',
                'customview_detail'        => 'Custom page details',
                'link_index'               => 'Links',
                'link_saveinfo'            => 'Link add/edit page',
                'link_save'                => 'Add / Edit Links',
                'link_delete'              => 'Link delete',
                'link_statusupdate'        => 'Link status update',
                'link_detail'              => 'Link details',
                'theme_index'              => 'Theme admin',
                'theme_save'               => 'Topic admin add/edit',
                'theme_upload'             => 'Theme upload and installation',
                'theme_delete'             => 'Subject delete',
                'theme_download'           => 'Theme download',
                'slide_index'              => 'HomePage Slide',
                'slide_saveinfo'           => 'Carousel add/edit page',
                'slide_save'               => 'Carousel add/edit',
                'slide_statusupdate'       => 'Rotation status update',
                'slide_delete'             => 'Rotation delete',
                'slide_detail'             => 'Rotation details',
                'screeningprice_index'     => 'Screening price',
                'screeningprice_save'      => 'Filter price add/edit',
                'screeningprice_delete'    => 'Filter price delete',
                'region_index'             => 'Regional admin',
                'region_save'              => 'Region add/edit',
                'region_delete'            => 'Region delete',
                'region_codedata'          => 'Get area number data',
                'express_index'            => 'Express Management',
                'express_save'             => 'Express add/edit',
                'express_delete'           => 'Express delete',
                'payment_index'            => 'Payment method',
                'payment_saveinfo'         => 'Payment method installation / editing page',
                'payment_save'             => 'Payment method installation / editing',
                'payment_delete'           => 'Payment method delete',
                'payment_install'          => 'Payment method installation',
                'payment_statusupdate'     => 'Payment method status update',
                'payment_uninstall'        => 'Payment method unloading',
                'payment_upload'           => 'Payment method upload',
                'quicknav_index'           => 'Quick navigation',
                'quicknav_saveinfo'        => 'Quick navigation add/edit page',
                'quicknav_save'            => 'Quick navigation add/edit',
                'quicknav_statusupdate'    => 'Quick navigation status update',
                'quicknav_delete'          => 'Quick navigation delete',
                'quicknav_detail'          => 'Quick navigation details',
                'design_index'             => 'Page design',
                'design_saveinfo'          => 'Page design add/edit page',
                'design_save'              => 'Page design add/edit',
                'design_statusupdate'      => 'Page design status update',
                'design_upload'            => 'Page design import',
                'design_download'          => 'Page Design Download',
                'design_sync'              => 'Page design synchronization home page',
                'design_delete'            => 'Page design delete',
            ]
        ],
        'brand_index' => [
            'name' => 'Brand Admin',
            'item' => [
                'brand_index'           => 'Brand admin',
                'brand_saveinfo'        => 'Brand add/edit page',
                'brand_save'            => 'Brand add/edit',
                'brand_statusupdate'    => 'Brand status update',
                'brand_delete'          => 'Brand delete',
                'brand_detail'          => 'Brand details',
                'brandcategory_index'   => 'Brand classification',
                'brandcategory_save'    => 'Brand category add/edit',
                'brandcategory_delete'  => 'Brand classification delete',
            ]
        ],
        'warehouse_index' => [
            'name' => 'Warehouse Admin',
            'item' => [
                'warehouse_index'               => 'Warehouse admin',
                'warehouse_saveinfo'            => 'Warehouse add/edit page',
                'warehouse_save'                => 'Warehouse add/edit',
                'warehouse_delete'              => 'Warehouse delete',
                'warehouse_statusupdate'        => 'Warehouse status update',
                'warehouse_detail'              => 'Warehouse details',
                'warehousegoods_index'          => 'Warehouse commodity admin',
                'warehousegoods_detail'         => 'Warehouse item details',
                'warehousegoods_delete'         => 'Warehouse Item Deletion',
                'warehousegoods_statusupdate'   => 'Warehouse commodity status update',
                'warehousegoods_goodssearch'    => 'Warehouse item search',
                'warehousegoods_goodsadd'       => 'Warehouse item search add',
                'warehousegoods_goodsdel'       => 'Warehouse item search delete',
                'warehousegoods_inventoryinfo'  => 'Warehouse commodity inventory editing page',
                'warehousegoods_inventorysave'  => 'Edit warehouse inventory',
            ]
        ],
        'app_index' => [
            'name' => 'Mobile Admin',
            'item' => [
                'appconfig_index'            => 'Basic config',
                'appconfig_save'             => 'Basic config saving',
                'apphomenav_index'           => 'Home page navigation',
                'apphomenav_saveinfo'        => 'Home navigation add/edit page',
                'apphomenav_save'            => 'Home page navigation add/edit',
                'apphomenav_statusupdate'    => 'Homepage navigation status update',
                'apphomenav_delete'          => 'Home page navigation delete',
                'apphomenav_detail'          => 'Home page navigation details',
                'appcenternav_index'         => 'User center navigation',
                'appcenternav_saveinfo'      => 'User center navigation add/edit page',
                'appcenternav_save'          => 'User center navigation add/edit',
                'appcenternav_statusupdate'  => 'User center navigation status update',
                'appcenternav_delete'        => 'User center navigation delete',
                'appcenternav_detail'        => 'User center navigation details',
                'appmini_index'              => 'Applet list',
                'appmini_created'            => 'Applet package generation',
                'appmini_delete'             => 'Applet package delete',
                'appmini_themeupload'        => 'Applet theme upload',
                'appmini_themesave'          => 'Applet theme switching',
                'appmini_themedelete'        => 'Applet theme switching',
                'appmini_themedownload'      => 'Applet theme download',
                'appmini_config'             => 'Applet config',
                'appmini_save'               => 'Applet config save',
            ]
        ],
        'article_index' => [
            'name' => 'Article Admin',
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
            'name' => 'Data Admin',
            'item' => [
                'answer_index'          => 'Q&A message',
                'answer_reply'          => 'Q&A message reply',
                'answer_delete'         => 'Q&A message delete',
                'answer_statusupdate'   => 'Update message status',
                'answer_saveinfo'       => 'Q&A add/edit page',
                'answer_save'           => 'Q&A add/edit',
                'answer_detail'         => 'Q&A message details',
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
            ]
        ],
        'store_index' => [
            'name' => 'Application Center',
            'item' => [
                'pluginsadmin_index'         => 'Application admin',
                'plugins_index'              => 'Application call admin',
                'pluginsadmin_saveinfo'      => 'Application add/edit page',
                'pluginsadmin_save'          => 'Apply add/edit',
                'pluginsadmin_statusupdate'  => 'Application status update',
                'pluginsadmin_delete'        => 'Apply delete',
                'pluginsadmin_upload'        => 'Application upload',
                'pluginsadmin_download'      => 'Application packaging',
                'pluginsadmin_install'       => 'Application installation',
                'pluginsadmin_uninstall'     => 'Apps Uninstall',
                'pluginsadmin_sortsave'      => 'Apply sort save',
                'store_index'                => 'App store',
                'packageinstall_index'       => 'Package installation page',
                'packageinstall_install'     => 'Package installation',
                'packageupgrade_upgrade'     => 'Package update',
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