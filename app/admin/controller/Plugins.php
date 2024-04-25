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
use app\service\SystemService;
use app\service\PluginsService;
use app\service\ResourcesService;

/**
 * 应用调用入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Plugins extends Base
{    
    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 参数
        $params = $this->GetClassVars();

        // 请求参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginsname',
                'error_msg'         => MyLang('plugins_name_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginscontrol',
                'error_msg'         => MyLang('plugins_control_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginsaction',
                'error_msg'         => MyLang('plugins_action_tips'),
            ],
        ];
        $ret = ParamsChecked($params['data_request'], $p);
        if($ret !== true)
        {
            if(IS_AJAX)
            {
                return ApiService::ApiDataReturn(DataReturn($ret, -5000));
            } else {
                MyViewAssign('msg', $ret);
                return MyView('public/tips_error');
            }
        }

        // 插件权限校验
        $power_plugins = MyCache(SystemService::CacheKey('shopxo.cache_admin_power_plugins_key').$this->admin['id']);
        if(empty($power_plugins) || !array_key_exists($params['data_request']['pluginsname'], $power_plugins))
        {
            $msg = MyLang('plugins_use_no_power_tips');
            if(IS_AJAX)
            {
                return ApiService::ApiDataReturn(DataReturn($msg, -5000));
            } else {
                MyViewAssign('msg', $msg);
                return MyView('public/tips_error');
            }
        }

        // 应用名称/控制器/方法
        $pluginsname = $params['data_request']['pluginsname'];
        $pluginscontrol = strtolower($params['data_request']['pluginscontrol']);
        $pluginsaction = strtolower($params['data_request']['pluginsaction']);

        // 编辑器文件存放地址定义
        MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue('plugins_'.$pluginsname));

        // 调用
        $ret = PluginsService::PluginsControlCall($pluginsname, $pluginscontrol, $pluginsaction, 'admin', $params);

        // ajax 返回的都是数组、使用统一api返回处理
        if(IS_AJAX)
        {
            return ApiService::ApiDataReturn(($ret['code'] == 0) ? $ret['data'] : $ret);
        }

        // 正确则返回视图内容
        if($ret['code'] == 0)
        {
            return $ret['data'];
        }

        // 是否未绑定商店账号
        if($ret['code'] == -300)
        {
            return $this->NotBindStoreAccountTips($ret['msg']);
        }

        // 调用失败
        MyViewAssign('msg', $ret['msg']);
        return MyView('public/tips_error');
    }

    /**
     * 获取类属性数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-07
     * @desc    description
     */
    public function GetClassVars()
    {
        $data = [];
        $vers = get_class_vars(get_class($this));
        foreach($vers as $k=>$v)
        {
            if(property_exists($this, $k))
            {
                $data[$k] = $this->$k;
            }
        }
        return $data;
    }
}
?>