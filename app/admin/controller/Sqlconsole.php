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
use app\service\SqlConsoleService;

/**
 * sql控制台
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Sqlconsole extends Base
{
    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-26T19:13:29+0800
     */
    public function Index()
    {
        return MyView();
    }

    /**
     * sql执行
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Implement()
    {
        // 是否开启开发者模式
        if(MyConfig('shopxo.is_develop') !== true)
        {
            $ret = DataReturn(MyLang('not_open_developer_mode_tips'), -1);
        } else {
            $ret = SqlConsoleService::Implement($this->data_request);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>