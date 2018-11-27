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
 * 机器人检测
 * @author   liu21st <liu21st@gmail.com>
 */
class RobotCheckBehavior {
    
    public function run(&$params) {
        // 机器人访问检测
        if(C('LIMIT_ROBOT_VISIT',null,true) && self::isRobot()) {
            // 禁止机器人访问
            exit('Access Denied');
        }
    }

    static private function isRobot() {
        static $_robot = null;
        if(is_null($_robot)) {
            $spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
            $browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
            if(preg_match("/($browsers)/", $_SERVER['HTTP_USER_AGENT'])) {
                $_robot	 =	  false ;
            } elseif(preg_match("/($spiders)/", $_SERVER['HTTP_USER_AGENT'])) {
                $_robot	 =	  true;
            } else {
                $_robot	 =	  false;
            }
        }
        return $_robot;
    }
}