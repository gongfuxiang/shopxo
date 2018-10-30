<?php

/**
 * 支付宝生活号回调处理
 */

// 默认绑定模块
$_GET['m'] = 'Api';
$_GET['c'] = 'AlipayLife';
$_GET['a'] = 'Index';

// 引入公共入口文件
require './core.php';

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

?>