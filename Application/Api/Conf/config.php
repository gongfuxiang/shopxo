<?php

/**
 * 模块配置信息
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */

$default_theme = S('cache_common_default_theme_data');
return array(
	// 伪静态后缀
	'URL_HTML_SUFFIX'		=>	MyC('home_seo_url_html_suffix'),

	// URL模式
	'URL_MODEL'          	=>	MyC('home_seo_url_model'),

	// 设置默认的模板主题
	'DEFAULT_THEME'       	=>  empty($default_theme) ? 'Default' : $default_theme,
);