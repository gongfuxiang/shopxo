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
use app\service\WarehouseService;

/**
 * 订单拆分服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-18
 * @desc    description
 */
class OrderSplitService
{
    /**
     * 订单拆分入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Run($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods',
                'error_msg'         => '商品为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'goods',
                'error_msg'         => '商品有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 商品仓库集合
        $data = self::GoodsWarehouseAggregate($params['goods']);
        return DataReturn('操作成功', 0, $data);
    }

    /**
     * 商品仓库集合
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-18
     * @desc    description
     * @param   [array]          $data [商品数据]
     */
    public static function GoodsWarehouseAggregate($data)
    {
        // 仓库组扩展展示数据
        // name 名称
        // price 金额
        // type 类型（0减少, 1增加）
        // tips 提示信息
        // business 业务类型（内容格式不限）
        // ext 扩展数据（内容格式不限）
        // $extension_data = [
            // [
            //     'name'       => '感恩节9折',
            //     'price'      => 23,
            //     'type'       => 0,
            //     'tips'       => '-￥23元',
            //     'business'   => null,
            //     'ext'        => null,
            // ],
        // ];

        // 数据分组
        $result = [];
        foreach($data as $v)
        {
            // 不存在规格则使用默认
            $spec = empty($v['spec']) ? [['type' => '默认规格','value' => 'default']] : $v['spec'];

            // 获取商品库存
            $where = [
                'wgs.goods_id'      => $v['goods_id'],
                'wgs.md5_key'       => md5(implode('', array_column($spec, 'value'))),
                'wg.is_enable'      => 1,
                'w.is_enable'       => 1,
                'w.is_delete_time'  => 0,
            ];
            $field = 'distinct w.id,w.name,w.alias,w.lng,w.lat,w.province,w.city,w.county,w.address,wgs.inventory,w.is_default,w.level';
            $warehouse = Db::name('WarehouseGoodsSpec')->alias('wgs')->join(['__WAREHOUSE_GOODS__'=>'wg'], 'wgs.warehouse_id=wg.warehouse_id')->join(['__WAREHOUSE__'=>'w'], 'wg.warehouse_id=w.id')->where($where)->field($field)->order('w.level desc,w.is_default desc,wgs.inventory desc')->select();

            // 默认仓库
            $warehouse_default = [];

            // 商品仓库分组
            if(!empty($warehouse))
            {
                foreach($warehouse as $w)
                {
                    // 是否还存在未分配的数量
                    if($v['stock'] > 0)
                    {
                        // 赋值数据
                        $temp_v = $v;

                        // 购买数量计算
                        if($temp_v['stock'] > $w['inventory'])
                        {
                            $temp_v['stock'] = $w['inventory'];
                        }

                        // 总价计算
                        $temp_v['total_price'] = $temp_v['price']*$temp_v['stock'];

                        // 减除数量
                        $v['stock'] -= $w['inventory'];

                        // 是否第一次赋值
                        if(!array_key_exists($w['id'], $result))
                        {
                            // 仓库
                            $warehouse_handle = WarehouseService::DataHandle([$w]);
                            $result[$w['id']] = $warehouse_handle[0];
                            $result[$w['id']]['extension_data'] = [
                                [
                                    'name'       => '感恩节9折',
                                    'price'      => 23,
                                    'type'       => 0,
                                    'tips'       => '-￥23元',
                                    'business'   => null,
                                    'ext'        => null,
                                ],
                            ];
                            $result[$w['id']]['goods_items'] = [];
                            unset($result[$w['id']]['is_default'], $result[$w['id']]['level'], $result[$w['id']]['inventory']);

                            // 订单基础信息
                            $result[$w['id']]['items_total_price'] = 0;
                            $result[$w['id']]['items_stock_total'] = 0;
                        }

                        // 商品归属到仓库下
                        $result[$w['id']]['goods_items'] = array_merge($result[$w['id']]['goods_items'], [$temp_v]);

                        // 基础信息
                        $result[$w['id']]['items_total_price'] += $temp_v['total_price'];
                        $result[$w['id']]['items_stock_total'] += $temp_v['stock'];
                    } else {
                        break;
                    }
                }
            } else {
                // 未获取到仓库则使用默认仓库
                if(empty($warehouse_default))
                {
                    $warehouse_default = Db::name('Warehouse')->where(['is_default'=>1, 'is_enable'=>1, 'is_delete_time'=>0])->field('id,name,alias,lng,lat,province,city,county,address')->find();
                }
                if(!empty($warehouse_default))
                {
                    if(!array_key_exists($warehouse_default['id'], $result))
                    {
                        // 仓库
                        $warehouse_handle = WarehouseService::DataHandle([$warehouse_default]);
                        $result[$warehouse_default['id']] = $warehouse_handle[0];
                        $result[$warehouse_default['id']]['extension_data'] = [];
                        $result[$warehouse_default['id']]['goods_items'] = [];

                        // 订单基础信息
                        $result[$warehouse_default['id']]['items_total_price'] = 0;
                        $result[$warehouse_default['id']]['items_stock_total'] = 0;
                    }

                    // 商品归属到仓库下
                    $result[$warehouse_default['id']]['goods_items'][] = $v;

                    // 基础信息
                    $result[$warehouse_default['id']]['items_total_price'] += $temp_v['total_price'];
                    $result[$warehouse_default['id']]['items_stock_total'] += $temp_v['stock'];
                }
            }
        }
        return array_values($result);
    }
}
?>