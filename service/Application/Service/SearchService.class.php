<?php

namespace Service;

use Service\GoodsService;

/**
 * 搜索服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SearchService
{
    /**
     * 根据分类id获取同级列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryList($params = [])
    {
        // 根据分类id获取同级列表
        $category = GoodsService::GoodsCategoryRow(['id'=>$params['category_id']]);
        $pid = empty($category['pid']) ? 0 : $category['pid'];
        return GoodsService::GoodsCategoryList(['pid'=>$pid]);
    }

    /**
     * 获取商品价格筛选列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ScreeningPriceList($params = [])
    {
        $field = empty($params['field']) ? '*' : $params['field'];
        return M('ScreeningPrice')->field($field)->where(['is_enable'=>1])->order('sort asc')->select();
    }

    /**
     * 获取商品列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsList($params = [])
    {
        $result = [
            'total'     => 0,
            'data'      => [],
        ];
        $goods_where = [
            'g.is_delete_time' => 0,
        ];
        $result['data'] = GoodsService::GoodsList(['where'=>$goods_where]);

        return $result;
    }
}
?>