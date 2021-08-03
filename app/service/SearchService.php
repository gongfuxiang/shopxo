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
namespace app\service;

use think\facade\Db;
use app\service\GoodsService;
use app\service\BrandService;
use app\service\ResourcesService;

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
        // 返回格式
        $result = [
            'page_total'    => 0,
            'total'         => 0,
            'data'          => [],
        ];
        
        // 搜索条件
        $where = self::SearchWhereHandle($params);
        $where_base = $where['base'];
        $where_keywords = $where['keywords'];
        $where_screening_price = $where['screening_price'];

        // 排序
        if(!empty($params['order_by_field']) && !empty($params['order_by_type']) && $params['order_by_field'] != 'default')
        {
            $order_by = 'g.'.$params['order_by_field'].' '.$params['order_by_type'];
        } else {
            $order_by = 'g.access_count desc, g.sales_count desc, g.id desc';
        }

        // 分页计算
        $field = 'g.*';
        $page = max(1, isset($params['page']) ? intval($params['page']) : 1);
        $n = MyC('home_search_limit_number', 20, true);
        $m = intval(($page-1)*$n);

        // 搜索商品列表读取前钩子
        $hook_name = 'plugins_service_search_goods_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'                 => $hook_name,
            'is_backend'                => true,
            'params'                    => &$params,
            'where_base'                => &$where_base,
            'where_keywords'            => &$where_keywords,
            'where_screening_price'     => &$where_screening_price,
            'field'                     => &$field,
            'order_by'                  => &$order_by,
            'page'                      => &$page,
            'm'                         => &$m,
            'n'                         => &$n,
        ]);

        // 获取商品总数
        $result['total'] = (int) Db::name('Goods')->alias('g')->join('goods_category_join gci', 'g.id=gci.goods_id')->where($where_base)->where(function($query) use($where_keywords) {
            self::SearchKeywordsWhereJoinType($query, $where_keywords);
        })->where(function($query) use($where_screening_price) {
            $query->whereOr($where_screening_price);
        })->count('DISTINCT g.id');

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 查询数据
            $data = Db::name('Goods')->alias('g')->join('goods_category_join gci', 'g.id=gci.goods_id')->field($field)->where($where_base)->where(function($query) use($where_keywords) {
                self::SearchKeywordsWhereJoinType($query, $where_keywords);
            })->where(function($query) use($where_screening_price) {
                $query->whereOr($where_screening_price);
            })->group('g.id')->order($order_by)->limit($m, $n)->select()->toArray();

            // 数据处理
            $goods = GoodsService::GoodsDataHandle($data);

            // 返回数据
            $result['data'] = $goods['data'];
            $result['page_total'] = ceil($result['total']/$n);
        }
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 关键字搜索关系类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-02
     * @desc    description
     * @param   [object]          $query          [查询对象]
     * @param   [array]           $where_keywords [搜索关键字]
     */
    public static function SearchKeywordsWhereJoinType($query, $where_keywords)
    {
        // 搜索关键字默认或的关系
        $join = 'whereOr';

        // 是否开启并且关系
        if(MyC('home_search_is_keywords_where_and') == 1)
        {
            $join = 'where';
        }

        // 条件设置
        $query->$join($where_keywords);
    }

    /**
     * 搜索条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchWhereHandle($params = [])
    {
        // 搜索商品条件处理钩子
        $hook_name = 'plugins_service_search_goods_list_where';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]);

        // 基础条件
        $where_base = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1]
        ];

        // 关键字
        $where_keywords = [];
        if(!empty($params['wd']))
        {
            $keywords_fields = 'g.title|g.simple_desc';
            if(MyC('home_search_is_keywords_seo_fields') == 1)
            {
                $keywords_fields .= '|g.seo_title|g.seo_keywords|g.seo_desc';
            }
            $keywords = explode(' ', $params['wd']);
            foreach($keywords as $kv)
            {
                $where_keywords[] = [$keywords_fields, 'like', '%'.$kv.'%'];
            }
        }

        // 品牌
        // 不存在搜索品牌的时候则看是否指定品牌
        if(!empty($params['brand_ids']))
        {
            if(!is_array($params['brand_ids']))
            {
                $params['brand_ids'] = (substr($params['brand_ids'], 0, 1) == '{') ? json_decode(htmlspecialchars_decode($params['brand_ids']), true) : explode(',', $params['brand_ids']);
            }
            if(!empty($params['brand_ids']))
            {
                $where_base[] = ['g.brand_id', 'in', array_unique($params['brand_ids'])];
            }
        } else {
            if(!empty($params['brand_id']))
            {
                $where_base[] = ['g.brand_id', 'in', [$params['brand_id']]];
            }
        }

        // 分类id
        // 不存在搜索分类的时候则看是否指定分类
        if(!empty($params['category_ids']))
        {
            if(!is_array($params['category_ids']))
            {
                $params['category_ids'] = (substr($params['category_ids'], 0, 1) == '{') ? json_decode(htmlspecialchars_decode($params['category_ids']), true) : explode(',', $params['category_ids']);
            }
            if(!empty($params['category_ids']))
            {
                $ids = GoodsService::GoodsCategoryItemsIds($params['category_ids'], 1);
                $where_base[] = ['gci.category_id', 'in', $ids];
            }
        } else {
            if(!empty($params['category_id']))
            {
                $ids = GoodsService::GoodsCategoryItemsIds([intval($params['category_id'])], 1);
                $where_base[] = ['gci.category_id', 'in', $ids];
            }
        }

        // 筛选价格
        $where_screening_price = [];
        if(!empty($params['screening_price_values']))
        {
            if(!is_array($params['screening_price_values']))
            {
                $params['screening_price_values'] = (substr($params['screening_price_values'], 0, 1) == '{') ? json_decode(htmlspecialchars_decode($params['screening_price_values']), true) : explode(',', $params['screening_price_values']);
            }
            if(!empty($params['screening_price_values']))
            {
                foreach($params['screening_price_values'] as $v)
                {
                    $temp = explode('-', $v);
                    if(count($temp) == 2)
                    {
                        // 最小金额等于0、最大金额大于0
                        if(empty($temp[0]) && !empty($temp[1]))
                        {
                            $where_screening_price[] = [
                                ['min_price', '<=', $temp[1]],
                            ];

                        // 最小金额大于0、最大金额大于0
                        // 最小金额等于0、最大金额等于0
                        } elseif((!empty($temp[0]) && !empty($temp[1])) || (empty($temp[0]) && empty($temp[1])))
                        {
                            $where_screening_price[] = [
                                ['min_price', '>=', $temp[0]],
                                ['min_price', '<=', $temp[1]],
                            ];

                        // 最小金额大于0、最大金额等于0
                        } elseif(!empty($temp[0]) && empty($temp[1]))
                        {
                            $where_screening_price[] = [
                                ['min_price', '>=', $temp[0]],
                            ];
                        }
                    }
                }
            }
        }

        // 商品参数、属性
        if(!empty($params['goods_params_values']))
        {
            if(!is_array($params['goods_params_values']))
            {
                $params['goods_params_values'] = (substr($params['goods_params_values'], 0, 1) == '{') ? json_decode(htmlspecialchars_decode($params['goods_params_values']), true) : explode(',', $params['goods_params_values']);
            }
            if(!empty($params['goods_params_values']))
            {
                $ids = Db::name('GoodsParams')->where(['value'=>$params['goods_params_values'], 'type'=>self::SearchParamsWhereTypeValue()])->column('goods_id');
                if(!empty($ids))
                {
                    $where_base[] = ['g.id', 'in', $ids];
                }
            }
        }

        // 商品规格
        if(!empty($params['goods_spec_values']))
        {
            if(!is_array($params['goods_spec_values']))
            {
                $params['goods_spec_values'] = (substr($params['goods_spec_values'], 0, 1) == '{') ? json_decode(htmlspecialchars_decode($params['goods_spec_values']), true) : explode(',', $params['goods_spec_values']);
            }
            if(!empty($params['goods_spec_values']))
            {
                $ids = Db::name('GoodsSpecValue')->where(['value'=>$params['goods_spec_values']])->column('goods_id');
                if(!empty($ids))
                {
                    $where_base[] = ['g.id', 'in', $ids];
                }
            }
        }

        return [
            'base'              => $where_base,
            'keywords'          => $where_keywords,
            'screening_price'   => $where_screening_price,
        ];
    }

    /**
     * 参数搜索条件类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-11
     * @desc    description
     */
    public static function SearchParamsWhereTypeValue()
    {
        // 获取配置
        $value = MyC('home_search_params_type');
        if(empty($value))
        {
            $value = [2];
        }

        // 是否为数组
        if(!is_array($value))
        {
            $value = explode(',', $value);
        }

        return $value;
    }

    /**
     * 搜索记录添加
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-21T00:37:44+0800
     * @param   [array]          $params [输入参数]
     */
    public static function SearchAdd($params = [])
    {
        // 搜索数据结果
        if(!empty($params['search_result_data']))
        {
            $search_result = [
                'total'         => empty($params['search_result_data']['total']) ? 0 : $params['search_result_data']['total'],
                'data_count'    => empty($params['search_result_data']['data']) ? 0 : count($params['search_result_data']['data']),
                'page'          => empty($params['page']) ? 1 : intval($params['page']),
                'page_total'    => empty($params['search_result_data']['page_total']) ? 0 : $params['search_result_data']['page_total'],
            ];
        }

        // 日志数据
        $data = [
            'user_id'           => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'keywords'          => empty($params['wd']) ? '' : $params['wd'],
            'order_by_field'    => empty($params['order_by_field']) ? '' : $params['order_by_field'],
            'order_by_type'     => empty($params['order_by_type']) ? '' : $params['order_by_type'],
            'search_result'     => empty($search_result) ? '' : json_encode($search_result, JSON_UNESCAPED_UNICODE),
            'ymd'               => date('Ymd'),
            'add_time'          => time(),
        ];

        // 参数处理
        $field_arr = [
            'brand_ids',
            'category_ids',
            'screening_price_values',
            'goods_params_values',
            'goods_spec_values',
        ];
        foreach($field_arr as $v)
        {
            $data[$v] = empty($params[$v]) ? '' : (is_array($params[$v]) ? json_encode($params[$v], JSON_UNESCAPED_UNICODE) : $params[$v]);
        }

        Db::name('SearchHistory')->insert($data);
    }

    /**
     * 搜索关键字列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-03
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchKeywordsList($params = [])
    {
        $key = MyConfig('shopxo.cache_search_keywords_key');
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug'))
        {
            switch(intval(MyC('home_search_keywords_type', 0)))
            {
                case 1 :
                    $data = Db::name('SearchHistory')->where([['keywords', '<>', '']])->group('keywords')->limit(10)->column('keywords');
                    break;
                case 2 :
                    $keywords = MyC('home_search_keywords', '', true);
                    if(!empty($keywords))
                    {
                        $data = explode(',', $keywords);
                    }
                    break;
            }

            // 没数据则赋空数组值
            if(empty($data))
            {
                $data = [];
            }

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
    }

    /**
     * 分类下品牌列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CategoryBrandList($params = [])
    {
        $data = [];
        if(MyC('home_search_is_brand', 0) == 1)
        {
            // 基础条件
            $brand_where = [
                ['is_enable', '=', 1],
            ];

            // 搜索条件
            $where = self::SearchWhereHandle($params);
            $where_base = $where['base'];
            $where_keywords = $where['keywords'];
            $where_screening_price = $where['screening_price'];

            // 一维数组、参数值去重
            if(!empty($where_base) || !empty($where_keywords) || !empty($where_screening_price))
            {
                $ids = Db::name('Goods')->alias('g')->join('goods_category_join gci', 'g.id=gci.goods_id')->where($where_base)->where(function($query) use($where_keywords) {
                    self::SearchKeywordsWhereJoinType($query, $where_keywords);
                })->where(function($query) use($where_screening_price) {
                    $query->whereOr($where_screening_price);
                })->group('g.brand_id')->column('g.brand_id');
                if(!empty($ids))
                {
                    $brand_where[] = ['id', 'in', array_unique($ids)];
                }
            }

            // 获取品牌列表
            $data_params = [
                'field'     => 'id,name,logo,website_url',
                'where'     => $brand_where,
                'm'         => 0,
                'n'         => 0,
            ];
            $ret = BrandService::BrandList($data_params);
            $data = empty($ret['data']) ? [] : $ret['data'];
        }
        return $data;
    }

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
        $data = [];
		
		// 查询分类条件处理钩子
        $hook_name = 'plugins_service_search_category_list_where';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]);
		
        if(MyC('home_search_is_category', 0) == 1)
        {
            $pid = empty($params['category_id']) ? 0 : intval($params['category_id']);
            $where = [
                ['pid', '=', $pid],
            ];
            $data = GoodsService::GoodsCategoryList(['where'=>$where, 'field'=>'id,name']);
        }
        return $data;
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
        $data = [];
        if(MyC('home_search_is_price', 0) == 1)
        {
            $data = Db::name('ScreeningPrice')->field('id,name,min_price,max_price')->where(['is_enable'=>1])->order('sort asc')->select()->toArray();
        }
        return $data;
    }

    /**
     * 搜索商品参数列表、去重
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchGoodsParamsValueList($params = [])
    {
        $data = [];
        if(MyC('home_search_is_params', 0) == 1)
        {
            // 搜索条件
            $where = self::SearchWhereHandle($params);
            $where_base = $where['base'];
            $where_keywords = $where['keywords'];
            $where_screening_price = $where['screening_price'];

            // 仅搜索基础参数
            $where_base[] = ['gp.type', 'in', self::SearchParamsWhereTypeValue()];

            // 一维数组、参数值去重
            $data = Db::name('Goods')->alias('g')->join('goods_category_join gci', 'g.id=gci.goods_id')->join('goods_params gp', 'g.id=gp.goods_id')->where($where_base)->where(function($query) use($where_keywords) {
                self::SearchKeywordsWhereJoinType($query, $where_keywords);
            })->where(function($query) use($where_screening_price) {
                $query->whereOr($where_screening_price);
            })->group('gp.value')->field('gp.value')->select()->toArray();
        }
        return $data;
    }

    /**
     * 搜索商品规格列表、去重
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-08
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchGoodsSpecValueList($params = [])
    {
        $data = [];
        if(MyC('home_search_is_spec', 0) == 1)
        {
            // 搜索条件
            $where = self::SearchWhereHandle($params);
            $where_base = $where['base'];
            $where_keywords = $where['keywords'];
            $where_screening_price = $where['screening_price'];

            // 一维数组、参数值去重
            $data = Db::name('Goods')->alias('g')->join('goods_category_join gci', 'g.id=gci.goods_id')->join('goods_spec_value gsv', 'g.id=gsv.goods_id')->where($where_base)->where(function($query) use($where_keywords) {
                self::SearchKeywordsWhereJoinType($query, $where_keywords);
            })->where(function($query) use($where_screening_price) {
                $query->whereOr($where_screening_price);
            })->group('gsv.value')->field('gsv.value')->select()->toArray();
        }
        return $data;
    }

    /**
     * 搜索条件基础数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-11
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchMapInfo($params = [])
    {
        // 分类
        $category = empty($params['category_id']) ? [] : GoodsService::GoodsCategoryRow(['id'=>intval($params['category_id']), 'field'=>'name,vice_name,describe,seo_title,seo_keywords,seo_desc']);

        // 品牌
        $brand = null;
        if(!empty($params['brand_id']))
        {
            $data_params = [
                'field'     => 'id,name,describe,logo,website_url',
                'where'     => ['id'=>intval($params['brand_id'])],
                'm'         => 0,
                'n'         => 1,
            ];
            $ret = BrandService::BrandList($data_params);
            if(!empty($ret['data']) && !empty($ret['data'][0]))
            {
                $brand = $ret['data'][0];
            }
        }

        return [
            'category'  => empty($category) ? null : $category,
            'brand'     => empty($brand) ? null : $brand,
        ];
        
    }
}
?>