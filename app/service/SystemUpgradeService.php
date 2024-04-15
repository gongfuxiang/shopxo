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

use app\service\SqlConsoleService;
use app\service\PluginsService;
use app\service\StoreService;

/**
 * 系统更新服务层
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-04-23
 * @desc    description
 */
class SystemUpgradeService
{
    // 输入参数
    public static $params;

    // session key
    public static $package_url_key = 'package_url_key';
    public static $package_system_dir_key = 'package_system_dir_key';
    public static $package_upgrade_dir_key = 'package_upgrade_dir_key';

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
        // 是否存在插件未更新的情况
        $plugins_check = PluginsService::PluginsNewVersionCheck();
        if($plugins_check['code'] != 0)
        {
            return $plugins_check;
        }

        // 参数校验
        $ret = self::ParamsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 操作类型
        switch(self::$params['opt'])
        {
            // 获取url地址
            case 'url' :
                $ret = self::UrlHandle(self::$params);
                break;

            // 下载包
            case 'download_system' :
            case 'download_upgrade' :
                $ret = self::DownloadHandle(self::$params);
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
    public static function UpgradeHandle($params = [])
    {
        // 系统包
        $system_url = MySession(self::$package_system_dir_key);
        if(empty($system_url) || !file_exists($system_url))
        {
            return DataReturn(MyLang('common_service.systemupgrade.system_package_no_exist_tips'), -1);
        }

        // 升级包
        $upgrade_url = MySession(self::$package_upgrade_dir_key);
        if(empty($upgrade_url) || !file_exists($upgrade_url))
        {
            return DataReturn(MyLang('common_service.systemupgrade.update_package_no_exist_tips'), -1);
        }

        // 系统包处理
        $ret = self::SystemPackageHandle($system_url);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 升级包处理
        $ret = self::UpgradePackageHandle($upgrade_url);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 移除session
        MySession(self::$package_url_key, null);
        MySession(self::$package_system_dir_key, null);
        MySession(self::$package_upgrade_dir_key, null);

        // 删除本地文件
        \base\FileUtil::UnlinkFile($system_url);
        \base\FileUtil::UnlinkFile($upgrade_url);

        // 返回提示
        return DataReturn(MyLang('update_success'), 0);
    }

    /**
     * 升级包处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-24
     * @desc    description
     * @param   [string]      $package_file [包地址]
     */
    public static function UpgradePackageHandle($package_file)
    {
        // 开始解压文件
        $zip = new \ZipArchive();
        $resource = $zip->open($package_file);
        if($resource !== true)
        {
            return DataReturn(MyLang('form_open_zip_message').'['.$resource.']', -11);
        }

        // 需要处理的文件
        $handle_file_arr = [
            'update.sql',
            'power.sql',
        ];

        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(strpos($file, '/.') === false && !is_dir($file) && in_array($file, $handle_file_arr))
            {
                // 读取这个文件
                $stream = $zip->getStream($file);
                if($stream !== false)
                {
                    $file_content = stream_get_contents($stream);
                    if(!empty($file_content))
                    {
                        SqlConsoleService::Implement(['sql'=>$file_content]);
                    }
                    fclose($stream);
                }
            }
        }
        // 关闭zip  
        $zip->close();

        return DataReturn('success', 0);
    }

    /**
     * 系统包处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-24
     * @desc    description
     * @param   [string]      $package_file [包地址]
     */
    public static function SystemPackageHandle($package_file)
    {
        // 开始解压文件
        $zip = new \ZipArchive();
        $resource = $zip->open($package_file);
        if($resource !== true)
        {
            return DataReturn(MyLang('form_open_zip_message').'['.$resource.']', -11);
        }
        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(!empty($file) && strpos($file, '/.') === false)
            {
                // 文件实际位置
                $new_file = ROOT.$file;

                // 截取文件路径
                $file_path = substr($new_file, 0, strrpos($new_file, '/'));

                // 路径不存在则创建、根目录文件不创建目录
                if(strpos($file, '/') !== false)
                {
                    \base\FileUtil::CreateDir($file_path);
                }

                // 如果不是目录则写入文件
                if(!is_dir($new_file))
                {
                    // 读取这个文件
                    $stream = $zip->getStream($file);
                    if($stream !== false)
                    {
                        $file_content = stream_get_contents($stream);
                        if($file_content !== false)
                        {
                            file_put_contents($new_file, $file_content);
                        }
                        fclose($stream);
                    }
                }
            }
        }
        // 关闭zip  
        $zip->close();

        return DataReturn('success', 0);
    }

    /**
     * 下载软件包
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DownloadHandle($params = [])
    {
        // 获取下载地址
        $data = MySession(self::$package_url_key);
        if(empty($data) || !is_array($data) || empty($data[$params['opt']]))
        {
            return DataReturn('下载地址为空', -1);
        }
        $url = $data[$params['opt']];
        
        // 获取目录文件
        $key = md5($url);
        $res = self::DirFileData($key);

        // 目录不存在则创建
        \base\FileUtil::CreateDir($res['dir'].$res['path']);

        // 下载保存
        if(@file_put_contents($res['url'], RequestGet($url, 300000)) !== false)
        {
            // 存储已下载文件地址session
            MySession(self::SaveDirPathUrl($params['opt']), $res['url']);
            return DataReturn('success', 0);
        }
        return DataReturn(MyLang('common_service.systemupgrade.package_download_fail_tips'), -1);
    }

    /**
     * 获取存储路径session key
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-23
     * @desc    description
     * @param   [string]          $opt [操作类型]
     */
    public static function SaveDirPathUrl($opt)
    {
        $dir_arr = [
            'download_system'   => self::$package_system_dir_key,
            'download_upgrade'  => self::$package_upgrade_dir_key,
        ];
        return isset($dir_arr[$opt]) ? $dir_arr[$opt] : '';
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
        $ret = StoreService::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_system_upgrade_url'), $params);
        if(!empty($ret) && isset($ret['code']) && $ret['code'] == 0)
        {
            MySession(self::$package_url_key, $ret['data']);
            return DataReturn(MyLang('get_success'), 0);
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
        $path = 'runtime'.DS.'data'.DS.'system_upgrade'.DS;
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
                'checked_type'      => 'in',
                'key_name'          => 'opt',
                'checked_data'      => ['url', 'download_system', 'download_upgrade', 'upgrade'],
                'error_msg'         => MyLang('operate_type_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        self::$params = $params;
        return DataReturn('success', 0);
    }
}
?>