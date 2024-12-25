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
use app\service\StoreService;

/**
 * 应用商店
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @@date    2019-06-13
 */
class Store extends Base
{
    /**
     * 应用商店首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-13
     * @desc    description
     */
    public function Index()
    {
        MyViewAssign([
            'common_plugins_goods_type_list'  => MyConst('common_plugins_goods_type_list'),
            'store_url'                       => StoreService::StoreUrl(),
        ]);
        return MyView();
    }

    /**
     * 应用市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Market()
    {
        $ret = StoreService::PackageDataList($this->data_request);
        if($ret['code'] == 0 && isset($ret['data']['data_list']))
        {
            $ret['data']['data_list'] = MyView('public/market/list', ['data_list'=>$ret['data']['data_list']]);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>