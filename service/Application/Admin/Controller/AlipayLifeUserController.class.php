<?php

namespace Admin\Controller;

/**
 * 生活号用户管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLifeUserController extends CommonController
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
     * [Index 用户列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 参数
		$param = array_merge($_POST, $_GET);

		// 条件
		$where = $this->GetIndexWhere();

		// 模型
		$m = M('AlipayLifeUser');

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->alias('au')->where($where)->join('INNER JOIN __USER__ AS u ON u.id=au.user_id')->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/AlipayLifeUser/Index'),
			);
		$page = new \Library\Page($page_param);

		// 获取列表
		$field = 'u.*, au.alipay_life_id';
		$list = $this->SetDataHandle($m->alias('au')->where($where)->join('INNER JOIN __USER__ AS u ON u.id=au.user_id')->field($field)->limit($page->GetPageStarNumber(), $number)->order('au.id desc')->select());

		// 性别
		$this->assign('common_gender_list', L('common_gender_list'));

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
	 * @param    [array]      $data [用户数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function SetDataHandle($data)
	{
		if(!empty($data))
		{
			$common_gender_list = L('common_gender_list');
			$life_m = M('AlipayLife');
			foreach($data as &$v)
			{
				// 生日
				if(!empty($v['birthday']))
				{
					$v['birthday_text'] = date('Y-m-d', $v['birthday']);
				} else {
					$v['birthday_text'] = '';
				}

				// 注册时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

				// 更新时间
				$v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

				// 性别
				$v['gender_text'] = $common_gender_list[$v['gender']]['name'];

				// 所属生活号
				$v['alipay_life_name'] = $life_m->where(['id'=>$v['alipay_life_id']])->getField('name');
			}
		}
		return $data;
	}

	/**
	 * [GetIndexWhere 用户列表条件]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T22:16:29+0800
	 */
	private function GetIndexWhere()
	{
		$where = array('is_delete_time'=>0);

		// 模糊
		if(!empty($_REQUEST['keyword']))
		{
			$like_keyword = array('like', '%'.I('keyword').'%');
			$where[] = array(
					'u.username'		=>	$like_keyword,
					'u.nickname'		=>	$like_keyword,
					'u.mobile'		=>	$like_keyword,
					'_logic'		=>	'or',
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			// 等值
			if(I('gender', -1) > -1)
			{
				$where['u.gender'] = intval(I('gender', 0));
			}

			// 表达式
			if(!empty($_REQUEST['time_start']))
			{
				$where['au.add_time'][] = array('gt', strtotime(I('time_start')));
			}
			if(!empty($_REQUEST['time_end']))
			{
				$where['au.add_time'][] = array('lt', strtotime(I('time_end')));
			}
		}

		return $where;
	}
}
?>