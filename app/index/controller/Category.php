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
use app\service\ApiService;

/**
 * 商品分类
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Category extends Common
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
        MyViewAssign([
            // 分类展示层级模式
            'category_level'        => MyC('common_show_goods_category_level', 0, true),
            // 浏览器名称
            'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('category.base_nav_title'), 1),
        ]);
        return MyView();
    }

    /**
     * 商品搜索数据列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-12
     * @desc    description
     */
    public function DataList()
    {
        // 搜素条件
        $map = SearchService::SearchWhereHandle($this->data_request);

        // 获取数据
        $ret = SearchService::GoodsList($map, $this->data_request);

        // 搜索记录
        $this->data_request['user_id'] = isset($this->user['id']) ? $this->user['id'] : 0;
        $this->data_request['search_result_data'] = $ret['data'];
        SearchService::SearchAdd($this->data_request);

        // 渲染html
        $ret['data']['data'] = MyView('', ['data'=>$ret['data']['data']]);

        // 返回数据
        return ApiService::ApiDataReturn($ret);
    }
}
?>