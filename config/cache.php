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
// | 缓存设置
// +----------------------------------------------------------------------
return [
    // 默认缓存驱动
    'default' => (MyFileConfig('common_data_is_use_redis_cache', '', 0, true) == 1) ? 'redis' : 'file',

    // 缓存连接方式配置
    'stores'  => [
        // 文件缓存
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => 'shopxo',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        // redis缓存
        'redis'   =>  [
            // 驱动方式
            'type'      => 'redis',
            // 连接地址
            'host'      => MyFileConfig('common_cache_data_redis_host', '', '127.0.0.1', true),
            // 端口号
            'port'      => MyFileConfig('common_cache_data_redis_port', '', 6379, true),
            // 密码
            'password'  => MyFileConfig('common_cache_data_redis_password', '', '', true),
            // 全局缓存有效期（0为永久有效）
            'expire'    => intval(MyFileConfig('common_cache_data_redis_expire', '', 0, true)),
            // 缓存前缀
            'prefix'    => MyFileConfig('common_cache_data_redis_prefix', '', 'redis_shopxo', true),
        ], 
    ],
];
?>