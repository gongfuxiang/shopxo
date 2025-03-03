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
use app\service\UserService;
use app\service\BuyService;
use app\service\GoodsService;
use app\service\ResourcesService;

/**
 * 商品购物车服务层
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-10-09
 * @desc    description
 */
class GoodsCartService
{
    /**
     * 获取购物车列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCartList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 基础参数
        $where = (!empty($params['where']) && is_array($params['where'])) ? $params['where'] : [];
        $where[] = ['c.user_id', '=', $params['user']['id']];
        $field = 'c.*,g.inventory_unit,g.is_shelves,g.is_delete_time,g.buy_min_number,g.buy_max_number,g.model,g.site_type';

        // 购物车列表读取前钩子
        $hook_name = 'plugins_service_cart_goods_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
        ]);

        // 获取购物车数据
        $data = Db::name('Cart')->alias('c')->leftJoin('goods g', 'g.id=c.goods_id')->where($where)->field($field)->order('c.id desc')->select()->toArray();
        if(!empty($data))
        {
            // 收藏数据
            $favor_where = [
                ['goods_id', 'in', array_column($data, 'goods_id')],
                ['user_id', '=', $params['user']['id']],
            ];
            $favor_data = Db::name('GoodsFavor')->where($favor_where)->column('goods_id');

            // 商品处理
            $res = GoodsService::GoodsDataHandle($data, ['data_key_field'=>'goods_id']);
            $data = $res['data'];
            foreach($data as &$v)
            {
                // 是否已收藏
                $v['is_favor'] = (empty($favor_data) || !in_array($v['goods_id'], $favor_data)) ? 0 : 1;

                // 规格
                $v['spec'] = empty($v['spec']) ? null : json_decode($v['spec'], true);
                $v['spec_text'] = empty($v['spec']) ? '' : implode('，', array_filter(array_map(function($spec)
                        {
                            return (isset($spec['type']) && isset($spec['value'])) ? $spec['type'].':'.$spec['value'] : '';
                        }, $v['spec'])));

                // 获取商品基础信息
                $spec_params = array_merge($params, [
                    'id'    => $v['goods_id'],
                    'spec'  => $v['spec'],
                    'stock' => $v['stock'],
                ]);
                $goods_base = GoodsService::GoodsSpecDetail($spec_params);
                $v['is_invalid'] = 0;
                if($goods_base['code'] == 0)
                {
                    $v['inventory'] = $goods_base['data']['spec_base']['inventory'];
                    $v['price'] = $goods_base['data']['spec_base']['price'];
                    $v['total_price'] = PriceNumberFormat($v['stock']* $v['price']);
                    $v['original_price'] = $goods_base['data']['spec_base']['original_price'];
                    $v['spec_base_id'] = $goods_base['data']['spec_base']['id'];
                    $v['spec_buy_min_number'] = $goods_base['data']['spec_base']['buy_min_number'];
                    $v['spec_buy_max_number'] = $goods_base['data']['spec_base']['buy_max_number'];
                    $v['spec_weight'] = $goods_base['data']['spec_base']['weight'];
                    $v['spec_volume'] = $goods_base['data']['spec_base']['volume'];
                    $v['spec_coding'] = $goods_base['data']['spec_base']['coding'];
                    $v['spec_barcode'] = $goods_base['data']['spec_base']['barcode'];
                    $v['extends'] = $goods_base['data']['spec_base']['extends'];

                    // 商品价格容器赋值规格价格
                    $v['price_container']['price'] = $v['price'];
                    $v['price_container']['original_price'] = $v['original_price'];
                } else {
                    $v['is_invalid'] = 1;
                    $v['inventory'] = 0;
                    $v['total_price'] = 0;
                    $v['spec_base_id'] = 0;
                    $v['spec_buy_min_number'] = 0;
                    $v['spec_buy_max_number'] = 0;
                    $v['spec_weight'] = 0;
                    $v['spec_volume'] = 0;
                    $v['spec_coding'] = '';
                    $v['spec_barcode'] = '';
                    $v['extends'] = '';
                }

                // 最大限购数量、不能超过库存
                $v['buy_max_number'] = ($v['buy_max_number'] <= 0) ? $v['inventory']: $v['buy_max_number'];

                // 错误处理
                if(!isset($v['is_error']) || $v['is_error'] == 0)
                {
                    $v['is_error'] = 0;
                    $v['error_msg'] = '';
                }
                if($v['is_error'] == 0 && $v['is_invalid'] == 1)
                {
                    $v['is_error'] = 1;
                    $v['error_msg'] = MyLang('goods_already_invalid_title');
                }
                if($v['is_error'] == 0 && $v['inventory'] <= 0)
                {
                    $v['is_error'] = 1;
                    $v['error_msg'] = MyLang('goods_no_inventory_title');
                }
            }
        }
        return DataReturn(MyLang('operate_success'), 0, $data);
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCartListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 商品数据处理
            $ret = GoodsService::GoodsDataHandle($data, ['data_key_field'=>'goods_id']);
            $data = $ret['data'];

            // 是否公共读取
            $is_public = (isset($params['is_public']) && $params['is_public'] == 0) ? 0 : 1;
            $users = [];
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']) && $is_public == 0)
                {
                    if(!array_key_exists($v['user_id'], $users))
                    {
                        $users[$v['user_id']] = UserService::GetUserViewInfo($v['user_id']);
                    }
                    $v['user'] =  $users[$v['user_id']];
                }

                // 规格
                $v['spec_text'] = null;
                if(!empty($v['spec']))
                {
                    $v['spec'] = json_decode($v['spec'], true);
                    if(!empty($v['spec']) && is_array($v['spec']))
                    {
                        $v['spec_text'] = implode('，', array_filter(array_map(function($spec)
                        {
                            return (isset($spec['type']) && isset($spec['value'])) ? $spec['type'].':'.$spec['value'] : '';
                        }, $v['spec'])));
                    }
                } else {
                    $v['spec'] = null;
                }
            }
        }
        return $data;
    }

    /**
     * 购物车添加/更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCartSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否批量
        $ret = DataReturn(MyLang('operate_fail'), -1);
        if(!empty($params['goods_data']))
        {
            // 是否数组
            if(!is_array($params['goods_data']))
            {
                $params['goods_data'] = json_decode(base64_decode(urldecode($params['goods_data'])), true);
            }
            if(empty($params['goods_data']))
            {
                return DataReturn(MyLang('params_error_tips'), -1);
            }
            // 循环处理
            foreach($params['goods_data'] as $k=>$v)
            {
                $ret = self::GoodsCartSaveHandle(array_merge($v, ['user'=>$params['user']]));
                if($ret['code'] != 0)
                {
                    $ret['msg'] = ($k+1).'、'.$ret['msg'];
                    return $ret;
                }
            }
        } else {
            $ret = self::GoodsCartSaveHandle($params);
        }
        return $ret;
    }

    /**
     * 购物车添加/更新处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCartSaveHandle($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => MyLang('goods_id_error_tips'),
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => MyLang('common_service.goodscart.save_stock_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck($params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 获取商品
        $goods_id = intval($params['goods_id']);
        $goods = Db::name('Goods')->where(['id'=>$goods_id, 'is_shelves'=>1, 'is_delete_time'=>0])->find();
        if(empty($goods))
        {
            return DataReturn(MyLang('goods_no_exist_or_delete_error_tips'), -2);
        }

        // 无封面图片
        if(empty($goods['images']))
        {
            $goods['images'] = ResourcesService::AttachmentPathHandle(GoodsService::GoodsImagesCoverHandle($goods_id));
        }

        // 是否支持购物车操作
        $ret = GoodsService::IsGoodsSiteTypeConsistent($goods_id, $goods['site_type']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 规格处理
        $spec = BuyService::GoodsSpecificationsHandle($params);

        // 获取商品基础信息
        $spec_params = array_merge($params, [
            'id'    => $goods_id,
            'spec'  => $spec,
        ]);
        $goods_base = GoodsService::GoodsSpecDetail($spec_params);
        if($goods_base['code'] != 0)
        {
            return $goods_base;
        }

        // 是否存在规格
        if(!empty($spec))
        {
            // 获取商品规格图片
            $images = BuyService::BuyGoodsSpecImages($goods_id, $spec);
            if(!empty($images))
            {
                $goods['images'] = $images;
                $goods['images_old'] = ResourcesService::AttachmentPathViewHandle($images);
            }

            // 规格库存赋值
            $goods['inventory'] = $goods_base['data']['spec_base']['inventory'];
            // 规格最大限购
            $goods['buy_max_number'] = $goods_base['data']['spec_base']['buy_max_number'];
        }

        // 数量
        $stock = ($goods['buy_max_number'] > 0 && $params['stock'] > $goods['buy_max_number']) ? $goods['buy_max_number'] : $params['stock'];

        // 库存
        if($stock > $goods['inventory'])
        {
            return DataReturn(MyLang('common_service.goodscart.save_inventory_not_enough_tips'), -1);
        }

        // 添加购物车
        $data = [
            'user_id'       => $params['user']['id'],
            'goods_id'      => $goods_id,
            'title'         => $goods['title'],
            'images'        => $goods['images'],
            'original_price'=> $goods_base['data']['spec_base']['original_price'],
            'price'         => $goods_base['data']['spec_base']['price'],
            'stock'         => $stock,
            'spec'          => empty($spec) ? '' : json_encode($spec, JSON_UNESCAPED_UNICODE),
        ];

        // 存在则更新
        $where = ['user_id'=>$data['user_id'], 'goods_id'=>$data['goods_id'], 'spec'=>$data['spec']];
        $temp = Db::name('Cart')->where($where)->find();
        if(empty($temp))
        {
            $data['add_time'] = time();
            if(Db::name('Cart')->insertGetId($data) > 0)
            {
                return DataReturn(MyLang('join_success'), 0, self::UserGoodsCartTotal($params));
            }
        } else {
            // 购物车数量是否已经到达最大库存数量
            if($temp['stock'] >= $goods['inventory'])
            {
                return DataReturn(MyLang('common_service.goodscart.save_buy_max_error_tips').'('.$temp['stock'].'>'.$goods['inventory'].')', -1);
            }
            // 是否达到最大限购数量
            if($goods['buy_max_number'] > 0 && $temp['stock'] >= $goods['buy_max_number'])
            {
                return DataReturn(MyLang('common_service.goodscart.save_buy_max_error_tips').'('.$temp['stock'].'>'.$goods['buy_max_number'].')', -1);
            }
            // 加入数量、避免超过最大库存
            $data['stock'] += $temp['stock'];
            if($data['stock'] > $goods['inventory'])
            {
                $data['stock'] = $goods['inventory'];
            }
            if($goods['buy_max_number'] > 0 && $data['stock'] > $goods['buy_max_number'])
            {
                $data['stock'] = $goods['buy_max_number'];
            }
            $data['upd_time'] = time();
            if(Db::name('Cart')->where($where)->update($data) !== false)
            {
                return DataReturn(MyLang('join_success'), 0, self::UserGoodsCartTotal($params));
            }
        }
        return DataReturn(MyLang('join_fail'), -100);
    }

    /**
     * 购物车数量更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCartStock($params = [])
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
                'key_name'          => 'goods_id',
                'error_msg'         => MyLang('goods_id_error_tips'),
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => MyLang('common_service.goodscart.save_stock_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck($params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 获取购物车商品
        $params['where'] = [
            ['c.id', '=', intval($params['id'])],
            ['c.goods_id', '=', intval($params['goods_id'])],
        ];
        $cart = self::GoodsCartList($params);
        if($cart['code'] != 0)
        {
            return $cart;
        }
        if(empty($cart['data']) || empty($cart['data'][0]))
        {
            return DataReturn(MyLang('common_service.goodscart.save_stock_update_data_empty_tips'), -1);
        }
        $data = $cart['data'][0];
        // 是否存在错误
        if($data['is_error'] == 1)
        {
            return DataReturn($data['error_msg'], -1);
        }

        // 商品校验
        $data['stock'] = intval($params['stock']);
        $ret = BuyService::BuyGoodsCheck(['goods'=>[$data]]);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 更新数据
        $upd_data = [
            'stock'     => $data['stock'],
            'upd_time'  => time(),
        ];
        if(Db::name('Cart')->where(['id'=>$data['id']])->update($upd_data) !== false)
        {
            // 重新计算总价
            $data['total_price'] = PriceNumberFormat($data['stock']*$data['price']);

            // 购物车更新成功钩子
            $hook_name = 'plugins_service_cart_update_success';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data'          => &$data,
                'goods_id'      => $params['goods_id']
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            return DataReturn(MyLang('update_success'), 0, $data);
        }
        return DataReturn(MyLang('update_fail'), -100);
    }

    /**
     * 购物车汇总
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function GoodsCartTotal($where = [])
    {
        $data = Db::name('Cart')->where($where)->field('SUM(`stock`*`price`) AS total_price, SUM(`stock`) AS buy_number')->find();
        if(empty($data['buy_number']))
        {
            $data['buy_number'] = 0;
        }
        if(empty($data['total_price']))
        {
            $data['total_price'] = 0.00;
        }
        return $data;
    }

    /**
     * 用户购物车总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     * @return  [array]                  [总数超过99则返回 99+]
     */
    public static function UserGoodsCartTotal($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return ['buy_number'=>0, 'total_price'=>0.00];
        }

        // 条件
        $where = [
            ['user_id', '=', $params['user']['id']]
        ];

        // 购物车总数读取前钩子
        $hook_name = 'plugins_service_user_cart_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'where'         => &$where,
        ]);

        // 获取汇总
        $data = self::GoodsCartTotal($where);
        if($data['buy_number'] > 99)
        {
            $data['buy_number'] = '99+';
        }
        return $data;
    }

    /**
     * 商品购物车删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsCartDelete($params = [])
    {
        // 参数处理
        $ids = empty($params['ids']) ? (empty($params['id']) ? [] : $params['id']) : $params['ids'];
        if(empty($ids))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($ids))
        {
            $ids = explode(',', $ids);
        }

        // 条件
        $where = [
            ['id', 'in', $ids],
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        // 删除
        if(Db::name('Cart')->where($where)->delete())
        {
            return DataReturn(MyLang('delete_success'), 0, self::UserGoodsCartTotal($params));
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }
}
?>