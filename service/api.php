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

// 后台应用入口文件

// 引入公共入口文件
require './core.php';

// 默认绑定访问Api模块
if(empty($_GET['m']))
{
	define('BIND_MODULE', 'Api');
}

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

?>