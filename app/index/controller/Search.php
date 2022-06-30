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

        // 搜素条件
        $map = SearchService::SearchWhereHandle($this->data_request);

        // 获取商品列表
        $ret = SearchService::GoodsList($map, $this->data_request);

        // 分页
        $page_params = [
            'number'    => $ret['data']['page_size'],
            'total'     => $ret['data']['total'],
            'where'     => $this->data_request,
            'page'      => $ret['data']['page'],
            'url'       => MyUrl('index/search/index'),
            'bt_number' => IsMobile() ? 2 : 4,
        ];
        $page = new \base\Page($page_params);
        $page_html = $page->GetPageHtml();

        // 关键字处理
        $params = $this->data_request;
        if(!empty($params['wd']))
        {
            $params['wd'] = AsciiToStr($params['wd']);
        }

        // 基础参数赋值
        MyViewAssign('params', $params);
        MyViewAssign('page_html', $page_html);
        MyViewAssign('data_total', $ret['data']['total']);
        MyViewAssign('data_list', $ret['data']['data']);

        // 品牌列表
        $brand_list = SearchService::SearchMapHandle(SearchService::CategoryBrandList($this->data_request), 'bid', 'id', $this->data_request);
        MyViewAssign('brand_list', $brand_list);

        // 指定数据
        $search_map_info = SearchService::SearchMapInfo($this->data_request);
        MyViewAssign('search_map_info', $search_map_info);

        // 商品分类
        $category_list = SearchService::SearchMapHandle(SearchService::GoodsCategoryList($this->data_request), 'cid', 'id', $this->data_request);
        MyViewAssign('category_list', $category_list);

        // 筛选价格区间
        $screening_price_list = SearchService::SearchMapHandle(SearchService::ScreeningPriceList($this->data_request), 'peid', 'id', $this->data_request);
        MyViewAssign('screening_price_list', $screening_price_list);

        // 商品参数
        $goods_params_list = SearchService::SearchMapHandle(SearchService::SearchGoodsParamsValueList($map, $this->data_request), 'psid', 'id', $this->data_request, ['is_ascii'=>true, 'field'=>'value']);
        MyViewAssign('goods_params_list', $goods_params_list);

        // 商品规格
        $goods_spec_list = SearchService::SearchMapHandle(SearchService::SearchGoodsSpecValueList($map, $this->data_request), 'scid', 'id', $this->data_request, ['is_ascii'=>true, 'field'=>'value']);
        MyViewAssign('goods_spec_list', $goods_spec_list);

        // 排序方式
        MyViewAssign('map_order_by_list', SearchService::SearchMapOrderByList($this->data_request));

        // 搜索记录
        $params['user_id'] = empty($this->user) ? 0 : $this->user['id'];
        $params['search_result_data'] = $ret['data'];
        SearchService::SearchAdd($params);

        // seo
        $this->SetSeo($search_map_info, $params);

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
     * @param   [array]     $data   [条件基础数据]
     * @param   [array]     $params [输入参数]
     */
    private function SetSeo($data = [], $params = [])
    {
        // 默认关键字
        $seo_title = empty($params['wd']) ? '' : $params['wd'];

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