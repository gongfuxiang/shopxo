<?php

namespace Home\Controller;

/**
 * 自定义页面
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CustomViewController extends CommonController
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
	}

	/**
     * [Index 文章详情]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		$m = M('CustomView');
		$field = array('id', 'title', 'content', 'is_header', 'is_footer', 'is_full_screen', 'access_count');
		$data = $m->field($field)->where(array('id'=>I('id'), 'is_enable'=>1))->find();
		if(!empty($data['content']))
		{
			// 访问统计
			$m->where(array('id'=>I('id')))->setInc('access_count');

			// 静态资源地址处理
			$data['content'] = ContentStaticReplace($data['content'], 'get');

			$this->assign('is_header', $data['is_header']);
			$this->assign('is_footer', $data['is_footer']);
			$this->assign('data', $data);
			$this->display('Index');
		} else {
			$this->assign('msg', L('customview_on_exist_error'));
			$this->display('/Public/TipsError');
		}
	}
}
?>