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
 * 模块语言包-繁体中文
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
            'order_transaction_amount_name'     => '訂單成交金額走勢',
            'order_trading_trend_name'          => '訂單交易走勢',
            'goods_hot_name'                    => '熱賣商品',
            'goods_hot_tips'                    => '僅顯示前13條商品',
            'payment_name'                      => '支付方式',
            'order_region_name'                 => '訂單地域分佈',
            'order_region_tips'                 => '僅顯示10條數據',
            'new_user_name'                     => '新增用戶',
            'buy_user_name'                     => '下單用戶',
            'upgrade_check_loading_tips'        => '正在獲取最新內容、請稍候…',
            'upgrade_version_name'              => '更新版本：',
            'upgrade_date_name'                 => '更新日期：',
        ],
        // 页面基础
        'base_update_system_title'              => '系統更新',
        'base_update_button_title'              => '立即更新',
        'base_item_base_stats_title'            => '商城統計',
        'base_item_base_stats_tips'             => '時間篩選僅對總數有效',
        'base_item_user_title'                  => '用戶總量',
        'base_item_order_number_title'          => '訂單總量',
        'base_item_order_complete_number_title' => '成交總量',
        'base_item_order_complete_title'        => '訂單總計',
        'base_item_last_month_title'            => '上月',
        'base_item_same_month_title'            => '當月',
        'base_item_yesterday_title'             => '昨日',
        'base_item_today_title'                 => '今日',
        'base_item_order_profit_title'          => '訂單成交金額走勢',
        'base_item_order_trading_title'         => '訂單交易走勢',
        'base_item_order_tips'                  => '所有訂單',
        'base_item_hot_sales_goods_title'       => '熱賣商品',
        'base_item_hot_sales_goods_tips'        => '不含取消關閉的訂單',
        'base_item_payment_type_title'          => '支付方式',
        'base_item_map_whole_country_title'     => '訂單地域分佈',
        'base_item_map_whole_country_tips'      => '不含取消關閉的訂單、默認維度（省）',
        'base_item_map_whole_country_province'  => '省',
        'base_item_map_whole_country_city'      => '市',
        'base_item_map_whole_country_county'    => '區/縣',
        'base_item_new_user_title'              => '新增用戶',
        'base_item_buy_user_title'              => '下單用戶',
        'system_info_title'                     => '系統資訊',
        'system_ver_title'                      => '軟體版本',
        'system_os_ver_title'                   => '作業系統',
        'system_php_ver_title'                  => 'PHP版本',
        'system_mysql_ver_title'                => 'MySQL版本',
        'system_server_ver_title'               => '伺服器端資訊',
        'system_host_title'                     => '當前功能變數名稱',
        'development_team_title'                => '開發團隊',
        'development_team_website_title'        => '公司官網',
        'development_team_website_value'        => '上海縱之格科技有限公司',
        'development_team_support_title'        => '技術支援',
        'development_team_support_value'        => 'ShopXO企業級電商系統提供商',
        'development_team_ask_title'            => '交流提問',
        'development_team_ask_value'            => 'ShopXO交流提問',
        'development_team_agreement_title'      => '開源協定',
        'development_team_agreement_value'      => '查看開源協定',
        'development_team_update_log_title'     => '更新日誌',
        'development_team_update_log_value'     => '查看更新日誌',
        'development_team_members_title'        => '研發成員',
        'development_team_members_value'        => [
            ['name' => '龔哥哥', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => '用戶',
        // 动态表格
        'form_table'                            => [
            'id'                    => '用戶ID',
            'number_code'           => '會員碼',
            'system_type'           => '系統類型',
            'platform'              => '所屬平臺',
            'avatar'                => '頭像',
            'username'              => '用戶名',
            'nickname'              => '昵稱',
            'mobile'                => '手機',
            'email'                 => '郵箱',
            'gender_name'           => '性別',
            'status_name'           => '狀態',
            'province'              => '所在省',
            'city'                  => '所在市',
            'county'                => '所在區/縣',
            'address'               => '詳細地址',
            'birthday'              => '生日',
            'integral'              => '可用積分',
            'locking_integral'      => '鎖定積分',
            'referrer'              => '邀請用戶',
            'referrer_placeholder'  => '請輸入邀請用戶名/昵稱/手機/郵箱',
            'add_time'              => '註冊時間',
            'upd_time'              => '更新時間',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => '用戶地址',
        // 详情
        'detail_user_address_idcard_name'       => '姓名',
        'detail_user_address_idcard_number'     => '號碼',
        'detail_user_address_idcard_pic'        => '照片',
        // 动态表格
        'form_table'                            => [
            'user'              => '用戶資訊',
            'user_placeholder'  => '請輸入用戶名/昵稱/手機/郵箱',
            'alias'             => '別名',
            'name'              => '連絡人',
            'tel'               => '聯繫電話',
            'province_name'     => '所屬省',
            'city_name'         => '所屬市',
            'county_name'       => '所屬區/縣',
            'address'           => '詳細地址',
            'address_last_code' => '地址最後一級編碼',
            'position'          => '經緯度',
            'idcard_info'       => '身份證資訊',
            'is_default'        => '是否默認',
            'add_time'          => '創建時間',
            'upd_time'          => '更新時間',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => '移除後保存生效、確認繼續嗎？',
            'address_no_data'                   => '地址數據為空',
            'address_not_exist'                 => '地址不存在',
            'address_logo_message'              => '請上傳logo圖片',
        ],
        // 主导航
        'base_nav_list'                       => [
            ['name' => '基礎配寘', 'type' => 'base'],
            ['name' => '網站設定', 'type' => 'siteset'],
            ['name' => '網站類型', 'type' => 'sitetype'],
            ['name' => '用戶註冊', 'type' => 'register'],
            ['name' => '用戶登錄', 'type' => 'login'],
            ['name' => '密碼找回', 'type' => 'forgetpwd'],
            ['name' => '驗證碼', 'type' => 'verify'],
            ['name' => '訂單售後', 'type' => 'orderaftersale'],
            ['name' => '附件', 'type' => 'attachment'],
            ['name' => '緩存', 'type' => 'cache'],
            ['name' => '擴展項', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => '首頁', 'type' => 'index'],
            ['name' => '蒐索', 'type' => 'search'],
            ['name' => '訂單', 'type' => 'order'],
            ['name' => '商品', 'type' => 'goods'],
            ['name' => '購物車', 'type' => 'cart'],
            ['name' => '擴展', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => '網站狀態',
        'base_item_site_domain_title'           => '網站功能變數名稱地址',
        'base_item_site_filing_title'           => '備案資訊',
        'base_item_site_other_title'            => '其它',
        'base_item_session_cache_title'         => 'Session緩存配寘',
        'base_item_data_cache_title'            => '數據緩存配寘',
        'base_item_redis_cache_title'           => 'redis緩存配寘',
        'base_item_crontab_config_title'        => '定時腳本配寘',
        'base_item_regex_config_title'          => '正則配寘',
        'base_item_quick_nav_title'             => '快捷導航',
        'base_item_user_base_title'             => '用戶基礎',
        'base_item_user_address_title'          => '用戶地址',
        'base_item_multilingual_title'          => '多語言',
        'base_item_site_auto_mode_title'        => '自動模式',
        'base_item_site_manual_mode_title'      => '手動模式',
        'base_item_default_payment_title'       => '默認支付方式',
        'base_item_display_type_title'          => '展示型',
        'base_item_self_extraction_title'       => '自提點',
        'base_item_fictitious_title'            => '虛擬銷售',
        'choice_upload_logo_title'              => '選擇logo',
        'add_goods_title'                       => '商品添加',
        'add_self_extractio_address_title'      => '添加地址',
        'site_domain_tips_list'                 => [
            '1. 網站功能變數名稱未設定則使用當前網站功能變數名稱功能變數名稱地址[ '.__MY_DOMAIN__.' ]',
            '2. 附件和靜態地址未設定則使用當前網站靜態功能變數名稱地址[ '.__MY_PUBLIC_URL__.' ]',
            '3. 如伺服器端不是以public設為根目錄的、則這裡配寘【附件cdn功能變數名稱、css/js靜態檔案cdn功能變數名稱】需要後面再加public、如：'.__MY_PUBLIC_URL__.'public/',
            '4.在命令列模式下運行項目，該區域地址必須配寘、否則項目中一些地址會缺失功能變數名稱資訊',
            '5.請勿亂配寘、錯誤地址會導致網站無法訪問（地址配寘以http開頭）、如果自己站的配寘了https則以https開頭',
        ],
        'site_cache_tips_list'                  => [
            '1.默認使用的檔案緩存、使用Redis緩存PHP需要先安裝Redis擴展',
            '2.請確保Redis服務穩定性（Session使用緩存後、服務不穩定可能導致後臺也無法登入）',
            '3.如遇到Redis服務异常無法登入管理後臺、修改設定檔[ config ]目錄下[ session.php，cache.php ]檔案',
        ],
        'goods_tips_list'                       => [
            '1. WEB端默認展示3級，最低1級、最高3級（如設定為0級則默認為3級）',
            '2.手機端默認展示0級（商品列表模式）、最低0級、最高3級（1~3為分類展示模式）',
            '3.層級不一樣、前端分類頁樣式也會不一樣',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1.配寘每個樓層最多展示多少個商品',
            '2.不建議將數量修改的太大、會導致PC端左側空白區域太大',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            '綜合為：熱度->銷量->最新進行降序（desc）排序',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1.可點擊商品標題拖拽排序、按照順序展示',
            '2.不建議添加很多商品、會導致PC端左側空白區域太大',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1.默認以【用戶名、手機、郵箱】作為用戶唯一',
            '2.開啟則加入【系統標識】並行作為用戶唯一',
        ],
        'extends_crontab_tips'                  => '建議將腳本地址添加到linux定時任務定時請求即可（結果sucs:0，fail:0冒號後面則是處理的數據條數，sucs成功，fali失敗）',
        'left_images_random_tips'               => '左側圖片最多可上傳3張圖片、每次隨機展示其中一張',
        'background_color_tips'                 => '可自定義背景圖片、默認底灰色',
        'site_setup_layout_tips'                => '拖拽模式需要自行進入首頁設計頁面、請先保存選中配寘後再',
        'site_setup_layout_button_name'         => '設計頁面',
        'site_setup_goods_category_tips'        => '如需更多樓層展示，請先到/商品管理->商品分類、一級分類設置首頁推薦',
        'site_setup_goods_category_no_data_tips'=> '暫無數據，請先到/商品管理->商品分類、一級分類設置首頁推薦',
        'site_setup_order_default_payment_tips' => '可以設定不同平臺對應的默認支付方式、請先在[網站管理->支付方式]中安裝好支付挿件啟用並對用戶開放',
        'site_setup_choice_payment_message'     => '請選擇{:name}默認支付方式',
        'sitetype_top_tips_list'                => [
            '1.快遞：常規電商流程，用戶選擇收貨地址下單支付->商家派發給協力廠商物流發貨->確認收貨->訂單完成',
            '2.同城：同城騎手或自行配送，用戶選擇收貨地址下單支付->商家派發給同城協力廠商配送或自行配送->確認收貨->訂單完成',
            '3.展示：僅展示產品，可發起諮詢（不能下單）',
            '4.自提：下單時選擇自提貨物地址，用戶下單支付->確認提貨->訂單完成',
            '5.虛擬：常規電商流程，用戶下單支付->自動發貨->確認提貨->訂單完成',
        ],
        // 添加自提地址表单
        'form_take_address_title'                  => '自提地址',
        'form_take_address_logo'                   => 'LOGO',
        'form_take_address_logo_tips'              => '建議300*300px',
        'form_take_address_alias'                  => '別名',
        'form_take_address_alias_message'          => '別名格式最多16個字',
        'form_take_address_name'                   => '連絡人',
        'form_take_address_name_message'           => '連絡人格式2~16個字之間',
        'form_take_address_tel'                    => '聯繫電話',
        'form_take_address_tel_message'            => '請填寫聯繫電話',
        'form_take_address_address'                => '詳細地址',
        'form_take_address_address_message'        => '詳細地址格式1~80個字之間',
        // 域名绑定语言
        'form_domain_multilingual_domain_name'     => '域名',
        'form_domain_multilingual_domain_message'  => '請填寫功能變數名稱',
        'form_domain_multilingual_select_message'  => '請選擇功能變數名稱對應語言',
        'form_domain_multilingual_add_title'       => '添加域名',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => '後臺登入',
        'admin_login_info_bg_images_list_tips'  => [
            '1.背景圖片位於[ public/static/admin/default/images/login ]目錄下',
            '2.背景圖片命名規則（1~50）、如1.png',
        ],
        'map_type_tips'                         => '由於每一家的地圖標準不一樣、請勿隨意切換地圖、會導致地圖座標標注不準確的情况。',
        'apply_map_baidu_name'                  => '請到百度地圖開放平臺申請',
        'apply_map_amap_name'                   => '請到高德地圖開放平臺申請',
        'apply_map_tencent_name'                => '請到騰訊地圖開放平臺申請',
        'apply_map_tianditu_name'               => '請到天地圖開放平臺申請',
        'cookie_domain_list_tips'               => [
            '1.默認空、則僅對當前訪問功能變數名稱有效',
            '2.如需要二級功能變數名稱也共亯cookie則填寫頂層網域名、如：baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => '品牌',
        // 动态表格
        'form_table'                            => [
            'id'                   => '品牌ID',
            'name'                 => '名稱',
            'describe'             => '描述',
            'logo'                 => 'LOGO',
            'url'                  => '官網地址',
            'brand_category_text'  => '品牌分類',
            'is_enable'            => '是否啟用',
            'sort'                 => '排序',
            'add_time'             => '創建時間',
            'upd_time'             => '更新時間',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => '品牌分類',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => '文章',
        'detail_content_title'                  => '詳情內容',
        'detail_images_title'                   => '詳情圖片',
        // 动态表格
        'form_table'                            => [
            'id'                     => '檔案ID',
            'cover'                  => '封面',
            'info'                   => '標題',
            'describe'               => '描述',
            'article_category_name'  => '分類',
            'is_enable'              => '是否啟用',
            'is_home_recommended'    => '首頁推薦',
            'jump_url'               => '跳轉url地址',
            'images_count'           => '圖片數量',
            'access_count'           => '訪問次數',
            'add_time'               => '創建時間',
            'upd_time'               => '更新時間',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => '文章分類',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => '自定義頁面',
        'detail_content_title'                  => '詳情內容',
        'detail_images_title'                   => '詳情圖片',
        'save_view_tips'                        => '請先保存再預覽效果',
        // 动态表格
        'form_table'                            => [
            'id'              => '數據ID',
            'logo'            => 'logo',
            'name'            => '名稱',
            'is_enable'       => '是否啟用',
            'is_header'       => '是否頭部',
            'is_footer'       => '是否尾部',
            'is_full_screen'  => '是否滿屏',
            'images_count'    => '圖片數量',
            'access_count'    => '訪問次數',
            'add_time'        => '創建時間',
            'upd_time'        => '更新時間',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => '更多設計範本下載',
        'upload_list_tips'                      => [
            '1.選擇已下載的頁面設計zip包',
            '2.導入將自動新增一條數據',
        ],
        'operate_sync_tips'                     => '資料同步到首頁拖拽視覺化中、之後再修改數據不受影響、但是不要删除相關附件',
        // 动态表格
        'form_table'                            => [
            'id'                => '數據ID',
            'logo'              => 'logo',
            'name'              => '名稱',
            'access_count'      => '訪問次數',
            'is_enable'         => '是否啟用',
            'is_header'         => '是否含頭部',
            'is_footer'         => '是否含尾部',
            'seo_title'         => 'SEO標題',
            'seo_keywords'      => 'SEO關鍵字',
            'seo_desc'          => 'SEO描述',
            'add_time'          => '創建時間',
            'upd_time'          => '更新時間',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => '倉庫',
        'top_tips_list'                         => [
            '1.權重數值越大代表權重越高、扣除庫存按照權重依次扣除）',
            '2.倉庫僅軟删除、删除後將不可用、僅資料庫中保留數據）可以自行删除關聯的商品數據',
            '3.倉庫停用和删除、關聯的商品庫存會立即釋放',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => '名稱/別名',
            'level'          => '權重',
            'is_enable'      => '是否啟用',
            'contacts_name'  => '連絡人',
            'contacts_tel'   => '聯繫電話',
            'province_name'  => '所在省',
            'city_name'      => '所在市',
            'county_name'    => '所在區/縣',
            'address'        => '詳細地址',
            'position'       => '經緯度',
            'add_time'       => '創建時間',
            'upd_time'       => '更新時間',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => '請選擇倉庫',
        ],
        // 基础
        'add_goods_title'                       => '商品添加',
        'no_spec_data_tips'                     => '無規格數據',
        'batch_setup_inventory_placeholder'     => '批量設定的值',
        'base_spec_inventory_title'             => '規格庫存',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基礎資訊',
            'goods_placeholder'  => '請輸入商品名稱/型號',
            'warehouse_name'     => '倉庫',
            'is_enable'          => '是否啟用',
            'inventory'          => '總庫存',
            'spec_inventory'     => '規格庫存',
            'add_time'           => '創建時間',
            'upd_time'           => '更新時間',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => '管理員資訊不存在',
        // 列表
        'top_tips_list'                         => [
            '1. admin帳戶默認擁有所有許可權，不可更改。',
            '2. admin 帳戶不可更改，但是可以在資料表中修改( '.MyConfig('database.connections.mysql.prefix').'admin ) 欄位 username',
        ],
        'base_nav_title'                        => '管理員',
        // 登录
        'login_type_username_title'             => '帳號密碼',
        'login_type_mobile_title'               => '手機驗證碼',
        'login_type_email_title'                => '郵箱驗證碼',
        'login_close_tips'                      => '暫時關閉了登入',
        // 忘记密码
        'form_forget_password_name'             => '忘記密碼？',
        'form_forget_password_tips'             => '請聯系管理員重置密碼',
        // 动态表格
        'form_table'                            => [
            'username'              => '管理員',
            'status'                => '狀態',
            'gender'                => '性別',
            'mobile'                => '手機',
            'email'                 => '郵箱',
            'role_name'             => '角色組',
            'login_total'           => '登錄次數',
            'login_time'            => '最後登錄時間',
            'add_time'              => '創建時間',
            'upd_time'              => '更新時間',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '用戶註冊協定', 'type' => 'register'],
            ['name' => '用戶隱私政策', 'type' => 'privacy'],
            ['name' => '帳號註銷協定', 'type' => 'logout']
        ],
        'top_tips'          => '前端訪問協定地址新增參數is_ content=1則僅展示純協定內容',
        'view_detail_name'                      => '查看詳情',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '基礎配寘', 'type' => 'index'],
            ['name' => 'APP/小程式', 'type' => 'app'],
        ],
        'home_diy_template_title'               => '首頁DIY範本',
        'online_service_title'                  => '線上客服',
        'user_base_popup_title'                 => '用戶基礎資訊彈窗提示',
        'user_onekey_bind_mobile_tips_list'     => [
            '1. 獲取當前小程式平臺帳戶或者本本機的手機號碼一鍵登入綁定，現時僅支持【微信小程式、百度小程式、頭條小程式】',
            '2. 依賴需要開啟《强制綁定手機》有效',
            '3. 部分小程式平臺可能需要申請許可權、請根據小程式平臺要求申請後再對應開啟',
        ],
        'user_address_platform_import_tips_list'=> [
            '1. 獲取當前小程式平臺app帳戶的收貨地址，現時僅支持【小程式】',
            '2. 確認導入後直接添加為系統用戶收貨地址',
            '3. 部分小程式平臺可能需要申請許可權、請根據小程式平臺要求申請後再對應開啟',
        ],
        'user_base_popup_top_tips_list'         => [
            '1. 現時僅微信小程式平臺自動授權登入後無用戶昵稱和頭像資訊',
        ],
        'online_service_top_tips_list'          => [
            '1.自定義客服http協定採用webview管道打開',
            '2.客服優先順序順序【客服系統->自定義客服->企業微信客服（僅app+h5+微信小程式生效）->各端平臺客服->電話客服】',
        ],
        'home_diy_template_tips'                => '不選擇DIY範本則默認跟隨統一的首頁配寘',
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '當前主題', 'type' => 'index'],
            ['name' => '主題安裝', 'type' => 'upload'],
            ['name' => '源碼包下載', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => '更多主題下載',
        'nav_theme_download_name'               => '查看小程式打包教程',
        'nav_theme_download_tips'               => '手機端主題採用uniapp開發（支持多端小程式、H5、APP）',
        'form_alipay_extend_title'              => '客服配寘',
        'form_alipay_extend_tips'               => 'PS：如【APP/小程式】中開啟（開啟線上客服），則以下配寘必填[企業編碼]和[聊天窗編碼]',
        'form_theme_upload_tips'                => '上傳一個zip壓縮格式的安裝包',
        'list_no_data_tips'                     => '沒有相關主題包',
        'list_author_title'                     => '作者',
        'list_version_title'                    => '適用版本',
        'package_generate_tips'                 => '生成時間比較長，請不要關閉流覽器窗口！',
        // 动态表格
        'form_table'                            => [
            'name'  => '包名',
            'size'  => '大小',
            'url'   => '下載地址',
            'time'  => '創建時間',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '簡訊設定', 'type' => 'index'],
            ['name' => '消息範本', 'type' => 'message'],
        ],
        'top_tips'                              => '阿裡雲簡訊管理地址',
        'top_to_aliyun_tips'                    => '點擊去阿裡雲購買簡訊',
        'base_item_admin_title'                 => '後臺',
        'base_item_index_title'                 => '前端',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '郵箱設置', 'type' => 'index'],
            ['name' => '消息範本', 'type' => 'message'],
        ],
        'top_tips'                              => '由於不同郵箱平臺存在一些差异、配寘也有所不同、具體以郵箱平臺配寘教程為准',
        // 基础
        'test_title'                            => '測試',
        'test_content'                          => '郵件配寘-發送測試內容',
        'base_item_admin_title'                 => '後臺',
        'base_item_index_title'                 => '前端',
        // 表单
        'form_item_test'                        => '測試接收的郵寄地址',
        'form_item_test_tips'                   => '請先保存配寘後，再進行測試',
        'form_item_test_button_title'           => '測試',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => '根據服務器環境[Nginx、Apache、IIS]不同配寘相應的偽靜態規則',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => '商品',
        'goods_nav_list'                        => [
            'base'            => ['name' => '基礎資訊', 'type'=>'base'],
            'spec'            => ['name' => '商品規格', 'type'=>'spec'],
            'spec_images'     => ['name' => '規格圖片', 'type'=>'spec_images'],
            'parameters'      => ['name' => '商品參數', 'type'=>'parameters'],
            'photo'           => ['name' => '商品相册', 'type'=>'photo'],
            'video'           => ['name' => '商品視頻', 'type'=>'video'],
            'app'             => ['name' => '手機詳情', 'type'=>'app'],
            'web'             => ['name' => '電腦詳情', 'type'=>'web'],
            'fictitious'      => ['name' => '虛擬資訊', 'type'=>'fictitious'],
            'extends'         => ['name' => '擴展數據', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO資訊', 'type'=>'seo'],
        ],
        'delete_only_goods_text'                => '僅商品',
        'delete_goods_and_images_text'          => '商品和圖片',
        // 动态表格
        'form_table'                            => [
            'id'                      => '商品ID',
            'info'                    => '商品資訊',
            'info_placeholder'        => '請輸入商品名稱/簡述/編碼/條碼/SEO資訊',
            'category_text'           => '商品分類',
            'brand_name'              => '品牌',
            'price'                   => '銷售價格',
            'original_price'          => '原價',
            'inventory'               => '庫存總量',
            'is_shelves'              => '上下架',
            'is_deduction_inventory'  => '扣减庫存',
            'site_type'               => '商品類型',
            'model'                   => '商品型號',
            'place_origin_name'       => '生產地',
            'give_integral'           => '購買贈送積分比例',
            'buy_min_number'          => '單次最低起購數量',
            'buy_max_number'          => '單次最大購買數量',
            'sort_level'              => '排序權重',
            'sales_count'             => '銷量',
            'access_count'            => '訪問次數',
            'add_time'                => '創建時間',
            'upd_time'                => '更新時間',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => '商品分類',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => '商品評論',
        // 动态表格
        'form_table'                            => [
            'user'               => '用戶資訊',
            'user_placeholder'   => '請輸入用戶名/昵稱/手機/郵箱',
            'goods'              => '基礎資訊',
            'goods_placeholder'  => '請輸入商品名稱/型號',
            'business_type'      => '業務類型',
            'content'            => '評論內容',
            'images'             => '評論圖片',
            'rating'             => '評分',
            'reply'              => '回復內容',
            'is_show'            => '是否顯示',
            'is_anonymous'       => '是否匿名',
            'is_reply'           => '是否回復',
            'reply_time_time'    => '回復時間',
            'add_time_time'      => '創建時間',
            'upd_time_time'      => '更新時間',
        ],
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => '商品參數',
        // 动态表格
        'form_table'                            => [
            'category_id'   => '商品分類',
            'name'          => '名稱',
            'is_enable'     => '是否啟用',
            'config_count'  => '參數數量',
            'add_time'      => '創建時間',
            'upd_time'      => '更新時間',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => '商品分類',
            'name'         => '名稱',
            'is_enable'    => '是否啟用',
            'content'      => '規格值',
            'add_time'     => '創建時間',
            'upd_time'     => '更新時間',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => '用戶資訊',
            'user_placeholder'   => '請輸入用戶名/昵稱/手機/郵箱',
            'goods'              => '商品資訊',
            'goods_placeholder'  => '請輸入商品名稱/簡述/SEO資訊',
            'price'              => '銷售價格',
            'original_price'     => '原價',
            'add_time'           => '創建時間',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => '用戶資訊',
            'user_placeholder'   => '請輸入用戶名/昵稱/手機/郵箱',
            'goods'              => '商品資訊',
            'goods_placeholder'  => '請輸入商品名稱/簡述/SEO資訊',
            'price'              => '銷售價格',
            'original_price'     => '原價',
            'add_time'           => '創建時間',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => '用戶資訊',
            'user_placeholder'   => '請輸入用戶名/昵稱/手機/郵箱',
            'goods'              => '商品資訊',
            'goods_placeholder'  => '請輸入商品名稱/簡述/SEO資訊',
            'price'              => '銷售價格',
            'original_price'     => '原價',
            'add_time'           => '創建時間',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => '友情連結',
        // 动态表格
        'form_table'                            => [
            'info'                => '名稱',
            'url'                 => 'url地址',
            'describe'            => '描述',
            'is_enable'           => '是否啟用',
            'is_new_window_open'  => '是否新窗口打開',
            'sort'                => '排序',
            'add_time'            => '創建時間',
            'upd_time'            => '更新時間',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '中間導航', 'type' => 'header'],
            ['name' => '底部導航', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => '自定義',
            'article'           => '文章',
            'customview'        => '自定義頁面',
            'goods_category'    => '商品分類',
            'design'            => '頁面設計',
            'plugins'           => '挿件首頁',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => '導航名稱',
            'data_type'           => '導航資料類型',
            'is_show'             => '是否顯示',
            'is_new_window_open'  => '新窗口打開',
            'sort'                => '排序',
            'add_time'            => '創建時間',
            'upd_time'            => '更新時間',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => '訂單id有誤',
            'payment_choice_tips'               => '請選擇支付方式',
        ],
        // 页面基础
        'form_delivery_title'                   => '發貨操作',
        'form_service_title'                    => '服務操作',
        'form_payment_title'                    => '支付操作',
        'form_item_take'                        => '取貨碼',
        'form_item_take_message'                => '請填寫4位數取貨碼',
        'form_item_express_add_name'            => '添加快遞',
        'form_item_express_choice_win_name'     => '選擇快遞',
        'form_item_express_id'                  => '快遞管道',
        'form_item_express_number'              => '快遞單號',
        'form_item_service_name'                => '服務人員姓名',
        'form_item_service_name_message'        => '請填寫服務人員姓名',
        'form_item_service_mobile'              => '服務人員手機',
        'form_item_service_mobile_message'      => '請填寫服務人員手機',
        'form_item_service_time'                => '服務時間',
        'form_item_service_start_time'          => '服務開始時間',
        'form_item_service_start_time_message'  => '請選擇服務開始時間',
        'form_item_service_end_time'            => '服務結束時間',
        'form_item_service_end_time_message'    => '請選擇服務結束時間',
        'form_item_note'                        => '備註資訊',
        'form_item_note_message'                => '備註資訊最多200個字',
        // 地址
        'detail_user_address_title'             => '收貨地址',
        'detail_user_address_name'              => '收件人',
        'detail_user_address_tel'               => '收件電話',
        'detail_user_address_value'             => '詳細地址',
        'detail_user_address_idcard'            => '身份證資訊',
        'detail_user_address_idcard_name'       => '姓名',
        'detail_user_address_idcard_number'     => '號碼',
        'detail_user_address_idcard_pic'        => '照片',
        'detail_take_address_title'             => '取貨地址',
        'detail_take_address_contact'           => '聯系資訊',
        'detail_take_address_value'             => '詳細地址',
        'detail_service_title'                  => '服務資訊',
        'detail_fictitious_title'               => '金鑰資訊',
        // 订单售后
        'detail_aftersale_status'               => '狀態',
        'detail_aftersale_type'                 => '類型',
        'detail_aftersale_price'                => '金額',
        'detail_aftersale_number'               => '數量',
        'detail_aftersale_reason'               => '原因',
        // 商品
        'detail_goods_title'                    => '訂單商品',
        'detail_payment_amount_less_tips'       => '請注意、該訂單支付金額小於總價金額',
        'detail_no_payment_tips'                => '請注意、該訂單還未支付',
        // 动态表格
        'form_table'                            => [
            'goods'               => '基礎資訊',
            'goods_placeholder'   => '請輸入訂單ID/訂單號/商品名稱/型號',
            'user'                => '用戶資訊',
            'user_placeholder'    => '請輸入用戶名/昵稱/手機/郵箱',
            'status'              => '訂單狀態',
            'pay_status'          => '支付狀態',
            'total_price'         => '總價',
            'pay_price'           => '支付金額',
            'price'               => '單價',
            'warehouse_name'      => '出貨倉庫',
            'order_model'         => '訂單模式',
            'client_type'         => '來源',
            'address'             => '地址資訊',
            'service'             => '服務資訊',
            'take'                => '取貨資訊',
            'refund_price'        => '退款金額',
            'returned_quantity'   => '退貨數量',
            'buy_number_count'    => '購買總數',
            'increase_price'      => '新增金額',
            'preferential_price'  => '優惠金額',
            'payment_name'        => '支付方式',
            'user_note'           => '用戶備註',
            'extension'           => '擴展資訊',
            'express'             => '快遞資訊',
            'express_placeholder' => '請輸入快遞單號',
            'aftersale'           => '最新售後',
            'is_comments'         => '用戶是否評論',
            'confirm_time'        => '確認時間',
            'pay_time'            => '支付時間',
            'delivery_time'       => '發貨時間',
            'collect_time'        => '完成時間',
            'cancel_time'         => '取消時間',
            'close_time'          => '關閉時間',
            'add_time'            => '創建時間',
            'upd_time'            => '更新時間',
        ],
        // 动态表格统计字段
        'form_table_stats'                      => [
            'total_price'        => '訂單總額',
            'pay_price'          => '支付總額',
            'buy_number_count'   => '商品總數',
            'refund_price'       => '退款金額',
            'returned_quantity'  => '退貨數量',
        ],
        // 快递表格
        'form_table_express'                    => [
            'name'    => '快遞公司',
            'number'  => '快遞單號',
            'note'    => '快遞備註',
            'time'    => '發貨時間',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => '稽核操作',
        'form_refuse_title'                     => '拒絕操作',
        'form_user_info_title'                  => '用戶資訊',
        'form_apply_info_title'                 => '申請資訊',
        'forn_apply_info_type'                  => '類型',
        'forn_apply_info_price'                 => '金額',
        'forn_apply_info_number'                => '數量',
        'forn_apply_info_reason'                => '原因',
        'forn_apply_info_msg'                   => '說明',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基礎資訊',
            'goods_placeholder'  => '請輸入訂單ID/訂單號/商品名稱/型號',
            'user'               => '用戶資訊',
            'user_placeholder'   => '請輸入用戶名/昵稱/手機/郵箱',
            'status'             => '狀態',
            'type'               => '申請類型',
            'reason'             => '原因',
            'price'              => '退款金額',
            'number'             => '退貨數量',
            'msg'                => '退款說明',
            'refundment'         => '退款類型',
            'voucher'            => '憑證',
            'express_name'       => '快遞公司',
            'express_number'     => '快遞單號',
            'refuse_reason'      => '拒絕原因',
            'apply_time'         => '申請時間',
            'confirm_time'       => '確認時間',
            'delivery_time'      => '退貨時間',
            'audit_time'         => '稽核時間',
            'add_time'           => '創建時間',
            'upd_time'           => '更新時間',
        ],
        // 动态表格统计字段
        'form_table_stats'  => [
            'price'   => '退款總額',
            'number'  => '退貨總數',
        ],
    ],

    // 支付方式
    'payment'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '已安裝', 'type' => 0],
            ['name' => '未安裝', 'type' => 1],
        ],
        'base_nav_title'                        => '支付方式',
        'base_upload_payment_name'              => '導入支付',
        'base_nav_store_payment_name'           => '更多主題下載',
        'upload_top_list_tips'                  => [
            [
                'name'  => '1.類名必須於檔名一致（去除.php），如Alipay.php則取Alipay',
            ],
            [
                'name'  => '2.類必須定義的方法',
                'item'  => [
                    '2.1. Config配寘方法',
                    '2.2. Pay支付方法',
                    '2.3. Respond回檔方法',
                    '2.4. Notify非同步回檔方法（可選、未定義則調用Respond方法）',
                    '2.5. Refund退款方法（可選、未定義則不能發起原路退款）',
                ],
            ],
            [
                'name'  => '3.可自定義輸出內容方法',
                'item'  => [
                    '3.1. SuccessReturn支付成功（可選）',
                    '3.2. ErrorReturn支付失敗（可選）',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS：以上條件不滿足則無法查看挿件，將挿件放入.zip壓縮包中上傳、支持一個壓縮中包含多個支付挿件',
        // 动态表格
        'form_table'                            => [
            'name'            => '名稱',
            'logo'            => 'LOGO',
            'version'         => '版本',
            'apply_version'   => '適用版本',
            'apply_terminal'  => '適用終端',
            'author'          => '作者',
            'desc'            => '描述',
            'enable'          => '是否啟用',
            'open_user'       => '用戶開放',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => '快遞',
    ],

    // 主题管理
    'themeadmin'            => [
        'base_nav_list'                         => [
            ['name' => '當前主題', 'type' => 'index'],
            ['name' => '主題安裝', 'type' => 'upload'],
        ],
        'base_upload_theme_name'                => '導入主題',
        'base_nav_store_theme_name'             => '更多主題下載',
        'list_author_title'                     => '作者',
        'list_version_title'                    => '適用版本',
        'form_theme_upload_tips'                => '上傳一個zip壓縮格式的主題安裝包',
    ],

    // 主题数据
    'themedata'             => [
        'base_nav_title'                        => '主題數據',
        'upload_list_tips'                      => [
            '1.選擇已下載的主題數據zip包',
            '2.導入將自動新增一條數據',
        ],
        // 动态表格
        'form_table'                            => [
            'unique'    => '唯一標識',
            'name'      => '名稱',
            'type'      => '資料類型',
            'theme'     => '主題',
            'view'      => '頁面',
            'is_enable' => '是否啟用',
            'add_time'  => '添加時間',
            'upd_time'  => '更新時間',
        ],
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => '手機用戶中心導航',
        // 动态表格
        'form_table'                            => [
            'name'           => '名稱',
            'platform'       => '所屬平臺',
            'images_url'     => '導航圖標',
            'event_type'     => '事件類型',
            'event_value'    => '事件值',
            'desc'           => '描述',
            'is_enable'      => '是否啟用',
            'is_need_login'  => '是否需登入',
            'sort'           => '排序',
            'add_time'       => '創建時間',
            'upd_time'       => '更新時間',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => '手機首頁導航',
        // 动态表格
        'form_table'                            => [
            'name'           => '名稱',
            'platform'       => '所屬平臺',
            'images'         => '導航圖標',
            'event_type'     => '事件類型',
            'event_value'    => '事件值',
            'is_enable'      => '是否啟用',
            'is_need_login'  => '是否需登入',
            'sort'           => '排序',
            'add_time'       => '創建時間',
            'upd_time'       => '更新時間',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => '支付請求日誌',
        // 动态表格
        'form_table'                            => [
            'user'              => '用戶資訊',
            'user_placeholder'  => '請輸入用戶名/昵稱/手機/郵箱',
            'log_no'            => '支付單號',
            'payment'           => '支付方式',
            'status'            => '狀態',
            'total_price'       => '業務訂單金額',
            'pay_price'         => '支付金額',
            'business_type'     => '業務類型',
            'business_list'     => '業務id/單號',
            'trade_no'          => '支付平臺交易號',
            'buyer_user'        => '支付平臺用戶帳號',
            'subject'           => '訂單名稱',
            'request_params'    => '請求參數',
            'pay_time'          => '支付時間',
            'close_time'        => '關閉時間',
            'add_time'          => '創建時間',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => '支付請求日誌',
        // 动态表格
        'form_table'                            => [
            'business_type'    => '業務類型',
            'request_params'   => '請求參數',
            'response_data'    => '響應數據',
            'business_handle'  => '業務處理結果',
            'request_url'      => '請求url地址',
            'server_port'      => '埠號',
            'server_ip'        => '服務器ip',
            'client_ip'        => '用戶端ip',
            'os'               => '作業系統',
            'browser'          => '瀏覽器',
            'method'           => '請求類型',
            'scheme'           => 'http類型',
            'version'          => 'http版本',
            'client'           => '用戶端詳情',
            'add_time'         => '創建時間',
            'upd_time'         => '更新時間',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => '用戶資訊',
            'user_placeholder'  => '請輸入用戶名/昵稱/手機/郵箱',
            'payment'           => '支付方式',
            'business_type'     => '業務類型',
            'business_id'       => '業務訂單id',
            'trade_no'          => '支付平臺交易號',
            'buyer_user'        => '支付平臺用戶帳號',
            'refundment_text'   => '退款管道',
            'refund_price'      => '退款金額',
            'pay_price'         => '訂單支付金額',
            'msg'               => '描述',
            'request_params'    => '請求參數',
            'return_params'     => '響應參數',
            'add_time_time'     => '退款時間',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => '返回到應用管理>>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => '請先點擊勾勾啟用',
            'save_no_data_tips'                 => '沒有可保存的挿件數據',
        ],
        // 基础导航
        'base_nav_title'                        => '應用',
        'base_upload_application_name'          => '導入應用',
        'base_nav_more_plugins_download_name'   => '更多挿件下載',
        // 基础页面
        'base_search_input_placeholder'         => '請輸入名稱/描述',
        'base_top_tips_one'                     => '清單排序方式[自定義排序->最早安裝]',
        'base_top_tips_two'                     => '可點擊拖動調整挿件調用和展示順序',
        'base_open_setup_title'                 => '開啟設定',
        'data_list_author_title'                => '作者',
        'data_list_author_url_title'            => '主頁',
        'data_list_version_title'               => '版本',
        'data_list_second_domain_title'         => '二級功能變數名稱',
        'data_list_second_domain_tips'          => '請在後臺[系統->系統配寘->安全]中配寘好Cookie有效功能變數名稱主功能變數名稱',
        'uninstall_confirm_tips'                => '卸載可能會遺失挿件基礎配寘數據不可恢復、確認操作嗎？',
        'not_install_divide_title'              => '以下挿件未安裝',
        'delete_plugins_text'                   => '1.僅删除應用',
        'delete_plugins_text_tips'              => '（僅删除應用程式碼，保留應用數據）',
        'delete_plugins_data_text'              => '2.删除應用並删除數據',
        'delete_plugins_data_text_tips'         => '（將删除應用程式碼和應用數據）',
        'delete_plugins_ps_tips'                => 'PS：以下操作後均不可恢復，請謹慎操作！',
        'delete_plugins_button_name'            => '僅删除應用',
        'delete_plugins_data_button_name'       => '删除應用和數據',
        'cancel_delete_plugins_button_name'     => '再考慮一下',
        'more_plugins_store_to_text'            => '去應用商店挑選更多挿件豐富網站>>',
        'no_data_store_to_text'                 => '到應用商店挑選挿件豐富網站>>',
        'plugins_category_title'                => '應用分類',
        'plugins_category_admin_title'          => '分類管理',
        'plugins_menu_control_title'            => '左側選單',
    ],

    // 插件分类
    'pluginscategory'       => [
        'base_nav_title'                        => '應用分類',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => '返回後臺',
        'get_loading_tips'                      => '正在獲取中…',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => '角色',
        'admin_not_modify_tips'                 => '超級管理員默認擁有所有許可權，不可更改。',
        // 动态表格
        'form_table'                            => [
            'name'       => '角色名稱',
            'is_enable'  => '是否啟用',
            'add_time'   => '創建時間',
            'upd_time'   => '更新時間',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => '許可權',
        'top_tips_list'                         => [
            '1. 非專業科技人員請勿操作該頁面數據、操作失誤可能會導致許可權選單錯亂。',
            '2. 許可權選單分為[使用、操作]兩種類型，使用選單一般開啟顯示，操作選單必須隱藏。',
            '3. 如果出現許可權選單錯亂，可以重新覆蓋[ '.MyConfig('database.connections.mysql.prefix').'power ]資料表的資料恢復。',
            '4. [超級管理員、admin帳戶]默認擁有所有許可權，不可更改。',
        ],
        'content_top_tips_list'                 => [
            '1.填寫[控制器名稱和方法名稱]需要對應創建相應的控制器和方法的定義',
            '2.控制器檔案位置[ app/admin/controller ]、該操作僅開發人員使用',
            '3.控制器名稱/方法名稱與自定義url地址、兩者必須填寫一個',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => '快捷導航',
        // 动态表格
        'form_table'                            => [
            'name'         => '名稱',
            'platform'     => '所屬平臺',
            'images'       => '導航圖標',
            'event_type'   => '事件類型',
            'event_value'  => '事件值',
            'is_enable'    => '是否啟用',
            'sort'         => '排序',
            'add_time'     => '創建時間',
            'upd_time'     => '更新時間',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => '地區',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => '篩選價格',
        'top_tips_list'                         => [
            '最小價格0 -最大價格100則是小於100',
            '最小價格1000 -最大價格0則是大於1000',
            '最小價格100 -最大價格500則是大於等於100並且小於500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => '輪播',
        // 动态表格
        'form_table'                            => [
            'name'         => '名稱',
            'describe'     => '描述',
            'platform'     => '所屬平臺',
            'images'       => '圖片',
            'event_type'   => '事件類型',
            'event_value'  => '事件值',
            'is_enable'    => '是否啟用',
            'sort'         => '排序',
            'start_time'   => '開始時間',
            'end_time'     => '結束時間',
            'add_time'     => '創建時間',
            'upd_time'     => '更新時間',
        ],
    ],

    // diy装修
    'diy'                   => [
        'nav_store_diy_name'                    => '更多diy範本下載',
        'nav_apptabbar_name'                    => '底部選單',
        'upload_list_tips'                      => [
            '1. 選擇已下載的diy設計zip包',
            '2. 導入將自動新增一條數據',
        ],
        // 动态表格
        'form_table'                            => [
            'id'            => '數據ID',
            'md5_key'       => '唯一標識',
            'logo'          => 'logo',
            'name'          => '名稱',
            'describe'      => '描述',
            'access_count'  => '訪問次數',
            'is_enable'     => '是否啟用',
            'add_time'      => '創建時間',
            'upd_time'      => '更新時間',
        ],
    ],

    // 附件
    'attachment'                 => [
        'base_nav_title'                        => '附件',
        'category_admin_title'                  => '分類管理',
        // 动态表格
        'form_table'                            => [
            'category_name'  => '分類',
            'type_name'      => '類型',
            'info'           => '附件',
            'original'       => '原檔名',
            'title'          => '新檔名',
            'size'           => '大小',
            'ext'            => '尾碼',
            'url'            => 'url地址 ',
            'hash'           => 'hash',
            'add_time'       => '創建時間',
        ],
    ],

    // 附件分类
    'attachmentcategory'        => [
        'base_nav_title'                        => '附件分類',
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => '用戶資訊',
            'user_placeholder'    => '請輸入用戶名/昵稱/手機/郵箱',
            'type'                => '操作類型',
            'operation_integral'  => '操作積分',
            'original_integral'   => '原始積分',
            'new_integral'        => '最新積分',
            'msg'                 => '操作原因',
            'operation_id'        => '操作人員id',
            'add_time_time'       => '操作時間',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => '用戶資訊',
            'user_placeholder'          => '請輸入用戶名/昵稱/手機/郵箱',
            'type'                      => '消息類型',
            'business_type'             => '業務類型',
            'title'                     => '標題',
            'detail'                    => '詳情',
            'is_read'                   => '是否已讀',
            'user_is_delete_time_text'  => '用戶是否删除',
            'add_time_time'             => '發送時間',
        ],
    ],

    // 短信日志
    'smslog'               => [
        // 动态表格
        'form_table'                            => [
            'platform'        => '短信平臺',
            'status'          => '狀態',
            'mobile'          => '手機',
            'template_value'  => '範本內容',
            'template_var'    => '範本變數',
            'sign_name'       => '簡訊簽名',
            'request_url'     => '請求介面',
            'request_params'  => '請求參數',
            'response_data'   => '響應數據',
            'reason'          => '失敗原因',
            'tsc'             => '耗時(秒)',
            'add_time'        => '添加時間',
            'upd_time'        => '更新時間',
        ],
    ],

    // 邮件日志
    'emaillog'               => [
        // 动态表格
        'form_table'                            => [
            'email'           => '收件郵箱',
            'status'          => '狀態',
            'title'           => '郵件標題',
            'template_value'  => '郵件內容',
            'template_var'    => '郵件變數',
            'reason'          => '失敗原因',
            'smtp_host'       => 'SMTP服務器',
            'smtp_port'       => 'SMTP端口',
            'smtp_name'       => '郵箱用戶名',
            'smtp_account'    => '發信人郵件',
            'smtp_send_name'  => '發件人姓名',
            'tsc'             => '耗時(秒)',
            'add_time'        => '添加時間',
            'upd_time'        => '更新時間',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS：非開發人員請不要隨意執行任何SQL語句，操作可能導致將整個系統資料庫被删除。',
    ],

    // 应用商店
    'store'                 => [
        'to_store_name'                         => '去應用商店挑選挿件',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => '後臺管理系統',
        'remove_cache_title'                    => '清除緩存',
        // 商品参数
        'form_goods_params_config_error_tips'   => '商品參數配寘資訊',
        'form_goods_params_copy_no_tips'        => '請先粘貼配寘資訊',
        'form_goods_params_copy_error_tips'     => '配寘格式錯誤',
        'form_goods_params_type_message'        => '請選擇商品參數展示類型',
        'form_goods_params_params_name'         => '參數名稱',
        'form_goods_params_params_message'      => '請填寫參數名稱',
        'form_goods_params_value_name'          => '參數值',
        'form_goods_params_value_message'       => '請填寫參數值',
        'form_goods_params_move_type_tips'      => '操作類型配寘有誤',
        'form_goods_params_move_top_tips'       => '已到最頂部',
        'form_goods_params_move_bottom_tips'    => '已到最底部',
        'form_goods_params_thead_type_title'    => '展示範圍',
        'form_goods_params_thead_name_title'    => '參數名稱',
        'form_goods_params_thead_value_title'   => '參數值',
        'form_goods_params_row_add_title'       => '添加一行',
        'form_goods_params_list_tips'           => [
            '1.全部（在商品基礎資訊和詳情參數下都展示）',
            '2.詳情（僅在商品詳情參數下都展示）',
            '3.基礎（僅在商品基礎資訊下都展示）',
            '4.快捷操作將會清除原來的數據、重載頁面便可恢復原來的數據（僅保存商品後生效）',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => '系統',
            'item'  => [
                'config_index'                 => '系統配寘',
                'config_store'                 => '商店資訊',
                'config_save'                  => '配寘保存',
                'index_storeaccountsbind'      => '應用商店帳號綁定',
                'index_inspectupgrade'         => '系統更新檢查',
                'index_inspectupgradeconfirm'  => '系統更新確認',
                'index_stats'                  => '首頁統計資料',
                'index_income'                 => '首頁統計資料（收入統計）',
                'shortcutmenu_index'           => '常用功能',
                'shortcutmenu_save'            => '常用功能添加/編輯',
                'shortcutmenu_sort'            => '常用功能排序',
                'shortcutmenu_delete'          => '常用功能删除',
            ]
        ],
        'site_index' => [
            'name'  => '網站',
            'item'  => [
                'site_index'                  => '網站設定',
                'site_save'                   => '網站設定編輯',
                'site_goodssearch'            => '網站設定商品搜索',
                'layout_layoutindexhomesave'  => '首頁佈局管理',
                'sms_index'                   => '簡訊設定',
                'sms_save'                    => '簡訊設定編輯',
                'email_index'                 => '郵箱設置',
                'email_save'                  => '郵箱設置/編輯',
                'email_emailtest'             => '郵件發送測試',
                'seo_index'                   => 'SEO設定',
                'seo_save'                    => 'SEO設定編輯',
                'agreement_index'             => '協定管理',
                'agreement_save'              => '協定設定編輯',
            ]
        ],
        'power_index' => [
            'name'  => '權限',
            'item'  => [
                'admin_index'        => '管理員清單',
                'admin_saveinfo'     => '管理員添加/編輯頁面',
                'admin_save'         => '管理員添加/編輯',
                'admin_delete'       => '管理員删除',
                'admin_detail'       => '管理員詳情',
                'role_index'         => '角色管理',
                'role_saveinfo'      => '角色組添加/編輯頁面',
                'role_save'          => '角色組添加/編輯',
                'role_delete'        => '角色删除',
                'role_statusupdate'  => '角色狀態更新',
                'role_detail'        => '角色詳情',
                'power_index'        => '許可權分配',
                'power_save'         => '許可權添加/編輯',
                'power_statusupdate' => '許可權狀態更新',
                'power_delete'       => '許可權删除',
            ]
        ],
        'user_index' => [
            'name'  => '用戶',
            'item'  => [
                'user_index'            => '用戶列表',
                'user_saveinfo'         => '用戶編輯/添加頁面',
                'user_save'             => '用戶添加/編輯',
                'user_delete'           => '用戶删除',
                'user_detail'           => '用戶詳情',
                'useraddress_index'     => '用戶地址',
                'useraddress_saveinfo'  => '用戶地址編輯頁面',
                'useraddress_save'      => '用戶地址編輯',
                'useraddress_delete'    => '用戶地址删除',
                'useraddress_detail'    => '用戶地址詳情',
            ]
        ],
        'goods_index' => [
            'name'  => '商品',
            'item'  => [
                'goods_index'                       => '商品管理',
                'goods_saveinfo'                    => '商品添加/編輯頁面',
                'goods_save'                        => '商品添加/編輯',
                'goods_delete'                      => '商品删除',
                'goods_statusupdate'                => '商品狀態更新',
                'goods_basetemplate'                => '獲取商品基礎範本',
                'goods_detail'                      => '商品詳情',
                'goodscategory_index'               => '商品分類',
                'goodscategory_save'                => '商品分類添加/編輯',
                'goodscategory_statusupdate'        => '商品分類狀態更新',
                'goodscategory_delete'              => '商品分類删除',
                'goodsparamstemplate_index'         => '商品參數',
                'goodsparamstemplate_delete'        => '商品參數删除',
                'goodsparamstemplate_statusupdate'  => '商品參數狀態更新',
                'goodsparamstemplate_saveinfo'      => '商品參數添加/編輯頁面',
                'goodsparamstemplate_save'          => '商品參數添加/編輯',
                'goodsparamstemplate_detail'        => '商品參數詳情',
                'goodsspectemplate_index'           => '商品規格',
                'goodsspectemplate_delete'          => '商品規格删除',
                'goodsspectemplate_statusupdate'    => '商品規格狀態更新',
                'goodsspectemplate_saveinfo'        => '商品規格添加/編輯頁面',
                'goodsspectemplate_save'            => '商品規格添加/編輯',
                'goodsspectemplate_detail'          => '商品規格詳情',
                'goodscomments_detail'              => '商品評論詳情',
                'goodscomments_index'               => '商品評論',
                'goodscomments_reply'               => '商品評論回復',
                'goodscomments_delete'              => '商品評論删除',
                'goodscomments_statusupdate'        => '商品評論狀態更新',
                'goodscomments_saveinfo'            => '商品評論添加/編輯頁面',
                'goodscomments_save'                => '商品評論添加/編輯',
                'goodsbrowse_index'                 => '商品瀏覽',
                'goodsbrowse_delete'                => '商品瀏覽删除',
                'goodsbrowse_detail'                => '商品瀏覽詳情',
                'goodsfavor_index'                  => '商品收藏',
                'goodsfavor_delete'                 => '商品收藏删除',
                'goodsfavor_detail'                 => '商品收藏詳情',
                'goodscart_index'                   => '商品購物車',
                'goodscart_delete'                  => '商品購物車删除',
                'goodscart_detail'                  => '商品購物車詳情',
            ]
        ],
        'order_index' => [
            'name'  => '訂單',
            'item'  => [
                'order_index'             => '訂單管理',
                'order_delete'            => '訂單删除',
                'order_cancel'            => '訂單取消',
                'order_delivery'          => '訂單發貨',
                'order_collect'           => '訂單收貨',
                'order_pay'               => '訂單支付',
                'order_confirm'           => '訂單確認',
                'order_detail'            => '訂單詳情',
                'order_deliveryinfo'      => '訂單發貨頁面',
                'order_serviceinfo'       => '訂單服務頁面',
                'orderaftersale_index'    => '訂單售後',
                'orderaftersale_delete'   => '訂單售後删除',
                'orderaftersale_cancel'   => '訂單售後取消',
                'orderaftersale_audit'    => '訂單售後稽核',
                'orderaftersale_confirm'  => '訂單售後確認',
                'orderaftersale_refuse'   => '訂單售後拒絕',
                'orderaftersale_detail'   => '訂單售後詳情',
            ]
        ],
        'navigation_index' => [
            'name'  => '網站',
            'item'  => [
                'navigation_index'                 => '導航管理',
                'navigation_save'                  => '導航添加/編輯',
                'navigation_delete'                => '導航删除',
                'navigation_statusupdate'          => '導航狀態更新',
                'customview_index'                 => '自定義頁面',
                'customview_saveinfo'              => '自定義頁面添加/編輯頁面',
                'customview_save'                  => '自定義頁面添加/編輯',
                'customview_delete'                => '自定義頁面删除',
                'customview_statusupdate'          => '自定義頁面狀態更新',
                'customview_detail'                => '自定義頁面詳情',
                'link_index'                       => '友情連結',
                'link_saveinfo'                    => '友情連結添加/編輯頁面',
                'link_save'                        => '友情連結添加/編輯',
                'link_delete'                      => '友情連結删除',
                'link_statusupdate'                => '友情連結狀態更新',
                'link_detail'                      => '友情連結詳情',
                'themeadmin_index'                 => '主題管理',
                'themeadmin_save'                  => '主題管理添加/編輯',
                'themeadmin_upload'                => '主題上傳安裝',
                'themeadmin_delete'                => '主題删除',
                'themeadmin_download'              => '主題下載',
                'themeadmin_market'                => '主題範本市場',
                'themeadmin_storeuploadinfo'       => '主題上傳頁面',
                'themeadmin_storeupload'           => '主題上傳',
                'themedata_index'                  => '主題數據',
                'themedata_saveinfo'               => '主題數據添加/編輯頁面',
                'themedata_save'                   => '主題數據添加/編輯',
                'themedata_upload'                 => '主題數據上傳',
                'themedata_delete'                 => '主題數據删除',
                'themedata_download'               => '主題數據下載',
                'slide_index'                      => '首頁輪播',
                'slide_saveinfo'                   => '輪播添加/編輯頁面',
                'slide_save'                       => '輪播添加/編輯',
                'slide_statusupdate'               => '輪播狀態更新',
                'slide_delete'                     => '輪播删除',
                'slide_detail'                     => '輪播詳情',
                'screeningprice_index'             => '篩選價格',
                'screeningprice_save'              => '篩選價格添加/編輯',
                'screeningprice_delete'            => '篩選價格删除',
                'region_index'                     => '地區管理',
                'region_save'                      => '地區添加/編輯',
                'region_statusupdate'              => '地區狀態更新',
                'region_delete'                    => '地區删除',
                'region_codedata'                  => '獲取地區編號數據',
                'express_index'                    => '快遞管理',
                'express_save'                     => '快遞添加/編輯',
                'express_delete'                   => '快遞删除',
                'payment_index'                    => '支付方式',
                'payment_saveinfo'                 => '支付方式安裝/編輯頁面',
                'payment_save'                     => '支付方式安裝/編輯',
                'payment_delete'                   => '支付方式删除',
                'payment_install'                  => '支付方式安裝',
                'payment_statusupdate'             => '支付方式狀態更新',
                'payment_uninstall'                => '支付方式卸載',
                'payment_upload'                   => '支付方式上傳',
                'payment_market'                   => '支付挿件市場',
                'quicknav_index'                   => '快捷導航',
                'quicknav_saveinfo'                => '快捷導航添加/編輯頁面',
                'quicknav_save'                    => '快捷導航添加/編輯',
                'quicknav_statusupdate'            => '快捷導航狀態更新',
                'quicknav_delete'                  => '快捷導航删除',
                'quicknav_detail'                  => '快捷導航詳情',
                'design_index'                     => '頁面設計',
                'design_saveinfo'                  => '頁面設計添加/編輯頁面',
                'design_save'                      => '頁面設計添加/編輯',
                'design_statusupdate'              => '頁面設計狀態更新',
                'design_upload'                    => '頁面設計導入',
                'design_download'                  => '頁面設計下載',
                'design_sync'                      => '頁面設計同步首頁',
                'design_delete'                    => '頁面設計删除',
                'design_market'                    => '頁面設計範本市場',
                'attachment_index'                 => '附件管理',
                'attachment_detail'                => '附件管理詳情',
                'attachment_saveinfo'              => '附件管理添加/編輯頁面',
                'attachment_save'                  => '附件管理添加/編輯',
                'attachment_delete'                => '附件管理删除',
                'attachmentcategory_index'         => '附件分類',
                'attachmentcategory_save'          => '附件分類添加/編輯',
                'attachmentcategory_statusupdate'  => '附件狀態更新',
                'attachmentcategory_delete'        => '附件分類删除',
            ]
        ],
        'brand_index' => [
            'name'  => '品牌',
            'item'  => [
                'brand_index'           => '品牌管理',
                'brand_saveinfo'        => '品牌添加/編輯頁面',
                'brand_save'            => '品牌添加/編輯',
                'brand_statusupdate'    => '品牌狀態更新',
                'brand_delete'          => '品牌删除',
                'brand_detail'          => '品牌詳情',
                'brandcategory_index'   => '品牌分類',
                'brandcategory_save'    => '品牌分類添加/編輯',
                'brandcategory_delete'  => '品牌分類删除',
            ]
        ],
        'warehouse_index' => [
            'name'  => '倉庫',
            'item'  => [
                'warehouse_index'               => '倉庫管理',
                'warehouse_saveinfo'            => '倉庫添加/編輯頁面',
                'warehouse_save'                => '倉庫添加/編輯',
                'warehouse_delete'              => '倉庫删除',
                'warehouse_statusupdate'        => '倉庫狀態更新',
                'warehouse_detail'              => '倉庫詳情',
                'warehousegoods_index'          => '倉庫商品管理',
                'warehousegoods_detail'         => '倉庫商品詳情',
                'warehousegoods_delete'         => '倉庫商品删除',
                'warehousegoods_statusupdate'   => '倉庫商品狀態更新',
                'warehousegoods_goodssearch'    => '倉庫商品搜索',
                'warehousegoods_goodsadd'       => '倉庫商品搜索添加',
                'warehousegoods_goodsdel'       => '倉庫商品搜索删除',
                'warehousegoods_inventoryinfo'  => '倉庫商品庫存編輯頁面',
                'warehousegoods_inventorysave'  => '倉庫商品庫存編輯',
            ]
        ],
        'app_index' => [
            'name'  => '手機',
            'item'  => [
                'appconfig_index'                  => '基礎配寘',
                'appconfig_save'                   => '基礎配寘保存',
                'appmini_index'                    => '小程式清單',
                'appmini_created'                  => '小程式包生成',
                'appmini_delete'                   => '小程式包删除',
                'appmini_themeupload'              => '小程式主題上傳',
                'appmini_themesave'                => '小程式主題切換',
                'appmini_themedelete'              => '小程式主題切換',
                'appmini_themedownload'            => '小程式主題下載',
                'appmini_config'                   => '小程式配寘',
                'appmini_save'                     => '小程式配寘保存',
                'diy_index'                        => 'DIY裝修',
                'diy_saveinfo'                     => 'DIY裝修添加/編輯頁面',
                'diy_save'                         => 'DIY裝修添加/編輯',
                'diy_statusupdate'                 => 'DIY裝修狀態更新',
                'diy_delete'                       => 'DIY裝修删除',
                'diy_download'                     => 'DIY裝修匯出',
                'diy_upload'                       => 'DIY裝修導入',
                'diy_detail'                       => 'DIY裝修詳情',
                'diy_preview'                      => 'DIY裝修預覽',
                'diy_market'                       => 'DIY裝修範本市場',
                'diy_apptabbar'                    => 'DIY裝修底部選單',
                'diy_storeuploadinfo'              => 'DIY裝修上傳頁面',
                'diy_storeupload'                  => 'DIY裝修上傳',
                'diyapi_init'                      => 'DIY裝修-公共初始化',
                'diyapi_attachmentcategory'        => 'DIY裝修-附件分類',
                'diyapi_attachmentlist'            => 'DIY裝修-附件清單',
                'diyapi_attachmentsave'            => 'DIY裝修-附件保存',
                'diyapi_attachmentdelete'          => 'DIY裝修-附件删除',
                'diyapi_attachmentupload'          => 'DIY裝修-附件上傳',
                'diyapi_attachmentcatch'           => 'DIY裝修-附件遠程下載',
                'diyapi_attachmentscanuploaddata'  => 'DIY裝修-附件掃碼上傳數據',
                'diyapi_attachmentmovecategory'    => 'DIY裝修-附件移動分類',
                'diyapi_attachmentcategorysave'    => 'DIY裝修-附件分類保存',
                'diyapi_attachmentcategorydelete'  => 'DIY裝修-附件分類删除',
                'diyapi_goodslist'                 => 'DIY裝修-商品列表',
                'diyapi_customviewlist'            => 'DIY裝修-自定義頁面清單',
                'diyapi_designlist'                => 'DIY裝修-頁面設計清單',
                'diyapi_articlelist'               => 'DIY裝修-文章列表',
                'diyapi_brandlist'                 => 'DIY裝修-品牌列表',
                'diyapi_diylist'                   => 'DIY裝修-DIY裝修清單',
                'diyapi_diydetail'                 => 'DIY裝修-DIY裝修詳情',
                'diyapi_diysave'                   => 'DIY裝修-DIY裝修保存',
                'diyapi_diyupload'                 => 'DIY裝修-DIY裝修導入',
                'diyapi_diydownload'               => 'DIY裝修-DIY裝修匯出',
                'diyapi_diyinstall'                => 'DIY裝修-DIY裝修範本安裝',
                'diyapi_diymarket'                 => 'DIY裝修-DIY裝修範本市場',
                'diyapi_goodsappointdata'          => 'DIY裝修-商品指定數據',
                'diyapi_goodsautodata'             => 'DIY裝修-商品自動數據',
                'diyapi_articleappointdata'        => 'DIY裝修-文章指定數據',
                'diyapi_articleautodata'           => 'DIY裝修-文章自動數據',
                'diyapi_brandappointdata'          => 'DIY裝修-品牌指定數據',
                'diyapi_brandautodata'             => 'DIY裝修-品牌自動數據',
                'diyapi_userheaddata'              => 'DIY裝修-用戶頭部數據',
                'diyapi_custominit'                => 'DIY裝修-自定義初始化',
                'apphomenav_index'                 => '首頁導航',
                'apphomenav_saveinfo'              => '首頁導航添加/編輯頁面',
                'apphomenav_save'                  => '首頁導航添加/編輯',
                'apphomenav_statusupdate'          => '首頁導航狀態更新',
                'apphomenav_delete'                => '首頁導航删除',
                'apphomenav_detail'                => '首頁導航詳情',
                'appcenternav_index'               => '用戶中心導航',
                'appcenternav_saveinfo'            => '用戶中心導航添加/編輯頁面',
                'appcenternav_save'                => '用戶中心導航添加/編輯',
                'appcenternav_statusupdate'        => '用戶中心導航狀態更新',
                'appcenternav_delete'              => '用戶中心導航删除',
                'appcenternav_detail'              => '用戶中心導航詳情',
            ]
        ],
        'article_index' => [
            'name'  => '文章',
            'item'  => [
                'article_index'           => '文章管理',
                'article_saveinfo'        => '文章添加/編輯頁面',
                'article_save'            => '文章添加/編輯',
                'article_delete'          => '文章删除',
                'article_statusupdate'    => '文章狀態更新',
                'article_detail'          => '文章詳情',
                'articlecategory_index'   => '文章分類',
                'articlecategory_save'    => '文章分類編輯/添加',
                'articlecategory_delete'  => '文章分類删除',
            ]
        ],
        'data_index' => [
            'name'  => '資料',
            'item'  => [
                'message_index'         => '消息管理',
                'message_delete'        => '消息删除',
                'message_detail'        => '消息詳情',
                'paylog_index'          => '支付日誌',
                'paylog_detail'         => '支付日誌詳情',
                'paylog_close'          => '支付日誌關閉',
                'payrequestlog_index'   => '支付請求日誌清單',
                'payrequestlog_detail'  => '支付請求日誌詳情',
                'refundlog_index'       => '退款日誌',
                'refundlog_detail'      => '退款日誌詳情',
                'integrallog_index'     => '積分日誌',
                'integrallog_detail'    => '積分日誌詳情',
                'smslog_index'          => '簡訊日誌',
                'smslog_detail'         => '簡訊日誌詳情',
                'smslog_delete'         => '簡訊日誌删除',
                'smslog_alldelete'      => '簡訊日誌全部删除',
                'emaillog_index'        => '郵件日誌',
                'emaillog_detail'       => '郵件日誌詳情',
                'emaillog_delete'       => '郵件日誌删除',
                'emaillog_alldelete'    => '郵件日誌全部删除',
                'errorlog_index'        => '錯誤日誌',
                'errorlog_detail'       => '錯誤日誌詳情',
                'errorlog_delete'       => '錯誤日誌删除',
                'errorlog_alldelete'    => '錯誤日誌全部删除',
            ]
        ],
        'store_index' => [
            'name'  => '應用',
            'item'  => [
                'pluginsadmin_index'            => '應用管理',
                'plugins_index'                 => '應用調用管理',
                'pluginsadmin_saveinfo'         => '應用添加/編輯頁面',
                'pluginsadmin_save'             => '應用添加/編輯',
                'pluginsadmin_statusupdate'     => '應用狀態更新',
                'pluginsadmin_delete'           => '應用删除',
                'pluginsadmin_upload'           => '應用上傳',
                'pluginsadmin_download'         => '應用打包',
                'pluginsadmin_install'          => '應用安裝',
                'pluginsadmin_uninstall'        => '應用卸載',
                'pluginsadmin_sortsave'         => '應用排序保存',
                'pluginsadmin_market'           => '應用挿件市場',
                'store_index'                   => '應用商店',
                'packageinstall_index'          => '套裝軟體安裝頁面',
                'packageinstall_install'        => '套裝軟體安裝',
                'packageupgrade_upgrade'        => '套裝軟體更新',
                'pluginscategory_index'         => '應用分類',
                'pluginscategory_save'          => '應用分類添加/編輯',
                'pluginscategory_statusupdate'  => '應用分類狀態更新',
                'pluginscategory_delete'        => '應用分類删除',
                'store_market'                  => '應用市場',
            ]
        ],
        'tool_index' => [
            'name'  => '工具',
                'item'                  => [
                'cache_index'           => '緩存管理',
                'cache_statusupdate'    => '網站緩存更新',
                'cache_templateupdate'  => '範本緩存更新',
                'cache_moduleupdate'    => '模塊緩存更新',
                'cache_logdelete'       => '日誌删除',
                'sqlconsole_index'      => 'SQL控制台',
                'sqlconsole_implement'  => 'SQL執行',
            ]
        ],
    ],
];
?>