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
namespace app\index\controller;

use think\facade\Hook;
use app\service\BannerService;
use app\service\GoodsService;
use app\service\ArticleService;
use app\service\OrderService;
use app\service\AppHomeNavService;

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
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-02T11:11:49+0800
     */
    public function Index()
    {
        // 首页轮播
        $banner = BannerService::Banner();
        $this->assign('banner_list', $banner);

        // H5导航
        $this->assign('navigation', AppHomeNavService::AppHomeNav());

        // 楼层数据
        $this->assign('goods_floor_list', GoodsService::HomeFloorList());

        // 文章
        $params = [
            'where' => ['is_enable'=>1, 'is_home_recommended'=>1],
            'field' => 'id,title,title_color,article_category_id',
            'm' => 0,
            'n' => 9,
        ];
        $article_list = ArticleService::ArticleList($params);
        $this->assign('article_list', $article_list['data']);

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1]);
        $this->assign('user_order_status', $user_order_status['data']);

        // 加载百度地图api
        // 存在地图事件则载入
        if(in_array(3, array_column($banner, 'event_type')))
        {
            $this->assign('is_load_baidu_map_api', 1);
        }

        // 钩子
        $this->PluginsHook();
        
        return $this->fetch();
    }

    /**
     * 钩子处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private function PluginsHook($params = [])
    {
        $hook_arr = [
            // 楼层数据顶部钩子
            'plugins_view_home_floor_top',

            // 楼层数据底部钩子
            'plugins_view_home_floor_bottom',

            // 轮播混合数据底部钩子
            'plugins_view_home_banner_mixed_bottom',
        ];
        foreach($hook_arr as $hook_name)
        {
            $this->assign($hook_name.'_data', Hook::listen($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'    => false,
                    'user'          => $this->user,
                ]));
        }
    }
}
?>