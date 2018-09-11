<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 管理员
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CommonController extends Controller
{
	// 管理员
	protected $admin;

	// 权限
	protected $power;

	// 左边权限菜单
	protected $left_menu;

	// 输入参数 post
    protected $data_post;

    // 输入参数 get
    protected $data_get;

    // 输入参数 request
    protected $data_request;

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
		
		// 权限
		$this->PowerInit();

		// 管理员信息
		$this->admin = I('session.admin');

		// 视图初始化
		$this->ViewInit();

		// 输入参数
        $this->data_post = I('post.');
        $this->data_get = I('get.');
        $this->data_request = I('request.');
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
	 * @datetime 2016-12-03T12:42:35+0800
	 */
	protected function Is_Login()
	{
		if(empty($_SESSION['admin']))
		{
			$this->error(L('common_login_invalid'), U('Admin/Admin/LoginInfo'));
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

		// 权限菜单
		$this->assign('left_menu', $this->left_menu);

		// 用户
		$this->assign('admin', $this->admin);

		// 图片host地址
		$this->assign('image_host', C('IMAGE_HOST'));
	}

	/**
	 * [PowerInit 权限初始化]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-19T22:41:20+0800
	 */
	private function PowerInit()
	{
		// 基础参数
		$admin_id = isset($_SESSION['admin']['id']) ? intval($_SESSION['admin']['id']) : 0;
		$role_id = isset($_SESSION['admin']['role_id']) ? intval($_SESSION['admin']['role_id']) : 0;

		// 读取缓存数据
		$this->left_menu = S(C('cache_admin_left_menu_key').$admin_id);
		$this->power = S(C('cache_admin_power_key').$admin_id);

		// 缓存没数据则从数据库重新读取
		if(($role_id > 0 || $admin_id == 1) && empty($this->left_menu))
		{
			// 获取一级数据
			$p = M('Power');
			if($admin_id == 1)
			{
				$field = array('id', 'name', 'control', 'action', 'is_show', 'icon');
				$this->left_menu = $p->where(array('pid' => 0))->field($field)->order('sort')->select();
			} else {
				$field = array('p.id', 'p.name', 'p.control', 'p.action', 'p.is_show', 'p.icon');
				$this->left_menu = $p->alias('p')->join('__ROLE_POWER__ AS rp ON p.id = rp.power_id')->where(array('rp.role_id' => $role_id, 'p.pid' => 0))->field($field)->order('p.sort')->select();
			}
			
			// 有数据，则处理子级数据
			if(!empty($this->left_menu))
			{
				foreach($this->left_menu as $k=>$v)
				{
					// 权限
					$this->power[$v['id']] = strtolower($v['control'].'_'.$v['action']);

					// 获取子权限
					if($admin_id == 1)
					{
						$item = $p->where(array('pid' => $v['id']))->field($field)->order('sort')->select();
					} else {
						$item = $p->alias('p')->join('__ROLE_POWER__ AS rp ON p.id = rp.power_id')->where(array('rp.role_id' => $role_id, 'p.pid' => $v['id']))->field($field)->order('p.sort')->select();
					}

					// 权限列表
					if(!empty($item))
					{
						foreach($item as $ks=>$vs)
						{
							// 权限
							$this->power[$vs['id']] = strtolower($vs['control'].'_'.$vs['action']);

							// 是否显示视图
							if($vs['is_show'] == 0)
							{
								unset($item[$ks]);
							}
						}
					}

					// 是否显示视图
					if($v['is_show'] == 1)
					{
						// 子级
						$this->left_menu[$k]['item'] = $item;
					} else {
						unset($this->left_menu[$k]);
					}
				}
			}
			S(C('cache_admin_left_menu_key').$admin_id, $this->left_menu);
			S(C('cache_admin_power_key').$admin_id, $this->power);
		}
	}

	/**
	 * [Is_Power 是否有权限]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-20T19:18:29+0800
	 */
	protected function Is_Power()
	{
		// 不需要校验权限的方法
		$unwanted_power = array('getnodeson');
		if(!in_array(strtolower(ACTION_NAME), $unwanted_power))
		{
			// 角色组权限列表校验
			if(!in_array(strtolower(CONTROLLER_NAME.'_'.ACTION_NAME), $this->power))
			{
				$this->error(L('common_there_is_no_power'));
			}
		}
	}

	/**
	 * [MyConfigSave 配置数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	protected function MyConfigSave()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数校验
		if(empty($_POST))
		{
			$this->error(L('common_param_error'));
		}

		// 循环保存数据
		$success = 0;
		$c = M('Config');

		// 不实例化的字段
		$no_all = array(
			'home_footer_info',
			'home_email_user_reg',
			'home_email_user_forget_pwd',
			'home_email_user_email_binding',
			'home_site_close_reason',
		);

		// 开始更新数据
		foreach($_POST as $k=>$v)
		{
			if(!in_array($k, $no_all))
			{
				$v = I($k);
			}
			if($c->where(array('only_tag'=>$k))->save(array('value'=>$v, 'upd_time'=>time())))
			{
				$success++;
			}
		}
		if($success > 0)
		{
			// 配置信息更新
			MyConfigInit(1);

			$this->ajaxReturn(L('common_operation_edit_success').'['.$success.']');
		} else {
			$this->ajaxReturn(L('common_operation_edit_error'), -100);
		}
	}

	/**
	 * 图片删除
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [string]          $img [图片地址 path+name]
	 */
	protected function ImagesDelete($img)
	{
		if(empty($img)) return false;

		if(file_exists(ROOT_PATH.$img))
		{
			return unlink(ROOT_PATH.$img);
		}
		return false;
	}

	/**
	 * 图片批量删除
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [array]          $img_all [图片地址 path+name]
	 */
	protected function ImagesDeleteAll($img_all)
	{
		if(!empty($img_all) && is_array($img_all))
		{
			for($i=0; $i<count($img_all); $i++)
			{
				$this->ImagesDelete($img_all[$i]);
				$this->ImagesDelete(str_replace(['compr', 'small'], 'small', $img_all[$i]));
				$this->ImagesDelete(str_replace(['compr', 'small'], 'compr', $img_all[$i]));
				$this->ImagesDelete(str_replace(['compr', 'small'], 'original', $img_all[$i]));
			}
		}
	}

	/**
	 * 图片存储
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-09-11
	 * @desc    description
	 * @param   [string]          $field 		[name名称]
	 * @param   [string]          $post_name 	[file form name名称]
	 * @param   [string]          $dir   		[存储路径标记]
	 */
	protected function ImagesSave($field, $post_name, $dir = 'common')
	{
		if(isset($_FILES[$post_name]['error']))
		{
			// 文件上传校验
			$error = FileUploadError($post_name);
			if($error !== true)
			{
				$this->ajaxReturn($error, -1);
			}

			// 文件类型
			list($type, $suffix) = explode('/', $_FILES[$post_name]['type']);
			$path = 'Public'.DS.'Upload'.DS.$dir.DS.'images'.DS;
			if(!is_dir($path))
			{
				mkdir(ROOT_PATH.$path, 0777, true);
			}
			$filename = date('YmdHis').'_logo.'.$suffix;
			$file = $path.$filename;
			if(move_uploaded_file($_FILES[$post_name]['tmp_name'], ROOT_PATH.$file))
			{
				$_POST[$field] = DS.$file;
			}
		}
	}
}
?>