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
namespace app\api\controller;

use think\Db;
use app\service\ResourcesService;

/**
 * 开发测试
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Devtest extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * 附件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        if(input('pwd') != 'shopxo520')
        {
            die('非法访问');
        }

        $count = 0;
        $success = 0;
        $error = 0;

        $path_all = [
            'video' => __MY_ROOT_PUBLIC__.'static/upload/video/',
            'file' => __MY_ROOT_PUBLIC__.'static/upload/file/',
            'image' => __MY_ROOT_PUBLIC__.'static/upload/images/',
        ];
        foreach($path_all as $type=>$path)
        {
            $path = GetDocumentRoot() . (substr($path, 0, 1) == "/" ? "":"/") . $path;

            // 从磁盘获取文件
            $files = $this->GetDirFilesList($path, $type, $path);
            if(!empty($files))
            {
                $count += count($files);
                foreach($files as $v)
                {
                    $temp = Db::name('Attachment')->where(['title'=>$v['title'], 'hash'=>$v['hash']])->find();
                    if(empty($temp))
                    {
                        $ret = ResourcesService::AttachmentAdd($v);
                        if($ret['code'] == 0)
                        {
                            $success++;
                        } else {
                            $error++;
                        }
                    } else {
                        $success++;
                    }
                }
            }
        }
        echo '总数['.$count.'], 成功['.$success.'], 失败['.$error.']';
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-17T23:24:59+0800
     * @param    [string]        $path          [路径地址]
     * @param    [string]        $type   [允许的文件]
     * @param    [array]         &$files        [数据]
     * @return   [array]                        [数据]
     */
    private function GetDirFilesList($path, $type, $path_old, &$files = array())
    {
        if(!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        $document_root = GetDocumentRoot();
        while(false !== ($file = readdir($handle)))
        {
            if($file != 'index.html' && $file != '.' && $file != '..' && substr($file, 0, 1) != '.')
            {
                $path2 = $path . $file;
                if(is_dir($path2))
                {
                    $this->GetDirFilesList($path2, $type, $path_old, $files);
                } else {
                    $url = ResourcesService::AttachmentPathHandle(substr($path2, strlen($document_root)));
                    $title = substr($url, strripos($url, '/')+1);
                    $root_path = ROOT.'public'.$url;
                    $path_type = str_replace($path_old, '', $root_path);
                    $files[] = array(
                        'url'       => $url,
                        'original'  => $title,
                        'title'     => $title,
                        'type'      => $type,
                        'path_type' => substr($path_type, 0, stripos($path_type, '/')),
                        'size'      => file_exists($root_path) ? filesize($root_path) : 0,
                        'hash'      => file_exists($root_path) ? hash_file('sha256', $root_path, false) : '',
                        'ext'       => substr($title, strripos($title, '.')),
                    );
                }
            }
        }
        return $files;
    }
}
?>