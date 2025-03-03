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
use app\module\FormTableHandleModule;
use app\service\SystemService;
use app\service\SystemBaseService;
use app\service\StoreService;
use app\service\ResourcesService;
use app\service\GoodsCategoryService;
use app\service\NavigationService;
use app\service\BuyService;
use app\service\MessageService;
use app\service\SearchService;
use app\service\UserService;
use app\service\AdminService;
use app\service\MultilingualService;
use app\service\BreadcrumbService;
use app\service\GoodsCartService;
use app\service\ThemeDataService;
use app\service\ConfigService;

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
    // 顶部导航、底部导航
    protected $nav_header;
    protected $nav_footer;

    // 用户信息
    protected $user;

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

    // 面包屑导航
    protected $breadcrumb_data;

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

        // 站点状态校验
        $this->SiteStstusCheck('_web');

        // web端pc访问状态
        if(!IsMobile())
        {
            $this->SiteStstusCheck('_web_pc');
        }

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

        // 面包屑导航
        MyViewAssign('breadcrumb_data', $this->breadcrumb_data);
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
     * 公共数据初始化
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

        // 货币符号
        $assign['currency_symbol'] = ResourcesService::CurrencyDataSymbol();

        // 站点类型
        $assign['common_site_type'] = SystemBaseService::SiteTypeValue();

        // 预约模式
        $assign['common_order_is_booking'] = MyC('common_order_is_booking', 0, true);

        // 商店信息
        $assign['common_customer_store_tel'] = MyC('common_customer_store_tel');
        $assign['common_customer_store_email'] = MyC('common_customer_store_email');
        $assign['common_customer_store_address'] = MyC('common_customer_store_address');
        $assign['common_customer_store_describe'] = MyC('common_customer_store_describe');
        $assign['common_customer_store_qrcode'] = AttachmentPathViewHandle(MyC('common_customer_store_qrcode'));

        // 主题
        $this->default_theme = DefaultTheme();
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

        // 静态文件状态css,js
        $assign['static_path_data'] = ResourcesService::StaticCssOrJsPathData($this->default_theme, $this->module_name, $this->controller_name, $this->action_name);

        // 导航
        $assign['nav_header'] = $this->nav_header;
        $assign['nav_footer'] = $this->nav_footer;

        // 导航/底部默认显示
        $assign['is_header'] = 1;
        $assign['is_footer'] = 1;

        // 是否已关闭顶部小导航、主导航、搜索栏则不展示头部数据
        if(MyC('home_main_top_header_status', 1) == 0 && MyC('home_main_header_status', 1) == 0 && MyC('home_main_logo_search_status', 1) == 0)
        {
            $assign['is_header'] = 0;
        }

        // 左侧大分类是否隐藏展开
        $common_goods_category_hidden = ($this->controller_name != 'index' || MyC('home_index_banner_left_status', 1) != 1) ? 1 : 0;
        $assign['common_goods_category_hidden'] = $common_goods_category_hidden;

        // 价格正则
        $assign['default_price_regex'] = MyConst('common_regex_price');

        // 附件host地址
        $assign['attachment_host'] = SystemBaseService::AttachmentHost();

        // css/js引入host地址
        $assign['public_host'] = MyConfig('shopxo.public_host');

        // 当前url地址
        $assign['my_domain'] = __MY_DOMAIN__;

        // 当前host地址
        $assign['my_host'] = __MY_HOST__;

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

        // url模式
        $assign['url_model'] = MyC('home_seo_url_model', 0);

        // seo
        $assign['home_seo_site_title'] = MyC('home_seo_site_title');
        $assign['home_seo_site_keywords'] = MyC('home_seo_site_keywords');
        $assign['home_seo_site_description'] = MyC('home_seo_site_description');

        // 用户数据
        $assign['user'] = $this->user;

        // 用户中心菜单
        $assign['user_left_menu'] = NavigationService::UserCenterLeftList();

        // 商品大分类
        $assign['goods_category_list'] = GoodsCategoryService::GoodsCategoryAll();

        // 搜索框下热门关键字
        $assign['home_search_keywords'] = SearchService::SearchKeywordsList();

        // 开发模式
        $assign['shopxo_is_develop'] = MyConfig('shopxo.is_develop');

        // 默认不加载页面加载层、是否加载图片动画
        $assign['is_page_loading'] = 0;
        $assign['is_page_loading_images'] = 0;
        $assign['page_loading_logo'] = $assign['home_site_logo_square'];
        $assign['page_loading_images_url'] = StaticAttachmentUrl('loading.gif');
        $assign['page_loading_logo_border'] = StaticAttachmentUrl('loading-border.svg', 'svg');

        // 顶部右侧导航
        $assign['common_nav_top_right_list'] = NavigationService::HomeHavTopRight(['user'=>$this->user]);

        // 底部导航
        $assign['common_bottom_nav_list'] = NavigationService::BottomNavigationData(['user'=>$this->user]);

        // 编辑器文件存放地址
        $assign['editor_path_type'] = ResourcesService::EditorPathTypeValue(empty($this->user['id']) ? 'public' : 'user-'.$this->user['id']);

        // 分类展示层级模式
        $assign['category_show_level'] = MyC('common_show_goods_category_level', 3, true);

        // 备案信息
        $assign['home_site_icp'] = MyC('home_site_icp');
        $assign['home_site_security_record_name'] = MyC('home_site_security_record_name');
        $assign['home_site_security_record_url'] = MyC('home_site_security_record_url');
        $assign['home_site_telecom_license'] = MyC('home_site_telecom_license');
        $assign['home_site_company_license'] = MyC('home_site_company_license');

        // 是否加载附件组件
        $admin = AdminService::LoginInfo();
        $assign['is_load_upload_editor'] = (!empty($this->user) || !empty($admin)) ? 1 : 0;

        // 布局样式+管理
        $assign['is_load_layout'] = 0;
        $assign['is_load_layout_admin'] = 0;

        // 默认不加载放大镜
        $assign['is_load_imagezoom'] = 0;

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

        // 是否加载uniapp webview js
        $assign['is_load_uniapp_webview'] = 0;

        // 登录/注册方式
        $assign['home_user_login_type'] = MyC('home_user_login_type', [], true);
        $assign['home_user_reg_type'] = MyC('home_user_reg_type', [], true);

        // 底部信息
        $assign['home_theme_footer_bottom_powered'] = htmlspecialchars_decode(MyC('home_theme_footer_bottom_powered'));

        // 纯净模式
        $assign['page_pure'] = in_array($this->controller_name.$this->action_name, ['usermodallogininfo']) ? 1 : 0;

        // 系统环境参数最大数
        $assign['env_max_input_vars_count'] = SystemService::EnvMaxInputVarsCount();

        // 站点商店信息
        $site_store_info = StoreService::SiteStoreInfo();
        $assign['site_store_info'] = $site_store_info;
        // 更多链接地址
        $site_store_links = empty($site_store_info['links']) ? [] : $site_store_info['links'];
        $assign['site_store_links'] = $site_store_links;

        // 页面语言
        $assign['lang_data'] = SystemService::PageViewLangData();

        // 省市联动是否必选选择
        $assign['is_force_region_choice'] = 1;

        // 多语言
        $assign['multilingual_default_code'] = MultilingualService::GetUserMultilingualValue();

        // 主题样式
        $assign['theme_style_data'] = SystemService::ThemeStyleData(['default_theme'=>$this->default_theme]);

        // 面包屑导航
        $assign['breadcrumb_data'] = BreadcrumbService::Data();

        // 用户购物车数量
        $assign['user_cart_summary'] = GoodsCartService::UserGoodsCartTotal(['user'=>$this->user]);

        // 主题数据
        $assign['theme_data'] = ThemeDataService::ThemeData(array_merge($this->data_request, [
                'module_name'      => $this->module_name,
                'controller_name'  => $this->controller_name,
                'action_name'      => $this->action_name,
                'mca'              => $this->mca,
                'default_theme'    => $this->default_theme,
            ]));

        // 主题数据管理
        $assign['theme_data_admin_data'] = ThemeDataService::ThemeDataAdminData(array_merge($this->data_request, [
                'default_theme'    => $this->default_theme,
            ]));

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
            $params['system_user'] = $this->user;
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
     * 导航初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-12-08
     * @desc    description
     */
    private function NavInit()
    {
        // 主导航和底部导航
        $nav = NavigationService::Nav();
        $this->nav_header = $nav['header'];
        $this->nav_footer = $nav['footer'];
    }

    /**
     * 站点状态校验
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-01-26
     * @desc    description
     * @param   [string]          $type [web端首页 _web_home , web端PC访问 _web_pc]
     */
    protected function SiteStstusCheck($type = '')
    {
        if(MyC('home_site'.$type.'_state') != 1)
        {
            // 提示信息
            $reason = MyC('home_site_close_reason', MyLang('upgrading_tips'), true);

            // 是否ajax请求
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn($reason, -10000)));
            } else {
                // 默认提示信息增加样式，则使用用户自定义信息展示
                if(stripos($reason, '<html>') === false)
                {
                    exit('<!DOCTYPE html><html><head><meta charset="utf-8" /><title>'.MyC('home_site_name').'</title><body><div style="text-align: center;margin-top: 15%;font-size: 18px;color: #f00;">'.$reason.'</div></body></html>');
                } else {
                    exit($reason);
                }
            }
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
        // 模板数据
        $assign = [];

        // 钩子列表
        $hook_arr = [
            // css钩子
            'plugins_css',
            // js钩子
            'plugins_js',
            // 公共header内钩子
            'plugins_common_header',
            // 公共header内css钩子
            'plugins_common_header_css',
            // 公共header内js钩子
            'plugins_common_header_javascript',
            // 公共页面底部钩子
            'plugins_common_page_bottom',
            // 公共顶部钩子
            'plugins_view_common_top',
            // 公共底部钩子
            'plugins_view_common_bottom',
            // header公共顶部钩子
            'plugins_view_common_top_header',
            // footer公共底部钩子
            'plugins_view_common_bottom_footer',
            // 公共顶部小导航钩子-左侧前面
            'plugins_view_header_navigation_top_left_begin',
            // 公共顶部小导航钩子-左侧后面
            'plugins_view_header_navigation_top_left_end',
            // 公共顶部小导航钩子-右侧前面
            'plugins_view_header_navigation_top_right_begin',
            // 公共顶部小导航钩子-右侧后面
            'plugins_view_header_navigation_top_right_end',
            // 用户登录容器内顶部钩子
            'plugins_view_user_login_content_inside_top',
            // 用户登录容器内底部钩子
            'plugins_view_user_login_content_inside_bottom',
            // 用户登录页面顶部钩子
            'plugins_view_user_login_info_top',
            // 用户登录内底部钩子
            'plugins_view_user_login_inside_bottom',
            // 用户登录内注册底部钩子
            'plugins_view_user_login_inside_reg_bottom',
            // 用户登录内容页面底部钩子
            'plugins_view_user_login_content_bottom',
            // 用户注册页面钩子
            'plugins_view_user_reg_info',
            // 用户注册页面顶部钩子
            'plugins_view_user_reg_info_top',
            // 用户注册页面内底部钩子
            'plugins_view_user_reg_info_inside_bottom',
            // 用户注册页面内登录底部钩子
            'plugins_view_user_reg_info_inside_login_bottom',
            // 用户注册页面底部钩子
            'plugins_view_user_reg_info_bottom',
            // 底部导航上面钩子
            'plugins_view_common_footer_top',
            // logo右侧
            'plugins_view_common_logo_right',
            // 公共搜索框右侧
            'plugins_view_common_search_right',
            // 公共搜索框内左侧
            'plugins_view_common_search_inside_left',
            // 公共搜索框内右侧
            'plugins_view_common_search_inside_right',
            // 中间导航左侧
            'plugins_view_common_header_nav_left',
            // 中间导航搜索内部
            'plugins_view_common_header_nav_search_inside',
            // 中间导航内容内部顶部
            'plugins_view_common_header_nav_content_inside_top',
            // 中间导航内容内部底部
            'plugins_view_common_header_nav_content_inside_bottom',
            // 中间导航右侧
            'plugins_view_common_header_nav_right',
        ];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
                ['hook_name'    => $hook_name,
                'is_backend'    => false,
                'user'          => $this->user,
            ]);
        }

        // 公共表格钩子名称动态处理
        $current = 'plugins_view_index_'.$this->controller_name;

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