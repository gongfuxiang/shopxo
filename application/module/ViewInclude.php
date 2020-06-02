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
namespace app\module;

use think\Controller;

/**
 * 视图模块引入
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-25
 * @desc    description
 */
class ViewInclude extends Controller
{
    /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-25
     * @desc    description
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
     * @date    2020-05-25
     * @desc    description
     * @param   [string]          $template [模板地址]
     * @param   [mixed]           $data     [请求数据]
     * @return  [string]                    [模板内容]
     */
    public function Run($template, $data = [])
    {
        $this->assign('module_data', $data);
        return $this->fetch($template);
    }
}
?>