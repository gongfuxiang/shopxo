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
namespace app\service;

use think\Db;
use app\service\ResourcesService;

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
        // 从缓存获取数据
        $data = [];
        $key = config('shopxo.cache_plugins_data_key').$plugins;
        if($is_cache === true)
        {
            $data = cache($key);
        }

        // 数据不存在则从数据库读取
        if(empty($data))
        {
            // 获取数据
            $ret = self::PluginsField($plugins, 'data');
            if(!empty($ret['data']))
            {
                $data = json_decode($ret['data'], true);
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
                        $attachment_ext = config('ueditor.fileManagerAllowFiles');

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

                // 存储缓存
                cache($key, $data);
            }
        }
        return DataReturn('处理成功', 0, $data);
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
            cache(config('shopxo.cache_plugins_data_key').$params['plugins'], null);
            
            return DataReturn('操作成功');
        }
        return DataReturn('操作失败', -100);
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
            return DataReturn('应用未安装['.$plugins.']', -10);
        }
        if($ret != 1)
        {
            return DataReturn('应用未启用['.$plugins.']', -10);
        }
        return DataReturn('验证成功', 0);
    }

    /**
     * 应用控制器调用
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @date     2020-01-02
     * @param   [string]          $plugins        [应用标记]
     * @param   [string]          $control        [应用控制器]
     * @param   [string]          $action         [应用方法]
     * @param   [string]          $group          [应用组(admin, index, api)]
     * @param   [array]           $params         [输入参数]
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
        $plugins = '\app\plugins\\'.$plugins.'\\'.$group.'\\'.$control;
        if(!class_exists($plugins))
        {
            return DataReturn('应用控制器未定义['.$control.']', -1);
        }

        // 调用方法
        $action = ucfirst($action);
        $obj = new $plugins();
        if(!method_exists($obj, $action))
        {
            return DataReturn('应用方法未定义['.$action.']', -1);
        }
        return DataReturn('验证成功', 0, $obj->$action($params));
    }
}
?>