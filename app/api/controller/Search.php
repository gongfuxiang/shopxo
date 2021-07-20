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
namespace app\api\controller;

use app\service\ApiService;
use app\service\SystemBaseService;
use app\service\SearchService;

/**
 * 商品搜索
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Search extends Common
{
    /**
     * [__construct 构造方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

    /**
     * 商品搜索
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-12
     * @desc    description
     */
    public function Index()
    {
        // 搜索记录
        $this->data_post['user_id'] = isset($this->user['id']) ? $this->user['id'] : 0;
        SearchService::SearchAdd($this->data_post);

        // 获取数据
        $ret = SearchService::GoodsList($this->data_post);
        $result = $ret['data'];

        // 品牌列表
        $result['brand_list'] = SearchService::CategoryBrandList($this->data_request);

        // 指定数据
        $result['search_map_info'] = SearchService::SearchMapInfo($this->data_request);

        // 商品分类
        $result['category_list'] = SearchService::GoodsCategoryList($this->data_request);

        // 筛选价格区间
        $result['screening_price_list'] = SearchService::ScreeningPriceList($this->data_request);

        // 商品参数
        $result['goods_params_list'] = SearchService::SearchGoodsParamsValueList($this->data_request);

        // 商品规格
        $result['goods_spec_list'] = SearchService::SearchGoodsSpecValueList($this->data_request);

        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }
}
?>