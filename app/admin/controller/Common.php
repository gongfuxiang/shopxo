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
use app\module\FormTableHandleModule;
use app\service\SystemService;
use app\service\SystemBaseService;
use app\service\AdminService;
use app\service\AdminPowerService;
use app\service\ResourcesService;
use app\service\StoreService;
use app\service\MultilingualService;
use app\service\ConfigService;

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
    // 当前管理员可使用的插件
    protected $admin_plugins;

    // 当前主题
    protected $default_theme;

    // 输入参数 post|get|request
    protected $data_post;
    protected $data_get;
    protected $data_request;

    // 页面操作表单
    protected $form_back_params;
    protected $form_back_control;
    protected $form_back_action;
    protected $form_back_url;

    // 当前系统操作名称
    protected $module_name;
    protected $controller_name;
    protected $action_name;
    protected $mca;

    // 当前插件操作名称
    protected $plugins_module_name;
    protected $plugins_controller_name;
    protected $plugins_action_name;
    protected $plugins_mca;

    // 页面唯一标记
    protected $page_unique_mark;

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

		// 系统初始化
        $this->SystemInit();

        // 系统运行开始
        SystemService::SystemBegin($this->data_request);

		// 管理员信息
		$this->admin = AdminService::LoginInfo();

		// 权限菜单、当前管理员可使用的插件
        $menu = AdminPowerService::PowerMenuInit($this->admin);
		$this->left_menu = $menu['admin_left_menu'];
        $this->admin_plugins = $menu['admin_plugins'];

		// 视图初始化
		$this->ViewInit();

        // 公共钩子初始化
        $this->CommonPluginsInit();
	}

    /**
     * 析构函数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     */
    public function __destruct()
    {
        // 系统运行结束
        SystemService::SystemEnd($this->data_request);
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
    	// 输入参数
        $this->data_post = input('post.');
        $this->data_get = input('get.');
        $this->data_request = input();

        // 配置信息初始化
        ConfigService::ConfigInit();
    }

	/**
	 * 登录校验
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
				exit(json_encode(DataReturn(MyLang('login_failure_tips'), -400, [
                    'login'   => MyUrl('admin/admin/logininfo'),
                    'logout'  => MyUrl('admin/admin/logout'),
                ])));
			} else {
				die('<script type="text/javascript">if(self.frameElement && self.frameElement.tagName == "IFRAME"){parent.location.reload();}else{window.location.href="'.MyUrl('admin/admin/logininfo').'";}</script>');
			}
		}
	}

	/**
	 * 视图初始化
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:30:06+0800
	 */
	public function ViewInit()
	{
        // 模板数据
        $assign = [
            // 静态文件缓存版本号
            'static_cache_version'   => MyC('home_static_cache_version'),
            // logo
            'home_site_logo'         => AttachmentPathViewHandle(MyC('home_site_logo')),
            'home_site_logo_wap'     => AttachmentPathViewHandle(MyC('home_site_logo_wap')),
            'home_site_logo_app'     => AttachmentPathViewHandle(MyC('home_site_logo_app')),
            'home_site_logo_square'  => AttachmentPathViewHandle(MyC('home_site_logo_square')),
            // 站点名称
            'home_site_name'         => MyC('home_site_name'),
        ];

        // 系统类型
        $this->system_type = SystemService::SystemTypeValue();
        $assign['system_type'] = $this->system_type;

        // 公共参数（去除多余的参数、避免给页面url地址造成污染）
        $temp_params = $this->data_request;
        unset($temp_params['s'], $temp_params['pluginsname'], $temp_params['pluginscontrol'], $temp_params['pluginsaction']);
        $assign['params'] = $temp_params;

		// 主题
        $this->default_theme = 'default';
        $assign['default_theme'] = $this->default_theme;

        // 当前系统操作名称
        $this->module_name = RequestModule();
        $this->controller_name = RequestController();
        $this->action_name = RequestAction();
        $this->mca = $this->module_name.$this->controller_name.$this->action_name;

        // 当前系统操作名称
        $assign['module_name'] = $this->module_name;
        $assign['controller_name'] = $this->controller_name;
        $assign['action_name'] = $this->action_name;
        $assign['mca'] = $this->mca;

        // 当前插件操作名称, 兼容插件模块名称
        if(empty($this->data_request['pluginsname']))
        {
            // 插件名称/控制器/方法
            $this->plugins_module_name = '';
            $this->plugins_controller_name = '';
            $this->plugins_action_name = '';

            // 页面唯一标记
            $this->page_unique_mark = $this->module_name.'-'.$this->controller_name.'-'.$this->action_name;
        } else {
            // 插件名称/控制器/方法
            $this->plugins_module_name = $this->data_request['pluginsname'];
            $this->plugins_controller_name = empty($this->data_request['pluginscontrol']) ? 'index' : $this->data_request['pluginscontrol'];
            $this->plugins_action_name = empty($this->data_request['pluginsaction']) ? 'index' : $this->data_request['pluginsaction'];

            // 页面唯一标记
            $this->page_unique_mark = $this->module_name.'-'.$this->controller_name.'-'.$this->plugins_module_name.'-'.$this->plugins_controller_name.'-'.$this->plugins_action_name;
        }
        $this->plugins_mca = $this->plugins_module_name.$this->plugins_controller_name.$this->plugins_action_name;

        // 页面唯一标记
        $assign['page_unique_mark'] = $this->page_unique_mark;

        // 当前插件操作名称
        $assign['plugins_module_name'] = $this->plugins_module_name;
        $assign['plugins_controller_name'] = $this->plugins_controller_name;
        $assign['plugins_action_name'] = $this->plugins_action_name;
        $assign['plugins_mca'] = $this->plugins_mca;

        // 基础表单数据、去除数组和对象列
        $form_back_params = $this->data_request;
        if(!empty($form_back_params) && is_array($form_back_params))
        {
            foreach($form_back_params as $k=>$v)
            {
                if(is_array($v) || is_object($v))
                {
                    unset($form_back_params[$k]);
                }
            }
            unset($form_back_params['s'], $form_back_params['pluginsname'], $form_back_params['pluginscontrol'], $form_back_params['pluginsaction'], $form_back_params['id'], $form_back_params['form_back_control'], $form_back_params['form_back_action']);
        }
        $this->form_back_params = $form_back_params;
        $assign['form_back_params'] = $this->form_back_params;
        // 页面表单操作指定返回、方法默认index
        if(empty($this->plugins_module_name))
        {
            $this->form_back_control = empty($this->data_request['form_back_control']) ? $this->controller_name : $this->data_request['form_back_control'];
            $this->form_back_action = empty($this->data_request['form_back_action']) ? 'index' : $this->data_request['form_back_action'];
            $this->form_back_url = MyUrl($this->module_name.'/'.$this->form_back_control.'/'.$this->form_back_action, $this->form_back_params);
        } else {
            $this->form_back_control = empty($this->data_request['form_back_control']) ? $this->plugins_controller_name : $this->data_request['form_back_control'];
            $this->form_back_action = empty($this->data_request['form_back_action']) ? 'index' : $this->data_request['form_back_action'];
            $this->form_back_url = PluginsHomeUrl($this->plugins_module_name, $this->form_back_control, $this->form_back_action, $this->form_back_params);
        }
        // 基础表单返回url
        $assign['form_back_url'] = $this->form_back_url;

        // 分页信息
        $this->page = max(1, isset($this->data_request['page']) ? intval($this->data_request['page']) : 1);
        $this->page_size = min(empty($this->data_request['page_size']) ? MyC('common_page_size', 10, true) : intval($this->data_request['page_size']), 1000);
        $this->page_start = intval(($this->page-1)*$this->page_size);
        $assign['page'] = $this->page;
        $assign['page_size'] = $this->page_size;
        $assign['page_start'] = $this->page_start;

        // 管理员
        $assign['admin'] = $this->admin;

        // 权限菜单
        $assign['left_menu'] = $this->left_menu;
        // 当前管理员可使用的插件
        $assign['admin_plugins'] = $this->admin_plugins;

        // 货币符号
        $assign['currency_symbol'] = ResourcesService::CurrencyDataSymbol();

        // 静态文件状态css,js
        $assign['static_path_data'] = ResourcesService::StaticCssOrJsPathData($this->default_theme, $this->module_name, $this->controller_name, $this->action_name);

        // 后台logo
        $assign['admin_logo'] = MyC('admin_logo');

        // 价格正则
        $assign['default_price_regex'] = MyConst('common_regex_price');

        // 附件host地址
        $assign['attachment_host'] = SystemBaseService::AttachmentHost();

        // css/js引入host地址
        $assign['public_host'] = MyConfig('shopxo.public_host');

        // 当前host地址
        $assign['my_host'] = __MY_HOST__;

        // 当前url地址
        $assign['my_domain'] = __MY_DOMAIN__;

        // 当前站点url地址
        $assign['my_url'] = __MY_URL__;

        // 当前完整url地址
        $assign['my_view_url'] = __MY_VIEW_URL__;

        // 项目public目录URL地址
        $assign['my_public_url'] = __MY_PUBLIC_URL__;

        // 当前http类型
        $assign['my_http'] = __MY_HTTP__;

        // 首页地址
        $assign['home_url'] = SystemService::DomainUrl();

        // 开发模式
        $assign['shopxo_is_develop'] = MyConfig('shopxo.is_develop');

        // 加载页面加载层、是否加载图片动画
        $assign['is_page_loading'] = ($this->module_name.$this->controller_name.$this->action_name == 'adminindexindex') ? 0 : 1;
        $assign['is_page_loading_images'] = 0;
        $assign['page_loading_logo'] = $assign['home_site_logo_square'];
        $assign['page_loading_images_url'] = StaticAttachmentUrl('loading.gif');
        $assign['page_loading_logo_border'] = StaticAttachmentUrl('loading-border.svg', 'svg');

        // 是否加载附件组件
        $assign['is_load_upload_editor'] = !empty($this->admin) ? 1 : 0;

        // 布局样式+管理
        $assign['is_load_layout'] = 0;
        $assign['is_load_layout_admin'] = 0;

        // 是否加载视频播放器组件
        $assign['is_load_ckplayer'] = 0;

        // 是否加载条形码组件
        $assign['is_load_barcode'] = 0;

        // 默认不加载地图api、类型默认百度地图
        $assign['is_load_map_api'] = 0;
        $assign['load_map_type'] = MyC('common_map_type', 'baidu', true);
        $assign['map_tencent_libraries'] = 'service';

        // 默认不加载打印组件
        $assign['is_load_hiprint'] = 0;

        // 默认不加载视频扫码组件
        $assign['is_load_video_scan'] = 0;

        // 默认不加载echarts图表组件
        $assign['is_load_echarts'] = 0;

        // 默认不加载动画数数
        $assign['is_load_animation_count'] = 0;

        // 默认不加载代码编辑器
        $assign['is_load_ace_builds'] = 0;

        // 是否加载webuploader
        $assign['is_load_webuploader'] = 0;

        // 站点名称
        $assign['admin_theme_site_name'] = MyC('admin_theme_site_name', 'ShopXO', true);

        // 站点商店信息
        $site_store_error = '';
        $site_store_info = StoreService::SiteStoreInfo();
        if(empty($site_store_info) && !empty($this->admin))
        {
            $ret = StoreService::SiteStoreAccountsBindHandle('', '', 'auto');
            if($ret['code'] == 0)
            {
                $site_store_info = StoreService::SiteStoreInfo();
            } else {
                $site_store_error = $ret['msg'];
            }
        }
        $assign['site_store_error'] = $site_store_error;
        $assign['site_store_info'] = $site_store_info;

        // 更多链接地址
        $site_store_links = empty($site_store_info['links']) ? [] : $site_store_info['links'];
        $assign['site_store_links'] = $site_store_links;

        // 系统基础信息
        $is_system_show_base = (empty($site_store_info) || empty($site_store_info['vip']) || !isset($site_store_info['vip']['status']) || $site_store_info['vip']['status'] == 0 || ($site_store_info['vip']['status'] == 1 && (AdminIsPower('index', 'storeaccountsbind') || AdminIsPower('index', 'inspectupgrade')))) ? 1 : 0;
        $assign['is_system_show_base'] = $is_system_show_base;
        // 是否已绑定商店账户
        $is_not_bind_accounts = AdminIsPower('index', 'storeaccountsbind') && (empty($site_store_info) || empty($site_store_info['user'])) ? 1 : 0;
        $assign['is_not_bind_accounts'] = $is_not_bind_accounts;

        // 后台公告
        $admin_notice = MyC('admin_notice');
        $assign['admin_notice'] = empty($admin_notice) ? '' : str_replace("\n", '<br />', $admin_notice);

        // 系统环境参数最大数
        $assign['env_max_input_vars_count'] = SystemService::EnvMaxInputVarsCount();

        // 主题配色选择数据
        $assign['theme_color_choice_data'] = SystemService::GetAdminThemeColor($this->data_request);

        // 主题配色页面数据
        $assign['theme_color_view_data'] = SystemService::AdminThemeStyleDefaultData($this->data_request);

        // 页面语言
        $assign['lang_data'] = SystemService::PageViewLangData();

        // 省市联动是否必选选择
        $assign['is_force_region_choice'] = 1;

        // 多语言
        $assign['multilingual_default_code'] = MultilingualService::GetUserMultilingualValue();
        $assign['multilingual_data'] = (MyC('admin_use_multilingual_status') == 1) ? MultilingualService::MultilingualData() : [];

        // 是否主题数据管理
        $assign['is_theme_data_admin'] = (isset($this->data_request['is_theme_data_admin']) && $this->data_request['is_theme_data_admin'] == 1) ? 1 : 0;

        // 模板赋值
        MyViewAssign($assign);
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
            $assign = [];
            $params = $this->data_request;
            $params['system_admin'] = $this->admin;
            $ret = (new FormTableHandleModule())->Run($module['module'], $module['action'], $params);
            if($ret['code'] == 0)
            {
                // 表格数据
                $this->form_table = $ret['data']['table'];
                $this->form_where = $ret['data']['where'];
                $this->form_params = $ret['data']['params'];
                $this->form_md5_key = $ret['data']['md5_key'];
                $this->form_user_fields = $ret['data']['user_fields'];
                $this->form_order_by = $ret['data']['order_by'];
                $assign['form_table'] = $this->form_table;
                $assign['form_params'] = $this->form_params;
                $assign['form_md5_key'] = $this->form_md5_key;
                $assign['form_user_fields'] = $this->form_user_fields;
                $assign['form_order_by'] = $this->form_order_by;

                // 列表数据
                $this->data_total = $ret['data']['data_total'];
                $this->data_list = $ret['data']['data_list'];
                $this->data_detail = $ret['data']['data_detail'];
                // 建议使用新的变量、避免冲突
                $assign['form_table_data_total'] = $this->data_total;
                $assign['form_table_data_list'] = $this->data_list;
                $assign['form_table_data_detail'] = $this->data_detail;
                // 兼容老版本的数据读取变量（永久保留）
                $assign['data_list'] = $this->data_list;
                $assign['data'] = $this->data_detail;

                // 分页数据
                $this->page = $ret['data']['page'];
                $this->page_start = $ret['data']['page_start'];
                $this->page_size = $ret['data']['page_size'];
                $this->page_html = $ret['data']['page_html'];
                $this->page_url = $ret['data']['page_url'];
                $assign['page'] = $this->page;
                $assign['page_start'] = $this->page_start;
                $assign['page_size'] = $this->page_size;
                $assign['page_html'] = $this->page_html;
                $assign['page_url'] = $this->page_url;

                // 是否开启打印和pdf导出、则引入组件
                if((isset($this->form_table['base']['is_data_print']) && $this->form_table['base']['is_data_print'] == 1) || (isset($this->form_table['base']['is_data_export_pdf']) && $this->form_table['base']['is_data_export_pdf'] == 1))
                {
                    $assign['is_load_hiprint'] = 1;
                }
            } else {
                $this->form_error = $ret['msg'];
                $assign['form_error'] = $this->form_error;
            }
            // 模板赋值
            MyViewAssign($assign);
        }
    }

	/**
	 * 是否有权限
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-20T19:18:29+0800
	 */
	protected function IsPower()
	{
		// 不需要校验权限的方法
		$unwanted_power = ['getnodeson', 'node'];
        if(!AdminIsPower(null, null, $unwanted_power))
        {
            $msg = MyLang('no_power_tips');
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn($msg, -1000)));
            } else {
                MyRedirect(MyUrl('admin/error/tips', ['msg'=>urlencode(base64_encode($msg))]), true);
            }
        }
	}

    /**
     * 商店账号未绑定提示
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-15
     * @desc    description
     * @param   [string]      $msg [提示信息]
     */
    public function NotBindStoreAccountTips($msg)
    {
        MyViewAssign('ext_html', '<p class="am-margin-top-sm"><button type="button" class="am-btn am-btn-primary am-radius am-btn-xs am-margin-left-xs am-icon-gg store-accounts-event"> '.MyLang('store_bind_accounts_name').'</button></p><p class="am-text-warning am-margin-top-xl">'.MyLang('store_already_bind_accounts_tips').'</p>');
        MyViewAssign('msg', $msg);
        return MyView('public/tips_error');
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
        $msg = MyLang('illegal_access_tips').'('.$method.')';
        if(IS_AJAX)
        {
            return DataReturn($msg, -1000);
        } else {
            MyViewAssign('msg', $msg);
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
        // 模板数据
        $assign = [];

        // 钩子列表
        $hook_arr = [
            // css钩子
            'plugins_admin_css',
            // js钩子
            'plugins_admin_js',
            // 公共header内钩子
            'plugins_admin_common_header',
            // 公共header内css钩子
            'plugins_admin_common_header_css',
            // 公共header内js钩子
            'plugins_admin_common_header_javascript',
            // 公共页面底部钩子
            'plugins_admin_common_page_bottom',
            // 公共顶部钩子
            'plugins_admin_view_common_top',
            // 公共底部钩子
            'plugins_admin_view_common_bottom',
        ];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
                ['hook_name'    => $hook_name,
                'is_backend'    => false,
                'admin'         => $this->admin,
            ]);
        }

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
        $assign['hook_name_form_grid'] = $current.'_grid';
        // 内容外部顶部
        $assign['hook_name_content_top'] = $current.'_content_top';
        // 内容外部底部
        $assign['hook_name_content_bottom'] = $current.'_content_bottom';
        // 内容内部顶部
        $assign['hook_name_content_inside_top'] = $current.'_content_inside_top';
        // 内容内部底部
        $assign['hook_name_content_inside_bottom'] = $current.'_content_inside_bottom';
        // 表格列表顶部操作
        $assign['hook_name_form_top_operate'] = $current.'_top_operate';
        // 表格列表底部操作
        $assign['hook_name_form_bottom_operate'] = $current.'_bottom_operate';
        // 表格列表后面操作栏
        $assign['hook_name_form_list_operate'] = $current.'_list_operate';

        // 公共详情页面钩子名称动态处理
        // 内容外部顶部
        $assign['hook_name_detail_top'] = $current.'_detail_top';
        // 内容外部底部
        $assign['hook_name_detail_bottom'] = $current.'_detail_bottom';
        // 内容内部顶部
        $assign['hook_name_detail_inside_top'] = $current.'_detail_inside_top';
        // 内容内部底部
        $assign['hook_name_detail_inside_bottom'] = $current.'_detail_inside_bottom';

        // 模板赋值
        MyViewAssign($assign);
    }
}
?>