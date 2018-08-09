<?php

/**
 * 模块语言包-个人资料
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	'personal_save_nav_title_text'		=>	'个人资料',
	'personal_nickname_text'			=>	'昵称',
	'personal_nickname_format'			=>	'昵称 2~16 个字符之间',
	'personal_signature_text'			=>	'个人签名',
	'personal_signature_format'			=>	'个人签名 0~168个字符之间',
	'personal_describe_text'			=>	'个人描述',
	'personal_describe_format'			=>	'个人描述 0~255个字符之间',
	'personal_birthday_text'			=>	'生日',
	'personal_birthday_format'			=>	'生日格式有误',

	// 个人资料展示列表
	'personal_show_list'	=>	array(
			'nickname'			=>	array('name' => '昵称'),
			'gender_text'		=>	array('name' => '性别'),
			'birthday_text'		=>	array('name' => '生日'),
			'mobile_security'	=>	array('name' => '手机号码', 'tips' => '<a href="'.U('Home/Safety/MobileInfo').'">修改</a>'),
			'email_security'	=>	array('name' => '电子邮箱', 'tips' => '<a href="'.U('Home/Safety/EmailInfo').'">修改</a>'),
			'signature'			=>	array('name' => '个人签名'),
			'describe'			=>	array('name' => '个人描述'),
			'add_time_text'		=>	array('name' => '注册时间'),
			'upd_time_text'		=>	array('name' => '最后更新时间'),
		),
);
?>