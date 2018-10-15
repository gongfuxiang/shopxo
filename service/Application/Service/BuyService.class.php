<?php

namespace Service;

use Service\GoodsService;
use Service\UserService;

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
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取商品
        $goods_id = intval($params['goods_id']);
        $goods = M('Goods')->where(['id'=>$goods_id, 'is_shelves'=>1, 'is_delete_time'=>0])->find();
        if(empty($goods))
        {
            return DataReturn('商品不存在或已删除', -2);
        }

        // 属性处理
        $attr = self::GoodsAttrParsing($params);

        // 添加购物车
        $data = [
            'user_id'       => $params['user']['id'],
            'goods_id'      => $goods_id,
            'title'         => $goods['title'],
            'images'        => $goods['images'],
            'original_price'=> $goods['original_price'],
            'price'         => $goods['price'],
            'stock'         => intval($params['stock']),
            'attribute'     => count($attr) == 0 ? '' : json_encode($attr),
        ];

        // 存在则更新
        $m = M('Cart');
        $where = ['user_id'=>$data['user_id'], 'goods_id'=>$data['goods_id'], 'attribute'=>$data['attribute']];
        $temp = $m->where($where)->find();
        if(empty($temp))
        {
            $data['add_time'] = time();
            if($m->add($data) > 0)
            {
                return DataReturn(L('common_join_success'), 0, self::UserCartTotal($params));
            }
        } else {
            $data['upd_time'] = time();
            $data['stock'] += $temp['stock'];
            if($data['stock'] > $goods['inventory'])
            {
                $data['stock'] = $goods['inventory'];
            }
            if($m->where($where)->save($data))
            {
                return DataReturn(L('common_join_success'), 0, self::UserCartTotal($params));
            }
        }
        
        return DataReturn(L('common_join_error'), -100);
    }

    /**
     * 商品属性解析
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function GoodsAttrParsing($params = [])
    {
        $data = [];
        if(!empty($params['attr']) && is_array($params['attr']) && !empty($params['goods_id']))
        {
            foreach($params['attr'] as $k=>$v)
            {
                $attr_type_name = M('GoodsAttributeType')->where(['goods_id'=>$params['goods_id'], 'id'=>$k])->getField('name');
                $attr_name = M('GoodsAttribute')->where(['goods_id'=>$params['goods_id'], 'id'=>$v])->getField('name');
                if(!empty($attr_type_name) && !empty($attr_name))
                {
                    $data[] = [
                        'attr_type_id'     => $k,
                        'attr_type_name'   => $attr_type_name,
                        'attr_id'          => $v,
                        'attr_name'        => $attr_name,
                    ];
                }
            }
        }
        return $data;
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $where = (!empty($params['where']) && is_array($params['where'])) ? $params['where'] : [];
        $where['c.user_id'] = $params['user']['id'];

        $field = 'c.*, g.title, g.images, g.original_price, g.price, g.inventory, g.inventory_unit, g.is_shelves, g.is_delete_time, g.buy_min_number, g.buy_max_number';
        $data = M('Cart')->alias('c')->join(' __GOODS__ AS g ON g.id=c.goods_id')->where($where)->field($field)->select();
        if(empty($data) || !is_array($data))
        {
            return DataReturn(L('common_not_data_tips'), -100);
        }

        // 数据处理
        $images_host = C('IMAGE_HOST');
        foreach($data as &$v)
        {
            $v['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$v['goods_id']]);
            $v['images_old'] = $v['images'];
            $v['images'] = empty($v['images']) ? null : $images_host.$v['images'];
            $v['attribute'] = empty($v['attribute']) ? null : json_decode($v['attribute'], true);
            $v['total_price'] = $v['stock']*$v['price'];
        }

        return DataReturn(L('common_operation_success'), 0, $data);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 删除
        $where = [
            'id'        => ['in', explode(',', $params['id'])],
            'user_id'   => $params['user']['id']
        ];
        if(M('Cart')->where($where)->delete())
        {
            return DataReturn(L('common_operation_delete_success'), 0, self::UserCartTotal($params));
        }
        return DataReturn(L('common_operation_delete_error'), -100);
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
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
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
        if(M('Cart')->where($where)->save($data))
        {
            return DataReturn(L('common_operation_update_success'), 0);
        }
        return DataReturn(L('common_operation_update_error'), -100);
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
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'attr',
                'error_msg'         => '属性参数有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取商品
        $p = [
            'where' => [
                'g.id'                => intval($params['goods_id']),
                'g.is_delete_time'    => 0,
                'g.is_shelves'        => 1,
            ],
            'field' => 'g.id, g.id AS goods_id, g.title, g.images, g.original_price, g.price, g.inventory, g.inventory_unit, g.buy_min_number, g.buy_max_number',
        ];
        $goods = GoodsService::GoodsList($p);
        if(empty($goods[0]))
        {
            return DataReturn(L('common_data_no_exist_error'), -10);
        }

        // 数量/小计
        $goods[0]['stock'] = $params['stock'];
        $goods[0]['total_price'] = $params['stock']*$goods[0]['price'];

        // 属性
        if(!empty($params['attr']))
        {
            $params['attr'] = json_decode($params['attr'], true);
        }
        $goods[0]['attribute'] = self::GoodsAttrParsing($params);

        return DataReturn(L('common_operation_success'), 0, $goods);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取购物车数据
        $params['where'] = [
            'g.is_delete_time'  => 0,
            'g.is_shelves'      => 1,
            'c.id'              => ['in', explode(',', $params['ids'])],
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
            M('Cart')->where(['id'=>['in', explode(',', $params['ids'])]])->delete();
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
            $ret = DataReturn('参数有误', -1);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据校验
        $total_price = 0;
        $m = M('Goods');
        $attr_type_m = M('GoodsAttributeType');
        $attr_m = M('GoodsAttribute');
        foreach($params['goods'] as $v)
        {
            $goods = $m->find($v['goods_id']);

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

            // 属性
            if(!empty($v['attribute']))
            {
                foreach($v['attribute'] as $vs)
                {
                    // 属性类型
                    $attr_type = $attr_type_m->find($vs['attr_type_id']);
                    if(empty($attr_type))
                    {
                        return DataReturn('['.$v['goods_id'].']商品属性类型有误['.$vs['attr_type_id'].']', -1);
                    }

                    // 具体属性
                    $attr_content = $attr_m->find($vs['attr_id']);
                    if(empty($attr_content))
                    {
                        return DataReturn('['.$v['goods_id'].']商品属性有误-'.$vs['attr_id'].']', -1);
                    }
                }
            }

            // 总价
            $total_price += $goods['price']*$v['stock'];
            $result[] = $goods;
        }

        $data = [
            'total_price'   => $total_price,
        ];
        return DataReturn(L('common_operation_success'), 0, $data);
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
            [
                'checked_type'      => 'empty',
                'key_name'          => 'express_id',
                'error_msg'         => '快递有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'payment_id',
                'error_msg'         => '支付方式有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 清单商品
        $goods = self::BuyTypeGoodsList($params);
        if(!isset($goods['code']) || $goods['code'] != 0)
        {
            return $goods;
        }
        $check = self::BuyGoodsCheck(['goods'=>$goods['data']]);
        if(!isset($check['code']) || $check['code'] != 0)
        {
            return $check;
        }

        // 用户地址
        $address = UserService::UserAddressRow(array_merge($params, ['id'=>$params['address_id']]));
        if(empty($address))
        {
            return $address;
        }

        // 优惠金额
        $preferential_price = 0.00;

        // 店铺
        $shop_id = 0;

        // 订单写入
        $order = [
            'order_no'              => date('YmdHis').GetNumberCode(6),
            'user_id'               => $params['user']['id'],
            'shop_id'               => $shop_id,
            'receive_address_id'    => $address['data']['id'],
            'receive_name'          => $address['data']['name'],
            'receive_tel'           => $address['data']['tel'],
            'receive_province'      => $address['data']['province'],
            'receive_city'          => $address['data']['city'],
            'receive_county'        => $address['data']['county'],
            'receive_address'       => $address['data']['address'],
            'user_note'             => I('user_note', null, null, $params),
            'status'                => 1,
            'preferential_price'    => $preferential_price,
            'price'                 => $check['data']['total_price'],
            'total_price'           => $check['data']['total_price']-$preferential_price,
            'express_id'            => intval($params['express_id']),
            'payment_id'            => intval($params['payment_id']),
            'add_time'              => time(),
            'confirm_time'          => time(),
        ];

        // 开始事务
        $m = M('Order');
        $m->startTrans();

        // 订单添加
        $order_id = $m->add($order);
        if($order_id > 0)
        {
            $detail_m = M('OrderDetail');
            foreach($goods['data'] as $v)
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
                    'attribute'         => empty($v['attribute']) ? '' : json_encode($v['attribute']),
                    'buy_number'        => $v['stock'],
                    'add_time'          => time(),
                ];
                if($detail_m->add($detail) <= 0)
                {
                    $m->rollback();
                    return DataReturn('订单详情添加失败', -1);
                }
            }
        } else {
            $m->rollback();
            return DataReturn('订单添加失败', -1);
        }

        // 订单提交成功
        $m->commit();

        // 删除购物车
        self::BuyCartDelete($params);

        // 获取订单信息
        switch($order['status'])
        {
            // 预约成功
            case 0 :
                $msg = L('common_booking_success');
                break;

            // 提交成功
            case 1 :
                $msg = L('common_submit_success');
                break;

            // 默认操作成功
            default :
                $msg = L('common_operation_success');
        }

        $result = [
            'order'     => $m->find($order_id),
            'pay_url'   => U('Home/Order/Pay', ['id'=>$order_id]),
        ];
        return DataReturn($msg, 0, $result);
    }

    /**
     * 购物车总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function CartTotal($params = [])
    {
        return (int) M('Cart')->where($where)->count();
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return 0;
        }
        return self::CartTotal(['user_id'=>$params['user']['id']]);
    }
}
?>