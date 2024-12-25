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
use app\service\ResourcesService;
use app\service\ConfigService;
use app\service\StoreService;
use app\service\ThemeDataService;

/**
 * 主题管理服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ThemeAdminService
{
    // 静态目录和html目录
    private static $html_path = 'app'.DS.'index'.DS.'view'.DS;
    private static $static_path = 'public'.DS.'static'.DS.'index'.DS;

    // 排除的文件后缀
    private static $exclude_ext = ['php'];

    /**
     * 默认主题标识符
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-20
     * @desc    description
     */
    public static function DefaultTheme()
    {
        return MyC('common_default_theme', 'default', true);
    }

    /**
     * 主题首页是否主题数据模式
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-05
     * @desc    description
     * @param   [string]          $theme [主题标识]
     */
    public static function HomeIsThemeDataMode($theme)
    {
        $ret = self::ThemeAdminConfig($theme);
        return (!empty($ret['data']) && isset($ret['data']['home_is_theme_data_mode']) && $ret['data']['home_is_theme_data_mode'] == 1);
    }

    /**
     * 获取模板列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-05-10T10:24:40+0800
     * @param    [array]          $params [输入参数]
     * @return   [array]                  [模板列表]
     */
    public static function ThemeAdminList($params = [])
    {
        $result = [];
        $dir = ROOT.self::$html_path;
        if(is_dir($dir))
        {
            if($dh = opendir($dir))
            {
                $default_preview = __MY_PUBLIC_URL__.'static'.DS.'common'.DS.'images'.DS.'default-preview.jpg';
                while(($temp_file = readdir($dh)) !== false)
                {
                    if(substr($temp_file, 0, 1) == '.' || in_array($temp_file, ['index.html']) || is_dir($temp_file))
                    {
                        continue;
                    }

                    $config = $dir.$temp_file.DS.'config.json';
                    if(!file_exists($config))
                    {
                        continue;
                    }

                    // 读取配置文件
                    $config = json_decode(file_get_contents($config), true);
                    if(!empty($config) && is_array($config))
                    {
                        if(empty($config['name']) || empty($config['ver']) || empty($config['author']))
                        {
                            continue;
                        }
                        $preview = ROOT.self::$static_path.$temp_file.DS.'images'.DS.'preview.jpg';
                        $result[] = array(
                            'theme'                    =>  $temp_file,
                            'name'                     =>  htmlentities($config['name']),
                            'ver'                      =>  str_replace(array('，',','), ', ', htmlentities($config['ver'])),
                            'author'                   =>  htmlentities($config['author']),
                            'home'                     =>  isset($config['home']) ? $config['home'] : '',
                            'preview'                  =>  file_exists($preview) ? __MY_PUBLIC_URL__.'static'.DS.'index'.DS.$temp_file.DS.'images'.DS.'preview.jpg' : $default_preview,
                            'is_theme_data_admin'      => (isset($config['is_theme_data_admin']) && $config['is_theme_data_admin'] == 1) ? 1 : 0,
                            'home_is_theme_data_mode'  => (isset($config['home_is_theme_data_mode']) && $config['home_is_theme_data_mode'] == 1) ? 1 : 0,
                            'is_delete'                => ($temp_file == 'default') ? 0 : 1,
                        );
                    }
                }
                closedir($dh);
            }
        }
        return $result;
    }

    /**
     * 主题数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-12-20
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeAdminData($params = [])
    {
        $data = [];
        if(!empty($params['id']))
        {
            $list = self::ThemeAdminList($params);
            if(!empty($list))
            {
                $list = array_column($list, null, 'theme');
                if(array_key_exists($params['id'], $list))
                {
                    $data = $list[$params['id']];
                }
            }
        }
        return $data;
    }

    /**
     * 模板上传
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:53:45+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ThemeAdminUpload($params = [])
    {
        // 文件上传校验
        $error = FileUploadError('theme');
        if($error !== true)
        {
            return DataReturn($error, -1);
        }

        // 文件格式化校验
        $type = ResourcesService::ZipExtTypeList();
        if(!in_array($_FILES['theme']['type'], $type))
        {
            return DataReturn(MyLang('form_upload_zip_message'), -2);
        }

        // 上传处理
        return self::ThemeAdminUploadHandle($_FILES['theme']['tmp_name'], $params);
    }
    
    /**
     * 模板上传处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     * @param   [string]         $package_file [软件包地址]
     * @param   [array]          $params       [输入参数]
     */
    public static function ThemeAdminUploadHandle($package_file, $params = [])
    {
        // 目录是否有权限
        if(!is_writable(ROOT.self::$html_path))
        {
            return DataReturn(MyLang('common_service.themeadmin.view_dir_no_power_tips').'['.ROOT.self::$html_path.']', -10);
        }
        if(!is_writable(ROOT.self::$static_path))
        {
            return DataReturn(MyLang('common_service.themeadmin.static_dir_no_power_tips').'['.self::$static_path.']', -10);
        }

        // 资源目录
        $dir_list = [
            '_html_'        => ROOT.self::$html_path,
            '_static_'      => ROOT.self::$static_path,
        ];

        // 开始解压文件
        $zip = new \ZipArchive();
        $resource = $zip->open($package_file);
        if($resource !== true)
        {
            return DataReturn(MyLang('form_open_zip_message').'['.$resource.']', -11);
        }

        // 配置信息
        for($i=0; $i<$zip->numFiles; $i++)
        {
            $file = $zip->getNameIndex($i);
            if(stripos($file, 'config.json') !== false)
            {
                // 打开文件资源
                $stream = $zip->getStream($file);
                if($stream === false)
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.themeadmin.upload_config_file_get_fail_tips'), -1);
                }

                // 获取配置信息并解析
                $file_content = stream_get_contents($stream);
                $config = empty($file_content) ? [] : json_decode($file_content, true);
                if(empty($config))
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.themeadmin.config_file_no_exist_tips'), -1);
                }

                // 主题名称
                $theme = substr($file, 0, strpos($file, '/'));

                // 安全验证
                $ret = self::ThemeAdminLegalCheck($theme, $config);
                if($ret['code'] != 0)
                {
                    $zip->close();
                    return $ret;
                }
            }
        }

        // 处理文件
        $success = 0;
        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(strpos($file, '/.') === false && strpos($file, '__') === false)
            {
                // 文件包对应系统所在目录
                $is_has_find = false;
                foreach($dir_list as $dir_key=>$dir_value)
                {
                    if(strpos($file, $dir_key) !== false)
                    {
                        // 匹配成功文件路径处理、跳出循环
                        $new_file = str_replace($dir_key.'/', '', $dir_value.$file);
                        $is_has_find = true;
                        break;
                    }
                }

                // 没有匹配到则指定目录跳过
                if($is_has_find == false)
                {
                    continue;
                }

                // 排除后缀文件
                $pos = strripos($file, '.');
                if($pos !== false)
                {
                    $info = pathinfo($file);
                    if(isset($info['extension']) && in_array(strtolower($info['extension']), self::$exclude_ext))
                    {
                        continue;
                    }
                }

                // 截取文件路径
                $file_path = substr($new_file, 0, strrpos($new_file, '/'));

                // 路径不存在则创建
                if(!is_dir($file_path))
                {
                    mkdir($file_path, 0777, true);
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
                            if(file_put_contents($new_file, $file_content))
                            {
                                $success++;
                            }
                        }
                        fclose($stream);
                    }
                }
            }
        }
        // 关闭zip  
        $zip->close();

        // 未匹配成功一个文件则认为插件包无效
        if($success <= 0)
        {
            return DataReturn(MyLang('common_service.themeadmin.package_invalid_tips'), -1);
        }

        // 主题数据处理
        ThemeDataService::ThemeDataUploadHandle($package_file, array_merge($params, ['is_theme_upload'=>1]));

        // 返回成功
        return DataReturn(MyLang('install_success'), 0);
    }

    /**
     * 主题切换保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-05-26
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeAdminSwitch($params = [])
    {
        // 主题标识
        $theme = empty($params['theme']) ? 'default' : $params['theme'];

        // 安全判断
        $ret = self::ThemeAdminLegalCheck($theme);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 切换配置
        $params['common_default_theme'] = $theme;
        $ret = ConfigService::ConfigSave($params);
        if($ret['code'] == 0)
        {
            // 同步前端主题session数据
            DefaultTheme($theme);
        }
        return $ret;
    }

    /**
     * 模板删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:46:02+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ThemeAdminDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('common_service.themeadmin.template_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 防止路径回溯
        $id = htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($params['id'])));
        if(empty($id))
        {
            return DataReturn(MyLang('common_service.themeadmin.theme_name_error_tips'), -1);
        }

        // default不能删除
        if($id == 'default')
        {
            return DataReturn(MyLang('common_service.themeadmin.system_theme_not_delete_tips'), -2);
        }

        // 不能删除正在使用的主题
        if(self::DefaultTheme() == $id)
        {
            return DataReturn(MyLang('common_service.themeadmin.current_use_theme_error_tips'), -2);
        }

        // 开始删除主题
        if(\base\FileUtil::UnlinkDir(ROOT.self::$html_path.$id) && \base\FileUtil::UnlinkDir(ROOT.self::$static_path.$id))
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 主题打包
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ThemeAdminDownload($params = [])
    {
        // 生成下载包
        $package = self::ThemeAdminDownloadHandle($params);
        if($package['code'] != 0)
        {
            return $package;
        }

        // 开始下载
        if(\base\FileUtil::DownloadFile($package['data']['file'], $package['data']['config']['name'].'_v'.$package['data']['config']['ver'].'.zip', true))
        {
            return DataReturn(MyLang('download_success'), 0);
        }
        return DataReturn(MyLang('download_fail'), -100);
    }

    /**
     * 上传到应用商店
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-08-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeAdminStoreUpload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'version_new',
                'error_msg'         => MyLang('common_service.themeadmin.form_item_version_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'describe',
                'error_msg'         => MyLang('common_service.themeadmin.form_item_desc_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'apply_version',
                'error_msg'         => MyLang('common_service.themeadmin.form_item_apply_version_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 生成下载包
        $package = self::ThemeAdminDownloadHandle($params);
        if($package['code'] != 0)
        {
            return $package;
        }

        // 帐号信息
        $user = StoreService::AccountsData();
        if(empty($user['accounts']) || empty($user['password']))
        {
            return DataReturn(MyLang('store_account_not_bind_tips'), -300);
        }

        // 上传到远程
        $params['theme'] = $package['data']['theme'];
        $params['file'] = new \CURLFile($package['data']['file']);
        $ret = StoreService::RemoteStoreData($user['accounts'], $user['password'], MyConfig('shopxo.store_themeadmin_upload_url'), $params, 2);
        // 是个与否都删除本地生成的zip包
        @unlink($package['data']['file']);
        // 返回数据
        return $ret;
    }

    /**
     * 主题打包处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ThemeAdminDownloadHandle($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('common_service.themeadmin.template_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启开发者模式
        if(MyConfig('shopxo.is_develop') !== true)
        {
            return DataReturn(MyLang('not_open_developer_mode_tips'), -1); 
        }

        // 防止路径回溯
        $theme = htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($params['id'])));
        if(empty($theme))
        {
            return DataReturn(MyLang('common_service.themeadmin.theme_name_error_tips'), -1);
        }

        // 获取配置信息
        $config_res = self::ThemeAdminConfig($theme);
        if($config_res['code'] != 0)
        {
            return $config_res;
        }
        $config = $config_res['data'];

        // 安全判断
        $ret = self::ThemeAdminLegalCheck($theme, $config);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 目录不存在则创建
        $new_dir = ROOT.'runtime'.DS.'data'.DS.'theme_package'.DS.$theme;
        \base\FileUtil::CreateDir($new_dir);

        // 复制包目录 - 视图
        $old_dir = ROOT.self::$html_path.$theme;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_html_') != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'['.MyLang('common_service.themeadmin.theme_copy_view_fail_tips').']', -2);
            }
        }

        // 复制包目录 - 静态文件
        $old_dir = ROOT.self::$static_path.$theme;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir.DS.'_static_') != true)
            {
                return DataReturn(MyLang('project_copy_fail_tips').'['.MyLang('common_service.themeadmin.theme_copy_static_fail_tips').']', -2);
            }
        }

        // 配置文件历史信息更新
        $new_config_file = $new_dir.DS.'_html_'.DS.'config.json';
        if(!file_exists($new_config_file))
        {
            return DataReturn(MyLang('common_service.themeadmin.theme_new_config_error_tips'), -10);
        }
        if(empty($config['history']))
        {
            $config['history'] = [];
        }
        $config['history'][] = [
            'host'  => __MY_HOST__,
            'url'   => __MY_URL__,
            'ip'    => __MY_ADDR__,
            'time'  => date('Y-m-d H:i:s'),
        ];
        if(@file_put_contents($new_config_file, JsonFormat($config)) === false)
        {
            return DataReturn(MyLang('common_service.themeadmin.theme_new_config_update_fail_tips'), -11);
        }

        // 主题数据包同步到主题里面一起下载
        ThemeDataService::ThemeDownloadPackageHandle(['theme'=>$theme, 'dir'=>$new_dir.DS.'_theme_data_'.DS]);

        // 生成压缩包
        $zip = new \base\ZipFolder();
        if(!$zip->zip($new_dir.'.zip', $new_dir))
        {
            return DataReturn(MyLang('form_generate_zip_message'), -100);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($new_dir);

        // 返回数据
        return DataReturn('success', 0, [
            'file'    => $new_dir.'.zip',
            'config'  => $config,
            'theme'   => $theme,
        ]);
    }

    /**
     * 主题安全判断
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-05-26
     * @desc    description
     * @param   [string]          $theme [主题标识]
     * @param   [array]           $data  [主题数据]
     */
    public static function ThemeAdminLegalCheck($theme, $data = [])
    {
        if(RequestModule() == 'admin')
        {
            $key = 'theme_legal_check_'.$theme;
            $ret = MyCache($key);
            if(empty($ret))
            {
                if(empty($data))
                {
                    $config_res = self::ThemeAdminConfig($theme);
                    if($config_res['code'] != 0)
                    {
                        return $config_res;
                    }
                    $data = $config_res['data'];
                }
                $check_params = [
                    'type'      => 'webtheme',
                    'config'    => $data,
                    'plugins'   => $theme,
                    'author'    => isset($data['author']) ? $data['author'] : '',
                    'ver'       => isset($data['version']) ? $data['version'] : (isset($data['ver']) ? $data['ver'] : ''), 
                ];
                $ret = StoreService::PluginsLegalCheck($check_params);
                MyCache($key, $ret, 3600);
            }
            if(!in_array($ret['code'], [0, -9999]))
            {
                return $ret;
            }
        }
        return DataReturn('success', 0);
    }

    /**
     * 主题配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [string]          $theme [主题标识]
     */
    public static function ThemeAdminConfig($theme)
    {
        // 获取配置信息
        $config_file = ROOT.self::$html_path.$theme.DS.'config.json';
        if(!file_exists($config_file))
        {
            return DataReturn(MyLang('common_service.themeadmin.config_file_no_exist_tips'), -1);
        }
        $config = json_decode(file_get_contents($config_file), true);
        if(empty($config))
        {
            return DataReturn(MyLang('common_service.themeadmin.config_error_tips'), -1);
        }
        return DataReturn('success', 0, $config);
    }

    /**
     * web主题更新信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeAdminUpgradeInfo($params = [])
    {
        if(!empty($params))
        {
            // 数据处理
            $data = [];
            foreach($params as $v)
            {
                if(!empty($v['name']) && !empty($v['ver']) && !empty($v['theme']) && !empty($v['author']))
                {
                    $data[] = [
                        'plugins'   => $v['theme'],
                        'name'      => $v['name'],
                        'ver'       => $v['ver'],
                        'author'    => $v['author'],
                    ];
                }
            }
            if(!empty($data))
            {
                // 获取更新信息
                $request_params = [
                    'plugins_type'  => 'webtheme',
                    'plugins_data'  => $data,
                ];
                $res = StoreService::PluginsUpgradeInfo($request_params);
                if(!empty($res['data']) && is_array($res['data']))
                {
                    $res['data'] = array_column($res['data'], null, 'plugins');
                }
                return $res;
            }
        }
        return DataReturn(MyLang('plugins_no_data_tips'), 0);
    }

    /**
     * 主题数据管理url
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-11
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeAdminDataUrl($params = [])
    {
        // 链接数据
        $data = [
            'themedata'          => MyUrl('admin/themedata/saveinfo', ['is_theme_data_admin' => 1]),
            'goods'              => MyUrl('admin/goods/saveinfo', ['is_theme_data_admin' => 1]),
            'goods_category'     => MyUrl('admin/goodscategory/index', ['is_theme_data_admin' => 1]),
            'brand'              => MyUrl('admin/brand/saveinfo', ['is_theme_data_admin' => 1]),
            'brand_list'         => MyUrl('admin/brand/index', ['is_theme_data_admin' => 1]),
            'article'            => MyUrl('admin/article/saveinfo', ['is_theme_data_admin' => 1]),
            'article_list'       => MyUrl('admin/article/index', ['is_theme_data_admin' => 1]),
            'article_category'   => MyUrl('admin/articlecategory/index', ['is_theme_data_admin' => 1]),
            'link_list'          => MyUrl('admin/link/index', ['is_theme_data_admin' => 1]),
            'navigation_header'  => MyUrl('admin/navigation/index', ['type' => 'header', 'is_theme_data_admin' => 1]),
            'navigation_footer'  => MyUrl('admin/navigation/index', ['type' => 'footer', 'is_theme_data_admin' => 1]),
            'slide_list'         => MyUrl('admin/slide/index', ['is_theme_data_admin' => 1]),
            'store_config'       => MyUrl('admin/config/store', ['is_theme_data_admin' => 1]),
            'user_login'         => MyUrl('admin/site/index', ['nav_type' => 'login', 'is_theme_data_admin' => 1]),
            'user_register'      => MyUrl('admin/site/index', ['nav_type' => 'register', 'is_theme_data_admin' => 1]),
            'user_forget'        => MyUrl('admin/site/index', ['nav_type' => 'forgetpwd', 'is_theme_data_admin' => 1]),
            'screening_price'    => MyUrl('admin/screeningprice/index', ['is_theme_data_admin' => 1]),
            'site_base'          => MyUrl('admin/site/index', ['nav_type'=>'base', 'switch'=>'site-base', 'is_theme_data_admin' => 1]),
            'site_filing'        => MyUrl('admin/site/index', ['nav_type'=>'base', 'switch'=>'site-filing', 'is_theme_data_admin' => 1]),
        ];

        // 主题数据管理url钩子
        $hook_name = 'plugins_service_theme_admin_data_url';
        MyEventTrigger($hook_name, [
            'hook_name'   => $hook_name,
            'is_backend'  => true,
            'data'        => &$data,
        ]);

        return $data;
    }

    /**
     * 模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeAdminMarket($params = [])
    {
        $params['type'] = 'webtheme';
        return StoreService::PackageDataList($params);
    }
}
?>