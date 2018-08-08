<?php

namespace Admin\Model;
use Think\Model;

/**
 * 友情链接模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class LinkModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 自定义
		array('name', 'CheckName', '{%link_name_format}', 1, 'callback', 3),
		array('describe', 'CheckDescribe', '{%link_describe_format}', 2, 'callback', 3),
		array('url', 'CheckUrl', '{%link_url_format}', 1, 'callback', 3),
		array('is_new_window_open', array(0,1), '{%common_new_window_open_tips}', 1, 'in', 3),
		array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),
	);

	/**
	 * [CheckName 名称校验]
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
	 * [CheckDescribe 描述校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T19:29:30+0800
	 */
	public function CheckDescribe()
	{
		$len = Utf8Strlen(I('describe'));
		return ($len <= 60);
	}

	/**
	 * [CheckUrl url地址校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T15:12:32+0800
	 */
	public function CheckUrl()
	{
		return (preg_match('/'.L('common_regex_url').'/', I('url')) == 1) ? true : false;
	}
}
?>