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
namespace app\admin\controller;

use app\service\ArticleService;
use app\service\NavigationService;
use app\service\GoodsService;
use app\service\CustomViewService;

/**
 * 导航管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Navigation extends Common
{
	private $nav_type;

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
		$this->IsLogin();

		// 权限校验
		$this->IsPower();

		// 导航类型
		$this->nav_type = empty($this->data_request['nav_type']) ? 'header' : $this->data_request['nav_type'];
	}

	/**
     * [Index 导航列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
        // 获取列表
        $data_params = [
            'where'         => $this->form_where,
            'order_by'      => $this->form_order_by['data'],
        ];
        $data_params['where'][] = ['nav_type', '=', $this->nav_type];
        $ret = NavigationService::NavList($data_params);
		MyViewAssign('data_list', $ret['data']);

		// 一级分类
		MyViewAssign('nav_header_pid_list', NavigationService::LevelOneNav(['nav_type'=>$this->nav_type]));

		// 获取分类和文章
		$article_category_content = ArticleService::ArticleCategoryListContent();
        MyViewAssign('article_list', $article_category_content['data']);

		// 商品分类
		MyViewAssign('goods_category_list', GoodsService::GoodsCategoryAll());

		// 自定义页面
        $custom_view = CustomViewService::CustomViewList(['where'=>['is_enable'=>1], 'field'=>'id,title', 'n'=>0]);
		MyViewAssign('customview_list', $custom_view['data']);

		MyViewAssign('nav_type', $this->nav_type);
		return MyView();
	}

	/**
     * [Save 添加/编辑]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-07T21:58:19+0800
     */
	public function Save()
	{
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = $this->data_request;
        $params['nav_type'] = $this->nav_type;
        return NavigationService::NavSave($params);
	}

	/**
	 * [Delete 删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-09T21:13:47+0800
	 */
	public function Delete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始处理
        $params = $this->data_request;
        return NavigationService::NavDelete($params);
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
        $params = $this->data_request;
        return NavigationService::NavStatusUpdate($params);
	}
}
?>