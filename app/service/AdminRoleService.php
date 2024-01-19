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
use app\service\AdminPowerService;
use app\service\PluginsAdminService;

/**
 * 角色服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AdminRoleService
{
    /**
     * 角色列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RoleList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取角色列表
        $data = Db::name('Role')->field($field)->where($where)->order($order_by)->limit($m, $n)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::RoleListHandle($data, $params));
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-01
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function RoleListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 获取对应菜单权限数据
            $power_list = [];
            $ids = array_column($data, 'id');
            $powers_data = Db::name('Role')->alias('r')->join('role_power rp', 'rp.role_id = r.id')->join('power p', 'rp.power_id = p.id')->where(['r.id'=>$ids])->field('rp.role_id, rp.power_id, p.name')->select()->toArray();
            if(!empty($powers_data))
            {
                foreach($powers_data as $p)
                {
                    $power_list[$p['role_id']][] = $p['name'];
                }
            }

            // 获取插件权限
            $power_plugins_list = [];
            $powers_data = Db::name('Role')->alias('r')->join('role_plugins rp', 'rp.role_id = r.id')->where(['r.id'=>$ids])->field('rp.role_id, rp.plugins, rp.name')->select()->toArray();
            if(!empty($powers_data))
            {
                foreach($powers_data as $p)
                {
                    $power_plugins_list[$p['role_id']][] = $p['name'];
                }
            }

            // 是否存在超级管理角色组
            // 超级管理员数据库中并没存储关联关系，所以这里直接读取全部权限菜单
            if(in_array(1, $ids))
            {
                // 全部菜单
                $power_list[1] = Db::name('Power')->column('name');

                // 全部插件
                $plugins_data = self::PluginsList();
                $power_plugins_list[1] = empty($plugins_data) ? [] : array_column($plugins_data, 'name');
            }
            
            // 循环处理数据
            foreach($data as &$v)
            {
                // 对应菜单权限数据
                $v['power'] = array_key_exists($v['id'], $power_list) ? $power_list[$v['id']] : [];

                // 对应插件权限数据
                $v['power_plugins'] = array_key_exists($v['id'], $power_plugins_list) ? $power_plugins_list[$v['id']] : [];

                // 时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return $data;
    }

    /**
     * 角色状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RoleStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
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

        // 数据更新
        if(Db::name('Role')->where(['id'=>intval($params['id'])])->update(['is_enable'=>intval($params['state'])]))
        {
            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
    }

    /**
     * 权限菜单编辑列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RolePowerEditData($params = [])
    {
        // 当前角色关联的所有菜单id
        $action = empty($params['role_id']) ? [] : Db::name('RolePower')->where(['role_id'=>$params['role_id']])->column('power_id');
        // 权限列表
        $power_field = 'id,name,is_show';
        $power = Db::name('Power')->field($power_field)->where(['pid'=>0])->order('sort')->select()->toArray();
        if(!empty($power))
        {
            foreach($power as &$v)
            {
                // 是否有权限
                $v['is_power'] = in_array($v['id'], $action) ? 'ok' : 'no';

                // 获取子权限
                $item = Db::name('Power')->field($power_field)->where(array('pid'=>$v['id']))->order('sort')->select()->toArray();
                if(!empty($item))
                {
                    foreach($item as $ks=>$vs)
                    {
                        // 是否有权限
                        $item[$ks]['is_power'] = in_array($vs['id'], $action) ? 'ok' : 'no';

                        // 获取三级
                        $items = Db::name('Power')->field($power_field)->where(array('pid'=>$vs['id']))->order('sort')->select()->toArray();
                        if(!empty($items))
                        {
                            foreach($items as $kss=>$vss)
                            {
                                // 是否有权限
                                $items[$kss]['is_power'] = in_array($vss['id'], $action) ? 'ok' : 'no';
                            }
                            // 放入二级级数据中
                            $item[$ks]['item'] = $items;
                        }
                    }
                    // 放入一级数据中
                    $v['item'] = $item;
                }
            }
        }

        // 插件权限
        $plugins = [];
        $action = empty($params['role_id']) ? [] : Db::name('RolePlugins')->where(['role_id'=>$params['role_id']])->column('plugins');
        // 插件列表
        $plugins_data = self::PluginsList();
        if(!empty($plugins_data))
        {
            foreach($plugins_data as $pv)
            {
                if(!empty($pv['plugins']) && !empty($pv['name']) && !empty($pv['logo']))
                {
                    $plugins[] = [
                        'plugins'   => $pv['plugins'],
                        'name'      => $pv['name'],
                        'logo'      => $pv['logo'],
                        'is_power'  => (empty($action) || !in_array($pv['plugins'], $action)) ? 'no' : 'ok',
                    ];
                }
            }
        }

        return [
            'power'     => $power,
            'plugins'   => $plugins,
        ];
    }

    /**
     * 角色保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-07T00:24:14+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RoleSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '角色名称不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,8',
                'error_msg'         => MyLang('common_service.role.form_item_name_message'),
            ],
            [
                'checked_type'      => 'unique',
                'key_name'          => 'name',
                'checked_data'      => 'Role',
                'checked_key'       => 'id',
                'error_msg'         => MyLang('common_service.role.save_role_already_exist_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 角色数据更新
            $role_data = [
                'name'      => $params['name'],
                'is_enable' => isset($params['is_enable']) ? intval($params['is_enable']) : 0,
                'upd_time'  => time(),
            ];

            // 不存在添加，则更新
            if(empty($params['id']))
            {
                $role_data['add_time'] = time();
                $role_id = Db::name('Role')->insertGetId($role_data);
            } else {
                // 是否包含删除超级管理员角色
                if($params['id'] == 1)
                {
                    throw new \Exception(MyLang('common_service.role.save_super_role_not_edit_tips'));
                }

                // 更新
                if(Db::name('Role')->where(['id'=>$params['id']])->update($role_data) !== false)
                {
                    $role_id = $params['id'];
                }
            }
            if(empty($role_id))
            {
                throw new \Exception(MyLang('common_service.role.save_role_save_fail_tips'));
            }

            // 菜单权限数据删除
            if(Db::name('RolePower')->where(['role_id'=>$role_id])->delete() === false)
            {
                throw new \Exception(MyLang('common_service.role.save_role_menu_empty_fail_tips'));
            }

            // 菜单权限数据添加
            if(!empty($params['power_id']))
            {
                $rp_data = [];
                foreach(explode(',', $params['power_id']) as $power_id)
                {
                    if(!empty($power_id))
                    {
                        $rp_data[] = [
                            'role_id'   => $role_id,
                            'power_id'  => $power_id,
                            'add_time'  => time(),
                        ];
                    }
                }
                if(!empty($rp_data))
                {
                    if(Db::name('RolePower')->insertAll($rp_data) < count($rp_data))
                    {
                        throw new \Exception(MyLang('common_service.role.save_role_menu_add_fail_tips'));
                    }
                }
            }

            // 插件权限删除
            if(Db::name('RolePlugins')->where(['role_id'=>$role_id])->delete() === false)
            {
                throw new \Exception(MyLang('common_service.role.save_role_plugins_empty_fail_tips'));
            }

            // 插件权限数据添加
            if(!empty($params['plugins']))
            {
                $plugins_data = self::PluginsList();
                $plugins_list = empty($plugins_data) ? [] : array_column($plugins_data, null, 'plugins');
                $rp_data = [];
                foreach(explode(',', $params['plugins']) as $plugins)
                {
                    if(!empty($plugins) && array_key_exists($plugins, $plugins_list))
                    {
                        $rp_data[] = [
                            'role_id'   => $role_id,
                            'name'      => $plugins_list[$plugins]['name'],
                            'plugins'   => $plugins,
                            'add_time'  => time(),
                        ];
                    }
                }
                if(!empty($rp_data))
                {
                    if(Db::name('RolePlugins')->insertAll($rp_data) < count($rp_data))
                    {
                        throw new \Exception(MyLang('common_service.role.save_role_plugins_add_fail_tips'));
                    }
                }
            }

            // 提交事务
            Db::commit();

            // 清除用户权限数据
            AdminPowerService::PowerCacheDelete();

            return DataReturn(MyLang('operate_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 角色删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-07T00:24:14+0800
     * @param    [array]          $params [输入参数]
     */
    public static function RoleDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 是否包含删除超级管理员角色
        if(in_array(1, $params['ids']))
        {
            return DataReturn(MyLang('common_service.role.delete_super_role_not_tips'), -1);
        }

        // 开启事务
        Db::startTrans();

        // 删除角色
        if(Db::name('Role')->where(['id'=>$params['ids']])->delete() !== false && Db::name('RolePower')->where(['role_id'=>$params['ids']])->delete() !== false && Db::name('RolePlugins')->where(['role_id'=>$params['ids']])->delete() !== false)
        {
            // 提交事务
            Db::commit();

            // 清除用户权限数据
            AdminPowerService::PowerCacheDelete();

            return DataReturn(MyLang('delete_success'), 0);
        }

        Db::rollback();
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 获取插件列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-01-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PluginsList($params = [])
    {
        $result = [];
        $res = PluginsAdminService::PluginsList();
        if(!empty($res['data']))
        {
            // 已安装的插件
            if(!empty($res['data']['db_data']))
            {
                $result = $res['data']['db_data'];
            }

            // 未安装的插件
            if(!empty($res['data']['dir_data']))
            {
                $result = array_merge($result, $res['data']['dir_data']);
            }
        }
        return $result;
    }
}
?>