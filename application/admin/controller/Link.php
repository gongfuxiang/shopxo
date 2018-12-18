<?php

namespace app\admin\controller;

/**
 * 友情链接
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Link extends Common
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
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 获取导航列表
		$list = db('Link')->field(array('id', 'name', 'url', 'describe', 'sort', 'is_enable', 'is_new_window_open'))->order('sort')->select();
		$this->assign('list', $list);

		// 是否新窗口打开
		$this->assign('common_is_new_window_open_list', lang('common_is_new_window_open_list'));

		// 是否启用
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));

		$this->display('Index');
	}

	/**
	 * [Save 数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-05T20:12:30+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// id为空则表示是新增
		$m = D('Link');

		// 公共额外数据处理
		$m->sort 		=	intval(I('sort'));
		$m->describe 	=	I('describe');

		// 添加
		if(empty($_POST['id']))
		{
			if($m->create($_POST, 1))
			{
				// 额外数据处理
				$m->add_time	=	time();
				$m->name 		=	I('name');
				$m->describe 	=	I('describe');
				
				// 写入数据库
				if($m->add())
				{
					$this->ajaxReturn('新增成功');
				} else {
					$this->ajaxReturn('新增失败', -100);
				}
			}
		} else {
			// 编辑
			if($m->create($_POST, 2))
			{
				// 额外数据处理
				$m->name 		=	I('name');
				$m->describe 	=	I('describe');

				// 移除 id
				unset($m->id);

				// 更新数据库
				if($m->where(array('id'=>I('id')))->save())
				{
					$this->ajaxReturn('编辑成功');
				} else {
					$this->ajaxReturn('编辑失败或数据未改变', -100);
				}
			}
		}
		$this->ajaxReturn($m->getError(), -1);
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
			$this->error('非法访问');
		}

		$m = D('Link');
		if($m->create($_POST, 4))
		{
			if($m->delete($id))
			{
				$this->ajaxReturn('删除成功');
			} else {
				$this->ajaxReturn('删除失败或资源不存在', -100);
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
			$this->ajaxReturn('参数错误', -1);
		}

		// 数据更新
		if(db('Link')->where(array('id'=>I('id')))->save(array('is_enable'=>I('state'))))
		{
			$this->ajaxReturn('编辑成功');
		} else {
			$this->ajaxReturn('编辑失败或数据未改变', -100);
		}
	}
}
?>