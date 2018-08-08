<?php

namespace Admin\Model;
use Think\Model;

/**
 * 导航模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class NavigationModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(
		// 自定义导航
		array('name', 'CheckName', '{%navheader_name_format}', 1, 'callback', 5),
		array('url', 'CheckUrl', '{%navheader_url_format}', 1, 'callback', 5),
		array('is_show', array(0,1), '{%common_show_tips}', 1, 'in', 5),
		array('is_new_window_open', array(0,1), '{%common_new_window_open_tips}', 1, 'in', 5),

		// 文章分类导航
		array('value', 'CheckArticleCategoryValue', '{%navheader_article_category_id_format}', 1, 'callback', 6),
		array('is_show', array(0,1), '{%common_show_tips}', 1, 'in', 6),
		array('is_new_window_open', array(0,1), '{%common_new_window_open_tips}', 1, 'in', 6),

		// 自定义页面导航
		array('value', 'CheckCustomViewValue', '{%navheader_customview_id_format}', 1, 'callback', 7),
		array('is_show', array(0,1), '{%common_show_tips}', 1, 'in', 7),
		array('is_new_window_open', array(0,1), '{%common_new_window_open_tips}', 1, 'in', 7),

		// 商品分类导航
		array('value', 'CheckGoodsCategoryValue', '{%navheader_goods_category_id_format}', 1, 'callback', 8),
		array('is_show', array(0,1), '{%common_show_tips}', 1, 'in', 8),
		array('is_new_window_open', array(0,1), '{%common_new_window_open_tips}', 1, 'in', 8),


		// 删除校验是否存在子级
		array('id', 'IsExistSon', '{%common_is_exist_son_error}', 1, 'callback', 4),
	);

	/**
	 * [CheckName 导航名称校验]
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

	/**
	 * [CheckArticleCategoryValue 文章分类id校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T15:12:32+0800
	 */
	public function CheckArticleCategoryValue()
	{
		return ($this->db(0)->table('__ARTICLE_CATEGORY__')->where(array('id'=>I('value')))->count() == 1);
	}

	/**
	 * [CheckCustomViewValue 自定义页面id校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T15:12:32+0800
	 */
	public function CheckCustomViewValue()
	{
		return ($this->db(0)->table('__CUSTOM_VIEW__')->where(array('id'=>I('value')))->count() == 1);
	}

	/**
	 * [IsExistSon 校验节点下是否存在子级数据]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 */
	public function IsExistSon()
	{
		return ($this->db(0)->where(array('pid'=>I('id')))->count() == 0);
	}

	/**
	 * [CheckGoodsCategoryValue 商品分类id校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T15:12:32+0800
	 */
	public function CheckGoodsCategoryValue()
	{
		return ($this->db(0)->table('__GOODS_CATEGORY__')->where(array('id'=>I('value')))->count() == 1);
	}
}
?>