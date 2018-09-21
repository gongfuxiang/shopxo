<?php

namespace Service;

use Service\GoodsService;

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
                return DataReturn(L('common_join_success'), 0);
            }
        } else {
            $data['upd_time'] = time();
            $data['stock'] += $temp['stock'];
            if($m->where($where)->save($data))
            {
                return DataReturn(L('common_join_success'), 0);
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

        $field = 'c.*, g.title, g.images, g.original_price, g.price, g.inventory, g.inventory_unit, g.is_shelves, g.is_delete_time';
        $data = M('Cart')->alias('c')->join(' __GOODS__ AS g ON g.id=c.goods_id')->where($where)->field($field)->select();
        if(!empty($data) && is_array($data))
        {
            $images_host = C('IMAGE_HOST');
            foreach($data as &$v)
            {
                $v['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$v['goods_id']]);
                $v['images'] = empty($v['images']) ? null : $images_host.$v['images'];
                $v['attribute'] = empty($v['attribute']) ? null : json_decode($v['attribute'], true);
                $v['total_price'] = $v['stock']*$v['price'];
            }
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
            return DataReturn(L('common_operation_delete_success'), 0);
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
            'field' => 'g.id, g.id AS goods_id, g.title, g.images, g.original_price, g.price, g.inventory, g.inventory_unit',
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
}
?>