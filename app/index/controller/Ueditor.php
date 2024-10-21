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
namespace app\index\controller;

use app\service\SeoService;
use app\service\ApiService;
use app\service\UeditorService;

/**
 * 百度编辑器控制器入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Ueditor extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

    /**
     * 运行入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-08-06
     * @desc    description
     */
    public function Index()
    {
        return ApiService::ApiDataReturn(UeditorService::Run(input()));
    }

    /**
     * 扫码上传
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-20
     * @desc    description
     */
    public function ScanUpload()
    {
        $cid = empty($this->data_request['cid']) ? '' : $this->data_request['cid'];
        $key = empty($this->data_request['key']) ? '' : $this->data_request['key'];
        $type = empty($this->data_request['type']) ? 'uploadimage' : $this->data_request['type'];
        $extensions = [
            'uploadimage' => implode(',', array_map(function($item){return substr($item, 1);}, MyConfig('ueditor.imageAllowFiles'))),
            'uploadfile'  => implode(',', array_map(function($item){return substr($item, 1);}, MyConfig('ueditor.fileAllowFiles'))),
            'uploadvideo' => implode(',', array_map(function($item){return substr($item, 1);}, MyConfig('ueditor.videoAllowFiles'))),
        ];
        MyViewAssign([
            'is_header'            => 0,
            'is_footer'            => 0,
            'is_load_webuploader'  => 1,
            'category_id'          => $cid,
            'upload_key'           => $key,
            'upload_action'        => $type,
            'scan_key_exist'       => UeditorService::ScanKeyIsExist($this->data_request),
            'extensions'           => empty($extensions[$type]) ? '' : $extensions[$type],
            'home_seo_site_title'  => SeoService::BrowserSeoTitle(MyLang('ueditor.base_nav_title'), 2),
        ]);
        return MyView();
    }
}
?>