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
                'key_name'          => 'business_type',
                'error_msg'         => MyLang('common_service.paylog.save_business_type_empty_tips'),
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'business_ids',
                'error_msg'         => MyLang('common_service.paylog.save_business_ids_format_tips'),
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'total_price',
                'error_msg'         => MyLang('common_service.paylog.save_total_price_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 日志主数据
        $data = [
            'log_no'            => date('YmdHis').GetNumberCode(6),
            'user_id'           => empty($params['user_id']) ? 0 : intval($params['user_id']),
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
                return DataReturn(MyLang('insert_success'), 0, $data);
            }
        }
        return DataReturn(MyLang('common_service.paylog.pay_log_insert_fail_tips'), -100);
    }

    /**
     * 支付日志请求记录
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-11-17
     * @desc    description
     * @param   [string]          $log_no [支付日志单号]
     * @param   [array]           $params [输入参数]
     */
    public static function PayLogRequestRecord($log_no, $params = [])
    {
        $data = [
            'request_params'  => empty($params['request_params']) ? '' : (is_array($params['request_params']) ? json_encode($params['request_params'], JSON_UNESCAPED_UNICODE) : $params['request_params']),
        ];
        Db::name('PayLog')->where(['log_no'=>$log_no])->update($data);
    }

    /**
     * 支付日志数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-11-17
     * @desc    description
     * @param   [string]          $log_no [支付日志单号]
     * @param   [array]           $params [输入参数]
     */
    public static function PayLogData($log_no, $params = [])
    {
        $data = Db::name('PayLog')->where(['log_no'=>$log_no])->find();
        if(!empty($data))
        {
            if(!empty($data['request_params']) && IsJson($data['request_params']))
            {
                $data['request_params'] = json_decode($data['request_params'], true);
            }
            return $data;
        }
        return [];
    }

    /**
     * 业务订单支付日志数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-11-17
     * @desc    业务订单id 和 业务订单号必须传一个
     * @param   [string]       $params['business_type'] [业务类型]
     * @param   [int]          $params['business_id']   [业务订单id]
     * @param   [string]       $params['business_no']   [业务订单号]
     * @param   [int]          $params['status']        [支付状态]
     */
    public static function BusinessOrderPayLogData($params = [])
    {
        if(!empty($params['business_type']))
        {
            $where = [];
            // 业务订单id
            if(!empty($params['business_id']))
            {
                $where[] = ['plv.business_id', '=', intval($params['business_id'])];
            }
            // 业务订单号
            if(!empty($params['business_no']))
            {
                $where[] = ['plv.business_no', '=', trim($params['business_no'])];
            }
            // 增加基础条件查询数据
            if(!empty($where))
            {
                // 业务类型
                $where[] = ['pl.business_type', '=', trim($params['business_type'])];
                // 状态
                if(isset($params['status']))
                {
                    $where[] = ['pl.status', '=', intval($params['status'])];
                }
                // 获取数据
                $data = Db::name('PayLog')->alias('pl')->join('pay_log_value plv', 'pl.id=plv.pay_log_id')->where($where)->find();
                if(!empty($data))
                {
                    if(!empty($data['request_params']) && IsJson($data['request_params']))
                    {
                        $data['request_params'] = json_decode($data['request_params'], true);
                    }
                    return $data;
                }
            }
        }
        return [];
    }

    /**
     * 支付日志更新
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
            return DataReturn(MyLang('common_service.paylog.pay_log_id_empty_tips'), -1);
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
        if(Db::name('PayLog')->where(['id'=>intval($params['log_id']), 'status'=>0])->update($data) !== false)
        {
            return DataReturn(MyLang('update_success'), 0);
        }
        return DataReturn(MyLang('common_service.paylog.pay_log_update_fail_tips'), -100);
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
        return DataReturn(MyLang('handle_success'), 0, $data);
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
    public static function PayLogListHandle($data, $params = [])
    {
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
        return $data;
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
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 关闭操作
        if(Db::name('PayLog')->where(['id'=>$params['ids'], 'status'=>0])->update(['status'=>2, 'close_time'=>time()]))
        {
            return DataReturn(MyLang('close_success'), 0);
        }
        return DataReturn(MyLang('close_fail'), -100);
    }
}
?>