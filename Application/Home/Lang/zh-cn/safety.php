<?php

/**
 * 模块语言包-安全
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	'safety_email_send_title'				=>	'电子邮箱绑定',
	'safety_loginpwd_nav_title_text'		=>	'登录密码修改',
	'safety_original_mobile_nav_title'		=>	'原手机号码校验',
	'safety_new_mobile_nav_title'			=>	'新手机号码校验',
	'safety_original_email_nav_title'		=>	'原电子邮箱校验',
	'safety_new_email_nav_title'			=>	'新电子邮箱校验',
	'safety_my_loginpwd_text'				=>	'当前密码',
	'safety_new_loginpwd_text'				=>	'新密码',
	'safety_confirm_new_loginpwd_text'		=>	'确认密码',
	'safety_my_loginpwd_tips'				=>	'当前密码格式 6~18 个字符之间',
	'safety_new_loginpwd_tips'				=>	'新密码格式 6~18 个字符之间',
	'safety_confirm_new_loginpwd_tips'		=>	'确认密码格式 6~18 个字符之间，与新密码一致',
	'safety_confirm_new_loginpwd_error'		=>	'确认密码与新密码不一致',
	'safety_my_pwd_error'					=>	'当前密码错误',
	'safety_original_accounts_check_error'	=>	'原帐号校验失败',

	// 安全项列表
	'safety_panel_list'				=>	array(
			array(
					'title'		=>	'登录密码',
					'msg'		=>	'互联网存在被盗风险，建议您定期更改密码以保护安全。',
					'url'		=>	U('Home/Safety/LoginPwdInfo'),
					'type'		=>	'loginpwd',
				),
			array(
					'title'		=>	'手机号码',
					'no_msg'	=>	'您还没有绑定手机号码',
					'ok_msg'	=>	'已绑定手机 #accounts#',
					'tips'		=>	'可用于登录，密码找回，账户安全管理校验，接受账户提醒通知。',
					'url'		=>	U('Home/Safety/MobileInfo'),
					'type'		=>	'mobile',
				),
			array(
					'title'		=>	'电子邮箱',
					'no_msg'	=>	'您还没有绑定电子邮箱',
					'ok_msg'	=>	'已绑定电子邮箱 #accounts#',
					'tips'		=>	'可用于登录，密码找回，账户安全管理校验，接受账户提醒邮件。',
					'url'		=>	U('Home/Safety/EmailInfo'),
					'type'		=>	'email',
				),
		),
);
?>