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

        $m->startTrans();

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
                $m->commit();
                return DataReturn(L('common_operation_add_success'), 0);
            } else {
                $m->rollback();
                return DataReturn(L('common_operation_add_error'));
            }
        } else {
            $data['upd_time'] = time();
            if($m->where($where)->save($data))
            {
                $m->commit();
                return DataReturn(L('common_operation_update_success'), 0);
            } else {
                $m->rollback();
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
            return DataReturn($ret, -1);
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

        // 模型
        $m = M('UserAddress');

        // 开启事务
        $m->startTrans();

        // 先全部设置为0 再将当前设置为1
        $all_status = $m->where(['user_id' => $params['user']['id']])->save(['is_default'=>0]);
        $my_status = $m->where(['user_id' => $params['user']['id'], 'id'=>$params['id']])->save(['is_default'=>1]);
        if($all_status && $my_status)
        {
            // 提交事务
            $m->commit();
            return DataReturn(L('common_operation_set_success'), 0);
        } else {
            // 回滚事务
            $m->rollback();
            return DataReturn(L('common_operation_set_error'), -100);
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
            $user = M('User')->field('*')->find($user_id);
            if(!empty($user))
            {
                // 基础数据处理
                $user['add_time_text']  =   date('Y-m-d H:i:s', $user['add_time']);
                $user['upd_time_text']  =   date('Y-m-d H:i:s', $user['upd_time']);
                $user['gender_text']    =   L('common_gender_list')[$user['gender']]['name'];
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
                        $user['avatar'] = C('IMAGE_HOST').$user['avatar'];
                    }
                } else {
                    $user['avatar'] = C('IMAGE_HOST').'/Public/Home/'.C('DEFAULT_THEME').'/Images/default-user-avatar.jpg';
                }

                // 存储session
                $_SESSION['user'] = $user;
                return !empty($_SESSION['user']);
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
        $images_obj = \Library\Images::Instance(['is_new_name'=>false]);

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
        if(M('User')->where(['id'=>$params['user']['id']])->save($data))
        {
            self::UserLoginRecord($params['user']['id']);
            return DataReturn('上传成功', 0);
        }
        return DataReturn('上传失败', -100);
    }

}
?>