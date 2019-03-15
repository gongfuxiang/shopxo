<?php
namespace app\plugins\shopoauth;

use think\Db;
use think\Controller;
use app\service\UserService;
use app\service\PluginsService;
use app\plugins\shopoauth\ThinkOauth;
use app\plugins\shopoauth\LoginEvent;

/**
 * 第三方登入 API - 钩子入口
 * @author   Guoguo
 * @blog     http://gadmin.cojz8.com
 * @version  1.0.0
 * @datetime 2019年3月14日
 */
class Auth extends Controller
{
	/**
	 * 解绑
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2019-03-15
	 * @desc    description
	 * @param   array           $params [description]
	 * @return  [type]                  [description]
	 */
	public function remove($params = [])
	{
		if(!empty($params['type']))
		{
			$user = UserService::LoginUserInfo();
			if(!empty($user['id']))
            {
            	Db::name('PluginsShopoauthOauth')->where(['platform'=>$params['type'], 'user_id'=>$user['id']])->delete();
            }
		}
		$this->redirect(MyUrl('user/personal/index'));  
	}

	/**
	 * 跳转授权登入
	 * @author   Guoguo
	 * @blog     http://gadmin.cojz8.com
	 * @version  1.0.0
	 * @datetime 2019年3月14日
	 */
	public function login($params = []){
		$sns = ThinkOauth::getInstance($params['type']);
		$this->redirect($sns->getRequestCodeURL());
	}
	/**
	 * 授权回调地址
	 * @author   Guoguo
	 * @blog     http://gadmin.cojz8.com
	 * @version  1.0.0
	 * @datetime 2019年3月14日
	 */
    public function callback($params = [])
    {
		if(empty($params['code']) || empty($params['type']))
		{
			return 'ERROR-10001——参数出错！';exit;
		}
        $sns = ThinkOauth::getInstance($params['type']);
        $token = $sns->getAccessToken($params['code'], []);

		$type = $params['type'];

        //获取当前登录用户信息
        if(is_array($token))
        {
            $even = new LoginEvent();
            $result = $even->$type($token);
			
			/*校验是否登入*/
			$user = UserService::LoginUserInfo();
			$user_id = empty($user['id']) ? 0 : $user['id'];
	
			//登入后返回信息
			if(!empty($result))
			{
				$oauth = [
					'user_id'		=> $user_id,
					'platform'		=> $result['type'],
					'openid'		=> $result['token']['openid'],
					'openname'		=> $result['name'],
					'access_token'	=> $result['token']['access_token'],
					'refresh_token'	=> $result['token']['refresh_token'],
					'expires_in'	=> $result['token']['expires_in'],
					'createtime'	=> time(),
					'updatetime'	=> time(),
					'logintime'		=> time()
				];

			   //判断或者写入oauth表
			   $where = ['openid'=>$oauth['openid']];
			   $oauth_user = Db::name('PluginsShopoauthOauth')->where($where)->find();
			   if(!empty($oauth_user))
			   {
				   $up_data = [
					   'access_token'		=> $result['token']['access_token'],
					   'refresh_token'		=> $result['token']['refresh_token'],
					   'expires_in'			=> $result['token']['expires_in'],
					   'updatetime'			=> time(),
					   'logintime'			=> time()
				   ];
				   
				   //更新表数据
				   //用户是否已绑定账号
				   if(!empty($oauth_user['user_id']))
				   {
						Db::name('PluginsShopoauthOauth')->where($where)->update($up_data); 

						//更新用户登录缓存数据
						UserService::UserLoginRecord($oauth_user['user_id']);
						$this->redirect('/');  
				   } else {
						Db::name('PluginsShopoauthOauth')->where($where)->update($up_data); 
						session('oauth_id', $oauth['openid']);

						//跳转注册页面
						$this->success('登入成功，请绑定或注册账号~', MyUrl('/index/user/reginfo'));
				   }
			   } else {
				   $id = Db::name('PluginsShopoauthOauth')->insertGetId($oauth);
				   if($user_id > 0)
				   {
					    UserService::UserLoginRecord($user_id);
						$this->redirect('/');
				   } else {
						session('oauth_id', $oauth['openid']);
						$this->success('登入成功，请绑定或注册账号~', MyUrl('/index/user/reginfo'));
				   }
			   }
            } else {
				$this->error('系统出错~');
            }
        } else {
        	$this->error('TOKEN-ERROR-10001——参数出错！~');
        }
    }
}
?>