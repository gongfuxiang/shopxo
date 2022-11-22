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
use app\service\ArticleService;
use app\service\MessageService;
use app\service\AppService;
use app\service\PluginsService;
use app\service\GoodsCartService;

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

        // 购物车汇总
        $cart_total = GoodsCartService::UserGoodsCartTotal(['user'=>$this->user]);

        // 未读消息总数
        $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0];
        $message_total = MessageService::UserMessageTotal($params);

		// 返回数据
		$result = [
			'navigation'			=> AppHomeNavService::AppHomeNav(),
			'banner_list'			=> BannerService::Banner(),
			'data_list'				=> $data_list,
			'article_list'			=> ArticleService::HomeArticleList(),
			'right_icon_list'		=> AppService::HomeRightIconList(['message_total'=>$message_total]),
			'cart_total'			=> $cart_total,
			'message_total'			=> $message_total,
			'plugins_sort_list'		=> PluginsService::PluginsSortList(),
		];
		return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
	}
}
?>