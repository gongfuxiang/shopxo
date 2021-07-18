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
        if(!empty($data))
        {
            // 获取对应权限数据
            $powers = [];
            $ids = array_column($data, 'id');
            $powers_data = Db::name('Role')->alias('r')->join('role_power rp', 'rp.role_id = r.id')->join('power p', 'rp.power_id = p.id')->where(['r.id'=>$ids])->field('rp.role_id, rp.power_id, p.name')->select()->toArray();
            if(!empty($powers_data))
            {
                foreach($powers_data as $p)
                {
                    $powers[$p['role_id']][] = $p['name'];
                }
            }
            // 是否存在超级管理角色组
            // 超级管理员数据库中并没存储关联关系，所以这里直接读取全部权限菜单
            if(in_array(1, $ids))
            {
                $powers[1] = Db::name('Power')->column('name');
            }
            
            // 循环处理数据
            foreach($data as &$v)
            {
                // 对应权限数据
                $v['items'] = array_key_exists($v['id'], $powers) ? $powers[$v['id']] : [];

                // 时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 角色总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-07
     * @desc    description
     * @param   [array]           $where [条件]
     */
    public static function RoleTotal($where = [])
    {
        return (int) Db::name('Role')->where($where)->count();
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
        $power = Db::name('Power')->field($power_field)->where(['pid'=>0])->order('sort')->select()->toArray();
        if(!empty($power))
        {
            foreach($power as &$v)
            {
                // 是否有权限
                $v['is_power'] = in_array($v['id'], $action) ? 'ok' : 'no';

                // 获取子权限
                $item =  Db::name('Power')->field($power_field)->where(array('pid'=>$v['id']))->order('sort')->select()->toArray();
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
            [
                'checked_type'      => 'unique',
                'key_name'          => 'name',
                'checked_data'      => 'Role',
                'checked_key'       => 'id',
                'error_msg'         => '角色已存在[{$var}]',
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
        $role_data = [
            'name'      =>  $params['name'],
            'is_enable' =>  isset($params['is_enable']) ? intval($params['is_enable']) : 0,
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
                return DataReturn('超级管理员角色不可编辑', -1);
            }

            // 更新
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
        AdminPowerService::PowerCacheDelete();

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
        if(empty($params['ids']))
        {
            return DataReturn('角色id有误', -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 是否包含删除超级管理员角色
        if(in_array(1, $params['ids']))
        {
            return DataReturn('超级管理员角色不可删除', -1);
        }

        // 开启事务
        Db::startTrans();

        // 删除角色
        if(Db::name('Role')->where(['id'=>$params['ids']])->delete() !== false && Db::name('RolePower')->where(['role_id'=>$params['ids']])->delete() !== false)
        {
            // 提交事务
            Db::commit();

            // 清除用户权限数据
            AdminPowerService::PowerCacheDelete();

            return DataReturn('删除成功', 0);
        }

        Db::rollback();
        return DataReturn('删除失败', -100);
    }
}
?>