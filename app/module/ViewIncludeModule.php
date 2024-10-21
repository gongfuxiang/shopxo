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
namespace app\module;

/**
 * 视图模块引入
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-25
 * @desc    description
 */
class ViewIncludeModule
{
    /**
     * 运行入口
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-25
     * @desc    description
     * @param   [string]          $template [模板地址]
     * @param   [mixed]           $data     [请求数据]
     * @param   [mixed]           $params   [额外参数]
     * @return  [string]                    [模板内容]
     */
    public function Run($template, $data = [], $params = [])
    {
        return MyView($template, ['module_data'=>$data, 'module_params'=>$params]);
    }
}
?>