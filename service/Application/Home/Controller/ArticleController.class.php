<?php

namespace Home\Controller;

use Service\ArticleService;

/**
 * 文章详情
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ArticleController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();
	}

	/**
     * [Index 文章详情]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 获取文章
		$id = intval(I('id'));
		$params = [
			'where' => ['a.is_enable'=>1, 'a.id'=>$id],
			'field' => 'a.id,a.title,a.title_color,a.content,a.access_count,a.article_category_id,a.add_time',
			'm' => 0,
			'n' => 1,
		];
		$article = ArticleService::ArticleList($params);
		if(!empty($article[0]))
		{
			// 访问统计
			ArticleService::ArticleAccessCountInc(['id'=>$id]);

			// 是否外部链接
			if(!empty($article[0]['jump_url']))
			{
				redirect($article[0]['jump_url']);
			}

			// 浏览器标题
			$this->assign('home_seo_site_title', $this->GetBrowserSeoTitle($article[0]['title'], 1));

			// 获取分类和文字
			$this->assign('category_list', ArticleService::ArticleCategoryList());

			$this->assign('article', $article[0]);
			$this->display('Index');
		} else {
			$this->assign('msg', L('article_on_exist_error'));
			$this->display('/Public/TipsError');
		}
	}
}
?>