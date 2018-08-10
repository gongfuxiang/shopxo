<?php

namespace Home\Controller;

/**
 * 首页
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class IndexController extends CommonController
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
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{

		// 首页轮播
		$this->assign('banner_list', $this->GetHomeBanner());

		$this->display('Index');
	}

	/**
	 * 获取首页banner
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-08-10
	 * @desc    description
	 */
	private function GetHomeBanner()
	{
		// 轮播图片
		$banner = M('Slide')->field('jump_url,jump_url_type,images_url,name,bg_color')->where(['platform'=>APPLICATION_CLIENT_TYPE, 'is_enable'=>1])->select();
		if(!empty($banner))
		{
			$images_host = C('IMAGE_HOST');
			foreach($banner as &$v)
			{
				$v['images_url'] = $images_host.$v['images_url'];
				$v['jump_url'] = empty($v['jump_url']) ? null : $v['jump_url'];
			}
			$result['banner'] = $banner;
		}
		return $banner;
	}
}
?>