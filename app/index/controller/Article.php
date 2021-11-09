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
     * 文章详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
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
			$article_category = ArticleService::ArticleCategoryList();
            MyViewAssign('category_list', $article_category['data']);

            // 上一篇、下一篇
            MyViewAssign('last_next_data', ArticleService::ArticleLastNextData($id));

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
            $this->PluginsContentHook($id, $article);

			MyViewAssign('article', $article);
			return MyView();
		}

        // 无数据
		MyViewAssign('msg', '文章不存在或已删除');
		return MyView('public/tips_error');
	}

    /**
     * 文章分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-08
     * @desc    description
     */
    public function Category()
    {
        // 条件
        $where = ArticleService::ArticleWhere($this->data_request);

        // 总数
        $total = ArticleService::ArticleTotal($where);

        // 分页
        $page_params = [
            'number'    =>  $this->page_size,
            'total'     =>  $total,
            'where'     =>  $this->data_request,
            'page'      =>  $this->page,
            'url'       =>  MyUrl('index/article/category'),
        ];
        $page = new \base\Page($page_params);

        // 获取列表
        $data_params = [
            'm'         => $page->GetPageStarNumber(),
            'n'         => $this->page_size,
            'where'     => $where,
        ];
        $ret = ArticleService::ArticleList($data_params);

        // 获取分类
        $article_category = ArticleService::ArticleCategoryList();
        MyViewAssign('category_list', $article_category['data']);

        // 分类信息
        $category_info = ArticleService::ArticleCategoryInfo($this->data_request, $article_category['data']);
        MyViewAssign('category_info', $category_info);

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(empty($category_info) ? '所有文章' : $category_info['name'], 1));

        // 基础参数赋值
        MyViewAssign('page_html', $page->GetPageHtml());
        MyViewAssign('data_list', $ret['data']);
        MyViewAssign('params', $this->data_request);

        // 钩子
        $this->PluginsCategoryHook($ret['data'], $this->data_request);
        return MyView();
    }

    /**
     * 分类钩子处理
     * @author  whats
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     * @param   [array]           $data      [文章内容]
     * @param   [array]           $params    [输入参数]
     */
    private function PluginsCategoryHook(&$data, $params = [])
    {
        $hook_arr = [
            // 分类内容顶部钩子
            'plugins_view_article_category_top',

            // 分类底部钩子
            'plugins_view_article_category_bottom',

            // 分类内容顶部钩子
            'plugins_view_article_category_content_top',

            // 分类内容底部钩子
            'plugins_view_article_category_content_botton',

            // 分类左侧内部顶部钩子
            'plugins_view_article_category_left_inside_top',

            // 分类左侧内部底部钩子
            'plugins_view_article_category_left_inside_botton',
        ];
        foreach($hook_arr as $hook_name)
        {
            MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => &$data,
                'params'        => $params,
            ]));
        }
    }

    /**
     * 内容钩子处理
     * @author  whats
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     * @param   [int]             $article_id   [文章id]
     * @param   [array]           $article      [文章内容]
     */
    private function PluginsContentHook($article_id, &$article)
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