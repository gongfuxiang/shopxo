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
    // 首页
    'index'                 => [
        // 页面公共
        'page_common'           => [
            'order_transaction_amount_name'     => '订单成交金额',
            'order_trading_trend_name'          => '订单交易',
            'goods_hot_name'                    => '热销商品',
            'goods_hot_tips'                    => '仅显示前13条商品',
            'payment_name'                      => '支付方式',
            'order_region_name'                 => '订单地域分布',
            'order_region_tips'                 => '仅显示10条数据',
            'new_user_name'                     => '新增用户',
            'buy_user_name'                     => '下单用户',
            'upgrade_check_loading_tips'        => '正在获取最新内容、请稍候...',
            'upgrade_version_name'              => '更新版本：',
            'upgrade_date_name'                 => '更新日期：',
        ],
        // 页面基础
        'base_update_system_title'              => '系统更新',
        'base_update_button_title'              => '立即更新',
        'base_item_base_stats_title'            => '商城统计',
        'base_item_base_stats_tips'             => '时间筛选仅对总数有效',
        'base_item_user_title'                  => '用户总量',
        'base_item_order_number_title'          => '订单总量',
        'base_item_order_complete_number_title' => '成交总量',
        'base_item_order_complete_title'        => '订单总计',
        'base_item_last_month_title'            => '上月',
        'base_item_same_month_title'            => '当月',
        'base_item_yesterday_title'             => '昨日',
        'base_item_today_title'                 => '今日',
        'base_item_order_profit_title'          => '订单成交金额',
        'base_item_order_trading_title'         => '订单交易',
        'base_item_order_tips'                  => '所有订单',
        'base_item_hot_sales_goods_title'       => '热销商品',
        'base_item_hot_sales_goods_tips'        => '不含取消关闭的订单',
        'base_item_payment_type_title'          => '支付方式',
        'base_item_map_whole_country_title'     => '订单地域分布',
        'base_item_map_whole_country_tips'      => '不含取消关闭的订单、默认维度（省）',
        'base_item_map_whole_country_province'  => '省',
        'base_item_map_whole_country_city'      => '市',
        'base_item_map_whole_country_county'    => '区/县',
        'base_item_new_user_title'              => '新增用户',
        'base_item_buy_user_title'              => '下单用户',
        'system_info_title'                     => '系统信息',
        'system_ver_title'                      => '软件版本',
        'system_os_ver_title'                   => '操作系统',
        'system_php_ver_title'                  => 'PHP版本',
        'system_mysql_ver_title'                => 'MySQL版本',
        'system_server_ver_title'               => '服务器端信息',
        'system_host_title'                     => '当前域名',
        'development_team_title'                => '开发团队',
        'development_team_website_title'        => '公司官网',
        'development_team_website_value'        => '上海纵之格科技有限公司',
        'development_team_support_title'        => '技术支持',
        'development_team_support_value'        => 'ShopXO企业级电商系统提供商',
        'development_team_ask_title'            => '交流提问',
        'development_team_ask_value'            => 'ShopXO交流提问',
        'development_team_agreement_title'      => '开源协议',
        'development_team_agreement_value'      => '查看开源协议',
        'development_team_update_log_title'     => '更新日志',
        'development_team_update_log_value'     => '查看更新日志',
        'development_team_members_title'        => '研发成员',
        'development_team_members_value'        => [
            ['name' => '龚哥哥', 'url' => 'http://gong.gg/']
        ],
    ],

    // 用户
    'user'                  => [
        'base_nav_title'                        => '用户',
        // 动态表格
        'form_table'                            => [
            'id'                    => '用户ID',
            'number_code'           => '会员码',
            'system_type'           => '系统类型',
            'platform'              => '所属平台',
            'avatar'                => '头像',
            'username'              => '用户名',
            'nickname'              => '昵称',
            'mobile'                => '手机',
            'email'                 => '邮箱',
            'gender_name'           => '性别',
            'status_name'           => '状态',
            'province'              => '所在省',
            'city'                  => '所在市',
            'county'                => '所在区/县',
            'address'               => '详细地址',
            'birthday'              => '生日',
            'integral'              => '可用积分',
            'locking_integral'      => '锁定积分',
            'referrer'              => '邀请用户',
            'referrer_placeholder'  => '请输入邀请用户名/昵称/手机/邮箱',
            'add_time'              => '注册时间',
            'upd_time'              => '更新时间',
        ],
    ],

    // 用户地址
    'useraddress'      => [
        'base_nav_title'                        => '用户地址',
        // 详情
        'detail_user_address_idcard_name'       => '姓名',
        'detail_user_address_idcard_number'     => '号码',
        'detail_user_address_idcard_pic'        => '照片',
        // 动态表格
        'form_table'                            => [
            'user'               => '用户信息',
            'user_placeholder'   => '请输入用户名/昵称/手机/邮箱',
            'alias'              => '别名',
            'name'               => '联系人',
            'tel'                => '联系电话',
            'province_name'      => '所属省',
            'city_name'          => '所属市',
            'county_name'        => '所属区/县',
            'address'            => '详细地址',
            'address_last_code'  => '地址最后一级编码',
            'position'           => '经纬度',
            'idcard_info'        => '身份证信息',
            'is_default'         => '是否默认',
            'add_time'           => '创建时间',
            'upd_time'           => '更新时间',
        ],
    ],

    // 站点设置
    'site'                  => [
        // 页面公共
        'page_common'           => [
            'remove_confirm_tips'               => '移除后保存生效、确认继续吗？',
            'address_no_data'                   => '地址数据为空',
            'address_not_exist'                 => '地址不存在',
            'address_logo_message'              => '请上传logo图片',
        ],
        // 主导航
        'base_nav_list'                       => [
            ['name' => '基础配置', 'type' => 'base'],
            ['name' => '网站设置', 'type' => 'siteset'],
            ['name' => '站点类型', 'type' => 'sitetype'],
            ['name' => '用户注册', 'type' => 'register'],
            ['name' => '用户登录', 'type' => 'login'],
            ['name' => '密码找回', 'type' => 'forgetpwd'],
            ['name' => '验证码', 'type' => 'verify'],
            ['name' => '订单售后', 'type' => 'orderaftersale'],
            ['name' => '附件', 'type' => 'attachment'],
            ['name' => '缓存', 'type' => 'cache'],
            ['name' => '扩展项', 'type' => 'extends'],
        ],
        // 网站设置导航
        'siteset_nav_list'                      => [
            ['name' => '首页', 'type' => 'index'],
            ['name' => '搜索', 'type' => 'search'],
            ['name' => '订单', 'type' => 'order'],
            ['name' => '商品', 'type' => 'goods'],
            ['name' => '购物车', 'type' => 'cart'],
            ['name' => '扩展', 'type' => 'extends'],
        ],
        // 页面基础
        'base_item_site_status_title'           => '站点状态',
        'base_item_site_domain_title'           => '站点域名地址',
        'base_item_site_filing_title'           => '备案信息',
        'base_item_site_other_title'            => '其它',
        'base_item_session_cache_title'         => 'Session缓存配置',
        'base_item_data_cache_title'            => '数据缓存配置',
        'base_item_redis_cache_title'           => 'redis缓存配置',
        'base_item_crontab_config_title'        => '定时脚本配置',
        'base_item_regex_config_title'          => '正则配置',
        'base_item_quick_nav_title'             => '快捷导航',
        'base_item_user_base_title'             => '用户基础',
        'base_item_user_address_title'          => '用户地址',
        'base_item_multilingual_title'          => '多语言',
        'base_item_site_auto_mode_title'        => '自动模式',
        'base_item_site_manual_mode_title'      => '手动模式',
        'base_item_default_payment_title'       => '默认支付方式',
        'base_item_display_type_title'          => '展示型',
        'base_item_self_extraction_title'       => '自提点',
        'base_item_fictitious_title'            => '虚拟销售',
        'choice_upload_logo_title'              => '选择logo',
        'add_goods_title'                       => '商品添加',
        'add_self_extractio_address_title'      => '添加地址',
        'site_domain_tips_list'                 => [
            '1. 站点域名未设置则使用当前站点域名域名地址[ '.__MY_DOMAIN__.' ]',
            '2. 附件和静态地址未设置则使用当前站点静态域名地址[ '.__MY_PUBLIC_URL__.' ]',
            '3. 如服务器端不是以public设为根目录的、则这里配置【附件cdn域名、css/js静态文件cdn域名】需要后面再加public、如：'.__MY_PUBLIC_URL__.'public/',
            '4. 在命令行模式下运行项目，该区域地址必须配置、否则项目中一些地址会缺失域名信息',
            '5. 请勿乱配置、错误地址会导致网站无法访问（地址配置以http开头）、如果自己站的配置了https则以https开头',
        ],
        'site_cache_tips_list'                  => [
            '1. 默认使用的文件缓存、使用Redis缓存PHP需要先安装Redis扩展',
            '2. 请确保Redis服务稳定性（Session使用缓存后、服务不稳定可能导致后台也无法登录）',
            '3. 如遇到Redis服务异常无法登录管理后台、修改配置文件[ config ]目录下[ session.php,cache.php ]文件',
        ],
        'goods_tips_list'                       => [
            '1. WEB端默认展示3级，最低1级、最高3级(如设置为0级则默认为3级)',
            '2. 手机端默认展示0级(商品列表模式)、最低0级、最高3级(1~3为分类展示模式)',
            '3. 层级不一样、前端分类页样式也会不一样',
        ],
        'goods_auto_mode_max_count_tips_list'   => [
            '1. 配置每个楼层最多展示多少个商品',
            '2. 不建议将数量修改的太大、会导致PC端左侧空白区域太大',
        ],
        'goods_auto_mode_order_by_tips_list'    => [
            '综合为：热度->销量->最新 进行 降序(desc)排序',
        ],
        'goods_manual_mode_max_tips_list'       => [
            '1. 可点击商品标题拖拽排序、按照顺序展示',
            '2. 不建议添加很多商品、会导致PC端左侧空白区域太大',
        ],
        'user_unique_system_type_model_tips_list'=> [
            '1. 默认以【用户名、手机、邮箱】作为用户唯一',
            '2. 开启则加入【系统标识】并行作为用户唯一',
        ],
        'extends_crontab_tips'                  => '建议将脚本地址添加到linux定时任务定时请求即可（结果 sucs:0, fail:0 冒号后面则是处理的数据条数，sucs成功，fali失败）',
        'left_images_random_tips'               => '左侧图片最多可上传3张图片、每次随机展示其中一张',
        'background_color_tips'                 => '可自定义背景图片、默认底灰色',
        'site_setup_layout_tips'                => '拖拽模式需要自行进入首页设计页面、请先保存选中配置后再',
        'site_setup_layout_button_name'         => '设计页面',
        'site_setup_goods_category_tips'        => '如需更多楼层展示，请先到 / 商品管理->商品分类、一级分类设置首页推荐',
        'site_setup_goods_category_no_data_tips'=> '暂无数据，请先到 / 商品管理->商品分类、一级分类设置首页推荐',
        'site_setup_order_default_payment_tips' => '可以设置不同平台对应的默认支付方式、请先在[ 网站管理 -> 支付方式 ]中安装好支付插件启用并对用户开放',
        'site_setup_choice_payment_message'     => '请选择{:name}默认支付方式',
        'sitetype_top_tips_list'                => [
            '1. 快递：常规电商流程，用户选择收货地址下单支付 -> 商家派发给第三方物流发货 -> 确认收货 -> 订单完成',
            '2. 同城：同城骑手或自行配送，用户选择收货地址下单支付 -> 商家派发给同城第三方配送或自行配送 -> 确认收货 -> 订单完成',
            '3. 展示：仅展示产品，可发起咨询（不能下单）',
            '4. 自提：下单时选择自提货物地址，用户下单支付 -> 确认提货 -> 订单完成',
            '5. 虚拟：常规电商流程，用户下单支付 -> 自动发货 -> 确认提货 -> 订单完成',
        ],
        // 添加自提地址表单
        'form_take_address_title'                  => '自提地址',
        'form_take_address_logo'                   => 'LOGO',
        'form_take_address_logo_tips'              => '建议300*300px',
        'form_take_address_alias'                  => '别名',
        'form_take_address_alias_message'          => '别名格式最多16个字符',
        'form_take_address_name'                   => '联系人',
        'form_take_address_name_message'           => '联系人格式2~16个字符之间',
        'form_take_address_tel'                    => '联系电话',
        'form_take_address_tel_message'            => '请填写联系电话',
        'form_take_address_address'                => '详细地址',
        'form_take_address_address_message'        => '详细地址格式1~80个字符之间',
        // 域名绑定语言
        'form_domain_multilingual_domain_name'     => '域名',
        'form_domain_multilingual_domain_message'  => '请填写域名',
        'form_domain_multilingual_select_message'  => '请选择域名对应语言',
        'form_domain_multilingual_add_title'       => '添加域名',
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => '后台登录',
        'admin_login_info_bg_images_list_tips'  => [
            '1. 背景图片位于[ public/static/admin/default/images/login ]目录下',
            '2. 背景图片命名规则(1~50)、如 1.png',
        ],
        'map_type_tips'                         => '由于每一家的地图标准不一样、请勿随意切换地图、会导致地图坐标标注不准确的情况。',
        'apply_map_baidu_name'                  => '请到百度地图开放平台申请',
        'apply_map_amap_name'                   => '请到高德地图开放平台申请',
        'apply_map_tencent_name'                => '请到腾讯地图开放平台申请',
        'apply_map_tianditu_name'               => '请到天地图开放平台申请',
        'cookie_domain_list_tips'               => [
            '1. 默认空、则仅对当前访问域名有效',
            '2. 如需要二级域名也共享cookie则填写顶级域名、如：baidu.com',
        ],
    ],

    // 品牌
    'brand'                 => [
        'base_nav_title'                        => '品牌',
        // 动态表格
        'form_table'                            => [
            'id'                   => '品牌ID',
            'name'                 => '名称',
            'describe'             => '描述',
            'logo'                 => 'LOGO',
            'url'                  => '官网地址',
            'brand_category_text'  => '品牌分类',
            'is_enable'            => '是否启用',
            'sort'                 => '排序',
            'add_time'             => '创建时间',
            'upd_time'             => '更新时间',
        ],
    ],

    // 品牌分类
    'brandcategory'         => [
        'base_nav_title'                        => '品牌分类',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => '文章',
        'detail_content_title'                  => '详情内容',
        'detail_images_title'                   => '详情图片',
        // 动态表格
        'form_table'                            => [
            'id'                     => '文章ID',
            'cover'                  => '封面',
            'info'                   => '标题',
            'describe'               => '描述',
            'article_category_name'  => '分类',
            'is_enable'              => '是否启用',
            'is_home_recommended'    => '首页推荐',
            'jump_url'               => '跳转url地址',
            'images_count'           => '图片数量',
            'access_count'           => '访问次数',
            'add_time'               => '创建时间',
            'upd_time'               => '更新时间',
        ],
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => '文章分类',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => '自定义页面',
        'detail_content_title'                  => '详情内容',
        'detail_images_title'                   => '详情图片',
        'save_view_tips'                        => '请先保存再预览效果',
        // 动态表格
        'form_table'                            => [
            'id'              => '数据ID',
            'logo'            => 'logo',
            'name'            => '名称',
            'is_enable'       => '是否启用',
            'is_header'       => '是否头部',
            'is_footer'       => '是否尾部',
            'is_full_screen'  => '是否满屏',
            'images_count'    => '图片数量',
            'access_count'    => '访问次数',
            'add_time'        => '创建时间',
            'upd_time'        => '更新时间',
        ],
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => '更多设计模板下载',
        'upload_list_tips'                      => [
            '1. 选择已下载的页面设计zip包',
            '2. 导入将自动新增一条数据',
        ],
        'operate_sync_tips'                     => '数据同步到首页拖拽可视化中、之后再修改数据不受影响、但是不要删除相关附件',
        // 动态表格
        'form_table'                            => [
            'id'                => '数据ID',
            'logo'              => 'logo',
            'name'              => '名称',
            'access_count'      => '访问次数',
            'is_enable'         => '是否启用',
            'is_header'         => '是否含头部',
            'is_footer'         => '是否含尾部',
            'seo_title'         => 'SEO标题',
            'seo_keywords'      => 'SEO关键字',
            'seo_desc'          => 'SEO描述',
            'add_time'          => '创建时间',
            'upd_time'          => '更新时间',
        ],
    ],

    // 仓库管理
    'warehouse'             => [
        'base_nav_title'                        => '仓库',
        'top_tips_list'                         => [
            '1. 权重数值越大代表权重越高、扣除库存按照权重依次扣除）',
            '2. 仓库仅软删除、删除后将不可用、仅数据库中保留数据）可以自行删除关联的商品数据',
            '3. 仓库停用和删除、关联的商品库存会立即释放',
        ],
        // 动态表格
        'form_table'                            => [
            'info'           => '名称/别名',
            'level'          => '权重',
            'is_enable'      => '是否启用',
            'contacts_name'  => '联系人',
            'contacts_tel'   => '联系电话',
            'province_name'  => '所在省',
            'city_name'      => '所在市',
            'county_name'    => '所在区/县',
            'address'        => '详细地址',
            'position'       => '经纬度',
            'add_time'       => '创建时间',
            'upd_time'       => '更新时间',
        ],
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => '请选择仓库',
        ],
        // 基础
        'add_goods_title'                       => '商品添加',
        'no_spec_data_tips'                     => '无规格数据',
        'batch_setup_inventory_placeholder'     => '批量设置的值',
        'base_spec_inventory_title'             => '规格库存',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基础信息',
            'goods_placeholder'  => '请输入商品名称/型号',
            'warehouse_name'     => '仓库',
            'is_enable'          => '是否启用',
            'inventory'          => '总库存',
            'spec_inventory'     => '规格库存',
            'add_time'           => '创建时间',
            'upd_time'           => '更新时间',
        ],
    ],

    // 管理员
    'admin'                 => [
        'admin_no_data_tips'                    => '管理员信息不存在',
        // 列表
        'top_tips_list'                         => [
            '1. admin 账户默认拥有所有权限，不可更改。',
            '2. admin 账户不可更改，但是可以在数据表中修改( '.MyConfig('database.connections.mysql.prefix').'admin ) 字段 username',
        ],
        'base_nav_title'                        => '管理员',
        // 登录
        'login_type_username_title'             => '账号密码',
        'login_type_mobile_title'               => '手机验证码',
        'login_type_email_title'                => '邮箱验证码',
        'login_close_tips'                      => '暂时关闭了登录',
        'login_welcome_title'                   => '欢迎登录',
        // 忘记密码
        'form_forget_password_name'             => '忘记密码?',
        'form_forget_password_tips'             => '请联系管理员重置密码',
        // 动态表格
        'form_table'                            => [
            'username'              => '管理员',
            'status'                => '状态',
            'gender'                => '性别',
            'mobile'                => '手机',
            'email'                 => '邮箱',
            'role_name'             => '角色组',
            'login_total'           => '登录次数',
            'login_time'            => '最后登录时间',
            'add_time'              => '创建时间',
            'upd_time'              => '更新时间',
        ],
    ],

    // 协议
    'agreement'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '用户注册协议', 'type' => 'register'],
            ['name' => '用户隐私政策', 'type' => 'privacy'],
            ['name' => '账号注销协议', 'type' => 'logout']
        ],
        'top_tips'          => '前端访问协议地址增加参数 is_content=1 则仅展示纯协议内容',
        'view_detail_name'                      => '查看详情',
    ],

    // 手机配置
    'appconfig'             => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '基础配置', 'type' => 'index'],
            ['name' => 'APP/小程序', 'type' => 'app'],
        ],
        'home_diy_template_title'               => '首页DIY模板',
        'online_service_title'                  => '在线客服',
        'user_base_popup_title'                 => '用户基础信息弹窗提示',
        'user_onekey_bind_mobile_tips_list'     => [
            '1. 获取当前小程序平台账户或者本本机的手机号码一键登录绑定，目前仅支持【微信小程序、百度小程序、头条小程序】',
            '2. 依赖需要开启《强制绑定手机》有效',
            '3. 部分小程序平台可能需要申请权限、请根据小程序平台要求申请后再对应开启',
        ],
        'user_address_platform_import_tips_list'=> [
            '1. 获取当前小程序平台app账户的收货地址，目前仅支持【小程序】',
            '2. 确认导入后直接添加为系统用户收货地址',
            '3. 部分小程序平台可能需要申请权限、请根据小程序平台要求申请后再对应开启',
        ],
        'user_base_popup_top_tips_list'         => [
            '1. 目前仅微信小程序平台自动授权登录后无用户昵称和头像信息',
        ],
        'online_service_top_tips_list'          => [
            '1. 自定义客服http协议采用webview方式打开',
            '2. 客服优先级顺序【 客服系统 -> 自定义客服 -> 企业微信客服(仅app+h5+微信小程序生效) -> 各端平台客服 -> 电话客服 】',
        ],
        'home_diy_template_tips'                => '不选择DIY模板则默认跟随统一的首页配置',
    ],

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '当前主题', 'type' => 'index'],
            ['name' => '主题安装', 'type' => 'upload'],
            ['name' => '源码包下载', 'type' => 'package'],
        ],
        'nav_store_theme_name'                  => '更多主题下载',
        'nav_theme_download_name'               => '查看小程序打包教程',
        'nav_theme_download_tips'               => '手机端主题采用uniapp开发（支持多端小程序、H5、APP）',
        'form_alipay_extend_title'              => '客服配置',
        'form_alipay_extend_tips'               => 'PS：如【APP/小程序】中开启（开启在线客服），则以下配置必填 [企业编码] 和 [聊天窗编码]',
        'form_theme_upload_tips'                => '上传一个zip压缩格式的安装包',
        'list_no_data_tips'                     => '没有相关主题包',
        'list_author_title'                     => '作者',
        'list_version_title'                    => '适用版本',
        'package_generate_tips'                 => '生成时间比较长，请不要关闭浏览器窗口！',
        // 动态表格
        'form_table'                            => [
            'name'  => '包名',
            'size'  => '大小',
            'url'   => '下载地址',
            'time'  => '创建时间',
        ],
    ],

    // 短信设置
    'sms'                   => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '短信设置', 'type' => 'index'],
            ['name' => '消息模板', 'type' => 'message'],
        ],
        'top_tips'                              => '阿里云短信管理地址',
        'top_to_aliyun_tips'                    => '点击去阿里云购买短信',
        'base_item_admin_title'                 => '后台',
        'base_item_index_title'                 => '前端',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '邮箱设置', 'type' => 'index'],
            ['name' => '消息模板', 'type' => 'message'],
        ],
        'top_tips'                              => '由于不同邮箱平台存在一些差异、配置也有所不同、具体以邮箱平台配置教程为准',
        // 基础
        'test_title'                            => '测试',
        'test_content'                          => '邮件配置-发送测试内容',
        'base_item_admin_title'                 => '后台',
        'base_item_index_title'                 => '前端',
        // 表单
        'form_item_test'                        => '测试接收的邮件地址',
        'form_item_test_tips'                   => '请先保存配置后，再进行测试',
        'form_item_test_button_title'           => '测试',
    ],

    // seo设置
    'seo'                   => [
        'top_tips'                              => '根据服务器环境[Nginx、Apache、IIS]不同配置相应的伪静态规则',
    ],

    // 商品
    'goods'                 => [
        'base_nav_title'                        => '商品',
        'goods_nav_list'                        => [
            'base'            => ['name' => '基础信息', 'type'=>'base'],
            'spec'            => ['name' => '商品规格', 'type'=>'spec'],
            'spec_images'     => ['name' => '规格图片', 'type'=>'spec_images'],
            'parameters'      => ['name' => '商品参数', 'type'=>'parameters'],
            'photo'           => ['name' => '商品相册', 'type'=>'photo'],
            'video'           => ['name' => '商品视频', 'type'=>'video'],
            'app'             => ['name' => '手机详情', 'type'=>'app'],
            'web'             => ['name' => '电脑详情', 'type'=>'web'],
            'fictitious'      => ['name' => '虚拟信息', 'type'=>'fictitious'],
            'extends'         => ['name' => '扩展数据', 'type'=>'extends'],
            'seo'             => ['name' => 'SEO信息', 'type'=>'seo'],
        ],
        'delete_only_goods_text'                => '仅商品',
        'delete_goods_and_images_text'          => '商品和图片',
        // 动态表格
        'form_table'                            => [
            'id'                      => '商品ID',
            'info'                    => '商品信息',
            'info_placeholder'        => '请输入商品名称/简述/编码/条码/SEO信息',
            'category_text'           => '商品分类',
            'brand_name'              => '品牌',
            'price'                   => '销售价格',
            'original_price'          => '原价',
            'inventory'               => '库存总量',
            'is_shelves'              => '上下架',
            'is_deduction_inventory'  => '扣减库存',
            'site_type'               => '商品类型',
            'model'                   => '商品型号',
            'place_origin_name'       => '生产地',
            'give_integral'           => '购买赠送积分比例',
            'buy_min_number'          => '单次最低起购数量',
            'buy_max_number'          => '单次最大购买数量',
            'sort_level'              => '排序权重',
            'sales_count'             => '销量',
            'access_count'            => '访问次数',
            'add_time'                => '创建时间',
            'upd_time'                => '更新时间',
        ],
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => '商品分类',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => '商品评论',
        // 动态表格
        'form_table'                            => [
            'user'               => '用户信息',
            'user_placeholder'   => '请输入用户名/昵称/手机/邮箱',
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

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => '商品参数',
        // 动态表格
        'form_table'                            => [
            'category_id'   => '商品分类',
            'name'          => '名称',
            'is_enable'     => '是否启用',
            'config_count'  => '参数数量',
            'add_time'      => '创建时间',
            'upd_time'      => '更新时间',
        ],
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 动态表格
        'form_table'                            => [
            'category_id'  => '商品分类',
            'name'         => '名称',
            'is_enable'    => '是否启用',
            'content'      => '规格值',
            'add_time'     => '创建时间',
            'upd_time'     => '更新时间',
        ],
    ],

    // 商品浏览
    'goodsbrowse'           => [
        // 动态表格
        'form_table'                            => [
            'user'               => '用户信息',
            'user_placeholder'   => '请输入用户名/昵称/手机/邮箱',
            'goods'              => '商品信息',
            'goods_placeholder'  => '请输入商品名称/简述/SEO信息',
            'price'              => '销售价格',
            'original_price'     => '原价',
            'add_time'           => '创建时间',
        ],
    ],

    // 商品购物车
    'goodscart'             => [
        // 动态表格
        'form_table'                            => [
            'user'               => '用户信息',
            'user_placeholder'   => '请输入用户名/昵称/手机/邮箱',
            'goods'              => '商品信息',
            'goods_placeholder'  => '请输入商品名称/简述/SEO信息',
            'price'              => '销售价格',
            'original_price'     => '原价',
            'add_time'           => '创建时间',
        ],
    ],

    // 商品收藏
    'goodsfavor'            => [
        // 动态表格
        'form_table'                            => [
            'user'               => '用户信息',
            'user_placeholder'   => '请输入用户名/昵称/手机/邮箱',
            'goods'              => '商品信息',
            'goods_placeholder'  => '请输入商品名称/简述/SEO信息',
            'price'              => '销售价格',
            'original_price'     => '原价',
            'add_time'           => '创建时间',
        ],
    ],

    // 友情链接
    'link'                  => [
        'base_nav_title'                        => '友情链接',
        // 动态表格
        'form_table'                            => [
            'info'                => '名称',
            'url'                 => 'url地址',
            'describe'            => '描述',
            'is_enable'           => '是否启用',
            'is_new_window_open'  => '是否新窗口打开',
            'sort'                => '排序',
            'add_time'            => '创建时间',
            'upd_time'            => '更新时间',
        ],
    ],

    // 导航管理
    'navigation'            => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '中间导航', 'type' => 'header'],
            ['name' => '底部导航', 'type' => 'footer'],
        ],
        // 添加类型列表
        'base_add_type_list'                    => [
            'custom'            => '自定义',
            'article'           => '文章',
            'customview'        => '自定义页面',
            'goods_category'    => '商品分类',
            'design'            => '页面设计',
            'plugins'           => '插件首页',
        ],
        // 动态表格
        'form_table'                            => [
            'info'                => '导航名称',
            'data_type'           => '导航数据类型',
            'is_show'             => '是否显示',
            'is_new_window_open'  => '新窗口打开',
            'sort'                => '排序',
            'add_time'            => '创建时间',
            'upd_time'            => '更新时间',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => '订单id有误',
            'payment_choice_tips'               => '请选择支付方式',
        ],
        // 页面基础
        'form_delivery_title'                   => '发货操作',
        'form_service_title'                    => '服务操作',
        'form_payment_title'                    => '支付操作',
        'form_item_take'                        => '取货码',
        'form_item_take_message'                => '请填写4位数取货码',
        'form_item_express_add_name'            => '添加快递',
        'form_item_express_choice_win_name'     => '选择快递',
        'form_item_express_id'                  => '快递方式',
        'form_item_express_number'              => '快递单号',
        'form_item_service_name'                => '服务人员姓名',
        'form_item_service_name_message'        => '请填写服务人员姓名',
        'form_item_service_mobile'              => '服务人员手机',
        'form_item_service_mobile_message'      => '请填写服务人员手机',
        'form_item_service_time'                => '服务时间',
        'form_item_service_start_time'          => '服务开始时间',
        'form_item_service_start_time_message'  => '请选择服务开始时间',
        'form_item_service_end_time'            => '服务结束时间',
        'form_item_service_end_time_message'    => '请选择服务结束时间',
        'form_item_note'                        => '备注信息',
        'form_item_note_message'                => '备注信息最多200个字符',
        // 地址
        'detail_user_address_title'             => '收货地址',
        'detail_user_address_name'              => '收件人',
        'detail_user_address_tel'               => '收件电话',
        'detail_user_address_value'             => '详细地址',
        'detail_user_address_idcard'            => '身份证信息',
        'detail_user_address_idcard_name'       => '姓名',
        'detail_user_address_idcard_number'     => '号码',
        'detail_user_address_idcard_pic'        => '照片',
        'detail_take_address_title'             => '取货地址',
        'detail_take_address_contact'           => '联系信息',
        'detail_take_address_value'             => '详细地址',
        'detail_service_title'                  => '服务信息',
        'detail_fictitious_title'               => '密钥信息',
        // 订单售后
        'detail_aftersale_status'               => '状态',
        'detail_aftersale_type'                 => '类型',
        'detail_aftersale_price'                => '金额',
        'detail_aftersale_number'               => '数量',
        'detail_aftersale_reason'               => '原因',
        // 商品
        'detail_goods_title'                    => '订单商品',
        'detail_payment_amount_less_tips'       => '请注意、该订单支付金额小于总价金额',
        'detail_no_payment_tips'                => '请注意、该订单还未支付',
        // 动态表格
        'form_table'                            => [
            'goods'               => '基础信息',
            'goods_placeholder'   => '请输入订单ID/订单号/商品名称/型号',
            'user'                => '用户信息',
            'user_placeholder'    => '请输入用户名/昵称/手机/邮箱',
            'status'              => '订单状态',
            'pay_status'          => '支付状态',
            'total_price'         => '总价',
            'pay_price'           => '支付金额',
            'price'               => '单价',
            'warehouse_name'      => '出货仓库',
            'order_model'         => '订单模式',
            'client_type'         => '来源',
            'address'             => '地址信息',
            'service'             => '服务信息',
            'take'                => '取货信息',
            'refund_price'        => '退款金额',
            'returned_quantity'   => '退货数量',
            'buy_number_count'    => '购买总数',
            'increase_price'      => '增加金额',
            'preferential_price'  => '优惠金额',
            'payment_name'        => '支付方式',
            'user_note'           => '用户备注',
            'extension'           => '扩展信息',
            'express'             => '快递信息',
            'express_placeholder' => '请输入快递单号',
            'aftersale'           => '最新售后',
            'is_comments'         => '用户是否评论',
            'confirm_time'        => '确认时间',
            'pay_time'            => '支付时间',
            'delivery_time'       => '发货时间',
            'collect_time'        => '完成时间',
            'cancel_time'         => '取消时间',
            'close_time'          => '关闭时间',
            'add_time'            => '创建时间',
            'upd_time'            => '更新时间',
        ],
        // 动态表格统计字段
        'form_table_stats'                      => [
            'total_price'        => '订单总额',
            'pay_price'          => '支付总额',
            'buy_number_count'   => '商品总数',
            'refund_price'       => '退款金额',
            'returned_quantity'  => '退货数量',
        ],
        // 快递表格
        'form_table_express'                    => [
            'name'    => '快递公司',
            'number'  => '快递单号',
            'note'    => '快递备注',
            'time'    => '发货时间',
        ],
    ],

    // 订单售后
    'orderaftersale'        => [
        'form_audit_title'                      => '审核操作',
        'form_refuse_title'                     => '拒绝操作',
        'form_user_info_title'                  => '用户信息',
        'form_apply_info_title'                 => '申请信息',
        'forn_apply_info_type'                  => '类型',
        'forn_apply_info_price'                 => '金额',
        'forn_apply_info_number'                => '数量',
        'forn_apply_info_reason'                => '原因',
        'forn_apply_info_msg'                   => '说明',
        // 动态表格
        'form_table'                            => [
            'goods'              => '基础信息',
            'goods_placeholder'  => '请输入订单ID/订单号/商品名称/型号',
            'user'               => '用户信息',
            'user_placeholder'   => '请输入用户名/昵称/手机/邮箱',
            'status'             => '状态',
            'type'               => '申请类型',
            'reason'             => '原因',
            'price'              => '退款金额',
            'number'             => '退货数量',
            'msg'                => '退款说明',
            'refundment'         => '退款类型',
            'voucher'            => '凭证',
            'express_name'       => '快递公司',
            'express_number'     => '快递单号',
            'refuse_reason'      => '拒绝原因',
            'apply_time'         => '申请时间',
            'confirm_time'       => '确认时间',
            'delivery_time'      => '退货时间',
            'audit_time'         => '审核时间',
            'add_time'           => '创建时间',
            'upd_time'           => '更新时间',
        ],
        // 动态表格统计字段
        'form_table_stats'  => [
            'price'   => '退款总额',
            'number'  => '退货总数',
        ],
    ],

    // 支付方式
    'payment'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '已安装', 'type' => 0],
            ['name' => '未安装', 'type' => 1],
        ],
        'base_nav_title'                        => '支付方式',
        'base_upload_payment_name'              => '导入支付',
        'base_nav_store_payment_name'           => '更多支付方式下载',
        'upload_top_list_tips'                  => [
            [
                'name'  => '1. 类名必须于文件名一致（去除 .php ），如 Alipay.php 则取 Alipay',
            ],
            [
                'name'  => '2. 类必须定义的方法',
                'item'  => [
                    '2.1. Config 配置方法',
                    '2.2. Pay 支付方法',
                    '2.3. Respond 回调方法',
                    '2.4. Notify 异步回调方法（可选、未定义则调用Respond方法）',
                    '2.5. Refund 退款方法（可选、未定义则不能发起原路退款）',
                ],
            ],
            [
                'name'  => '3. 可自定义输出内容方法',
                'item'  => [
                    '3.1. SuccessReturn 支付成功（可选）',
                    '3.2. ErrorReturn 支付失败（可选）',
                ],
            ]
        ],
        'upload_top_tips_ps'                        => 'PS：以上条件不满足则无法查看插件，将插件放入.zip压缩包中上传、支持一个压缩中包含多个支付插件',
        // 动态表格
        'form_table'                            => [
            'name'            => '名称',
            'logo'            => 'LOGO',
            'version'         => '版本',
            'apply_version'   => '适用版本',
            'apply_terminal'  => '适用终端',
            'author'          => '作者',
            'desc'            => '描述',
            'enable'          => '是否启用',
            'open_user'       => '用户开放',
        ],
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => '快递',
    ],

    // 主题管理
    'themeadmin'            => [
        'base_nav_list'                         => [
            ['name' => '当前主题', 'type' => 'index'],
            ['name' => '主题安装', 'type' => 'upload'],
        ],
        'base_upload_theme_name'                => '导入主题',
        'base_nav_store_theme_name'             => '更多主题下载',
        'list_author_title'                     => '作者',
        'list_version_title'                    => '适用版本',
        'form_theme_upload_tips'                => '上传一个zip压缩格式的主题安装包',
    ],

    // 主题数据
    'themedata'            => [
        'base_nav_title'                        => '主题数据',
        'upload_list_tips'                      => [
            '1. 选择已下载的主题数据zip包',
            '2. 导入将自动新增一条数据',
        ],
        // 动态表格
        'form_table'                            => [
            'unique'    => '唯一标识',
            'name'      => '名称',
            'type'      => '数据类型',
            'theme'     => '主题',
            'view'      => '页面',
            'is_enable' => '是否启用',
            'add_time'  => '添加时间',
            'upd_time'  => '更新时间',
        ],
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => '手机用户中心导航',
        // 动态表格
        'form_table'                            => [
            'name'           => '名称',
            'platform'       => '所属平台',
            'images_url'     => '导航图标',
            'event_type'     => '事件类型',
            'event_value'    => '事件值',
            'desc'           => '描述',
            'is_enable'      => '是否启用',
            'is_need_login'  => '是否需登录',
            'sort'           => '排序',
            'add_time'       => '创建时间',
            'upd_time'       => '更新时间',
        ],
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => '手机首页导航',
        // 动态表格
        'form_table'                            => [
            'name'           => '名称',
            'platform'       => '所属平台',
            'images'         => '导航图标',
            'event_type'     => '事件类型',
            'event_value'    => '事件值',
            'is_enable'      => '是否启用',
            'is_need_login'  => '是否需登录',
            'sort'           => '排序',
            'add_time'       => '创建时间',
            'upd_time'       => '更新时间',
        ],
    ],

    // 支付日志
    'paylog'                => [
        'pay_request_title'                     => '支付请求日志',
        // 动态表格
        'form_table'                            => [
            'user'              => '用户信息',
            'user_placeholder'  => '请输入用户名/昵称/手机/邮箱',
            'log_no'            => '支付单号',
            'payment'           => '支付方式',
            'status'            => '状态',
            'total_price'       => '业务订单金额',
            'pay_price'         => '支付金额',
            'business_type'     => '业务类型',
            'business_list'     => '业务id/单号',
            'trade_no'          => '支付平台交易号',
            'buyer_user'        => '支付平台用户帐号',
            'subject'           => '订单名称',
            'request_params'    => '请求参数',
            'pay_time'          => '支付时间',
            'close_time'        => '关闭时间',
            'add_time'          => '创建时间',
        ],
    ],

    // 支付请求日志
    'payrequestlog'         => [
        'base_nav_title'                        => '支付请求日志',
        // 动态表格
        'form_table'                            => [
            'business_type'    => '业务类型',
            'request_params'   => '请求参数',
            'response_data'    => '响应数据',
            'business_handle'  => '业务处理结果',
            'request_url'      => '请求url地址',
            'server_port'      => '端口号',
            'server_ip'        => '服务器ip',
            'client_ip'        => '客户端ip',
            'os'               => '操作系统',
            'browser'          => '浏览器',
            'method'           => '请求类型',
            'scheme'           => 'http类型',
            'version'          => 'http版本',
            'client'           => '客户端详情',
            'add_time'         => '创建时间',
            'upd_time'         => '更新时间',
        ],
    ],

    // 退款日志
    'refundlog'               => [
        // 动态表格
        'form_table'                            => [
            'user'              => '用户信息',
            'user_placeholder'  => '请输入用户名/昵称/手机/邮箱',
            'payment'           => '支付方式',
            'business_type'     => '业务类型',
            'business_id'       => '业务订单id',
            'trade_no'          => '支付平台交易号',
            'buyer_user'        => '支付平台用户帐号',
            'refundment_text'   => '退款方式',
            'refund_price'      => '退款金额',
            'pay_price'         => '订单支付金额',
            'msg'               => '描述',
            'request_params'    => '请求参数',
            'return_params'     => '响应参数',
            'add_time_time'     => '退款时间',
        ],
    ],

    // 插件调用
    'plugins'               => [
        'back_to_plugins_admin'                 => '返回到应用管理 >>',
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => '请先点击勾勾启用',
            'save_no_data_tips'                 => '没有可保存的插件数据',
        ],
        // 基础导航
        'base_nav_title'                        => '应用',
        'base_upload_application_name'          => '导入应用',
        'base_nav_more_plugins_download_name'   => '更多插件下载',
        // 基础页面
        'base_search_input_placeholder'         => '请输入名称/描述',
        'base_top_tips_one'                     => '列表排序方式[ 自定义排序 -> 最早安装 ]',
        'base_top_tips_two'                     => '可点击拖动调整插件调用和展示顺序',
        'base_open_setup_title'                 => '开启设置',
        'data_list_author_title'                => '作者',
        'data_list_author_url_title'            => '主页',
        'data_list_version_title'               => '版本',
        'data_list_second_domain_title'         => '二级域名',
        'data_list_second_domain_tips'          => '请在后台[ 系统 -> 系统配置 -> 安全 ]中配置好Cookie有效域名主域名',
        'uninstall_confirm_tips'                => '卸载可能会丢失插件基础配置数据不可恢复、确认操作吗？',
        'not_install_divide_title'              => '以下插件未安装',
        'delete_plugins_text'                   => '1. 仅删除应用',
        'delete_plugins_text_tips'              => '（仅删除应用代码，保留应用数据）',
        'delete_plugins_data_text'              => '2. 删除应用并删除数据',
        'delete_plugins_data_text_tips'         => '（将删除应用代码和应用数据）',
        'delete_plugins_ps_tips'                => 'PS：以下操作后均不可恢复，请谨慎操作！',
        'delete_plugins_button_name'            => '仅删除应用',
        'delete_plugins_data_button_name'       => '删除应用和数据',
        'cancel_delete_plugins_button_name'     => '再考虑一下',
        'more_plugins_store_to_text'            => '去应用商店挑选更多插件丰富站点 >>',
        'no_data_store_to_text'                 => '到应用商店挑选插件丰富站点 >>',
        'plugins_category_title'                => '应用分类',
        'plugins_category_admin_title'          => '分类管理',
        'plugins_menu_control_title'            => '左侧菜单',
    ],

    // 插件分类
    'pluginscategory'       => [
        'base_nav_title'                        => '应用分类',
    ],

    // 安装页面
    'packageinstall'        => [
        'back_admin_title'                      => '返回后台',
        'get_loading_tips'                      => '正在获取中...',
    ],

    // 角色管理
    'role'                  => [
        'base_nav_title'                        => '角色',
        'admin_not_modify_tips'                 => '超级管理员默认拥有所有权限，不可更改。',
        // 动态表格
        'form_table'                            => [
            'name'       => '角色名称',
            'is_enable'  => '是否启用',
            'add_time'   => '创建时间',
            'upd_time'   => '更新时间',
        ],
    ],

    // 权限管理
    'power'                 => [
        'base_nav_title'                        => '权限',
        'top_tips_list'                         => [
            '1. 非专业技术人员请勿操作该页面数据、操作失误可能会导致权限菜单错乱。',
            '2. 权限菜单分为[ 使用、操作 ]两种类型，使用菜单一般开启显示，操作菜单必须隐藏。',
            '3. 如果出现权限菜单错乱，可以重新覆盖[ '.MyConfig('database.connections.mysql.prefix').'power ]数据表的数据恢复。',
            '4. [ 超级管理员、admin账户 ]默认拥有所有权限，不可更改。',
        ],
        'content_top_tips_list'                 => [
            '1. 填写[ 控制器名称 和 方法名称 ]需要对应创建相应的控制器和方法的定义',
            '2. 控制器文件位置[ app/admin/controller ]、该操作仅开发人员使用',
            '3. 控制器名称/方法名称 与 自定义url地址、两者必须填写一个',
        ],
    ],

    // 快捷导航
    'quicknav'              => [
        'base_nav_title'                        => '快捷导航',
        // 动态表格
        'form_table'                            => [
            'name'         => '名称',
            'platform'     => '所属平台',
            'images'       => '导航图标',
            'event_type'   => '事件类型',
            'event_value'  => '事件值',
            'is_enable'    => '是否启用',
            'sort'         => '排序',
            'add_time'     => '创建时间',
            'upd_time'     => '更新时间',
        ],
    ],

    // 地区管理
    'region'                => [
        'base_nav_title'                        => '地区',
    ],

    // 筛选价格
    'screeningprice'        => [
        'base_nav_title'                        => '筛选价格',
        'top_tips_list'                         => [
            '最小价格0 - 最大价格100 则是小于100',
            '最小价格1000 - 最大价格0 则是大于1000',
            '最小价格100 - 最大价格500 则是大于等于100并且小于500',
        ],
    ],

    // 首页轮播
    'slide'                 => [
        'base_nav_title'                        => '轮播',
        // 动态表格
        'form_table'                            => [
            'name'         => '名称',
            'describe'     => '描述',
            'platform'     => '所属平台',
            'images'       => '图片',
            'event_type'   => '事件类型',
            'event_value'  => '事件值',
            'is_enable'    => '是否启用',
            'sort'         => '排序',
            'start_time'   => '开始时间',
            'end_time'     => '结束时间',
            'add_time'     => '创建时间',
            'upd_time'     => '更新时间',
        ],
    ],

    // diy装修
    'diy'                   => [
        'nav_store_diy_name'                    => '更多diy模板下载',
        'nav_apptabbar_name'                    => '底部菜单',
        'upload_list_tips'                      => [
            '1. 选择已下载的diy设计zip包',
            '2. 导入将自动新增一条数据',
        ],
        // 动态表格
        'form_table'                            => [
            'id'            => '数据ID',
            'md5_key'       => '唯一标识',
            'logo'          => 'logo',
            'name'          => '名称',
            'describe'      => '描述',
            'access_count'  => '访问次数',
            'is_enable'     => '是否启用',
            'add_time'      => '创建时间',
            'upd_time'      => '更新时间',
        ],
    ],

    // 附件
    'attachment'                 => [
        'base_nav_title'                        => '附件',
        'category_admin_title'                  => '分类管理',
        // 动态表格
        'form_table'                            => [
            'category_name'  => '分类',
            'type_name'      => '类型',
            'info'           => '附件',
            'original'       => '原文件名',
            'title'          => '新文件名',
            'size'           => '大小',
            'ext'            => '后缀',
            'url'            => 'url地址 ',
            'hash'           => 'hash',
            'add_time'       => '创建时间',
        ],
    ],

    // 附件分类
    'attachmentcategory'        => [
        'base_nav_title'                        => '附件分类',
    ],

    // 积分日志
    'integrallog'           => [
        // 动态表格
        'form_table'                            => [
            'user'                => '用户信息',
            'user_placeholder'    => '请输入用户名/昵称/手机/邮箱',
            'type'                => '操作类型',
            'operation_integral'  => '操作积分',
            'original_integral'   => '原始积分',
            'new_integral'        => '最新积分',
            'msg'                 => '操作原因',
            'operation_id'        => '操作人员id',
            'add_time_time'       => '操作时间',
        ],
    ],

    // 消息日志
    'message'               => [
        // 动态表格
        'form_table'                            => [
            'user'                      => '用户信息',
            'user_placeholder'          => '请输入用户名/昵称/手机/邮箱',
            'type'                      => '消息类型',
            'business_type'             => '业务类型',
            'title'                     => '标题',
            'detail'                    => '详情',
            'is_read'                   => '是否已读',
            'user_is_delete_time_text'  => '用户是否删除',
            'add_time_time'             => '发送时间',
        ],
    ],

    // 短信日志
    'smslog'               => [
        // 动态表格
        'form_table'                            => [
            'platform'        => '短信平台',
            'status'          => '状态',
            'mobile'          => '手机',
            'template_value'  => '模板内容',
            'template_var'    => '模板变量',
            'sign_name'       => '短信签名',
            'request_url'     => '请求接口',
            'request_params'  => '请求参数',
            'response_data'   => '响应数据',
            'reason'          => '失败原因',
            'tsc'             => '耗时(秒)',
            'add_time'        => '添加时间',
            'upd_time'        => '更新时间',
        ],
    ],

    // 邮件日志
    'emaillog'               => [
        // 动态表格
        'form_table'                            => [
            'email'           => '收件邮箱',
            'status'          => '状态',
            'title'           => '邮件标题',
            'template_value'  => '邮件内容',
            'template_var'    => '邮件变量',
            'reason'          => '失败原因',
            'smtp_host'       => 'SMTP服务器',
            'smtp_port'       => 'SMTP端口',
            'smtp_name'       => '邮箱用户名',
            'smtp_account'    => '发信人邮件',
            'smtp_send_name'  => '发件人姓名',
            'tsc'             => '耗时(秒)',
            'add_time'        => '添加时间',
            'upd_time'        => '更新时间',
        ],
    ],

    // 错误日志
    'errorlog'               => [
        // 动态表格
        'form_table'                            => [
            'message'         => '错误信息',
            'file'            => '错误文件路径',
            'line'            => '错误文件行号',
            'code'            => '错误编码',
            'ip'              => '请求ip',
            'uri'             => 'uri地址段',
            'request_params'  => '请求参数',
            'memory_use'      => '使用内存',
            'tsc'             => '耗时(秒)',
            'add_time'        => '添加时间',
        ],
    ],

    // sql控制台
    'sqlconsole'            => [
        'top_tips'                              => 'PS：非开发人员请不要随意执行任何SQL语句，操作可能导致将整个系统数据库被删除。',
    ],

    // 应用商店
    'store'                 => [
        'to_store_name'                         => '去应用商店挑选插件',
    ],

    // 公共
    'common'                => [
        // 公共
        'admin_browser_title'                   => '后台管理系统',
        'remove_cache_title'                    => '清除缓存',
        // 商品参数
        'form_goods_params_config_error_tips'   => '商品参数配置信息',
        'form_goods_params_copy_no_tips'        => '请先粘贴配置信息',
        'form_goods_params_copy_error_tips'     => '配置格式错误',
        'form_goods_params_type_message'        => '请选择商品参数展示类型',
        'form_goods_params_params_name'         => '参数名称',
        'form_goods_params_params_message'      => '请填写参数名称',
        'form_goods_params_value_name'          => '参数值',
        'form_goods_params_value_message'       => '请填写参数值',
        'form_goods_params_move_type_tips'      => '操作类型配置有误',
        'form_goods_params_move_top_tips'       => '已到最顶部',
        'form_goods_params_move_bottom_tips'    => '已到最底部',
        'form_goods_params_thead_type_title'    => '展示范围',
        'form_goods_params_thead_name_title'    => '参数名称',
        'form_goods_params_thead_value_title'   => '参数值',
        'form_goods_params_row_add_title'       => '添加一行',
        'form_goods_params_list_tips'           => [
            '1. 全部（在商品基础信息和详情参数下都展示）',
            '2. 详情（仅在商品详情参数下都展示）',
            '3. 基础（仅在商品基础信息下都展示）',
            '4. 快捷操作将会清除原来的数据、重载页面便可恢复原来的数据（仅保存商品后生效）',
        ],
    ],

    // 后台权限菜单
    'admin_power_menu_list' => [
        'config_index' => [
            'name'  => '系统',
            'item'  => [
                'config_index'                 => '系统配置',
                'config_store'                 => '商店信息',
                'config_save'                  => '配置保存',
                'index_storeaccountsbind'      => '应用商店帐号绑定',
                'index_inspectupgrade'         => '系统更新检查',
                'index_inspectupgradeconfirm'  => '系统更新确认',
                'index_stats'                  => '首页统计数据',
                'index_income'                 => '首页统计数据（收入统计）',
                'shortcutmenu_index'           => '常用功能',
                'shortcutmenu_save'            => '常用功能添加/编辑',
                'shortcutmenu_sort'            => '常用功能排序',
                'shortcutmenu_delete'          => '常用功能删除',
            ]
        ],
        'site_index' => [
            'name'  => '站点',
            'item'  => [
                'site_index'                  => '站点设置',
                'site_save'                   => '站点设置编辑',
                'site_goodssearch'            => '站点设置商品搜索',
                'layout_layoutindexhomesave'  => '首页布局管理',
                'sms_index'                   => '短信设置',
                'sms_save'                    => '短信设置编辑',
                'email_index'                 => '邮箱设置',
                'email_save'                  => '邮箱设置/编辑',
                'email_emailtest'             => '邮件发送测试',
                'seo_index'                   => 'SEO设置',
                'seo_save'                    => 'SEO设置编辑',
                'agreement_index'             => '协议管理',
                'agreement_save'              => '协议设置编辑',
            ]
        ],
        'power_index' => [
            'name'  => '权限',
            'item'  => [
                'admin_index'        => '管理员列表',
                'admin_saveinfo'     => '管理员添加/编辑页面',
                'admin_save'         => '管理员添加/编辑',
                'admin_delete'       => '管理员删除',
                'admin_detail'       => '管理员详情',
                'role_index'         => '角色管理',
                'role_saveinfo'      => '角色组添加/编辑页面',
                'role_save'          => '角色组添加/编辑',
                'role_delete'        => '角色删除',
                'role_statusupdate'  => '角色状态更新',
                'role_detail'        => '角色详情',
                'power_index'        => '权限分配',
                'power_save'         => '权限添加/编辑',
                'power_statusupdate' => '权限状态更新',
                'power_delete'       => '权限删除',
            ]
        ],
        'user_index' => [
            'name'  => '用户',
            'item'  => [
                'user_index'            => '用户列表',
                'user_saveinfo'         => '用户编辑/添加页面',
                'user_save'             => '用户添加/编辑',
                'user_delete'           => '用户删除',
                'user_detail'           => '用户详情',
                'useraddress_index'     => '用户地址',
                'useraddress_saveinfo'  => '用户地址编辑页面',
                'useraddress_save'      => '用户地址编辑',
                'useraddress_delete'    => '用户地址删除',
                'useraddress_detail'    => '用户地址详情',
            ]
        ],
        'goods_index' => [
            'name'  => '商品',
            'item'  => [
                'goods_index'                       => '商品管理',
                'goods_saveinfo'                    => '商品添加/编辑页面',
                'goods_save'                        => '商品添加/编辑',
                'goods_delete'                      => '商品删除',
                'goods_statusupdate'                => '商品状态更新',
                'goods_basetemplate'                => '获取商品基础模板',
                'goods_detail'                      => '商品详情',
                'goodscategory_index'               => '商品分类',
                'goodscategory_save'                => '商品分类添加/编辑',
                'goodscategory_statusupdate'        => '商品分类状态更新',
                'goodscategory_delete'              => '商品分类删除',
                'goodsparamstemplate_index'         => '商品参数',
                'goodsparamstemplate_delete'        => '商品参数删除',
                'goodsparamstemplate_statusupdate'  => '商品参数状态更新',
                'goodsparamstemplate_saveinfo'      => '商品参数添加/编辑页面',
                'goodsparamstemplate_save'          => '商品参数添加/编辑',
                'goodsparamstemplate_detail'        => '商品参数详情',
                'goodsspectemplate_index'           => '商品规格',
                'goodsspectemplate_delete'          => '商品规格删除',
                'goodsspectemplate_statusupdate'    => '商品规格状态更新',
                'goodsspectemplate_saveinfo'        => '商品规格添加/编辑页面',
                'goodsspectemplate_save'            => '商品规格添加/编辑',
                'goodsspectemplate_detail'          => '商品规格详情',
                'goodscomments_detail'              => '商品评论详情',
                'goodscomments_index'               => '商品评论',
                'goodscomments_reply'               => '商品评论回复',
                'goodscomments_delete'              => '商品评论删除',
                'goodscomments_statusupdate'        => '商品评论状态更新',
                'goodscomments_saveinfo'            => '商品评论添加/编辑页面',
                'goodscomments_save'                => '商品评论添加/编辑',
                'goodsbrowse_index'                 => '商品浏览',
                'goodsbrowse_delete'                => '商品浏览删除',
                'goodsbrowse_detail'                => '商品浏览详情',
                'goodsfavor_index'                  => '商品收藏',
                'goodsfavor_delete'                 => '商品收藏删除',
                'goodsfavor_detail'                 => '商品收藏详情',
                'goodscart_index'                   => '商品购物车',
                'goodscart_delete'                  => '商品购物车删除',
                'goodscart_detail'                  => '商品购物车详情',
            ]
        ],
        'order_index' => [
            'name'  => '订单',
            'item'  => [
                'order_index'             => '订单管理',
                'order_delete'            => '订单删除',
                'order_cancel'            => '订单取消',
                'order_delivery'          => '订单发货',
                'order_collect'           => '订单收货',
                'order_pay'               => '订单支付',
                'order_confirm'           => '订单确认',
                'order_detail'            => '订单详情',
                'order_deliveryinfo'      => '订单发货页面',
                'order_serviceinfo'       => '订单服务页面',
                'orderaftersale_index'    => '订单售后',
                'orderaftersale_delete'   => '订单售后删除',
                'orderaftersale_cancel'   => '订单售后取消',
                'orderaftersale_audit'    => '订单售后审核',
                'orderaftersale_confirm'  => '订单售后确认',
                'orderaftersale_refuse'   => '订单售后拒绝',
                'orderaftersale_detail'   => '订单售后详情',
            ]
        ],
        'navigation_index' => [
            'name'  => '网站',
            'item'  => [
                'navigation_index'                 => '导航管理',
                'navigation_save'                  => '导航添加/编辑',
                'navigation_delete'                => '导航删除',
                'navigation_statusupdate'          => '导航状态更新',
                'customview_index'                 => '自定义页面',
                'customview_saveinfo'              => '自定义页面添加/编辑页面',
                'customview_save'                  => '自定义页面添加/编辑',
                'customview_delete'                => '自定义页面删除',
                'customview_statusupdate'          => '自定义页面状态更新',
                'customview_detail'                => '自定义页面详情',
                'link_index'                       => '友情链接',
                'link_saveinfo'                    => '友情链接添加/编辑页面',
                'link_save'                        => '友情链接添加/编辑',
                'link_delete'                      => '友情链接删除',
                'link_statusupdate'                => '友情链接状态更新',
                'link_detail'                      => '友情链接详情',
                'themeadmin_index'                 => '主题管理',
                'themeadmin_save'                  => '主题管理添加/编辑',
                'themeadmin_upload'                => '主题上传安装',
                'themeadmin_delete'                => '主题删除',
                'themeadmin_download'              => '主题下载',
                'themeadmin_market'                => '主题模板市场',
                'themeadmin_storeuploadinfo'       => '主题上传页面',
                'themeadmin_storeupload'           => '主题上传',
                'themedata_index'                  => '主题数据',
                'themedata_saveinfo'               => '主题数据添加/编辑页面',
                'themedata_save'                   => '主题数据添加/编辑',
                'themedata_upload'                 => '主题数据上传',
                'themedata_delete'                 => '主题数据删除',
                'themedata_download'               => '主题数据下载',
                'themedata_detail'                 => '主题数据详情',
                'themedata_goodssearch'            => '主题数据商品搜索',
                'themedata_articlesearch'          => '主题数据文章搜索',
                'slide_index'                      => '首页轮播',
                'slide_saveinfo'                   => '轮播添加/编辑页面',
                'slide_save'                       => '轮播添加/编辑',
                'slide_statusupdate'               => '轮播状态更新',
                'slide_delete'                     => '轮播删除',
                'slide_detail'                     => '轮播详情',
                'screeningprice_index'             => '筛选价格',
                'screeningprice_save'              => '筛选价格添加/编辑',
                'screeningprice_delete'            => '筛选价格删除',
                'region_index'                     => '地区管理',
                'region_save'                      => '地区添加/编辑',
                'region_statusupdate'              => '地区状态更新',
                'region_delete'                    => '地区删除',
                'region_codedata'                  => '获取地区编号数据',
                'express_index'                    => '快递管理',
                'express_save'                     => '快递添加/编辑',
                'express_delete'                   => '快递删除',
                'payment_index'                    => '支付方式',
                'payment_saveinfo'                 => '支付方式安装/编辑页面',
                'payment_save'                     => '支付方式安装/编辑',
                'payment_delete'                   => '支付方式删除',
                'payment_install'                  => '支付方式安装',
                'payment_statusupdate'             => '支付方式状态更新',
                'payment_uninstall'                => '支付方式卸载',
                'payment_upload'                   => '支付方式上传',
                'payment_market'                   => '支付插件市场',
                'quicknav_index'                   => '快捷导航',
                'quicknav_saveinfo'                => '快捷导航添加/编辑页面',
                'quicknav_save'                    => '快捷导航添加/编辑',
                'quicknav_statusupdate'            => '快捷导航状态更新',
                'quicknav_delete'                  => '快捷导航删除',
                'quicknav_detail'                  => '快捷导航详情',
                'design_index'                     => '页面设计',
                'design_saveinfo'                  => '页面设计添加/编辑页面',
                'design_save'                      => '页面设计添加/编辑',
                'design_statusupdate'              => '页面设计状态更新',
                'design_upload'                    => '页面设计导入',
                'design_download'                  => '页面设计下载',
                'design_sync'                      => '页面设计同步首页',
                'design_delete'                    => '页面设计删除',
                'design_market'                    => '页面设计模板市场',
                'attachment_index'                 => '附件管理',
                'attachment_detail'                => '附件管理详情',
                'attachment_saveinfo'              => '附件管理添加/编辑页面',
                'attachment_save'                  => '附件管理添加/编辑',
                'attachment_delete'                => '附件管理删除',
                'attachmentcategory_index'         => '附件分类',
                'attachmentcategory_save'          => '附件分类添加/编辑',
                'attachmentcategory_statusupdate'  => '附件状态更新',
                'attachmentcategory_delete'        => '附件分类删除',
            ]
        ],
        'brand_index' => [
            'name'  => '品牌',
            'item'  => [
                'brand_index'           => '品牌管理',
                'brand_saveinfo'        => '品牌添加/编辑页面',
                'brand_save'            => '品牌添加/编辑',
                'brand_statusupdate'    => '品牌状态更新',
                'brand_delete'          => '品牌删除',
                'brand_detail'          => '品牌详情',
                'brandcategory_index'   => '品牌分类',
                'brandcategory_save'    => '品牌分类添加/编辑',
                'brandcategory_delete'  => '品牌分类删除',
            ]
        ],
        'warehouse_index' => [
            'name'  => '仓库',
            'item'  => [
                'warehouse_index'               => '仓库管理',
                'warehouse_saveinfo'            => '仓库添加/编辑页面',
                'warehouse_save'                => '仓库添加/编辑',
                'warehouse_delete'              => '仓库删除',
                'warehouse_statusupdate'        => '仓库状态更新',
                'warehouse_detail'              => '仓库详情',
                'warehousegoods_index'          => '仓库商品管理',
                'warehousegoods_detail'         => '仓库商品详情',
                'warehousegoods_delete'         => '仓库商品删除',
                'warehousegoods_statusupdate'   => '仓库商品状态更新',
                'warehousegoods_goodssearch'    => '仓库商品搜索',
                'warehousegoods_goodsadd'       => '仓库商品搜索添加',
                'warehousegoods_goodsdel'       => '仓库商品搜索删除',
                'warehousegoods_inventoryinfo'  => '仓库商品库存编辑页面',
                'warehousegoods_inventorysave'  => '仓库商品库存编辑',
            ]
        ],
        'app_index' => [
            'name'  => '手机',
            'item'  => [
                'appconfig_index'                  => '基础配置',
                'appconfig_save'                   => '基础配置保存',
                'appmini_index'                    => '小程序列表',
                'appmini_created'                  => '小程序包生成',
                'appmini_delete'                   => '小程序包删除',
                'appmini_themeupload'              => '小程序主题上传',
                'appmini_themesave'                => '小程序主题切换',
                'appmini_themedelete'              => '小程序主题切换',
                'appmini_themedownload'            => '小程序主题下载',
                'appmini_config'                   => '小程序配置',
                'appmini_save'                     => '小程序配置保存',
                'diy_index'                        => 'DIY装修',
                'diy_saveinfo'                     => 'DIY装修添加/编辑页面',
                'diy_save'                         => 'DIY装修添加/编辑',
                'diy_statusupdate'                 => 'DIY装修状态更新',
                'diy_delete'                       => 'DIY装修删除',
                'diy_download'                     => 'DIY装修导出',
                'diy_upload'                       => 'DIY装修导入',
                'diy_detail'                       => 'DIY装修详情',
                'diy_preview'                      => 'DIY装修预览',
                'diy_market'                       => 'DIY装修模板市场',
                'diy_apptabbar'                    => 'DIY装修底部菜单',
                'diy_storeuploadinfo'              => 'DIY装修上传页面',
                'diy_storeupload'                  => 'DIY装修上传',
                'diyapi_init'                      => 'DIY装修-公共初始化',
                'diyapi_attachmentcategory'        => 'DIY装修-附件分类',
                'diyapi_attachmentlist'            => 'DIY装修-附件列表',
                'diyapi_attachmentsave'            => 'DIY装修-附件保存',
                'diyapi_attachmentdelete'          => 'DIY装修-附件删除',
                'diyapi_attachmentupload'          => 'DIY装修-附件上传',
                'diyapi_attachmentcatch'           => 'DIY装修-附件远程下载',
                'diyapi_attachmentscanuploaddata'  => 'DIY装修-附件扫码上传数据',
                'diyapi_attachmentmovecategory'    => 'DIY装修-附件移动分类',
                'diyapi_attachmentcategorysave'    => 'DIY装修-附件分类保存',
                'diyapi_attachmentcategorydelete'  => 'DIY装修-附件分类删除',
                'diyapi_goodslist'                 => 'DIY装修-商品列表',
                'diyapi_customviewlist'            => 'DIY装修-自定义页面列表',
                'diyapi_designlist'                => 'DIY装修-页面设计列表',
                'diyapi_articlelist'               => 'DIY装修-文章列表',
                'diyapi_brandlist'                 => 'DIY装修-品牌列表',
                'diyapi_diylist'                   => 'DIY装修-DIY装修列表',
                'diyapi_diydetail'                 => 'DIY装修-DIY装修详情',
                'diyapi_diysave'                   => 'DIY装修-DIY装修保存',
                'diyapi_diyupload'                 => 'DIY装修-DIY装修导入',
                'diyapi_diydownload'               => 'DIY装修-DIY装修导出',
                'diyapi_diyinstall'                => 'DIY装修-DIY装修模板安装',
                'diyapi_diymarket'                 => 'DIY装修-DIY装修模板市场',
                'diyapi_goodsappointdata'          => 'DIY装修-商品指定数据',
                'diyapi_goodsautodata'             => 'DIY装修-商品自动数据',
                'diyapi_articleappointdata'        => 'DIY装修-文章指定数据',
                'diyapi_articleautodata'           => 'DIY装修-文章自动数据',
                'diyapi_brandappointdata'          => 'DIY装修-品牌指定数据',
                'diyapi_brandautodata'             => 'DIY装修-品牌自动数据',
                'diyapi_userheaddata'              => 'DIY装修-用户头部数据',
                'diyapi_custominit'                => 'DIY装修-自定义初始化',
                'apphomenav_index'                 => '首页导航',
                'apphomenav_saveinfo'              => '首页导航添加/编辑页面',
                'apphomenav_save'                  => '首页导航添加/编辑',
                'apphomenav_statusupdate'          => '首页导航状态更新',
                'apphomenav_delete'                => '首页导航删除',
                'apphomenav_detail'                => '首页导航详情',
                'appcenternav_index'               => '用户中心导航',
                'appcenternav_saveinfo'            => '用户中心导航添加/编辑页面',
                'appcenternav_save'                => '用户中心导航添加/编辑',
                'appcenternav_statusupdate'        => '用户中心导航状态更新',
                'appcenternav_delete'              => '用户中心导航删除',
                'appcenternav_detail'              => '用户中心导航详情',
            ]
        ],
        'article_index' => [
            'name'  => '文章',
            'item'  => [
                'article_index'           => '文章管理',
                'article_saveinfo'        => '文章添加/编辑页面',
                'article_save'            => '文章添加/编辑',
                'article_delete'          => '文章删除',
                'article_statusupdate'    => '文章状态更新',
                'article_detail'          => '文章详情',
                'articlecategory_index'   => '文章分类',
                'articlecategory_save'    => '文章分类编辑/添加',
                'articlecategory_delete'  => '文章分类删除',
            ]
        ],
        'data_index' => [
            'name'  => '数据',
            'item'  => [
                'message_index'         => '消息管理',
                'message_delete'        => '消息删除',
                'message_detail'        => '消息详情',
                'paylog_index'          => '支付日志',
                'paylog_detail'         => '支付日志详情',
                'paylog_close'          => '支付日志关闭',
                'payrequestlog_index'   => '支付请求日志列表',
                'payrequestlog_detail'  => '支付请求日志详情',
                'refundlog_index'       => '退款日志',
                'refundlog_detail'      => '退款日志详情',
                'integrallog_index'     => '积分日志',
                'integrallog_detail'    => '积分日志详情',
                'smslog_index'          => '短信日志',
                'smslog_detail'         => '短信日志详情',
                'smslog_delete'         => '短信日志删除',
                'smslog_alldelete'      => '短信日志全部删除',
                'emaillog_index'        => '邮件日志',
                'emaillog_detail'       => '邮件日志详情',
                'emaillog_delete'       => '邮件日志删除',
                'emaillog_alldelete'    => '邮件日志全部删除',
                'errorlog_index'        => '错误日志',
                'errorlog_detail'       => '错误日志详情',
                'errorlog_delete'       => '错误日志删除',
                'errorlog_alldelete'    => '错误日志全部删除',
            ]
        ],
        'store_index' => [
            'name'  => '应用',
            'item'  => [
                'pluginsadmin_index'            => '应用管理',
                'plugins_index'                 => '应用调用管理',
                'pluginsadmin_saveinfo'         => '应用添加/编辑页面',
                'pluginsadmin_save'             => '应用添加/编辑',
                'pluginsadmin_statusupdate'     => '应用状态更新',
                'pluginsadmin_delete'           => '应用删除',
                'pluginsadmin_upload'           => '应用上传',
                'pluginsadmin_download'         => '应用打包',
                'pluginsadmin_install'          => '应用安装',
                'pluginsadmin_uninstall'        => '应用卸载',
                'pluginsadmin_sortsave'         => '应用排序保存',
                'pluginsadmin_market'           => '应用插件市场',
                'store_index'                   => '应用商店',
                'packageinstall_index'          => '软件包安装页面',
                'packageinstall_install'        => '软件包安装',
                'packageupgrade_upgrade'        => '软件包更新',
                'pluginscategory_index'         => '应用分类',
                'pluginscategory_save'          => '应用分类添加/编辑',
                'pluginscategory_statusupdate'  => '应用分类状态更新',
                'pluginscategory_delete'        => '应用分类删除',
                'store_market'                  => '应用市场',
            ]
        ],
        'tool_index' => [
            'name'  => '工具',
                'item'                  => [
                'cache_index'           => '缓存管理',
                'cache_statusupdate'    => '站点缓存更新',
                'cache_templateupdate'  => '模板缓存更新',
                'cache_moduleupdate'    => '模块缓存更新',
                'cache_logdelete'       => '日志删除',
                'sqlconsole_index'      => 'SQL控制台',
                'sqlconsole_implement'  => 'SQL执行',
            ]
        ],
    ],
];
?>