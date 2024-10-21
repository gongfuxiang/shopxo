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
use app\module\LayoutModule;

/**
 * 布局服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-22
 * @desc    description
 */
class LayoutService
{
    // 布局key
    public static $layout_key;

    /**
     * 初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-08
     * @desc    description
     */
    public static function Init()
    {
        self::$layout_key = [
            'home'  => [
                'type'  => 'layout_index_home_data',
                'name'  => MyLang('home_title'),
            ],
        ];
    }

    /**
     * 布局配置保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     * @param   [array]          $params    [输入参数]
     */
    public static function LayoutConfigSave($key, $params)
    {
        // 初始化
        self::Init();

        // key 值是否存在
        if(!array_key_exists($key, self::$layout_key))
        {
            return DataReturn(MyLang('common_service.base.key_error_tips'), -1);
        }

        // 获取配置信息
        $key_info = self::$layout_key[$key];
        $where = ['type'=>$key_info['type'], 'is_enable'=>1];
        $info = Db::name('Layout')->where($where)->find();

        // 配置信息
        $config = empty($params['config']) ? '' : LayoutModule::ConfigSaveHandle($params['config']);

        // 数据保存
        $data = [ 
            'type'    => $key_info['type'],
            'name'    => $key_info['name'],
            'config'  => empty($config) ? '' : (is_array($config) ? json_encode($config, JSON_UNESCAPED_UNICODE) : $config),
        ];
        if(empty($info))
        {
            $data['add_time'] = time();
            if(Db::name('Layout')->insertGetId($data) <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -1);
            }
        } else {
            $data['upd_time'] = time();
            if(!Db::name('Layout')->where(['id'=>$info['id']])->update($data))
            {
                return DataReturn(MyLang('update_fail'), -1);
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 布局配置获取-管理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     */
    public static function LayoutConfigAdminData($key)
    {
        // 初始化
        self::Init();

        // 是否存在key
        if(array_key_exists($key, self::$layout_key))
        {
            $config = Db::name('Layout')->where(['type'=>self::$layout_key[$key]['type'], 'is_enable'=>1])->value('config');
            return LayoutModule::ConfigAdminHandle($config);
        }
        return '';
    }

    /**
     * 布局配置获取-展示使用
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     */
    public static function LayoutConfigData($key)
    {
        // 初始化
        self::Init();

        // 是否存在key
        if(array_key_exists($key, self::$layout_key))
        {
            $config = Db::name('Layout')->where(['type'=>self::$layout_key[$key]['type'], 'is_enable'=>1])->value('config');
            return LayoutModule::ConfigHandle($config);
        }
        return '';
    }
}
?>