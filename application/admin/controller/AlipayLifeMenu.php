<?php

namespace app\admin\controller;

use Service\AlipayLifeService;

/**
 * 生活号菜单管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeMenu extends Common
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
     * [Index 生活号菜单列表]
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
        $m = db('AlipayLifeMenu');

        // 条件
        $where = $this->GetIndexWhere();

        // 分页
        $number = MyC('admin_page_number');
        $page_param = array(
                'number'    =>  $number,
                'total'     =>  $m->where($where)->count(),
                'where'     =>  $params,
                'url'       =>  url('Admin/AlipayLifeMenu/Index'),
            );
        $page = new \base\Page($page_param);

        // 获取列表
        $list = $m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select();
        $list = $this->SetDataHandle($list);

        // 参数
        $this->assign('params', $params);

        // 分页
        $this->assign('page_html', $page->GetPageHtml());

        // 发布状态
        $this->assign('common_release_status_list', lang('common_release_status_list'));

        // 菜单类型
        $this->assign('common_alipay_life_menu_type_list', lang('common_alipay_life_menu_type_list'));

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
            $common_release_status_list = lang('common_release_status_list');
            $common_alipay_life_menu_type_list = lang('common_alipay_life_menu_type_list');
            foreach($data as &$v)
            {
                // 状态
                $v['status_name'] = $common_release_status_list[$v['status']]['name'];

                // 类型
                $v['type_name'] = $common_alipay_life_menu_type_list[$v['type']]['name'];

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
            if(I('type', -1) > -1)
            {
                $where['type'] = intval(I('type', 0));
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
        $data = empty($_REQUEST['id']) ? array() : db('AlipayLifeMenu')->find(I('id'));
        $this->assign('data', $data);

        // 菜单类型
        $this->assign('common_alipay_life_menu_type_list', lang('common_alipay_life_menu_type_list'));

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

        // 参数
        $this->assign('params', array_merge($_POST, $_GET));
        $this->display('SaveInfo');
    }


    /**
     * [Index 内容列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function ContentIndex()
    {
        // 参数
        $params = array_merge($_POST, $_GET);

        // 条件
        $where = ['alipay_life_menu_id' => intval($params['menu_id']), 'pid'=>0];

        // 获取列表
        $list = $this->SetDataHandleContent(db('AlipayLifeMenuContent')->where($where)->order('sort asc')->select());
        if(!empty($list))
        {
            foreach($list as &$v)
            {
                $v['items'] =  $this->SetDataHandleContent(db('AlipayLifeMenuContent')->where(['pid'=>$v['id']])->order('sort asc')->select());
            }
        }

        // 主数据
        $data = empty($_REQUEST['menu_id']) ? array() : db('AlipayLifeMenu')->find(I('menu_id'));
        $this->assign('data', $data);

        // 参数
        $this->assign('params', $params);

        // 数据列表
        $this->assign('list', $list);
        $this->assign('list_count', count($list));
        $this->display('ContentIndex');
    }

    /**
     * 内容处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-29
     * @desc    description
     * @param    [array]      $data [数据]
     * @return   [array]            [处理好的数据]
     */
    private function SetDataHandleContent($data)
    {
        if(!empty($data))
        {
            $common_alipay_life_menu_action_type_list = lang('common_alipay_life_menu_action_type_list');
            foreach($data as &$v)
            {
                // 事件类型
                $v['action_type_name'] = $common_alipay_life_menu_action_type_list[$v['action_type']]['name'];

                // 图标
                $v['icon'] =  empty($v['icon']) ? '' : config('IMAGE_HOST').$v['icon'];

                // 时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return $data;
    }

    /**
     * 内容添加/编辑页面
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-29
     * @desc    description
     */
    public function ContentSaveInfo()
    {
        // 主数据
        $menu = empty($_REQUEST['menu_id']) ? array() : db('AlipayLifeMenu')->find(I('menu_id'));
        $this->assign('menu', $menu);

        // 数据
        $data = empty($_REQUEST['id']) ? array() : db('AlipayLifeMenuContent')->find(I('id'));
        $this->assign('data', $data);

        // 事件类型
        $this->assign('common_alipay_life_menu_action_type_list', lang('common_alipay_life_menu_action_type_list'));

        // 获取父级分类
        $this->assign('alipay_life_menu_list', db('AlipayLifeMenuContent')->field('id,name')->where(['pid'=>0, 'alipay_life_menu_id'=>$menu['id']])->select());

        // 参数
        $this->assign('params', array_merge($_POST, $_GET));
        $this->display('ContentSaveInfo');
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
        $list = AlipayLifeService::MenuDetailList($params);

        // 参数
        $this->assign('params', $params);

        // 数据列表
        $this->assign('list', $list);
        $this->display('Detail');
    }

    /**
     * [Save 生活号菜单保存]
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

        $ret = AlipayLifeService::MenuSave($_POST);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [ContentSave 生活号菜单内容保存]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function ContentSave()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        $ret = AlipayLifeService::MenuContentSave($_POST);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Delete 生活号菜单删除]
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
        $id = intval(I('id'));
        $m = db('AlipayLifeMenu');
        $m->startTrans();
        if($m->delete($id) && db('AlipayLifeMenuContent')->where(['alipay_life_message_id'=>$id])->delete())
        {
            $m->commit();
            $this->ajaxReturn('删除成功');
        }
        $m->rollback();
        $this->ajaxReturn('删除失败或资源不存在', -100);
    }

    /**
     * [Delete 生活号菜单内容删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function ContentDelete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 删除
        if(db('AlipayLifeMenuContent')->delete(intval(I('id'))))
        {
            $this->ajaxReturn('删除成功');
        }
        $this->ajaxReturn('删除失败或资源不存在', -100);
    }

    /**
     * 发送菜单
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-24
     * @desc    description
     */
    public function Release()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        $ret = AlipayLifeService::MenuSubmit($_POST);
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

        $ret = AlipayLifeService::AlipayLifeSearch($_POST);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>