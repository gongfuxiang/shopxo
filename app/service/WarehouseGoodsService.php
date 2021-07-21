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
use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\WarehouseService;

/**
 * 仓库商品服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-11
 * @desc    description
 */
class WarehouseGoodsService
{
    /**
     * 数据列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function WarehouseGoodsList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);
        $data = Db::name('WarehouseGoods')->field($field)->where($where)->order($order_by)->limit($m, $n)->select()->toArray();
        return DataReturn('处理成功', 0, self::DataHandle($data));
    }

    /**
     * 数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [array]          $data [需要处理的数据]
     */
    public static function DataHandle($data)
    {
        if(!empty($data))
        {
            // 字段列表
            $keys = ArrayKeys($data);

            // 获取商品信息
            if(in_array('goods_id', $keys))
            {
                $goods_params = [
                    'where' => [
                        'id'                => array_unique(array_column($data, 'goods_id')),
                        'is_delete_time'    => 0,
                    ],
                    'field'  => 'id,title,images,price,min_price',
                    'm'      => 0,
                    'n'      => 0,
                ];
                $ret = GoodsService::GoodsList($goods_params);
                $goods = [];
                if(!empty($ret['data']))
                {
                    foreach($ret['data'] as $g)
                    {
                        $goods[$g['id']] = $g;
                    }
                }
            }

            // 仓库名称
            if(in_array('warehouse_id', $keys))
            {
                $warehouse = [];
                $w_ret = WarehouseService::WarehouseIdsAllList(array_column($data, 'warehouse_id'));
                if(!empty($w_ret['data']))
                {
                    foreach($w_ret['data'] as $wv)
                    {
                        $warehouse[$wv['id']] = $wv['name'];
                    }
                }
            }

            // 数据处理
            foreach($data as &$v)
            {
                // 用户
                if(isset($v['user_id']))
                {
                    $v['user'] = UserService::GetUserViewInfo($v['user_id']);
                }

                // 商品信息
                if(isset($v['goods_id']))
                {
                    $v['goods'] = isset($goods[$v['goods_id']]) ? $goods[$v['goods_id']] : [];
                }

                // 仓库
                if(isset($v['warehouse_id']))
                {
                    $v['warehouse_name'] = isset($warehouse[$v['warehouse_id']]) ? $warehouse[$v['warehouse_id']] : '';
                }

                // 时间
                if(isset($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }
            }
        }
        return $data;
    }

    /**
     * 总数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function WarehouseGoodsTotal($where = [])
    {
        return (int) Db::name('WarehouseGoods')->where($where)->count();
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-11
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function WarehouseGoodsDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn('商品id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 启动事务
        Db::startTrans();

        // 循环处理删除
        foreach($params['ids'] as $k=>$id)
        {
            // 位置
            $index = $k+1;

            // 获取数据
            $warehouse_goods = Db::name('WarehouseGoods')->where(['id'=>intval($id)])->find();
            if(empty($warehouse_goods))
            {
                return DataReturn('第'.$index.'条数据不存在', -1);
            }

            // 删除仓库商品和仓库商品规格数据
            $where = [
                'goods_id'      => $warehouse_goods['goods_id'],
                'warehouse_id'  => $warehouse_goods['warehouse_id'],
            ];
            if(Db::name('WarehouseGoods')->where($where)->delete() && Db::name('WarehouseGoodsSpec')->where($where)->delete() !== false)
            {
                // 同步商品库存
                $ret = self::GoodsSpecInventorySync($warehouse_goods['goods_id']);
                if($ret['code'] != 0)
                {
                    Db::rollback();
                    return $ret;
                }
            } else {
                Db::rollback();
                return DataReturn('第'.$index.'删除失败', -100);
            }
        }

        // 提交事务
        Db::commit();
        return DataReturn('删除成功', 0);
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-11
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function WarehouseGoodsStatusUpdate($params = [])
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
                'error_msg'         => '操作字段有误',
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

        // 获取数据
        $where = ['id'=>intval($params['id'])];
        $warehouse_goods = Db::name('WarehouseGoods')->where($where)->find();
        if(empty($warehouse_goods))
        {
            return DataReturn('数据不存在', -1);
        }

        // 启动事务
        Db::startTrans();

        // 数据更新
        if(Db::name('WarehouseGoods')->where($where)->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            // 状态更新
            if($params['field'] == 'is_enable')
            {
                // 同步商品库存
                $ret = self::GoodsSpecInventorySync($warehouse_goods['goods_id']);
                if($ret['code'] != 0)
                {
                    Db::rollback();
                    return $ret;
                }
            }

            // 提交事务
            Db::commit();
            return DataReturn('编辑成功', 0);
        }

        Db::rollback();
        return DataReturn('编辑失败', -100);
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsSearchList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'warehouse_id',
                'error_msg'         => '仓库id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 返回数据
        $result = [
            'page_total'    => 0,
            'page_size'     => 20,
            'page'          => max(1, isset($params['page']) ? intval($params['page']) : 1),
            'total'         => 0,
            'data'          => [],
        ];

        // 条件
        $where = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1]
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['g.title', 'like', '%'.$params['keywords'].'%'];
        }

        // 分类id
        if(!empty($params['category_id']))
        {
            $category_ids = GoodsService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $category_ids[] = $params['category_id'];
            $where[] = ['gci.category_id', 'in', $category_ids];
        }

        // 获取商品总数
        $result['total'] = GoodsService::CategoryGoodsTotal($where);

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 基础参数
            $field = 'g.id,g.title,g.images';
            $order_by = 'g.id desc';

            // 分页计算
            $m = intval(($result['page']-1)*$result['page_size']);
            $goods = GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>$m, 'n'=>$result['page_size'], 'field'=>$field, 'order_by'=>$order_by]);
            $result['data'] = $goods['data'];
            $result['page_total'] = ceil($result['total']/$result['page_size']);
            // 数据处理
            if(!empty($result['data']) && is_array($result['data']))
            {
                // 获取仓库商品
                $warehouse_goods_ids = Db::name('WarehouseGoods')->where(['warehouse_id'=>intval($params['warehouse_id']), 'goods_id'=>array_column($result['data'], 'id')])->column('goods_id');
                if(!empty($warehouse_goods_ids))
                {
                    foreach($result['data'] as &$v)
                    {
                        // 是否已添加
                        $v['is_exist'] = in_array($v['id'], $warehouse_goods_ids) ? 1 : 0;
                    }
                }
            }
        }
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 仓库商品添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-14
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function WarehouseGoodsAdd($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'warehouse_id',
                'error_msg'         => '仓库id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 不存在添加
        $where = [
            'goods_id'      => intval($params['goods_id']),
            'warehouse_id'  => intval($params['warehouse_id']),
        ];
        $warehouse_goods = Db::name('WarehouseGoods')->where($where)->find();
        if(empty($warehouse_goods))
        {
            $data = [
                'warehouse_id'  => intval($params['warehouse_id']),
                'goods_id'      => intval($params['goods_id']),
                'is_enable'     => 1,
                'add_time'      => time(),
            ];
            if(Db::name('WarehouseGoods')->insertGetId($data) <= 0)
            {
                return DataReturn('添加失败', -100);
            }
        }
        return DataReturn('添加成功', 0);
    }

    /**
     * 仓库商品删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-14
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function WarehouseGoodsDel($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'warehouse_id',
                'error_msg'         => '仓库id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 启动事务
        Db::startTrans();

        // 删除仓库商品和仓库商品规格数据
        $where = [
            'goods_id'      => intval($params['goods_id']),
            'warehouse_id'  => intval($params['warehouse_id']),
        ];
        if(Db::name('WarehouseGoods')->where($where)->delete() !== false && Db::name('WarehouseGoodsSpec')->where($where)->delete() !== false)
        {
            // 同步商品库存
            $ret = self::GoodsSpecInventorySync($params['goods_id']);
            if($ret['code'] != 0)
            {
                Db::rollback();
                return $ret;
            }

            // 提交事务
            Db::commit();
            return DataReturn('删除成功', 0);
        }

        Db::rollback();
        return DataReturn('删除失败', -100);
    }

    /**
     * 仓库商品库存数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-15
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function WarehouseGoodsInventoryData($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '数据id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取仓库商品
        $where = [
            'id'    => intval($params['id']),
        ];
        $warehouse_goods = Db::name('WarehouseGoods')->where($where)->find();
        if(empty($warehouse_goods))
        {
            return DataReturn('无相关商品数据', -1);
        }

        // 获取商品规格
        $res = GoodsService::GoodsSpecificationsActual($warehouse_goods['goods_id']);
        $inventory_spec = [];
        if(!empty($res['value']) && is_array($res['value']))
        {
            // 获取当前配置的库存
            $spec = array_column($res['value'], 'value');
            foreach($spec as $v)
            {
                $arr = explode(GoodsService::$goods_spec_to_string_separator, $v);
                $inventory_spec[] = [
                    'name'      => implode(' / ', $arr),
                    'spec'      => json_encode(self::GoodsSpecMuster($v, $res['title']), JSON_UNESCAPED_UNICODE),
                    'md5_key'   => md5(implode('', $arr)),
                    'inventory' => 0,
                ];
            }
        } else {
            // 没有规格则处理默认规格 default
            $str = 'default';
            $inventory_spec[] = [
                'name'      => '默认规格',
                'spec'      => $str,
                'md5_key'   => md5($str),
                'inventory' => 0,
            ];
        }

        // 获取库存
        $keys = array_column($inventory_spec, 'md5_key');
        $where = [
            'md5_key'               => $keys,
            'warehouse_goods_id'    => $warehouse_goods['id'],
            'warehouse_id'          => $warehouse_goods['warehouse_id'],
            'goods_id'              => $warehouse_goods['goods_id'],
        ];
        $inventory_data = Db::name('WarehouseGoodsSpec')->where($where)->column('inventory', 'md5_key');
        if(!empty($inventory_data))
        {
            foreach($inventory_spec as &$v)
            {
                if(array_key_exists($v['md5_key'], $inventory_data))
                {
                    $v['inventory'] = $inventory_data[$v['md5_key']];
                }
            }
        }

        // 返回数据
        $result = [
            'data'  => $warehouse_goods,
            'spec'  => $inventory_spec,
        ];
        return DataReturn('success', 0, $result);
    }

    /**
     * 规格值组合
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [string]         $spec_str   [规格字符串，英文逗号分割]
     * @param   [array]          $spec_title [规格类型名称]
     */
    public static function GoodsSpecMuster($spec_str, $spec_title)
    {
        $result = [];
        $arr = explode(GoodsService::$goods_spec_to_string_separator, $spec_str);
        if(count($arr) == count($spec_title))
        {
            foreach($arr as $k=>$v)
            {
                $result[] = [
                    'type'  => $spec_title[$k],
                    'value' => $v,
                ];
            }
        }
        return $result;
    }

    /**
     * 仓库商品库存保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-15
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function WarehouseGoodsInventorySave($params = [])
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
                'key_name'          => 'specifications_inventory',
                'error_msg'         => '库存数据有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'specifications_inventory',
                'error_msg'         => '库存数据有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'specifications_md5_key',
                'error_msg'         => '库存唯一值有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'specifications_md5_key',
                'error_msg'         => '库存唯一值有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'specifications_spec',
                'error_msg'         => '库存规格有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'specifications_spec',
                'error_msg'         => '库存规格有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取仓库商品
        $where = [
            'id'    => intval($params['id']),
        ];
        $warehouse_goods = Db::name('WarehouseGoods')->where($where)->find();
        if(empty($warehouse_goods))
        {
            return DataReturn('无相关商品数据', -1);
        }

        // 数据组装
        $inventory = [];
        $spec_value = [];
        $md5_key = [];
        $data = [];
        foreach($params['specifications_spec'] as $k=>$v)
        {
            // 规格值,md5key,库存 必须存在
            if(!empty($v) && array_key_exists($k, $params['specifications_md5_key']) && array_key_exists($k, $params['specifications_inventory']))
            {
                $inventory = intval($params['specifications_inventory'][$k]);
                if($inventory > 0)
                {
                    $data[] = [
                        'warehouse_goods_id'    => $warehouse_goods['id'],
                        'warehouse_id'          => $warehouse_goods['warehouse_id'],
                        'goods_id'              => $warehouse_goods['goods_id'],
                        'md5_key'               => $params['specifications_md5_key'][$k],
                        'spec'                  => htmlspecialchars_decode($v),
                        'inventory'             => $inventory,
                        'add_time'              => time(),
                    ];
                }
            }
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
        
            // 删除原始数据
            $where = [
                'warehouse_id'          => $warehouse_goods['warehouse_id'],
                'goods_id'              => $warehouse_goods['goods_id'],
            ];
            Db::name('WarehouseGoodsSpec')->where($where)->delete();

            // 添加数据
            if(!empty($data))
            {
                if(Db::name('WarehouseGoodsSpec')->insertAll($data) < count($data))
                {
                    throw new \Exception('规格库存添加失败');
                }
            }

            // 仓库商品更新
            if(!Db::name('WarehouseGoods')->where(['id'=>$warehouse_goods['id']])->update([
                'inventory' => array_sum(array_column($data, 'inventory')),
                'upd_time'  => time(),
            ]))
            {
                throw new \Exception('库存商品更新失败');
            }

            // 同步商品库存
            $ret = self::GoodsSpecInventorySync($warehouse_goods['goods_id']);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 完成
            Db::commit();
            return DataReturn('操作成功', 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 商品改变规格仓库商品库存同步
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsSpecChangeInventorySync($goods_id)
    {
        // 获取商品实际规格
        $res = GoodsService::GoodsSpecificationsActual($goods_id);
        if(empty($res['value']))
        {
            // 没有规格则读取默认规格数据
            $res['value'][] = [
                'base_id'   => Db::name('GoodsSpecBase')->where(['goods_id'=>$goods_id])->value('id'),
                'value'     => 'default',
            ];
        }

        // 获取商品仓库
        $warehouse_goods = Db::name('WarehouseGoods')->where(['goods_id'=>$goods_id])->select()->toArray();
        if(!empty($warehouse_goods))
        {
            // 循环根据仓库处理库存
            foreach($warehouse_goods as $wg)
            {
                $data = [];
                foreach($res['value'] as $v)
                {
                    $md5_key = md5(empty($v['value']) ? 'default' : str_replace(GoodsService::$goods_spec_to_string_separator, '', $v['value']));
                    $inventory = (int) Db::name('WarehouseGoodsSpec')->where([
                        'warehouse_id'  => $wg['warehouse_id'],
                        'goods_id'      => $wg['goods_id'],
                        'md5_key'       => $md5_key,
                    ])->value('inventory');
                    $data[] = [
                        'warehouse_goods_id'    => $wg['id'],
                        'warehouse_id'          => $wg['warehouse_id'],
                        'goods_id'              => $wg['goods_id'],
                        'md5_key'               => $md5_key,
                        'spec'                  => json_encode(self::GoodsSpecMuster($v['value'], $res['title']), JSON_UNESCAPED_UNICODE),
                        'inventory'             => $inventory,
                        'add_time'              => time(),
                    ];
                }

                // 删除原始数据
                $where = [
                    'warehouse_id'  => $wg['warehouse_id'],
                    'goods_id'      => $wg['goods_id'],
                ];
                Db::name('WarehouseGoodsSpec')->where($where)->delete();

                // 添加数据
                if(Db::name('WarehouseGoodsSpec')->insertAll($data) < count($data))
                {
                    return DataReturn('规格库存添加失败', -1);
                }

                // 仓库商品更新
                if(!Db::name('WarehouseGoods')->where(['id'=>$wg['id']])->update([
                    'inventory' => array_sum(array_column($data, 'inventory')),
                    'upd_time'  => time(),
                ]))
                {
                    return DataReturn('库存商品更新失败', -1);
                }
            }
        }

        // 商品库存同步
        return self::GoodsSpecInventorySync($goods_id);
    }

    /**
     * 商品规格库存同步
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     */
    public static function GoodsSpecInventorySync($goods_id)
    {
        // 获取商品实际规格
        $res = GoodsService::GoodsSpecificationsActual($goods_id);
        if(empty($res['value']))
        {
            // 没有规格则读取默认规格数据
            $res['value'][] = [
                'base_id'   => Db::name('GoodsSpecBase')->where(['goods_id'=>$goods_id])->value('id'),
                'value'     => 'default',
            ];
        }
        $inventory_total = 0;

        // 商品规格库存
        foreach($res['value'] as $v)
        {
            $inventory = self::WarehouseGoodsSpecInventory($goods_id, str_replace(GoodsService::$goods_spec_to_string_separator, '', $v['value']));
            if(Db::name('GoodsSpecBase')->where(['id'=>$v['base_id'], 'goods_id'=>$goods_id])->update(['inventory'=>$inventory]) === false)
            {
                return DataReturn('商品规格库存同步失败', -20);
            }
            $inventory_total += $inventory;
        }

        // 商品库存
        $data = [
            'inventory' => $inventory_total,
            'upd_time'  => time(),
        ];
        if(Db::name('Goods')->where(['id'=>$goods_id])->update($data) === false)
        {
            return DataReturn('商品库存同步失败', -21);
        }

        // 商品仓库库存修改钩子
        $hook_name = 'plugins_service_warehouse_goods_inventory_sync';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'goods_id'      => $goods_id
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        return DataReturn('更新成功', 0);
    }

    /**
     * 根据商品id和规格获取库存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-16
     * @desc    description
     * @param   [int]          $goods_id [商品id]
     * @param   [string]       $spec_str [规格值，如 套餐一白色16G（无则 default）]
     */
    public static function WarehouseGoodsSpecInventory($goods_id, $spec_str = 'default')
    {
        // 获取商品仓库
        // 仓库商品是否有效
        $warehouse_ids = Db::name('WarehouseGoods')->where(['goods_id'=>$goods_id, 'is_enable'=>1])->column('warehouse_id');
        if(empty($warehouse_ids))
        {
            return 0;
        }

        // 检查仓库是否启用
        $warehouse_ids = Db::name('Warehouse')->where(['id'=>$warehouse_ids, 'is_enable'=>1, 'is_delete_time'=>0])->column('id');
        if(empty($warehouse_ids))
        {
            return 0;
        }

        // 获取商品规格库存
        $where = [
            'warehouse_id'  => $warehouse_ids,
            'goods_id'      => $goods_id,
            'md5_key'       => md5(empty($spec_str) ? 'default' : $spec_str),
        ];
        return (int) Db::name('WarehouseGoodsSpec')->where($where)->sum('inventory');
    }

    /**
     * 仓库库存扣减
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-02
     * @desc    description
     * @param   [int]          $order_id     [订单id]
     * @param   [int]          $goods_id     [商品id]
     * @param   [string]       $spec         [商品规格]
     * @param   [int]          $buy_number   [扣除库存数量]
     */
    public static function WarehouseGoodsInventoryDeduct($order_id, $goods_id, $spec, $buy_number)
    {
        // 获取仓库id
        $warehouse_id = Db::name('Order')->where(['id'=>$order_id])->value('warehouse_id');
        if(empty($warehouse_id))
        {
            return DataReturn('订单仓库id有误', -1);
        }

        // 规格key，空则默认default
        $md5_key = 'default';
        if(!empty($spec))
        {
            if(!is_array($spec))
            {
                $spec = json_decode($spec, true);
            }
            $md5_key = implode('', array_column($spec, 'value'));
        }
        $md5_key = md5($md5_key);

        // 扣除仓库商品规格库存
        $where = ['warehouse_id'=>$warehouse_id, 'goods_id'=>$goods_id, 'md5_key'=>$md5_key];
        $inventory = (int) Db::name('WarehouseGoodsSpec')->where($where)->value('inventory');
        if($inventory < $buy_number)
        {
            return DataReturn('仓库商品规格库存不足['.$warehouse_id.'-'.$goods_id.'('.$inventory.'<'.$buy_number.')]', -11);
        }
        if(!Db::name('WarehouseGoodsSpec')->where($where)->dec('inventory', $buy_number)->update())
        {
            return DataReturn('仓库商品规格库存扣减失败['.$warehouse_id.'-'.$goods_id.'('.$buy_number.')]', -11);
        }

        // 扣除仓库商品库存
        $where = ['warehouse_id'=>$warehouse_id, 'goods_id'=>$goods_id];
        $inventory = Db::name('WarehouseGoods')->where($where)->value('inventory');
        if($inventory < $buy_number)
        {
            return DataReturn('仓库商品库存不足['.$warehouse_id.'-'.$goods_id.'('.$inventory.'<'.$buy_number.')]', -11);
        }
        if(!Db::name('WarehouseGoods')->where($where)->dec('inventory', $buy_number)->update())
        {
            return DataReturn('仓库商品库存扣减失败['.$warehouse_id.'-'.$goods_id.'('.$buy_number.')]', -12);
        }

        // 商品库存扣除钩子
        $hook_name = 'plugins_service_warehouse_goods_inventory_deduct';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_id'      => $order_id,
            'goods_id'      => $goods_id,
            'spec'          => $spec,
            'buy_number'    => $buy_number,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        return DataReturn('扣除成功', 0);
    }

    /**
     * 仓库库存回滚
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-08-02
     * @desc    description
     * @param   [int]          $order_id     [订单id]
     * @param   [int]          $goods_id     [商品id]
     * @param   [string]       $spec         [商品规格]
     * @param   [int]          $buy_number   [扣除库存数量]
     */
    public static function WarehouseGoodsInventoryRollback($order_id, $goods_id, $spec, $buy_number)
    {
        // 获取仓库id
        $warehouse_id = Db::name('Order')->where(['id'=>$order_id])->value('warehouse_id');
        if(empty($warehouse_id))
        {
            return DataReturn('订单仓库id有误', -1);
        }

        // 规格key，空则默认default
        $md5_key = 'default';
        if(!empty($spec))
        {
            if(!is_array($spec))
            {
                $spec = json_decode($spec, true);
            }
            $md5_key = implode('', array_column($spec, 'value'));
        }
        $md5_key = md5($md5_key);

        // 扣除仓库商品规格库存
        $where = ['warehouse_id'=>$warehouse_id, 'goods_id'=>$goods_id, 'md5_key'=>$md5_key];
        if(!Db::name('WarehouseGoodsSpec')->where($where)->inc('inventory', $buy_number)->update())
        {
            return DataReturn('仓库商品规格库存回滚失败['.$warehouse_id.'-'.$goods_id.'('.$buy_number.')]', -11);
        }

        // 扣除仓库商品库存
        $where = ['warehouse_id'=>$warehouse_id, 'goods_id'=>$goods_id];
        if(!Db::name('WarehouseGoods')->where($where)->inc('inventory', $buy_number)->update())
        {
            return DataReturn('仓库商品库存回滚失败['.$warehouse_id.'-'.$goods_id.'('.$buy_number.')]', -12);
        }

        // 商品库存回滚钩子
        $hook_name = 'plugins_service_warehouse_goods_inventory_rollback';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_id'      => $order_id,
            'goods_id'      => $goods_id,
            'spec'          => $spec,
            'buy_number'    => $buy_number,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        return DataReturn('回滚成功', 0);
    }
}
?>