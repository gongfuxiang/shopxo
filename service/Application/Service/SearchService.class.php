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
     * 根据分类id获取下级列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryList($params = [])
    {
        return GoodsService::GoodsCategoryList(['pid'=>$params['category_id']]);
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
            'page_total'    => 0,
            'total'         => 450,
            'data'          => [],
        ];
        $where = [
            'g.is_delete_time'  => 0,
            'g.is_shelves'      => 1,
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where['g.title'] = ['like', '%'.$params['keywords'].'%'];
        }

        // 品牌
        if(!empty($params['brand_id']))
        {
            $where['g.brand_id'] = intval($params['brand_id']);
        }

        // 分类id
        if(!empty($params['category_id']))
        {
            $category_ids = GoodsService::GoodsCategoryItemsIds(['category_id'=>$params['category_id']]);
            $category_ids[] = $params['category_id'];
            $where['gci.id'] = ['in', $category_ids];
        }

        // 筛选价格
        if(!empty($params['screening_price_id']))
        {
            $price = M('ScreeningPrice')->field('min_price,max_price')->where(['is_enable'=>1, 'id'=>intval($params['screening_price_id'])])->find();
            if(!empty($price['min_price']) && !empty($price['max_price']))
            {
                $where['g.price'] = [
                    ['EGT', $price['min_price']],
                    ['LT', $price['max_price']],
                ];
            } else if(!empty($price['min_price']))
            {
                $where['g.price'] = ['EGT', $price['min_price']];
            } else if(!empty($price['max_price']))
            {
                $where['g.price'] = ['LT', $price['max_price']];
            }
        }

        // 获取商品总数
        $result['total'] = GoodsService::GoodsTotal(['where'=>$where]);

        // 获取商品列表
        if($result['total'] > 0)
        {
            $page = intval(I('page', 1));
            $n = 10;
            $m = intval(($page-1)*$n);
            $result['data'] = GoodsService::GoodsList(['where'=>$where, 'm'=>$m, 'n'=>$n]);
            $result['page_total'] = ceil($result['total']/$n);
        }
        return $result;
    }
}
?>