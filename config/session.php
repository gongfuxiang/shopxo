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
// | 会话设置（有效期读取后台 系统配置 → Session配置，默认 43200 秒）
// +----------------------------------------------------------------------
$session_expire = (int) MyFileConfig('common_session_expire', '', 43200, true);
if($session_expire < 0)
{
    $session_expire = 43200;
}

if(MyFileConfig('common_session_is_use_cache', '', 0, true) == 1)
{
    // redis配置
    // 请确保缓存配置文件cache.php中的stores中已经添加了redis缓存配置
    $config = [
        'type'    => 'cache',
        'store'   => 'redis',
        'prefix'  => MyFileConfig('common_cache_session_redis_prefix', '', 'shopxo', true),
        // 服务端 Session 数据有效期（秒）
        'expire'  => $session_expire,
    ];
} else {
    // 默认配置
    $config = [
        // session name
        'name'            => 'PHPSESSID',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id'  => '',
        // 驱动方式 支持file cache
        'type'            => 'file',
        // 存储连接标识 当type使用cache的时候有效
        'store'           => null,
        // 服务端 Session 数据有效期（秒）
        'expire'          => $session_expire,
        // 前缀
        'prefix'          => 'shopxo',
    ];
}
return $config;
?>