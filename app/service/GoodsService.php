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
use app\service\SystemService;
use app\service\SystemBaseService;
use app\service\ResourcesService;
use app\service\AttachmentService;
use app\service\UserService;
use app\service\BrandService;
use app\service\RegionService;
use app\service\WarehouseGoodsService;
use app\service\GoodsCategoryService;
use app\service\GoodsSpecService;
use app\service\GoodsParamsService;
use app\service\GoodsCommentsService;

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
     * 商品规格默认名称
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-09
     * @desc    description
     */
    public static function GoodsSpecDefaultName()
    {
        return MyLang('common_service.goods.base_goods_spec_default_name');
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
        // 缓存
        $key = SystemService::CacheKey('shopxo.cache_goods_floor_list_key');
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug') || MyC('common_data_is_use_cache') != 1)
        {
            // 商品大分类
            $data = GoodsCategoryService::GoodsCategoryList(['where'=>[
                ['pid', '=', 0],
                ['is_home_recommended', '=', 1],
                ['is_enable', '=', 1],
            ]]);
            if(!empty($data))
            {
                // 楼层左侧商品分类从配置中读取
                $floor_left_top_category = MyC('home_index_floor_left_top_category');
                if(!empty($floor_left_top_category))
                {
                    $floor_left_top_category = json_decode($floor_left_top_category, true);
                }

                // 楼层关键字从配置中读取
                $floor_keywords = MyC('home_index_floor_top_right_keywords');
                if(!empty($floor_keywords))
                {
                    $floor_keywords = json_decode($floor_keywords, true);
                }

                // 数据模式
                // 0 自动模式
                // 1 手动模式
                // 2 拖拽模式
                $floor_data_type = MyC('home_index_floor_data_type', 0, true);

                // 数据处理
                switch($floor_data_type)
                {
                    // 自动模式
                    case 0 :
                        // 商品数量
                        $goods_count = MyC('home_index_floor_goods_max_count', 8, true);
                        // 排序配置
                        $floor_order_by_type_list = MyConst('common_goods_order_by_type_list');
                        $floor_order_by_rule_list = MyConst('common_data_order_by_rule_list');
                        $floor_order_by_type = MyC('home_index_floor_goods_order_by_type', 0, true);
                        $floor_order_by_rule = MyC('home_index_floor_goods_order_by_rule', 0, true);
                        // 排序字段名称
                        $order_by_field = array_key_exists($floor_order_by_type, $floor_order_by_type_list) ? $floor_order_by_type_list[$floor_order_by_type]['value'] : $floor_order_by_type_list[0]['value'];
                        // 排序规则
                        $order_by_rule = array_key_exists($floor_order_by_rule, $floor_order_by_rule_list) ? $floor_order_by_rule_list[$floor_order_by_rule]['value'] : $floor_order_by_rule_list[0]['value'];
                        // 排序
                        $order_by = implode(' '.$order_by_rule.', ', explode(',', $order_by_field)).' '.$order_by_rule;
                        break;

                    // 手动模式
                    case 1 :
                        $manual_mode = MyC('home_index_floor_manual_mode_goods');
                        if(!empty($manual_mode))
                        {
                            $floor_manual_mode_goods = json_decode($manual_mode, true);
                        }
                        break;
                }

                // 首页获取数据信息钩子
                $hook_name = 'plugins_service_home_floor_data_begin';
                MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => $params,
                    'data'          => &$data,
                ]);

                // 根据分类获取楼层商品
                foreach($data as &$v)
                {
                    // 数据模式
                    switch($floor_data_type)
                    {
                        // 自动模式
                        case 0 :
                            if(isset($goods_count) && isset($order_by))
                            {
                                // 获取分类ids
                                $category_ids = GoodsCategoryService::GoodsCategoryItemsIds([$v['id']], 1);

                                // 获取商品id
                                $goods_params = [
                                    'where'         => [
                                        ['gci.category_id', 'in', $category_ids],
                                        ['g.is_shelves', '=', 1],
                                        ['g.is_delete_time', '=', 0],
                                    ],
                                    'order_by'      => $order_by,
                                    'field'         => 'g.id',
                                    'n'             => $goods_count,
                                    'is_data_handle'=> 0,
                                ];
                                $res = self::CategoryGoodsList($goods_params);
                                $v['goods_ids'] = empty($res) ? [] : array_column($res, 'id');
                            }
                            break;

                        // 手动模式
                        case 1 :
                            if(!empty($floor_manual_mode_goods) && is_array($floor_manual_mode_goods) && array_key_exists($v['id'], $floor_manual_mode_goods))
                            {
                                $v['goods_ids'] = $floor_manual_mode_goods[$v['id']];
                            }
                            break;
                    }

                    // 商品数据、后面实时读取这里赋空值
                    $v['goods'] = [];

                    // 楼层左侧分类
                    if(!empty($floor_left_top_category) && !empty($floor_left_top_category[$v['id']]))
                    {
                        $v['items'] = GoodsCategoryService::GoodsCategoryList(['where'=>[['id', 'in', explode(',', $floor_left_top_category[$v['id']])]], 'm'=>0, 'n'=>0]);
                    } else {
                        $v['items'] = [];
                    }

                    // 楼层关键字
                    $v['config_keywords'] = (empty($floor_keywords) || empty($floor_keywords[$v['id']])) ? [] : explode(',', $floor_keywords[$v['id']]);
                }
            } else {
                $data = [];
            }

            // 存储缓存
            MyCache($key, $data, 180);
        }

        // 商品读取、商品信息需要实时读取
        if(!empty($data) && is_array($data))
        {
            // 商品id一次性读取商品
            $goods_ids = [];
            foreach($data as $cg)
            {
                if(!empty($cg['goods_ids']) && is_array($cg['goods_ids']))
                {
                    $goods_ids = array_merge($goods_ids, $cg['goods_ids']);
                }
            }
            // 读取商品
            $goods_list = [];
            if(!empty($goods_ids))
            {
                $res = self::GoodsList([
                    'where'    => [
                        ['id', 'in', array_unique($goods_ids)],
                        ['is_shelves', '=', 1],
                    ],
                    'm'        => 0,
                    'n'        => 0,
                    'field'    => '*',
                    'is_spec'  => 1,
                    'is_cart'  => 1,
                ]);
                $goods_list = empty($res['data']) ? [] : array_column($res['data'], null, 'id');
            }

            // 根据分类获取楼层商品
            if(!empty($goods_list))
            {
                foreach($data as &$cv)
                {
                    if(!empty($cv['goods_ids']) && is_array($cv['goods_ids']))
                    {
                        $temp = [];
                        foreach($cv['goods_ids'] as $gid)
                        {
                            if(array_key_exists($gid, $goods_list))
                            {
                                $temp[] = $goods_list[$gid];
                            }
                        }
                        $cv['goods'] = $temp;
                    }
                }
            }
        }
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
        // 商品与分类联表总数读取前钩子
        $hook_name = 'plugins_service_category_goods_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'where'         => &$where,
        ]);

        // 获取总数
        return (int) Db::name('Goods')->alias('g')->join('goods_category_join gci', 'g.id=gci.goods_id')->where($where)->count('DISTINCT g.id');
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
        $order_by = empty($params['order_by']) ? 'g.sort_level desc, g.id desc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 商品列表读取前钩子
        $hook_name = 'plugins_service_category_goods_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
            'order_by'      => &$order_by,
            'm'             => &$m,
            'n'             => &$n,
        ]);

        // 条件处理
        $where_g = [];
        $where_gci = [];
        foreach($where as $v)
        {
            if(is_array($v) && count($v) == 3)
            {
                if(substr($v[0], 0, 4) == 'gci.')
                {
                    $where_gci[] = $v;
                } else {
                    $where_g[] = $v;
                }
            }
        }

        // 只有商品条件、排序、字段
        if(empty($where_gci) && stripos($field, 'gci.') === false && stripos($order_by, 'gci.') === false)
        {
            $data = Db::name('Goods')->alias('g')->where($where_g)->field($field)->order($order_by)->limit($m, $n)->select()->toArray();
        // 排序存在商品分类、商品
        } else if((stripos($order_by, 'gci.') !== false && stripos($order_by, 'g.') !== false) || (stripos($field, 'gci.') !== false && stripos($field, 'g.') !== false))
        {
            $data = Db::name('Goods')->alias('g')->join('goods_category_join gci', 'g.id=gci.goods_id')->field($field)->where($where)->group('g.id')->order($order_by)->limit($m, $n)->select()->toArray();
        // 子查询
        } else {
            $data = Db::name('Goods')->alias('g')->where($where_g)->where('g.id', 'IN', function($query) use($where_gci) {
                $query->name('GoodsCategoryJoin')->alias('gci')->where($where_gci)->field('gci.goods_id');
            })->order($order_by)->limit($m, $n)->select()->toArray();
        }
        
        // 数据处理
        if(!isset($params['is_data_handle']) || $params['is_data_handle'] == 1)
        {
            $data = self::GoodsDataHandle($data, $params);
        }

        return $data;
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
            // 商品列表钩子-前面
            $hook_name = 'plugins_service_goods_list_handle_begin';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);

            // 其它额外处理
            $is_photo = !isset($params['is_photo']) || (isset($params['is_photo']) && $params['is_photo'] == 1);
            $is_spec = isset($params['is_spec']) && $params['is_spec'] == 1;
            $is_content_app = isset($params['is_content_app']) && $params['is_content_app'] == 1;
            $is_category = isset($params['is_category']) && $params['is_category'] == 1;
            $is_params = isset($params['is_params']) && $params['is_params'] == 1;
            $is_cart = isset($params['is_cart']) && $params['is_cart'] == 1;
            $is_favor = isset($params['is_favor']) && $params['is_favor'] == 1;
            $data_key_field = empty($params['data_key_field']) ? 'id' : $params['data_key_field'];
            $goods_ids = array_filter(array_column($data, $data_key_field));
            $currency_symbol = ResourcesService::CurrencyDataSymbol();
            $common_goods_sales_price_status = MyC('common_goods_sales_price_status', 0, true);
            $common_goods_original_price_status = MyC('common_goods_original_price_status', 0, true);
            $common_goods_sales_price_unit_status = MyC('common_goods_sales_price_unit_status', 0, true);
            $common_goods_original_price_unit_status = MyC('common_goods_original_price_unit_status', 0, true);
            $common_goods_sales_number_status = MyC('common_goods_sales_number_status', 0, true);
            $common_goods_inventory_status = MyC('common_goods_inventory_status', 0, true);

            // 字段列表
            $keys = ArrayKeys($data);

            // 品牌名称
            if(in_array('brand_id', $keys))
            {
                $brand_list = BrandService::BrandName(array_column($data, 'brand_id'));
            }

            // 产地名称
            if(in_array('place_origin', $keys))
            {
                $place_origin_list = RegionService::RegionName(array_column($data, 'place_origin'));
            }

            // 相册
            $photo = $is_photo ? self::GoodsPhotoData($goods_ids) : [];

            // 商品分类
            $category_group = $is_category ? self::GoodsListCategoryGroupList($goods_ids, $params) : [];

            // 规格
            $spec_group = $is_spec ? self::GoodsSpecificationsData($goods_ids, $params) : [];

            // 参数
            $params_group = $is_params ? self::GoodsParametersData($goods_ids, $params) : [];

            // app数据
            $app_group = $is_content_app ? self::GoodsAppData($goods_ids, $params) : [];

            // 获取商品购物车数量
            $user_cart = $is_cart ? self::UserCartGoodsCountData($goods_ids, $params) : [];

            // 获取商品购物车数量
            $user_favor = $is_favor ? self::UserFavorGoodsCountData($goods_ids, $params) : [];

            // 开始处理数据
            foreach($data as $k=>&$v)
            {
                // 增加索引
                $v['data_index'] = $k+1;

                // 数据主键id
                $data_id = isset($v[$data_key_field]) ? $v[$data_key_field] : 0;

                // 当前库存单位
                $inventory_unit = empty($v['inventory_unit']) ? '' : ' / '.$v['inventory_unit'];
                // 原价基础字段数据
                // 原价标题名称
                $v['show_field_original_price_text'] = MyLang('goods_original_price_title');
                // 售价符号
                $v['show_original_price_symbol'] = $currency_symbol;
                // 售价符号
                $v['show_original_price_unit'] = $common_goods_original_price_unit_status == 1 ? $inventory_unit : '';
                // 是否展示原价(否0, 是1)
                $v['show_field_original_price_status'] = $common_goods_original_price_status;

                // 售价基础字段数据
                // 售价标题名称
                $v['show_field_price_text'] = MyLang('goods_sales_price_title');
                // 售价符号
                $v['show_price_symbol'] = $currency_symbol;
                // 售价符号
                $v['show_price_unit'] = $common_goods_sales_price_unit_status == 1 ? $inventory_unit : '';
                // 是否展示售价(否0, 是1)
                $v['show_field_price_status'] = $common_goods_sales_price_status;

                // 是否展示销量和库存
                $v['show_sales_number_status'] = $common_goods_sales_number_status;
                $v['show_inventory_status'] = $common_goods_inventory_status;

                // 公共插件数据
                // 商品详情面板提示数据、一维数组
                $v['plugins_view_panel_data'] = [];

                // 商品详情icon数据、二维数组
                // name     必填(建议不超过6个字符)
                // bg_color 默认(#666)
                // br_color 默认(#666)
                // color    默认($fff)
                // url      默认空(手机端请自行调整url地址)
                // [
                //      'name'      => 'icon名称',
                //      'bg_color'  => '#666',
                //      'br_color'  => '#666',
                //      'color'     => '#fff',
                //      'url'       => 'url地址'
                // ]
                $v['plugins_view_icon_data'] = [];

                // 商品价格容器
                $v['price_container'] = [
                    'price'                 => isset($v['price']) ? $v['price'] : 0.00,
                    'min_price'             => isset($v['min_price']) ? $v['min_price'] : 0.00,
                    'max_price'             => isset($v['max_price']) ? $v['max_price'] : 0.00,
                    'original_price'        => isset($v['original_price']) ? $v['original_price'] : 0.00,
                    'min_original_price'    => isset($v['min_original_price']) ? $v['min_original_price'] : 0.00,
                    'max_original_price'    => isset($v['max_original_price']) ? $v['max_original_price'] : 0.00,
                ];

                // 商品处理前钩子
                $hook_name = 'plugins_service_goods_handle_begin';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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

                // 商品url地址
                if(!empty($data_id))
                {
                    $v['goods_url'] = self::GoodsUrlCreate($data_id);
                }

                // 获取相册
                if($is_photo && !empty($data_id))
                {
                    $v['photo'] = (empty($photo) || empty($photo[$data_id])) ? [] : $photo[$data_id];
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

                // 分享图片
                if(isset($v['share_images']))
                {
                    $v['share_images_old'] = $v['share_images'];
                    $v['share_images'] = ResourcesService::AttachmentPathViewHandle($v['share_images']);
                }

                // PC内容处理
                if(isset($v['content_web']))
                {
                    $v['content_web'] = ResourcesService::ContentStaticReplace($v['content_web'], 'get');
                    // 手机端富文本处理
                    if(APPLICATION == 'app')
                    {
                        $v['content_web'] = ResourcesService::ApMiniRichTextContentHandle($v['content_web']);
                    }
                }

                // 虚拟商品展示数据
                if(isset($v['fictitious_goods_value']))
                {
                    // 非后台模块移除该字段、避免数据泄露
                    if(RequestModule() != 'admin')
                    {
                        unset($v['fictitious_goods_value']);
                    } else {
                        $v['fictitious_goods_value'] = ResourcesService::ContentStaticReplace($v['fictitious_goods_value'], 'get');
                    }
                }

                // 产地
                if(isset($v['place_origin']))
                {
                    $v['place_origin_name'] = (!empty($place_origin_list) && is_array($place_origin_list) && array_key_exists($v['place_origin'], $place_origin_list)) ? $place_origin_list[$v['place_origin']] : '';
                }

                // 品牌
                if(isset($v['brand_id']))
                {
                    $v['brand_name'] = (!empty($brand_list) && is_array($brand_list) && array_key_exists($v['brand_id'], $brand_list)) ? $brand_list[$v['brand_id']] : '';
                }

                // 时间
                if(!empty($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(!empty($v['upd_time']))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }

                // 是否需要分类名称
                if($is_category && !empty($data_id))
                {
                    if(array_key_exists($data_id, $category_group))
                    {
                        $temp = $category_group[$data_id];
                        $v['category_ids'] = $temp['category_ids'];
                        $v['category_text'] = empty($temp['category_names']) ? '' : implode('，', $temp['category_names']);
                    } else {
                        $v['category_ids'] = [];
                        $v['category_text'] = '';
                    }
                }

                // 规格基础
                if(isset($v['spec_base']))
                {
                    $v['spec_base'] = empty($v['spec_base']) ? '' : json_decode($v['spec_base'], true);
                }

                // 获取规格
                if($is_spec && !empty($data_id))
                {
                    $v['specifications'] = (!empty($spec_group) && array_key_exists($data_id, $spec_group)) ? $spec_group[$data_id] : [];
                }

                // 获取商品参数
                if($is_params && !empty($data_id))
                {
                    $v['parameters'] = (!empty($params_group) && array_key_exists($data_id, $params_group)) ? $params_group[$data_id] : [];
                }

                // 获取app内容
                if($is_content_app && !empty($data_id))
                {
                    $v['content_app'] = (!empty($app_group) && array_key_exists($data_id, $app_group)) ? $app_group[$data_id] : [];
                }

                // 用户购物车总数
                if($is_cart && !empty($data_id))
                {
                    $v['user_cart_count'] = (!empty($user_cart) && array_key_exists($data_id, $user_cart)) ? $user_cart[$data_id] : 0;
                }

                // 用户收藏
                if($is_favor && !empty($user_favor))
                {
                    $v['user_is_favor'] = (!empty($user_favor) && in_array($data_id, $user_favor)) ? 1 : 0;
                }

                // 商品处理后钩子
                $hook_name = 'plugins_service_goods_handle_end';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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

            // 商品列表钩子-后面
            $hook_name = 'plugins_service_goods_list_handle_end';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);

            // 错误处理
            if(!empty($data) & is_array($data))
            {
                foreach($data as &$gv)
                {
                    // 数据主键id
                    $data_id = isset($v[$data_key_field]) ? $v[$data_key_field] : 0;

                    // 错误处理
                    if(!isset($gv['is_error']) || $gv['is_error'] == 0)
                    {
                        $gv['is_error'] = 0;
                        $gv['error_msg'] = '';
                    }
                    if($gv['is_error'] == 0 && array_key_exists('is_delete_time', $gv) && $gv['is_delete_time'] != 0)
                    {
                        $gv['is_error'] = 1;
                        $gv['error_msg'] = MyLang('goods_already_nullify_title');
                    }
                    // 是否上架
                    if($gv['is_error'] == 0 && array_key_exists('is_shelves', $gv) && $gv['is_shelves'] != 1)
                    {
                        $gv['is_error'] = 1;
                        $gv['error_msg'] = MyLang('goods_already_shelves_title');
                    }
                    // 是否有库存
                    if($gv['is_error'] == 0 && array_key_exists('inventory', $gv) && $gv['inventory'] <= 0)
                    {
                        $gv['is_error'] = 1;
                        $gv['error_msg'] = MyLang('goods_no_inventory_title');
                    }
                    // 没错误则判断类型是否一致
                    if($gv['is_error'] == 0 && array_key_exists('site_type', $gv) && !empty($data_id))
                    {
                        $ret = self::IsGoodsSiteTypeConsistent($data_id, $gv['site_type']);
                        if($ret['code'] != 0)
                        {
                            $gv['is_error'] = 1;
                            $gv['error_msg'] = $ret['msg'];
                        }
                    }
                }
            }
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 商品列表获取产品分类分组信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-10-13
     * @desc    description
     * @param   [array]          $goods_ids [商品id]
     * @param   [array]          $params    [输入参数]
     */
    public static function GoodsListCategoryGroupList($goods_ids, $params = [])
    {
        $result = [];
        $category_join = Db::name('GoodsCategoryJoin')->where(['goods_id'=>$goods_ids])->field('goods_id,category_id')->select()->toArray();
        if(!empty($category_join))
        {
            $category_name = Db::name('GoodsCategory')->where(['id'=>array_unique(array_column($category_join, 'category_id'))])->column('name', 'id');
            if(!empty($category_name))
            {
                foreach($category_join as $v)
                {
                    if(array_key_exists($v['category_id'], $category_name))
                    {
                        if(!array_key_exists($v['goods_id'], $result))
                        {
                            $result[$v['goods_id']] = [
                                'category_ids'  => [],
                                'category_names' => [],
                            ];
                        }
                        $result[$v['goods_id']]['category_ids'][] = $v['category_id'];
                        $result[$v['goods_id']]['category_names'][] = $category_name[$v['category_id']];
                    }
                }
            }
        }
        return $result;
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
     * @param   [int|array]          $goods_ids [商品id]
     */
    public static function GoodsPhotoData($goods_ids)
    {
        $data = Db::name('GoodsPhoto')->where(['goods_id'=>$goods_ids, 'is_show'=>1])->order('sort asc')->select()->toArray();
        if(is_array($goods_ids))
        {
            $group = [];
            if(!empty($data))
            {
                foreach($data as $v)
                {
                    if(!array_key_exists($v['goods_id'], $group))
                    {
                        $group[$v['goods_id']] = [];
                    }

                    $v['images_old'] = $v['images'];
                    $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                    $group[$v['goods_id']][] = $v;
                }
            }
            return $group;
        }
        return $data;
    }

    /**
     * 获取用户收藏商品数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-20
     * @desc    description
     * @param   [array]           $goods_ids [商品id]
     * @param   [array]           $params    [输入参数]
     */
    public static function UserFavorGoodsCountData($goods_ids, $params = [])
    {
        $result = [];
        $user = UserService::LoginUserInfo();
        if(!empty($user))
        {
            $where = [
                ['goods_id', 'in', $goods_ids],
                ['user_id', '=', $user['id']],
            ];
            $result = Db::name('GoodsFavor')->where($where)->column('goods_id');
        }
        return $result;
    }

    /**
     * 获取用户购物车商品总数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-20
     * @desc    description
     * @param   [array]           $goods_ids [商品id]
     * @param   [array]           $params    [输入参数]
     */
    public static function UserCartGoodsCountData($goods_ids, $params = [])
    {
        $result = [];
        $user = UserService::LoginUserInfo();
        if(!empty($user))
        {
            $where = [
                ['goods_id', 'in', $goods_ids],
                ['user_id', '=', $user['id']],
            ];
            $result = Db::name('Cart')->where($where)->field('SUM(stock) AS count, goods_id')->group('goods_id')->select()->toArray();
            if(!empty($result))
            {
                $result = array_column($result, 'count', 'goods_id');
            }
        }
        return $result;
    }

    /**
     * 获取商品手机详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-10
     * @desc    description
     * @param   [array]           $goods_ids [商品id]
     * @param   [array]           $params    [输入参数]
     * @return  [array]                      [app内容]
     */
    public static function GoodsAppData($goods_ids, $params = [])
    {
        $group = [];
        $data = Db::name('GoodsContentApp')->where(['goods_id'=>$goods_ids])->field('id,goods_id,images,content')->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 数据处理
                $v['images_old'] = $v['images'];
                $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                $v['content_old'] = $v['content'];
                $v['content'] = empty($v['content']) ? null : explode("\n", $v['content']);
                // 数据组合
                if(!array_key_exists($v['goods_id'], $group))
                {
                    $group[$v['goods_id']] = [];
                }
                $group[$v['goods_id']][] = $v;
            }
        }
        return $group;
    }

    /**
     * 获取商品规格
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [array]           $goods_ids [商品id]
     * @param   [array]           $params    [输入参数]
     */
    public static function GoodsSpecificationsData($goods_ids, $params = [])
    {
        // 静态数据容器，确保每一个商品只读取一次规格，避免重复读取浪费资源
        static $goods_spec_group_static_data = [];
        $temp_goods_ids = [];
        foreach($goods_ids as $gid)
        {
            if(empty($goods_spec_group_static_data) || !array_key_exists($gid, $goods_spec_group_static_data))
            {
                $temp_goods_ids[] = $gid;
            }
        }
        // 存在未读取的规格咋数据库读取
        if(!empty($temp_goods_ids))
        {
            $temp_group = [];
            $data = Db::name('GoodsSpecType')->where(['goods_id'=>$temp_goods_ids])->order('id asc')->select()->toArray();
            if(!empty($data))
            {
                // 分组
                foreach($data as $v)
                {
                    if(!array_key_exists($v['goods_id'], $temp_group))
                    {
                        $temp_group[$v['goods_id']] = ['choose'=>[]];
                    }
                    $temp_group[$v['goods_id']]['choose'][] = $v;
                }
                // 数据处理
                foreach($temp_group as $gid=>&$gv)
                {
                    if(!empty($gv['choose']))
                    {
                        // 基础处理
                        foreach($gv['choose'] as &$gvs)
                        {
                            $gvs_value = json_decode($gvs['value'], true);
                            foreach($gvs_value as &$gvss)
                            {
                                $gvss['images'] = ResourcesService::AttachmentPathViewHandle($gvss['images']);
                            }
                            $gvs['value'] = $gvs_value;
                            $gvs['add_time'] = date('Y-m-d H:i:s', $gvs['add_time']);
                        }

                        // 只有一个规格的时候直接获取规格值的库存数
                        if(count($gv['choose']) == 1)
                        {
                            foreach($gv['choose'][0]['value'] as &$temp_spec)
                            {
                                $temp_spec_params = array_merge($params, [
                                    'id'    => $gid,
                                    'spec'  => [
                                        ['type' => $gv['choose'][0]['name'], 'value' => $temp_spec['name']]
                                    ],
                                ]);
                                $temp = self::GoodsSpecDetail($temp_spec_params);
                                if($temp['code'] == 0)
                                {
                                    $temp_spec['is_only_level_one'] = 1;
                                    $temp_spec['inventory'] = $temp['data']['spec_base']['inventory'];
                                }
                            }
                        }
                    }
                    $goods_spec_group_static_data[$gid] = $gv;
                }
            }
            // 空数据记录、避免重复查询
            foreach($temp_goods_ids as $gid)
            {
                if(!array_key_exists($gid, $goods_spec_group_static_data))
                {
                    $goods_spec_group_static_data[$gid] = [];
                }
            }
        }
        // 返回当前指定的商品id对应的规格数据
        $group = [];
        if(!empty($goods_spec_group_static_data))
        {
            foreach($goods_ids as $gid)
            {
                if(!empty($goods_spec_group_static_data[$gid]))
                {
                    $group[$gid] = $goods_spec_group_static_data[$gid];
                }
            }
        }
        return $group;
    }

    /**
     * 获取商品参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-31
     * @desc    description
     * @param   [array]           $goods_ids [商品id]
     * @param   [array]           $params    [输入参数]
     */
    public static function GoodsParametersData($goods_ids, $params = [])
    {
        $data = [];
        $list = Db::name('GoodsParams')->where(['goods_id'=>$goods_ids])->order('id asc')->select()->toArray();
        if(!empty($list))
        {
            // 分组
            foreach($list as $v)
            {
                if(!array_key_exists($v['goods_id'], $data))
                {
                    $data[$v['goods_id']] = ['base'=>[], 'detail'=>[]];
                }

                // 基础
                if(in_array($v['type'], [0,2]))
                {
                    $data[$v['goods_id']]['base'][] = $v;
                }

                // 详情
                if(in_array($v['type'], [0,1]))
                {
                    $data[$v['goods_id']]['detail'][] = $v;
                }
            }
        }

        // 商品参数钩子
        $hook_name = 'plugins_service_goods_parameters_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'goods_ids'     => $goods_ids,
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
        $value = Db::name('GoodsSpecValue')->where($where)->field('goods_spec_base_id,value')->select()->toArray();
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
            return Db::name('Goods')->where(['id'=>intval($params['goods_id'])])->inc('access_count')->update();
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
        // 商品总数读取前钩子
        $hook_name = 'plugins_service_goods_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'where'         => &$where,
        ]);

        // 获取总数
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

        // 商品列表读取前钩子
        $hook_name = 'plugins_service_goods_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
            'order_by'      => &$order_by,
            'm'             => &$m,
            'n'             => &$n,
        ]);

        // 查询商品
        $data = Db::name('Goods')->field($field)->where($where)->order($order_by)->limit($m, $n)->select()->toArray();

        // 数据处理
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
                'error_msg'         => MyLang('common_service.goods.form_item_title_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'simple_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.goods.form_item_simple_desc_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'model',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.goods.form_item_model_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => MyLang('common_service.goods.form_item_category_id_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'inventory_unit',
                'checked_data'      => '1,6',
                'error_msg'         => MyLang('common_service.goods.form_item_inventory_unit_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'site_type',
                'checked_data'      => array_column(MyConst('common_site_type_list'), 'value'),
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.goods.save_site_type_error_tips'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_title',
                'checked_data'      => '100',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_title_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_keywords',
                'checked_data'      => '130',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_keywords_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'seo_desc',
                'checked_data'      => '230',
                'is_checked'        => 1,
                'error_msg'         => MyLang('form_seo_desc_message'),
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
        $attachment = ResourcesService::AttachmentParams($params, ['images', 'video', 'share_images']);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }

        // 编辑器内容
        $content_web = empty($params['content_web']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['content_web']), 'add');
        $fictitious_goods_value = empty($params['fictitious_goods_value']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['fictitious_goods_value']), 'add');

        // 封面图片、默认相册第一张
        $images = empty($attachment['data']['images']) ? (isset($photo['data'][0]) ? $photo['data'][0] : '') : $attachment['data']['images'];

        // 基础数据
        $data = [
            'title'                     => $params['title'],
            'title_color'               => empty($params['title_color']) ? '' : $params['title_color'],
            'simple_desc'               => $params['simple_desc'],
            'model'                     => empty($params['model']) ? '' : $params['model'],
            'place_origin'              => isset($params['place_origin']) ? intval($params['place_origin']) : 0,
            'inventory_unit'            => $params['inventory_unit'],
            'is_deduction_inventory'    => isset($params['is_deduction_inventory']) ? intval($params['is_deduction_inventory']) : 0,
            'is_shelves'                => isset($params['is_shelves']) ? intval($params['is_shelves']) : 0,
            'content_web'               => $content_web,
            'photo_count'               => count($photo['data']),
            'images'                    => $images,
            'brand_id'                  => isset($params['brand_id']) ? intval($params['brand_id']) : 0,
            'video'                     => $attachment['data']['video'],
            'seo_title'                 => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'              => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'                  => empty($params['seo_desc']) ? '' : $params['seo_desc'],
            'is_exist_many_spec'        => empty($specifications['data']['title']) ? 0 : 1,
            'spec_base'                 => empty($specifications_base['data']) ? '' : json_encode($specifications_base['data'], JSON_UNESCAPED_UNICODE),
            'fictitious_goods_value'    => $fictitious_goods_value,
            'site_type'                 => (isset($params['site_type']) && $params['site_type'] != '') ? $params['site_type'] : -1,
            'sort_level'                => empty($params['sort_level']) ? 0 : intval($params['sort_level']),
            'share_images'              => $attachment['data']['share_images'],
        ];

        // 是否存在赠送积分
        if(array_key_exists('give_integral', $params))
        {
            $data['give_integral'] = max(0, ($params['give_integral'] <= 100) ? intval($params['give_integral']) : 0);
        }

        // 商品保存处理钩子
        $hook_name = 'plugins_service_goods_save_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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

        // 捕获异常
        try {
            // 添加/编辑
            if(empty($params['id']))
            {
                $data['add_time'] = time();
                $goods_id = Db::name('Goods')->insertGetId($data);
                if($goods_id <= 0)
                {
                    throw new \Exception(MyLang('insert_fail'));
                }
            } else {
                $data['upd_time'] = time();
                if(Db::name('Goods')->where(['id'=>intval($params['id'])])->update($data))
                {
                    $goods_id = $params['id'];
                } else {
                    throw new \Exception(MyLang('update_fail'));
                }
            }

            // 分类
            $ret = self::GoodsCategoryInsert(explode(',', $params['category_id']), $goods_id);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 规格
            $ret = self::GoodsSpecificationsInsert($specifications['data'], $goods_id);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            } else {
                // 更新商品基础信息
                $ret = self::GoodsSaveBaseUpdate($goods_id);
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }
            }

            // 相册
            $ret = self::GoodsPhotoInsert($photo['data'], $goods_id);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 手机详情
            $ret = self::GoodsContentAppInsert($content_app['data'], $goods_id);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 商品参数
            $ret = self::GoodsParamsInsert($params, $goods_id);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 仓库规格库存同步
            $ret = WarehouseGoodsService::GoodsSpecChangeInventorySync($goods_id);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 完成
            Db::commit();
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }

        // 商品保存后钩子
        $hook_name = 'plugins_service_goods_save_end';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => $data,
            'goods_id'      => $goods_id,
        ]);

        // 返回信息
        return DataReturn(MyLang('operate_success'), 0);
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
    public static function GoodsParamsInsert($params, $goods_id)
    {
        // 删除商品参数
        Db::name('GoodsParams')->where(['goods_id'=>$goods_id])->delete();

        // 获取参数解析并添加
        $config = GoodsParamsService::GoodsParamsTemplateHandle($params);
        if($config['code'] == 0 && !empty($config['data']))
        {
            foreach($config['data'] as &$v)
            {
                $v['goods_id'] = $goods_id;
                $v['add_time'] = time();
            }
            if(Db::name('GoodsParams')->insertAll($config['data']) < count($config['data']))
            {
                return DataReturn(MyLang('common_service.goods.save_params_add_fail_tips'), -1);
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 商品保存基础信息更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T01:56:42+0800
     * @param   [int]            $goods_id [商品id]
     * @param   [array]          $params   [输入参数]
     */
    public static function GoodsSaveBaseUpdate($goods_id, $params = [])
    {
        // 商品基础数据
        $base = Db::name('GoodsSpecBase')->where(['goods_id'=>$goods_id])->select()->toArray();
        if(empty($base))
        {
            return DataReturn(MyLang('common_service.goods.save_goods_base_empty_tips'), -1);
        }
        // 汇总处理
        $data = [
            'min_price'           => min(array_column($base, 'price')),
            'max_price'           => max(array_column($base, 'price')),
            'min_original_price'  => min(array_column($base, 'original_price')),
            'max_original_price'  => max(array_column($base, 'original_price')),
            'inventory'           => array_sum(array_column($base, 'inventory')),
        ];
        // 起购数、限购数处理
        $data['buy_min_number'] = min(array_column($base, 'buy_min_number'));
        if($data['buy_min_number'] <= 0)
        {
            $data['buy_min_number'] = 1;
        }
        $buy_max_number = max(array_column($base, 'buy_max_number'));
        $data['buy_max_number'] = ($buy_max_number > 0 && min(array_column($base, 'buy_max_number')) > 0) ? $buy_max_number : 0;

        // 销售价格 - 展示价格
        $data['price'] = (!empty($data['max_price']) && $data['min_price'] != $data['max_price']) ? $data['min_price'].'-'.$data['max_price'] : $data['min_price'];

        // 原价价格 - 展示价格
        $data['original_price'] = (!empty($data['max_original_price']) && $data['min_original_price'] != $data['max_original_price']) ? $data['min_original_price'].'-'.$data['max_original_price'] : $data['min_original_price'];

        // 更新商品表
        $data['upd_time'] = time();
        if(Db::name('Goods')->where(['id'=>$goods_id])->update($data) === false)
        {
            return DataReturn(MyLang('common_service.goods.save_goods_base_update_fail_tips'), -1);
        }

        // 商品基础数据更新钩子
        $hook_name = 'plugins_service_goods_base_update';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'goods_id'      => $goods_id,
            'params'        => $params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        return DataReturn(MyLang('operate_success'), 0);
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
        // 销售价、原价、起购数、限购数、重量、体积、编码、条形码、扩展
        $base_count = 9;

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
                                $data[$ks][] = trim($vs);
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
                        return DataReturn(MyLang('common_service.goods.save_spec_column_repeat_tips').'['.implode(',', array_unique($temp_column)).']', -1);
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
                            return DataReturn(MyLang('common_service.goods.save_spec_value_repeat_tips').'['.implode(',', array_unique($repeat_rows_all)).']', -1);
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
                                    $spec_name = trim($params['specifications_name_'.$key]);
                                    $title[$spec_name] = [
                                        'name'  => $spec_name,
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
                    return DataReturn(MyLang('common_service.goods.save_spec_name_column_repeat_tips').'['.implode(',', array_unique($repeat_names_all)).']', -1);
                }
            } else {
                if(!isset($data[0][0]) || $data[0][0] < 0)
                {
                    return DataReturn(MyLang('common_service.goods.save_spec_base_price_error_tips'), -1);
                }
            }
        } else {
            return DataReturn(MyLang('common_service.goods.save_spec_empty_tips'), -1);
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
            return DataReturn(MyLang('common_service.goods.save_photo_empty_tips'), -1);
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
                    return DataReturn(MyLang('common_service.goods.save_category_add_fail_tips'), -1);
                }
            }
        }
        return DataReturn(MyLang('insert_success'), 0);
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
                    return DataReturn(MyLang('common_service.goods.save_app_content_add_fail_tips'), -1);
                }
            }
        }
        return DataReturn(MyLang('insert_success'), 0);
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
                    return DataReturn(MyLang('common_service.goods.save_photo_add_fail_tips'), -1);
                }
            }
        }
        return DataReturn(MyLang('insert_success'), 0);
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
                return DataReturn(MyLang('common_service.goods.save_spec_type_add_fail_tips'), -1);
            }
        }

        // 基础/规格值
        if(!empty($data['data']))
        {
            // 基础字段
            $count = count($data['data'][0]);
            $temp_key = ['price', 'original_price', 'buy_min_number', 'buy_max_number', 'weight', 'volume', 'coding', 'barcode', 'extends'];
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
                    return DataReturn(MyLang('common_service.goods.save_spec_base_add_fail_tips'), -1);
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
                        return DataReturn(MyLang('common_service.goods.save_spec_base_add_fail_tips'), -1);
                    }

                    // 规格值添加
                    foreach($temp_value as &$value)
                    {
                        $value['goods_spec_base_id'] = $base_id;
                    }
                    if(Db::name('GoodsSpecValue')->insertAll($temp_value) < count($temp_value))
                    {
                        return DataReturn(MyLang('common_service.goods.save_spec_value_add_fail_tips'), -1);
                    }
                }
            }
        }
        return DataReturn(MyLang('insert_success'), 0);
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
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 删除商品操作
            self::GoodsDeleteHandle($params['ids'], $params);

            // 商品删除钩子
            $hook_name = 'plugins_service_goods_delete';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'goods_ids'     => $params['ids'],
            ]);

            // 提交事务
            Db::commit();
            return DataReturn(MyLang('delete_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 商品删除操作
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-27
     * @desc    description
     * @param   [array]          $goods_ids [商品id]
     * @param   [array]          $params    [输入参数]
     */
    public static function GoodsDeleteHandle($goods_ids, $params = [])
    {
        // 是否删除图片
        $del_images_data = [];
        $is_del_images = isset($params['is_del_images']) && $params['is_del_images'] == 1;
        if($is_del_images)
        {
            // 商品主图
            $goods_images = Db::name('Goods')->where([['id', 'in', $goods_ids], ['images', '<>', '']])->column('images');
            if(!empty($goods_images))
            {
                $del_images_data = array_unique(array_merge($del_images_data, $goods_images));
            }
            // 商品分享图
            $goods_share_images = Db::name('Goods')->where([['id', 'in', $goods_ids], ['share_images', '<>', '']])->column('share_images');
            if(!empty($goods_share_images))
            {
                $del_images_data = array_unique(array_merge($del_images_data, $goods_share_images));
            }
            // 商品规格图
            $goods_spec_type = Db::name('GoodsSpecType')->where(['goods_id'=>$goods_ids])->column('value');
            if(!empty($goods_spec_type))
            {
                foreach($goods_spec_type as $v)
                {
                    $v = json_decode($v, true);
                    if(!empty($v) && is_array($v))
                    {
                        $temp = array_unique(array_filter(array_column($v, 'images')));
                        if(!empty($temp))
                        {
                            $del_images_data = array_unique(array_merge($del_images_data, $temp));
                        }
                    }
                }
            }
            // 商品相册
            $goods_photo = Db::name('GoodsPhoto')->where(['goods_id'=>$goods_ids])->column('images');
            if(!empty($goods_photo))
            {
                $del_images_data = array_unique(array_merge($del_images_data, $goods_photo));
            }
            // 商品详情图
            $goods_content = Db::name('Goods')->where([['id', 'in', $goods_ids], ['content_web', '<>', '']])->column('content_web');
            if(!empty($goods_content))
            {
                foreach($goods_content as $v)
                {
                    $temp = ResourcesService::RichTextMatchContentAttachment($v, 'goods', 'images');
                    if(!empty($temp))
                    {
                        $del_images_data = array_unique(array_merge($del_images_data, $temp));
                    }
                }
            }
            // 商品app
            $goods_app = Db::name('GoodsContentApp')->where([['goods_id', 'in', $goods_ids], ['images', '<>', '']])->column('images');
            if(!empty($goods_app))
            {
                $del_images_data = array_unique(array_merge($del_images_data, $goods_app));
            }
        }

        // 删除商品
        if(Db::name('Goods')->where(['id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_goods_fail_tips'));
        }
        // 商品规格
        if(Db::name('GoodsSpecType')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_spec_type_fail_tips'));
        }
        if(Db::name('GoodsSpecValue')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_spec_value_fail_tips'));
        }
        if(Db::name('GoodsSpecBase')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_spec_base_fail_tips'));
        }

        // 关联分类
        if(Db::name('GoodsCategoryJoin')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_goods_category_fail_tips'));
        }

        // 相册
        if(Db::name('GoodsPhoto')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_goods_photo_fail_tips'));
        }

        // app内容
        if(Db::name('GoodsContentApp')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_app_content_fail_tips'));
        }

        // 商品参数
        if(Db::name('GoodsParams')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_params_fail_tips'));
        }

        // 商品关联仓库信息+库存
        if(Db::name('WarehouseGoods')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_warehouse_goods_fail_tips'));
        }
        if(Db::name('WarehouseGoodsSpec')->where(['goods_id'=>$goods_ids])->delete() === false)
        {
            throw new \Exception(MyLang('common_service.goods.delete_warehouse_goods_spec_fail_tips'));
        }

        // 是否删除图片
        if($is_del_images && !empty($del_images_data))
        {
            AttachmentService::AttachmentUrlDelete($del_images_data);
        }
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
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => MyLang('operate_field_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 基础参数
            $goods_id = intval($params['id']);
            $field = $params['field'];
            $status = intval($params['state']);

            // 数据更新
            if(!Db::name('Goods')->where(['id'=>$goods_id])->update([$field=>$status, 'upd_time'=>time()]))
            {
                throw new \Exception(MyLang('operate_fail'));
            }

            // 商品状态更新钩子
            $hook_name = 'plugins_service_goods_field_status_update';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'goods_id'      => $goods_id,
                'field'         => $field,
                'status'        => $status,
            ]);

            // 提交事务
            Db::commit();
            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
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
        $type = Db::name('GoodsSpecType')->where($where)->order('id asc')->field('id,name,value')->select()->toArray();
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
            $temp_value = Db::name('GoodsSpecValue')->where($where)->field('goods_spec_base_id,value')->order('id asc')->select()->toArray();
            if(!empty($temp_value))
            {
                foreach($temp_value as $value_v)
                {
                    $key = '';
                    foreach($type as $type_v)
                    {
                        foreach($type_v['value'] as $type_vs)
                        {
                            if(trim($type_vs['name']) == trim($value_v['value']))
                            {
                                $key = $type_v['id'];
                                break;
                            }
                        }
                    }
                    if(!empty($key))
                    {
                        $value[$value_v['goods_spec_base_id']][] = [
                            'data_type' => 'spec',
                            'data'  => [
                                'key'       => $key,
                                'value'     => trim($value_v['value']),
                            ],
                        ];
                    }
                }
            }

            if(!empty($value))
            {
                foreach($value as $k=>&$v)
                {
                    $base = Db::name('GoodsSpecBase')->find($k);
                    $base['weight'] = PriceBeautify($base['weight']);
                    $base['volume'] = PriceBeautify($base['volume']);
                    $v[] = [
                        'data_type' => 'base',
                        'data'      => $base,
                    ];
                }
            }
        } else {
            $base = Db::name('GoodsSpecBase')->where($where)->find();
            $base['weight'] = PriceBeautify($base['weight']);
            $base['volume'] = PriceBeautify($base['volume']);
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
        return Db::name('GoodsParams')->where(['goods_id'=>$goods_id])->order('id asc')->select()->toArray();
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
                'error_msg'         => MyLang('goods_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'spec',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.goods.base_spec_not_choice_tips'),
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
            'goods_id'  => $goods_id,
        ];

        // 规格数据
        // 规格不为数组则为json字符串
        $spec = [];
        if(!empty($params['spec']))
        {
            if(!is_array($params['spec']))
            {
                $params['spec'] = json_decode(htmlspecialchars_decode($params['spec']), true);
            }
            $spec = array_column($params['spec'], 'value');
        }

        // 规格基础静态临时存储
        static $goods_service_goods_spec_base_static_data = [];
        $key = $goods_id.(empty($spec) ? '' : md5(json_encode($spec, JSON_UNESCAPED_UNICODE)));
        if(array_key_exists($key, $goods_service_goods_spec_base_static_data))
        {
            $base = Db::name('GoodsSpecBase')->find($goods_service_goods_spec_base_static_data[$key]);
        } else {
            // 商品信息
            $info = Db::name('Goods')->where(['id'=>$goods_id])->field('id,title,is_exist_many_spec')->find();
            if(empty($info))
            {
                return DataReturn('【'.$goods_id.'】'.MyLang('no_goods'), -1);
            }

            // 规格值校验处理
            $base = [];
            if(empty($spec))
            {
                // 没有指定规格、但是商品已存在规则则报错
                if($info['is_exist_many_spec'] == 1)
                {
                    return DataReturn('【'.$info['title'].'】'.MyLang('common_service.goods.base_spec_not_choice_tips'), -1);
                }

                // 单个规则则直接获取规格基础
                $base = Db::name('GoodsSpecBase')->where($where)->find();
            } else {
                // 指定规格规格、但是商品没有规格则报错
                if($info['is_exist_many_spec'] == 0)
                {
                    return DataReturn('【'.$info['title'].'】'.MyLang('common_service.goods.base_spec_empty_tips'), -1);
                }

                // 获取规格值基础值id
                $where['value'] = $spec;
                $ids = Db::name('GoodsSpecValue')->where($where)->column('goods_spec_base_id');
                if(!empty($ids))
                {
                    // 根据基础值id获取规格值列表
                    $temp_data = Db::name('GoodsSpecValue')->where(['goods_spec_base_id'=>$ids])->field('goods_spec_base_id,value')->order('id asc')->select()->toArray();
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
            }
            if(!empty($base))
            {
                $goods_service_goods_spec_base_static_data[$key] = $base['id'];
            }
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
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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

            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('common_service.goods.base_spec_empty_tips'), -100);
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
                'error_msg'         => MyLang('goods_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'spec',
                'error_msg'         => MyLang('common_service.goods.base_spec_not_choice_tips'),
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
        if(!is_array($params['spec']))
        {
            $params['spec'] = json_decode(htmlspecialchars_decode($params['spec']), true);
        }
        $where['value'] = array_column($params['spec'], 'value');

        // 获取规格值基础值id
        $ids = Db::name('GoodsSpecValue')->where($where)->column('goods_spec_base_id');
        if(!empty($ids))
        {
            // 根据基础值id获取规格值列表
            $temp_data = Db::name('GoodsSpecValue')->where(['goods_spec_base_id'=>$ids])->field('goods_spec_base_id,value')->order('id asc')->select()->toArray();
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
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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

                return DataReturn(MyLang('operate_success'), 0, $data);
            }
        }
        return DataReturn(MyLang('common_service.goods.base_spec_type_empty_tips'), -100);
    }

    /**
     * 商品购买数量获取商品信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-10-05
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsStock($params = [])
    {
        // 是否批量
        if(!empty($params['goods_data']))
        {
            // 是否数组
            if(!is_array($params['goods_data']))
            {
                $params['goods_data'] = json_decode(htmlspecialchars_decode($params['goods_data']), true);
            }
            if(empty($params['goods_data']))
            {
                return DataReturn(MyLang('params_error_tips'), -1);
            }

            // 循环处理
            $result = [];
            foreach($params['goods_data'] as $v)
            {
                // 请求参数
                $p = [
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'id',
                        'error_msg'         => MyLang('goods_id_error_tips'),
                    ],
                    [
                        'checked_type'      => 'isset',
                        'key_name'          => 'stock',
                        'error_msg'         => MyLang('common_service.goods.base_buy_stock_error_tips'),
                    ],
                ];
                $ret = ParamsChecked($v, $p);
                if($ret !== true)
                {
                    return DataReturn($ret, -1);
                }

                // 获取商品基础信息
                $result[] = self::GoodsSpecDetail($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $result);
        } else {
            // 请求参数
            $p = [
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'id',
                    'error_msg'         => MyLang('goods_id_error_tips'),
                ],
                [
                    'checked_type'      => 'isset',
                    'key_name'          => 'stock',
                    'error_msg'         => MyLang('common_service.goods.base_buy_stock_error_tips'),
                ],
            ];
            $ret = ParamsChecked($params, $p);
            if($ret !== true)
            {
                return DataReturn($ret, -1);
            }

            // 获取商品基础信息
            return self::GoodsSpecDetail($params);
        }
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
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return DataReturn(MyLang('get_success'), 0, $data);
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

        // 是否展示型商品
        if($site_type == 4)
        {
            return DataReturn(MyLang('goods_only_show_title'), -1, $site_type);
        }
        
        // 商品类型与当前系统的类型是否一致包含其中
        if(IsGoodsSiteTypeConsistent($site_type) == 1)
        {
            return DataReturn('success', 0, $site_type);
        }

        // 仅可单独购买
        $site_type_arr = MyConst('common_site_type_list');
        $msg = array_key_exists($site_type, $site_type_arr) ? MyLang('only_title').$site_type_arr[$site_type]['name'] : MyLang('goods_only_buy_title');
        return DataReturn($msg, -1, $site_type);
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
     * 商品底部左侧小导航
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-19
     * @desc    description
     * @param   [array]          $goods  [商品信息]
     */
    public static function GoodsBuyLeftNavList($goods)
    {
        // 当前用户是否已收藏
        $user_is_favor = isset($goods['user_is_favor']) && $goods['user_is_favor'] == 1;
        // 导航数据
        // name     名称
        // icon     icon图标
        // type     类型
        // url      url跳转地址
        // active   是否选中（0否、1是）
        // class    自定义class
        // document 元素数据
        $data = [
            [
                'name'      => MyLang('home_title'),
                'icon'      => (APPLICATION == 'web') ? 'iconfont icon-index' : StaticAttachmentUrl('home-icon.png'),
                'type'      => 'home',
                'url'       => SystemService::DomainUrl(),
                'active'    => 0,
                'class'     => '',
                'document'  => '',
            ],
            [
                'name'      => $user_is_favor ? MyLang('already_favor_title') : MyLang('favor_title'),
                'icon'      => (APPLICATION == 'web') ? ('iconfont '.($user_is_favor ? 'icon-heart' : 'icon-heart-o')) : StaticAttachmentUrl('favor'.($user_is_favor ? '-active' : '').'-icon.png'),
                'type'      => 'favor',
                'active'    => $user_is_favor ? 1 : 0,
                'class'     => '',
                'document'  => '',
            ]
        ];

        // 手机端是否存在客服
        if(APPLICATION == 'app' && MyC('common_app_is_online_service') == 1)
        {
            // 是否存在自定义客服
            $custom = MyC('common_app_customer_service_custom', []);
            if(empty($custom) || empty($custom[APPLICATION_CLIENT_TYPE]))
            {
                // h5,ios,android端必须存在电话
                $status = in_array(APPLICATION_CLIENT_TYPE, ['h5', 'ios', 'android']);
                if($status)
                {
                    $tel = MyC('common_app_customer_service_tel');
                    $status = !empty($tel);
                } else {
                    $status = true;
                }
            } else {
                $status = true;
            }
            if($status)
            {
                $data[] = [
                    'name'  => MyLang('chat_title'),
                    'icon'  => StaticAttachmentUrl('chat-icon.png'),
                    'type'  => 'chat',
                ];
            }
        }

        // 商品购买导航左侧钩子
        $hook_name = 'plugins_service_goods_buy_left_nav_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'goods'         => $goods,
            'data'          => &$data,
        ]);
        return $data;
    }

    /**
     * 商品购买按钮列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-19
     * @desc    description
     * @param   [array]          $goods  [商品信息]
     */
    public static function GoodsBuyButtonList($goods)
    {
        // 错误信息
        $error = '';

        // 是否已下架
        if($goods['is_shelves'] != 1)
        {
            $error = MyLang('goods_already_shelves_title');
        }

        // 按钮列表
        // color 颜色类型[main主, second次]（默认 main）
        // type 类型[show展示, buy购买, cart加入购物车, other其他值]
        // name 名称
        // title 元素title说明（可选）
        // value 数据值（可选）
        // icon icon类名称（可选）
        // class 自定义类名称（可选）
        // business 业务
        $data = [];
        if(empty($error))
        {
            // 获取商品类型
            $model_type = self::GoodsSalesModelType($goods['id'], $goods['site_type']);

            // 是否展示型
            if($model_type['data'] == 4)
            {
                $name = MyC('common_is_exhibition_mode_btn_text');
                $data[] = [
                    'color' => 'main',
                    'type'  => 'show',
                    'name'  => empty($name) ? MyLang('goods_show_title') : $name,
                    'value' => MyC('common_customer_store_tel'),
                    'icon'  => 'am-icon-phone', 
                ];
                $error = MyLang('goods_only_show_title');
            } else {
                // 还有库存
                if($goods['inventory'] <= 0)
                {
                    $error = MyLang('goods_no_inventory_title');
                }
                if(empty($error))
                {
                    // web端class
                    $class_name = (APPLICATION == 'web') ? 'buy-event login-event' : '';

                    // 购买
                    $name = (MyC('common_order_is_booking', 0, true) == 1) ? MyLang('goods_booking_title') : MyLang('goods_buy_title');
                    $buy = [
                        'color' => 'main',
                        'type'  => 'buy',
                        'title' => $name,
                        'name'  => $name,
                        'class' => $class_name,
                        'icon'  => '',
                    ];

                    // 商品类型是否和当前站点类型一致
                    $cart = [];
                    $ret = self::IsGoodsSiteTypeConsistent($goods['id'], $goods['site_type']);
                    if($ret['code'] == 0)
                    {
                        // 加入购物车
                        $name = MyLang('goods_cart_title');
                        $cart = [
                            'color' => 'second',
                            'type'  => 'cart',
                            'title' => $name,
                            'name'  => $name,
                            'class' => $class_name,
                            'icon'  => 'am-icon-opencart',
                        ];
                    } else {
                        $error = $ret['msg'];
                    }

                    // 主按钮顺序处理，手机端立即购买放在最后面
                    if(APPLICATION == 'app')
                    {
                        if(!empty($cart))
                        {
                            $data[] = $cart;
                        }
                        $data[] = $buy;
                    } else {
                        $data[] = $buy;
                        if(!empty($cart))
                        {
                            $data[] = $cart;
                        }
                    }
                }
            }
        }

        // 商品购买导航按钮钩子
        $hook_name = 'plugins_service_goods_buy_nav_button_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'goods'         => $goods,
            'data'          => &$data,
            'error'         => &$error,
        ]);

        // 是否存在按钮数据
        if(empty($data) && empty($error))
        {
            $error = MyLang('goods_stop_sale_title');
        }

        // 返回数据
        $count = 0;
        $types = [];
        if(!empty($data) && is_array($data))
        {
            $count = count($data);
            $types = array_column($data, 'type');
        }
        return [
            'data'      => $data,
            'count'     => $count,
            'error'     => $error,
            'is_buy'    => in_array('buy', $types) ? 1 : 0,
            'is_cart'   => in_array('cart', $types) ? 1 : 0,
            'is_show'   => in_array('show', $types) ? 1 : 0,
        ];
    }

    /**
     * 商品详情中间tabs导航列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-19
     * @desc    description
     * @param   [array]          $goods  [商品信息]
     */
    public static function GoodsDetailMiddleTabsNavList($goods)
    {
        // 从缓存获取
        $key = SystemService::CacheKey('shopxo.cache_goods_detail_middle_tabs_key').APPLICATION;
        $data = MyCache($key);
        if($data === null || MyEnv('app_debug') || MyC('common_data_is_use_cache') != 1)
        {
            // 是否展示商品评价
            $is_comments = MyC('common_is_goods_detail_show_comments', 1);

            // app与web端不一致
            if(APPLICATION == 'app') 
            {
                // 这里的 ent 值必须和系统中区域块定义的一致
                $data = [
                    [
                        'type'  => 'main',
                        'name'  => MyLang('goods_main_title'),
                        'ent'   => '.page',
                    ],
                ];

                // 是否展示商品评价
                if($is_comments == 1)
                {
                    $data[] = [
                        'type'  => 'comments',
                        'name'  => MyLang('comment_title'),
                        'ent'   => '.goods-comment',
                    ];
                }

                // 商品详情介绍
                $data[] = [
                    'type'  => 'detail',
                    'name'  => MyLang('detail_title'),
                    'ent'   => '.goods-detail',
                ];
            } else {
                // 评论总数
                $comments_count = isset($goods['comments_count']) ? $goods['comments_count'] : GoodsCommentsService::GoodsCommentsTotal(['goods_id'=>$goods['id'], 'is_show'=>1]);

                // 列表
                // type 类型
                // name 名称
                // active 选中（可选）
                // value 数据值（可选）
                $data = [
                    [
                        'type'      => 'detail',
                        'name'      => MyLang('detail_title'),
                        'active'    => 1,
                    ],
                ];

                // 是否展示商品评价
                if($is_comments == 1)
                {
                    $data[] = [
                        'type'      => 'comments',
                        'name'      => MyLang('comment_title').'('.$comments_count.')',
                    ];
                }

                // 猜你喜欢，目前以销量最高推荐
                if(MyC('common_is_goods_detail_show_guess_you_like', 0) == 1)
                {
                    $data[] = [
                        'type'      => 'guess_you_like',
                        'name'      => MyLang('goods_guess_you_like_title'),
                    ];
                }
            }

            // 商品详情中间导航钩子
            $hook_name = 'plugins_service_goods_detail_middle_tabs_nav_handle';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'goods'         => $goods,
                'data'          => &$data,
            ]);

            // 格式集合
            $data = [
                'nav'   => $data,
                'type'  => array_column($data, 'type'),
            ];

            // 存储缓存
            MyCache($key, $data, 180);
        }
        return $data;
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

        // 自定义路径
        $path = 'download'.DS.'goods_qrcode'.DS.APPLICATION_CLIENT_TYPE.DS.date('Y', $add_time).DS.date('m', $add_time).DS.date('d', $add_time).DS;

        // 名称增加站点模式（站点模式不一样商品url地址也会不一样）
        $filename = $goods_id.SystemBaseService::SiteTypeValue().'.png';

        // 二维码处理参数
        $params = [
            'path'      => DS.$path,
            'filename'  => $filename,
            'content'   => MyUrl('index/goods/index', ['id'=>$goods_id]),
        ];

        // 创建二维码
        return (new \base\Qrcode())->Create($params);
    }

    /**
     * 商品url生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-02-12
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsUrlCreate($goods_id)
    {
        return (APPLICATION_CLIENT_TYPE == 'pc') ? MyUrl('index/goods/index', ['id'=>$goods_id]) : '/pages/goods-detail/goods-detail?id='.$goods_id;
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
    public static function GoodsSearchList($params = [])
    {
        // 分页
        $page = max(1, isset($params['page']) ? intval($params['page']) : 1);
        $page_size = empty($params['page_size']) ? 20 : min(intval($params['page_size']), 100);
        $page_start = intval(($page-1)*$page_size);

        // 返回格式
        $result = [
            'page'          => $page,
            'page_start'    => $page_start,
            'page_size'     => $page_size,
            'page_total'    => 0,
            'total'         => 0,
            'data'          => [],
        ];
        
        // 搜索条件
        $where_base = empty($params['where_base']) ? [] : $params['where_base'];
        $where_keywords = empty($params['where_keywords']) ? [] : $params['where_keywords'];

        // 排序
        $order_by = empty($params['order_by']) ? 'access_count desc, sales_count desc, id desc' : $params['order_by'];

        // 指定字段
        $field = empty($params['field']) ? '*' : $params['field'];

        // 商品搜索列表读取前钩子
        $hook_name = 'plugins_service_goods_search_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'                 => $hook_name,
            'is_backend'                => true,
            'params'                    => $params,
            'where_base'                => &$where_base,
            'where_keywords'            => &$where_keywords,
            'field'                     => &$field,
            'order_by'                  => &$order_by,
            'page'                      => &$result['page'],
        ]);

        // 获取商品总数
        $result['total'] = (int) Db::name('Goods')->where($where_base)->where(function($query) use($where_keywords) {
            $query->whereOr($where_keywords);
        })->count();

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 查询数据
            $goods = self::GoodsDataHandle(Db::name('Goods')->field($field)->where($where_base)->where(function($query) use($where_keywords) {
                $query->whereOr($where_keywords);
            })->order($order_by)->limit($result['page_start'], $result['page_size'])->select()->toArray(), $params);

            // 返回数据
            $result['data'] = $goods['data'];
            $result['page_total'] = ceil($result['total']/$result['page_size']);
        }
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 商品基础模板
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-26
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsBaseTemplate($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_ids',
                'error_msg'         => MyLang('form_goods_category_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 规格模板
        $spec = GoodsSpecService::GoodsCategorySpecTemplateList($params);

        // 参数模板
        $parameter = GoodsParamsService::GoodsCategoryParamsTemplateList($params);

        return DataReturn(MyLang('operate_success'), 0, [
            'spec'      => $spec['data'],
            'params'    => $parameter['data'],
        ]);
    }

    /**
     * 商品详情页面猜你喜欢的相关商品
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-12-29
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsDetailGuessYouLikeData($goods_id)
    {
        $goods_list = [];
        if(!empty($goods_id) && MyC('common_is_goods_detail_show_guess_you_like', 0) == 1)
        {
            $category_ids = Db::name('GoodsCategoryJoin')->where(['goods_id'=>$goods_id])->column('category_id');
            if(!empty($category_ids))
            {
                $category_ids = GoodsCategoryService::GoodsCategoryParentIds(GoodsCategoryService::GoodsCategoryItemsIds($category_ids));
                $params = [
                    'where'     => [
                        ['g.is_shelves', '=', 1],
                        ['g.is_delete_time', '=', 0],
                        ['gci.category_id', 'in', $category_ids],
                        ['g.id', 'not in', $goods_id],
                    ],
                    'order_by'  => 'g.sales_count desc',
                    'n'         => 16,
                    'is_spec'   => 1,
                    'is_cart'   => 1,
                ];
                $ret = self::CategoryGoodsList($params);
                $goods_list = empty($ret['data']) ? [] : $ret['data'];
            }
        }
        return $goods_list;
    }

    /**
     * 商品详情页面看了又看的相关商品
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-12-29
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsDetailSeeingYouData($goods_id)
    {
        $goods_list = [];
        if(!empty($goods_id) && MyC('common_is_goods_detail_show_seeing_you', 0) == 1)
        {
            $params = [
                'where'     => [
                    ['is_shelves', '=', 1],
                    ['is_delete_time', '=', 0],
                    ['id', 'not in', $goods_id],
                ],
                'order_by'  => 'access_count desc',
                'n'         => 10,
            ];
            $ret = self::GoodsList($params);
            if(!empty($ret['data']))
            {
                $goods_list = $ret['data'];
            }
        }
        return $goods_list;
    }

    /**
     * 指定读取商品列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params    [输入参数]
     */
    public static function AppointGoodsList($params = [])
    {
        $result = [];
        if(!empty($params['goods_ids']))
        {
            // 非数组则转为数组
            if(!is_array($params['goods_ids']))
            {
                $params['goods_ids'] = explode(',', $params['goods_ids']);
            }

            // 基础条件
            $where = [
                ['is_delete_time', '=', 0],
                ['is_shelves', '=', 1],
                ['id', 'in', array_unique($params['goods_ids'])]
            ];

            // 获取数据
            $is_spec = isset($params['is_spec']) ? $params['is_spec'] : 0;
            $is_cart = isset($params['is_cart']) ? $params['is_cart'] : 0;
            $is_favor = isset($params['is_favor']) ? $params['is_favor'] : 0;
            $ret = self::GoodsList(['where'=>$where, 'm'=>0, 'n'=>0, 'is_spec'=>$is_spec, 'is_cart'=>$is_cart, 'is_favor'=>$is_favor]);
            if(!empty($ret['data']))
            {
                $temp = array_column($ret['data'], null, 'id');
                foreach($params['goods_ids'] as $id)
                {
                    if(!empty($id) && array_key_exists($id, $temp))
                    {
                        $result[] = $temp[$id];
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 自动读取商品列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params [输入参数]
     */
    public static function AutoGoodsList($params = [])
    {
        // 基础条件
        $where = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1],
        ];

        // 商品关键字
        if(!empty($params['goods_keywords']))
        {
            $where[] = ['g.title|g.simple_desc', 'like', '%'.$params['goods_keywords'].'%'];
        }

        // 分类条件
        if(!empty($params['goods_category_ids']))
        {
            if(!is_array($params['goods_category_ids']))
            {
                $params['goods_category_ids'] = explode(',', $params['goods_category_ids']);
            }
            $where[] = ['gci.category_id', 'in', GoodsCategoryService::GoodsCategoryItemsIds($params['goods_category_ids'], 1)];
        }

        // 品牌条件
        if(!empty($params['goods_brand_ids']))
        {
            if(!is_array($params['goods_brand_ids']))
            {
                $params['goods_brand_ids'] = explode(',', $params['goods_brand_ids']);
            }
            $where[] = ['g.brand_id', 'in', $params['goods_brand_ids']];
        }

        // 排序
        $order_by_type_list = MyConst('common_goods_order_by_type_list');
        $order_by_rule_list = MyConst('common_data_order_by_rule_list');
        // 排序类型
        $order_by_type = !isset($params['goods_order_by_type']) || !array_key_exists($params['goods_order_by_type'], $order_by_type_list) ? $order_by_type_list[0]['value'] : $order_by_type_list[$params['goods_order_by_type']]['value'];
        // 排序值
        $order_by_rule = !isset($params['goods_order_by_rule']) || !array_key_exists($params['goods_order_by_rule'], $order_by_rule_list) ? $order_by_rule_list[0]['value'] : $order_by_rule_list[$params['goods_order_by_rule']]['value'];
        // 拼接排序
        $order_by = $order_by_type.' '.$order_by_rule;

        // 获取数据
        $is_spec = isset($params['is_spec']) ? $params['is_spec'] : 0;
        $is_cart = isset($params['is_cart']) ? $params['is_cart'] : 0;
        $is_favor = isset($params['is_favor']) ? $params['is_favor'] : 0;
        $ret = self::CategoryGoodsList([
            'where'     => $where,
            'm'         => 0,
            'n'         => empty($params['goods_number']) ? 10 : intval($params['goods_number']),
            'order_by'  => $order_by,
            'is_spec'   => $is_spec,
            'is_cart'   => $is_cart,
            'is_favor'  => $is_favor,
        ]);
        return empty($ret['data']) ? [] : $ret['data'];
    }
}
?>