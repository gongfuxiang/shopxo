<?php

/**
 * 公共配置信息
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */

$timezone = S('cache_common_timezone_data');
return array(
	// 是否开启开发者模式
	'IS_DEVELOP'			=>	true,

	// 显示页面Trace信息
	'SHOW_PAGE_TRACE'		=>	false,

	// 允许访问的模块列表
	'MODULE_ALLOW_LIST'		=>	array('Admin', 'Api', 'Home'),

	// 默认模块,暂时默认后端
	'DEFAULT_MODULE'     	=>	'Home',

	// 默认控制器名称
	'DEFAULT_CONTROLLER'	=>	'Index',

	// 默认操作名称
	'DEFAULT_ACTION'		=>	'Index',

	// URL模式
	'URL_MODEL'          	=>	0,

	// 默认语言
	'DEFAULT_LANG'          =>	'zh-cn',

	// 默认输出编码
	'DEFAULT_CHARSET'		=>	'utf-8',

	// 开启session
	'SESSION_AUTO_START' 	=>	true,

	// 开启语言包功能
	'LANG_SWITCH_ON'		=>	true,

	// 默认AJAX 数据返回格式,可选JSON XML
	'DEFAULT_AJAX_RETURN'	=>	'JSON',

	// 默认参数过滤方法 用于I函数
	'DEFAULT_FILTER'		=>	'htmlspecialchars',

	// 时区
	'DEFAULT_TIMEZONE'		=>	empty($timezone) ? 'Asia/Shanghai' : $timezone,


	// ------ 模板 start ------ //
	// 模板不存在的时候调用默认模板
	'TMPL_LOAD_DEFAULTTHEME'=>	true,

	// 模板静态文件后缀
	'TMPL_TEMPLATE_SUFFIX'	=>	'.html',

	// 模板定界符
	'TMPL_L_DELIM'			=>	'{{',
	'TMPL_R_DELIM'			=>	'}}',

	// 错误显示信息,非调试模式有效
	'ERROR_MESSAGE'			=>	'页面错误！请稍后再试～',
	// ------ 模板 end ------ //


	// ------ 缓存 start ------ //
	// 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_TIME'		=>	0,

	// 数据缓存是否压缩缓存
	'DATA_CACHE_COMPRESS'	=>	false,

	// 数据缓存是否校验缓存
	'DATA_CACHE_CHECK'		=>	false,

	// 缓存前缀
	'DATA_CACHE_PREFIX'		=>	'',

	// 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
	'DATA_CACHE_TYPE'		=>	'File',

	// 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
	'DATA_CACHE_SUBDIR'		=>	false,

	// 子目录缓存级别
	'DATA_PATH_LEVEL'		=>	1,
	// ------ 缓存 end ------ //
	

	// 缓存key列表
	// 公共系统配置信息key
	'cache_common_my_config_key'			=>	'cache_common_my_config_data',

	// 前台顶部导航，后端菜单更新则删除缓存
	'cache_common_home_nav_header_key'		=>	'cache_common_home_nav_header_data',

	// 前台顶部导航
	'cache_common_home_nav_footer_key'		=>	'cache_common_home_nav_footer_data',

	// 时区
	'cache_common_timezone_key'				=>	'cache_common_timezone_data',

	// 默认模板
	'cache_common_default_theme_key'		=>	'cache_common_default_theme_data',

	// 频道缓存 array(id => name)
	'cache_home_channel_key'				=>	'cache_home_channel_data',


	// 图片host, 数据库图片地址以/Public/...开头
	'IMAGE_HOST'					=>	substr(__MY_URL__, 0, -1),

	// 缓存路径
	'data_cache_dir'		=> TEMP_PATH,

	// 开启U带域名
	'url_domain_deploy' 	=>  true,
	
	// 支付宝appid
	'alipay_mini_appid'		=> '2018071160553916',

	// 支付宝密钥
	'alipay_key_secret'		=>
	[
	    'id' 	=> '2088131739974941',
	    'key' 	=> 'tbhpeal8zsqzxc0b5s6r2jrvzjoo74il',
	    'name' 	=> 'byjzglm@dingtalk.com',
	],
);