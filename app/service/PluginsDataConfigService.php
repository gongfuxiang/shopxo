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

/**
 * 插件配置服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-03-31
 * @desc    description
 */
class PluginsDataConfigService
{
    // 配置数据
    public static $data_config = [];

    /**
     * 单个配置数据值
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-15
     * @desc    description
     * @param   [string]       $plugins   [插件标识]
     * @param   [string]       $key       [数据key]
     * @param   [mexid]        $default   [默认值]
     */
    public static function DataConfigValue($plugins, $key, $default = null)
    {
        // 是否已读取配置数据
        if(!array_key_exists($plugins, self::$data_config))
        {
            self::$data_config[$plugins] = self::ConfigData($plugins);
        }

        // 返回配置数据值
        return (empty(self::$data_config[$plugins]) || !array_key_exists($key, self::$data_config[$plugins])) ? $default : self::$data_config[$plugins][$key];
    }

    /**
     * 所有配置数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-15
     * @desc    description
     * @param   [string]       $plugins   [插件标识]
     */
    public static function DataConfigData($plugins = [])
    {
        $data = [];
        if(!empty($plugins))
        {
            $data = Db::name('PluginsDataConfig')->where(['plugins'=>$plugins])->column('value', 'only_tag');
            if(!empty($data))
            {
                // 所有附件后缀名称、附件处理
                $attachment_ext = MyConfig('ueditor.fileManagerAllowFiles');
                if(!empty($attachment_ext) && is_array($attachment_ext))
                {
                    foreach($data as $k=>$v)
                    {
                        if(!empty($v))
                        {
                            // 附件
                            if(!is_array($v) && !is_object($v))
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

                            // json
                            if(in_array(substr($v, 0, 1), ['[', '{']))
                            {
                                $data[$k] = json_decode($v, true);
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-15
     * @desc    description
     * @param   [string]       $plugins [插件标识]
     * @param   [array]        $params  [输入参数]
     */
    public static function DataConfigSave($plugins, $params = [])
    {
        // 参数校验
        if(empty($params['data']))
        {
            return DataReturn('配置数据为空', -1);
        }
        if(!is_array($params['data']))
        {
            $params['data'] = json_decode(htmlspecialchars_decode($params['data']), true);
        }

        // 所有附件后缀名称、附件处理
        $attachment_ext = MyConfig('ueditor.fileManagerAllowFiles');

        // 数据处理
        foreach($params['data'] as $k=>$v)
        {
            // 附件处理
            if(!empty($attachment_ext) && is_array($attachment_ext))
            {
                if(!empty($v) && !is_array($v) && !is_object($v))
                {
                    $ext = strrchr(substr($v, -6), '.');
                    if($ext !== false)
                    {
                        if(in_array($ext, $attachment_ext))
                        {
                            $v = ResourcesService::AttachmentPathHandle($v);
                        }
                    }
                }
            }

            // 配置数据
            $data = [
                'plugins'   => $plugins,
                'only_tag'  => $k,
                'value'     => is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v,
            ];
            $info = Db::name('PluginsDataConfig')->where(['plugins'=>$data['plugins'], 'only_tag'=>$data['only_tag']])->find();
            if(empty($info))
            {
                $data['add_time'] = time();
                Db::name('PluginsDataConfig')->insertGetId($data);
            } else {
                $data['upd_time'] = time();
                Db::name('PluginsDataConfig')->where(['id'=>$info['id']])->update($data);
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-09-15
     * @desc    description
     * @param   [string]       $plugins [插件标识]
     */
    public static function DataConfigDelete($plugins)
    {
        if(Db::name('PluginsDataConfig')->where(['plugins'=>$plugins])->delete() !== false)
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -1);
    }
}
?>