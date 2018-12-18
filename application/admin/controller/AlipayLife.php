<?php

namespace app\admin\controller;

use Service\AlipayLifeService;

/**
 * 生活号管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLife extends Common
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
     * [Index 生活号列表]
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
        $m = db('AlipayLife');

        // 条件
        $where = $this->GetIndexWhere();

        // 分页
        $number = MyC('admin_page_number');
        $page_param = array(
                'number'    =>  $number,
                'total'     =>  $m->alias('a')->join('INNER JOIN __ALIPAY_LIFE_CATEGORY_JOIN__ AS cj ON a.id=cj.alipay_life_id')->where($where)->count('DISTINCT a.id'),
                'where'     =>  $param,
                'url'       =>  url('Admin/AlipayLife/Index'),
            );
        $page = new \base\Page($page_param);

        // 获取列表
        $list = $m->alias('a')->field('a.*')->join('INNER JOIN __ALIPAY_LIFE_CATEGORY_JOIN__ AS cj ON a.id=cj.alipay_life_id')->where($where)->limit($page->GetPageStarNumber(), $number)->order('a.id desc')->group('a.id')->select();
        $list = $this->SetDataHandle($list);

        // 参数
        $this->assign('param', $param);

        // 分页
        $this->assign('page_html', $page->GetPageHtml());

        // 是否上下架
        $this->assign('common_goods_is_shelves_list', lang('common_goods_is_shelves_list'));

        // 生活号分类
        $alipay_life_category = db('AlipayLifeCategory')->where(['is_enable'=>1])->field('id,name')->select();
        $this->assign('alipay_life_category', $alipay_life_category);

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
            $common_is_enable_tips = lang('common_is_enable_tips');
            foreach($data as &$v)
            {
                // 分类名称
                $category_all = db('AlipayLifeCategoryJoin')->where(['alipay_life_id'=>$v['id']])->getField('alipay_life_category_id', true);
                $v['alipay_life_category_text'] = db('AlipayLifeCategory')->where(['id'=>['in', $category_all]])->getField('name', true);

                // logo
                $v['logo'] =  empty($v['logo']) ? '' : config('IMAGE_HOST').$v['logo'];

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
            $where['a.name'] = array('like', '%'.I('keyword').'%');
        }

        // 是否更多条件
        if(I('is_more', 0) == 1)
        {
            if(I('is_shelves', -1) > -1)
            {
                $where['a.is_shelves'] = intval(I('is_shelves', 0));
            }
            if(I('alipay_life_category_id', -1) > -1)
            {
                $where['cj.alipay_life_category_id'] = intval(I('alipay_life_category_id', 0));
            }

            // 表达式
            if(!empty($_REQUEST['time_start']))
            {
                $where['a.add_time'][] = array('gt', strtotime(I('time_start')));
            }
            if(!empty($_REQUEST['time_end']))
            {
                $where['a.add_time'][] = array('lt', strtotime(I('time_end')));
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
        $data = empty($_REQUEST['id']) ? array() : db('AlipayLife')->find(I('id'));

        // 获取分类关联数据
        $data['category_all'] = db('AlipayLifeCategoryJoin')->where(['alipay_life_id'=>$data['id']])->getField('alipay_life_category_id', true);
        $this->assign('data', $data);

        // 是否上下架
        $this->assign('common_goods_is_shelves_list', lang('common_goods_is_shelves_list'));

        // 生活号分类
        $alipay_life_category = db('AlipayLifeCategory')->where(['is_enable'=>1])->field('id,name')->select();
        $this->assign('alipay_life_category', $alipay_life_category);

        // 参数
        $this->assign('param', array_merge($_POST, $_GET));

        $this->display('SaveInfo');
    }

    /**
     * [Save 生活号保存]
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

        // 图片
        $this->FileSave('logo', 'file_logo', 'alipay_life');

        // id为空则表示是新增
        $m = D('AlipayLife');

        // 开启事务
        $m->startTrans();

        // 分类
        $category_m = db('AlipayLifeCategoryJoin');
        if(empty($_POST['id']))
        {
            $type = 1;
        } else {
            $type = 2;
            $category_m->where(['alipay_life_id'=>I('id')])->delete();
        }

        $status = false;
        $msg = '';
        $alipay_life_id = I('id', 0);
        if($m->create($_POST, $type))
        {
            // 额外数据处理
            $m->upd_time            =   time();
            $m->name                =   I('name');
            $m->appid               =   I('appid');
            $m->rsa_public          =   I('rsa_public');
            $m->rsa_private         =   I('rsa_private');
            $m->out_rsa_public      =   I('out_rsa_public');

            if($type == 1)
            {
                // 写入数据库
                $m->add_time = time();
                $alipay_life_id = $m->add();
                if($alipay_life_id)
                {
                    $status = true;
                    $msg = '新增成功';
                } else {
                    $msg = '新增失败';
                }
            } else {
                // 更新数据库
                if($m->where(array('id'=>$alipay_life_id))->save())
                {
                    $status = true;
                    $msg = '编辑成功';
                } else {
                    $msg = '编辑失败或数据未改变';
                }
            }
        } else {
            $msg = $m->getError();
        }

        // 分类处理
        if($status === true)
        {
            $count = 0;
            $all = explode(',', I('alipay_life_category_id'));
            foreach($all as $v)
            {
                if($category_m->add(['alipay_life_id'=>$alipay_life_id, 'alipay_life_category_id'=>$v, 'add_time'=>time()]))
                {
                    $count++;
                }
            }
            if($count < count($all))
            {
                // 回滚事务
                $m->rollback();

                $this->ajaxReturn('分类添加失败', -10);
            }
        } else {
            // 回滚事务
            $m->rollback();
            $this->ajaxReturn($msg, -100);
        }

        // 回滚事务
        $m->commit();
        $this->ajaxReturn($msg, 0);
    }

    /**
     * [Delete 生活号删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Delete()
    {
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        $m = D('AlipayLife');
        if($m->create($_POST, 5))
        {
            $id = I('id');

            // 删除
            if($m->delete($id))
            {
                $this->ajaxReturn('删除成功');
            } else {
                $this->ajaxReturn('删除失败或资源不存在', -100);
            }
        } else {
            $this->ajaxReturn($m->getError(), -1);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error('非法访问');
        }

        // 开始处理
        $params = $_POST;
        $params['alipay_life_id'] = isset($params['id']) ? $params['id'] : 0;
        if(isset($params['state']))
        {
            $params['status'] = $params['state'];
        }
        $ret = AlipayLifeService::LifeStatus($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>