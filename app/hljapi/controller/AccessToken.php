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
namespace app\hljapi\controller;

use app\service\ApiService;
use app\service\PluginsService;

/**
 * token授权 - 黑龙江政采
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AccessToken extends Common
{    
    /**
     * 预占订单
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-02
     * @desc    description
     */
    public function Index()
    {
        $ret = PluginsService::PluginsControlCall('govpurheilongjiang', 'base', 'accesstoken', 'api', $this->GetClassVars());
        return ApiService::ApiDataReturn($ret['data']);
    }
}
?>