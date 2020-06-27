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
        if(Db::name('UserIntegralLog')->insertGetId($data) > 0)
        {
            $type_msg = lang('common_integral_log_type_list')[$type]['name'];
            $integral = ($data['type'] == 0) ? $data['original_integral']-$data['new_integral'] : $data['new_integral']-$data['original_integral'];
            $detail = $msg.'积分'.$type_msg.$integral;
            MessageService::MessageAdd($user_id, '积分变动', $detail);

            // 用户登录数据更新防止数据存储session不同步展示
            if(in_array(APPLICATION_CLIENT_TYPE, ['pc', 'h5']))
            {
                $user = UserService::LoginUserInfo();
                if(isset($user['id']) && $user['id'] == $user_id)
                {
                    UserService::UserLoginRecord($user_id);
                }
            }
            
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
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = '*';
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('UserIntegralLog')->where($where)->field($field)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $integral_log_type_list = lang('common_integral_log_type_list');
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
        return DataReturn('处理成功', 0, $data);
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
     * 订单商品积分赠送（已丢弃）
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 订单
        $order = Db::name('Order')->field('id,user_id,status')->find(intval($params['order_id']));
        if(empty($order))
        {
            return DataReturn('订单不存在或已删除，中止操作', 0);
        }
        if(!in_array($order['status'], [4]))
        {
            return DataReturn('当前订单状态不允许操作['.$params['order_id'].'-'.$order['status'].']', 0);
        }

        // 获取用户信息
        $user = Db::name('User')->field('id')->find(intval($order['user_id']));
        if(empty($user))
        {
            return DataReturn('用户不存在或已删除，中止操作', 0);
        }

        // 获取订单商品
        $goods_all = Db::name('OrderDetail')->where(['order_id'=>$params['order_id']])->column('goods_id');
        if(!empty($goods_all))
        {
            foreach($goods_all as $goods_id)
            {
                $give_integral = Db::name('Goods')->where(['id'=>$goods_id])->value('give_integral');
                if(!empty($give_integral))
                {
                    // 用户积分添加
                    $user_integral = Db::name('User')->where(['id'=>$user['id']])->value('integral');
                    if(!Db::name('User')->where(['id'=>$user['id']])->setInc('integral', $give_integral))
                    {
                        return DataReturn('用户积分赠送失败['.$params['order_id'].'-'.$goods_id.']', -10);
                    }

                    // 积分日志
                    self::UserIntegralLogAdd($user['id'], $user_integral, $user_integral+$give_integral, '订单商品完成赠送', 1);
                }
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('没有需要操作的数据', 0);
    }
}
?>