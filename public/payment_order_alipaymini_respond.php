<?php

/**
 * 订单支付同步入口
 */

// 默认绑定模块
$_GET['s'] = '/index/order/respond';

// 支付模块标记
define('PAYMENT_TYPE', 'AlipayMini');

// 引入入口文件
require __DIR__.'/index.php';
?>