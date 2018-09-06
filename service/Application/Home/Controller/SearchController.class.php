<?php

namespace Home\Controller;

use Service\GoodsService;
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
        // 分类id
        $category_id = intval(I('category_id', 0));

        // 搜索关键字
        $keywords = trim(I('keywords'));

        // 品牌列表
        $this->assign('brand_list', BrandService::CategoryBrandList(['category_id'=>$category_id]));

        // 根据分类id获取同级列表
        $category = GoodsService::GoodsCategoryRow(['id'=>$category_id]);
        $pid = empty($category['pid']) ? 0 : $category['pid'];
        $this->assign('category_list', GoodsService::GoodsCategoryList(['pid'=>$pid]));

        // 价格区间
        $price_list = [
            ['id'=>1, 'name'=>'100以下'],
            ['id'=>2, 'name'=>'100-300'],
            ['id'=>3, 'name'=>'300-600'],
            ['id'=>4, 'name'=>'600-1000'],
            ['id'=>5, 'name'=>'1000-1500'],
            ['id'=>6, 'name'=>'1500-2000'],
            ['id'=>7, 'name'=>'2000-3000'],
            ['id'=>8, 'name'=>'3000-4000'],
            ['id'=>9, 'name'=>'5000-8000'],
            ['id'=>10, 'name'=>'10000y以上'],
        ];
        $this->assign('price_list', $price_list);

        $this->display('Index');
    }
}
?>