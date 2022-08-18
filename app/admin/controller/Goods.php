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
use app\service\SystemBaseService;
use app\service\GoodsService;
use app\service\RegionService;
use app\service\BrandService;
use app\service\GoodsParamsService;
use app\service\ResourcesService;

/**
 * 商品管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Goods extends Base
{
	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		return MyView();
	}

	/**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-05T08:21:54+0800
     */
    public function Detail()
    {
    	// 模板数据
    	$assign = [
    		// 商品参数类型
            'common_goods_parameters_type_list' => MyConst('common_goods_parameters_type_list'),
    	];
        if(!empty($this->data_detail))
        {
            // 获取商品编辑规格
            $assign['specifications'] = GoodsService::GoodsEditSpecifications($this->data_detail['id']);

            // 获取商品编辑参数
            $assign['parameters'] = GoodsService::GoodsEditParameters($this->data_detail['id']);
        }
        MyViewAssign($assign);
        return MyView();
    }

	/**
	 * 添加/编辑页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 参数
		$params = $this->data_request;

		// 是否存在数据
		$data = $this->data_detail;
		if(!empty($params['id']) && empty($data))
		{
			return $this->error('商品信息不存在', MyUrl('admin/goods/index'));
		}

		// 模板信息
		$assign = [
			// 商品参数类型
			'common_goods_parameters_type_list'	=> MyConst('common_goods_parameters_type_list'),
			// 站点类型
			'common_site_type_list'				=> MyConst('common_site_type_list'),
			// 当前系统设置的站点类型
			'common_site_type'					=> SystemBaseService::SiteTypeValue(),
			// 地区信息
			'region_province_list'				=> RegionService::RegionItems(['pid'=>0]),
			// 商品分类
			'goods_category_list' 				=> GoodsService::GoodsCategoryAll(),
			// 品牌
			'brand_list' 						=> BrandService::CategoryBrand(),
			// 编辑器文件存放地址
			'editor_path_type'					=> ResourcesService::EditorPathTypeValue('goods'),
		];

		// 商品信息
		if(!empty($data))
		{
			// 获取商品编辑规格
			$assign['specifications'] = GoodsService::GoodsEditSpecifications($data['id']);

            // 获取商品编辑参数
            $assign['parameters'] = GoodsService::GoodsEditParameters($data['id']);
		}

		// 规格扩展数据
		$goods_spec_extends = GoodsService::GoodsSpecificationsExtends($params);
		$assign['goods_specifications_extends'] = $goods_spec_extends['data'];

        // 商品参数模板
        $data_params = [
            'm'     => 0,
            'n'     => 0,
            'where' => [
                ['is_enable', '=', 1],
                ['config_count', '>', 0],
            ],
            'field' => 'id,name',
        ];
        $template = GoodsParamsService::GoodsParamsTemplateList($data_params);
        $assign['goods_template_list'] = $template['data'];

        // 是否拷贝
        $assign['is_copy'] = (isset($params['is_copy']) && $params['is_copy'] == 1) ? 1 : 0;

		// 商品编辑页面钩子
		$hook_name = 'plugins_view_admin_goods_save';
        $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
        [
            'hook_name'    	=> $hook_name,
            'is_backend'   	=> true,
            'goods_id'      => isset($params['id']) ? $params['id'] : 0,
            'data'			=> &$data,
            'params'       	=> &$params,
        ]);

		// 数据/参数
		unset($params['id'], $params['is_copy']);
		$assign['data'] = $data;
		$assign['params'] = $params;

		// 数据赋值
		MyViewAssign($assign);
		return MyView();
	}

	/**
	 * 添加/编辑
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
		$params = $this->data_post;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(GoodsService::GoodsSave($params));
	}

	/**
	 * 删除
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
		$params = $this->data_post;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(GoodsService::GoodsDelete($params));
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
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = $this->data_post;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(GoodsService::GoodsStatusUpdate($params));
	}
}
?>