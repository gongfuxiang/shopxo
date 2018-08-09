<?php

namespace Home\Controller;

/**
 * 安全
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class SafetyController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();
	}

	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{
		// 安全信息列表
		$this->assign('safety_panel_list', L('safety_panel_list'));

		// 数据列表
		$data = array(
				'mobile'	=>	$this->user['mobile_security'],
				'email'		=>	$this->user['email_security'],
			);
		$this->assign('data', $data);
		$this->display('Index');
	}

	/**
	 * [LoginPwdInfo 登录密码修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function LoginPwdInfo()
	{
		$this->display('LoginPwdInfo');
	}

	/**
	 * [MobileInfo 原手机号码修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function MobileInfo()
	{
		if(empty($this->user['mobile']))
		{
			redirect(U('Home/Safety/NewMobileInfo'));
		}
		$this->display('MobileInfo');
	}

	/**
	 * [NewMobileInfo 新手机号码修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function NewMobileInfo()
	{
		if(!isset($_SESSION['safety_sms']) && !empty($this->user['mobile']))
		{
			$this->error(L('safety_original_accounts_check_error'), U('Home/Safety/MobileInfo'));
		}
		$this->display('NewMobileInfo');
	}

	/**
	 * [EmailInfo 电子邮箱修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function EmailInfo()
	{
		if(empty($this->user['email']))
		{
			redirect(U('Home/Safety/NewEmailInfo'));
		}
		$this->display('EmailInfo');
	}

	/**
	 * [NewEmailInfo 新电子邮箱修改页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:12:20+0800
	 */
	public function NewEmailInfo()
	{
		if(!isset($_SESSION['safety_email']) && !empty($this->user['email']))
		{
			$this->error(L('safety_original_accounts_check_error'), U('Home/Safety/EmailInfo'));
		}
		$this->display('NewEmailInfo');
	}

	/**
	 * [VerifyEntry 验证码显示]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T15:10:21+0800
	 */
	public function VerifyEntry()
	{
		$this->CommonVerifyEntry('safety');
	}

	/**
	 * [LoginPwdUpdate 登录密码修改]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T10:38:23+0800
	 */
	public function LoginPwdUpdate()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 安全设置
		$m = D('Safety');

		// 校验
		if($m->create($_POST, 5) !== false)
		{
			// 获取用户账户信息
			$user = $m->field(array('id', 'pwd', 'salt'))->find($this->user['id']);

			// 密码校验
			if(LoginPwdEncryption(trim(I('my_pwd')), $user['salt']) != $user['pwd'])
			{
				$this->ajaxReturn(L('safety_my_pwd_error'), -4);
			}

			// 更新用户密码
			$salt = GetNumberCode(6);
			$data = array(
					'pwd'		=>	LoginPwdEncryption(trim(I('new_pwd')), $salt),
					'salt'		=>	$salt,
					'upd_time'	=>	time(),
				);

			// 更新数据库
			if($m->where(array('id'=>$this->user['id']))->save($data) !== false)
			{
				$this->ajaxReturn(L('common_operation_success'));
			} else {
				$this->ajaxReturn(L('common_operation_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [VerifySend 验证码发送]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T19:17:10+0800
	 */
	public function VerifySend()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$type = I('type');
		$accounts = I('accounts');
		if(empty($accounts))
		{
			$accounts = ($type == 'sms') ? $this->user['mobile'] : $this->user['email'];
		} else {
			// 帐号是否已存在
			$this->IsExistAccounts($accounts, $type);
		}

		// 验证码基础参数
		$img_verify_param = array(
				'key_prefix' => 'safety',
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);

		// 是否开启图片验证码
		$verify = $this->CommonIsImaVerify($img_verify_param);

		// 发送验证码
		$verify_param = array(
				'key_prefix' => md5('safety_'.$accounts),
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);
		$code = GetNumberCode(6);
		if($type == 'sms')
		{
			$obj = new \My\Sms($verify_param);
			$state = $obj->SendText($accounts, MyC('home_sms_user_mobile_binding'), $code);
		} else {
			$obj = new \My\Email($verify_param);
			$email_param = array(
					'email'		=>	$accounts,
					'content'	=>	MyC('home_email_user_email_binding'),
					'title'		=>	MyC('home_site_name').' - '.L('safety_email_send_title'),
					'code'		=>	$code,
				);
			$state = $obj->SendHtml($email_param);
		}
		
		// 状态
		if($state)
		{
			// 清除验证码
			if(isset($verify) && is_object($verify))
			{
				$verify->Remove();
			}

			$this->ajaxReturn(L('common_send_success'));
		} else {
			$this->ajaxReturn(L('common_send_error').'['.$obj->error.']', -100);
		}
	}

	/**
	 * [IsExistAccounts 帐号是否已存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T18:01:43+0800
	 * @param    [string]       $accounts [帐号, 手机|邮箱]
	 * @param    [string]       $type     [帐号类型, sms|email]
	 */
	private function IsExistAccounts($accounts, $type)
	{
		if($type == 'sms')
		{
			$user = M('User')->where(array('mobile'=>$accounts))->getField('id');
		} else {
			$user = M('User')->where(array('email'=>$accounts))->getField('id');
		}
		if(!empty($user))
		{
			$msg = ($type == 'sms') ? L('common_mobile_exist_error') : L('common_email_exist_error');
			$this->ajaxReturn($msg, -10);
		}
	}

	/**
	 * [VerifyCheck 原账户验证码校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T15:57:19+0800
	 */
	public function VerifyCheck()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$type = I('type');
		$accounts = I('accounts');
		if(empty($accounts))
		{
			$accounts = ($type == 'sms') ? $this->user['mobile'] : $this->user['email'];
		}

		// 验证码校验
		$verify_param = array(
				'key_prefix' => md5('safety_'.$accounts),
				'expire_time' => MyC('common_verify_expire_time')
			);
		if($type == 'sms')
		{
			$obj = new \My\Sms($verify_param);
		} else {
			$obj = new \My\Email($verify_param);
		}
		// 是否已过期
		if(!$obj->CheckExpire())
		{
			$this->ajaxReturn(L('common_verify_expire'), -10);
		}
		// 是否正确
		if($obj->CheckCorrect(I('verify')))
		{
			// 校验成功标记
			$_SESSION['safety_'.$type] = true;

			// 清除验证码
			$obj->Remove();

			$this->ajaxReturn(L('common_success'));
		} else {
			$this->ajaxReturn(L('common_verify_error'), -11);
		}
	}

	/**
	 * [AccountsUpdate 账户更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-28T17:04:36+0800
	 */
	public function AccountsUpdate()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$type = I('type');
		$accounts = I('accounts');
		if(empty($type) || empty($accounts))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 帐号是否已存在
		$this->IsExistAccounts($accounts, $type);

		// 验证码校验
		$verify_param = array(
				'key_prefix' => md5('safety_'.$accounts),
				'expire_time' => MyC('common_verify_expire_time')
			);
		if($type == 'sms')
		{
			$obj = new \My\Sms($verify_param);
		} else {
			$obj = new \My\Email($verify_param);
		}
		// 是否已过期
		if(!$obj->CheckExpire())
		{
			$this->ajaxReturn(L('common_verify_expire'), -10);
		}
		// 是否正确
		if(!$obj->CheckCorrect(I('verify')))
		{
			$this->ajaxReturn(L('common_verify_error'), -11);
		}

		// 更新帐号
		$field = ($type == 'sms') ? 'mobile' : 'email';
		$data = array(
				$field		=>	I('accounts'),
				'upd_time'	=>	time(),
			);
		// 更新数据库
		if(M('User')->where(array('id'=>$this->user['id']))->save($data) !== false)
		{
			// 更新用户session数据
			$this->UserLoginRecord($this->user['id']);

			// 校验成功标记
			unset($_SESSION['safety_'.$type]);

			// 清除验证码
			$obj->Remove();

			$this->ajaxReturn(L('common_operation_success'));
		} else {
			$this->ajaxReturn(L('common_operation_error'), -100);
		}
	}
}
?>