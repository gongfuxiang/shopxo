<?php
namespace app\admin\controller;

use app\service\ConfigService;

/**
 * 邮箱设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Email extends Common
{
	/**
	 * 构造方法
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 配置列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 配置信息
		$this->assign('data', ConfigService::ConfigList());
		$type = input('type', 'email');

		// 导航
		$this->assign('nav_type', $type);
		if($type == 'email')
		{
			return $this->fetch('Index');
		} else {
			return $this->fetch('Message');
		}
	}

	/**
	 * [Save 配置数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	public function Save()
	{
		return ConfigService::ConfigSave($_POST);
	}

	/**
	 * [EmailTest 邮件测试]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T15:30:10+0800
	 */
	public function EmailTest()
	{
		// 验证码公共基础参数
		$verify_param = array(
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);

		$obj = new \base\Email($verify_param);
		$email_param = array(
				'email'		=>	input('email'),
				'content'	=>	'邮件配置-发送测试内容',
				'title'		=>	MyC('home_site_name').' - '.'测试',
			);
		// 发送
		if($obj->SendHtml($email_param))
		{
			return json(DataReturn('发送成功'));
		} else {
			return json(DataReturn('发送失败'.'['.$obj->error.']', -100));
		}
	}
}
?>