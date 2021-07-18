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

use app\service\SeoService;
use app\service\SearchService;

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
            return MyRedirect(MyUrl('index/search/index', ['wd'=>StrToAscii($keywords)]));
        }

        // 参数初始化
        $this->ParamsInit();

        // 品牌列表
        $brand_list = SearchService::CategoryBrandList($this->data_request);
        MyViewAssign('brand_list', $brand_list);

        // 指定数据
        $search_map_info = SearchService::SearchMapInfo($this->data_request);
        MyViewAssign('search_map_info', $search_map_info);

        // 商品分类
        $category_list = SearchService::GoodsCategoryList($this->data_request);
        MyViewAssign('category_list', $category_list);

        // 筛选价格区间
        $screening_price_list = SearchService::ScreeningPriceList($this->data_request);
        MyViewAssign('screening_price_list', $screening_price_list);

        // 商品参数
        $goods_params_list = SearchService::SearchGoodsParamsValueList($this->data_request);
        MyViewAssign('goods_params_list', $goods_params_list);

        // 商品规格
        $goods_spec_list = SearchService::SearchGoodsSpecValueList($this->data_request);
        MyViewAssign('goods_spec_list', $goods_spec_list);

        // 参数
        MyViewAssign('params', $this->data_request);

        // seo
        $this->SetSeo($search_map_info);

        // 钩子
        $this->PluginsHook();

        return MyView();
    }

    /**
     * seo设置
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-11
     * @desc    description
     * @param   [array]           $data [条件基础数据]
     */
    private function SetSeo($data = [])
    {
        // 默认关键字
        $seo_title = empty($this->data_request['wd']) ? '' : $this->data_request['wd'];

        // 分类、品牌
        $seo_data = empty($data['category']) ? (empty($data['brand']) ? [] : $data['brand']) : $data['category'];
        if(!empty($seo_data))
        {
            $seo_title = empty($seo_data['seo_title']) ? $seo_data['name'] : $seo_data['seo_title'];

            // 关键字和描述
            if(!empty($seo_data['seo_keywords']))
            {
                MyViewAssign('home_seo_site_keywords', $seo_data['seo_keywords']);
            }
            if(!empty($seo_data['seo_desc']))
            {
                MyViewAssign('home_seo_site_description', $seo_data['seo_desc']);
            }
        }
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(empty($seo_title) ? '商品搜索' : $seo_title, 1));
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
        MyViewAssign('data', $ret['data']['data']);
        $ret['data']['data'] = MyView('content');
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
            MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'   => false,
                ]));
        }
    }
}
?>