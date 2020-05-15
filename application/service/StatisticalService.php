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
 * 数据统计服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class StatisticalService
{
    // 近3天,近7天,近15天,近30天
    private static $nearly_three_days;
    private static $nearly_seven_days;
    private static $nearly_fifteen_days;
    private static $nearly_thirty_days;

    // 近30天日期
    private static $thirty_time_start;
    private static $thirty_time_end;

    // 近15天日期
    private static $fifteen_time_start;
    private static $fifteen_time_end;

    // 近7天日期
    private static $seven_time_start;
    private static $seven_time_end;

    // 昨天日期
    private static $yesterday_time_start;
    private static $yesterday_time_end;

    // 今天日期
    private static $today_time_start;
    private static $today_time_end;

    /**
     * 初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-22
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function Init($params = [])
    {
        static $object = null;
        if(!is_object($object))
        {
            // 初始化标记对象，避免重复初始化
            $object = (object) [];

            // 近30天日期
            self::$thirty_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-30 day')));
            self::$thirty_time_end = time();

            // 近15天日期
            self::$fifteen_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-15 day')));
            self::$fifteen_time_end = time();

            // 近7天日期
            self::$seven_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-7 day')));
            self::$seven_time_end = time();

            // 昨天日期
            self::$yesterday_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')));
            self::$yesterday_time_end = strtotime(date('Y-m-d 23:59:59', strtotime('-1 day')));

            // 今天日期
            self::$today_time_start = strtotime(date('Y-m-d 00:00:00'));
            self::$today_time_end = time();

            // 近3天,近7天,近15天,近30天
            $nearly_all = [
                3   => 'nearly_three_days',
                7   => 'nearly_seven_days',
                15  => 'nearly_fifteen_days',
                30  => 'nearly_thirty_days',
            ];
            foreach($nearly_all as $day=>$name)
            {
                $date = [];
                $time = time();
                for($i=0; $i<$day; $i++)
                {
                    $date[] = [
                        'start_time'    => strtotime(date('Y-m-d 00:00:00', time()-$i*3600*24)),
                        'end_time'      => strtotime(date('Y-m-d 23:59:59', time()-$i*3600*24)),
                        'name'          => date('Y-m-d', time()-$i*3600*24),
                    ];
                }
                self::${$name} = array_reverse($date);
            }
        }
    }

    /**
     * 用户总数,今日,昨日,总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserYesterdayTodayTotal($params = [])
    {
        // 初始化
        self::Init($params);

        // 总数
        $total_count = Db::name('User')->count();

        // 昨天
        $where = [
            ['add_time', '>=', self::$yesterday_time_start],
            ['add_time', '<=', self::$yesterday_time_end],
        ];
        $yesterday_count = Db::name('User')->where($where)->count();

        // 今天
        $where = [
            ['add_time', '>=', self::$today_time_start],
            ['add_time', '<=', self::$today_time_end],
        ];
        $today_count = Db::name('User')->where($where)->count();

        // 数据组装
        $result = [
            'total_count'       => $total_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
        ];
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 订单总数,今日,昨日,总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderNumberYesterdayTodayTotal($params = [])
    {
        // 初始化
        self::Init($params);

        // 订单状态
        // （0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）
        
        // 总数
        $where = [
            ['status', '<=', 4],
        ];
        $total_count = Db::name('Order')->where($where)->count();

        // 昨天
        $where = [
            ['status', '<=', 4],
            ['add_time', '>=', self::$yesterday_time_start],
            ['add_time', '<=', self::$yesterday_time_end],
        ];
        $yesterday_count = Db::name('Order')->where($where)->count();

        // 今天
        $where = [
            ['status', '<=', 4],
            ['add_time', '>=', self::$today_time_start],
            ['add_time', '<=', self::$today_time_end],
        ];
        $today_count = Db::name('Order')->where($where)->count();

        // 数据组装
        $result = [
            'total_count'       => $total_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
        ];
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 订单成交总量,今日,昨日,总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderCompleteYesterdayTodayTotal($params = [])
    {
        // 初始化
        self::Init($params);

        // 订单状态
        // （0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）
        
        // 总数
        $where = [
            ['status', '=', 4],
        ];
        $total_count = Db::name('Order')->where($where)->count();

        // 昨天
        $where = [
            ['status', '=', 4],
            ['add_time', '>=', self::$yesterday_time_start],
            ['add_time', '<=', self::$yesterday_time_end],
        ];
        $yesterday_count = Db::name('Order')->where($where)->count();

        // 今天
        $where = [
            ['status', '=', 4],
            ['add_time', '>=', self::$today_time_start],
            ['add_time', '<=', self::$today_time_end],
        ];
        $today_count = Db::name('Order')->where($where)->count();

        // 数据组装
        $result = [
            'total_count'       => $total_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
        ];
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 订单收入总计,今日,昨日,总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderCompleteMoneyYesterdayTodayTotal($params = [])
    {
        // 初始化
        self::Init($params);

        // 订单状态
        // （0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）
        
        // 总数
        $where = [
            ['status', 'in', [2,3,4]],
        ];
        $total_count = Db::name('Order')->where($where)->sum('total_price');

        // 昨天
        $where = [
            ['status', 'in', [2,3,4]],
            ['add_time', '>=', self::$yesterday_time_start],
            ['add_time', '<=', self::$yesterday_time_end],
        ];
        $yesterday_count = Db::name('Order')->where($where)->sum('total_price');

        // 今天
        $where = [
            ['status', 'in', [2,3,4]],
            ['add_time', '>=', self::$today_time_start],
            ['add_time', '<=', self::$today_time_end],
        ];
        $today_count = Db::name('Order')->where($where)->sum('total_price');

        // 数据组装
        $result = [
            'total_count'       => PriceNumberFormat($total_count),
            'yesterday_count'   => PriceNumberFormat($yesterday_count),
            'today_count'       => PriceNumberFormat($today_count),
        ];
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 订单交易趋势, 30天数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderTradingTrendSevenTodayTotal($params = [])
    {
        // 初始化
        self::Init($params);

        // 订单状态列表
        $order_status_list = lang('common_order_user_status');
        $status_arr = array_column($order_status_list, 'id');

        // 循环获取统计数据
        $data = [];
        $count_arr = [];
        $name_arr = [];
        if(!empty($status_arr))
        {
            foreach(self::$nearly_thirty_days as $day)
            {
                // 当前日期名称
                $name_arr[] = $day['name'];

                // 根据支付名称获取数量
                foreach($status_arr as $status)
                {
                    // 获取订单
                    $where = [
                        ['status', '=', $status],
                        ['add_time', '>=', $day['start_time']],
                        ['add_time', '<=', $day['end_time']],
                    ];
                    $count_arr[$status][] = Db::name('Order')->where($where)->count();
                }
            }
        }

        // 数据格式组装
        foreach($status_arr as $status)
        {
            $data[] = [
                'name'      => $order_status_list[$status]['name'],
                'type'      => 'line',
                'tiled'     => '总量',
                'data'      => empty($count_arr[$status]) ? [] : $count_arr[$status],
            ];
        }

        // 数据组装
        $result = [
            'title_arr' => array_column($order_status_list, 'name'),
            'name_arr'  => $name_arr,
            'data'      => $data,
        ];
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 订单支付方式, 30天数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderPayTypeSevenTodayTotal($params = [])
    {
        // 初始化
        self::Init($params);

        // 获取支付方式名称
        $where = [
            ['business_type', '=', 1],
        ];
        $pay_name_arr = Db::name('PayLog')->where($where)->group('payment_name')->column('payment_name');


        // 循环获取统计数据
        $data = [];
        $count_arr = [];
        $name_arr = [];
        if(!empty($pay_name_arr))
        {
            foreach(self::$nearly_thirty_days as $day)
            {
                // 当前日期名称
                $name_arr[] = date('m-d', strtotime($day['name']));

                // 根据支付名称获取数量
                foreach($pay_name_arr as $payment)
                {
                    // 获取订单
                    $where = [
                        ['payment_name', '=', $payment],
                        ['add_time', '>=', $day['start_time']],
                        ['add_time', '<=', $day['end_time']],
                    ];
                    $count_arr[$payment][] = Db::name('PayLog')->where($where)->count();
                }
            }
        }

        // 数据格式组装
        foreach($pay_name_arr as $payment)
        {
            $data[] = [
                'name'      => $payment,
                'type'      => 'line',
                'stack'     => '总量',
                'areaStyle' => (object) [],
                'data'      => empty($count_arr[$payment]) ? [] : $count_arr[$payment],
            ];
        }

        // 数据组装
        $result = [
            'title_arr' => $pay_name_arr,
            'name_arr'  => $name_arr,
            'data'      => $data,
        ];
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 热销商品, 30天数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsHotSaleSevenTodayTotal($params = [])
    {
        // 初始化
        self::Init($params);
    
        // 获取订单id
        $where = [
            ['status', '<=', 4],
            ['add_time', '>=', self::$thirty_time_start],
            ['add_time', '<=', self::$thirty_time_end],
        ];
        $order_ids = Db::name('Order')->where($where)->column('id');

        // 获取订单详情热销商品
        if(empty($order_ids))
        {
            $data = [];
        } else {
            $data = Db::name('OrderDetail')->field('goods_id, sum(buy_number) AS value')->where('order_id', 'IN', $order_ids)->group('goods_id')->order('value desc')->limit(10)->select();
        }

        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 获取商品名称（这里不一次性读取、为了兼容 mysql 5.7+版本）
                $v['name'] = Db::name('OrderDetail')->where('goods_id', $v['goods_id'])->value('title');
                if(mb_strlen($v['name'], 'utf-8') > 12)
                {
                    $v['name'] = mb_substr($v['name'], 0, 12, 'utf-8').'...';
                }
                unset($v['goods_id']);
            }
        }

        // 数据组装
        $result = [
            'name_arr'  => array_column($data, 'name'),
            'data'      => $data,
        ];
        return DataReturn('处理成功', 0, $result);
    }
}
?>