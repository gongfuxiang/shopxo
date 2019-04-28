<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace base;

/**
 * 行为记录
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-04-11T16:50:41+0800
 */
class Behavior
{
	/**
	 * [__construct 开始收集数据]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:51:02+0800
	 * @param    [array]     $param [参数]
	 */
	public function __construct($param = array())
	{
		// 数据列表
		$data = array(
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
			);

		// 描述信息
		if(!empty($param['msg']))
		{
			$data['msg'] = $param['msg'];
		}

		// mysql版本
		if(!empty($param['mysql_version']))
		{
			$data['mysql_version'] = $param['mysql_version'];
		}

		// 上报数据
		$url = 'http://report.shopxo.net/install.php';
		if(function_exists('curl_init'))
		{
			$this->CurlPost($url, $data);
		} else {
			$this->FsockopenPost($url, $data);
		}
	}

	/**
	* [CurlPost curl post]
	* @author   Devil
	* @blog     http://gong.gg/
	* @version  0.0.1
	* @datetime 2016-12-03T21:58:54+0800
	* @param    [string] $url  [请求地址]
	* @param    [array]  $post [发送的post数据]
	*/
	private function CurlPost($url, $post)
	{
		$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => false,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => $post,
			);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);

		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	/**
	 * [FsockopenPost fsockopen方式]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T21:58:54+0800
	 * @param    [string] $url  [url地址]
	 * @param    [string] $data [发送参数]
	 */
	private function FsockopenPost($url, $data = '')
	{
	    $row = parse_url($url);
	    $host = $row['host'];
	    $port = isset($row['port']) ? $row['port'] : 80;
	    $file = $row['path'];
	    $post = '';
	    while (list($k,$v) = FunEach($data)) 
	    {
	        if(isset($k) && isset($v)) $post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
	    }
	    $post = substr( $post , 0 , -1 );
	    $len = strlen($post);
	    $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
	    if (!$fp) {
	        return "$errstr ($errno)\n";
	    } else {
	        $receive = '';
	        $out = "POST $file HTTP/1.0\r\n";
	        $out .= "Host: $host\r\n";
	        $out .= "Content-type: application/x-www-form-urlencoded\r\n";
	        $out .= "Connection: Close\r\n";
	        $out .= "Content-Length: $len\r\n\r\n";
	        $out .= $post;    
	        fwrite($fp, $out);
	        while (!feof($fp)) {
	          $receive .= fgets($fp, 128);
	        }
	        fclose($fp);
	        $receive = explode("\r\n\r\n",$receive);
	        unset($receive[0]);
	        return implode("",$receive);
	    }
	}

	/**
	 * [GetScheme http类型]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:04:33+0800
	 */
	private function GetScheme()
	{
		return empty($_SERVER['HTTPS']) ? 'HTTP' : 'HTTPS';
	}

	/**
	 * [GetClinet 客户端]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:04:56+0800
	 */
	private function GetClinet()
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
	private function GetHttpVersion()
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
	private function GetMethod()
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
	private function GetOs()
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
	private function GetBrowser()
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
	private function GetUrl($type = 'url')
	{
		// 当前host
		$host = empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST'];

		// 是否获取host
		if($type == 'host')
		{
			return $host;
		}

		// http类型
		$http = empty($_SERVER['HTTPS']) ? 'http' : 'https';

		// 根目录
		$root = '';
		if(!empty($_SERVER['SCRIPT_NAME']))
		{
			$root = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')+1);
		} else {
			if(!empty($_SERVER['PHP_SELF']))
			{
				$root = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')+1);
			}
		}

		// url 或 request
		if($type == 'url')
		{
			return $http.'://'.$host.$root;
		} else {
			if(!empty($_SERVER['REQUEST_URI']))
			{
				return $http.'://'.$host.$_SERVER['REQUEST_URI'];
			}
		}
	}

	/**
	 * [GetServerIP 获取服务器ip]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:34:24+0800
	 */
	private function GetServerIP()
	{
		return empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'];
	}

	/**
	 * [GetServerPort 获取当前web端口]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T16:35:42+0800
	 */
	private function GetServerPort()
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
		$onlineip = ''; 
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown'))
		{ 
			$onlineip = getenv('HTTP_CLIENT_IP'); 
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
		{ 
			$onlineip = getenv('HTTP_X_FORWARDED_FOR'); 
		} elseif(getenv('REMOTE_ADDR' ) && strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
		{ 
			$onlineip = getenv('REMOTE_ADDR'); 
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
		{ 
			$onlineip = $_SERVER['REMOTE_ADDR']; 
		} 
		if($long)
		{
			$onlineip = sprintf("%u", ip2long($realip));
		}
		return $onlineip;
	}

	/**
	 * [GetSourceUrl 获取来源url地址]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-04-11T15:57:00+0800
	 */
	private function GetSourceUrl()
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
	private function GetUserCookie()
	{
		if(!empty($_COOKIE['behavior_user_cookie'])) return $_COOKIE['behavior_user_cookie'];

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
	private function GetUserNumberRand()
	{
		$str = date('YmdHis');
		for($i=0; $i<6; $i++) $str .= rand(0, 9);
		return $str;
	}
}
?>