<?php

namespace Api\Controller;

/**
 * 用户留言
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AnswerController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
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
    }

    /**
     * [Index 获取列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 登录校验
        $this->Is_Login();

        $m = M('Answer');

        $number = 10;
        $page = intval(I('page', 1));
        $where = ['user_id' => $this->user['id'], 'is_delete_time'=>0];
        $total = $m->where($where)->count();
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);
        $field = '*';
        $data = $m->where($where)->field($field)->limit($start, $number)->order('id desc')->select();

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $this->SetDataList($data),
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * [SetDataList 数据处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-17T17:55:36+0800
     * @param    [array]      $data     [组织数据]
     * @return   [array]                [处理好的数据]
     */
    private function SetDataList($data)
    {
        if(!empty($data))
        {
            foreach($data as &$v)
            {
                // 添加时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }
        return $data;
    }

    /**
     * 用户留言添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     */
    public function Add()
    {
        // 登录校验
        $this->Is_Login();

        // 参数校验
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '联系人有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'tel',
                'error_msg'         => '联系电话有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'content',
                'error_msg'         => '详细内容有误',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 开始操作
        $m = M('Answer');
        $data = [
            'user_id'       => $this->user['id'],
            'name'          => I('name'),
            'tel'           => I('tel'),
            'content'       => I('content'),
            'add_time'      => time(),
        ];
        if($m->add($data) > 0)
        {
            $this->ajaxReturn(L('common_submit_success'), 0);
        } else {
            $this->ajaxReturn(L('common_submit_error'));
        }
    }

    /**
     * [Common 公共获取列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Common()
    {
        $m = M('Answer');

        $number = 10;
        $page = intval(I('page', 1));
        $where = ['a.is_show'=>1, 'a.is_delete_time'=>0];
        $total = $m->alias('a')->join(' INNER JOIN __USER__ AS u ON u.id=a.user_id')->where($where)->count();
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);
        $field = 'a.*, u.avatar';
        $data = $m->alias('a')->join(' INNER JOIN __USER__ AS u ON u.id=a.user_id')->where($where)->field($field)->limit($start, $number)->order('a.id desc')->select();

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $this->SetDataList($data),
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }
}
?>