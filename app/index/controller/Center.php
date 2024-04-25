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

use app\index\controller\Common;

/**
 * 用户中心
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Center extends Common
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

        // 用户控制器
        if($this->controller_name == 'user')
        {
            // 仅用户中心需要验证登录
            if(in_array($this->action_name, ['index']))
            {
                // 是否登录
                IsUserLogin();
            }
        } else {
            // 支付同步返回不验证登录状态
            if(!in_array($this->action_name, ['respond']))
            {
                // 是否登录
                IsUserLogin();
            }
        }
    }
}
?>