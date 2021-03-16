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

// 是否开启redis
$common_session_is_use_cache = MyFileConfig('common_session_is_use_cache', '', 0, true);
if($common_session_is_use_cache == 1)
{
    // redis配置
    $config = [
        // 使用redis
        'type'      => 'redis',
        // 连接地址
        'host'      => MyFileConfig('common_cache_session_redis_host', '', '127.0.0.1', true),
        // 端口号
        'port'      => MyFileConfig('common_cache_session_redis_port', '', 6379, true),
        // 密码
        'password'  => MyFileConfig('common_cache_session_redis_password', '', '', true),
        // 全局缓存有效期、默认3600秒
        'expire'    => MyFileConfig('common_cache_session_redis_expire', '', 3600, true), 
        // 缓存前缀
        'prefix'    => MyFileConfig('common_cache_session_redis_prefix', '', 'shopxo', true),
    ];
} else {
    // 默认配置
    $config = [
        // session_id
        'id'                => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id'    => '',
        // SESSION 前缀
        'prefix'            => 'shopxo',
        // 驱动方式 支持redis memcache memcached
        'type'              => '',
        // 是否自动开启 SESSION
        'auto_start'        => true,
    ];
}
return $config;
?>