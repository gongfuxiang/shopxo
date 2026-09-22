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
namespace app\api\controller\admin;

use app\service\AdminService;

/**
 * 后台 API 首页 / 初始化
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2026-09-18
 * @desc    对齐多商户 seller/Index::Init，免登录返回登录相关配置
 */
class Index extends Common
{
    /**
     * 初始化配置
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-18
     * @param   [array]           $params [输入参数]
     * @desc    api.php?s=admin/index&business_control=index&business_action=init
     */
    public function Init($params = [])
    {
        return DataReturn('success', 0, AdminService::ApiInitConfigData());
    }
}
?>
