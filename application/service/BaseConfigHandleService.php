<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

/**
 * 基础配置处理服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-25
 * @desc    description
 */
class BaseConfigHandleService
{
    /**
     * 运行入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-25
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Run($params = [])
    {
        // session配置
        $ret = self::SessionHandle($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // cache配置
        $ret = self::CacheHandle($params);

        return $ret;
    }

    /**
     * session配置处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-25
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SessionHandle($params = [])
    {
        if(MyC('common_session_is_use_cache') == 1)
        {
            $config = [
                // 使用redis
                'type'      => 'redis',
                // 连接地址
                'host'      => MyC('common_cache_session_redis_host', '127.0.0.1', true),
                // 端口号
                'port'      => MyC('common_cache_session_redis_port', 6379, true),
                // 密码
                'password'  => MyC('common_cache_session_redis_password', '', true),
                // 全局缓存有效期、默认3600秒
                'expire'    => MyC('common_cache_session_redis_expire', 3600, true), 
                // 缓存前缀
                'prefix'    => MyC('common_cache_session_redis_prefix', 'shopxo', true),
            ];
        } else {
            $config = [
                // session_id
                'id'                => '',
                // SESSION_ID的提交变量,解决flash上传跨域
                'var_session_id'    => '',
                // SESSION 前缀
                'prefix'            => 'shopxo',
                // 驱动方式 支持redis memcache memcached
                'type'              => '',
                // 过期时间(默认3600秒)
                'expire'            => 3600,
                // 是否自动开启 SESSION
                'auto_start'        => true,
            ];
        }

        // 配置文件
        $file_dir = ROOT.'config'.DS;
        $file_name = 'session.php';

        // 保存文件
        return self::ConfigFileSave($file_dir, $file_name, $config, 'Session配置');
    }

    /**
     * cache配置处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-25
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function CacheHandle($params = [])
    {
        // 是否使用缓存
        if(MyC('common_data_is_use_cache') == 1)
        {
            $config = [
                // 使用redis
                'type'      => 'redis',
                // 连接地址
                'host'      => MyC('common_cache_data_redis_host', '127.0.0.1', true),
                // 端口号
                'port'      => MyC('common_cache_data_redis_port', 6379, true),
                // 密码
                'password'  => MyC('common_cache_data_redis_password', '', true),
                // 全局缓存有效期（0为永久有效）
                'expire'    => MyC('common_cache_data_redis_expire', 0, true), 
                // 缓存前缀
                'prefix'    => MyC('common_cache_data_redis_prefix', 'shopxo', true),
            ];
            
        } else {
            $config = [
                // 驱动方式
                'type'   => 'File',
                // 缓存保存目录
                'path'   => '',
                // 缓存前缀
                'prefix' => 'shopxo',
                // 缓存有效期 0表示永久缓存
                'expire' => 0,
            ];
        }

        // 配置文件
        $file_dir = ROOT.'config'.DS;
        $file_name = 'cache.php';

        // 保存文件
        return self::ConfigFileSave($file_dir, $file_name, $config, '缓存配置');
    }

    /**
     * 配置文件保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-25
     * @desc    description
     * @param   [string]          $file_dir     [文件路径]
     * @param   [string]          $file_name    [文件名称]
     * @param   [array]           $config       [配置信息]
     * @param   [string]          $name         [描述名称]
     */
    private static function ConfigFileSave($file_dir, $file_name, $config, $name)
    {
        // 是否有写权限
        $config_file = $file_dir.$file_name;
        if(file_exists($config_file))
        {
            if(!is_writable($config_file))
            {
                return DataReturn($name.'文件没有操作权限'.'['.$config_file.']', -10);
            }
        } else {
            if(!is_dir($file_dir))
            {
                return DataReturn($name.'路径不存在'.'['.$file_dir.']', -11);
            }
            if(!is_writable($file_dir))
            {
                return DataReturn($name.'路径没有操作权限'.'['.$file_dir.']', -12);
            }
        }

        // 生成配置文件
        $ret = @file_put_contents($config_file, "<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------

// {$name}\nreturn ".var_export($config, true).";\n?>");
        if($ret === false)
        {
            return DataReturn($name.'处理失败['.$config_file.']', -100);
        }
        return DataReturn('处理成功', 0);
    }
}
?>