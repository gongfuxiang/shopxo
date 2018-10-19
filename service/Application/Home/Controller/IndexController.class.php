<?php

namespace Home\Controller;

use Service\BannerService;
use Service\GoodsService;
use Service\ArticleService;
use Service\OrderService;

/**
 * 首页
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class IndexController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();
	}
	
	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{
		// 首页轮播
		$this->assign('banner_list', BannerService::Home());

		// 楼层数据
		$this->assign('goods_floor_list', GoodsService::HomeFloorList());

		// 新闻
		$params = [
			'where' => ['a.is_enable'=>1, 'a.is_home_recommended'=>1],
			'field' => 'a.id,a.title,a.title_color,ac.name AS category_name',
			'm' => 0,
			'n' => 9,
		];
		$this->assign('article_list', ArticleService::ArticleList($params));

		// 用户订单状态
		$user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1]);
		$this->assign('user_order_status', $user_order_status['data']);

		$this->display('Index');
	}
}
?>