<?php

namespace Home\Model;
use Think\Model;

/**
 * 用户说说评论模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MoodCommentsModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 添加
		array('content', 'CheckContent', '{%bubble_comments_content_error}', 1, 'callback', 1),
		array('mood_id', 'CheckMoodId', '{%bubble_comments_mood_id_error}', 1, 'callback', 1),
		array('reply_id', 'CheckReplyId', '{%bubble_comments_reply_id_error}', 2, 'callback', 1),
		array('parent_id', 'CheckParentId', '{%bubble_comments_parent_id_error}', 2, 'callback', 1),
	);

	/**
	 * [CheckContent 评论内容校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T19:29:30+0800
	 * @param    [string] $value [校验值]
	 */
	public function CheckContent($value)
	{
		$len = Utf8Strlen($value);
		return ($len > 0 && $len <= 255);
	}

	/**
	 * [CheckMoodId 说说是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 * @param    [string] $value [校验值]
	 */
	public function CheckMoodId($value)
	{
		$id = $this->db(0)->table('__MOOD__')->where(array('id'=>$value))->getField('id');
		return !empty($id);
	}

	/**
	 * [CheckReplyId 被回复的评论是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 * @param    [string] $value [校验值]
	 */
	public function CheckReplyId($value)
	{
		if(empty($value))
		{
			return true;
		}
		$id = $this->db(0)->where(array('id'=>$value))->getField('id');
		return !empty($id);
	}

	/**
	 * [CheckParentId 被回复的父评论是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 * @param    [string] $value [校验值]
	 */
	public function CheckParentId($value)
	{
		if(empty($value))
		{
			return true;
		}
		$id = $this->db(0)->where(array('id'=>$value))->getField('id');
		return !empty($id);
	}
}
?>