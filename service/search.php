<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 搜索入口
define('BIND_MODULE', 'Home');
define('BIND_CONTROLLER', 'Search');
define('BIND_ACTION', 'Index');

// 引入公共入口文件
require './core.php';

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
?>