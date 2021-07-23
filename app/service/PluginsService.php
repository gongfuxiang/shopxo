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
        // 从缓存获取数据、数据不存在则从数据库读取
        $data = ($is_cache === true) ? self::PluginsCacheData($plugins) : [];
        if($data === null || !$is_cache)
        {
            // 获取数据
            $ret = self::PluginsField($plugins, 'data');
            if(!empty($ret['data']))
            {
                // 数据处理
                $data = self::PluginsDataHandle($ret['data'], $attachment_field);
            } else {
                $data = [];
            }

            // 存储缓存
            self::PluginsCacheStorage($plugins, $data);
        }
        return DataReturn('处理成功', 0, $data);
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
                        $data[$field.'_old'] = $data[$field];
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
                                    $data[$k.'_old'] = $v;
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
                'error_msg'         => '应用标记不能为空',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'data',
                'error_msg'         => '数据参数不能为空',
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
            
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
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
        return MyCache(MyConfig('shopxo.cache_plugins_data_key').$plugins, $data);
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
        return MyCache(MyConfig('shopxo.cache_plugins_data_key').$plugins);
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
        MyCache(MyConfig('shopxo.cache_plugins_data_key').$plugins, null);
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
        $data = Db::name('Plugins')->where(['plugins'=>$plugins])->value($field);
        return DataReturn('操作成功', 0, $data);
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
                    return DataReturn('地址有误['.$plugins.']', -10);
                }
                MyRedirect(__MY_URL__, true);
            }
            return DataReturn('应用未安装['.$plugins.']', -10);
        }
        if($ret != 1)
        {
            return DataReturn('应用未启用['.$plugins.']', -11);
        }
        return DataReturn('验证成功', 0);
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
            return DataReturn('应用控制器未定义['.$control.']', -1);
        }

        // 调用方法
        $action = ucfirst($action);
        $obj = new $plugins_class($params);
        if(!method_exists($obj, $action))
        {
            return DataReturn('应用方法未定义['.$action.']', -1);
        }

        // 调用方法仅传递请求参数
        if(!empty($params) && isset($params['data_request']))
        {
            $params = $params['data_request'];
        }

        // 安全判断
        if(MyConfig('shopxo.is_develop') === false && RequestModule() == 'admin')
        {
            $key = 'plugins_legal_check_'.$plugins;
            $ret = MyCache($key);
            if(empty($ret))
            {
                $config = PluginsAdminService::GetPluginsConfig($plugins);
                if(empty($config) || empty($config['base']))
                {
                    return DataReturn('应用插件配置信息有误', -1);
                }
 
                $check_params = [
                    'type'      => 'plugins',
                    'config'    => $config,
                    'plugins'   => $plugins,
                    'author'    => $config['base']['author'],
                    'ver'       => $config['base']['version'],
                ];
                $ret = StoreService::PluginsLegalCheck($check_params);
                MyCache($key, $ret, 3600);
            }
            if($ret['code'] != 0)
            {
                return $ret;
            }
        }

        // 调用对应插件
        return DataReturn('调用成功', 0, $obj->$action($params));
    }

    /**
     * 应用回调事件调用
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2020-01-02
     * @param    [string]          $plugins        [应用标记]
     * @param    [string]          $action         [事件方法(Upload 上传, Install 安装, Uninstall 卸载, Download 下载, Upgrade 更新, Delete 删除)]
     * @param    [array]           $params         [输入参数]
     */
    public static function PluginsEventCall($plugins, $action, $params = [])
    {
        // 应用事件
        $plugins = '\app\plugins\\'.$plugins.'\\Event';
        if(!class_exists($plugins))
        {
            return DataReturn('应用事件未定义['.$plugins.']', -1);
        }

        // 调用方法
        $action = ucfirst($action);
        $obj = new $plugins($params);
        if(!method_exists($obj, $action))
        {
            return DataReturn('应用事件方法未定义['.$action.']', -1);
        }

        // 调用方法仅传递请求参数
        if(!empty($params) && isset($params['data_request']))
        {
            $params = $params['data_request'];
        }
        return DataReturn('调用成功', 0, $obj->$action($params));
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
                $v['data'] = self::PluginsDataHandle($v['data']);
            }
        } else {
            $data = [];
        }
        return $data;
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
        $result = DataReturn('无插件数据', 0);

        // 缓存记录
        $time = 3600;
        $key = 'plugins_upgrade_check_info';
        $res = MyCache($key);
        if(empty($res))
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
                        if(!empty($result['data']))
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
}
?>