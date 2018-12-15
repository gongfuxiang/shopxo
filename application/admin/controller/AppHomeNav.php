<?php

namespace app\admin\controller;

/**
 * 手机管理-首页导航管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppHomeNav extends Common
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
     * [Index 手机管理-首页导航列表]
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
        $m = db('AppHomeNav');

        // 条件
        $where = $this->GetIndexWhere();

        // 分页
        $number = MyC('admin_page_number');
        $page_param = array(
                'number'    =>  $number,
                'total'     =>  $m->where($where)->count(),
                'where'     =>  $param,
                'url'       =>  url('Admin/AppHomeNav/Index'),
            );
        $page = new \base\Page($page_param);

        // 获取列表
        $list = $this->SetDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('is_enable desc, sort asc')->select());

        // 参数
        $this->assign('param', $param);

        // 分页
        $this->assign('page_html', $page->GetPageHtml());

        // 是否启用
        $this->assign('common_is_enable_list', lang('common_is_enable_list'));

        // 所属平台
        $this->assign('common_platform_type', lang('common_platform_type'));

        // app事件类型
        $this->assign('common_app_event_type', lang('common_app_event_type'));

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
     * @param    [array]      $data [手机管理-首页导航数据]
     * @return   [array]            [处理好的数据]
     */
    private function SetDataHandle($data)
    {
        if(!empty($data))
        {
            $common_platform_type = lang('common_platform_type');
            $common_is_enable_tips = lang('common_is_enable_tips');
            $common_app_event_type = lang('common_app_event_type');
            foreach($data as &$v)
            {
                // 是否启用
                $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];

                // 平台类型
                $v['platform_text'] = $common_platform_type[$v['platform']]['name'];

                // 跳转类型
                $v['event_type_text'] = $common_app_event_type[$v['event_type']]['name'];

                // 图片地址
                $v['images_url'] =  empty($v['images_url']) ? '' : config('IMAGE_HOST').$v['images_url'];

                // 添加时间
                $v['add_time_text'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time_text'] = date('Y-m-d H:i:s', $v['upd_time']);
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
            if(I('is_enable', -1) > -1)
            {
                $where['is_enable'] = intval(I('is_enable', 0));
            }
            if(I('event_type', -1) > -1)
            {
                $where['event_type'] = intval(I('event_type', 0));
            }
            if(!empty($_REQUEST['platform']))
            {
                $where['platform'] = I('platform');
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
        // 手机管理-首页导航信息
        $data = empty($_REQUEST['id']) ? array() : db('AppHomeNav')->find(I('id'));
        $this->assign('data', $data);

        // 所属平台
        $this->assign('common_platform_type', lang('common_platform_type'));

        // app事件类型
        $this->assign('common_app_event_type', lang('common_app_event_type'));

        // 参数
        $this->assign('platform_type', 'alipay');
        $this->assign('param', array_merge($_POST, $_GET));

        $this->display('SaveInfo');
    }

    /**
     * [Save 手机管理-首页导航添加/编辑]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function Save()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(lang('common_unauthorized_access'));
        }

        // 图片
        $this->FileSave('images_url', 'file_images_url', 'app_home_nav');

        // 添加
        if(empty($_POST['id']))
        {
            $this->Add();

        // 编辑
        } else {
            $this->Edit();
        }
    }

    /**
     * [Add 手机管理-首页导航添加]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-18T16:20:59+0800
     */
    private function Add()
    {
        // 手机管理-首页导航模型
        $m = D('AppHomeNav');

        // 数据自动校验
        if($m->create($_POST, 1))
        {
            // 额外数据处理
            $m->name            =   I('name');
            $m->jump_url        =   I('jump_url');
            $m->event_type      =   intval(I('event_type', -1));
            $m->images_url      =   I('images_url');
            $m->platform        =   I('platform');
            $m->is_enable       =   intval(I('is_enable', 0));
            $m->bg_color        =   I('bg_color');
            $m->sort            =   intval(I('sort'));
            $m->add_time        =   time();

            // 数据添加
            if($m->add())
            {
                $this->ajaxReturn(lang('common_operation_add_success'));
            } else {
                $this->ajaxReturn(lang('common_operation_add_error'), -100);
            }
        } else {
            $this->ajaxReturn($m->getError(), -1);
        }
    }

    /**
     * [Edit 手机管理-首页导航编辑]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-17T22:13:40+0800
     */
    private function Edit()
    {
        // 手机管理-首页导航模型
        $m = D('AppHomeNav');

        // 数据自动校验
        if($m->create($_POST, 2))
        {
            // 额外数据处理
            $m->name            =   I('name');
            $m->jump_url        =   I('jump_url');
            $m->event_type      =   intval(I('event_type', -1));
            $m->images_url      =   I('images_url');
            $m->platform        =   I('platform');
            $m->is_enable       =   intval(I('is_enable', 0));
            $m->bg_color        =   I('bg_color');
            $m->sort            =   intval(I('sort'));
            $m->upd_time        =   time();

            // 更新数据库
            if($m->where(array('id'=>I('id')))->save())
            {
                $this->ajaxReturn(lang('common_operation_edit_success'));
            } else {
                $this->ajaxReturn(lang('common_operation_edit_error'), -100);
            }
        } else {
            $this->ajaxReturn($m->getError(), -1);
        }
    }

    /**
     * [Delete 手机管理-首页导航删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(lang('common_unauthorized_access'));
        }

        // 参数处理
        $id = I('id');

        // 删除数据
        if(!empty($id))
        {
            // 模型
            $m = db('AppHomeNav');

            // 是否存在
            $data = $m->find($id);
            if(empty($data))
            {
                $this->ajaxReturn(lang('common_data_no_exist_error'), -2);
            }
            if($data['is_enable'] == 1)
            {
                $this->ajaxReturn(lang('common_already_is_enable_error'), -3);
            }

            // 删除
            if($m->where(array('id'=>$id))->delete() !== false)
            {
                $this->ajaxReturn(lang('common_operation_delete_success'));
            } else {
                $this->ajaxReturn(lang('common_operation_delete_error'), -100);
            }
        } else {
            $this->ajaxReturn(lang('common_param_error'), -1);
        }
    }

    /**
     * [StatusUpdate 状态更新]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        // 参数
        if(empty($_POST['id']) || !isset($_POST['state']))
        {
            $this->ajaxReturn(lang('common_param_error'), -1);
        }

        // 数据更新
        if(db('AppHomeNav')->where(array('id'=>I('id')))->save(array('is_enable'=>I('state'))))
        {
            $this->ajaxReturn(lang('common_operation_edit_success'));
        } else {
            $this->ajaxReturn(lang('common_operation_edit_error'), -100);
        }
    }
}
?>