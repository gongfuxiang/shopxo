<?php

namespace Home\Controller;

/**
 * 学生
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2017-03-02T22:48:35+0800
 */
class StudentController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-02T22:48:35+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();
	}

	/**
	 * [Index 首页]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function Index()
	{
		// 获取列表
		$where = array('us.user_id'=>$this->user['id']);
		$list = $this->StudentSetDataHandle(M('UserStudent')->alias('AS us')->join('__STUDENT__ AS s ON s.id=us.student_id ')->field('s.*, us.id AS user_student_id,us.add_time AS bundled_time')->where($where)->select());

		// 数据列表
		$this->assign('list', $list);
		$this->display('Index');
	}

	/**
	 * [StudentSetDataHandle 数据处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-29T21:27:15+0800
	 * @param    [array]      $data [学生数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function StudentSetDataHandle($data)
	{
		$result = array();
		if(!empty($data))
		{
			$c = M('Class');
			$r = M('Region');
			$s = M('Semester');
			foreach($data as $k=>$v)
			{
				if(!isset($result[$v['semester_id']]))
				{
					// 学期
					$result[$v['semester_id']]['id'] = $v['semester_id'];
					$result[$v['semester_id']]['name'] = $s->where(array('id'=>$v['semester_id']))->getField('name');
				}

				// 班级
				$tmp_class = $c->field(array('pid', 'name'))->find($v['class_id']);
				if(!empty($tmp_class))
				{
					$p_name = ($tmp_class['pid'] > 0) ? $c->where(array('id'=>$tmp_class['pid']))->getField('name') : '';
					$v['class_name'] = empty($p_name) ? $tmp_class['name'] : $p_name.'-'.$tmp_class['name'];
				} else {
					$v['class_name'] = '';
				}
				
				// 地区
				$v['region_name'] = $r->where(array('id'=>$v['region_id']))->getField('name');

				// 学期
				$v['semester_text'] = $result[$v['semester_id']]['name'];

				// 出生
				$v['birthday'] = date('Y-m-d', $v['birthday']);

				// 报名时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

				// 更新时间
				$v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

				// 绑定时间
				$v['bundled_time'] = date('Y-m-d H:i:s', $v['bundled_time']);

				// 性别
				$v['gender'] = L('common_gender_list')[$v['gender']]['name'];

				// 状态
				$v['state'] = L('common_student_state_list')[$v['state']]['name'];

				// 缴费状态
				$v['tuition_state_text'] = L('common_tuition_state_list')[$v['tuition_state']]['name'];

				$result[$v['semester_id']]['item'][] = $v;
			}
		}
		return $result;
	}

	/**
	 * [ScoreInfo 成绩查询]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-22T22:39:04+0800
	 */
	public function ScoreInfo()
	{
		$id = I('id');
		if(!empty($id))
		{
			$field = array('s.username', 's.class_id', 'f.score', 'f.subject_id', 'f.score_id', 'f.comment', 'f.add_time');
			$where = array('us.user_id'=>$this->user['id'], 'us.id'=>$id);
			$data = $this->ScoreSetDataHandle(M('UserStudent')->alias('AS us')->join('__STUDENT__ AS s ON us.student_id=s.id')->join('__FRACTION__ AS f ON s.id=f.student_id')->where($where)->field($field)->order('f.score_id ASC')->select());
			$this->assign('data', $data);
			$this->assign('student_score_title_list', L('student_score_title_list'));
		}
		$this->display('ScoreInfo');
	}

	/**
	 * [ScoreSetDataHandle 数据处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-29T21:27:15+0800
	 * @param    [array]      $data [学生数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function ScoreSetDataHandle($data)
	{
		$result = array();
		if(!empty($data))
		{
			$score = M('Score');
			$subject = M('Subject');
			$class = M('Class');
			$score_level = L('common_fraction_level_list');
			foreach($data as $k=>$v)
			{
				// 基础资料
				if(!isset($result['username']))
				{
					// 姓名
					$result['username'] = $v['username'];

					// 班级
					$tmp_class = $class->field(array('pid', 'name'))->find($v['class_id']);
					if(!empty($tmp_class))
					{
						$p_name = ($tmp_class['pid'] > 0) ? $class->where(array('id'=>$tmp_class['pid']))->getField('name') : '';
						$result['class_name'] = empty($p_name) ? $tmp_class['name'] : $p_name.'-'.$tmp_class['name'];
					}
				}

				// 学期key
				$score_key = $v['score_id'];

				// 成绩期号
				if(!isset($result['item'][$score_key]))
				{
					$result['item'][$score_key]['name'] = $score->where(array('id'=>$v['score_id']))->getField('name');
				}
				
				// 科目
				$v['subject_name'] = $subject->where(array('id'=>$v['subject_id']))->getField('name');

				// 成绩等级
				foreach($score_level as $level)
				{
					if($v['score'] >= $level['min'] && $v['score'] <= $level['max'])
					{
						$v['score_level'] = $level['name'];
					}
				}
				if(!isset($v['score_level']))
				{
					$v['score_level'] = '';
				}

				// 添加时间
				$v['add_time'] = date('Y-m-d', $v['add_time']);

				unset($v['username'], $v['class_id'], $v['subject_id'], $v['score_id']);
				$result['item'][$score_key]['item'][] = $v;
			}
		}
		return $result;
	}

	/**
	 * [PolyInfo 学生关联-页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-15T11:22:33+0800
	 */
	public function PolyInfo()
	{
		$this->display('PolyInfo');
	}

	/**
	 * [Delete 学生关联-解除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-25T22:36:12+0800
	 */
	public function Delete()
	{
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 数据删除
		if(M('UserStudent')->where(array('id'=>I('id'), 'user_id'=>$this->user['id']))->delete())
		{
			$this->ajaxReturn(L('common_operation_delete_success'));
		} else {
			$this->ajaxReturn(L('common_operation_delete_error'), -100);
		}
	}

	/**
	 * [Poly 学生关联]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-22T16:47:29+0800
	 */
	public function Poly()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数是否有误
		if(empty($_POST['id']) || empty($_POST['accounts']) || empty($_POST['verify']))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 账户是否存在
		$accounts = I('accounts');
		$field = $this->StudentPolyAccountsCheck($accounts);

		// 验证码校验
		$verify_param = array(
				'key_prefix' => 'student_poly',
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);
		if($field == 'email')
		{
			$obj = new \My\Email($verify_param);
			
		} else {
			$obj = new \My\Sms($verify_param);
		}
		// 是否已过期
		if(!$obj->CheckExpire())
		{
			$this->ajaxReturn(L('common_verify_expire'), -10);
		}
		// 是否正确
		if(!$obj->CheckCorrect(I('verify')))
		{
			$this->ajaxReturn(L('common_verify_error'), -11);
		}

		// 查询用户数据
		$where = array('id'=>I('id'), $field=>$accounts, 'semester_id'=>MyC('admin_semester_id'));
		$student_id = M('Student')->where($where)->getField('id');
		if(!empty($student_id))
		{
			$data = array(
					'student_id'	=>	$student_id,
					'user_id'		=>	$this->user['id'],
					'add_time'		=>	time(),
				);
			if(M('UserStudent')->add($data))
			{
				// 清除验证码
				$obj->Remove();

				$this->ajaxReturn(L('common_operation_join_success'));
			}
			$this->ajaxReturn(L('common_operation_join_error'), -100);
		}
		$this->ajaxReturn(L('common_student_no_exist_error'), -1000);
	}

	/**
	 * [PolyQuery 学生关联-数据查询]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-17T16:58:26+0800
	 */
	public function PolyQuery()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数是否有误
		if(empty($_POST['username']) || empty($_POST['student_number']) || empty($_POST['id_card']))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 查询用户数据
		$where = array('username'=>I('username'), 'number'=>I('student_number'), 'id_card'=>I('id_card'), 'semester_id'=>MyC('admin_semester_id'));
		$data = M('Student')->field(array('id', 'my_mobile', 'parent_mobile', 'email'))->where($where)->find();
		if(!empty($data))
		{
			// 是否已关联过
			$temp = M('UserStudent')->where(array('student_id'=>$data['id'], 'user_id'=>$this->user['id']))->getField('id');
			if(!empty($temp))
			{
				$this->ajaxReturn(L('student_join_accounts_exist_tips'), -2);
			}

			// 封装返回数据
			$result = array('id' => $data['id'], 'contact_list' => array());
			if(!empty($data['my_mobile']))
			{
				$result['contact_list'][] = $data['my_mobile'];
			}
			if(!empty($data['parent_mobile']))
			{
				$result['contact_list'][] = $data['parent_mobile'];
			}
			if(!empty($data['email']))
			{
				$result['contact_list'][] = $data['email'];
			}
			$this->ajaxReturn(L('common_operation_success'), 0, $result);
		}
		$this->ajaxReturn(L('common_student_no_exist_error'), -1000);
	}

	/**
	 * [PolyVerifyEntry 学生关联-验证码显示]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T15:10:21+0800
	 */
	public function PolyVerifyEntry()
	{
		$this->CommonVerifyEntry(I('type', 'student_poly'));
	}

	/**
	 * [PolyVerifySend 学生关联-验证码发送]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-10T17:35:03+0800
	 */
	public function PolyVerifySend()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$accounts = I('accounts');
		if(empty($accounts))
		{
			$this->ajaxReturn(L('common_param_error'), -10);
		}

		// 账户是否存在
		$type = $this->StudentPolyAccountsCheck($accounts);

		// 验证码公共基础参数
		$verify_param = array(
				'key_prefix' => 'student_poly',
				'expire_time' => MyC('common_verify_expire_time'),
				'time_interval'	=>	MyC('common_verify_time_interval'),
			);

		// 是否开启图片验证码
		$verify = $this->CommonIsImaVerify($verify_param);

		// 验证码
		$code = GetNumberCode(6);

		// 邮箱
		if($type == 'email')
		{
			$obj = new \My\Email($verify_param);
			$email_param = array(
					'email'		=>	$accounts,
					'content'	=>	MyC('home_email_user_student_binding'),
					'title'		=>	MyC('home_site_name').' - '.L('student_operation_binding_text'),
					'code'		=>	$code,
				);
			$state = $obj->SendHtml($email_param);

		// 短信
		} else {
			$obj = new \My\Sms($verify_param);
			$state = $obj->SendText($accounts, MyC('home_sms_user_student_binding'), $code);
		}

		// 状态
		if($state)
		{
			// 清除验证码
			if(isset($verify) && is_object($verify))
			{
				$verify->Remove();
			}

			$this->ajaxReturn(L('common_send_success'));
		} else {
			$this->ajaxReturn(L('common_send_error').'['.$obj->error.']', -100);
		}
	}

	/**
	 * [StudentPolyAccountsCheck 学生是否存在]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-22T17:39:56+0800
	 * @param    [string]       $accounts [my_mobile, parent_mobile, email]
	 * @return   [string]                 [表字段名称 my_mobile, parent_mobile, email]
	 */
	private function StudentPolyAccountsCheck($accounts)
	{
		$field = array('my_mobile', 'parent_mobile', 'email');
		$where = array(
					array(
						'my_mobile'		=>	$accounts,
						'parent_mobile'	=>	$accounts,
						'email'			=>	$accounts,
						'_logic'		=>	'OR',
					),
				'semester_id'	=>	MyC('admin_semester_id'),
			);
		$data = M('Student')->field($field)->where($where)->find();
		if(!empty($data))
		{
			$key = array_search($accounts, $data);
			return ($key === false) ? '' : $key;
		} else {
			$this->ajaxReturn(L('common_student_no_exist_error'), -1000);
		}
	}
}
?>