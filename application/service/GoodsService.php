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
use think\facade\Hook;
use app\service\ResourcesService;
use app\service\BrandService;
use app\service\RegionService;
use app\service\WarehouseGoodsService;

/**
 * 商品服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsService
{
    // 规格转成字符串分割符号
    public static $goods_spec_to_string_separator = '{|}';

    /**
     * 根据id获取一条商品分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryRow($params = [])
    {
        if(empty($params['id']))
        {
            return null;
        }
        $field = empty($params['field']) ? 'id,pid,icon,name,vice_name,describe,bg_color,big_images,sort,is_home_recommended' : $params['field'];
        $data = self::GoodsCategoryDataDealWith([Db::name('GoodsCategory')->field($field)->where(['is_enable'=>1, 'id'=>intval($params['id'])])->find()]);
        return empty($data[0]) ? null : $data[0];
    }

    /**
     * 获取所有分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryAll($params = [])
    {
        // 从缓存获取
        $key = config('shopxo.cache_goods_category_key');
        $data = cache($key);
        if(empty($data) || config('app_debug'))
        {
            // 获取分类
            $params['where'] = ['pid'=>0, 'is_enable'=>1];
            $data = self::GoodsCategory($params);

            // 存储缓存
            cache($key, $data, 60);
        }
        return $data;
    }

    /**
     * 获取分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategory($params = [])
    {
        // 获取分类
        $where = empty($params['where']) ? ['pid'=>0, 'is_enable'=>1] : $params['where'];
        $data = self::GoodsCategoryList(['where'=>$where]);
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $where['pid'] = $v['id'];
                $v['items'] = self::GoodsCategoryList(['where'=>$where]);
                if(!empty($v['items']))
                {
                    // 一次性查出所有二级下的三级、再做归类、避免sql连接超多
                    $where['pid'] = array_column($v['items'], 'id');
                    $itemss = self::GoodsCategoryList(['where'=>$where]);
                    if(!empty($itemss))
                    {
                        foreach($v['items'] as &$vs)
                        {
                            foreach($itemss as $vss)
                            {
                                if($vs['id'] == $vss['pid'])
                                {
                                    $vs['items'][] = $vss;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 根据pid获取商品分类列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCategoryList($params = [])
    {
        // 条件、附加必须启用状态
        $where = empty($params['where']) ? [] : $params['where'];
        $where['is_enable'] = 1;

        // 数量、默认0,0则全部
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 0;

        $field = 'id,pid,icon,name,vice_name,describe,bg_color,big_images,sort,is_home_recommended,seo_title,seo_keywords,seo_desc';
        $data = Db::name('GoodsCategory')->field($field)->where($where)->order('sort asc')->limit($m, $n)->select();
        return self::GoodsCategoryDataDealWith($data);
    }

    /**
     * 商品分类数据处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-06
     * @desc    description
     * @param   [array]          $data [商品分类数据 二维数组]
     */
    public static function GoodsCategoryDataDealWith($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as &$v)
            {
                if(is_array($v))
                {
                    if(isset($v['icon']))
                    {
                        $v['icon'] = ResourcesService::AttachmentPathViewHandle($v['icon']);
                    }
                    if(isset($v['big_images']))
                    {
                        $v['big_images_old'] = $v['big_images'];
                        $v['big_images'] = ResourcesService::AttachmentPathViewHandle($v['big_images']);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 获取首页楼层数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function HomeFloorList($params = [])
    {
        // 商品数量
        $goods_count = MyC('home_index_floor_goods_max_count', 8, true);
        $goods_category_count = MyC('home_index_floor_left_goods_category_max_count', 6, true);

        // 排序配置
        $floor_order_by_type_list = lang('goods_order_by_type_list');
        $floor_order_by_rule_list = lang('goods_order_by_rule_list');
        $floor_order_by_type = MyC('home_index_floor_goods_order_by_type', 0, true);
        $floor_order_by_rule = MyC('home_index_floor_goods_order_by_rule', 0, true);
        // 排序字段名称
        $order_by_field = array_key_exists($floor_order_by_type, $floor_order_by_type_list) ? $floor_order_by_type_list[$floor_order_by_type]['value'] : $floor_order_by_type_list[0]['value'];
        // 排序规则
        $order_by_rule = array_key_exists($floor_order_by_rule, $floor_order_by_rule_list) ? $floor_order_by_rule_list[$floor_order_by_rule]['value'] : $floor_order_by_rule_list[0]['value'];
        // 排序
        $order_by = implode(' '.$order_by_rule.', ', explode(',', $order_by_field)).' '.$order_by_rule;

        // 缓存
        $key = config('shopxo.cache_goods_floor_list_key');
        $data = cache($key);
        if(empty($data) || config('app_debug'))
        {
            // 商品大分类
            $where = ['pid'=>0, 'is_home_recommended'=>1, 'is_enable'=>1];
            $data = self::GoodsCategoryList(['where'=>$where]);
            if(!empty($data))
            {
                $level = MyC('common_show_goods_category_level', 3, true);
                if($level > 1)
                {
                    foreach($data as &$c)
                    {
                        // 获取二级分类
                        $where['pid'] = $c['id'];
                        $c['items'] = self::GoodsCategoryList(['where'=>$where, 'n'=>$goods_category_count]);
                    }
                }

                // 楼层关键字从配置中读取
                $floor_keywords = MyC('home_index_floor_top_right_keywords');
                if(!empty($floor_keywords))
                {
                    $floor_keywords = json_decode($floor_keywords, true);
                }

                // 根据分类获取楼层商品
                foreach($data as &$v)
                {
                    // 获取分类ids
                    $category_ids = self::GoodsCategoryItemsIds([$v['id']], 1);

                    // 获取商品ids
                    $where = [
                        'gci.category_id'       => $category_ids,
                        'g.is_home_recommended' => 1,
                        'g.is_shelves'          => 1,
                    ];
                    $v['goods_ids'] = Db::name('Goods')->alias('g')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'g.id=gci.goods_id')->where($where)->group('g.id')->order($order_by)->limit($goods_count)->column('g.id');
                    $v['goods'] = [];

                    // 楼层关键字
                    $v['config_keywords'] = empty($floor_keywords[$v['id']]) ? [] : explode(',', $floor_keywords[$v['id']]);
                }
            }

            // 存储缓存
            cache($key, $data, 60);
        }

        // 商品读取、商品信息需要实时读取
        if(!empty($data) && is_array($data))
        {
            // 去除分类关键字前缀
            $order_by = str_replace('g.', '', $order_by);

            // 根据分类获取楼层商品
            foreach($data as &$v)
            {
                if(!empty($v['goods_ids']) && is_array($v['goods_ids']))
                {
                    $res = self::GoodsList(['where'=>['id'=>$v['goods_ids'], 'is_home_recommended'=>1, 'is_shelves'=>1], 'm'=>0, 'n'=>$goods_count, 'field'=>'*', 'order_by'=>$order_by]);
                    $v['goods'] = $res['data'];
                }
            }
        }
        return $data;
    }

    /**
     * 获取商品分类下的所有分类id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $ids       [分类id数组]
     * @param   [int]            $is_enable [是否启用 null, 0否, 1是]
     * @param   [string]         $order_by  [排序, 默认sort asc]
     */
    public static function GoodsCategoryItemsIds($ids = [], $is_enable = null, $order_by = 'sort asc')
    {
        $where = ['pid'=>$ids];
        if($is_enable !== null)
        {
            $where['is_enable'] = $is_enable;
        }
        $data = Db::name('GoodsCategory')->where($where)->order($order_by)->column('id');
        if(!empty($data))
        {
            $temp = self::GoodsCategoryItemsIds($data, $is_enable, $order_by);
            if(!empty($temp))
            {
                $data = array_merge($data, $temp);
            }
        }
        $data = empty($data) ? $ids : array_unique(array_merge($ids, $data));
        return $data;
    }

    /**
     * 获取分类与商品关联总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     * @param   array           $where [条件]
     */
    public static function CategoryGoodsTotal($where = [])
    {
        return (int) Db::name('Goods')->alias('g')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'g.id=gci.goods_id')->where($where)->count('DISTINCT g.id');
    }

    /**
     * 获取分类与商品关联列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   array           $params [输入参数: where, field, is_photo]
     */
    public static function CategoryGoodsList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? 'g.*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'g.id desc' : trim($params['order_by']);

        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $data = Db::name('Goods')->alias('g')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'g.id=gci.goods_id')->field($field)->where($where)->group('g.id')->order($order_by)->limit($m, $n)->select();
        
        // 数据处理
        return self::GoodsDataHandle($data, $params);
    }

    /**
     * 商品数据处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-08T23:16:42+0800
     * @param    [array]                   $data   [商品列表]
     * @param    [array]                   $params [输入参数]
     */
    public static function GoodsDataHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 其它额外处理
            $is_photo = (isset($params['is_photo']) && $params['is_photo'] == true) ? true : false;
            $is_spec = (isset($params['is_spec']) && $params['is_spec'] == true) ? true : false;
            $is_content_app = (isset($params['is_content_app']) && $params['is_content_app'] == true) ? true : false;
            $is_category = (isset($params['is_category']) && $params['is_category'] == true) ? true : false;
            $is_params = (isset($params['is_params']) && $params['is_params'] == true) ? true : false;
            $data_key_field = empty($params['data_key_field']) ? 'id' : $params['data_key_field'];

            // 开始处理数据
            foreach($data as &$v)
            {
                // 数据主键id
                $data_id = isset($v[$data_key_field]) ? $v[$data_key_field] : 0;

                // 商品处理前钩子
                $hook_name = 'plugins_service_goods_handle_begin';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'goods'         => &$v,
                    'goods_id'      => $data_id,
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }

                // 商品价格容器
                $v['price_container'] = [
                    'price'                 => isset($v['price']) ? $v['price'] : 0.00,
                    'min_price'             => isset($v['min_price']) ? $v['min_price'] : 0.00,
                    'max_price'             => isset($v['max_price']) ? $v['max_price'] : 0.00,
                    'original_price'        => isset($v['original_price']) ? $v['original_price'] : 0.00,
                    'min_original_price'    => isset($v['min_original_price']) ? $v['min_original_price'] : 0.00,
                    'max_original_price'    => isset($v['max_original_price']) ? $v['max_original_price'] : 0.00,
                ];

                // 商品url地址
                if(!empty($data_id))
                {
                    $v['goods_url'] = MyUrl('index/goods/index', ['id'=>$data_id]);
                }

                // 获取相册
                if($is_photo && !empty($data_id))
                {
                    $v['photo'] = self::GoodsPhotoData($data_id);
                    if(!empty($v['photo']))
                    {
                        foreach($v['photo'] as &$vs)
                        {
                            $vs['images_old'] = $vs['images'];
                            $vs['images'] = ResourcesService::AttachmentPathViewHandle($vs['images']);
                        }
                    }
                }

                // 商品封面图片
                if(isset($v['images']))
                {
                    // 无封面图片
                    if(empty($v['images']))
                    {
                        // 获取商品封面图片
                        $v['images'] = ResourcesService::AttachmentPathHandle(self::GoodsImagesCoverHandle($data_id, isset($v['photo']) ? $v['photo'] : []));
                    }
                    $v['images_old'] = $v['images'];
                    $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                }

                // 视频
                if(isset($v['video']))
                {
                    $v['video_old'] = $v['video'];
                    $v['video'] = ResourcesService::AttachmentPathViewHandle($v['video']);
                }

                // PC内容处理
                if(isset($v['content_web']))
                {
                    $v['content_web'] = ResourcesService::ContentStaticReplace($v['content_web'], 'get');
                }

                // 虚拟商品展示数据
                if(isset($v['fictitious_goods_value']))
                {
                    $v['fictitious_goods_value'] = ResourcesService::ContentStaticReplace($v['fictitious_goods_value'], 'get');
                }

                // 产地
                if(isset($v['place_origin']))
                {
                    $v['place_origin_name'] = empty($v['place_origin']) ? null : RegionService::RegionName($v['place_origin']);
                }

                // 品牌
                if(isset($v['brand_id']))
                {
                    $v['brand_name'] = empty($v['brand_id']) ? null : BrandService::BrandName($v['brand_id']);
                }

                // 时间
                if(!empty($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(!empty($v['upd_time']))
                {
                    $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
                }

                // 是否需要分类名称
                if($is_category && !empty($data_id))
                {
                    $v['category_ids'] = Db::name('GoodsCategoryJoin')->where(['goods_id'=>$data_id])->column('category_id');
                    $category_name = Db::name('GoodsCategory')->where(['id'=>$v['category_ids']])->column('name');
                    $v['category_text'] = implode('，', $category_name);
                }

                // 规格基础
                if(isset($v['spec_base']))
                {
                    $v['spec_base'] = empty($v['spec_base']) ? '' : json_decode($v['spec_base'], true);
                }

                // 获取规格
                if($is_spec && !empty($data_id))
                {
                    $v['specifications'] = self::GoodsSpecificationsData($data_id);
                }

                // 获取商品参数
                if($is_params && !empty($data_id))
                {
                    $v['parameters'] = self::GoodsParametersData($data_id);
                }

                // 获取app内容
                if($is_content_app && !empty($data_id))
                {
                    $v['content_app'] = self::GoodsContentAppData(['goods_id'=>$data_id]);
                }

                // 展示字段
                $v['show_field_original_price_text'] = '原价';
                $v['show_field_price_text'] = '销售价';

                // 商品处理后钩子
                $hook_name = 'plugins_service_goods_handle_end';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'goods'         => &$v,
                    'goods_id'      => isset($data_id) ? $data_id : 0,
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }
            }
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 获取商品封面图片
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-19
     * @desc    description
     * @param   [int]             $goods_id [商品id]
     * @param   [array]           $photo    [商品相册]
     */
    public static function GoodsImagesCoverHandle($goods_id = 0, $photo = [])
    {
        // 是否已存在相册
        if(!empty($photo))
        {
            $photo = self::GoodsPhotoData($goods_id);
            if(!empty($photo[0]) && !empty($photo[0]['images']))
            {
                $images = $photo[0]['images'];
            }
        }

        // 无主图，并且有商品id
        if(empty($images) && !empty($goods_id))
        {
            $images = Db::name('GoodsPhoto')->where(['goods_id'=>$goods_id, 'is_show'=>1])->order('sort asc')->value('images');
        }

        return isset($images) ? $images : '';
    }

    /**
     * 商品相册
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-19
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsPhotoData($goods_id)
    {
        return Db::name('GoodsPhoto')->where(['goods_id'=>$goods_id, 'is_show'=>1])->order('sort asc')->select();
    }

    /**
     * 获取商品手机详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [array]                  [app内容]
     */
    public static function GoodsContentAppData($params = [])
    {
        $data = Db::name('GoodsContentApp')->where(['goods_id'=>$params['goods_id']])->field('id,images,content')->order('sort asc')->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['images_old'] = $v['images'];
                $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                $v['content_old'] = $v['content'];
                $v['content'] = empty($v['content']) ? null : explode("\n", $v['content']);
            }
        }
        return $data;
    }

    /**
     * 获取商品规格
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [int]           $goods_id [商品id]
     */
    public static function GoodsSpecificationsData($goods_id)
    {
        // 条件
        $where = ['goods_id'=>$goods_id];

        // 规格类型
        $choose = Db::name('GoodsSpecType')->where($where)->order('id asc')->select();
        if(!empty($choose))
        {
            // 数据处理
            foreach($choose as &$temp_type)
            {
                $temp_type_value = json_decode($temp_type['value'], true);
                foreach($temp_type_value as &$vs)
                {
                    $vs['images'] = ResourcesService::AttachmentPathViewHandle($vs['images']);
                }
                $temp_type['value'] = $temp_type_value;
                $temp_type['add_time'] = date('Y-m-d H:i:s');
            }

            // 只有一个规格的时候直接获取规格值的库存数
            if(count($choose) == 1)
            {
                foreach($choose[0]['value'] as &$temp_spec)
                {
                    $temp_spec_params = [
                        'id'    => $goods_id,
                        'spec'  => [
                            ['type' => $choose[0]['name'], 'value' => $temp_spec['name']]
                        ],
                    ];
                    $temp = self::GoodsSpecDetail($temp_spec_params);
                    if($temp['code'] == 0)
                    {
                        $temp_spec['is_only_level_one'] = 1;
                        $temp_spec['inventory'] = $temp['data']['spec_base']['inventory'];
                    }
                }
            }
        }
        return ['choose'=>$choose];
    }

    /**
     * 获取商品参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-31
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsParametersData($goods_id)
    {
        $base = [];
        $detail = [];
        $list = Db::name('GoodsParams')->where(['goods_id'=>$goods_id])->order('id asc')->select();
        if(!empty($list))
        {
            foreach($list as $v)
            {
                $temp = [
                    'name'  => $v['name'],
                    'value' => $v['value'],
                ];

                // 基础
                if(in_array($v['type'], [0,2]))
                {
                    $base[] = $temp;
                }

                // 详情
                if(in_array($v['type'], [0,1]))
                {
                    $detail[] = $temp;
                }
            }
        }

        // 返回的数据
        $data = [
            'base'      => $base,
            'detail'    => $detail,
        ];

        // 商品参数钩子
        $hook_name = 'plugins_service_goods_parameters_data';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'goods_id'      => $goods_id,
        ]);

        return $data;
    }

    /**
     * 商品规格简洁的数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-15
     * @desc    description
     * @param   [array]          $data [规格数据]
     */
    public static function GoodsSpecificationsConcise($data)
    {
        $result = [];
        if(!empty($data))
        {
            foreach($data as $v)
            {
                $result[] = array_column($v['value'], 'name');
            }
        }
        return $result;
    }

    /**
     * 获取商品当前实际存在的规格
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [int]           $goods_id [商品id]
     */
    public static function GoodsSpecificationsActual($goods_id)
    {
        // 规格名称
        $where = ['goods_id'=>$goods_id];
        $title = Db::name('GoodsSpecType')->where($where)->column('name');
       
        // 规格值
        $value = Db::name('GoodsSpecValue')->where($where)->field('goods_spec_base_id,value')->select();
        $group = [];
        if(!empty($value))
        {
            foreach($value as $v)
            {
                // 不存在则添加
                if(!isset($group[$v['goods_spec_base_id']]))
                {
                    $group[$v['goods_spec_base_id']] = [];
                    $group[$v['goods_spec_base_id']]['base_id'] = $v['goods_spec_base_id'];
                }

                // 多个规格组合
                $group[$v['goods_spec_base_id']]['value'][] = $v['value'];
            }
            foreach($group as &$gv)
            {
                $gv['value'] = implode(self::$goods_spec_to_string_separator, $gv['value']);
            }
            sort($group);
        }
        
        return [
            'title' => $title,
            'value' => $group,
        ];
    }

    /**
     * 商品访问统计加1
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsAccessCountInc($params = [])
    {
        if(!empty($params['goods_id']))
        {
            return Db::name('Goods')->where(['id'=>intval($params['goods_id'])])->setInc('access_count');
        }
        return false;
    }

    /**
     * 获取商品总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     * @param   [array]           $where [条件]
     */
    public static function GoodsTotal($where = [])
    {
        return (int) Db::name('Goods')->where($where)->count();
    }

    /**
     * 获取商品列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   array           $params [输入参数: where, field, is_photo]
     */
    public static function GoodsList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);

        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $data = Db::name('Goods')->field($field)->where($where)->order($order_by)->limit($m, $n)->select();
        
        return self::GoodsDataHandle($data, $params);
    }

    /**
     * 商品保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-10T01:02:11+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'title',
                'checked_data'      => '2,160',
                'error_msg'         => '标题名称格式 2~160 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'simple_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => '商品简述格式 最多230个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'model',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => '商品型号格式 最多30个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => '请至少选择一个商品分类',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'inventory_unit',
                'checked_data'      => '1,6',
                'error_msg'         => '库存单位格式 1~6 个字符',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'buy_min_number',
                'error_msg'         => '请填写有效的最低起购数量',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'site_type',
                'checked_data'      => array_merge([-1], array_column(lang('common_site_type_list'), 'value')),
                'is_checked'        => 2,
                'error_msg'         => '商品类型数据值范围有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_title',
                'checked_data'      => '100',
                'is_checked'        => 1,
                'error_msg'         => 'SEO标题格式 最多100个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_keywords',
                'checked_data'      => '130',
                'is_checked'        => 1,
                'error_msg'         => 'SEO关键字格式 最多130个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => 'SEO描述格式 最多230个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 规格基础
        $specifications_base = self::GetFormGoodsSpecificationsBaseParams($params);
        if($specifications_base['code'] != 0)
        {
            return $specifications_base;
        }

        // 规格值
        $specifications = self::GetFormGoodsSpecificationsParams($params);
        if($specifications['code'] != 0)
        {
            return $specifications;
        }
        
        // 相册
        $photo = self::GetFormGoodsPhotoParams($params);
        if($photo['code'] != 0)
        {
            return $photo;
        }

        // 手机端详情
        $content_app = self::GetFormGoodsContentAppParams($params);
        if($content_app['code'] != 0)
        {
            return $content_app;
        }

        // 其它附件
        $data_fields = ['images', 'video'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }

        // 编辑器内容
        $content_web = empty($params['content_web']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['content_web']), 'add');
        $fictitious_goods_value = empty($params['fictitious_goods_value']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['fictitious_goods_value']), 'add');

        // 赠送积分
        $give_integral = max(0, (isset($params['give_integral']) && $params['give_integral'] <= 100) ? intval($params['give_integral']) : 0);

        // 封面图片、默认相册第一张
        $images = empty($attachment['data']['images']) ? (isset($photo['data'][0]) ? $photo['data'][0] : '') : $attachment['data']['images'];

        // 基础数据
        $data = [
            'title'                     => $params['title'],
            'title_color'               => empty($params['title_color']) ? '' : $params['title_color'],
            'simple_desc'               => $params['simple_desc'],
            'model'                     => $params['model'],
            'place_origin'              => isset($params['place_origin']) ? intval($params['place_origin']) : 0,
            'inventory_unit'            => $params['inventory_unit'],
            'give_integral'             => $give_integral,
            'buy_min_number'            => max(1, isset($params['buy_min_number']) ? intval($params['buy_min_number']) : 1),
            'buy_max_number'            => isset($params['buy_max_number']) ? intval($params['buy_max_number']) : 0,
            'is_deduction_inventory'    => isset($params['is_deduction_inventory']) ? intval($params['is_deduction_inventory']) : 0,
            'is_shelves'                => isset($params['is_shelves']) ? intval($params['is_shelves']) : 0,
            'content_web'               => $content_web,
            'photo_count'               => count($photo['data']),
            'is_home_recommended'       => isset($params['is_home_recommended']) ? intval($params['is_home_recommended']) : 0,
            'images'                    => $images,
            'brand_id'                  => isset($params['brand_id']) ? intval($params['brand_id']) : 0,
            'video'                     => $attachment['data']['video'],
            'seo_title'                 => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'              => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'                  => empty($params['seo_desc']) ? '' : $params['seo_desc'],
            'is_exist_many_spec'        => empty($specifications['data']['title']) ? 0 : 1,
            'spec_base'                 => empty($specifications_base['data']) ? '' : json_encode($specifications_base['data'], JSON_UNESCAPED_UNICODE),
            'fictitious_goods_value'    => $fictitious_goods_value,
            'site_type'                 => isset($params['site_type']) ? $params['site_type'] : -1,
        ];

        // 商品保存处理钩子
        $hook_name = 'plugins_service_goods_save_handle';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'spec'          => $specifications['data'],
            'goods_id'      => isset($params['id']) ? intval($params['id']) : 0,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 启动事务
        Db::startTrans();

        // 添加/编辑
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $goods_id = Db::name('Goods')->insertGetId($data);
        } else {
            $goods = Db::name('Goods')->find($params['id']);
            $data['upd_time'] = time();
            if(Db::name('Goods')->where(['id'=>intval($params['id'])])->update($data))
            {
                $goods_id = $params['id'];
            }
        }

        // 是否成功
        $status = false;
        if(isset($goods_id) && $goods_id > 0)
        {
            // 分类
            $ret = self::GoodsCategoryInsert(explode(',', $params['category_id']), $goods_id);
            if($ret['code'] != 0)
            {
                // 回滚事务
                Db::rollback();
                return $ret;
            }

            // 规格
            $ret = self::GoodsSpecificationsInsert($specifications['data'], $goods_id);
            if($ret['code'] != 0)
            {
                // 回滚事务
                Db::rollback();
                return $ret;
            } else {
                // 更新商品基础信息
                $ret = self::GoodsSaveBaseUpdate($params, $goods_id);
                if($ret['code'] != 0)
                {
                    // 回滚事务
                    Db::rollback();
                    return $ret;
                }
            }

            // 相册
            $ret = self::GoodsPhotoInsert($photo['data'], $goods_id);
            if($ret['code'] != 0)
            {
                // 回滚事务
                Db::rollback();
                return $ret;
            }

            // 手机详情
            $ret = self::GoodsContentAppInsert($content_app['data'], $goods_id);
            if($ret['code'] != 0)
            {
                // 回滚事务
                Db::rollback();
                return $ret;
            }

            // 商品参数
            $ret = self::GoodsParametersInsert($params, $goods_id);
            if($ret['code'] != 0)
            {
                // 回滚事务
                Db::rollback();
                return $ret;
            }

            // 仓库规格库存同步
            $ret = WarehouseGoodsService::GoodsSpecChangeInventorySync($goods_id);
            if($ret['code'] != 0)
            {
                // 回滚事务
                Db::rollback();
                return $ret;
            }

            // 操作成功
            $status = true;
        }

        // 事务处理
        if($status)
        {
            Db::commit();
        } else {
            Db::rollback();
        }

        // 商品保存后钩子
        $hook_name = 'plugins_service_goods_save_end';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => $data,
            'goods_id'      => $goods_id,
        ]);

        // 返回信息
        if($status)
        {
            return DataReturn('操作成功', 0);
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 商品参数添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-31
     * @desc    description
     * @param   [array]             $params   [输入参数]
     * @param   [int]               $goods_id [商品id]
     */
    public static function GoodsParametersInsert($params, $goods_id)
    {
        // 删除商品参数
        Db::name('GoodsParams')->where(['goods_id'=>$goods_id])->delete();

        // 展示范围、参数名称、参数值
        if(!empty($params['parameters_type']) && !empty($params['parameters_name']) && !empty($params['parameters_value']) && is_array($params['parameters_type']) && is_array($params['parameters_name']) && is_array($params['parameters_value']))
        {
            $data = [];
            $time = time();
            foreach($params['parameters_type'] as $k=>$v)
            {
                if(isset($params['parameters_name'][$k]) && isset($params['parameters_value'][$k]))
                {
                    $data[] = [
                        'type'      => $v,
                        'name'      => $params['parameters_name'][$k],
                        'value'     => $params['parameters_value'][$k],
                        'goods_id'  => $goods_id,
                        'add_time'  => $time,
                    ];
                }
            }
            if(!empty($data))
            {
                if(Db::name('GoodsParams')->insertAll($data) < count($data))
                {
                    return DataReturn('规格参数添加失败', -1);
                }
            }
        }
        return DataReturn('添加成功', 0);
    }

    /**
     * 商品保存基础信息更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T01:56:42+0800
     * @param    [array]          $params   [输入参数]
     * @param    [int]            $goods_id [商品id]
     */
    public static function GoodsSaveBaseUpdate($params, $goods_id)
    {
        $data = Db::name('GoodsSpecBase')->field('min(price) AS min_price, max(price) AS max_price, sum(inventory) AS inventory, min(original_price) AS min_original_price, max(original_price) AS max_original_price')->where(['goods_id'=>$goods_id])->find();
        if(empty($data))
        {
            return DataReturn('没找到商品基础信息', -1);
        }

        // 销售价格 - 展示价格
        $data['price'] = (!empty($data['max_price']) && $data['min_price'] != $data['max_price']) ? $data['min_price'].'-'.$data['max_price'] : $data['min_price'];

        // 原价价格 - 展示价格
        $data['original_price'] = (!empty($data['max_original_price']) && $data['min_original_price'] != $data['max_original_price']) ? $data['min_original_price'].'-'.$data['max_original_price'] : $data['min_original_price'];

        // 更新商品表
        $data['upd_time'] = time();
        if(Db::name('Goods')->where(['id'=>$goods_id])->update($data))
        {
            return DataReturn('操作成功', 0);
        }
        return DataReturn('操作失败', 0);
    }

    /**
     * 获取规格值参数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GetFormGoodsSpecificationsParams($params = [])
    {
        $data = [];
        $title = [];
        $images = [];

        // 基础字段数据字段长度
        $base_count = 6;

        // 规格值
        foreach($params as $k=>$v)
        {
            if(substr($k, 0, 15) == 'specifications_')
            {
                $keys = explode('_', $k);
                if(count($keys) > 1)
                {
                    if($keys[1] != 'name')
                    {
                        foreach($v as $ks=>$vs)
                        {
                            if($keys[1] == 'extends')
                            {
                                $data[$ks][] = empty($vs) ? null : htmlspecialchars_decode($vs);
                            } else {
                                $data[$ks][] = $vs;
                            }
                        }
                    }
                }
            }
        }

        // 规格处理
        if(!empty($data[0]))
        {
            $count = count($data[0])-$base_count;
            if($count > 0)
            {
                // 列之间是否存在相同的值
                $column_value = [];
                foreach($data as $data_value)
                {
                    foreach($data_value as $temp_key=>$temp_value)
                    {
                        if($temp_key < $count)
                        {
                            $column_value[$temp_key][] = $temp_value;
                        }
                    }
                }
                if(!empty($column_value) && count($column_value) > 1)
                {
                    $temp_column = [];
                    foreach($column_value as $column_key=>$column_val)
                    {
                        foreach($column_value as $column_keys=>$column_vals)
                        {
                            if($column_key != $column_keys)
                            {
                                $temp = array_intersect($column_val, $column_vals);
                                $temp_column = array_merge($temp_column, $temp);
                            }
                        }
                    }
                    if(!empty($temp_column))
                    {
                        return DataReturn('规格值列之间不能重复['.implode(',', array_unique($temp_column)).']', -1);
                    }
                }

                // 规格值是否重复
                if(!empty($column_value[0]))
                {
                    $temp_row_data = [];
                    $temp_row_count = count($column_value);
                    foreach($column_value[0] as $row_key=>$row_value)
                    {
                        for($i=0; $i<$temp_row_count; $i++)
                        {
                            if(isset($column_value[$i][$row_key]))
                            {
                                if(isset($temp_row_data[$row_key]))
                                {
                                    $temp_row_data[$row_key] .= $column_value[$i][$row_key];
                                } else {
                                    $temp_row_data[$row_key] = $column_value[$i][$row_key];
                                }
                            }
                        }
                    }
                    if(!empty($temp_row_data))
                    {
                        $unique_all = array_unique($temp_row_data);
                        $repeat_rows_all = array_diff_assoc($temp_row_data, $unique_all); 
                        if(!empty($repeat_rows_all))
                        {
                            return DataReturn('规格值不能重复['.implode(',', array_unique($repeat_rows_all)).']', -1);
                        }
                    }
                }
                
                // 规格名称
                $names_value = [];
                $names = array_slice($data[0], 0, $count);
                foreach($names as $v)
                {
                    foreach($params as $ks=>$vs)
                    {
                        if(substr($ks, 0, 21) == 'specifications_value_')
                        {
                            if(in_array($v, $vs))
                            {
                                $key = substr($ks, 21);
                                if(!empty($params['specifications_name_'.$key]))
                                {
                                    $title[$params['specifications_name_'.$key]] = [
                                        'name'  => $params['specifications_name_'.$key],
                                        'value' => array_unique($vs),
                                    ];
                                    $names_value[] = $params['specifications_name_'.$key];
                                }
                            }
                        }
                    }
                }

                // 规格名称列之间是否存在重复
                $unique_all = array_unique($names_value);
                $repeat_names_all = array_diff_assoc($names_value, $unique_all); 
                if(!empty($repeat_names_all))
                {
                    return DataReturn('规格名称列之间不能重复['.implode(',', array_unique($repeat_names_all)).']', -1);
                }
            } else {
                if(empty($data[0][0]) || $data[0][0] <= 0)
                {
                    return DataReturn('请填写有效的规格销售价格', -1);
                }
                if(!isset($data[0][1]) || $data[0][1] < 0)
                {
                    return DataReturn('请填写规格库存', -1);
                }
            }
        } else {
            return DataReturn('请填写规格', -1);
        }

        // 规格图片
        if(!empty($params['spec_images_name']) && !empty($params['spec_images']))
        {
            foreach($params['spec_images_name'] as $k=>$v)
            {
                if(!empty($params['spec_images'][$k]))
                {
                    $images[$v] = $params['spec_images'][$k];
                }
            }
        }

        return DataReturn('success', 0, ['data'=>$data, 'title'=>$title, 'images'=>$images]);
    }

    /**
     * 获取规格基础参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-09-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GetFormGoodsSpecificationsBaseParams($params = [])
    {
        $result = [];
        foreach($params as $k=>$v)
        {
            if(substr($k, 0, 16) == 'spec_base_title_')
            {
                $key = substr($k, 16);
                $result[] = [
                    'title'     => $v,
                    'value'     => isset($params['spec_base_value_'.$key]) ? $params['spec_base_value_'.$key] : [],
                ];
            }
        }
        return DataReturn('success', 0, $result);
    }

    /**
     * 获取商品相册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [array]                  [一维数组但图片地址]
     */
    public static function GetFormGoodsPhotoParams($params = [])
    {
        if(empty($params['photo']))
        {
            return DataReturn('请上传相册', -1);
        }

        $result = [];
        if(!empty($params['photo']) && is_array($params['photo']))
        {
            foreach($params['photo'] as $v)
            {
                $result[] = ResourcesService::AttachmentPathHandle($v);
            }
        }
        return DataReturn('success', 0, $result);
    }

    /**
     * 获取app内容
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-09
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function GetFormGoodsContentAppParams($params = [])
    {
        // 开始处理
        $result = [];
        $name = 'content_app_';
        foreach($params AS $k=>$v)
        {
            if(substr($k, 0, 12) == $name)
            {
                $key = explode('_', str_replace($name, '', $k));
                if(count($key) == 2)
                {
                    $result[$key[1]][$key[0]] = $v;
                    if($key[0] == 'images')
                    {
                        $result[$key[1]][$key[0]] = ResourcesService::AttachmentPathHandle($v);
                    }
                }
            }
        }
        return DataReturn('success', 0, $result);
    }

    /**
     * 商品分类添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]          $data     [数据]
     * @param   [int]            $goods_id [商品id]
     * @return  [array]                    [boolean | msg]
     */
    public static function GoodsCategoryInsert($data, $goods_id)
    {
        Db::name('GoodsCategoryJoin')->where(['goods_id'=>$goods_id])->delete();
        if(!empty($data))
        {
            foreach($data as $category_id)
            {
                $temp_category = [
                    'goods_id'      => $goods_id,
                    'category_id'   => $category_id,
                    'add_time'      => time(),
                ];
                if(Db::name('GoodsCategoryJoin')->insertGetId($temp_category) <= 0)
                {
                    return DataReturn('商品分类添加失败', -1);
                }
            }
        }
        return DataReturn('添加成功', 0);
    }

    /**
     * 商品手机详情添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]          $data     [数据]
     * @param   [int]            $goods_id [商品id]
     * @return  [array]                    [boolean | msg]
     */
    public static function GoodsContentAppInsert($data, $goods_id)
    {
        Db::name('GoodsContentApp')->where(['goods_id'=>$goods_id])->delete();
        if(!empty($data))
        {
            foreach(array_values($data) as $k=>$v)
            {
                $temp_content = [
                    'goods_id'  => $goods_id,
                    'images'    => empty($v['images']) ? '' : $v['images'],
                    'content'   => $v['text'],
                    'sort'      => $k,
                    'add_time'  => time(),
                ];
                if(Db::name('GoodsContentApp')->insertGetId($temp_content) <= 0)
                {
                    return DataReturn('手机详情添加失败', -1);
                }
            }
        }
        return DataReturn('添加成功', 0);
    }

    /**
     * 商品相册添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]          $data     [数据]
     * @param   [int]            $goods_id [商品id]
     * @return  [array]                    [boolean | msg]
     */
    public static function GoodsPhotoInsert($data, $goods_id)
    {
        Db::name('GoodsPhoto')->where(['goods_id'=>$goods_id])->delete();
        if(!empty($data))
        {
            foreach($data as $k=>$v)
            {
                $temp_photo = [
                    'goods_id'  => $goods_id,
                    'images'    => $v,
                    'is_show'   => 1,
                    'sort'      => $k,
                    'add_time'  => time(),
                ];
                if(Db::name('GoodsPhoto')->insertGetId($temp_photo) <= 0)
                {
                    return DataReturn('相册添加失败', -1);
                }
            }
        }
        return DataReturn('添加成功', 0);
    }

    /**
     * 商品规格添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]          $data     [数据]
     * @param   [int]            $goods_id [商品id]
     * @return  [array]                    [boolean | msg]
     */
    public static function GoodsSpecificationsInsert($data, $goods_id)
    {
        // 删除原来的数据
        Db::name('GoodsSpecType')->where(['goods_id'=>$goods_id])->delete();
        Db::name('GoodsSpecValue')->where(['goods_id'=>$goods_id])->delete();
        Db::name('GoodsSpecBase')->where(['goods_id'=>$goods_id])->delete();

        // 类型
        if(!empty($data['title']))
        {
            foreach($data['title'] as &$v)
            {
                $spec = [];
                foreach($v['value'] as $vs)
                {
                    $spec[] = [
                        'name'      => $vs,
                        'images'    => isset($data['images'][$vs]) ? ResourcesService::AttachmentPathHandle($data['images'][$vs]) : '',
                    ];
                }
                $v['goods_id']  = $goods_id;
                $v['value']     = json_encode($spec, JSON_UNESCAPED_UNICODE);
                $v['add_time']  = time();
            }
            if(Db::name('GoodsSpecType')->insertAll($data['title']) < count($data['title']))
            {
                return DataReturn('规格类型添加失败', -1);
            }
        }

        // 基础/规格值
        if(!empty($data['data']))
        {
            // 基础字段
            $count = count($data['data'][0]);
            $temp_key = ['price', 'weight', 'coding', 'barcode', 'original_price', 'extends'];
            $key_count = count($temp_key);

            // 等于key总数则只有一列基础规格
            if($count == $key_count)
            {
                $temp_data = [
                    'goods_id' => $goods_id,
                    'add_time' => time(),
                ];
                for($i=0; $i<$count; $i++)
                {
                    $temp_data[$temp_key[$i]] = $data['data'][0][$i];
                }

                // 获取仓库规格库存
                $temp_data['inventory'] = WarehouseGoodsService::WarehouseGoodsSpecInventory($goods_id);

                // 规格基础添加
                if(Db::name('GoodsSpecBase')->insertGetId($temp_data) <= 0)
                {
                    return DataReturn('规格基础添加失败', -1);
                }

            // 多规格操作
            } else {
                $base_start = $count-$key_count;
                $value = [];
                $base = [];
                foreach($data['data'] as $v)
                {
                    $temp_value = [];
                    $temp_data = [
                        'goods_id' => $goods_id,
                        'add_time' => time(),
                    ];
                    for($i=0; $i<$count; $i++)
                    {
                        if($i < $base_start)
                        {
                            $temp_value[] = [
                                'goods_id'  => $goods_id,
                                'value'     => $v[$i],
                                'add_time'  => time()
                            ];
                        } else {
                            $temp_data[$temp_key[$i-$base_start]] = $v[$i];
                        }
                    }

                    // 获取仓库规格库存
                    $temp_data['inventory'] = WarehouseGoodsService::WarehouseGoodsSpecInventory($goods_id, implode('', array_column($temp_value, 'value')));
                    
                    // 规格基础添加
                    $base_id = Db::name('GoodsSpecBase')->insertGetId($temp_data);
                    if(empty($base_id))
                    {
                        return DataReturn('规格基础添加失败', -1);
                    }

                    // 规格值添加
                    foreach($temp_value as &$value)
                    {
                        $value['goods_spec_base_id'] = $base_id;
                    }
                    if(Db::name('GoodsSpecValue')->insertAll($temp_value) < count($temp_value))
                    {
                        return DataReturn('规格值添加失败', -1);
                    }
                }
            }
        }

        return DataReturn('添加成功', 0);
    }

    /**
     * 商品删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-07T00:24:14+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn('操作id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 开启事务
        Db::startTrans();

        // 删除商品
        if(Db::name('Goods')->where(['id'=>$params['ids']])->delete())
        {
            // 商品规格
            if(Db::name('GoodsSpecType')->where(['goods_id'=>$params['ids']])->delete() === false)
            {
                Db::rollback();
                return DataReturn('规格类型删除失败', -100);
            }
            if(Db::name('GoodsSpecValue')->where(['goods_id'=>$params['ids']])->delete() === false)
            {
                Db::rollback();
                return DataReturn('规格值删除失败', -100);
            }
            if(Db::name('GoodsSpecBase')->where(['goods_id'=>$params['ids']])->delete() === false)
            {
                Db::rollback();
                return DataReturn('规格基础删除失败', -100);
            }

            // 相册
            if(Db::name('GoodsPhoto')->where(['goods_id'=>$params['ids']])->delete() === false)
            {
                Db::rollback();
                return DataReturn('相册删除失败', -100);
            }

            // app内容
            if(Db::name('GoodsContentApp')->where(['goods_id'=>$params['ids']])->delete() === false)
            {
                Db::rollback();
                return DataReturn('相册删除失败', -100);
            }

            // 商品删除钩子
            $hook_name = 'plugins_service_goods_delete';
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data_ids'      => $params['ids'],
            ]);

            // 提交事务
            Db::commit();
            return DataReturn('删除成功', 0);
        }

        Db::rollback();
        return DataReturn('删除失败', -100);
    }

    /**
     * 商品状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => '未指定操作字段',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(Db::name('Goods')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 获取商品编辑规格
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-14
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsEditSpecifications($goods_id)
    {
        $where = ['goods_id'=>$goods_id];

        // 获取规格类型
        $type = Db::name('GoodsSpecType')->where($where)->order('id asc')->field('id,name,value')->select();
        $value = [];
        if(!empty($type))
        {
            // 数据处理
            foreach($type as &$temp_type)
            {
                $temp_type_value = json_decode($temp_type['value'], true);
                foreach($temp_type_value as &$vs)
                {
                    $vs['images_old'] = $vs['images'];
                    $vs['images'] = ResourcesService::AttachmentPathViewHandle($vs['images']);
                }
                $temp_type['value'] = $temp_type_value;
            }


            // 获取规格值
            $temp_value = Db::name('GoodsSpecValue')->where($where)->field('goods_spec_base_id,value')->order('id asc')->select();
            if(!empty($temp_value))
            {
                foreach($temp_value as $value_v)
                {
                    $key = '';
                    foreach($type as $type_v)
                    {
                        foreach($type_v['value'] as $type_vs)
                        {
                            if($type_vs['name'] == $value_v['value'])
                            {
                                $key = $type_v['id'];
                                break;
                            }
                        }
                    }
                    $value[$value_v['goods_spec_base_id']][] = [
                        'data_type' => 'spec',
                        'data'  => [
                            'key'       => $key,
                            'value'     => $value_v['value'],
                        ],
                    ];
                }
            }

            if(!empty($value))
            {
                foreach($value as $k=>&$v)
                {
                    $base = Db::name('GoodsSpecBase')->find($k);
                    $base['weight'] = PriceBeautify($base['weight']);
                    $v[] = [
                        'data_type' => 'base',
                        'data'      => $base,
                    ];
                }
            }
        } else {
            $base = Db::name('GoodsSpecBase')->where($where)->find();
            $base['weight'] = PriceBeautify($base['weight']);
            $value[][] = [
                'data_type' => 'base',
                'data'      => $base,
            ];
        }

        return [
            'type'      => $type,
            'value'     => array_values($value),
        ];
    }

    /**
     * 获取商品编辑参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-31
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsEditParameters($goods_id)
    {
        return Db::name('GoodsParams')->where(['goods_id'=>$goods_id])->order('id asc')->select();
    }

    /**
     * 商品规格信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-14
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsSpecDetail($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'spec',
                'is_checked'        => 1,
                'error_msg'         => '请选择规格',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $goods_id = intval($params['id']);
        $where = [
            'goods_id'  => intval($params['id']),
        ];

        // 有规格值
        if(!empty($params['spec']))
        {
            $value = [];
            // 规格不为数组则为json字符串
            if(!is_array($params['spec']))
            {
                $params['spec'] = json_decode(htmlspecialchars_decode($params['spec']), true);
            }
            foreach($params['spec'] as $v)
            {
                $value[] = $v['value'];
            }
            $where['value'] = $value;

            // 获取规格值基础值id
            $ids = Db::name('GoodsSpecValue')->where($where)->column('goods_spec_base_id');
            if(!empty($ids))
            {
                // 根据基础值id获取规格值列表
                $temp_data = Db::name('GoodsSpecValue')->where(['goods_spec_base_id'=>$ids])->field('goods_spec_base_id,value')->order('id asc')->select();
                if(!empty($temp_data))
                {
                    // 根据基础值id分组
                    $data = [];
                    foreach($temp_data as $v)
                    {
                        $data[$v['goods_spec_base_id']][] = $v;
                    }

                    // 从条件中匹配对应的规格值得到最终的基础值id
                    $base_id = 0;
                    $spec_str = implode('', array_column($params['spec'], 'value'));
                    foreach($data as $value_v)
                    {
                        $temp_str = implode('', array_column($value_v, 'value'));
                        if($temp_str == $spec_str)
                        {
                            $base_id = $value_v[0]['goods_spec_base_id'];
                            break;
                        }
                    }
                    
                    // 获取基础值数据
                    if(!empty($base_id))
                    {
                        $base = Db::name('GoodsSpecBase')->find($base_id);
                    }
                }
            }
        } else {
            $base = Db::name('GoodsSpecBase')->where($where)->find();
        }

        // 是否有规格
        if(!empty($base))
        {
            // 单位 .00 处理
            $base['weight'] = PriceBeautify($base['weight']);

            // 处理好的数据
            // 扩展元素标记与html内容数据
            // extends_element下包含多个元素 ['element'=>'', 'content'=>'']
            $data = [
                'spec_base'         => $base,
                'extends_element'   => [],
            ];

            // 商品获取规格钩子
            $hook_name = 'plugins_service_goods_spec_base';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data'          => &$data,
                'goods_id'      => $goods_id
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 返回成功
            return DataReturn('操作成功', 0, $data);
        }

        return DataReturn('没有相关规格', -100);
    }

    /**
     * 商品规格类型
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-14
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsSpecType($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'spec',
                'error_msg'         => '请选择规格',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 条件
        $goods_id = intval($params['id']);
        $where = [
            'goods_id'  => intval($params['id']),
        ];

        // 规格不为数组则为json字符串
        $value = [];
        if(!is_array($params['spec']))
        {
            $params['spec'] = json_decode(htmlspecialchars_decode($params['spec']), true);
        }
        foreach($params['spec'] as $v)
        {
            $value[] = $v['value'];
        }
        $where['value'] = $value;

        // 获取规格值基础值id
        $ids = Db::name('GoodsSpecValue')->where($where)->column('goods_spec_base_id');
        if(!empty($ids))
        {
            // 根据基础值id获取规格值列表
            $temp_data = Db::name('GoodsSpecValue')->where(['goods_spec_base_id'=>$ids])->field('goods_spec_base_id,value')->order('id asc')->select();
            if(!empty($temp_data))
            {
                // 根据基础值id分组
                $group = [];
                foreach($temp_data as $v)
                {
                    $group[$v['goods_spec_base_id']][] = $v;
                }

                // 获取当前操作元素索引
                $index = count($params['spec'])-1;
                $spec_str = implode('', array_column($params['spec'], 'value'));
                $spec_type = [];
                foreach($group as $v)
                {
                    $temp_str = implode('', array_column($v, 'value'));
                    if(isset($v[$index+1]) && stripos($temp_str, $spec_str) !== false)
                    {
                        // 判断是否还有库存
                        $inventory = Db::name('GoodsSpecBase')->where(['id'=>$v[$index+1]['goods_spec_base_id']])->value('inventory');
                        if($inventory > 0)
                        {
                            $spec_type[$v[$index+1]['value']] = $v[$index+1]['value'];
                        }
                    }
                }

                // 处理好的数据
                // 扩展元素标记与html内容数据
                // extends_element下包含多个元素 ['element'=>'', 'content'=>'']
                $data = [
                    'spec_type'         => array_values($spec_type),
                    'extends_element'   => [],
                ];

                // 商品获取规格类型钩子
                $hook_name = 'plugins_service_goods_spec_type';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => $params,
                    'data'          => &$data,
                    'goods_id'      => $goods_id
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }

                return DataReturn('操作成功', 0, $data);
            }
        }
        return DataReturn('没有相关规格类型', -100);
    }

    /**
     * 获取商品分类节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCategoryNodeSon($params = [])
    {
        // id
        $id = isset($params['id']) ? intval($params['id']) : 0;

        // 获取数据
        $field = 'id,pid,icon,name,sort,is_enable,bg_color,big_images,vice_name,describe,is_home_recommended,seo_title,seo_keywords,seo_desc';
        $data = Db::name('GoodsCategory')->field($field)->where(['pid'=>$id])->order('sort asc')->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['is_son']            =   (Db::name('GoodsCategory')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['ajax_url']          =   MyUrl('admin/goodscategory/getnodeson', array('id'=>$v['id']));
                $v['delete_url']        =   MyUrl('admin/goodscategory/delete');
                $v['icon_url']          =   ResourcesService::AttachmentPathViewHandle($v['icon']);
                $v['big_images_url']    =   ResourcesService::AttachmentPathViewHandle($v['big_images']);
                $v['json']              =   json_encode($v);
            }
            return DataReturn('操作成功', 0, $data);
        }
        return DataReturn('没有相关数据', -100);
    }

    /**
     * 商品分类保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T01:04:03+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCategorySave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,16',
                'error_msg'         => '名称格式 2~16 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'vice_name',
                'checked_data'      => '60',
                'is_checked'        => 1,
                'error_msg'         => '副名称格式 最多30个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'describe',
                'checked_data'      => '200',
                'is_checked'        => 1,
                'error_msg'         => '描述格式 最多200个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_title',
                'checked_data'      => '100',
                'is_checked'        => 1,
                'error_msg'         => 'SEO标题格式 最多100个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_keywords',
                'checked_data'      => '130',
                'is_checked'        => 1,
                'error_msg'         => 'SEO关键字格式 最多130个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => 'SEO描述格式 最多230个字符',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 其它附件
        $data_fields = ['icon', 'big_images'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }

        // 数据
        $data = [
            'name'                  => $params['name'],
            'pid'                   => isset($params['pid']) ? intval($params['pid']) : 0,
            'vice_name'             => isset($params['vice_name']) ? $params['vice_name'] : '',
            'describe'              => isset($params['describe']) ? $params['describe'] : '',
            'bg_color'              => isset($params['bg_color']) ? $params['bg_color'] : '',
            'is_home_recommended'   => isset($params['is_home_recommended']) ? intval($params['is_home_recommended']) : 0,
            'sort'                  => isset($params['sort']) ? intval($params['sort']) : 0,
            'is_enable'             => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            'icon'                  => $attachment['data']['icon'],
            'big_images'            => $attachment['data']['big_images'],
            'seo_title'             => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'          => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'              => empty($params['seo_desc']) ? '' : $params['seo_desc'],
        ];

        // 父级id宇当前id不能相同
        if(!empty($params['id']) && $params['id'] == $data['pid'])
        {
            return DataReturn('父级不能与当前相同', -10);
        }

        // 添加/编辑
        $msg = '操作失败';
        $code = -100;
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('GoodsCategory')->insertGetId($data) > 0)
            {
                $code = 0;
                $msg = '添加成功';
            } else {
                $msg = '添加失败';
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('GoodsCategory')->where(['id'=>intval($params['id'])])->update($data))
            {
                $code = 0;
                $msg = '编辑成功';
            } else {
                $msg = '编辑失败';
            }
        }

        // 状态
        if($code == 0)
        {
            // 删除大分类缓存
            cache(config('shopxo.cache_goods_category_key'), null);
        }
        return DataReturn($msg, $code);
    }

    /**
     * 商品分类删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-17T02:40:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsCategoryDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '删除数据id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'admin',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取分类下所有分类id
        $ids = self::GoodsCategoryItemsIds([$params['id']]);
        $ids[] = $params['id'];

        // 开始删除
        if(Db::name('GoodsCategory')->where(['id'=>$ids])->delete())
        {
            // 删除大分类缓存
            cache(config('shopxo.cache_goods_category_key'), null);

            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -100);
    }

    /**
     * 根据商品id获取分类名称
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsCategoryNames($goods_id)
    {
        $data = Db::name('GoodsCategory')->alias('gc')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'gc.id=gci.category_id')->where(['gci.goods_id'=>$goods_id])->column('gc.name');
        return DataReturn('获取成功', 0, $data);
    }

    /**
     * 商品规格扩展数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-07-21T16:08:34+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsSpecificationsExtends($params = [])
    {
        // 数据
        $data = [];

        // 规格扩展数据钩子
        $hook_name = 'plugins_service_goods_spec_extends_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return DataReturn('获取成功', 0, $data);
    }

    /**
     * 商品类型校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-03
     * @desc    description
     * @param   [int]          $goods_id  [商品 id]
     * @param   [int]          $site_type [商品类型]
     */
    public static function IsGoodsSiteTypeConsistent($goods_id, $site_type = null)
    {
        // 是否已指定商品类型
        if($site_type === null)
        {
            $site_type = Db::name('Goods')->where(['id'=>$goods_id])->value('site_type');
        }
        
        // 商品类型与当前系统的类型是否一致包含其中
        if(IsGoodsSiteTypeConsistent($site_type) == 1)
        {
            return DataReturn('success', 0, $site_type);
        }

        // 是否展示型商品
        if($site_type == 1)
        {
            return DataReturn('仅展示', -1, $site_type);
        }

        // 仅可单独购买
        return DataReturn('仅单独购买', -1, $site_type);
    }

    /**
     * 商品销售默认类型
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-03
     * @desc    description
     * @param   [int]          $goods_id  [商品 id]
     * @param   [int]          $site_type [商品类型]
     */
    public static function GoodsSalesModelType($goods_id, $site_type = null)
    {
        // 是否已指定商品类型
        if($site_type === null)
        {
            $site_type = Db::name('Goods')->where(['id'=>$goods_id])->value('site_type');
        }

        // 匹配商品销售模式
        return DataReturn('success', 0, GoodsSalesModelType($site_type));
    }

    /**
     * 商品二维码生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-13
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @param   [int]          $add_time [商品创建时间]
     */
    public static function GoodsQrcode($goods_id, $add_time)
    {
        // 时间格式、是否已是时间格式
        if(strstr($add_time, '-') != false)
        {
            $add_time = strtotime($add_time);
        }

        // 自定义路径和名称
        $path = 'static'.DS.'upload'.DS.'images'.DS.'goods_qrcode'.DS.APPLICATION_CLIENT_TYPE.DS.date('Y', $add_time).DS.date('m', $add_time).DS.date('d', $add_time).DS;
        $filename = $goods_id.'.png';

        // 二维码处理参数
        $params = [
            'path'      => DS.$path,
            'filename'  => $filename,
            'content'   => MyUrl('index/goods/index', ['id'=>$goods_id], true, true),
        ];

        // 创建二维码
        return (new \base\Qrcode())->Create($params);
    }
}
?>