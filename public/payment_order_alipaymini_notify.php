<?php

/**
 * 订单支付异步入口
 */
file_put_contents('./gggggg.txt', json_encode($_REQUEST));
// 默认绑定模块
$_GET['s'] = '/api/ordernotify/notify';

// 支付模块标记
define('PAYMENT_TYPE', 'AlipayMini');

// 引入入口文件
require __DIR__.'/index.php';
?>