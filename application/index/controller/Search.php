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
use app\service\SearchService;
use app\service\BrandService;
use app\service\SeoService;
use app\service\GoodsService;

/**
 * 搜索
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Search extends Common
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
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        $keywords = input('post.wd');
        if(!empty($keywords))
        {
            return redirect(MyUrl('index/search/index', ['wd'=>StrToAscii($keywords)]));
        } else {
            // 参数初始化
            $this->ParamsInit();

            // 品牌列表
            $this->assign('brand_list', BrandService::CategoryBrandList(['category_id'=>$this->params['category_id'], 'keywords'=>$this->params['wd']]));

            // 商品分类
            $this->assign('category_list', SearchService::GoodsCategoryList(['category_id'=>$this->params['category_id']]));

            // 筛选价格区间
            $this->assign('screening_price_list', SearchService::ScreeningPriceList(['field'=>'id,name']));

            // 参数
            $this->assign('params', $this->params);

            // seo
            $this->SetSeo();

            // 钩子
            $this->PluginsHook();

            return $this->fetch();
        }
    }

    /**
     * seo设置
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-02T21:29:04+0800
     */
    private function SetSeo()
    {
        $seo_title = $this->params['wd'];
        if(!empty($this->params['category_id']))
        {
            $category = GoodsService::GoodsCategoryRow(['id'=>$this->params['category_id'], 'field'=>'name,seo_title,seo_keywords,seo_desc']);
            if(!empty($category))
            {
                $seo_title = empty($category['seo_title']) ? $category['name'] : $category['seo_title'];

                // 关键字和描述
                if(!empty($category['seo_keywords']))
                {
                    $this->assign('home_seo_site_keywords', $category['seo_keywords']);
                }
                if(!empty($category['seo_desc']))
                {
                    $this->assign('home_seo_site_description', $category['seo_desc']);
                }
            }                
        }
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle(empty($seo_title) ? '商品搜索' : $seo_title, 1));
    }

    /**
     * 参数初始化
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-06-02T01:38:27+0800
     */
    private function ParamsInit()
    {
        // 品牌id
        $this->params['brand_id'] = isset($this->params['brand_id']) ? intval($this->params['brand_id']) : 0;

        // 分类id
        $this->params['category_id'] = isset($this->params['category_id']) ? intval($this->params['category_id']) : 0;

        // 筛选价格id
        $this->params['screening_price_id'] = isset($this->params['screening_price_id']) ? intval($this->params['screening_price_id']) : 0;

        // 搜索关键字
        $this->params['wd'] = empty($this->params['wd']) ? '' : (IS_AJAX ? trim($this->params['wd']) : AsciiToStr($this->params['wd']));

        // 排序方式
        $this->params['order_by_field'] = empty($this->params['order_by_field']) ? 'default' : $this->params['order_by_field'];
        $this->params['order_by_type'] = empty($this->params['order_by_type']) ? 'desc' : $this->params['order_by_type'];

        // 用户信息
        $this->params['user_id'] = isset($this->user['id']) ? $this->user['id'] : 0;
    }

    /**
     * 获取商品列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     */
    public function GoodsList()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }
        
        // 参数初始化
        $this->ParamsInit();

        // 获取商品列表
        $this->params['keywords'] = $this->params['wd'];
        $ret = SearchService::GoodsList($this->params);

        // 搜索记录
        SearchService::SearchAdd($this->params);

        // 无数据直接返回
        if(empty($ret['data']['data']) || $ret['code'] != 0)
        {
            return DataReturn('没有更多数据啦', -100);
        }

        // 返回数据html
        $this->assign('data', $ret['data']['data']);
        $ret['data']['data'] = $this->fetch('content');
        return $ret;
    }

    /**
     * 钩子处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     */
    private function PluginsHook()
    {
        // 搜索页面顶部钩子
        $hook_name = 'plugins_view_search_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面底部钩子
        $hook_name = 'plugins_view_search_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面顶部内部结构里面钩子
        $hook_name = 'plugins_view_search_inside_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面底部内部结构里面钩子
        $hook_name = 'plugins_view_search_inside_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面数据容器顶部钩子
        $hook_name = 'plugins_view_search_data_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面数据容器底部钩子
        $hook_name = 'plugins_view_search_data_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面搜索导航条顶部钩子
        $hook_name = 'plugins_view_search_nav_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面搜索导航条内前面钩子
        $hook_name = 'plugins_view_search_nav_inside_begin';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面搜索导航条内尾部钩子
        $hook_name = 'plugins_view_search_nav_inside_end';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));

        // 搜索页面筛选条件内尾部钩子
        $hook_name = 'plugins_view_search_screen_inside_end';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'    => $hook_name,
                'is_backend'   => false,
            ]));
    }
}
?>