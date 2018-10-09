<?php

namespace Home\Model;
use Think\Model;

/**
 * 个人资料模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PersonalModel extends CommonModel
{
	// 表名
	protected $tableName = 'user';

	// 数据自动校验
	protected $_validate = array(
		// 添加/编辑
		array('nickname', '2,16', '{%personal_nickname_format}', 1, 'length', 3),
		array('gender', array(0,1,2), '{%common_gender_tips}', 1, 'in', 3),
	);
}
?>