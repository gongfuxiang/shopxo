<?php

namespace Api\Controller;

/**
 * 首页
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class IndexController extends CommonController
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

		// 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }
	}

	/**
	 * [Index 首页入口]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-05-25T11:03:59+0800
	 */
	public function Index()
	{
		switch(APPLICATION_CLIENT)
        {
            case 'kehu' :
                $this->CHomeInit();
                break;

            case 'shanghu' :
                $this->MHomeInit();
                break;
        }
	}
	
	/**
	 * [CHomeInit 客户端 - 首页初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	private function CHomeInit()
	{
		// 初始化数据
		$result = array(
			'username'		=> '用户名',
			'take_count'	=> 0,
			'is_service'	=> 0,
			'service_date'	=> null,
			'merchant_id'	=> 0,
			'banner'		=> null,
		);

		if(!empty($this->user))
		{
			// 基础信息
			$result['username'] = $this->user['username'];
			$result['merchant_id'] = $this->user['merchant_id'];

			// 待预约取件总数
			$where = array('user_id'=>$this->user['id'], 'status'=>0, 'is_delete'=>0);
			$result['take_count'] = intval(M('TakeOrder')->where($where)->count());

			// 服务
			$service_date = UserServiceExpire($this->user['service_expire_time']);
			if($service_date !== false)
			{
				$result['is_service'] = 1;
				$result['service_date'] = $service_date;
			}
		}

		// 轮播图片
		$banner = M('Slide')->field('jump_url,images_url,name')->where(['type'=>0, 'is_enable'=>1])->select();
		if(!empty($banner))
		{
			$images_host = C('IMAGE_HOST');
			foreach($banner as &$v)
			{
				$v['images_url'] = $images_host.$v['images_url'];
				$v['jump_url'] = empty($v['jump_url']) ? null : $v['jump_url'];
			}
			$result['banner'] = $banner;
		}

		// 返回数据
		$this->ajaxReturn(L('common_operation_success'), 0, $result);
	}

	/**
	 * [ServiceInit 客户端 - 服务记录初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T16:50:32+0800
	 */
	public function ServiceInit()
	{
		// 初始化数据
		$result = array(
			'take_count'	=> 0,
			'send_count'	=> 0,
		);

		if(!empty($this->user))
		{
			$where = array('user_id'=>$this->user['id'], 'is_delete'=>0);
			$result['take_count'] = M('TakeOrder')->where($where)->count();
			$result['send_count'] = M('SendOrder')->where($where)->count();
		}

		// 返回数据
		$this->ajaxReturn(L('common_operation_success'), 0, $result);
	}

	/**
	 * [MHomeInit 商户端 - 首页初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-05-25T11:03:43+0800
	 */
	private function MHomeInit()
	{
		// 初始化数据
		$result = array(
			'username'				=> '用户名',
			'merchant_id'			=> 0,
			'merchant_status'		=> 0,
			'take_notice_count'		=> 0,
			'take_success_count'	=> 0,
			'send_notice_count'		=> 0,
			'send_storage_count'	=> 0,
			'send_success_count'	=> 0,
			'referrer_user_count'	=> 0,
			'is_may_submit_audit'	=> 0,
			'banner'				=> null,
		);

		// 用户信息
		if(!empty($this->user))
		{
			// 基础信息
			$result['username'] = $this->user['username'];
			$result['merchant_id'] = $this->user['merchant_id'];
		}

		// 商户信息
		if(!empty($this->merchant))
		{
			// 状态
			$result['merchant_status'] = $this->merchant['status'];

			// db
			$take_m = M('TakeOrder');
			$send_m = M('SendOrder');

			// 条件
			$where = array('merchant_id'=>$this->merchant['id'], 'is_delete'=>0);

			// 取件通知总数
			$where['status'] = 1;
			$result['take_notice_count'] = intval($take_m->where($where)->count());

			// 取件完成总数
			$where['status'] = 2;
			$result['take_success_count'] = intval($take_m->where($where)->count());


			// 收件通知总数
			$where['status'] = 0;
			$result['send_notice_count'] = intval($send_m->where($where)->count());

			// 收件入库总数
			$where['status'] = ['in', [1,2]];
			$result['send_storage_count'] = intval($send_m->where($where)->count());

			// 收件入库总数
			$where['status'] = 3;
			$result['send_success_count'] = intval($send_m->where($where)->count());


			// 推荐用户购买服务数量
			$user_where = ['referrer'=>$this->user['id'], 'is_delete'=>0, 'service_buy_number'=>['gt', 0]];
			$result['referrer_user_count'] = intval(M('User')->where($user_where)->count());
			
			// 当前商户是否可提交审核
			if(in_array($this->merchant['status'], [0,3]))
			{
				$common_shanghu_referrer_user_number = intval(MyC('common_shanghu_referrer_user_number', 0));
				if($common_shanghu_referrer_user_number == 0)
				{
					$result['is_may_submit_audit'] = 1;
				} else {
					if($result['referrer_user_count'] >= $common_shanghu_referrer_user_number)
					{
						$result['is_may_submit_audit'] = 1;
					}
				}
			}
		}

		// 轮播图片
		$banner = M('Slide')->field('jump_url,images_url,name')->where(['type'=>1, 'is_enable'=>1])->select();
		if(!empty($banner))
		{
			$images_host = C('IMAGE_HOST');
			foreach($banner as &$v)
			{
				$v['images_url'] = $images_host.$v['images_url'];
				$v['jump_url'] = empty($v['jump_url']) ? null : $v['jump_url'];
			}
			$result['banner'] = $banner;
		}

		// 返回数据
		$this->ajaxReturn(L('common_operation_success'), 0, $result);
	}

	/**
	 * [QueryInit 商户端 - 信息查询初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-05-25T11:03:43+0800
	 */
	public function QueryInit()
	{
		// 初始化数据
		$result = array(
			'take_count'	=> 0,
			'send_count'	=> 0,
			'user_count'	=> 0,
		);

		if(!empty($this->merchant))
		{
			$where = array('merchant_id'=>$this->merchant['id'], 'is_delete'=>0);
			$result['take_count'] = M('TakeOrder')->where($where)->count();
			$result['send_count'] = M('SendOrder')->where($where)->count();
			$result['user_count'] = M('User')->where($where)->count();
		}

		// 返回数据
		$this->ajaxReturn(L('common_operation_success'), 0, $result);
	}
}
?>