<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Behavior;
/**
 * 浏览器防刷新检测
 */
class BrowserCheckBehavior {
    public function run(&$params) {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            //	启用页面防刷新机制
            $guid	=	md5($_SERVER['PHP_SELF']);
            // 浏览器防刷新的时间间隔（秒） 默认为10
            $refleshTime    =   C('LIMIT_REFLESH_TIMES',null,10);
            // 检查页面刷新间隔
            if(cookie('_last_visit_time_'.$guid) && cookie('_last_visit_time_'.$guid)>time()-$refleshTime) {
                // 页面刷新读取浏览器缓存
                header('HTTP/1.1 304 Not Modified');
                exit;
            }else{
                // 缓存当前地址访问时间
                cookie('_last_visit_time_'.$guid, $_SERVER['REQUEST_TIME']);
                //header('Last-Modified:'.(date('D,d M Y H:i:s',$_SERVER['REQUEST_TIME']-C('LIMIT_REFLESH_TIMES'))).' GMT');
            }
        }
    }
}