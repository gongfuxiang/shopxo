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
use app\service\IntegralService;
use app\service\UserService;

/**
 * 用户管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class User extends Common
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
     * [Index 用户列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 参数
		$params = input();

		// 条件
		$where = UserService::UserListWhere($params);

		// 总数
		$total = UserService::UserTotal($where);

		// 分页
		$number = MyC('admin_page_number', 10, true);
		$page_params = array(
				'number'	=>	$number,
				'total'		=>	$total,
				'where'		=>	$params,
				'page'		=>	isset($params['page']) ? intval($params['page']) : 1,
				'url'		=>	MyUrl('admin/user/index'),
			);
		$page = new \base\Page($page_params);

		// 获取管理员列表
		$data_params = [
			'where'		=> $where,
			'm'			=> $page->GetPageStarNumber(),
			'n'			=> $number,
		];
		$data = UserService::UserList($data_params);

		// 性别
		$this->assign('common_gender_list', lang('common_gender_list'));

		// 用户状态
		$this->assign('common_user_status_list', lang('common_user_status_list'));

		// Excel地址
		$this->assign('excel_url', MyUrl('admin/user/excelexport', $params));

		$this->assign('params', $params);
		$this->assign('page_html', $page->GetPageHtml());
		$this->assign('data_list', $data['data']);
		return $this->fetch();
	}

	/**
	 * [ExcelExport excel文件导出]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:46:00+0800
	 */
	public function ExcelExport()
	{
		// 条件
		$where = UserService::UserListWhere(input('post.'));

		$data_params = [
			'where'		=> $where,
			'm'			=> 0,
			'n'			=> 0,
		];
		$data = UserService::UserList($data_params);

		// Excel驱动导出数据
		$excel = new \base\Excel(array('filename'=>'user', 'title'=>lang('excel_user_title_list'), 'data'=>$data['data'], 'msg'=>'没有相关数据'));
		return $excel->Export();
	}

	/**
	 * [SaveInfo 用户添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 参数
		$params = input();

		// 用户编辑
		$data = [];
		if(!empty($params['id']))
		{
			$data_params = [
				'where'		=> ['id'=>$params['id']],
				'm'			=> 0,
				'n'			=> 1,
			];
			$ret = UserService::UserList($data_params);
			if(empty($ret['data'][0]))
			{
				return $this->error('用户信息不存在', MyUrl('admin/user/index'));
			}

			// 生日
			$ret['data'][0]['birthday_text'] = empty($ret['data'][0]['birthday']) ? '' : date('Y-m-d', $ret['data'][0]['birthday']);
			
			$data = $ret['data'][0];
		}

		// 用户编辑页面钩子
		$hook_name = 'plugins_view_admin_user_save';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
        [
            'hook_name'    	=> $hook_name,
            'is_backend'   	=> true,
            'user_id'      	=> isset($params['id']) ? $params['id'] : 0,
            'data'			=> &$data,
            'params'       	=> &$params,
        ]));

		// 性别
		$this->assign('common_gender_list', lang('common_gender_list'));

		// 数据
		$this->assign('data', $data);
		$this->assign('params', $params);
		return $this->fetch();
	}


	/**
	 * [Save 用户添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function Save()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return UserService::UserSave($params);
	}

	/**
	 * [Delete 用户删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function Delete()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始操作
		$params = input('post.');
		$params['admin'] = $this->admin;
		return UserService::UserDelete($params);
	}
}
?>