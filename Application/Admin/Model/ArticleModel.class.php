<?php

namespace Admin\Model;
use Think\Model;

/**
 * 文章模型
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ArticleModel extends CommonModel
{
	// 数据自动校验
	protected $_validate = array(		
		// 添加,编辑
		array('title', '2,60', '{%article_title_format}', 1, 'length', 3),
		array('article_category_id', 'IsExistArticleClassId', '{%article_article_category_id_error}', 1, 'callback', 3),
		array('title_color', 'CheckColor', '{%common_color_format}', 2, 'function', 3),
		array('jump_url', 'CheckUrl', '{%article_jump_url_format}', 2, 'function', 3),
		array('is_enable', array(0,1), '{%common_enable_tips}', 1, 'in', 3),
		array('content', '10,105000', '{%article_content_format_mobile}', 1, 'length', 3),
	);

	/**
	 * [IsExistArticleClassId 文章分类是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T14:09:40+0800
	 * @return [boolean] [存在true, 不存在false]
	 */
	public function IsExistArticleClassId()
	{
		$temp = $this->db(0)->table('__ARTICLE_CATEGORY__')->field(array('id'))->find(I('article_category_id'));
		return !empty($temp);
	}
}
?>