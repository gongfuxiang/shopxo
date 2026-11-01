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

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\ArticleCategoryService;
use app\service\NavigationService;
use app\service\GoodsCategoryService;
use app\service\CustomViewService;
use app\service\DesignService;
use app\service\PluginsService;

/**
 * 导航管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Navigation extends Base
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

		// 导航类型
		$this->nav_type = empty($this->data_request['type']) ? 'header' : $this->data_request['type'];
	}

	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 模板数据
		$assign = [
			// 导航类型
			'nav_type'				=> $this->nav_type,
			// 一级分类
			'nav_header_pid_list'	=> NavigationService::LevelOneNav(['nav_type'=>$this->nav_type]),
			// 商品分类
			'goods_category_list'	=> GoodsCategoryService::GoodsCategoryAll(),
			// 管理导航
            'nav_data'          	=> MyLang('navigation.base_nav_list'),
            // 添加类型列表
            'add_type_list'      	=> MyLang('navigation.base_add_type_list'),
            // 插件列表
            'plugins_list'          => PluginsService::PluginsHomeDataList(),
		];

        // 获取列表
        $data_params = [
            'where'         => $this->form_where,
            'order_by'      => $this->form_order_by['data'],
        ];
        $data_params['where'][] = ['nav_type', '=', $this->nav_type];
        $ret = NavigationService::NavList($data_params);
		$assign['data_list'] = $ret['data'];
		
		// 获取分类和文章
		$article_category_content = ArticleCategoryService::ArticleCategoryListContent();
		$assign['article_list'] = $article_category_content['data'];

		// 自定义页面
        $custom_view = CustomViewService::CustomViewList(['where'=>['is_enable'=>1], 'field'=>'id,name', 'n'=>0]);
		$assign['customview_list'] = $custom_view['data'];

		// 页面设计
        $design_view = DesignService::DesignList(['where'=>['is_enable'=>1], 'field'=>'id,name', 'n'=>0]);
		$assign['design_list'] = $design_view['data'];

		// 数据赋值
		MyViewAssign($assign);
		return MyView();
	}

	/**
     * 添加/编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-07T21:58:19+0800
     */
	public function Save()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(NavigationService::NavSave($params));
	}

	/**
	 * 删除
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-09T21:13:47+0800
	 */
	public function Delete()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(NavigationService::NavDelete($params));
	}

	/**
	 * 状态更新
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusUpdate()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(NavigationService::NavStatusUpdate($params));
	}
}
?>