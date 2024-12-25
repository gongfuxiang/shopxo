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
namespace app\service;

use app\service\UserService;
use app\service\MultilingualService;
use app\service\PluginsService;
use app\service\DesignService;
use app\service\CustomViewService;
use app\service\ThemeAdminService;
use app\service\PluginsAdminService;

/**
 * 配置服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SystemService
{
    // 后台主题配色缓存key
    public static $admin_theme_color_value_key = 'admin_theme_color_value_key';

    /**
     * 系统运行开始
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SystemBegin($params = [])
    {
        // 基础数据初始化
        self::BaseInit($params);

        // 钩子
        $hook_name = 'plugins_service_system_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]);
    }

    /**
     * 系统运行结束
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function SystemEnd($params = [])
    {
        $hook_name = 'plugins_service_system_end';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
        ]);
    }

    /**
     * 基础数据初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function BaseInit($params = [])
    {
        // uuid
        $uuid = empty($params['uuid']) ? MySession('uuid') : $params['uuid'];
        if(empty($uuid))
        {
            $uuid = MyCookie('uuid');
            if(empty($uuid))
            {
                $uuid = UUId();
            }
        }
        MySession('uuid', $uuid);
        MyCookie('uuid', $uuid, false);

        // token
        if(!empty($params['token']))
        {
            $key = UserService::$user_token_key;
            MySession($key, $params['token']);
            MyCookie($key, $params['token'], false);
        }

        // 邀请人id
        if(!empty($params['referrer']))
        {
            MySession('share_referrer_id', $params['referrer']);
            MyCookie('share_referrer_id', $params['referrer'], false);
        }

        // 多语言初始化设置
        MultilingualService::SetUserMultilingualValue();
    }

    /**
     * 系统安装检查
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-18
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SystemInstallCheck($params = [])
    {
        if(!file_exists(ROOT.'config/database.php'))
        {
            if(!isset($params['is_redirect']) || $params['is_redirect'] != 0)
            {
                MyRedirect(__MY_URL__.'install.php?s=index/index', true);
            }
            return false;
        }
        return true;
    }

    /**
     * 系统类型值
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-15
     * @desc    description
     */
    public static function SystemTypeValue()
    {
        // 取默认值
        $value = SYSTEM_TYPE;

        // 默认值则判断是否参数存在值
        if($value == 'default')
        {
            $system_type = MyInput('system_type');
            if(!empty($system_type))
            {
                $value = $system_type;
            }
        }

        // 系统类型钩子
        $hook_name = 'plugins_service_system_system_type_value';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'value'         => &$value,
        ]);

        return $value;
    }

    /**
     * 缓存key获取
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-21
     * @desc    description
     * @param   [string]          $key [缓存key]
     */
    public static function CacheKey($key)
    {
        return MyConfig($key).'_'.SYSTEM_TYPE.'_'.RequestModule();
    }

    /**
     * 获取环境参数最大数
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-24
     * @desc    description
     */
    public static function EnvMaxInputVarsCount()
    {
        return intval(ini_get('max_input_vars'));
    }

    /**
     * 域名地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-05-12
     * @desc    description
     */
    public static function DomainUrl()
    {
        static $site_domain_url = null;
        if($site_domain_url === null)
        {
            if(APPLICATION == 'web')
            {
                $site_domain_url = MyC('common_domain_host');
                if(empty($site_domain_url))
                {
                    $site_domain_url = __MY_URL__;
                }
                if(substr($site_domain_url, -1) != DS)
                {
                    $site_domain_url .= DS;
                }
            } else {
                $site_domain_url = '/pages/index/index';
            }
        }
        return $site_domain_url;
    }

    /**
     * 页面语言数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-08-22
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PageViewLangData($params = [])
    {
        // 页面公共语言
        $lang_common = MyLang('page_common');
        if(empty($lang_common) || !is_array($lang_common))
        {
            $lang_common = [];
        }
        // 当前控制器
        $lang_page = MyLang(RequestController().'.page_common');
        if(empty($lang_page) || !is_array($lang_page))
        {
            $lang_page = [];
        }
        $data = array_merge($lang_common, $lang_page);

        // 追加多语言code
        $data['multilingual_default_code'] = MultilingualService::GetUserMultilingualValue();

        // 分页加入语言
        $page_lang = MyLang('common_extend.base.page');
        if(!empty($page_lang) && is_array($page_lang))
        {
            foreach($page_lang as $k=>$v)
            {
                $data['page_'.$k] = $v;
            }
        }

        // 页面语言读取钩子
        $hook_name = 'plugins_service_system_page_view_lang_data';
        MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => &$data,
            ]);

        return $data;
    }

    /**
     * 主题样式默认数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-06-13
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeStyleDefaultData($params = [])
    {
        return [
            // 基准大小、背景色
            'html_body_size'                          => 10,
            'body_bg_color'                           => '#f7f7f7',
            
            // 价格色
            'color_price'                             => '#E22C08',
            
            // 红色、黄色、蓝色、绿色
            'color_red'                               => '#E22C08',
            'color_yellow'                            => '#FAAD14',
            'color_blue'                              => '#76AFFF',
            'color_green'                             => '#5EB95E',
            
            // 主色
            'color_main'                              => '#E22C08',
            'color_main_light'                        => '#ffe3de',
            'color_main_hover'                        => '#EA6B52',
            
            // 次色
            'color_secondary'                         => '#FFB8AA',
            
            // 圆角
            'border_radius_sm'                        => 0.2,
            'border_radius'                           => 0.4,
            'border_radius_lg'                        => 0.8,
            
            // 阴影
            'box_shadow'                              => '0 5px 20px rgba(50,55,58,0.1)',
            'box_shadow_sm'                           => '0 2px 8px rgba(50,55,58,0.1)',
            'box_shadow_lg'                           => '0 8px 34px rgba(50,55,58,0.1)',
            
            // 顶部公共温馨提示
            'color_tips_warm_bg'                      =>  '#F1FCFF',
            'color_tips_warm_br'                      =>  '#F1FCFF',
            
            // 默认基础色 - 按钮
            'color_button_default'                    => '#EEEEEE',
            'color_button_default_hover'              => '#dddddd',
            'color_button_default_focus'              => '#c7c7c7',
            'color_button_default_active'             => '#c7c7c7',
            'color_button_default_disabled'           => '#c2c2c2',
            'color_button_default_border'             => '#EEEEEE',
            'color_button_default_hover_border'       => '#dddddd',
            'color_button_default_focus_border'       => '#c7c7c7',
            'color_button_default_active_border'      => '#c7c7c7',
            'color_button_default_disabled_border'    => '#c7c7c7',
            'color_button_default_text'               => '#666666',
            'color_button_default_hover_text'         => '#444444',
            'color_button_default_focus_text'         => '#444444',
            'color_button_default_active_text'        => '#444444',
            'color_button_default_disabled_text'      => '#444444',
            
            // 主色 - 按钮
            'color_button_primary'                    => '#E22C08',
            'color_button_primary_hover'              => '#EA6B52',
            'color_button_primary_focus'              => '#C02000',
            'color_button_primary_active'             => '#C02000',
            'color_button_primary_disabled'           => '#F6BFB4',
            'color_button_primary_border'             => '#E22C08',
            'color_button_primary_hover_border'       => '#EA6B52',
            'color_button_primary_focus_border'       => '#C02000',
            'color_button_primary_active_border'      => '#C02000',
            'color_button_primary_disabled_border'    => '#F6BFB4',
            'color_button_primary_text'               => '#FFFFFF',
            'color_button_primary_hover_text'         => '#FFFFFF',
            'color_button_primary_focus_text'         => '#FFFFFF',
            'color_button_primary_active_text'        => '#FFFFFF',
            'color_button_primary_disabled_text'      => '#FFFFFF',
            
            // 次色 - 按钮
            'color_button_secondary'                  => '#FFEFE5',
            'color_button_secondary_hover'            => '#FCE9E6',
            'color_button_secondary_focus'            => '#FCE9E6',
            'color_button_secondary_active'           => '#F5B5A9',
            'color_button_secondary_disabled'         => '#F5B5A9',
            'color_button_secondary_border'           => '#FFCBAB',
            'color_button_secondary_hover_border'     => '#FDB6B0',
            'color_button_secondary_focus_border'     => '#FDB6B0',
            'color_button_secondary_active_border'    => '#F5B5A9',
            'color_button_secondary_disabled_border'  => '#F5B5A9',
            'color_button_secondary_text'             => '#E22C08',
            'color_button_secondary_hover_text'       => '#EA6247',
            'color_button_secondary_focus_text'       => '#E64829',
            'color_button_secondary_active_text'      => '#E2300D',
            'color_button_secondary_disabled_text'    => '#E2300D',
            
            // 成功 - 按钮
            'color_button_success'                    => '#62C584',
            'color_button_success_hover'              => '#3bdb74',
            'color_button_success_focus'              => '#3bdb74',
            'color_button_success_active'             => '#59bc59',
            'color_button_success_disabled'           => '#59bc59',
            'color_button_success_border'             => '#7fe27f',
            'color_button_success_hover_border'       => '#97ee97',
            'color_button_success_focus_border'       => '#5eb95e',
            'color_button_success_active_border'      => '#59bc59',
            'color_button_success_disabled_border'    => '#59bc59',
            'color_button_success_text'               => '#FFFFFF',
            'color_button_success_hover_text'         => '#FFFFFF',
            'color_button_success_focus_text'         => '#FFFFFF',
            'color_button_success_active_text'        => '#bffbbf',
            'color_button_success_disabled_text'      => '#bffbbf',
            
            // 警告 - 按钮
            'color_button_warning'                    => '#FAAD14',
            'color_button_warning_hover'              => '#FBC55A',
            'color_button_warning_focus'              => '#FBC55A',
            'color_button_warning_active'             => '#EB9C00',
            'color_button_warning_disabled'           => '#FDE6B8',
            'color_button_warning_border'             => '#FAAD14',
            'color_button_warning_hover_border'       => '#FBC55A',
            'color_button_warning_focus_border'       => '#FBC55A',
            'color_button_warning_active_border'      => '#EB9C00',
            'color_button_warning_disabled_border'    => '#FDE6B8',
            'color_button_warning_text'               => '#FFFFFF',
            'color_button_warning_hover_text'         => '#FFFFFF',
            'color_button_warning_focus_text'         => '#FFFFFF',
            'color_button_warning_active_text'        => '#FFFFFF',
            'color_button_warning_disabled_text'      => '#FFFFFF',
            
            // 危险 - 按钮
            'color_button_danger'                     => '#ffebeb',
            'color_button_danger_hover'               => '#FFEFED',
            'color_button_danger_focus'               => '#FFEFED',
            'color_button_danger_active'              => '#FFC2B6',
            'color_button_danger_disabled'            => '#FFFFFF',
            'color_button_danger_border'              => '#E33816',
            'color_button_danger_hover_border'        => '#DF2500',
            'color_button_danger_focus_border'        => '#D58576',
            'color_button_danger_active_border'       => '#FFC2B6',
            'color_button_danger_disabled_border'     => '#D58E80',
            'color_button_danger_text'                => '#da5c43',
            'color_button_danger_hover_text'          => '#e04527',
            'color_button_danger_focus_text'          => '#E12C08',
            'color_button_danger_active_text'         => '#C72100',
            'color_button_danger_disabled_text'       => '#FFC3B7',
            
            // 辅助 - 按钮
            'color_button_assist'                     => '#f7e7e7',
            'color_button_assist_hover'               => '#f2bab0',
            'color_button_assist_focus'               => '#fac3bb',
            'color_button_assist_active'              => '#E22C08',
            'color_button_assist_disabled'            => '#faeaec',
            'color_button_assist_border'              => '#E22C08',
            'color_button_assist_hover_border'        => '#ea755d',
            'color_button_assist_focus_border'        => '#D58576',
            'color_button_assist_active_border'       => '#E22C08',
            'color_button_assist_disabled_border'     => '#f6d9d5',
            'color_button_assist_text'                => '#E22C08',
            'color_button_assist_hover_text'          => '#e54726',
            'color_button_assist_focus_text'          => '#e33210',
            'color_button_assist_active_text'         => '#ffffff',
            'color_button_assist_disabled_text'       => '#f1c3bb',
            
            // 小徽章部分
            // 默认基础色 - 小徽章
            'color_badge_default'                     => '#EEEEEE',
            'color_badge_default_hover'               => '#e9e9e9',
            'color_badge_default_text'                => '#666666',
            'color_badge_default_hover_text'          => '#666666',
            
            // 主色 - 小徽章
            'color_badge_primary'                     => '#eaf1fb',
            'color_badge_primary_hover'               => '#e4eefe',
            'color_badge_primary_text'                => '#0c7cd5',
            'color_badge_primary_hover_text'          => '#0c7cd5',
            
            // 次色 - 小徽章
            'color_badge_secondary'                   => '#ffefe5',
            'color_badge_secondary_hover'             => '#ffebdf',
            'color_badge_secondary_text'              => '#f18f51',
            'color_badge_secondary_hover_text'        => '#f18f51',
            
            // 成功色 - 小徽章
            'color_badge_success'                     => '#d5fbd5',
            'color_badge_success_hover'               => '#c6f9c6',
            'color_badge_success_text'                => '#46cf45',
            'color_badge_success_hover_text'          => '#46cf45',
            
            // 警告色 - 小徽章
            'color_badge_warning'                     => '#ffeac2',
            'color_badge_warning_hover'               => '#ffe3ae',
            'color_badge_warning_text'                => '#f3a200',
            'color_badge_warning_hover_text'          => '#f3a200',
            
            // 危险色 - 小徽章
            'color_badge_danger'                      => '#FFE6E6',
            'color_badge_danger_hover'                => '#ffdcdc',
            'color_badge_danger_text'                 => '#e04527',
            'color_badge_danger_hover_text'           => '#e04527',
        ];
    }

    /**
     * 主题样式数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-06-13
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeStyleData($params = [])
    {
        // 默认样式数据
        $data = self::ThemeStyleDefaultData();

        // 主题是否自定义配色
        if(!empty($params['default_theme']))
        {
            $theme_config = ThemeAdminService::ThemeAdminConfig($params['default_theme']);
            if(!empty($theme_config['data']) && !empty($theme_config['data']['theme_style']) && is_array($theme_config['data']['theme_style']))
            {
                $data = array_merge($data, $theme_config['data']['theme_style']);
            }
        }

        // 插件是否自定义配色
        if(RequestController() == 'plugins')
        {
            $plugins = PluginsRequestName();
            if(!empty($plugins))
            {
                $group = RequestModule();
                $plugins_config = PluginsAdminService::GetPluginsConfig($plugins);
                if(!empty($plugins_config) && is_array($plugins_config) && !empty($plugins_config['extend']) && is_array($plugins_config['extend']))
                {
                    if(!empty($plugins_config['extend'][$group]) && !empty($plugins_config['extend'][$group]['theme_style']) && is_array($plugins_config['extend'][$group]['theme_style']))
                    {
                        $data = array_merge($data, $plugins_config['extend'][$group]['theme_style']);
                    } else if(!empty($plugins_config['extend']['theme_style']) && is_array($plugins_config['extend']['theme_style']))
                    {
                        $data = array_merge($data, $plugins_config['extend']['theme_style']);
                    }
                }
            }
        }

        // 主题样式数据钩子
        $hook_name = 'plugins_service_system_view_theme_style_data';
        MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => &$data,
            ]);

        return $data;
    }

    /**
     * 设置后台主题配色
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-01-09
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SetAdminThemeColor($params = [])
    {
        if(isset($params['value']) && $params['value'] != 'blue')
        {
            MyCookie(self::$admin_theme_color_value_key, $params['value']);
        } else {
            MyCookie(self::$admin_theme_color_value_key, null);
        }
        return MyUrl('admin/index/index');
    }

    /**
     * 获取后台主题配色选择数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-01-09
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GetAdminThemeColor($params = [])
    {
        // 主题列表
        $list = [
            'blue'    => ['color' => '#2A94FF', 'name' => MyLang('blue_title')],
            'green'   => ['color' => '#37D995', 'name' => MyLang('green_title')],
            'red'     => ['color' => '#F54D40', 'name' => MyLang('red_title')],
            'orange'  => ['color' => '#FF971E', 'name' => MyLang('orange_title')],
            'yellow'  => ['color' => '#F4BE36', 'name' => MyLang('yellow_title')],
            'purple'  => ['color' => '#9574CD', 'name' => MyLang('purple_title')],
            'brown'   => ['color' => '#9F5933', 'name' => MyLang('brown_title')],
            'black'   => ['color' => '#394244', 'name' => MyLang('black_title')],
        ];

        // 当前选择的主题
        $value = MyCookie(self::$admin_theme_color_value_key);
        if(empty($value) || !array_key_exists($value, $list))
        {
            $value = 'blue';
        }
        $theme = $list[$value]['name'];

        // 可选数据列表
        $data = [];
        foreach($list as $k=>$v)
        {
            $data[] = [
                'name'  => $v['name'],
                'color' => $v['color'],
                'value' => $k,
                'url'   => MyUrl('admin/index/color', ['value'=>$k]),
            ];
        }

        // 配色数据
        $data = [
            'data'  => $data,
            'theme' => $theme,
            'value' => $value,
        ];

        // 后台主题配色选择数据钩子
        $hook_name = 'plugins_service_system_admin_theme_style_choice_data';
        MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => &$data,
            ]);

        return $data;
    }

    /**
     * 后台主题配色数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-01-10
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AdminThemeStyleDefaultData($params = [])
    {
        // 主题配色列表
        $list = [
            // 蓝色
            'blue'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#F1FCFF',
                'color_tips_warm_br'   => '#F1FCFF',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#2A94FF',
                'color_main_light'     => '#EDF4FF',
                'color_main_hover'     => '#1677FF',
                'color_main_disabled'  => '#7B94AE',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],

            // 绿色
            'green'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#F1FFF5',
                'color_tips_warm_br'   => '#F1FFF5',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#37D995',
                'color_main_light'     => '#dffff2',
                'color_main_hover'     => '#22e895',
                'color_main_disabled'  => '#79AB8C',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],

            // 红色
            'red'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#fff7f7',
                'color_tips_warm_br'   => '#fff7f7',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#F54D40',
                'color_main_light'     => '#fff3f2',
                'color_main_hover'     => '#ec3628',
                'color_main_disabled'  => '#AF6963',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],

            // 橙色
            'orange'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#FFF8F1',
                'color_tips_warm_br'   => '#FFF8F1',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#FF971E',
                'color_main_light'     => '#fff4e8',
                'color_main_hover'     => '#FF8106',
                'color_main_disabled'  => '#B49877',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],

            // 黄色
            'yellow'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#fff9f3',
                'color_tips_warm_br'   => '#fff9f3',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#F4BE36',
                'color_main_light'     => '#fff6df',
                'color_main_hover'     => '#FFD550',
                'color_main_disabled'  => '#A89B79',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],

            // 紫色
            'purple'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#f9f4ff',
                'color_tips_warm_br'   => '#f9f4ff',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#9574CD',
                'color_main_light'     => '#f6f2ff',
                'color_main_hover'     => '#B39EDC',
                'color_main_disabled'  => '#7C6D95',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],

            // 棕色
            'brown'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#FFF6F1',
                'color_tips_warm_br'   => '#FFF6F1',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#9F5933',
                'color_main_light'     => '#ffefd9',
                'color_main_hover'     => '#CA9A6F',
                'color_main_disabled'  => '#7F685B',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],

            // 黑色
            'black'  => [
                // 基础
                'html_body_size'       => '10px',
                'body_bg_color'        => '#ffffff',
                'color_red'            => '#E22C08',
                'color_yellow'         => '#FAAD14',
                'color_blue'           => '#2A94FF',
                'color_green'          => '#5EB95E',
                // 温馨提示
                'color_tips_warm_bg'   => '#F8F8F8',
                'color_tips_warm_br'   => '#F8F8F8',
                // 左侧菜单
                'color_left_menu_bg'   => '#1E222B',
                // 主色
                'color_main'           => '#394244',
                'color_main_light'     => '#ebebeb',
                'color_main_hover'     => '#334F5B',
                'color_main_disabled'  => '#333637',
                // 圆角
                'border_radius_sm'     => '0.2rem',
                'border_radius'        => '0.4rem',
                'border_radius_lg'     => '0.8rem',
                // 阴影
                'box_shadow'           => '0 5px 20px rgba(50,55,58,0.1)',
                'box_shadow_sm'        => '0 2px 8px rgba(50,55,58,0.1)',
                'box_shadow_lg'        => '0 8px 34px rgba(50,55,58,0.1)',
            ],
        ];

        // 当前选择的主题
        $value = MyCookie(self::$admin_theme_color_value_key);
        if(empty($value) || !array_key_exists($value, $list))
        {
            $value = 'blue';
        }
        $data = $list[$value];

        // 后台主题样式数据钩子
        $hook_name = 'plugins_service_system_view_admin_theme_style_data';
        MyEventTrigger($hook_name,
            [
                'hook_name'   => $hook_name,
                'is_backend'  => true,
                'data'        => &$data,
            ]);

        return $data;
    }

    /**
     * 站点默认首页数据列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-03
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function SiteDefaultIndexDataList($params = [])
    {
        // 数据容器
        $data = [];

        // 插件列表
        $plugins_list = PluginsService::PluginsHomeDataList();
        if(!empty($plugins_list))
        {
            $data[] = [
                'name'  => MyLang('plugins_title'),
                'data'  => $plugins_list,
            ];
        }

        // 页面设计
        $page_size = 30;
        $ret = DesignService::DesignList([
            'n'      => $page_size,
            'field'  => 'id,name',
            'where'  => [
                ['is_enable', '=', 1],
            ],
        ]);
        if(!empty($ret['data']) && is_array($ret['data']))
        {
            $design_list = [];
            foreach($ret['data'] as $v)
            {
                $design_list[] = [
                    'type'   => 'design',
                    'name'   => $v['name'],
                    'value'  => $v['id'],
                ];
            }
            if(!empty($design_list))
            {
                $data[] = [
                    'name'  => MyLang('common_service.system.design_title').'('.MyLang('new_title').$page_size.')'.MyLang('strip_title'),
                    'data'  => $design_list,
                ];
            }
        }

        // 自定义页面
        $page_size = 30;
        $ret = CustomViewService::CustomViewList([
            'n'      => $page_size,
            'field'  => 'id,name',
            'where'  => [
                ['is_enable', '=', 1],
            ],
        ]);
        if(!empty($ret['data']) && is_array($ret['data']))
        {
            $custom_view_list = [];
            foreach($ret['data'] as $v)
            {
                $custom_view_list[] = [
                    'type'   => 'custom_view',
                    'name'   => $v['name'],
                    'value'  => $v['id'],
                ];
            }
            if(!empty($custom_view_list))
            {
                $data[] = [
                    'name'  => MyLang('common_service.system.custom_view_title').'('.MyLang('new_title').$page_size.')'.MyLang('strip_title'),
                    'data'  => $custom_view_list,
                ];
            }
        }

        // 站点默认首页数据列表钩子
        $hook_name = 'plugins_service_system_site_default_index_data_list';
        MyEventTrigger($hook_name,
            [
                'hook_name'   => $hook_name,
                'is_backend'  => true,
                'data'        => &$data,
            ]);

        return $data;
    }
}
?>