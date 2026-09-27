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

namespace app\index\controller;

use app\service\I18nService;
use app\service\ApiService;

/**
 * 多语言数据管理（用户端）
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-08-31
 */
class I18n extends Common
{
	/**
	 * 构造方法
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

		// 登录校验
		IsUserLogin();
	}

	/**
	 * 多语言数据读取（输入框弹窗回显）
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2026-08-31
	 */
	public function Index()
	{
		// 参数
		$params = $this->data_request;
		$table_name = isset($params['table']) ? trim($params['table']) : '';
		$business_id = isset($params['id']) ? intval($params['id']) : 0;
		$plugins = isset($params['plugins']) ? trim($params['plugins']) : '';

		// 仅支持已配置的业务表
		if(empty(I18nService::FieldsConfig($table_name)))
		{
			return ApiService::ApiDataReturn(DataReturn(MyLang('common_service.i18n.table_not_support_tips'), -1));
		}
		return ApiService::ApiDataReturn(DataReturn(MyLang('operate_success'), 0, I18nService::ValueData($table_name, $business_id, $plugins)));
	}
}
?>
