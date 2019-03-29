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
use app\service\GoodsService;
use app\service\UserService;
use app\service\ResourcesService;

/**
 * 购买服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BuyService
{
    /**
     * 购物车添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartAdd($params = [])
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
                'key_name'          => 'stock',
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => '购买数量有误',
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

        // 获取商品
        $goods_id = intval($params['goods_id']);
        $goods = Db::name('Goods')->where(['id'=>$goods_id, 'is_shelves'=>1, 'is_delete_time'=>0])->find();
        if(empty($goods))
        {
            return DataReturn('商品不存在或已删除', -2);
        }

        // 规格处理
        $spec = self::GoodsSpecificationsHandle($params);

        // 获取商品基础信息
        $goods_base = GoodsService::GoodsSpecDetail(['id'=>$goods_id, 'spec'=>$spec]);
        if($goods_base['code'] != 0)
        {
            return $goods_base;
        }

        // 添加购物车
        $data = [
            'user_id'       => $params['user']['id'],
            'goods_id'      => $goods_id,
            'title'         => $goods['title'],
            'images'        => $goods['images'],
            'original_price'=> $goods_base['data']['original_price'],
            'price'         => $goods_base['data']['price'],
            'stock'         => intval($params['stock']),
            'spec'          => empty($spec) ? '' : json_encode($spec),
        ];

        // 存在则更新
        $where = ['user_id'=>$data['user_id'], 'goods_id'=>$data['goods_id'], 'spec'=>$data['spec']];
        $temp = Db::name('Cart')->where($where)->find();
        if(empty($temp))
        {
            $data['add_time'] = time();
            if(Db::name('Cart')->insertGetId($data) > 0)
            {
                return DataReturn('加入成功', 0, self::UserCartTotal($params));
            }
        } else {
            $data['upd_time'] = time();
            $data['stock'] += $temp['stock'];
            if($data['stock'] > $goods['inventory'])
            {
                $data['stock'] = $goods['inventory'];
            }
            if(Db::name('Cart')->where($where)->update($data))
            {
                return DataReturn('加入成功', 0, self::UserCartTotal($params));
            }
        }
        
        return DataReturn('加入失败', -100);
    }

    /**
     * 商品规格解析
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function GoodsSpecificationsHandle($params = [])
    {
        $spec = '';
        if(!empty($params['spec']))
        {
            if(!is_array($params['spec']))
            {
                $spec = json_decode(htmlspecialchars_decode($params['spec']), true);
            } else {
                $spec = $params['spec'];
            }
        }
        return empty($spec) ? '' : $spec;
    }

    /**
     * 获取购物车列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartList($params = [])
    {
        // 请求参数
        $p = [
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

        $where = (!empty($params['where']) && is_array($params['where'])) ? $params['where'] : [];
        $where['c.user_id'] = $params['user']['id'];

        $field = 'c.*, g.title, g.images, g.inventory_unit, g.is_shelves, g.is_delete_time, g.buy_min_number, g.buy_max_number';
        $data = Db::name('Cart')->alias('c')->join(['__GOODS__'=>'g'], 'g.id=c.goods_id')->where($where)->field($field)->select();


        // 数据处理
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 规格
                $v['spec'] = empty($v['spec']) ? null : json_decode($v['spec'], true);

                // 获取商品基础信息
                $goods_base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>$v['spec']]);
                if($goods_base['code'] == 0)
                {
                    $v['inventory'] = $goods_base['data']['inventory'];
                    $v['price'] = (float) $goods_base['data']['price'];
                    $v['original_price'] = (float) $goods_base['data']['original_price'];
                    $v['spec_weight'] = $goods_base['data']['weight'];
                    $v['spec_coding'] = $goods_base['data']['coding'];
                    $v['spec_barcode'] = $goods_base['data']['barcode'];
                } else {
                    return $goods_base;
                }

                // 基础信息
                $v['goods_url'] = MyUrl('index/goods/index', ['id'=>$v['goods_id']]);
                $v['images_old'] = $v['images'];
                $v['images'] = ResourcesService::AttachmentPathViewHandle($v['images']);
                $v['total_price'] = $v['stock']* ((float) $v['price']);
                $v['buy_max_number'] = ($v['buy_max_number'] <= 0) ? $v['inventory']: $v['buy_max_number'];
            }
        }

        return DataReturn('操作成功', 0, $data);
    }

    /**
     * 购物车删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartDelete($params = [])
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

        // 查询用户状态是否正常
        $ret = UserService::UserStatusCheck('id', $params['user']['id']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 删除
        $where = [
            'id'        => explode(',', $params['id']),
            'user_id'   => $params['user']['id']
        ];
        if(Db::name('Cart')->where($where)->delete())
        {
            return DataReturn('删除成功', 0, self::UserCartTotal($params));
        }
        return DataReturn('删除失败或资源不存在', -100);
    }

    /**
     * 购物车数量保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartStock($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '数据id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'stock',
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => '购买数量有误',
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

        // 更新
        $where = [
            'id'        => intval($params['id']),
            'goods_id'  => intval($params['goods_id']),
            'user_id'   => intval($params['user']['id']),
        ];
        $data = [
            'stock'     => intval($params['stock']),
            'upd_time'  => time(),
        ];
        if(Db::name('Cart')->where($where)->update($data))
        {
            return DataReturn('更新成功', 0);
        }
        return DataReturn('更新失败', -100);
    }

    /**
     * 下订单 - 正常购买
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyGoods($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'stock',
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'min',
                'key_name'          => 'stock',
                'checked_data'      => 1,
                'error_msg'         => '购买数量有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'spec',
                'error_msg'         => '规格参数有误',
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

        // 获取商品
        $p = [
            'where' => [
                'id'                => intval($params['goods_id']),
                'is_delete_time'    => 0,
                'is_shelves'        => 1,
            ],
            'field' => 'id, id AS goods_id, title, images, inventory_unit, buy_min_number, buy_max_number',
        ];
        $ret = GoodsService::GoodsList($p);
        if(empty($ret['data'][0]))
        {
            return DataReturn('资源不存在或已被删除', -10);
        }

        // 规格
        $ret['data'][0]['spec'] = self::GoodsSpecificationsHandle($params);

        // 获取商品基础信息
        $goods_base = GoodsService::GoodsSpecDetail(['id'=>$ret['data'][0]['goods_id'], 'spec'=>$ret['data'][0]['spec']]);
        if($goods_base['code'] == 0)
        {
            $ret['data'][0]['inventory'] = $goods_base['data']['inventory'];
            $ret['data'][0]['price'] = (float) $goods_base['data']['price'];
            $ret['data'][0]['original_price'] = (float) $goods_base['data']['original_price'];
            $ret['data'][0]['spec_weight'] = $goods_base['data']['weight'];
            $ret['data'][0]['spec_coding'] = $goods_base['data']['coding'];
            $ret['data'][0]['spec_barcode'] = $goods_base['data']['barcode'];
        } else {
            return $goods_base;
        }

        // 数量/小计
        $ret['data'][0]['stock'] = $params['stock'];
        $ret['data'][0]['total_price'] = $params['stock']* ((float) $ret['data'][0]['price']);

        return DataReturn('操作成功', 0, $ret['data']);
    }

    /**
     * 下订单 - 购物车
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyCart($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ids',
                'error_msg'         => '购物车id有误',
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

        // 获取购物车数据
        $params['where'] = [
            'g.is_delete_time'  => 0,
            'g.is_shelves'      => 1,
            'c.id'              => explode(',', $params['ids']),
        ];
        return self::CartList($params);
    }

    /**
     * 下订单购物车删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-12T00:42:49+0800
     * @param   [array]          $params [输入参数]
     */
    public static function BuyCartDelete($params = [])
    {
        if(isset($params['buy_type']) && $params['buy_type'] == 'cart' && !empty($params['ids']))
        {
            Db::name('Cart')->where(['id'=>explode(',', $params['ids'])])->delete();
        }
    }

    /**
     * 根据购买类型获取商品列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyTypeGoodsList($params = [])
    {
        if(isset($params['buy_type']))
        {
            switch($params['buy_type'])
            {
                // 正常购买
                case 'goods' :
                    $ret = BuyService::BuyGoods($params);
                    break;

                // 购物车
                case 'cart' :
                    $ret = BuyService::BuyCart($params);
                    break;

                // 默认
                default :
                    $ret = DataReturn('参数有误', -1);
            }
        } else {
            $ret = DataReturn('参数有误', -1);
        }

        // 数据组装
        if($ret['code'] == 0)
        {
            // 商品数据
            $goods = $ret['data'];

            // 用户默认地址
            $address_params = [
                'user'  => $params['user'],
            ];
            if(!empty($params['address_id']))
            {
                $address_params['where'] = ['id' => $params['address_id']];
            }
            $address = UserService::UserDefaultAddress($address_params);

            // 商品/基础信息
            $total_price = empty($goods) ? 0 : array_sum(array_column($goods, 'total_price'));
            $base = [
                // 总价
                'total_price'           => $total_price,

                // 订单实际支付金额(已减去优惠金额, 已加上增加金额)
                'actual_price'          => $total_price,

                // 优惠金额
                'preferential_price'    => 0.00,

                // 增加金额
                'increase_price'        => 0.00,

                // 商品数量
                'goods_count'           => count($goods),

                // 规格重量总计
                'spec_weight_total'     => empty($goods) ? 0 : array_sum(array_map(function($v) {return $v['spec_weight']*$v['stock'];}, $goods)),

                // 购买总数
                'buy_count'             => empty($goods) ? 0 : array_sum(array_column($goods, 'stock')),

                // 默认地址
                'address'               => empty($address['data']) ? null : $address['data'],
            ];

            // 扩展展示数据
            // name 名称
            // price 金额
            // type 类型（0减少, 1增加）
            // tips 提示信息
            $extension_data = [
                // [
                //     'name'  => '感恩节9折',
                //     'price' => 23,
                //     'type'  => 0,
                //     'tips'  => '-￥23元'
                // ],
            ];

            // 返回数据
            $result = [
                'goods'             => $goods,
                'base'              => $base,
                'extension_data'    => $extension_data,
            ];

            // 生成订单数据处理钩子
            $hook_name = 'plugins_service_buy_handle';
            $ret = Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$result,
            ]);
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            return DataReturn('操作成功', 0, $result);
        }

        return $ret;
    }

    /**
     * 购买商品校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BuyGoodsCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods',
                'error_msg'         => '商品信息有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据校验
        foreach($params['goods'] as $v)
        {
            // 获取商品信息
            $goods = Db::name('Goods')->find($v['goods_id']);

            // 规格
            $goods_base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>isset($v['spec']) ? $v['spec'] : []]);
            if($goods_base['code'] == 0)
            {
                $goods['price'] = $goods_base['data']['price'];
                $goods['inventory'] = $goods_base['data']['inventory'];
            } else {
                return $goods_base;
            }

            // 基础判断
            if(empty($goods))
            {
                return DataReturn('['.$v['goods_id'].']商品不存在', -1);
            }
            if($goods['is_shelves'] != 1)
            {
                return DataReturn('['.$v['goods_id'].']商品已下架', -1);
            }
            if($v['stock'] > $goods['inventory'])
            {
                return DataReturn('['.$v['goods_id'].']购买数量超过商品库存数量['.$v['stock'].'>'.$goods['inventory'].']', -1);
            }
            if($goods['buy_min_number'] > 1 && $v['stock'] < $goods['buy_min_number'])
            {
                return DataReturn('['.$v['goods_id'].']低于商品起购数量['.$v['stock'].'<'.$goods['buy_min_number'].']', -1);
            }
            if($goods['buy_max_number'] > 1 && $v['stock'] > $goods['buy_max_number'])
            {
                return DataReturn('['.$v['goods_id'].']超过商品限购数量['.$v['stock'].'>'.$goods['buy_max_number'].']', -1);
            }
        }

        return DataReturn('操作成功', 0);
    }

    /**
     * 订单添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderAdd($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'address_id',
                'error_msg'         => '地址有误',
            ],
        ];
        if(MyC('common_order_is_booking', 0) != 1)
        {
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'payment_id',
                'error_msg'         => '支付方式有误',
            ];
        }
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

        // 清单商品
        $params['is_order_submit'] = 1;
        $buy = self::BuyTypeGoodsList($params);
        if(!isset($buy['code']) || $buy['code'] != 0)
        {
            return $buy;
        }
        $check = self::BuyGoodsCheck(['goods'=>$buy['data']['goods']]);
        if(!isset($check['code']) || $check['code'] != 0)
        {
            return $check;
        }

        // 收货地址
        if(empty($buy['data']['base']['address']))
        {
            return DataReturn('收货地址有误', -1);
        } else {
            $address = $buy['data']['base']['address'];
        }

        // 店铺
        $shop_id = 0;

        // 订单写入
        $order = [
            'order_no'              => date('YmdHis').GetNumberCode(6),
            'user_id'               => $params['user']['id'],
            'shop_id'               => $shop_id,
            'receive_address_id'    => $address['id'],
            'receive_name'          => $address['name'],
            'receive_tel'           => $address['tel'],
            'receive_province'      => $address['province'],
            'receive_city'          => $address['city'],
            'receive_county'        => $address['county'],
            'receive_address'       => $address['address'],
            'user_note'             => isset($params['user_note']) ? htmlentities($params['user_note']) : '',
            'status'                => (intval(MyC('common_order_is_booking', 0)) == 1) ? 0 : 1,
            'preferential_price'    => $buy['data']['base']['preferential_price'],
            'increase_price'        => $buy['data']['base']['increase_price'],
            'price'                 => $buy['data']['base']['total_price'],
            'total_price'           => $buy['data']['base']['actual_price'],
            'extension_data'        => empty($buy['data']['extension_data']) ? '' : json_encode($buy['data']['extension_data']),
            'payment_id'            => isset($params['payment_id']) ? intval($params['payment_id']) : 0,
            'add_time'              => time(),
        ];
        if($order['status'] == 1)
        {
            $order['confirm_time'] = time();
        }

        // 开始事务
        Db::startTrans();

        // 订单添加
        $order_id = Db::name('Order')->insertGetId($order);
        if($order_id > 0)
        {
            foreach($buy['data']['goods'] as $v)
            {
                $detail = [
                    'order_id'          => $order_id,
                    'user_id'           => $params['user']['id'],
                    'shop_id'           => $shop_id,
                    'goods_id'          => $v['goods_id'],
                    'title'             => $v['title'],
                    'images'            => $v['images_old'],
                    'original_price'    => $v['original_price'],
                    'price'             => $v['price'],
                    'spec'              => empty($v['spec']) ? '' : json_encode($v['spec']),
                    'spec_weight'       => empty($v['spec_weight']) ? 0.00 : (float) $v['spec_weight'],
                    'spec_coding'       => empty($v['spec_coding']) ? '' : $v['spec_coding'],
                    'spec_barcode'      => empty($v['spec_barcode']) ? '' : $v['spec_barcode'],
                    'buy_number'        => intval($v['stock']),
                    'add_time'          => time(),
                ];
                if(Db::name('OrderDetail')->insertGetId($detail) <= 0)
                {
                    Db::rollback();
                    return DataReturn('订单详情添加失败', -1);
                }
            }
        } else {
            Db::rollback();
            return DataReturn('订单添加失败', -1);
        }

        // 库存扣除
        if($order['status'] == 1)
        {
            $ret = self::OrderInventoryDeduct(['order_id'=>$order_id, 'order_data'=>$order]);
            if($ret['code'] != 0)
            {
                // 事务回滚
                Db::rollback();
                return DataReturn($ret['msg'], -10);
            }
        }
        

        // 订单提交成功
        Db::commit();

        // 删除购物车
        self::BuyCartDelete($params);

        // 返回信息
        $result = [
            'order'     => Db::name('Order')->find($order_id),
            'jump_url'  => MyUrl('index/order/index'),
        ];


        // 获取订单信息
        switch($order['status'])
        {
            // 预约成功
            case 0 :
                $msg = '预约成功';
                break;

            // 提交成功
            case 1 :
                $msg = '提交成功';
                $result['jump_url'] = MyUrl('index/order/pay', ['id'=>$order_id]);
                break;

            // 默认操作成功
            default :
                $msg = '操作成功';
        }

        return DataReturn($msg, 0, $result);
    }

    /**
     * 购物车总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function CartTotal($where = [])
    {
        return (int) Db::name('Cart')->where($where)->count();
    }

    /**
     * 用户购物车总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserCartTotal($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return 0;
        }
        return self::CartTotal(['user_id'=>$params['user']['id']]);
    }

    /**
     * 库存扣除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderInventoryDeduct($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否扣除库存
        $common_is_deduction_inventory = MyC('common_is_deduction_inventory', 0);
        if($common_is_deduction_inventory != 1)
        {
            return DataReturn('未开启扣除库存', 0);
        }

        // 扣除库存规则
        $common_deduction_inventory_rules = MyC('common_deduction_inventory_rules', 1);
        switch($common_deduction_inventory_rules)
        {
            // 订单确认成功
            case 0 :
                if($params['order_data']['status'] != 1)
                {
                    return DataReturn('当前订单状态未操作确认-不扣除库存['.$params['order_id'].']', 0);
                }
                break;

            // 订单支付成功
            case 1 :
                if($params['order_data']['status'] != 2)
                {
                    return DataReturn('当前订单状态未操作支付-不扣除库存['.$params['order_id'].']', 0);
                }
                break;

            // 订单发货
            case 2 :
                if($params['order_data']['status'] != 3)
                {
                    return DataReturn('当前订单状态未操作发货-不扣除库存['.$params['order_id'].']', 0);
                }
                break;
        }

        // 获取订单商品
        $order_detail = Db::name('OrderDetail')->field('goods_id,buy_number,spec')->where(['order_id'=>$params['order_id']])->select();
        if(!empty($order_detail))
        {
            foreach($order_detail as $v)
            {
                // 查看是否已扣除过库存,避免更改模式导致重复扣除
                $temp = Db::name('OrderGoodsInventoryLog')->where(['order_id'=>$params['order_id'], 'goods_id'=>$v['goods_id']])->find();
                if(empty($temp))
                {
                    $goods = Db::name('Goods')->field('is_deduction_inventory,inventory')->find($v['goods_id']);
                    if(isset($goods['is_deduction_inventory']) && $goods['is_deduction_inventory'] == 1)
                    {
                        // 扣除操作
                        if(!Db::name('Goods')->where(['id'=>$v['goods_id']])->setDec('inventory', $v['buy_number']))
                        {
                            return DataReturn('商品库存扣减失败['.$params['order_id'].'-'.$v['goods_id'].'('.$goods['inventory'].'-'.$v['buy_number'].')]', -10);
                        }

                        // 扣除规格库存
                        $spec = empty($v['spec']) ? '' : json_decode($v['spec'], true);
                        $base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>$spec]);
                        if($base['code'] == 0)
                        {
                            // 扣除规格操作
                            if(!Db::name('GoodsSpecBase')->where(['id'=>$base['data']['id'], 'goods_id'=>$v['goods_id']])->setDec('inventory', $v['buy_number']))
                            {
                                return DataReturn('规格库存扣减失败['.$params['order_id'].'-'.$v['goods_id'].'('.$goods['inventory'].'-'.$v['buy_number'].')]', -10);
                            }
                        } else {
                            return $base;
                        }

                        // 扣除日志添加
                        $log_data = [
                            'order_id'              => $params['order_id'],
                            'goods_id'              => $v['goods_id'],
                            'order_status'          => $params['order_data']['status'],
                            'original_inventory'    => $goods['inventory'],
                            'new_inventory'         => Db::name('Goods')->where(['id'=>$v['goods_id']])->value('inventory'),
                            'add_time'              => time(),
                        ];
                        if(Db::name('OrderGoodsInventoryLog')->insertGetId($log_data) <= 0)
                        {
                            return DataReturn('库存扣减日志添加失败['.$params['order_id'].'-'.$v['goods_id'].']', -100);
                        }
                    }
                }
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('没有需要扣除库存的数据', 0);
    }

    /**
     * 库存回滚
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderInventoryRollback($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'order_data',
                'error_msg'         => '订单更新数据有误',
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 订单状态
        if(!in_array($params['order_data']['status'], [5,6]))
        {
            return DataReturn('当前订单状态不允许回滚库存['.$params['order_id'].'-'.$params['order_data']['status'].']', 0);
        }

        // 获取订单商品
        $order_detail = Db::name('OrderDetail')->field('goods_id,buy_number,spec')->where(['order_id'=>$params['order_id']])->select();
        if(!empty($order_detail))
        {
            foreach($order_detail as $v)
            {
                // 查看是否已扣除过库存
                $temp = Db::name('OrderGoodsInventoryLog')->where(['order_id'=>$params['order_id'], 'goods_id'=>$v['goods_id'], 'is_rollback'=>0])->find();
                if(!empty($temp))
                {
                    // 回滚操作
                    if(!Db::name('Goods')->where(['id'=>$v['goods_id']])->setInc('inventory', $v['buy_number']))
                    {
                        return DataReturn('商品库存回滚失败['.$params['order_id'].'-'.$v['goods_id'].']', -10);
                    }

                    // 扣除规格库存
                    $spec = empty($v['spec']) ? '' : json_decode($v['spec'], true);
                    $base = GoodsService::GoodsSpecDetail(['id'=>$v['goods_id'], 'spec'=>$spec]);
                    if($base['code'] == 0)
                    {
                        // 扣除规格操作
                        if(!Db::name('GoodsSpecBase')->where(['id'=>$base['data']['id'], 'goods_id'=>$v['goods_id']])->setInc('inventory', $v['buy_number']))
                        {
                            return DataReturn('规格库存回滚失败['.$params['order_id'].'-'.$v['goods_id'].']', -10);
                        }
                    } else {
                        return $base;
                    }

                    // 回滚日志更新
                    $log_data = [
                        'is_rollback'   => 1,
                        'rollback_time' => time(),
                    ];
                    if(!Db::name('OrderGoodsInventoryLog')->where(['id'=>$temp['id']])->update($log_data))
                    {
                        return DataReturn('库存回滚日志更新失败['.$temp['id'].'-'.$params['order_id'].']', -100);
                    }
                }
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('没有需要回滚的数据', 0);
    }
}
?>