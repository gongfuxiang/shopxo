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

/**
 * 权限服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AdminPowerService
{
    /**
     * 权限菜单列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function PowerList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);

        // 获取权限菜单列表
        $data = Db::name('Power')->field($field)->where($where)->order($order_by)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['item'] = Db::name('Power')->field($field)->where(['pid'=>$v['id']])->order($order_by)->select();
            }
        }
        return $data;
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
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '权限名称不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '2,16',
                'error_msg'         => '权限名称格式 2~16 个字符之间',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'control',
                'error_msg'         => '控制器名称不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'control',
                'checked_data'      => '1,30',
                'error_msg'         => '控制器名称格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'action',
                'error_msg'         => '方法名称不能为空',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'action',
                'checked_data'      => '1,30',
                'error_msg'         => '方法名称格式 1~30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'icon',
                'checked_data'      => '60',
                'is_checked'        => 1,
                'error_msg'         => '图标格式 0~30 个字符之间',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_show',
                'checked_data'      => [0,1],
                'error_msg'         => '是否显示范围值有误',
            ],
        ];
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
            'is_show'   => isset($params['is_show']) ? intval($params['is_show']) : 0,
        ];
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('Power')->insertGetId($data) > 0)
            {
                // 清除用户权限数据
                self::PowerCacheDelete();
                
                return DataReturn('添加成功', 0);
            }
            return DataReturn('添加失败', -100);
        } else {
            if(Db::name('Power')->where(['id'=>intval($params['id'])])->update($data) !== false)
            {
                // 清除用户权限数据
                self::PowerCacheDelete();

                return DataReturn('更新成功', 0);
            }
            return DataReturn('更新失败', -100);
        }
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
            return DataReturn('权限菜单id有误', -1);
        }

        if(Db::name('Power')->delete(intval($params['id'])))
        {
            // 清除用户权限数据
            self::PowerCacheDelete();

            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败', -100);
    }

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

        // 获取角色列表
        $data = Db::name('Role')->where($where)->order($order_by)->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 关联查询权限和角色数据
                if($v['id'] == 1)
                {
                    $v['item'] = Db::name('Power')->select();
                } else {
                    $v['item'] = Db::name('Role')->alias('r')->join(['__ROLE_POWER__'=>'rp'], 'rp.role_id = r.id')->join(['__POWER__'=>'p'], 'rp.power_id = p.id')->where(array('r.id'=>$v['id']))->field('p.id,p.name')->select();
                }
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
                'error_msg'         => '操作id有误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => '状态有误',
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
            return DataReturn('编辑成功');
        }
        return DataReturn('编辑失败或数据未改变', -100);
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
        $power = Db::name('Power')->field($power_field)->where(['pid'=>0])->order('sort')->select();
        if(!empty($power))
        {
            foreach($power as &$v)
            {
                // 是否有权限
                $v['is_power'] = in_array($v['id'], $action) ? 'ok' : 'no';

                // 获取子权限
                $item =  Db::name('Power')->field($power_field)->where(array('pid'=>$v['id']))->order('sort')->select();
                if(!empty($item))
                {
                    foreach($item as $ks=>$vs)
                    {
                        $item[$ks]['is_power'] = in_array($vs['id'], $action) ? 'ok' : 'no';
                    }
                    $v['item'] = $item;
                }
            }
        }
        return $power;
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
                'error_msg'         => '角色名称格式 2~8 个字符之间',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开启事务
        Db::startTrans();

        // 角色数据更新
        $role_data = array(
                'name'      =>  $params['name'],
                'is_enable' =>  isset($params['is_enable']) ? intval($params['is_enable']) : 0,
            );
        if(empty($params['id']))
        {
            $role_data['add_time'] = time();
            $role_id = Db::name('Role')->insertGetId($role_data);
        } else {
            if(Db::name('Role')->where(['id'=>$params['id']])->update($role_data) !== false)
            {
                $role_id = $params['id'];
            }
        }
        if(empty($role_id))
        {
            Db::rollback();
            return DataReturn('角色数据保存失败', -2);
        }

        // 权限关联数据删除
        if(Db::name('RolePower')->where(['role_id'=>$role_id])->delete() === false)
        {
            Db::rollback();
            return DataReturn('角色权限操作失败', -3);
        }

        // 权限关联数据添加
        if(!empty($params['power_id']))
        {
            $rp_data = [];
            foreach(explode(',', $params['power_id']) as $power_id)
            {
                if(!empty($power_id))
                {
                    $rp_data[] = [
                            'role_id'   =>  $role_id,
                            'power_id'  =>  $power_id,
                            'add_time'  =>  time(),
                        ];
                }
            }
            if(!empty($rp_data))
            {
                if(Db::name('RolePower')->insertAll($rp_data) < count($rp_data))
                {
                    Db::rollback();
                    return DataReturn('角色权限添加失败', -10);
                }
            }
        }

        // 提交事务
        Db::commit();

        // 清除用户权限数据
        self::PowerCacheDelete();

        return DataReturn('操作成功', 0);
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
        if(empty($params['id']))
        {
            return DataReturn('角色id有误', -1);
        }

        // 开启事务
        Db::startTrans();

        // 删除角色
        if(Db::name('Role')->delete(intval($params['id'])) !== false && Db::name('RolePower')->where(['role_id'=>intval($params['id'])])->delete() !== false)
        {
            // 提交事务
            Db::commit();

            // 清除用户权限数据
            self::PowerCacheDelete();

            return DataReturn('删除成功', 0);
        }

        Db::rollback();
        return DataReturn('删除失败', -100);
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
                cache(config('cache_admin_power_key').$id, null);
                cache(config('cache_admin_left_menu_key').$id, null);
            }
        }
    }

    /**
     * 管理员权限菜单初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-06
     * @desc    description
     */
    public static function PowerMenuInit()
    {
        // 基础参数
        $admin = session('admin');
        $admin_id = isset($admin['id']) ? intval($admin['id']) : 0;
        $role_id = isset($admin['role_id']) ? intval($admin['role_id']) : 0;

        // 读取缓存数据
        $admin_left_menu = cache(config('cache_admin_left_menu_key').$admin_id);
        $admin_power = cache(config('cache_admin_power_key').$admin_id);

        // 缓存没数据则从数据库重新读取
        if(($role_id > 0 || $admin_id == 1) && empty($admin_left_menu))
        {
            // 获取一级数据
            if($admin_id == 1 || $role_id == 1)
            {
                $field = 'id,name,control,action,is_show,icon';
                $admin_left_menu = Db::name('Power')->where(array('pid' => 0))->field($field)->order('sort')->select();
            } else {
                $field = 'p.id,p.name,p.control,p.action,p.is_show,p.icon';
                $admin_left_menu = Db::name('Power')->alias('p')->join(['__ROLE_POWER__'=>'rp'], 'p.id=rp.power_id')->where(array('rp.role_id' => $role_id, 'p.pid' => 0))->field($field)->order('p.sort')->select();
            }
            
            // 有数据，则处理子级数据
            if(!empty($admin_left_menu))
            {
                foreach($admin_left_menu as $k=>$v)
                {
                    // 权限
                    $admin_power[$v['id']] = strtolower($v['control'].'_'.$v['action']);

                    // 获取子权限
                    if($admin_id == 1 || $role_id == 1)
                    {
                        $items = Db::name('Power')->where(array('pid' => $v['id']))->field($field)->order('sort')->select();
                    } else {
                        $items = Db::name('Power')->alias('p')->join(['__ROLE_POWER__'=>'rp'], 'p.id=rp.power_id')->where(array('rp.role_id' => $role_id, 'p.pid' => $v['id']))->field($field)->order('p.sort')->select();
                    }

                    // 权限列表
                    $is_show_parent = $v['is_show'];
                    if(!empty($items))
                    {
                        foreach($items as $ks=>$vs)
                        {
                            // 权限
                            $admin_power[$vs['id']] = strtolower($vs['control'].'_'.$vs['action']);

                            // 是否显示视图
                            if($vs['is_show'] == 0)
                            {
                                unset($items[$ks]);
                            }
                        }

                        // 如果存在子级数据，但是子级无显示项、则父级也不显示
                        if(empty($items))
                        {
                            $is_show_parent = 0;
                        }
                    }

                    // 是否显示视图
                    if($is_show_parent == 1)
                    {
                        // 子级
                        $admin_left_menu[$k]['items'] = $items;
                    } else {
                        unset($admin_left_menu[$k]);
                    }
                }
            }
            cache(config('cache_admin_left_menu_key').$admin_id, $admin_left_menu);
            cache(config('cache_admin_power_key').$admin_id, $admin_power);
        }
    }
}
?>