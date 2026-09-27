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

use app\index\controller\Common;
use app\service\ApiService;
use app\service\SeoService;
use app\service\SearchService;
use app\service\BreadcrumbService;

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
        // 是否需要登录
        $ret = SearchService::SearchIsLoginCheck();
        if($ret['code'] != 0)
        {
            IsUserLogin();
        }

        // post搜索
        if(!empty($this->data_post['wd']))
        {
            return MyRedirect(MyUrl('index/search/index', ['wd'=>StrToAscii($this->data_post['wd'])]));
        }
        $params = $this->data_request;

        // 用户id
        $params['user_id'] = empty($this->user) ? 0 : $this->user['id'];

        // 是否禁止搜索
        $check_params = $params;
        // 关键字处理
        if(!empty($check_params['wd']))
        {
            $check_params['wd'] = AsciiToStr($check_params['wd']);
        }
        $ret = SearchService::SearchProhibitCheck($check_params);
        if($ret['code'] != 0)
        {
            // 增加搜索记录
            $check_params['search_result_data'] = $ret['msg'];
            SearchService::SearchAdd($check_params);
            // 返回错误
            MyViewAssign([
                'msg'     => $ret['msg'],
                'params'  => $check_params
            ]);
            return MyView('public/tips_error');
        }

        // 搜素条件
        $map = SearchService::SearchWhereHandle($this->data_request);

        // 商品列表改为 AJAX（DataList），首屏不查列表
        // 展示布局以后台默认为准（0九宫格、1图文列表）；刷新页面不沿用用户上次选择
        $list_layout_value = intval(MyC('home_search_goods_show_type', 0, true));

        // 面包屑导航
        $breadcrumb_data = BreadcrumbService::Data('GoodsSearch', $params);

        // 关键字处理
        if(!empty($params['wd']))
        {
            $params['wd'] = AsciiToStr($params['wd']);
        }

        // 价格滑条
        if(!empty($params['price']))
        {
            if(is_array($params['price']))
            {
                $params['price'] = reset($params['price']);
            }
            $arr = explode('-', $params['price']);
            if(count($arr) == 2)
            {
                $params['price_min'] = is_array($arr[0]) ? 0 : $arr[0];
                $params['price_max'] = is_array($arr[1]) ? 0 : $arr[1];
            }
        }
        // 防止模板输出时出现数组转字符串
        if(isset($params['price_min']) && is_array($params['price_min']))
        {
            $params['price_min'] = empty($params['price_min']) ? 0 : reset($params['price_min']);
        }
        if(isset($params['price_max']) && is_array($params['price_max']))
        {
            $params['price_max'] = empty($params['price_max']) ? 0 : reset($params['price_max']);
        }

        // 模板数据
        $assign = [
            // 基础参数
            'is_map'            => $map['is_map'],
            'params'            => $params,
            'page_html'         => '',
            'data_total'        => 0,
            'data_list'         => [],
            // 排序方式
            'map_order_by_list' => SearchService::SearchMapOrderByList($this->data_request),
            // 面包屑导航
            'breadcrumb_data'   => $breadcrumb_data,
            // 列表布局类型
            'list_layout_value' => $list_layout_value,
            // 范围滑条组件
            'is_load_jrange'    => 1,
            // 滑条价格最大金额
            'range_max_price'   => SearchService::SearchGoodsMaxPrice(),
        ];

        // 指定数据
        $assign['search_map_info'] = SearchService::SearchMapInfo($this->data_request);

        // 基础筛选项：分类 / 品牌 / 价格 / 产地；参数/规格按分类模板下拉（无分类则全部启用模板）
        $assign['brand_list'] = SearchService::SearchMapHandle(SearchService::CategoryBrandList($map, $this->data_request), 'bid', 'id', $this->data_request);
        $assign['category_list'] = SearchService::SearchMapHandle(SearchService::GoodsCategoryList($this->data_request), 'cid', 'id', $this->data_request);
        $assign['screening_price_list'] = SearchService::SearchMapHandle(SearchService::ScreeningPriceList($this->data_request), 'peid', 'id', $this->data_request);
        $assign['goods_produce_region_list'] = SearchService::SearchMapHandle(SearchService::SearchGoodsProduceRegionList($map, $this->data_request), 'poid', 'id', $this->data_request);
        $assign['goods_params_list'] = SearchService::SearchGoodsParamsTemplateFilterList($this->data_request);
        $assign['goods_spec_list'] = SearchService::SearchGoodsSpecTemplateFilterList($this->data_request);

        // seo信息
        // 默认关键字
        $seo_title = empty($params['wd']) ? '' : $params['wd'];
        if(!empty($assign['search_map_info']))
        {
            // 分类、品牌
            $seo_info = empty($assign['search_map_info']['category']) ? (empty($assign['search_map_info']['brand']) ? [] : $assign['search_map_info']['brand']) : $assign['search_map_info']['category'];
            if(!empty($seo_info))
            {
                $seo_title = empty($seo_info['seo_title']) ? $seo_info['name'] : $seo_info['seo_title'];
                // 关键字和描述
                if(!empty($seo_info['seo_keywords']))
                {
                    $assign['home_seo_site_keywords'] = $seo_info['seo_keywords'];
                }
                if(!empty($seo_info['seo_desc']))
                {
                    $assign['home_seo_site_description'] = $seo_info['seo_desc'];
                }
            }
        }
        $assign['home_seo_site_title'] = SeoService::BrowserSeoTitle(empty($seo_title) ? MyLang('search.base_nav_title') : $seo_title, 1);

        // 模板赋值
        MyViewAssign($assign);
        // 钩子
        $this->PluginsHook();
        return MyView();
    }

    /**
     * 商品搜索数据列表（AJAX）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-10
     * @desc    短地址模式路由为 search-goods → search/goodslist，勿用 search/datalist（会被 search 路由吞到 index）
     */
    public function GoodsList()
    {
        // 是否需要登录
        $ret = SearchService::SearchIsLoginCheck();
        if($ret['code'] != 0)
        {
            IsUserLogin();
        }

        // 是否禁止搜索
        $check_params = $this->data_request;
        if(!empty($check_params['wd']))
        {
            $check_params['wd'] = AsciiToStr($check_params['wd']);
        }
        $ret = SearchService::SearchProhibitCheck($check_params);
        if($ret['code'] != 0)
        {
            return ApiService::ApiDataReturn($ret);
        }

        // 搜素条件
        $map = SearchService::SearchWhereHandle($this->data_request);

        // 布局：本次请求可临时指定；未指定则用后台默认（不写 Session，刷新即恢复默认）
        if(isset($this->data_request['layout']) && $this->data_request['layout'] !== '')
        {
            $list_layout_value = empty($this->data_request['layout']) ? 0 : intval($this->data_request['layout']);
        } else {
            $list_layout_value = intval(MyC('home_search_goods_show_type', 0, true));
        }

        // 获取商品列表（列表场景关闭相册与规格，降低开销）
        $ret = SearchService::GoodsList($map, array_merge($this->data_request, [
            'is_spec'        => 0,
            'is_cart'        => 0,
            'is_photo'       => 0,
            'is_search_list' => 1,
        ]));

        // 搜索记录
        $this->data_request['user_id'] = empty($this->user) ? 0 : $this->user['id'];
        $this->data_request['search_result_data'] = isset($ret['data']) ? $ret['data'] : [];
        SearchService::SearchAdd($this->data_request);

        // 无数据时仍返回空列表结构，便于前端渲染
        if(!isset($ret['data']) || !is_array($ret['data']))
        {
            $ret['data'] = [
                'page_start' => 0,
                'page_size'  => MyC('home_search_limit_number', 20, true),
                'page'       => 1,
                'page_total' => 0,
                'total'      => 0,
                'data'       => [],
            ];
        }
        // GoodsList 无数据时 code=-1，前端仍按成功渲染空态
        if(empty($ret['data']['data']))
        {
            $ret['data']['data'] = [];
            $ret['code'] = 0;
            $ret['msg'] = empty($ret['msg']) ? MyLang('no_data') : $ret['msg'];
        } else {
            $ret['code'] = 0;
        }

        // 渲染商品 HTML（空模板名走当前 action：search/goodslist）
        $ret['data']['data'] = MyView('', [
            'data'              => $ret['data']['data'],
            'list_layout_value' => intval($list_layout_value),
        ]);

        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 参数/规格筛选项（AJAX，随分类变化刷新）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-11
     * @desc    description
     */
    public function MapFilter()
    {
        $ret = SearchService::SearchIsLoginCheck();
        if($ret['code'] != 0)
        {
            IsUserLogin();
        }

        $goods_params_list = SearchService::SearchGoodsParamsTemplateFilterList($this->data_request);
        $goods_spec_list = SearchService::SearchGoodsSpecTemplateFilterList($this->data_request);
        $html = MyView('search/module/map/content/params_spec', [
            'module_data' => [
                'goods_params_list' => $goods_params_list,
                'goods_spec_list'   => $goods_spec_list,
            ],
        ]);
        return ApiService::ApiDataReturn(DataReturn(MyLang('operate_success'), 0, [
            'html' => $html,
        ]));
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
        $assign = [];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'   => false,
                ]);
        }
        MyViewAssign($assign);
    }
}
?>
