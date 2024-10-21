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

use think\facade\Db;
use app\service\SystemService;
use app\service\GoodsCartService;
use app\service\MessageService;
use app\service\OrderService;
use app\service\GoodsService;
use app\service\GoodsBrowseService;
use app\service\GoodsFavorService;
use app\service\IntegralService;
use app\service\QuickNavService;

/**
 * 导航服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class NavigationService
{
    /**
     * 获取导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-08-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Nav($params = [])
    {
        // 读取缓存数据
        $header = MyCache(SystemService::CacheKey('shopxo.cache_common_home_nav_header_key'));
        $footer = MyCache(SystemService::CacheKey('shopxo.cache_common_home_nav_footer_key'));

        // 是否需要重新读取
        $is_query = MyEnv('app_debug') || MyInput('lang') || MyC('common_data_is_use_cache') != 1;
        // 缓存没数据则从数据库重新读取,顶部菜单
        if($header === null || $is_query)
        {
            // 获取导航数据
            $header = self::NavDataAll('header');
        }

        // 底部导航
        if($footer === null || $is_query)
        {
            // 获取导航数据
            $footer = self::NavDataAll('footer');
        }

        // 中间大导航添加首页导航
        array_unshift($header, [
            'id'                    => 0,
            'pid'                   => 0,
            'name'                  => MyLang('home_title'),
            'url'                   => SystemService::DomainUrl(),
            'data_type'             => 'system',
            'is_show'               => 1,
            'is_new_window_open'    => 0,
            'items'                 => [],
        ]);

        // 选中处理
        if(!empty($header))
        {
            $suffix = MyC('home_seo_url_html_suffix', 'html', true);
            foreach($header as $k=>&$v)
            {
                if($k > 0)
                {
                    $url = str_replace(['.'.$suffix, $suffix], '', $v['url']);
                    $v['active'] = (stripos(__MY_VIEW_URL__, $url) === false) ? 0 : 1;
                    if($v['active'] == 0 && !empty($v['items']))
                    {
                        $status = false;
                        foreach($v['items'] as &$vs)
                        {
                            $url = str_replace(['.'.$suffix, $suffix], '', $vs['url']);
                            if((stripos(__MY_VIEW_URL__, $url) !== false))
                            {
                                $vs['active'] = 1;
                                $status = true;
                            } else {
                                $vs['active'] = 0;
                            }
                        }

                        // 当子元素被选中则父级也选中
                        if($status)
                        {
                            $v['active'] = 1;
                        }
                    }
                } else {
                    // 首页选中处理
                    if(__MY_VIEW_URL__ == $v['url'])
                    {
                        $v['active'] = 1;
                    }
                }
            }
        }
        return [
            'header' => $header,
            'footer' => $footer,
        ];
    }

    /**
     * 获取导航数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-15
     * @param   [string]          $nav_type [导航类型（header, footer）]
     */
    public static function NavDataAll($nav_type)
    {
        // 返回数据
        static $main_nav_static_data = null;
        if($main_nav_static_data === null)
        {
            // 获取导航数据
            $nav = self::NavDataDealWith(Db::name('Navigation')->field('id,pid,name,url,value,data_type,nav_type,is_new_window_open')->where(['is_show'=>1])->order('sort asc,id asc')->select()->toArray());
            // 数据处理
            $temp_nav_data = [
                'header' => [],
                'footer' => [],
            ];
            if(!empty($nav))
            {
                foreach($nav as $v)
                {
                    if(empty($v['pid']))
                    {
                        $temp_nav_data[$v['nav_type']][$v['id']] = $v;
                    }
                }
                foreach($nav as $v)
                {
                    if(!empty($v['pid']) && array_key_exists($v['pid'], $temp_nav_data[$v['nav_type']]))
                    {
                        if(empty($temp_nav_data[$v['nav_type']][$v['pid']]['items']))
                        {
                            $temp_nav_data[$v['nav_type']][$v['pid']]['items'] = [];
                        }
                        $temp_nav_data[$v['nav_type']][$v['pid']]['items'][] = $v;
                    }
                }
            }
            $temp_nav_data['header'] = array_values($temp_nav_data['header']);
            $temp_nav_data['footer'] = array_values($temp_nav_data['footer']);
            $main_nav_static_data = $temp_nav_data;
        }
        $data = $main_nav_static_data[$nav_type];

        // 导航钩子
        $hook_name = 'plugins_service_navigation_'.$nav_type.'_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
            $nav_type       => &$data,
        ]);

        // 没数据则赋空数组值
        if(empty($data))
        {
            $data = [];
        }

        // 缓存
        MyCache(SystemService::CacheKey('shopxo.cache_common_home_nav_'.$nav_type.'_key'), $data, 180);
        return $data;
    }

    /**
     * 导航数据处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-05T21:36:46+0800
     * @param    [array]      $data [需要处理的数据]
     * @return   [array]            [处理好的数据]
     */
    public static function NavDataDealWith($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                // url处理
                switch($v['data_type'])
                {
                    // 文章分类
                    case 'article':
                        $v['url'] = MyUrl('index/article/index', ['id'=>$v['value']]);
                        break;

                    // 自定义页面
                    case 'customview':
                        $v['url'] = MyUrl('index/customview/index', ['id'=>$v['value']]);
                        break;

                    // 商品分类
                    case 'goods_category':
                        $v['url'] = MyUrl('index/search/index', ['cid'=>$v['value']]);
                        break;

                    // 页面设计
                    case 'design':
                        $v['url'] = MyUrl('index/design/index', ['id'=>$v['value']]);
                        break;

                    // 插件首页
                    case 'plugins':
                        $v['url'] = PluginsHomeUrl($v['value'], 'index', 'index');
                        break;
                }
                $data[$k] = $v;
            }
        }
        return $data;
    }

    /**
     * 获取导航列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params  [输入参数]
     */
    public static function NavList($params = [])
    {
        // 基础参数
        $field = '*';
        $where = empty($params['where']) ? [] : $params['where'];
        $order_by = empty($params['order_by']) ? 'sort asc,id asc' : $params['order_by'];

        // 获取数据
        $where1 = $where;
        $where1[] = ['pid', '=', 0];
        $data = self::NavigationHandle(self::NavDataDealWith(Db::name('Navigation')->field($field)->where($where1)->order($order_by)->select()->toArray()));
        $result = [];
        if(!empty($data))
        {
            // 子级数据组合
            $where2 = $where;
            $where2[] = ['pid', 'in', array_column($data, 'id')];
            $items_data = self::NavigationHandle(self::NavDataDealWith(Db::name('Navigation')->field($field)->where($where2)->order($order_by)->select()->toArray()));
            $items_group = [];
            if(!empty($items_data))
            {
                foreach($items_data as $tv)
                {
                    $items_group[$tv['pid']][] = $tv;
                }
            }

            // 数据集合
            if(!empty($items_group))
            {
                foreach($data as $dv)
                {
                    if(array_key_exists($dv['id'], $items_group))
                    {
                        $dv['is_sub_data'] = 1;
                        $result[] = $dv;
                        $result = array_merge($result, $items_group[$dv['id']]);
                    } else {
                        $result[] = $dv;
                    }
                }
            } else {
                $result = $data;
            }
        }

        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-15
     * @desc    description
     * @param   [array]          $data [导航数据]
     */
    public static function NavigationHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            $nav_type_list = MyConst('common_nav_type_list');
            foreach($data as &$v)
            {
                // 数据类型
                $v['data_type_text'] = isset($nav_type_list[$v['data_type']]) ? $nav_type_list[$v['data_type']]['name'] : '';

                // 时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }
        return $data;
    }

    /**
     * 获取一级导航列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function LevelOneNav($params = [])
    {
        if(empty($params['nav_type']))
        {
            return [];
        }

        return Db::name('Navigation')->field('id,name')->where(['is_show'=>1, 'pid'=>0, 'nav_type'=>$params['nav_type']])->select()->toArray();
    }

    /**
     * 导航保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-07T21:58:19+0800
     * @param    [array]          $params [输入参数]
     */
    public static function NavSave($params = [])
    {
        if(empty($params['data_type']))
        {
            return DataReturn(MyLang('operate_type_error_tips'), -1);
        }

        // 请求类型
        $p = [
            [
                'checked_type'      => 'length',
                'key_name'          => 'sort',
                'checked_data'      => '4',
                'error_msg'         => MyLang('form_sort_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_show',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_is_show_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'is_new_window_open',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_is_new_window_open_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'nav_type',
                'checked_data'      => ['header', 'footer'],
                'error_msg'         => MyLang('data_type_error_tips'),
            ],
        ];
        // 仅自定义必填名称
        if($params['data_type'] == 'custom')
        {
            $p[] = [
                    'checked_type'      => 'length',
                    'key_name'          => 'name',
                    'checked_data'      => '1,60',
                    'error_msg'         => MyLang('common_service.navigation.form_item_name_message'),
                ];
        }
        switch($params['data_type'])
        {
            // 自定义导航
            case 'custom':
                $p[] = [
                        'checked_type'      => 'fun',
                        'key_name'          => 'url',
                        'checked_data'      => 'CheckUrl',
                        'error_msg'         => MyLang('common_service.navigation.form_item_url_message'),
                    ];
                break;

            // 文章分类导航
            case 'article':
                $p[] = [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => MyLang('common_service.navigation.form_item_value_article_message'),
                    ];
                break;

            // 自定义页面导航
            case 'customview':
                $p[] = [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => MyLang('common_service.navigation.form_item_value_customview_message'),
                    ];
                break;

            // 商品分类导航
            case 'goods_category':
                $p[] = [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => MyLang('common_service.navigation.form_item_value_goods_category_message'),
                    ];
                break;

            // 页面设计导航
            case 'design':
                $p[] = [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => MyLang('common_service.navigation.form_item_value_design_message'),
                    ];
                break;

            // 插件首页
            case 'plugins':
                $p[] = [
                        'checked_type'      => 'empty',
                        'key_name'          => 'value',
                        'error_msg'         => MyLang('common_service.navigation.form_item_value_plugins_message'),
                    ];
                break;

            // 没找到
            default :
                return DataReturn(MyLang('operate_type_error_tips'), -1);
        }

        // 参数
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 保存数据
        return self::NacDataSave($params); 
    }

    /**
     * 导航数据保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-05T20:12:30+0800
     * @param    [array]          $params [输入参数]
     */
    public static function NacDataSave($params = [])
    {
        // 缓存 key
        $cache_key = SystemService::CacheKey('shopxo.cache_common_home_nav_'.$params['nav_type'].'_key');

        // 非自定义导航数据处理
        if(empty($params['name']))
        {
            switch($params['data_type'])
            {
                // 文章分类
                case 'article':
                    $temp_name = Db::name('Article')->where(['id'=>$params['value']])->value('title');
                    break;

                // 自定义页面
                case 'customview':
                    $temp_name = Db::name('CustomView')->where(['id'=>$params['value']])->value('name');
                    break;

                // 商品分类
                case 'goods_category':
                    $temp_name = Db::name('GoodsCategory')->where(['id'=>$params['value']])->value('name');
                    break;

                // 页面设计
                case 'design':
                    $temp_name = Db::name('Design')->where(['id'=>$params['value']])->value('name');
                    break;

                // 插件首页
                case 'plugins':
                    $temp_name = Db::name('Plugins')->where(['plugins'=>$params['value']])->value('name');
                    break;
            }
            // 只截取16个字符
            $params['name'] = mb_substr($temp_name, 0, 16, MyConfig('shopxo.default_charset'));
        }

        // 数据
        $data = [
            'pid'                   => isset($params['pid']) ? intval($params['pid']) : 0,
            'value'                 => isset($params['value']) ? trim($params['value']) : '',
            'name'                  => $params['name'],
            'url'                   => isset($params['url']) ? $params['url'] : '',
            'nav_type'              => $params['nav_type'],
            'data_type'             => $params['data_type'],
            'sort'                  => intval($params['sort']),
            'is_show'               => intval($params['is_show']),
            'is_new_window_open'    => intval($params['is_new_window_open']),
        ];

        // id为空则表示是新增
        if(empty($params['id']))
        {
            $data['add_time'] = time();
            if(Db::name('Navigation')->insertGetId($data) > 0)
            {
                // 清除缓存
                MyCache($cache_key, null);
                
                return DataReturn(MyLang('insert_success'), 0);
            } else {
                return DataReturn(MyLang('insert_fail'), -100);
            }
        } else {
            $data['upd_time'] = time();
            if(Db::name('Navigation')->where(['id'=>intval($params['id'])])->update($data))
            {
                // 清除缓存
                MyCache($cache_key, null);

                return DataReturn(MyLang('edit_success'), 0);
            } else {
                return DataReturn(MyLang('edit_fail'), -100);
            }
        }
    }

    /**
     * 导航删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function NavDelete($params = [])
    {
        // 参数是否有误
        if(empty($params['ids']))
        {
            return DataReturn(MyLang('data_id_error_tips'), -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 启动事务
        Db::startTrans();

        // 删除操作
        if(Db::name('Navigation')->where(['id'=>$params['ids']])->delete() !== false && Db::name('Navigation')->where(['pid'=>$params['ids']])->delete() !== false)
        {
            // 提交事务
            Db::commit();

            // 清除缓存
            MyCache(SystemService::CacheKey('shopxo.cache_common_home_nav_header_key'), null);
            MyCache(SystemService::CacheKey('shopxo.cache_common_home_nav_footer_key'), null);

            return DataReturn(MyLang('delete_success'), 0);
        }

        // 回滚事务
        Db::rollback();
        return DataReturn(MyLang('delete_fail'), -100);
    }

    /**
     * 状态更新
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     * @param    [array]          $params [输入参数]
     */
    public static function NavStatusUpdate($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'field',
                'error_msg'         => MyLang('operate_field_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'state',
                'checked_data'      => [0,1],
                'error_msg'         => MyLang('form_status_range_message'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 数据更新
        if(Db::name('Navigation')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            // 清除缓存
            MyCache(SystemService::CacheKey('shopxo.cache_common_home_nav_header_key'), null);
            MyCache(SystemService::CacheKey('shopxo.cache_common_home_nav_footer_key'), null);

            return DataReturn(MyLang('edit_success'), 0);
        }
        return DataReturn(MyLang('edit_fail'), -100);
    }

    /**
     * 获取前端顶部右侧导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function HomeHavTopRight($params = [])
    {
        // 列表
        $lang = MyLang('common_service.navigation.header_top_nav_right');
        $data = [
            [
                'name'      => $lang['user_center'],
                'type'      => 'center',
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'icon-user-center-nav-top',
                'url'       => MyUrl('index/user/index'),
                'items'     => [],
            ],
            [
                'name'      => $lang['user_shop'],
                'type'      => 'myself',
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'icon-mall-nav-top',
                'url'       => '',
                'items'     => [
                    [
                        'name'  => $lang['user_order'],
                        'url'   => MyUrl('index/order/index'),
                    ],
                ],
            ],
            [
                'name'      => $lang['favor'],
                'type'      => 'favor',
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'icon-collect-nav-top',
                'url'       => '',
                'items'     => [
                    [
                        'name'  => $lang['goods_favor'],
                        'url'   => MyUrl('index/usergoodsfavor/index'),
                    ],
                ],
            ],
        ];

        // 百宝箱、快捷导航
        if(MyC('home_navigation_main_quick_status') == 1)
        {
            $nav_quick = QuickNavService::QuickNav();
            if(!empty($nav_quick))
            {
                $data[] = [
                    'name'      => MyC('home_navigation_main_quick_name', MyLang('common.navigation_main_quick_name'), true),
                    'type'      => 'quick',
                    'is_login'  => 1,
                    'badge'     => null,
                    'icon'      => 'icon-more-nav-top',
                    'url'       => '',
                    'items'     => $nav_quick,
                ];
            }
        }

        // 购物车和消息
        $data = array_merge($data, [
            [
                'name'      => $lang['cart'],
                'type'      => 'cart',
                'is_login'  => 1,
                'badge'     => -1,
                'icon'      => 'icon-cart-nav-top',
                'url'       => MyUrl('index/cart/index'),
                'items'     => [],
            ],
            [
                'name'      => $lang['message'],
                'type'      => 'message',
                'is_login'  => 1,
                'badge'     => 0,
                'icon'      => 'icon-message-nav-top',
                'url'       => MyUrl('index/message/index'),
                'items'     => [],
            ],
        ]);

        // 追加多语言
        if(MyC('home_use_multilingual_status') == 1)
        {
            $multilingual_data = MultilingualService::MultilingualData();
            if(!empty($multilingual_data) && !empty($multilingual_data['data']) && !empty($multilingual_data['default']))
            {
                $data[] = [
                    'name'      => $multilingual_data['default']['name'],
                    'type'      => 'multilingual',
                    'is_login'  => 0,
                    'badge'     => null,
                    'icon'      => empty($multilingual_data['default']['icon']) ? 'icon-language-nav-top' : $multilingual_data['default']['icon'],
                    'url'       => '',
                    'items'     => $multilingual_data['data'],
                ];
            }
        }

        // 顶部小导航右侧钩子
        $hook_name = 'plugins_service_header_navigation_top_right_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        // 实时数据处理
        if(!empty($data) && !empty($params['user']))
        {
            // 所有类型
            $type = array_column($data, 'type');

            // 消息总数
            $index = array_search('message', $type);
            if($index !== false)
            {
                // 未读消息总数
                $message_params = ['user'=>$params['user'], 'is_more'=>1, 'is_read'=>0, 'user_type'=>'user'];
                $message_total = MessageService::UserMessageTotal($message_params);
                $data[$index]['badge'] = ($message_total <= 0) ? -1 : $message_total;
            }

            // 购物车商品汇总
            $index = array_search('cart', $type);
            if($index !== false)
            {
                $cart_res = GoodsCartService::UserGoodsCartTotal(['user'=>$params['user']]);
                $data[$index]['badge'] = $cart_res['buy_number'];
            }
        }
        return $data;
    }

    /**
     * 用户中心资料修改展示字段
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function UsersPersonalShowFieldList($params = [])
    {
        // is_ext       扩展数据 1, key不存在用户字段中可使用该扩展
        // name         显示名称
        // value        扩展自定义值
        // tips         html提示操作内容
        $modify_title = MyLang('modify_title');
        $data = [
            'avatar'            =>  [
                'name' => MyLang('user_avatar_title'),
                'tips' => '<a href="javascript:;" data-am-modal="{target:\'#user-avatar-popup\'}">'.$modify_title.'</a>'
            ],
            'nickname'          =>  [
                'name' => MyLang('user_nickname_title')
            ],
            'address_info'      => [
                'name' => MyLang('address_title')
            ],
            'gender_text'       =>  [
                'name' => MyLang('gender_title')
            ],
            'birthday'          =>  [
                'name' => MyLang('birthday_title')
            ],
            'mobile_security'   =>  [
                'name' => MyLang('user_mobile_title'),
                'tips' => '<a href="'.MyUrl('index/safety/mobileinfo').'">'.$modify_title.'</a>'
            ],
            'email_security'    =>  [
                'name' => MyLang('user_email_title'),
                'tips' => '<a href="'.MyUrl('index/safety/emailinfo').'">'.$modify_title.'</a>'
            ],
            'add_time_text'     =>  [
                'name' => MyLang('register_time_title')
            ],
            'upd_time_text'     =>  [
                'name' => MyLang('upd_time_title')
            ],
        ];

        // 用户中心资料修改展示字段钩子
        $hook_name = 'plugins_service_users_personal_show_field_list_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 用户安全项列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function UserSafetyPanelList($params = [])
    {
        $lang = MyLang('common_service.navigation.safety_panel_list');
        $data = [
            [
                'title'         => $lang['loginpwd']['title'],
                'msg'           => $lang['loginpwd']['msg'],
                'url'           => MyUrl('index/safety/loginpwdinfo'),
                'type'          => 'loginpwd',
            ],
            [
                'title'         => $lang['mobile']['title'],
                'no_msg'        => $lang['mobile']['no_msg'],
                'ok_msg'        => $lang['mobile']['ok_msg'],
                'tips'          => $lang['mobile']['tips'],
                'url'           => MyUrl('index/safety/mobileinfo'),
                'type'          => 'mobile',
            ],
            [
                'title'         => $lang['email']['title'],
                'no_msg'        => $lang['email']['no_msg'],
                'ok_msg'        => $lang['email']['ok_msg'],
                'tips'          => $lang['email']['tips'],
                'url'           => MyUrl('index/safety/emailinfo'),
                'type'          => 'email',
            ],
            [
                'title'         => $lang['logout']['title'],
                'msg'           => $lang['logout']['msg'],
                'url'           => MyUrl('index/safety/logoutinfo'),
                'type'          => 'logout',
                'submit_text'   => $lang['logout']['submit_text'],
            ],
        ];

        // 用户安全项列表钩子
        $hook_name = 'plugins_service_users_safety_panel_list_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 用户中心左侧菜单
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function UserCenterLeftList($params = [])
    {
        // name        名称
        // url         页面地址
        // is_show     是否显示（0否, 1是）
        // contains    包含的子页面（包括自身） 如用户中心（index 组, user 控制器, index 方法 [ indexuserindex ]）
        // icon        icon类
        // item        二级数据
        // is_system   是否系统内置菜单（0否, 1是）扩展数据可空或0

        // 菜单列表
        $lang = MyLang('common_service.navigation.user_center_left_list');
        $data = [
            'center' => [
                'name'      => $lang['center'],
                'url'       => MyUrl('index/user/index'),
                'is_show'   => 1,
                'contains'  => ['indexuserindex'],
                'icon'      => 'icon-user-center-left-home',
                'is_system' => 1,
            ],
            'business' => [
                'name'      => $lang['business'],
                'is_show'   => 1,
                'icon'      => 'icon-user-center-left-business',
                'is_system' => 1,
                'item'      => [
                    [
                        'name'      => $lang['order'],
                        'url'       => MyUrl('index/order/index'),
                        'is_show'   => 1,
                        'contains'  => ['indexorderindex', 'indexorderdetail', 'indexordercomments'],
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                    [
                        'name'      => $lang['orderaftersale'],
                        'url'       => MyUrl('index/orderaftersale/index'),
                        'is_show'   => 1,
                        'contains'  => ['indexorderaftersaleindex', 'indexorderaftersaledetail'],
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                    [
                        'name'      => $lang['goodsfavor'],
                        'url'       => MyUrl('index/usergoodsfavor/index'),
                        'contains'  => ['indexusergoodsfavorindex'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                    [
                        'name'      => $lang['goodscomments'],
                        'url'       => MyUrl('index/usergoodscomments/index'),
                        'contains'  => ['indexusergoodscommentsindex'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                ]
            ],
            'property' => [
                'name'      => $lang['property'],
                'is_show'   => 1,
                'icon'      => 'icon-user-center-left-property',
                'is_system' => 1,
                'item'      => [
                    [
                        'name'      => $lang['integral'],
                        'url'       => MyUrl('index/userintegral/index'),
                        'contains'  => ['indexuserintegralindex'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                ]
            ],
            'base' => [
                'name'      => $lang['base'],
                'is_show'   => 1,
                'icon'      => 'icon-user-center-left-base',
                'is_system' => 1,
                'item'      => [
                    [
                        'name'      => $lang['personal'],
                        'url'       => MyUrl('index/personal/index'),
                        'contains'  => ['indexpersonalindex', 'indexpersonalsaveinfo'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                    [
                        'name'      => $lang['address'],
                        'url'       => MyUrl('index/useraddress/index'),
                        'contains'  => ['indexuseraddressindex', 'indexuseraddresssaveinfo'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                    [
                        'name'      => $lang['safety'],
                        'url'       => MyUrl('index/safety/index'),
                        'contains'  => ['indexsafetyindex', 'indexsafetyloginpwdinfo', 'indexsafetymobileinfo', 'indexsafetynewmobileinfo', 'indexsafetyemailinfo', 'indexsafetynewemailinfo', 'indexsafetylogoutinfo'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                    [
                        'name'      => $lang['message'],
                        'url'       => MyUrl('index/message/index'),
                        'contains'  => ['indexmessageindex'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                    [
                        'name'      => $lang['goodsbrowse'],
                        'url'       => MyUrl('index/usergoodsbrowse/index'),
                        'contains'  => ['indexusergoodsbrowseindex'],
                        'is_show'   => 1,
                        'icon'      => '',
                        'is_system' => 1,
                    ],
                ]
            ],
            'logout' => [
                'name'      =>  $lang['logout'],
                'url'       =>  MyUrl('index/user/logout'),
                'contains'  =>  ['indexuserlogout'],
                'is_show'   =>  1,
                'icon'      =>  'icon-user-center-left-logout',
                'is_system' =>  1,
            ],
        ];

        // 用户中心左侧菜单钩子
        $hook_name = 'plugins_service_users_center_left_menu_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 获取网站底部导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入信息]
     */
    public static function BottomNavigationData($params = [])
    {
        $cart_total = 0;
        if(!empty($params['user']))
        {
            // 购物车商品汇总
            $cart_res = GoodsCartService::UserGoodsCartTotal(['user'=>$params['user']]);
            $cart_total = $cart_res['buy_number'];
        }
        
        // 列表
        $lang = MyLang('common_service.navigation.bottom_navigation_data');
        $data = [
            [
                'name'      => $lang['home'],
                'is_login'  => 0,
                'badge'     => null,
                'icon'      => 'icon-web-mobile-bottom-nav-home',
                'only_tag'  => 'indexindex',
                'url'       => SystemService::DomainUrl(),
            ],
            [
                'name'      => $lang['category'],
                'is_login'  => 0,
                'badge'     => null,
                'icon'      => 'icon-web-mobile-bottom-nav-category',
                'only_tag'  => 'categoryindex',
                'url'       => MyUrl('index/category/index'),
            ],
            [
                'name'      => $lang['cart'],
                'is_login'  => 1,
                'badge'     => $cart_total,
                'icon'      => 'icon-web-mobile-bottom-nav-cart',
                'only_tag'  => 'cartindex',
                'url'       => MyUrl('index/cart/index'),
            ],
            [
                'name'      => $lang['user'],
                'is_login'  => 1,
                'badge'     => null,
                'icon'      => 'icon-web-mobile-bottom-nav-user',
                'only_tag'  => 'userindex',
                'url'       => MyUrl('index/user/index'),
            ],
        ];

        // 网站底部导航
        $hook_name = 'plugins_service_bottom_navigation_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }

    /**
     * 用户中心基础信息中mini导航
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function UserCenterMiniNavigationData($params = [])
    {
        $user_order_count = 0;
        $user_goods_favor_count = 0;
        $user_goods_browse_count = 0;
        $user_integral = 0;
        if(!empty($params['user']))
        {
            // 订单总数
            $where = [
                ['user_id', '=', $params['user']['id']],
                ['is_delete_time', '=', 0],
                ['user_is_delete_time', '=', 0],
            ];
            $user_order_count = OrderService::OrderTotal($where);

            // 商品收藏/我的足迹总数
            $where = [
                ['user_id', '=', $params['user']['id']],
            ];
            $user_goods_favor_count = GoodsFavorService::GoodsFavorTotal($where);
            $user_goods_browse_count = GoodsBrowseService::GoodsBrowseTotal($where);

            // 用户积分
            $integral = IntegralService::UserIntegral($params['user']['id']);
            $user_integral = (!empty($integral) && !empty($integral['integral'])) ? $integral['integral'] : 0;
        }
        
        // 列表
        $lang = MyLang('common_service.navigation.user_center_mini_navigation_data');
        $data = [
            [
                'name'      => $lang['order'],
                'value'     => $user_order_count,
                'url'       => MyUrl('index/order/index'),
            ],
            [
                'name'      => $lang['goodsfavor'],
                'value'     => $user_goods_favor_count,
                'url'       => MyUrl('index/usergoodsfavor/index'),
            ],
            [
                'name'      => $lang['goodsbrowse'],
                'value'     => $user_goods_browse_count,
                'url'       => MyUrl('index/usergoodsbrowse/index'),
            ],
            [
                'name'      => $lang['integral'],
                'value'     => $user_integral,
                'url'       => MyUrl('index/userintegral/index'),
            ],
        ];

        // 用户中心基础信息中mini导航
        $hook_name = 'plugins_service_user_center_mini_navigation_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'data'          => &$data,
        ]);

        return $data;
    }
}
?>