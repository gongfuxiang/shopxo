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
					'name'		=>	'我的交易',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-cube',
					'item'		=>	array(
							array(
									'control'	=>	'Order',
									'action'	=>	'Index',
									'name'		=>	'订单管理',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-th-list',
								)
						)
				),
			array(
					'name'		=>	'我的收藏',
					'is_show'	=>	1,
					'icon'		=>	'am-icon-heart',
					'item'		=>	array(
							array(
									'control'	=>	'UserFavor',
									'action'	=>	'Goods',
									'name'		=>	'商品收藏',
									'is_show'	=>	1,
									'icon'		=>	'am-icon-shopping-basket',
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