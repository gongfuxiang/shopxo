<?php

/**
 * 模块语言包-缓存管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
	// 缓存类型列表
	'cache_type_list'			=>	array(
			array(
				'is_enable' => 1,
				'name' => '站点缓存',
				'url' => U('Admin/Cache/SiteUpdate'),
				'desc' => '数据转换后或前台不能正常访问时，可以使用此功能更新所有缓存'
			),
			array(
				'is_enable' => 1,
				'name' => '模板缓存',
				'url' => U('Admin/Cache/TemplateUpdate'),
				'desc' => '当页面显示不正常，可尝试使用此功能修复'
			),
			array(
				'is_enable' => 0,
				'name' => '模块缓存',
				'url' => U('Admin/Cache/ModuleUpdate'),
				'desc' => '更新页面布局与模块后未生效，可尝试使用此功能修复'
			),
		),
);
?>