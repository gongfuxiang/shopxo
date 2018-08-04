<?php

namespace Api\Controller;

/**
 * 用户地址
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class UserAddressController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-02T22:48:35+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 登录校验
        $this->Is_Login();
    }

    /**
     * 获取用户地址详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function GetDetail()
    {
        // 请求参数
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'address_id',
                'error_msg'         => '地址ID不能为空',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 获取数据
        $field = 'id,alias,name,tel,province,city,county,address,lng,lat,is_default';
        $where = ['user_id'=>$this->user['id'], 'is_delete_time'=>0, 'id'=>intval($this->data_post['address_id'])];
        $data = M('UserAddress')->where($where)->field($field)->find();
        if(!empty($address))
        {
            $data['province_name'] = GetRegionName($data['province']);
            $data['city_name'] = GetRegionName($data['city']);
            $data['county_name'] = GetRegionName($data['county']);
        }

        $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * 获取用户地址列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function GetList()
    {
        $field = 'id,alias,name,tel,province,city,county,address,lng,lat,is_default';
        $where = ['user_id'=>$this->user['id'], 'is_delete_time'=>0];
        $data = M('UserAddress')->where($where)->field($field)->order('id desc')->select();
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                $v['province_name'] = GetRegionName($v['province']);
                $v['city_name'] = GetRegionName($v['city']);
                $v['county_name'] = GetRegionName($v['county']);
            }
        }
        $this->ajaxReturn(L('common_operation_success'), 0, $data);
    }

    /**
     * 用户地址编辑
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function Edit()
    {
        // 请求参数
        $params = [
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
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        $m = M('UserAddress');
        if(!empty($this->data_post['address_id']))
        {
            $where = ['user_id' => $this->user['id'], 'id'=>$this->data_post['address_id']];
            $temp = $m->where($where)->find();
        }
        
        $data = [
            'name'      => I('name'),
            'tel'       => I('tel'),
            'province'  => I('province'),
            'city'      => I('city'),
            'county'    => I('county'),
            'address'   => I('address'),
        ];
        if(empty($temp))
        {
            $data['user_id'] = $this->user['id'];
            $data['add_time'] = time();
            if($m->add($data) > 0)
            {
                $this->ajaxReturn(L('common_operation_add_success'), 0);
            } else {
                $this->ajaxReturn(L('common_operation_add_error'));
            }
        } else {
            $data['upd_time'] = time();
            if($m->where($where)->save($data))
            {
                $this->ajaxReturn(L('common_operation_update_success'), 0);
            } else {
                $this->ajaxReturn(L('common_operation_update_error'));
            }
        }
    }

    /**
     * 删除地址
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function Delete()
    {
        // 请求参数
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'address_id',
                'error_msg'         => '地址ID不能为空',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 软删除数据
        $where = ['user_id' => $this->user['id'], 'id'=>$this->data_post['address_id']];
        $data = ['is_delete_time' => time()];
        if(M('UserAddress')->where($where)->save($data))
        {
            $this->ajaxReturn(L('common_operation_delete_success'), 0);
        } else {
            $this->ajaxReturn(L('common_operation_delete_error'), -100);
        }
    }

    /**
     * 默认地址设置
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function SetDefault()
    {
        // 请求参数
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'address_id',
                'error_msg'         => '地址ID不能为空',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 模型
        $m = M('UserAddress');

        // 开启事务
        $m->startTrans();

        // 先全部设置为0 再将当前设置为1
        $all_status = $m->where(['user_id' => $this->user['id']])->save(['is_default'=>0]);
        $my_status = $m->where(['user_id' => $this->user['id'], 'id'=>$this->data_post['address_id']])->save(['is_default'=>1]);
        if($all_status && $my_status)
        {
            // 提交事务
            $m->commit();
            $this->ajaxReturn(L('common_operation_set_success'), 0);
        } else {
            // 回滚事务
            $m->rollback();
            $this->ajaxReturn(L('common_operation_delete_error'), -100);
        }
    }

}
?>