<?php

namespace Admin\Model;
use Think\Model;

/**
 * 支付方式模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PaymentModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加,编辑
        array('logo', '0,255', '{%payment_logo_format}', 1, 'length', 3),
		array('name', '1,30', '{%payment_name_format}', 1, 'length', 3),
        array('payment', '0,255', '{%payment_payment_format}', 1, 'length', 3),
        array('desc', '0,255', '{%payment_desc_format}', 1, 'length', 3),
        array('apply_terminal', 'require', '{%payment_apply_terminal_format}', 1, '', 3),
		array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),
	);
}
?>