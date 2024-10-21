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
use app\service\PluginsAdminService;
use app\service\ResourcesService;
use app\service\PluginsService;
use app\service\PackageUpgradeService;
use app\service\PluginsCategoryService;

/**
 * 应用管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Pluginsadmin extends Base
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
            // 应用商店地址
            'store_url' => StoreService::StoreUrl(),
        ];

        // 页面类型
        $view_type = empty($this->data_request['view_type']) ? 'index' : $this->data_request['view_type'];
        if($view_type == 'index')
        {
            // 插件列表
            $ret = PluginsAdminService::PluginsList(['is_power'=>true]);
            $assign['data_list'] = $ret['data'];

            // 插件更新信息
            $upgrade = PluginsService::PluginsUpgradeInfo($ret['data']);
            $assign['upgrade_info'] = $upgrade['data'];

            // 插件分类
            $categosy = PluginsCategoryService::PluginsCategoryList();
            $assign['plugins_categosy_list'] = $categosy['data'];
        }
        MyViewAssign($assign);
        return MyView($view_type);
    }

    /**
     * 添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-12T21:30:26+0800
     */
    public function SaveInfo()
    {
        // 参数
        $params = $this->data_request;

        // 获取数据
        $data = [];
        if(!empty($params['id']))
        {
            // 获取数据
            $ret = PluginsAdminService::PluginsList();
            if(!empty($ret['data']['db_data']) || !empty($ret['data']['dir_data']))
            {
                $res = array_column(array_merge($ret['data']['db_data'], $ret['data']['dir_data']), null, 'plugins');
                if(isset($res[$params['id']]))
                {
                    $data = $res[$params['id']];
                    $params['plugins'] = $params['id'];
                }
            }
        }

        // 模板数据
        $assign = [
            'data'      => $data,
            'params'    => $params,
        ];

        // 名称校验
        if(!empty($params['plugins']))
        {
            $ret = PluginsAdminService::PluginsVerification($params, $params['plugins']);
            if($ret['code'] != 0)
            {
                $assign['verification_msg'] = $ret['msg'];
                MyViewAssign($assign);
                return MyView('firststep');
            }
        }

        // 标记为空或等于view 并且 编辑数据为空则走第一步
        if(empty($params['plugins']) && empty($data['data'][0]))
        {
            MyViewAssign($assign);
            return MyView('firststep');
        } else {
            // 编辑器文件存放地址
            $assign['editor_path_type'] = ResourcesService::EditorPathTypeValue('plugins_'.$params['plugins']);

            // 唯一标记
            $assign['plugins'] = $params['plugins'];

            //数据赋值
            MyViewAssign($assign);
            return MyView('saveinfo');
        }
    }

    /**
     * 上传到商店页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-12T21:30:26+0800
     */
    public function StoreUploadInfo()
    {
        // 参数
        $params = $this->data_request;

        // 获取数据
        $data = [];
        if(!empty($params['id']))
        {
            // 获取数据
            $ret = PluginsAdminService::PluginsList();
            if(!empty($ret['data']['db_data']) || !empty($ret['data']['dir_data']))
            {
                $res = array_column(array_merge($ret['data']['db_data'], $ret['data']['dir_data']), null, 'plugins');
                if(isset($res[$params['id']]))
                {
                    $data = $res[$params['id']];
                }
            }
        }
        MyViewAssign('data', $data);
        return MyView();
    }

    /**
     * 添加/编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-12T21:30:26+0800
     */
    public function Save()
    {
        return ApiService::ApiDataReturn(PluginsAdminService::PluginsSave($this->data_request));
    }

    /**
     * 删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-12T21:30:26+0800
     */
    public function Delete()
    {
        return ApiService::ApiDataReturn(PluginsAdminService::PluginsDelete($this->data_request));
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        return ApiService::ApiDataReturn(PluginsAdminService::PluginsStatusUpdate($this->data_request));
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
        return ApiService::ApiDataReturn(PluginsAdminService::PluginsUpload($this->data_request));
    }

    /**
     * 应用打包
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-22
     * @desc    description
     */
    public function Download()
    {
        $ret = PluginsAdminService::PluginsDownload($this->data_request);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        }
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
        return ApiService::ApiDataReturn(PluginsAdminService::PluginsStoreUpload($this->data_request));
    }

    /**
     * 安装
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Install()
    {
        return ApiService::ApiDataReturn(PluginsAdminService::PluginsInstall($this->data_request));
    }

    /**
     * 卸载
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-17
     * @desc    description
     */
    public function Uninstall()
    {
        return ApiService::ApiDataReturn(PluginsAdminService::PluginsUninstall($this->data_request));
    }

    /**
     * 设置保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-05
     * @desc    description
     */
    public function SetupSave()
    {
        return ApiService::ApiDataReturn(PluginsAdminService::SetupSave($this->data_request));
    }

    /**
     * 插件更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-05
     * @desc    description
     */
    public function Upgrade()
    {
        return ApiService::ApiDataReturn(PackageUpgradeService::Run($this->data_request));
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
        $ret = PluginsAdminService::PluginsAdminMarket($this->data_request);
        if($ret['code'] == 0 && isset($ret['data']['data_list']))
        {
            $ret['data']['data_list'] = MyView('public/market/list', ['data_list'=>$ret['data']['data_list']]);
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>