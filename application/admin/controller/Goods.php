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

use think\facade\Hook;
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
		// 总数
		$total = GoodsService::GoodsTotal($this->form_where);

		// 分页
		$page_params = [
			'number'	=>	$this->page_size,
			'total'		=>	$total,
			'where'		=>	$this->data_request,
			'page'		=>	$this->page,
			'url'		=>	MyUrl('admin/goods/index'),
		];
		$page = new \base\Page($page_params);

		// 获取数据列表
		$data_params = [
            'where'         => $this->form_where,
            'm'             => $page->GetPageStarNumber(),
            'n'             => $this->page_size,
            'order_by'      => $this->form_order_by['data'],
            'is_category'   => 1,
        ];
        $ret = GoodsService::GoodsList($data_params);

        // 基础参数赋值
		$this->assign('params', $this->data_request);
		$this->assign('page_html', $page->GetPageHtml());
		$this->assign('data_list', $ret['data']);
		return $this->fetch();
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
        if(!empty($this->data_request['id']))
        {
            // 条件
            $where = [
                ['is_delete_time', '=', 0],
                ['id', '=', intval($this->data_request['id'])],
            ];

            // 获取列表
            $data_params = [
                'm'                 => 0,
                'n'                 => 1,
                'where'             => $where,
                'is_photo'          => 1,
                'is_content_app'    => 1,
                'is_category'       => 1,
            ];
            $ret = GoodsService::GoodsList($data_params);
            $data = [];
            if(!empty($ret['data']) && !empty($ret['data'][0]))
            {
                $data = $ret['data'][0];

                // 获取商品编辑规格
                $specifications = GoodsService::GoodsEditSpecifications($data['id']);
                $this->assign('specifications', $specifications);

                // 获取商品编辑参数
                $parameters = GoodsService::GoodsEditParameters($data['id']);

                // 商品参数类型
                $this->assign('common_goods_parameters_type_list', lang('common_goods_parameters_type_list'));

                $this->assign('parameters', $parameters);
            }

            $this->assign('data', $data);
        }
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
		$params = $this->data_request;

		// 商品信息
		$data = [];
		if(!empty($params['id']))
		{
			// 条件
            $where = [
                ['is_delete_time', '=', 0],
                ['id', '=', intval($params['id'])],
            ];

        	// 获取数据
			$data_params = [
				'where'				=> $where,
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
			$specifications = GoodsService::GoodsEditSpecifications($data['id']);
            $this->assign('specifications', $specifications);

            // 获取商品编辑参数
            $parameters = GoodsService::GoodsEditParameters($data['id']);
            $this->assign('parameters', $parameters);
		}

		// 地区信息
		$this->assign('region_province_list', RegionService::RegionItems(['pid'=>0]));

		// 商品分类
		$this->assign('goods_category_list', GoodsService::GoodsCategoryAll());

		// 品牌
		$this->assign('brand_list', BrandService::CategoryBrand());

		// 规格扩展数据
		$goods_spec_extends = GoodsService::GoodsSpecificationsExtends($params);
		$this->assign('goods_specifications_extends', $goods_spec_extends['data']);

        // 站点类型
        $this->assign('common_site_type_list', lang('common_site_type_list'));
        // 当前系统设置的站点类型
        $this->assign('common_site_type', SystemBaseService::SiteTypeValue());

        // 商品参数类型
        $this->assign('common_goods_parameters_type_list', lang('common_goods_parameters_type_list'));

        // 商品参数模板
        $data_params = array(
            'm'     => 0,
            'n'     => 0,
            'where' => [
                ['is_enable', '=', 1],
                ['config_count', '>', 0],
            ],
            'field' => 'id,name',
        );
        $template = GoodsParamsService::GoodsParamsTemplateList($data_params);
        $this->assign('goods_template_list', $template['data']);

        // 是否拷贝
        $this->assign('is_copy', (isset($params['is_copy']) && $params['is_copy'] == 1) ? 1 : 0);

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
		$this->assign('editor_path_type', ResourcesService::EditorPathTypeValue('goods'));

		// 数据
		unset($params['id'], $params['is_copy']);
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
		$params = $this->data_post;
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
		$params = $this->data_post;
		$params['admin'] = $this->admin;
		return GoodsService::GoodsDelete($params);
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
		return GoodsService::GoodsStatusUpdate($params);
	}
}
?>