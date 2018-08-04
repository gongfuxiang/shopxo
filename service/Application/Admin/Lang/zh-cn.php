<?php

/**
 * 模块语言包
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	// 索引对应数据库字段名称
	// col对应excel列标记
	// type定义excel数据类型
	
	// 用户excel导出标题列表
	'excel_user_title_list'		=>	array(
			'username'		=>	array(
					'col' => 'A',
					'name' => '姓名',
					'type' => 'string',
				),
			'nickname'		=>	array(
					'col' => 'B',
					'name' => '昵称',
					'type' => 'int',
				),
			'gender_text'	=>	array(
					'col' => 'C',
					'name' => '性别',
					'type' => 'string',
				),
			'birthday_text'=>	array(
					'col' => 'D',
					'name' => '生日',
					'type' => 'string',
				),
			'mobile'		=>	array(
					'col' => 'E',
					'name' => '手机号码',
					'type' => 'int',
				),
			'email'			=>	array(
					'col' => 'F',
					'name' => '电子邮箱',
					'type' => 'string',
				),
			'organization_name'	=>	array(
					'col' => 'G',
					'name' => '地区',
					'type' => 'string',
				),
			'province'		=>	array(
					'col' => 'H',
					'name' => '所在省',
					'type' => 'string',
				),
			'city'		=>	array(
					'col' => 'I',
					'name' => '所在市',
					'type' => 'string',
				),
			'address'		=>	array(
					'col' => 'J',
					'name' => '详细地址',
					'type' => 'string',
				),
			'add_time'		=>	array(
					'col' => 'K',
					'name' => '注册时间',
					'type' => 'string',
				),
		),


	// 组织excel导出标题列表
	'excel_organization_title_list'		=>	array(
			'name'					=>	array('col' => 'A', 'name' => '组织名称'),
			'alipay_account'		=>	array('col' => 'B', 'name' => '支付宝账户'),
			'alipay_appid'			=>	array('col' => 'C', 'name' => '支付宝appid'),
			'contacts'				=>	array('col' => 'D', 'name' => '联系人'),
			'tel'					=>	array('col' => 'E', 'name' => '联系电话'),
			'email'					=>	array('col' => 'F', 'name' => '电子邮箱'),
			'address'				=>	array('col' => 'G', 'name' => '详细地址'),
			'is_enable_text'		=>	array('col' => 'H', 'name' => '是否启用'),
			'pay_time_node_text'	=>	array('col' => 'I', 'name' => '缴费时间节点'),
			'introduce'				=>	array('col' => 'J', 'name' => '介绍'),
			'add_time'				=>	array('col' => 'K', 'name' => '添加时间'),
		),

	// 交费记录导出
	'excel_paydues_title_list'			=>	array(
			'organization_name'		=>	array('col' => 'A', 'name' => '组织名称'),
			'user_name'				=>	array('col' => 'B', 'name' => '用户姓名'),
			'years_text'			=>	array('col' => 'C', 'name' => '年份'),
			'month_text'			=>	array('col' => 'D', 'name' => '月份'),
			'status_text'			=>	array('col' => 'E', 'name' => '支付状态'),
			'amount'				=>	array('col' => 'F', 'name' => '金额'),
			'pay_time_text'			=>	array('col' => 'G', 'name' => '支付时间'),
			'add_time_text'			=>	array('col' => 'H', 'name' => '添加时间'),

		),
);
?>