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

use app\service\ApiService;
use app\module\LayoutModule;

/**
 * 布局自动化
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Layout extends Common
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
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-16
     * @desc    description
     */
    public function GoodsSearch()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        $ret = LayoutModule::GoodsSearchList($params);
        if($ret['code'] == 0)
        {
            MyViewAssign('data', $ret['data']['data']);
            $ret['data']['data'] = MyView('../../../module/view/layout/public/common/goodssearch');
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 商品数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-16
     * @desc    description
     */
    public function GoodsData()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(LayoutModule::GoodsDataList($params));
    }
}
?>