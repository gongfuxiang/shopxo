<?php

/**
 * 模块语言包-seo
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	// url模式列表
	'seo_url_model_list'		=>	array(
			0 => array('value' => 0, 'name' => '普通模式', 'checked' => true),
			1 => array('value' => 1, 'name' => 'PATHINFO模式'),
			2 => array('value' => 2, 'name' => 'REWRITE模式'),
			3 => array('value' => 3, 'name' => '兼容模式'),
		),

	// 文章浏览方案列表
	'seo_article_browser_list'	=>	array(
			0 => array('value' => 0, 'name' => '文章标题', 'checked' => true),
			1 => array('value' => 1, 'name' => '文章标题 - 站点名称'),
			2 => array('value' => 2, 'name' => '文章标题 - 站点标题'),
		),

	// 频道浏览方案列表
	'seo_channel_browser_list'	=>	array(
			0 => array('value' => 0, 'name' => '频道名称', 'checked' => true),
			1 => array('value' => 1, 'name' => '频道名称 - 站点名称'),
			2 => array('value' => 2, 'name' => '频道名称 - 站点标题'),
		),
);
?>