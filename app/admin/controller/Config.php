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

use app\service\ConfigService;

/**
 * 配置设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Config extends Common
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
		$this->IsLogin();

		// 权限校验
		$this->IsPower();
	}

	/**
     * 后台配置
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 静态数据
		MyViewAssign('common_excel_charset_list', lang('common_excel_charset_list'));
		MyViewAssign('common_is_enable_list', lang('common_is_enable_list'));
		MyViewAssign('common_login_type_list', lang('common_login_type_list'));
        MyViewAssign('common_close_open_list', lang('common_close_open_list'));

		// 配置信息
		MyViewAssign('data', ConfigService::ConfigList());
		
		MyViewAssign('view_type', 'index');
		return MyView();
	}

	/**
     * 商店信息
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Store()
	{
		// 配置信息
		MyViewAssign('data', ConfigService::ConfigList());
		
		MyViewAssign('view_type', 'store');
		return MyView();
	}

	/**
	 * 配置数据保存
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	public function Save()
	{
		// 参数
		$params = $_POST;

		// 字段不存在赋值
		$empty_value_field_list = [];

		// 页面类型
		$view_type = empty($this->data_request['view_type']) ? 'index' : $this->data_request['view_type'];
		switch($view_type)
		{
			case 'store' :
				$empty_value_field_list['common_customer_store_qrcode'] = '';
				break;
		}

		// 空字段处理
		if(!empty($empty_value_field_list))
		{
			foreach($empty_value_field_list as $fk=>$fv)
			{
				if(!isset($params[$fk]))
				{
					$params[$fk] = $fv;
				}
			}
		}

		// 默认值字段处理
		$default_value_field_list = [
			'admin_login_type'=>'username',
		];
		if(!empty($default_value_field_list))
		{
			foreach($default_value_field_list as $fk=>$fv)
			{
				if(empty($params[$fk]))
				{
					$params[$fk] = $fv;
				}
			}
		}
		
		// 保存
		return ConfigService::ConfigSave($params);
	}
}
?>