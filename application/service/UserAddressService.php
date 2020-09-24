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
use think\facade\Hook;
use app\service\RegionService;

/**
 * 用户地址服务层
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-21
 * @desc    description
 */
class UserAddressService
{
    /**
     * 用户地址列表列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $where = (!empty($params['where']) && is_array($params['where'])) ? $params['where'] : [];
        $where['user_id'] = $params['user']['id'];
        $where['is_delete_time'] = 0;

        // 获取用户地址
        $field = 'id,alias,name,tel,province,city,county,address,lng,lat,is_default';
        $data = Db::name('UserAddress')->where($where)->field($field)->order('id desc')->select();
        if(!empty($data))
        {
            $is_default = false;
            foreach($data as &$v)
            {
                // 地区
                $v['province_name'] = RegionService::RegionName($v['province']);
                $v['city_name'] = RegionService::RegionName($v['city']);
                $v['county_name'] = RegionService::RegionName($v['county']);

                // 是否有默认地址
                if($is_default === false && $v['is_default'] == 1)
                {
                    $is_default = true;
                }
            }

            // 是否处理默认地址,没有默认地址将第一个设置为默认地址
            $is_default_handle = isset($params['is_default_handle']) ? intval($params['is_default_handle']) : 1;
            if($is_default === false && $is_default_handle == 1)
            {
                $data[0]['is_default'] = true;
            }
        }

        // 用户地址列表钩子
        $hook_name = 'plugins_service_user_address_list';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
            'user_id'       => $params['user']['id'],
        ]);

        return DataReturn('操作成功', 0, $data);
    }

    /**
     * 获取地址详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressRow($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '地址id不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取用户地址
        $params['where'] = [
            'id'    => intval($params['id']),
        ];
        $params['is_default_handle'] = 0;
        $ret = self::UserAddressList($params);
        if(!empty($ret['data'][0]))
        {
            $ret['data'] = $ret['data'][0];
        }
        return $ret;
    }

    /**
     * 用户默认地址
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserDefaultAddress($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取用户地址
        $params['where'] = empty($params['where']) ? ['is_default'=>1] : $params['where'];
        $ret = self::UserAddressList($params);
        $data = [];
        if(!empty($ret['data'][0]))
        {
            $data = $ret['data'][0];
        } else {
            // 没有默认地址则读取第一条作为默认地址
            unset($params['where']);
            $ret = self::UserAddressList($params);
            if(!empty($ret['data'][0]))
            {
                $data = $ret['data'][0];
            }
        }

        // 用户默认地址钩子
        $hook_name = 'plugins_service_user_address_default_row';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
            'user_id'       => $params['user']['id'],
        ]);

        return DataReturn('获取成功', 0, $data);
    }

    /**
     * 用户地址保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressSave($params = [])
    {
        // 参数校验
        $ret = self::UserAddressSaveParamsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        if(!empty($params['id']))
        {
            $where = ['user_id' => $params['user']['id'], 'id'=>$params['id']];
            $temp = Db::name('UserAddress')->where($where)->find();
        }
        
        // 操作数据
        $is_default = isset($params['is_default']) ? intval($params['is_default']) : 0;
        $data = [
            'name'          => $params['name'],
            'tel'           => $params['tel'],
            'province'      => intval($params['province']),
            'city'          => intval($params['city']),
            'county'        => isset($params['county']) ? intval($params['county']) : 0,
            'address'       => $params['address'],
            'is_default'    => $is_default,
        ];
        if(!empty($params['alias']))
        {
            $data['alias'] = $params['alias'];
        }
        if(!empty($params['lng']))
        {
            $data['lng'] = floatval($params['lng']);
        }
        if(!empty($params['lat']))
        {
            $data['lat'] = floatval($params['lat']);
        }

        // 用户地址保存前钩子
        $hook_name = 'plugins_service_user_address_save_begin';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
            'user_id'       => $params['user']['id'],
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        Db::startTrans();

        // 默认地址处理
        if($is_default == 1)
        {
            Db::name('UserAddress')->where(['user_id'=>$params['user']['id'], 'is_default'=>1])->update(['is_default'=>0]);
        }

        // 添加/更新数据
        $status = false;
        $msg = '操作失败';
        if(empty($temp))
        {
            $data['user_id'] = $params['user']['id'];
            $data['add_time'] = time();
            if(Db::name('UserAddress')->insertGetId($data) > 0)
            {
                $status = true;
                $msg = '新增成功';
            } else {
                $msg = '新增失败';
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('UserAddress')->where($where)->update($data))
            {
                $status = true;
                $msg = '更新成功';
            } else {
                $msg = '更新失败';
            }
        }

        // 事务处理
        if($status)
        {
            Db::commit();
        } else {
            Db::rollback();
        }

        // 用户地址保存后钩子
        $hook_name = 'plugins_service_user_address_save_end';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => $data,
            'user_id'       => $params['user']['id'],
        ]);

        return DataReturn($msg, $status ? 0 : -100);
    }

    /**
     * 地址保存参数校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-21
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private static function UserAddressSaveParamsCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '姓名不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'tel',
                'error_msg'         => '联系电话不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'province',
                'error_msg'         => '省不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'city',
                'error_msg'         => '城市不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'address',
                'error_msg'         => '详细地址不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        return DataReturn('success', 0);
    }

    /**
     * 用户地址删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '地址id不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 软删除数据
        $where = ['user_id' => $params['user']['id'], 'id'=>$params['id']];
        $data = ['is_delete_time' => time()];
        if(Db::name('UserAddress')->where($where)->update($data))
        {
            // 用户地址删除钩子
            $hook_name = 'plugins_service_user_address_delete';
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data_id'       => $params['id'],
                'user_id'       => $params['user']['id'],
            ]);

            return DataReturn('删除成功', 0);
        } else {
            return DataReturn('删除失败或资源不存在', -100);
        }
    }

    /**
     * 用户地址设置默认地址
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-25
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressDefault($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '地址id不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开启事务
        Db::startTrans();

        // 先全部设置为0 再将当前设置为1
        $all_status = Db::name('UserAddress')->where(['user_id' => $params['user']['id']])->update(['is_default'=>0]);
        $my_status = Db::name('UserAddress')->where(['user_id' => $params['user']['id'], 'id'=>$params['id']])->update(['is_default'=>1]);
        if($all_status !== false && $my_status)
        {
            // 用户地址删除钩子
            $hook_name = 'plugins_service_user_address_default';
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data_id'       => $params['id'],
                'user_id'       => $params['user']['id'],
            ]);

            // 提交事务
            Db::commit();
            return DataReturn('设置成功', 0);
        } else {
            // 回滚事务
            Db::rollback();
            return DataReturn('设置失败', -100);
        }
    }

    /**
     * 外部系统地址添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-21
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function OutSystemUserAddressAdd($params = [])
    {
        // 参数校验
        $ret = self::UserAddressSaveParamsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 省市区解析
        $province_name = $params['province'];
        $city_name = $params['city'];
        $county_name = isset($params['county']) ? $params['county'] : '';
        $town_name = isset($params['town']) ? $params['town'] : '';

        // 开始匹配地址
        $field = 'id,name,level';

        // 省匹配
        $search = ['市', '区'];
        $where = [
            ['pid', '=', 0],
            ['level', '=', 1],
            ['name', 'like', str_replace($search, '', $province_name).'%'],
        ];
        $province = Db::name('Region')->where($where)->field($field)->find();
        if(empty($province))
        {
            return DataReturn('省匹配失败', -1);
        }

        // 市匹配
        $where = [
            ['pid', '=', $province['id']],
            ['level', '=', 2],
        ];
        $search = ['自治州', '自治县', '特别行政区', '地区', '市', '县', '区'];
        $where1 = $where;
        $where1[] = ['name', 'like', str_replace($search, '', $city_name).'%'];
        $city = Db::name('Region')->where($where1)->field($field)->find();
        if(empty($city))
        {
            // 没查询到则使用县字段查询
            $where2 = $where;
            $where2[] = ['name', 'like', str_replace($search, '', $county_name).'%'];
            $city = Db::name('Region')->where($where2)->field($field)->find();
            if(empty($city))
            {
                return DataReturn('市匹配失败', -1);
            }
        }

        // 区/县匹配
        $where = [
            ['pid', '=', $city['id']],
            ['level', '=', 3],
        ];
        $search = ['街道', '县', '镇'];
        $where1 = $where;
        $where1[] = ['name', 'like', str_replace($search, '', $county_name).'%'];
        $county = Db::name('Region')->where($where1)->field($field)->find();
        if(empty($county) && !empty($town_name))
        {
            // 没查询到则使用街道字段查询
            $where2 = $where;
            $where2[] = ['name', 'like', str_replace($search, '', $town_name).'%'];
            $county = Db::name('Region')->where($where2)->field($field)->find();
        }

        // 地区id赋值
        $params['province'] = $province['id'];
        $params['city'] = $city['id'];
        $params['county'] = empty($county['id']) ? 0 : $county['id'];

        // 存在街道字段数据则拼接到详细地址前面
        if(!empty($town_name))
        {
            $params['address'] = $town_name.$params['address'];
        }

        // 地址存在则不重复添加
        $where = [
            'name'              => $params['name'],
            'tel'               => $params['tel'],
            'province'          => $params['province'],
            'city'              => $params['city'],
            'county'            => $params['county'],
            'address'           => $params['address'],
            'user_id'           => $params['user']['id'],
            'is_delete_time'    => 0,
        ];
        $address = Db::name('UserAddress')->where($where)->find();
        if(!empty($address))
        {
            return DataReturn('地址已存在', -1);
        }

        // 地址保存
        return self::UserAddressSave($params);
    }
}
?>