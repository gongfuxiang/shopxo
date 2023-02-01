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
 * 手机端 - 配置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppConfig extends Base
{
	/**
     * 配置列表
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
        	// 配置数据
        	'data'		=> ConfigService::ConfigList(),
        	// 管理导航
            'nav_data'	=> MyLang('appconfig.base_nav_list'),
            // 页面导航
			'nav_type'	=> $type,
        ];
        MyViewAssign($assign);
        return MyView($type);
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
		// 空字段处理
		$field_list = [
			'common_user_onekey_bind_mobile_list',
			'common_user_address_platform_import_list',
		];
		return ApiService::ApiDataReturn(ConfigService::ConfigSave(ConfigService::FieldsEmptyDataHandle($_POST, $field_list)));
	}
}
?>