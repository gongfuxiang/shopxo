<?php
namespace app\api\controller;

use app\service\GoodsService;

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
		$result = [
			'data_list'						=> GoodsService::HomeFloorList(),
			'common_shop_notice'			=> MyC('common_shop_notice', null, true),
			'common_app_is_enable_search'	=> (int) MyC('common_app_is_enable_search', 1),
			'common_app_is_enable_answer'	=> (int) MyC('common_app_is_enable_answer', 1),
		];

		// 返回数据
		return json(DataReturn('success', 0, $result));
	}
}
?>