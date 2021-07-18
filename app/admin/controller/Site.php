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
use app\service\GoodsService;
use app\service\SiteService;
use app\service\ResourcesService;

/**
 * 站点设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Site extends Common
{
	public $nav_type;
	public $view_type;

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

		// 导航类型
		$this->nav_type = input('nav_type', 'base');
		$this->view_type = input('view_type', 'index');

		// 仅网站设置页面存在多个子页面
        if($this->nav_type != 'siteset')
        {
        	$this->view_type = 'index';
        }
	}

	/**
	 * 配置列表
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-11-25
	 * @desc    description
	 */
	public function Index()
	{
		// 公共数据
		$this->CurrentViewInit();

		// 配置信息
		$data = ConfigService::ConfigList();
		MyViewAssign('data', $data);

		// 数据处理
		switch($this->nav_type)
		{
			// 自提点
			case 'sitetype' :
				// 地址处理
	        	if(!empty($data['common_self_extraction_address']) && !empty($data['common_self_extraction_address']['value']))
				{
					$address = ConfigService::SiteTypeExtractionAddressList($data['common_self_extraction_address']['value']);
					MyViewAssign('sitetype_address_list', $address['data']);
				}

				// 加载百度地图api
	        	MyViewAssign('is_load_baidu_map_api', 1);
				break;

			// 网站设置
			case 'siteset' :
				// 获取商品一级分类
				$where = [
					['pid', '=', 0],
					['is_home_recommended', '=', 1],
					['is_enable', '=', 1],
				];
            	$category = GoodsService::GoodsCategoryList(['where'=>$where]);
            	if(!empty($category))
            	{
            		// 关键字
            		$floor_keywords = (empty($data['home_index_floor_top_right_keywords']) || empty($data['home_index_floor_top_right_keywords']['value'])) ? [] : json_decode($data['home_index_floor_top_right_keywords']['value'], true);
            		// 分类
            		$floor_category = (empty($data['home_index_floor_left_top_category']) || empty($data['home_index_floor_left_top_category']['value'])) ? [] : json_decode($data['home_index_floor_left_top_category']['value'], true);
            		foreach($category as &$c)
            		{
            			// 获取二级分类
            			$where = [
							['pid', '=', $c['id']],
							['is_enable', '=', 1],
						];
            			$c['items'] = GoodsService::GoodsCategoryList(['where'=>$where]);

            			// 配置的关键字
            			$c['config_keywords'] = array_key_exists($c['id'], $floor_keywords) ? $floor_keywords[$c['id']] : '';

            			// 配置左侧分类
            			$c['config_category_ids'] = array_key_exists($c['id'], $floor_category) ? explode(',', $floor_category[$c['id']]) : [];
            		}
            	}
            	MyViewAssign('goods_category_list', $category);

            	// 楼层自定义商品
            	if(!empty($data['home_index_floor_manual_mode_goods']) && !empty($data['home_index_floor_manual_mode_goods']['value']))
            	{
            		$ret = SiteService::FloorManualModeGoodsViewHandle(json_decode($data['home_index_floor_manual_mode_goods']['value'], true));
            		MyViewAssign('floor_manual_mode_goods_list', $ret['data']);
            	}
				break;
		}

		// 编辑器文件存放地址
        MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue('common'));

        // 视图
        $view = 'site/'.$this->nav_type.'/'.$this->view_type;
        return MyView($view);
	}

	/**
	 * 公共视图
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-11-21
	 * @desc    description
	 */
	public function CurrentViewInit()
	{
		// 主/子导航
        MyViewAssign('nav_type', $this->nav_type);
        MyViewAssign('view_type', $this->view_type);

		// 时区
		MyViewAssign('site_timezone_list', lang('site_timezone_list'));

		// 关闭开启
		MyViewAssign('common_close_open_list', lang('common_close_open_list'));

		// 登录方式
		MyViewAssign('common_login_type_list', lang('common_login_type_list'));

		// 用户注册类型列表
		MyViewAssign('common_user_reg_type_list', lang('common_user_reg_type_list'));

		// 图片验证码规则
		MyViewAssign('site_images_verify_rules_list', lang('site_images_verify_rules_list'));

		// 热门搜索关键字
		MyViewAssign('common_search_keywords_type_list', lang('common_search_keywords_type_list'));

		// 是否
		MyViewAssign('common_is_text_list', lang('common_is_text_list'));

		// 站点类型
		MyViewAssign('common_site_type_list', lang('common_site_type_list'));

		// 扣除库存规则
		MyViewAssign('common_deduction_inventory_rules_list', lang('common_deduction_inventory_rules_list'));

		// 增加销量规则
		MyViewAssign('common_sales_count_inc_rules_list', lang('common_sales_count_inc_rules_list'));

		// 首页商品排序规则
		MyViewAssign('goods_order_by_type_list', lang('goods_order_by_type_list'));
		MyViewAssign('goods_order_by_rule_list', lang('goods_order_by_rule_list'));

		// 首页楼层数据类型
		MyViewAssign('common_site_floor_data_type_list', lang('common_site_floor_data_type_list'));

		// 搜索参数类型
		MyViewAssign('common_goods_parameters_type_list', lang('common_goods_parameters_type_list'));

		// 主导航
		MyViewAssign('second_nav_list', [
			[
				'name'	=> '基础配置',
				'type'	=> 'base',
			],
			[
				'name'	=> '网站设置',
				'type'	=> 'siteset',
			],
			[
				'name'	=> '站点类型',
				'type'	=> 'sitetype',
			],
			[
				'name'	=> '用户注册',
				'type'	=> 'register',
			],
			[
				'name'	=> '用户登录',
				'type'	=> 'login',
			],
			[
				'name'	=> '密码找回',
				'type'	=> 'forgetpwd',
			],
			[
				'name'	=> '验证码',
				'type'	=> 'verify',
			],
			[
				'name'	=> '订单售后',
				'type'	=> 'orderaftersale',
			],
			[
				'name'	=> '附件',
				'type'	=> 'attachment',
			],
			[
				'name'	=> '缓存',
				'type'	=> 'cache',
			],
			[
				'name'	=> '扩展项',
				'type'	=> 'extends',
			],
		]);

		// 网站设置导航
		MyViewAssign('siteset_nav_list', [
			[
				'name'	=> '首页',
				'type'	=> 'index',
			],
			[
				'name'	=> '商品',
				'type'	=> 'goods',
			],
			[
				'name'	=> '搜索',
				'type'	=> 'search',
			],
			[
				'name'	=> '订单',
				'type'	=> 'order',
			],
			[
				'name'	=> '优惠',
				'type'	=> 'discount',
			],
			[
				'name'	=> '扩展',
				'type'	=> 'extends',
			],
		]);
	}
	
	/**
	 * 配置数据保存
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-11-25
	 * @desc    description
	 */
	public function Save()
	{
		// 参数
		$params = $_POST;

		// 字段不存在赋空值
		$field_list = [];

		// 导航类型
		switch($this->nav_type)
		{
			// 用户注册
			case 'register' :
				$field_list[] = 'home_user_reg_type';
				$field_list[] = 'home_site_user_register_bg_images';
				break;

			// 用户登录
			case 'login' :
				$field_list[] = 'home_user_login_type';
				$field_list[] = 'home_site_user_login_ad1_images';
				$field_list[] = 'home_site_user_login_ad2_images';
				$field_list[] = 'home_site_user_login_ad3_images';
				break;

			// 密码找回
			case 'forgetpwd' :
				$field_list[] = 'home_site_user_forgetpwd_ad1_images';
				$field_list[] = 'home_site_user_forgetpwd_ad2_images';
				$field_list[] = 'home_site_user_forgetpwd_ad3_images';
				break;

			// 图片验证码
			case 'verify' :
				$field_list[] = 'common_images_verify_rules';
				break;

			// 站点类型
			case 'sitetype' :
				// 自提地址处理
				if(!empty($params['common_self_extraction_address']))
				{
					if(!is_array($params['common_self_extraction_address']))
					{
						$address = json_decode($params['common_self_extraction_address'], true);
					} else {
						$address = $params['common_self_extraction_address'];
					}
					foreach($address as $k=>$v)
					{
						$address[$k]['id'] = $k;
						$address[$k]['logo'] = empty($v['logo']) ? '' : ResourcesService::AttachmentPathHandle($v['logo']);
					}
					$params['common_self_extraction_address'] = json_encode($address, JSON_UNESCAPED_UNICODE);
				}
				break;

			// 网站设置
			case 'siteset' :
				switch($this->view_type)
				{
					// 首页
					case 'index' :
						// 楼层关键字
						$params['home_index_floor_top_right_keywords'] = empty($params['home_index_floor_top_right_keywords']) ? '' : json_encode($params['home_index_floor_top_right_keywords'], JSON_UNESCAPED_UNICODE);
						// 楼层自定义商品
						$params['home_index_floor_manual_mode_goods'] = empty($params['home_index_floor_manual_mode_goods']) ? '' : json_encode($params['home_index_floor_manual_mode_goods'], JSON_UNESCAPED_UNICODE);
						// 楼层左侧分类
						$params['home_index_floor_left_top_category'] = empty($params['home_index_floor_left_top_category']) ? '' : json_encode($params['home_index_floor_left_top_category'], JSON_UNESCAPED_UNICODE);
						break;

					// 搜索
					case 'search' :
						$field_list[] = 'home_search_params_type';
						break;
				}
				break;

			// 缓存
			case 'cache' :
				// session是否使用缓存
				// 数据是否使用缓存
				if((isset($params['common_session_is_use_cache']) && $params['common_session_is_use_cache'] == 1) || (isset($params['common_data_is_use_cache']) && $params['common_data_is_use_cache'] == 1))
				{
					// 连接测试
					$ret = SiteService::RedisCheckConnectPing($params['common_cache_data_redis_host'], $params['common_cache_data_redis_port'], $params['common_cache_data_redis_password']);
					if($ret['code'] != 0)
					{
						return $ret;
					}
				}
				break;
		}

		// 开始处理空值
		if(!empty($field_list))
		{
			foreach($field_list as $field)
			{
				if(!isset($params[$field]))
				{
					$params[$field] = '';
				}
			}
		}

		// 基础配置
		$ret = ConfigService::ConfigSave($params);

		// 清除缓存
		if($ret['code'] == 0)
		{
			switch($this->nav_type)
			{
				// 登录
				case 'login' :
					MyCache(MyConfig('shopxo.cache_user_login_left_key'), null);

				// 密码找回
				case 'forgetpwd' :
					MyCache(MyConfig('shopxo.cache_user_forgetpwd_left_key'), null);
					break;
			}
		}

		return $ret;
	}

	/**
	 * 商品搜索
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-11-25
	 * @desc    description
	 */
    public function GoodsSearch()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 搜索数据
        $ret = SiteService::GoodsSearchList($this->data_post);
        if($ret['code'] == 0)
        {
            MyViewAssign('data', $ret['data']['data']);
            $ret['data']['data'] = MyView('site/public/goods_search');
        }
        return $ret;
    }
}
?>