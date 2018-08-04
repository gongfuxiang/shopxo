<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;
/**
 * 加密解密类
 */
class Crypt {

    private static $handler    =   '';

    public static function init($type=''){
        $type   =   $type?:C('DATA_CRYPT_TYPE');
        $class  =   strpos($type,'\\')? $type: 'Think\\Crypt\\Driver\\'. ucwords(strtolower($type));
        self::$handler  =    $class;
    }

    /**
     * 加密字符串
     * @param string $str 字符串
     * @param string $key 加密key
     * @param integer $expire 有效期（秒） 0 为永久有效
     * @return string
     */
    public static function encrypt($data,$key,$expire=0){
        if(empty(self::$handler)){
            self::init();
        }
        $class  =   self::$handler; 
        return $class::encrypt($data,$key,$expire);
    }

    /**
     * 解密字符串
     * @param string $str 字符串
     * @param string $key 加密key
     * @return string
     */
    public static function decrypt($data,$key){
        if(empty(self::$handler)){
            self::init();
        }
        $class  =   self::$handler;         
        return $class::decrypt($data,$key);
    }
}