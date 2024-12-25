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
 * 数据统计服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class StatisticalService
{
    // 近3天,近7天,近15天,近30天
    public static $nearly_three_days;
    public static $nearly_seven_days;
    public static $nearly_fifteen_days;
    public static $nearly_thirty_days;

    // 昨天
    public static $yesterday_time_start;
    public static $yesterday_time_end;

    // 今天
    public static $today_time_start;
    public static $today_time_end;

    // 近365天
    public static $year_time_start;
    public static $year_time_end;

    // 近180天
    public static $half_year_time_start;
    public static $half_year_time_end;

    // 近30天
    public static $thirty_time_start;
    public static $thirty_time_end;

    // 近15天
    public static $fifteen_time_start;
    public static $fifteen_time_end;

    // 近7天
    public static $seven_time_start;
    public static $seven_time_end;

    // 近3天
    public static $three_time_start;
    public static $three_time_end;

    // 上月
    public static $last_month_time_start;
    public static $last_month_time_end;

    // 当月
    public static $this_month_time_start;
    public static $this_month_time_end;

    // 去年
    public static $this_year_time_start;
    public static $this_year_time_end;

    // 今年
    public static $last_year_time_start;
    public static $last_year_time_end;

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

            // 昨天日期
            self::$yesterday_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')));
            self::$yesterday_time_end = strtotime(date('Y-m-d 23:59:59', strtotime('-1 day')));

            // 今天日期
            self::$today_time_start = strtotime(date('Y-m-d 00:00:00'));
            self::$today_time_end = time();

            // 近365天日期
            self::$year_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-365 day')));
            self::$year_time_end = time();

            // 近180天日期
            self::$half_year_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-180 day')));
            self::$half_year_time_end = time();

            // 近30天日期
            self::$thirty_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-29 day')));
            self::$thirty_time_end = time();

            // 近15天日期
            self::$fifteen_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-14 day')));
            self::$fifteen_time_end = time();

            // 近7天日期
            self::$seven_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-6 day')));
            self::$seven_time_end = time();

            // 近3天日期
            self::$three_time_start = strtotime(date('Y-m-d 00:00:00', strtotime('-2 day')));
            self::$three_time_end = time();

            // 上月
            self::$last_month_time_start = strtotime(date('Y-m-01 00:00:00', strtotime('-1 month', strtotime(date('Y-m', time())))));
            self::$last_month_time_end = strtotime(date('Y-m-t 23:59:59', strtotime('-1 month', strtotime(date('Y-m', time())))));

            // 当月
            self::$this_month_time_start = strtotime(date('Y-m-01 00:00:00'));
            self::$this_month_time_end = time();

            // 去年
            self::$last_year_time_start = strtotime(date('Y-01-01 00:00:00', strtotime('-1 year', strtotime(date('Y-m', time())))));
            self::$last_year_time_end = strtotime(date('Y-12-31 23:59:59', strtotime('-1 year', strtotime(date('Y-m', time())))));

            // 今年
            self::$this_year_time_start = strtotime(date('Y-01-01 00:00:00'));
            self::$this_year_time_end = time();


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
     * 获取时间列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-31
     * @desc    description
     * @param   [array]           $params [数据参数]
     */
    public static function DateTimeList($params = [])
    {
        // 初始化
        self::Init($params);

        // 统计时间配置列表
        return [
            '3-day' => [
                'key'    => '3-day',
                'name'   => MyLang('common_service.statistical.time_section_day_3_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$three_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$three_time_end),
            ],
            '7-day' => [
                'key'    => '7-day',
                'name'   => MyLang('common_service.statistical.time_section_day_7_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$seven_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$seven_time_end),
            ],
            '15-day' => [
                'key'    => '15-day',
                'name'   => MyLang('common_service.statistical.time_section_day_15_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$fifteen_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$fifteen_time_end),
            ],
            '30-day' => [
                'key'    => '30-day',
                'name'   => MyLang('common_service.statistical.time_section_day_30_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$thirty_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$thirty_time_end),
            ],
            '180-day' => [
                'key'    => '180-day',
                'name'   => MyLang('common_service.statistical.time_section_day_180_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$half_year_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$half_year_time_end),
            ],
            '365-day' => [
                'key'    => '365-day',
                'name'   => MyLang('common_service.statistical.time_section_day_365_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$year_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$year_time_end),
            ],
            'this-month' => [
                'key'    => 'this-month',
                'name'   => MyLang('common_service.statistical.time_section_this_month_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$this_month_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$this_month_time_end),
            ],
            'last-month' => [
                'key'    => 'last-month',
                'name'   => MyLang('common_service.statistical.time_section_last_month_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$last_month_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$last_month_time_end),
            ],
            'this-year' => [
                'key'    => 'this-year',
                'name'   => MyLang('common_service.statistical.time_section_this_year_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$this_year_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$this_year_time_end),
            ],
            'last-year' => [
                'key'    => 'last-year',
                'name'   => MyLang('common_service.statistical.time_section_last_year_name'),
                'start'  => date('Y-m-d H:i:s', StatisticalService::$last_year_time_start),
                'end'    => date('Y-m-d H:i:s', StatisticalService::$last_year_time_end),
            ],
        ];
    }

    /**
     * 用户总数,今日,昨日,当月,上月总数
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

        // 上月
        $where = [
            ['add_time', '>=', self::$last_month_time_start],
            ['add_time', '<=', self::$last_month_time_end],
        ];
        $last_month_count = Db::name('User')->where($where)->count();

        // 当月
        $where = [
            ['add_time', '>=', self::$this_month_time_start],
            ['add_time', '<=', self::$this_month_time_end],
        ];
        $same_month_count = Db::name('User')->where($where)->count();

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
            'last_month_count'  => $last_month_count,
            'same_month_count'  => $same_month_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 订单总数,今日,昨日,当月,上月总数
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

        // 上月
        $where = [
            ['status', '<=', 4],
            ['add_time', '>=', self::$last_month_time_start],
            ['add_time', '<=', self::$last_month_time_end],
        ];
        $last_month_count = Db::name('Order')->where($where)->count();

        // 当月
        $where = [
            ['status', '<=', 4],
            ['add_time', '>=', self::$this_month_time_start],
            ['add_time', '<=', self::$this_month_time_end],
        ];
        $same_month_count = Db::name('Order')->where($where)->count();

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
            'last_month_count'  => $last_month_count,
            'same_month_count'  => $same_month_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 订单成交总量,今日,昨日,当月,上月总数
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

        // 上月
        $where = [
            ['status', '=', 4],
            ['add_time', '>=', self::$last_month_time_start],
            ['add_time', '<=', self::$last_month_time_end],
        ];
        $last_month_count = Db::name('Order')->where($where)->count();

        // 当月
        $where = [
            ['status', '=', 4],
            ['add_time', '>=', self::$this_month_time_start],
            ['add_time', '<=', self::$this_month_time_end],
        ];
        $same_month_count = Db::name('Order')->where($where)->count();

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
            'last_month_count'  => $last_month_count,
            'same_month_count'  => $same_month_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 订单收入总计,今日,昨日,当月,上月总数
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

        // 是否有收入统计权限
        if(AdminIsPower('index', 'income'))
        {
            // 上月
            $where = [
                ['status', 'in', [2,3,4]],
                ['add_time', '>=', self::$last_month_time_start],
                ['add_time', '<=', self::$last_month_time_end],
            ];
            $last_month_count = Db::name('Order')->where($where)->sum('pay_price')-Db::name('Order')->where($where)->sum('refund_price');

            // 当月
            $where = [
                ['status', 'in', [2,3,4]],
                ['add_time', '>=', self::$this_month_time_start],
                ['add_time', '<=', self::$this_month_time_end],
            ];
            $same_month_count = Db::name('Order')->where($where)->sum('pay_price')-Db::name('Order')->where($where)->sum('refund_price');

            // 昨天
            $where = [
                ['status', 'in', [2,3,4]],
                ['add_time', '>=', self::$yesterday_time_start],
                ['add_time', '<=', self::$yesterday_time_end],
            ];
            $yesterday_count = Db::name('Order')->where($where)->sum('pay_price')-Db::name('Order')->where($where)->sum('refund_price');

            // 今天
            $where = [
                ['status', 'in', [2,3,4]],
                ['add_time', '>=', self::$today_time_start],
                ['add_time', '<=', self::$today_time_end],
            ];
            $today_count = Db::name('Order')->where($where)->sum('pay_price')-Db::name('Order')->where($where)->sum('refund_price');
        } else {
            $last_month_count = 0.00;
            $same_month_count = 0.00;
            $yesterday_count = 0.00;
            $today_count = 0.00;
        }

        // 数据组装
        $result = [
            'last_month_count'  => PriceNumberFormat($last_month_count),
            'same_month_count'  => PriceNumberFormat($same_month_count),
            'yesterday_count'   => PriceNumberFormat($yesterday_count),
            'today_count'       => PriceNumberFormat($today_count),
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 基础数据总计
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-31
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BaseTotalCount($params = [])
    {
        // 日期条件处理
        $where = [];
        if(!empty($params['start']))
        {
            $where[] = ['add_time', '>=', $params['start']];
        }
        if(!empty($params['end']))
        {
            $where[] = ['add_time', '<=', $params['end']];
        }

        // 用户总数
        $user_count = Db::name('User')->where($where)->count();

        // 订单总数
        $order_count = Db::name('Order')->where(array_merge($where, [['status', '<=', 4]]))->count();

        // 订单成交总量
        $order_sale_count = Db::name('Order')->where(array_merge($where, [['status', '=', 4]]))->count();

        // 订单收入总计、是否有收入统计权限
        if(AdminIsPower('index', 'income'))
        {
            $order_complete_total = Db::name('Order')->where(array_merge($where, [['status', 'in', [2,3,4]]]))->sum('pay_price')-Db::name('Order')->where(array_merge($where, [['status', 'in', [2,3,4]]]))->sum('refund_price');
        } else {
            $order_complete_total = 0.00;
        }
        

        $result = [
            'user_count'            => $user_count,
            'order_count'           => $order_count,
            'order_sale_count'      => $order_sale_count,
            'order_complete_total'  => PriceNumberFormat($order_complete_total),
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 区间时间创建
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-30
     * @desc    description
     * @param   [int]          $start [起始时间]
     * @param   [int]          $end   [结束时间]
     */
    public static function DayCreate($start, $end)
    {
        $data = [];
        while(true)
        {
            // 计算时间条件
            $temp_end = strtotime('+1 day', $start);

            // 最大时间减1秒，条件使用 start >= ? && end <= ?
            // start 2021-01-01 00:00:00 , end 2021-01-01 23:59:58
            $data[] = [
                'start' => $start,
                'end'   => $temp_end-1,
                'date'  => date('Y-m-d H:i:s', $start).' - '.date('Y-m-d H:i:s', $temp_end-1),
            ];

            // 结束跳出循环
            if($temp_end >= $end)
            {
                // 结束使用最大时间替代计算的最后一个最大时间
                $count = count($data)-1;
                $data[$count]['end'] = $end;
                $data[$count]['date'] = date('Y-m-d H:i:s', $data[$count]['start']).' - '.date('Y-m-d H:i:s', $end);
                break;
            }
            $start = $temp_end;
        }
        return $data;
    }

    /**
     * 订单交易趋势
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderTradingTotal($params = [])
    {
        // 订单状态列表
        $order_status_list = MyConst('common_order_status');
        $status_arr = array_column($order_status_list, 'id');

        // 循环获取统计数据
        $data = [];
        $value_arr = [];
        $name_arr = [];
        $date = self::DayCreate($params['start'], $params['end']);
        foreach($date as $day)
        {
            // 当前日期名称
            $name_arr[] = date('Y-m-d', $day['start']);

            // 根据状态获取数量
            foreach($status_arr as $status)
            {
                // 获取订单
                $where = [
                    ['status', '=', $status],
                    ['add_time', '>=', $day['start']],
                    ['add_time', '<=', $day['end']],
                ];
                $value_arr[$status][] = Db::name('Order')->where($where)->count();
            }
        }

        // 数据格式组装
        foreach($status_arr as $status)
        {
            $data[] = [
                'name'      => $order_status_list[$status]['name'],
                'type'      => ($status == 4) ? 'bar' : 'line',
                'tiled'     => MyLang('common_service.statistical.stats_total_name'),
                'data'      => empty($value_arr[$status]) ? [] : $value_arr[$status],
            ];
        }

        // 数据组装
        $result = [
            'title_arr' => array_column($order_status_list, 'name'),
            'name_arr'  => $name_arr,
            'data'      => $data,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 订单收益趋势
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderProfitTotal($params = [])
    {
        // 订单状态列表
        $order_status_list = MyConst('common_order_status');
        $status_arr = array_column($order_status_list, 'id');

        // 循环获取统计数据
        $data = [];
        $value_arr = [];
        $name_arr = [];

        // 订单收入总计、是否有收入统计权限
        if(AdminIsPower('index', 'income'))
        {
            $date = self::DayCreate($params['start'], $params['end']);
            foreach($date as $day)
            {
                // 当前日期名称
                $name_arr[] = date('Y-m-d', $day['start']);

                // 根据状态获取数量
                foreach($status_arr as $status)
                {
                    // 获取订单
                    $where = [
                        ['status', '=', $status],
                        ['add_time', '>=', $day['start']],
                        ['add_time', '<=', $day['end']],
                    ];
                    $value_arr[$status][] = Db::name('Order')->where($where)->sum('pay_price');
                }
            }

            // 数据格式组装
            foreach($status_arr as $status)
            {
                $data[] = [
                    'name'      => $order_status_list[$status]['name'],
                    'type'      => ($status == 4) ? 'line' : 'bar',
                    'tiled'     => MyLang('common_service.statistical.stats_total_name'),
                    'data'      => empty($value_arr[$status]) ? [] : $value_arr[$status],
                ];
            }
        }

        // 数据组装
        $result = [
            'title_arr' => array_column($order_status_list, 'name'),
            'name_arr'  => $name_arr,
            'data'      => $data,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 热销商品
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function GoodsHotTotal($params = [])
    {    
        // 获取订单id
        $where = [
            ['status', '<=', 4],
        ];
        if(!empty($params['start']))
        {
            $where[] = ['add_time', '>=', $params['start']];
        }
        if(!empty($params['end']))
        {
            $where[] = ['add_time', '<=', $params['end']];
        }
        $order_ids = Db::name('Order')->where($where)->column('id');

        // 获取订单详情热销商品
        if(empty($order_ids))
        {
            $data = [];
        } else {
            $data = Db::name('OrderDetail')->field('goods_id, sum(buy_number) AS value')->where('order_id', 'IN', $order_ids)->group('goods_id')->order('value desc')->limit(13)->select()->toArray();
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
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 支付方式
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PayTypeTotal($params = [])
    {
        // 获取支付方式名称
        $where = [
            ['business_type', '<>', ''],
            ['status', '=', 1],
        ];
        $pay_name_arr = Db::name('PayLog')->where($where)->group('payment_name')->column('payment_name');


        // 循环获取统计数据
        $data = [];
        $value_arr = [];
        $name_arr = [];
        if(!empty($pay_name_arr))
        {
            $date = self::DayCreate($params['start'], $params['end']);
            foreach($date as $day)
            {
                // 当前日期名称
                $name_arr[] = date('m-d', $day['start']);

                // 根据支付名称获取数量
                foreach($pay_name_arr as $payment)
                {
                    // 获取订单
                    $where = [
                        ['payment_name', '=', $payment],
                        ['add_time', '>=', $day['start']],
                        ['add_time', '<=', $day['end']],
                    ];
                    $value_arr[$payment][] = Db::name('PayLog')->where($where)->count();
                }
            }
        }

        // 数据格式组装
        foreach($pay_name_arr as $payment)
        {
            $data[] = [
                'name'      => $payment,
                'type'      => 'line',
                'stack'     => MyLang('common_service.statistical.stats_total_name'),
                'areaStyle' => (object) [],
                'data'      => empty($value_arr[$payment]) ? [] : $value_arr[$payment],
            ];
        }

        // 数据组装
        $result = [
            'title_arr' => $pay_name_arr,
            'name_arr'  => $name_arr,
            'data'      => $data,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 订单地域分布
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function OrderWholeCountryTotal($params = [])
    {
        // 维度默认省
        $region_arr = ['province_name', 'city_name', 'county_name'];
        $region_name = (empty($params['value']) || !array_key_exists($params['value'], $region_arr)) ? 'od.'.$region_arr[0] : 'od.'.$region_arr[$params['value']];

        // 获取订单id
        $where = [
            ['o.status', '<=', 4],
            ['o.order_model', 'not in', [5,6]],
        ];
        if(!empty($params['start']))
        {
            $where[] = ['o.add_time', '>=', $params['start']];
        }
        if(!empty($params['end']))
        {
            $where[] = ['o.add_time', '<=', $params['end']];
        }
        $data = array_reverse(Db::name('Order')->alias('o')->join('order_address od', 'o.id=od.order_id')->where($where)->field($region_name.' as name, count(o.id) AS value')->group($region_name)->order('value desc')->limit(10)->select()->toArray());
        // 数据组装
        $result = [
            'name_arr'  => array_column($data, 'name'),
            'data'      => array_column($data, 'value'),
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 新增用户
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function NewUserTotal($params = [])
    {
        // 循环获取统计数据
        $data = [];
        $value_arr = [];
        $name_arr = [];
        $date = self::DayCreate($params['start'], $params['end']);
        foreach($date as $day)
        {
            // 当前日期名称
            $name_arr[] = date('m-d', $day['start']);

            // 用户总数
            $where = [
                ['add_time', '>=', $day['start']],
                ['add_time', '<=', $day['end']],
            ];
            $value_arr[] = Db::name('User')->where($where)->count();
        }

        // 数据格式组装
        $data[] = [
            'name'  => MyLang('common_service.statistical.stats_total_name'),
            'type'  => 'line',
            'data'  => $value_arr,
        ];

        // 数据组装
        $result = [
            'name_arr'  => $name_arr,
            'data'      => $data,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 下单用户
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function BuyUserTotal($params = [])
    {
        // 循环获取统计数据
        $data = [];
        $value_arr = [];
        $name_arr = [];
        $date = self::DayCreate($params['start'], $params['end']);
        foreach($date as $day)
        {
            // 当前日期名称
            $name_arr[] = date('m-d', $day['start']);

            // 用户总数
            $where = [
                ['add_time', '>=', $day['start']],
                ['add_time', '<=', $day['end']],
                ['status', 'not in', [5,6]],
            ];
            $value_arr[] = Db::name('Order')->where($where)->count();
        }

        // 数据格式组装
        $data[] = [
            'name'  => MyLang('common_service.statistical.stats_total_name'),
            'type'  => 'line',
            'data'  => $value_arr,
        ];

        // 数据组装
        $result = [
            'name_arr'  => $name_arr,
            'data'      => $data,
        ];
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 统计数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-30
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StatsData($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => MyLang('common_service.statistical.stats_type_empty_tips'),
            ],
        ];
        if(isset($params['type']) && in_array($params['type'], ['order-profit', 'order-trading', 'pay-type']))
        {
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'start',
                'error_msg'         => MyLang('common_service.statistical.stats_time_start_tips'),
            ];
            $p[] = [
                'checked_type'      => 'empty',
                'key_name'          => 'end',
                'error_msg'         => MyLang('common_service.statistical.stats_time_end_tips'),
            ];
        }
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 时间处理
        if(!empty($params['start']))
        {
            $params['start'] = strtotime($params['start']);
        }
        if(!empty($params['end']))
        {
            $params['end'] = strtotime($params['end']);
        }
        if(!empty($params['start']) && !empty($params['end']) && $params['end'] < $params['start'])
        {
            return DataReturn(MyLang('common_service.statistical.stats_time_error_tips'), -1);
        }

        // 根据类型处理数据
        switch($params['type'])
        {
            // 全部
            case 'all' :
                $order_profit = self::OrderProfitTotal($params);
                $order_trading = self::OrderTradingTotal($params);
                $goods_hot = self::GoodsHotTotal($params);
                $pay_type = self::PayTypeTotal($params);
                $order_whole_country = self::OrderWholeCountryTotal($params);
                $renew_usert = self::NewUserTotal($params);
                $buy_user = self::BuyUserTotal($params);
                $new_user = self::NewUserTotal($params);
                $ret = DataReturn('success', 0, [
                    'order_profit'         => $order_profit['data'],
                    'order_trading'        => $order_trading['data'],
                    'goods_hot'            => $goods_hot['data'],
                    'pay_type'             => $pay_type['data'],
                    'order_whole_country'  => $order_whole_country['data'],
                    'renew_usert'          => $renew_usert['data'],
                    'buy_user'             => $buy_user['data'],
                    'new_user'             => $new_user['data'],
                ]);
                break;

            // 基础配置
            case 'base-count' :
                $ret = self::BaseTotalCount($params);
                break;

            // 订单成交金额走势
            case 'order-profit' :
                $ret = self::OrderProfitTotal($params);
                break;

            // 订单交易走势
            case 'order-trading' :
                $ret = self::OrderTradingTotal($params);
                break;

            // 热销商品
            case 'goods-hot' :
                $ret = self::GoodsHotTotal($params);
                break;

            // 支付方式
            case 'pay-type' :
                $ret = self::PayTypeTotal($params);
                break;

            // 订单地域分布
            case 'order-whole-country' :
                $ret = self::OrderWholeCountryTotal($params);
                break;

            // 新增用户
            case 'new-user' :
                $ret = self::NewUserTotal($params);
                break;

            // 下单用户
            case 'buy-user' :
                $ret = self::BuyUserTotal($params);
                break;

            default :
                $ret = DataReturn(MyLang('common_service.statistical.stats_type_error_tips'), -1);
        }
        return $ret;
    }
}
?>