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
     * 附件初始化 1.6升级运行
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

        $path_all = [
            'video' => __MY_ROOT_PUBLIC__.'static/upload/video/',
            'file' => __MY_ROOT_PUBLIC__.'static/upload/file/',
            'image' => __MY_ROOT_PUBLIC__.'static/upload/images/',
        ];
        foreach($path_all as $type=>$path)
        {
            $path = GetDocumentRoot() . (substr($path, 0, 1) == "/" ? "":"/") . $path;
            $handle = opendir($path);
            while(false !== ($file = readdir($handle)))
            {
                if($file != 'index.html' && $file != '.' && $file != '..' && substr($file, 0, 1) != '.')
                {
                    $ret = ResourcesService::AttachmentDiskFilesToDb($file);
                    if(isset($ret['msg']))
                    {
                        echo $ret['msg'];
                    }
                }
            }
        }
    }
}
?>