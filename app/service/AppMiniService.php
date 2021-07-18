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

use app\service\ResourcesService;

/**
 * 小程序服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppMiniService
{
    // 当前小程序包名称
    public static $application_name;

    // 原包地址/操作地址
    public static $old_root;
    public static $new_root;
    public static $old_path;
    public static $new_path;

    // 当前默认主题
    public static $default_theme;

    /**
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function Init($params = [])
    {
        // 当前小程序包名称
        self::$application_name = isset($params['application_name']) ? $params['application_name'] : 'weixin';

        // 原包地址/操作地址
        self::$old_root = ROOT.'sourcecode';
        self::$new_root = ROOT.'public'.DS.'download'.DS.'sourcecode';
        self::$old_path = self::$old_root.DS.self::$application_name;
        self::$new_path = self::$new_root.DS.self::$application_name;

        // 默认主题
        self::$default_theme = self::DefaultTheme();
    }

    /**
     * 默认主题标识符
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-20
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DefaultTheme($params = [])
    {
        return MyC(self::DefaultThemeKey($params), 'default', true);
    }

    /**
     * 默认主题标识符数据key
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function DefaultThemeKey($params = [])
    {
        if(empty(self::$application_name))
        {
            // 初始化
            self::Init($params);
        }

        return 'common_app_mini_'.self::$application_name.'_default_theme';
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
    public static function ThemeList($params = [])
    {
        // 初始化
        self::Init($params);

        // 读取目录
        $result = [];
        $dir = self::$old_path.DS;
        if(is_dir($dir))
        {
            if($dh = opendir($dir))
            {
                $img_obj = \base\Images::Instance();
                $default_preview = __MY_PUBLIC_URL__.'static'.DS.'common'.DS.'images'.DS.'default-preview.jpg';
                while(($temp_file = readdir($dh)) !== false)
                {
                    if(substr($temp_file, 0, 1) == '.')
                    {
                        continue;
                    }

                    $config = $dir.$temp_file.DS.'config.json';
                    if(is_file($dir.$temp_file) || !is_dir($dir.$temp_file) || !is_file($config) || !file_exists($config))
                    {
                        continue;
                    }

                    // 读取配置文件
                    $data = json_decode(file_get_contents($config), true);
                    if(!empty($data) && is_array($data))
                    {
                        if(empty($data['name']) || empty($data['ver']) || empty($data['author']))
                        {
                            continue;
                        }
                        $preview = $dir.$temp_file.DS.'images'.DS.'preview.jpg';
                        $result[] = array(
                            'theme'     =>  $temp_file,
                            'name'      =>  htmlentities($data['name']),
                            'ver'       =>  str_replace(array('，',','), ', ', htmlentities($data['ver'])),
                            'author'    =>  htmlentities($data['author']),
                            'home'      =>  isset($data['home']) ? $data['home'] : '',
                            'preview'   =>  $img_obj->ImageToBase64(file_exists($preview) ? $preview : $default_preview),
                            'is_delete' => ($temp_file == 'default') ? 0 : 1,
                        );
                    }
                }
                closedir($dh);
            }
        }
        return $result;
    }

    /**
     * 模板上传
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:53:45+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ThemeUpload($params = [])
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
            return DataReturn('文件格式有误，请上传zip压缩包', -2);
        }

        // 上传处理
        return self::ThemeUploadHandle($_FILES['theme']['tmp_name'], $params);
    }

    /**
     * 模板上传处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:53:45+0800
     * @param    [string]         $package_file [软件包地址]
     * @param    [array]          $params       [输入参数]
     */
    public static function ThemeUploadHandle($package_file, $params = [])
    {
        // 初始化
        self::Init($params);

        // 主题目录
        $dir = self::$old_path.DS;

        // 目录是否有权限
        if(!is_writable($dir))
        {
            return DataReturn('视图目录没权限['.$dir.']', -10);
        }

        // 开始解压文件
        $resource = zip_open($package_file);
        while(($temp_resource = zip_read($resource)) !== false)
        {
            if(zip_entry_open($resource, $temp_resource))
            {
                // 资源文件
                $file = zip_entry_name($temp_resource);

                // 排除系统.开头的临时文件和目录
                if(strpos($file, '/.') !== false)
                {
                    continue;
                }

                // 截取文件路径
                $file_path = $dir.substr($file, 0, strrpos($file, '/'));

                // 路径不存在则创建
                if(!is_dir($file_path))
                {
                    mkdir($file_path, 0777, true);
                }

                // 如果不是目录则写入文件
                if(!is_dir($dir.$file))
                {
                    // 读取这个文件
                    $file_size = zip_entry_filesize($temp_resource);
                    $file_content = zip_entry_read($temp_resource, $file_size);
                    file_put_contents($dir.$file, $file_content);
                }
                
                // 关闭目录项  
                zip_entry_close($temp_resource);
            }
        }
        return DataReturn('安装成功');
    }

    /**
     * 模板删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:46:02+0800
     * @param    [array]          $params [输入参数]
     */
    public static function ThemeDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '模板id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 初始化
        self::Init($params);

        // 防止路径回溯
        $id = htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($params['id'])));
        if(empty($id))
        {
            return DataReturn('主题名称有误', -1);
        }

        // default不能删除
        if($id == 'default')
        {
            return DataReturn('系统模板不能删除', -2);
        }

        // 不能删除正在使用的主题
        if(self::$default_theme == $id)
        {
            return DataReturn('不能删除正在使用的主题', -2);
        }

        // 开始删除主题
        if(\base\FileUtil::UnlinkDir(self::$old_path.DS.$id))
        {
            return DataReturn('删除成功');
        }
        return DataReturn('删除失败或资源不存在', -100);
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
    public static function ThemeDownload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '模板id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 初始化
        self::Init($params);

        // 是否开启开发者模式
        if(MyConfig('shopxo.is_develop') !== true)
        {
            return DataReturn('请先开启开发者模式', -1); 
        }

        // 防止路径回溯
        $theme = htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($params['id'])));
        if(empty($theme))
        {
            return DataReturn('主题名称有误', -1);
        }

        // 获取配置信息
        $config_res = self::MiniThemeConfig($theme, $params);
        if($config_res['code'] != 0)
        {
            return $config_res;
        }
        $config = $config_res['data'];

        // 目录不存在则创建
        $new_dir = ROOT.'runtime'.DS.'data'.DS.'theme_appmini_package'.DS.$theme;
        \base\FileUtil::CreateDir($new_dir);

        // 复制包目录
        $old_dir = self::$old_path.DS.$theme;
        if(is_dir($old_dir))
        {
            if(\base\FileUtil::CopyDir($old_dir, $new_dir) != true)
            {
                return DataReturn('项目包复制失败', -2);
            }
        }

        // 历史信息更新
        $ret = self::HistoryUpdateHandle($new_dir);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 生成压缩包
        $zip = new \base\ZipFolder();
        if(!$zip->zip($new_dir.'.zip', $new_dir))
        {
            return DataReturn('压缩包生成失败', -100);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($new_dir);

        // 开始下载
        $appmini_type = lang('common_appmini_type');
        $application_name = array_key_exists(self::$application_name, $appmini_type) ? $appmini_type[self::$application_name]['name'].'-' : '';
        if(\base\FileUtil::DownloadFile($new_dir.'.zip', $application_name.$config['name'].'_v'.$config['ver'].'.zip'))
        {
            @unlink($new_dir.'.zip');
        } else {
            return DataReturn('下载失败', -100);
        }
    }

    /**
     * 主题配置信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [string]         $theme     [主题标识]
     * @param   [array]          $params    [输入参数]
     */
    public static function MiniThemeConfig($theme, $params)
    {
        // 初始化
        self::Init($params);

        // 获取配置信息
        $config_file = self::$old_path.DS.$theme.DS.'config.json';
        if(!file_exists($config_file))
        {
            return DataReturn('小程序主题配置文件不存在', -1);
        }
        $config = json_decode(file_get_contents($config_file), true);
        if(empty($config))
        {
            return DataReturn('主小程序题配置信息有误', -1);
        }
        return DataReturn('success', 0, $config);
    }

    /**
     * 获取小程序下载包数据列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-05-10T10:24:40+0800
     * @param    [array]          $params [输入参数]
     */
    public static function DownloadDataList($params = [])
    {
        // 初始化
        self::Init($params);

        // 获取包列表
        $result = [];
        if(is_dir(self::$new_path))
        {
            if($dh = opendir(self::$new_path))
            {
                while(($temp_file = readdir($dh)) !== false)
                {
                    if($temp_file != '.' && $temp_file != '..')
                    {
                        $file_path = self::$new_path.DS.$temp_file;
                        $url = __MY_PUBLIC_URL__.'download'.DS.'sourcecode'.DS.self::$application_name.DS.$temp_file;
                        $result[] = [
                            'name'  => (substr($temp_file, -4) === '.zip') ? substr($temp_file, 0, strlen($temp_file)-4) : $temp_file,
                            'url'   => substr($url, -4) == '.zip' ? $url : '',
                            'size'  => FileSizeByteToUnit(filesize($file_path)),
                            'time'  => date('Y-m-d H:i:s', filectime($file_path)),
                        ];
                    }
                }
                closedir($dh);
            }
        }
        return DataReturn('success', 0, $result);
    }

    /**
     * 源码包生成
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function Created($params = [])
    {
        // 是否https
        if(MyConfig('shopxo.is_develop') !== true)
        {
            if(__MY_HTTP__ != 'https')
            {
                return DataReturn('请使用https协议', -1);
            }
        }

        // 初始化
        self::Init($params);

        // 配置内容
        $title = MyC('common_app_mini_'.self::$application_name.'_title');
        $describe = MyC('common_app_mini_'.self::$application_name.'_describe');
        if(empty($title) || empty($describe))
        {
            return DataReturn('配置信息不能为空', -1);
        }

        // 源码目录不存在则创建
        \base\FileUtil::CreateDir(self::$new_root);

        // 源码目标目录是否存在
        if(!is_dir(self::$new_root))
        {
            return DataReturn('源码目标目录不存在['.self::$new_root.']', -1);
        }

        // 源码目标目录没有权限
        if(!is_writable(self::$new_root))
        {
            return DataReturn('源码目标目录没有权限['.self::$new_root.']', -1);
        }

        // 目录不存在则创建
        \base\FileUtil::CreateDir(self::$new_path);

        // 复制包目录
        $old_dir = self::$old_path.DS.self::$default_theme;
        $new_dir = self::$new_path.DS.date('YmdHis');
        if(\base\FileUtil::CopyDir($old_dir, $new_dir) != true)
        {
            return DataReturn('项目包复制失败', -2);
        }

        // 校验基础文件是否存在
        if(!file_exists($new_dir.DS.'app.js') || !file_exists($new_dir.DS.'app.json'))
        {
            return DataReturn('包基础文件不存在，请重新生成', -3);
        }

        // 替换内容
        // app.js
        $search = [
            '{{request_url}}',
            '{{application_title}}',
            '{{application_describe}}',
            '{{currency_symbol}}',
        ];
        $replace = [
            __MY_URL__,
            $title,
            $describe,
            MyConfig('shopxo.currency_symbol'),
        ];
        $status = file_put_contents($new_dir.DS.'app.js', str_replace($search, $replace, file_get_contents($new_dir.DS.'app.js')));
        if($status === false)
        {
            return DataReturn('基础配置替换失败', -4);
        }

        // app.json
        $status = file_put_contents($new_dir.DS.'app.json', str_replace(['{{application_title}}'], [$title], file_get_contents($new_dir.DS.'app.json')));
        if($status === false)
        {
            return DataReturn('基础配置替换失败', -4);
        }

        // 小程序额外处理
        switch(self::$application_name)
        {
            // 微信
            case 'weixin' :
                $ret = self::ExtendHandleWeixin($new_dir);
                break;

            // 默认
            default :
                $ret = DataReturn('无需处理', 0);
        }
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 历史信息更新
        $ret = self::HistoryUpdateHandle($new_dir);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 生成压缩包
        $zip = new \base\ZipFolder();
        if(!$zip->zip($new_dir.'.zip', $new_dir))
        {
            return DataReturn('压缩包生成失败', -100);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($new_dir);

        return DataReturn('生成成功', 0);
    }

    /**
     * 历史信息更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-18
     * @desc    description
     * @param   [string]          $new_dir [新源码包目录]
     */
    public static function HistoryUpdateHandle($new_dir)
    {
        // 配置信息
        $file = $new_dir.DS.'app.json';
        $config = json_decode(file_get_contents($file), true);

        // 插件配置为空防止成为数组
        if(array_key_exists('plugins', $config) && empty($config['plugins']))
        {
            $config['plugins'] = (object) [];
        }

        // 历史信息
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
        if(@file_put_contents($file, JsonFormat($config)) === false)
        {
            return DataReturn('新应用配置文件更新失败', -11);
        }
    }

    /**
     * 扩展处理 - 微信
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param   [string]          $new_dir [新源码包目录]
     */
    private static function ExtendHandleWeixin($new_dir)
    {
        // 启用好物推荐
        if(MyC('common_app_is_good_thing', 0) == 1)
        {
            // app.json
            $file = $new_dir.DS.'app.json';
            $config = json_decode(file_get_contents($file), true);
            if(is_array($config) && isset($config['plugins']))
            {
                $config['plugins']['goodsSharePlugin'] = [
                    'version'   => MyC('common_app_is_good_thing_ver', '4.0.1', true),
                    'provider'  => 'wx56c8f077de74b07c',
                ];
                if(file_put_contents($file, JsonFormat($config)) === false)
                {
                    return DataReturn('好物推荐主配置失败', -50);
                }
            }

            // goods-detail.json
            $file = $new_dir.DS.'pages'.DS.'goods-detail'.DS.'goods-detail.json';
            $config = json_decode(file_get_contents($file), true);
            if(is_array($config) && isset($config['usingComponents']))
            {
                $config['usingComponents']['share-button'] = 'plugin://goodsSharePlugin/share-button';
                if(file_put_contents($file, JsonFormat($config)) === false)
                {
                    return DataReturn('好物推荐商品配置失败', -51);
                }
            }
        }

        // 启用直播
        if(MyC('common_app_weixin_liveplayer', 0) == 1)
        {
            // app.json
            $file = $new_dir.DS.'app.json';
            $config = json_decode(file_get_contents($file), true);
            if(is_array($config) && isset($config['plugins']))
            {
                $config['plugins']['live-player-plugin'] = [
                    'version'   => MyC('common_app_weixin_liveplayer_ver', '1.3.0', true),
                    'provider'  => 'wx2b03c6e691cd7370',
                ];
                if(file_put_contents($file, JsonFormat($config)) === false)
                {
                    return DataReturn('直播配置失败', -50);
                }
            }
        }

        return DataReturn('配置成功', 0);
    }

    /**
     * 源码包删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Delete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn('操作id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 初始化
        self::Init($params);

        // 循环操作
        $sucs = 0;
        $fail = 0;
        foreach($params['ids'] as $id)
        {
            // 目录处理
            $suffix = '';
            if(substr($id, -4) === '.zip')
            {
                $name = substr($id, 0, strlen($id)-4);
                $suffix = '.zip';
            } else {
                $name = $id;
            }

            // 防止路径回溯
            $path_name = self::$new_path.DS.htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($id)));
            $path_zip = $path_name.'.zip';

            // 删除包
            $statusz = \base\FileUtil::UnlinkFile($path_zip);
            $statusf = \base\FileUtil::UnlinkDir($path_name);
            if($statusz || $statusf)
            {
                $sucs++;
            } else {
                $fail++;
            }
        }

        // 成功
        if($sucs == count($params['ids']))
        {
            return DataReturn('删除成功');
        }

        // 失败
        if($fail == count($params['ids']))
        {
            return DataReturn('删除失败', -100);
        }

        return DataReturn('成功['.$sucs.'],失败['.$fail.']');
    }

    /**
     * 小程序主题更新信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AppMiniUpgradeInfo($params = [])
    {
        if(!empty($params) && !empty($params['data']) && !empty($params['terminal']))
        {
            // 数据处理
            $data = [];
            foreach($params['data'] as $v)
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
                    'plugins_type'      => 'minitheme',
                    'plugins_data'      => $data,
                    'plugins_terminal'  => $params['terminal'],
                ];
                $res = StoreService::PluginsUpgradeInfo($request_params);
                if(!empty($res['data']))
                {
                    $res['data'] = array_column($res['data'], null, 'plugins');
                }
                return $res;
            }
        }

        return DataReturn('无插件数据', 0);
    }
}
?>