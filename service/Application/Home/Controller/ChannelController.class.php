<?php

namespace Home\Controller;

/**
 * 频道
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ChannelController extends CommonController
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
	}

	/**
     * [Index 频道]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 模型对象
		$m = M('Article');

		// 条件
		$where = $this->GetIndexWhere();

		// 分页
		$number = 10;
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$_GET,
				'url'		=>	U('Home/Channel/Index'),
			);
		$page = new \My\Page($page_param);

		// 获取列表
		$list = LayoutArticleDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select());

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

		// 数据列表
		$this->assign('list', $list);

		// 布局+模块列表
		$this->assign('data', $this->GetLayoutList('channel'));

		// 友情链接
		$this->assign('link', LayoutLink('channel', 1));

		// 频道数据
		$channel = $this->GetChannelData();

		// 浏览器标题
		$title = isset($channel[I('id')]) ? $channel[I('id')] : '';
		$this->assign('home_seo_site_title', $this->GetBrowserSeoTitle($title, MyC('home_seo_channel_browser')));

		$this->display('Index');
	}

	/**
	 * [GetChannelData 获取频道数据]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-25T14:38:33+0800
	 */
	private function GetChannelData()
	{
		$data = S(C('cache_home_channel_key'));
		if(empty($data))
		{
			$data = M('ArticleClass')->getField('id,name');
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

		// 文章分类id
		if(!empty($_REQUEST['id']))
		{
			$where['article_class_id'] = intval(I('id'));
		}

		return $where;
	}
}
?>