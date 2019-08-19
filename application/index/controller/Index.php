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
        $this->assign('banner_list', BannerService::Banner());

        // H5导航
        $this->assign('navigation', AppHomeNavService::AppHomeNav());

        // 楼层数据
        $this->assign('goods_floor_list', GoodsService::HomeFloorList());

        // 新闻
        $params = [
            'where' => ['a.is_enable'=>1, 'a.is_home_recommended'=>1],
            'field' => 'a.id,a.title,a.title_color,ac.name AS category_name',
            'm' => 0,
            'n' => 9,
        ];
        $article_list = ArticleService::ArticleList($params);
        $this->assign('article_list', $article_list['data']);

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1]);
        $this->assign('user_order_status', $user_order_status['data']);

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
        // 楼层数据顶部钩子
        $hook_name = 'plugins_view_home_floor_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'user'          => $this->user,
            ]));

        // 楼层数据底部钩子
        $hook_name = 'plugins_view_home_floor_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'user'          => $this->user,
            ]));

        // 轮播混合数据底部钩子
        $hook_name = 'plugins_view_home_banner_mixed_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'user'          => $this->user,
            ]));
    }
}
?>