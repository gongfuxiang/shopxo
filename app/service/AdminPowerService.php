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
use app\service\AdminService;
use app\service\AdminRoleService;
use app\service\PluginsService;

/**
 * 权限菜单服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AdminPowerService
{
    /**
     * 获取分类节点数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-16T23:54:46+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PowerNodeSon($params = [])
    {
        // id
        $id = isset($params['id']) ? intval($params['id']) : 0;

        // 获取数据
        $data = Db::name('Power')->where(['pid'=>$id])->order('sort asc')->select()->toArray();
        if(!empty($data))
        {;
            foreach($data as &$v)
            {
                $v['is_son']  = (Db::name('Power')->where(['pid'=>$v['id']])->count() > 0) ? 'ok' : 'no';
                $v['json']    = json_encode($v);
            }
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('no_data'), -100);
    }

    /**
     * 权限菜单保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-07T00:24:14+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PowerSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,60',
                'error_msg'         => MyLang('common_service.power.form_item_name_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'icon',
                'checked_data'      => '60',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.power.form_item_icon_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_show',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_is_show_message'),
            ],
        ];
        // 是否自定义url地址
        if(empty($params['url']))
        {
            $p[] = [
                'checked_type'      => 'length',
                'key_name'          => 'control',
                'checked_data'      => '1,80',
                'error_msg'         => MyLang('common_service.power.form_item_control_message'),
            ];
            $p[] = [
                'checked_type'      => 'length',
                'key_name'          => 'action',
                'checked_data'      => '1,80',
                'error_msg'         => MyLang('common_service.power.form_item_action_message'),
            ];
        } else {
            $p[] = [
                'checked_type'      => 'length',
                'key_name'          => 'url',
                'checked_data'      => '1,255',
                'error_msg'         => MyLang('common_service.power.form_item_url_message'),
            ];
        }
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 保存数据
        $data = [
            'pid'       => isset($params['pid']) ? intval($params['pid']) : 0,
            'sort'      => isset($params['sort']) ? intval($params['sort']) : 0,
            'icon'      => isset($params['icon']) ? $params['icon'] : '',
            'name'      => $params['name'],
            'control'   => $params['control'],
            'action'    => $params['action'],
            'url'       => $params['url'],
            'is_show'   => isset($params['is_show']) ? intval($params['is_show']) : 0,
        ];
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            $data['id'] = Db::name('Power')->insertGetId($data);
            if($data['id'] > 0)
            {
                return DataReturn(MyLang('insert_success'), 0, $data);
            }
            return DataReturn(MyLang('insert_fail'), -100);
        } else {
            $data['upd_time'] = time();
            if(Db::name('Power')->where(['id'=>intval($params['id'])])->update($data) !== false)
            {
                $data['id'] = intval($params['id']);
                return DataReturn(MyLang('update_success'), 0, $data);
            }
            return DataReturn(MyLang('update_fail'), -100);
        }
    }

    /**
     * 权限菜单状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PowerStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => MyLang('operate_field_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 捕获异常
        try {
            // 数据更新
            if(!Db::name('Power')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
            {
                throw new \Exception(MyLang('operate_fail'));
            }

            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 获取权限下的所有权限id
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]         $ids       [分类id数组]
     * @param   [int]           $is_show   [是否显示 null, 0否, 1是]
     * @param   [int]           $level     [指定级别 null, 整数、默认则全部下级]
     */
    public static function PowerItemsIds($ids = [], $is_show = null, $level = null)
    {
        if(!is_array($ids))
        {
            $ids = explode(',', $ids);
        }
        $where = [
            ['pid', 'in', $ids],
        ];
        if($is_show !== null)
        {
            $where[] = ['is_show', '=', $is_show];
        }

        // 级别记录处理
        if($level !== null)
        {
            if(is_array($level))
            {
                $level['temp'] += 1;
            } else {
                $level = [
                    'value' => $level,
                    'temp'  => 1,
                ];
            }
        }

        // 是否超过级别限制
        if($level === null || $level['temp'] < $level['value'])
        {
            $data = Db::name('Power')->where($where)->column('id');
            if(!empty($data))
            {
                $temp = self::PowerItemsIds($data, $is_show, $level);
                if(!empty($temp))
                {
                    $data = array_merge($data, $temp);
                }
            }
        }
        return empty($data) ? $ids : array_unique(array_merge($ids, $data));
    }

    /**
     * 权限菜单删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-07T00:24:14+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PowerDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['id']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }

        // 获取分类下所有分类id
        $ids = self::PowerItemsIds([$params['id']]);

        // 开始删除
        if(Db::name('Power')->where(['id'=>$ids])->delete())
        {
            // 清除用户权限数据
            self::PowerCacheDelete();
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 后台管理员权限缓存数据清除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-26T23:45:26+0800
     */
    public static function PowerCacheDelete()
    {
        $admin = Db::name('Admin')->column('id');
        if(!empty($admin))
        {
            foreach($admin as $id)
            {
                MyCache(SystemService::CacheKey('shopxo.cache_admin_left_menu_key').$id, null);
                MyCache(SystemService::CacheKey('shopxo.cache_admin_power_key').$id, null);
                MyCache(SystemService::CacheKey('shopxo.cache_admin_power_plugins_key').$id, null);
                MyCache(SystemService::CacheKey('shopxo.cache_admin_power_all_plugins_key').$id, null);
            }
        }
    }
    
    /**
     * 管理员权限菜单初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-01-23
     * @desc    description
     * @param   [array]         $admin      [管理员信息]
     * @param   [boolean]       $is_refresh [是否强制刷新]
     */
    public static function PowerMenuInit($admin = [], $is_refresh = false)
    {
        // 不存在管理员信息则读取登录信息
        if(empty($admin))
        {
            $admin = AdminService::LoginInfo();
        }

        // 基础参数
        $admin_id = isset($admin['id']) ? intval($admin['id']) : 0;
        $role_id = isset($admin['role_id']) ? intval($admin['role_id']) : 0;

        // 读取缓存数据
        $admin_left_menu = MyCache(SystemService::CacheKey('shopxo.cache_admin_left_menu_key').$admin_id);
        $admin_power = MyCache(SystemService::CacheKey('shopxo.cache_admin_power_key').$admin_id);
        $admin_plugins = MyCache(SystemService::CacheKey('shopxo.cache_admin_power_plugins_key').$admin_id);
        $admin_all_plugins = MyCache(SystemService::CacheKey('shopxo.cache_admin_power_all_plugins_key').$admin_id);

        // 缓存没数据则从数据库重新读取
        if((($role_id > 0 || $admin_id == 1) && (empty($admin_left_menu) || empty($admin_power))) || $is_refresh || MyEnv('app_debug') || MyInput('lang') || MyC('common_data_is_use_cache') != 1)
        {
            // 获取一级数据、有数据，则处理子级数据
            $admin_left_menu = self::AdminPowerMenuData($admin_id, $role_id);
            if(!empty($admin_left_menu))
            {
                // 三级页面菜单
                $three_data = self::PowerMenuThreeData();

                // 获取下级插件
                $plugins_data = AdminRoleService::PluginsList();
                // 后台全部插件权限
                $admin_all_plugins = [];
                if(!empty($plugins_data))
                {
                    foreach($plugins_data as $pv)
                    {
                        $power_data = [];
                        $plugins_power = PluginsService::PluginsAdminPowerMenu($pv['plugins']);
                        if(!empty($plugins_power))
                        {
                            foreach($plugins_power as $ppv)
                            {
                                if(!empty($ppv['control']))
                                {
                                    if(!empty($ppv['action']))
                                    {
                                        $power_data[] = $ppv['control'].'-'.$ppv['action'];
                                    }
                                    if(!empty($ppv['item']) && is_array($ppv['item']))
                                    {
                                        foreach($ppv['item'] as $ppvs)
                                        {
                                            if(!empty($ppvs['action']))
                                            {
                                                $control = empty($ppvs['control']) ? $ppv['control'] : $ppvs['control'];
                                                $power_data[] = $control.'-'.$ppvs['action'];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $admin_all_plugins[$pv['plugins']] = [
                            'name'     => $pv['name'],
                            'plugins'  => $pv['plugins'],
                            'power'    => $power_data,
                        ];
                    }
                }

                // 语言数据
                $lang = MyLang('admin_power_menu_list');
                $temp_lang = [];

                // 当前站点地址
                $domain_url = SystemService::DomainUrl();

                // 菜单权限
                foreach($admin_left_menu as $k=>$v)
                {
                    // 自定义url处理
                    if(!empty($v['url']))
                    {
                        if(!in_array(substr($v['url'], 0, 6), ['http:/', 'https:']))
                        {
                            if(substr($v['url'], 0, 1) == '/')
                            {
                                $v['url'] = substr($v['url'], 1);
                            }
                            $admin_left_menu[$k]['url'] = $domain_url.$v['url'];
                        }
                    }

                    // 是否存在控制器和方法
                    if(!empty($v['control']) && !empty($v['action']))
                    {
                        // 权限
                        $key = strtolower($v['control'].'_'.$v['action']);
                        $admin_left_menu[$k]['key'] = $key;
                        $admin_power[$key] = $v['name'];

                        // url、存在自定义url则不覆盖
                        if(empty($v['url']))
                        {
                            $admin_left_menu[$k]['url'] = MyUrl('admin/'.strtolower($v['control']).'/'.strtolower($v['action']));
                        }

                        // 语言处理
                        if(!empty($lang) && is_array($lang) && array_key_exists($key, $lang))
                        {
                            $temp_lang = $lang[$key];
                            $admin_left_menu[$k]['name'] = $temp_lang['name'];
                        }
                    }

                    // 获取二级菜单
                    $two = self::AdminPowerMenuData($admin_id, $role_id, $v['id']);
                    $is_old_two_data = !empty($two);
                    $three_power = [];
                    if(!empty($two))
                    {
                        foreach($two as $ks=>$vs)
                        {
                            // 自定义url处理
                            if(!empty($vs['url']))
                            {
                                if(!in_array(substr($vs['url'], 0, 6), ['http:/', 'https:']))
                                {
                                    if(substr($vs['url'], 0, 1) == '/')
                                    {
                                        $vs['url'] = substr($vs['url'], 1);
                                    }
                                    $two[$ks]['url'] = $domain_url.$vs['url'];
                                }
                            }

                            // 是否存在控制器和方法
                            $key = '';
                            if(!empty($vs['control']) && !empty($vs['action']))
                            {
                                // 权限
                                $key = strtolower($vs['control'].'_'.$vs['action']);
                                $two[$ks]['key'] = $key;
                                $admin_power[$key] = $vs['name'];

                                // url、存在自定义url则不覆盖
                                if(empty($vs['url']))
                                {
                                    $two[$ks]['url'] = MyUrl('admin/'.strtolower($vs['control']).'/'.strtolower($vs['action']));
                                }

                                // 语言处理
                                if(!empty($temp_lang['item']) && is_array($temp_lang['item']) && array_key_exists($key, $temp_lang['item']))
                                {
                                    $two[$ks]['name'] = $temp_lang['item'][$key];
                                }
                            }

                            // 自定义三级页面菜单
                            if(!empty($key) && !empty($three_data) && is_array($three_data) && !empty($three_data[$key]))
                            {
                                $three = $three_data[$key];
                                foreach($three as &$vss)
                                {
                                    // 导航类型
                                    $url_params = [];
                                    if(array_key_exists('type', $vss))
                                    {
                                        $url_params['nav_type'] = $vss['type'];
                                    }
                                    $vss['url'] = MyUrl('admin/'.strtolower($vs['control']).'/'.strtolower($vs['action']), $url_params);
                                    $vss['key'] = $key.'_'.$vss['type'];
                                    $vss['id'] = $vs['id'].$vss['type'];
                                }
                                $two[$ks]['items'] = $three;
                            }

                            // 是否显示视图
                            if(isset($vs['is_show']) && $vs['is_show'] == 0)
                            {
                                unset($two[$ks]);
                            }

                            // 获取三级权限
                            $three_power = self::AdminPowerMenuData($admin_id, $role_id, $vs['id']);
                            if(!empty($three_power))
                            {
                                foreach($three_power as $itsv)
                                {
                                    // 是否存在控制器和方法
                                    if(!empty($itsv['control']) && !empty($itsv['action']))
                                    {
                                        // 权限
                                        $key = strtolower($itsv['control'].'_'.$itsv['action']);
                                        $admin_power[$key] = $itsv['name'];
                                    }
                                }

                                // 存在三级的权限菜单则表示二级也有数据
                                $is_old_two_data = true;
                            }
                        }
                        $two = array_values($two);
                    } else {
                        // 二级下的三级权限菜单
                        $two_ids = Db::name('Power')->where(['pid'=>$v['id']])->column('id');
                        $three_power = self::AdminPowerMenuData($admin_id, $role_id, $two_ids);
                        if(!empty($three_power))
                        {
                            foreach($three_power as $itsv)
                            {
                                // 是否存在控制器和方法
                                if(!empty($itsv['control']) && !empty($itsv['action']))
                                {
                                    // 权限
                                    $key = strtolower($itsv['control'].'_'.$itsv['action']);
                                    $admin_power[$key] = $itsv['name'];
                                }
                            }

                            // 存在三级的权限菜单则表示二级也有数据
                            $is_old_two_data = true;
                        }
                    }

                    // 一级菜单下的插件
                    if(!empty($plugins_data))
                    {
                        foreach($plugins_data as $pv)
                        {
                            if(!empty($pv['plugins_menu_control']) && strtolower($pv['plugins_menu_control']) == strtolower($v['control']))
                            {
                                $two[] = [
                                    'id'    => 'plugins-'.$pv['plugins'],
                                    'key'   => 'plugins-'.$pv['plugins'],
                                    'name'  => $pv['name'],
                                    'url'   => PluginsAdminUrl($pv['plugins'], 'admin', 'index'),
                                ];
                            }
                        }
                    }

                    // 是否有二级数据
                    if(!empty($two))
                    {
                        $admin_left_menu[$k]['items'] = $two;
                    }
                    // 数据是否显示、本来就不显示或者原来下级有数据但是现在没有数据了
                    if($v['is_show'] == 0 || ($is_old_two_data && empty($two)))
                    {
                        unset($admin_left_menu[$k]);
                    }
                }

                // 插件权限
                if($admin_id == 1 || $role_id == 1)
                {
                    $admin_plugins = $admin_all_plugins;
                } else {
                    $admin_plugins = Db::name('RolePlugins')->where(['role_id'=>$role_id])->column('name,plugins,power', 'plugins');
                    if(!empty($admin_plugins) && is_array($admin_plugins))
                    {
                        $admin_plugins = array_map(function($item)
                        {
                            $item['power'] = empty($item['power']) ? [] : json_decode($item['power'], true);
                            return $item;
                        }, $admin_plugins);
                    }
                }
            }
            MyCache(SystemService::CacheKey('shopxo.cache_admin_left_menu_key').$admin_id, $admin_left_menu);
            MyCache(SystemService::CacheKey('shopxo.cache_admin_power_key').$admin_id, $admin_power);
            MyCache(SystemService::CacheKey('shopxo.cache_admin_power_plugins_key').$admin_id, $admin_plugins);
            MyCache(SystemService::CacheKey('shopxo.cache_admin_power_all_plugins_key').$admin_id, $admin_all_plugins);
        }

        // 后台左侧菜单钩子
        $hook_name = 'plugins_service_admin_menu_data';
        MyEventTrigger($hook_name, [
            'hook_name'         => $hook_name,
            'is_backend'        => true,
            'admin'             => $admin,
            'admin_left_menu'   => &$admin_left_menu,
            'admin_power'       => &$admin_power,
            'admin_plugins'     => &$admin_plugins,
            'admin_all_plugins' => &$admin_all_plugins,
        ]);

        // 返回菜单和权限数据
        return [
            'admin_left_menu'   => $admin_left_menu,
            'admin_power'       => $admin_power,
            'admin_plugins'     => $admin_plugins,
            'admin_all_plugins' => $admin_all_plugins,
        ];
    }

    /**
     * 三级页面菜单数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-12-05
     * @desc    description
     */
    public static function PowerMenuThreeData()
    {
        // 小程序配置页面
        $appmini_type = MyConst('common_appmini_type');
        if(!empty($appmini_type) && is_array($appmini_type))
        {
            $appmini_type = array_values(array_map(function($item)
            {
                return ['name' => $item['name'], 'type' => $item['value']];
            }, $appmini_type));
        }

        // 页面对应的三级导航数据
        return [
            // 站点设置
            'site_index'          => MyLang('site.base_nav_list'),
            // 小程序配置
            'appmini_config'      => $appmini_type,
        ];
    }

    /**
     * 权限菜单读取
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-01
     * @desc    description
     * @param   [int]          $admin_id [管理员id]
     * @param   [int]          $role_id  [角色id]
     * @param   [int|array]    $pid      [父id]
     */
    public static function AdminPowerMenuData($admin_id, $role_id, $pid = 0)
    {
        if($admin_id == 1 || $role_id == 1)
        {
            $field = 'id,name,control,action,url,is_show,icon';
            $data = Db::name('Power')->where(['pid' => $pid])->field($field)->order('sort')->select()->toArray();
        } else {
            $field = 'p.id,p.name,p.control,p.action,p.url,p.is_show,p.icon';
            $data = Db::name('Power')->alias('p')->join('role_power rp', 'p.id=rp.power_id')->where(['rp.role_id' => $role_id, 'p.pid' => $pid])->field($field)->order('p.sort')->select()->toArray();
        }
        return empty($data) ? [] : $data;
    }

    /**
     * 权限数据导出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2025-09-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PowerExport($params = [])
    {
        // 一级
        $data = Db::name('Power')->where(['pid'=>0])->field('id,pid,name')->order('sort asc')->select()->toArray();
        if(!empty($data))
        {
            // 读取二级
            $two = Db::name('Power')->where(['pid'=>array_column($data, 'id')])->field('id,pid,name')->order('sort asc')->select()->toArray();
            if(!empty($two))
            {
                // 读取三级
                $three = Db::name('Power')->where(['pid'=>array_column($two, 'id')])->field('id,pid,name')->order('sort asc')->select()->toArray();
                if(!empty($three))
                {
                    // 三级处理
                    $three_group = [];
                    foreach($three as $threev)
                    {
                        if(!array_key_exists($threev['pid'], $three_group))
                        {
                            $three_group[$threev['pid']] = [];
                        }
                        $three_group[$threev['pid']][] = $threev;
                    }
                    // 加入到二级
                    foreach($two as $thk=>$thv)
                    {
                        $two[$thk]['items'] = (!empty($three_group) && array_key_exists($thv['id'], $three_group)) ? $three_group[$thv['id']] : [];
                    }
                }
                // 二级处理
                $two_group = [];
                foreach($two as $twv)
                {
                    if(!array_key_exists($twv['pid'], $two_group))
                    {
                        $two_group[$twv['pid']] = [];
                    }
                    $two_group[$twv['pid']][] = $twv;
                }
                // 加入到一级
                foreach($data as $k=>$v)
                {
                    $data[$k]['items'] = (!empty($two_group) && array_key_exists($v['id'], $two_group)) ? $two_group[$v['id']] : [];
                }
            }
            // 导出数据处理
            $result = [];
            foreach($data as $v)
            {
                $result[] = [
                    'id'          => $v['id'],
                    'one_name'    => $v['name'],
                    'two_name'    => '',
                    'three_name'  => '',
                ];
                if(!empty($v['items']))
                {
                    foreach($v['items'] as $vs)
                    {
                        $result[] = [
                            'id'          => $vs['id'],
                            'one_name'    => '',
                            'two_name'    => $vs['name'],
                            'three_name'  => '',
                        ];
                        if(!empty($vs['items']))
                        {
                            foreach($vs['items'] as $vss)
                            {
                                $result[] = [
                                    'id'          => $vss['id'],
                                    'one_name'    => '',
                                    'two_name'    => '',
                                    'three_name'  => $vss['name'],
                                ];
                            }
                        }
                    }
                }
            }
            // Excel驱动导出数据
            $title = MyLang('power.export_header_title');
            $export_title = [
                'id'            =>  [
                    'name' => $title['id'],
                    'type' => 'string',
                ],
                'one_name'      =>  [
                    'name' => $title['one_name'],
                    'type' => 'string',
                ],
                'two_name'      =>  [
                    'name' => $title['two_name'],
                    'type' => 'string',
                ],
                'three_name'    =>  [
                    'name' => $title['three_name'],
                    'type' => 'string',
                ],
            ];
            $excel = new \base\Excel(array('filename'=>MyLang('power.export_filename'), 'title'=>$export_title, 'data'=>$result, 'msg'=>MyLang('no_data')));
            return $excel->Export();
        }
        return DataReturn(MyLang('no_data'), -1);
    }

    /**
     * 后台菜单搜索数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    已授权左侧菜单，并反推不在权限表中的页面切换导航
     * @param   [array]          $menu [左侧菜单]
     */
    public static function MenuSearchList($menu = [])
    {
        $result = [];
        if(empty($menu) || !is_array($menu))
        {
            return $result;
        }
        $nav_map = self::MenuSearchPageNavMap();
        foreach($menu as $v)
        {
            self::MenuSearchItemHandle($result, $v, [], $nav_map);
        }
        self::MenuSearchConfigHandle($result);
        self::MenuSearchPluginsHandle($result);
        self::MenuSearchPaymentHandle($result);
        self::MenuSearchThemeHandle($result);
        self::MenuSearchDiyHandle($result);
        self::MenuSearchDesignHandle($result);
        return $result;
    }

    /**
     * 页面切换导航（不在权限表，挂在已授权父页面上）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     */
    public static function MenuSearchPageNavMap()
    {
        $map = [
            'exact'     => [
                'appconfig_index'   => [
                    ['param' => 'type', 'data' => MyLang('appconfig.base_nav_list')],
                ],
                'agreement_index'   => [
                    ['param' => 'type', 'data' => MyLang('agreement.base_nav_list')],
                ],
                'sms_index'         => [
                    ['param' => 'type', 'data' => MyLang('sms.base_nav_list')],
                ],
                'email_index'       => [
                    ['param' => 'type', 'data' => MyLang('email.base_nav_list')],
                ],
                'navigation_index'  => [
                    ['param' => 'type', 'data' => MyLang('navigation.base_nav_list')],
                ],
                'payment_index'     => [
                    ['param' => 'type', 'data' => MyLang('payment.base_nav_list')],
                ],
                'site_index_siteset'=> [
                    ['param' => 'view_type', 'data' => MyLang('site.siteset_nav_list')],
                ],
            ],
            'prefix'    => [
                'appmini_config_'   => [
                    ['param' => 'view_type', 'data' => MyLang('appmini.base_nav_list')],
                ],
            ],
        ];

        // 插件可追加页面切换导航
        $hook_name = 'plugins_service_admin_menu_search_nav';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$map,
        ]);
        return $map;
    }

    /**
     * 递归生成搜索项
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     * @param   [array]          $result   [结果]
     * @param   [array]          $item     [菜单项]
     * @param   [array]          $parents  [上级名称]
     * @param   [array]          $nav_map  [页面切换导航]
     */
    private static function MenuSearchItemHandle(&$result, $item, $parents, $nav_map)
    {
        if(empty($item) || !is_array($item))
        {
            return;
        }
        $name = isset($item['name']) ? trim($item['name']) : '';
        $url = empty($item['url']) ? '' : $item['url'];
        $key = '';
        if(isset($item['key']) && $item['key'] !== '')
        {
            $key = $item['key'];
        } elseif(isset($item['id']))
        {
            $key = $item['id'];
        }
        $key = (string) $key;
        $has_children = !empty($item['items']) && is_array($item['items']);
        if($name !== '' && $url !== '' && !$has_children)
        {
            $path = array_merge($parents, [$name]);
            $menu_id = isset($item['id']) ? (string) $item['id'] : '';
            $result[] = [
                'name'      => $name,
                'path'      => implode(' / ', $path),
                'url'       => $url,
                'key'       => $key,
                'menu_id'   => $menu_id,
                'keywords'  => implode(' ', $path),
            ];
            self::MenuSearchPageNavHandle($result, $key, $url, $path, $nav_map, $menu_id);
        }
        if($has_children)
        {
            $next_parents = ($name === '') ? $parents : array_merge($parents, [$name]);
            foreach($item['items'] as $child)
            {
                self::MenuSearchItemHandle($result, $child, $next_parents, $nav_map);
            }
        }
    }

    /**
     * 追加页面内切换导航
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     * @param   [array]          $result   [结果]
     * @param   [string]         $key      [父菜单key]
     * @param   [string]         $url      [父页面地址]
     * @param   [array]          $path     [父级路径]
     * @param   [array]          $nav_map  [页面切换导航]
     * @param   [string]         $menu_id  [左侧菜单选中id]
     */
    private static function MenuSearchPageNavHandle(&$result, $key, $url, $path, $nav_map, $menu_id = '')
    {
        $groups = [];
        if(!empty($nav_map['exact'][$key]) && is_array($nav_map['exact'][$key]))
        {
            $groups = $nav_map['exact'][$key];
        } elseif(!empty($nav_map['prefix']) && is_array($nav_map['prefix']))
        {
            foreach($nav_map['prefix'] as $prefix=>$prefix_groups)
            {
                if($prefix !== '' && strpos($key, $prefix) === 0 && is_array($prefix_groups))
                {
                    $groups = $prefix_groups;
                    break;
                }
            }
        }
        if(empty($groups))
        {
            return;
        }
        foreach($groups as $group)
        {
            if(empty($group['param']) || empty($group['data']) || !is_array($group['data']))
            {
                continue;
            }
            foreach($group['data'] as $nav)
            {
                if(empty($nav['name']) || !isset($nav['type']) || $nav['type'] === '')
                {
                    continue;
                }
                $nav_path = array_merge($path, [$nav['name']]);
                $parsed = self::MenuSearchUrlParse($url);
                $params = empty($parsed['params']) ? [] : $parsed['params'];
                $params[$group['param']] = $nav['type'];
                $nav_url = ($parsed['control'] === '') ? self::MenuSearchUrlAppend($url, [$group['param'] => $nav['type']]) : MyUrl('admin/'.$parsed['control'].'/'.$parsed['action'], $params);
                $result[] = [
                    'name'      => $nav['name'],
                    'path'      => implode(' / ', $nav_path),
                    'url'       => $nav_url,
                    'key'       => ($menu_id === '') ? $key.'_'.$nav['type'] : $menu_id,
                    'menu_id'   => $menu_id,
                    'keywords'  => implode(' ', $nav_path),
                ];
            }
        }
    }

    /**
     * 地址追加参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     * @param   [string]         $url     [地址]
     * @param   [array]          $params  [参数]
     */
    public static function MenuSearchUrlAppend($url, $params = [])
    {
        if(empty($url) || empty($params) || !is_array($params))
        {
            return $url;
        }
        $fragment = '';
        if(strpos($url, '#') !== false)
        {
            $fragment = strstr($url, '#');
            $url = strstr($url, '#', true);
        }
        $join = (strpos($url, '?') === false) ? '?' : '&';
        return $url.$join.http_build_query($params).$fragment;
    }

    /**
     * 配置项加入搜索（名称在语言包，页面在视图里）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     * @param   [array]          $result [菜单搜索结果]
     */
    private static function MenuSearchConfigHandle(&$result)
    {
        $fields = self::MenuSearchConfigFieldList();
        if(empty($fields) || empty($result))
        {
            return;
        }
        $indexed = [];
        foreach($result as $item)
        {
            if(empty($item['url']))
            {
                continue;
            }
            $indexed[] = [
                'item'      => $item,
                'parsed'    => self::MenuSearchUrlParse($item['url']),
            ];
        }
        $exists = [];
        foreach($fields as $field)
        {
            $best = null;
            $best_score = -1;
            $best_extra = 999;
            foreach($indexed as $row)
            {
                if($row['parsed']['control'] !== $field['control'] || $row['parsed']['action'] !== $field['action'])
                {
                    continue;
                }
                $cmp = self::MenuSearchParamMatch($row['parsed']['params'], $field['params'], $field['control']);
                if($cmp === null)
                {
                    continue;
                }
                if($cmp['score'] > $best_score || ($cmp['score'] == $best_score && $cmp['extra'] < $best_extra))
                {
                    $best = $row;
                    $best_score = $cmp['score'];
                    $best_extra = $cmp['extra'];
                }
            }
            if(empty($best))
            {
                continue;
            }
            $parent = $best['item'];
            $key = $parent['key'].'_cfg_'.$field['tag'];
            if(isset($exists[$key]))
            {
                continue;
            }
            $exists[$key] = 1;
            $url = MyUrl('admin/'.$field['control'].'/'.$field['action'], $field['params']);
            $result[] = [
                'name'      => $field['name'],
                'path'      => $parent['path'].' / '.$field['name'],
                'url'       => $url,
                'key'       => empty($parent['menu_id']) ? $key : $parent['menu_id'],
                'menu_id'   => empty($parent['menu_id']) ? '' : $parent['menu_id'],
                'keywords'  => $parent['path'].' '.$field['name'].' '.$field['desc'].' '.$field['tag'],
            ];
        }
    }

    /**
     * 已安装插件加入搜索（插件表有记录即已安装，未挂到左侧菜单也收录）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [array]          $result [菜单搜索结果]
     */
    private static function MenuSearchPluginsHandle(&$result)
    {
        $admin = AdminService::LoginInfo();
        if(empty($admin['id']))
        {
            return;
        }
        $admin_id = intval($admin['id']);
        $role_id = empty($admin['role_id']) ? 0 : intval($admin['role_id']);
        $is_super = ($admin_id == 1 || $role_id == 1);
        $role_power = [];
        if(!$is_super)
        {
            $rows = Db::name('RolePlugins')->where(['role_id'=>$role_id])->column('power', 'plugins');
            if(!empty($rows) && is_array($rows))
            {
                foreach($rows as $plugins=>$power)
                {
                    if(!is_array($power))
                    {
                        $power = empty($power) ? [] : json_decode($power, true);
                    }
                    $role_power[$plugins] = empty($power) || !is_array($power) ? [] : $power;
                }
            }
        }

        $installed = Db::name('Plugins')->column('plugins');
        if(empty($installed) || !is_array($installed))
        {
            return;
        }
        $exists = [];
        $menu_path = [];
        foreach($result as $item)
        {
            $mark = (isset($item['url']) ? $item['url'] : '').'|'.(isset($item['name']) ? $item['name'] : '');
            $exists[$mark] = 1;
            $menu_key = '';
            if(!empty($item['menu_id']))
            {
                $menu_key = (string) $item['menu_id'];
            } elseif(isset($item['key']))
            {
                $menu_key = (string) $item['key'];
            }
            if($menu_key !== '' && strpos($menu_key, 'plugins-') === 0 && !isset($menu_path[$menu_key]))
            {
                $menu_path[$menu_key] = isset($item['path']) ? $item['path'] : '';
            }
        }

        $group_name = MyLang('admin_power_menu_list.store_index.name');
        if($group_name === '' || $group_name === 'admin_power_menu_list.store_index.name')
        {
            $group_name = '';
        }
        foreach($installed as $plugins)
        {
            if($plugins === '' || (!$is_super && !array_key_exists($plugins, $role_power)))
            {
                continue;
            }
            $info = self::MenuSearchPluginName($plugins);
            if($info['name'] === '')
            {
                continue;
            }
            $menu_id = isset($menu_path['plugins-'.$plugins]) ? 'plugins-'.$plugins : '';
            $base_path = ($menu_id !== '') ? $menu_path[$menu_id] : (($group_name === '') ? $info['name'] : $group_name.' / '.$info['name']);
            $power_keys = self::MenuSearchPluginPowerKeys($plugins);
            $pages = [
                [
                    'name'      => $info['name'],
                    'control'   => 'admin',
                    'action'    => 'index',
                    'is_root'   => 1,
                ],
            ];
            foreach(self::MenuSearchPluginNavData($plugins) as $nav)
            {
                if(empty($nav['name']) || empty($nav['control']) || empty($nav['action']) || !is_string($nav['name']))
                {
                    continue;
                }
                $pages[] = [
                    'name'      => $nav['name'],
                    'control'   => $nav['control'],
                    'action'    => $nav['action'],
                    'is_root'   => 0,
                ];
            }
            foreach($pages as $page)
            {
                if(empty($page['is_root']) && !self::MenuSearchPluginPageAllow($plugins, $page['control'], $page['action'], $is_super, $role_power, $power_keys))
                {
                    continue;
                }
                $url = PluginsAdminUrl($plugins, $page['control'], $page['action']);
                $mark = $url.'|'.$page['name'];
                if(isset($exists[$mark]))
                {
                    continue;
                }
                $exists[$mark] = 1;
                $path = !empty($page['is_root']) ? $base_path : $base_path.' / '.$page['name'];
                $result[] = [
                    'name'      => $page['name'],
                    'path'      => $path,
                    'url'       => $url,
                    'key'       => ($menu_id === '') ? 'plugins-'.$plugins : $menu_id,
                    'menu_id'   => $menu_id,
                    'keywords'  => $path.' '.$info['name'].' '.$info['config_name'].' '.$info['desc'].' '.$plugins,
                ];
            }
        }
    }

    /**
     * 支付方式加入搜索（已安装/未安装分别跳转）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [array]          $result [菜单搜索结果]
     */
    private static function MenuSearchPaymentHandle(&$result)
    {
        if(!AdminIsPower('payment', 'index', null))
        {
            return;
        }
        $ret = PaymentService::PluginsPaymentList(null);
        if(empty($ret['data']) || !is_array($ret['data']))
        {
            return;
        }
        $parent = self::MenuSearchParentByControl($result, 'payment');
        $nav = MyLang('payment.base_nav_list');
        $nav_map = [];
        if(!empty($nav) && is_array($nav))
        {
            foreach($nav as $v)
            {
                if(isset($v['type']) && isset($v['name']))
                {
                    $nav_map[intval($v['type'])] = $v['name'];
                }
            }
        }
        $exists = [];
        foreach($result as $item)
        {
            $exists[(isset($item['url']) ? $item['url'] : '').'|'.(isset($item['name']) ? $item['name'] : '')] = 1;
        }
        foreach($ret['data'] as $row)
        {
            $name = empty($row['name']) ? '' : trim($row['name']);
            $payment = empty($row['payment']) ? '' : trim($row['payment']);
            if($name === '')
            {
                continue;
            }
            $type = (isset($row['is_install']) && $row['is_install'] == 1) ? 0 : 1;
            $url = MyUrl('admin/payment/index', ['type'=>$type]);
            $mark = $url.'|'.$name;
            if(isset($exists[$mark]))
            {
                continue;
            }
            $exists[$mark] = 1;
            $tab = isset($nav_map[$type]) ? $nav_map[$type] : '';
            $base = empty($parent['path']) ? (MyLang('payment.base_nav_title') ?: '支付方式') : $parent['path'];
            $path = ($tab === '') ? $base.' / '.$name : $base.' / '.$tab.' / '.$name;
            $result[] = [
                'name'      => $name,
                'path'      => $path,
                'url'       => $url,
                'key'       => empty($parent['menu_id']) ? 'payment_index' : $parent['menu_id'],
                'menu_id'   => empty($parent['menu_id']) ? '' : $parent['menu_id'],
                'keywords'  => $path.' '.$name.' '.$payment,
            ];
        }
    }

    /**
     * 主题加入搜索（统一进主题管理）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [array]          $result [菜单搜索结果]
     */
    private static function MenuSearchThemeHandle(&$result)
    {
        if(!AdminIsPower('themeadmin', 'index', null))
        {
            return;
        }
        $list = ThemeAdminService::ThemeAdminList();
        if(empty($list) || !is_array($list))
        {
            return;
        }
        $parent = self::MenuSearchParentByControl($result, 'themeadmin');
        $url = MyUrl('admin/themeadmin/index');
        $exists = [];
        foreach($result as $item)
        {
            $exists[(isset($item['url']) ? $item['url'] : '').'|'.(isset($item['name']) ? $item['name'] : '')] = 1;
        }
        $base = empty($parent['path']) ? (MyLang('admin_power_menu_list.websiteadmin_index.item.themeadmin_index') ?: '主题管理') : $parent['path'];
        foreach($list as $row)
        {
            $name = empty($row['name']) ? '' : trim(html_entity_decode($row['name'], ENT_QUOTES, 'UTF-8'));
            $theme = empty($row['theme']) ? '' : trim($row['theme']);
            if($name === '')
            {
                continue;
            }
            $mark = $url.'|'.$name;
            if(isset($exists[$mark]))
            {
                continue;
            }
            $exists[$mark] = 1;
            $path = $base.' / '.$name;
            $result[] = [
                'name'      => $name,
                'path'      => $path,
                'url'       => $url,
                'key'       => empty($parent['menu_id']) ? 'themeadmin_index' : $parent['menu_id'],
                'menu_id'   => empty($parent['menu_id']) ? '' : $parent['menu_id'],
                'keywords'  => $path.' '.$name.' '.$theme,
            ];
        }
    }

    /**
     * DIY 装修数据加入搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [array]          $result [菜单搜索结果]
     */
    private static function MenuSearchDiyHandle(&$result)
    {
        if(!AdminIsPower('diy', 'index', null))
        {
            return;
        }
        $list = Db::name('Diy')->field('id,name')->order('id desc')->select()->toArray();
        if(empty($list) || !is_array($list))
        {
            return;
        }
        $can_edit = AdminIsPower('diy', 'saveinfo', null);
        $parent = self::MenuSearchParentByControl($result, 'diy');
        $base = empty($parent['path']) ? (MyLang('admin_power_menu_list.app_index.item.diy_index') ?: 'DIY装修') : $parent['path'];
        $exists = [];
        foreach($result as $item)
        {
            $exists[(isset($item['url']) ? $item['url'] : '').'|'.(isset($item['name']) ? $item['name'] : '')] = 1;
        }
        foreach($list as $row)
        {
            $name = empty($row['name']) ? '' : trim($row['name']);
            $id = empty($row['id']) ? 0 : intval($row['id']);
            if($name === '' || $id <= 0)
            {
                continue;
            }
            $url = $can_edit ? MyUrl('admin/diy/saveinfo', ['id'=>$id]) : MyUrl('admin/diy/index');
            $mark = $url.'|'.$name;
            if(isset($exists[$mark]))
            {
                continue;
            }
            $exists[$mark] = 1;
            $path = $base.' / '.$name;
            $result[] = [
                'name'      => $name,
                'path'      => $path,
                'url'       => $url,
                'key'       => empty($parent['menu_id']) ? 'diy_'.$id : $parent['menu_id'],
                'menu_id'   => empty($parent['menu_id']) ? '' : $parent['menu_id'],
                'keywords'  => $path.' '.$name.' diy '.$id,
                'is_blank'  => $can_edit ? 1 : 0,
            ];
        }
    }

    /**
     * 页面设计数据加入搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [array]          $result [菜单搜索结果]
     */
    private static function MenuSearchDesignHandle(&$result)
    {
        if(!AdminIsPower('design', 'index', null))
        {
            return;
        }
        $list = Db::name('Design')->field('id,name')->order('id desc')->select()->toArray();
        if(empty($list) || !is_array($list))
        {
            return;
        }
        $can_edit = AdminIsPower('design', 'saveinfo', null);
        $parent = self::MenuSearchParentByControl($result, 'design');
        $base = empty($parent['path']) ? (MyLang('admin_power_menu_list.websiteadmin_index.item.design_index') ?: '页面设计') : $parent['path'];
        $exists = [];
        foreach($result as $item)
        {
            $exists[(isset($item['url']) ? $item['url'] : '').'|'.(isset($item['name']) ? $item['name'] : '')] = 1;
        }
        foreach($list as $row)
        {
            $name = empty($row['name']) ? '' : trim($row['name']);
            $id = empty($row['id']) ? 0 : intval($row['id']);
            if($name === '' || $id <= 0)
            {
                continue;
            }
            $url = $can_edit ? MyUrl('admin/design/saveinfo', ['id'=>$id]) : MyUrl('admin/design/index');
            $mark = $url.'|'.$name;
            if(isset($exists[$mark]))
            {
                continue;
            }
            $exists[$mark] = 1;
            $path = $base.' / '.$name;
            $result[] = [
                'name'      => $name,
                'path'      => $path,
                'url'       => $url,
                'key'       => empty($parent['menu_id']) ? 'design_'.$id : $parent['menu_id'],
                'menu_id'   => empty($parent['menu_id']) ? '' : $parent['menu_id'],
                'keywords'  => $path.' '.$name.' design '.$id,
                'is_blank'  => $can_edit ? 1 : 0,
            ];
        }
    }

    /**
     * 按控制器从已有搜索结果中找父菜单 path / menu_id
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [array]          $result  [菜单搜索结果]
     * @param   [string]         $control [控制器]
     */
    private static function MenuSearchParentByControl($result, $control)
    {
        $out = ['path'=>'', 'menu_id'=>''];
        if(empty($result) || !is_array($result) || $control === '')
        {
            return $out;
        }
        $control = strtolower($control);
        foreach($result as $item)
        {
            if(empty($item['url']))
            {
                continue;
            }
            $parsed = self::MenuSearchUrlParse($item['url']);
            if($parsed['control'] !== $control || $parsed['action'] !== 'index')
            {
                continue;
            }
            // 优先无额外 query 的列表页
            if(!empty($parsed['params']))
            {
                continue;
            }
            $out['path'] = empty($item['path']) ? '' : $item['path'];
            $out['menu_id'] = empty($item['menu_id']) ? (empty($item['key']) ? '' : (string) $item['key']) : (string) $item['menu_id'];
            return $out;
        }
        // 退化为任意同控制器 index
        foreach($result as $item)
        {
            if(empty($item['url']))
            {
                continue;
            }
            $parsed = self::MenuSearchUrlParse($item['url']);
            if($parsed['control'] !== $control || $parsed['action'] !== 'index')
            {
                continue;
            }
            $out['path'] = empty($item['path']) ? '' : $item['path'];
            $out['menu_id'] = empty($item['menu_id']) ? (empty($item['key']) ? '' : (string) $item['key']) : (string) $item['menu_id'];
            break;
        }
        return $out;
    }

    /**
     * 插件显示名称
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [string]         $plugins [插件标识]
     */
    private static function MenuSearchPluginName($plugins)
    {
        $name = MyLang('plugin_name', null, null, $plugins);
        if($name === '' || $name === 'plugin_name')
        {
            $name = '';
        }
        $config = PluginsAdminService::GetPluginsConfig($plugins);
        $config_name = (!empty($config['base']['name']) && is_string($config['base']['name'])) ? $config['base']['name'] : '';
        $desc = (!empty($config['base']['desc']) && is_string($config['base']['desc'])) ? $config['base']['desc'] : '';
        if($name === '')
        {
            $name = ($config_name === '') ? $plugins : $config_name;
        }
        return [
            'name'          => $name,
            'config_name'   => $config_name,
            'desc'          => $desc,
        ];
    }

    /**
     * 插件后台导航
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [string]         $plugins [插件标识]
     */
    private static function MenuSearchPluginNavData($plugins)
    {
        $class = 'app\\plugins\\'.$plugins.'\\service\\BaseService';
        if(!class_exists($class))
        {
            return [];
        }
        $list = [];
        if(method_exists($class, 'AdminNavMenuList'))
        {
            $ref = new \ReflectionMethod($class, 'AdminNavMenuList');
            if($ref->isPublic() && $ref->getNumberOfRequiredParameters() == 0)
            {
                try
                {
                    $list = $class::AdminNavMenuList();
                } catch(\Throwable $e) {
                    $list = [];
                }
            }
        }
        if(empty($list) && method_exists($class, 'AdminPowerMenu'))
        {
            $ref = new \ReflectionMethod($class, 'AdminPowerMenu');
            if($ref->isPublic() && $ref->getNumberOfRequiredParameters() == 0)
            {
                try
                {
                    $list = $class::AdminPowerMenu();
                } catch(\Throwable $e) {
                    $list = [];
                }
            }
        }
        return (empty($list) || !is_array($list)) ? [] : $list;
    }

    /**
     * 插件权限菜单标识
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [string]         $plugins [插件标识]
     */
    private static function MenuSearchPluginPowerKeys($plugins)
    {
        $keys = [];
        $menu = PluginsService::PluginsAdminPowerMenu($plugins);
        if(empty($menu) || !is_array($menu))
        {
            return $keys;
        }
        foreach($menu as $row)
        {
            if(empty($row['control']))
            {
                continue;
            }
            if(!empty($row['action']))
            {
                $keys[] = strtolower($row['control'].'-'.$row['action']);
            }
            if(!empty($row['item']) && is_array($row['item']))
            {
                foreach($row['item'] as $child)
                {
                    if(empty($child['action']))
                    {
                        continue;
                    }
                    $control = empty($child['control']) ? $row['control'] : $child['control'];
                    $keys[] = strtolower($control.'-'.$child['action']);
                }
            }
        }
        return $keys;
    }

    /**
     * 当前管理员是否可打开该插件页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [string]         $plugins    [插件标识]
     * @param   [string]         $control    [控制器]
     * @param   [string]         $action     [方法]
     * @param   [boolean]        $is_super   [是否超级管理员]
     * @param   [array]          $role_power [角色插件权限]
     * @param   [array]          $all_keys   [插件全部权限标识]
     */
    private static function MenuSearchPluginPageAllow($plugins, $control, $action, $is_super, $role_power, $all_keys)
    {
        if($is_super)
        {
            return true;
        }
        if(!array_key_exists($plugins, $role_power))
        {
            return false;
        }
        $power = $role_power[$plugins];
        if(empty($power) || !is_array($power))
        {
            return true;
        }
        $key = strtolower($control.'-'.$action);
        $power = array_map('strtolower', $power);
        if(in_array($key, $power, true))
        {
            return true;
        }
        return !in_array($key, $all_keys, true);
    }

    /**
     * 从配置页视图提取配置项
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     */
    private static function MenuSearchConfigFieldList()
    {
        static $fields = null;
        if($fields !== null)
        {
            return $fields;
        }
        $fields = [];
        $lang = MyLang('common_config');
        if(empty($lang) || !is_array($lang))
        {
            return $fields;
        }
        $root = APP_PATH.'admin'.DS.'view'.DS.'default'.DS;
        $dirs = ['site', 'appconfig', 'appmini', 'config', 'sms', 'email', 'agreement', 'seo'];
        foreach($dirs as $dir)
        {
            $path = $root.$dir;
            if(!is_dir($path))
            {
                continue;
            }
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS));
            foreach($iterator as $file)
            {
                if(!$file->isFile() || strtolower($file->getExtension()) !== 'html')
                {
                    continue;
                }
                $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root)));
                $route = self::MenuSearchViewRoute($relative);
                if(empty($route))
                {
                    continue;
                }
                $content = file_get_contents($file->getPathname());
                if($content === false || $content === '')
                {
                    continue;
                }
                $case = '';
                $panel_switch = '';
                $lines = preg_split("/\r\n|\n|\r/", $content);
                foreach($lines as $line)
                {
                    if(preg_match('/\{\{\s*case\s+([^}]+)\}\}/', $line, $case_match))
                    {
                        $case = trim($case_match[1]);
                    } elseif(strpos($line, '{{/case}}') !== false || strpos($line, '{{ /case }}') !== false)
                    {
                        $case = '';
                    }
                    // nav-content 内二级 tabs 面板（data-key，排除纯数字楼层等非 tab）
                    if(preg_match('/\bitem\b/', $line) && preg_match('/data-key=["\']([^"\']+)["\']/', $line, $key_match))
                    {
                        $panel_key = trim($key_match[1]);
                        if($panel_key !== '' && strpos($panel_key, '{{') === false && preg_match('/[a-zA-Z]/', $panel_key))
                        {
                            $panel_switch = $panel_key;
                        }
                    }
                    if(!preg_match_all('/\$data\.([a-zA-Z0-9_]+)/', $line, $tags))
                    {
                        continue;
                    }
                    foreach($tags[1] as $tag)
                    {
                        if(empty($lang[$tag]['name']))
                        {
                            continue;
                        }
                        $params = $route['params'];
                        $cases = ($case === '') ? [''] : preg_split('/\s*\|\s*/', $case);
                        foreach($cases as $case_value)
                        {
                            $item_params = $params;
                            if($case_value !== '' && !empty($route['switch_param']))
                            {
                                $item_params[$route['switch_param']] = $case_value;
                            } elseif($panel_switch !== '' && empty($route['switch_param']))
                            {
                                $item_params['switch'] = $panel_switch;
                            }
                            $unique = $route['control'].'|'.$route['action'].'|'.$tag.'|'.json_encode($item_params);
                            if(isset($fields[$unique]))
                            {
                                continue;
                            }
                            $fields[$unique] = [
                                'tag'       => $tag,
                                'name'      => $lang[$tag]['name'],
                                'desc'      => empty($lang[$tag]['desc']) ? '' : $lang[$tag]['desc'],
                                'control'   => $route['control'],
                                'action'    => $route['action'],
                                'params'    => $item_params,
                            ];
                        }
                    }
                }
                // 二级 tabs（nav_switch_btn）名称加入搜索
                foreach(self::MenuSearchNavSwitchTabList($content) as $tab)
                {
                    $item_params = $route['params'];
                    $item_params['switch'] = $tab['key'];
                    $tag = 'nav_tab_'.$tab['key'];
                    $unique = $route['control'].'|'.$route['action'].'|'.$tag.'|'.json_encode($item_params);
                    if(isset($fields[$unique]))
                    {
                        continue;
                    }
                    $fields[$unique] = [
                        'tag'       => $tag,
                        'name'      => $tab['name'],
                        'desc'      => '',
                        'control'   => $route['control'],
                        'action'    => $route['action'],
                        'params'    => $item_params,
                    ];
                }
            }
        }
        $fields = array_values($fields);
        return $fields;
    }

    /**
     * 解析视图中 nav_switch_btn 的 tabs（名称 + key）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [string]         $content [视图内容]
     */
    private static function MenuSearchNavSwitchTabList($content)
    {
        $result = [];
        if($content === '' || strpos($content, 'nav_switch_btn') === false)
        {
            return $result;
        }
        $matches = [];
        if(!preg_match_all("/'name'\s*=>\s*MyLang\(\s*'([^']+)'[^)]*\)\s*,\s*'key'\s*=>\s*'([^']+)'/s", $content, $matches, PREG_SET_ORDER))
        {
            preg_match_all("/'key'\s*=>\s*'([^']+)'\s*,\s*'name'\s*=>\s*MyLang\(\s*'([^']+)'[^)]*\)/s", $content, $matches, PREG_SET_ORDER);
            foreach($matches as &$row)
            {
                // 统一为 [lang_key, key]
                $tmp = $row[1];
                $row[1] = $row[2];
                $row[2] = $tmp;
            }
            unset($row);
        }
        $exists = [];
        foreach($matches as $row)
        {
            $lang_key = isset($row[1]) ? trim($row[1]) : '';
            $key = isset($row[2]) ? trim($row[2]) : '';
            if($lang_key === '' || $key === '' || isset($exists[$key]) || !preg_match('/[a-zA-Z]/', $key))
            {
                continue;
            }
            $name = MyLang($lang_key);
            if($name === '' || $name === $lang_key)
            {
                continue;
            }
            $exists[$key] = 1;
            $result[] = [
                'key'   => $key,
                'name'  => $name,
            ];
        }
        return $result;
    }

    /**
     * 配置视图对应后台地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     * @param   [string]         $relative [相对视图路径]
     */
    private static function MenuSearchViewRoute($relative)
    {
        if(preg_match('#^site/([^/]+)/([^/]+)\.html$#', $relative, $match))
        {
            return [
                'control'   => 'site',
                'action'    => 'index',
                'params'    => ['nav_type'=>$match[1], 'view_type'=>$match[2]],
            ];
        }
        if(preg_match('#^appconfig/([^/]+)\.html$#', $relative, $match))
        {
            return [
                'control'   => 'appconfig',
                'action'    => 'index',
                'params'    => ['type'=>$match[1]],
            ];
        }
        if(preg_match('#^(sms|email|agreement)/([^/]+)\.html$#', $relative, $match))
        {
            return [
                'control'   => $match[1],
                'action'    => 'index',
                'params'    => ['type'=>$match[2]],
            ];
        }
        if($relative === 'config/index.html')
        {
            return ['control'=>'config', 'action'=>'index', 'params'=>[]];
        }
        if($relative === 'config/store.html')
        {
            return ['control'=>'config', 'action'=>'store', 'params'=>[]];
        }
        if($relative === 'seo/index.html')
        {
            return ['control'=>'seo', 'action'=>'index', 'params'=>[]];
        }
        if($relative === 'appmini/config.html')
        {
            return [
                'control'       => 'appmini',
                'action'        => 'config',
                'params'        => [],
                'switch_param'  => 'nav_type',
            ];
        }
        return null;
    }

    /**
     * 解析后台地址中的控制器、方法和参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     * @param   [string]         $url [地址]
     */
    private static function MenuSearchUrlParse($url)
    {
        $result = ['control'=>'', 'action'=>'', 'params'=>[]];
        if(empty($url))
        {
            return $result;
        }
        $parts = parse_url($url);
        $query = [];
        if(!empty($parts['query']))
        {
            parse_str($parts['query'], $query);
        }
        $path = '';
        if(!empty($query['s']))
        {
            $path = $query['s'];
            unset($query['s']);
        } elseif(!empty($parts['path']))
        {
            $path = $parts['path'];
        }
        $path = preg_replace('/\.html$/', '', $path);
        $path = preg_replace('#^.*/admin\.php/?#', '', $path);
        $seg = array_values(array_filter(explode('/', $path), 'strlen'));
        if(!empty($seg) && $seg[0] === 'admin')
        {
            array_shift($seg);
        }
        $result['control'] = isset($seg[0]) ? strtolower($seg[0]) : '';
        $result['action'] = isset($seg[1]) ? strtolower($seg[1]) : 'index';
        $params = [];
        for($i = 2; $i + 1 < count($seg); $i += 2)
        {
            $params[$seg[$i]] = $seg[$i + 1];
        }
        foreach($query as $key=>$value)
        {
            if(is_array($value))
            {
                continue;
            }
            $params[$key] = $value;
        }
        $result['params'] = $params;
        return $result;
    }

    /**
     * 菜单参数是否覆盖配置页参数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    description
     * @param   [array]          $item_params  [菜单参数]
     * @param   [array]          $need_params  [配置页参数]
     * @param   [string]         $control      [控制器]
     */
    private static function MenuSearchParamMatch($item_params, $need_params, $control)
    {
        $defaults = [
            'site'          => ['nav_type'=>'base', 'view_type'=>'index'],
            'appconfig'     => ['type'=>'index'],
            'sms'           => ['type'=>'index'],
            'email'         => ['type'=>'index'],
            'agreement'     => ['type'=>'register'],
        ];
        $control_default = empty($defaults[$control]) ? [] : $defaults[$control];
        $score = 0;
        // switch 为页内二级 tabs，不在左侧菜单 url 上，仅用于打开时定位面板
        $skip_keys = ['switch'=>1];
        foreach($need_params as $key=>$value)
        {
            if(isset($skip_keys[$key]))
            {
                continue;
            }
            if(array_key_exists($key, $item_params))
            {
                if((string) $item_params[$key] !== (string) $value)
                {
                    return null;
                }
                $score++;
            } elseif(!isset($control_default[$key]) || (string) $control_default[$key] !== (string) $value)
            {
                return null;
            }
        }
        $extra = 0;
        foreach($item_params as $key=>$value)
        {
            if(!array_key_exists($key, $need_params))
            {
                $extra++;
            }
        }
        return ['score'=>$score, 'extra'=>$extra];
    }
}
?>