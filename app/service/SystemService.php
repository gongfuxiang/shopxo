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
namespace app\service;

use app\service\UserService;

/**
 * 配置服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SystemService
{
    /**
     * 系统运行开始
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SystemBegin($params = [])
    {
        // 基础数据初始化
        self::BaseInit($params);

        // 钩子
        $hook_name = 'plugins_service_system_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]);
    }

    /**
     * 系统运行结束
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SystemEnd($params = [])
    {
        $hook_name = 'plugins_service_system_end';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]);
    }

    /**
     * 基础数据初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BaseInit($params = [])
    {
        // uuid
        $uuid = MySession('uuid');
        if(empty($uuid))
        {
            $uuid = empty($params['uuid']) ? UUId() : $params['uuid'];
            MySession('uuid', $uuid);
            cookie('uuid', $uuid);
        }

        // token
        if(!empty($params['token']))
        {
            $key = UserService::$user_token_key;
            MySession($key, $params['token']);
            cookie($key, $params['token']);
        }

        // 邀请人id
        if(!empty($params['referrer']))
        {
            MySession('share_referrer_id', $params['referrer']);
            cookie('share_referrer_id', $params['referrer']);
        }
    }

    /**
     * 系统安装检查
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SystemInstallCheck($params = [])
    {
        if(!file_exists(ROOT.'config/database.php'))
        {
            MyRedirect(__MY_URL__.'install.php?s=index/index', true);
        }
    }

    /**
     * 系统类型值
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-15
     * @desc    description
     */
    public static function SystemTypeValue()
    {
        // 取默认值
        $value = SYSTEM_TYPE;

        // 系统类型钩子
        $hook_name = 'plugins_service_system_system_type_value';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'value'         => &$value,
        ]);

        return $value;
    }
}
?>