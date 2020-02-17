<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\index\controller;

use think\facade\Hook;
use think\Controller;
use app\service\SystemService;
use app\service\GoodsService;
use app\service\NavigationService;
use app\service\BuyService;
use app\service\MessageService;
use app\service\SearchService;
use app\service\ConfigService;
use app\service\LinkService;
use app\service\UserService;

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
    // 顶部导航
    protected $nav_header;

    // 底部导航
    protected $nav_footer;

    // 用户信息
    protected $user;

    // 请求参数
    protected $params;

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

        // 系统运行开始
        SystemService::SystemBegin();

        // 站点状态校验
        $this->SiteStstusCheck();

        // 公共数据初始化
        $this->CommonInit();

        // 菜单
        $this->NavInit();

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
        SystemService::SystemEnd();
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

        // 公共顶部小导航钩子-左侧
        $this->assign('plugins_view_header_navigation_top_left_data', Hook::listen('plugins_view_header_navigation_top_left', ['hook_name'=>'plugins_view_header_navigation_top_left', 'is_backend'=>false, 'user'=>$this->user]));

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
        // 公共参数
        $this->params = input();
        
        // 配置信息初始化
        ConfigService::ConfigInit();
        
        // url模式
        if(MyC('home_seo_url_model', 0) == 0)
        {
            \think\facade\Url::root(__MY_ROOT_PUBLIC__.'index.php?s=');
        }

        // 推荐人
        if(!empty($this->params['referrer']))
        {
            session('share_referrer_id', $this->params['referrer']);
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
        $this->assign('params', $this->params);

        // 价格符号
        $this->assign('price_symbol', config('shopxo.price_symbol'));

        // 站点类型
        $this->assign('common_site_type', MyC('common_site_type', 0, true));

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

        // 导航
        $this->assign('nav_header', $this->nav_header);
        $this->assign('nav_footer', $this->nav_footer);

        // 导航/底部默认显示
        $this->assign('is_header', 1);
        $this->assign('is_footer', 1);

        // 图片host地址
        $this->assign('attachment_host', config('shopxo.attachment_host'));

        // seo
        $this->assign('home_seo_site_title', MyC('home_seo_site_title'));
        $this->assign('home_seo_site_keywords', MyC('home_seo_site_keywords'));
        $this->assign('home_seo_site_description', MyC('home_seo_site_description'));

        // 页面最大宽度
        $max_width = MyC('home_content_max_width', 0, true);
        $max_width_style = ($max_width == 0) ? '' : 'max-width:'.$max_width.'px;';
        $this->assign('max_width_style', $max_width_style);

        // 用户数据
        $this->assign('user', $this->user);

        // 用户中心菜单
        $this->assign('user_left_menu', NavigationService::UsersCenterLeftList());

        // 商品大分类
        $this->assign('goods_category_list', GoodsService::GoodsCategoryAll());

        // 搜索框下热门关键字
        $home_search_keywords = [];
        switch(intval(MyC('home_search_keywords_type', 0)))
        {
            case 1 :
                $home_search_keywords = SearchService::SearchKeywordsList();
                break;
            case 2 :
                $home_search_keywords = explode(',', MyC('home_search_keywords'));
                break;
        }
        $this->assign('home_search_keywords', $home_search_keywords);

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
        $this->assign('editor_path_type', empty($this->user['id']) ? 'public' : 'user-'.$this->user['id']);

        // 备案信息
        $this->assign('home_site_icp', MyC('home_site_icp'));
        $this->assign('home_site_security_record_name', MyC('home_site_security_record_name'));
        $this->assign('home_site_security_record_url', MyC('home_site_security_record_url'));

        // 默认不加载百度地图api
        $this->assign('is_load_baidu_map_api', 0);
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
        $navigation = NavigationService::Nav();
        $this->nav_header = $navigation['header'];
        $this->nav_footer = $navigation['footer'];
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