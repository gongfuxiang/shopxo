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
use app\service\ThemeAdminService;
use app\service\StoreService;

/**
 * 主题管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ThemeAdmin extends Base
{
    /**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 应用商店
            'store_theme_url'  => StoreService::StoreThemeUrl(),
        ];
        // 页面类型
        $view_type = empty($this->data_request['view_type']) ? 'index' : $this->data_request['view_type'];
        if($view_type == 'index')
        {
            // 获取主题列表
            $data_list = ThemeAdminService::ThemeAdminList();

            // 更新信息
            $upgrade = ThemeAdminService::ThemeAdminUpgradeInfo($data_list);

            // 模板数据
            $assign = array_merge($assign, [
                // 基础导航
                'base_nav'         => MyLang('theme.base_nav_list'),
                // 默认主题
                'theme'            => ThemeAdminService::DefaultTheme(),
                // 主题列表
                'data_list'        => $data_list,
                // 主题数据管理url
                'admin_url_data'   => ThemeAdminService::ThemeAdminDataUrl(),
                // 插件更新信息
                'upgrade_info'     => $upgrade['data'],
                // 应用商店
                'store_theme_url'  => StoreService::StoreThemeUrl(),
            ]);
        }
        MyViewAssign($assign);
        return MyView($view_type);
    }

    /**
     * 上传页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function StoreUploadInfo()
    {
        MyViewAssign('data', ThemeAdminService::ThemeAdminData($this->data_request));
        return MyView();
    }

    /**
     * 打包下载
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     */
    public function Download()
    {
        $ret = ThemeAdminService::ThemeAdminDownload($this->data_request);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        }
    }

    /**
     * 切换保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-19T00:58:47+0800
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(ThemeAdminService::ThemeAdminSwitch($this->data_request));
    }

    /**
     * 删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-09T21:13:47+0800
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(ThemeAdminService::ThemeAdminDelete($this->data_request));
    }

    /**
     * 上传到商店
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function StoreUpload()
    {
        return ApiService::ApiDataReturn(ThemeAdminService::ThemeAdminStoreUpload($this->data_request));
    }

    /**
     * 上传安装
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-05-10T16:27:09+0800
     */
    public function Upload()
    {
        return ApiService::ApiDataReturn(ThemeAdminService::ThemeAdminUpload($this->data_request));
    }

    /**
     * 模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     */
    public function Market()
    {
        $ret = ThemeAdminService::ThemeAdminMarket($this->data_request);
        if($ret['code'] == 0 && isset($ret['data']['data_list']))
        {
            $ret['data']['data_list'] = MyView('public/market/list', ['data_list'=>$ret['data']['data_list']]);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>