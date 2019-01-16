<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\service\ArticleService;

/**
 * 文章管理
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
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 文章列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 参数
        $params = input();

        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = ArticleService::ArticleListWhere($params);

        // 获取总数
        $total = ArticleService::ArticleTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  MyUrl('admin/article/index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'     => $page->GetPageStarNumber(),
            'n'     => $number,
            'where' => $where,
            'field' => 'a.*',
        );
        $data = ArticleService::ArticleList($data_params);
        $this->assign('data_list', $data['data']);

        // 是否启用
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));

		// 是否
        $this->assign('common_is_text_list', lang('common_is_text_list'));

        // 文章分类
        $article_category = ArticleService::ArticleCategoryList(['field'=>'id,name']);
        $this->assign('article_category_list', $article_category['data']);

        // 参数
        $this->assign('params', $params);
        return $this->fetch();
	}

	/**
	 * [SaveInfo 文章添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 参数
        $params = input();

        // 数据
        if(!empty($params['id']))
        {
            // 获取列表
            $data_params = array(
                'm'     => 0,
                'n'     => 1,
                'where' => ['a.id'=>intval($params['id'])],
                'field' => 'a.*',
            );
            $data = ArticleService::ArticleList($data_params);
            $this->assign('data', empty($data['data'][0]) ? [] : $data['data'][0]);
        }

		// 是否启用
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));

		// 文章分类
        $article_category = ArticleService::ArticleCategoryList(['field'=>'id,name']);
        $this->assign('article_category_list', $article_category['data']);

		// 参数
        $this->assign('params', $params);

        // 编辑器文件存放地址
        $this->assign('editor_path_type', 'article');

        return $this->fetch();
	}

	/**
	 * [Save 文章添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function Save()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        return ArticleService::ArticleSave($params);
	}

	/**
	 * [Delete 文章删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function Delete()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        $params['admin'] = $this->admin;
        return ArticleService::ArticleDelete($params);
	}

	/**
	 * [StatusUpdate 状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusUpdate()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        $params['admin'] = $this->admin;
        $params['field'] = 'is_enable';
        return ArticleService::ArticleStatusUpdate($params);
	}

	/**
	 * [StatusHomeRecommended 是否首页推荐状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusHomeRecommended()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        $params['admin'] = $this->admin;
        $params['field'] = 'is_home_recommended';
        return ArticleService::ArticleStatusUpdate($params);
	}
}
?>