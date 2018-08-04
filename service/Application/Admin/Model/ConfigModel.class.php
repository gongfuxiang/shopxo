<?php

namespace Admin\Model;
use Think\Model;

/**
 * 配置模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ConfigModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加,编辑
		array('mobile', 'CheckMobile', '{%common_mobile_format_error}', 2, 'function', 3),
		array('gender', array(0,1,2), '{%common_gender_tips}', 1, 'in', 3),
		array('role_id', 'IsExistRole', '{%login_role_id_error}', 1, 'callback', 3),
	);
}
?>