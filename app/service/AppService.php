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

/**
 * app服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppService
{
    /**
     * 商品详情导航更多列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function GoodsNavMoreList($params = [])
    {
        $data = [];
        if(!empty($params['page']))
        {
            $is_goods_detail_show_left_more = MyC('common_is_goods_detail_show_left_more', 0) == 1;
            if($is_goods_detail_show_left_more)
            {
                $lang = MyLang('app_goods_nav_more_list_data');
                switch($params['page'])
                {
                    // 商品页面
                    // icon 参考各终端
                    // web http://amazeui.shopxo.net/css/icon
                    // uniapp https://hellouniapp.dcloud.net.cn/pages/extUI/icons/icons
                    case 'goods' :
                        if($is_goods_detail_show_left_more)
                        {
                            $data = [
                                [
                                    'name'  => $lang['goodsfavor'],
                                    'url'   => '/pages/user-favor/user-favor',
                                    'icon'  => 'icon-collect'
                                ],
                                [
                                    'name'  => $lang['goodsbrowse'],
                                    'url'   => '/pages/user-goods-browse/user-goods-browse',
                                    'icon'  => 'icon-eye'
                                ],
                                [
                                    'name'  => $lang['home'],
                                    'url'   => '/pages/index/index',
                                    'icon'  => 'icon-home'
                                ]
                            ];
                        }
                        break;
                }
            }
        }

        // 导航更多信息钩子
        $hook_name = 'plugins_service_app_goods_more_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 首页右侧icon列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-11-26
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function HomeRightIconList($params = [])
    {
        // 消息总数
        $message_total = empty($params['message_total']) ? 0 : $params['message_total'];

        // 列表数据
        // name 名称（必填）
        // icon 图标（必填、参考uniapp扩展图标文档）
        // url  访问地址（可选）
        $lang = MyLang('app_home_right_list_data');
        $data = [
            [
                'name'  => $lang['goodsfavor'],
                'icon'  => 'icon-star',
                'url'   => '/pages/user-favor/user-favor',
            ],
            [
                'name'  => $lang['usermessage'],
                'icon'  => 'icon-message',
                'badge' => $message_total,
                'url'   => '/pages/message/message',
            ]
        ];

        // 钩子
        $hook_name = 'plugins_service_app_home_right_icon_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'data'          => &$data,
            'params'        => $params,
        ]);

        return $data;
    }

    /**
     * 内部页面地址列表（后台事件值配置用，与 DIY 页面链接独立）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-21
     * @desc    系统内置分组 + 钩子扩展；插件分组 data 为插件块（name/type/data），块内条目含 name/page，可选 tips；需拼参页面放块末尾
     * @param   [array]          $params [输入参数]
     */
    public static function PagesList($params = [])
    {
        $data = [
            'base' => [
                'name'  => MyLang('app_pages.group_base'),
                'type'  => 'base',
                'data'  => [
                    [
                        'name'  => MyLang('app_pages.page_index'),
                        'page'  => '/pages/index/index',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_goods_search_start'),
                        'page'  => '/pages/goods-search-start/goods-search-start',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_cart'),
                        'page'  => '/pages/cart/cart',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_cart_page'),
                        'page'  => '/pages/cart-page/cart-page',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_login'),
                        'page'  => '/pages/login/login',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_article_category'),
                        'page'  => '/pages/article-category/article-category',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_setup'),
                        'page'  => '/pages/setup/setup',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_about'),
                        'page'  => '/pages/about/about',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_goods_category'),
                        'page'  => '/pages/goods-category/goods-category',
                        'tips'  => MyLang('app_pages.tips_goods_category'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_goods_search'),
                        'page'  => '/pages/goods-search/goods-search',
                        'tips'  => MyLang('app_pages.tips_goods_search'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_goods_detail'),
                        'page'  => '/pages/goods-detail/goods-detail',
                        'tips'  => MyLang('app_pages.tips_goods_detail'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_goods_comment'),
                        'page'  => '/pages/goods-comment/goods-comment',
                        'tips'  => MyLang('app_pages.tips_goods_comment'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_article_detail'),
                        'page'  => '/pages/article-detail/article-detail',
                        'tips'  => MyLang('app_pages.tips_article_detail'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_diy'),
                        'page'  => '/pages/diy/diy',
                        'tips'  => MyLang('app_pages.tips_diy'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_design'),
                        'page'  => '/pages/design/design',
                        'tips'  => MyLang('app_pages.tips_design'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_customview'),
                        'page'  => '/pages/customview/customview',
                        'tips'  => MyLang('app_pages.tips_customview'),
                    ],
                    [
                        'name'  => MyLang('app_pages.page_web_view'),
                        'page'  => '/pages/web-view/web-view',
                        'tips'  => MyLang('app_pages.tips_web_view'),
                    ],
                ],
            ],
            'user' => [
                'name'  => MyLang('app_pages.group_user'),
                'type'  => 'user',
                'data'  => [
                    [
                        'name'  => MyLang('app_pages.page_user'),
                        'page'  => '/pages/user/user',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_order'),
                        'page'  => '/pages/user-order/user-order',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_orderaftersale'),
                        'page'  => '/pages/user-orderaftersale/user-orderaftersale',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_favor'),
                        'page'  => '/pages/user-favor/user-favor',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_goods_comments'),
                        'page'  => '/pages/user-goods-comments/user-goods-comments',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_address'),
                        'page'  => '/pages/user-address/user-address',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_integral'),
                        'page'  => '/pages/user-integral/user-integral',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_message'),
                        'page'  => '/pages/message/message',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_goods_browse'),
                        'page'  => '/pages/user-goods-browse/user-goods-browse',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_personal'),
                        'page'  => '/pages/personal/personal',
                    ],
                    [
                        'name'  => MyLang('app_pages.page_user_order_detail'),
                        'page'  => '/pages/user-order-detail/user-order-detail',
                        'tips'  => MyLang('app_pages.tips_user_order_detail'),
                    ],
                ],
            ],
            'plugins' => [
                'name'  => MyLang('app_pages.group_plugins'),
                'type'  => 'plugins',
                'data'  => [],
            ],
        ];

        // 插件扩展钩子（向 data.plugins.data 追加插件块；块内条目含 name/page，可选 tips，需拼参放块末尾）
        $hook_name = 'plugins_service_app_pages_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        // 基础配置里的名称（含 i18n）覆盖插件分组和已写入的文案
        if(!empty($data['plugins']['data']) && is_array($data['plugins']['data']))
        {
            foreach($data['plugins']['data'] as &$plugin)
            {
                self::PagesListPluginConfigI18nHandle($plugin);
            }
            unset($plugin);
        }

        return $data;
    }

    /**
     * 插件基础配置多语言名称应用到页面地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    后台默认不替换插件配置，这里强制按当前语言覆盖
     * @param   [array]          $plugin [插件页面块]
     */
    private static function PagesListPluginConfigI18nHandle(&$plugin)
    {
        $plugins = isset($plugin['type']) ? $plugin['type'] : '';
        if($plugins === '' || $plugins === 'plugins')
        {
            return;
        }
        $ret = PluginsService::PluginsData($plugins);
        $config = (empty($ret['data']) || !is_array($ret['data'])) ? [] : $ret['data'];
        if(empty($config))
        {
            return;
        }
        $raw = $config;
        I18nService::PluginsConfigHandle($plugins, $config, true);

        $pairs = [];
        foreach($raw as $key=>$value)
        {
            if(!is_string($value) || $value === '' || !isset($config[$key]) || !is_string($config[$key]) || $config[$key] === '' || $config[$key] === $value)
            {
                continue;
            }
            $pairs[] = [$value, $config[$key]];
        }
        if(!empty($pairs))
        {
            usort($pairs, function($a, $b)
            {
                return mb_strlen($b[0]) - mb_strlen($a[0]);
            });
            self::PagesListTextReplace($plugin, $pairs);
        }
        if(!empty($config['application_name']) && is_string($config['application_name']))
        {
            $plugin['name'] = $config['application_name'];
        }
    }

    /**
     * 替换页面名称和提示里的配置原文
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-09-22
     * @desc    description
     * @param   [array]          $node  [节点]
     * @param   [array]          $pairs [原文与译文]
     */
    private static function PagesListTextReplace(&$node, $pairs)
    {
        if(empty($node) || !is_array($node) || empty($pairs))
        {
            return;
        }
        foreach($node as $key=>&$value)
        {
            if(($key === 'name' || $key === 'tips') && is_string($value) && $value !== '')
            {
                foreach($pairs as $pair)
                {
                    $value = str_replace($pair[0], $pair[1], $value);
                }
            } elseif(is_array($value))
            {
                self::PagesListTextReplace($value, $pairs);
            }
        }
    }
}
?>