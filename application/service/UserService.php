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
use app\service\SafetyService;
use app\service\ResourcesService;

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
     * 获取用户登录信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-27
     * @desc    description
     */
    public static function LoginUserInfo()
    {
        // 参数
        $params = input();

        // 用户数据处理
        $user = null;
        if(APPLICATION == 'web')
        {
            // web用户session
            $user = session('user');

            // token仅小程序浏览器环境和api接口环境中有效
            if(empty($user) && !empty($params['token']) && in_array(MiniAppEnv(), config('shopxo.mini_app_type_list')))
            {
                $user = self::UserTokenData($params['token']);
                if($user !== null && isset($user['id']))
                {
                    self::UserLoginRecord($user['id']);
                }
            }
        } else {
            if(!empty($params['token']))
            {
                $user = self::UserTokenData($params['token']);
            }
        }

        return $user;
    }

    /**
     * 获取用户token用户数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-18T19:01:59+0800
     * @desc     description
     * @param    [string]                   $token [用户token]
     */
    private static function UserTokenData($token)
    {
        $user = cache(config('shopxo.cache_user_info').$token);
        if($user !== null && isset($user['id']))
        {
            return $user;
        }

        // 数据库校验
        return self::AppUserInfoHandle(null, 'token', $token);
    }

    /**
     * 用户状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-02-27
     * @desc    description
     * @param   [string]          $field [条件字段]
     * @param   [string]          $value [条件值]
     */
    public static function UserStatusCheck($field, $value)
    {
        // 查询用户状态是否正常
        $user = self::UserInfo($field, $value);
        if(empty($user))
        {
            return DataReturn('用户不存在或已删除', -110);
        }
        if(!in_array($user['status'], [0,1]))
        {
            $common_user_status_list = lang('common_user_status_list');
            if(isset($common_user_status_list[$user['status']]))
            {
                return DataReturn($common_user_status_list[$user['status']]['tips'], -110);
            } else {
                return DataReturn('用户状态有误', -110);
            }
        }
        return DataReturn('正常', 0);
    }

    /**
     * 用户列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserList($params = [])
    {
        $where = empty($params['where']) ? [] : $params['where'];
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : trim($params['order_by']);

        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 获取用户列表
        $data = Db::name('User')->where($where)->order($order_by)->limit($m, $n)->select();
        if(!empty($data))
        {
            $common_gender_list = lang('common_gender_list');
            $common_user_status_list = lang('common_user_status_list');
            foreach($data as &$v)
            {
                // 生日
                $v['birthday_text'] = empty($v['birthday']) ? '' : date('Y-m-d', $v['birthday']);

                // 头像
                if(!empty($v['avatar']))
                {
                    $v['avatar'] = ResourcesService::AttachmentPathViewHandle($v['avatar']);
                } else {
                    $v['avatar'] = config('shopxo.attachment_host').'/static/index/'.strtolower(MyC('common_default_theme', 'default', true)).'/images/default-user-avatar.jpg';
                }

                // 注册时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

                // 性别
                $v['gender_text'] = isset($common_gender_list[$v['gender']]) ? $common_gender_list[$v['gender']]['name'] : '未知';

                // 状态
                $v['status_text'] = $common_user_status_list[$v['status']]['name'];
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 用户列表条件
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserListWhere($params = [])
    {
        $where = [];
        if(!empty($params['keywords']))
        {
            $where[] =['username|nickname|mobile', 'like', '%'.$params['keywords'].'%'];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 性别
            if(isset($params['gender']) && $params['gender'] > -1)
            {
                $where[] = ['gender', '=', intval($params['gender'])];
            }

            // 状态
            if(isset($params['status']) && $params['status'] > -1)
            {
                $where[] = ['status', '=', intval($params['status'])];
            }

            // 时间
            if(!empty($params['time_start']))
            {
                $where[] = ['add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['add_time', '<', strtotime($params['time_end'])];
            }
        }
        return $where;
    }

    /**
     * 用户总数
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $where [条件]
     */
    public static function UserTotal($where)
    {
        return (int) Db::name('User')->where($where)->count();
    }

    /**
     * 用户信息保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'admin',
                'error_msg'         => '用户信息有误',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'username',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => '用户名格式最多 30 个字符之间',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'nickname',
                'checked_data'      => '30',
                'is_checked'        => 1,
                'error_msg'         => '用户昵称格式最多 30 个字符之间',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'mobile',
                'checked_data'      => 'CheckMobile',
                'is_checked'        => 1,
                'error_msg'         => '手机号码格式错误',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'email',
                'checked_data'      => 'CheckEmail',
                'is_checked'        => 1,
                'error_msg'         => '邮箱格式错误',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'gender',
                'checked_data'      => array_column(lang('common_gender_list'), 'id'),
                'error_msg'         => '性别值范围不正确',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'status',
                'checked_data'      => array_column(lang('common_user_status_list'), 'id'),
                'error_msg'         => '状态值范围不正确',
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'address',
                'checked_data'      => '80',
                'is_checked'        => 1,
                'error_msg'         => '地址格式最多 80 个字符之间',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'pwd',
                'checked_data'      => 'CheckLoginPwd',
                'is_checked'        => 1,
                'error_msg'         => '密码格式 6~18 个字符之间',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 更新数据
        $data = [
            'username'              => isset($params['username']) ? $params['username'] :  '',
            'nickname'              => isset($params['nickname']) ? $params['nickname'] :  '',
            'mobile'                => isset($params['mobile']) ? $params['mobile'] :  '',
            'email'                 => isset($params['email']) ? $params['email'] :  '',
            'address'               => isset($params['address']) ? $params['address'] :  '',
            'gender'                => intval($params['gender']),
            'integral'              => intval($params['integral']),
            'status'                => intval($params['status']),
            'alipay_openid'         => isset($params['alipay_openid']) ? $params['alipay_openid'] :  '',
            'baidu_openid'          => isset($params['baidu_openid']) ? $params['baidu_openid'] :  '',
            'toutiao_openid'        => isset($params['toutiao_openid']) ? $params['toutiao_openid'] :  '',
            'qq_openid'             => isset($params['qq_openid']) ? $params['qq_openid'] :  '',
            'qq_unionid'            => isset($params['qq_unionid']) ? $params['qq_unionid'] :  '',
            'weixin_openid'         => isset($params['weixin_openid']) ? $params['weixin_openid'] :  '',
            'weixin_unionid'        => isset($params['weixin_unionid']) ? $params['weixin_unionid'] :  '',
            'weixin_web_openid'     => isset($params['weixin_web_openid']) ? $params['weixin_web_openid'] :  '',
            'birthday'              => empty($params['birthday']) ? 0 : strtotime($params['birthday']),
            'upd_time'              => time(),
        ];

        // 用户保存处理钩子
        $hook_name = 'plugins_service_user_save_handle';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            'user_id'       => isset($params['id']) ? intval($params['id']) : 0,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 密码
        if(!empty($params['pwd']))
        {
            $data['salt'] = GetNumberCode(6);
            $data['pwd'] = LoginPwdEncryption(trim($params['pwd']), $data['salt']);
        }

        // 更新/添加
        if(!empty($params['id']))
        {
            // 获取用户信息
            $user = Db::name('User')->field('id,integral')->find($params['id']);
            if(empty($user))
            {
                return DataReturn('用户信息不存在', -10);
            }

            $data['upd_time'] = time();
            if(Db::name('User')->where(['id'=>$params['id']])->update($data))
            {
                $user_id = $params['id'];
            }
        } else {
            $data['add_time'] = time();
            $user_id = Db::name('User')->insertGetId($data);
        }

        // 状态
        if(isset($user_id))
        {
            if(($data['integral'] > 0 && empty($user)) || (isset($user['integral']) && $user['integral'] != $data['integral']))
            {
                $integral_type = 1;
                $integral = 0;
                if(isset($user['integral']))
                {
                    $integral_type = ($user['integral'] > $data['integral']) ? 0 : 1;
                    $integral = $user['integral'];
                }
                IntegralService::UserIntegralLogAdd($user_id, $integral, $data['integral'], '管理员操作', $integral_type, $params['admin']['id']);
            }
            return DataReturn('操作成功', 0);
        }
        return DataReturn('操作失败', -100);
    }

    /**
     * 用户删除
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     * @param    [array]          $params [输入参数]
     */
    public static function UserDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '删除id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
           
        // 删除操作
        if(Db::name('User')->delete(intval($params['id'])))
        {
            return DataReturn('删除成功');
        }
        return DataReturn('删除失败或资源不存在', -100);
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
                // 坐标处理
                if(in_array(APPLICATION_CLIENT_TYPE, config('shopxo.coordinate_transformation')))
                {
                    // 坐标转换 百度转火星(高德，谷歌，腾讯坐标)
                    if(isset($v['lng']) && isset($v['lat']) && $v['lng'] > 0 && $v['lat'] > 0)
                    {
                        $map = \base\GeoTransUtil::BdToGcj($v['lng'], $v['lat']);
                        if(isset($map['lng']) && isset($map['lat']))
                        {
                            $v['lng'] = $map['lng'];
                            $v['lat'] = $map['lat'];
                        }
                    }
                }

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
        return DataReturn('操作成功', 0, $data);
    }

    /**
     * [UserAddressRow 获取地址详情]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-23T23:19:25+0800
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
        if(!empty($ret['data'][0]))
        {
            $ret['data'] = $ret['data'][0];
        } else {
            // 没有默认地址则读取第一条作为默认地址
            unset($params['where']);
            $ret = self::UserAddressList($params);
            if(!empty($ret['data'][0]))
            {
                $ret['data'] = $ret['data'][0];
            }
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
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
            'province'      => $params['province'],
            'city'          => $params['city'],
            'county'        => $params['county'],
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

        Db::startTrans();

        // 默认地址处理
        if($is_default == 1)
        {
            Db::name('UserAddress')->where(['user_id'=>$params['user']['id'], 'is_default'=>1])->update(['is_default'=>0]);
        }

        // 添加/更新数据
        if(empty($temp))
        {
            $data['user_id'] = $params['user']['id'];
            $data['add_time'] = time();
            if(Db::name('UserAddress')->insertGetId($data) > 0)
            {
                Db::commit();
                return DataReturn('新增成功', 0);
            } else {
                Db::rollback();
                return DataReturn('新增失败');
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('UserAddress')->where($where)->update($data))
            {
                Db::commit();
                return DataReturn('更新成功', 0);
            } else {
                Db::rollback();
                return DataReturn('更新失败');
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
     * [UserLoginRecord 用户登录记录]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T11:37:43+0800
     * @param    [int]     $user_id [用户id]
     * @param    [boolean] $is_app  [是否为app]
     * @return   [boolean]          [记录成功true, 失败false]
     */
    public static function UserLoginRecord($user_id = 0, $is_app = false)
    {
        if(!empty($user_id))
        {
            $user = Db::name('User')->field('*')->find($user_id);
            if(!empty($user))
            {
                // 用户数据处理
                $user = self::UserHandle($user);

                // 用户登录成功信息纪录钩子
                $hook_name = 'plugins_service_user_login_success_record';
                Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'user'          => &$user,
                    'user_id'       => $user_id
                ]);

                // 非app则存储session
                if($is_app == false)
                {
                    // 存储session
                    session('user', $user);
                    return (session('user') !== null);
                }
            }
        }
        return false;
    }

    /**
     * 用户数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-01-23
     * @desc    description
     * @param   [ array]          $user [用户数据]
     */
    private static function UserHandle($user)
    {
        // 基础数据处理
        if(isset($user['add_time']))
        {
            $user['add_time_text']  =   date('Y-m-d H:i:s', $user['add_time']);
        }
        if(isset($user['upd_time']))
        {
            $user['upd_time_text']  =   date('Y-m-d H:i:s', $user['upd_time']);
        }
        if(isset($user['gender']))
        {
            $user['gender_text']    =   lang('common_gender_list')[$user['gender']]['name'];
        }
        if(isset($user['birthday']))
        {
            $user['birthday_text']  =   empty($user['birthday']) ? '' : date('Y-m-d', $user['birthday']);
        }

        // 邮箱/手机
        if(isset($user['mobile']))
        {
            $user['mobile_security']=   empty($user['mobile']) ? '' : substr($user['mobile'], 0, 3).'***'.substr($user['mobile'], -3);
        }
        if(isset($user['email']))
        {
            $user['email_security'] =   empty($user['email']) ? '' : substr($user['email'], 0, 3).'***'.substr($user['email'], -3);
        }

        // 显示名称,根据规则优先展示
        $user['user_name_view'] = isset($user['username']) ? $user['username'] : '';
        if(empty($user['user_name_view']) && isset($user['nickname']))
        {
            $user['user_name_view'] = $user['nickname'];
        }
        if(empty($user['user_name_view']) && isset($user['mobile_security']))
        {
            $user['user_name_view'] = $user['mobile_security'];
        }
        if(empty($user['user_name_view']) && isset($user['email_security']))
        {
            $user['user_name_view'] = $user['email_security'];
        }

        // 头像
        if(!empty($user['avatar']))
        {
            $user['avatar'] = ResourcesService::AttachmentPathViewHandle($user['avatar']);
        } else {
            $user['avatar'] = config('shopxo.attachment_host').'/static/index/'.strtolower(config('DEFAULT_THEME', 'default')).'/images/default-user-avatar.jpg';
        }

        return $user;
    }

    /**
     * 用户头像更新
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-16
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserAvatarUpload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'img_width',
                'error_msg'         => '图片宽度不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'img_height',
                'error_msg'         => '图片高度不能为空',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'img_x',
                'error_msg'         => '图片裁剪x坐标有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'img_y',
                'error_msg'         => '图片裁剪y坐标有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'img_field',
                'error_msg'         => '图片name字段值不能为空',
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

        // 开始处理图片存储
        // 定义图片目录
        $root_path = ROOT.'public'.DS;
        $img_path = 'static'.DS.'upload'.DS.'images'.DS.'user_avatar'.DS;
        $date = DS.date('Y').DS.date('m').DS.date('d').DS;

        // 图像类库
        $images_obj = \base\Images::Instance(['is_new_name'=>false]);

        // 文件上传校验
        $error = FileUploadError($params['img_field']);
        if($error !== true)
        {
            return DataReturn($error, -2);
        }

        $original = $images_obj->GetCompressCut($_FILES[$params['img_field']], $root_path.$img_path.'original'.$date, 800, 800, $params['img_x'], $params['img_y'], $params['img_width'], $params['img_height']);
        if(!empty($original))
        {
            $compr = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'compr'.$date, 200, 200);
            $small = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'small'.$date, 50, 50);
        }
        if(empty($compr) || empty($small))
        {
            return DataReturn('图片有误，请换一张', -3);
        }

        // 更新用户头像
        $data = [
            'avatar'    => DS.$img_path.'compr'.$date.$compr,
            'upd_time'  => time(),
        ];
        if(Db::name('User')->where(['id'=>$params['user']['id']])->update($data))
        {
            if(APPLICATION == 'web')
            {
                self::UserLoginRecord($params['user']['id']);
            }
            return DataReturn('上传成功', 0);
        }
        return DataReturn('上传失败', -100);
    }

    /**
     * 用户登录
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Login($params = [])
    {
        // 是否开启用户登录
        if(MyC('home_user_login_state') != 1)
        {
            return DataReturn('暂时关闭用户登录', -1);
        }

        // 登录帐号格式校验
        if(empty($params['accounts']))
        {
            return DataReturn('登录账号有误', -1);
        }

        // 密码
        $pwd = trim($params['pwd']);
        if(!CheckLoginPwd($pwd))
        {
            return DataReturn('密码格式 6~18 个字符之间', -2);
        }

        // 是否开启图片验证码
        $verify_params = [
            'key_prefix' => 'login',
            'expire_time' => MyC('common_verify_expire_time'),
        ];
        $verify = self::IsImaVerify($params, $verify_params, MyC('home_user_login_img_verify_state'));
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 获取用户账户信息
        $where = array('username|mobile|email' => $params['accounts'], 'is_delete_time'=>0);
        $user = Db::name('User')->field('id,pwd,salt,status')->where($where)->find();
        if(empty($user))
        {
            return DataReturn('帐号不存在', -3);
        }

        // 用户状态
        if(in_array($user['status'], [2,3]))
        {
            return DataReturn(lang('common_user_status_list')[$user['status']]['tips'], -10);
        }

        // 密码校验
        if(LoginPwdEncryption($pwd, $user['salt']) != $user['pwd'])
        {
            return DataReturn('密码错误', -4);
        }

        // 用户登录前钩子
        $hook_name = 'plugins_service_user_login_begin';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'user_id'       => $user['id']
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 更新用户密码
        $salt = GetNumberCode(6);
        $data = array(
                'pwd'       =>  LoginPwdEncryption($pwd, $salt),
                'salt'      =>  $salt,
                'upd_time'  =>  time(),
            );
        if(Db::name('User')->where(['id'=>$user['id']])->update($data) !== false)
        {
            // 清除图片验证码
            if(isset($verify) && isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return self::UserLoginHandle($user['id'], $params);
        }
        return DataReturn('登录失效，请重新登录', -100);
    }

    /**
     * 登录处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-24
     * @desc    description
     * @param   [int]          $user_id [用户id]
     * @param   [array]        $params  [输入参数]
     */
    public static function UserLoginHandle($user_id, $params = [])
    {
        // 登录记录
        if(self::UserLoginRecord($user_id))
        {
            // 返回前端html代码
            $body_html = [];

            // 用户登录后钩子
            $hook_name = 'plugins_service_user_login_end';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'user_id'       => $user_id,
                'user'          => Db::name('User')->field('id,username,nickname,mobile,email,gender,avatar,province,city,birthday')->where(['id'=>$user_id])->find(),
                'body_html'     => &$body_html,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 登录返回
            $result = [
                'body_html'    => is_array($body_html) ? implode(' ', $body_html) : $body_html,
            ];
            return DataReturn('登录成功', 0, $result);
        }
        return DataReturn('登录失效，请重新登录', -100);
    }

    /**
     * 用户注册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-03
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Reg($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => '账号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pwd',
                'error_msg'         => '密码不能为空',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(lang('common_user_reg_state_list'), 'value'),
                'error_msg'         => '注册类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'is_checked'        => 2,
                'error_msg'         => '验证码不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('home_user_reg_state')))
        {
            return DataReturn('暂时关闭用户注册', -1);
        }

        // 账户校验
        $ret = self::UserRegAccountsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 是否需要审核
        $common_register_is_enable_audit = MyC('common_register_is_enable_audit', 0);

        // 用户数据
        $salt = GetNumberCode(6);
        $data = [
            'upd_time'      => time(),
            'salt'          => $salt,
            'pwd'           => LoginPwdEncryption($params['pwd'], $salt),
            'status'        => ($common_register_is_enable_audit == 1) ? 3 : 0,
        ];

        // 验证码校验
        $verify_params = [
            'key_prefix' => 'reg_'.md5($params['accounts']),
            'expire_time' => MyC('common_verify_expire_time'),
        ];

        // 账户类型
        switch($params['type'])
        {
            // 短信
            case 'sms' :
                $data['mobile'] = $params['accounts'];
                $obj = new \base\Sms($verify_params);
                break;

            // 邮箱
            case 'email' :
                $data['email'] = $params['accounts'];
                $obj = new \base\Email($verify_params);
                break;

            // 默认 账号
             default :
                $data['username'] = $params['accounts'];
                // 是否开启图片验证码
                // images_verify_reg 由前端图片验证码传递的 type 一致
                $verify_params['key_prefix'] = 'images_verify_reg';
                $verify = self::IsImaVerify($params, $verify_params, MyC('home_user_register_img_verify_state'));
                if($verify['code'] != 0)
                {
                    return $verify;
                }
        }

        // 验证码校验
        // sms, email
        if(isset($obj) && is_object($obj))
        {
            // 是否已过期
            if(!$obj->CheckExpire())
            {
                return DataReturn('验证码已过期', -10);
            }
            // 是否正确
            if(!$obj->CheckCorrect($params['verify']))
            {
                return DataReturn('验证码错误', -11);
            }
        }

        // 数据添加
        $user_ret = self::UserInsert($data, $params);
        if($user_ret['code'] == 0)
        {
            // 清除验证码
            if(isset($obj) && is_object($obj))
            {
                $obj->Remove();
            }

            // 是否需要审核
            if($common_register_is_enable_audit == 1)
            {
                return DataReturn('注册成功，请等待审核');
            }

            // 用户登录session纪录
            if(self::UserLoginRecord($user_ret['data']['user_id']))
            {
                return DataReturn('注册成功', 0, $user_ret);
            }
            return DataReturn('注册成功，请到登录页面登录帐号');
        } else {
            return $user_ret;
        }
        return DataReturn('注册失败', -100);
    }

    /**
     * 用户注册账户校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    private static function UserRegAccountsCheck($params = [])
    {
        switch($params['type'])
        {
            // 手机
            case 'sms' :
                // 手机号码格式
                if(!CheckMobile($params['accounts']))
                {
                     return DataReturn('手机号码格式错误', -2);
                }

                // 手机号码是否已存在
                if(self::IsExistAccounts($params['accounts'], 'mobile'))
                {
                     return DataReturn('手机号码已存在', -3);
                }
                break;

            // 邮箱
            case 'email' :
                // 电子邮箱格式
                if(!CheckEmail($params['accounts']))
                {
                     return DataReturn('电子邮箱格式错误', -2);
                }

                // 电子邮箱是否已存在
                if(self::IsExistAccounts($params['accounts'], 'email'))
                {
                     return DataReturn('电子邮箱已存在', -3);
                }
                break;

            // 用户名
            case 'username' :
                // 用户名格式
                if(!CheckUserName($params['accounts']))
                {
                     return DataReturn('用户名格式由 字母数字下划线 2~18 个字符', -2);
                }
                break;
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 账户是否存在
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-08T10:27:14+0800
     * @param    [string] $accounts     [账户名称]
     * @param    [string] $field        [字段名称]
     * @return   [boolean]              [存在true, 不存在false]
     */
    private static function IsExistAccounts($accounts, $field = 'mobile')
    {
        $id = Db::name('User')->where(array($field=>$accounts))->value('id');
        return !empty($id);
    }

    /**
     * 是否开启图片验证码校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-22T15:48:31+0800
     * @param    [array]    $params         [输入参数]
     * @param    [array]    $verify_params  [配置参数]
     * @param    [int]      $status         [状态 0未开启, 1已开启]
     * @return   [object]                   [图片验证码类对象]
     */
    private static function IsImaVerify($params, $verify_params, $status = 0)
    {
        if($status == 1)
        {
            if(empty($params['verify']))
            {
                return DataReturn('图片验证码为空', -10);
            }
            $verify = new \base\Verify($verify_params);
            if(!$verify->CheckExpire())
            {
                return DataReturn('验证码已过期', -11);
            }
            if(!$verify->CheckCorrect($params['verify']))
            {
                return DataReturn('验证码错误', -12);
            }
            return DataReturn('操作成功', 0, $verify);
        }
        return DataReturn('操作成功', 0);
    }

    /**
     * 用户注册-验证码发送
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T10:06:29+0800
     * @param   [array]          $params [输入参数]
     */
    public static function RegVerifySend($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => '账号不能为空',
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(lang('common_user_reg_state_list'), 'value'),
                'error_msg'         => '注册类型有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('home_user_reg_state')))
        {
            return DataReturn('暂时关闭用户注册');
        }

        // 验证码基础参数
        $verify_params = [
            'key_prefix' => 'reg',
            'expire_time' => MyC('common_verify_expire_time'),
            'time_interval' =>  MyC('common_verify_time_interval'),
        ];

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $verify_params, MyC('home_img_verify_state'));
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 账户校验
        $ret = self::UserRegAccountsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码基础参数 key
        $verify_params['key_prefix'] = 'reg_'.md5($params['accounts']);

        // 发送验证码
        $code = GetNumberCode(4);
        switch($params['type'])
        {
            // 短信
            case 'sms' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params['accounts'], $code, MyC('home_sms_user_reg'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = array(
                        'email'     =>  $params['accounts'],
                        'content'   =>  MyC('home_email_user_reg'),
                        'title'     =>  MyC('home_site_name').' - 用户注册',
                        'code'      =>  $code,
                    );
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn('该类型不支持验证码发送', -2);
        }
        
        // 状态
        if($status)
        {
            // 清除验证码
            if(isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return DataReturn('发送成功', 0);
        } else {
            return DataReturn('发送失败'.'['.$obj->error.']', -100);
        }
    }

    /**
     * 密码找回验证码发送
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:35:03+0800
     * @param   [array]          $params [输入参数]
     */
    public static function ForgetPwdVerifySend($params = [])
    {
        // 参数
        if(empty($params['accounts']))
        {
            return DataReturn('参数错误', -10);
        }

        // 验证码基础参数
        $verify_params = [
            'key_prefix' => 'forget',
            'expire_time' => MyC('common_verify_expire_time'),
            'time_interval' =>  MyC('common_verify_time_interval'),
        ];

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $verify_params, MyC('home_img_verify_state'));
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 账户是否存在，并返回账户格式类型
        $ret = self::UserForgetAccountsCheck($params['accounts']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码基础参数 key
        $verify_params['key_prefix'] = 'forget_'.md5($params['accounts']);

        // 验证码
        $code = GetNumberCode(4);

        // 账户字段类型
        switch($ret['data'])
        {
            // 手机
            case 'mobile' :
                $obj = new \base\Sms($verify_params);
                $status = $obj->SendCode($params['accounts'], $code, MyC('home_sms_user_forget_pwd'));
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                $email_params = [
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('home_email_user_forget_pwd'),
                    'title'     =>  MyC('home_site_name').' - '.'密码找回',
                    'code'      =>  $code,
                ];
                $status = $obj->SendHtml($email_params);
                break;

            // 默认
            default :
                return DataReturn('手机/邮箱格式有误', -1);
        }

        // 状态
        if($status)
        {
            // 清除图片验证码
            if(isset($verify) && isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return DataReturn('发送成功', 0);
        } else {
            return DataReturn('发送失败'.'['.$obj->error.']', -100);
        }
    }

    /**
     * [UserForgetAccountsCheck 帐号校验]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:59:53+0800
     * @param    [string]     $accounts [账户名称]
     * @return   [string]               [账户字段 mobile, email]
     */
    private static function UserForgetAccountsCheck($accounts)
    {
        if(CheckMobile($accounts))
        {
            if(!self::IsExistAccounts($accounts, 'mobile'))
            {
                return DataReturn('手机号码不存在', -3);
            }
            return DataReturn('操作成功', 0, 'mobile');
        } else if(CheckEmail($accounts))
        {
            if(!self::IsExistAccounts($accounts, 'email'))
            {
                return DataReturn('电子邮箱不存在', -3);
            }
            return DataReturn('操作成功', 0, 'email');
        }
        return DataReturn('手机/邮箱格式有误', -4);
    }

    /**
     * 密码找回
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-10T17:35:03+0800
     * @param   [array]          $params [输入参数]
     */
    public static function ForgetPwd($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'accounts',
                'error_msg'         => '账号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pwd',
                'error_msg'         => '密码不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => '验证码不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 账户是否存在
        $ret = self::UserForgetAccountsCheck($params['accounts']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码校验
        $verify_params = [
            'key_prefix' => 'forget_'.md5($params['accounts']),
            'expire_time' => MyC('common_verify_expire_time'),
            'time_interval' =>  MyC('common_verify_time_interval'),
        ];
        switch($ret['data'])
        {
            // 手机
            case 'mobile' :
                $obj = new \base\Sms($verify_params);
                break;

            // 邮箱
            case 'email' :
                $obj = new \base\Email($verify_params);
                break;

            // 默认
            default :
                return DataReturn('手机/邮箱格式有误', -1);
        }
        
        // 是否已过期
        if(!$obj->CheckExpire())
        {
            return DataReturn('验证码已过期', -10);
        }
        // 是否正确
        if(!$obj->CheckCorrect($params['verify']))
        {
            return DataReturn('验证码错误', -11);
        }

        // 获取用户信息
        $user = Db::name('User')->where([$ret['data']=>$params['accounts']])->find();
        if(empty($user))
        {
            return DataReturn('用户信息不存在', -12);
        }

        // 密码修改
        $ret = SafetyService::UserLoginPwdUpdate($params['accounts'], $user['id'], $params['pwd']);
        if($ret['code'] != 0)
        {
            // 清除验证码
            $obj->Remove();
            return DataReturn('操作成功', 0);
        }
        return $ret;
    }

    /**
     * 用户资料保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-04
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function PersonalSave($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'length',
                'checked_data'      => '2,16',
                'key_name'          => 'nickname',
                'error_msg'         => '昵称 2~16 个字符之间',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'birthday',
                'error_msg'         => '请填写生日',
            ],
            [
                'checked_type'      => 'in',
                'checked_data'      => [0,1,2],
                'key_name'          => 'gender',
                'error_msg'         => '性别选择有误',
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

        // 更新数据库
        $data = [
            'birthday'      => empty($params['birthday']) ? '' : strtotime($params['birthday']),
            'nickname'      => $params['nickname'],
            'gender'        => intval($params['gender']),
            'upd_time'      => time(),
        ];
        if(Db::name('User')->where(array('id'=>$params['user']['id']))->update($data))
        {
            // 更新用户session数据
            self::UserLoginRecord($params['user']['id']);

            return DataReturn('编辑成功', 0);
        }
        return DataReturn('编辑失败或数据未改变', -100);
    }

    /**
     * 用户授权数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [array]          $params    [用户数据]
     * @param   [string]         $field     [平台字段名称]
     */
    public static function AuthUserProgram($params, $field)
    {
        // 用户信息
        $data = [
            $field              => $params['openid'],
            'nickname'          => empty($params['nick_name']) ? '' : $params['nick_name'],
            'avatar'            => empty($params['avatar']) ? '' : $params['avatar'],
            'gender'            => empty($params['gender']) ? 0 : intval($params['gender']),
            'province'          => empty($params['province']) ? '' : $params['province'],
            'city'              => empty($params['city']) ? '' : $params['city'],
            'referrer'          => isset($params['referrer']) ? $params['referrer'] : 0,
        ];

        // 用户信息处理
        $user = self::AppUserInfoHandle(null, $field, $params['openid']);
        if(!empty($user))
        {
            return DataReturn('授权成功', 0, $user);
        } else {
            // 用户unionid
            $unionid = self::UserUnionidHandle($params);
            if(!empty($unionid['field']) && !empty($unionid['value']))
            {
                // unionid字段是否存在用户
                $user_unionid = UserService::AppUserInfoHandle(null, $unionid['field'], $unionid['value']);
                if(!empty($user_unionid))
                {
                    // openid绑定
                    if(Db::name('User')->where(['id'=>$user_unionid['id']])->update([$field=>$params['openid'], 'upd_time'=>time()]))
                    {
                        // 直接返回用户信息
                        $user_unionid[$field] = $params['openid'];
                        return DataReturn('授权成功', 0, $user_unionid);
                    }
                }

                // 如果用户不存在数据库中，则unionid放入用户data中
                $data[$unionid['field']] = $unionid['value'];
            }

            // 不强制绑定手机则写入用户信息
            if(intval(MyC('common_user_is_mandatory_bind_mobile')) != 1)
            {
                $ret = self::UserInsert($data, $params);
                if($ret['code'] == 0)
                {
                    return DataReturn('授权成功', 0, self::AppUserInfoHandle($ret['data']['user_id']));
                } else {
                    return $ret;
                }
            }
        }
        return DataReturn('授权成功', 0, self::AppUserInfoHandle(null, null, null, $data));
    }

    /**
     * 用户unionid处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-02-11
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function UserUnionidHandle($params = [])
    {
        // 用户unionid列表
        // 微信用户unionid
        // QQ用户unionid
        $field = null;
        $value = null;
        $unionid_all = ['weixin_unionid', 'qq_unionid'];
        foreach($unionid_all as $unionid)
        {
            if(!empty($params[$unionid]))
            {
                $field = $unionid;
                $value = $params[$unionid];
                break;
            }
        }
        return ['field'=>$field, 'value'=>$value];
    }

    /**
     * app用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-06
     * @desc    description
     * @param   [int]             $user_id          [指定用户id]
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [array]           $user             [用户信息]
     */
    public static function AppUserInfoHandle($user_id = null, $where_field = null, $where_value = null, $user = [])
    {
        // 获取用户信息
        $field = 'id,username,nickname,mobile,email,avatar,alipay_openid,weixin_openid,weixin_unionid,weixin_web_openid,baidu_openid,toutiao_openid,qq_openid,qq_unionid,integral,locking_integral,referrer,add_time';
        if(!empty($user_id))
        {
            $user = self::UserInfo('id', $user_id, $field);
        } elseif(!empty($where_field) && !empty($where_value) && empty($user))
        {
            $user = self::UserInfo($where_field, $where_value, $field);
        }

        if(!empty($user))
        {
            // 用户信息处理
            $user = self::GetUserViewInfo(0, $user);

            // 是否强制绑定手机号码
            $user['is_mandatory_bind_mobile'] = intval(MyC('common_user_is_mandatory_bind_mobile'));

            // 基础处理
            if(isset($user['id']))
            {
                // token生成并存储缓存
                if($user['is_mandatory_bind_mobile'] == 0 || ($user['is_mandatory_bind_mobile'] == 1 && !empty($user['mobile'])))
                {
                    $user['token'] = md5(md5($user['id'].time()).rand(100, 1000000));
                    cache(config('shopxo.cache_user_info').$user['token'], $user);

                    // 非token数据库校验，则重新生成token更新到数据库
                    if($where_field != 'token')
                    {
                        Db::name('User')->where(['id'=>$user['id']])->update(['token'=>$user['token'], 'upd_time'=>time()]);
                    }
                } else {
                    $user['token'] = '';
                }

                // 用户登录纪录处理
                if(in_array(APPLICATION_CLIENT_TYPE, ['pc', 'h5']))
                {
                    self::UserLoginRecord($user['id'], true);
                }
            }
        }

        return $user;
    }

    /**
     * 根据字段获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-25
     * @desc    description
     * @param   [string]          $where_field      [字段名称]
     * @param   [string]          $where_value      [字段值]
     * @param   [string]          $field            [指定字段]
     */
    public static function UserInfo($where_field, $where_value, $field = '*')
    {
        if(empty($where_field) || empty($where_value))
        {
            return '';
        }
        
        return Db::name('User')->where([$where_field=>$where_value, 'is_delete_time'=>0])->field($field)->find();
    }

    /**
     * 用户添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-03
     * @desc    description
     * @param   [array]          $data   [用户添加数据]
     * @param   [array]          $params [输入参数]
     */
    public static function UserInsert($data, $params = [])
    {
        // 账号是否存在，以用户名 手机 邮箱 作为唯一
        if(!empty($data['username']))
        {
            $temp = Db::name('User')->where(['username'=>$data['username'], 'is_delete_time'=>0])->find();
        } else if(!empty($data['mobile']))
        {
            $temp = Db::name('User')->where(['mobile'=>$data['mobile'], 'is_delete_time'=>0])->find();
        } else if(!empty($data['email']))
        {
            $temp = Db::name('User')->where(['email'=>$data['email'], 'is_delete_time'=>0])->find();
        }
        if(!empty($temp))
        {
            return DataReturn('账号已存在', -10);
        }

        // 用户unionid
        $unionid = self::UserUnionidHandle($params);
        if(!empty($unionid['field']) && !empty($unionid['value']))
        {
            // unionid放入用户data中
            $data[$unionid['field']] = $unionid['value'];
        }

        // 推荐人id
        $data['referrer'] = self::UserReferrerDecrypt($params);

        // 添加用户
        $data['add_time'] = time();
        $user_id = Db::name('User')->insertGetId($data);
        if($user_id > 0)
        {
            // 清除推荐id
            if(isset($data['referrer']))
            {
                session('share_referrer_id', null);
            }

            // 返回前端html代码
            $body_html = [];

            // 注册成功后钩子
            $hook_name = 'plugins_service_user_register_end';
            $ret = HookReturnHandle(Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'user_id'       => $user_id,
                'user'          => Db::name('User')->field('id,username,nickname,mobile,email,gender,avatar,province,city,birthday')->where(['id'=>$user_id])->find(),
                'body_html'     => &$body_html,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }

            // 登录返回
            $result = [
                'body_html'     => is_array($body_html) ? implode(' ', $body_html) : $body_html,
                'user_id'       => $user_id,
            ];

            return DataReturn('添加成功', 0, $result);
        }
        return DataReturn('添加失败', -100);
    }

    /**
     * app用户注册
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppReg($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'mobile',
                'error_msg'         => '手机号码不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'verify',
                'error_msg'         => '验证码不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'app_type',
                'error_msg'         => '终端用户类型不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 手机号码格式
        if(!CheckMobile($params['mobile']))
        {
             return DataReturn('手机号码格式错误', -2);
        }

        // 验证码校验
        $verify_params = [
            'key_prefix' => 'bind_'.md5($params['mobile']),
            'expire_time' => MyC('common_verify_expire_time')
        ];
        $obj = new \base\Sms($verify_params);

        // 是否已过期
        if(!$obj->CheckExpire())
        {
            return DataReturn('验证码已过期', -10);
        }
        // 是否正确
        if(!$obj->CheckCorrect($params['verify']))
        {
            return DataReturn('验证码错误', -11);
        }

        // 用户信息
        $accounts_field = $params['app_type'].'_openid';
        if(empty($params[$accounts_field]))
        {
            return DataReturn('用户openid不能为空', -20);
        }

        // 是否需要审核
        $common_register_is_enable_audit = MyC('common_register_is_enable_audit', 0);

        // 用户数据
        $data = array(
            $accounts_field     => $params[$accounts_field],
            'mobile'            => $params['mobile'],
            'status'            => ($common_register_is_enable_audit == 1) ? 3 : 0,
        );

        // 获取用户信息
        $temp_user = Db::name('User')->where([
            ['mobile', '=', $data['mobile']],
            ['is_delete_time', '=', 0],
        ])->find();
        $open_user = Db::name('User')->where([
            [$accounts_field, '=', $params[$accounts_field]],
            ['is_delete_time', '=', 0],
        ])->find();

        // 如果手机号码存在，并且openid也已存在，则更新掉之前的openid
        if(!empty($temp_user))
        {
            if(!empty($open_user))
            {
                Db::name('User')->where(['id'=>$open_user['id']])->update([$accounts_field=>'', 'upd_time'=>time()]);
            }
        } else {
            $temp_user = $open_user;
        }

        // 额外信息
        if(empty($temp_user['nickname']) && !empty($params['nickname']))
        {
            $data['nickname'] = $params['nickname'];
        }
        if(empty($temp_user['avatar']) && !empty($params['avatar']))
        {
            $data['avatar'] = $params['avatar'];
        }
        if(empty($temp_user['province']) && !empty($params['province']))
        {
            $data['province'] = $params['province'];
        }
        if(empty($temp_user['city']) && !empty($params['city']))
        {
            $data['city'] = $params['city'];
        }
        if(empty($temp_user) && isset($params['gender']))
        {
            $data['gender'] = intval($params['gender']);
        }

        // 不存在添加/则更新
        if(empty($temp_user))
        {
            $user_ret = self::UserInsert($data, $params);
            if($user_ret['code'] == 0)
            {
                $user_id = $user_ret['data']['user_id'];
            } else {
                return $user_ret;
            }
        } else {
            // 用户unionid
            $unionid = self::UserUnionidHandle($params);
            if(!empty($unionid['field']) && !empty($unionid['value']))
            {
                if(empty($temp_user[$unionid['field']]))
                {
                    // unionid放入用户data中
                    $data[$unionid['field']] = $unionid['value'];
                }
            }

            $data['upd_time'] = time();
            if(Db::name('User')->where(['id'=>$temp_user['id']])->update($data))
            {
                $user_id = $temp_user['id'];
            }
        }
        
        if(isset($user_id) && $user_id > 0)
        {
            // 清除验证码
            $obj->Remove();
            return DataReturn('绑定成功', 0, self::AppUserInfoHandle($user_id));
        }
        return DataReturn('绑定失败', -100);
    }

    /**
     * app用户绑定验证码发送
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-27
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AppUserBindVerifySend($params = [])
    {
        // 数据验证
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'mobile',
                'error_msg'         => '手机号码不能为空',
            ],
            [
                'checked_type'      => 'fun',
                'key_name'          => 'mobile',
                'checked_data'      => 'CheckMobile',
                'error_msg'         => '手机号码格式错误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 验证码公共基础参数
        $verify_params = [
            'key_prefix' => 'bind_'.md5($params['mobile']),
            'expire_time' => MyC('common_verify_expire_time'),
            'time_interval' => MyC('common_verify_time_interval'),
        ];

        // 发送验证码
        $obj = new \base\Sms($verify_params);
        $code = GetNumberCode(4);
        $status = $obj->SendCode($params['mobile'], $code, MyC('home_sms_user_mobile_binding'));
        
        // 状态
        if($status)
        {
            return DataReturn('发送成功', 0);
        }
        return DataReturn('发送失败'.'['.$obj->error.']', -100);
    }

    /**
     * 用户退出
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-05T14:31:23+0800
     * @param   [array]          $params [输入参数]
     */
    public static function Logout($params = [])
    {
        // 用户信息
        $user = self::LoginUserInfo();

        // 清除session
        session('user', null);

        // html代码
        $body_html = [];

        // 用户退出钩子
        $hook_name = 'plugins_service_user_logout_handle';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => [],
            'user_id'       => isset($user['id']) ? $user['id'] : 0,
            'user'          => $user,
            'body_html'     => &$body_html,
        ]);

        // 数据返回
        $result = [
            'body_html'    => is_array($body_html) ? implode(' ', $body_html) : $body_html,
        ];

        return DataReturn('退出成功', 0, $result);
    }

    /**
     * 获取用户展示信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-05
     * @desc    description
     * @param   [int]          $user_id     [用户id]
     * @param   [array]        $user        [指定用户信息]
     * @param   [boolean]      $is_privacy  [是否隐私处理展示用户名]
     */
    public static function GetUserViewInfo($user_id, $user = [], $is_privacy = false)
    {
        // 是否指定用户信息
        if(empty($user) && !empty($user_id))
        {
            $user = Db::name('User')->field('username,nickname,mobile,email,avatar')->find($user_id);
        }
        
        // 用户数据处理
        $user = self::UserHandle($user);

        return $user;
    }

    /**
     * 用户登录,密码找回左侧数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-17
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function UserEntranceLeftData($params = [])
    {
        // 从缓存获取
        $data = empty($params['cache_key']) ? [] : cache($params['cache_key']);

        // 获取数据
        if(empty($data))
        {
            $data = [];
            if(!empty($params['left_key']))
            {
                for($i=1; $i<=3; $i++)
                {
                    $images_value = MyC('home_site_user_'.$params['left_key'].'_ad'.$i.'_images');
                    $url_value = MyC('home_site_user_'.$params['left_key'].'_ad'.$i.'_url');
                    $bg_color_value = MyC('home_site_user_'.$params['left_key'].'_ad'.$i.'_bg_color');
                    if(!empty($images_value))
                    {
                        $data[] = [
                            'images'    => ResourcesService::AttachmentPathViewHandle($images_value),
                            'url'       => empty($url_value) ? null : $url_value,
                            'bg_color'  => empty($bg_color_value) ? null : $bg_color_value,
                        ];
                    }
                }

                // 存储缓存
                if(!empty($params['cache_key']))
                {
                    cache($params['cache_key'], $data);
                }
            }
        }
        return DataReturn('操作成功', 0, $data);
    }

    /**
     * 用户推荐id加密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-21
     * @desc    description
     * @param   [int]           $user_id [用户id]
     */
    public static function UserReferrerEncryption($user_id)
    {
        return StrToAscii(base64_encode($user_id));
    }

    /**
     * 用户推荐id解密
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-06-21
     * @desc    description
     * @param   [array]           $params [输入参数, referrer 参数用户推荐id]
     */
    public static function UserReferrerDecrypt($params = [])
    {
        // 推荐人
        $referrer = empty($params['referrer']) ? session('share_referrer_id') : $params['referrer'];

        // 查看用户id是否已加密
        if(preg_match('/[a-zA-Z]/', $referrer))
        {
            $referrer = base64_decode(AsciiToStr($referrer));
        }

        return intval($referrer);
    }
}
?>