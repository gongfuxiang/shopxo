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
 * 配置设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Config extends Base
{
	/**
     * 后台配置
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 模板数据
		$assign = [
			// 数据
			'data'                             => ConfigService::ConfigList(),
			// 页面类型
			'view_type'                        => 'index',
			// 静态数据
			'common_token_created_rules_list'  => MyConst('common_token_created_rules_list'),
		];
		MyViewAssign($assign);
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
		// 模板数据
		$assign = [
			// 数据
			'data'		=> ConfigService::ConfigList(),
			// 页面类型
			'view_type'	=> 'store',
		];
		MyViewAssign($assign);
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
		$field_list = [];

		// 页面类型
		$view_type = empty($this->data_request['view_type']) ? 'index' : $this->data_request['view_type'];
		switch($view_type)
		{
			// 系统配置
			case 'index' :
				$field_list[] = 'admin_logo';
				$field_list[] = 'admin_login_logo';
				$field_list[] = 'admin_login_ad_images';
				$field_list[] = 'common_token_created_rules';
				break;

			// 商店信息
			case 'store' :
				$field_list[] = 'common_customer_store_qrcode';
				break;
		}

		// 空字段处理
		$params = ConfigService::FieldsEmptyDataHandle($params, $field_list);

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
		return ApiService::ApiDataReturn(ConfigService::ConfigSave($params));
	}
}
?>