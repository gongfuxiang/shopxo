<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\service\AppMiniService;

/**
 * 支付宝小程序管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppMiniAlipayList extends Common
{
	private $application_name;
	private $old_path;
	private $new_path;

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

		// 参数
		$this->params = input();
		$this->params['application_name'] = 'alipay';
	}

	/**
     * [Index 列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		$this->assign('data', AppMiniService::DataList($this->params));
		return $this->fetch();
	}

	/**
	 * [Created 生成]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-05T20:12:30+0800
	 */
	public function Created()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 配置内容
        $app_mini_title = MyC('common_app_mini_alipay_title');
        $app_mini_describe = MyC('common_app_mini_alipay_describe');
        if(empty($app_mini_title) || empty($app_mini_describe))
        {
            return DataReturn('配置信息不能为空', -1);
        }

		// 开始操作
		$this->params['app_mini_title'] = $app_mini_title;
		$this->params['app_mini_describe'] = $app_mini_describe;
		return AppMiniService::Created($this->params);
	}

	/**
	 * [Delete 删除包]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-09T21:13:47+0800
	 */
	public function Delete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 开始操作
		return AppMiniService::Delete($this->params);
	}
}
?>