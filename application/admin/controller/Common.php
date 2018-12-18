<?php
namespace app\admin\controller;

use think\Controller;
use app\service\AdminPowerService;
use app\service\ConfigService;

/**
 * 管理员公共控制器
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Common extends Controller
{
	// 管理员
	protected $admin;

	// 权限
	protected $power;

	// 左边权限菜单
	protected $left_menu;

	/**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();

		// 系统初始化
        $this->SystemInit();

		// 管理员信息
		$this->admin = session('admin');

		// 权限菜单
		AdminPowerService::PowerMenuInit();

		// 权限
		$this->left_menu = isset($this->admin['id']) ? cache(config('cache_admin_left_menu_key').$this->admin['id']) : [];
		$this->power = isset($this->admin['id']) ? cache(config('cache_admin_power_key').$this->admin['id']) : [];

		// 视图初始化
		$this->ViewInit();
	}

	/**
     * 系统初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-07
     * @desc    description
     */
    private function SystemInit()
    {
    	// 配置信息初始化
        ConfigService::ConfigInit();

        // url模式,后端采用兼容模式
        \think\facade\Url::root(__MY_ROOT__.'index.php?s=');
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
		if(session('admin') === null)
		{
			if(IS_AJAX)
			{
				exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
			} else {
				die('<script type="text/javascript">if(self.frameElement && self.frameElement.tagName == "IFRAME"){parent.location.reload();}else{window.location.href="'.url('admin/admin/logininfo').'";}</script>');
			}
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
        $default_theme = 'default';
        $this->assign('default_theme', $default_theme);

        // 当前操作名称
        $module_name = strtolower(request()->module());
        $controller_name = strtolower(request()->controller());
        $action_name = strtolower(request()->action());

        // 当前操作名称
        $this->assign('module_name', $module_name);
        $this->assign('controller_name', $controller_name);
        $this->assign('action_name', $action_name);

		// 控制器静态文件状态css,js
        $module_css = $module_name.DS.$default_theme.DS.'css'.DS.$controller_name;
        $module_css .= file_exists(ROOT_PATH.'static'.DS.$module_css.'.'.$action_name.'.css') ? '.'.$action_name.'.css' : '.css';
        $this->assign('module_css', file_exists(ROOT_PATH.'static'.DS.$module_css) ? $module_css : '');

        $module_js = $module_name.DS.$default_theme.DS.'js'.DS.$controller_name;
        $module_js .= file_exists(ROOT_PATH.'static'.DS.$module_js.'.'.$action_name.'.js') ? '.'.$action_name.'.js' : '.js';
        $this->assign('module_js', file_exists(ROOT_PATH.'static'.DS.$module_js) ? $module_js : '');

		// 权限菜单
		$this->assign('left_menu', $this->left_menu);

		// 用户
		$this->assign('admin', $this->admin);

		// 图片host地址
		$this->assign('image_host', config('IMAGE_HOST'));
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
		if(!in_array(strtolower(request()->action()), $unwanted_power))
		{
			// 角色组权限列表校验
			if(!in_array(strtolower(request()->controller().'_'.request()->action()), $this->power))
			{
				return $this->error('无权限');
			}
		}
	}

	/**
	 * 文件删除
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [string]          $img [图片地址 path+name]
	 */
	protected function FileDelete($img)
	{
		if(empty($img)) return false;

		if(file_exists(ROOT_PATH.$img))
		{
			return unlink(ROOT_PATH.$img);
		}
		return false;
	}

	/**
	 * 文件批量删除
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [array]          $img_all [图片地址 path+name]
	 */
	protected function FileDeleteAll($img_all)
	{
		if(!empty($img_all) && is_array($img_all))
		{
			for($i=0; $i<config($img_all); $i++)
			{
				$this->FileDelete($img_all[$i]);
				$this->FileDelete(str_replace(['compr', 'small'], 'small', $img_all[$i]));
				$this->FileDelete(str_replace(['compr', 'small'], 'compr', $img_all[$i]));
				$this->FileDelete(str_replace(['compr', 'small'], 'original', $img_all[$i]));
			}
		}
	}

	/**
	 * 文件存储
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-09-11
	 * @desc    description
	 * @param   [string]          $field 		[name名称]
	 * @param   [string]          $post_name 	[file form name名称]
	 * @param   [string]          $dir   		[存储路径标记]
	 */
	protected function FileSave($field, $post_name, $dir = 'common')
	{
		if(isset($_FILES[$post_name]['error']))
		{
			$path = DS.'static'.DS.'upload'.DS.$dir.DS.date('Y').DS.date('m').DS.date('d').DS;
			$file_obj = new \base\FileUpload(['root_path'=>ROOT_PATH, 'path'=>$path]);
			$ret = $file_obj->Save($post_name);
			if($ret['status'] === true)
			{
				$_POST[$field] = $ret['data']['url'];
			}
		}
	}
}
?>