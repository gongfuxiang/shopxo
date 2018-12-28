<?php

/**
 * 订单支付异步入口
 */

// 默认绑定模块
$_GET['s'] = '/api/ordernotify/notify';

// 支付模块标记
define('PAYMENT_TYPE', 'Alipay');

// 引入入口文件
require __DIR__.'/index.php';
?>