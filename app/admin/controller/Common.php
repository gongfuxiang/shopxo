<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\BaseController;
use app\module\FormHandleModule;
use app\service\SystemService;
use app\service\SystemBaseService;
use app\service\AdminService;
use app\service\AdminPowerService;
use app\service\ConfigService;
use app\service\ResourcesService;
use app\service\StoreService;

/**
 * 管理员公共控制器
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Common extends BaseController
{
	// 管理员
	protected $admin;

	// 左边权限菜单
	protected $left_menu;

    // 输入参数 post|get|request
    protected $data_post;
    protected $data_get;
    protected $data_request;

    // 当前系统操作名称
    protected $module_name;
    protected $controller_name;
    protected $action_name;

    // 当前插件操作名称
    protected $plugins_module_name;
    protected $plugins_controller_name;
    protected $plugins_action_name;

    // 动态表格
    protected $form_table;
    protected $form_where;
    protected $form_params;
    protected $form_md5_key;
    protected $form_user_fields;
    protected $form_order_by;
    protected $form_error;

    // 列表数据
    protected $data_total;
    protected $data_list;
    protected $data_detail;

    // 分页信息
    protected $page;
    protected $page_start;
    protected $page_size;
    protected $page_html;
    protected $page_url;

    // 系统类型
    protected $system_type;

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
        // 检测是否是新安装
        SystemService::SystemInstallCheck();

        // 输入参数
        $this->data_post = input('post.');
        $this->data_get = input('get.');
        $this->data_request = input();

		// 系统初始化
        $this->SystemInit();

		// 管理员信息
		$this->admin = AdminService::LoginInfo();

		// 权限菜单
		AdminPowerService::PowerMenuInit($this->admin);
		$this->left_menu = AdminPowerService::MenuData($this->admin);

		// 视图初始化
		$this->ViewInit();

        // 动态表格初始化
        $this->FormTableInit();

        // 公共钩子初始化
        $this->CommonPluginsInit();
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
    }

	/**
	 * [IsLogin 登录校验]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:42:35+0800
	 */
	protected function IsLogin()
	{
		if(empty($this->admin))
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
        // 系统类型
        $this->system_type = SystemService::SystemTypeValue();
        MyViewAssign('system_type', $this->system_type);

        // 公共参数
        MyViewAssign('params', $this->data_request);

		// 主题
        $default_theme = 'default';
        MyViewAssign('default_theme', $default_theme);

        // 当前系统操作名称
        $this->module_name = RequestModule();
        $this->controller_name = RequestController();
        $this->action_name = RequestAction();

        // 当前系统操作名称
        MyViewAssign('module_name', $this->module_name);
        MyViewAssign('controller_name', $this->controller_name);
        MyViewAssign('action_name', $this->action_name);

        // 当前插件操作名称, 兼容插件模块名称
        if(empty($this->data_request['pluginsname']))
        {
            $this->plugins_module_name = '';
            $this->plugins_controller_name = '';
            $this->plugins_action_name = '';
        } else {
            $this->plugins_module_name = $this->data_request['pluginsname'];
            $this->plugins_controller_name = empty($this->data_request['pluginscontrol']) ? 'index' : $this->data_request['pluginscontrol'];
            $this->plugins_action_name = empty($this->data_request['pluginsaction']) ? 'index' : $this->data_request['pluginsaction'];
        }

        // 当前插件操作名称
        MyViewAssign('plugins_module_name', $this->plugins_module_name);
        MyViewAssign('plugins_controller_name', $this->plugins_controller_name);
        MyViewAssign('plugins_action_name', $this->plugins_action_name);

        // 管理员
        MyViewAssign('admin', $this->admin);

        // 权限菜单
        MyViewAssign('left_menu', $this->left_menu);

        // 分页信息
        $this->page = max(1, isset($this->data_request['page']) ? intval($this->data_request['page']) : 1);
        $this->page_size = min(empty($this->data_request['page_size']) ? MyC('common_page_size', 10, true) : intval($this->data_request['page_size']), 1000);
        MyViewAssign('page', $this->page);
        MyViewAssign('page_size', $this->page_size);

        // 货币符号
        MyViewAssign('currency_symbol', ResourcesService::CurrencyDataSymbol());

		// 控制器静态文件状态css,js
        $module_css = $this->module_name.DS.$default_theme.DS.'css'.DS.$this->controller_name;
        $module_css .= file_exists(ROOT_PATH.'static'.DS.$module_css.'.'.$this->action_name.'.css') ? '.'.$this->action_name.'.css' : '.css';
        MyViewAssign('module_css', file_exists(ROOT_PATH.'static'.DS.$module_css) ? $module_css : '');

        $module_js = $this->module_name.DS.$default_theme.DS.'js'.DS.$this->controller_name;
        $module_js .= file_exists(ROOT_PATH.'static'.DS.$module_js.'.'.$this->action_name.'.js') ? '.'.$this->action_name.'.js' : '.js';
        MyViewAssign('module_js', file_exists(ROOT_PATH.'static'.DS.$module_js) ? $module_js : '');

        // 价格正则
        MyViewAssign('default_price_regex', MyConst('common_regex_price'));

		// 附件host地址
        MyViewAssign('attachment_host', SystemBaseService::AttachmentHost());

        // css/js引入host地址
        MyViewAssign('public_host', MyConfig('shopxo.public_host'));

        // 当前url地址
        MyViewAssign('my_domain', __MY_DOMAIN__);

        // 当前完整url地址
        MyViewAssign('my_url', __MY_URL__);

        // 项目public目录URL地址
        MyViewAssign('my_public_url', __MY_PUBLIC_URL__);

        // 当前http类型
        MyViewAssign('my_http', __MY_HTTP__);

        // 首页地址
        MyViewAssign('home_url', SystemService::HomeUrl());

        // 开发模式
        MyViewAssign('shopxo_is_develop', MyConfig('shopxo.is_develop'));

        // 是否加载视频播放器组件
        MyViewAssign('is_load_ckplayer', 0);

        // 默认不加载地图api、类型默认百度地图
        MyViewAssign('is_load_map_api', 0);
        MyViewAssign('load_map_type', MyC('common_map_type', 'baidu', true));

        // 布局样式+管理
        MyViewAssign('is_load_layout', 0);
        MyViewAssign('is_load_layout_admin', 0);

        // 是否加载附件组件
        MyViewAssign('is_load_upload_editor', !empty($this->admin) ? 1 : 0);

        // 站点名称
        MyViewAssign('admin_theme_site_name', MyC('admin_theme_site_name', 'ShopXO', true));

        // 站点商店信息
        $site_store_error = '';
        $site_store_info = StoreService::SiteStoreInfo();
        if(empty($site_store_info))
        {
            $ret = StoreService::SiteStoreAccountsBindHandle();
            if($ret['code'] == 0)
            {
                $site_store_info = StoreService::SiteStoreInfo();
            } else {
                $site_store_error = $ret['msg'];
            }
        }
        MyViewAssign('site_store_error', $site_store_error);
        MyViewAssign('site_store_info', $site_store_info);

        // 更多链接地址
        $site_store_links = empty($site_store_info['links']) ? [] : $site_store_info['links'];
        MyViewAssign('site_store_links', $site_store_links);

        // 系统基础信息
        $is_system_show_base = (empty($site_store_info) || empty($site_store_info['vip']) || !isset($site_store_info['vip']['status']) || $site_store_info['vip']['status'] == 0 || ($site_store_info['vip']['status'] == 1 && (AdminIsPower('index', 'storeaccountsbind') || AdminIsPower('index', 'inspectupgrade')))) ? 1 : 0;
        MyViewAssign('is_system_show_base', $is_system_show_base);

        // 后台公告
        $admin_notice = MyC('admin_notice');
        MyViewAssign('admin_notice',  empty($admin_notice) ? '' : str_replace("\n", '<br />', $admin_notice));

        // 系统环境参数最大数
        MyViewAssign('env_max_input_vars_count', SystemService::EnvMaxInputVarsCount());
	}

    /**
     * 动态表格初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     */
    public function FormTableInit()
    {
        // 获取表格模型
        $module = FormModulePath($this->data_request);
        if(!empty($module))
        {
            // 调用表格处理
            $params = $this->data_request;
            $params['system_admin'] = $this->admin;
            $ret = (new FormHandleModule())->Run($module['module'], $module['action'], $params);
            if($ret['code'] == 0)
            {
                // 表格数据
                $this->form_table = $ret['data']['table'];
                $this->form_where = $ret['data']['where'];
                $this->form_params = $ret['data']['params'];
                $this->form_md5_key = $ret['data']['md5_key'];
                $this->form_user_fields = $ret['data']['user_fields'];
                $this->form_order_by = $ret['data']['order_by'];
                MyViewAssign('form_table', $this->form_table);
                MyViewAssign('form_params', $this->form_params);
                MyViewAssign('form_md5_key', $this->form_md5_key);
                MyViewAssign('form_user_fields', $this->form_user_fields);
                MyViewAssign('form_order_by', $this->form_order_by);

                // 列表数据
                $this->data_total = $ret['data']['data_total'];
                $this->data_list = $ret['data']['data_list'];
                $this->data_detail = $ret['data']['data_detail'];
                MyViewAssign('data_total', $this->data_total);
                MyViewAssign('data_list', $this->data_list);
                MyViewAssign('data', $this->data_detail);

                // 分页数据
                $this->page = $ret['data']['page'];
                $this->page_start = $ret['data']['page_start'];
                $this->page_size = $ret['data']['page_size'];
                $this->page_html = $ret['data']['page_html'];
                $this->page_url = $ret['data']['page_url'];
                MyViewAssign('page', $this->page);
                MyViewAssign('page_start', $this->page_start);
                MyViewAssign('page_size', $this->page_size);
                MyViewAssign('page_html', $this->page_html);
                MyViewAssign('page_url', $this->page_url);
            } else {
                $this->form_error = $ret['msg'];
                MyViewAssign('form_error', $this->form_error);
            }
        }
    }

	/**
	 * [IsPower 是否有权限]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-20T19:18:29+0800
	 */
	protected function IsPower()
	{
		// 不需要校验权限的方法
		$unwanted_power = ['getnodeson'];
        if(!AdminIsPower(null, null, $unwanted_power))
        {
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn('无权限', -1000)));
            } else {
                return $this->error('无权限');
            }
        }
	}

    /**
     * 成功提示
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-15
     * @desc    description
     * @param   [string]      $msg [提示信息、默认（操作成功）]
     */
    public function success($msg)
    {
        if(IS_AJAX)
        {
            return DataReturn($msg, 0);
        } else {
            MyViewAssign('msg', $msg);
            return MyView('public/jump_success');
        }
    }

    /**
     * 错误提示
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-15
     * @desc    description
     * @param   [string]      $msg [提示信息、默认（操作失败）]
     */
    public function error($msg)
    {
        if(IS_AJAX)
        {
            return DataReturn($msg, -1);
        } else {
            MyViewAssign('msg', $msg);
            return MyView('public/jump_error');
        }
    }

    /**
     * 空方法响应
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     * @param   [string]         $method [方法名称]
     * @param   [array]          $args   [参数]
     */
    public function __call($method, $args)
    {
        if(IS_AJAX)
        {
            return DataReturn($method.' 非法访问', -1000);
        } else {
            MyViewAssign('msg', $method.' 非法访问');
            return MyView('public/tips_error');
        }
    }

    /**
     * 公共钩子初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-07
     * @desc    description
     */
    private function CommonPluginsInit()
    {
        // css钩子
        MyViewAssign('plugins_admin_css_data', MyEventTrigger('plugins_admin_css', ['hook_name'=>'plugins_admin_css', 'is_backend'=>true]));

        // js钩子
        MyViewAssign('plugins_admin_js_data', MyEventTrigger('plugins_admin_js', ['hook_name'=>'plugins_admin_js', 'is_backend'=>true]));
        
        // 公共header内钩子
        MyViewAssign('plugins_admin_common_header_data', MyEventTrigger('plugins_admin_common_header', ['hook_name'=>'plugins_admin_common_header', 'is_backend'=>true, 'admin'=>$this->admin]));

        // 公共页面底部钩子
        MyViewAssign('plugins_admin_common_page_bottom_data', MyEventTrigger('plugins_admin_common_page_bottom', ['hook_name'=>'plugins_admin_common_page_bottom', 'is_backend'=>true, 'admin'=>$this->admin]));

        // 公共顶部钩子
        MyViewAssign('plugins_admin_view_common_top_data', MyEventTrigger('plugins_admin_view_common_top', ['hook_name'=>'plugins_admin_view_common_top', 'is_backend'=>true, 'admin'=>$this->admin]));

        // 公共底部钩子
        MyViewAssign('plugins_admin_view_common_bottom_data', MyEventTrigger('plugins_admin_view_common_bottom', ['hook_name'=>'plugins_admin_view_common_bottom', 'is_backend'=>true, 'admin'=>$this->admin]));

        // 公共表格钩子名称动态处理
        $current = 'plugins_view_admin_'.$this->controller_name;

        // 是否插件默认下
        if($this->controller_name == 'plugins')
        {
            if(!empty($this->plugins_module_name))
            {
                $current .= '_'.$this->plugins_module_name.'_'.$this->plugins_controller_name;
            }
        }

        // 表格列表公共标识
        MyViewAssign('hook_name_form_grid', $current.'_grid');
        // 内容外部顶部
        MyViewAssign('hook_name_content_top', $current.'_content_top');
        // 内容外部底部
        MyViewAssign('hook_name_content_bottom', $current.'_content_bottom');
        // 内容内部顶部
        MyViewAssign('hook_name_content_inside_top', $current.'_content_inside_top');
        // 内容内部底部
        MyViewAssign('hook_name_content_inside_bottom', $current.'_content_inside_bottom');
        // 表格列表顶部操作
        MyViewAssign('hook_name_form_top_operate', $current.'_top_operate');
        // 表格列表底部操作
        MyViewAssign('hook_name_form_bottom_operate', $current.'_bottom_operate');
        // 表格列表后面操作栏
        MyViewAssign('hook_name_form_list_operate', $current.'_list_operate');

        // 公共详情页面钩子名称动态处理
        // 内容外部顶部
        MyViewAssign('hook_name_detail_top', $current.'_detail_top');
        // 内容外部底部
        MyViewAssign('hook_name_detail_bottom', $current.'_detail_bottom');
        // 内容内部顶部
        MyViewAssign('hook_name_detail_inside_top', $current.'_detail_inside_top');
        // 内容内部底部
        MyViewAssign('hook_name_detail_inside_bottom', $current.'_detail_inside_bottom');
    }
}
?>