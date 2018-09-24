<?php

namespace Service;

use Service\ResourcesService;

/**
 * 用户服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserService
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $where = (!empty($params['where']) && is_array($params['where'])) ? $params['where'] : [];
        $where['user_id'] = $params['user']['id'];
        $where['is_delete_time'] = 0;

        // 获取用户地址
        $field = 'id,alias,name,tel,province,city,county,address,lng,lat,is_default';
        $data = M('UserAddress')->where($where)->field($field)->order('id desc')->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['province_name'] = ResourcesService::RegionName(['region_id'=>$v['province']]);
                $v['city_name'] = ResourcesService::RegionName(['region_id'=>$v['city']]);
                $v['county_name'] = ResourcesService::RegionName(['region_id'=>$v['county']]);
            }
        }
        return DataReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * [UserAddressRow 获取地址详情]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-23T23:19:25+0800
     * @param    array                    $params [description]
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret);
        }

        // 获取用户地址
        $params['where'] = [
            'id'    => intval($params['id']),
        ];
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取用户地址
        $params['where'] = ['is_default'=>1];
        $ret = self::UserAddressList($params);
        if(!empty($ret['data'][0]))
        {
            $ret['data'] = $ret['data'][0];
        }
        return $ret;
    }

    /**
     * [UserAddressSave 用户地址保存]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-23T22:28:31+0800
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressSave($params = [])
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
                'key_name'          => 'county',
                'error_msg'         => '区/县不能为空',
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret);
        }

        $m = M('UserAddress');
        if(!empty($params['id']))
        {
            $where = ['user_id' => $params['user']['id'], 'id'=>$params['id']];
            $temp = $m->where($where)->find();
        }
        
        // 操作数据
        $is_default = intval(I('is_default', 0));
        $data = [
            'name'          => I('name', '', '', $params),
            'alias'         => I('alias', '', '', $params),
            'tel'           => I('tel', '', '', $params),
            'province'      => I('province', '', '', $params),
            'city'          => I('city', '', '', $params),
            'county'        => I('county', '', '', $params),
            'address'       => I('address', '', '', $params),
            'is_default'    => $is_default,
            'lng'           => floatval(I('lng')),
            'lat'           => floatval(I('lat')),
        ];

        // 默认地址处理
        if($is_default == 1)
        {
            $m->where(['user_id'=>$params['user']['id'], 'is_default'=>1])->save(['is_default'=>0]);
        }

        // 添加/更新数据
        if(empty($temp))
        {
            $data['user_id'] = $params['user']['id'];
            $data['add_time'] = time();
            if($m->add($data) > 0)
            {
                return DataReturn(L('common_operation_add_success'), 0);
            } else {
                return DataReturn(L('common_operation_add_error'));
            }
        } else {
            $data['upd_time'] = time();
            if($m->where($where)->save($data))
            {
                return DataReturn(L('common_operation_update_success'), 0);
            } else {
                return DataReturn(L('common_operation_update_error'));
            }
        }
    }

    /**
     * [UserAddressDelete 用户地址删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-23T23:55:51+0800
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret);
        }

        // 软删除数据
        $where = ['user_id' => $params['user']['id'], 'id'=>$params['id']];
        $data = ['is_delete_time' => time()];
        if(M('UserAddress')->where($where)->save($data))
        {
            return DataReturn(L('common_operation_delete_success'), 0);
        } else {
            return DataReturn(L('common_operation_delete_error'), -100);
        }
    }
}
?>