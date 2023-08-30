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
use app\service\GoodsCategoryService;
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

        // web端首页状态
        $this->SiteStstusCheck('_web_home');
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
        // 数据模式
        $floor_data_type = MyC('home_index_floor_data_type', 0, true);

        // 是否设计模式
        $admin = AdminService::LoginInfo();
        $is_design = (!empty($this->data_request['save_url']) && isset($this->data_request['is_design']) && $this->data_request['is_design'] == 1 && $floor_data_type == 2 && !empty($admin)) ? 1 : 0;

        // 模板数据
        $assign = [
            // 数据模式
            'floor_data_type'   => $floor_data_type,
            // 是否设计模式
            'is_design'         => $is_design,
            // 首页轮播
            'banner_list'       => BannerService::Banner(),
            // 手机默认下导航
            'navigation'        => IsMobile() ? AppHomeNavService::AppHomeNav() : [],
            // 文章
            'article_list'      => ArticleService::HomeArticleList(),
        ];

        // 用户订单状态
        $user_order_status = OrderService::OrderStatusStepTotal(['user_type'=>'user', 'user'=>$this->user, 'is_comments'=>1]);
        $assign['user_order_status'] = $user_order_status['data'];

        // 是否设计模式
        if($is_design == 1)
        {
            // 商品分类
            $goods_category = GoodsCategoryService::GoodsCategoryAll();

            // 保存数据地址
            $assign['layout_save_url'] = base64_decode(urldecode($this->data_request['save_url']));

            // 设计配置数据
            $assign['layout_data'] = LayoutService::LayoutConfigAdminData('home');

            // 页面列表
            $assign['pages_list'] = BaseLayout::PagesList();

            // 商品分类
            $assign['goods_category_list'] = $goods_category;

            // 商品搜索分类（分类）
            $assign['layout_goods_category'] = $goods_category;
            $assign['layout_goods_category_field'] = 'gci.category_id';

            // 品牌
            $assign['brand_list'] = BrandService::CategoryBrand();

            // 静态数据
            $assign['border_style_type_list'] = BaseLayout::ConstData('border_style_type_list');
            $assign['goods_view_list_show_style'] = BaseLayout::ConstData('goods_view_list_show_style');
            $assign['many_images_view_list_show_style'] = BaseLayout::ConstData('many_images_view_list_show_style');
            $assign['images_text_view_list_show_style'] = BaseLayout::ConstData('images_text_view_list_show_style');
            $assign['images_magic_cube_view_list_show_style'] = BaseLayout::ConstData('images_magic_cube_view_list_show_style');

            // 首页商品排序规则
            $assign['common_goods_order_by_type_list'] = MyConst('common_goods_order_by_type_list');
            $assign['common_goods_order_by_rule_list'] = MyConst('common_goods_order_by_rule_list');

            // 浏览器名称
            $assign['home_seo_site_title'] = SeoService::BrowserSeoTitle(MyLang('index.design_browser_seo_title'), 1);

            // 编辑器文件存放地址定义
            $assign['editor_path_type'] = 'index-design';

            // 加载布局样式+管理
            $assign['is_load_layout'] = 1;
            $assign['is_load_layout_admin'] = 1;
        } else {
            // 数据模式
            if($floor_data_type == 2)
            {
                // 设计配置数据
                $assign['layout_data'] = LayoutService::LayoutConfigData('home');

                // 加载布局样式
                $assign['is_load_layout'] = 1;
            } else {
                // 楼层数据
                $assign['goods_floor_list'] = GoodsService::HomeFloorList();
            }
        }

        // 友情链接
        $assign['link_list'] = LinkService::HomeLinkList();

        // 数据赋值
        MyViewAssign($assign);
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
        $assign = [];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'    => false,
                    'user'          => $this->user,
                ]);
        }
        MyViewAssign($assign);
    }
}
?>