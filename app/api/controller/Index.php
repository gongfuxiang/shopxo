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
namespace app\api\controller;

use app\service\ApiService;
use app\service\SystemBaseService;
use app\service\GoodsService;
use app\service\BannerService;
use app\service\AppHomeNavService;
use app\service\BuyService;
use app\service\LayoutService;

/**
 * 首页
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Common
{
	/**
	 * [__construct 构造方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function __construct()
    {
		// 调用父类前置方法
		parent::__construct();
	}

	/**
	 * [Index 入口]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-05-25T11:03:59+0800
	 */
	public function Index()
	{
		// 数据模式
        if(MyC('home_index_floor_data_type', 0, true) == 2)
        {
            $data_list = LayoutService::LayoutConfigData('home');
        } else {
        	$data_list = GoodsService::HomeFloorList();
        }

		// 返回数据
		$result = [
			'navigation'		=> AppHomeNavService::AppHomeNav(),
			'banner_list'		=> BannerService::Banner(),
			'data_list'			=> $data_list,
			'common_cart_total'	=> BuyService::UserCartTotal(['user'=>$this->user]),
		];
		return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
	}
}
?>