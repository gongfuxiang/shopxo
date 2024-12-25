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
use app\service\RegionService;
use app\service\ResourcesService;

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
     * 列表-管理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserAddressAdminList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取列表
        $data = Db::name('UserAddress')->where($where)->order($order_by)->limit($m, $n)->select()->toArray();
        return DataReturn(MyLang('handle_success'), 0, self::UserAddressListHandle($data), $params);
    }

    /**
     * 删除-管理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressAdminDelete($params = [])
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

        // 删除操作
        if(Db::name('UserAddress')->where(['id'=>$params['ids']])->delete())
        {
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 数据列表处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-14
     * @desc    description
     * @param   [array]          $data   [数据列表]
     * @param   [array]          $params [输入参数]
     */
    public static function UserAddressListHandle($data, $params = [])
    {
        if(!empty($data))
        {
            // 地区
            $region_ids = array_unique(array_merge(array_column($data, 'province'), array_column($data, 'city'), array_column($data, 'county')));
            $region = empty($region_ids) ? [] : RegionService::RegionName($region_ids);

            // 是否公共方法
            $is_public = !isset($params['is_public']) ? 1 : intval($params['is_public']);

            $users = [];
            foreach($data as &$v)
            {
                // 用户信息
                if(isset($v['user_id']) && $is_public == 0)
                {
                    if(!array_key_exists($v['user_id'], $users))
                    {
                        $users[$v['user_id']] = UserService::GetUserViewInfo($v['user_id']);
                    }
                    $v['user'] =  $users[$v['user_id']];
                }

                // 地区
                $v['province_name'] = (!empty($v['province']) && !empty($region) && array_key_exists($v['province'], $region)) ? $region[$v['province']] : '';
                $v['city_name'] = (!empty($v['city']) && !empty($region) && array_key_exists($v['city'], $region)) ? $region[$v['city']] : '';
                $v['county_name'] = (!empty($v['county']) && !empty($region) && array_key_exists($v['county'], $region)) ? $region[$v['county']] : '';

                // 附件
                if(isset($v['idcard_front']))
                {
                    $v['idcard_front_old'] = $v['idcard_front'];
                    $v['idcard_front'] =  ResourcesService::AttachmentPathViewHandle($v['idcard_front']);
                }
                if(isset($v['idcard_back']))
                {
                    $v['idcard_back_old'] = $v['idcard_back'];
                    $v['idcard_back'] =  ResourcesService::AttachmentPathViewHandle($v['idcard_back']);
                }

                // 时间
                if(isset($v['add_time']))
                {
                    $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                }
                if(isset($v['upd_time']))
                {
                    $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
                }
            }
        }
        return $data;
    }

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
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $where = (!empty($params['where']) && is_array($params['where'])) ? $params['where'] : [];
        $where['user_id'] = $params['user']['id'];

        // 获取用户地址
        $field = 'id,alias,name,tel,province,city,county,address,address_last_code,lng,lat,is_default,idcard_name,idcard_number,idcard_front,idcard_back';
        $data = self::UserAddressListHandle(Db::name('UserAddress')->where($where)->field($field)->order('id desc')->select()->toArray());
        if(!empty($data))
        {
            $is_default = false;
            foreach($data as &$v)
            {
                // 是否有默认地址
                if($is_default === false && $v['is_default'] == 1)
                {
                    $is_default = true;
                }

                // 地理位置距离字段
                $v['distance_value'] = null;
                $v['distance_unit'] = null;

                // 地址状态（0未禁用、1已禁用），msg禁用原因
                $v['address_disable_status'] = 0;
                $v['address_disable_msg'] = null;
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
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
            'user_id'       => $params['user']['id'],
        ]);

        // 根据距离排序
        if(count($data) > 1 && array_sum(array_column($data, 'distance_value')) > 0)
        {
            $data = ArrayQuickSort($data, 'distance_value');
        }

        return DataReturn(MyLang('operate_success'), 0, $data);
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
                'error_msg'         => MyLang('common_service.useraddress.address_id_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
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
                'error_msg'         => MyLang('user_info_incorrect_tips'),
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
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
            'user_id'       => $params['user']['id'],
        ]);

        // 如果地址为禁用状态则赋值空
        if(!empty($data) && isset($data['address_disable_status']) && $data['address_disable_status'] == 1)
        {
            $data = null;
        }

        return DataReturn(MyLang('get_success'), 0, $data);
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

        // 附件
        $data_fields = ['idcard_front', 'idcard_back'];
        $attachment = ResourcesService::AttachmentParams($params, $data_fields);
        if($attachment['code'] != 0)
        {
            return $attachment;
        }

        // 地址最后一级编码
        $address_last_code = '';
        foreach(['county', 'city', 'province'] as $field)
        {
            if(!empty($params[$field]))
            {
                $address_last_code = Db::name('Region')->where(['id'=>$params[$field]])->value('code');
                if(!empty($address_last_code))
                {
                    break;
                }
            }
        }
        
        // 操作数据
        $is_default = isset($params['is_default']) ? intval($params['is_default']) : 0;
        $data = [
            'alias'              => empty($params['alias']) ? '' : $params['alias'],
            'name'               => $params['name'],
            'tel'                => $params['tel'],
            'province'           => intval($params['province']),
            'city'               => intval($params['city']),
            'county'             => isset($params['county']) ? intval($params['county']) : 0,
            'address'            => $params['address'],
            'address_last_code'  => $address_last_code,
            'is_default'         => $is_default,
        ];
        // 坐标
        if(array_key_exists('lng', $params))
        {
            $data['lng'] = isset($params['lng']) ? floatval($params['lng']) : 0;
        }
        if(array_key_exists('lat', $params))
        {
            $data['lat'] = isset($params['lat']) ? floatval($params['lat']) : 0;
        }
        // 身份证信息
        if(array_key_exists('idcard_name', $params))
        {
            $data['idcard_name'] = empty($params['idcard_name']) ? '' : $params['idcard_name'];
        }
        if(array_key_exists('idcard_number', $params))
        {
            $data['idcard_number'] = empty($params['idcard_number']) ? '' : $params['idcard_number'];
        }
        if(array_key_exists('idcard_front', $params))
        {
            $data['idcard_front'] = $attachment['data']['idcard_front'];
        }
        if(array_key_exists('idcard_back', $params))
        {
            $data['idcard_back'] = $attachment['data']['idcard_back'];
        }

        // 用户地址保存前钩子
        $hook_name = 'plugins_service_user_address_save_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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
        if(empty($temp))
        {
            $data['user_id'] = $params['user']['id'];
            $data['add_time'] = time();
            $data['id'] = Db::name('UserAddress')->insertGetId($data);
            if($data['id'] > 0)
            {
                $status = true;
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('UserAddress')->where($where)->update($data))
            {
                $status = true;
            }
        }
        if($status)
        {
            Db::commit();
        } else {
            Db::rollback();
        }

        // 用户地址保存后钩子
        $hook_name = 'plugins_service_user_address_save_end';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => $data,
            'user_id'       => $params['user']['id'],
        ]);
        if($status)
        {
            return DataReturn(MyLang('operate_success'), 0, $data);
        }
        return DataReturn(MyLang('operate_fail'), -100);
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
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '30',
                'error_msg'         => MyLang('common_service.useraddress.form_item_name_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'alias',
                'checked_data'      => '16',
                'is_checked'        => 1,
                'error_msg'         => MyLang('common_service.useraddress.form_item_alias_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'tel',
                'error_msg'         => MyLang('common_service.useraddress.form_item_tel_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'province',
                'error_msg'         => MyLang('form_region_province_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'city',
                'error_msg'         => MyLang('form_region_city_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'address',
                'error_msg'         => MyLang('common_service.useraddress.form_item_address_message'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];

        // 是否需要上传身份证
        if(RequestModule() != 'admin' && MyC('home_user_address_idcard_status') == 1)
        {
            $p = array_merge($p, [
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'idcard_name',
                    'error_msg'         => MyLang('common_service.useraddress.form_item_idcard_name_message'),
                ],
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'idcard_number',
                    'error_msg'         => MyLang('common_service.useraddress.form_item_idcard_number_message'),
                ],
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'idcard_front',
                    'error_msg'         => MyLang('common_service.useraddress.form_item_idcard_front_message'),
                ],
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'idcard_back',
                    'error_msg'         => MyLang('common_service.useraddress.form_item_idcard_back_message'),
                ],
            ]);
        }
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
                'error_msg'         => MyLang('common_service.useraddress.address_id_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 软删除数据
        $where = ['user_id' => $params['user']['id'], 'id'=>$params['id']];
        if(Db::name('UserAddress')->where($where)->delete())
        {
            // 用户地址删除钩子
            $hook_name = 'plugins_service_user_address_delete';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data_id'       => $params['id'],
                'user_id'       => $params['user']['id'],
            ]);

            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
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
                'error_msg'         => MyLang('common_service.useraddress.address_id_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
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
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => $params,
                'data_id'       => $params['id'],
                'user_id'       => $params['user']['id'],
            ]);

            // 提交事务
            Db::commit();
            return DataReturn(MyLang('setup_success'), 0);
        }
        // 回滚事务
        Db::rollback();
        return DataReturn(MyLang('setup_fail'), -100);
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
            return DataReturn(MyLang('common_service.useraddress.province_matching_fail_tips'), -1);
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
                return DataReturn(MyLang('common_service.useraddress.city_matching_fail_tips'), -1);
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
            'name'      => $params['name'],
            'tel'       => $params['tel'],
            'province'  => $params['province'],
            'city'      => $params['city'],
            'county'    => $params['county'],
            'address'   => $params['address'],
            'user_id'   => $params['user']['id'],
        ];
        $address = Db::name('UserAddress')->where($where)->find();
        if(!empty($address))
        {
            return DataReturn(MyLang('common_service.useraddress.address_exist_tips'), -1);
        }
        // 地址保存
        return self::UserAddressSave($params);
    }

    /**
     * 附件存储路径标识
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-10-14
     * @desc    description
     * @param   [int]          $user_id [用户id]
     */
    public static function EditorAttachmentPathType($user_id)
    {
        return 'user_address-'.intval($user_id%(3*24)/24).'-'.$user_id;
    }
}
?>