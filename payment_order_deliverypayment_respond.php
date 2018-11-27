<?php

/**
 * 订单支付同步入口
 */

// 默认绑定模块
$_GET['m'] = 'Home';
$_GET['c'] = 'Order';
$_GET['a'] = 'Respond';

// 支付模块标记
define('PAYMENT_TYPE', 'DeliveryPayment');

// 引入公共入口文件
require './core.php';

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

?>