<?php

namespace app\admin\controller;

use Service\AlipayLifeService;

/**
 * 生活号批量上下架管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeStatus extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 登录校验
        $this->Is_Login();

        // 权限校验
        $this->Is_Power();
    }

    /**
     * [Index 生活号批量上下架列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 参数
        $params = array_merge($_POST, $_GET);

        // 模型对象
        $m = db('AlipayLifeStatus');

        // 条件
        $where = $this->GetIndexWhere();

        // 分页
        $number = MyC('admin_page_number');
        $page_param = array(
                'number'    =>  $number,
                'total'     =>  $m->where($where)->count(),
                'where'     =>  $params,
                'url'       =>  url('Admin/AlipayLifeStatus/Index'),
            );
        $page = new \base\Page($page_param);

        // 获取列表
        $list = $m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select();
        $list = $this->SetDataHandle($list);

        // 参数
        $this->assign('params', $params);

        // 分页
        $this->assign('page_html', $page->GetPageHtml());

        // 处理状态
        $this->assign('common_handle_status_list', lang('common_handle_status_list'));

        // 上下架
        $this->assign('common_shelves_select_list', lang('common_shelves_select_list'));

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
            $common_handle_status_list = lang('common_handle_status_list');
            $common_shelves_select_list = lang('common_shelves_select_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_handle_status_list[$v['status']]['name'];

                // 上下架
                $v['is_shelves_name'] = $common_shelves_select_list[$v['is_shelves']]['name'];

                // 生活号
                $v['alipay_life_all'] = empty($v['alipay_life_ids']) ? '' : db('AlipayLife')->where(['id'=>['in', json_decode($v['alipay_life_ids'], true)]])->getField('name', true);

                // 时间
                $v['startup_time'] = empty($v['startup_time']) ? '' : date('Y-m-d H:i:s', $v['startup_time']);
                $v['success_time'] = empty($v['success_time']) ? '' : date('Y-m-d H:i:s', $v['success_time']);
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
            $where['name'] = array('like', '%'.I('keyword').'%');
        }

        // 是否更多条件
        if(I('is_more', 0) == 1)
        {
            if(I('status', -1) > -1)
            {
                $where['status'] = intval(I('status', 0));
            }
            if(I('is_shelves', -1) > -1)
            {
                $where['is_shelves'] = intval(I('is_shelves', 0));
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
        // 数据
        $data = empty($_REQUEST['id']) ? array() : db('AlipayLifeStatus')->find(I('id'));
        $this->assign('data', $data);

        // 生活号
        $alipay_life_list = [];
        $alipay_life_ids_all = [];
        if(!empty($_GET['alipay_life_id']))
        {
            $alipay_life_ids_all = [intval(I('alipay_life_id'))];
        }
        if(!empty($data['alipay_life_ids']))
        {
            $alipay_life_ids_all = json_decode($data['alipay_life_ids'], true);
        }
        if(!empty($alipay_life_ids_all))
        {
            $alipay_life_list = db('AlipayLife')->field('id,name')->where(['id'=>['in', $alipay_life_ids_all]])->select();
        }
        $this->assign('alipay_life_ids_all', $alipay_life_ids_all);
        $this->assign('alipay_life_list', $alipay_life_list);

        // 生活号分类
        $alipay_life_category = db('AlipayLifeCategory')->where(['is_enable'=>1])->field('id,name')->select();
        $this->assign('alipay_life_category', $alipay_life_category);

        // 上下架
        $this->assign('common_shelves_select_list', lang('common_shelves_select_list'));

        // 参数
        $this->assign('params', array_merge($_POST, $_GET));
        $this->display('SaveInfo');
    }

    /**
     * 详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-30
     * @desc    description
     */
    public function Detail()
    {
        // 参数
        $params = array_merge($_POST, $_GET);

        // 获取列表
        $list = AlipayLifeService::StatusDetailList($params);

        // 参数
        $this->assign('params', $params);

        // 数据列表
        $this->assign('list', $list);
        $this->display('Detail');
    }

    /**
     * [Save 生活号批量上下架保存]
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
            $this->error('非法访问');
        }

        $ret = AlipayLifeService::LifeStatusSave($_POST);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Delete 生活号批量上下架删除]
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
            $this->error('非法访问');
        }

        // 删除
        if(db('AlipayLifeStatus')->delete(intval(I('id'))))
        {
            $this->ajaxReturn('删除成功');
        }
        $this->ajaxReturn('删除失败或资源不存在', -100);
    }

    /**
     * 提交
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     */
    public function Submit()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        $ret = AlipayLifeService::LifeStatusSubmit($_POST);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * 生活号搜索
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-29
     * @desc    description
     */
    public function Search()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        $params = $_POST;
        $params['is_all'] = 1;
        $ret = AlipayLifeService::AlipayLifeSearch($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>