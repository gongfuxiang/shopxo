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

use app\service\EmailLogService;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Email驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-01-10T21:51:08+0800
 */
class Email
{
	private $interval_time;
	private $expire_time;
	private $key_code;
	private $is_frq;
	private $is_log;
	public  $error;
	private $obj;

	/**
	 * 构造方法
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-07T14:03:02+0800
	 * @param    [int]        $params['interval_time'] 	[间隔时间（默认30）单位（秒）]
	 * @param    [int]        $params['expire_time'] 	[到期时间（默认30）单位（秒）]
	 * @param    [string]     $params['key_prefix']     [验证码种存储前缀key（默认 空）]
	 * @param    [string]     $params['is_frq']			[是否验证频率（默认 是）]
	 * @param    [string]     $params['is_log']         [是否记录日志（默认 是）]
	 */
	public function __construct($params = [])
	{
		$this->interval_time = isset($params['interval_time']) ? intval($params['interval_time']) : 30;
		$this->expire_time = isset($params['expire_time']) ? intval($params['expire_time']) : 30;
		$this->key_code = isset($params['key_prefix']) ? trim($params['key_prefix']).'_sms_code' : '_sms_code';
		$this->is_frq = isset($params['is_frq']) ? intval($params['is_frq']) : 1;
		$this->is_log = isset($params['is_log']) ? intval($params['is_log']) : 1;
	}

	/**
	 * 邮件初始化
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T11:03:38+0800
	 */
	private function EmailInit()
	{
		// 建立邮件发送类  
		$this->obj = new PHPMailer();

		// 使用smtp方式发送
		$this->obj->IsSMTP();

		// 服务器host地址
		$this->obj->Host = MyC('common_email_smtp_host');

		//smtp验证功能；
		$this->obj->SMTPAuth = true;

		// 端口
		$this->obj->Port = MyC('common_email_smtp_port', 25, true);

		// SSL方式加密
		if(MyC('common_email_is_use_ssl', 0, true) == 1)
		{
			$this->obj->SMTPSecure = 'ssl';
		}

		// 邮箱用户名
		$this->obj->Username =  MyC('common_email_smtp_name');

		// 邮箱密码
		$this->obj->Password = MyC('common_email_smtp_pwd');

		// 发件人
		$this->obj->From = MyC('common_email_smtp_account');

		// 发件人姓名
		$this->obj->FromName = MyC('common_email_smtp_send_name');

		// 是否开启html格式
		$this->obj->isHTML(true);

		// 设置编码
		$this->obj->CharSet = 'utf-8';
	}

	/**
	 * html邮件发送
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T10:56:43+0800
	 * @param    [string]   $params['email'] 		[收件邮箱]
	 * @param    [string]   $params['content'] 		[内容]
	 * @param    [string]   $params['title'] 		[标题]
	 * @param    [string]   $params['code'] 		[验证码]
	 * @param    [string]   $params['username'] 	[收件人名称]
	 */
	public function SendHtml($params = [])
	{
		if(empty($params['email']))
		{
			$this->error = MyLang('common_extend.base.email.email_empty_tips');
			return false;
		}
		if(empty($params['content']))
		{
			$this->error = MyLang('common_extend.base.email.content_empty_tips');
			return false;
		}
		if(empty($params['title']))
		{
			$this->error = MyLang('common_extend.base.email.title_empty_tips');
			return false;
		}

		// 是否频繁操作
		if($this->is_frq == 1)
		{
			if(!$this->IntervalTimeCheck())
			{
				$this->error = MyLang('operate_frequent_tips');
				return false;
			}
		}

		// 验证码替换
		$params_code = empty($params['code']) ? '' : $params['code'];
		if(!empty($params_code))
		{
			$params['content'] = str_replace('#code#', $params_code, $params['content']);
		}

		// 邮件初始化
		$this->EmailInit();

		// 收件人地址，可以替换成任何想要接收邮件的email信箱,格式("收件人email","收件人姓名")
		if(!is_array($params['email']))
		{
			$params['email'] = explode(',', $params['email']);
		}
		foreach($params['email'] as $email)
		{
			$username = isset($params['username']) ? $params['username'] : $email;
			$this->obj->AddAddress($email, $username);
		}

		// 邮件标题
		$this->obj->Subject = $params['title'];

		// 邮件内容
		$this->obj->Body = $params['content'];

		// 邮件正文不支持HTML的备用显示
		$this->obj->AltBody = strip_tags($params['content']);

		// 添加短信日志
		if($this->is_log == 1)
		{
	        $log = EmailLogService::EmailLogAdd($this->obj->Host, $this->obj->Port, $this->obj->Username, $this->obj->From, $this->obj->FromName, $params['email'], $params['title'], $params['content'], $params_code);
	        if($log['code'] != 0)
	        {
	            $this->error = $log['msg'];
	            return false;
	        }
        }

		// 发送邮件
		if($this->obj->Send())
		{
			// 种session
			if($this->is_frq == 1)
			{
				$this->KindofSession(empty($params_code) ? '' : $params_code);
			}

			// 日志回调
			if($this->is_log == 1)
			{
				EmailLogService::EmailLogResponse($log['data']['id'], 1, time()-$log['data']['add_time']);
			}

			return true;
		} else {
			$this->error = $this->obj->ErrorInfo;

			// 日志回调
			if($this->is_log == 1)
			{
				EmailLogService::EmailLogResponse($log['data']['id'], 2, time()-$log['data']['add_time'], $this->error);
			}
		}
		return false;
	}

	/**
	 * 种验证码session
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-07T14:59:13+0800
	 * @param    [string]      $value [存储值或验证码]
	 */
	private function KindofSession($value = '')
	{
		$data = [
			'value'	=> $value,
			'time' 	=> time(),
		];
		MyCache($this->key_code, $data, $this->expire_time);
	}

	/**
	 * 验证码是否过期
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T19:02:26+0800
	 * @return   [boolean] [有效true, 无效false]
	 */
	public function CheckExpire()
	{
		$data = MyCache($this->key_code);
		if(!empty($data))
		{
			return (time() <= $data['time']+$this->expire_time);
		}
		return false;
	}

	/**
	 * 验证码是否正确
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T16:55:00+0800
	 * @param    [string] $code    [验证码（默认从post读取）]
	 * @return   [booolean]        [正确true, 错误false]
	 */
	public function CheckCorrect($code = '')
	{
		// 安全验证
        if(SecurityPreventViolence($this->key_code, 1, $this->expire_time))
        {
        	// 验证是否正确
        	$data = MyCache($this->key_code);
			if(!empty($data))
			{
				if(empty($code) && !empty($_POST['code']))
				{
					$code = trim($_POST['code']);
				}
				return (isset($data['value']) && $data['value'] == $code);
			}
        }
		return false;
	}

	/**
	 * 验证码清除
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-08T10:18:20+0800
	 * @return   [other] [无返回值]
	 */
	public function Remove()
	{
		MyCache($this->key_code, null);
		SecurityPreventViolence($this->key_code, 0);
	}

	/**
	 * 是否已经超过控制的间隔时间
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T11:26:52+0800
	 * @return   [booolean]        [已超过间隔时间true, 未超过间隔时间false]
	 */
	private function IntervalTimeCheck()
	{
		$data = MyCache($this->key_code);
		if(!empty($data))
		{
			return (time() > $data['time']+$this->interval_time);
		}
		return true;
	}
}
?>