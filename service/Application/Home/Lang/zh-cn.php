<?php

/**
 * 模块语言包-前台公共
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	// 用户中心菜单, is_show = [0禁用, 1启用]
	'user_left_menu'		=>	array(
			array(
					'control'	=>	'Bubble',
					'action'	=>	'Index',
					'name'		=>	'冒泡广场',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-slideshare',
				),
			array(
					'name'		=>	'学生信息',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-mortar-board fs-12',
					'item'		=>	array(
							array(
									'control'	=>	'Student',
									'action'	=>	'Index',
									'name'		=>	'学生列表',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-th-list',
								)
						)
				),
			array(
					'name'		=>	'会员信息',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-user',
					'item'		=>	array(
							array(
									'control'	=>	'Personal',
									'action'	=>	'Index',
									'name'		=>	'个人资料',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-gear',
								),
							array(
									'control'	=>	'Safety',
									'action'	=>	'Index',
									'name'		=>	'安全设置',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-user-secret',
								),
							array(
									'control'	=>	'User',
									'action'	=>	'Logout',
									'name'		=>	'安全退出',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-power-off',
								),
						)
				),
		),

	// 用户中心未显示的菜单active选中映射（小写）
	'user_left_menu_hidden_active'	=>	array(
			'studentpolyinfo'		=>	'studentindex',
			'studentscoreinfo'		=>	'studentindex',
			'personalsaveinfo'		=>	'Personalindex',
			'safetyloginpwdinfo'	=>	'safetyindex',
			'safetymobileinfo'		=>	'safetyindex',
			'safetynewmobileinfo'	=>	'safetyindex',
			'safetyemailinfo'		=>	'safetyindex',
			'safetynewemailinfo'	=>	'safetyindex',
		),

	// 用户顶部导航
	'user_nav_menu'			=>	array(
			array(
					'control'	=>	'Bubble',
					'action'	=>	'Index',
					'name'		=>	'冒泡',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-slideshare',
				),
			array(
					'control'	=>	'Personal',
					'action'	=>	'Index',
					'name'		=>	'资料',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-gear',
				),
			array(
					'control'	=>	'Student',
					'action'	=>	'Index',
					'name'		=>	'学生',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-mortar-board fs-12 w-14',
				),
			array(
					'control'	=>	'Safety',
					'action'	=>	'Index',
					'name'		=>	'设置',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-user-secret',
				),
			array(
					'control'	=>	'User',
					'action'	=>	'Logout',
					'name'		=>	'退出',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-power-off',
				),
		),
);
?>