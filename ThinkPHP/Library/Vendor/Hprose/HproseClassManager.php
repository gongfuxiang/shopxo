<?php
/**********************************************************\
|                                                          |
|                          hprose                          |
|                                                          |
| Official WebSite: http://www.hprose.com/                 |
|                   http://www.hprose.net/                 |
|                   http://www.hprose.org/                 |
|                                                          |
\**********************************************************/

/**********************************************************\
 *                                                        *
 * HproseClassManager.php                                 *
 *                                                        *
 * hprose class manager library for php5.                 *
 *                                                        *
 * LastModified: Nov 12, 2013                             *
 * Author: Ma Bingyao <andot@hprfc.com>                   *
 *                                                        *
\**********************************************************/

class HproseClassManager {
    private static $classCache1 = array();
    private static $classCache2 = array();
    public static function register($class, $alias) {
        self::$classCache1[$alias] = $class;
        self::$classCache2[$class] = $alias;        
    }
    public static function getClassAlias($class) {
        if (array_key_exists($class, self::$classCache2)) {
            return self::$classCache2[$class];
        }
        $alias = str_replace('\\', '_', $class);
        self::register($class, $alias);
        return $alias;
    }
    public static function getClass($alias) {
        if (array_key_exists($alias, self::$classCache1)) {
            return self::$classCache1[$alias];
        }
        if (!class_exists($alias)) {
            $class = str_replace('_', '\\', $alias);
            if (class_exists($class)) {
                self::register($class, $alias);
                return $class;
            }
            eval("class " . $alias . " { }");
        }
        return $alias;
    }
}
?>