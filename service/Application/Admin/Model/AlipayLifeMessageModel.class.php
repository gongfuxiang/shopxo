<?php

namespace Admin\Model;
use Think\Model;

/**
 * 生活号消息模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeMessageModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加,编辑
        array('logo', '0,255', '{%alipay_life_logo_format}', 1, 'length', 3),
		array('name', '2,30', '{%alipay_life_name_format}', 1, 'length', 3),
        array('appid', '1,60', '{%alipay_life_appid_format}', 1, 'length', 3),
        array('rsa_public', '1,2000', '{%alipay_life_rsa_public_format}', 1, 'length', 3),
        array('rsa_private', '1,2000', '{%alipay_life_rsa_private_format}', 1, 'length', 3),
        array('out_rsa_public', '1,2000', '{%alipay_life_out_rsa_public_format}', 1, 'length', 3),
        array('alipay_life_category_id', 'require', '{%alipay_life_category_id_format}', 1, '', 3),
	);
}
?>