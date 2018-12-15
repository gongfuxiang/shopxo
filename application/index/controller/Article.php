<?php
namespace app\index\controller;

use app\service\ArticleService;

/**
 * 文章详情
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Article extends Common
{
	/**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
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
		$id = input('id');
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
				return redirect($article[0]['jump_url']);
			}

			// 浏览器标题
			$this->assign('home_seo_site_title', $this->GetBrowserSeoTitle($article[0]['title'], 1));

			// 获取分类和文字
			$this->assign('category_list', ArticleService::ArticleCategoryList());

			$this->assign('article', $article[0]);
			return $this->fetch();
		} else {
			$this->assign('msg', lang('article_on_exist_error'));
			return $this->fetch('public/tips_error');
		}
	}
}
?>