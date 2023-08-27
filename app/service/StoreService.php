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

use app\service\ConfigService;

/**
 * 应用商店服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2019-06-16T00:33:28+0800
 */
class StoreService
{
    // 站点商店数据缓存key
    public static $site_store_info_key = 'admin_site_store_info_data';

    /**
     * 应用商店地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StoreUrl($params = [])
    {
        return MyConfig('shopxo.store_url').self::RequestParamsString($params);
    }

    /**
     * 应用商店支付插件地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StorePaymentUrl($params = [])
    {
        return MyConfig('shopxo.store_payment_url').self::RequestParamsString($params);
    }

    /**
     * 应用商店页面设计地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StoreDesignUrl($params = [])
    {
        return MyConfig('shopxo.store_design_url').self::RequestParamsString($params);
    }
    
    /**
     * 应用商店主题地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function StoreThemeUrl($params = [])
    {
        return MyConfig('shopxo.store_theme_url').self::RequestParamsString($params);
    }

    /**
     * 请求参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-12
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function RequestParamsString($params = [])
    {
        // 当前管理员后台地址
        $admin_url = explode('?', __MY_VIEW_URL__);

        // 拼接商店请求参数地址
        return '?name='.urlencode(base64_encode(MyC('home_site_name'))).'&ver='.urlencode(base64_encode(APPLICATION_VERSION)).'&url='.urlencode(base64_encode(__MY_URL__)).'&host='.urlencode(base64_encode(__MY_HOST__)).'&ip='.urlencode(base64_encode(__MY_ADDR__)).'&admin_url='.urlencode(base64_encode($admin_url[0]));
    }

    /**
     * 获取站点商店信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-16
     * @desc    description
     */
    public static function SiteStoreInfo()
    {
        $res = MyCache(self::$site_store_info_key);
        return empty($res) ? [] : $res;
    }

    /**
     * 站点应用商店帐号绑定
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-16
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SiteStoreAccountsBind($params = [])
    {
        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'common_store_accounts',
                'checked_data'      => '1,80',
                'error_msg'         => MyLang('store_bind_form_accounts_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'common_store_password',
                'checked_data'      => '6,30',
                'error_msg'         => MyLang('store_bind_form_password_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 保存商店帐号信息
        // 处理转义符号并加密
        $save_data = [
            'common_store_accounts' => $params['common_store_accounts'],
            'common_store_password' => Authcode(htmlspecialchars_decode($params['common_store_password']), 'ENCODE'),
        ];
        $ret = ConfigService::ConfigSave($save_data);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 绑定处理
        return self::SiteStoreAccountsBindHandle($params['common_store_accounts'], $params['common_store_password']);
    }

    /**
     * 账号数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-17
     * @desc    description
     */
    public static function AccountsData()
    {
        // 数据库配置中读取
        $accounts = MyC('common_store_accounts');
        $password = MyC('common_store_password');

        // 存在密码则解密
        if(!empty($password))
        {
            $password = Authcode($password, 'DECODE');
        }
        return [
            'accounts' => $accounts,
            'password' => $password,
        ];
    }

    /**
     * 站点应用商店帐号绑定处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-16
     * @desc    description
     * @param   [string]          $accounts [帐号]
     * @param   [string]          $password [密码]
     */
    public static function SiteStoreAccountsBindHandle($accounts = '', $password = '')
    {
        // 帐号信息、站点初始化信息接口、帐号信息可以为空
        if(empty($accounts) || empty($password))
        {
            $user = self::AccountsData();
            $accounts = $user['accounts'];
            $password = $user['password'];
        }

        // 获取信息
        $res = self::RemoteStoreData($accounts, $password, MyConfig('shopxo.store_site_info_url'));
        if($res['code'] == 0)
        {
            // 存储缓存、取远程给的时间，未拿到时间则默认60分钟
            $cache_time = (empty($res['data']['base']) || empty($res['data']['base']['cache_time'])) ? 3600 : intval($res['data']['base']['cache_time']);
            MyCache(self::$site_store_info_key, $res['data'], $cache_time);

            return DataReturn(MyLang('bind_success'), 0);
        }
        return $res;
    }

