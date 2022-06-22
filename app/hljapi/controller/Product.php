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
 * 商品 - 黑龙江政采
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Product extends Common
{    
    /**
     * 商品价格查询
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-02
     * @desc    description
     */
    public function getSellPrice()
    {
        $ret = PluginsService::PluginsControlCall('govpurheilongjiang', 'goods', 'price', 'api', $this->GetClassVars());
        return ApiService::ApiDataReturn($ret['data']);
    }

    /**
     * 商品库存查询
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-02
     * @desc    description
     */
    public function getStock()
    {
        $ret = PluginsService::PluginsControlCall('govpurheilongjiang', 'goods', 'inventory', 'api', $this->GetClassVars());
        return ApiService::ApiDataReturn($ret['data']);
    }

    /**
     * 商品可售查询
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-02
     * @desc    description
     */
    public function saleCheck()
    {
        $ret = PluginsService::PluginsControlCall('govpurheilongjiang', 'goods', 'issales', 'api', $this->GetClassVars());
        return ApiService::ApiDataReturn($ret['data']);
    }

    /**
     * 商品上下架通知
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-02
     * @desc    description
     */
    public function shelfUpdateNotice()
    {
        $ret = PluginsService::PluginsControlCall('govpurheilongjiang', 'goods', 'shelves', 'api', $this->GetClassVars());
        return ApiService::ApiDataReturn($ret['data']);
    }

    /**
     * 商品是否支持货到付款
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-02
     * @desc    description
     */
    public function checkIsCod()
    {
        $ret = PluginsService::PluginsControlCall('govpurheilongjiang', 'goods', 'iscod', 'api', $this->GetClassVars());
        return ApiService::ApiDataReturn($ret['data']);
    }
}
?>