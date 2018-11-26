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
            'total'         => 0,
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
            $where['gci.category_id'] = ['in', $category_ids];
        }

        // 筛选价格
        if(!empty($params['screening_price_id']))
        {
            $price = M('ScreeningPrice')->field('min_price,max_price')->where(['is_enable'=>1, 'id'=>intval($params['screening_price_id'])])->find();
            $params['min_price'] = $price['min_price'];
            $params['max_price'] = $price['max_price'];
        }
        if(!empty($params['min_price']) && !empty($params['max_price']))
        {
            $where['g.price'] = [
                ['EGT', $params['min_price']],
                ['LT', $params['max_price']],
            ];
        } else if(!empty($params['min_price']))
        {
            $where['g.price'] = ['EGT', $params['min_price']];
        } else if(!empty($params['max_price']))
        {
            $where['g.price'] = ['LT', $params['max_price']];
        }

        // 获取商品总数
        $result['total'] = GoodsService::GoodsTotal(['where'=>$where]);

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 排序
            $order_by = '';
            if(!empty($params['order_by_field']) && !empty($params['order_by_type']) && $params['order_by_field'] != 'default')
            {
                $order_by = $params['order_by_field'].' '.$params['order_by_type'];
            } else {
                $order_by = 'access_count '.$params['order_by_type'].', sales_count '.$params['order_by_type'];
            }
            
            // 分页计算
            $page = intval(I('page', 1));
            $n = 10;
            $m = intval(($page-1)*$n);
            $result['data'] = GoodsService::GoodsList(['where'=>$where, 'm'=>$m, 'n'=>$n, 'order_by'=>$order_by]);
            $result['page_total'] = ceil($result['total']/$n);
        }
        return $result;
    }

    /**
     * [SearchAdd 搜索记录添加]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-21T00:37:44+0800
     * @param   [array]          $params [输入参数]
     */
    public static function SearchAdd($params = [])
    {
        // 筛选价格
        $screening_price = '';
        if(!empty($params['screening_price_id']))
        {
            $price = M('ScreeningPrice')->field('min_price,max_price')->where(['is_enable'=>1, 'id'=>intval($params['screening_price_id'])])->find();
            $params['min_price'] = $price['min_price'];
            $params['max_price'] = $price['max_price'];
        } else {
            if(!empty($params['min_price']) || !empty($params['max_price']))
            $price = [
                'min_price' => !empty($params['min_price']) ? $params['min_price'] : 0,
                'max_price' => !empty($params['max_price']) ? $params['max_price'] : 0,
            ];
        }
        if(!empty($price))
        {
            $screening_price = $price['min_price'].'-'.$price['max_price'];
        }
        
        // 参数
        $params['screening_price'] = $screening_price;
        $params['ymd'] = date('Ymd');
        $params['add_time'] = time();

        // 添加日志
        M('SearchHistory')->add($params);
    }

    /**
     * [SearchKeywordsList 获取热门关键字列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-20T23:55:06+0800
     * @param   [array]          $params [输入参数]
     */
    public function SearchKeywordsList($params = [])
    {
        return M('SearchHistory')->where(['keywords'=>['neq', '']])->group('keywords')->limit(10)->getField('keywords', true);
    }
}
?>