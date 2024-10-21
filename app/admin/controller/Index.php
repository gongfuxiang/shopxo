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

use app\admin\controller\Common;
use app\service\ApiService;
use app\service\StatisticalService;
use app\service\StoreService;
use app\service\SystemUpgradeService;
use app\service\ShortcutMenuService;
use app\service\SystemService;

/**
 * 首页
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Common
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
		if(in_array($this->action_name, ['storeaccountsbind', 'inspectupgrade', 'inspectupgradeconfirm', 'stats']))
		{
			$this->IsPower();
		}
	}

	/**
	 * 首页
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-05T21:36:13+0800
	 */
	public function Index()
	{
		// 默认地址
		$to_url = '';

		// 是否指定页面地址
		if(!empty($this->data_request['to_url']))
		{
			$to_url = base64_decode(urldecode($this->data_request['to_url']));
            MyViewAssign('appoint_to_url', 1);
		}
		MyViewAssign('to_url', $to_url);
		return MyView();
	}

	/**
	 * 初始化页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-05T21:36:41+0800
	 */
	public function Init()
	{
		// 模板数据
		$assign = [];

		// 系统信息
		$mysql_ver = \think\facade\Db::query('SELECT VERSION() AS `ver`');
		$data = [
			'server_ver'	=>	php_sapi_name(),
			'php_ver'		=>	PHP_VERSION,
			'mysql_ver'		=>	isset($mysql_ver[0]['ver']) ? $mysql_ver[0]['ver'] : '',
			'os_ver'		=>	PHP_OS,
			'host'			=>	isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : '',
			'ver'			=>	'ShopXO'.' '.APPLICATION_VERSION,
		];
		$assign['data'] = $data;

        // 常用功能
        $assign['shortcut_menu_data'] = ShortcutMenuService::ShortcutMenuList(['admin'=>$this->admin]);

		// 用户是否有数据统计权限
		$is_stats = AdminIsPower('index', 'stats');
		MyViewAssign('is_stats', $is_stats);
		if($is_stats == 1)
		{
			// 默认时间
			$default_day = '30-day';
			$assign['default_day'] = $default_day;

			// 收入统计权限
			$is_income = AdminIsPower('index', 'income');
			$assign['is_income'] = $is_income;

			// 时间
			$time_data = StatisticalService::DateTimeList();
			$assign['time_data'] = $time_data;

			// 基础数据总计
			$time = [];
			if(!empty($time_data) && !empty($default_day) && isset($time_data[$default_day]))
			{
				$time['start'] = strtotime($time_data[$default_day]['start']);
				$time['end'] = strtotime($time_data[$default_day]['end']);
			}
			$base_count = StatisticalService::BaseTotalCount($time);
			$assign['base_count'] = $base_count['data'];

			// 用户
			$user = StatisticalService::UserYesterdayTodayTotal();
			$assign['user'] = $user['data'];

			// 订单总数
			$order_number = StatisticalService::OrderNumberYesterdayTodayTotal();
			$assign['order_number'] = $order_number['data'];

			// 订单成交总量
			$order_complete_number = StatisticalService::OrderCompleteYesterdayTodayTotal();
			$assign['order_complete_number'] = $order_complete_number['data'];

			// 订单收入总计
			if($is_income)
			{
				$order_complete_money = StatisticalService::OrderCompleteMoneyYesterdayTodayTotal();
				$assign['order_complete_money'] = $order_complete_money['data'];
			}

			// 加载图表组件
            $assign['is_load_echarts'] = 1;

            // 加载动画数数
        	$assign['is_load_animation_count'] = 1;
		}
		MyViewAssign($assign);

		// 钩子初始化
        $this->PluginsInit();
		return MyView();
	}

	/**
	 * 配色切换
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2022-08-13
	 * @desc    description
	 */
	public function Color()
	{
		return MyRedirect(SystemService::SetAdminThemeColor($this->data_request));
	}

	/**
     * 钩子初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-07
     * @desc    description
     */
    private function PluginsInit()
    {
    	// 钩子列表
    	$hook_arr = [
    		// 顶部钩子
    		'plugins_admin_view_index_init_top',
    		// 公告顶部钩子
    		'plugins_admin_view_index_init_notice_top',
    		// 基础统计顶部钩子
    		'plugins_admin_view_index_init_stats_base_top',
    		// 基础统计内部顶部钩子
    		'plugins_admin_view_index_init_stats_inside_base_top',
    		// 订单金额走势统计内部顶部钩子
    		'plugins_admin_view_index_init_stats_inside_amount_trend_top',
    		// 订单交易走势统计内部顶部钩子
    		'plugins_admin_view_index_init_stats_inside_order_trading_top',
    		// 组合商品和支付统计内部顶部钩子
    		'plugins_admin_view_index_init_stats_inside_compose_top',
    		// 地域分布统计内部顶部钩子
    		'plugins_admin_view_index_init_stats_inside_region_top',
    		// 系统信息顶部钩子
    		'plugins_admin_view_index_init_system_info_top',
    		// 底部钩子
    		'plugins_admin_view_index_init_bottom',
    	];
    	$assign = [];
    	foreach($hook_arr as $hook_name)
    	{
    		// 基础统计顶部钩子
	        $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
	        	['hook_name'	=> $hook_name,
	        	'is_backend'	=> false,
	        	'admin'			=> $this->admin,
	        ]);
    	}
    	MyViewAssign($assign);
    }

	/**
	 * 应用商店帐号绑定
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2021-04-16
	 * @desc    description
	 */
	public function StoreAccountsBind()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(StoreService::SiteStoreAccountsBind($params));
	}

	/**
	 * 检查更新
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2021-04-16
	 * @desc    description
	 */
	public function InspectUpgrade()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(StoreService::SiteInspectUpgrade($params));
	}

	/**
	 * 检查更新确认
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2021-04-16
	 * @desc    description
	 */
	public function InspectUpgradeConfirm()
	{
        $params = $this->data_request;
        return ApiService::ApiDataReturn(SystemUpgradeService::Run($params));
	}

	/**
	 * 统计数据
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2021-08-30
	 * @desc    description
	 */
	public function Stats()
	{
		$params = $this->data_request;
        return ApiService::ApiDataReturn(StatisticalService::StatsData($params));
	}
}
?>