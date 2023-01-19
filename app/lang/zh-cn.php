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
 * 公共语言包-中文
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return [
    // 页面公共
    'page_common'           => [
        // 基础
        'chosen_select_no_results_text'     => '没有匹配到结果',
        'error_text'                        => '异常错误',
        'reminder_title'                    => '温馨提示',
        'operate_params_error'              => '操作参数有误',
        'not_operate_error'                 => '没有相关操作',
        'not_data_error'                    => '没有相关数据',
        'select_reverse_name'               => '反选',
        'select_all_name'                   => '全选',
        'loading_tips'                      => '加载中...',
        'goods_stock_max_tips'              => '最大限购数量',
        'goods_stock_min_tips'              => '最低起购数量',
        'goods_inventory_number_tips'       => '库存数量',
        'goods_no_choice_spec_tips'         => '请选择规格',
        'goods_spec_empty_tips'             => '无规格数据',
        'goods_id_empty_tips'               => '商品ID数据',
        'input_empty_tips'                  => '请输入数据',
        'store_enabled_tips'                => '您的浏览器不支持本地存储。请禁用“专用模式”，或升级到现代浏览器。',
        // 上传下载
        'get_loading_tips'                  => '正在获取中...',
        'download_loading_tips'             => '正在下载中...',
        'update_loading_tips'               => '正在更新中...',
        'install_loading_tips'              => '正在安装中...',
        'system_download_loading_tips'      => '系统包正在下载中...',
        'upgrade_download_loading_tips'     => '升级包正在下载中...',
        // 公共common.js
        'select_not_chosen_tips'            => '请选择项',
        'select_chosen_min_tips'            => '至少选择{value}项',
        'select_chosen_max_tips'            => '最多选择{value}项',
        'upload_images_max_tips'            => '最多上传{value}张图片',
        'upload_video_max_tips'             => '最多上传{value}个视频',
        'upload_annex_max_tips'             => '最多上传{value}个附件',
        'form_config_type_params_tips'      => '表单[类型]参数配置有误',
        'form_config_value_params_tips'     => '表单[类型]参数配置有误',
        'form_call_fun_not_exist_tips'      => '表单定义的方法未定义',
        'form_config_main_tips'             => '表单[action或method]参数配置有误',
        'max_input_vars_tips'               => '请求参数数量已超出php.ini限制',
        'operate_add_name'                  => '新增',
        'operate_edit_name'                 => '编辑',
        'operate_delete_name'               => '删除',
        'upload_images_format_tips'         => '图片格式错误，请重新上传',
        'upload_video_format_tips'          => '视频格式错误，请重新上传',
        'ie_browser_tips'                   => 'ie浏览器不可用',
        'browser_api_error_tips'            => '浏览器不支持全屏API或已被禁用',
        'request_handle_loading_tips'       => '正在处理中、请稍候...',
        'params_error_tips'                 => '参数配置有误',
        'config_fun_not_exist_tips'         => '配置方法未定义',
        'delete_confirm_tips'               => '删除后不可恢复、确认操作吗？',
        'operate_confirm_tips'              => '操作后不可恢复、确认继续吗？',
        'window_close_confirm_tips'         => '您确定要关闭本页吗？',
        'fullscreen_open_name'              => '开启全屏',
        'fullscreen_exit_name'              => '退出全屏',
        'map_dragging_icon_tips'            => '拖动红色图标直接定位',
        'map_type_not_exist_tips'           => '该地图功能未定义',
        'map_address_analysis_tips'         => '您选择地址没有解析到结果！',
        'map_coordinate_tips'               => '坐标有误',
        'before_choice_data_tips'           => '请先选择数据',
        'address_data_empty_tips'           => '地址为空',
        'assembly_not_init_tips'            => '组件未初始化',
        'not_specified_container_tips'      => '未指定容器',
        'not_specified_assembly_tips'       => '未指定加载组建',
        'not_specified_form_name_tips'      => '未指定表单name名称',
        'not_load_lib_hiprint_error'        => '请先引入hiprint组件库',
    ],

    // 公共基础
    'error'                                                 => '异常错误',
    'operate_fail'                                          => '操作失败',
    'operate_success'                                       => '操作成功',
    'get_fail'                                              => '获取失败',
    'get_success'                                           => '获取成功',
    'update_fail'                                           => '更新失败',
    'update_success'                                        => '更新成功',
    'insert_fail'                                           => '添加失败',
    'insert_success'                                        => '添加成功',
    'edit_fail'                                             => '编辑失败',
    'edit_success'                                          => '编辑成功',
    'change_fail'                                           => '修改失败',
    'change_success'                                        => '修改成功',
    'delete_fail'                                           => '删除失败',
    'delete_success'                                        => '删除成功',
    'cancel_fail'                                           => '取消失败',
    'cancel_success'                                        => '取消成功',
    'close_fail'                                            => '关闭失败',
    'close_success'                                         => '关闭成功',
    'send_fail'                                             => '发送失败',
    'send_success'                                          => '发送成功',
    'join_fail'                                             => '加入失败',
    'join_success'                                          => '加入成功',
    'created_fail'                                          => '生成失败',
    'created_success'                                       => '生成成功',
    'auth_fail'                                             => '授权失败',
    'auth_success'                                          => '授权成功',
    'upload_fail'                                           => '上传失败',
    'upload_success'                                        => '上传成功',
    'apply_fail'                                            => '申请失败',
    'apply_success'                                         => '申请成功',
    'handle_fail'                                           => '处理失败',
    'handle_success'                                        => '处理成功',
    'handle_noneed'                                         => '无需处理',
    'loading_fail'                                          => '加载失败',
    'loading_success'                                       => '加载成功',
    'request_fail'                                          => '请求失败',
    'request_success'                                       => '请求成功',
    'logout_fail'                                           => '注销失败',
    'logout_success'                                        => '注销成功',
    'pay_fail'                                              => '支付失败',
    'pay_success'                                           => '支付成功',
    'no_data'                                               => '没有相关数据',
    'params_error_tips'                                     => '参数错误',
    'content_params_empty_tips'                             => '内容参数为空',
    'illegal_access_tips'                                   => '非法访问',
    'login_failure_tips'                                    => '登录失效，请重新登录',
    'upgrading_tips'                                        => '升级中...',
    'processing_tips'                                       => '处理中...',
    'searching_tips'                                        => '搜索中...',
    'controller_not_exist_tips'                             => '控制器不存在',
    'plugins_name_tips'                                     => '应用名称有误',
    // 常用
    'clear_search_where'                                    => '清除搜索条件',
    'operate_delete_tips'                                   => '删除后不可恢复、确认操作吗？',
    'home_title'                                            => '首页',
    'operate_title'                                         => '操作',
    'select_all_title'                                      => '全选',
    'reverse_select_title'                                  => '反选',
    'reverse_select_title'                                  => '反选',
    'reset_title'                                           => '重置',
    'confirm_title'                                         => '确认',
    'cancel_title'                                          => '取消',
    'search_title'                                          => '搜索',
    'setup_title'                                           => '设置',
    'edit_title'                                            => '编辑',
    'delete_title'                                          => '删除',
    'add_title'                                             => '新增',
    'submit_title'                                          => '提交',
    'detail_title'                                          => '详情',
    'view_title'                                            => '查看',
    'choice_title'                                          => '选择',
    'already_choice_title'                                  => '已选',
    'enter_title'                                           => '进入',
    'map_title'                                             => '地图',
    'view_map_title'                                        => '查看地图',
    'see_title'                                             => '看看',
    'close_title'                                           => '关闭',
    'open_title'                                            => '打开',
    'number_title'                                          => '数量',
    'spec_title'                                            => '规格',
    'inventory_title'                                       => '库存',
    'sales_title'                                           => '销量',
    'access_title'                                          => '访问',
    'hot_title'                                             => '热度',
    'favor_title'                                           => '收藏',
    'already_favor_title'                                   => '已收藏',
    'comment_title'                                         => '评价',
    'default_title'                                         => '默认',
    'setup_default_title'                                   => '设为默认',
    // 商品基础相关
    'goods_stop_sale_title'                                 => '暂停销售',
    'goods_buy_title'                                       => '立即购买',
    'goods_booking_title'                                   => '立即预约',
    'goods_show_title'                                      => '立即咨询',
    'goods_cart_title'                                      => '加入购物车',
    'goods_no_inventory_title'                              => '没货了',
    'goods_already_shelves_title'                           => '已下架',
    'goods_only_show_title'                                 => '仅展示',
    'goods_sales_price_title'                               => '销售价',
    'goods_original_price_title'                            => '原价',
    'goods_main_title'                                      => '商品',
    'goods_guess_you_like_title'                            => '猜你喜欢',
    'goods_category_title'                                  => '商品分类',
    'goods_inventory_insufficient_min_number_tips'          => '库存不足起购数',
    // 用户基础相关
    'user_no_login_tips'                                    => '请先登录',
    // 分页
    'page_each_page_name'                                   => '每页',
    'page_page_unit'                                        => '条',
    'page_data_total'                                       => '共 {:total} 条数据',
    'page_page_total'                                       => '共 {:total} 页',
    // 动态表格
    'form_table_search_first'                               => [
        'input'         => '请输入',
        'select'        => '请选择',
        'section_min'   => '最小值',
        'section_max'   => '最大值',
        'date_start'    => '开始',
        'date_end'      => '结束',
        'ym'            => '请选择年月',
    ],
    'form_table_base_detail_title'                          => '基础信息',
    'form_table_config_error_tips'                          => '动态表格配置有误',
    'form_table_column_sort_tips'                           => '可点击拖拽调整显示顺序、如需恢复点击重置即可',
    'form_table_nav_operate_data_print_name'                => '数据打印',
    'form_table_nav_operate_data_print_tips'                => '打印当前数据',
    'form_table_nav_operate_data_export_pdf_name'           => '导出PDF',
    'form_table_nav_operate_data_export_excel_name'         => '导出Excel',
    'form_table_nav_operate_data_export_tips'               => '导出当前数据',
    'form_table_nav_operate_data_list_print_tips'           => '选中列表需要打印的数据（可多选）',
    'form_table_nav_operate_data_list_export_excel_tips'    => '以搜索条件导出全部数据',
    'form_table_nav_operate_data_list_export_pdf_tips'      => '选中列表需要导出的数据（可多选）',
    'form_table_nav_operate_data_list_delete_tips'          => '选中列表需要删除的数据（可多选）',
    // 右侧导航
    'header_top_nav_right'                                  => [
        'user_center'           => '个人中心',
        'user_shop'             => '我的商城',
        'user_order'            => '我的订单',
        'favor'                 => '我的收藏',
        'goods_favor'           => '商品收藏',
        'cart'                  => '购物车',
        'message'               => '消息',
    ],
    // 用户注册类型列表
    'common_user_reg_type_list'          =>  [
        0 => ['value' => 'username', 'name' => '账号'],
        1 => ['value' => 'sms', 'name' => '短信'],
        2 => ['value' => 'email', 'name' => '邮箱'],
    ],
    // 登录方式
    'common_login_type_list'     =>  [
        0 => ['value' => 'username', 'name' => '帐号密码', 'checked' => true],
        1 => ['value' => 'email', 'name' => '邮箱验证码'],
        2 => ['value' => 'sms', 'name' => '手机验证码'],
    ],
    // 性别
    'common_gender_list'                =>  [
        0 => ['id' => 0, 'name' => '保密', 'checked' => true],
        1 => ['id' => 1, 'name' => '女'],
        2 => ['id' => 2, 'name' => '男'],
    ],
    // 关闭开启状态
    'common_close_open_list'          =>  [
        0 => ['value' => 0, 'name' => '关闭'],
        1 => ['value' => 1, 'name' => '开启'],
    ],
    // 是否启用
    'common_is_enable_tips'             =>  [
        0 => ['id' => 0, 'name' => '未启用'],
        1 => ['id' => 1, 'name' => '已启用'],
    ],
    'common_is_enable_list'             =>  [
        0 => ['id' => 0, 'name' => '不启用'],
        1 => ['id' => 1, 'name' => '启用', 'checked' => true],
    ],
    // 是否显示
    'common_is_show_list'               =>  [
        0 => ['id' => 0, 'name' => '不显示'],
        1 => ['id' => 1, 'name' => '显示', 'checked' => true],
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
        'baidu'     => ['id' => 'baidu', 'name' => '百度地图', 'checked' => true],
        'amap'      => ['id' => 'amap', 'name' => '高德地图'],
        'tencent'   => ['id' => 'tencent', 'name' => '腾讯地图'],
        'tianditu'  => ['id' => 'tianditu', 'name' => '天地图'],
    ],
    // 支付支付状态
    'common_order_pay_status'   => [
        0 => ['id' => 0, 'name' => '待支付', 'checked' => true],
        1 => ['id' => 1, 'name' => '已支付'],
        2 => ['id' => 2, 'name' => '已退款'],
        3 => ['id' => 3, 'name' => '部分退款'],
    ],
    // 订单状态
    'common_order_status'          =>  [
        0 => ['id' => 0, 'name' => '待确认', 'checked' => true],
        1 => ['id' => 1, 'name' => '待付款'],
        2 => ['id' => 2, 'name' => '待发货'],
        3 => ['id' => 3, 'name' => '待收货'],
        4 => ['id' => 4, 'name' => '已完成'],
        5 => ['id' => 5, 'name' => '已取消'],
        6 => ['id' => 6, 'name' => '已关闭'],
    ],
    // 所属平台
    'common_platform_type'          =>  [
        'pc'        => ['value' => 'pc', 'name' => 'PC网站'],
        'h5'        => ['value' => 'h5', 'name' => 'H5手机网站'],
        'ios'       => ['value' => 'ios', 'name' => '苹果APP'],
        'android'   => ['value' => 'android', 'name' => '安卓APP'],
        'weixin'    => ['value' => 'weixin', 'name' => '微信小程序'],
        'alipay'    => ['value' => 'alipay', 'name' => '支付宝小程序'],
        'baidu'     => ['value' => 'baidu', 'name' => '百度小程序'],
        'toutiao'   => ['value' => 'toutiao', 'name' => '头条小程序'],
        'qq'        => ['value' => 'qq', 'name' => 'QQ小程序'],
        'kuaishou'  => ['value' => 'kuaishou', 'name' => '快手小程序'],
    ],
    // app平台
    'common_app_type'          =>  [
        'ios'       => ['value' => 'ios', 'name' => '苹果APP'],
        'android'   => ['value' => 'android', 'name' => '安卓APP'],
    ],
    // 小程序平台
    'common_appmini_type'          =>  [
        'weixin'    => ['value' => 'weixin', 'name' => '微信小程序'],
        'alipay'    => ['value' => 'alipay', 'name' => '支付宝小程序'],
        'baidu'     => ['value' => 'baidu', 'name' => '百度小程序'],
        'toutiao'   => ['value' => 'toutiao', 'name' => '头条小程序'],
        'qq'        => ['value' => 'qq', 'name' => 'QQ小程序'],
        'kuaishou'  => ['value' => 'kuaishou', 'name' => '快手小程序'],
    ],
    // 扣除库存规则
    'common_deduction_inventory_rules_list' =>  [
        0 => ['id' => 0, 'name' => '订单确认成功'],
        1 => ['id' => 1, 'name' => '订单支付成功'],
        2 => ['id' => 2, 'name' => '订单发货'],
    ],
    // 商品增加销量规则
    'common_sales_count_inc_rules_list'     =>  [
        0 => ['id' => 0, 'name' => '订单支付'],
        1 => ['id' => 1, 'name' => '订单收货'],
    ],
    // 是否已读
    'common_is_read_list'               =>  [
        0 => ['id' => 0, 'name' => '未读', 'checked' => true],
        1 => ['id' => 1, 'name' => '已读'],
    ],
    // 消息类型
    'common_message_type_list'          =>  [
        0 => ['id' => 0, 'name' => '默认', 'checked' => true],
    ],
    // 用户积分 - 操作类型
    'common_integral_log_type_list'             =>  [
        0 => ['id' => 0, 'name' => '减少', 'checked' => true],
        1 => ['id' => 1, 'name' => '增加'],
    ],
    // 是否上架/下架
    'common_is_shelves_list'                    =>  [
        0 => ['id' => 0, 'name' => '下架'],
        1 => ['id' => 1, 'name' => '上架', 'checked' => true],
    ],
    // 是否
    'common_is_text_list'   =>  [
        0 => ['id' => 0, 'name' => '否', 'checked' => true],
        1 => ['id' => 1, 'name' => '是'],
    ],
    // 用户状态
    'common_user_status_list'           =>  [
        0 => ['id' => 0, 'name' => '正常', 'checked' => true],
        1 => ['id' => 1, 'name' => '禁止发言', 'tips' => '用户被禁止发言'],
        2 => ['id' => 2, 'name' => '禁止登录', 'tips' => '用户被禁止登录'],
        3 => ['id' => 3, 'name' => '待审核', 'tips' => '用户等待审核中'],
    ],
    // 导航数据类型
    'common_nav_type_list'              =>  [
        'custom' => ['value'=>'custom', 'name'=>'自定义'],
        'article' => ['value'=>'article', 'name'=>'文章'],
        'customview' => ['value'=>'customview', 'name'=>'自定义页面'],
        'goods_category' => ['value'=>'goods_category', 'name'=>'商品分类'],
    ],
    // 搜索框下热门关键字类型
    'common_search_keywords_type_list'      =>  [
        0 => ['value' => 0, 'name' => '关闭'],
        1 => ['value' => 1, 'name' => '自动'],
        2 => ['value' => 2, 'name' => '自定义'],
    ],
    // app事件类型
    'common_app_event_type' =>  [
        0 => ['value' => 0, 'name' => 'WEB页面'],
        1 => ['value' => 1, 'name' => '内部页面(小程序/APP内部地址)'],
        2 => ['value' => 2, 'name' => '外部小程序(同一个主体下的小程序appid)'],
        3 => ['value' => 3, 'name' => '跳转原生地图查看指定位置'],
        4 => ['value' => 4, 'name' => '拨打电话'],
    ],
    // 订单售后类型
    'common_order_aftersale_type_list' =>  [
        0 => ['value' => 0, 'name' => '仅退款', 'desc' => '未收到货(未签收),协商同意前提下', 'icon' => 'am-icon-random', 'class' => 'am-fl'],
        1 => ['value' => 1, 'name' => '退款退货', 'desc' => '已收到货,需要退换已收到的货物', 'icon' => 'am-icon-retweet', 'class' => 'am-fr'],
    ],
    // 订单售后状态
    'common_order_aftersale_status_list' =>  [
        0 => ['value' => 0, 'name' => '待确认'],
        1 => ['value' => 1, 'name' => '待退货'],
        2 => ['value' => 2, 'name' => '待审核'],
        3 => ['value' => 3, 'name' => '已完成'],
        4 => ['value' => 4, 'name' => '已拒绝'],
        5 => ['value' => 5, 'name' => '已取消'],
    ],
    // 订单售后退款方式
    'common_order_aftersale_refundment_list' =>  [
        0 => ['value' => 0, 'name' => '原路退回'],
        1 => ['value' => 1, 'name' => '退至钱包'],
        2 => ['value' => 2, 'name' => '手动处理'],
    ],
    // 商品评分
    'common_goods_comments_rating_list' =>  [
        0 => ['value'=>0, 'name'=>'未评分', 'badge'=>''],
        1 => ['value'=>1, 'name'=>'1分', 'badge'=>'danger'],
        2 => ['value'=>2, 'name'=>'2分', 'badge'=>'warning'],
        3 => ['value'=>3, 'name'=>'3分', 'badge'=>'secondary'],
        4 => ['value'=>4, 'name'=>'4分', 'badge'=>'primary'],
        5 => ['value'=>5, 'name'=>'5分', 'badge'=>'success'],
    ],
    // 商品评论业务类型
    'common_goods_comments_business_type_list' =>  [
        'order' => ['value' => 'order', 'name' => '订单'],
    ],
    // 站点类型
    'common_site_type_list' =>  [
        0 => ['value' => 0, 'name' => '快递'],
        1 => ['value' => 1, 'name' => '展示'],
        2 => ['value' => 2, 'name' => '自提'],
        3 => ['value' => 3, 'name' => '虚拟售卖'],
        4 => ['value' => 4, 'name' => '快递+自提', 'is_ext' => 1],
    ],
    // 订单类型
    'common_order_type_list' =>  [
        0 => ['value' => 0, 'name' => '快递'],
        1 => ['value' => 1, 'name' => '展示'],
        2 => ['value' => 2, 'name' => '自提'],
        3 => ['value' => 3, 'name' => '虚拟销售'],
    ],
    // 下单站点类型列表
    'common_buy_site_model_list' =>  [
        ['value' => 0, 'name' => '快递邮寄'],
        ['value' => 2, 'name' => '自提点取货'],
    ],
    // 管理员状态
    'common_admin_status_list'               =>  [
        0 => ['value' => 0, 'name' => '正常', 'checked' => true],
        1 => ['value' => 1, 'name' => '暂停'],
        2 => ['value' => 2, 'name' => '已离职'],
    ],
    // 支付日志状态
    'common_pay_log_status_list'            => [
        0 => ['value' => 0, 'name' => '待支付', 'checked' => true],
        1 => ['value' => 1, 'name' => '已支付'],
        2 => ['value' => 2, 'name' => '已关闭'],
    ],
    // 商品参数组件类型
    'common_goods_parameters_type_list'     => [
        0 => ['value' => 0, 'name' => '全部'],
        1 => ['value' => 1, 'name' => '详情', 'checked' => true],
        2 => ['value' => 2, 'name' => '基础'],
    ],
    // 商品关联排序类型
    'goods_order_by_type_list'              => [
        0 => ['value' => 'g.access_count,g.sales_count,g.id', 'name' => '综合', 'checked' => true],
        1 => ['value' => 'g.sales_count', 'name' => '销量'],
        2 => ['value' => 'g.access_count', 'name' => '热度'],
        3 => ['value' => 'g.min_price', 'name' => '价格'],
        4 => ['value' => 'g.id', 'name' => '最新'],
    ],
    // 商品关联排序规则
    'goods_order_by_rule_list'              => [
        0 => ['value' => 'desc', 'name' => '降序(desc)', 'checked' => true],
        1 => ['value' => 'asc', 'name' => '升序(asc)'],
    ],
    // 首页数据类型
    'common_site_floor_data_type_list'      => [
        0 => ['value' => 0, 'name' => '自动模式', 'checked' => true],
        1 => ['value' => 1, 'name' => '手动模式'],
        2 => ['value' => 2, 'name' => '拖拽模式'],
    ],
    // 文件上传错误码
    'common_file_upload_error_list'         =>  [
        1 => '文件大小超过服务器允许上传的最大值',
        2 => '文件大小超出浏览器限制，查看是否超过[站点设置->附件最大限制]',
        3 => '文件仅部分被上传',
        4 => '没有找到要上传的文件',
        5 => '没有找到服务器临时文件夹',
        6 => '文件写入到临时文件夹出错 ',
        7 => '文件写入失败',
        8 => '文件上传扩展没有打开',
    ],
    // -------------------- 后端相关 --------------------
    // 图片验证码
    'site_images_verify_rules_list'  => [
        0 => ['value' => 'bgcolor', 'name' => '彩色背景'],
        1 => ['value' => 'textcolor', 'name' => '彩色文本'],
        2 => ['value' => 'point', 'name' => '干扰点'],
        3 => ['value' => 'line', 'name' => '干扰线'],
    ],
    // 时区
    'site_timezone_list' => [
        'Pacific/Pago_Pago' => '(标准时-11:00) 中途岛、萨摩亚群岛',
        'Pacific/Rarotonga' => '(标准时-10:00) 夏威夷',
        'Pacific/Gambier' => '(标准时-9:00) 阿拉斯加',
        'America/Dawson' => '(标准时-8:00) 太平洋时间(美国和加拿大)',
        'America/Creston' => '(标准时-7:00) 山地时间(美国和加拿大)',
        'America/Belize' => '(标准时-6:00) 中部时间(美国和加拿大)、墨西哥城',
        'America/Eirunepe' => '(标准时-5:00) 东部时间(美国和加拿大)、波哥大',
        'America/Antigua' => '(标准时-4:00) 大西洋时间(加拿大)、加拉加斯',
        'America/Argentina/Buenos_Aires' => '(标准时-3:00) 巴西、布宜诺斯艾利斯、乔治敦',
        'America/Noronha' => '(标准时-2:00) 中大西洋',
        'Atlantic/Cape_Verde' => '(标准时-1:00) 亚速尔群岛、佛得角群岛',
        'Africa/Ouagadougou' => '(格林尼治标准时) 西欧时间、伦敦、卡萨布兰卡',
        'Europe/Andorra' => '(标准时+1:00) 中欧时间、安哥拉、利比亚',
        'Europe/Mariehamn' => '(标准时+2:00) 东欧时间、开罗，雅典',
        'Asia/Bahrain' => '(标准时+3:00) 巴格达、科威特、莫斯科',
        'Asia/Dubai' => '(标准时+4:00) 阿布扎比、马斯喀特、巴库',
        'Asia/Kolkata' => '(标准时+5:00) 叶卡捷琳堡、伊斯兰堡、卡拉奇',
        'Asia/Dhaka' => '(标准时+6:00) 阿拉木图、 达卡、新亚伯利亚',
        'Indian/Christmas' => '(标准时+7:00) 曼谷、河内、雅加达',
        'Asia/Shanghai' => '(标准时+8:00)北京、重庆、香港、新加坡',
        'Australia/Darwin' => '(标准时+9:00) 东京、汉城、大阪、雅库茨克',
        'Australia/Adelaide' => '(标准时+10:00) 悉尼、关岛',
        'Australia/Currie' => '(标准时+11:00) 马加丹、索罗门群岛',
        'Pacific/Fiji' => '(标准时+12:00) 奥克兰、惠灵顿、堪察加半岛'
    ],
    // seo
    // url模式列表
    'seo_url_model_list'        =>  [
        0 => ['value' => 0, 'name' => '兼容模式', 'checked' => true],
        1 => ['value' => 1, 'name' => 'PATHINFO模式'],
        2 => ['value' => 2, 'name' => 'PATHINFO模式+短地址'],
    ],
];
?>