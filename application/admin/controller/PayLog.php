<?php

namespace app\admin\controller;

/**
 * 支付日志管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class PayLog extends Common
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
     * [Index 支付日志列表]
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
		$m = db('PayLog');

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->alias('p')->join('__USER__ AS u ON u.id=p.user_id')->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	url('Admin/PayLog/Index'),
			);
		$page = new \base\Page($page_param);

		// 获取列表
		$field = 'p.*,u.username,u.nickname,u.mobile,u.gender';
		$list = $this->SetDataHandle($m->alias('p')->join('__USER__ AS u ON u.id=p.user_id')->field($field)->where($where)->limit($page->GetPageStarNumber(), $number)->order('p.id desc')->select());

		// 性别
		$this->assign('common_gender_list', lang('common_gender_list'));

		// 支付日志类型
		$pay_list = $m->field('payment AS id, payment_name AS name')->group('payment')->select();
		$this->assign('common_pay_type_list', $pay_list);

		// 业务类型
		$this->assign('common_business_type_list', lang('common_business_type_list'));

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
	 * @param    [array]      $data [支付日志数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function SetDataHandle($data)
	{
		if(!empty($data))
		{
			$common_gender_list = lang('common_gender_list');
			$common_business_type_list = lang('common_business_type_list');
			foreach($data as &$v)
			{
				// 业务类型
				$v['business_type_text'] = $common_business_type_list[$v['business_type']]['name'];

				// 性别
				$v['gender_text'] = $common_gender_list[$v['gender']]['name'];

				// 添加时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
			}
		}
		return $data;
	}

	/**
	 * [GetIndexWhere 支付日志列表条件]
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
			$like_keyword = array('like', '%'.I('keyword').'%');
			$where[] = array(
					'u.username'	=>	$like_keyword,
					'u.nickname'	=>	$like_keyword,
					'u.mobile'		=>	$like_keyword,
					'p.trade_no'	=>	$like_keyword,
					'_logic'		=>	'or',
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			// 等值
			if(!empty($_REQUEST['pay_type']))
			{
				$where['p.payment'] = I('pay_type');
			}
			if(I('business_type', -1) > -1)
			{
				$where['p.business_type'] = intval(I('business_type', 0));
			}
			if(I('gender', -1) > -1)
			{
				$where['u.gender'] = intval(I('gender', 0));
			}

			// 表达式
			if(!empty($_REQUEST['time_start']))
			{
				$where['p.add_time'][] = array('gt', strtotime(I('time_start')));
			}
			if(!empty($_REQUEST['time_end']))
			{
				$where['p.add_time'][] = array('lt', strtotime(I('time_end')));
			}
		}

		return $where;
	}
}
?>