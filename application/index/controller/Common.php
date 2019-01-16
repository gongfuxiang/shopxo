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
namespace app\index\controller;

use think\Controller;
use app\service\GoodsService;
use app\service\NavigationService;
use app\service\BuyService;
use app\service\MessageService;
use app\service\SearchService;
use app\service\ConfigService;
use app\service\LinkService;

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

        // 站点状态校验
        $this->SiteStstusCheck();

        // 公共数据初始化
        $this->CommonInit();

        // 菜单
        $this->NavInit();

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
        
        // url模式
        if(MyC('home_seo_url_model', 0) == 0)
        {
            \think\facade\Url::root(__MY_ROOT_PUBLIC__.'index.php?s=');
        }
    }

    /**
     * [Is_Login 登录校验]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T11:43:48+0800
     */
    protected function Is_Login()
    {
        if(session('user') == null)
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
     * [CommonInit 公共数据初始化]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-03-09T11:43:48+0800
     */
    private function CommonInit()
    {
        // 用户数据
        if(session('user') != null)
        {
            $this->user = session('user');
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
        $default_theme = strtolower(MyC('common_default_theme', 'default', true));
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

        // 导航
        $this->assign('nav_header', $this->nav_header);
        $this->assign('nav_footer', $this->nav_footer);

        // 导航/底部默认显示
        $this->assign('is_header', 1);
        $this->assign('is_footer', 1);

        // 图片host地址
        $this->assign('attachment_host', config('shopxo.attachment_host'));

        // 标题
        $this->assign('home_seo_site_title', MyC('home_seo_site_title'));

        // 页面最大宽度
        $max_width = MyC('home_content_max_width', 0, true);
        $max_width_style = ($max_width == 0) ? '' : 'max-width:'.$max_width.'px;';
        $this->assign('max_width_style', $max_width_style);

        // 用户数据
        $this->assign('user', $this->user);

        // 用户中心菜单
        $this->assign('user_left_menu', lang('user_left_menu'));

        // 商品大分类
        $this->assign('goods_category_list', GoodsService::GoodsCategory());

        // 购物车商品总数
        $common_cart_total = BuyService::UserCartTotal(['user'=>$this->user]);
        $this->assign('common_cart_total', ($common_cart_total > 99) ? '99+' : $common_cart_total);

        // 未读消息总数
        $params = ['user'=>$this->user, 'is_more'=>1, 'is_read'=>0, 'user_type'=>'user'];
        $common_message_total = MessageService::UserMessageTotal($params);
        $this->assign('common_message_total', ($common_message_total > 99) ? '99+' : $common_message_total);

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

        // 商城公告
        $this->assign('common_shop_notice', MyC('common_shop_notice'));

        // 友情链接
        $link = LinkService::LinkList(['where'=>['is_enable'=>1]]);
        $this->assign('link_list', $link['data']);
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
        $navigation = NavigationService::Home();
        $this->nav_header = $navigation['header'];
        $this->nav_footer = $navigation['footer'];
    }

    /**
     * [GetBrowserSeoTitle 获取浏览器seo标题]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-25T14:21:21+0800
     * @param    [string]     $title [标题]
     * @param    [int]        $type  [页面类型 0, 1, 2]
     * @return   [string]            [浏览器seo标题]
     */
    protected function GetBrowserSeoTitle($title, $type)
    {
        switch($type)
        {
            case 0:
                break;

            case 1:
                $site_name = MyC('home_site_name');
                break;

            default:
                $site_name = MyC('home_seo_site_title');
        }
        return empty($title) ? $site_name : $title.' - '.$site_name;
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
            return $this->fetch('public/error');
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
                die(json_encode(DataReturn(MyC('home_site_close_reason', '网站维护中...'), -10000)));
            } else {
                die('<div style="text-align: center;margin-top: 15%;font-size: 18px;color: #f00;">'.MyC('home_site_close_reason', '网站维护中...', true).'</div>');
            }
        }
    }
}
?>