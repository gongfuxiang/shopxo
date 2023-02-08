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
use app\service\OrderService;
use app\service\BuyService;
use app\service\MessageService;
use app\service\IntegralService;

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
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->select()->toArray();

        // 状态
        $sucs = 0;
        $fail = 0;
        if(!empty($order))
        {
            // 语言
            $lang = MyLang('common_service.crontab');
            $message = $lang['order_close_message'];
            $status_history = $lang['order_close_status_history'];

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
                        MessageService::MessageAdd($v['user_id'], $message['title'], $message['desc'], $message['type'], $v['id']);

                        // 订单状态日志
                        OrderService::OrderHistoryAdd($v['id'], $upd_data['status'], $v['status'], $status_history['desc'], 0, $status_history['type']);

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
        return DataReturn(MyLang('operate_success'), 0, ['sucs'=>$sucs, 'fail'=>$fail]);
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
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->select()->toArray();

        // 状态
        $sucs = 0;
        $fail = 0;
        if(!empty($order))
        {
            // 语言
            $lang = MyLang('common_service.crontab');
            $message = $lang['order_collect_message'];
            $status_history = $lang['order_collect_status_history'];

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
                        $ret = OrderService::GoodsSalesCountInc(['order_id'=>$v['id'], 'opt_type'=>'collect']);
                        if($ret['code'] == 0)
                        {
                            // 用户消息
                            MessageService::MessageAdd($v['user_id'], $message['title'], $message['desc'], $message['type'], $v['id']);

                            // 订单状态日志
                            OrderService::OrderHistoryAdd($v['id'], $upd_data['status'], $v['status'], $status_history['desc'], 0, $status_history['type']);

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
        return DataReturn(MyLang('operate_success'), 0, ['sucs'=>$sucs, 'fail'=>$fail]);
    }

    /**
     * 支付日志订单关闭
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-28
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PayLogOrderClose($params = [])
    {
        // 时长
        $time = time()-(intval(MyC('common_pay_log_order_close_limit_time', 30, true))*60);

        // 更新关闭
        $where = [
            ['add_time', '<', $time],
            ['status', '=', 0],
        ];
        $data = [
            'status'        => 2,
            'close_time'    => time(),
        ];
        $res = Db::name('PayLog')->where($where)->update($data);
        return DataReturn(MyLang('operate_success'), 0, $res);
    }

    /**
     * 商品积分赠送
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-28
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsGiveIntegral($params = [])
    {
        // 获取可赠送的日志数据
        $time = time()-(intval(MyC('common_goods_give_integral_limit_time', 21600, true))*60);
        $where = [
            ['add_time', '<', $time],
            ['status', '=', 0],
        ];
        $data = Db::name('GoodsGiveIntegralLog')->where($where)->field('id,status,user_id,integral')->select()->toArray();

        // 状态
        $sucs = 0;
        $fail = 0;
        if(!empty($data))
        {
            // 语言
            $lang = MyLang('common_service.crontab.goods_give_integral');

            // 更新状态
            $upd_data = [
                'status'    => 1,
                'upd_time'  => time(),
            ];
            foreach($data as $v)
            {
                // 开启事务
                Db::startTrans();
                if(Db::name('GoodsGiveIntegralLog')->where(['id'=>$v['id'], 'status'=>0])->update($upd_data))
                {
                    // 用户是否存在
                    $count = (int) Db::name('User')->where(['id'=>$v['user_id']])->count();
                    if($count > 0)
                    {
                        // 扣减用户锁定积分
                        if(!Db::name('User')->where(['id'=>$v['user_id']])->dec('locking_integral', $v['integral'])->update())
                        {
                            return DataReturn($lang['user_lock_integral_dec_fail'].'['.$v['id'].'-'.$v['user_id'].']', -2);
                        }

                        // 增加用户有效积分
                        $user_integral = Db::name('User')->where(['id'=>$v['user_id']])->value('integral');
                        if(!Db::name('User')->where(['id'=>$v['user_id']])->inc('integral', $v['integral'])->update())
                        {
                            return DataReturn($lang['user_valid_integral_inc_fail'].'['.$v['id'].'-'.$v['user_id'].']', -3);
                        }

                        // 积分日志
                        IntegralService::UserIntegralLogAdd($v['user_id'], $user_integral, $v['integral'], $lang['integral_log_desc'], 1);
                    }

                    // 提交事务
                    Db::commit();
                    $sucs++;
                    continue;
                }
                // 事务回滚
                Db::rollback();
                $fail++;
            }
        }
        return DataReturn(MyLang('operate_success'), 0, ['sucs'=>$sucs, 'fail'=>$fail]);
    }
}
?>