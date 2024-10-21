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
use app\module\DiyModule;

/**
 * 手机底部菜单服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-22
 * @desc    description
 */
class AppTabbarService
{
    // 布局key
    public static $app_tabbar_key;

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
        self::$app_tabbar_key = [
            'home'  => [
                'type'  => 'app_tabbar_index_home_data',
                'name'  => MyLang('home_title'),
            ],
        ];
    }

    /**
     * 底部菜单保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     * @param   [array]          $params    [输入参数]
     */
    public static function AppTabbarConfigSave($key, $params)
    {
        // 初始化
        self::Init();

        // key 值是否存在
        if(!array_key_exists($key, self::$app_tabbar_key))
        {
            return DataReturn(MyLang('common_service.base.key_error_tips'), -1);
        }

        // 获取配置信息
        $key_info = self::$app_tabbar_key[$key];
        $where = ['type'=>$key_info['type'], 'is_enable'=>1];
        $info = Db::name('AppTabbar')->where($where)->find();

        // 配置信息
        $config = empty($params['config']) ? '' : DiyModule::ConfigSaveHandle($params['config']);

        // 数据保存
        $data = [ 
            'type'    => $key_info['type'],
            'name'    => $key_info['name'],
            'config'  => empty($config) ? '' : (is_array($config) ? json_encode($config, JSON_UNESCAPED_UNICODE) : $config),
        ];
        if(empty($info))
        {
            $data['add_time'] = time();
            if(Db::name('AppTabbar')->insertGetId($data) <= 0)
            {
                return DataReturn(MyLang('insert_fail'), -1);
            }
        } else {
            $data['upd_time'] = time();
            if(!Db::name('AppTabbar')->where(['id'=>$info['id']])->update($data))
            {
                return DataReturn(MyLang('update_fail'), -1);
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 底部菜单获取
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]         $key       [数据key值]
     */
    public static function AppTabbarConfigData($key)
    {
        // 初始化
        self::Init();

        // 是否存在key
        if(array_key_exists($key, self::$app_tabbar_key))
        {
            $config = Db::name('AppTabbar')->where(['type'=>self::$app_tabbar_key[$key]['type'], 'is_enable'=>1])->value('config');
            return empty($config) ? null : DiyModule::ConfigViewHandle($config);
        }
        return null;
    }
}
?>