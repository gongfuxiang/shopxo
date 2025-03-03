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
use app\service\WarehouseService;
use app\service\GoodsService;

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
                'checked_type'      => 'is_array',
                'key_name'          => 'goods',
                'error_msg'         => MyLang('goods_empty_or_format_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 商品仓库集合
        $warehouse_goods = self::GoodsWarehouseAggregate($params);
        // 存在多个订单，但是订单模式非（快递、同城），则设置为快递模式
        if(count($warehouse_goods) > 1 && !in_array($params['site_model'], [0,1]))
        {
            $params['site_model'] = 0;
            $params['common_site_type'] = 0;
        }

        // 分组商品基础处理
        $data = self::GroupGoodsBaseHandle($warehouse_goods, $params);

        // 生成订单仓库分组商品数据处理钩子
        $hook_name = 'plugins_service_buy_group_goods_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 根据扩展数据重新计算金额
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 是否存在扩展数据
                if(!empty($v['order_base']['extension_data']))
                {
                    // 扩展数据金额计算
                    $ext = self::ExtensionDataPriceHandle($v['order_base']['extension_data']);

                    // 增加/减少
                    $v['order_base']['increase_price'] = PriceBeautify(PriceNumberFormat($v['order_base']['increase_price']+$ext['inc']));
                    $v['order_base']['preferential_price'] = PriceBeautify(PriceNumberFormat($v['order_base']['preferential_price']+$ext['dec']));

                    // 实际金额/总额处理
                    $v['order_base']['actual_price'] = PriceBeautify(PriceNumberFormat(($v['order_base']['actual_price']+$ext['inc'])-$ext['dec']));
                    $v['order_base']['total_price'] = PriceBeautify(PriceNumberFormat($v['order_base']['total_price']));

                    // 防止实际金额负数
                    if($v['order_base']['actual_price'] < 0)
                    {
                        $v['order_base']['actual_price'] = 0;
                    }
                }
            }
        }

        // 返回数据
        return DataReturn(MyLang('operate_success'), 0, $data);
    }

    /**
     * 扩展数据解析金额
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-23
     * @desc    description
     * @param   [array]          $data [扩展数据]
     */
    public static function ExtensionDataPriceHandle($data)
    {
        $inc = 0;
        $dec = 0;
        if(!empty($data))
        {
            if(!is_array($data))
            {
                $data = json_decode($data, true);
            }
            foreach($data as $v)
            {
                if(isset($v['type']) && isset($v['price']) && $v['price'] > 0)
                {
                    switch($v['type'])
                    {
                        // 减
                        case 0 :
                            $dec += $v['price'];
                            break;

                        // 加
                        case 1 :
                            $inc += $v['price'];
                            break;
                    }
                }
            }
        }
        return [
            'inc'   => $inc,
            'dec'   => $dec,
        ];
    }

    /**
     * 分组商品基础处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-23
     * @desc    description
     * @param   [array]          $data      [分组商品]
     * @param   [array]          $params    [输入参数]
     */
    public static function GroupGoodsBaseHandle($data, $params)
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 当前仓库的商品总价
                $total_price = PriceNumberFormat(array_sum(array_column($v['goods_items'], 'total_price')));

                // 订单基础信息
                $v['order_base'] = [
                    // 总价
                    'total_price'                       => PriceBeautify($total_price),
                    
                    // 订单实际支付金额(已减去优惠金额, 已加上增加金额)
                    'actual_price'                      => PriceBeautify($total_price),
                    
                    // 优惠金额
                    'preferential_price'                => 0.00,
                    
                    // 增加金额
                    'increase_price'                    => 0.00,
                    
                    // 商品总数
                    'goods_count'                       => count($v['goods_items']),
                    
                    // 规格重量总计
                    'spec_weight_total'                 => array_sum(array_map(function($v) {return $v['spec_weight']*$v['stock'];}, $v['goods_items'])),
                    
                    // 规格体积总计
                    'spec_volume_total'                 => array_sum(array_map(function($v) {return $v['spec_volume']*$v['stock'];}, $v['goods_items'])),
                    
                    // 购买总数
                    'buy_count'                         => array_sum(array_column($v['goods_items'], 'stock')),
                    
                    // 默认地址
                    'address'                           => $params['address'],
                    
                    // 自提地址列表
                    'extraction_address'                => $params['extraction_address'],
                    
                    // 当前使用的站点模式
                    'site_model'                        => $params['site_model'],
                    
                    // 公共站点模式
                    'common_site_type'                  => $params['common_site_type'],
                    
                    // 商品售价是否展示
                    'goods_price_show_status'           => (array_sum(array_filter(array_column($v['goods_items'], 'show_field_price_status'))) > 0) ? 1 : 0,

                    // 商品原价是否展示
                    'goods_original_price_show_status'  => (array_sum(array_filter(array_column($v['goods_items'], 'show_field_original_price_status'))) > 0) ? 1 : 0,

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
                    'extension_data'                    => [],
                ];
            }
        }
        return $data;
    }

    /**
     * 商品仓库集合
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsWarehouseAggregate($params)
    {
        // 默认仓库
        $warehouse_default = [];

        // 数据分组
        $result = [];
        foreach($params['goods'] as $v)
        {
            // 不存在规格则使用默认
            $spec = empty($v['spec']) ? [['type' => GoodsService::GoodsSpecDefaultName(),'value' => 'default']] : $v['spec'];

            // 获取商品库存、仓库商品壳仓库商品规格都必须等于当前商品
            $where = [
                ['wgs.goods_id', '=', $v['goods_id']],
                ['wgs.md5_key', '=', md5(implode('', array_column($spec, 'value')))],
                ['wgs.inventory', '>', 0],
                ['wg.goods_id', '=', $v['goods_id']],
                ['wg.is_enable', '=', 1],
                ['w.is_enable', '=', 1],
                ['w.is_delete_time', '=', 0],
            ];
            $field = 'distinct w.id,w.name,w.alias,w.lng,w.lat,w.province,w.city,w.county,w.address,wgs.inventory,w.is_default,w.level';
            $warehouse = Db::name('WarehouseGoodsSpec')->alias('wgs')->join('warehouse_goods wg', 'wgs.warehouse_id=wg.warehouse_id')->join('warehouse w', 'wg.warehouse_id=w.id')->where($where)->field($field)->order('w.level desc,w.is_default desc,wgs.inventory desc')->select()->toArray();

            // 商品仓库分配仓库组合钩子
            $hook_name = 'plugins_service_buy_group_goods_warehouse_handle';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'spec'          => $spec,
                'where'         => $where,
                'data'          => &$warehouse,
            ]);

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
                        if($temp_v['stock'] > $w['inventory'] && $w['inventory'] > 0)
                        {
                            $temp_v['stock'] = $w['inventory'];
                        }

                        // 总价计算
                        $temp_v['total_price'] = PriceBeautify(PriceNumberFormat($temp_v['price']*$temp_v['stock']));

                        // 减除数量
                        $v['stock'] -= $w['inventory'];

                        // 是否第一次赋值
                        if(!array_key_exists($w['id'], $result))
                        {
                            // 仓库
                            $warehouse_handle = WarehouseService::WarehouseListHandle([$w]);
                            $result[$w['id']] = $warehouse_handle[0];
                            $result[$w['id']]['goods_items'] = [];
                            unset($result[$w['id']]['is_default'], $result[$w['id']]['level'], $result[$w['id']]['inventory']);
                        }

                        // 商品归属到仓库下
                        $result[$w['id']]['goods_items'] = array_merge($result[$w['id']]['goods_items'], [$temp_v]);
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

                // 商品仓库分配默认仓库组合钩子
                $hook_name = 'plugins_service_buy_group_goods_default_warehouse_handle';
                MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => $params,
                    'data'          => &$warehouse_default,
                ]);

                // 存在默认仓库则继续分配
                if(!empty($warehouse_default))
                {
                    if(!array_key_exists($warehouse_default['id'], $result))
                    {
                        // 仓库
                        $warehouse_handle = WarehouseService::WarehouseListHandle([$warehouse_default]);
                        $result[$warehouse_default['id']] = $warehouse_handle[0];
                        $result[$warehouse_default['id']]['goods_items'] = [];
                    }

                    // 商品归属到仓库下
                    $result[$warehouse_default['id']]['goods_items'][] = $v;
                }
            }
        }
        return array_values($result);
    }
}
?>