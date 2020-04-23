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

/**
 * 商品服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsService
{
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

        if(empty($data))
        {
            // 获取分类
            $params['where'] = ['pid'=>0, 'is_enable'=>1];
            $data = self::GoodsCategory($params);

            // 存储缓存
            cache($key, $data, 3600*24);
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
        $data = self::GoodsCategoryList($where);
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $where['pid'] = $v['id'];
                $v['items'] = self::GoodsCategoryList($where);
                if(!empty($v['items']))
                {
                    // 一次性查出所有二级下的三级、再做归类、避免sql连接超多
                    $where['pid'] = array_column($v['items'], 'id');
                    $items = self::GoodsCategoryList($where);
                    if(!empty($items))
                    {
                        foreach($v['items'] as &$vs)
                        {
                            foreach($items as $vss)
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
     * @param   [array]          $where [条件]
     */
    public static function GoodsCategoryList($where = [])
    {
        $where['is_enable'] = 1;
        $field = 'id,pid,icon,name,vice_name,describe,bg_color,big_images,sort,is_home_recommended,seo_title,seo_keywords,seo_desc';
        $data = Db::name('GoodsCategory')->field($field)->where($where)->order('sort asc')->select();
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
    private static function GoodsCategoryDataDealWith($data)
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
        // 缓存
        $key = config('shopxo.cache_goods_floor_list_key');
        $data = cache($key);
        if(empty($data))
        {
            // 商品大分类
            $params['where'] = ['pid'=>0, 'is_home_recommended'=>1, 'is_enable'=>1];
            $data = self::GoodsCategory($params);
            if(!empty($data))
            {
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
                    $v['goods_ids'] = Db::name('Goods')->alias('g')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'g.id=gci.goods_id')->where($where)->group('g.id')->order('g.id desc')->limit(8)->column('g.id');
                    $v['goods'] = [];
                }
            }

            // 存储缓存
            cache($key, $data, 3600*24);
        }

        // 商品读取、商品不缓存、商品价格会根据用户等级可能会不一样
        if(!empty($data) && is_array($data))
        {
            // 根据分类获取楼层商品
            foreach($data as &$v)
            {
                if(!empty($v['goods_ids']) && is_array($v['goods_ids']))
                {
                    $res = self::GoodsList(['where'=>['id'=>$v['goods_ids'], 'is_home_recommended'=>1, 'is_shelves'=>1], 'm'=>0, 'n'=>8, 'field'=>'*']);
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
        
        return self::GoodsDataHandle($params, $data);
    }

    /**
     * 商品数据处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-08T23:16:42+0800
     * @param    [array]                   $params [输入参数]
     * @param    [array]                   $data   [商品列表]
     */
    public static function GoodsDataHandle($params, $data)
    {
        if(!empty($data))
        {
            // 其它额外处理
            $is_photo = (isset($params['is_photo']) && $params['is_photo'] == true) ? true : false;
            $is_spec = (isset($params['is_spec']) && $params['is_spec'] == true) ? true : false;
            $is_content_app = (isset($params['is_content_app']) && $params['is_content_app'] == true) ? true : false;
            $is_category = (isset($params['is_category']) && $params['is_category'] == true) ? true : false;

            // 开始处理数据
            foreach($data as &$v)
            {
                // 商品处理前钩子
                $hook_name = 'plugins_service_goods_handle_begin';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'goods'         => &$v,
                    'goods_id'      => isset($v['id']) ? $v['id'] : 0,
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
                if(!empty($v['id']))
                {
                    $v['goods_url'] = MyUrl('index/goods/index', ['id'=>$v['id']]);
                }

                // 商品封面图片
                if(isset($v['images']))
                {
                    $v['images_old'] = $v['images'];
                    $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                }

                // 视频
                if(isset($v['video']))
                {
                    $v['video_old'] = $v['video'];
                    $v['video'] = ResourcesService::AttachmentPathViewHandle($v['video']);
                }

                // 商品首页推荐图片，不存在则使用商品封面图片
                if(isset($v['home_recommended_images']))
                {
                    if(empty($v['home_recommended_images']))
                    {
                        if(isset($v['images']))
                        {
                            $v['home_recommended_images'] = $v['images'];
                        } else {
                            if(!empty($v['id']))
                            {
                                $images = Db::name('Goods')->where(['id'=>$v['id']])->value('images');
                                $v['home_recommended_images'] = ResourcesService::AttachmentPathViewHandle($images);
                            }
                        }
                    } else {
                        $v['home_recommended_images_old'] = $v['home_recommended_images'];
                        $v['home_recommended_images'] = ResourcesService::AttachmentPathViewHandle($v['home_recommended_images']);
                    }
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
                if($is_category && !empty($v['id']))
                {
                    $v['category_ids'] = Db::name('GoodsCategoryJoin')->where(['goods_id'=>$v['id']])->column('category_id');
                    $category_name = Db::name('GoodsCategory')->where(['id'=>$v['category_ids']])->column('name');
                    $v['category_text'] = implode('，', $category_name);
                }

                // 获取相册
                if($is_photo && !empty($v['id']))
                {
                    $v['photo'] = Db::name('GoodsPhoto')->where(['goods_id'=>$v['id'], 'is_show'=>1])->order('sort asc')->select();
                    if(!empty($v['photo']))
                    {
                        foreach($v['photo'] as &$vs)
                        {
                            $vs['images_old'] = $vs['images'];
                            $vs['images'] = ResourcesService::AttachmentPathViewHandle($vs['images']);
                        }
                    }
                }

                // 规格基础
                if(isset($v['spec_base']))
                {
                    $v['spec_base'] = empty($v['spec_base']) ? '' : json_decode($v['spec_base'], true);
                }

                // 获取规格
                if($is_spec && !empty($v['id']))
                {
                    $v['specifications'] = self::GoodsSpecifications(['goods_id'=>$v['id']]);
                }

                // 获取app内容
                if($is_content_app && !empty($v['id']))
                {
                    $v['content_app'] = self::GoodsContentApp(['goods_id'=>$v['id']]);
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
                    'goods_id'      => isset($v['id']) ? $v['id'] : 0,
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }
            }
        }
        return DataReturn('处理成功', 0, $data);
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
    public static function GoodsContentApp($params = [])
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
     * 获取商品属性
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsSpecifications($params = [])
    {
        // 条件
        $where = ['goods_id'=>$params['goods_id']];

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
                        'id'    => $params['goods_id'],
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
     * 商品收藏
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsFavor($params = [])
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
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck('id', $params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 开始操作
        $data = ['goods_id'=>intval($params['id']), 'user_id'=>$params['user']['id']];
        $temp = Db::name('GoodsFavor')->where($data)->find();
        if(empty($temp))
        {
            // 添加收藏
            $data['add_time'] = time();
            if(Db::name('GoodsFavor')->insertGetId($data) > 0)
            {
                return DataReturn('收藏成功', 0, [
                    'text'      => '已收藏',
                    'status'    => 1,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            } else {
                return DataReturn('收藏失败');
            }
        } else {
            // 是否强制收藏
            if(isset($params['is_mandatory_favor']) && $params['is_mandatory_favor'] == 1)
            {
                return DataReturn('收藏成功', 0, [
                    'text'      => '已收藏',
                    'status'    => 1,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            }

            // 删除收藏
            if(Db::name('GoodsFavor')->where($data)->delete() > 0)
            {
                return DataReturn('取消成功', 0, [
                    'text'      => '收藏',
                    'status'    => 0,
                    'count'     => self::GoodsFavorTotal(['goods_id'=>$data['goods_id']]),
                ]);
            } else {
                return DataReturn('取消失败');
            }
        }
    }

    /**
     * 用户是否收藏了商品
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [int]                    [1已收藏, 0未收藏]
     */
    public static function IsUserGoodsFavor($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $data = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = Db::name('GoodsFavor')->where($data)->find();
        return DataReturn('操作成功', 0, empty($temp) ? 0 : 1);
    }

    /**
     * 前端商品收藏列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserGoodsFavorListWhere($params = [])
    {
        $where = [
            ['g.is_delete_time', '=', 0]
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where[]= ['f.user_id', '=', $params['user']['id']];
        }

        if(!empty($params['keywords']))
        {
            $where[] = ['g.title|g.model|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords', 'like', '%'.$params['keywords'].'%'];
        }

        return $where;
    }

    /**
     * 商品收藏总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function GoodsFavorTotal($where = [])
    {
        return (int) Db::name('GoodsFavor')->alias('f')->join(['__GOODS__'=>'g'], 'g.id=f.goods_id')->where($where)->count();
    }

    /**
     * 商品收藏列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsFavorList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'f.id desc' : $params['order_by'];
        $field = 'f.*, g.title, g.original_price, g.price, g.min_price, g.images';

        // 获取数据
        $data = Db::name('GoodsFavor')->alias('f')->join(['__GOODS__'=>'g'], 'g.id=f.goods_id')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 图片
                $v['images_old'] = $v['images'];
                $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);

                $v['goods_url'] = MyUrl('index/goods/index', ['id'=>$v['goods_id']]);
            }
        }
        return DataReturn('处理成功', 0, $data);
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
     * 商品浏览保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-15
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsBrowseSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $where = ['goods_id'=>intval($params['goods_id']), 'user_id'=>$params['user']['id']];
        $temp = Db::name('GoodsBrowse')->where($where)->find();

        $data = [
            'goods_id'  => intval($params['goods_id']),
            'user_id'   => $params['user']['id'],
            'upd_time'  => time(),
        ];
        if(empty($temp))
        {
            $data['add_time'] = time();
            $status = Db::name('GoodsBrowse')->insertGetId($data) > 0;
        } else {
            $status = Db::name('GoodsBrowse')->where($where)->update($data) !== false;
        }
        if($status)
        {
            return DataReturn('处理成功', 0);
        }
        return DataReturn('处理失败', -100);
    }

    /**
     * 前端商品浏览列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserGoodsBrowseListWhere($params = [])
    {
        $where = [
            ['g.is_delete_time', '=', 0]
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['b.user_id', '=', $params['user']['id']];
        }

        if(!empty($params['keywords']))
        {
            $where[] = ['g.title|g.model|g.simple_desc|g.seo_title|g.seo_keywords|g.seo_keywords', 'like', '%'.$params['keywords'].'%'];
        }

        return $where;
    }

    /**
     * 商品浏览总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function GoodsBrowseTotal($where = [])
    {
        return (int) Db::name('GoodsBrowse')->alias('b')->join(['__GOODS__'=>'g'], 'g.id=b.goods_id')->where($where)->count();
    }

    /**
     * 商品浏览列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsBrowseList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'b.id desc' : $params['order_by'];
        $field = 'b.*, g.title, g.original_price, g.price, g.min_price, g.images';

        // 获取数据
        $data = Db::name('GoodsBrowse')->alias('b')->join(['__GOODS__'=>'g'], 'g.id=b.goods_id')->field($field)->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['images_old'] = $v['images'];
                $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                $v['goods_url'] = MyUrl('index/goods/index', ['id'=>$v['goods_id']]);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 商品浏览删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsBrowseDelete($params = [])
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
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 删除
        $where = [
            'id'        => explode(',', $params['id']),
            'user_id'   => $params['user']['id']
        ];
        if(Db::name('GoodsBrowse')->where($where)->delete())
        {
            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败或资源不存在', -100);
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
        
        return self::GoodsDataHandle($params, $data);
    }

    /**
     * 后台管理商品列表条件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GetAdminIndexWhere($params = [])
    {
        $where = [
            ['is_delete_time', '=', 0],
        ];

        // 模糊
        if(!empty($params['keywords']))
        {
            $where[] = ['title|model|simple_desc|seo_title|seo_keywords|seo_keywords', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['is_shelves']) && $params['is_shelves'] > -1)
            {
                $where[] = ['is_shelves', '=', intval($params['is_shelves'])];
            }
            if(isset($params['is_home_recommended']) && $params['is_home_recommended'] > -1)
            {
                $where[] = ['is_home_recommended', '=', intval($params['is_home_recommended'])];
            }

            // 时间
            if(!empty($params['time_start']))
            {
                $where[] = ['add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['add_time', '<', strtotime($params['time_end'])];
            }

            // 商品分类
            if(!empty($params['category_id']) && $params['category_id'] > 0)
            {
                $category_ids = self::GoodsCategoryItemsIds([intval($params['category_id'])], 1);
                $goods_ids = Db::name('GoodsCategoryJoin')->where(['category_id'=>$category_ids])->column('goods_id');
                if(!empty($goods_ids))
                {
                    $where[] = ['id', 'in', $goods_ids];
                } else {
                    // 避免空条件造成无效的错觉
                    $where[] = ['id', '=', 0];
                }
            }

            // 品牌
            if(!empty($params['brand_id']) && $params['brand_id'] > 0)
            {
                $where[] = ['brand_id', '=', intval($params['brand_id'])];
            }
        }
        return $where;
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
                'checked_data'      => '2,60',
                'error_msg'         => '标题名称格式 2~60 个字符',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'simple_desc',
                'checked_data'      => '160',
                'is_checked'        => 1,
                'error_msg'         => '商品简述格式 最多160个字符',
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
        $data_fields = ['home_recommended_images', 'video'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }

        // 编辑器内容
        $content_web = empty($params['content_web']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['content_web']), 'add');
        $fictitious_goods_value = empty($params['fictitious_goods_value']) ? '' : ResourcesService::ContentStaticReplace(htmlspecialchars_decode($params['fictitious_goods_value']), 'add');

        // 基础数据
        $data = [
            'title'                     => $params['title'],
            'title_color'               => empty($params['title_color']) ? '' : $params['title_color'],
            'simple_desc'               => $params['simple_desc'],
            'model'                     => $params['model'],
            'place_origin'              => isset($params['place_origin']) ? intval($params['place_origin']) : 0,
            'inventory_unit'            => $params['inventory_unit'],
            'give_integral'             => intval($params['give_integral']),
            'buy_min_number'            => max(1, isset($params['buy_min_number']) ? intval($params['buy_min_number']) : 1),
            'buy_max_number'            => isset($params['buy_max_number']) ? intval($params['buy_max_number']) : 0,
            'is_deduction_inventory'    => isset($params['is_deduction_inventory']) ? intval($params['is_deduction_inventory']) : 0,
            'is_shelves'                => isset($params['is_shelves']) ? intval($params['is_shelves']) : 0,
            'content_web'               => $content_web,
            'images'                    => isset($photo['data'][0]) ? $photo['data'][0] : '',
            'photo_count'               => count($photo['data']),
            'is_home_recommended'       => isset($params['is_home_recommended']) ? intval($params['is_home_recommended']) : 0,
            'home_recommended_images'   => $attachment['data']['home_recommended_images'],
            'brand_id'                  => isset($params['brand_id']) ? intval($params['brand_id']) : 0,
            'video'                     => $attachment['data']['video'],
            'seo_title'                 => empty($params['seo_title']) ? '' : $params['seo_title'],
            'seo_keywords'              => empty($params['seo_keywords']) ? '' : $params['seo_keywords'],
            'seo_desc'                  => empty($params['seo_desc']) ? '' : $params['seo_desc'],
            'is_exist_many_spec'        => empty($specifications['data']['title']) ? 0 : 1,
            'spec_base'                 => empty($specifications_base['data']) ? '' : json_encode($specifications_base['data']),
            'fictitious_goods_value'       => $fictitious_goods_value,
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

            // 提交事务
            Db::commit();
            return DataReturn('操作成功', 0);
        }

        // 回滚事务
        Db::rollback();
        return DataReturn('操作失败', -100);
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
    private static function GoodsSaveBaseUpdate($params, $goods_id)
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
    private static function GetFormGoodsSpecificationsParams($params = [])
    {
        $data = [];
        $title = [];
        $images = [];

        // 基础字段数据字段长度
        $base_count = 7;

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
    private static function GetFormGoodsSpecificationsBaseParams($params = [])
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
    private static function GetFormGoodsPhotoParams($params = [])
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
    private static function GetFormGoodsContentAppParams($params = [])
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
    private static function GoodsCategoryInsert($data, $goods_id)
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
    private static function GoodsContentAppInsert($data, $goods_id)
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
    private static function GoodsPhotoInsert($data, $goods_id)
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
    private static function GoodsSpecificationsInsert($data, $goods_id)
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
                $v['value']     = json_encode($spec);
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
            $temp_key = ['price', 'inventory', 'weight', 'coding', 'barcode', 'original_price', 'extends'];
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
        if(empty($params['id']))
        {
            return DataReturn('商品id有误', -1);
        }

        // 开启事务
        Db::startTrans();

        // 删除商品
        if(Db::name('Goods')->delete(intval($params['id'])))
        {
            // 商品规格
            if(Db::name('GoodsSpecType')->where(['goods_id'=>intval($params['id'])])->delete() === false)
            {
                Db::rollback();
                return DataReturn('规格类型删除失败', -100);
            }
            if(Db::name('GoodsSpecValue')->where(['goods_id'=>intval($params['id'])])->delete() === false)
            {
                Db::rollback();
                return DataReturn('规格值删除失败', -100);
            }
            if(Db::name('GoodsSpecBase')->where(['goods_id'=>intval($params['id'])])->delete() === false)
            {
                Db::rollback();
                return DataReturn('规格基础删除失败', -100);
            }

            // 相册
            if(Db::name('GoodsPhoto')->where(['goods_id'=>intval($params['id'])])->delete() === false)
            {
                Db::rollback();
                return DataReturn('相册删除失败', -100);
            }

            // app内容
            if(Db::name('GoodsContentApp')->where(['goods_id'=>intval($params['id'])])->delete() === false)
            {
                Db::rollback();
                return DataReturn('相册删除失败', -100);
            }

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
                $temp_data = Db::name('GoodsSpecValue')->where(['goods_spec_base_id'=>$ids])->field('goods_spec_base_id,value')->select();
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
            $temp_data = Db::name('GoodsSpecValue')->where(['goods_spec_base_id'=>$ids])->field('goods_spec_base_id,value')->select();
            if(!empty($temp_data))
            {
                // 根据基础值id分组
                $group = [];
                foreach($temp_data as $v)
                {
                    $group[$v['goods_spec_base_id']][] = $v;
                }

                // 获取当前操作元素索引
                $last  = end($params['spec']);
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
}
?>