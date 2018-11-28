<?php
require("class.phpmailer.php");  
$mail = new phpmailer(); //建立邮件发送类
$mail->IsSMTP();//使用smtp方式发送
$mail->Host = 'smtp.163.com';
$mail->SMTPAuth = true;//smtp验证功能；
$mail->Username = 'weiletao88@163.com';//邮箱用户名
$mail->Password = 'weiletao?';//s邮箱密码
$mail->From = 'weiletao88@163.com';//发件人
$mail->FromName = "真租网";//发件人姓名
$mail->AddAddress("1655098383@qq.com", "用户接收111");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")

$mail->IsHTML(true);//是否开启html格式
$mail->CharSet = "utf-8";//设置编码

$mail->Subject = "邮件标题";//邮件标题
$mail->Body = "<b style='color:#F00;font-size:30px;'>ddddtest</b><img src='http://localhost/zhenzuwang/zzw/Public/Home/Images/logo.png' />";//邮件内容
$mail->AltBody = "这是一封测试邮件";//邮件正文不支持HTML的备用显示
$mail->AddAttachment("../aaa.docx"); // 添加附件

if(!$mail->Send()){
	echo "发送失败";
	echo "错误原因：".$mail->ErrorInfo;exit;
}else{
	echo "发送成功！";
}
?>