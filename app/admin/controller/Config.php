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
use app\service\GoodsCategoryService;
use app\service\SiteService;

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
		// 系统配置 tab（与模板 data-key、url 参数 switch 一致）
		$nav_tab_keys = ['base-config', 'admin-login', 'goods', 'map', 'extend', 'verify-config', 'attachment-config', 'safety', 'cache-config', 'cookie-config', 'session-config'];
		$switch = isset($this->data_request['switch']) ? $this->data_request['switch'] : '';
		$config_nav_tab_index = 0;
		$config_tab_key = $nav_tab_keys[0];
		if(!empty($switch) && in_array($switch, $nav_tab_keys, true))
		{
			$config_nav_tab_index = array_search($switch, $nav_tab_keys, true);
			$config_tab_key = $switch;
		}

		// 模板数据
		$assign = [
			// 数据
			'data'                             => ConfigService::ConfigList(),
			// 页面类型
			'view_type'                        => 'index',
			// 当前 tab
			'config_nav_tab_index'             => $config_nav_tab_index,
			'config_tab_key'                   => $config_tab_key,
			// 静态数据
			'common_token_created_rules_list'  => MyConst('common_token_created_rules_list'),
			// 登录方式
			'common_login_type_list'           => MyConst('common_login_type_list'),
			// excel编码
			'common_excel_charset_list'        => MyConst('common_excel_charset_list'),
			// excel导出类型
			'common_excel_export_type_list'    => MyConst('common_excel_export_type_list'),
			// 地图类型
			'common_map_type_list'             => MyConst('common_map_type_list'),
			// 关闭开启
			'common_close_open_list'           => MyConst('common_close_open_list'),
			// 图片验证码类型 / 规则（验证码 tab）
			'common_site_images_verify_rand_type_list' => MyConst('common_site_images_verify_rand_type_list'),
			'common_site_images_verify_rules_list'     => MyConst('common_site_images_verify_rules_list'),
			// 是否
			'common_is_text_list'              => MyConst('common_is_text_list'),
			// 商品基础字段
			'common_goods_base_fields_list'    => MyConst('common_goods_base_fields_list'),
			// 商品导航
			'goods_admin_nav_list'             => MyConst('common_goods_admin_nav_list'),
			// 商品分类
			'goods_category_list'              => GoodsCategoryService::GoodsCategoryAll(),
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
			'data'                  => ConfigService::ConfigList(),
			// 平台客户端
			'common_platform_type'  => MyConst('common_platform_type'),
			// 页面类型
			'view_type'             => 'store',
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
		$params = $this->data_request;

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
				$field_list[] = 'common_images_verify_rules';
				break;

			// 商店信息
			case 'store' :
				$field_list[] = 'common_customer_store_public_weixin';
				$field_list[] = 'common_customer_store_public_alipay';
				$field_list[] = 'common_customer_store_chat_line';
				$field_list[] = 'common_customer_store_chat_weixin';
				$field_list[] = 'common_customer_store_platform_client';
				break;
		}

		// 缓存 Redis 连接校验（与原站点设置-缓存一致）
		if($view_type == 'index')
		{
			if((isset($params['common_session_is_use_cache']) && $params['common_session_is_use_cache'] == 1) || (isset($params['common_data_is_use_redis_cache']) && $params['common_data_is_use_redis_cache'] == 1))
			{
				$ret = SiteService::RedisCheckConnectPing($params['common_cache_data_redis_host'], $params['common_cache_data_redis_port'], $params['common_cache_data_redis_password']);
				if($ret['code'] != 0)
				{
					return ApiService::ApiDataReturn($ret);
				}
			}
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