<?php
namespace app\service;

use think\Db;

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
    private static $application_name;

    // 原包地址/操作地址
    private static $old_root;
    private static $new_root;
    private static $old_path;
    private static $new_path;

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
        self::$application_name = isset($params['application_name']) ? $params['application_name'] : 'alipay';

        // 原包地址/操作地址
        self::$old_root = ROOT.'public'.DS.'appmini'.DS.'old';
        self::$new_root = ROOT.'public'.DS.'appmini'.DS.'new';
        self::$old_path = self::$old_root.DS.self::$application_name;
        self::$new_path = self::$new_root.DS.self::$application_name;
    }

    /**
     * 获取小程序生成列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-05-10T10:24:40+0800
     * @param    [array]          $params [输入参数]
     */
    public static function DataList($params = [])
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
                        $url = __MY_URL__.'appmini'.DS.'new'.DS.self::$application_name.DS.$temp_file;
                        $result[] = [
                            'name'  => $temp_file,
                            'url'   => substr($url, -4) == '.zip' ? $url : '',
                            'size'  => FileSizeByteToUnit(filesize($file_path)),
                            'time'  => date('Y-m-d H:i:s', filectime($file_path)),
                        ];
                    }
                }
                closedir($dh);
            }
        }
        return $result;
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
        // 初始化
        self::Init($params);

        // 配置内容
        $app_mini_alipay_title = MyC('common_app_mini_alipay_title');
        $app_mini_alipay_describe = MyC('common_app_mini_alipay_describe');
        if(empty($app_mini_alipay_title) || empty($app_mini_alipay_describe))
        {
            return DataReturn('配置信息不能为空', -1);
        }

        // 源码包目录是否存在
        if(!is_dir(self::$new_root))
        {
            return DataReturn('源码包目录不存在', -1);
        }

        // 源码包目录是否有权限
        if(!is_writable(self::$new_root))
        {
            return DataReturn('源码包目录没有权限', -1);
        }

        // 目录不存在则创建
        \base\FileUtil::CreateDir(self::$new_path);

        // 复制包目录
        $new_dir = self::$new_path.DS.date('YmdHis');
        if(\base\FileUtil::CopyDir(self::$old_path, $new_dir) != true)
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
        $status = file_put_contents($new_dir.DS.'app.js', str_replace(['{{request_url}}', '{{application_title}}', '{{application_describe}}'], [__MY_URL__.'index.php?s=', $app_mini_alipay_title, $app_mini_alipay_describe], file_get_contents($new_dir.DS.'app.js')));
        if($status === false)
        {
            return DataReturn('基础配置替换失败', -4);
        }

        // app.json
        $status = file_put_contents($new_dir.DS.'app.json', str_replace(['{{application_title}}'], [$app_mini_alipay_title], file_get_contents($new_dir.DS.'app.json')));
        if($status === false)
        {
            return DataReturn('基础配置替换失败', -4);
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
     * 源码包删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public static function Delete($params = [])
    {
        // 参数
        if(empty($params['id']))
        {
            return DataReturn('操作id不能为空', -10);
        }

        // 初始化
        self::Init($params);

        // 删除压缩包
        $path = self::$new_path.DS.$params['id'];
        if(substr($path, -4) == '.zip')
        {
            $status = \base\FileUtil::UnlinkFile($path);
        } else {
            $status = \base\FileUtil::UnlinkDir($path);
        }
        if($status)
        {
            return DataReturn('删除成功');
        }
        return DataReturn('删除失败或资源不存在', -100);
    }
}
?>