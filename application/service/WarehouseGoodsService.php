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
use app\service\GoodsService;
use app\service\UserService;

/**
 * 仓库商品服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-07
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
        $order_by = 'id desc';
        $data = Db::name('WarehouseGoods')->field($field)->where($where)->order($order_by)->limit($m, $n)->select();
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
                $warehouse = Db::name('Warehouse')->where(['id'=>array_column($data, 'warehouse_id')])->column('name', 'id');
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
        return DataReturn('处理成功', 0, $data);
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
     * @date    2018-12-18
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

        // 删除操作
        if(Db::name('WarehouseGoods')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败', -100);
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-07
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

        // 数据更新
        if(Db::name('WarehouseGoods')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败', -100);
    }
}
?>