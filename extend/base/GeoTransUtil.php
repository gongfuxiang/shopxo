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
 * 坐标转换工具
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2018-06-04T21:51:08+0800
 */
class GeoTransUtil
{
    private static $x_pi = 3.14159265358979324*3000.0/180.0;
    private static $pi = 3.14159265358979324;
    private static $a = 6378245.0;
    private static $ee = 0.00669342162296594323;
    private static $earth_radius = 6378.137;
    
    /** 
    * 计算两组经纬度坐标 之间的距离 
    * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km); 
    * return m or km 
    */ 

    /**
     * [GetDistance 获取两个坐标之间的距离]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-23T09:33:58+0800
     * @param    [float]                $lng1     [经度1]
     * @param    [float]                $lat1     [维度1]
     * @param    [float]                $lng2     [经度2]
     * @param    [float]                $lat2     [维度2]
     * @param    [int]                  $len_type [返回结果类型（1千米, 2公里）默认千米]
     * @param    [int]                  $decimal  [保留小数位数（默认2）]
     * @return   [int|float]                      [返回千米或公里值]
     */
    public static function GetDistance($lng1, $lat1, $lng2, $lat2, $len_type = 1, $decimal = 2) 
    { 
        $radLat1 = $lat1 * self::$pi / 180.0;
        $radLat2 = $lat2 * self::$pi / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * self::$pi / 180.0) - ($lng2 * self::$pi / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $s = $s * self::$earth_radius;
        if($len_type == 1)
        {
            $s = round($s * 1000);
        }
        return round($s, $decimal);
    }

    /**
     * [GcjToBd 火星坐标(GCJ02坐标，高德，谷歌，腾讯坐标)到百度坐标BD-09]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-18T13:58:14+0800
     * @param    [float]                   $lng [经度]
     * @param    [float]                   $lat [纬度]
     * @return   [array]                        [转换后的进维度]
     */
    public static function GcjToBd($lng, $lat)
    {
        $result = ['lng'=>0, 'lat'=>0];
        if(empty($lng) || empty($lat))
        {
            return $result;
        }

        $x = $lng;
        $y = $lat;
        $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * self::$x_pi);  
        $theta = atan2($y, $x) + 0.000003 * cos($x * self::$x_pi);
        $result['lng'] = $z * cos($theta) + 0.0065; 
        $result['lat'] = $z * sin($theta) + 0.006;
        return $result;
    }
    
    /**
     * [BdToGcj 百度坐标BD-09到火星坐标GCJ02(高德，谷歌，腾讯坐标)]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-18T13:58:14+0800
     * @param    [float]                   $lng [经度]
     * @param    [float]                   $lat [纬度]
     * @return   [array]                        [转换后的进维度]
     */
    public static function BdToGcj($lng, $lat)
    {
        $result = ['lng'=>0, 'lat'=>0];
        if(empty($lng) || empty($lat))
        {
            return $result;
        }

        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * self::$x_pi); 
        $theta = atan2($y, $x) - 0.000003 * cos($x * self::$x_pi);
        $result['lng'] = $z * cos($theta);
        $result['lat'] = $z * sin($theta);
        return $result;
    }
    
    /**
     * [WgsToGcj WGS-84(GPS坐标，谷歌地球坐标) 到 GCJ-02(火星坐标) 的转换]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-18T14:03:26+0800
     * @param    [float]                   $lng [经度]
     * @param    [float]                   $lat [纬度]
     * @return   [array]                        [转换后的进维度]
     */
    public static function WgsToGcj($lng, $lat)
    {
        $result = ['lng'=>0, 'lat'=>0];
        if(empty($lng) || empty($lat))
        {
            return $result;
        }

        //double wgLat, double wgLon, out double mgLat, out double mgLon
        $wgLat = $lat;
        $wgLon = $lng;
        if(self::outOfChina($wgLat, $wgLon))
        {
            return ['lng'=>$wgLon, 'lat'=>$wgLat];
        }
        
        $dLat = GeoTransUtil::transformLat($wgLon - 105.0, $wgLat - 35.0);
        $dLon = GeoTransUtil::transformLon($wgLon - 105.0, $wgLat - 35.0);
        $radLat = $wgLat / 180.0 * self::$pi;
        $magic = sin($radLat);
        $magic = 1 - self::$ee * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $dLat = ($dLat * 180.0) / ((self::$a * (1 - self::$ee)) / ($magic * $sqrtMagic) * self::$pi);
        $dLon = ($dLon * 180.0) / (self::$a / $sqrtMagic * cos($radLat) * self::$pi);
        
        $result['lng'] = $wgLon + $dLon;
        $result['lat'] = $wgLat + $dLat;
        return $result;
    }

    /**
     * [GcjToWgs GCJ-02 到 WGS-84 的转换]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-10-18T14:03:26+0800
     * @param    [float]                   $lng [经度]
     * @param    [float]                   $lat [纬度]
     * @return   [array]                        [转换后的进维度]
     */
    public static function GcjToWgs($lng, $lat)
    {
        $result = ['lng'=>0, 'lat'=>0];
        if(empty($lng) || empty($lat))
        {
            return $result;
        }

        $to = self::WgsToGcj($lng, $lat);
        $lat = $from->x;
        $lon = $from->y;
        $g_lat = $to->x;
        $g_lon = $to->y;
        $d_lat = $g_lat - $lat;
        $d_lon = $g_lon - $lon;
        
        $result['lng'] = $lon - $d_lon;
        $result['lat'] = $lat - $d_lat;
        return $result;
    }

    private static function outOfChina($lat,$lon)
    {
        if ($lon < 72.004 || $lon > 137.8347)
            return true;
        if ($lat < 0.8293 || $lat > 55.8271)
            return true;
        
        return false;
    }

    private static function transformLat($x,$y)
    {
        $ret = -100.0 + 2.0 * $x + 3.0 * $y + 0.2 * $y * $y + 0.1 * $x * $y + 0.2 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * self::$pi) + 20.0 * sin(2.0 * $x * self::$pi)) * 2.0 / 3.0;
        $ret += (20.0 * sin($y * self::$pi) + 40.0 * sin($y / 3.0 * self::$pi)) * 2.0 / 3.0;
        $ret += (160.0 * sin($y / 12.0 * self::$pi) + 320 * sin($y * self::$pi / 30.0)) * 2.0 / 3.0;
        return $ret;
    }

    private static function transformLon($x, $y)
    {
        $ret = 300.0 + $x + 2.0 * $y + 0.1 * $x * $x + 0.1 * $x * $y + 0.1 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * self::$pi) + 20.0 * sin(2.0 * $x * self::$pi)) * 2.0 / 3.0;
        $ret += (20.0 * sin($x * self::$pi) + 40.0 * sin($x / 3.0 * self::$pi)) * 2.0 / 3.0;
        $ret += (150.0 * sin($x / 12.0 * self::$pi) + 300.0 * sin($x / 30.0 * self::$pi)) * 2.0 / 3.0;
        return $ret;
    }
}

/*// 测试demo
require './GeoTransUtil.class.php';

// 高德转百度
//$ret = GeoTransUtil::GcjToBd(106.504943,29.53319);

// 百度转高德
$ret = GeoTransUtil::BdToGcj(106.518492,29.540528);
echo '</br>';
echo $ret['lng'].','.$ret['lat'];*/

?>