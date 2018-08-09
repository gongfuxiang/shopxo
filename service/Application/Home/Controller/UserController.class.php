<?php

namespace Home\Controller;

/**
 * 用户
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class UserController extends CommonController
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
	}

	/**
	 * [GetrefererUrl 获取上一个页面地址]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T15:46:16+0800
	 */
	private function GetrefererUrl()
	{
		// 上一个页面, 空则用户中心
		if(empty($_SERVER['HTTP_REFERER']))
		{
			$referer_url = U('Home/Bubble/Index');
		} else {
			if(strpos($_SERVER['HTTP_REFERER'], 'RegInfo') !== false || strpos($_SERVER['HTTP_REFERER'], 'LoginInfo') !== false || strpos($_SERVER['HTTP_REFERER'], 'ForgetPwdInfo') !== false)
			{
				$referer_url = U('Home/Bubble/Index');
			} else {
				$referer_url = $_SERVER['HTTP_REFERER'];
			}
		}
		return $referer_url;
	}

	/**
	 * [Index 用户中心]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function Index()
	{
		$this->display('Index');
	}

	/**
	 * [ForgetPwdInfo 密码找回]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T17:06:47+0800
	 */
	public function ForgetPwdInfo()
	{
		if(empty($this->user))
		{
			$this->display('ForgetPwdInfo');
		} else {
			$this->assign('msg', L('common_forget_already_had_tips'));
			$this->display('/Public/TipsError');
		}
	}

	/**
	 * [EmailRegInfo 用户注册页面-邮箱]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T12:18:17+0800
	 */
	public function EmailRegInfo()
	{
		if(in_array('email', MyC('home_user_reg_state')))
		{
			if(empty($this->user))
			{
				$this->assign('referer_url', $this->GetrefererUrl());
				$this->display('EmailRegInfo');
			} else {
				$this->assign('msg', L('common_reg_already_had_tips'));
				$this->display('/Public/TipsError');
			}
		} else {
			$this->assign('msg', L('common_close_email_user_reg_tips'));
			$this->display('/Public/TipsError');
		}
	}

	/**
	 * [RegInfo 用户注册页面-短信]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function RegInfo()
	{
		if(in_array('sms', MyC('home_user_reg_state')))
		{
			if(empty($this->user))
			{
				$this->assign('referer_url', $this->GetrefererUrl());
				$this->display('RegInfo');
			} else {
				$this->assign('msg', L('common_reg_already_had_tips'));
				$this->display('/Public/TipsError');
			}
		} else {
			$this->assign('msg', L('common_close_sms_user_reg_tips'));
			$this->display('/Public/TipsError');
		}
	}

	/**
	 * [LoginInfo 用户登录页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function LoginInfo()
	{
		if(MyC('home_user_login_state') == 1)
		{
			if(empty($this->user))
			{
				$this->assign('referer_url', $this->GetrefererUrl());
				$this->display('LoginInfo');
			} else {
				$this->assign('msg', L('common_login_already_had_tips'));
				$this->display('/Public/TipsError');
			}
		} else {
			$this->assign('msg', L('common_close_user_login_tips'));
			$this->display('/Public/TipsError');
		}
	}

	/**
	 * [Reg 用户注册-数据添加]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-07T00:08:36+0800
	 */
	public function Reg()
	{
		// 是否开启用户注册
		if(!in_array(I('type'), MyC('home_user_reg_state')))
		{
			$this->error(L('common_close_user_reg_tips'));
		}

		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 账户校验
		$this->UserRegAccountsCheck();

		// 验证码校验
		$verify_param = array(
				'key_prefix' => 'reg',
				'expire_time' => MyC('common_verify_expire_time')
			);
		if(I('type') == 'sms')
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

		// 模型
		$m = D('User');

		// 数据自动校验
		if($m->create($_POST, 1))
		{
			// 额外数据处理
			if(I('type') == 'sms')
			{
				$m->mobile = I('accounts');
			} else {
				$m->email = I('accounts');
			}
			$m->add_time	=	time();
			$m->upd_time	=	time();
			$m->salt 		=	GetNumberCode(6);
			$m->pwd 		=	LoginPwdEncryption(I('pwd'), $m->salt);

			// 数据添加
			$user_id = $m->add();
			if($user_id > 0)
			{
				// 清除验证码
				$obj->Remove();

				if($this->UserLoginRecord($user_id))
				{
					$this->ajaxReturn(L('common_reg_success'));
				}
				$this->ajaxReturn(L('common_reg_success_login_tips'));
			} else {
				$this->ajaxReturn(L('common_reg_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Login 用户登录]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T10:57:31+0800
	 */
	public function Login()
	{
		// 是否开启用户登录
		if(MyC('home_user_login_state') != 1)
		{
			$this->error(L('common_close_user_login_tips'));
		}

		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 登录帐号格式校验
		$accounts = I('accounts');
		if(!CheckMobile($accounts) && !CheckEmail($accounts))
		{
			$this->ajaxReturn(L('user_login_accounts_format'), -1);
		}

		// 密码
		$pwd = trim(I('pwd'));
		if(!CheckLoginPwd($pwd))
		{
			$this->ajaxReturn(L('user_reg_pwd_format'), -2);
		}

		// 获取用户账户信息
		$where = array('mobile' => $accounts, 'email' => $accounts, '_logic' => 'OR');
		$user = M('User')->field(array('id', 'pwd', 'salt', 'state'))->where($where)->find();
		if(empty($user))
		{
			$this->ajaxReturn(L('user_login_accounts_on_exist_error'), -3);
		}
		// 用户状态
		if($user['state'] == 2)
		{
			$this->ajaxReturn(L('common_user_state_list')[$user['state']]['tips'], -10);
		}

		// 密码校验
		if(LoginPwdEncryption($pwd, $user['salt']) != $user['pwd'])
		{
			$this->ajaxReturn(L('user_common_pwd_error'), -4);
		}

		// 更新用户密码
		$salt = GetNumberCode(6);
		$data = array(
				'pwd'		=>	LoginPwdEncryption($pwd, $salt),
				'salt'		=>	$salt,
				'upd_time'	=>	time(),
			);
		if(M('User')->where(array('id'=>$user['id']))->save($data) !== false)
		{
			// 登录记录
			if($this->UserLoginRecord($user['id']))
			{
				$this->ajaxReturn(L('common_login_success'));
			}
		}
		$this->ajaxReturn(L('common_login_invalid'), -100);
	}

	/**
	 * [UserVerifyEntry 用户-验证码显示]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T15:10:21+0800
	 */
	public function UserVerifyEntry()
	{
		$this->CommonVerifyEntry(I('type', 'reg'));
	}

	/**
	 * [RegVerifySend 用户注册-验证码发送]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T19:17:10+0800
	 */
	public function RegVerifySend()
	{
		// 是否开启用户注册
		if(!in_array(I('type'), MyC('home_user_reg_state')))
		{
			$this->error(L('common_close_user_reg_tips'));
		}

		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 账户校验
		$this->UserRegAccountsCheck();

		// 验证码公共基础参数
		$verify_param = array(
				'key_prefix' => 'reg',
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);

		// 是否开启图片验证码
		$verify = $this->CommonIsImaVerify($verify_param);

		// 发送验证码
		$code = GetNumberCode(6);
		if(I('type') == 'sms')
		{
			$obj = new \My\Sms($verify_param);
			$state = $obj->SendText(I('accounts'), MyC('home_sms_user_reg'), $code);
		} else {
			$obj = new \My\Email($verify_param);
			$email_param = array(
					'email'		=>	I('accounts'),
					'content'	=>	MyC('home_email_user_reg'),
					'title'		=>	MyC('home_site_name').' - '.L('common_email_send_user_reg_title'),
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
	 * [UserRegAccountsCheck 用户注册账户校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T10:06:29+0800
	 */
	private function UserRegAccountsCheck()
	{
		// 参数
		$type = I('type');
		$accounts = I('accounts');
		if(empty($accounts) || empty($type) || !in_array($type, array('sms', 'email')))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 手机号码
		if($type == 'sms')
		{
			// 手机号码格式
			if(!CheckMobile($accounts))
			{
				$this->ajaxReturn(L('common_mobile_format_error'), -2);
			}

			// 手机号码是否已存在
			if($this->IsExistAccounts($accounts, 'mobile'))
			{
				$this->ajaxReturn(L('common_mobile_exist_error'), -3);
			}

		// 电子邮箱
		} else {
			// 电子邮箱格式
			if(!CheckEmail($accounts))
			{
				$this->ajaxReturn(L('common_email_format_error'), -2);
			}

			// 电子邮箱是否已存在
			if($this->IsExistAccounts($accounts, 'email'))
			{
				$this->ajaxReturn(L('common_email_exist_error'), -3);
			}
		}
	}

	/**
	 * [ForgetPwdVerifySend 密码找回验证码发送]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T17:35:03+0800
	 */
	public function ForgetPwdVerifySend()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$accounts = I('accounts');
		if(empty($accounts))
		{
			$this->ajaxReturn(L('common_param_error'), -10);
		}

		// 账户是否存在
		$type = $this->UserForgetAccountsCheck($accounts);

		// 验证码公共基础参数
		$verify_param = array(
				'key_prefix' => 'forget',
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);

		// 是否开启图片验证码
		$verify = $this->CommonIsImaVerify($verify_param);

		// 验证码
		$code = GetNumberCode(6);

		// 手机
		if($type == 'mobile')
		{
			$obj = new \My\Sms($verify_param);
			$state = $obj->SendText($accounts, MyC('home_sms_user_forget_pwd'), $code);

		// 邮箱
		} else if($type == 'email')
		{
			$obj = new \My\Email($verify_param);
			$email_param = array(
					'email'		=>	$accounts,
					'content'	=>	MyC('home_email_user_forget_pwd'),
					'title'		=>	MyC('home_site_name').' - '.L('common_email_send_user_reg_title'),
					'code'		=>	$code,
				);
			$state = $obj->SendHtml($email_param);
		} else {
			$this->ajaxReturn(L('user_login_accounts_format'), -1);
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
	 * [ForgetPwd 密码找回]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T17:55:42+0800
	 */
	public function ForgetPwd()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$accounts = I('accounts');
		$verify = I('verify');
		$pwd = trim(I('pwd'));
		if(empty($accounts) || empty($verify) || empty($pwd))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 账户是否存在
		$field = $this->UserForgetAccountsCheck($accounts);

		// 验证码校验
		$verify_param = array(
				'key_prefix' => 'forget',
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);
		if($field == 'mobile')
		{
			$obj = new \My\Sms($verify_param);
		} else if($field == 'email')
		{
			$obj = new \My\Email($verify_param);
		}
		// 是否已过期
		if(!$obj->CheckExpire())
		{
			$this->ajaxReturn(L('common_verify_expire'), -10);
		}
		// 是否正确
		if(!$obj->CheckCorrect($verify))
		{
			$this->ajaxReturn(L('common_verify_error'), -11);
		}

		// 更新用户密码
		$salt = GetNumberCode(6);
		$data = array(
				'pwd'		=>	LoginPwdEncryption($pwd, $salt),
				'salt'		=>	$salt,
				'upd_time'	=>	time(),
			);
		if(M('User')->where(array($field=>$accounts))->save($data) !== false)
		{
			$this->ajaxReturn(L('common_operation_success'));
		}
		$this->ajaxReturn(L('common_operation_error'), -100);
	}

	/**
	 * [UserForgetAccountsCheck 帐号校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T17:59:53+0800
	 * @param    [string]     $accounts [账户名称]
	 * @return   [string]               [账户字段 mobile, email]
	 */
	private function UserForgetAccountsCheck($accounts)
	{
		if(CheckMobile($accounts))
		{
			if(!$this->IsExistAccounts($accounts, 'mobile'))
			{
				$this->ajaxReturn(L('common_mobile_no_exist_error'), -3);
			}
			return 'mobile';
		} else if(CheckEmail($accounts))
		{
			if(!$this->IsExistAccounts($accounts, 'email'))
			{
				$this->ajaxReturn(L('common_email_no_exist_error'), -3);
			}
			return 'email';
		}
		$this->ajaxReturn(L('common_accounts_format_error'), -4);
	}

	/**
	 * [IsExistAccounts 账户是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-08T10:27:14+0800
	 * @param    [string] $accounts 	[账户名称]
	 * @param    [string] $field 		[字段名称]
	 * @return   [boolean] 				[存在true, 不存在false]
	 */
	private function IsExistAccounts($accounts, $field = 'mobile')
	{
		$id = M('User')->where(array($field=>$accounts))->getField('id');
		return !empty($id);
	}

	/**
	 * [Logout 退出]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-05T14:31:23+0800
	 */
	public function Logout()
	{
		if(isset($_SESSION['user']))
		{
			unset($_SESSION['user']);
		}
		redirect(__MY_URL__);
	}
}
?>