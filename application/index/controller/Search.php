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
use app\service\SeoService;
use app\service\SearchService;
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

        // 参数初始化
        $this->ParamsInit();
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
        // 当前用户id
        $this->data_request['user_id'] = empty($this->user) ? 0 : $this->user['id'];

        // 搜索关键字
        $this->data_request['wd'] = empty($this->data_request['wd']) ? '' : (IS_POST ? trim($this->data_request['wd']) : AsciiToStr($this->data_request['wd']));
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
            // 品牌列表
            $brand_list = SearchService::CategoryBrandList($this->data_request);
            $this->assign('brand_list', $brand_list);

            // 商品分类
            $category_list = SearchService::GoodsCategoryList($this->data_request);
            $this->assign('category_list', $category_list);

            // 筛选价格区间
            $screening_price_list = SearchService::ScreeningPriceList($this->data_request);
            $this->assign('screening_price_list', $screening_price_list);

            // 商品参数
            $goods_params_list = SearchService::SearchGoodsParamsValueList($this->data_request);
            $this->assign('goods_params_list', $goods_params_list);

            // 商品规格
            $goods_spec_list = SearchService::SearchGoodsSpecValueList($this->data_request);
            $this->assign('goods_spec_list', $goods_spec_list);

            // 参数
            $this->assign('params', $this->data_request);

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
        $seo_title = $this->data_request['wd'];
        if(!empty($this->data_request['category_id']))
        {
            $category = GoodsService::GoodsCategoryRow(['id'=>$this->data_request['category_id'], 'field'=>'name,seo_title,seo_keywords,seo_desc']);
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

        // 获取商品列表
        $ret = SearchService::GoodsList($this->data_request);

        // 搜索记录
        $this->data_request['search_result_data'] = $ret['data'];
        SearchService::SearchAdd($this->data_request);

        // 无数据直接返回
        if($ret['code'] != 0 || empty($ret['data']['data']))
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
        $hook_arr = [
            // 搜索页面顶部钩子
            'plugins_view_search_top',

            // 搜索页面底部钩子
            'plugins_view_search_bottom',

            // 搜索页面顶部内部结构里面钩子
            'plugins_view_search_inside_top',

            // 搜索页面底部内部结构里面钩子
            'plugins_view_search_inside_bottom',

            // 搜索页面数据容器顶部钩子
            'plugins_view_search_data_top',

            // 搜索页面数据容器底部钩子
            'plugins_view_search_data_bottom',

            // 搜索条件顶部钩子
            'plugins_view_search_map_top',

            // 搜索页面搜索导航条顶部钩子
            'plugins_view_search_nav_top',

            // 搜索页面搜索导航条内前面钩子
            'plugins_view_search_nav_inside_begin',

            // 搜索页面搜索导航条内尾部钩子
            'plugins_view_search_nav_inside_end',

            // 搜索页面筛选条件内前面钩子
            'plugins_view_search_map_inside_begin',

            // 搜索页面筛选条件内基础底部钩子
            'plugins_view_search_map_inside_base_bottom',

            // 搜索页面筛选条件内尾部钩子
            'plugins_view_search_map_inside_end',
        ];
        foreach($hook_arr as $hook_name)
        {
            $this->assign($hook_name.'_data', Hook::listen($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'   => false,
                ]));
        }
    }
}
?>