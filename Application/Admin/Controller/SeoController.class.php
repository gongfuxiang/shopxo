<?php

namespace Admin\Controller;

/**
 * seo设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SeoController extends CommonController
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
     * [Index 配置列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// url模式
		$this->assign('seo_url_model_list', L('seo_url_model_list'));

		// 文章标题seo方案
		$this->assign('seo_article_browser_list', L('seo_article_browser_list'));

		// 频道标题seo方案
		$this->assign('seo_channel_browser_list', L('seo_channel_browser_list'));

		// 配置信息
		$data = M('Config')->getField('only_tag,name,describe,value,error_tips');
		$this->assign('data', $data);
		
		$this->display('Index');
	}

	/**
	 * [Save 配置数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	public function Save()
	{
		$this->MyConfigSave();
	}
}
?>