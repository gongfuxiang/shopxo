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
namespace app\index\controller;

use app\BaseController;
use app\module\FormHandleModule;
use app\service\SystemService;
use app\service\SystemBaseService;
use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\NavigationService;
use app\service\BuyService;
use app\service\MessageService;
use app\service\SearchService;
use app\service\ConfigService;
use app\service\UserService;
use app\service\AdminService;
use app\service\QuickNavService;

/**
 * 前端公共控制器
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-11-30
 * @desc    description
 */
class Common extends BaseController
{
    // 顶部导航、底部导航、快捷导航
    protected $nav_header;
    protected $nav_footer;
    protected $nav_quick;

    // 用户信息
    protected $user;

    // 请求参数
    protected $params;

    // 当前操作名称
    protected $module_name;
    protected $controller_name;
    protected $action_name;

    // 输入参数 post|get|request
    protected $data_post;
    protected $data_get;
    protected $data_request;

    // 分页信息
    protected $page;
    protected $page_size;

    // 动态表格
    protected $form_table;
    protected $form_where;
    protected $form_params;
    protected $form_md5_key;
    protected $form_user_fields;
    protected $form_order_by;
    protected $form_error;

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

        // 系统运行开始
        SystemService::SystemBegin($this->data_request);

        // 站点状态校验
        $this->SiteStstusCheck();

        // 公共数据初始化
        $this->CommonInit();

        // 菜单
        $this->NavInit();

        // 视图初始化
        $this->ViewInit();

        // 动态表格初始化
        $this->FormTableInit();

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
        // 配置信息初始化
        ConfigService::ConfigInit();

        // 推荐人
        if(!empty($this->data_request['referrer']))
        {
            MySession('share_referrer_id', $this->data_request['referrer']);
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
        $this->user = UserService::LoginUserInfo();
    }

