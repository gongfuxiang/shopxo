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
use app\service\PackageInstallService;
use app\service\AppTabbarService;
use app\service\DiyService;
use app\service\DiyApiService;

/**
 * DiyApi接口
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class DiyApi extends Base
{
    /**
     * 公共初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function Init()
    {
        return ApiService::ApiDataReturn(DiyApiService::Init($this->data_request));
    }

    /**
     * Diy装修详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiyDetail()
    {
        $params = $this->data_request;
        $params['control'] = 'diy';
        $params['action'] = 'detail';
        return ApiService::ApiDataReturn(DataReturn('success', 0, FormModuleData($params)));
    }

    /**
     * Diy装修保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiySave()
    {
        return ApiService::ApiDataReturn(DiyService::DiySave($this->data_request));
    }

    /**
     * Diy装修导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiyUpload()
    {
        return ApiService::ApiDataReturn(DiyService::DiyUpload($this->data_request));
    }

    /**
     * Diy装修导出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function DiyDownload()
    {
        $ret = DiyService::DiyDownload($this->data_request);
        if($ret['code'] != 0)
        {
            return MyView('public/tips_error', ['msg'=>$ret['msg']]);
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * diy模板安装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function DiyInstall()
    {
        $params = $this->data_request;
        $params['type'] = 'diy';
        return ApiService::ApiDataReturn(PackageInstallService::Install($params));
    }

    /**
     * diy模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function DiyMarket()
    {
        return ApiService::ApiDataReturn(DiyService::DiyMarket($this->data_request));
    }

    /**
     * 底部菜单保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AppTabbarSave()
    {
        $business = empty($this->data_request['business']) ? 'home' : $this->data_request['business'];
        return ApiService::ApiDataReturn(AppTabbarService::AppTabbarConfigSave($business, $this->data_request));
    }

    /**
     * 底部菜单数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-18
     * @desc    description
     */
    public function AppTabbarData()
    {
        $business = empty($this->data_request['business']) ? 'home' : $this->data_request['business'];
        return ApiService::ApiDataReturn(DataReturn('success', 0, AppTabbarService::AppTabbarConfigData($business)));
    }
}
?>