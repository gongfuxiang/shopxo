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
namespace app\admin\controller;

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\ConfigService;

/**
 * 邮箱设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Email extends Base
{
	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 导航
		$type = empty($this->data_request['type']) ? 'index' : $this->data_request['type'];
		$assign = [

			// 静态数据
			'common_is_text_list'	=> MyConst('common_is_text_list'),
			// 配置信息
			'data'					=> ConfigService::ConfigList(),

			// 页面导航
			'nav_type'				=> $type,
		];
		MyViewAssign($assign);
		return MyView($type);
	}

	/**
	 * 保存
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	public function Save()
	{
		return ApiService::ApiDataReturn(ConfigService::ConfigSave($_POST));
	}

	/**
	 * 邮件测试
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T15:30:10+0800
	 */
	public function EmailTest()
	{
		// 验证码公共基础参数
		$verify_param = [
				'expire_time' 	=> MyC('common_verify_expire_time'),
				'interval_time'	=>	MyC('common_verify_interval_time'),
			];

		$obj = new \base\Email($verify_param);
		$email_param = [
				'email'		=>	isset($this->data_request['email']) ? $this->data_request['email'] : '',
				'content'	=>	'邮件配置-发送测试内容',
				'title'		=>	MyC('home_site_name').' - '.'测试',
			];
		// 发送
		if($obj->SendHtml($email_param))
		{
			$ret = DataReturn('发送成功');
		} else {
			$ret = DataReturn('发送失败'.'['.$obj->error.']', -100);
		}
		return ApiService::ApiDataReturn($ret);
	}
}
?>