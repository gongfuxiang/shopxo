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
}
?>