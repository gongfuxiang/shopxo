<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
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
    private $application_name;

    // 原包地址/操作地址
    private $old_root;
    private $new_root;
    private $old_path;
    private $new_path;

    /**
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-21
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public function Init($params = [])
    {
        // 当前小程序包名称
        $this->application_name = isset($params['application_name']) ? $params['application_name'] : 'alipay';

        // 原包地址/操作地址
        $this->old_root = ROOT.'public'.DS.'appmini'.DS.'old';
        $this->new_root = ROOT.'public'.DS.'appmini'.DS.'new';
        $this->old_path = $this->old_root.DS.$this->application_name;
        $this->new_path = $this->new_root.DS.$this->application_name;
    }

    /**
     * 获取小程序生成列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-05-10T10:24:40+0800
     * @param    [array]          $params [输入参数]
     */
    public function DataList($params = [])
    {
        // 初始化
        $this->Init($params);

        // 获取包列表
        $result = [];
        if(is_dir($this->new_path))
        {
            if($dh = opendir($this->new_path))
            {
                while(($temp_file = readdir($dh)) !== false)
                {
                    if($temp_file != '.' && $temp_file != '..')
                    {
                        $file_path = $this->new_path.DS.$temp_file;
                        $url = __MY_PUBLIC_URL__.'appmini'.DS.'new'.DS.$this->application_name.DS.$temp_file;
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
    public function Created($params = [])
    {
        // 初始化
        $this->Init($params);

        // 配置内容
        if(empty($params['app_mini_title']) || empty($params['app_mini_describe']))
        {
            return DataReturn('配置信息不能为空', -1);
        }

        // 源码包目录是否存在
        if(!is_dir($this->new_root))
        {
            return DataReturn('源码包目录不存在', -1);
        }

        // 源码包目录是否有权限
        if(!is_writable($this->new_root))
        {
            return DataReturn('源码包目录没有权限', -1);
        }

        // 目录不存在则创建
        \base\FileUtil::CreateDir($this->new_path);

        // 复制包目录
        $new_dir = $this->new_path.DS.date('YmdHis');
        if(\base\FileUtil::CopyDir($this->old_path, $new_dir) != true)
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
        $status = file_put_contents($new_dir.DS.'app.js', str_replace(['{{request_url}}', '{{application_title}}', '{{application_describe}}'], [__MY_URL__, $params['app_mini_title'], $params['app_mini_describe']], file_get_contents($new_dir.DS.'app.js')));
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
    public function Delete($params = [])
    {
        // 参数
        if(empty($params['id']))
        {
            return DataReturn('操作id不能为空', -10);
        }

        // 初始化
        $this->Init($params);

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
        $path = $this->new_path.DS.htmlentities(str_replace(array('.', '/', '\\', ':'), '', strip_tags($name))).$suffix;

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