<?php

namespace Admin\Model;
use Think\Model;

/**
 * 角色模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class RoleModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加,编辑
		array('name', 'CheckName', '{%role_name_format}', 1, 'callback', 3),
		array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),

		// 删除
		array('id', 'IsExistRole', '{%role_no_exist_tips}', 1, 'callback', 5),
	);

	/**
	 * [CheckName 权限名称校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T19:29:30+0800
	 */
	public function CheckName()
	{
		$len = Utf8Strlen(I('name'));
		return ($len >= 2 && $len <= 16);
	}

	/**
	 * [IsExistRole 校验角色是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 */
	public function IsExistRole()
	{
		$id = $this->db(0)->where(array('id'=>I('id')))->getField('id');
		return !empty($id);
	}
}
?>