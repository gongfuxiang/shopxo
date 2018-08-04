<?php

namespace Admin\Controller;

/**
 * 消息管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class MessageController extends CommonController
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
     * [Index 消息列表]
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
		$m = M('Message');

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->alias('m')->join('__USER__ AS u ON u.id=m.user_id')->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/Message/Index'),
			);
		$page = new \Library\Page($page_param);

		// 获取列表
		$field = 'm.*,u.username,u.nickname,u.mobile,u.gender';
		$list = $this->SetDataHandle($m->alias('m')->join('__USER__ AS u ON u.id=m.user_id')->field($field)->where($where)->limit($page->GetPageStarNumber(), $number)->order('m.id desc')->select());

		// 性别
		$this->assign('common_gender_list', L('common_gender_list'));

		// 消息类型
		$this->assign('common_message_type_list', L('common_message_type_list'));

		// 是否已读
		$this->assign('common_is_read_list', L('common_is_read_list'));

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
	 * @param    [array]      $data [消息数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function SetDataHandle($data)
	{
		if(!empty($data))
		{
			$common_gender_list = L('common_gender_list');
			$common_is_read_list = L('common_is_read_list');
			$common_message_type_list = L('common_message_type_list');
			foreach($data as &$v)
			{
				// 消息类型
				$v['type_text'] = $common_message_type_list[$v['type']]['name'];

				// 是否已读
				$v['is_read_text'] = $common_is_read_list[$v['is_read']]['name'];

				// 性别
				$v['gender_text'] = $common_gender_list[$v['gender']]['name'];

				// 添加时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
			}
		}
		return $data;
	}

	/**
	 * [GetIndexWhere 消息列表条件]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T22:16:29+0800
	 */
	private function GetIndexWhere()
	{
		$where = array('m.is_delete_time'=>0);

		// 模糊
		if(!empty($_REQUEST['keyword']))
		{
			$like_keyword = array('like', '%'.I('keyword').'%');
			$where[] = array(
					'u.username'	=>	$like_keyword,
					'u.nickname'	=>	$like_keyword,
					'u.mobile'		=>	$like_keyword,
					'm.title'		=>	$like_keyword,
					'_logic'		=>	'or',
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			// 等值
			if(I('type', -1) > -1)
			{
				$where['m.type'] = intval(I('type', 0));
			}
			if(I('is_read', -1) > -1)
			{
				$where['m.is_read'] = intval(I('is_read', 0));
			}
			if(I('gender', -1) > -1)
			{
				$where['u.gender'] = intval(I('gender', 0));
			}

			// 表达式
			if(!empty($_REQUEST['time_start']))
			{
				$where['m.add_time'][] = array('gt', strtotime(I('time_start')));
			}
			if(!empty($_REQUEST['time_end']))
			{
				$where['m.add_time'][] = array('lt', strtotime(I('time_end')));
			}
		}

		return $where;
	}

	/**
	 * [Delete 消息删除]
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
			$this->error(L('common_unauthorized_access'));
		}

		// 参数处理
		$id = I('id');

		// 删除数据
		if(!empty($id))
		{
			// 消息模型
			$m = M('Message');

			// 消息是否存在
			$user = $m->where(array('id'=>$id))->getField('id');
			if(empty($user))
			{
				$this->ajaxReturn(L('common_data_no_exist_error'), -2);
			}

			// 删除消息
			$status = $m->where(array('id'=>$id))->save(['is_delete_time'=>time()]);
			if($status !== false)
			{
				$this->ajaxReturn(L('common_operation_delete_success'));
			} else {
				$this->ajaxReturn(L('common_operation_delete_error'), -100);
			}
		} else {
			$this->ajaxReturn(L('common_param_error'), -1);
		}
	}
}
?>