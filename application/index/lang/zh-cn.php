<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

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

    
    // 个人资料展示列表
    'personal_show_list'    =>  array(
            'avatar'            =>  array('name' => '头像', 'tips' => '<a href="javascript:;" data-am-modal="{target:\'#user-avatar-popup\'}">修改</a>'),
            'nickname'          =>  array('name' => '昵称'),
            'gender_text'       =>  array('name' => '性别'),
            'birthday_text'     =>  array('name' => '生日'),
            'mobile_security'   =>  array('name' => '手机号码', 'tips' => '<a href="'.MyUrl('index/safety/MobileInfo').'">修改</a>'),
            'email_security'    =>  array('name' => '电子邮箱', 'tips' => '<a href="'.MyUrl('index/safety/EmailInfo').'">修改</a>'),
            'add_time_text'     =>  array('name' => '注册时间'),
            'upd_time_text'     =>  array('name' => '最后更新时间'),
        ),


    // 安全项列表
    'safety_panel_list'             =>  array(
            array(
                    'title'     =>  '登录密码',
                    'msg'       =>  '互联网存在被盗风险，建议您定期更改密码以保护安全。',
                    'url'       =>  MyUrl('index/safety/loginpwdinfo'),
                    'type'      =>  'loginpwd',
                ),
            array(
                    'title'     =>  '手机号码',
                    'no_msg'    =>  '您还没有绑定手机号码',
                    'ok_msg'    =>  '已绑定手机 #accounts#',
                    'tips'      =>  '可用于登录，密码找回，账户安全管理校验，接受账户提醒通知。',
                    'url'       =>  MyUrl('index/safety/mobileinfo'),
                    'type'      =>  'mobile',
                ),
            array(
                    'title'     =>  '电子邮箱',
                    'no_msg'    =>  '您还没有绑定电子邮箱',
                    'ok_msg'    =>  '已绑定电子邮箱 #accounts#',
                    'tips'      =>  '可用于登录，密码找回，账户安全管理校验，接受账户提醒邮件。',
                    'url'       =>  MyUrl('index/safety/emailinfo'),
                    'type'      =>  'email',
                ),
        ),
);
?>