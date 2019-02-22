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
            self::${$name} = $date;
        }
        
    }
    /**
     * 用户总数,今日,昨日
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
        $data = [
            'total_count'       => $total_count,
            'yesterday_count'   => $yesterday_count,
            'today_count'       => $today_count,
        ];
        return DataReturn('处理成功', 0, $data);
    }

}
?>