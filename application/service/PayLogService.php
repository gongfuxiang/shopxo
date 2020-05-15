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
     * @param   [int]               $order_id       [业务订单id]
     * @param   [float]             $total_price    [业务订单实际金额]
     * @param   [string]            $trade_no       [支付平台交易号]
     * @param   [string]            $buyer_user     [支付平台用户帐号]
     * @param   [float]             $pay_price      [支付金额]
     * @param   [string]            $subject        [业务订单名称]
     * @param   [string]            $payment        [支付方式标记]
     * @param   [string]            $payment_name   [支付方式名称]
     * @param   [int]               $business_type  [业务类型（0默认, 1订单, 2充值, ...）]
     * @return  [boolean]                           [成功true, 失败false]
     */
    public static function PayLogInsert($params = [])
    {
        $data = [
            'user_id'           => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'order_id'          => isset($params['order_id']) ? intval($params['order_id']) : 0,
            'total_price'       => isset($params['total_price']) ? PriceNumberFormat($params['total_price']) : 0.00,
            'trade_no'          => isset($params['trade_no']) ? $params['trade_no'] : '',
            'buyer_user'        => isset($params['buyer_user']) ? $params['buyer_user'] : '',
            'pay_price'         => isset($params['pay_price']) ? PriceNumberFormat($params['pay_price']) : 0.00,
            'subject'           => isset($params['subject']) ? $params['subject'] : '',
            'payment'           => isset($params['payment']) ? $params['payment'] : '',
            'payment_name'      => isset($params['payment_name']) ? $params['payment_name'] : '',
            'business_type'     => isset($params['business_type']) ? intval($params['business_type']) : 0,
            'add_time'          => time(),
        ];
        return Db::name('PayLog')->insertGetId($data) > 0;
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
        $data = Db::name('PayLog')->field('payment AS id, payment_name AS name')->group('id')->select();
        return DataReturn('处理成功', 0, $data);
    }
    
    /**
     * 后台管理员列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AdminPayLogList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $field = 'p.*,u.username,u.nickname,u.mobile,u.email,u.gender';
        $order_by = empty($params['order_by']) ? 'p.id desc' : $params['order_by'];

        // 获取数据列表
        $data = Db::name('PayLog')->alias('p')->join(['__USER__'=>'u'], 'u.id=p.user_id')->where($where)->field($field)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $common_business_type_list = lang('common_business_type_list');
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                // 业务类型
                $v['business_type_name'] = $common_business_type_list[$v['business_type']]['name'];

                // 性别
                $v['gender_text'] = $common_gender_list[$v['gender']]['name'];

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
    public static function AdminPayLogTotal($where = [])
    {
        return (int) Db::name('PayLog')->alias('p')->join(['__USER__'=>'u'], 'u.id=p.user_id')->where($where)->count();
    }

    /**
     * 列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AdminPayLogListWhere($params = [])
    {
        $where = [];
        
        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['p.trade_no|u.username|u.nickname|u.mobile', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['business_type']) && $params['business_type'] > -1)
            {
                $where[] = ['p.business_type', '=', intval($params['business_type'])];
            }
            if(!empty($params['pay_type']))
            {
                $where[] = ['p.payment', '=', $params['pay_type']];
            }
            if(isset($params['gender']) && $params['gender'] > -1)
            {
                $where[] = ['u.gender', '=', intval($params['gender'])];
            }

            if(!empty($params['price_start']))
            {
                $where[] = ['p.pay_price', '>', PriceNumberFormat($params['price_start'])];
            }
            if(!empty($params['price_end']))
            {
                $where[] = ['p.pay_price', '<', PriceNumberFormat($params['price_end'])];
            }

            if(!empty($params['time_start']))
            {
                $where[] = ['p.add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['p.add_time', '<', strtotime($params['time_end'])];
            }
        }

        return $where;
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
    public static function PayLogDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '操作id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 删除操作
        if(Db::name('PayLog')->where(['id'=>$params['id']])->delete())
        {
            return DataReturn('删除成功');
        }

        return DataReturn('删除失败或资源不存在', -100);
    }
}
?>