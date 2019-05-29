<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/
require_once 'phpqrcode/phpqrcode.php';
$url = urldecode($_GET["data"]);
if(substr($url, 0, 6) == "weixin"){
	QRcode::png($url);
}else{
	 header('HTTP/1.1 404 Not Found');
}
