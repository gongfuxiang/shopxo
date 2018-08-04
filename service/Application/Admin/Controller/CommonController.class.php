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
			'common_agreement_kehu',
			'common_agreement_shanghu',
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
	 * 获取属性参数
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-09
	 * @desc    description
	 */
	protected function GetFormGoodsAttributeParams()
	{
		$result = [];
		foreach($_POST as $k=>$v)
		{
			if(substr($k, 0, 9) == 'attribute')
			{
				// key分解
				$key = explode('_', $k);

				// 当前key是否是具体属性数据
				if(in_array('find', $key))
				{
					$result[$key[1]][$key[2]][$key[3]][$key[4]] = $v;
				} else {
					// 属性类型数据
					$result[$key[1]][$key[2]][$key[3]] = $v;
				}
			}
		}
		return ['status'=>true, 'data'=>$result];
	}

	/**
	 * 获取app内容
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-09
	 * @desc    description
	 */
	protected function GetFormGoodsContentAppParams()
	{
		// 图像类库
		$images_obj = \Library\Images::Instance(['is_new_name'=>false]);

		// 定义图片目录
		$root_path = ROOT_PATH;
		$img_path = 'Public'.DS.'Upload'.DS.'goods_app'.DS;
		$date = DS.date('Y').DS.date('m').DS.date('d').DS;

		// 开始处理
		$result = [];
		$name = 'content_app_';
		foreach($_POST AS $k=>$v)
		{
			if(substr($k, 0, 12) == $name)
			{
				$key = explode('_', str_replace($name, '', $k));
				if(count($key) == 2)
				{
					$result[$key[1]][$key[0]] = $v;

					if($key[0] == 'images')
					{
						$images_key = $name.$key[0].'_file_'.$key[1];
						if(isset($_FILES[$images_key]))
						{
							// 文件上传校验
							$error = FileUploadError($images_key);
							if($error !== true)
							{
								return ['status'=>false, 'msg'=>$error];
							}

							// 存储图片
							$temp_all = [
									'tmp_name'	=>	$_FILES[$images_key]['tmp_name'],
									'type'		=>	$_FILES[$images_key]['type']
								];
							$original = $images_obj->GetOriginal($temp_all, $root_path.$img_path.'original'.$date);
							if(!empty($original))
							{
								// 根据原图再次生成小图
								$compr = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'compr'.$date, 600);
								$small = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'small'.$date, 100, 100);

								if(!empty($compr))
								{
									$result[$key[1]][$key[0]] = DS.$img_path.'compr'.$date.$small;
								} else {
									// 如果图片格式有误，则删除原图片
									$this->ImagesDelete($img_path.'original'.$date.$original);
								}
				 			}
						}
					}
				}
			}
		}
		return ['status'=>true, 'data'=>$result];
	}

	/**
	 * 获取商品相册
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @return  [array]          [一维数组但图片地址]
	 */
	protected function GetFormGoodsPhotoParams()
	{
		// 原始图片
		if(!empty($_POST['photo']) && is_array($_POST['photo']))
		{
			$result = $_POST['photo'];
		} else {
			$result = [];
		}

		// 开始处理图片存储
		$images_name = 'photo_file';
		if(!empty($_FILES[$images_name]))
		{
			// 定义图片目录
			$root_path = ROOT_PATH;
			$img_path = 'Public'.DS.'Upload'.DS.'goods_photo'.DS;
			$date = DS.date('Y').DS.date('m').DS.date('d').DS;

			// 图像类库
			$images_obj = \Library\Images::Instance(['is_new_name'=>false]);
			
			// 图片存储处理
			for($i=0; $i<count($_FILES[$images_name]['tmp_name']); $i++)
			{
				// 文件上传校验
				$error = FileUploadError($images_name, $i);
				if($error !== true)
				{
					return ['status'=>false, 'msg'=>$error];
				}

				// 存储图片
				$temp_all = [
						'tmp_name'	=>	$_FILES[$images_name]['tmp_name'][$i],
						'type'		=>	$_FILES[$images_name]['type'][$i]
					];
				$original = $images_obj->GetOriginal($temp_all, $root_path.$img_path.'original'.$date);
				if(!empty($original))
				{
					// 根据原图再次生成小图
					$compr = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'compr'.$date, 600);
					$small = $images_obj->GetBinaryCompress($root_path.$img_path.'original'.$date.$original, $root_path.$img_path.'small'.$date, 100, 100);

					if(!empty($compr))
					{
						$result[] = DS.$img_path.'compr'.$date.$small;
					} else {
						// 如果图片格式有误，则删除原图片
						$this->ImagesDelete($img_path.'original'.$date.$original);
					}
	 			}
			}
		}

		return ['status'=>true, 'data'=>$result];
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
}
?>