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
        if(APPLICATION == 'web')
        {
            return session('user');
        } else {
            $params = input();
            return empty($params['user_id']) ? null : self::UserLoginRecord($params['user_id'], true);
        }
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

        // 获取管理员列表
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
                $v['gender_text'] = $common_gender_list[$v['gender']]['name'];

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
            'weixin_openid'         => isset($params['weixin_openid']) ? $params['weixin_openid'] :  '',
            'baidu_openid'          => isset($params['baidu_openid']) ? $params['baidu_openid'] :  '',
            'birthday'              => empty($params['birthday']) ? 0 : strtotime($params['birthday']),
            'upd_time'              => time(),
        ];

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
                // 基础数据处理
                $user['add_time_text']  =   date('Y-m-d H:i:s', $user['add_time']);
                $user['upd_time_text']  =   date('Y-m-d H:i:s', $user['upd_time']);
                $user['gender_text']    =   lang('common_gender_list')[$user['gender']]['name'];
                $user['birthday_text']  =   empty($user['birthday']) ? '' : date('Y-m-d', $user['birthday']);
                $user['mobile_security']=   empty($user['mobile']) ? '' : substr($user['mobile'], 0, 3).'***'.substr($user['mobile'], -3);
                $user['email_security'] =   empty($user['email']) ? '' : substr($user['email'], 0, 3).'***'.substr($user['email'], -3);

                // 显示名称,根据规则优先展示
                $user['user_name_view'] = $user['username'];
                if(empty($user['user_name_view']))
                {
                    $user['user_name_view'] = $user['nickname'];
                }
                if(empty($user['user_name_view']))
                {
                    $user['user_name_view'] = $user['mobile_security'];
                }
                if(empty($user['user_name_view']))
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

                if($is_app == true)
                {
                    return $user;
                } else {
                    // 存储session
                    session('user', $user);
                    return (session('user') !== null);
                }
            }
        }
        return false;
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
            self::UserLoginRecord($params['user']['id']);
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
        $ret = Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'user_id'       => $user['id']
        ]);
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
            // 登录记录
            if(self::UserLoginRecord($user['id']))
            {
                // 返回前端html代码
                $body_html = [];

                // 用户登录后钩子
                $hook_name = 'plugins_service_user_login_end';
                $ret = Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'user_id'       => $user['id'],
                    'user'          => Db::name('User')->field('id,username,nickname,mobile,email,gender,avatar,province,city,birthday')->where(['id'=>$user['id']])->find(),
                    'body_html'     => &$body_html,
                ]);
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
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '注册类型有误',
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

        // 验证码校验
        $verify_param = array(
                'key_prefix' => 'reg',
                'expire_time' => MyC('common_verify_expire_time')
            );
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_param);
        } else {
            $obj = new \base\Email($verify_param);
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

        // 是否需要审核
        $common_register_is_enable_audit = MyC('common_register_is_enable_audit', 0);

        // 用户数据
        $salt = GetNumberCode(6);
        $data = [
            'add_time'      => time(),
            'upd_time'      => time(),
            'salt'          => $salt,
            'pwd'           => LoginPwdEncryption($params['pwd'], $salt),
            'status'        => ($common_register_is_enable_audit == 1) ? 3 : 0,
        ];
        if($params['type'] == 'sms')
        {
            $data['mobile'] = $params['accounts'];
        } else {
            $data['email'] = $params['accounts'];
        }

        // 数据添加
        $user_ret = self::UserInsert($data, $params);
        if($user_ret['code'] == 0)
        {
            // 清除验证码
            $obj->Remove();

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
        // 参数
        $type = $params['type'];
        $accounts = $params['accounts'];
        if(empty($accounts) || empty($type) || !in_array($type, array('sms', 'email')))
        {
             return DataReturn('参数错误', -1);
        }

        // 手机号码
        if($type == 'sms')
        {
            // 手机号码格式
            if(!CheckMobile($accounts))
            {
                 return DataReturn('手机号码格式错误', -2);
            }

            // 手机号码是否已存在
            if(self::IsExistAccounts($accounts, 'mobile'))
            {
                 return DataReturn('手机号码已存在', -3);
            }

        // 电子邮箱
        } else {
            // 电子邮箱格式
            if(!CheckEmail($accounts))
            {
                 return DataReturn('电子邮箱格式错误', -2);
            }

            // 电子邮箱是否已存在
            if(self::IsExistAccounts($accounts, 'email'))
            {
                 return DataReturn('电子邮箱已存在', -3);
            }
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
     * @return   [object]                   [图片验证码类对象]
     */
    private static function IsImaVerify($params, $verify_params)
    {
        if(MyC('home_img_verify_state') == 1)
        {
            if(empty($params['verify']))
            {
                return DataReturn('参数错误', -10);
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
                'checked_type'      => 'empty',
                'key_name'          => 'type',
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

        // 账户校验
        $ret = self::UserRegAccountsCheck($params);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码公共基础参数
        $verify_params = array(
                'key_prefix' => 'reg',
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' =>  MyC('common_verify_time_interval'),
            );

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $verify_params);
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 发送验证码
        $code = GetNumberCode(6);
        if($params['type'] == 'sms')
        {
            $obj = new \base\Sms($verify_params);
            $status = $obj->SendCode($params['accounts'], $code, MyC('home_sms_user_reg'));
        } else {
            $obj = new \base\Email($verify_params);
            $email_param = array(
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('home_email_user_reg'),
                    'title'     =>  MyC('home_site_name').' - 用户注册',
                    'code'      =>  $code,
                );
            $status = $obj->SendHtml($email_param);
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

        // 账户是否存在
        $ret = self::UserForgetAccountsCheck($params['accounts']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 验证码公共基础参数
        $verify_params = array(
                'key_prefix' => 'forget',
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' =>  MyC('common_verify_time_interval'),
            );

        // 是否开启图片验证码
        $verify = self::IsImaVerify($params, $verify_params);
        if($verify['code'] != 0)
        {
            return $verify;
        }

        // 验证码
        $code = GetNumberCode(6);

        // 手机
        if($ret['data'] == 'mobile')
        {
            $obj = new \base\Sms($verify_params);
            $status = $obj->SendCode($params['accounts'], $code, MyC('home_sms_user_forget_pwd'));

        // 邮箱
        } else if($ret['data'] == 'email')
        {
            $obj = new \base\Email($verify_params);
            $email_param = array(
                    'email'     =>  $params['accounts'],
                    'content'   =>  MyC('home_email_user_forget_pwd'),
                    'title'     =>  MyC('home_site_name').' - '.'密码找回',
                    'code'      =>  $code,
                );
            $status = $obj->SendHtml($email_param);
        } else {
            return DataReturn('手机/邮箱格式有误', -1);
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
        $verify_params = array(
                'key_prefix' => 'forget',
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' =>  MyC('common_verify_time_interval'),
            );
        if($ret['data'] == 'mobile')
        {
            $obj = new \base\Sms($verify_params);
        } else if($ret['data'] == 'email')
        {
            $obj = new \base\Email($verify_params);
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
        $data = [
            $field              => $params['openid'],
            'nickname'          => empty($params['nick_name']) ? '' : $params['nick_name'],
            'avatar'            => empty($params['avatar']) ? '' : $params['avatar'],
            'gender'            => empty($params['gender']) ? 0 : ($params['gender'] == 'm') ? 2 : 1,
            'province'          => empty($params['province']) ? '' : $params['province'],
            'city'              => empty($params['city']) ? '' : $params['city'],
            'referrer'          => isset($params['referrer']) ? intval($params['referrer']) : 0,
        ];
        $user = self::UserInfo($field, $params['openid']);
        if(!empty($user))
        {
            $data = $user;
        }

        // 返回成功
        return DataReturn('授权成功', 0, $data);
    }

    /**
     * 根据字段获取用户信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-25
     * @desc    description
     * @param   [string]          $field [字段名称]
     * @param   [string]          $value [字段值]
     */
    public static function UserInfo($field, $value)
    {
        if(empty($field) || empty($value))
        {
            return '';
        }
        
        return Db::name('User')->where([$field=>$value, 'is_delete_time'=>0])->find();
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

        $user_id = Db::name('User')->insertGetId($data);
        if($user_id > 0)
        {
            // 返回前端html代码
            $body_html = [];

            // 注册成功后钩子
            $hook_name = 'plugins_service_user_register_end';
            $ret = Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'user_id'       => $user_id,
                'user'          => Db::name('User')->field('id,username,nickname,mobile,email,gender,avatar,province,city,birthday')->where(['id'=>$user_id])->find(),
                'body_html'     => &$body_html,
            ]);
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
        $verify_param = array(
                'key_prefix' => 'bind',
                'expire_time' => MyC('common_verify_expire_time')
            );
        $obj = new \base\Sms($verify_param);

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
        $where = ['mobile'=>$data['mobile'], 'is_delete_time'=>0];
        $temp_user = Db::name('User')->where($where)->find();

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
            $data['referrer'] = isset($params['referrer']) ? intval($params['referrer']) : 0;
            $data['add_time'] = time();
            $user_ret = self::UserInsert($data, $params);
            if($user_ret['code'] == 0)
            {
                $user_id = $user_ret['data']['user_id'];
            } else {
                return $user_ret;
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('User')->where($where)->update($data))
            {
                $user_id = $temp_user['id'];
            }
        }
        
        if(isset($user_id) && $user_id > 0)
        {
            // 清除验证码
            $obj->Remove();

            return DataReturn('绑定成功', 0, self::UserLoginRecord($user_id, true));
        } else {
            return DataReturn('绑定失败', -100);
        }
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
        $verify_param = array(
                'key_prefix' => 'bind',
                'expire_time' => MyC('common_verify_expire_time'),
                'time_interval' => MyC('common_verify_time_interval'),
            );

        // 发送验证码
        $obj = new \base\Sms($verify_param);
        $code = GetNumberCode(6);
        $status = $obj->SendCode($params['mobile'], $code, MyC('home_sms_user_mobile_binding'));
        
        // 状态
        if($status)
        {
            return DataReturn('发送成功', 0);
        } else {
            return DataReturn('发送失败'.'['.$obj->error.']', -100);
        }
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
        $ret = Hook::listen($hook_name, [
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

}
?>