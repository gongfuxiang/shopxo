<?php

namespace Admin\Controller;

use Service\UserService;

/**
 * 用户管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserController extends CommonController
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
		$m = M('User');

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/User/Index'),
			);
		$page = new \Library\Page($page_param);

		// 获取列表
		$list = $this->SetDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select());

		// 性别
		$this->assign('common_gender_list', L('common_gender_list'));

		// 参数
		$this->assign('param', $param);

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

		// 数据列表
		$this->assign('list', $list);

		// Excel地址
		$this->assign('excel_url', U('Admin/User/ExcelExport', $param));

		$this->display('Index');
	}

	/**
	 * [ExcelExport excel文件导出]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-10T15:46:00+0800
	 */
	public function ExcelExport()
	{
		// 条件
		$where = $this->GetIndexWhere();

		// 读取数据
		$data = $this->SetDataHandle(M('User')->where($where)->select());

		// Excel驱动导出数据
		$excel = new \My\Excel(array('filename'=>'user', 'title'=>L('excel_user_title_list'), 'data'=>$data, 'msg'=>L('common_not_data_tips')));
		$excel->Export();
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
			foreach($data as &$v)
			{
				// 生日
				if(!empty($v['birthday']))
				{
					$v['birthday_text'] = date('Y-m-d', $v['birthday']);
				} else {
					$v['birthday_text'] = '';
				}

				// 头像
                if(!empty($v['avatar']))
                {
                    if(substr($v['avatar'], 0, 4) != 'http')
                    {
                        $v['avatar'] = C('IMAGE_HOST').$v['avatar'];
                    }
                } else {
                    $v['avatar'] = C('IMAGE_HOST').'/Public/Home/'.C('DEFAULT_THEME').'/Images/default-user-avatar.jpg';
                }

				// 注册时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

				// 更新时间
				$v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

				// 性别
				$v['gender_text'] = $common_gender_list[$v['gender']]['name'];
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
					'username'		=>	$like_keyword,
					'nickname'		=>	$like_keyword,
					'mobile'		=>	$like_keyword,
					'_logic'		=>	'or',
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			// 等值
			if(I('gender', -1) > -1)
			{
				$where['gender'] = intval(I('gender', 0));
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
	 * [SaveInfo 用户添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 用户信息
		$data = empty($_REQUEST['id']) ? array() : M('User')->find(I('id'));
		$data['birthday_text'] = empty($data['birthday']) ? '' : date('Y-m-d', $data['birthday']);
		$this->assign('data', $data);

		// 性别
		$this->assign('common_gender_list', L('common_gender_list'));

		// 参数
		$this->assign('param', array_merge($_POST, $_GET));

		$this->display('SaveInfo');
	}


	/**
	 * [Save 用户添加/编辑]
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
			$this->error(L('common_unauthorized_access'));
		}

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
	 * [Add 用户添加]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-18T16:20:59+0800
	 */
	private function Add()
	{
		// 用户模型
		$m = D('User');

		// 数据自动校验
		if($m->create($_POST, 1))
		{
			// 用户名生成
			$username = GenerateUserName();
            if($username === false)
            {
                $this->ajaxReturn('用户名达到规则极限', -1);
            }

			// 数据添加
			$data = [
				'username'				=> $username,
				'mobile'				=> I('mobile'),
				'gender'				=> intval(I('gender')),
				'email'					=> I('email'),
				'address'				=> I('address'),
				'integral'				=> intval(I('integral')),
				'birthday'				=> empty($_POST['birthday']) ? 0 : strtotime($_POST['birthday']),
				'add_time'				=> time(),
			];
			if($m->add($data))
			{
				$this->ajaxReturn(L('common_operation_add_success'));
			} else {
				$this->ajaxReturn(L('common_operation_add_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Edit 用户编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-17T22:13:40+0800
	 */
	private function Edit()
	{
		// 用户模型
		$m = D('User');
		$user_id = I('id');

		// 数据自动校验
		if($m->create($_POST, 2))
		{
			// 获取用户信息
			$user = $m->field('integral')->find($user_id);

			// 更新数据库
			$data = [
				'mobile'				=> I('mobile'),
				'gender'				=> intval(I('gender')),
				'email'					=> I('email'),
				'address'				=> I('address'),
				'integral'				=> intval(I('integral')),
				'birthday'				=> empty($_POST['birthday']) ? 0 : strtotime($_POST['birthday']),
				'upd_time'				=> time(),
			];
			if($m->where(array('id'=>$user_id))->save($data))
			{
				// 积分改变则写入积分日志
				if($user['integral'] != $data['integral'])
				{
					$integral_type = ($user['integral'] > $data['integral']) ? 0 : 1;
					UserService::UserIntegralLogAdd($user_id, $user['integral'], $data['integral'], '管理员操作', $integral_type, $this->admin['id']);
				}

				$this->ajaxReturn(L('common_operation_edit_success'));
			} else {
				$this->ajaxReturn(L('common_operation_edit_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Delete 用户删除]
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
			// 用户模型
			$m = M('User');

			// 用户是否存在
			$user = $m->where(array('id'=>$id))->getField('id');
			if(empty($user))
			{
				$this->ajaxReturn(L('common_user_no_exist_error'), -2);
			}

			// 删除用户
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