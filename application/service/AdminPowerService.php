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
 * 权限菜单服务层
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