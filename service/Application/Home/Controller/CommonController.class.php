<?php

namespace Home\Controller;

use Think\Controller;
use Service\GoodsService;
use Service\NavigationService;
use Service\BuyService;
use Service\MessageService;
use Service\ResourcesService;

/**
 * 前台
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CommonController extends Controller
{
	// 顶部导航
	protected $nav_header;

	// 底部导航
	protected $nav_footer;

	// 用户信息
	protected $user;

	/**
	 * [__construt 构造方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:29:53+0800
	 * @param    [string]       $msg  [提示信息]
	 * @param    [int]          $code [状态码]
	 * @param    [mixed]        $data [数据]
	 */
	protected function _initialize()
	{
		// 配置信息初始化
		MyConfigInit();

		// 公共数据初始化
		$this->CommonInit();

		// 菜单
		$this->NavInit();

		// 视图初始化
		$this->ViewInit();

		// 站点状态校验
		$this->SiteStateCheck();
	}

	/**
	 * [ajaxReturn 重写ajax返回方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-07T22:03:40+0800
	 * @param    [string]       $msg  [提示信息]
	 * @param    [int]          $code [状态码]
	 * @param    [mixed]        $data [数据]
	 * @return   [json]               [json数据]
	 */
	protected function ajaxReturn($msg = '', $code = 0, $data = '')
	{
		//清除缓冲区中的内容
		ob_clean();

		// 输出json
		header('Content-Type:application/json; charset=utf-8');
		exit(json_encode(DataReturn($msg, $code, $data)));
	}

	/**
	 * [Is_Login 登录校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T11:43:48+0800
	 */
	protected function Is_Login()
	{
		if(empty($_SESSION['user']))
		{
			if(IS_AJAX)
			{
				$this->ajaxReturn(L('common_login_invalid'), -400);
			} else {
				redirect(U('Home/User/LoginInfo'));
			}
		}
	}

	/**
	 * [CommonInit 公共数据初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T11:43:48+0800
	 */
	private function CommonInit()
	{
		// 用户数据
		if(!empty($_SESSION['user']))
		{
			$this->user = I('session.user');
		}
	}

	/**
	 * [ViewInit 视图初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:30:06+0800
	 */
	public function ViewInit()
	{
		// 主题
		$default_theme = C('DEFAULT_THEME');
		$this->assign('default_theme', $default_theme);

		// 控制器静态文件状态css,js
		$module_css = MODULE_NAME.DS.$default_theme.DS.'Css'.DS.CONTROLLER_NAME;
		$module_css .= file_exists(ROOT_PATH.'Public'.DS.$module_css.'.'.ACTION_NAME.'.css') ? '.'.ACTION_NAME.'.css' : '.css';
		$this->assign('module_css', file_exists(ROOT_PATH.'Public'.DS.$module_css) ? $module_css : '');

		$module_js = MODULE_NAME.DS.$default_theme.DS.'Js'.DS.CONTROLLER_NAME;
		$module_js .= file_exists(ROOT_PATH.'Public'.DS.$module_js.'.'.ACTION_NAME.'.js') ? '.'.ACTION_NAME.'.js' : '.js';
		$this->assign('module_js', file_exists(ROOT_PATH.'Public'.DS.$module_js) ? $module_js : '');

		// 导航
		$this->assign('nav_header', $this->nav_header);
		$this->assign('nav_footer', $this->nav_footer);

		// 导航/底部默认显示
		$this->assign('is_header', 1);
		$this->assign('is_footer', 1);

		// 图片host地址
		$this->assign('image_host', C('IMAGE_HOST'));

		// 标题
		$this->assign('home_seo_site_title', MyC('home_seo_site_title'));

		// 页面最大宽度
		$max_width = MyC('home_content_max_width', 0, true);
		$max_width_style = ($max_width == 0) ? '' : 'max-width:'.$max_width.'px;';
		$this->assign('max_width_style', $max_width_style);

		// 用户数据
		$this->assign('user', $this->user);

		// 用户中心菜单
		$this->assign('user_left_menu', L('user_left_menu'));

		// 商品大分类
		$this->assign('goods_category_list', GoodsService::GoodsCategory());

		// 购物车商品总数
		$common_cart_total = BuyService::UserCartTotal(['user'=>$this->user]);
		$this->assign('common_cart_total', ($common_cart_total > 99) ? '99+' : $common_cart_total);

		// 未读消息总数
		$params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0];
		$common_message_total = MessageService::UserMessageTotal($params);
		$this->assign('common_message_total', ($common_message_total > 99) ? '99+' : $common_message_total);

		// 搜索框下热门关键字
		$home_search_keywords = [];
		switch(intval(MyC('home_search_keywords_type', 0)))
		{
			case 1 :
				$home_search_keywords = ResourcesService::SearchKeywordsList();
				break;
			case 2 :
				$home_search_keywords = explode(',', MyC('home_search_keywords'));
				break;
		}
		$this->assign('home_search_keywords', $home_search_keywords);

		// 当前控制器名称
		$this->assign('controller_name', CONTROLLER_NAME);
	}

	/**
	 * [NavInit 导航初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-19T22:41:20+0800
	 */
	private function NavInit()
	{
		$navigation = NavigationService::Home();
		$this->nav_header = $navigation['header'];
		$this->nav_footer = $navigation['footer'];
	}

	/**
	 * [GetBrowserSeoTitle 获取浏览器seo标题]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-25T14:21:21+0800
	 * @param    [string]     $title [标题]
	 * @param    [int]     	  $type  [页面类型 0, 1, 2]
	 * @return   [string]            [浏览器seo标题]
	 */
	protected function GetBrowserSeoTitle($title, $type)
	{
		switch($type)
		{
			case 0:
				break;

			case 1:
				$site_name = MyC('home_site_name');
				break;

			default:
				$site_name = MyC('home_seo_site_title');
		}
		return empty($title) ? $site_name : $title.' - '.$site_name;
	}

	/**
	 * [_empty 空方法操作]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-25T15:47:50+0800
	 * @param    [string]      $name [方法名称]
	 */
	protected function _empty($name)
	{
		$this->assign('msg', L('common_unauthorized_access'));
		$this->display('/Public/Error');
	}

	/**
	 * [SiteStateCheck 站点状态校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-25T21:43:07+0800
	 */
	private function SiteStateCheck()
	{
		if(MyC('home_site_state') == 0)
		{
			// 是否ajax请求
        	if(IS_AJAX)
	        {
	            $this->error(MyC('home_site_close_reason', L('common_site_maintenance_tips')));
	        } else {
	        	$this->assign('msg', MyC('home_site_close_reason', L('common_site_maintenance_tips'), true));
				$this->assign('is_header', 0);
				$this->assign('is_footer', 0);
				$this->display('/Public/Error');
				exit;
	        }
		}
	}

	/**
	 * [CommonIsImaVerify 是否开启图片验证码校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-22T15:48:31+0800
	 * @param    [array] $verify_param [配置参数]
	 * @return   [object]              [图片验证码类对象]
	 */
	protected function CommonIsImaVerify($verify_param)
	{
		if(MyC('home_img_verify_state') == 1)
		{
			if(empty($_POST['verify']))
			{
				$this->ajaxReturn(L('common_param_error'), -10);
			}
			$verify = new \Library\Verify($verify_param);
			if(!$verify->CheckExpire())
			{
				$this->ajaxReturn(L('common_verify_expire'), -11);
			}
			if(!$verify->CheckCorrect(I('verify')))
			{
				$this->ajaxReturn(L('common_verify_error'), -12);
			}
			return $verify;
		}
	}

	/**
	 * [CommonVerifyEntry 验证码显示]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-05T15:10:21+0800
	 * @param    [string] $type [验证码类型]
	 */
	protected function CommonVerifyEntry($type = 'shopxo')
	{
		$param = array(
				'width' => 100,
				'height' => 32,
				'key_prefix' => $type,
			);
		$verify = new \Library\Verify($param);
		$verify->Entry();
	}

}
?>