    /**
     * [IsLogin 登录校验]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T11:43:48+0800
     */
    protected function IsLogin()
    {
        if(empty($this->user))
        {
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
            } else {
                MyRedirect('index/user/logininfo', true);
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
        // 公共参数
        MyViewAssign('params', $this->data_request);

        // 货币符号
        MyViewAssign('currency_symbol', ResourcesService::CurrencyDataSymbol());

        // 站点类型
        MyViewAssign('common_site_type', SystemBaseService::SiteTypeValue());

        // 预约模式
        MyViewAssign('common_order_is_booking', MyC('common_order_is_booking', 0, true));

        // 商店信息
        MyViewAssign('common_customer_store_tel', MyC('common_customer_store_tel'));
        MyViewAssign('common_customer_store_email', MyC('common_customer_store_email'));
        MyViewAssign('common_customer_store_address', MyC('common_customer_store_address'));
        MyViewAssign('common_customer_store_qrcode', AttachmentPathViewHandle(MyC('common_customer_store_qrcode')));
        
        // 主题
        $default_theme = strtolower(MyC('common_default_theme', 'default', true));
        MyViewAssign('default_theme', $default_theme);

        // 当前操作名称, 兼容插件模块名称
        $this->module_name = RequestModule();
        $this->controller_name = RequestController();
        $this->action_name = RequestAction();

        // 当前操作名称
        MyViewAssign('module_name', $this->module_name);
        MyViewAssign('controller_name', $this->controller_name);
        MyViewAssign('action_name', $this->action_name);

        // 分页信息
        $this->page = max(1, isset($this->data_request['page']) ? intval($this->data_request['page']) : 1);
        $this->page_size = MyC('common_page_size', 10, true);
        MyViewAssign('page', $this->page);
        MyViewAssign('page_size', $this->page_size);

        // 控制器静态文件状态css,js
        $module_css = $this->module_name.DS.$default_theme.DS.'css'.DS.$this->controller_name;
        $module_css .= file_exists(ROOT_PATH.'static'.DS.$module_css.'.'.$this->action_name.'.css') ? '.'.$this->action_name.'.css' : '.css';
        MyViewAssign('module_css', file_exists(ROOT_PATH.'static'.DS.$module_css) ? $module_css : '');

        $module_js = $this->module_name.DS.$default_theme.DS.'js'.DS.$this->controller_name;
        $module_js .= file_exists(ROOT_PATH.'static'.DS.$module_js.'.'.$this->action_name.'.js') ? '.'.$this->action_name.'.js' : '.js';
        MyViewAssign('module_js', file_exists(ROOT_PATH.'static'.DS.$module_js) ? $module_js : '');

        // 导航
        MyViewAssign('nav_header', $this->nav_header);
        MyViewAssign('nav_footer', $this->nav_footer);
        MyViewAssign('nav_quick', $this->nav_quick);

        // 导航/底部默认显示
        MyViewAssign('is_header', 1);
        MyViewAssign('is_footer', 1);

        // 左侧大分类是否隐藏展开
        $common_goods_category_hidden = ($this->controller_name != 'index' || MyC('home_index_banner_left_status', 1) != 1) ? 1 : 0;
        MyViewAssign('common_goods_category_hidden', $common_goods_category_hidden);

        // 价格正则
        MyViewAssign('default_price_regex', lang('common_regex_price'));

        // 附件host地址
        MyViewAssign('attachment_host', SystemBaseService::AttachmentHost());

        // css/js引入host地址
        MyViewAssign('public_host', MyConfig('shopxo.public_host'));

        // 当前url地址
        MyViewAssign('my_url', __MY_URL__);

        // 项目public目录URL地址
        MyViewAssign('my_public_url', __MY_PUBLIC_URL__);

        // 当前http类型
        MyViewAssign('my_http', __MY_HTTP__);

        // url模式
        MyViewAssign('url_model', MyC('home_seo_url_model', 0));

        // seo
        MyViewAssign('home_seo_site_title', MyC('home_seo_site_title'));
        MyViewAssign('home_seo_site_keywords', MyC('home_seo_site_keywords'));
        MyViewAssign('home_seo_site_description', MyC('home_seo_site_description'));

        // 用户数据
        MyViewAssign('user', $this->user);

        // 用户中心菜单
        MyViewAssign('user_left_menu', NavigationService::UsersCenterLeftList());

        // 商品大分类
        MyViewAssign('goods_category_list', GoodsService::GoodsCategoryAll());

        // 搜索框下热门关键字
        MyViewAssign('home_search_keywords', SearchService::SearchKeywordsList());

        // 开发模式
        MyViewAssign('shopxo_is_develop', MyConfig('shopxo.is_develop'));

        // 顶部右侧导航
        MyViewAssign('common_nav_top_right_list', NavigationService::HomeHavTopRight(['user'=>$this->user]));

        // 底部导航
        MyViewAssign('common_bottom_nav_list', NavigationService::BottomNavigation(['user'=>$this->user]));

        // 编辑器文件存放地址
        MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue(empty($this->user['id']) ? 'public' : 'user-'.$this->user['id']));

        // 分类展示层级模式
        MyViewAssign('category_show_level', MyC('common_show_goods_category_level', 3, true));

        // 备案信息
        MyViewAssign('home_site_icp', MyC('home_site_icp'));
        MyViewAssign('home_site_security_record_name', MyC('home_site_security_record_name'));
        MyViewAssign('home_site_security_record_url', MyC('home_site_security_record_url'));

        // 布局样式+管理
        MyViewAssign('is_load_layout', 0);
        MyViewAssign('is_load_layout_admin', 0);

        // 默认不加载放大镜
        MyViewAssign('is_load_imagezoom', 0);

        // 默认不加载百度地图api
        MyViewAssign('is_load_baidu_map_api', 0);

        // 是否加载附件组件
        MyViewAssign('is_load_upload_editor', (!empty($this->user) || AdminService::LoginInfo()) ? 1 : 0);

        // 存在地图事件则载入
        if(in_array(3, array_column($this->nav_quick, 'event_type')))
        {
            MyViewAssign('is_load_baidu_map_api', 1);
        }

        // 登录/注册方式
        MyViewAssign('home_user_login_type', MyC('home_user_login_type', [], true));
        MyViewAssign('home_user_reg_type', MyC('home_user_reg_type', [], true));

        // 底部信息
        MyViewAssign('home_theme_footer_bottom_powered', htmlspecialchars_decode(MyC('home_theme_footer_bottom_powered')));
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
            $params['system_user'] = $this->user;
            $ret = (new FormHandleModule())->Run($module['module'], $module['action'], $params);
            if($ret['code'] == 0)
            {
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
            } else {
                $this->form_error = $ret['msg'];
                MyViewAssign('form_error', $this->form_error);
            }
        }
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
        // 主导航和底部导航
        $nav = NavigationService::Nav();
        $this->nav_header = $nav['header'];
        $this->nav_footer = $nav['footer'];

