<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付样例-查退款单</title>
</head>
<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/
require_once "../lib/WxPay.Api.php";
require_once 'log.php';
require_once "WxPay.Config.php";

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$matches = array();
if( (isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != "" 
		&& !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["transaction_id"], $matches))
	|| (isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""
		&& !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["out_trade_no"], $matches))
	|| (isset($_REQUEST["out_refund_no"]) && $_REQUEST["out_refund_no"] != "" 
		&& !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["out_refund_no"], $matches))
	|| (isset($_REQUEST["refund_id"]) && $_REQUEST["refund_id"] != "" 
		&& !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["refund_id"], $matches)))
{
	header('HTTP/1.1 404 Not Found');  
	exit();
}

function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#f00;'>$key</font> : ".htmlspecialchars($value, ENT_QUOTES)." <br/>";
    }
}

if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
	try{
		$transaction_id = $_REQUEST["transaction_id"];
		$input = new WxPayRefundQuery();
		$input->SetTransaction_id($transaction_id);
		$config = new WxPayConfig();
		printf_info(WxPayApi::refundQuery($config, $input));
	} catch(Exception $e) {
		Log::ERROR(json_encode($e));
	}
}

if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
	try{
		$out_trade_no = $_REQUEST["out_trade_no"];
		$input = new WxPayRefundQuery();
		$input->SetOut_trade_no($out_trade_no);
		$config = new WxPayConfig();
		printf_info(WxPayApi::refundQuery($config, $input));
	} catch(Exception $e) {
		Log::ERROR(json_encode($e));
	}
	exit();
}

if(isset($_REQUEST["out_refund_no"]) && $_REQUEST["out_refund_no"] != ""){
	try{
		$out_refund_no = $_REQUEST["out_refund_no"];
		$input = new WxPayRefundQuery();
		$input->SetOut_refund_no($out_refund_no);
		$config = new WxPayConfig();
		printf_info(WxPayApi::refundQuery($config, $input));
	} catch(Exception $e) {
		Log::ERROR(json_encode($e));
	}
	exit();
}

if(isset($_REQUEST["refund_id"]) && $_REQUEST["refund_id"] != ""){
	try{
		$refund_id = $_REQUEST["refund_id"];
		$input = new WxPayRefundQuery();
		$input->SetRefund_id($refund_id);
		$config = new WxPayConfig();
		printf_info(WxPayApi::refundQuery($config, $input));
	} catch(Exception $e) {
		Log::ERROR(json_encode($e));
	}
	exit();
}
	
?>
<body>  
	<form action="#" method="post">
        <div style="margin-left:2%;color:#f00">微信订单号、商户订单号、微信订单号、微信退款单号选填至少一个，微信退款单号优先：</div><br/>
        <div style="margin-left:2%;">微信订单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="transaction_id" /><br /><br />
        <div style="margin-left:2%;">商户订单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="out_trade_no" /><br /><br />
        <div style="margin-left:2%;">商户退款单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="out_refund_no" /><br /><br />
        <div style="margin-left:2%;">微信退款单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="refund_id" /><br /><br />
		<div align="center">
			<input type="submit" value="查询" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" />
		</div>
	</form>
</body>
</html>
