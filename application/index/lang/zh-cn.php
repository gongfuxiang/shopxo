<?php

/**
 * 模块语言包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
    // 用户中心菜单, is_show = [0禁用, 1启用]
    'user_left_menu'        =>  array(
        array(
                'control'   =>  'user',
                'action'    =>  'index',
                'name'      =>  '个人中心',
                'is_show'   =>  1,
                'icon'      =>  'am-icon-home',
            ),
        array(
                'name'      =>  '交易管理',
                'is_show'   =>  1,
                'icon'      =>  'am-icon-cube',
                'item'      =>  array(
                        array(
                                'control'   =>  'order',
                                'action'    =>  'index',
                                'name'      =>  '订单管理',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-th-list',
                            ),
                        array(
                                'control'   =>  'userfavor',
                                'action'    =>  'goods',
                                'name'      =>  '我的收藏',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-heart-o',
                            ),
                    )
            ),
        array(
                'name'      =>  '资料管理',
                'is_show'   =>  1,
                'icon'      =>  'am-icon-user',
                'item'      =>  array(
                        array(
                                'control'   =>  'personal',
                                'action'    =>  'index',
                                'name'      =>  '个人资料',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-gear',
                            ),
                        array(
                                'control'   =>  'useraddress',
                                'action'    =>  'index',
                                'name'      =>  '我的地址',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-street-view',
                            ),
                        array(
                                'control'   =>  'safety',
                                'action'    =>  'index',
                                'name'      =>  '安全设置',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-user-secret',
                            ),
                        array(
                                'control'   =>  'message',
                                'action'    =>  'index',
                                'name'      =>  '我的消息',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-bell-o',
                            ),
                        array(
                                'control'   =>  'userintegral',
                                'action'    =>  'index',
                                'name'      =>  '我的积分',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-fire',
                            ),
                        array(
                                'control'   =>  'usergoodsbrowse',
                                'action'    =>  'index',
                                'name'      =>  '我的足迹',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-lastfm',
                            ),
                        array(
                                'control'   =>  'user',
                                'action'    =>  'logout',
                                'name'      =>  '安全退出',
                                'is_show'   =>  1,
                                'icon'      =>  'am-icon-power-off',
                            ),
                    )
            ),
    ),

    // 用户
    'user_reg_there_are_accounts_text'          =>  '已有帐号？',
    'user_reg_login_immediately_text'           =>  '立即登录',
    'user_reg_pwd_text'                         =>  '设置登录密码',
    'user_reg_pwd_format'                       =>  '密码格式 6~18 个字符之间',
    'user_common_pwd_error'                     =>  '密码错误',
    'user_reg_submit_text'                      =>  '注册',
    'user_reg_forget_pwd_text'                  =>  '忘记密码？',
    'user_login_submit_text'                    =>  '登录',
    'user_login_on_accounts_text'               =>  '还没有帐号？',
    'user_login_immediately_reg_text'           =>  '立即注册',
    'user_login_pwd_text'                       =>  '登录密码',
    'user_login_accounts_text'                  =>  '手机/邮箱',
    'user_login_accounts_format'                =>  '手机/邮箱格式有误',
    'user_login_accounts_on_exist_error'        =>  '帐号不存在',
    'user_reg_no_mobile_tips'                   =>  '没手机？使用邮箱注册',
    'user_reg_no_email_tips'                    =>  '没邮箱？使用手机号码注册',

    // 用户 - 个人资料
    'personal_save_nav_title_text'      =>  '个人资料',
    'personal_nickname_text'            =>  '昵称',
    'personal_nickname_format'          =>  '昵称 2~16 个字符之间',
    'personal_birthday_text'            =>  '生日',
    'personal_birthday_format'          =>  '生日格式有误',

    // 个人资料展示列表
    'personal_show_list'    =>  array(
            'avatar'            =>  array('name' => '头像', 'tips' => '<a href="javascript:;" data-am-modal="{target:\'#user-avatar-popup\'}">修改</a>'),
            'nickname'          =>  array('name' => '昵称'),
            'gender_text'       =>  array('name' => '性别'),
            'birthday_text'     =>  array('name' => '生日'),
            'mobile_security'   =>  array('name' => '手机号码', 'tips' => '<a href="'.url('index/safety/MobileInfo').'">修改</a>'),
            'email_security'    =>  array('name' => '电子邮箱', 'tips' => '<a href="'.url('index/safety/EmailInfo').'">修改</a>'),
            'add_time_text'     =>  array('name' => '注册时间'),
            'upd_time_text'     =>  array('name' => '最后更新时间'),
        ),

    // 用户 - 地址
    'useraddress_name_text'         => '姓名',
    'useraddress_name_format'       => '姓名格式 2~16 个字符之间',

    'useraddress_alias_text'         => '别名',
    'useraddress_alias_format'       => '别名格式最多 16 个字符',

    'useraddress_tel_text'          => '电话',
    'useraddress_tel_format'        => '电话格式有误',

    'useraddress_address_text'      => '详细地址',
    'useraddress_address_format'    => '详细地址格式 1~80 个字符之间',

    // 安全
    'safety_email_send_title'               =>  '电子邮箱绑定',
    'safety_loginpwd_nav_title_text'        =>  '登录密码修改',
    'safety_original_mobile_nav_title'      =>  '原手机号码校验',
    'safety_new_mobile_nav_title'           =>  '新手机号码校验',
    'safety_original_email_nav_title'       =>  '原电子邮箱校验',
    'safety_new_email_nav_title'            =>  '新电子邮箱校验',
    'safety_my_loginpwd_text'               =>  '当前密码',
    'safety_new_loginpwd_text'              =>  '新密码',
    'safety_confirm_new_loginpwd_text'      =>  '确认密码',
    'safety_my_loginpwd_tips'               =>  '当前密码格式 6~18 个字符之间',
    'safety_new_loginpwd_tips'              =>  '新密码格式 6~18 个字符之间',
    'safety_confirm_new_loginpwd_tips'      =>  '确认密码格式 6~18 个字符之间，与新密码一致',
    'safety_confirm_new_loginpwd_error'     =>  '确认密码与新密码不一致',
    'safety_my_pwd_error'                   =>  '当前密码错误',
    'safety_original_accounts_check_error'  =>  '原帐号校验失败',

    // 安全项列表
    'safety_panel_list'             =>  array(
            array(
                    'title'     =>  '登录密码',
                    'msg'       =>  '互联网存在被盗风险，建议您定期更改密码以保护安全。',
                    'url'       =>  url('index/safety/loginpwdinfo'),
                    'type'      =>  'loginpwd',
                ),
            array(
                    'title'     =>  '手机号码',
                    'no_msg'    =>  '您还没有绑定手机号码',
                    'ok_msg'    =>  '已绑定手机 #accounts#',
                    'tips'      =>  '可用于登录，密码找回，账户安全管理校验，接受账户提醒通知。',
                    'url'       =>  url('index/safety/mobileinfo'),
                    'type'      =>  'mobile',
                ),
            array(
                    'title'     =>  '电子邮箱',
                    'no_msg'    =>  '您还没有绑定电子邮箱',
                    'ok_msg'    =>  '已绑定电子邮箱 #accounts#',
                    'tips'      =>  '可用于登录，密码找回，账户安全管理校验，接受账户提醒邮件。',
                    'url'       =>  url('index/safety/emailinfo'),
                    'type'      =>  'email',
                ),
        ),

    // 自定义页面
    'order_payment_not_data_tips'       => '没有支付方式',

    // 自定义页面
    'customview_on_exist_error'             =>  '页面不存在或已删除',

    // 确认订单
    'buy_user_address_not_data_tips'    => '没有地址',
    'buy_express_not_data_tips'         => '没有物流方式',
    'buy_payment_not_data_tips'         => '没有支付方式',
    'buy_goods_not_data_tips'           => '没有商品',

    // 文章
    'article_add_time_text'             =>  '发布时间',
    'article_access_count_text'         =>  '浏览次数',
    'article_on_exist_error'            =>  '文章不存在或已删除',
);
?>