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

use think\Controller;
use think\facade\Hook;
use app\module\FormHandleModule;
use app\service\SystemService;
use app\service\BaseService;
use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\NavigationService;
use app\service\BuyService;
use app\service\MessageService;
use app\service\SearchService;
use app\service\ConfigService;
use app\service\LinkService;
use app\service\UserService;
use app\service\QuickNavService;

/**
 * 前端公共控制器
 * @author   Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2018-11-30
 * @desc    description
 */
class Common extends Controller
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
        parent::__construct();

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
        $this->assign('plugins_css_data', Hook::listen('plugins_css', ['hook_name'=>'plugins_css', 'is_backend'=>false]));

        // js钩子
        $this->assign('plugins_js_data', Hook::listen('plugins_js', ['hook_name'=>'plugins_js', 'is_backend'=>false]));
        
        // 公共header内钩子
        $this->assign('plugins_common_header_data', Hook::listen('plugins_common_header', ['hook_name'=>'plugins_common_header', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共页面底部钩子
        $this->assign('plugins_common_page_bottom_data', Hook::listen('plugins_common_page_bottom', ['hook_name'=>'plugins_common_page_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部钩子
        $this->assign('plugins_view_common_top_data', Hook::listen('plugins_view_common_top', ['hook_name'=>'plugins_view_common_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共底部钩子
        $this->assign('plugins_view_common_bottom_data', Hook::listen('plugins_view_common_bottom', ['hook_name'=>'plugins_view_common_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-左侧前面
        $this->assign('plugins_view_header_navigation_top_left_begin_data', Hook::listen('plugins_view_header_navigation_top_left_begin', ['hook_name'=>'plugins_view_header_navigation_top_left_begin', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-左侧后面
        $this->assign('plugins_view_header_navigation_top_left_end_data', Hook::listen('plugins_view_header_navigation_top_left_end', ['hook_name'=>'plugins_view_header_navigation_top_left_end', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-右侧前面
        $this->assign('plugins_view_header_navigation_top_right_begin_data', Hook::listen('plugins_view_header_navigation_top_right_begin', ['hook_name'=>'plugins_view_header_navigation_top_right_begin', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共顶部小导航钩子-右侧后面
        $this->assign('plugins_view_header_navigation_top_right_end_data', Hook::listen('plugins_view_header_navigation_top_right_end', ['hook_name'=>'plugins_view_header_navigation_top_right_end', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户登录页面顶部钩子
        $this->assign('plugins_view_user_login_info_top_data', Hook::listen('plugins_view_user_login_info_top', ['hook_name'=>'plugins_view_user_login_info_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户注册页面钩子
        $this->assign('plugins_view_user_reg_info_data', Hook::listen('plugins_view_user_reg_info', ['hook_name'=>'plugins_view_user_reg_info', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户注册页面顶部钩子
        $this->assign('plugins_view_user_reg_info_top_data', Hook::listen('plugins_view_user_reg_info_top', ['hook_name'=>'plugins_view_user_reg_info_top', 'is_backend'=>false, 'user'=>$this->user]));

        // 用户注册页面底部钩子
        $this->assign('plugins_view_user_reg_info_bottom_data', Hook::listen('plugins_view_user_reg_info_bottom', ['hook_name'=>'plugins_view_user_reg_info_bottom', 'is_backend'=>false, 'user'=>$this->user]));

        // 底部导航上面钩子
        $this->assign('plugins_view_common_footer_top_data', Hook::listen('plugins_view_common_footer_top', ['hook_name'=>'plugins_view_common_footer_top', 'is_backend'=>false, 'user'=>$this->user]));

        // logo右侧
        $this->assign('plugins_view_common_logo_right_data', Hook::listen('plugins_view_common_logo_right', ['hook_name'=>'plugins_view_common_logo_right', 'is_backend'=>false, 'user'=>$this->user]));

        // 公共搜索框右侧
        $this->assign('plugins_view_common_search_right_data', Hook::listen('plugins_view_common_search_right', ['hook_name'=>'plugins_view_common_search_right', 'is_backend'=>false, 'user'=>$this->user]));

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
        $this->assign('hook_name_content_top', $current.'_content_top');
        // 内容外部底部
        $this->assign('hook_name_content_bottom', $current.'_content_bottom');
        // 内容内部顶部
        $this->assign('hook_name_content_inside_top', $current.'_content_inside_top');
        // 内容内部底部
        $this->assign('hook_name_content_inside_bottom', $current.'_content_inside_bottom');
        // 表格列表顶部操作
        $this->assign('hook_name_form_top_operate', $current.'_top_operate');
        // 表格列表底部操作
        $this->assign('hook_name_form_bottom_operate', $current.'_bottom_operate');
        // 表格列表后面操作栏
        $this->assign('hook_name_form_list_operate', $current.'_list_operate');

        // 公共详情页面钩子名称动态处理
        // 内容外部顶部
        $this->assign('hook_name_detail_top', $current.'_detail_top');
        // 内容外部底部
        $this->assign('hook_name_detail_bottom', $current.'_detail_bottom');
        // 内容内部顶部
        $this->assign('hook_name_detail_inside_top', $current.'_detail_inside_top');
        // 内容内部底部
        $this->assign('hook_name_detail_inside_bottom', $current.'_detail_inside_bottom');
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
        
        // url模式
        if(MyC('home_seo_url_model', 0) == 0)
        {
            \think\facade\Url::root(__MY_ROOT_PUBLIC__.'index.php?s=');
        }

        // 推荐人
        if(!empty($this->data_request['referrer']))
        {
            session('share_referrer_id', $this->data_request['referrer']);
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
                return $this->redirect('index/user/logininfo');
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
        $this->assign('params', $this->data_request);

        // 货币符号
        $this->assign('currency_symbol', ResourcesService::CurrencyDataSymbol());

        // 站点类型
        $this->assign('common_site_type', BaseService::SiteTypeValue());

        // 预约模式
        $this->assign('common_order_is_booking', MyC('common_order_is_booking', 0, true));

        // 商店信息
        $this->assign('common_customer_store_tel', MyC('common_customer_store_tel'));
        $this->assign('common_customer_store_email', MyC('common_customer_store_email'));
        $this->assign('common_customer_store_address', MyC('common_customer_store_address'));
        $this->assign('common_customer_store_qrcode', AttachmentPathViewHandle(MyC('common_customer_store_qrcode')));
        
        // 主题
        $default_theme = strtolower(MyC('common_default_theme', 'default', true));
        $this->assign('default_theme', $default_theme);

        // 当前操作名称, 兼容插件模块名称
        $this->module_name = strtolower(request()->module());
        $this->controller_name = strtolower(request()->controller());
        $this->action_name = strtolower(request()->action());

        // 当前操作名称
        $this->assign('module_name', $this->module_name);
        $this->assign('controller_name', $this->controller_name);
        $this->assign('action_name', $this->action_name);

        // 分页信息
        $this->page = max(1, isset($this->data_request['page']) ? intval($this->data_request['page']) : 1);
        $this->page_size = MyC('common_page_size', 10, true);
        $this->assign('page', $this->page);
        $this->assign('page_size', $this->page_size);

        // 控制器静态文件状态css,js
        $module_css = $this->module_name.DS.$default_theme.DS.'css'.DS.$this->controller_name;
        $module_css .= file_exists(ROOT_PATH.'static'.DS.$module_css.'.'.$this->action_name.'.css') ? '.'.$this->action_name.'.css' : '.css';
        $this->assign('module_css', file_exists(ROOT_PATH.'static'.DS.$module_css) ? $module_css : '');

        $module_js = $this->module_name.DS.$default_theme.DS.'js'.DS.$this->controller_name;
        $module_js .= file_exists(ROOT_PATH.'static'.DS.$module_js.'.'.$this->action_name.'.js') ? '.'.$this->action_name.'.js' : '.js';
        $this->assign('module_js', file_exists(ROOT_PATH.'static'.DS.$module_js) ? $module_js : '');

        // 导航
        $this->assign('nav_header', $this->nav_header);
        $this->assign('nav_footer', $this->nav_footer);
        $this->assign('nav_quick', $this->nav_quick);

        // 导航/底部默认显示
        $this->assign('is_header', 1);
        $this->assign('is_footer', 1);

        // 附件host地址
        $this->assign('attachment_host', config('shopxo.attachment_host'));

        // css/js引入host地址
        $this->assign('public_host', config('shopxo.public_host'));

        // 当前url地址
        $this->assign('my_url', __MY_URL__);

        // 项目public目录URL地址
        $this->assign('my_public_url', __MY_PUBLIC_URL__);

        // 当前http类型
        $this->assign('my_http', __MY_HTTP__);

        // seo
        $this->assign('home_seo_site_title', MyC('home_seo_site_title'));
        $this->assign('home_seo_site_keywords', MyC('home_seo_site_keywords'));
        $this->assign('home_seo_site_description', MyC('home_seo_site_description'));

        // 用户数据
        $this->assign('user', $this->user);

        // 用户中心菜单
        $this->assign('user_left_menu', NavigationService::UsersCenterLeftList());

        // 商品大分类
        $this->assign('goods_category_list', GoodsService::GoodsCategoryAll());

        // 搜索框下热门关键字
        $this->assign('home_search_keywords', SearchService::SearchKeywordsList());

        // 友情链接
        $link = LinkService::LinkList(['where'=>['is_enable'=>1]]);
        $this->assign('link_list', $link['data']);

        // 开发模式
        $this->assign('shopxo_is_develop', config('shopxo.is_develop'));

        // 顶部右侧导航
        $this->assign('common_nav_top_right_list', NavigationService::HomeHavTopRight(['user'=>$this->user]));

        // 底部导航
        $this->assign('common_bottom_nav_list', NavigationService::BottomNavigation(['user'=>$this->user]));

        // 编辑器文件存放地址
        $this->assign('editor_path_type', ResourcesService::EditorPathTypeValue(empty($this->user['id']) ? 'public' : 'user-'.$this->user['id']));

        // 分类展示层级模式
        $this->assign('category_show_level', MyC('common_show_goods_category_level', 3, true));

        // 备案信息
        $this->assign('home_site_icp', MyC('home_site_icp'));
        $this->assign('home_site_security_record_name', MyC('home_site_security_record_name'));
        $this->assign('home_site_security_record_url', MyC('home_site_security_record_url'));

        // 默认不加载放大镜
        $this->assign('is_load_imagezoom', 0);

        // 默认不加载百度地图api
        $this->assign('is_load_baidu_map_api', 0);

        // 存在地图事件则载入
        if(in_array(3, array_column($this->nav_quick, 'event_type')))
        {
            $this->assign('is_load_baidu_map_api', 1);
        }

        // 登录/注册方式
        $this->assign('home_user_login_type', MyC('home_user_login_type', [], true));
        $this->assign('home_user_reg_type', MyC('home_user_reg_type', [], true));
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

                $this->assign('form_table', $this->form_table);
                $this->assign('form_params', $this->form_params);
                $this->assign('form_md5_key', $this->form_md5_key);
                $this->assign('form_user_fields', $this->form_user_fields);
                $this->assign('form_order_by', $this->form_order_by);
            } else {
                $this->form_error = $ret['msg'];
                $this->assign('form_error', $this->form_error);
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
            $this->assign('msg', $name.' 非法访问');
            return $this->fetch('public/tips_error');
        }
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
}
?>