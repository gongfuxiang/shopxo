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

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\ConfigService;
use app\service\ResourcesService;

/**
 * 协议管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Agreement extends Base
{
    /**
     * 配置列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-16
     * @desc    description
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 配置信息
            'data'              => ConfigService::ConfigList(),
            // 管理导航
            'nav_data'          => MyLang('agreement.base_nav_list'),
            // 编辑器文件存放地址
            'editor_path_type'  => ResourcesService::EditorPathTypeValue('agreement'),
        ];

        // 导航/视图
        $nav_type = empty($this->data_request['nav_type']) ? 'register' : $this->data_request['nav_type'];
        $assign['nav_type'] = $nav_type;

        // 数据赋值
        MyViewAssign($assign);
        return MyView($nav_type);
    }

    /**
     * 配置数据保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-16
     * @desc    description
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(ConfigService::ConfigSave($_POST));
    }
}
?>