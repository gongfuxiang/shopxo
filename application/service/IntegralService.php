<?php

namespace app\service;

use app\service\MessageService;

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
     * [UserIntegralLogAdd 用户积分日志添加]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-18T16:51:12+0800
     * @param    [int]                   $user_id           [用户id]
     * @param    [int]                   $original_integral [原始积分]
     * @param    [int]                   $new_integral      [最新积分]
     * @param    [string]                $msg               [操作原因]
     * @param    [int]                   $type              [操作类型（0减少, 1增加）]
     * @param    [int]                   $operation_id      [操作人员id]
     * @return   [boolean]                                  [成功true, 失败false]
     */
    public static function UserIntegralLogAdd($user_id, $original_integral, $new_integral, $msg = '', $type = 0, $operation_id = 0)
    {
        $data = array(
            'user_id'           => intval($user_id),
            'original_integral' => intval($original_integral),
            'new_integral'      => intval($new_integral),
            'msg'               => $msg,
            'type'              => intval($type),
            'operation_id'      => intval($operation_id),
            'add_time'          => time(),
        );
        if(db('UserIntegralLog')->insertGetId($data) > 0)
        {
            $type_msg = lang('common_integral_log_type_list')[$type]['name'];
            $integral = ($data['type'] == 0) ? $data['original_integral']-$data['new_integral'] : $data['new_integral']-$data['original_integral'];
            $detail = $msg.'积分'.$type_msg.$integral;
            MessageService::MessageAdd($user_id, '积分变动', $detail);
            return true;
        }
        return false;
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
     * 用户积分日志总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function UserIntegralLogTotal($where = [])
    {
        return (int) db('UserIntegralLog')->where($where)->count();
    }

    /**
     * 积分日志列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserIntegralLogList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'where',
                'error_msg'         => '条件不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'where',
                'error_msg'         => '条件格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_start',
                'error_msg'         => '分页起始值有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_number',
                'error_msg'         => '分页数量不能为空',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $limit_start = max(0, intval($params['limit_start']));
        $limit_number = max(1, intval($params['limit_number']));
        $order_by = empty($params['order_by']) ? 'id desc' : I('order_by', '', '', $params);

        // 获取数据列表
        $data = db('UserIntegralLog')->where($params['where'])->limit($limit_start, $limit_number)->order($order_by)->select();
        if(!empty($data))
        {
            $common_integral_log_type_list = lang('common_integral_log_type_list');
            foreach($data as &$v)
            {
                // 操作类型
                $v['type_name'] = $common_integral_log_type_list[$v['type']]['name'];

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
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
                'error_msg'         => '订单id有误',
            ]
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 订单
        $order = db('Order')->field('id,user_id,status')->find(intval($params['order_id']));
        if(empty($order))
        {
            return DataReturn('订单不存在或已删除，中止操作', 0);
        }
        if(!in_array($order['status'], [4]))
        {
            return DataReturn('当前订单状态不允许操作['.$params['order_id'].'-'.$order['status'].']', 0);
        }

        // 获取用户信息
        $user = db('User')->field('id')->find(intval($order['user_id']));
        if(empty($user))
        {
            return DataReturn('用户不存在或已删除，中止操作', 0);
        }

        // 获取订单商品
        $goods_all = db('OrderDetail')->where(['order_id'=>$params['order_id']])->column('goods_id');
        if(!empty($goods_all))
        {
            foreach($goods_all as $goods_id)
            {
                $give_integral = db('Goods')->where(['id'=>$goods_id])->value('give_integral');
                if(!empty($give_integral))
                {
                    // 用户积分添加
                    $user_integral = db('User')->where(['id'=>$user['id']])->value('integral');
                    if(!db('User')->where(['id'=>$user['id']])->setInc('integral', $give_integral))
                    {
                        return DataReturn('用户积分赠送失败['.$params['order_id'].'-'.$goods_id.']', -10);
                    }

                    // 积分日志
                    IntegralService::UserIntegralLogAdd($user['id'], $user_integral, $user_integral+$give_integral, '订单商品完成赠送', 1);
                }
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('没有需要操作的数据', 0);
    }
}
?>