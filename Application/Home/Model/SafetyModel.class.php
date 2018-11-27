<?php

namespace Home\Model;
use Think\Model;

/**
 * 安全设置模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SafetyModel extends CommonModel
{
	// 表名
	protected $tableName = 'user';

	// 数据自动校验
	protected $_validate = array(
		// 登录密码修改
		array('my_pwd', 'CheckLoginPwd', '{%safety_my_loginpwd_tips}', 1, 'function', 5),
		array('new_pwd', 'CheckLoginPwd', '{%safety_new_loginpwd_tips}', 1, 'function', 5),
		array('confirm_new_pwd', 'CheckLoginPwd', '{%safety_confirm_new_loginpwd_tips}', 1, 'function', 5),
		array('confirm_new_pwd', 'new_pwd', '{%safety_confirm_new_loginpwd_error}', 1, 'confirm', 5),
	);
}
?>