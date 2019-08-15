<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

use think\Db;
use app\service\GoodsService;

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
        return Db::name('ScreeningPrice')->field($field)->where(['is_enable'=>1])->order('sort asc')->select();
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
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1]
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['g.title|g.model|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords', 'like', '%'.$params['keywords'].'%'];
        }

        // 品牌
        if(!empty($params['brand_id']))
        {
            $where[] = ['g.brand_id', '=', intval($params['brand_id'])];
        }

        // 分类id
        if(!empty($params['category_id']))
        {
            $category_ids = GoodsService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $category_ids[] = $params['category_id'];
            $where[] = ['gci.category_id', 'in', $category_ids];
        }

        // 筛选价格
        if(!empty($params['screening_price_id']))
        {
            $price = Db::name('ScreeningPrice')->field('min_price,max_price')->where(['is_enable'=>1, 'id'=>intval($params['screening_price_id'])])->find();
            if(!empty($price))
            {
                $params['min_price'] = $price['min_price'];
                $params['max_price'] = $price['max_price'];
            }
        }
        if(!empty($params['min_price']))
        {
            $where[] = ['g.min_price', 'EGT', $params['min_price']];
        }
        if(!empty($params['max_price']))
        {
            $where[] = ['g.min_price', 'LT', $params['max_price']];
        }

        // 获取商品总数
        $result['total'] = GoodsService::CategoryGoodsTotal($where);

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 排序
            $order_by = '';
            if(!empty($params['order_by_field']) && !empty($params['order_by_type']) && $params['order_by_field'] != 'default')
            {
                $order_by = 'g.'.$params['order_by_field'].' '.$params['order_by_type'];
            } else {
                $order_by = 'g.access_count desc, g.sales_count desc';
            }
            
            // 分页计算
            $page = intval(input('page', 1));
            $n = 20;
            $m = intval(($page-1)*$n);
            $goods = GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>$m, 'n'=>$n, 'order_by'=>$order_by]);
            $result['data'] = $goods['data'];
            $result['page_total'] = ceil($result['total']/$n);
        }
        return DataReturn('处理成功', 0, $result);
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
            $price = Db::name('ScreeningPrice')->field('min_price,max_price')->where(['is_enable'=>1, 'id'=>intval($params['screening_price_id'])])->find();
        } else {
            $price = [
                'min_price' => !empty($params['min_price']) ? $params['min_price'] : 0,
                'max_price' => !empty($params['max_price']) ? $params['max_price'] : 0,
            ];
        }
        if(!empty($price))
        {
            $screening_price = $price['min_price'].'-'.$price['max_price'];
        }
        
        // 添加日志
        $data = [
            'user_id'               => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'brand_id'              => isset($params['brand_id']) ? intval($params['brand_id']) : 0,
            'category_id'           => isset($params['category_id']) ? intval($params['category_id']) : 0,
            'keywords'              => empty($params['keywords']) ? '' : $params['keywords'],
            'order_by_field'        => empty($params['order_by_field']) ? '' : $params['order_by_field'],
            'order_by_type'         => empty($params['order_by_type']) ? '' : $params['order_by_type'],
            'screening_price'       => $screening_price,
            'ymd'                   => date('Ymd'),
            'add_time'              => time(),
        ];
        Db::name('SearchHistory')->insert($data);
    }

    /**
     * [SearchKeywordsList 获取热门关键字列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-20T23:55:06+0800
     * @param   [array]          $params [输入参数]
     */
    public static function SearchKeywordsList($params = [])
    {
        $where = [
            ['keywords', '<>', ''],
        ];
        return Db::name('SearchHistory')->where($where)->group('keywords')->limit(10)->column('keywords');
    }
}
?>