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
        // 当前用户生成uuid并存储
        self::SetUserUUId($params);

        // 分享标识处理
        self::SetShareReferrer($params);

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
     * 分享标识处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SetShareReferrer($params = [])
    {
        // 推荐人
        if(!empty($params['referrer']))
        {
            MySession('share_referrer_id', $params['referrer']);
            cookie('share_referrer_id', $params['referrer']);
        }
    }

    /**
     * 当前用户生成uuid并存储
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SetUserUUId($params = [])
    {
        $uuid = MySession('uuid');
        if(empty($uuid))
        {
            MySession('uuid', UUId());
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
}
?>