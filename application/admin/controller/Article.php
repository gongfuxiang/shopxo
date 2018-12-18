<?php

namespace app\admin\controller;

use Service\ResourcesService;

/**
 * 文章管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Article extends Common
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
     * [Index 文章列表]
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
		$m = db('Article');

		// 条件
		$where = $this->GetIndexWhere();

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	url('Admin/Article/Index'),
			);
		$page = new \base\Page($page_param);

		// 获取列表
		$list = $this->SetDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select());

		// 是否启用
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));

		// 是否
        $this->assign('common_is_text_list', lang('common_is_text_list'));

		// 文章分类
		$this->assign('article_category_list', db('ArticleCategory')->field(array('id', 'name'))->where(array('is_enable'=>1))->select());

		// 参数
		$this->assign('param', $param);

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

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
	 * @param    [array]      $data [文章数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function SetDataHandle($data)
	{
		if(!empty($data))
		{
			$ac = db('ArticleCategory');
			foreach($data as &$v)
			{
				// 时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
				$v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

				// 是否启用
				$v['is_enable_text'] = lang('common_is_enable_list')[$v['is_enable']]['name'];

				// 文章分类
				$v['article_category_name'] = $ac->where(array('id'=>$v['article_category_id']))->getField('name');

				// url
				$v['url'] = HomeUrl('Article', 'Index', ['id'=>$v['id']]);
			}
		}
		return $data;
	}

	/**
	 * [GetIndexWhere 文章列表条件]
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
			$where[] = array(
					'title'		=>	array('like', '%'.I('keyword').'%'),
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			// 等值
			if(I('is_enable', -1) > -1)
			{
				$where['is_enable'] = intval(I('is_enable', 1));
			}
			if(I('article_category_id', -1) > -1)
			{
				$where['article_category_id'] = intval(I('article_category_id'));
			}
			if(I('is_home_recommended', -1) > -1)
			{
				$where['is_home_recommended'] = intval(I('is_home_recommended', 1));
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
	 * [SaveInfo 文章添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 文章信息
		if(empty($_REQUEST['id']))
		{
			$data = array();
		} else {
			$data = db('Article')->find(I('id'));
			if(!empty($data['content']))
			{
				// 静态资源地址处理
				$data['content'] = ResourcesService::ContentStaticReplace($data['content'], 'get');
			}
		}
		$this->assign('data', $data);

		// 是否启用
		$this->assign('common_is_enable_list', lang('common_is_enable_list'));

		// 文章分类
		$this->assign('article_category_list', db('ArticleCategory')->field(array('id', 'name'))->where(array('is_enable'=>1))->select());

		$this->display('SaveInfo');
	}

	/**
	 * [Save 文章添加/编辑]
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
			$this->error('非法访问');
		}

		// 数据处理
		$_POST['is_enable'] = isset($_POST['is_enable']) ? intval($_POST['is_enable']) : 0;

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
	 * [Add 文章添加]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-18T16:20:59+0800
	 */
	private function Add()
	{
		// 文章模型
		$m = D('Article');

		// 数据自动校验
		if($m->create($_POST, 1))
		{
			// 额外数据处理
			$m->add_time	=	time();
			$m->upd_time	=	time();
			$m->title 		=	I('title');
			
			// 静态资源地址处理
			$m->content 	=	ResourcesService::ContentStaticReplace($m->content, 'add');

			// 正则匹配文章图片
			$temp_image		=	$this->MatchContentImage($m->content);
			$m->image 		=	json_encode($temp_image);
			$m->image_count	=	count($temp_image);
			$m->is_home_recommended = intval(I('is_home_recommended', 0));
			
			// 数据添加
			if($m->add())
			{
				$this->ajaxReturn('新增成功');
			} else {
				$this->ajaxReturn('新增失败', -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Edit 文章编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-17T22:13:40+0800
	 */
	private function Edit()
	{
		// 文章模型
		$m = D('Article');

		// 数据自动校验
		if($m->create($_POST, 2))
		{
			// 额外数据处理
			$m->upd_time	=	time();
			$m->title 		=	I('title');

			// 静态资源地址处理
			$m->content 	=	ResourcesService::ContentStaticReplace($m->content, 'add');

			// 正则匹配文章图片
			$temp_image		=	$this->MatchContentImage($m->content);
			$m->image 		=	json_encode($temp_image);
			$m->image_count	=	count($temp_image);
			$m->is_home_recommended = intval(I('is_home_recommended', 0));

			// 数据更新
			if($m->where(array('id'=>I('id')))->save())
			{
				$this->ajaxReturn('编辑成功');
			} else {
				$this->ajaxReturn('编辑失败或数据未改变', -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [MatchContentImage 正则匹配文章图片]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-22T18:06:53+0800
	 * @param    [string]         $content [文章内容]
	 * @return   [array]    			   [文章图片数组（一维）]
	 */
	private function MatchContentImage($content)
	{
		if(!empty($content))
		{
			$pattern = '/<img.*?src=[\'|\"](\/Public\/Upload\/article\/image\/.*?[\.gif|\.jpg|\.jpeg|\.png|\.bmp])[\'|\"].*?[\/]?>/';
			preg_match_all($pattern, $content, $match);
			return empty($match[1]) ? array() : $match[1];
		}
		return array();
	}

	/**
	 * [Delete 文章删除]
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
			$this->error('非法访问');
		}

		// 删除数据
		if(!empty($_POST['id']))
		{
			// 更新
			if(db('Article')->delete(I('id')))
			{
				$this->ajaxReturn('删除成功');
			} else {
				$this->ajaxReturn('删除失败或资源不存在', -100);
			}
		} else {
			$this->ajaxReturn('参数错误', -1);
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
			$this->ajaxReturn('参数错误', -1);
		}

		// 数据更新
		if(db('Article')->where(array('id'=>I('id')))->save(array('is_enable'=>I('state'))))
		{
			$this->ajaxReturn('编辑成功');
		} else {
			$this->ajaxReturn('编辑失败或数据未改变', -100);
		}
	}

	/**
	 * [StatusHomeRecommended 是否首页推荐状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusHomeRecommended()
	{
		// 参数
		if(empty($_POST['id']) || !isset($_POST['state']))
		{
			$this->ajaxReturn('参数错误', -1);
		}

		// 数据更新
		if(db('Article')->where(array('id'=>I('id')))->save(array('is_home_recommended'=>I('state'))))
		{
			$this->ajaxReturn('编辑成功');
		} else {
			$this->ajaxReturn('编辑失败或数据未改变', -100);
		}
	}
}
?>