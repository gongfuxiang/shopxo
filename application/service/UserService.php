<?php

namespace app\service;

use think\Db;
use app\service\ResourcesService;
use app\service\MessageService;
use app\service\RegionService;

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
        $data = db('User')->where($where)->order($order_by)->limit($m, $n)->select();
        if(!empty($data))
        {
            $common_gender_list = lang('common_gender_list');
            foreach($data as &$v)
            {
                // 生日
                $v['birthday_text'] = empty($v['birthday']) ? '' : date('Y-m-d', $v['birthday']);

                // 头像
                if(!empty($v['avatar']))
                {
                    if(substr($v['avatar'], 0, 4) != 'http')
                    {
                        $v['avatar'] = config('IMAGE_HOST').$v['avatar'];
                    }
                } else {
                    $v['avatar'] = config('IMAGE_HOST').'/static/index/'.strtolower(MyC('cache_common_default_theme_data', 'default')).'/images/default-user-avatar.jpg';
                }

                // 注册时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

                // 性别
                $v['gender_text'] = $common_gender_list[$v['gender']]['name'];
            }
        }
        return $data;
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
        return (int) db('User')->where($where)->count();
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
                'checked_data'      => [0,1,2],
                'error_msg'         => '性别值范围不正确',
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
        $ret = params_checked($params, $p);
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
            $user = db('User')->field('id,integral')->find($params['id']);
            if(empty($user))
            {
                return DataReturn('用户信息不存在', -10);
            }

            $data['upd_time'] = time();
            if(db('User')->where(['id'=>$params['id']])->update($data))
            {
                $user_id = $params['id'];
            }
        } else {
            $data['add_time'] = time();
            $user_id = db('User')->insertGetId($data);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
           
        // 删除操作
        if(db('User')->delete(intval($params['id'])))
        {
            return DataReturn(lang('common_operation_delete_success'));
        }
        return DataReturn(lang('common_operation_delete_error'), -100);
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
        $data = db('UserAddress')->where($where)->field($field)->order('id desc')->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['province_name'] = RegionService::RegionName($v['province']);
                $v['city_name'] = RegionService::RegionName($v['city']);
                $v['county_name'] = RegionService::RegionName($v['county']);
            }
        }
        return DataReturn(lang('common_operation_success'), 0, $data);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
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
            return DataReturn($ret, -1);
        }

        if(!empty($params['id']))
        {
            $where = ['user_id' => $params['user']['id'], 'id'=>$params['id']];
            $temp = db('UserAddress')->where($where)->find();
        }
        
        // 操作数据
        $is_default = isset($params['is_default']) ? intval($params['is_default']) : 0;
        $data = [
            'name'          => $params['name'],
            'alias'         => $params['alias'],
            'tel'           => $params['tel'],
            'province'      => $params['province'],
            'city'          => $params['city'],
            'county'        => $params['county'],
            'address'       => $params['address'],
            'is_default'    => $is_default,
            'lng'           => floatval($params['lng']),
            'lat'           => floatval($params['lat']),
        ];

        Db::startTrans();

        // 默认地址处理
        if($is_default == 1)
        {
            db('UserAddress')->where(['user_id'=>$params['user']['id'], 'is_default'=>1])->update(['is_default'=>0]);
        }

        // 添加/更新数据
        if(empty($temp))
        {
            $data['user_id'] = $params['user']['id'];
            $data['add_time'] = time();
            if(db('UserAddress')->insertGetId($data) > 0)
            {
                Db::commit();
                return DataReturn(lang('common_operation_add_success'), 0);
            } else {
                Db::rollback();
                return DataReturn(lang('common_operation_add_error'));
            }
        } else {
            $data['upd_time'] = time();
            if(db('UserAddress')->where($where)->update($data))
            {
                Db::commit();
                return DataReturn(lang('common_operation_update_success'), 0);
            } else {
                Db::rollback();
                return DataReturn(lang('common_operation_update_error'));
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
            return DataReturn($ret, -1);
        }

        // 软删除数据
        $where = ['user_id' => $params['user']['id'], 'id'=>$params['id']];
        $data = ['is_delete_time' => time()];
        if(db('UserAddress')->where($where)->update($data))
        {
            return DataReturn(lang('common_operation_delete_success'), 0);
        } else {
            return DataReturn(lang('common_operation_delete_error'), -100);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开启事务
        Db::startTrans();

        // 先全部设置为0 再将当前设置为1
        $all_status = db('UserAddress')->where(['user_id' => $params['user']['id']])->update(['is_default'=>0]);
        $my_status = db('UserAddress')->where(['user_id' => $params['user']['id'], 'id'=>$params['id']])->update(['is_default'=>1]);
        if($all_status !== false && $my_status)
        {
            // 提交事务
            Db::commit();
            return DataReturn(lang('common_operation_set_success'), 0);
        } else {
            // 回滚事务
            Db::rollback();
            return DataReturn(lang('common_operation_set_error'), -100);
        }
    }

    /**
     * [UserLoginRecord 用户登录记录]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T11:37:43+0800
     * @param    [int]     $user_id [用户id]
     * @return   [boolean]          [记录成功true, 失败false]
     */
    public static function UserLoginRecord($user_id = 0)
    {
        if(!empty($user_id))
        {
            $user = db('User')->field('*')->find($user_id);
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
                    if(substr($user['avatar'], 0, 4) != 'http')
                    {
                        $user['avatar'] = config('IMAGE_HOST').$user['avatar'];
                    }
                } else {
                    $user['avatar'] = config('IMAGE_HOST').'/static/index/'.strtolower(config('DEFAULT_THEME', 'default')).'/images/default-user-avatar.jpg';
                }

                // 存储session
                session('user', $user);
                return (session('user') !== null);
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
    public function UserAvatarUpload($params = [])
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 开始处理图片存储
        // 定义图片目录
        $root_path = ROOT_PATH;
        $img_path = 'Public'.DS.'Upload'.DS.'user_avatar'.DS;
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
        if(db('User')->where(['id'=>$params['user']['id']])->update($data))
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
            return DataReturn(lang('common_close_user_login_tips'), -1);
        }

        // 登录帐号格式校验
        if(!CheckMobile($params['accounts']) && !CheckEmail($params['accounts']))
        {
            return DataReturn(lang('user_login_accounts_format'), -1);
        }

        // 密码
        $pwd = trim($params['pwd']);
        if(!CheckLoginPwd($pwd))
        {
            return DataReturn(lang('user_reg_pwd_format'), -2);
        }

        // 获取用户账户信息
        $where = array('mobile|email' => $params['accounts'], 'is_delete_time'=>0);
        $user = db('User')->field(array('id', 'pwd', 'salt', 'status'))->where($where)->find();
        if(empty($user))
        {
            return DataReturn(lang('user_login_accounts_on_exist_error'), -3);
        }
        // 用户状态
        if($user['status'] == 2)
        {
            return DataReturn(lang('common_user_status_list')[$user['status']]['tips'], -10);
        }

        // 密码校验
        if(LoginPwdEncryption($pwd, $user['salt']) != $user['pwd'])
        {
            return DataReturn(lang('user_common_pwd_error'), -4);
        }

        // 更新用户密码
        $salt = GetNumberCode(6);
        $data = array(
                'pwd'       =>  LoginPwdEncryption($pwd, $salt),
                'salt'      =>  $salt,
                'upd_time'  =>  time(),
            );
        if(db('User')->where(array('id'=>$user['id']))->update($data) !== false)
        {
            // 登录记录
            if(self::UserLoginRecord($user['id']))
            {
                return DataReturn(lang('common_login_success'), 0);
            }
        }
        return DataReturn(lang('common_login_invalid'), -100);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('home_user_reg_state')))
        {
            return DataReturn(lang('common_close_user_reg_tips'), -1);
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
            return DataReturn(lang('common_verify_expire'), -10);
        }
        // 是否正确
        if(!$obj->CheckCorrect($params['verify']))
        {
            return DataReturn(lang('common_verify_error'), -11);
        }

        $salt = GetNumberCode(6);
        $data = [
            'add_time'      => time(),
            'upd_time'      => time(),
            'salt'          => $salt,
            'pwd'           => LoginPwdEncryption($params['pwd'], $salt),
        ];
        if($params['type'] == 'sms')
        {
            $data['mobile'] = $params['accounts'];
        } else {
            $data['email'] = $params['accounts'];
        }

        // 数据添加
        $user_id = db('User')->insertGetId($data);
        if($user_id > 0)
        {
            // 清除验证码
            $obj->Remove();

            if(self::UserLoginRecord($user_id))
            {
                return DataReturn(lang('common_reg_success'), 0);
            }
            return DataReturn(lang('common_reg_success_login_tips'));
        }
        return DataReturn(lang('common_reg_error'), -100);
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
             return DataReturn(lang('common_param_error'), -1);
        }

        // 手机号码
        if($type == 'sms')
        {
            // 手机号码格式
            if(!CheckMobile($accounts))
            {
                 return DataReturn(lang('common_mobile_format_error'), -2);
            }

            // 手机号码是否已存在
            if(self::IsExistAccounts($accounts, 'mobile'))
            {
                 return DataReturn(lang('common_mobile_exist_error'), -3);
            }

        // 电子邮箱
        } else {
            // 电子邮箱格式
            if(!CheckEmail($accounts))
            {
                 return DataReturn(lang('common_email_format_error'), -2);
            }

            // 电子邮箱是否已存在
            if(self::IsExistAccounts($accounts, 'email'))
            {
                 return DataReturn(lang('common_email_exist_error'), -3);
            }
        }
        return DataReturn(lang('common_operation_success'), 0);
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
        $id = db('User')->where(array($field=>$accounts))->value('id');
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
                return DataReturn(lang('common_param_error'), -10);
            }
            $verify = new \base\Verify($verify_params);
            if(!$verify->CheckExpire())
            {
                return DataReturn(lang('common_verify_expire'), -11);
            }
            if(!$verify->CheckCorrect($params['verify']))
            {
                return DataReturn(lang('common_verify_error'), -12);
            }
            return DataReturn(lang('common_operation_success'), 0, $verify);
        }
        return DataReturn(lang('common_operation_success'), 0);
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
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 是否开启用户注册
        if(!in_array($params['type'], MyC('home_user_reg_state')))
        {
            return DataReturn(lang('common_close_user_reg_tips'));
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
                    'title'     =>  MyC('home_site_name').' - '.lang('common_email_send_user_reg_title'),
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

            return DataReturn(lang('common_send_success'), 0);
        } else {
            return DataReturn(lang('common_send_error').'['.$obj->error.']', -100);
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
            return DataReturn(lang('common_param_error'), -10);
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
                    'title'     =>  MyC('home_site_name').' - '.lang('common_email_send_user_forget_title'),
                    'code'      =>  $code,
                );
            $status = $obj->SendHtml($email_param);
        } else {
            return DataReturn(lang('user_login_accounts_format'), -1);
        }

        // 状态
        if($status)
        {
            // 清除验证码
            if(isset($verify['data']) && is_object($verify['data']))
            {
                $verify['data']->Remove();
            }

            return DataReturn(lang('common_send_success'), 0);
        } else {
            return DataReturn(lang('common_send_error').'['.$obj->error.']', -100);
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
                return DataReturn(lang('common_mobile_no_exist_error'), -3);
            }
            return DataReturn(lang('common_operation_success'), 0, 'mobile');
        } else if(CheckEmail($accounts))
        {
            if(!self::IsExistAccounts($accounts, 'email'))
            {
                return DataReturn(lang('common_email_no_exist_error'), -3);
            }
            return DataReturn(lang('common_operation_success'), 0, 'email');
        }
        return DataReturn(lang('common_accounts_format_error'), -4);
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
        $ret = params_checked($params, $p);
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
            return DataReturn(lang('common_verify_expire'), -10);
        }
        // 是否正确
        if(!$obj->CheckCorrect($params['verify']))
        {
            return DataReturn(lang('common_verify_error'), -11);
        }

        // 更新用户密码
        $salt = GetNumberCode(6);
        $data = array(
                'pwd'       =>  LoginPwdEncryption($params['pwd'], $salt),
                'salt'      =>  $salt,
                'upd_time'  =>  time(),
            );
        if(db('User')->where(array($ret['data']=>$params['accounts']))->update($data) !== false)
        {
            return DataReturn(lang('common_operation_success'));
        }
        return DataReturn(lang('common_operation_error'), -100);
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
                'checked_type'      => 'empty',
                'key_name'          => 'birthday',
                'error_msg'         => '请填写生日',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'gender',
                'error_msg'         => '请选择性别',
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
            return DataReturn($ret, -1);
        }

        // 更新数据库
        $data = [
            'birthday'      => strtotime($params['birthday']),
            'nickname'      => $params['nickname'],
            'gender'        => intval($params['gender']),
            'upd_time'      => time(),
        ];
        if(db('User')->where(array('id'=>$params['user']['id']))->update($data))
        {
            // 更新用户session数据
            self::UserLoginRecord($params['user']['id']);

            return DataReturn(lang('common_operation_edit_success'), 0);
        }
        return DataReturn(lang('common_operation_edit_error'), -100);
    }

}
?>