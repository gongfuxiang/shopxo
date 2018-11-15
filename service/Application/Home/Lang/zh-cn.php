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
					'control'	=>	'User',
					'action'	=>	'Index',
					'name'		=>	'个人中心',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-home',
				),
			array(
					'name'		=>	'交易管理',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-cube',
					'item'		=>	array(
							array(
									'control'	=>	'Order',
									'action'	=>	'Index',
									'name'		=>	'订单管理',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-th-list',
								),
							array(
									'control'	=>	'UserFavor',
									'action'	=>	'Goods',
									'name'		=>	'我的收藏',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-heart-o',
								),
						)
				),
			array(
					'name'		=>	'资料管理',
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
									'control'	=>	'UserAddress',
									'action'	=>	'Index',
									'name'		=>	'我的地址',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-street-view',
								),
							array(
									'control'	=>	'Safety',
									'action'	=>	'Index',
									'name'		=>	'安全设置',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-user-secret',
								),
							array(
									'control'	=>	'Message',
									'action'	=>	'Index',
									'name'		=>	'我的消息',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-bell-o',
								),
							array(
									'control'	=>	'UserIntegral',
									'action'	=>	'Index',
									'name'		=>	'我的积分',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-fire',
								),
							array(
									'control'	=>	'UserGoodsBrowse',
									'action'	=>	'Index',
									'name'		=>	'我的足迹',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-lastfm',
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
);
?>