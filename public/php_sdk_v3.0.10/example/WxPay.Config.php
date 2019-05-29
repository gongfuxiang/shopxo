<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/
require_once "../lib/WxPay.Config.Interface.php";

/**
*
* 该类需要业务自己继承， 该类只是作为deamon使用
* 实际部署时，请务必保管自己的商户密钥，证书等
* 
*/

class WxPayConfig extends WxPayConfigInterface
{
	//=======【基本信息设置】=====================================
	/**
	 * TODO: 修改这里配置为您自己申请的商户信息
	 * 微信公众号信息配置
	 * 
	 * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
	 * 
	 * MCHID：商户号（必须配置，开户邮件中可查看）
	 * 
	 */
	public function GetAppId()
	{
		return '';
	}
	public function GetMerchantId()
	{
		return '';
	}
	
	//=======【支付相关配置：支付成功回调地址/签名方式】===================================
	/**
	* TODO:支付回调url
	* 签名和验证签名方式， 支持md5和sha256方式
	**/
	public function GetNotifyUrl()
	{
		return "";
	}
	public function GetSignType()
	{
		return "HMAC-SHA256";
	}

	//=======【curl代理设置】===================================
	/**
	 * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
	 * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
	 * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
	 * @var unknown_type
	 */
	public function GetProxy(&$proxyHost, &$proxyPort)
	{
		$proxyHost = "0.0.0.0";
		$proxyPort = 0;
	}
	

	//=======【上报信息配置】===================================
	/**
	 * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
	 * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
	 * 开启错误上报。
	 * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
	 * @var int
	 */
	public function GetReportLevenl()
	{
		return 1;
	}


	//=======【商户密钥信息-需要业务方继承】===================================
	/*
	 * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）, 请妥善保管， 避免密钥泄露
	 * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
	 * 
	 * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）， 请妥善保管， 避免密钥泄露
	 * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
	 * @var string
	 */
	public function GetKey()
	{
		return '';
	}
	public function GetAppSecret()
	{
		return '';
	}


	//=======【证书路径设置-需要业务方继承】=====================================
	/**
	 * TODO：设置商户证书路径
	 * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
	 * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
	 * 注意:
	 * 1.证书文件不能放在web服务器虚拟目录，应放在有访问权限控制的目录中，防止被他人下载；
	 * 2.建议将证书文件名改为复杂且不容易猜测的文件名；
	 * 3.商户服务器要做好病毒和木马防护工作，不被非法侵入者窃取证书文件。
	 * @var path
	 */
	public function GetSSLCertPath(&$sslCertPath, &$sslKeyPath)
	{
		$sslCertPath = '../../../cert/apiclient_cert.pem';
		$sslKeyPath = '../../../cert/apiclient_key.pem';
	}
}
