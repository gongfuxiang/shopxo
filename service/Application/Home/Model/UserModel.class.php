<?php

namespace Home\Model;
use Think\Model;

/**
 * 用户模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 注册
		array('pwd', 'CheckLoginPwd', '{%user_reg_pwd_format}', 1, 'function', 1),
	);
}
?>