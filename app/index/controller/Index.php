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

use app\layout\service\BaseLayout;
use app\service\SeoService;
use app\service\AdminService;
use app\service\BannerService;
use app\service\GoodsService;
use app\service\ArticleService;
use app\service\OrderService;
use app\service\AppHomeNavService;
use app\service\BrandService;
use app\service\LinkService;
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
        MyViewAssign('banner_list', $banner);

        // 数据模式
        $floor_data_type = MyC('home_index_floor_data_type', 0, true);
        MyViewAssign('floor_data_type', $floor_data_type);

        // 是否设计模式
        $is_design = (!empty($this->data_request['save_url']) && isset($this->data_request['is_design']) && $this->data_request['is_design'] == 1 && $floor_data_type == 2 && AdminService::LoginInfo()) ? 1 : 0;
        MyViewAssign('is_design', $is_design);
        if($is_design == 1)
        {
            // 保存数据地址
            MyViewAssign('layout_save_url', base64_decode(urldecode($this->data_request['save_url'])));

            // 设计配置数据
            $layout_data = LayoutService::LayoutConfigAdminData('home');
            MyViewAssign('layout_data', $layout_data);

            // 页面列表
            $pages_list = BaseLayout::PagesList();
            MyViewAssign('pages_list', $pages_list);

            // 商品分类
            $goods_category = GoodsService::GoodsCategoryAll();
            MyViewAssign('goods_category_list', $goods_category);

            // 商品搜索分类（分类）
            MyViewAssign('layout_goods_category', $goods_category);
            MyViewAssign('layout_goods_category_field', 'gci.category_id');

            // 品牌
            MyViewAssign('brand_list', BrandService::CategoryBrand());

            // 静态数据
            MyViewAssign('border_style_type_list', BaseLayout::$border_style_type_list);
            MyViewAssign('goods_view_list_show_style', BaseLayout::$goods_view_list_show_style);
            MyViewAssign('many_images_view_list_show_style', BaseLayout::$many_images_view_list_show_style);

            // 首页商品排序规则
            MyViewAssign('goods_order_by_type_list', lang('goods_order_by_type_list'));
            MyViewAssign('goods_order_by_rule_list', lang('goods_order_by_rule_list'));

            // 浏览器名称
            MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('首页设计', 1));

            // 编辑器文件存放地址定义
            MyViewAssign('editor_path_type', 'index-design');

            // 加载布局样式+管理
            MyViewAssign('is_load_layout', 1);
            MyViewAssign('is_load_layout_admin', 1);
        } else {
            // 数据模式
            if($floor_data_type == 2)
            {
                // 设计配置数据
                $layout_data = LayoutService::LayoutConfigData('home');
                MyViewAssign('layout_data', $layout_data);

                // 加载布局样式
                MyViewAssign('is_load_layout', 1);
            } else {
                // H5导航
                MyViewAssign('navigation', AppHomeNavService::AppHomeNav());

                // 楼层数据
                MyViewAssign('goods_floor_list', GoodsService::HomeFloorList());

                // 文章
                $article_list = ArticleService::HomeArticleList();
                MyViewAssign('article_list', $article_list);

                // 用户订单状态
                $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1]);
                MyViewAssign('user_order_status', $user_order_status['data']);
            }
        }

        // 友情链接
        $link_list = LinkService::HomeLinkList();
        MyViewAssign('link_list', $link_list);

        // 加载百度地图api
        // 存在地图事件则载入
        if(in_array(3, array_column($banner, 'event_type')))
        {
            MyViewAssign('is_load_baidu_map_api', 1);
        }

        // 钩子
        $this->PluginsHook();
        
        return MyView();
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
            MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'    => false,
                    'user'          => $this->user,
                ]));
        }
    }
}
?>