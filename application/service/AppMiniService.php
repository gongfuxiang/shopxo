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
        self::$old_root = ROOT.'sourcecode';
        self::$new_root = ROOT.'public'.DS.'download'.DS.'sourcecode';
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
                        $url = __MY_PUBLIC_URL__.'download'.DS.'sourcecode'.DS.self::$application_name.DS.$temp_file;
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
        // 是否https
        if(config('shopxo.is_develop') !== true)
        {
            if(__MY_HTTP__ != 'https')
            {
                return DataReturn('请使用https协议', -1);
            }
        }

        // 初始化
        self::Init($params);

        // 配置内容
        if(empty($params['app_mini_title']) || empty($params['app_mini_describe']))
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
        $search = [
            '{{request_url}}',
            '{{application_title}}',
            '{{application_describe}}',
            '{{price_symbol}}',
        ];
        $replace = [
            __MY_URL__,
            $params['app_mini_title'],
            $params['app_mini_describe'],
            config('shopxo.price_symbol'),
        ];
        $status = file_put_contents($new_dir.DS.'app.js', str_replace($search, $replace, file_get_contents($new_dir.DS.'app.js')));
        if($status === false)
        {
            return DataReturn('基础配置替换失败', -4);
        }

        // app.json
        $status = file_put_contents($new_dir.DS.'app.json', str_replace(['{{application_title}}'], [$params['app_mini_title']], file_get_contents($new_dir.DS.'app.json')));
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
     * 扩展处理 - 微信
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param   [string]          $new_dir [新得源码包目录]
     */
    private static function ExtendHandleWeixin($new_dir)
    {
        // 启用好物推荐
        if(MyC('common_app_is_good_thing', 0) == 1)
        {
            // app.json
            $file = $new_dir.DS.'app.json';
            $data = json_decode(file_get_contents($file), true);
            if(is_array($data) && isset($data['plugins']))
            {
                $data['plugins']['goodsSharePlugin'] = [
                    'version'   => '4.0.1',
                    'provider'  => 'wx56c8f077de74b07c',
                ];
                if(file_put_contents($file, JsonFormat($data)) === false)
                {
                    return DataReturn('好物推荐主配置失败', -50);
                }
            }

            // goods-detail.json
            $file = $new_dir.DS.'pages'.DS.'goods-detail'.DS.'goods-detail.json';
            $data = json_decode(file_get_contents($file), true);
            if(is_array($data) && isset($data['usingComponents']))
            {
                $data['usingComponents']['share-button'] = 'plugin://goodsSharePlugin/share-button';
                if(file_put_contents($file, JsonFormat($data)) === false)
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
            $data = json_decode(file_get_contents($file), true);
            if(is_array($data) && isset($data['plugins']))
            {
                $data['plugins']['live-player-plugin'] = [
                    'version'   => '1.0.7',
                    'provider'  => 'wx2b03c6e691cd7370',
                ];
                if(file_put_contents($file, JsonFormat($data)) === false)
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
        // 参数
        if(empty($params['id']))
        {
            return DataReturn('操作id不能为空', -10);
        }

        // 初始化
        self::Init($params);

        // 目录处理
        $suffix = '';
        if(substr($params['id'], -4) === '.zip')
        {
            $name = substr($params['id'], 0, strlen($params['id'])-4);
            $suffix = '.zip';
        } else {
            $name = $params['id'];
        }

        // 防止路径回溯
        $path = self::$new_path.DS.htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($name))).$suffix;

        // 删除压缩包
        if($suffix == '.zip')
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