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

use app\service\SeoService;
use app\service\ApiService;
use app\service\ArticleService;
use app\service\ArticleCategoryService;
use app\service\BreadcrumbService;

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
            MyViewAssign('msg', MyLang('article.article_id_params_tips'));
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
            $article = $ret['data'][0];

			// 访问统计
			ArticleService::ArticleAccessCountInc(['id'=>$id]);

			// 是否外部链接
			if(!empty($article['jump_url']))
			{
				return MyRedirect($article['jump_url']);
			}

            // 模板数据
            $assign = [
                // 文章
                'article'                   => $article,
                // 上一篇、下一篇
                'last_next_data'            => ArticleService::ArticleLastNextData($id),
                // 面包屑导航
                'breadcrumb_data'           => BreadcrumbService::Data('ArticleDetail', ['article'=>$article]),
                // 推荐文章
                'recommended_article_list'  => ArticleService::RecommendedArticleList(),
            ];

			// 所有文章分类
			$article_category = ArticleCategoryService::ArticleCategoryList();
            $assign['category_list'] = $article_category['data'];

            // seo
            $seo_title = empty($article['seo_title']) ? $article['title'] : $article['seo_title'];
            $assign['home_seo_site_title'] = SeoService::BrowserSeoTitle($seo_title, 2);
            if(!empty($article['seo_keywords']))
            {
                $assign['home_seo_site_keywords'] = $article['seo_keywords'];
            }
            if(!empty($article['seo_desc']))
            {
                $assign['home_seo_site_description'] = $article['seo_desc'];
            }

			// 数据赋值
            MyViewAssign($assign);
            // 钩子
            $this->PluginsContentHook($id, $article);
			return MyView();
		}

        // 无数据
		MyViewAssign('msg', MyLang('article.article_no_data_tips'));
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
        // post搜索
        if(IS_POST)
        {
            $request_params = [];
            if(!empty($this->data_post['awd']))
            {
                $request_params['awd'] = StrToAscii($this->data_post['awd']);
            }
            if(!empty($this->data_post['id']))
            {
                $request_params['id'] = intval($this->data_post['id']);
            }
            return MyRedirect(MyUrl('index/article/category', $request_params));
        }
        $params = $this->data_request;

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
        $data_params = array_merge($params, [
            'm'      => $page->GetPageStarNumber(),
            'n'      => $this->page_size,
            'where'  => $where,
        ]);
        $ret = ArticleService::ArticleList($data_params);

        // 关键字处理
        if(!empty($params['awd']))
        {
            $params['awd'] = AsciiToStr($params['awd']);
        }

        // 所有文章分类
        $article_category = ArticleCategoryService::ArticleCategoryList($this->data_request);

        // 分类信息
        $category_info = ArticleCategoryService::ArticleCategoryInfo($this->data_request, $article_category['data']);

        // 模板数据
        $assign = [
            // 列表数据
            'page_html'        => $page->GetPageHtml(),
            'data_list'        => $ret['data'],
            'params'           => $params,
            // 分类信息
            'category_info'    => $category_info,
            // 所有分类
            'category_list'    => $article_category['data'],
            // 面包屑导航
            'breadcrumb_data'  => BreadcrumbService::Data('ArticleCategory', ['category_info'=>$category_info]),
        ];

        // 浏览器名称
        $assign['home_seo_site_title'] = SeoService::BrowserSeoTitle((empty($params['awd']) ? '' : $params['awd'].' - ').(empty($category_info) ? MyLang('article.category_base_nav_title') : $category_info['name']), 1);

        // 数据赋值
        MyViewAssign($assign);
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
        ];
        $assign = [];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => &$data,
                'params'        => $params,
            ]);
        }
        MyViewAssign($assign);
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
        $assign = [];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'article_id'    => $article_id,
                'article'       => &$article,
            ]);
        }
        MyViewAssign($assign);
    }
}
?>