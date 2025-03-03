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
use app\service\SlideService;
use app\service\AppHomeNavService;
use app\service\BuyService;
use app\service\LayoutService;
use app\service\ArticleService;
use app\service\MessageService;
use app\service\AppService;
use app\service\PluginsService;
use app\service\GoodsCartService;
use app\service\DiyService;

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
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-25T11:03:59+0800
     */
    public function Index()
    {
        $key = 'api_index_data_'.APPLICATION_CLIENT_TYPE;
        $result = MyCache($key);
        $result = '';
        if(empty($result) || (isset($this->data_request['is_cache']) && $this->data_request['is_cache'] == 0))
        {
            // 购物车汇总
            $cart_total = GoodsCartService::UserGoodsCartTotal(['user'=>$this->user]);

            // 数据模式（0自动模式, 1手动模式, 2拖拽模式, 3DIY模式）
            // 手机端是否DIY模式
            $data_list = DiyService::AppClientHomeDiyData();
            if(!empty($data_list))
            {
                $result = DataReturn('success', 0, [
                    'data_mode'   => 3,
                    'data_list'   => $data_list,
                    'cart_total'  => $cart_total,
                ]);
            } else {
                $data_mode = MyC('home_index_floor_data_type', 0, true);
                if($data_mode == 2)
                {
                    $data_list = LayoutService::LayoutConfigData('home');
                } else {
                    $data_list = GoodsService::HomeFloorList();
                }

                // 未读消息总数
                $message_total = MessageService::UserMessageTotal(['user'=>$this->user, 'is_more'=>1, 'is_read'=>0]);

                // 返回数据
                $result = SystemBaseService::DataReturn([
                    'data_mode'             => $data_mode,
                    'navigation'            => AppHomeNavService::AppHomeNav(),
                    'banner_list'           => SlideService::SlideList(),
                    'data_list'             => $data_list,
                    'article_list'          => ArticleService::RecommendedArticleList(),
                    'right_icon_list'       => AppService::HomeRightIconList(['message_total'=>$message_total]),
                    'cart_total'            => $cart_total,
                    'message_total'         => $message_total,
                    'plugins_sort_list'     => PluginsService::PluginsSortList(),
                ]);
            }

            // 缓存数据、没有用户登录信息则存储缓存
            if(empty($this->user))
            {
                MyCache($key, $result, 3600);
            }
        } else {
            $result['data']['is_result_data_cache'] = 1;
        }
        return ApiService::ApiDataReturn($result);
    }
}
?>