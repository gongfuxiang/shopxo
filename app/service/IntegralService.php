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
use app\service\MessageService;
use app\service\UserService;

/**
 * 积分服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class IntegralService
{
    /**
     * 用户积分更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-18T16:51:12+0800
     * @param    [int]                   $user_id           [用户id]
     * @param    [int]                   $original_integral [原始积分]
     * @param    [int]                   $operation_integral[操作积分]
     * @param    [string]                $msg               [操作原因]
     * @param    [int]                   $type              [操作类型（0减少, 1增加）]
     * @param    [int]                   $operation_id      [操作人员id]
     * @return   [boolean]                                  [成功true, 失败false]
     */
    public static function UserIntegralUpdate($user_id, $original_integral, $operation_integral, $msg = '', $type = 0, $operation_id = 0)
    {
        // 是否传递了原始积分
        if($original_integral === null)
        {
            $original_integral = Db::name('User')->where(['id'=>$user_id])->value('integral');
        }

        // 用户积分更新
        if($type == 1)
        {
            if(!Db::name('User')->where(['id'=>$user_id])->inc('integral', $operation_integral)->update())
            {
                return DataReturn(MyLang('common_service.integral.integral_inc_fail_tips'), -1);
            }
        } else {
            if(!Db::name('User')->where(['id'=>$user_id])->dec('integral', $operation_integral)->update())
            {
                return DataReturn(MyLang('common_service.integral.integral_dec_fail_tips'), -1);
            }
        }

        // 积分日志
        if(self::UserIntegralLogAdd($user_id, $original_integral, $operation_integral, $msg, $type, $operation_id))
        {
            return DataReturn('success', 0);
        }
        return DataReturn(MyLang('common_service.integral.integral_log_add_fail_tips'), -1);
    }

    /**
     * 用户积分日志添加
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-18T16:51:12+0800
     * @param    [int]                   $user_id           [用户id]
     * @param    [int]                   $original_integral [原始积分]
     * @param    [int]                   $operation_integral[操作积分]
     * @param    [string]                $msg               [操作原因]
     * @param    [int]                   $type              [操作类型（0减少, 1增加）]
     * @param    [int]                   $operation_id      [操作人员id]
     * @return   [boolean]                                  [成功true, 失败false]
     */
    public static function UserIntegralLogAdd($user_id, $original_integral, $operation_integral, $msg = '', $type = 0, $operation_id = 0)
    {
        // 积分日志数据
        $data = [
            'user_id'               => intval($user_id),
            'original_integral'     => $original_integral,
            'operation_integral'    => intval($operation_integral),
            'msg'                   => $msg,
            'type'                  => intval($type),
            'operation_id'          => intval($operation_id),
            'add_time'              => time(),
        ];
        $data['new_integral'] = ($data['type'] == 1) ? $data['original_integral']+$data['operation_integral'] : $data['original_integral']-$data['operation_integral'];
        $log_id = Db::name('UserIntegralLog')->insertGetId($data);
        if($log_id > 0)
        {
            $lang = MyLang('common_service.integral.add_message_data');
            $type_msg = MyConst('common_integral_log_type_list')[$type]['name'];
            $detail = $msg.$lang['title'].$type_msg.$operation_integral;
            MessageService::MessageAdd($user_id, $lang['desc'], $detail, $lang['title'], $log_id);
            return true;
        }
        return false;
    }

    /**
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function IntegralLogList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据列表
        $data = Db::name('UserIntegralLog')->where($where)->field($field)->limit($m, $n)->order($order_by)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::IntegralLogListHandle($data, $params));
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
    public static function IntegralLogListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            $integral_log_type_list = MyConst('common_integral_log_type_list');
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']))
                {
                    if(isset($params['is_public']) && $params['is_public'] == 0)
                    {
                        $v['user'] = UserService::GetUserViewInfo($v['user_id']);
                    }
                }

                // 操作类型
                $v['type_text'] = $integral_log_type_list[$v['type']]['name'];

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
            }
        }
        return $data;
    }

    /**
     * 总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function IntegralLogTotal($where = [])
    {
        return (int) Db::name('UserIntegralLog')->where($where)->count();
    }

    /**
     * 前端积分列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserIntegralLogListWhere($params = [])
    {
        // 条件初始化
        $where = [];

        // 用户id
        if(!empty($params['user']))
        {
            $where[] = ['user_id', '=', $params['user']['id']];
        }

        if(!empty($params['keywords']))
        {
            $where[] = ['msg', 'like', '%'.$params['keywords'] . '%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            if(isset($params['type']) && $params['type'] > -1)
            {
                $where[] = ['type', '=', intval($params['type'])];
            }

            // 时间
            if(!empty($params['time_start']))
            {
                $where[] = ['add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['add_time', '<', strtotime($params['time_end'])];
            }
        }

        return $where;
    }

    /**
     * 订单商品积分赠送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderGoodsIntegralGiving($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => MyLang('order_id_error_tips'),
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 订单
        $order = Db::name('Order')->field('id,user_id,status')->find(intval($params['order_id']));
        if(empty($order))
        {
            return DataReturn(MyLang('common_service.integral.order_empty_exit_tips'), 0);
        }
        if(!in_array($order['status'], [4]))
        {
            return DataReturn(MyLang('common_service.integral.order_status_not_allow_exit_tips'), 0);
        }

        // 获取用户信息
        $user = Db::name('User')->field('id')->find($order['user_id']);
        if(empty($user))
        {
            return DataReturn(MyLang('common_service.integral.user_empty_exit_tips'), 0);
        }

        // 获取订单商品
        $order_detail = Db::name('OrderDetail')->where(['order_id'=>$params['order_id']])->field('id,order_id,goods_id,user_id,total_price')->select()->toArray();
        if(!empty($order_detail))
        {
            // 获取赠送积分的商品
            $where = [
                ['id', 'in', array_column($order_detail, 'goods_id')],
                ['give_integral', '>', 0],
            ];
            $goods_give = Db::name('Goods')->where($where)->column('give_integral', 'id');
            if(!empty($goods_give))
            {
                // 循环发放
                foreach($order_detail as $dv)
                {
                    if(array_key_exists($dv['goods_id'], $goods_give))
                    {
                        $give_rate = $goods_give[$dv['goods_id']];
                        if($give_rate > 0 && $give_rate <= 100)
                        {
                            // 实际赠送积分
                            $give_integral = intval(($give_rate/100)*$dv['total_price']);
                            if($give_integral >= 1)
                            {
                                // 是否已存在日志记录
                                $where = [
                                    ['order_id', '=', $dv['order_id']],
                                    ['order_detail_id', '=', $dv['id']],
                                    ['goods_id', '=', $dv['goods_id']],
                                ];
                                $temp = Db::name('GoodsGiveIntegralLog')->where($where)->count();
                                if(empty($temp))
                                {
                                    // 增加用户锁定积分
                                    if(!Db::name('User')->where(['id'=>$user['id']])->inc('locking_integral', $give_integral)->update())
                                    {
                                        return DataReturn(MyLang('common_service.integral.integral_give_fail_tips').'['.$params['order_id'].'-'.$dv['goods_id'].']', -10);
                                    }

                                    // 积分赠送日志添加
                                    $log_data = [
                                        'order_id'          => $dv['order_id'],
                                        'order_detail_id'   => $dv['id'],
                                        'goods_id'          => $dv['goods_id'],
                                        'user_id'           => $dv['user_id'],
                                        'status'            => 0,
                                        'rate'              => $give_rate,
                                        'integral'          => $give_integral,
                                        'add_time'          => time(),
                                    ];
                                    if(Db::name('GoodsGiveIntegralLog')->insertGetId($log_data) <= 0)
                                    {
                                        return DataReturn(MyLang('common_service.integral.integral_give_log_add_fail_tips').'['.$params['order_id'].'-'.$dv['goods_id'].']', -11);
                                    }
                                }
                            }
                        }
                    }
                }
                return DataReturn(MyLang('operate_success'), 0);
            }
        }
        return DataReturn(MyLang('common_service.integral.integral_no_operate_data_tips'), 0);
    }

    /**
     * 订单商品积分释放
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderGoodsIntegralRollback($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_detail_id',
                'error_msg'         => MyLang('order_detail_id_error_tips'),
            ]
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 订单是否存在完成状态（订单赠送积分的条件是完成赠送）
        $order_status_history = Db::name('OrderStatusHistory')->where(['order_id'=>intval($params['order_id'])])->column('new_status');
        if(empty($order_status_history) || !in_array(4, $order_status_history))
        {
            return DataReturn(MyLang('common_service.integral.order_not_success_exit_tips'), 0);
        }

        // 订单详情
        $order_detail = Db::name('OrderDetail')->field('id,user_id,order_id,goods_id,price,total_price,refund_price,returned_quantity')->find(intval($params['order_detail_id']));
        if(empty($order_detail))
        {
            return DataReturn(MyLang('common_service.integral.order_detail_empty_exit_tips'), 0);
        }

        // 获取用户信息
        $user = Db::name('User')->where(['id'=>$order_detail['user_id']])->field('id')->find();
        if(empty($user))
        {
            return DataReturn(MyLang('common_service.integral.user_empty_exit_tips'), 0);
        }

        // 获取日志
        $where = [
            ['order_id', '=', $order_detail['order_id']],
            ['order_detail_id', '=', $order_detail['id']],
            ['goods_id', '=', $order_detail['goods_id']],
            ['user_id', '=', $order_detail['user_id']],
            ['status', '=', 0],
        ];
        $info = Db::name('GoodsGiveIntegralLog')->where($where)->find();
        if(empty($info))
        {
            return DataReturn(MyLang('common_service.integral.integral_data_empty_exit_tips'), 0);
        }

        // 存在退款金额则使用退款金额
        // 未存在退款金额则判断是否存在退款数量
        // 存在退款数量则使用退款数量*单价金额计算（防止订单退款金额为空仅存在退款数量）
        $refund_integral = 0;
        if($order_detail['refund_price'] > 0)
        {
            $refund_integral = intval(($info['rate']/100)*$order_detail['refund_price']);
        } else {
            if($order_detail['returned_quantity'] > 0)
            {
                $refund_integral = intval(($info['rate']/100)*($order_detail['price']*$order_detail['returned_quantity']));
            }
        }
        if($refund_integral >= 1)
        {
            // 扣减用户锁定积分
            if(!Db::name('User')->where(['id'=>$user['id']])->dec('locking_integral', $refund_integral)->update())
            {
                return DataReturn(MyLang('common_service.integral.lock_integral_dec_fail_tips').'['.$user['id'].'-'.$order_detail['order_id'].'-'.$order_detail['goods_id'].']', -10);
            }

            // 扣减日志积分
            if(!Db::name('GoodsGiveIntegralLog')->where(['id'=>$info['id']])->dec('integral', $refund_integral)->update())
            {
                return DataReturn(MyLang('common_service.integral.integral_log_dec_fail_tips').'['.$info['id'].'-'.$order_detail['order_id'].'-'.$order_detail['goods_id'].']', -11);
            }

            // 剩余0积分则关闭
            if(Db::name('GoodsGiveIntegralLog')->where(['id'=>$info['id']])->value('integral') <= 0)
            {
                if(!Db::name('GoodsGiveIntegralLog')->where(['id'=>$info['id']])->update(['status'=>2, 'upd_time'=>time()]))
                {
                    return DataReturn(MyLang('common_service.integral.integral_log_close_fail_tips').'['.$info['id'].'-'.$order_detail['order_id'].'-'.$order_detail['goods_id'].']', -12);
                }
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 用户积分
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-01
     * @desc    description
     * @param   [int]          $user_id [用户 id]
     */
    public static function UserIntegral($user_id)
    {
        return Db::name('User')->where(['id'=>$user_id])->field('integral,locking_integral')->find();
    }
}
?>