<?php

namespace Home\Controller;

/**
 * 个人资料
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class PersonalController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();
	}

	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{
		$this->assign('personal_show_list', L('personal_show_list'));
		$this->display('Index');
	}

	/**
	 * [SaveInfo 编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-26T14:26:01+0800
	 */
	public function SaveInfo()
	{
		// 性别
		$this->assign('common_gender_list', L('common_gender_list'));

		// 数据
		$this->assign('data', $this->user);

		$this->display('SaveInfo');
	}

	/**
	 * [Save 数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-26T14:26:34+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 个人资料模型
		$m = D('Personal');

		// 编辑
		if($m->create($_POST, 2))
		{
			// 额外数据处理
			$m->birthday		=	strtotime(I('birthday'));
			$m->nickname 		=	I('nickname');
			$m->signature 		=	I('signature');
			$m->describe 		=	I('describe');
			$m->upd_time		=	time();

			// 更新数据库
			if($m->where(array('id'=>$this->user['id']))->save())
			{
				// 更新用户session数据
				$this->UserLoginRecord($this->user['id']);

				$this->ajaxReturn(L('common_operation_edit_success'));
			} else {
				$this->ajaxReturn(L('common_operation_edit_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}
}
?>