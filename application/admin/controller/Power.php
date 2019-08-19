<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\facade\Hook;
use app\service\AdminPowerService;

/**
 * 权限管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Power extends Common
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
     * [Index 权限组列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		$data_params = [
			'field'		=> 'id,pid,name,control,action,sort,is_show,icon',
			'order_by'	=> 'sort asc',
			'where'		=> ['pid'=>0],
		];
		$data = AdminPowerService::PowerList($data_params);

		$this->assign('data', $data);
		$this->assign('common_is_show_list', lang('common_is_show_list'));
		return $this->fetch();
	}

	/**
	 * [PowerSave 权限添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-13T21:41:03+0800
	 */
	public function PowerSave()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return AdminPowerService::PowerSave($params);
	}

	/**
	 * [PowerDelete 权限删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:40:29+0800
	 */
	public function PowerDelete()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return AdminPowerService::PowerDelete($params);
	}

	/**
	 * [Role 角色组列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function Role()
	{
		$data_params = [
			'field'	=> 'id,name,is_enable,add_time',
		];
		$data = AdminPowerService::RoleList($data_params);

		$this->assign('data_list', $data);
		return $this->fetch();
	}

	/**
	 * [RoleSaveInfo 角色组添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function RoleSaveInfo()
	{
		// 参数
		$params = input();

		// 角色组
		$data = [];
		if(!empty($params['id']))
		{
			$data_params = [
				'where'	=> ['id'=>intval($params['id'])],
			];
			$ret = AdminPowerService::RoleList($data_params);
			if(!empty($ret[0]['id']))
			{
				$data = $ret[0];

				// 权限关联数据
				$params['role_id'] =  $ret[0]['id'];
			}
		}

		// 菜单列表
		$power = AdminPowerService::RolePowerEditData($params);
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));
		$this->assign('power', $power);

		// 角色编辑页面钩子
        $hook_name = 'plugins_view_admin_power_role_save';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
        [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'role_id'      	=> isset($params['id']) ? $params['id'] : 0,
            'data'          => &$data,
            'params'        => &$params,
        ]));

        // 数据
        $this->assign('data', $data);
        $this->assign('params', $params);
		return $this->fetch();
	}

	/**
	 * [RoleSave 角色组添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function RoleSave()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 开始操作
		return AdminPowerService::RoleSave(input('post.'));
	}

	/**
	 * [RoleDelete 角色删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function RoleDelete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 开始操作
		return AdminPowerService::RoleDelete(input('post.'));
	}

	/**
	 * [RoleStatusUpdate 角色状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function RoleStatusUpdate()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return AdminPowerService::RoleStatusUpdate($params);
	}
}
?>