        // 快捷导航
        $this->nav_quick = QuickNavService::QuickNav();
    }

    /**
     * [SiteStstusCheck 站点状态校验]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-25T21:43:07+0800
     */
    private function SiteStstusCheck()
    {
        if(MyC('home_site_state') != 1)
        {
            // 是否ajax请求
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn(MyC('home_site_close_reason', '网站维护中...'), -10000)));
            } else {
                exit('<div style="text-align: center;margin-top: 15%;font-size: 18px;color: #f00;">'.MyC('home_site_close_reason', '网站维护中...', true).'</div>');
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
        MyViewAssign('plugins_css_data', MyEventTrigger('plugins_css', ['hook_name'=>'plugins_css', 'is_backend'=>false]));

        // js钩子
        MyViewAssign('plugins_js_data', MyEventTrigger('plugins_js', ['hook_name'=>'plugins_js', 'is_backend'=>false]));
        
        // 公共header内钩子
        MyViewAssign('plugins_common_header_data', MyEventTrigger('plugins_common_header', ['hook_name'=>'plugins_common_header', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共页面底部钩子
        MyViewAssign('plugins_common_page_bottom_data', MyEventTrigger('plugins_common_page_bottom', ['hook_name'=>'plugins_common_page_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部钩子
        MyViewAssign('plugins_view_common_top_data', MyEventTrigger('plugins_view_common_top', ['hook_name'=>'plugins_view_common_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共底部钩子
        MyViewAssign('plugins_view_common_bottom_data', MyEventTrigger('plugins_view_common_bottom', ['hook_name'=>'plugins_view_common_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-左侧前面
        MyViewAssign('plugins_view_header_navigation_top_left_begin_data', MyEventTrigger('plugins_view_header_navigation_top_left_begin', ['hook_name'=>'plugins_view_header_navigation_top_left_begin', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-左侧后面
        MyViewAssign('plugins_view_header_navigation_top_left_end_data', MyEventTrigger('plugins_view_header_navigation_top_left_end', ['hook_name'=>'plugins_view_header_navigation_top_left_end', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-右侧前面
        MyViewAssign('plugins_view_header_navigation_top_right_begin_data', MyEventTrigger('plugins_view_header_navigation_top_right_begin', ['hook_name'=>'plugins_view_header_navigation_top_right_begin', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-右侧后面
        MyViewAssign('plugins_view_header_navigation_top_right_end_data', MyEventTrigger('plugins_view_header_navigation_top_right_end', ['hook_name'=>'plugins_view_header_navigation_top_right_end', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户登录页面顶部钩子
        MyViewAssign('plugins_view_user_login_info_top_data', MyEventTrigger('plugins_view_user_login_info_top', ['hook_name'=>'plugins_view_user_login_info_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户登录内底部钩子
        MyViewAssign('plugins_view_user_login_inside_bottom_data', MyEventTrigger('plugins_view_user_login_inside_bottom', ['hook_name'=>'plugins_view_user_login_inside_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户登录内容页面底部钩子
        MyViewAssign('plugins_view_user_login_content_bottom_data', MyEventTrigger('plugins_view_user_login_content_bottom', ['hook_name'=>'plugins_view_user_login_content_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户注册页面钩子
        MyViewAssign('plugins_view_user_reg_info_data', MyEventTrigger('plugins_view_user_reg_info', ['hook_name'=>'plugins_view_user_reg_info', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户注册页面顶部钩子
        MyViewAssign('plugins_view_user_reg_info_top_data', MyEventTrigger('plugins_view_user_reg_info_top', ['hook_name'=>'plugins_view_user_reg_info_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户注册页面内底部钩子
        MyViewAssign('plugins_view_user_reg_info_inside_bottom_data', MyEventTrigger('plugins_view_user_reg_info_inside_bottom', ['hook_name'=>'plugins_view_user_reg_info_inside_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户注册页面底部钩子
        MyViewAssign('plugins_view_user_reg_info_bottom_data', MyEventTrigger('plugins_view_user_reg_info_bottom', ['hook_name'=>'plugins_view_user_reg_info_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 底部导航上面钩子
        MyViewAssign('plugins_view_common_footer_top_data', MyEventTrigger('plugins_view_common_footer_top', ['hook_name'=>'plugins_view_common_footer_top', 'is_backend'=>false, 'user'=>$this->user]));

        // logo右侧
        MyViewAssign('plugins_view_common_logo_right_data', MyEventTrigger('plugins_view_common_logo_right', ['hook_name'=>'plugins_view_common_logo_right', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共搜索框右侧
        MyViewAssign('plugins_view_common_search_right_data', MyEventTrigger('plugins_view_common_search_right', ['hook_name'=>'plugins_view_common_search_right', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共表格钩子名称动态处理
        $current = 'plugins_view_index_'.$this->controller_name;

        // 是否插件默认下
        if($this->controller_name == 'plugins')
        {
            if(!empty($this->data_request['pluginsname']))
            {
                $current .= '_'.trim($this->data_request['pluginsname']);
            }
        }

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