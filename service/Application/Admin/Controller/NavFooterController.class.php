<?php

namespace Admin\Controller;

/**
 * 底部导航
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class NavFooterController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 导航列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 获取导航列表
		$this->assign('list', $this->GetNavList());

		// 文章分类
		$this->assign('article_category_list', M('ArticleCategory')->field(array('id', 'name'))->where(array('is_enable'=>1))->select());

		// 自定义页面
		$this->assign('customview_list', M('CustomView')->field(array('id', 'title'))->where(array('is_enable'=>1))->select());

		// 是否新窗口打开
		$this->assign('common_is_new_window_open_list', L('common_is_new_window_open_list'));

		// 是否显示
		$this->assign('common_is_show_list', L('common_is_show_list'));

		$this->display('Index');
	}

	/**
	 * [GetNavList 获取数据列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T22:16:29+0800
	 */
	private function GetNavList()
	{
		$m = M('Navigation');
		$field = array('id', 'name', 'url', 'value', 'data_type', 'sort', 'is_show', 'is_new_window_open');
		return NavDataDealWith($m->field($field)->where(array('nav_type'=>'footer'))->order('sort')->select());
	}

	/**
     * [Save 添加/编辑]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-07T21:58:19+0800
     */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 请求类型
		switch(I('data_type'))
		{
			// 自定义导航
			case 'custom':
				$this->DataSave(5);
				break;

			// 文章分类导航
			case 'article_category':
				$this->DataSave(6);
				break;

			// 自定义页面导航
			case 'customview':
				$this->DataSave(7);
				break;
		}
		$this->ajaxReturn(L('common_param_error'), -1);
	}

	/**
	 * [DataSave 导航数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-05T20:12:30+0800
	 * @param    [int]   $check_type [校验类型]
	 */
	private function DataSave($check_type)
	{
		$m = D('Navigation');

		// 数据校验
		if($m->create($_POST, $check_type))
		{
			// 非自定义导航数据处理
			if(empty($_POST['name']))
			{
				switch(I('data_type'))
				{
					// 文章分类导航
					case 'article_category':
						$temp_name = M('ArticleCategory')->where(array('id'=>I('value')))->getField('name');
						break;

					// 自定义页面导航
					case 'customview':
						$temp_name = M('CustomView')->where(array('id'=>I('value')))->getField('title');
						break;
				}
				// 只截取16个字符
				$m->name = mb_substr($temp_name, 0, 16, C('DEFAULT_CHARSET'));
			} else {
				$m->name 	=	I('name');
			}

			// 清除缓存
			S(C('cache_common_home_nav_footer_key'), null);

			// id为空则表示是新增
			if(empty($_POST['id']))
			{
				// 额外数据处理
				$m->add_time	=	time();
				$m->nav_type	=	'footer';

				// 写入数据库
				if($m->add())
				{
					$this->ajaxReturn(L('common_operation_add_success'));
				} else {
					$this->ajaxReturn(L('common_operation_add_error'), -100);
				}
			} else {
				// 数据编辑
				if($m->where(array('id'=>I('id')))->save())
				{
					$this->ajaxReturn(L('common_operation_edit_success'));
				} else {
					$this->ajaxReturn(L('common_operation_edit_error'), -100);
				}
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Delete 删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-09T21:13:47+0800
	 */
	public function Delete()
	{
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		$m = D('Navigation');
		if($m->create($_POST, 4))
		{
			if($m->delete($id))
			{
				// 清除缓存
				S(C('cache_common_home_nav_footer_key'), null);
				
				$this->ajaxReturn(L('common_operation_delete_success'));
			} else {
				$this->ajaxReturn(L('common_operation_delete_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [StatusUpdate 状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusUpdate()
	{
		// 参数
		if(empty($_POST['id']) || !isset($_POST['state']))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 数据更新
		if(M('Navigation')->where(array('id'=>I('id')))->save(array('is_show'=>I('state'))))
		{
			// 清除缓存
			S(C('cache_common_home_nav_footer_key'), null);

			$this->ajaxReturn(L('common_operation_edit_success'));
		} else {
			$this->ajaxReturn(L('common_operation_edit_error'), -100);
		}
	}
}
?>