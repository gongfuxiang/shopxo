<?php

namespace Home\Controller;

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
		$m = M('Article');
		$article = $m->where(array('id'=>I('id'), 'is_enable'=>1))->find();
		if(!empty($article['content']))
		{
			// 访问统计
			$m->where(array('id'=>I('id')))->setInc('access_count');

			// 是否外部链接
			if(!empty($article['jump_url']))
			{
				redirect($article['jump_url']);
			}

			// 静态资源地址处理
			$article['content'] = ContentStaticReplace($article['content'], 'get');

			// 时间
			$article['add_time'] = date('Y/m/d', $article['add_time']);

			$this->assign('article', $article);

			// 布局+模块列表
			$this->assign('data', $this->GetLayoutList('detail'));

			// 友情链接
			$this->assign('link', LayoutLink('detail', 1));

			// 浏览器标题
			$this->assign('home_seo_site_title', $this->GetBrowserSeoTitle($article['title'], MyC('home_seo_article_browser')));

			$this->display('Index');
		} else {
			$this->assign('msg', L('article_on_exist_error'));
			$this->display('/Public/TipsError');
		}
	}
}
?>