<?php

namespace Home\Controller;

/**
 * 空控制器
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class EmptyController extends CommonController
{
	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-25T15:30:36+0800
	 */
	public function Index()
	{
		$this->assign('msg', L('common_unauthorized_access'));
		$this->assign('is_footer', 0);
		$this->display('/Public/Error');
	}
}
?>