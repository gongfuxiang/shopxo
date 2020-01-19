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
namespace app\admin\controller;

use app\service\StoreService;
use app\service\PluginsAdminService;

/**
 * 应用管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Pluginsadmin extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 登录校验
        $this->IsLogin();

        // 权限校验
        $this->IsPower();

        // 小导航
        $this->view_type = input('view_type', 'home');
    }

    /**
     * [Index 配置列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 导航参数
        $this->assign('view_type', $this->view_type);

        // 参数
        $params = input();

        // 应用商店地址
        $this->assign('store_url', StoreService::StoreUrl());

        // 页面类型
        if($this->view_type == 'home')
        {
            $data = PluginsAdminService::PluginsList();
            $this->assign('data_list', $data['data']);
            return $this->fetch();
        } else {
            return $this->fetch('upload');
        }
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
        $params = input();

        // 参数
        $this->assign('params', $params);

        // 获取数据
        $data = [];
        if(!empty($params['id']))
        {
            // 获取数据
            $ret = PluginsAdminService::PluginsList();
            if(isset($ret['data'][$params['id']]))
            {
                $data = $ret['data'][$params['id']];
                $params['plugins'] = $params['id'];
            }
        }
        $this->assign('data', $data);

        // 名称校验
        if(!empty($params['plugins']))
        {
            $ret = PluginsAdminService::PluginsVerification($params, $params['plugins']);
            if($ret['code'] != 0)
            {
                $this->assign('verification_msg', $ret['msg']);
                return $this->fetch('first_step');
            }
        }

        // 标记为空或等于view 并且 编辑数据为空则走第一步
        if(empty($params['plugins']) && empty($data['data'][0]))
        {
            return $this->fetch('first_step');
        } else {
            // 编辑器文件存放地址
            $this->assign('editor_path_type', 'plugins_'.$params['plugins']);

            // 唯一标记
            $this->assign('plugins', $params['plugins']);
            return $this->fetch('save_info');
        }
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return PluginsAdminService::PluginsSave(input('post.'));
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return PluginsAdminService::PluginsDelete(input('post.'));
    }

    /**
     * [StatusUpdate 状态更新]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return PluginsAdminService::PluginsStatusUpdate(input('post.'));
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
        // 是否ajax
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        return PluginsAdminService::PluginsUpload(input());
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
        // 开始处理
        $ret = PluginsAdminService::PluginsDownload(input());
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
        } else {
            return $ret;
        }
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始操作
        return PluginsAdminService::PluginsInstall(input());
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始操作
        return PluginsAdminService::PluginsUninstall(input());
    }
}
?>