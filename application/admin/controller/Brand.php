<?php
namespace app\admin\controller;

use app\service\BrandService;

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
        $params = input();

        // 分页
        $number = 10;

        // 条件
        $where = BrandService::BrandListListWhere($params);

        // 获取总数
        $total = BrandService::BrandTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  url('admin/brand/index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'     => $page->GetPageStarNumber(),
            'n'     => $number,
            'where' => $where,
            'field' => '*',
        );
        $data = BrandService::BrandList($data_params);
        $this->assign('data_list', $data['data']);

        // 是否启用
        $this->assign('common_is_enable_list', lang('common_is_enable_list'));

        // 品牌分类
        $brand_category = BrandService::BrandCategoryList(['field'=>'id,name']);
        $this->assign('brand_category', $brand_category['data']);

        // 参数
        $this->assign('params', $params);
        return $this->fetch();
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
        // 参数
        $params = input();

        // 数据
        if(!empty($params['id']))
        {
            // 获取列表
            $data_params = array(
                'm'     => 0,
                'n'     => 1,
                'where' => ['id'=>intval($params['id'])],
                'field' => '*',
            );
            $data = BrandService::BrandList($data_params);
            $this->assign('data', empty($data['data'][0]) ? [] : $data['data'][0]);
        }

        // 是否启用
        $this->assign('common_is_enable_list', lang('common_is_enable_list'));

        // 品牌分类
		$brand_category = BrandService::BrandCategoryList(['field'=>'id,name']);
		$this->assign('brand_category', $brand_category['data']);

        // 参数
        $this->assign('params', $params);

        return $this->fetch();
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