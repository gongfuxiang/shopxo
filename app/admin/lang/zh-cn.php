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
            'order_transaction_amount_name'     => '订单成交金额走势',
            'order_trading_trend_name'          => '订单交易走势',
            'goods_hot_name'                    => '热销商品',
            'goods_hot_tips'                    => '仅显示前30条商品',
            'payment_name'                      => '支付方式',
            'order_region_name'                 => '订单地域分布',
            'order_region_tips'                 => '仅显示30条数据',
            'upgrade_check_loading_tips'        => '正在获取最新内容、请稍候...',
            'upgrade_version_name'              => '更新版本：',
            'upgrade_date_name'                 => '更新日期：',
        ],
    ],

    // 订单管理
    'order'                 => [
        // 页面公共
        'page_common'           => [
            'order_id_empty'                    => '订单id有误',
            'express_choice_tips'               => '请选择快递方式',
            'payment_choice_tips'               => '请选择支付方式',
        ],
    ],

    // 插件管理
    'pluginsadmin'          => [
        // 页面公共
        'page_common'           => [
            'not_enable_tips'                   => '请先点击勾勾启用',
            'save_no_data_tips'                 => '没有可保存的插件数据',
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
        'second_nav_list'                       => [
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
            ['name' => '商品', 'type' => 'goods'],
            ['name' => '搜索', 'type' => 'search'],
            ['name' => '订单', 'type' => 'order'],
            ['name' => '优惠', 'type' => 'discount'],
            ['name' => '扩展', 'type' => 'extends'],
        ],
    ],

    // 后台配置信息
    'config'                => [
        'admin_login_title'                     => '后台登录',
        'admin_login_info_bg_images_list_tips'  => [
            '1. 背景图片位于[ public/static/admin/default/images/login ]目录下',
            '2. 背景图片命名规则(1~50)、如 1.jpg',
        ],
        'map_type_tips'                         => '由于每一家的地图标准不一样、请勿随意切换地图、会导致地图坐标标注不准确的情况。',
        'view_config_course_name'               => '查看配置教程',
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
        // 表单
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称长度2~30个字符',
        'form_item_brand_category_id'           => '品牌分类',
        'form_item_brand_category_id_message'   => '请选择品牌分类',
        'form_item_website_url'                 => '官网地址',
        'form_item_website_url_placeholder'     => '官网地址、以http://或https://开头',
        'form_item_website_url_message'         => '官网地址格式有误',
        'form_item_describe'                    => '描述',
        'form_item_describe_message'            => '描述最多230个字符',
        'form_item_logo'                        => 'LOGO',
        'form_item_logo_tips'                   => '建议150*50px',
    ],

    // 品牌分类
    'brandcategory'       => [
        'base_nav_title'                        => '品牌分类',
        // 表单
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称长度2~16个字符',
    ],

    // 文章
    'article'               => [
        'base_nav_title'                        => '文章',
        'detail_content_title'                  => '详情内容',
        'detail_images_title'                   => '详情图片',
        // 表单
        'form_item_title'                       => '标题',
        'form_item_title_message'               => '标题长度2~60个字符',
        'form_item_article_category'            => '文章分类',
        'form_item_article_category_message'    => '请选择文章分类',
        'form_item_jump_url_title'              => '跳转url地址',
        'form_item_jump_url_tips'               => '带http://或https://，仅web端有效',
        'form_item_jump_url_message'            => '跳转url地址格式有误',
        'form_item_is_home_recommended_title'   => '首页推荐',
        'form_item_content_title'               => '内容',
        'form_item_content_placeholder'         => '内容格式10~105000个字符之间更多编辑功能请使用电脑访问',
        'form_item_content_message'             => '内容格式10~105000个字符之间',
    ],

    // 文章分类
    'articlecategory'       => [
        'base_nav_title'                        => '文章分类',
        // 表单
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称长度2~16个字符',
    ],

    // 自定义页面
    'customview'            => [
        'base_nav_title'                        => '自定义页面',
        'detail_content_title'                  => '详情内容',
        'detail_images_title'                   => '详情图片',
        // 表单
        'form_item_title'                       => '名称',
        'form_item_title_message'               => '名称长度2~60个字符',
        'form_item_is_header'                   => '是否含头部',
        'form_item_is_footer'                   => '是否含尾部',
        'form_item_is_full_screen'              => '是否满屏',
        'form_item_content_title'               => '内容',
        'form_item_content_placeholder'         => '内容格式10~105000个字符之间更多编辑功能请使用电脑访问',
        'form_item_content_message'             => '内容格式10~105000个字符之间',
    ],

    // 页面设计
    'design'                => [
        'nav_store_design_name'                 => '更多设计模板下载',
        'upload_list_tips'                      => [
            '1. 选择已下载的页面设计zip包',
            '2. 导入将自动新增一条数据',
        ],
        'operate_sync_tips'                     => '数据同步到首页拖拽可视化中、之后再修改数据不受影响、但是不要删除相关附件',
        // 表单
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称长度2~16个字符',
        'form_item_is_header'                   => '是否含头部',
        'form_item_is_footer'                   => '是否含尾部',
        'form_item_is_full_screen'              => '是否满屏',
        'form_logo_tips'                        => '建议大小300*300px',
    ],

    // 问答
    'answer'                => [
        'base_nav_title'                        => '问答',
        'user_info_title'                       => '用户信息',
        'form_item_name'                        => '联系人',
        'form_item_name_message'                => '联系人格式最多30个字符',
        'form_item_tel'                         => '电话',
        'form_item_tel_message'                 => '请填写有效的电话',
        'form_item_title'                       => '标题',
        'form_item_title_message'               => '标题格式最多60个字符',
        'form_item_access_count'                => '访问次数',
        'form_item_access_count_message'        => '访问次数格式0~9的数值',
        'form_item_content'                     => '内容',
        'form_item_content_message'             => '内容格式5~1000个字符之间',
        'form_item_reply'                       => '回复内容',
        'form_item_reply_message'               => '回复内容格式最多1000个字符',
        'form_is_reply'                         => '是否已回复',
    ],

    // 仓库商品
    'warehousegoods'        => [
        // 页面公共
        'page_common'           => [
            'warehouse_choice_tips'             => '请选择仓库',
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
        // 忘记密码
        'form_forget_password_name'             => '忘记密码?',
        'form_forget_password_tips'             => '请联系管理员重置密码',
        // 表单
        'form_item_username'                    => '用户名',
        'form_item_username_placeholder'        => '请输入用户名',
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
        'form_item_username_created_tips'       => '创建后不可更改',
        'form_item_username_edit_tips'          => '不可更改',
        'form_item_role'                        => '权限组',
        'form_item_role_message'                => '请选择所属角色组',
        'form_item_password_edit_tips'          => '输入则修改密码',
        'form_item_status'                      => '状态',
        'form_item_status_message'              => '请选择用户状态',
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

    // 小程序管理
    'appmini'               => [
        // 基础导航
        'base_nav_list'                         => [
            ['view_type' => 'index','name' => '当前主题'],
            ['view_type' => 'upload','name' => '主题安装'],
            ['view_type' => 'package','name' => '源码包下载'],
        ],
        'nav_store_theme_name'                  => '更多主题下载',
        'nav_theme_download_name'               => '查看小程序打包教程',
        'nav_theme_download_tips'               => '手机端主题采用uniapp开发（支持多端小程序+H5），APP也在紧急适配中。',
        'form_alipay_extend_title'              => '客服配置',
        'form_alipay_extend_tips'               => 'PS：如【APP/小程序】中开启（开启在线客服），则以下配置必填 [企业编码] 和 [聊天窗编码]',
        'list_no_data_tips'                     => '没有相关主题包',
        'list_author_title'                     => '作者：',
        'list_version_title'                    => '适用版本：',
        'package_generate_tips'                 => '生成时间比较长，请不要关闭浏览器窗口！',
    ],

    // 邮箱设置
    'email'                 => [
        // 基础导航
        'base_nav_list'                         => [
            ['name' => '邮箱设置', 'type' => 'index'],
            ['name' => '消息模板', 'type' => 'message'],
        ],
        'view_config_course_name'               => '查看配置教程',
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

    // 商品
    'goods'                 => [
        'base_nav_title'                        => '商品',
        'nav_right_list'                        => [
            ['name' => '基础信息', 'type'=>'base'],
            ['name' => '商品规格', 'type'=>'operations'],
            ['name' => '商品参数', 'type'=>'parameters'],
            ['name' => '商品相册', 'type'=>'photo'],
            ['name' => '商品视频', 'type'=>'video'],
            ['name' => '手机详情', 'type'=>'app'],
            ['name' => '电脑详情', 'type'=>'web'],
            ['name' => '虚拟信息', 'type'=>'fictitious'],
            ['name' => '扩展数据', 'type'=>'extends'],
            ['name' => 'SEO信息', 'type'=>'seo'],
        ],
        // 表单
        'form_item_title'                       => '商品名称',
        'form_item_title_message'               => '商品名称格式2~160个字符',
        'form_item_category_id'                 => '商品分类',
        'form_item_category_id_message'         => '请至少选择一个商品分类',
        'form_item_simple_desc'                 => '商品简述',
        'form_item_simple_desc_message'         => '商品简述格式最多230个字符',
        'form_item_model'                       => '商品型号',
        'form_item_model_message'               => '商品型号格式最多30个字符',
        'form_item_brand'                       => '品牌',
        'form_item_brand_message'               => '请选择品牌',
        'form_item_place_origin'                => '生产地',
        'form_item_place_origin_message'        => '请选择生产地',
        'form_item_inventory_unit'              => '库存单位',
        'form_item_inventory_unit_message'      => '库存单位格式1~6个字符',
        'form_item_give_integral'               => '购买赠送积分比例',
        'form_item_give_integral_tips'          => [
            '1. 按照商品金额比例乘以数量的比例进行发放',
            '2. 订单完成自动将发放到用户锁定积分',
            '3. 站点设置->扩展中脚本处理发放积分',
        ],
        'form_item_give_integral_placeholder'   => '购买赠送积分',
        'form_item_give_integral_message'       => '购买赠送积分比例0~100的数字',
        'form_item_buy_min_number'              => '最低起购数量',
        'form_item_buy_min_number_message'      => '最低起购数量1~100000000范围',
        'form_item_buy_max_number'              => '单次最大购买数量',
        'form_item_buy_max_number_tips'         => [
            '1. 单次最大数值100000000',
            '2. 小于等于0或空则不限',
        ],
        'form_item_buy_max_number_message'      => '单次最大购买数量 1~100000000范围',
        'form_item_site_type'                   => '商品类型',
        'form_item_site_type_tips'              => [
            '1. 当前系统配置的站点类型为( 站点类型 )',
            '2. 如果商品类型未配置则跟随系统配置的站点类型',
            '3. 当设置的商品类型不在系统设置的站点类型包含的时候，商品加入购物车功能将失效',
        ],
        'form_item_site_type_message'           => '请选择商品类型',
        'form_item_images'                      => '封面图片',
        'form_item_images_tips'                 => '留空则取相册第一张图、建议800*800px',
        'form_item_is_deduction_inventory'      => '扣减库存',
        'form_item_is_deduction_inventory_tips' => '扣除规则根据后台配置->扣除库存规则而定',
        'form_item_is_shelves'                  => '上下架',
        'form_item_is_shelves_tips'             => '下架后用户不可见',
        'form_item_spec_title'                  => '商品规格',
        'form_item_params_title'                => '商品参数',
        'form_item_photo_title'                 => '商品相册',
        'form_item_video_title'                 => '商品视频',
        'form_item_app_title'                   => '手机详情',
        'form_item_web_title'                   => '电脑详情',
        'form_item_fictitious_title'            => '虚拟信息',
        'form_item_extends_title'               => '扩展数据',
        'form_item_extends_popup_title'         => '规格扩展数据',
        // 规格
        'form_spec_top_list_tips'               => [
            '1. 批量添加规格可以快速创建商品SKU，大量节省SKU编辑时间，快捷操作数据不影响SKU数据，仅生成的时候重新覆盖SKU。',
            '2. 可以后台 商品管理->商品规格 中配置规格模板、选择商品规格模块快速生成对应规格数据、有效的提供效率',
            '3. 商品添加成功后，仓库管理->仓库商品中添加并配置库存',
        ],
        'form_spec_template_tips'               => '规格模板数据有误',
        'form_spec_template_name_exist_tips'    => '相同规格名称已经存在',
        'form_spec_template_placeholder'        => '商品规格模板...',
        'form_spec_template_message'            => '请选择商品规格模板',
        'form_spec_quick_add_title'             => '批量添加规格',
        'form_spec_quick_generate_title'        => '生成规格',
        'form_spec_type_title'                  => '规格名',
        'form_spec_type_message'                => '请填写规格名',
        'form_spec_value_title'                 => '规格值',
        'form_spec_value_message'               => '请填写规格值',
        'form_spec_value_add_title'             => '添加规格值',
        'form_spec_empty_data_tips'             => '请先添加规格',
        'form_spec_advanced_batch_setup_title'  => '高级批量设置',
        'form_spec_list_content_tips'           => '可直接点中规格行拖拽排序或点击上下移动',
        'form_spec_max_error'                   => '最多添加'.MyC('common_spec_add_max_number', 3, true).'列规格，可在后台管理[系统设置-后台配置]中配置',
        'form_spec_empty_fill_tips'             => '请先填写规格',
        'form_spec_images_message'              => '请上传规格图片',
        'form_spec_min_tips_message'            => '至少需要保留一行规格值',
        'form_spec_quick_error'                 => '快捷操作规格为空',
        'form_spec_quick_tips_msg'              => '生成规格将清空现有规格数据、是否继续？',
        'form_spec_move_type_tips'              => '操作类型配置有误',
        'form_spec_move_top_tips'               => '已到最顶部',
        'form_spec_move_bottom_tips'            => '已到最底部',
        'form_spec_thead_price_title'           => '售价(元)',
        'form_spec_thead_price_message'         => '请填写有效的销售金额',
        'form_spec_thead_original_price_title'  => '原价(元)',
        'form_spec_thead_original_price_message'=> '请填写有效的原价',
        'form_spec_thead_inventory_title'       => '库存',
        'form_spec_thead_weight_title'          => '重量(kg)',
        'form_spec_thead_weight_message'        => '规格重量0~100000000',
        'form_spec_thead_volume_title'          => '体积(m³)',
        'form_spec_thead_volume_message'        => '规格体积0~100000000',
        'form_spec_thead_coding_title'          => '编码',
        'form_spec_thead_coding_message'        => '规格编码最多60个字符',
        'form_spec_thead_barcode_title'         => '条形码',
        'form_spec_thead_barcode_message'       => '条形码最多60个字符',
        'form_spec_row_add_title'               => '添加一行',
        'form_spec_images_tips'                 => '规格名称与规格值保持一致，相同规格名称添加一次即可，重复添加则后面覆盖前面，顺序不影响前端展示效果。',
        'form_spec_images_title'                => '商品规格图片',
        'form_spec_images_add_title'            => '添加规格图片',
        'form_spec_images_add_auto_first'       => '第',
        'form_spec_images_add_auto_last'        => '列规格自动生成',
        'form_spec_images_type_title'           => '规格名称',
        'form_spec_images_type_message'         => '请填写规格名称',
        'form_spec_images_images_message'       => '请上传规格图片',
        'form_spec_all_operate_title'           => '批量操作',
        'form_spec_all_operate_placeholder'     => '批量设置的值',
        // 参数
        'form_params_select_title'              => '商品参数模板',
        'form_params_select_placeholder'        => '商品参数模板...',
        'form_params_select_message'            => '请选择商品参数模板',
        'form_params_value_placeholder'         => '粘贴商品参数配置信息',
        'form_params_config_copy_title'         => '复制配置',
        'form_params_config_empty_title'        => '清空参数',
        'form_params_list_content_tips'         => '可直接点中参数行拖拽排序或点击上下移动',
        // 相册
        'form_photo_top_tips'                   => '可拖拽图片进行排序，建议图片尺寸一致800*800px、最多30张',
        'form_photo_button_add_name'            => '上传相册',
        // 视频
        'form_video_top_tips'                   => '视频比图文更有具带入感，仅支持 mp4 格式',
        'form_video_button_add_name'            => '上传视频',
        // 手机详情
        'form_app_top_tips'                     => '设置手机详情后、在手机模式下将展示手机详情、比如[小程序、APP]',
        'form_app_value_title'                  => '文本内容',
        'form_app_value_message'                => '文本内容最多105000个字符',
        'form_app_button_add_name'              => '添加手机详情',
        // 电脑详情
        'form_web_content_message'              => '电脑端详情内容最多105000个字符',
    ],

    // 商品分类
    'goodscategory'         => [
        'base_nav_title'                        => '商品分类',
        // 表单
        'form_item_icon'                        => 'icon图标',
        'form_item_icon_tips'                   => '建议100*100px',
        'form_item_big_images'                  => '大图片',
        'form_item_big_images_tips'             => '建议360*360px',
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称格式2~16个字符',
        'form_item_vice_name'                   => '副名称',
        'form_item_vice_name_message'           => '副名称最多60个字符',
        'form_item_describe'                    => '描述',
        'form_item_describe_message'            => '描述最多200个字符',
        'form_item_is_home_recommended'         => '首页推荐',
    ],

    // 商品评论
    'goodscomments'         => [
        'base_nav_title'                        => '商品评论',
        // 表单
        'form_item_goods_info_title'            => '商品信息',
        'form_item_user_info_title'             => '用户信息',
        'form_item_business_type'               => '业务类型',
        'form_item_business_type_placeholder'   => '业务类型...',
        'form_item_business_type_message'       => '请选择业务类型',
        'form_item_rating'                      => '评分',
        'form_item_rating_placeholder'          => '未评分',
        'form_item_rating_message'              => '请选择评分',
        'form_item_content'                     => '评论内容',
        'form_item_content_message'             => '评论内容6~230个字符之间',
        'form_item_reply'                       => '回复内容',
        'form_item_reply_message'               => '回复内容最多230个字符',
        'form_item_reply_time'                  => '回复时间',
        'form_item_reply_time_message'          => '回复时间格式有误',
        'form_item_is_reply'                    => '是否已回复',
        'form_item_is_anonymous'                => '是否匿名',
    ],

    // 商品参数模板
    'goodsparamstemplate'   => [
        'detail_params_title'                   => '商品参数',
        // 表单
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称格式2~30个字符',
        'form_item_category_id'                 => '商品分类',
        'form_item_category_id_tips'            => '包含子级',
        'form_item_category_id_message'         => '请选择商品分类',
        'form_item_config_title'                => '参数配置',
        'form_item_config_value_placeholder'    => '粘贴商品参数配置信息',
        'form_item_config_template_title'       => '商品参数模板',
        'form_item_config_copy_title'           => '复制配置',
        'form_item_config_empty_title'          => '复制配置',
        'form_item_config_list_content_tips'    => '可直接点中参数行拖拽排序或点击上下移动',
    ],

    // 商品规格模板
    'goodsspectemplate'     => [
        // 表单
        'form_item_name'                        => '规格名称',
        'form_item_name_message'                => '规格名称格式1~30个字符',
        'form_item_category_id'                 => '商品分类',
        'form_item_category_id_tips'            => '包含子级',
        'form_item_category_id_message'         => '请选择商品分类',
        'form_item_content'                     => '规格值',
        'form_item_content_placeholder'         => '规格值（输入回车可实现多个）',
        'form_item_content_message'             => '规格值格式1~1000个字符',
    ],

    // 快递
    'express'               => [
        'base_nav_title'                        => '快递',
        // 表单
        'form_item_icon'                        => 'icon图标',
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称格式2~16个字符',
        'form_item_website_url'                 => '官网地址',
        'form_item_website_url_placeholder'     => '官网地址、以http://或https://开头',
        'form_item_website_url_message'         => '官网地址格式有误',
    ],

    // 用户中心导航
    'appcenternav'          => [
        'base_nav_title'                        => '手机用户中心导航',
        // 表单
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称格式2~60个字符',
        'form_item_desc'                        => '描述',
        'form_item_desc_message'                => '描述最多18个字符',
        'form_item_images_url'                  => '导航图标',
    ],

    // 手机首页导航
    'apphomenav'            => [
        'base_nav_title'                        => '手机首页导航',
        // 表单
        'form_item_name'                        => '名称',
        'form_item_name_message'                => '名称格式2~60个字符',
        'form_item_images_url'                  => '导航图标',
        'form_item_is_need_login'               => '是否需要登录',
    ],

    // 公共
    'common'                => [
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
];
?>