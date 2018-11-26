<?php

namespace Api\Controller;

use Service\SearchService;
use Service\GoodsService;

/**
 * 商品搜索
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

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }
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
        SearchService::SearchAdd($this->data_post);

        // 获取数据
        $result = SearchService::GoodsList($this->data_post);

        // 分类
        if(!empty($this->data_post['category_id']))
        {
            $result['category'] = GoodsService::GoodsCategoryRow(['id'=>$this->data_post['category_id']]);
        } else {
            $result['category'] = [];
        }
        
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }
}
?>