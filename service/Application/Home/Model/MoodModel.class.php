<?php

namespace Home\Model;
use Think\Model;

/**
 * 用户说说模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MoodModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加
		array('content', 'CheckContent', '{%bubble_mood_error}', 1, 'callback', 1),
		array('gender', array(0,1,2,3,4), '{%bubble_visible_error}', 1, 'in', 1),
	);

	/**
	 * [CheckContent 个人签名校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T19:29:30+0800
	 * @param    [string] $value [校验值]
	 */
	public function CheckContent($value)
	{
		$len = Utf8Strlen($value);
		return ($len > 0 && $len <= 168);
	}
}
?>