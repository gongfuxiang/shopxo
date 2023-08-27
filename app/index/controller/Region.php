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
use app\service\RegionService;

/**
 * 地区
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Region extends Common
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
     * 获取地区节点
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     */
    public function Index()
    {
        $pid = empty($this->data_request['pid']) ? 0 : intval($this->data_request['pid']);
        $params = [
            'where' => [
                ['pid', '=', $pid],
            ],
        ];
        return ApiService::ApiDataReturn(DataReturn('success', 0, RegionService::RegionNode($params)));
    }

    /**
     * 获取地区所有数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-29
     * @desc    description
     */
    public function All()
    {
        return ApiService::ApiDataReturn(RegionService::RegionAll());
    }

    /**
     * 获取地区编号数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-21
     * @desc    description
     */
    public function CodeData()
    {
        return ApiService::ApiDataReturn(RegionService::RegionCodeData($this->data_request));
    }
}
?>