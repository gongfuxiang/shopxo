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
namespace app\service;

use think\facade\Db;
use app\service\SystemService;
use app\service\ResourcesService;
use app\service\PluginsAdminService;
use app\service\StoreService;

/**
 * 应用服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PluginsService
{
    /**
     * 根据应用标记获取数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [string]          $plugins          [应用标记]
     * @param   [array]           $attachment_field [附件字段]
     * @param   [boolean]         $is_cache         [是否缓存读取, 默认true]
     */
    public static function PluginsData($plugins, $attachment_field = [], $is_cache = true)
    {
        static $static_all_plugins_data = [];
        if(array_key_exists($plugins, $static_all_plugins_data))
        {
            $data = $static_all_plugins_data[$plugins];
        } else {
            // 从缓存获取数据、数据不存在则从数据库读取
            $data = ($is_cache === true) ? self::PluginsCacheData($plugins) : [];
            if($data === null || !$is_cache || MyEnv('app_debug') || MyC('common_data_is_use_cache') != 1)
            {
                // 获取数据
                $ret = self::PluginsField($plugins, 'data');
                if(!empty($ret['data']))
                {
                    // 获取插件基础字段定义
                    $base_ser = self::PluginsBaseSerData($plugins);

                    // 数据处理、未指定附件字段则使用系统获取的附件字段
                    if(empty($attachment_field) && !empty($base_ser['attachment_field']))
                    {
                        $attachment_field = $base_ser['attachment_field'];
                    }
                    $data = self::PluginsDataHandle($ret['data'], $attachment_field);
                } else {
                    $data = [];
                }

                // 存储缓存
                self::PluginsCacheStorage($plugins, $data);
            }
            // 加入静态记录
            $static_all_plugins_data[$plugins] = $data;
        }
        return DataReturn(MyLang('handle_success'), 0, $data);
    }

    /**
     * 获取插件字段
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-05-08
     * @desc    description
     * @param   [string]          $plugins [插件名称]
     */
    public static function PluginsBaseSerData($plugins)
    {
        $result = [
            'private_field'     => [],
            'attachment_field'  => [],
            'ser'               => null,
        ];
        $ser = '\app\plugins\\'.$plugins.'\service\BaseService';
        if(class_exists($ser))
        {
            // 附件属性
            $result['attachment_field'] = property_exists($ser, 'base_config_attachment_field') ? $ser::$base_config_attachment_field : [];

            // 私有字段
            $result['private_field'] = property_exists($ser, 'base_config_private_field') ? $ser::$base_config_private_field : [];

            // 类对象
            $result['ser'] = $ser;
        }
        return $result;
    }

    /**
     * 插件配置数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-06
     * @desc    description
     * @param   [array|json]      $data             [应用配置数据]
     * @param   [array]           $attachment_field [附件字段]
     */
    public static function PluginsDataHandle($data, $attachment_field = [])
    {
        // 有数据并且非数组则解析json
        if(!empty($data) && !is_array($data))
        {
            $data = json_decode($data, true);
        }

        // 处理配置数据
        if(!empty($data) && is_array($data))
        {
            // 是否有自定义附件需要处理
            if(!empty($attachment_field) && is_array($attachment_field))
            {
                foreach($attachment_field as $field)
                {
                    if(isset($data[$field]))
                    {
                        $data[$field] = ResourcesService::AttachmentPathViewHandle($data[$field]);
                    }
                }
            } else {
                // 所有附件后缀名称
                $attachment_ext = MyConfig('ueditor.fileManagerAllowFiles');

                // 未自定义附件则自动根据内容判断处理附件，建议自定义附件字段名称处理更高效
                if(!empty($attachment_ext) && is_array($attachment_ext))
                {
                    foreach($data as $k=>$v)
                    {
                        if(!empty($v) && !is_array($v) && !is_object($v))
                        {
                            $ext = strrchr(substr($v, -6), '.');
                            if($ext !== false)
                            {
                                if(in_array($ext, $attachment_ext))
                                {
                                    $data[$k] = ResourcesService::AttachmentPathViewHandle($v);
                                }
                            }
                        }
                    }
                }
            }
        }
        return empty($data) ? [] : $data;
    }

    /**
     * 应用数据保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [string]          $plugins          [应用标记]
     * @param   [array]           $attachment_field [附件字段]
     */
    public static function PluginsDataSave($params = [], $attachment_field = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'plugins',
                'error_msg'         => MyLang('common_service.plugins.plugins_identification_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'data',
                'error_msg'         => MyLang('common_service.plugins.data_params_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 附件处理
        $attachment = ResourcesService::AttachmentParams($params['data'], $attachment_field);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }
        if(!empty($attachment['data']))
        {
            foreach($attachment['data'] as $field=>$value)
            {
                $params['data'][$field] = $value;
            }
        }

        // 移除多余的字段
        unset($params['data']['pluginsname'], $params['data']['pluginscontrol'], $params['data']['pluginsaction']);

        // 数据更新
        if(Db::name('Plugins')->where(['plugins'=>$params['plugins']])->update(['data'=>json_encode($params['data']), 'upd_time'=>time()]))
        {
            // 删除缓存
            self::PluginsCacheDelete($params['plugins']);
            
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 应用缓存c存储
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-23
     * @desc    description
     * @param   [string]          $plugins [应用标记]
     * @param   [mixed]           $data    [应用数据]
     */
    public static function PluginsCacheStorage($plugins, $data)
    {
        return MyCache(SystemService::CacheKey('shopxo.cache_plugins_data_key').$plugins, $data);
    }

    /**
     * 应用缓存获取
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-23
     * @desc    description
     * @param   [string]          $plugins [应用标记]
     */
    public static function PluginsCacheData($plugins)
    {
        return MyCache(SystemService::CacheKey('shopxo.cache_plugins_data_key').$plugins);
    }

    /**
     * 应用缓存删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-12-23
     * @desc    description
     * @param   [string]          $plugins [应用标记]
     */
    public static function PluginsCacheDelete($plugins)
    {
        MyCache(SystemService::CacheKey('shopxo.cache_plugins_data_key').$plugins, null);
    }

    /**
     * 根据应用标记获取指定字段数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [string]          $plugins        [应用标记]
     * @param   [string]          $field          [字段名称]
     * @return  [mixed]                           [不存在返回null, 则原始数据]
     */
    public static function PluginsField($plugins, $field)
    {
        static $plugins_field_data = null;
        if($plugins_field_data === null)
        {
            $plugins_field_data = Db::name('Plugins')->column('*', 'plugins');
        }
        $value = '';
        if(!empty($plugins_field_data) && array_key_exists($plugins, $plugins_field_data) && array_key_exists($field, $plugins_field_data[$plugins]))
        {
            $value = $plugins_field_data[$plugins][$field];
        }
        return DataReturn('success', 0, $value);
    }

    /**
     * 应用状态
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-10-17
     * @desc    description
     * @param   [string]          $plugins        [应用标记]
     */
    public static function PluginsStatus($plugins)
    {
        $ret = self::PluginsField($plugins, 'is_enable');
        return $ret['data'];
    }

    /**
     * 应用校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2020-01-02
     * @param   [string]          $plugins        [应用标记]
     */
    public static function PluginsCheck($plugins)
    {
        $ret = self::PluginsStatus($plugins);
        if($ret === null)
        {
            // 不存在的插件则进入首页
            $config = PluginsAdminService::GetPluginsConfig($plugins);
            if(empty($config))
            {
                if(IS_AJAX)
                {
                    return DataReturn(MyLang('common_service.plugins.plugins_invalid_tips').'['.$plugins.']', -10);
                }
                MyRedirect(SystemService::DomainUrl(), true);
            }
            return DataReturn(MyLang('common_service.plugins.plugins_not_install_tips').'['.$plugins.']', -10);
        }
        if($ret != 1)
        {
            return DataReturn(MyLang('common_service.plugins.plugins_not_enable_tips').'['.$plugins.']', -11);
        }
        return DataReturn(MyLang('check_success'), 0);
    }

    /**
     * 应用控制器调用
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2020-01-02
     * @param    [string]          $plugins        [应用标记]
     * @param    [string]          $control        [应用控制器]
     * @param    [string]          $action         [应用方法]
     * @param    [string]          $group          [应用组(admin, index, api)]
     * @param    [array]           $params         [输入参数]
     */
    public static function PluginsControlCall($plugins, $control, $action, $group = 'index', $params = [])
    {
        // 应用校验
        $ret = self::PluginsCheck($plugins);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 应用控制器
        $control = ucfirst($control);
        $plugins_class = '\app\plugins\\'.$plugins.'\\'.$group.'\\'.$control;
        if(!class_exists($plugins_class))
        {
            return DataReturn(MyLang('common_service.plugins.plugins_call_control_undefined_tips').'['.$control.']', -1);
        }

        // 调用方法仅传递请求参数，去除多余的参数、避免给页面url地址造成污染
        if(!empty($params['data_request']) && is_array($params['data_request']))
        {
            unset($params['data_request']['s'], $params['data_request']['pluginsname'], $params['data_request']['pluginscontrol'], $params['data_request']['pluginsaction']);
        }

        // 调用方法
        $action = ucfirst($action);
        $obj = new $plugins_class($params);
        if(!method_exists($obj, $action))
        {
            return DataReturn(MyLang('common_service.plugins.plugins_call_action_undefined_tips').'['.$action.']', -1);
        }

        // 调用方法仅传递请求参数，重新赋值参数数据
        if(isset($params['data_request']))
        {
            $params = $params['data_request'];
        }

        // 安全判断
        $ret = self::PluginsLegalCheck($plugins);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 调用对应插件
        return DataReturn('success', 0, $obj->$action($params));
    }

    /**
     * 插件安全判断
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-05-26
     * @desc    description
     * @param   [string]          $plugins [插件标识]
     * @param   [array]           $data    [插件数据]
     */
    public static function PluginsLegalCheck($plugins, $data = [])
    {
        if(RequestModule() == 'admin')
        {
            $key = 'plugins_legal_check_'.$plugins;
            $ret = MyCache($key);
            if(empty($ret))
            {
                if(empty($data))
                {
                    $data = PluginsAdminService::GetPluginsConfig($plugins);
                }
                if(empty($data) || empty($data['base']))
                {
                    return DataReturn(MyLang('common_service.plugins.plugins_call_config_error_tips'), -1);
                }
                $check_params = [
                    'type'      => 'plugins',
                    'config'    => $data,
                    'plugins'   => $plugins,
                    'author'    => $data['base']['author'],
                    'ver'       => $data['base']['version'],
                ];
                $ret = StoreService::PluginsLegalCheck($check_params);
                MyCache($key, $ret, 3600);
            }
            if(!in_array($ret['code'], [0, -9999]))
            {
                return $ret;
            }
        }
        return DataReturn('success', 0);
    }

    /**
     * 应用回调事件调用
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2020-01-02
     * @param    [string]          $plugins        [应用标记]
     * @param    [string]          $action         [事件方法(
     *  Upload          上传
     *  BeginInstall    安装前（验证返回状态）
     *  Install         安装
     *  Uninstall       卸载
     *  Download        下载
     *  BeginUpgrade    更新前（验证返回状态）
     *  Upgrade         更新
     *  Delete          删除)]
     * @param    [array]           $params         [输入参数]
     */
    public static function PluginsEventCall($plugins, $action, $params = [])
    {
        // 应用事件
        $plugins = '\app\plugins\\'.$plugins.'\\Event';
        if(!class_exists($plugins))
        {
            return DataReturn(MyLang('common_service.plugins.plugins_event_undefined_tips').'['.$plugins.']', 0);
        }

        // 调用方法
        $action = ucfirst($action);
        $obj = new $plugins($params);
        if(!method_exists($obj, $action))
        {
            return DataReturn(MyLang('common_service.plugins.plugins_event_action_undefined_tips').'['.$action.']', 0);
        }

        // 调用方法仅传递请求参数
        if(!empty($params) && isset($params['data_request']))
        {
            $params = $params['data_request'];
        }
        $ret = $obj->$action($params);
        if(!empty($ret) && is_array($ret) && array_key_exists('code', $ret) && array_key_exists('data', $ret) && array_key_exists('msg', $ret))
        {
            return $ret;
        }
        return DataReturn('success', 0, $ret);
    }

    /**
     * 所有有效插件配置列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-01-06
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PluginsBaseList($params = [])
    {
        $data = Db::name('Plugins')->where(['is_enable'=>1])->order(PluginsAdminService::$plugins_order_by)->field('id,plugins,data')->select()->toArray();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 获取插件基础字段定义
                $base_ser = self::PluginsBaseSerData($v['plugins']);

                // 处理配置数据
                $v['data'] = self::PluginsDataHandle($v['data'], $base_ser['attachment_field']);

                // 是否存在配置处理方法
                if($base_ser['ser'] !== null && method_exists($base_ser['ser'], 'BaseConfigHandle'))
                {
                    $v['data'] = $base_ser['ser']::BaseConfigHandle($v['data']);
                }

                // 移除私有字段及数据
                if(!empty($v['data']) && is_array($v['data']) && !empty($base_ser['private_field']) && is_array($base_ser['private_field']))
                {
                    $v['data'] = self::ConfigPrivateFieldsHandle($v['data'], $base_ser['private_field']);
                }
            }

            // 是否返回插件标识为索引
            if(!empty($params) && isset($params['is_key']) && $params['is_key'] == 1)
            {
                $data = array_column($data, null, 'plugins');
            }
        } else {
            $data = [];
        }
        return $data;
    }

    /**
     * 插件配置隐私字段处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-13
     * @desc    description
     * @param   [array]          $config   [配置数据]
     * @param   [array]          $fields   [字段列表]
     */
    public static function ConfigPrivateFieldsHandle($config, $fields)
    {
        if(!empty($config) && is_array($config) && !empty($fields) && is_array($fields))
        {
            foreach($fields as $pv)
            {
                if(array_key_exists($pv, $config))
                {
                    unset($config[$pv]);
                }
            }
        }
        return $config;
    }

    /**
     * 插件更新信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-04-21
     * @desc    description
     * @param   [array]           $params [输入参数、插件信息]
     */
    public static function PluginsUpgradeInfo($params = [])
    {
        // 默认返回数据
        $result = DataReturn(MyLang('plugins_no_data_tips'), 0);

        // 缓存记录
        $time = 3600;
        $key = 'plugins_upgrade_check_info';
        $res = MyCache($key);
        if(empty($res) || (isset($params['is_force_new']) && $params['is_force_new'] == 1))
        {
            if(!empty($params) && !empty($params['db_data']) && is_array($params['db_data']))
            {
                // 数据组装
                $data = [];
                foreach($params['db_data'] as $v)
                {
                    if(!empty($v['name']) && !empty($v['version']) && !empty($v['plugins']) && !empty($v['author']))
                    {
                        $data[] = [
                            'plugins'   => $v['plugins'],
                            'name'      => $v['name'],
                            'ver'       => $v['version'],
                            'author'    => $v['author'],
                        ];
                    }
                }

                // 获取更新信息
                if(!empty($data))
                {
                    // 获取更新信息
                    $request_params = [
                        'plugins_type'  => 'plugins',
                        'plugins_data'  => $data,
                    ];
                    $result = StoreService::PluginsUpgradeInfo($request_params);
                    if(!empty($result) && isset($result['code']) && $result['code'] == 0)
                    {
                        // 处理存在更新的插件数据
                        if(!empty($result['data']) && is_array($result['data']))
                        {
                            $result['data'] = array_column($result['data'], null, 'plugins');
                        }

                        // 存储缓存
                        MyCache($key, $result, $time);
                    }
                } else {
                    // 存储缓存
                    MyCache($key, $result, $time);
                }
            }
        } else {
            $result = $res;
        }
        return $result;
    }

    /**
     * 应用插件顺序数据列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-13
     * @desc    description
     */
    public static function PluginsSortList()
    {
        $data = Db::name('Plugins')->field('id,name,plugins')->where(['is_enable'=>1])->order(PluginsAdminService::$plugins_order_by)->select()->toArray();
        return empty($data) ? [] : $data;
    }

    /**
     * 所有带首页的插件列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-03
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PluginsHomeDataList($params = [])
    {
        $plugins_list = [];
        $ret = PluginsAdminService::PluginsList();
        if(!empty($ret['data']) && !empty($ret['data']['db_data']) && is_array($ret['data']['db_data']))
        {
            foreach($ret['data']['db_data'] as $v)
            {
                // 必须是带首页的插件
                if(!empty($v['plugins']) && !empty($v['name']) && isset($v['is_home']) && $v['is_home'] == true)
                {
                    $plugins_list[] = [
                        'type'   => 'plugins',
                        'name'   => $v['name'],
                        'value'  => $v['plugins'],
                    ];
                }
            }
        }
        return $plugins_list;
    }

    /**
     * 插件新版本检查
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-11-21
     * @desc    description
     * @param   [string]          $plugins [插件标识、空则全部]
     */
    public static function PluginsNewVersionCheck($plugins = '')
    {
        // 是否存在插件未更新的情况
        $where = empty($plugins) ? [] : ['plugins'=>$plugins];
        $plugins_list = Db::name('Plugins')->where($where)->column('plugins');
        if(!empty($plugins_list))
        {
            $data = [];
            foreach($plugins_list as $plugins)
            {
                $config = PluginsAdminService::GetPluginsConfig($plugins);
                if(!empty($config['base']))
                {
                    $data[$plugins] = $config['base'];
                }
            }
            if(!empty($data))
            {
                $check = self::PluginsUpgradeInfo(['db_data'=>$data, 'is_force_new'=>1]);
                if(isset($check['code']) && $check['code'] == 0 && !empty($check['data']))
                {
                    foreach($check['data'] as $v)
                    {
                        if(isset($data[$v['plugins']]))
                        {
                            return DataReturn(MyLang('common_service.plugins.plugins_new_version_update_tips', ['plugins'=>$data[$v['plugins']]['name'], 'version'=>$v['version_new']]), -1);
                        }
                    }
                }
            }
        }
        return DataReturn(MyLang('common_service.plugins.plugins_no_update_tips'), 0);
    }
}
?>