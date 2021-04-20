<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace base;

/**
 * 基础信息驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-04-11T16:50:41+0800
 */
class Behavior
{
	/**
	 * 上报安装日志
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-09-23
	 * @desc    description
	 * @param   [array]           $params [输入参数]
	 */
	public function ReportInstallLog($params = [])
	{
		// 数据列表
		$data = [
			'user'			=>	$this->GetUserCookie(),
			'host'			=>	$this->GetUrl('host'),
			'server_port'	=>	$this->GetServerPort(),
			'server_ip'		=>	$this->GetServerIP(),
			'url'			=>	$this->GetUrl('url'),
			'request_url'	=>	$this->GetUrl('request'),
			'source_url'	=>	$this->GetSourceUrl(),
			'client_ip'		=>	$this->GetClientIP(),
			'os'			=>	$this->GetOs(),
			'browser'		=>	$this->GetBrowser(),
			'method'		=>	$this->GetMethod(),
			'scheme'		=>	$this->GetScheme(),
			'version'		=>	$this->GetHttpVersion(),
			'client'		=>	$this->GetClinet(),
			'php_os'		=>	PHP_OS,
			'php_version'	=>	PHP_VERSION,
			'php_sapi_name'	=>	php_sapi_name(),
			'client_date'	=>	date('Y-m-d H:i:s'),
			'ymd'			=>	date('Ymd'),
			'ver'			=>	str_replace('v', '', APPLICATION_VERSION),
		];

		// 描述信息
		if(!empty($params['msg']))
		{
			$data['msg'] = $params['msg'];
		}

		// mysql版本
		if(!empty($params['mysql_version']))
		{
			$data['mysql_version'] = $params['mysql_version'];
		}

		// 上报数据
		$url = 'http://report.shopxo.net/install.php';
		if(function_exists('curl_init'))
		{
			CurlPost($url, $data);
		} else {
			FsockopenPost($url, $data);
		}
	}

	/**
	 * [GetScheme http类型]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:04:33+0800
	 */
	public function GetScheme()
	{
		return strtoupper(__MY_HTTP__);
	}

	/**
	 * [GetClinet 客户端]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:04:56+0800
	 */
	public function GetClinet()
	{
		return empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * [GetHttpVersion http版本]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:04:43+0800
	 */
	public function GetHttpVersion()
	{
		return empty($_SERVER['SERVER_PROTOCOL']) ? '' : str_replace(array('HTTP/', 'HTTPS/'), '', $_SERVER['SERVER_PROTOCOL']);
	}

	/**
	 * [GetMethod 请求类型]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:04:23+0800
	 */
	public function GetMethod()
	{
		return empty($_SERVER['REQUEST_METHOD']) ? '' : $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * [GetOs 用户操作系统]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:02:06+0800
	 */
	public function GetOs()
	{  
		if(!empty($_SERVER['HTTP_USER_AGENT']))
		{  
			$os = $_SERVER['HTTP_USER_AGENT'];  
			if(preg_match('/win/i', $os))
			{  
				$os = 'Windows';
			} elseif (preg_match('/mac/i',$os))
			{
				$os = 'MAC';
			} elseif (preg_match('/linux/i', $os))
			{
				$os = 'Linux';
			} elseif (preg_match('/unix/i', $os))
			{
				$os = 'Unix';
			} elseif (preg_match('/bsd/i', $os))
			{  
				$os = 'BSD';
			} elseif (preg_match('/iphone/i', $os))
			{  
				$os = 'iPhone';
			} elseif (preg_match('/android/i', $os))
			{  
				$os = 'Android';
			} else {
				$os = 'Other';
			}
			return $os;
		}
		return 'unknown';
	}

	/**
	 * [GetBrowser 用户浏览器]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:03:14+0800
	 */
	public function GetBrowser()
	{
		if(!empty($_SERVER['HTTP_USER_AGENT']))
		{
			$br = $_SERVER['HTTP_USER_AGENT'];
			if(preg_match('/MSIE/i', $br))
			{
				$br = 'MSIE';  
			} elseif(preg_match('/Firefox/i', $br))
			{  
				$br = 'Firefox';  
			} elseif(preg_match('/Chrome/i', $br))
			{  
				$br = 'Chrome';  
			} elseif(preg_match('/Safari/i', $br))
			{  
				$br = 'Safari';  
			} elseif (preg_match('/Opera/i', $br))
			{  
				$br = 'Opera';  
			} else {  
				$br = 'Other';  
			}  
				return $br;  
		}
		return 'unknown';
	}

	/**
	 * [GetUrl 获取url地址]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:29:03+0800
	 * @param    [string]        $type [host:host地址, url:url地址, request:完整url地址]
	 */
	public function GetUrl($type = 'url')
	{
		switch($type)
		{
			// host
			case 'host' :
				return __MY_HOST__;
				break;

			// 当前url
			case 'url' :
				return __MY_URL__;
				break;

			// 当前url+参数
			default :
				return __MY_VIEW_URL__;
		}
	}

	/**
	 * [GetServerIP 获取服务器ip]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:34:24+0800
	 */
	public function GetServerIP()
	{
		return __MY_ADDR__;
	}

	/**
	 * [GetServerPort 获取当前web端口]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:35:42+0800
	 */
	public function GetServerPort()
	{
		return empty($_SERVER['SERVER_PORT']) ? 80 : $_SERVER['SERVER_PORT'];
	}

	/**
	 * [GetClientIP 获取用户ip]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:00:10+0800
	 * @param    [boolean]     $long [是否转换成整数]
	 */
	function GetClientIP($long = false)
	{
		return GetClientIP($long);
	}

	/**
	 * [GetSourceUrl 获取来源url地址]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T15:57:00+0800
	 */
	public function GetSourceUrl()
	{
		return empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
	}

	/**
	 * [GetUserCookie 获取用户cookieid]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T15:55:12+0800
	 */
	public function GetUserCookie()
	{
		if(!empty($_COOKIE['behavior_user_cookie']))
		{
			return $_COOKIE['behavior_user_cookie'];
		}

		$user_cookie = $this->GetUserNumberRand();
		setcookie('behavior_user_cookie', $user_cookie);
		return $user_cookie;
	}

	/**
	 * [GetUserNumberRand 生成用户cookie编号]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T15:56:14+0800
	 */
	public function GetUserNumberRand()
	{
		$str = date('YmdHis');
		for($i=0; $i<6; $i++) $str .= rand(0, 9);
		return $str;
	}
}
?>