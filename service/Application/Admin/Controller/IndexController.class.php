<?php

namespace Admin\Controller;

/**
 * 首页
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class IndexController extends CommonController
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
	}

	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-05T21:36:13+0800
	 */
	public function Index()
	{
		$this->display('Index');
	}

	/**
	 * [Init 初始化页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-05T21:36:41+0800
	 */
	public function Init()
	{
		$mysql_ver = M()->query('SELECT VERSION() AS `ver`');
		$data = array(
				'server_ver'	=>	php_sapi_name(),
				'php_ver'		=>	PHP_VERSION,
				'mysql_ver'		=>	isset($mysql_ver[0]['ver']) ? $mysql_ver[0]['ver'] : '',
				'os_ver'		=>	PHP_OS,
				'host'			=>	isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : '',
				'ver'			=>	L('common_application_name').' '.L('common_application_ver'),
			);
		$this->assign('data', $data);
		$this->display('Init');
	}
}
?>