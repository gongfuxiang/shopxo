<?php

namespace Admin\Controller;

use Service\AlipayLifeService;

/**
 * 生活号消息管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeMessageController extends CommonController
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

        // 登录校验
        $this->Is_Login();

        // 权限校验
        $this->Is_Power();
    }

    /**
     * [Index 生活号消息列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 参数
        $param = array_merge($_POST, $_GET);

        // 模型对象
        $m = M('AlipayLifeMessage');

        // 条件
        $where = $this->GetIndexWhere();

        // 分页
        $number = MyC('admin_page_number');
        $page_param = array(
                'number'    =>  $number,
                'total'     =>  $m->where($where)->count(),
                'where'     =>  $param,
                'url'       =>  U('Admin/AlipayLifeMessage/Index'),
            );
        $page = new \Library\Page($page_param);

        // 获取列表
        $list = $m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select();
        $list = $this->SetDataHandle($list);

        // 参数
        $this->assign('param', $param);

        // 分页
        $this->assign('page_html', $page->GetPageHtml());

        // 发送状态
        $this->assign('common_send_status_list', L('common_send_status_list'));

        // 消息类型
        $this->assign('alipay_life_message_type_list', L('alipay_life_message_type_list'));

        // 发送类型
        $this->assign('alipay_life_message_send_type_list', L('alipay_life_message_send_type_list'));

        // 数据列表
        $this->assign('list', $list);
        $this->display('Index');
    }

    /**
     * [SetDataHandle 数据处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-29T21:27:15+0800
     * @param    [array]      $data [轮播图片数据]
     * @return   [array]            [处理好的数据]
     */
    private function SetDataHandle($data)
    {
        if(!empty($data))
        {
            $common_send_status_list = L('common_send_status_list');
            $alipay_life_message_type_list = L('alipay_life_message_type_list');
            $alipay_life_message_send_type_list = L('alipay_life_message_send_type_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_send_status_list[$v['status']]['name'];

                // 消息类型
                $v['type_name'] = $alipay_life_message_type_list[$v['type']]['name'];

                // 发送状态
                $v['send_type_name'] = $alipay_life_message_send_type_list[$v['send_type']]['name'];

                // 分类名称
                $category_all = M('AlipayLifeMessageCategoryJoin')->where(['alipay_life_id'=>$v['id']])->getField('alipay_life_category_id', true);
                $v['alipay_life_category_text'] = M('AlipayLifeMessageCategory')->where(['id'=>['in', $category_all]])->getField('name', true);

                // image_url
                $v['image_url'] =  empty($v['image_url']) ? '' : C('IMAGE_HOST').$v['image_url'];

                // 生活号分类
                $v['category_all'] = empty($v['alipay_life_category_id']) ? '' : M('AlipayLifeCategory')->where(['id'=>['in', json_decode($v['alipay_life_category_id'], true)]])->getField('name', true);

                // 生活号
                $v['alipay_life_name'] = empty($v['alipay_life_id']) ? '' : M('AlipayLife')->where(['id'=>$v['alipay_life_id']])->getField('name');

                // 时间
                $v['send_startup_time'] = empty($v['send_startup_time']) ? '' : date('Y-m-d H:i:s', $v['send_startup_time']);
                $v['send_success_time'] = empty($v['send_success_time']) ? '' : date('Y-m-d H:i:s', $v['send_success_time']);
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return $data;
    }

    /**
     * [GetIndexWhere 列表条件]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     */
    private function GetIndexWhere()
    {
        $where = array();

        // 模糊
        if(!empty($_REQUEST['keyword']))
        {
            $where['title'] = array('like', '%'.I('keyword').'%');
        }

        // 是否更多条件
        if(I('is_more', 0) == 1)
        {
            if(I('status', -1) > -1)
            {
                $where['status'] = intval(I('status', 0));
            }
            if(I('type', -1) > -1)
            {
                $where['type'] = intval(I('type', 0));
            }
            if(I('send_type', -1) > -1)
            {
                $where['send_type'] = intval(I('send_type', 0));
            }

            // 表达式
            if(!empty($_REQUEST['time_start']))
            {
                $where['add_time'][] = array('gt', strtotime(I('time_start')));
            }
            if(!empty($_REQUEST['time_end']))
            {
                $where['add_time'][] = array('lt', strtotime(I('time_end')));
            }
        }
        return $where;
    }

    /**
     * [SaveInfo 添加/编辑页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 轮播图片信息
        $data = empty($_REQUEST['id']) ? array() : M('AlipayLifeMessage')->find(I('id'));
        $this->assign('data', $data);

        // 发送状态
        $this->assign('common_send_status_list', L('common_send_status_list'));

        // 消息类型
        $this->assign('alipay_life_message_type_list', L('alipay_life_message_type_list'));

        // 单用户发消息用户信息
        $alipay_openid = empty($_GET['user_id']) ? '' : M('User')->where(['id'=>intval(I('user_id'))])->getField('alipay_openid');
        $this->assign('alipay_openid', $alipay_openid);

        // 消息发送类型
        $this->assign('alipay_life_message_send_type_list', L('alipay_life_message_send_type_list'));
        $this->assign('send_type', empty($alipay_openid) ? 1 : 0);

        // 生活号
        if(!empty($_GET['alipay_life_id']))
        {
            $alipay_life_list = M('AlipayLife')->field('id,name')->where(['id'=>intval(I('alipay_life_id'))])->select();
        } else {
            $alipay_life_list = [];
        }
        $this->assign('alipay_life_list', $alipay_life_list);

        // 生活号分类
        if(empty($alipay_openid))
        {
            $alipay_life_category = M('AlipayLifeCategory')->where(['is_enable'=>1])->field('id,name')->select();
        } else {
            $alipay_life_category = [];
        }
        $this->assign('alipay_life_category', $alipay_life_category);

        // 参数
        $this->assign('params', array_merge($_POST, $_GET));
        $this->assign('nav_type', I('nav_type', 0));
        $this->display('SaveInfo');
    }

    /**
     * [Save 生活号消息保存]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Save()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        $ret = AlipayLifeService::MessageAdd($_POST);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Delete 生活号消息删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 删除
        if(M('AlipayLifeMessage')->delete(intval(I('id'))))
        {
            $this->ajaxReturn(L('common_operation_delete_success'));
        } else {
            $this->ajaxReturn(L('common_operation_delete_error'), -100);
        }
    }

    /**
     * 发送消息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     */
    public function Send()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        $ret = AlipayLifeService::MessageSubmit($_POST);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>