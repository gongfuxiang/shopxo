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

use think\facade\Db;
use app\service\PluginsAdminService;
use app\service\PluginsService;
use app\service\PaymentService;
use app\service\ThemeAdminService;
use app\service\AppMiniService;
use app\service\StoreService;

/**
 * 包更新服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-04-22
 * @desc    description
 */
class PackageUpgradeService
{
    // 输入参数
    public static $params;

    /**
     * 更新入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Run($params = [])
    {
        // 参数校验
        $ret = self::ParamsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 插件信息获取
        $config = self::ConfigDataInit();
        if($config['code'] != 0)
        {
            return $config;
        }

        // 操作类型
        switch(self::$params['opt'])
        {
            // 获取url地址
            case 'url' :
                $ret = self::UrlHandle(self::$params);
                break;

            // 下载软件包
            case 'download' :
                $ret = self::DownloadHandle(self::$params['key']);
                break;

            // 更新软件包
            case 'upgrade' :
                $ret = self::UpgradeHandle(self::$params);
                break;
        }
        return $ret;
    }

    /**
     * 更新软件包
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UpgradeHandle($params)
    {
        // 获取目录文件
        $res = self::DirFileData($params['key']);
        if(!file_exists($res['url']))
        {
            return DataReturn(MyLang('common_service.pluginsupgrade.package_no_exist_tips'), -1);
        }

        // 根据插件类型调用安装程序
        switch($params['plugins_type'])
        {
            // 功能插件
            case 'plugins' :
                $ret = PluginsAdminService::PluginsUpgradeHandle($res['url'], $params);
                break;

            // 支付插件
            case 'payment' :
                $ret = PaymentService::UploadHandle($res['url'], $params);
                break;

            // web主题
            case 'webtheme' :
                $ret = ThemeAdminService::ThemeAdminUploadHandle($res['url'], $params);
                break;

            // 小程序主题
            case 'minitheme' :
                if(empty($params['plugins_terminal']))
                {
                    return DataReturn(MyLang('common_service.pluginsupgrade.terminal_not_appoint_error_tips'), -1);
                }
                $params['application_name'] = $params['plugins_terminal'];
                $ret = AppMiniService::ThemeUploadHandle($res['url'], $params);
                break;

            // 默认
            default :
                $ret = DataReturn(MyLang('common_service.pluginsupgrade.plugins_type_undefined_tips').'['.$params['plugins_type'].']', -1);
        }

        // 移除session
        MySession($params['key'], null);

        // 删除本地文件
        \base\FileUtil::UnlinkFile($res['url']);

        // 返回提示
        if($ret['code'] == 0)
        {
            $ret['msg'] = MyLang('update_success');
        }
        return $ret;
    }

    /**
     * 下载软件包
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [string]          $key [缓存key]
     */
    public static function DownloadHandle($key)
    {
        // 获取下载地址
        $url = MySession($key);
        if(empty($url))
        {
            return DataReturn(MyLang('common_service.pluginsupgrade.download_url_empty_tips'), -1);
        }

        // 获取目录文件
        $res = self::DirFileData($key);

        // 目录不存在则创建
        \base\FileUtil::CreateDir($res['dir'].$res['path']);

        // 下载保存
        if(@file_put_contents($res['url'], RequestGet($url, 300000)) !== false)
        {
            return DataReturn('success', 0, $key);
        }
        return DataReturn(MyLang('common_service.pluginsupgrade.plugins_download_fail_tips'), -1);
    }

    /**
     * 获取下载地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UrlHandle($params = [])
    {
        // 帐号信息
        $user = StoreService::AccountsData();
        if(empty($user['accounts']) || empty($user['password']))
        {
            return DataReturn(MyLang('store_account_not_bind_tips'), -300);
        }

        // 获取信息
        $ret = StoreService::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_plugins_upgrade_url'), $params);
        if(!empty($ret) && isset($ret['code']) && $ret['code'] == 0)
        {
            $key = md5($ret['data']);
            MySession($key, $ret['data']);
            $ret['data'] = $key;
        }
        return $ret;
    }
    
    /**
     * 获取软件存储信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [string]          $key [缓存key]
     */
    public static function DirFileData($key)
    {
        // 将软件包下载到磁盘
        $dir = ROOT;
        $path = 'runtime'.DS.'data'.DS.'plugins_package_upgrade'.DS;
        $filename = $key.'.zip';

        // 目录不存在则创建
        \base\FileUtil::CreateDir($dir.$path);

        return [
            'dir'   => $dir,
            'path'  => $path,
            'file'  => $filename,
            'url'   => $dir.$path.$filename,
        ];
    }

