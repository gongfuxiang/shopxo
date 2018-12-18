<?php

/**
 * 模块语言包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
    // 管理员
    'login_username_text'               =>  '用户名',
    'login_login_pwd_text'              =>  '登录密码',
    'login_username_format'             =>  '用户名格式 5~18 个字符（可以是字母数字下划线）',
    'login_login_pwd_format'            =>  '密码格式 6~18 个字符',
    'login_role_id_format'              =>  '请选择所属角色组',
    'login_button_text'                 =>  '登录',
    'login_forgot_pwd_text'             =>  '忘记密码?',
    'login_forgot_pwd_tips'             =>  '请联系管理员重置密码',
    'login_username_no_exist'           =>  '管理员不存在',
    'login_login_pwd_error'             =>  '密码错误',
    'login_role_id_error'               =>  '角色不存在',
    'login_login_error'                 =>  '登录失败，请稍后再试！',
    'login_login_success'               =>  '登录成功',

    // 管理员添加及编辑
    'login_total_name'                  =>  '登录次数',
    'login_last_time_name'              =>  '最后登录时间',
    'admin_add_name'                    =>  '管理员添加',
    'admin_edit_name'                   =>  '管理员编辑',
    'admin_view_role_name'              =>  '权限组',


    // 权限/菜单
    'power_level_text'                  =>  '栏目级别',
    'power_name_text'                   =>  '权限名称',
    'power_control_text'                =>  '控制器名称',
    'power_action_text'                 =>  '方法名称',
    'power_name_format'                 =>  '权限名称格式 2~8 个字符之间',
    'power_control_format'              =>  '控制器名格式 1~30 个字符之间（必须以字母开始，可以是字母数字下划线）',
    'power_action_format'               =>  '方法名格式 1~30 个字符之间（必须以字母开始，可以是字母数字下划线）',
    'power_level_format'                =>  '栏目级别选择错误',
    'power_add_name'                    =>  '权限添加',
    'power_edit_name'                   =>  '权限编辑',
    'power_no_exist_tips'               =>  '权限数据不存在',
    'power_exist_item_tips'             =>  '该权限存在子级数据',
    'power_view_have_title'             =>  '拥有权限',
    'role_view_role_text'               =>  '角色名称',
    'role_name_format'                  =>  '角色名称格式 2~8 个字符之间',
    'role_no_exist_tips'                =>  '角色数据不存在',
    'power_level_format'                =>  '栏目级别选择错误',
    'role_add_name'                     =>  '角色添加',
    'role_edit_name'                    =>  '角色编辑',
    'power_icon_text'                   =>  '图标class',
    'power_icon_format'                 =>  '图标格式 0~30 个字符之间',
    'power_icon_tips'                   =>  '参考 http://www.iconfont.cn/ 将icon放到 [ /static/admin/default/css/iconfontmenu.css ] 文件中',

    // 站点设置
    'site_site_logo_text'           =>  '选择logo',
    
    // 站点关闭状态列表
    'site_site_state_list'          =>  array(
            0 => array('value' => 0, 'name' => '关闭', 'checked' => true),
            1 => array('value' => 1, 'name' => '开启'),
        ),

    // 是否开启用户注册
    'site_user_reg_state_list'          =>  array(
            0 => array('value' => 'sms', 'name' => '短信'),
            1 => array('value' => 'email', 'name' => '邮箱'),
        ),

    // 是否开启用户登录
    'site_user_login_state_list'            =>  array(
            0 => array('value' => 0, 'name' => '关闭'),
            1 => array('value' => 1, 'name' => '开启', 'checked' => true),
        ),

    // 获取验证码-强制使用图片验证码状态列表
    'site_img_verify_state_list'        =>  array(
            0 => array('value' => 0, 'name' => '关闭'),
            1 => array('value' => 1, 'name' => '开启', 'checked' => true),
        ),

    // 时区
    'site_timezone_list' => array(
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
        'Australia/Eucla' => '(标准时+8:00)北京、重庆、香港、新加坡',
        'Australia/Darwin' => '(标准时+9:00) 东京、汉城、大阪、雅库茨克',
        'Australia/Adelaide' => '(标准时+10:00) 悉尼、关岛',
        'Australia/Currie' => '(标准时+11:00) 马加丹、索罗门群岛',
        'Pacific/Fiji' => '(标准时+12:00) 奥克兰、惠灵顿、堪察加半岛'
    ),

    // 短信
    'sms_sms_nav_name'          =>  '短信设置',
    'sms_message_nav_name'      =>  '消息模板',
    'sms_view_tips'             =>  '阿里云短信管理地址 https://dysms.console.aliyun.com/dysms.htm',

    // seo
    // url模式列表
    'seo_url_model_list'        =>  array(
            0 => array('value' => 0, 'name' => '兼容模式', 'checked' => true),
            1 => array('value' => 1, 'name' => 'PATHINFO模式'),
        ),

    // 邮箱
    'email_email_nav_name'          =>  '邮箱设置',
    'email_message_nav_name'        =>  '消息模板',
    'email_test_email_text'         =>  '测试接收的邮件地址',
    'email_test_email_tips'         =>  '请先保存配置后，再进行测试',
    'email_test_email_send_content' =>  '邮件配置-发送测试内容',

    // 用户
    'user_add_name'                 =>  '成员添加',
    'user_edit_name'                =>  '成员编辑',
    'user_so_keyword_tips'          =>  '姓名/手机/邮箱/昵称',
    'user_time_start_text'          =>  '起始时间',
    'user_time_end_text'            =>  '结束时间',
    'user_nickname_name'            =>  '昵称',
    'user_birthday_name'            =>  '生日',
    'user_nickname_format'          =>  '昵称最多 16 个字符',

    'user_birthday_format'          =>  '生日格式有误',
    'user_accounts_param_error'     =>  '至少填写一项手机或邮箱',

    'user_login_pwd_name'           =>  '登录密码',
    'user_login_pwd_format'         =>  '登录密码格式 6~18 个字符之间',

    'user_username_name'            =>  '用户名',
    'user_username_format'          =>  '用户名 2~30 个字符',

    'user_integral_name'            =>  '积分',

    'user_avatar_name'              =>  '用户头像',
    'user_province_name'            =>  '所在省',
    'user_city_name'                =>  '所在市',

    // 用户excel导出标题列表
    'excel_user_title_list'     =>  array(
            'username'      =>  array(
                    'col' => 'A',
                    'name' => '姓名',
                    'type' => 'string',
                ),
            'nickname'      =>  array(
                    'col' => 'B',
                    'name' => '昵称',
                    'type' => 'int',
                ),
            'gender_text'   =>  array(
                    'col' => 'C',
                    'name' => '性别',
                    'type' => 'string',
                ),
            'birthday_text'=>   array(
                    'col' => 'D',
                    'name' => '生日',
                    'type' => 'string',
                ),
            'mobile'        =>  array(
                    'col' => 'E',
                    'name' => '手机号码',
                    'type' => 'int',
                ),
            'email'         =>  array(
                    'col' => 'F',
                    'name' => '电子邮箱',
                    'type' => 'string',
                ),
            'province'      =>  array(
                    'col' => 'G',
                    'name' => '所在省',
                    'type' => 'string',
                ),
            'city'      =>  array(
                    'col' => 'H',
                    'name' => '所在市',
                    'type' => 'string',
                ),
            'address'       =>  array(
                    'col' => 'I',
                    'name' => '详细地址',
                    'type' => 'string',
                ),
            'add_time'      =>  array(
                    'col' => 'J',
                    'name' => '注册时间',
                    'type' => 'string',
                ),
        ),

    // 商品管理
    'goods_add_name'                    =>  '商品添加',
    'goods_edit_name'                   =>  '商品编辑',

    'goods_title_text'                  =>  '标题名称',
    'goods_title_format'                =>  '标题名称格式 2~60 个字符',

    'goods_model_text'                  =>  '商品型号',
    'goods_model_format'                =>  '商品型号格式 最多30个字符',

    'goods_category_id_text'            =>  '商品分类',
    'goods_category_id_format'          =>  '请至少选择一个商品分类',

    'goods_place_origin_name'           =>  '生产地',
    'goods_place_origin_format'         =>  '请选择生产地',

    'goods_inventory_text'              =>  '库存数量',
    'goods_inventory_format'            =>  '库存数量 1~100000000',

    'goods_inventory_unit_text'         =>  '库存单位',
    'goods_inventory_unit_format'       =>  '库存单位格式 1~6 个字符',

    'goods_original_price_icon'         =>  '原价',
    'goods_original_price_text'         =>  '原价(元)',
    'goods_price_text'                  =>  '销售价格(元)',
    'goods_price_tips'                  =>  '最多两位小数',
    'goods_price_format'                =>  '请填写有效的销售金额',

    'goods_give_integral_text'          =>  '购买赠送积分',
    'goods_give_integral_format'        =>  '购买赠送积分 0~100000000',

    'goods_buy_min_number_text'         =>  '最低起购数量',
    'goods_buy_min_number_tips'         =>  '默认数值 1',
    'goods_buy_min_number_format'       =>  '最低起购数量 1~100000000',

    'goods_buy_max_number_text'         =>  '单次最大购买数量',
    'goods_buy_max_number_tips'         =>  '单次最大数值 100000000, 小于等于0或空则不限',
    'goods_buy_max_number_format'       =>  '单次最大购买数量 1~100000000',

    'goods_is_deduction_inventory_text' =>  '扣减库存',
    'goods_is_deduction_inventory_tips' =>  '扣除规则根据后台配置->扣除库存规则而定',
    'goods_is_shelves_text'             =>  '上下架',
    'goods_is_shelves_tips'             =>  '下架后用户不可见',

    'goods_is_home_recommended_text'    =>  '首页推荐',
    'goods_is_home_recommended_tips'    =>  '推荐后在首页展示',

    'goods_images_text'                 =>  '相册',
    'goods_images_tips'                 =>  '可拖拽图片进行排序，建议图片尺寸一致',
    'goods_images_format'               =>  '请上传相册',
    'goods_content_web_format'          =>  '电脑端详情内容最多 105000 个字符',

    'goods_nav_base_name'               =>  '基础信息',
    'goods_nav_photo_name'              =>  '商品相册',
    'goods_nav_video_name'              =>  '商品视频',
    'goods_nav_attribute_name'          =>  '商品规格',
    'goods_nav_web_name'                =>  '电脑端详情',
    'goods_nav_app_name'                =>  '手机端详情',

    'goods_attribute_type_name'         =>  '标题',
    'goods_attribute_type_placeholder'  =>  '属性类型名称',
    'goods_attribute_type_format'       =>  '属性类型名称 1~10 个字符',
    'goods_attribute_type_add_sub_text' =>  '添加商品属性',

    'goods_attribute_type_type_name'    =>  '类型',
    'goods_attribute_type_type_format'  =>  '请选择属性类型',

    'goods_attribute_type_type_show'    =>  '展示',
    'goods_attribute_type_type_choose'  =>  '选择',
    'goods_attribute_add_sub_text'      =>  '添加属性',

    'goods_attribute_name'              =>  '属性',
    'goods_attribute_placeholder'       =>  '属性名称',
    'goods_attribute_format'            =>  '属性名称 1~10个字符之间',

    'goods_content_app_images_text'     =>  '图片',
    'goods_content_app_text_text'       =>  '文本内容',
    'goods_content_app_text_format'     =>  '文本内容最多 105000 个字符',
    'goods_content_app_add_sub_text'    =>  '添加手机详情',

    'goods_so_keyword_tips'             =>  '标题/型号',

    'goods_category_level_two'          => '二级',
    'goods_category_level_three'        => '三级',

    'goods_home_recommended_images_text'=> '首页推荐图片',
    'goods_home_recommended_images_tips'=> '留空则取相册第一张图',

    'goods_brand_id_text'               => '品牌',

    'goods_video_text'                  => '短视频',
    'goods_video_tips'                  => '视频比图文更有具带入感，仅支持 mp4 格式',


    // 商品分类
    'goods_category_add_name'               => '分类添加',
    'goods_category_edit_name'              => '分类编辑',

    'goods_category_big_images_text'        => '大图片',

    'goods_category_vice_name_text'         => '副名称',
    'goods_category_vice_name_format'       => '副名称最大60个字符',

    'goods_category_describe_text'          => '描述',
    'goods_category_describe_format'        => '描述最大200个字符',

    'goods_category_home_recommended_text'  => '首页推荐',

    // 订单
    'order_so_keyword_tips'         =>  '订单号/姓名/手机/地址/快递单号',
    'order_time_start_text'         =>  '起始时间',
    'order_time_end_text'           =>  '结束时间',

    'order_base_text'               =>  '基础信息',
    'order_receive_text'            =>  '收件信息',
    'order_express_text'            =>  '快递信息',
    'order_user_note_text'          =>  '用户备注',
    'order_price_th_text'           =>  '订单金额(元)',
    'order_payment_name_text'       =>  '支付方式',

    'order_confirm_time_text'       =>  '确认时间',
    'order_pay_time_text'           =>  '支付时间',
    'order_delivery_time_text'      =>  '发货时间',
    'order_cancel_time_text'        =>  '取消时间',
    'order_success_time_text'       =>  '完成时间',

    'order_delivery_popup_title'    =>  '发货操作',
    'order_express_not_data_tips'   =>  '没有快递方式',
    'order_business_express_title'  =>  '选择快递',
    'order_express_number_text'     =>  '快递单号',
    'order_express_number_format'   =>  '请填写快递单号',

    'order_order_no_text'           =>  '订单号',

    'order_price_text'              =>  '金额',
    'order_preferential_price_text' =>  '优惠',
    'order_total_price_text'        =>  '总价',
    'order_pay_price_text'          =>  '支付',

    'order_pay_popup_title'         =>  '支付操作',
    'order_business_pay_title'      =>  '选择支付',
    'order_payment_not_data_tips'   =>  '没有支付方式',

    'order_user_is_delete_text'     =>  '用户已删除',
);
?>