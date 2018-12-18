<?php

namespace app\admin\controller;

/**
 * 品牌管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Brand extends Common
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
     * [Index 品牌列表]
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
        $m = db('Brand');

        // 条件
        $where = $this->GetIndexWhere();

        // 分页
        $number = MyC('admin_page_number');
        $page_param = array(
                'number'    =>  $number,
                'total'     =>  $m->where($where)->count(),
                'where'     =>  $param,
                'url'       =>  url('Admin/Brand/Index'),
            );
        $page = new \base\Page($page_param);

        // 获取列表
        $list = $this->SetDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select());

        // 参数
        $this->assign('param', $param);

        // 分页
        $this->assign('page_html', $page->GetPageHtml());

        // 是否启用
        $this->assign('common_is_enable_list', lang('common_is_enable_list'));

        // 品牌分类
		$brand_category = db('BrandCategory')->where(['is_enable'=>1])->field('id,name')->select();
		$this->assign('brand_category', $brand_category);

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
                // 是否启用
                $v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];

                // 分类名称
                $v['brand_category_text'] = db('BrandCategory')->where(['id'=>$v['brand_category_id']])->getField('name');

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
            $where['name'] = array('like', '%'.I('keyword').'%');
        }

        // 是否更多条件
        if(I('is_more', 0) == 1)
        {
            if(I('is_enable', -1) > -1)
            {
                $where['is_enable'] = intval(I('is_enable', 0));
            }
            if(I('brand_category_id', -1) > -1)
            {
                $where['brand_category_id'] = intval(I('brand_category_id', 0));
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
        $data = empty($_REQUEST['id']) ? array() : db('Brand')->find(I('id'));
        $this->assign('data', $data);

        // 是否启用
        $this->assign('common_is_enable_list', lang('common_is_enable_list'));

        // 品牌分类
		$brand_category = db('BrandCategory')->where(['is_enable'=>1])->field('id,name')->select();
		$this->assign('brand_category', $brand_category);

        // 参数
        $this->assign('param', array_merge($_POST, $_GET));

        $this->display('SaveInfo');
    }

	/**
	 * [Save 品牌保存]
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
        $this->FileSave('logo', 'file_logo', 'brand');

		// id为空则表示是新增
		$m = D('Brand');

		// 公共额外数据处理
		$_POST['is_enable'] = intval(I('is_enable', 0));

		// 添加
		if(empty($_POST['id']))
		{
			if($m->create($_POST, 1))
			{
				// 额外数据处理
				$m->add_time 			=	time();
				$m->sort 				=	intval(I('sort'));
				$m->brand_category_id 	=	intval(I('brand_category_id'));
				$m->website_url 		=	I('website_url');
				$m->name 				=	I('name');
				
				// 写入数据库
				if($m->add())
				{
					$this->ajaxReturn('新增成功');
				} else {
					$this->ajaxReturn('新增失败', -100);
				}
			}
		} else {
			// 编辑
			if($m->create($_POST, 2))
			{
				// 额外数据处理
				$m->upd_time 			=	time();
				$m->sort 				=	intval(I('sort'));
				$m->brand_category_id 	=	intval(I('brand_category_id'));
				$m->website_url 		=	I('website_url');
				$m->name 				=	I('name');

				// 移除 id
				unset($m->id);

				// 更新数据库
				if($m->where(array('id'=>I('id')))->save())
				{
					$this->ajaxReturn('编辑成功');
				} else {
					$this->ajaxReturn('编辑失败或数据未改变', -100);
				}
			}
		}
		$this->ajaxReturn($m->getError(), -1);
	}

	/**
	 * [Delete 品牌删除]
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

		$m = D('Brand');
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
     * [StateUpdate 状态更新]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StateUpdate()
    {
        // 参数
        if(empty($_POST['id']) || !isset($_POST['state']))
        {
            $this->ajaxReturn('参数错误', -1);
        }

        // 数据更新
        if(db('Brand')->where(array('id'=>I('id')))->save(array('is_enable'=>I('state'))))
        {
            $this->ajaxReturn('编辑成功');
        } else {
            $this->ajaxReturn('编辑失败或数据未改变', -100);
        }
    }
}
?>