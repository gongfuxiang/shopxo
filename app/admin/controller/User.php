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
use app\service\UserService;

/**
 * 用户管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class User extends Base
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
		// Excel地址
		MyViewAssign('excel_url', MyUrl('admin/user/excelexport', $this->data_request));
		return MyView();
	}

	/**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-05T08:21:54+0800
     */
    public function Detail()
    {
        return MyView();
    }

	/**
	 * excel文件导出
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:46:00+0800
	 */
	public function ExcelExport()
	{
        // 获取数据列表
		$data_params = [
			'where'		=> $this->form_where,
			'm'			=> 0,
			'n'			=> 0,
		];
		$data = UserService::UserList($data_params);

		// Excel驱动导出数据
		$excel = new \base\Excel(array('filename'=>'user', 'title'=>MyConst('excel_user_title_list'), 'data'=>$data['data'], 'msg'=>'没有相关数据'));
		$excel->Export();
	}

	/**
	 * 添加/编辑页面
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 参数
		$params = $this->data_request;

		// 数据
		$data = $this->data_detail;
		if(!empty($params['id']))
		{
			if(empty($data))
			{
				return $this->error('用户信息不存在', MyUrl('admin/user/index'));
			}

			// 生日
			$data['birthday_text'] = empty($data['birthday']) ? '' : date('Y-m-d', $data['birthday']);
		}

		// 模板数据
		$assign = [
			// 静态数据
			'common_gender_list' => MyConst('common_gender_list'),
		];

		// 用户编辑页面钩子
		$hook_name = 'plugins_view_admin_user_save';
        $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
        [
            'hook_name'    	=> $hook_name,
            'is_backend'   	=> true,
            'user_id'      	=> isset($params['id']) ? $params['id'] : 0,
            'data'			=> &$data,
            'params'       	=> &$params,
        ]);

		// 数据/参数
		unset($params['id']);
        $assign['data'] = $data;
		$assign['params'] = $params;

		// 数据赋值
		MyViewAssign($assign);
		return MyView();
	}


	/**
	 * 用户添加/编辑
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
		$params = $this->data_post;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(UserService::UserSave($params));
	}

	/**
	 * 用户删除
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
		$params = $this->data_post;
		$params['admin'] = $this->admin;
		return ApiService::ApiDataReturn(UserService::UserDelete($params));
	}
}
?>