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
            $this->ajaxReturn(L('common_unauthorized_access'));
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
		// 初始化数据
		$result = array(
			'username'				=> null,
			'nickname'				=> null,
			'cart_count'			=> 0,
			'banner'				=> [],
			'category'				=> [],
			'customer_service_tel'  => MyC('common_customer_service_tel'),
		);

		if(!empty($this->user))
		{
			// 基础信息
			$result['username'] = $this->user['username'];
			$result['nickname'] = $this->user['nickname'];

			// 购物车总数
			$where = array('user_id'=>$this->user['id']);
			$result['cart_count'] = intval(M('Cart')->where($where)->count());
		}

		// 轮播图片
		$banner = M('Slide')->field('jump_url,jump_url_type,images_url,name')->where(['platform'=>APPLICATION_CLIENT_TYPE, 'is_enable'=>1])->select();
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

		// 商品分类
		$result['category'] = $this->GetGoodsCategoryList();

		// 返回数据
		$this->ajaxReturn(L('common_operation_success'), 0, $result);
	}
}
?>