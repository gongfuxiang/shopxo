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
                            if(!empty($three_data) && is_array($three_data) && !empty($three_data[$key]))
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
                    $admin_plugins = empty($plugins_data) ? [] : array_column($plugins_data, 'name', 'plugins');
                } else {
                    $admin_plugins = Db::name('RolePlugins')->where(['role_id'=>$role_id])->column('name', 'plugins');
                }
            }
            MyCache(SystemService::CacheKey('shopxo.cache_admin_left_menu_key').$admin_id, $admin_left_menu);
            MyCache(SystemService::CacheKey('shopxo.cache_admin_power_key').$admin_id, $admin_power);
            MyCache(SystemService::CacheKey('shopxo.cache_admin_power_plugins_key').$admin_id, $admin_plugins);
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
        ]);

        // 返回菜单和权限数据
        return [
            'admin_left_menu'   => $admin_left_menu,
            'admin_power'       => $admin_power,
            'admin_plugins'     => $admin_plugins,
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
}
?>