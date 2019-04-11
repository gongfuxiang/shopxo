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
namespace base;

/**
 * 年龄计算
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2019-04-12
 */
class Age {
    /**
     * 计算年龄精准到年月日
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-12T00:25:13+0800
     * @param    [date]                   $birthday [日期]
     * @return   [array]                            [计算后的时间]
     */
    public static function CalAge($birthday)
    {
        list($byear, $bmonth, $bday) = explode('-', $birthday);
        list($year, $month, $day) = explode('-', date('Y-m-d'));
        $bmonth = intval($bmonth);
        $bday = intval($bday);
        if($bmonth < 10)
        {
            $bmonth = '0' . $bmonth;
        }
        if($bday < 10)
        {
            $bday = '0' . $bday;
        }
        $bi = intval($byear . $bmonth . $bday);
        $ni = intval($year . $month . $day);
        $not_birth = 0;
        if($bi > $ni)
        {
            $not_birth = 1;
            $tmp = array($byear, $bmonth, $bday);
            list($byear, $bmonth, $bday) = array($year, $month, $day);
            list($year, $month, $day) = $tmp;
            list($bi, $ni) = array($ni, $bi);
        }

        //先取岁数
        $years = 0;
        while(($bi + 10000) <= $ni)
        {
            $bi += 10000;
            $years++;
            $byear++;
        }

        //得到岁数后 抛弃年
        list($m, $d) = self::GetMd(array($year, $month, $day), array($byear, $bmonth, $bday));
        return array('year' => $years, 'month' => $m, 'day' => $d, 'not_birth' => $not_birth);
    }

    /**
     * 只能用于一年内计算
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-12T00:24:40+0800
     * @param    [date]                   $ymd  [ymd]
     * @param    [date]                   $bymd [bymd]
     */
    public static function GetMd($ymd, $bymd)
    {
        list($y, $m, $d) = $ymd;
        list($by, $bm, $bd) = $bymd;
        if (($m . $d) < ($bm . $bd)) {
            $m +=12;
        }

        $month = 0;
        while ((($bm . $bd) + 100) <= ($m . $d)) {
            $bm++;
            $month++;
        }

        //同处一个月
        if($bd <= $d)
        {
            $day = $d - $bd;
        } else {
            //少一个月
            $mdays = $bm > 12 ? self::GetMothDay( ++$by, $bm - 12) : self::GetMothDay($by, $bm);
            $day = $mdays - $bd + $d;
        }
        return array($month, $day);
    }

    /**
     * 月
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-04-12T00:26:48+0800
     * @param    [int]                   $year  [年]
     * @param    [int]                   $month [月]
     */
    private static function GetMothDay($year, $month)
    {
        switch($month)
        {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $day = 31;
                break;
            case 2:
                //能被4除尽的为29天其他28天
                $day = (intval($year % 4) ? 28 : 29);
                break;
            default:
                $day = 30;
                break;
        }
        return $day;
    }
}
?>