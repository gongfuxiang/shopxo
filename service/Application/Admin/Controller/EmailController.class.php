<?php

namespace Admin\Controller;

/**
 * 邮箱设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class EmailController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

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
		$data = M('Config')->getField('only_tag,name,describe,value,error_tips');
		$this->assign('data', $data);
		$type = I('type', 'email');
		$this->assign('nav_type', $type);
		if($type == 'email')
		{
			$this->display('Index');
		} else {
			$this->display('Message');
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
		$this->MyConfigSave();
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

		$obj = new \Library\Email($verify_param);
		$email_param = array(
				'email'		=>	I('email'),
				'content'	=>	L('email_test_email_send_content'),
				'title'		=>	MyC('home_site_name').' - '.L('common_operation_test'),
			);
		// 发送
		if($obj->SendHtml($email_param))
		{
			$this->ajaxReturn(L('common_send_success'));
		} else {
			$this->ajaxReturn(L('common_send_error').'['.$obj->error.']', -100);
		}
	}
}
?>