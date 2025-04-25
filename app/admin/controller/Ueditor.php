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
namespace app\admin\controller;

use app\service\UeditorService;
use app\service\ApiService;

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

        // 登录校验
        $this->IsLogin();
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
        $action = empty($this->data_request['action']) ? 'config' : $this->data_request['action'];
        switch($action)
        {
            // 删除操作
            case 'deletefile' :
                $this->IsPower('attachment', 'delete');
                break;

            // 验证上传权限（初始化、列表、上传、分类）
            default :
                // 排除配置获取
                if(!in_array($action, ['config']))
                {
                    $this->IsPower('attachment', 'upload');
                }
        }
        return ApiService::ApiDataReturn(UeditorService::Run($this->data_request));
    }
}
?>