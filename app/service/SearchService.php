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
use app\service\I18nService;
use app\service\SystemService;
use app\service\GoodsService;
use app\service\BrandService;
use app\service\ResourcesService;
use app\service\GoodsCategoryService;
use app\service\RegionService;

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
     * 数据列表处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-01-19
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function DataHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 品牌
            $brand = BrandService::BrandName(array_unique(call_user_func_array('array_merge', array_filter(array_map(function($item)
            {
                return empty($item['brand_ids']) ? '' : json_decode($item['brand_ids'], true);
            }, $data)))));

            // 商品分类
            $category = GoodsCategoryService::GoodsCategoryName(array_unique(call_user_func_array('array_merge', array_filter(array_map(function($item)
            {
                return empty($item['category_ids']) ? '' : json_decode($item['category_ids'], true);
            }, $data)))));

            // 产地
            $produce_region = RegionService::RegionName(array_unique(call_user_func_array('array_merge', array_filter(array_map(function($item)
            {
                return empty($item['produce_region_ids']) ? '' : json_decode($item['produce_region_ids'], true);
            }, $data)))));

            foreach($data as &$v)
            {
                // 品牌
                if(array_key_exists('brand_ids', $v))
                {
                    $brand_arr = [];
                    if(!empty($brand) && !empty($v['brand_ids']))
                    {
                        $brand_ids = json_decode($v['brand_ids'], true);
                        foreach($brand_ids as $tv)
                        {
                            if(array_key_exists($tv, $brand))
                            {
                                $brand_arr[] = $brand[$tv];
                            }
                        }
                    }
                    $v['brand_text'] = empty($brand_arr) ? '' : implode('，', $brand_arr);
                }

                // 商品分类
                if(array_key_exists('category_ids', $v))
                {
                    $category_arr = [];
                    if(!empty($category) && !empty($v['category_ids']))
                    {
                        $category_ids = json_decode($v['category_ids'], true);
                        foreach($category_ids as $tv)
                        {
                            if(array_key_exists($tv, $category))
                            {
                                $category_arr[] = $category[$tv];
                            }
                        }
                    }
                    $v['category_text'] = empty($category_arr) ? '' : implode('，', $category_arr);
                }

                // 价格范围
                if(array_key_exists('screening_price_values', $v))
                {
                    $v['screening_price_values'] = empty($v['screening_price_values']) ? '' : implode('，', json_decode($v['screening_price_values'], true));
                }

                // 产地
                if(array_key_exists('produce_region_ids', $v))
                {
                    $produce_region_arr = [];
                    if(!empty($produce_region) && !empty($v['produce_region_ids']))
                    {
                        $produce_region_ids = json_decode($v['produce_region_ids'], true);
                        foreach($produce_region_ids as $tv)
                        {
                            if(array_key_exists($tv, $produce_region))
                            {
                                $produce_region_arr[] = $produce_region[$tv];
                            }
                        }
                    }
                    $v['produce_region_text'] = empty($produce_region_arr) ? '' : implode('，', $produce_region_arr);
                }

                // 商品参数
                if(array_key_exists('goods_params_values', $v))
                {
                    $v['goods_params_values'] = empty($v['goods_params_values']) ? '' : implode('，', json_decode($v['goods_params_values'], true));
                }

                // 商品规格
                if(array_key_exists('goods_spec_values', $v))
                {
                    $v['goods_spec_values'] = empty($v['goods_spec_values']) ? '' : implode('，', json_decode($v['goods_spec_values'], true));
                }
            }
        }
        return $data;
    }

    /**
     * 排序列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-01
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchMapOrderByList($params = [])
    {
        // 移除分页和框架的s模块参数
        unset($params['page'], $params['s']);

        // 处理排序参数
        $ov = empty($params['ov']) ? ['default'] : explode('-', $params['ov']);
        $data = MyConst('common_search_order_by_list');
        foreach($data as &$v)
        {
            // 是否选中
            $v['is_active'] = ($ov[0] == $v['type']) ? 1 : 0;

            // url
            $temp_ov = '';
            if($v['type'] == 'default')
            {
                $temp_params = $params;
                unset($temp_params['ov']);
            } else {
                // 类型
                if($ov[0] == $v['type'])
                {
                    $v['value'] = ($ov[1] == 'desc') ? 'asc' : 'desc';
                }

                // 参数值
                $temp_ov = $v['type'].'-'.$v['value'];
                $temp_params = array_merge($params, ['ov'=>$temp_ov]);
            }
            $v['url'] = MyUrl('index/search/index', $temp_params);
        }
        return $data;
    }

    /**
     * 搜素条件处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-30
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [string]         $pid    [参数字段]
     * @param   [string]         $did    [数据字段]
     * @param   [array]          $params [输入参数]
     * @param   [array]          $ext    [扩展数据]
     */
    public static function SearchMapHandle($data, $pid, $did, $params, $ext = [])
    {
        // 移除分页和框架的s模块参数
        unset($params['page'], $params['s']);

        // ascii字段处理
        $is_ascii = isset($ext['is_ascii']) && $ext['is_ascii'] == true;
        $field = empty($ext['field']) ? 'value' : $ext['field'];

        // 当前已选值（逗号多选）
        $selected = isset($params[$pid]) ? self::SearchRequestListValue($params[$pid]) : [];

        foreach($data as &$v)
        {
            // 是否转ascii处理主键字段
            if($is_ascii && !empty($field) && isset($v[$field]) && !is_array($v[$field]))
            {
                $v[$did] = StrToAscii($v[$field]);
            }
            $temp_params = $params;
            if(isset($v[$did]))
            {
                $id = strval($v[$did]);
                $next = $selected;
                if(in_array($id, $next, true))
                {
                    $next = array_values(array_filter($next, function($item) use ($id)
                    {
                        return strval($item) !== $id;
                    }));
                } else {
                    $next[] = $id;
                }
                if(empty($next))
                {
                    unset($temp_params[$pid]);
                } else {
                    $temp_params[$pid] = implode(',', $next);
                }
                $v['is_active'] = in_array($id, $selected, true) ? 1 : 0;
            } else {
                $v['is_active'] = 0;
            }
            $v['url'] = MyUrl('index/search/index', $temp_params);
        }
        return $data;
    }

    /**
     * 获取商品列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     * @param   [array]          $map    [搜素条件]
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsList($map, $params = [])
    {
        // 返回格式
        $result = [
            'page_start'    => 0,
            'page_size'     => 0,
            'page'          => 1,
            'page_total'    => 0,
            'total'         => 0,
            'data'          => [],
        ];

        // 搜索条件
        $order_by = $map['order_by'];
        $where_base = $map['base'];
        $where_keywords = $map['keywords'];
        $where_screening_price = $map['screening_price'];

        // 分页计算
        // 搜索列表场景只取展示必需字段，避免 g.* 过大
        if(!empty($params['is_search_list']))
        {
            $field = 'g.id,g.brand_id,g.title,g.title_color,g.simple_desc,g.images,g.inventory,g.inventory_unit,g.min_price,g.max_price,g.min_original_price,g.max_original_price,g.original_price,g.price,g.sales_count,g.access_count,g.model,g.is_exist_many_spec,g.buy_min_number,g.buy_max_number';
        } else {
            $field = 'g.*';
        }
        $result['page'] = max(1, isset($params['page']) ? intval($params['page']) : 1);
        $result['page_size'] = empty($params['page_size']) ? MyC('home_search_limit_number', 20, true) : intval($params['page_size']);
        // 数量不能超过500
        if($result['page_size'] > 500)
        {
            $result['page_size'] = 500;
        }
        $result['page_start'] = intval(($result['page']-1)*$result['page_size']);

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
            'page'                      => &$result['page'],
            'page_start'                => &$result['page_start'],
            'page_size'                 => &$result['page_size'],
        ]);

        // 仅在存在分类关联条件时才关联分类关系表
        $is_need_join_category = self::SearchWhereHasAlias($where_base, 'gci.');
        $goods_query = Db::name('Goods')->alias('g');
        if($is_need_join_category)
        {
            $goods_query->join('goods_category_join gci', 'g.id=gci.goods_id');
        }

        // 获取商品总数
        $result['total'] = (int) $goods_query->where($where_base)->where(function($query) use($where_keywords) {
            self::SearchKeywordsWhereJoinType($query, $where_keywords);
        })->where(function($query) use($where_screening_price) {
            $query->whereOr($where_screening_price);
        })->count($is_need_join_category ? 'DISTINCT g.id' : 'g.id');

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 查询数据
            $goods_query = Db::name('Goods')->alias('g');
            if($is_need_join_category)
            {
                $goods_query->join('goods_category_join gci', 'g.id=gci.goods_id');
            }
            $goods_query = $goods_query->field($field)->where($where_base)->where(function($query) use($where_keywords) {
                self::SearchKeywordsWhereJoinType($query, $where_keywords);
            })->where(function($query) use($where_screening_price) {
                $query->whereOr($where_screening_price);
            })->order($order_by)->limit($result['page_start'], $result['page_size']);
            if($is_need_join_category)
            {
                $goods_query->group('g.id');
            }
            $data = $goods_query->select()->toArray();

            // 数据处理
            $params['is_spec'] = (!isset($params['is_spec']) || $params['is_spec'] == 1) ? 1 : 0;
            $params['is_cart'] = (!isset($params['is_cart']) || $params['is_cart'] == 1) ? 1 : 0;
            $goods = GoodsService::GoodsDataHandle($data, $params);

            // 返回数据
            $result['data'] = $goods['data'];
            $result['page_total'] = ceil($result['total']/$result['page_size']);
        } else {
            return DataReturn(MyLang('no_data'), -1, $result);
        }
        return DataReturn(MyLang('handle_success'), 0, $result);
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
     * 按关键字从系统 i18n 表匹配商品 id（仅当前语言、商品可搜索字段）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-03-26
     * @desc    description
     * @param   [string]          $keyword [关键字]
     * @param   [array]           $fields  [i18n 字段名]
     */
    public static function SearchI18nGoodsIdsByKeyword($keyword, $fields = [])
    {
        return I18nService::BusinessIdsByKeyword('goods', $keyword, $fields);
    }

    /**
     * 搜索条件是否包含指定别名字段
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-04-29
     * @desc    description
     * @param   [array]           $where [条件]
     * @param   [string]          $alias [别名]
     */
    private static function SearchWhereHasAlias($where, $alias)
    {
        if(empty($where) || !is_array($where) || empty($alias))
        {
            return false;
        }
        foreach($where as $item)
        {
            if(!empty($item) && is_array($item) && isset($item[0]) && is_string($item[0]) && stripos($item[0], $alias) === 0)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 搜索参数数组值归一化（一维去空去重）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-04-29
     * @desc    description
     * @param   [mixed]           $value [输入值]
     */
    private static function SearchNormalizeArrayValue($value)
    {
        if(!is_array($value))
        {
            return [$value];
        }

        $result = [];
        array_walk_recursive($value, function($item) use (&$result)
        {
            if($item !== '' && $item !== null)
            {
                $result[] = $item;
            }
        });
        return array_values(array_unique($result));
    }

    /**
     * 请求参数解析为字符串列表（支持数组 / 逗号分隔 / JSON 对象，兼容 uniapp 筛选）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-11
     * @desc    description
     * @param   [mixed]           $value [请求值]
     */
    private static function SearchFilterRequestValues($value)
    {
        if($value === null || $value === '')
        {
            return [];
        }
        if(is_array($value))
        {
            return self::SearchNormalizeArrayValue($value);
        }
        $raw = htmlspecialchars_decode(strval($value));
        if($raw !== '' && in_array(substr($raw, 0, 1), ['{', '['], true))
        {
            $decoded = json_decode($raw, true);
            return self::SearchNormalizeArrayValue(empty($decoded) ? [] : $decoded);
        }
        return self::SearchRequestListValue($raw);
    }

    /**
     * 请求参数解析为整型 id 列表（支持数组 / 逗号分隔）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-10
     * @desc    description
     * @param   [mixed]           $value [请求值]
     */
    private static function SearchRequestIdsValue($value)
    {
        if(empty($value) && $value !== 0 && $value !== '0')
        {
            return [];
        }
        if(!is_array($value))
        {
            $value = explode(',', strval($value));
        }
        $ids = [];
        foreach(self::SearchNormalizeArrayValue($value) as $item)
        {
            $id = intval($item);
            if($id > 0)
            {
                $ids[] = $id;
            }
        }
        return array_values(array_unique($ids));
    }

    /**
     * 请求参数解析为字符串列表（支持数组 / 逗号分隔，用于筛选项 toggle）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-10
     * @desc    description
     * @param   [mixed]           $value [请求值]
     */
    private static function SearchRequestListValue($value)
    {
        if($value === null || $value === '')
        {
            return [];
        }
        if(!is_array($value))
        {
            $value = explode(',', strval($value));
        }
        $result = [];
        foreach(self::SearchNormalizeArrayValue($value) as $item)
        {
            $item = strval($item);
            if($item !== '')
            {
                $result[] = $item;
            }
        }
        return array_values(array_unique($result));
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
            // WEB端则处理关键字
            if(APPLICATION_CLIENT_TYPE == 'pc')
            {
                $params['wd'] = AsciiToStr($params['wd']);
            }
            $keywords = explode(' ', str_replace('+', ' ', trim($params['wd'])));
            $is_keywords_spec = true;
            if(count($keywords) == 1)
            {
                $goods_ids = Db::name('GoodsSpecBase')->where(['barcode|coding'=>$keywords[0]])->column('goods_id');
                $goods_ids = self::SearchNormalizeArrayValue($goods_ids);
                if(!empty($goods_ids))
                {
                    $where_base[] = ['g.id', 'in', $goods_ids];
                    $is_keywords_spec = false;
                }
            }
            if($is_keywords_spec)
            {
                $keywords_fields = 'g.title|g.simple_desc|g.spec_desc|g.approval_number|g.batch_number|g.produce_company|g.coding|g.model';
                if(MyC('home_search_is_keywords_seo_fields') == 1)
                {
                    $keywords_fields .= '|g.seo_title|g.seo_keywords|g.seo_desc';
                }
                // 非默认语言时，系统 i18n 商品文案一并参与关键字匹配（与主表字段 OR，默认语言不查 i18n 表）
                $is_i18n_keywords = I18nService::IsHandle();
                $i18n_keywords_fields = ['title', 'simple_desc', 'spec_desc', 'approval_number', 'batch_number', 'produce_company'];
                if(MyC('home_search_is_keywords_seo_fields') == 1)
                {
                    $i18n_keywords_fields = array_merge($i18n_keywords_fields, ['seo_title', 'seo_keywords', 'seo_desc']);
                }
                foreach($keywords as $kv)
                {
                    if($is_i18n_keywords)
                    {
                        $i18n_goods_ids = self::SearchI18nGoodsIdsByKeyword($kv, $i18n_keywords_fields);
                        $where_keywords[] = function($query) use($keywords_fields, $kv, $i18n_goods_ids)
                        {
                            $query->whereOr([[$keywords_fields, 'like', '%'.$kv.'%']]);
                            if(!empty($i18n_goods_ids))
                            {
                                $query->whereOr([['g.id', 'in', $i18n_goods_ids]]);
                            }
                        };
                    } else {
                        $where_keywords[] = [$keywords_fields, 'like', '%'.$kv.'%'];
                    }
                }
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
            $params['brand_ids'] = self::SearchNormalizeArrayValue($params['brand_ids']);
            if(!empty($params['brand_ids']))
            {
                $where_base[] = ['g.brand_id', 'in', array_unique($params['brand_ids'])];
            }
        }
        // 指定品牌
        if(!empty($params['brand']))
        {
            $where_base[] = ['g.brand_id', 'in', [intval($params['brand'])]];
        }
        // web端（支持 bid=1,2,3 多选）
        if(!empty($params['bid']))
        {
            $bid_ids = self::SearchRequestIdsValue($params['bid']);
            if(!empty($bid_ids))
            {
                $where_base[] = ['g.brand_id', 'in', $bid_ids];
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
            $params['category_ids'] = self::SearchNormalizeArrayValue($params['category_ids']);
            if(!empty($params['category_ids']))
            {
                $ids = GoodsCategoryService::GoodsCategoryItemsIds($params['category_ids'], 1);
                $ids = self::SearchNormalizeArrayValue($ids);
                $where_base[] = ['gci.category_id', 'in', $ids];
            }
        } else {
            if(!empty($params['category_id']))
            {
                $ids = GoodsCategoryService::GoodsCategoryItemsIds([intval($params['category_id'])], 1);
                $ids = self::SearchNormalizeArrayValue($ids);
                $where_base[] = ['gci.category_id', 'in', $ids];
            }
        }
        // web端（支持 cid=1,2,3 多选）
        if(!empty($params['cid']))
        {
            $cid_ids = self::SearchRequestIdsValue($params['cid']);
            if(!empty($cid_ids))
            {
                $ids = GoodsCategoryService::GoodsCategoryItemsIds($cid_ids, 1);
                $ids = self::SearchNormalizeArrayValue($ids);
                if(!empty($ids))
                {
                    $where_base[] = ['gci.category_id', 'in', $ids];
                }
            }
        }

        // 产地（支持 poid=1,2,3 多选）
        if(!empty($params['poid']))
        {
            $poid_ids = self::SearchRequestIdsValue($params['poid']);
            if(!empty($poid_ids))
            {
                $where_base[] = ['g.produce_region', 'in', $poid_ids];
            }
        }
        // 产地、多个id
        if(!empty($params['produce_region_ids']))
        {
            if(!is_array($params['produce_region_ids']))
            {
                $params['produce_region_ids'] = (substr($params['produce_region_ids'], 0, 1) == '{') ? json_decode(htmlspecialchars_decode($params['produce_region_ids']), true) : explode(',', $params['produce_region_ids']);
            }
            $params['produce_region_ids'] = self::SearchNormalizeArrayValue($params['produce_region_ids']);
            if(!empty($params['produce_region_ids']))
            {
                $where_base[] = ['g.produce_region', 'in', $params['produce_region_ids']];
            }
        }

        // 筛选价格
        $map_price = [];
        $where_screening_price = [];
        if(!empty($params['screening_price_values']))
        {
            if(!is_array($params['screening_price_values']))
            {
                $map_price = (substr($params['screening_price_values'], 0, 1) == '{') ? json_decode(htmlspecialchars_decode($params['screening_price_values']), true) : explode(',', $params['screening_price_values']);
            }
        }
        // web端（支持 peid=1,2,3 多选，区间 OR）
        if(!empty($params['peid']))
        {
            $peid_ids = self::SearchRequestIdsValue($params['peid']);
            if(!empty($peid_ids))
            {
                $temp_price_list = Db::name('ScreeningPrice')->where(['is_enable'=>1])->where('id', 'in', $peid_ids)->field('min_price,max_price')->select()->toArray();
                if(!empty($temp_price_list))
                {
                    foreach($temp_price_list as $temp_price)
                    {
                        $map_price[] = implode('-', $temp_price);
                    }
                }
            }
        }
        // 价格滑条
        if(!empty($params['price']) && stripos($params['price'], '-') !== false)
        {
            $map_price[] = $params['price'];
        }
        // 处理价格条件
        if(!empty($map_price))
        {
            foreach($map_price as $v)
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

        // 商品参数、属性（明文 / JSON / ascii psid）
        $map_params = [];
        if(!empty($params['goods_params_values']))
        {
            $map_params = self::SearchFilterRequestValues($params['goods_params_values']);
        }
        if(!empty($params['psid']))
        {
            foreach(self::SearchRequestListValue($params['psid']) as $v)
            {
                $map_params[] = AsciiToStr($v);
            }
        }
        $map_params = array_values(array_unique(array_filter(array_map('strval', $map_params), function($item)
        {
            return $item !== '';
        })));

        // 商品规格（明文 / JSON / ascii scid）
        $map_spec = [];
        if(!empty($params['goods_spec_values']))
        {
            $map_spec = self::SearchFilterRequestValues($params['goods_spec_values']);
        }
        if(!empty($params['scid']))
        {
            foreach(self::SearchRequestListValue($params['scid']) as $v)
            {
                $map_spec[] = AsciiToStr($v);
            }
        }
        $map_spec = array_values(array_unique(array_filter(array_map('strval', $map_spec), function($item)
        {
            return $item !== '';
        })));

        // 参数 + 规格：按商品 id 取交集后只写一条 g.id 条件，避免重复 where 冲突
        $filter_goods_ids = null;
        if(!empty($map_params))
        {
            $goods_ids = null;
            $scope = self::SearchParamsWhereTypeValue();
            foreach($map_params as $item)
            {
                $ids = Db::name('GoodsParams')->where(['md5_key'=>md5($item), 'scope'=>$scope])->column('goods_id');
                $ids = self::SearchNormalizeArrayValue($ids);
                $goods_ids = ($goods_ids === null) ? $ids : array_values(array_intersect($goods_ids, $ids));
                if(empty($goods_ids))
                {
                    break;
                }
            }
            $filter_goods_ids = empty($goods_ids) ? [0] : $goods_ids;
        }
        if(!empty($map_spec))
        {
            $goods_ids = null;
            foreach($map_spec as $item)
            {
                $ids = Db::name('GoodsSpecValue')->where(['md5_key'=>md5($item)])->column('goods_id');
                $ids = self::SearchNormalizeArrayValue($ids);
                $goods_ids = ($goods_ids === null) ? $ids : array_values(array_intersect($goods_ids, $ids));
                if(empty($goods_ids))
                {
                    break;
                }
            }
            $spec_ids = empty($goods_ids) ? [0] : $goods_ids;
            if($filter_goods_ids === null)
            {
                $filter_goods_ids = $spec_ids;
            } else {
                $filter_goods_ids = array_values(array_intersect($filter_goods_ids, $spec_ids));
                if(empty($filter_goods_ids))
                {
                    $filter_goods_ids = [0];
                }
            }
        }
        if($filter_goods_ids !== null)
        {
            $where_base[] = ['g.id', 'in', $filter_goods_ids];
        }

        // 排序
        $order_by = 'g.sort_level desc, g.inventory desc, g.access_count desc, g.sales_count desc, g.id desc';
        if(!empty($params['ov']))
        {
            // 数据库字段映射关系
            $fields = [
                'sales'     => 'g.sales_count',
                'access'    => 'g.access_count',
                'price'     => 'g.min_price',
                'new'       => 'g.id',
            ];

            // 参数判断
            $temp = explode('-', $params['ov']);
            if(count($temp) == 2 && $temp[0] != 'default' && array_key_exists($temp[0], $fields) && in_array($temp[1], ['desc', 'asc']))
            {
                $order_by = $fields[$temp[0]].' '.$temp[1];
            }
        } else {
            if(!empty($params['order_by_type']) && !empty($params['order_by_field']) && $params['order_by_field'] != 'default')
            {
                $order_by = 'g.'.$params['order_by_field'].' '.$params['order_by_type'];
            }
        }

        // 是否存在搜索条件
        $is_map = (count($where_base) > 2 || !empty($where_keywords) || !empty($where_screening_price) || !empty($params['ov'])) ? 1 : 0;
        return [
            'base'              => $where_base,
            'keywords'          => $where_keywords,
            'screening_price'   => $where_screening_price,
            'order_by'          => $order_by,
            'is_map'            => $is_map,
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
        // 防止缓存数据结构异常导致 where in 出现嵌套数组
        $value = self::SearchNormalizeArrayValue($value);
        $value = array_map(function($item)
        {
            return intval($item);
        }, $value);
        return array_values(array_filter(array_unique($value), function($item)
        {
            return $item >= 0;
        }));
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
        // 是否增加搜索记录
        if(MyC('home_search_history_record', 0) == 1)
        {
            // 排序
            $ov_arr = empty($params['ov']) ? '' : explode('-', $params['ov']);
            if(empty($ov_arr) && !empty($params['order_by_type']) && !empty($params['order_by_field']))
            {
                $ov_arr = [$params['order_by_field'], $params['order_by_type']];
            }

            // 结果仅保留商品id
            if(!empty($params['search_result_data']) && is_array($params['search_result_data']) && !empty($params['search_result_data']['data']))
            {
                $params['search_result_data']['data'] = array_column($params['search_result_data']['data'], 'id');
            }

            // 日志数据
            $data = [
                'user_id'         => isset($params['user_id']) ? intval($params['user_id']) : 0,
                'keywords'        => empty($params['wd']) ? '' : $params['wd'],
                'order_by_field'  => empty($ov_arr) ? '' : $ov_arr[0],
                'order_by_type'   => empty($ov_arr) ? '' : $ov_arr[1],
                'search_result'   => empty($params['search_result_data']) ? '' : (is_array($params['search_result_data']) ? json_encode($params['search_result_data'], JSON_UNESCAPED_UNICODE) : $params['search_result_data']),
                'ip'              => GetClientIP(),
                'ymd'             => date('Ymd'),
                'add_time'        => time(),
            ];

            // 参数处理
            $field_arr = [
                'brand_ids'               => ['brand_ids', 'brand', 'bid'],
                'category_ids'            => ['category_ids', 'category_id', 'cid'],
                'screening_price_values'  => ['screening_price_values', 'peid'],
                'goods_params_values'     => ['goods_params_values', 'psid'],
                'goods_spec_values'       => ['goods_spec_values', 'scid'],
                'produce_region_ids'      => ['produce_region_ids', 'poid'],
            ];
            foreach($field_arr as $k=>$v)
            {
                $item = [];
                foreach($v as $vs)
                {
                    if(!empty($params[$vs]))
                    {
                        $current = $params[$vs];

                        // 价格区间
                        if($vs == 'peid')
                        {
                            if(is_array($current))
                            {
                                $current = reset($current);
                            }
                            $temp_price = Db::name('ScreeningPrice')->where(['is_enable'=>1, 'id'=>intval($current)])->field('min_price,max_price')->find();
                            $current = empty($temp_price) ? '' : implode('-', $temp_price);
                        }

                        // Ascii 处理（psid/scid 支持逗号多选，需逐个解码）
                        if(in_array($vs, ['psid', 'scid'], true))
                        {
                            $decoded = [];
                            foreach(self::SearchRequestListValue($current) as $ascii_item)
                            {
                                $text = AsciiToStr($ascii_item);
                                if($text !== '')
                                {
                                    $decoded[] = $text;
                                }
                            }
                            $current = $decoded;
                        }

                        // 合并参数
                        if(is_array($current))
                        {
                            $tv = $current;
                        } else {
                            $tv = (is_string($current) && substr($current, 0, 1) == '{') ? json_decode(htmlspecialchars_decode($current), true) : $current;
                        }
                        if($tv !== '' && $tv !== null)
                        {
                            $item = array_merge($item, is_array($tv) ? $tv : [$tv]);
                        }
                    }
                }
                $data[$k] = empty($item) ? '' : (is_array($item) ? json_encode($item, JSON_UNESCAPED_UNICODE) : $item);
            }
            Db::name('SearchHistory')->insert($data);
        }
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
        $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key'), function()
        {
            $data = [];
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
            return empty($data) ? [] : $data;
        }, 180);
        return $data;
    }

    /**
     * 分类下品牌列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $map    [搜索条件]
     * @param   [array]          $params [输入参数]
     */
    public static function CategoryBrandList($map, $params = [])
    {
        $data = [];
        if(MyC('home_search_is_brand', 0) == 1)
        {
            $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_category_brand_list_'.md5(json_encode([$map, $params], JSON_UNESCAPED_UNICODE)), function() use($map)
            {
                $data = [];

                // 基础条件
            $brand_where = [
                ['is_enable', '=', 1],
            ];

            // 仅搜索关键字相关的品牌
            if(!empty($map['keywords']))
            {
                $where_keywords = $map['keywords'];
                $goods_query = Db::name('Goods')->alias('g');
                if(self::SearchWhereHasAlias($map['base'], 'gci.'))
                {
                    $goods_query->join('goods_category_join gci', 'g.id=gci.goods_id');
                }
                $ids = $goods_query->where($map['base'])->where(function($query) use($where_keywords) {
                    self::SearchKeywordsWhereJoinType($query, $where_keywords);
                })->column('distinct g.brand_id');
                $ids = self::SearchNormalizeArrayValue($ids);
                if(!empty($ids))
                {
                    $brand_where[] = ['id', 'in', array_unique($ids)];
                }
            }

            // 仅获取已关联商品的品牌
            $where = [
                ['is_shelves', '=', 1],
                ['is_delete_time', '=', 0],
                ['brand_id', '>', 0],
            ];
            $ids = self::SearchNormalizeArrayValue(MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_brand_goods_ids_', function() use($where)
            {
                return Db::name('Goods')->where($where)->column('distinct brand_id');
            }, 300));
            if(!empty($ids))
            {
                $brand_where[] = ['id', 'in', $ids];
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
                return $data;
            }, 120);
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
        if(MyC('home_search_is_category', 0) == 1)
        {
            $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_goods_category_list_'.md5(json_encode($params, JSON_UNESCAPED_UNICODE)), function() use($params)
            {
                $cid = empty($params['category_id']) ? (empty($params['cid']) ? 0 : intval($params['cid'])) : intval($params['category_id']);
                return GoodsCategoryService::GoodsCategoryList(['where'=>[['pid', '=', intval($cid)]], 'field'=>'id,name']);
            }, 300);
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
            // 缓存key按语言隔离（避免翻译数据跨语言串缓存）
            $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_screening_price_list_'.I18nService::CacheLangKey(), function()
            {
                return Db::name('ScreeningPrice')->field('id,name,min_price,max_price')->where(['is_enable'=>1])->order('sort asc')->select()->toArray();
            }, 300);

            // 多语言数据替换（缓存后处理、仅前台非默认语言生效）
            I18nService::DataHandle($data, 'screening_price');
        }
        return $data;
    }

    /**
     * 搜索商品产地、去重
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-08
     * @desc    description
     * @param   [array]          $map    [搜素条件]
     * @param   [array]          $params [输入参数]
     */
    public static function SearchGoodsProduceRegionList($map, $params = [])
    {
        $data = [];
        if(MyC('home_search_is_produce_region', 0) == 1)
        {
            $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_goods_produce_region_list_'.md5(json_encode([$map, $params], JSON_UNESCAPED_UNICODE)), function() use($map)
            {
                $data = [];

                // 搜索条件
            $where_base = $map['base'];
            $where_keywords = $map['keywords'];
            $where_screening_price = $map['screening_price'];

            // 一维数组、参数值去重
            $goods_query = Db::name('Goods')->alias('g');
            if(self::SearchWhereHasAlias($where_base, 'gci.'))
            {
                $goods_query->join('goods_category_join gci', 'g.id=gci.goods_id');
            }
            $list = RegionService::RegionName($goods_query->where($where_base)->where(function($query) use($where_keywords) {
                self::SearchKeywordsWhereJoinType($query, $where_keywords);
            })->where(function($query) use($where_screening_price) {
                $query->whereOr($where_screening_price);
            })->group('g.produce_region')->column('g.produce_region'));
            if(!empty($list))
            {
                foreach($list as $k=>$v)
                {
                    $data[] = [
                        'id'    => $k,
                        'name'  => $v,
                    ];
                }
            }

                return $data;
            }, 120);
        }
        return $data;
    }

    /**
     * 搜索商品参数列表、去重（按当前搜索结果聚合，API 等仍用）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-08
     * @desc    description
     * @param   [array]          $map    [搜素条件]
     * @param   [array]          $params [输入参数]
     */
    public static function SearchGoodsParamsValueList($map, $params = [])
    {
        $data = [];
        if(MyC('home_search_is_params', 0) == 1)
        {
            $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_goods_params_value_list_'.md5(json_encode([$map, $params], JSON_UNESCAPED_UNICODE)), function() use($map)
            {
                $data = [];

                // 搜索条件
            $where_base = $map['base'];
            $where_keywords = $map['keywords'];
            $where_screening_price = $map['screening_price'];

            // 仅搜索基础参数
            $scope = self::SearchNormalizeArrayValue(self::SearchParamsWhereTypeValue());
            $where_base[] = ['gp.scope', 'in', $scope];

            // 一维数组、参数值去重
            $goods_query = Db::name('Goods')->alias('g');
            if(self::SearchWhereHasAlias($where_base, 'gci.'))
            {
                $goods_query->join('goods_category_join gci', 'g.id=gci.goods_id');
            }
            $data = $goods_query->join('goods_params gp', 'g.id=gp.goods_id')->where($where_base)->where(function($query) use($where_keywords) {
                self::SearchKeywordsWhereJoinType($query, $where_keywords);
            })->where(function($query) use($where_screening_price) {
                $query->whereOr($where_screening_price);
            })->group('gp.value')->order('gp.id desc')->field('gp.value')->limit(200)->select()->toArray();
                return $data;
            }, 120);
        }
        return $data;
    }

    /**
     * 搜索商品规格列表、去重（按当前搜索结果聚合，API 等仍用）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-08
     * @desc    description
     * @param   [array]           $map    [搜素条件]
     * @param   [array]           $params [输入参数]
     */
    public static function SearchGoodsSpecValueList($map, $params = [])
    {
        $data = [];
        if(MyC('home_search_is_spec', 0) == 1)
        {
            $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_goods_spec_value_list_'.md5(json_encode([$map, $params], JSON_UNESCAPED_UNICODE)), function() use($map)
            {
                $data = [];

                // 搜索条件
            $where_base = $map['base'];
            $where_keywords = $map['keywords'];
            $where_screening_price = $map['screening_price'];

            // 一维数组、参数值去重
            $goods_query = Db::name('Goods')->alias('g');
            if(self::SearchWhereHasAlias($where_base, 'gci.'))
            {
                $goods_query->join('goods_category_join gci', 'g.id=gci.goods_id');
            }
            $data = $goods_query->join('goods_spec_value gsv', 'g.id=gsv.goods_id')->where($where_base)->where(function($query) use($where_keywords) {
                self::SearchKeywordsWhereJoinType($query, $where_keywords);
            })->where(function($query) use($where_screening_price) {
                $query->whereOr($where_screening_price);
            })->group('gsv.value')->order('gsv.id desc')->field('gsv.value')->limit(200)->select()->toArray();
                return $data;
            }, 120);
        }
        return $data;
    }

    /**
     * 解析搜索页分类 id（category_id / cid，支持逗号多选）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-11
     * @desc    无分类时返回空；有分类则含自身及全部子分类
     * @param   [array]           $params [输入参数]
     */
    public static function SearchFilterCategoryIds($params = [])
    {
        $ids = array_merge(
            self::SearchRequestIdsValue(isset($params['category_id']) ? $params['category_id'] : ''),
            self::SearchRequestIdsValue(isset($params['cid']) ? $params['cid'] : ''),
            self::SearchFilterRequestValues(isset($params['category_ids']) ? $params['category_ids'] : '')
        );
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), function($id)
        {
            return $id > 0;
        })));
        if(empty($ids))
        {
            return [];
        }
        return GoodsCategoryService::GoodsCategoryItemsIds($ids);
    }

    /**
     * 搜索参数/规格下拉当前已选值
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-11
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @param   [string]         $field  [goods_params_values / goods_spec_values]
     * @param   [string]         $ascii  [psid / scid]
     */
    public static function SearchFilterSelectedValues($params, $field, $ascii)
    {
        $selected = [];
        if(!empty($params[$field]))
        {
            $selected = self::SearchFilterRequestValues($params[$field]);
        }
        if(!empty($params[$ascii]))
        {
            foreach(self::SearchRequestListValue($params[$ascii]) as $v)
            {
                $selected[] = AsciiToStr($v);
            }
        }
        return array_values(array_unique(array_filter(array_map('strval', $selected), function($item)
        {
            return $item !== '';
        })));
    }

    /**
     * 搜索商品参数筛选项（模板模式：按分类读商品参数模板，每项下拉）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-11
     * @desc    未开启「商品参数自定义模式」；有分类则读该分类及子分类模板，无分类则读全部启用模板
     * @param   [array]          $params [输入参数]
     */
    public static function SearchGoodsParamsTemplateFilterList($params = [])
    {
        $data = [];
        // 自定义模式开启则不走模板筛选项；后台关闭搜索参数时也不展示
        if(MyC('home_search_is_params', 0) != 1 || MyC('common_is_goods_parameters_custom_mode', 0) == 1)
        {
            return $data;
        }
        $category_ids = self::SearchFilterCategoryIds($params);
        $list = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_goods_params_template_filter_'.md5(json_encode($category_ids, JSON_UNESCAPED_UNICODE)), function() use($category_ids)
        {
            $list = [];
            $where = [
                ['is_enable', '=', 1],
            ];
            // 有分类：仅当前分类及子分类模板；无分类：全部启用模板
            if(!empty($category_ids))
            {
                $where[] = ['category_id', 'in', $category_ids];
            }
            $templates = Db::name('GoodsParamsTemplate')->where($where)->column('id');
            if(!empty($templates))
            {
                $scope = self::SearchParamsWhereTypeValue();
                $configs = Db::name('GoodsParamsTemplateConfig')->where([
                    ['template_id', 'in', $templates],
                    ['scope', 'in', $scope],
                ])->field('template_id,name,data_type,value')->order('id asc')->select()->toArray();

                // 配置行多语言替换（按模板内行序号）
                I18nService::ParamsTemplateConfigHandle($configs);
                // 按参数名合并可选项
                $group = [];
                foreach($configs as $c)
                {
                    $name = trim(strval($c['name']));
                    if($name === '')
                    {
                        continue;
                    }
                    if(!isset($group[$name]))
                    {
                        $group[$name] = [];
                    }
                    $raw = isset($c['value']) ? strval($c['value']) : '';
                    if($raw === '')
                    {
                        continue;
                    }
                    // 单选/多选按换行拆；输入型也允许逗号/换行多个备选
                    if(in_array(intval($c['data_type']), [1, 2], true))
                    {
                        $vals = explode("\n", str_replace(["\r\n", "\r"], "\n", $raw));
                    } else {
                        $vals = preg_split('/[\n,，]+/u', $raw);
                    }
                    foreach($vals as $val)
                    {
                        $val = trim($val);
                        if($val !== '' && !in_array($val, $group[$name], true))
                        {
                            $group[$name][] = $val;
                        }
                    }
                }
                foreach($group as $name=>$values)
                {
                    if(empty($values))
                    {
                        continue;
                    }
                    $list[] = [
                        'name'    => $name,
                        'options' => array_map(function($value)
                        {
                            return [
                                'value' => $value,
                                'id'    => StrToAscii($value),
                            ];
                        }, $values),
                    ];
                }
            }
            return $list;
        }, 300);

        // 回填当前选中
        $selected = self::SearchFilterSelectedValues($params, 'goods_params_values', 'psid');
        foreach($list as &$item)
        {
            $item['selected'] = '';
            foreach($item['options'] as &$opt)
            {
                $opt['is_active'] = in_array($opt['value'], $selected, true) ? 1 : 0;
                if($opt['is_active'] == 1)
                {
                    $item['selected'] = $opt['value'];
                }
            }
            unset($opt);
        }
        unset($item);
        return $list;
    }

    /**
     * 搜索商品规格筛选项（按分类读商品规格模板，每项下拉）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-08-11
     * @desc    有分类则读该分类及子分类模板，无分类则读全部启用模板
     * @param   [array]           $params [输入参数]
     */
    public static function SearchGoodsSpecTemplateFilterList($params = [])
    {
        $data = [];
        if(MyC('home_search_is_spec', 0) != 1)
        {
            return $data;
        }
        $category_ids = self::SearchFilterCategoryIds($params);
        $list = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_goods_spec_template_filter_'.md5(json_encode($category_ids, JSON_UNESCAPED_UNICODE)), function() use($category_ids)
        {
            $list = [];
            $where = [
                ['is_enable', '=', 1],
            ];
            // 有分类：仅当前分类及子分类模板；无分类：全部启用模板
            if(!empty($category_ids))
            {
                $where[] = ['category_id', 'in', $category_ids];
            }
            $templates = Db::name('GoodsSpecTemplate')->where($where)->field('id,name,content')->order('id asc')->select()->toArray();
            $group = [];
            foreach($templates as $t)
            {
                $name = trim(strval($t['name']));
                if($name === '' || empty($t['content']))
                {
                    continue;
                }
                if(!isset($group[$name]))
                {
                    $group[$name] = [];
                }
                $vals = preg_split('/[\n,，]+/u', strval($t['content']));
                foreach($vals as $val)
                {
                    $val = trim($val);
                    if($val !== '' && !in_array($val, $group[$name], true))
                    {
                        $group[$name][] = $val;
                    }
                }
            }
            foreach($group as $name=>$values)
            {
                if(empty($values))
                {
                    continue;
                }
                $list[] = [
                    'name'    => $name,
                    'options' => array_map(function($value)
                    {
                        return [
                            'value' => $value,
                            'id'    => StrToAscii($value),
                        ];
                    }, $values),
                ];
            }
            return $list;
        }, 300);

        $selected = self::SearchFilterSelectedValues($params, 'goods_spec_values', 'scid');
        foreach($list as &$item)
        {
            $item['selected'] = '';
            foreach($item['options'] as &$opt)
            {
                $opt['is_active'] = in_array($opt['value'], $selected, true) ? 1 : 0;
                if($opt['is_active'] == 1)
                {
                    $item['selected'] = $opt['value'];
                }
            }
            unset($opt);
        }
        unset($item);
        return $list;
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
        $category = null;
        $cid = empty($params['category_id']) ? (empty($params['cid']) ? 0 : intval($params['cid'])) : intval($params['category_id']);
        if(!empty($cid))
        {
            $category = GoodsCategoryService::GoodsCategoryRow(['id'=>$cid, 'field'=>'id,name,vice_name,describe,seo_title,seo_keywords,seo_desc']);
        }

        // 品牌（仅 brand 单品牌入口展示详情；bid 多选走列表高亮）
        $brand = null;
        if(!empty($params['brand']))
        {
            $bid = intval($params['brand']);
            if(!empty($bid))
            {
                $data_params = [
                    'field'     => 'id,name,describe,logo,website_url,seo_title,seo_keywords,seo_desc',
                    'where'     => [
                        ['id', '=', $bid]
                    ],
                    'm'         => 0,
                    'n'         => 1,
                ];
                $ret = BrandService::BrandList($data_params);
                if(!empty($ret['data']) && !empty($ret['data'][0]))
                {
                    $brand = $ret['data'][0];
                }
            }
        }

        return [
            'category'  => empty($category) ? null : $category,
            'brand'     => empty($brand) ? null : $brand,
        ];
    }

    /**
     * 搜索商品最大金额
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-21
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchGoodsMaxPrice($params = [])
    {
        $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_keywords_key').'_goods_max_price_', function()
        {
            return Db::name('GoodsSpecBase')->max('price');
        }, 300);
        if(is_array($data))
        {
            $data = reset($data);
        }
        return is_numeric($data) ? $data : 0;
    }

    /**
     * 搜索禁止
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-06-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchProhibitCheck($params = [])
    {
        // 用户UserAgent禁止验证
        if(!empty($_SERVER) && !empty($_SERVER['HTTP_USER_AGENT']))
        {
            $prohibit = MyC('home_search_prohibit_user_agent', '', true);
            if(!empty($prohibit))
            {
                $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
                $prohibit = explode(',', strtolower($prohibit));
                foreach($prohibit as $v)
                {
                    if(stripos($user_agent, $v) !== false)
                    {
                        return DataReturn(MyLang('illegal_access_tips').'('.$v.')', -1);
                    }
                }
            }
        }

        // 禁止关键字
        if(!empty($params['wd']))
        {
            $prohibit = MyC('home_search_prohibit_keywords', [], true);
            if(!empty($prohibit) && is_array($prohibit) && in_array($params['wd'], $prohibit))
            {
                return DataReturn(MyLang('illegal_access_tips').'('.$params['wd'].')', -1);
            }
        }

        return DataReturn('success', 0);
    }

    /**
     * 搜索是否需要登录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-06-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchIsLoginCheck($params = [])
    {
        $data = MyC('home_search_is_login_required', [], true);
        if(empty($data) || !is_array($data) || !in_array(APPLICATION_CLIENT_TYPE, $data))
        {
            return DataReturn('success', 0);
        }
        return DataReturn('error', -1);
    }

    /**
     * 搜索排行榜
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-10-14
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchRankingList($params = [])
    {
        $data = MyCacheRemember(SystemService::CacheKey('shopxo.cache_search_start_ranking_key').APPLICATION_CLIENT_TYPE, function() use($params)
        {
            // 数据缓存
            $data = [];

            // 销量
            $where = [
                ['is_shelves', '=', 1],
                ['is_delete_time', '=', 0],
                ['inventory', '>', 0],
            ];
            $field = 'id,title,images';
            $order_by = 'sales_count desc';
            // 搜索排行榜商品销量读取前钩子
            $hook_name = 'plugins_service_search_ranking_goods_sales_begin';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'where'         => &$where,
                'field'         => &$field,
                'order_by'      => &$order_by,
            ]);
            $goods = Db::name('Goods')->where($where)->field($field)->order($order_by)->limit(0, 10)->select()->toArray();
            if(!empty($goods))
            {
                $data[] = [
                    'name'  => MyLang('sales_title'),
                    'icon'  => 'icon-fire',
                    'data'  => $goods
                ];
            }

            // 评分
            $where = [
                ['g.is_shelves', '=', 1],
                ['g.is_delete_time', '=', 0],
                ['g.inventory', '>', 0],
                ['gc.business_type', '=', 'order'],
            ];
            $field = 'g.id,g.title,g.images,AVG(gc.rating) AS avg_rating';
            $order_by = 'avg_rating desc';
            // 搜索排行榜商品评分读取前钩子
            $hook_name = 'plugins_service_search_ranking_goods_star_begin';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'where'         => &$where,
                'field'         => &$field,
                'order_by'      => &$order_by,
            ]);
            $goods = Db::name('GoodsComments')->alias('gc')->join('goods g', 'g.id=gc.goods_id')->where($where)->field($field)->order($order_by)->group('g.id')->limit(0, 10)->select()->toArray();
            if(!empty($goods))
            {
                $data[] = [
                    'name'  => MyLang('score_title'),
                    'icon'  => 'icon-fire',
                    'data'  => $goods
                ];
            }

            // 收藏
            $where = [
                ['g.is_shelves', '=', 1],
                ['g.is_delete_time', '=', 0],
                ['g.inventory', '>', 0],
            ];
            $field = 'g.id,g.title,g.images,COUNT(g.id) AS count';
            $order_by = 'count desc';
            // 搜索排行榜商品收藏读取前钩子
            $hook_name = 'plugins_service_search_ranking_goods_favor_begin';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'where'         => &$where,
                'field'         => &$field,
                'order_by'      => &$order_by,
            ]);
            $goods = Db::name('GoodsFavor')->alias('gf')->join('goods g', 'g.id=gf.goods_id')->where($where)->field($field)->order($order_by)->group('g.id')->limit(0, 10)->select()->toArray();
            if(!empty($goods))
            {
                $data[] = [
                    'name'  => MyLang('favor_title'),
                    'icon'  => 'icon-fire',
                    'data'  => $goods
                ];
            }
            // 数据处理
            if(!empty($data))
            {
                foreach($data as &$v)
                {
                    if(!empty($v['data']))
                    {
                        foreach($v['data'] as &$vs)
                        {
                            $vs['goods_url'] = GoodsService::GoodsUrlCreate($vs['id']);
                            $vs['images'] = ResourcesService::AttachmentPathViewHandle($vs['images']);
                        }
                    }
                }
            }

            return $data;
        }, 180);
        return $data;
    }

    /**
     * 搜索开始数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-10-14
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SearchStartData($params = [])
    {
        return DataReturn('success', 0, [
            // 推荐关键字
            'search_keywords'  => self::SearchKeywordsList($params),
            // 排行
            'ranking_list'     => self::SearchRankingList($params),
        ]);
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SearchHistoryDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 删除操作
        if(Db::name('SearchHistory')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 清空全部
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SearchHistoryAllDelete($params = [])
    {
        $where = [
            ['id', '>', 0]
        ];
        if(Db::name('SearchHistory')->where($where)->delete() === false)
        {
            return DataReturn(MyLang('operate_fail'), -100);
        }
        return DataReturn(MyLang('operate_success'));
    }
}
?>