<?php

/**
 * 订单支付异步入口
 */

// 默认绑定模块
$_GET['m'] = 'Api';
$_GET['c'] = 'OrderNotify';
$_GET['a'] = 'Notify';

// 引入公共入口文件
require './core.php';

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

?>