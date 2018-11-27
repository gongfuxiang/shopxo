<?php

namespace Admin\Controller;

/**
 * 优惠券管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CouponController extends CommonController
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
     * [Index 优惠券列表]
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
		$m = M('Coupon');

		// 条件
		$where = $this->GetIndexWhere();

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/Coupon/Index'),
			);
		$page = new \Library\Page($page_param);

		// 获取列表
		$list = $this->SetDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('is_enable desc, id desc')->select());

		// 参数
		$this->assign('param', $param);

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

		// 是否启用
		$this->assign('common_is_enable_list', L('common_is_enable_list'));

		// 优惠券类型
		$this->assign('common_coupon_type', L('common_coupon_type'));

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
	 * @param    [array]      $data [优惠券数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function SetDataHandle($data)
	{
		if(!empty($data))
		{
			$common_coupon_type = L('common_coupon_type');
			$common_is_enable_tips = L('common_is_enable_tips');
			foreach($data as &$v)
			{
				// 是否启用
				$v['is_enable_text'] = $common_is_enable_tips[$v['is_enable']]['name'];

				// 类型
				$v['type_text'] = $common_coupon_type[$v['type']]['name'];

				// 有效时间
				$v['valid_start_time_text'] = empty($v['valid_start_time']) ? '' : date('Y-m-d H:i:s', $v['valid_start_time']);
				$v['valid_end_time_text'] = empty($v['valid_end_time']) ? '' : date('Y-m-d H:i:s', $v['valid_end_time']);

				// 金额美化
				$v['price'] = PriceBeautify($v['price']);
				$v['use_where_price'] = PriceBeautify($v['use_where_price']);

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
			if(I('type', -1) > -1)
			{
				$where['type'] = intval(I('type', 0));
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
		// 优惠券信息
		$data = empty($_REQUEST['id']) ? array() : M('Coupon')->find(I('id'));
		if(!empty($data))
		{
			$data['valid_start_time'] = empty($data['valid_start_time']) ? '' : date('Y-m-d H:i:s', $data['valid_start_time']);
			$data['valid_end_time'] = empty($data['valid_end_time']) ? '' : date('Y-m-d H:i:s', $data['valid_end_time']);
			$data['price'] = PriceBeautify($data['price']);
			$data['use_where_price'] = PriceBeautify($data['use_where_price']);
		}
		$this->assign('data', $data);

		// 是否启用
		$this->assign('common_is_enable_list', L('common_is_enable_list'));

		// 优惠券类型
		$this->assign('common_coupon_type', L('common_coupon_type'));

		// 参数
		$this->assign('param', array_merge($_POST, $_GET));

		$this->display('SaveInfo');
	}

	/**
	 * [Save 优惠券添加/编辑]
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
	 * [Add 优惠券添加]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-18T16:20:59+0800
	 */
	private function Add()
	{
		// 优惠券模型
		$m = D('Coupon');

		// 数据自动校验
		if($m->create($_POST, 1))
		{
			// 额外数据处理
			if(!empty($m->valid_start_time))
			{
				$m->valid_start_time	=	strtotime($m->valid_start_time);
			}
			if(!empty($m->valid_end_time))
			{
				$m->valid_end_time	=	strtotime($m->valid_end_time);
			}
			if(empty($m->use_where_price))
			{
				$m->use_where_price = 0.00;
			}

			$m->name 		=	I('name');
			$m->type 		=	intval(I('type'));
			$m->is_enable 	=	intval(I('is_enable'));
			$m->add_time	=	time();

			// 数据添加
			if($m->add())
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
	 * [Edit 优惠券编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-17T22:13:40+0800
	 */
	private function Edit()
	{
		// 优惠券模型
		$m = D('Coupon');

		// 数据自动校验
		if($m->create($_POST, 2))
		{
			// 额外数据处理
			if(!empty($m->valid_start_time))
			{
				$m->valid_start_time	=	strtotime($m->valid_start_time);
			}
			if(!empty($m->valid_end_time))
			{
				$m->valid_end_time	=	strtotime($m->valid_end_time);
			}
			if(empty($m->use_where_price))
			{
				$m->use_where_price = 0.00;
			}
			$m->name 		=	I('name');
			$m->type 		=	intval(I('type'));
			$m->is_enable 	=	intval(I('is_enable'));
			$m->add_time	=	time();
			$m->upd_time	=	time();

			// 更新数据库
			if($m->where(array('id'=>I('id')))->save())
			{
				$this->ajaxReturn(L('common_operation_edit_success'));
			} else {
				$this->ajaxReturn(L('common_operation_edit_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [SendInfo 优惠券发放页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SendInfo()
	{
		// 优惠券信息
		$data = empty($_REQUEST['id']) ? array() : M('Coupon')->find(I('id'));
		if(!empty($data))
		{
			$data['valid_start_time'] = empty($data['valid_start_time']) ? '' : date('Y-m-d H:i:s', $data['valid_start_time']);
			$data['valid_end_time'] = empty($data['valid_end_time']) ? '' : date('Y-m-d H:i:s', $data['valid_end_time']);
			$data['price'] = PriceBeautify($data['price']);
			$data['use_where_price'] = PriceBeautify($data['use_where_price']);
			$data['type_text'] = L('common_coupon_type')[$data['type']]['name'];
		}

		$this->assign('data', $data);
		$this->assign('coupon_id', I('id'));
		$this->display('SendInfo');
	}

	/**
	 * [UserQuery 用户查询]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-05-23T16:53:53+0800
	 */
	public function UserQuery()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数
		$keyword = I('keyword');
		if(empty($keyword))
		{
			$this->error('搜索关键字不能为空');
		}

		// 查询数据
		$where = [
			[
				'username'	=> $keyword,
				'nickname'	=> $keyword,
				'mobile'	=> $keyword,
				'_logic'	=> 'or',
			],
			'is_delete_time' => 0,
		];
		$user = M('User')->where($where)->field('id,username,nickname,mobile,avatar')->find();
		if(empty($user))
		{
			$this->ajaxReturn(L('common_not_data_tips'), -1);
		}
		if(empty($user['avatar']))
		{
			$user['avatar'] = C('IMAGE_HOST').'Public/Common/Images/user-img-md.gif';
		}
		$this->ajaxReturn(L('common_operation_success'), 0, $user);
	}

	/**
	 * [Send 用户优惠券发放]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-05-23T17:45:06+0800
	 */
	public function Send()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 请求参数
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'coupon_id',
                'error_msg'         => '优惠券不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => '用户不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'valid_start_time',
                'error_msg'         => '有效起始时间不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'valid_end_time',
                'error_msg'         => '有效截止时间不能为空',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret, -1);
        }

        // 优惠券
		$coupon = M('Coupon')->find($this->data_post['coupon_id']);
		if(empty($coupon))
		{
			$this->ajaxReturn('优惠券不存在', -2);
		}
		if($coupon['is_enable'] != 1)
		{
			$this->ajaxReturn('优惠券已关闭', -2);
		}

		// 发放
		$success = 0;
		$failure = 0;
		$m = M('UserCoupon');
		foreach($this->data_post['user_id'] as $user_id)
		{
			$data = [
				'user_id'			=> $user_id,
				'coupon_id'			=> $coupon['id'],
				'status'			=> 0,
				'price'				=> $coupon['price'],
				'valid_start_time'	=> strtotime($this->data_post['valid_start_time']),
				'valid_end_time'	=> strtotime($this->data_post['valid_end_time']),
				'add_time'			=> time(),
				'upd_time'			=> time(),
			];
			if($m->add($data) > 0)
			{
				$success++;
			} else {
				$failure++;
			}
		}
		$this->ajaxReturn('成功['.$success.'], 失败['.$failure.']', 0);
	}

	/**
     * [User 用户优惠券 - 列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function User()
	{
		// 参数
		$param = array_merge($_POST, $_GET);

		// 模型对象
		$m = M('UserCoupon');

		// 条件
		$where = $this->GetUserWhere($param);

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->alias('uc')->join('__USER__ AS u ON u.id=uc.user_id')->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/Coupon/User'),
			);
		$page = new \Library\Page($page_param);

		// 获取列表
		$field = 'u.username, u.nickname, u.mobile, uc.*';
		$list = $this->UserSetDataHandle($m->alias('uc')->join('__USER__ AS u ON u.id=uc.user_id')->field($field)->where($where)->limit($page->GetPageStarNumber(), $number)->order('uc.id desc')->select());

		// 参数
		$this->assign('param', $param);

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

		// 是否启用
		$this->assign('common_user_coupon_status', L('common_user_coupon_status'));

		// 数据列表
		$this->assign('list', $list);
		$this->display('User');
	}

	/**
	 * [UserSetDataHandle 用户优惠券 - 数据处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-29T21:27:15+0800
	 * @param    [array]      $data [优惠券数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function UserSetDataHandle($data)
	{
		if(!empty($data))
		{
			$common_coupon_type = L('common_coupon_type');
			$common_user_coupon_status = L('common_user_coupon_status');
			$coupon = [];
			$coupon_m = M('Coupon');
			foreach($data as &$v)
			{
				// 获取优惠券信息
				if(!isset($coupon[$v['coupon_id']]))
				{
					$temp_coupon = $coupon_m->find($v['coupon_id']);
					$temp_coupon['type_text'] = $common_coupon_type[$temp_coupon['type']]['name'];
					$coupon[$v['coupon_id']] = $temp_coupon;
				} else {
					$temp_coupon = $coupon[$v['coupon_id']];
				}

				// 优惠券信息加入
				$v['coupon_type'] = $temp_coupon['type'];
				$v['coupon_type_text'] = $temp_coupon['type_text'];
				$v['coupon_name'] = $temp_coupon['name'];
				$v['coupon_use_where_price'] = PriceBeautify($temp_coupon['use_where_price']);

				// 有效时间
				$v['valid_start_time_text'] = empty($v['valid_start_time']) ? '' : date('Y-m-d H:i:s', $v['valid_start_time']);
				$v['valid_end_time_text'] = empty($v['valid_end_time']) ? '' : date('Y-m-d H:i:s', $v['valid_end_time']);

				// 金额美化
				$v['price'] = PriceBeautify($v['price']);

				// 是否启用
				$v['status_text'] = $common_user_coupon_status[$v['status']]['name'];

				// 添加时间
				$v['add_time_text'] = date('Y-m-d H:i:s', $v['add_time']);

				// 更新时间
				$v['upd_time_text'] = date('Y-m-d H:i:s', $v['upd_time']);
			}
		}
		return $data;
	}

	/**
	 * [GetUserWhere 用户优惠券 - 列表条件]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T22:16:29+0800
	 */
	private function GetUserWhere($param)
	{
		$where = array(
			'uc.coupon_id'		=> isset($this->data_get['coupon_id']) ? intval($this->data_get['coupon_id']) : 0,
			'uc.is_delete_time'	=> 0,
			'u.is_delete_time'	=> 0,
		);

		// 模糊
		if(!empty($_REQUEST['keyword']))
		{
			$like_keyword = array('like', '%'.$param['keyword'].'%');
			$where[] = array(
					'u.username'		=>	$like_keyword,
					'u.nickname'		=>	$like_keyword,
					'u.mobile'			=>	$like_keyword,
					'_logic'			=>	'or',
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			if($param['status'] > -1)
			{
				$where['uc.status'] = intval($param['status']);
			}

			// 表达式
			if(!empty($param['time_start']))
			{
				$where['uc.valid_start_time'] = array('gt', strtotime($param['time_start']));
			}
			if(!empty($param['time_end']))
			{
				$where['uc.valid_end_time'] = array('lt', strtotime($param['time_end']));
			}
		}
		return $where;
	}

	/**
	 * [Delete 用户优惠券删除]
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
			// 模型
			$m = M('UserCoupon');

			// 是否存在
			$user_coupon = $m->where(array('id'=>$id))->getField('id');
			if(empty($user_coupon))
			{
				$this->ajaxReturn(L('user_coupon_no_exist_error'), -2);
			}

			// 删除
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
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 数据更新
		if(M('Coupon')->where(array('id'=>I('id')))->save(array('is_enable'=>I('state'))))
		{
			$this->ajaxReturn(L('common_operation_edit_success'));
		} else {
			$this->ajaxReturn(L('common_operation_edit_error'), -100);
		}
	}
}
?>