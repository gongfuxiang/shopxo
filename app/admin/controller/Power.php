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
use app\service\AdminPowerService;

/**
 * 权限菜单管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Power extends Base
{
	/**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		$data_params = [
			'field'		=> 'id,pid,name,control,action,url,sort,is_show,icon',
			'order_by'	=> 'sort asc',
			'where'		=> [
				['pid', '=', 0]
			],
		];
		$assign = [
			'data'					=> AdminPowerService::PowerList($data_params),
			'common_is_show_list'	=> MyConst('common_is_show_list'),
		];
		MyViewAssign($assign);
		return MyView();
	}

	/**
	 * 权限添加/编辑
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T21:41:03+0800
	 */
	public function Save()
	{
		$params = $this->data_request;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(AdminPowerService::PowerSave($params));
	}

	/**
	 * 删除
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:40:29+0800
	 */
	public function Delete()
	{
		$params = $this->data_request;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(AdminPowerService::PowerDelete($params));
	}
}
?>