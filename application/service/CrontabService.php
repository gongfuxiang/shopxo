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
use app\service\OrderService;
use app\service\BuyService;
use app\service\MessageService;

/**
 * 定时任务服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2019-08-18T17:19:33+0800
 */
class CrontabService
{
    /**
     * 订单自动关闭
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T17:22:29+0800
     * @desc     从订单创建时间开始计算周期
     * @param    [array]       $params [输入参数]
     */
    public static function OrderClose($params = [])
    {
        // 获取可关闭订单
        $time = time()-(intval(MyC('common_order_close_limit_time', 30, true))*60);
        $where = [
            ['add_time', '<', $time],
            ['status', '=', 1],
        ];
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->select();

        // 状态
        $sucs = 0;
        $fail = 0;
        if(!empty($order))
        {
            // 订单更新数据
            $upd_data = [
                'status'        => 6,
                'close_time'    => time(),
                'upd_time'      => time(),
            ];
            foreach($order as $v)
            {
                // 开启事务
                Db::startTrans();
                if(Db::name('Order')->where(['id'=>$v['id'], 'status'=>1])->update($upd_data))
                {
                    // 库存回滚
                    $ret = BuyService::OrderInventoryRollback(['order_id'=>$v['id'], 'order_data'=>$upd_data]);
                    if($ret['code'] == 0)
                    {
                        // 用户消息
                        MessageService::MessageAdd($v['user_id'], '订单关闭', '订单超时关闭', 1, $v['id']);

                        // 订单状态日志
                        OrderService::OrderHistoryAdd($v['id'], $upd_data['status'], $v['status'], '超时关闭', 0, '系统');

                        // 提交事务
                        Db::commit();
                        $sucs++;
                        continue;
                    }
                }
                // 事务回滚
                Db::rollback();
                $fail++;
            }
        }
        return DataReturn('操作成功', 0, ['sucs'=>$sucs, 'fail'=>$fail]);
    }

    /**
     * 订单自动收货
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T17:24:05+0800
     * @desc     从发货时间开始计算周期
     * @param    [array]       $params [输入参数]
     */
    public static function OrderSuccess($params = [])
    {
        // 获取可收货订单
        $time = time()-(intval(MyC('common_order_success_limit_time', 21600, true))*60);
        $where = [
            ['delivery_time', '<', $time],
            ['status', '=', 3],
        ];
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->select();

        // 状态
        $sucs = 0;
        $fail = 0;
        if(!empty($order))
        {
            // 更新订单状态
            $upd_data = [
                'status'        => 4,
                'collect_time'  => time(),
                'upd_time'      => time(),
            ];
            foreach($order as $v)
            {
                // 开启事务
                Db::startTrans();
                if(Db::name('Order')->where(['id'=>$v['id'], 'status'=>3])->update($upd_data))
                {
                    // 订单商品积分赠送
                    $ret = IntegralService::OrderGoodsIntegralGiving(['order_id'=>$v['id']]);
                    if($ret['code'] == 0)
                    {
                        // 订单商品销量增加
                        $ret = OrderService::GoodsSalesCountInc(['order_id'=>$v['id']]);
                        if($ret['code'] == 0)
                        {
                            // 用户消息
                            MessageService::MessageAdd($v['user_id'], '订单收货', '订单自动收货成功', 1, $v['id']);

                            // 订单状态日志
                            OrderService::OrderHistoryAdd($v['id'], $upd_data['status'], $v['status'], '自动收货', 0, '系统');

                            // 提交事务
                            Db::commit();
                            $sucs++;
                            continue;
                        }
                    }
                }
                // 事务回滚
                Db::rollback();
                $fail++;
            }
        }
        return DataReturn('操作成功', 0, ['sucs'=>$sucs, 'fail'=>$fail]);
    }
}
?>