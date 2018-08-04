<?php

/**
 * 订单支付异步入口
 */

// 默认绑定模块
define('BIND_MODULE', 'Api');
define('BIND_CONTROLLER', 'OrderNotify');
define('BIND_ACTION', 'PayNotify');

// 引入公共入口文件
require '../core.php';

// 引入ThinkPHP入口文件
require '../ThinkPHP/ThinkPHP.php';

?>