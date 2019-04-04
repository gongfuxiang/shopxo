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
namespace app\plugins\ucenter;

use think\Controller;
use app\service\PluginsService;

/**
 * UCenter - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    /**
     * 应用响应入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function run($params = [])
    {
        if(!empty($params['hook_name']))
        {
            $config = PluginsService::PluginsData('ucenter');
            if($config['code'] == 0)
            {
                $ret = DataReturn('无需处理', 0);
                if(!empty($params['user']))
                {
                    switch($params['hook_name'])
                    {
                        // 注册
                        case 'plugins_service_user_register_end' :
                            $ret = $this->RegisterEndHandle($config['data'], $params);
                            break;

                        // 登录
                        case 'plugins_service_user_login_end' :
                            $ret = $this->LoginEndHandle($config['data'], $params);
                            break;

                        // 登录密码修改
                        case 'plugins_service_user_login_pwd_update' :
                            $ret = $this->LoginPwdUpdateHandle($config['data'], $params);
                            break;

                        // 账号修改
                        case 'plugins_service_user_accounts_update' :
                            $ret = $this->AccountsUpdateHandle($config['data'], $params);
                            break;
                    }
                }

                // 退出
                if(in_array($params['hook_name'], ['plugins_service_user_logout_handle']))
                {
                    $ret = $this->LogoutEndHandle($config['data'], $params);
                }

                return $ret;
            }
            

        // 默认返回视图
        } else {
            return '';
        }
    }

    /**
     * 账号修改
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-02
     * @desc    description
     * @param   [array]           $config [配置信息]
     * @param   [array]           $params [输入参数]
     */
    public function AccountsUpdateHandle($config = [], $params = [])
    {
        // 异步接口
        if(!empty($config['accounts_async_url']))
        {
            $accounts_async_url = explode("\n", $config['accounts_async_url']);
            if(!empty($accounts_async_url) && is_array($accounts_async_url))
            {
                foreach($accounts_async_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    SyncJob($url.$join.'data='.urlencode(json_encode($params['user'])).'&params='.urlencode(json_encode($params['params'])));
                }
            }
        }
    }

    /**
     * 登录密码修改
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-02
     * @desc    description
     * @param   [array]           $config [配置信息]
     * @param   [array]           $params [输入参数]
     */
    public function LoginPwdUpdateHandle($config = [], $params = [])
    {
        // 异步接口
        if(!empty($config['loginpwdupdate_async_url']))
        {
            $loginpwdupdate_async_url = explode("\n", $config['loginpwdupdate_async_url']);
            if(!empty($loginpwdupdate_async_url) && is_array($loginpwdupdate_async_url))
            {
                foreach($loginpwdupdate_async_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    SyncJob($url.$join.'data='.urlencode(json_encode($params['user'])).'&params='.urlencode(json_encode($params['params'])));
                }
            }
        }
    }

    /**
     * 退出
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-02
     * @desc    description
     * @param   [array]           $config [配置信息]
     * @param   [array]           $params [输入参数]
     */
    public function LogoutEndHandle($config = [], $params = [])
    {
        // 用户信息
        $user = empty($params['user']) ? '' : $params['user'];

        // 同步接口
        if(!empty($config['logout_sync_url']))
        {
            $logout_sync_url = explode("\n", $config['logout_sync_url']);
            if(!empty($logout_sync_url) && is_array($logout_sync_url))
            {
                foreach($logout_sync_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    $params['body_html'][] = '<script type="text/javascript" src="'.$url.$join.'data='.urlencode(json_encode($user)).'"></script>';
                }
            }
        }

        // 异步接口
        if(!empty($config['logout_async_url']))
        {
            $logout_async_url = explode("\n", $config['logout_async_url']);
            if(!empty($logout_async_url) && is_array($logout_async_url))
            {
                foreach($logout_async_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    SyncJob($url.$join.'data='.urlencode(json_encode($user)));
                }
            }
        }
    }

    /**
     * 注册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-02
     * @desc    description
     * @param   [array]           $config [配置信息]
     * @param   [array]           $params [输入参数]
     */
    public function RegisterEndHandle($config = [], $params = [])
    {
        // 同步接口
        if(!empty($config['register_sync_url']))
        {
            $register_sync_url = explode("\n", $config['register_sync_url']);
            if(!empty($register_sync_url) && is_array($register_sync_url))
            {
                foreach($register_sync_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    $params['body_html'][] = '<script type="text/javascript" src="'.$url.$join.'data='.urlencode(json_encode($params['user'])).'&params='.urlencode(json_encode($params['params'])).'"></script>';
                }
            }
        }

        // 异步接口
        if(!empty($config['register_async_url']))
        {
            $register_async_url = explode("\n", $config['register_async_url']);
            if(!empty($register_async_url) && is_array($register_async_url))
            {
                foreach($register_async_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    SyncJob($url.$join.'data='.urlencode(json_encode($params['user'])).'&params='.urlencode(json_encode($params['params'])));
                }
            }
        }
    }

    /**
     * 登录
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-02
     * @desc    description
     * @param   [array]           $config [配置信息]
     * @param   [array]           $params [输入参数]
     */
    public function LoginEndHandle($config = [], $params = [])
    {
        // 同步接口
        if(!empty($config['login_sync_url']))
        {
            $login_sync_url = explode("\n", $config['login_sync_url']);
            if(!empty($login_sync_url) && is_array($login_sync_url))
            {
                foreach($login_sync_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    $params['body_html'][] = '<script type="text/javascript" src="'.$url.$join.'data='.urlencode(json_encode($params['user'])).'&params='.urlencode(json_encode($params['params'])).'"></script>';
                }
            }
        }

        // 异步接口
        if(!empty($config['login_async_url']))
        {
            $login_async_url = explode("\n", $config['login_async_url']);
            if(!empty($login_async_url) && is_array($login_async_url))
            {
                foreach($login_async_url as $url)
                {
                    $join = (stripos($url, '?') === false) ? '?' : '&';
                    SyncJob($url.$join.'data='.urlencode(json_encode($params['user'])).'&params='.urlencode(json_encode($params['params'])));
                }
            }
        }
    }
}
?>