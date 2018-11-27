<?php

namespace Admin\Model;
use Think\Model;

/**
 * 缴费模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PayDuesModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加,编辑
		array('years', '4', '{%user_paydues_years_format}', 1, 'length', 3),
		array('month', '1,2', '{%user_paydues_month_format}', 1, 'length', 3),
		array('amount', 'CheckPrice', '{%user_dang_amount_format}', 1, 'function', 3),
		array('status', array(0,1,2), '{%user_paydues_status_format}', 1, 'in', 3),
	);
}
?>