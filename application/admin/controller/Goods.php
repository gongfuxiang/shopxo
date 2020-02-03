<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\facade\Hook;
use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\RegionService;
use app\service\BrandService;

/**
 * 商品管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Goods extends Common
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
		$this->IsLogin();

		// 权限校验
		$this->IsPower();
	}

	/**
     * [Index 商品列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 参数
		$params = input();

		// 条件
		$where = GoodsService::GetAdminIndexWhere($params);

		// 总数
		$total = GoodsService::GoodsTotal($where);

		// 分页
		$number = MyC('admin_page_number', 10, true);
		$page_params = array(
				'number'	=>	$number,
				'total'		=>	$total,
				'where'		=>	$params,
				'page'		=>	isset($params['page']) ? intval($params['page']) : 1,
				'url'		=>	MyUrl('admin/goods/index'),
			);
		$page = new \base\Page($page_params);

		// 获取数据列表
		$data_params = [
			'where'				=> $where,
			'm'					=> $page->GetPageStarNumber(),
			'n'					=> $number,
			'is_category'		=> 1,
			'is_admin_access'	=> 1,
		];
		$ret = GoodsService::GoodsList($data_params);

		// 商品分类
		$this->assign('goods_category_list', GoodsService::GoodsCategoryAll());

		// 品牌分类
		$this->assign('brand_list', BrandService::CategoryBrand());

		// 是否上下架
		$this->assign('common_is_shelves_list', lang('common_is_shelves_list'));

		// 是否首页推荐
		$this->assign('common_is_text_list', lang('common_is_text_list'));

		$this->assign('params', $params);
		$this->assign('page_html', $page->GetPageHtml());
		$this->assign('data_list', $ret['data']);
		return $this->fetch();
	}

	/**
	 * [SaveInfo 商品添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 参数
		$params = input();

		// 商品信息
		$data = [];
		if(!empty($params['id']))
		{
			$data_params = [
				'where'				=> ['id'=>$params['id']],
				'm'					=> 0,
				'n'					=> 1,
				'is_photo'			=> 1,
				'is_content_app'	=> 1,
				'is_category'		=> 1,
			];
			$ret = GoodsService::GoodsList($data_params);
			if(empty($ret['data'][0]))
			{
				return $this->error('商品信息不存在', MyUrl('admin/goods/index'));
			}
			$data = $ret['data'][0];

			// 获取商品编辑规格
			$specifications = GoodsService::GoodsEditSpecifications($ret['data'][0]['id']);
			$this->assign('specifications', $specifications);
		}

		// 地区信息
		$this->assign('region_province_list', RegionService::RegionItems(['pid'=>0]));

		// 商品分类
		$this->assign('goods_category_list', GoodsService::GoodsCategoryAll());

		// 品牌分类
		$this->assign('brand_list', BrandService::CategoryBrand());

		// 规格扩展数据
		$goods_spec_extends = GoodsService::GoodsSpecificationsExtends($params);
		$this->assign('goods_specifications_extends', $goods_spec_extends['data']);

		// 商品编辑页面钩子
		$hook_name = 'plugins_view_admin_goods_save';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
        [
            'hook_name'    	=> $hook_name,
            'is_backend'   	=> true,
            'goods_id'      => isset($params['id']) ? $params['id'] : 0,
            'data'			=> &$data,
            'params'       	=> &$params,
        ]));

        // 编辑器文件存放地址
		$this->assign('editor_path_type', 'goods');

		// 数据
		$this->assign('data', $data);
		$this->assign('params', $params);
		return $this->fetch();
	}

	/**
	 * [Save 商品添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function Save()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return GoodsService::GoodsSave($params);
	}

	/**
	 * [Delete 商品删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function Delete()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return GoodsService::GoodsDelete($params);
	}

	/**
	 * [StatusShelves 上下架状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusShelves()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		$params['field'] = 'is_shelves';
		return GoodsService::GoodsStatusUpdate($params);
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
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		$params['field'] = 'is_home_recommended';
		return GoodsService::GoodsStatusUpdate($params);
	}
}
?>