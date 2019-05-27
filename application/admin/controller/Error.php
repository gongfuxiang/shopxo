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
namespace app\admin\controller;

use think\Request;

/**
 * 空控制器响应
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-11-30
 * @desc    description
 */
class Error extends Common
{
    /**
     * 空控制器响应
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     * @param   Request         $request [参数]
     */
    public function Index(Request $request)
    {
        if(IS_AJAX)
        {
            exit(json_encode(DataReturn($request->controller().' 控制器不存在', -1000)));
        } else {
            $this->assign('msg', $request->controller().' 控制器不存在');
            return $this->fetch('public/tips_error');
        }
    }
}
?>