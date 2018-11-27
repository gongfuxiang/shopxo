<?php

namespace Admin\Controller;

/**
 * 问答管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AnswerController extends CommonController
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
     * [Index 问答列表]
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
		$m = M('Answer');

		// 条件
		$where = $this->GetIndexWhere();

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/Answer/Index'),
			);
		$page = new \Library\Page($page_param);

		// 获取列表
		$field = '*';
		$list = $this->SetDataHandle($m->where($where)->field($field)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select());

		// 状态
		$this->assign('common_is_show_list', L('common_is_show_list'));

		// 参数
		$this->assign('param', $param);

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

		// 数据列表
		$this->assign('list', $list);

		// Excel地址
		$this->assign('excel_url', U('Admin/Answer/ExcelExport', $param));

		$this->display('Index');
	}

	/**
	 * [SetDataHandle 数据处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-29T21:27:15+0800
	 * @param    [array]      $data [问答数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function SetDataHandle($data)
	{
		if(!empty($data))
		{
			$common_is_show_list = L('common_is_show_list');
			$common_gender_list = L('common_gender_list');
			$u = M('User');
			foreach($data as &$v)
			{
				// 用户信息
				$user = $u->where(['id'=>$v['user_id']])->field('username,nickname,mobile,gender,avatar')->find();
				$v['username'] = empty($user['username']) ? '' : $user['username'];
				$v['nickname'] = empty($user['nickname']) ? '' : $user['nickname'];
				$v['mobile'] = empty($user['mobile']) ? '' : $user['mobile'];
				$v['avatar'] = empty($user['avatar']) ? '' : $user['avatar'];
				$v['gender_text'] = isset($user['gender']) ? $common_gender_list[$user['gender']]['name'] : '';

				// 是否显示
				$v['is_show_text'] = $common_is_show_list[$v['is_show']]['name'];

				// 创建时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

				// 更新时间
				$v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
			}
		}
		return $data;
	}

	/**
	 * [GetIndexWhere 问答列表条件]
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
					'name'				=>	$like_keyword,
					'tel'				=>	$like_keyword,
					'content'			=>	$like_keyword,
					'_logic'			=>	'or',
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			// 等值
			if(I('is_show', -1) > -1)
			{
				$where['is_show'] = intval(I('is_show', 0));
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
	 * [Delete 问答删除]
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
			// 问答模型
			$m = M('Answer');

			// 问答是否存在
			$merchant = $m->where(array('id'=>$id))->getField('id');
			if(empty($merchant))
			{
				$this->ajaxReturn(L('common_data_no_exist_error'), -2);
			}

			// 删除问答
			if($m->where(array('id'=>$id))->save(['is_delete_time'=>time()]) !== false)
			{
				$this->ajaxReturn(L('common_operation_delete_success'));
			} else {
				$this->ajaxReturn(L('common_operation_delete_error'), -100);
			}
		} else {
			$this->ajaxReturn(L('common_param_error'), -1);
		}
	}

	/**
	 * [Save 问答回复处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-03-28T15:07:17+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数处理
		$id = I('id');
		$reply = I('reply');
		if(empty($id))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}
		if(empty($reply))
		{
			$this->ajaxReturn(L('answer_reply_format'), -2);
		}

		// 问答模型
		$m = M('Answer');

		// 问答是否存在
		$temp = $m->where(array('id'=>$id))->field('id')->find();
		if(empty($temp))
		{
			$this->ajaxReturn(L('common_data_no_exist_error'), -2);
		}
		// 更新问答
		$data = array('reply'=>$reply, 'is_reply'=>1, 'upd_time'=>time());
		if($m->where(array('id'=>$id))->save($data) !== false)
		{
			$this->ajaxReturn(L('common_operation_success'));
		} else {
			$this->ajaxReturn(L('common_operation_error'), -100);
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
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 数据更新
		if(M('Answer')->where(array('id'=>I('id')))->save(array('is_show'=>I('state'))))
		{
			$this->ajaxReturn(L('common_operation_edit_success'));
		} else {
			$this->ajaxReturn(L('common_operation_edit_error'), -100);
		}
	}
}
?>