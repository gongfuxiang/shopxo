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

use app\service\StatisticalService;
use app\service\StoreService;
use app\service\SystemUpgradeService;

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
		// 默认进入页面初始化
		$to_url = MyUrl('admin/index/init');

		// 是否指定页面地址
		if(!empty($this->data_request['to_url']))
		{
			$to_url = base64_decode(urldecode($this->data_request['to_url']));
		}

		MyViewAssign('to_url', $to_url);
		return MyView();
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
		// 系统信息
		$mysql_ver = \think\facade\Db::query('SELECT VERSION() AS `ver`');
		$data = array(
				'server_ver'	=>	php_sapi_name(),
				'php_ver'		=>	PHP_VERSION,
				'mysql_ver'		=>	isset($mysql_ver[0]['ver']) ? $mysql_ver[0]['ver'] : '',
				'os_ver'		=>	PHP_OS,
				'host'			=>	isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : '',
				'ver'			=>	'ShopXO'.' '.APPLICATION_VERSION,
			);
		MyViewAssign('data', $data);

		// 用户
		$user = StatisticalService::UserYesterdayTodayTotal();
		MyViewAssign('user', $user['data']);

		// 订单总数
		$order_number = StatisticalService::OrderNumberYesterdayTodayTotal();
		MyViewAssign('order_number', $order_number['data']);

		// 订单成交总量
		$order_complete_number = StatisticalService::OrderCompleteYesterdayTodayTotal();
		MyViewAssign('order_complete_number', $order_complete_number['data']);

		// 订单收入总计
		$order_complete_money = StatisticalService::OrderCompleteMoneyYesterdayTodayTotal();
		MyViewAssign('order_complete_money', $order_complete_money['data']);

		// 近30日订单成交金额走势
		$order_profit_chart = StatisticalService::OrderProfitSevenTodayTotal();
		MyViewAssign('order_profit_chart', $order_profit_chart['data']);

		// 近30日订单交易走势
		$order_trading_trend = StatisticalService::OrderTradingTrendSevenTodayTotal();
		MyViewAssign('order_trading_trend', $order_trading_trend['data']);
		
		// 近30日支付方式
		$pay_type_number = StatisticalService::PayTypeSevenTodayTotal();
		MyViewAssign('pay_type_number', $pay_type_number['data']);

		// 近30日热销商品
		$goods_hot_sale = StatisticalService::GoodsHotSaleSevenTodayTotal();
		MyViewAssign('goods_hot_sale', $goods_hot_sale['data']);

		return MyView();
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
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 权限校验
		$this->IsPower();

        // 开始处理
        $params = $this->data_request;
        return StoreService::SiteStoreAccountsBind($params);
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
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 权限校验
		$this->IsPower();

        // 开始处理
        $params = $this->data_request;
        return StoreService::SiteInspectUpgrade($params);
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
		// 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 权限校验
		$this->IsPower();

        // 开始处理
        $params = $this->data_request;
        return SystemUpgradeService::Run($params);
	}
}
?>