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
namespace app\install\controller;

use app\BaseController;
use app\service\SystemService;

/**
 * 安装程序-公共
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-11-30
 * @desc    description
 */
class Common extends BaseController
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
        // 当前方法
        MyViewAssign('action', RequestAction());

        // 系统类型
        MyViewAssign('system_type', SystemService::SystemTypeValue());

        // 系统环境参数最大数
        MyViewAssign('env_max_input_vars_count', SystemService::EnvMaxInputVarsCount());
    }

    /**
     * 空方法响应
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     * @param   [string]         $method [方法名称]
     * @param   [array]          $args   [参数]
     */
    public function __call($method, $args)
    {
        if(IS_AJAX)
        {
            return DataReturn($method.' 非法访问', -1000);
        } else {
            MyViewAssign('msg', $method.' 非法访问');
            return MyView('public/error');
        }
    }
}
?>