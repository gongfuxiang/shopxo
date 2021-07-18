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

/**
 * 支付日志服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PayLogService
{
    /**
     * 支付日志添加
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-07T00:57:36+0800
     * @param   [array]             $params         [输入参数]
     * @param   [int]               $user_id        [用户id]
     * @param   [int]               $business_ids   [业务订单id]
     * @param   [float]             $total_price    [业务订单实际金额]
     * @param   [string]            $subject        [业务订单名称]
     * @param   [string]            $business_type  [业务类型，字符串（如：订单、钱包充值、会员购买、等...）]
     * @return  [boolean]                           [成功true, 失败false]
     */
    public static function PayLogInsert($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'business_ids',
                'error_msg'         => '业务id为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'business_ids',
                'error_msg'         => '业务id数据类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => '用户id为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'business_type',
                'error_msg'         => '业务类型为空',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'total_price',
                'error_msg'         => '业务金额为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 业务id
        if(empty($params['business_ids']))
        {
            return DataReturn('业务id为空', -1);
        }

        // 日志主数据
        $data = [
            'log_no'            => date('YmdHis').GetNumberCode(6),
            'user_id'           => intval($params['user_id']),
            'total_price'       => PriceNumberFormat($params['total_price']),
            'business_type'     => trim($params['business_type']),
            'subject'           => isset($params['subject']) ? $params['subject'] : '',
            'payment'           => isset($params['payment']) ? $params['payment'] : '',
            'payment_name'      => isset($params['payment_name']) ? $params['payment_name'] : '',
            'add_time'          => time(),
        ];
        $pay_log_id = Db::name('PayLog')->insertGetId($data);
        if($pay_log_id > 0)
        {
            $business_nos = isset($params['business_nos']) && is_array($params['business_nos']) ? $params['business_nos'] : [];
            $value_data = [];
            foreach($params['business_ids'] as $bk=>$bv)
            {
                $value_data[] = [
                    'pay_log_id'    => $pay_log_id,
                    'business_id'   => $bv,
                    'business_no'   => isset($business_nos[$bk]) ? trim($business_nos[$bk]) : '',
                    'add_time'      => time(),
                ];
            }
            $res = Db::name('PayLogValue')->insertAll($value_data);
            if($res >= count($params['business_ids']))
            {
                // 日志 id 加入数组中
                $data['id'] = $pay_log_id;

                // 支付日志添加成功钩子
                $hook_name = 'plugins_service_paylog_insert_success';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => $params,
                    'data'          => $data,
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }

                // 返回成功
                return DataReturn('添加成功', 0, $data);
            }
        }
        return DataReturn('支付订单添加失败', -100);
    }

    /**
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-27
     * @desc    description
     * @param   [int]               $log_id         [支付日志id]
     * @param   [string]            $trade_no       [支付平台交易号]
     * @param   [string]            $buyer_user     [支付平台用户帐号]
     * @param   [float]             $pay_price      [支付金额]
     * @param   [string]            $payment        [支付方式标记]
     * @param   [string]            $payment_name   [支付方式名称]
     */
    public static function PayLogSuccess($params = [])
    {
        // 参数
        if(empty($params['log_id']))
        {
            return DataReturn('日志id有误', -100);
        }

        // 更新数据
        $data = [
            'trade_no'          => isset($params['trade_no']) ? $params['trade_no'] : '',
            'buyer_user'        => isset($params['buyer_user']) ? $params['buyer_user'] : '',
            'pay_price'         => isset($params['pay_price']) ? PriceNumberFormat($params['pay_price']) : 0.00,
            'subject'           => isset($params['subject']) ? $params['subject'] : '',
            'payment'           => isset($params['payment']) ? $params['payment'] : '',
            'payment_name'      => isset($params['payment_name']) ? $params['payment_name'] : '',
            'status'            => 1,
            'pay_time'          => time(),
        ];
        if(Db::name('PayLog')->where(['id'=>intval($params['log_id']), 'status'=>0])->update($data))
        {
            return DataReturn('日志订单更新成功', 0);
        }
        return DataReturn('日志订单更新失败', -100);
    }

    /**
     * 获取支付日志类型
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-23T02:22:03+0800
     * @param   [array]          $params [输入参数]
     */
    public static function PayLogTypeList($params = [])
    {
        $data = Db::name('PayLog')->field('payment as id, payment_name as name')->group('payment,payment_name')->select()->toArray();
        return DataReturn('处理成功', 0, $data);
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
    public static function PayLogList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取数据列表
        $data = Db::name('PayLog')->where($where)->field($field)->limit($m, $n)->order($order_by)->select()->toArray();
        if(!empty($data))
        {
            // 字段列表
            $keys = ArrayKeys($data);

            // 获取支付业务关联数据
            $log_value_list = [];
            $log_value = Db::name('PayLogValue')->field('pay_log_id,business_id,business_no')->where(['pay_log_id'=>array_column($data, 'id')])->select()->toArray();
            if(!empty($log_value))
            {
                foreach($log_value as $lv)
                {
                    $log_value_list[$lv['pay_log_id']][] = $lv;
                }
            }

            // 用户列表
            if(in_array('user_id', $keys) && isset($params['is_public']) && $params['is_public'] == 0)
            {
                $user_list = UserService::GetUserViewInfo(array_column($data, 'user_id'));
            }

            // 循环处理数据
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']))
                {
                    if(isset($params['is_public']) && $params['is_public'] == 0)
                    {
                        $v['user'] = (!empty($user_list) && is_array($user_list) && array_key_exists($v['user_id'], $user_list)) ? $user_list[$v['user_id']] : [];
                    }
                }

                // 关联业务数据
                $v['business_list'] = isset($log_value_list[$v['id']]) ? $log_value_list[$v['id']] : [];

                // 时间
                $v['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['pay_time'] = empty($v['pay_time']) ? '' : date('Y-m-d H:i:s', $v['pay_time']);
                $v['close_time'] = empty($v['close_time']) ? '' : date('Y-m-d H:i:s', $v['close_time']);
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
    public static function PayLogTotal($where = [])
    {
        return (int) Db::name('PayLog')->where($where)->count();
    }

    /**
     * 关闭
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PayLogClose($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn('操作id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 关闭操作
        if(Db::name('PayLog')->where(['id'=>$params['ids'], 'status'=>0])->update(['status'=>2, 'close_time'=>time()]))
        {
            return DataReturn('关闭成功');
        }

        return DataReturn('关闭失败', -100);
    }
}
?>