    /**
     * 配置信息初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     */
    public static function ConfigDataInit()
    {
        // 根据插件类型获取不通的配置信息
        switch(self::$params['plugins_type'])
        {
            // 功能插件
            case 'plugins' :
                // 获取配置
                $config = PluginsAdminService::GetPluginsConfig(self::$params['plugins_value']);
                if(empty($config) || empty($config['base']))
                {
                    return DataReturn(MyLang('common_service.pluginsupgrade.plugins_config_error_tips'), -1);
                }
                self::$params['plugins_config'] = $config;
                self::$params['plugins_ver'] = $config['base']['version'];
                self::$params['plugins_author'] = $config['base']['author'];
                // 安全校验
                $ret = PluginsService::PluginsLegalCheck(self::$params['plugins_value']);
                if($ret['code'] != 0)
                {
                    return $ret;
                }
                break;

            // 支付插件
            case 'payment' :
                // 获取配置
                $config = PaymentService::GetPaymentConfig(self::$params['plugins_value']);
                if(empty($config))
                {
                    return DataReturn(MyLang('common_service.pluginsupgrade.payment_config_error_tips'), -1);
                }
                self::$params['plugins_config'] = $config['base'];
                self::$params['plugins_ver'] = $config['base']['version'];
                self::$params['plugins_author'] = $config['base']['author'];
                // 安全校验
                $ret = PaymentService::PaymentLegalCheck(self::$params['plugins_value']);
                if($ret['code'] != 0)
                {
                    return $ret;
                }
                break;

            // web主题
            case 'webtheme' :
                // 获取配置
                $config = ThemeAdminService::ThemeAdminConfig(self::$params['plugins_value']);
                if($config['code'] != 0)
                {
                    return $config;
                }
                self::$params['plugins_config'] = $config['data'];
                self::$params['plugins_ver'] = $config['data']['ver'];
                self::$params['plugins_author'] = $config['data']['author'];
                // 安全校验
                $ret = ThemeAdminService::ThemeAdminLegalCheck(self::$params['plugins_value']);
                if($ret['code'] != 0)
                {
                    return $ret;
                }
                break;

            // 小程序主题
            case 'minitheme' :
                // 获取配置
                if(empty(self::$params['plugins_terminal']))
                {
                    return DataReturn(MyLang('common_service.pluginsupgrade.terminal_not_appoint_error_tips'), -1);
                }
                self::$params['application_name'] = self::$params['plugins_terminal'];
                $config = AppMiniService::MiniThemeConfig(self::$params['plugins_value'], self::$params);
                if($config['code'] != 0)
                {
                    return $config;
                }
                self::$params['plugins_config'] = $config['data'];
                self::$params['plugins_ver'] = $config['data']['ver'];
                self::$params['plugins_author'] = $config['data']['author'];
                break;

            // 默认
            default :
                return DataReturn(MyLang('common_service.pluginsupgrade.plugins_type_undefined_tips').'['.self::$params['plugins_type'].']', -1);
        }
        return DataReturn('success', 0);
    }

    /**
     * 输入参数校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ParamsCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'plugins_type',
                'error_msg'         => MyLang('common_service.pluginsupgrade.update_type_error_tps'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'plugins_value',
                'error_msg'         => MyLang('common_service.pluginsupgrade.plugins_identification_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'opt',
                'checked_data'      => ['url', 'download', 'upgrade'],
                'error_msg'         => MyLang('operate_type_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 下载和安装需要校验key
        if(in_array($params['opt'], ['download', 'upgrade']) && empty($params['key']))
        {
            return DataReturn(MyLang('common_service.pluginsupgrade.operate_key_error_tips'), -1);
        }

        self::$params = $params;
        return DataReturn('success', 0);
    }
}
?>