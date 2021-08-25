<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\index\controller;

use app\service\ArticleService;
use app\service\SeoService;

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
		if(empty($this->data_request['id']))
        {
            MyViewAssign('msg', '文章ID有误');
            return MyView('public/tips_error');
        }

        // 获取数据
        $id = intval($this->data_request['id']);
		$params = [
			'where'  => [
                'is_enable' => 1,
                'id'        => $id,
            ],
			'field'  => 'id,title,title_color,jump_url,content,access_count,article_category_id,seo_title,seo_keywords,seo_desc,add_time',
			'm'      => 0,
			'n'      => 1,
		];
		$ret = ArticleService::ArticleList($params);
		if(!empty($ret['data'][0]))
		{
            $article =  $ret['data'][0];

			// 访问统计
			ArticleService::ArticleAccessCountInc(['id'=>$id]);

			// 是否外部链接
			if(!empty($article['jump_url']))
			{
				return MyRedirect($article['jump_url']);
			}

			// 获取分类
			$article_category_content = ArticleService::ArticleCategoryListContent();
            MyViewAssign('category_list', $article_category_content['data']);

            // seo
            $seo_title = empty($article['seo_title']) ? $article['title'] : $article['seo_title'];
            MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle($seo_title, 2));
            if(!empty($article['seo_keywords']))
            {
                MyViewAssign('home_seo_site_keywords', $article['seo_keywords']);
            }
            if(!empty($article['seo_desc']))
            {
                MyViewAssign('home_seo_site_description', $article['seo_desc']);
            }
            
            // 钩子
            $this->PluginsHook($id, $article);

			MyViewAssign('article', $article);
			return MyView();
		}

        // 无数据
		MyViewAssign('msg', '文章不存在或已删除');
		return MyView('public/tips_error');
	}

    /**
     * 钩子处理
     * @author  whats
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     * @param   [int]             $article_id   [文章id]
     * @param   [array]           $article      [文章内容]
     */
    private function PluginsHook($article_id, &$article)
    {
        $hook_arr = [
            // 文章内容顶部钩子
            'plugins_view_article_detail_top',

            // 文章底部钩子
            'plugins_view_article_detail_bottom',

            // 文章内容顶部钩子
            'plugins_view_article_detail_content_top',

            // 文章内容底部钩子
            'plugins_view_article_detail_content_botton',

            // 文章左侧内部顶部钩子
            'plugins_view_article_detail_left_inside_top',

            // 文章左侧内部底部钩子
            'plugins_view_article_detail_left_inside_botton',
        ];
        foreach($hook_arr as $hook_name)
        {
            MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'article_id'    => $article_id,
                'article'       => &$article,
            ]));
        }
    }
}
?>