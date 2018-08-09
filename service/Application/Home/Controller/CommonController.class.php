<?php

namespace Home\Controller;
use Think\Controller;

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
		// ajax的时候，success和error错误由当前方法接收
		if(IS_AJAX)
		{
			if(isset($msg['info']))
			{
				// success模式下code=0, error模式下code参数-1
				$result = array('msg'=>$msg['info'], 'code'=>-1, 'data'=>'');
			}
		}
		
		// 默认情况下，手动调用当前方法
		if(empty($result))
		{
			$result = array('msg'=>$msg, 'code'=>$code, 'data'=>$data);
		}

		// 错误情况下，防止提示信息为空
		if($result['code'] != 0 && empty($result['msg']))
		{
			$result['msg'] = L('common_operation_error');
		}
		exit(json_encode($result));
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
			$this->error(L('common_login_invalid'), U('Home/User/LoginInfo'));
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
		$module_css = MODULE_NAME.DS.$default_theme.DS.'Css'.DS.CONTROLLER_NAME.'.css';
		$this->assign('module_css', file_exists(ROOT_PATH.'Public'.DS.$module_css) ? $module_css : '');
		$module_js = MODULE_NAME.DS.$default_theme.DS.'Js'.DS.CONTROLLER_NAME.'.js';
		$this->assign('module_js', file_exists(ROOT_PATH.'Public'.DS.$module_js) ? $module_js : '');

		// 导航
		$this->assign('nav_header', $this->nav_header);
		$this->assign('nav_footer', $this->nav_footer);

		// 当前页面选择导航状态
		$nav_pid	=	0;
		$nav_id 	=	0;
		foreach($this->nav_header as $v)
		{
			if(I('viewid') == $v['id'])
			{
				$nav_id = $v['id'];
			}
			if(!empty($v['item']))
			{
				foreach($v['item'] as $vs)
				{
					if(I('viewid') == $vs['id'])
					{
						$nav_pid = $v['id'];
						$nav_id = $vs['id'];
					}
				}
			}
		}
		$this->assign('nav_pid', $nav_pid);
		$this->assign('nav_id', $nav_id);

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

		// 用户顶部菜单
		$this->assign('user_nav_menu', L('user_nav_menu'));
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
		// 读取缓存数据
		$this->nav_header = S(C('cache_common_home_nav_header_key'));
		$this->nav_footer = S(C('cache_common_home_nav_footer_key'));

		// 导航模型
		$m = M('Navigation');
		$field = array('id', 'pid', 'name', 'url', 'value', 'data_type', 'is_new_window_open');

		// 缓存没数据则从数据库重新读取,顶部菜单
		if(empty($this->nav_header))
		{
			$this->nav_header = NavDataDealWith($m->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>0))->order('sort')->select());
			if(!empty($this->nav_header))
			{
				foreach($this->nav_header as $k=>$v)
				{
					$this->nav_header[$k]['item'] = NavDataDealWith($m->field($field)->where(array('nav_type'=>'header', 'is_show'=>1, 'pid'=>$v['id']))->order('sort')->select());
				}
			}
			S(C('cache_common_home_nav_header_key'), $this->nav_header);
		}

		// 底部导航
		if(empty($this->nav_footer))
		{
			$this->nav_footer = NavDataDealWith($m->field($field)->where(array('nav_type'=>'footer', 'is_show'=>1))->order('sort')->select());
			S(C('cache_common_home_nav_footer_key'), $this->nav_footer);
		}
	}

	/**
	 * [GetClassList 获取班级列表,二级]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-30T13:26:00+0800
	 * @return [array] [班级列表]
	 */
	protected function GetClassList()
	{
		$m = M('Class');
		$data = $m->field(array('id', 'name'))->where(array('is_enable'=>1, 'pid'=>0))->select();
		if(!empty($data))
		{
			foreach($data as $k=>$v)
			{
				$data[$k]['item'] = $m->field(array('id', 'name'))->where(array('is_enable'=>1, 'pid'=>$v['id']))->select();
			}
		}
		return $data;
	}

	/**
	 * [GetRoomList 获取教室列表,二级]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-30T13:26:00+0800
	 * @return [array] [班级列表]
	 */
	protected function GetRoomList()
	{
		$m = M('Room');
		$data = $m->field(array('id', 'name'))->where(array('is_enable'=>1, 'pid'=>0))->select();
		if(!empty($data))
		{
			foreach($data as $k=>$v)
			{
				$data[$k]['item'] = $m->field(array('id', 'name'))->where(array('is_enable'=>1, 'pid'=>$v['id']))->select();
			}
		}
		return $data;
	}

	/**
	 * [GetLayoutList 获取布局-模块列表]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-02-22T10:15:40+0800
	 * @param  [string] $type [布局类型(home, channel, detail)]
	 * @return [array]        [布局+模块数据]
	 */
	protected function GetLayoutList($type = 'home')
	{
		// 布局+模块列表
		$data = M('Layout')->field(array('id', 'value'))->where(array('is_enable'=>1, 'type'=>$type))->order('sort asc, id desc')->select();
		if(!empty($data))
		{
			// 布局模块处理驱动
			$lay = \My\LayoutModule::SetInstance();

			// 开始处理布局数据
			foreach($data as $k=>$v)
			{
				// 模块
				$item = M('LayoutModule')->where(array('layout_id'=>$v['id']))->select();
				if(!empty($item))
				{
					foreach($item as $ik=>$iv)
					{
						// 获取文章数据
						$article = LayoutArticleList($lay->GetLayoutMouleWhere($iv), $iv);

						// 模块数据生成
						$fun = GetViewTitleStyleFun($iv['title_style']);
						if(method_exists($lay, $fun))
						{
							$html = $lay->$fun($article, $iv);
							$iv = $lay->GetRules();
						} else {
							$html = '';
						}

						// 重新赋值
						$item[$ik] = $html;

						// 模板赋值
						$this->assign('data_'.$iv['id'], $article);
						$this->assign('rules_'.$iv['id'], $iv);
					}
				}
				$data[$k]['item'] = $item;
			}
		}
		return $data;
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
		$this->assign('is_footer', 0);
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
			$this->assign('msg', MyC('home_site_close_reason', L('common_site_maintenance_tips'), true));
			$this->assign('is_footer', 0);
			$this->display('/Public/Error');
			exit;
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
			$verify = new \My\Verify($verify_param);
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
	protected function CommonVerifyEntry($type = 'schoolcms')
	{
		$param = array(
				'width' => 100,
				'height' => 32,
				'key_prefix' => $type,
			);
		$verify = new \My\Verify($param);
		$verify->Entry();
	}

	/**
	 * [UserLoginRecord 用户登录记录]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T11:37:43+0800
	 * @param    [int]     $user_id [用户id]
	 * @return   [boolean]          [记录成功true, 失败false]
	 */
	protected function UserLoginRecord($user_id = 0)
	{
		if(!empty($user_id))
		{
			$field = array('id', 'mobile', 'email', 'nickname', 'gender', 'signature', 'describe', 'birthday', 'add_time', 'upd_time');
			$user = M('User')->field($field)->find($user_id);
			if(!empty($user))
			{
				// 基础数据处理
				$user['add_time_text']	=	date('Y-m-d H:i:s', $user['add_time']);
				$user['upd_time_text']	=	date('Y-m-d H:i:s', $user['upd_time']);
				$user['gender_text']	=	L('common_gender_list')[$user['gender']]['name'];
				$user['birthday_text']	=	empty($user['birthday']) ? '' : date('Y-m-d', $user['birthday']);
				$user['mobile_security']=	empty($user['mobile']) ? '' : substr($user['mobile'], 0, 3).'***'.substr($user['mobile'], -3);
				$user['email_security']	=	empty($user['email']) ? '' : substr($user['email'], 0, 3).'***'.substr($user['email'], -3);

				// 存储session
				$_SESSION['user'] = $user;
				return !empty($_SESSION['user']);
			}
		}
		return false;
	}
}
?>