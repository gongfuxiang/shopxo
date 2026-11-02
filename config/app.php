<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------
$default_timezone = MyFileConfig('common_timezone', '', 'Asia/Shanghai', true);
return [
    // 应用地址
    'app_host'         => '',
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 默认应用
    'default_app'      => 'index',
    // 默认时区
    'default_timezone' => empty($default_timezone) ? 'Asia/Shanghai' : $default_timezone,

    // 应用映射（自动多应用模式有效）
    'app_map'          => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => [],

    // 异常页面的模板文件
    'exception_tmpl'   => APP_PATH . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '系统出现错误、请联系网站管理员处理！',
    // 显示错误信息
    'show_error_msg'   => true,
];
?>