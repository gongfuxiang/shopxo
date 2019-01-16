<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
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
        \think\facade\Url::root(__MY_ROOT_PUBLIC__.'index.php?s=');
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
				die('<script type="text/javascript">if(self.frameElement && self.frameElement.tagName == "IFRAME"){parent.location.reload();}else{window.location.href="'.MyUrl('admin/admin/logininfo').'";}</script>');
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
		$this->assign('attachment_host', config('shopxo.attachment_host'));
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
			$power = empty($this->power) ? [] : $this->power;
            if(!in_array(strtolower(request()->controller().'_'.request()->action()), $power))
			{
                if(IS_AJAX)
                {
                    exit(json_encode(DataReturn('无权限', -1000)));
                } else {
                    return $this->error('无权限');
                }
			}
		}
	}

    /**
     * [_empty 空方法操作]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-25T15:47:50+0800
     * @param    [string]      $name [方法名称]
     */
    public function _empty($name)
    {
        if(IS_AJAX)
        {
            exit(json_encode(DataReturn($name.' 非法访问', -1000)));
        } else {
            exit($name.' 非法访问');
        }
    }
}
?>