<?php

namespace Home\Controller;

use Service\SearchService;
use Service\BrandService;

/**
 * 搜索
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SearchController extends CommonController
{
    private $params;

    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 品牌id
        $this->params['brand_id'] = intval(I('brand_id', 0));

        // 分类id
        $this->params['category_id'] = intval(I('category_id', 0));

        // 筛选价格id
        $this->params['screening_price_id'] = intval(I('screening_price_id', 0));

        // 搜索关键字
        $this->params['keywords'] = trim(I('keywords'));

        // 排序方式
        $this->params['order_type'] = I('order_type', 'default');
        $this->params['order_way'] = I('order_way', 'desc');
    }
    
    /**
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        if(IS_POST)
        {
            $this->redirect('Home/Search/Index', ['keywords'=>$this->params['keywords']]);
        } else {
            // 搜索记录
            SearchService::SearchAdd($this->params);

            // 品牌列表
            $this->assign('brand_list', BrandService::CategoryBrandList(['category_id'=>$this->params['category_id']]));

            // 商品分类
            $this->assign('category_list', SearchService::GoodsCategoryList(['category_id'=>$this->params['category_id']]));

            // 筛选价格区间
            $this->assign('screening_price_list', SearchService::ScreeningPriceList(['field'=>'id,name']));

            // 参数
            $this->assign('params', $this->params);

            $this->display('Index');
        }
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
        // 参数处理
        $this->params['order_by_field'] = trim(I('order_by_field', 'default'));
        $this->params['order_by_type'] = trim(I('order_by_type', 'desc'));
        
        // 获取商品列表
        $data = SearchService::GoodsList($this->params);
        if(empty($data['data']))
        {
            $msg = L('common_not_data_tips');
            $code = -100;
        } else {
            $msg = L('common_operation_success');
            $code = 0;
        }
        $this->ajaxReturn($msg, $code, $data);
    }
}
?>