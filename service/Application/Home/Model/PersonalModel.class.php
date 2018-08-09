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
		array('nickname', 'CheckNickName', '{%personal_nickname_format}', 1, 'callback', 3),
		array('gender', array(0,1,2), '{%common_gender_tips}', 1, 'in', 3),
		array('birthday', 'CheckBirthday', '{%student_birthday_format}', 1, 'callback', 3),
		array('signature', 'CheckSignature', '{%personal_signature_format}', 1, 'callback', 3),
		array('describe', 'CheckDescribe', '{%personal_describe_format}', 1, 'callback', 3),
	);

	/**
	 * [CheckNickName 昵称校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T19:29:30+0800
	 */
	public function CheckNickName()
	{
		$len = Utf8Strlen(I('nickname'));
		return ($len >= 2 && $len <= 16);
	}

	/**
	 * [CheckBirthday 生日校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T15:12:32+0800
	 */
	public function CheckBirthday()
	{
		return (preg_match('/'.L('common_regex_birthday').'/', I('birthday')) == 1) ? true : false;
	}

	/**
	 * [CheckSignature 个人签名校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T19:29:30+0800
	 */
	public function CheckSignature()
	{
		$len = Utf8Strlen(I('signature'));
		return ($len <= 168);
	}

	/**
	 * [CheckDescribe 个人描述校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T19:29:30+0800
	 */
	public function CheckDescribe()
	{
		$len = Utf8Strlen(I('describe'));
		return ($len <= 255);
	}
}
?>