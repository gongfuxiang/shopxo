<?php

namespace Admin\Model;
use Think\Model;

/**
 * 管理员模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AdminModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 登录
		array('username', 'CheckUserName', '{%login_username_format}', 1, 'function', 4),
		array('login_pwd', 'CheckLoginPwd', '{%login_login_pwd_format}', 1, 'function', 4),

		// 添加
		array('username', '', '{%common_username_already_exist}', 1, 'unique', 1),
		array('login_pwd', 'CheckLoginPwd', '{%login_login_pwd_format}', 1, 'function', 1),

		// 编辑
		array('login_pwd', 'CheckLoginPwd', '{%login_login_pwd_format}', 2, 'function', 2),
		
		// 添加,编辑
		array('mobile', 'CheckMobile', '{%common_mobile_format_error}', 2, 'function', 3),
		array('gender', array(0,1,2), '{%common_gender_tips}', 1, 'in', 3),
		array('role_id', 'IsExistRole', '{%login_role_id_error}', 1, 'callback', 3),

		// 删除
		array('id', 'IsExistAdmin', '{%login_username_no_exist}', 1, 'callback', 5),
	);

	/**
	 * [IsExistRole 角色id是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-21T22:13:52+0800
	 * @return [boolean] [存在true, 不存在false]
	 */
	public function IsExistRole()
	{
		// 当用户操作自身的情况下不需要校验
		$admin_id = isset($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : 0;
		if(I('id') != $admin_id)
		{
			$id = $this->db(0)->table('__ROLE__')->where(array('id'=>I('role_id')))->getField('id');
			return !empty($id);
		}
		return true;
	}

	/**
	 * [IsExistAdmin 校验管理员是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 * @return [boolean] [存在true, 不存在false]
	 */
	public function IsExistAdmin()
	{
		$user = $this->db(0)->where(array('id'=>I('id')))->getField('id');
		return !empty($user);
	}
}
?>