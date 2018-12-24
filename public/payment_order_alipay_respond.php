<?php

/**
 * 订单支付同步入口
 */

namespace think;

// 默认绑定模块
$_GET['s'] = '/index/order/respond';

// 支付模块标记
define('PAYMENT_TYPE', 'Alipay');

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 引入公共入口文件
require './core.php';

// 执行应用并响应
Container::get('app')->run()->send();
?>