    /**
     * 站点检查更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SiteInspectUpgrade($params = [])
    {
        // 帐号信息
        $user = self::AccountsData();

        // 获取信息
        return self::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_inspect_upgrade_url'));
    }

    /**
     * 插件安全合法校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-19
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PluginsLegalCheck($params = [])
    {
        // 参数校验
        if(empty($params) || empty($params['type']) || empty($params['plugins']) || empty($params['author']) || empty($params['ver']))
        {
            return DataReturn('插件参数有误', -1);
        }

        // 帐号信息
        $user = self::AccountsData();
        if(empty($user['accounts']) || empty($user['password']))
        {
            return DataReturn(MyLang('store_account_not_bind_tips'), -300);
        }

        // 获取信息
        $request_params = [
            'plugins_type'      => $params['type'],
            'plugins_value'     => $params['plugins'],
            'plugins_author'    => $params['author'],
            'plugins_ver'       => $params['ver'],
            'plugins_config'    => $params['config'],
        ];
        return self::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_plugins_legal_check_url'), $request_params);
    }

    /**
     * 插件更新信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-21
     * @desc    description
     * @param   [array]           $params [输入参数、插件信息]
     */
    public static function PluginsUpgradeInfo($params = [])
    {
        if(!empty($params) && !empty($params['plugins_type']) && !empty($params['plugins_data']) && is_array($params['plugins_data']))
        {
            // 帐号信息
            $user = self::AccountsData();
            if(empty($user['accounts']) || empty($user['password']))
            {
                return DataReturn(MyLang('store_account_not_bind_tips'), -300);
            }

            // 获取更新信息
            return self::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_plugins_upgrade_info_url'), $params);
        }
        return DataReturn(MyLang('plugins_no_data_tips'), 0);
    }

    /**
     * 远程获取数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-13
     * @desc    description
     * @param   [string]          $accounts [帐号]
     * @param   [string]          $password [密码]
     * @param   [string]          $url      [请求地址]
     * @param   [array]           $params   [额外参数]
     * @param   [array]           $data_type[请求数据类型]
     */
    public static function RemoteStoreData($accounts, $password, $url, $params = [], $data_type = 0)
    {
        // http状态验证
        $key = 'cache_store_url_http_code';
        $time = 600;
        $ret = MyCache($key);
        if(empty($ret))
        {
            $ret = GetHttpCode(self::StoreUrl(), 5);
            MyCache($key, $ret, $time);
        }
        if(!in_array($ret['data'], [200, 301, 302, 307, 308]))
        {
            $ret['msg'] = MyLang('store_content_error_tips').'[ '.$ret['msg'].' ]';
            return $ret;
        }

        // 基础数据获取
        $bo = new \base\Behavior();

        // 请求校验
        $data = [
            'accounts'      => $accounts,
            'authdata'      => empty($password) ? '' : htmlspecialchars_decode($password),
            'host'          => __MY_HOST__,
            'url'           => __MY_URL__,
            'ver'           => APPLICATION_VERSION,
            'server_port'   => $bo->GetServerPort(),
            'server_ip'     => $bo->GetServerIP(),
            'client_ip'     => $bo->GetClientIP(),
            'os'            => $bo->GetOs(),
            'browser'       => $bo->GetBrowser(),
            'scheme'        => $bo->GetScheme(),
            'version'       => $bo->GetHttpVersion(),
            'client'        => $bo->GetClinet(),
            'php_os'        => PHP_OS,
            'php_version'   => PHP_VERSION,
            'php_sapi_name' => php_sapi_name(),
            'client_date'   => date('Y-m-d H:i:s'), 
        ];
        $ret = CurlPost($url, array_merge($data, $params), $data_type);
        if($ret['code'] != 0)
        {
            // 网络不通
            MyCache($key, 0, $ret['data']);
            $ret['msg'] = MyLang('store_content_error_tips').'[ '.$ret['msg'].' ]';
            return $ret;
        }

        // 数据解析
        $result = json_decode($ret['data'], true);
        if(empty($result))
        {
            return DataReturn(MyLang('store_respond_data_error_tips').(empty($ret['data']) ? '' : '('.$ret['data'].')'), -1);
        }

        // 是否非数组
        if(is_string($result))
        {
            return DataReturn(MyLang('store_respond_data_invalid_tips').'[ '.$result.' ]', -1);
        }

        // 请求成功
        if(isset($result['code']) && $result['code'] == 0)
        {
            if(empty($result['data']))
            {
                return DataReturn(MyLang('store_respond_empty_tips'), -1);
            }
            return $result;
        }
        return DataReturn(empty($result['msg']) ? MyLang('store_respond_data_empty_tips') : MyLang('store_respond_result_tips').'[ '.$result['msg'].' ]', -1);
    }
}
?>