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
use app\service\NavigationService;
use app\service\RegionService;

/**
 * 面包屑导航服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class BreadcrumbService
{
    /**
     * 面包屑数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-07-20
     * @desc    description
     * @param   [string]          $method   [方法类型]
     * @param   [array]           $params   [输入参数]
     */
    public static function Data($method = '', $params = [])
    {
        // 默认首页
        $result = [
            [
                'type'  => 0,
                'name'  => MyLang('home_title'),
                'url'   => SystemService::DomainUrl(),
                'icon'  => 'am-icon-home',
            ],
        ];

        // 根据页面自动添加面包屑导航
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        if(!empty($backtrace))
        {
            // 用户中心
            if(!empty($backtrace[3]) && !empty($backtrace[3]['class']) && $backtrace[3]['class'] == 'app\index\controller\Center')
            {
                $result[] = [
                    'type'  => 0,
                    'name'  => MyLang('common_service.navigation.user_center_left_list.center'),
                    'url'   => MyUrl('index/user/index'),
                ];

                // 当前控制器
                $user_center_menu = NavigationService::UserCenterLeftList();
                if(!empty($user_center_menu))
                {
                    // 临时导航
                    $temp_nav = [];

                    // 当前控制器
                    $controller = RequestController();

                    // 是否插件
                    if($controller == 'plugins')
                    {
                        $url = PluginsHomeUrl(PluginsRequestName());
                    } else {
                        $url = MyUrl('index/'.$controller.'/index');
                    }

                    // 是否存在对应
                    foreach($user_center_menu as $v)
                    {
                        // 一级菜单是否存在
                        if(!empty($v['url']) && stripos($v['url'], $url) !== false)
                        {
                            $temp_nav = $v;
                            break;
                        }

                        // 子级数据
                        if(!empty($v['item']) && is_array($v['item']))
                        {
                            foreach($v['item'] as $vs)
                            {
                                if(!empty($vs['url']) && stripos($vs['url'], $url) !== false)
                                {
                                    $temp_nav = $vs;
                                    break 2;
                                }
                            }
                        }
                    }
                    if(!empty($temp_nav))
                    {
                        $result[] = [
                            'type'  => 0,
                            'name'  => $temp_nav['name'],
                            'url'   => $temp_nav['url'],
                        ];
                    }
                }
            }
        }

        // 根据类型调用方法
        $temp_result = [];
        if(!empty($method))
        {
            if(method_exists(__CLASS__, $method))
            {
                $temp_result = self::$method($params);
            }
        }

        return array_merge($result, $temp_result);
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-07-20
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsSearch($params = [])
    {
        $result = [];
        // 商品分类
        if(!empty($params['cid']))
        {
            // 一级
            $where = [
                ['id', '=', intval($params['cid'])],
                ['is_enable', '=', 1],
            ];
            $category = Db::name('GoodsCategory')->where($where)->field('id,pid,name')->find();
            if(!empty($category))
            {
                $where = [
                    ['pid', '=', $category['pid']],
                    ['is_enable', '=', 1],
                ];
                $category_list = Db::name('GoodsCategory')->where($where)->field('id,pid,name')->select()->toArray();
                if(!empty($category_list))
                {
                    array_unshift($result, [
                        'id'    => $category['id'],
                        'name'  => $category['name'],
                        'type'  => 1,
                        'data'  => array_map(function($v)
                                    {
                                        $v['url'] = MyUrl('index/search/index', ['cid'=>$v['id']]);
                                        return $v;
                                    }, $category_list),
                    ]);

                    // 二级
                    $where = [
                        ['id', '=', $category['pid']],
                        ['is_enable', '=', 1],
                    ];
                    $category = Db::name('GoodsCategory')->where($where)->field('id,pid,name')->find();
                    if(!empty($category))
                    {
                        $where = [
                            ['pid', '=', $category['pid']],
                            ['is_enable', '=', 1],
                        ];
                        $category_list = Db::name('GoodsCategory')->where($where)->field('id,pid,name')->select()->toArray();
                        if(!empty($category_list))
                        {
                            array_unshift($result, [
                                'id'    => $category['id'],
                                'name'  => $category['name'],
                                'type'  => 1,
                                'data'  => array_map(function($v)
                                            {
                                                $v['url'] = MyUrl('index/search/index', ['cid'=>$v['id']]);
                                                return $v;
                                            }, $category_list),
                            ]);

                            // 三级
                            $where = [
                                ['id', '=', $category['pid']],
                                ['is_enable', '=', 1],
                            ];
                            $category = Db::name('GoodsCategory')->where($where)->field('id,pid,name')->find();
                            if(!empty($category))
                            {
                                $where = [
                                    ['pid', '=', $category['pid']],
                                    ['is_enable', '=', 1],
                                ];
                                $category_list = Db::name('GoodsCategory')->where($where)->field('id,pid,name')->select()->toArray();
                                if(!empty($category_list))
                                {
                                    array_unshift($result, [
                                        'id'    => $category['id'],
                                        'name'  => $category['name'],
                                        'type'  => 1,
                                        'data'  => array_map(function($v)
                                                    {
                                                        $v['url'] = MyUrl('index/search/index', ['cid'=>$v['id']]);
                                                        return $v;
                                                    }, $category_list),
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // 品牌、价格、关键字、属性、规格
        // 集合名称
        $temp_name = [];

        // 品牌
        $bid = empty($params['bid']) ? (empty($params['brand']) ? 0 : intval($params['brand'])) : intval($params['bid']);
        if(!empty($bid))
        {
            $brand = Db::name('Brand')->where(['id'=>$bid])->value('name');
            if(!empty($name))
            {
                $temp_name[] = $name;
            }
        }

        // 价格区间
        if(!empty($params['peid']))
        {
            $name = Db::name('ScreeningPrice')->where(['id'=>intval($params['peid'])])->value('name');
            if(!empty($name))
            {
                $temp_name[] = $name;
            }
        }

        // 商品产地
        if(!empty($params['poid']))
        {
            $name = RegionService::RegionName(intval($params['poid']));
            if(!empty($name))
            {
                $temp_name[] = $name;
            }
        }

        // 搜索关键字
        if(!empty($params['wd']))
        {
            $temp_name[] = AsciiToStr($params['wd']);
        }

        // 属性
        if(!empty($params['psid']))
        {
            $temp_name[] = AsciiToStr($params['psid']);
        }

        // 规格
        if(!empty($params['scid']))
        {
            $temp_name[] = AsciiToStr($params['scid']);
        }
        if(!empty($temp_name))
        {
            $result[] = [
                'type'  => 0,
                'name'  => implode(' / ', $temp_name).MyLang('common_service.search.search_breadcrumb_result_last_text'),
            ];
        }
        return $result;
    }

    /**
     * 商品详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-07-20
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsDetail($params = [])
    {
        $result = [];
        if(!empty($params['goods']))
        {
            // 商品分类
            $cids = Db::name('GoodsCategoryJoin')->where(['goods_id'=>$params['goods']['id']])->column('category_id');
            if(!empty($cids))
            {
                $where = [
                    ['id', 'in', $cids],
                    ['is_enable', '=', 1],
                ];
                $category = Db::name('GoodsCategory')->where($where)->field('id,name')->select()->toArray();
                if(!empty($category))
                {
                    $category = array_map(function($v)
                    {
                        $v['url'] = MyUrl('index/search/index', ['cid'=>$v['id']]);
                        return $v;
                    }, $category);
                    if(count($category) == 1)
                    {
                        $result[] = [
                            'type'  => 0,
                            'name'  => $category[0]['name'],
                            'url'   => $category[0]['url'],
                        ];
                    } else {
                        $result[] = [
                            'type'  => 1,
                            'name'  => MyLang('goods_category_title'),
                            'data'  => $category,
                        ];
                    }
                }
            }
            // 当前商品名称
            $result[] = [
                'type'  => 0,
                'name'  => $params['goods']['title'],
            ];
        }
        return $result;
    }

    /**
     * 文章分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-07-20
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ArticleCategory($params = [])
    {
        $result = [];
        if(!empty($params['category_info']))
        {
            // 当前文章分类名称
            $result[] = [
                'type'  => 0,
                'name'  => $params['category_info']['name'],
            ];
        }
        return $result;
    }

    /**
     * 文章详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-07-20
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ArticleDetail($params = [])
    {
        $result = [];
        if(!empty($params['article']))
        {
            // 文章分类
            if(!empty($params['article']['article_category_id']))
            {
                $category_name = Db::name('ArticleCategory')->where(['id'=>$params['article']['article_category_id']])->value('name');
                if(!empty($category_name))
                {
                    $result[] = [
                        'type'  => 0,
                        'name'  => $category_name,
                        'url'   => MyUrl('index/article/category', ['id'=>$params['article']['article_category_id']]),
                    ];
                }
            }
            // 当前文章名称
            $result[] = [
                'type'  => 0,
                'name'  => $params['article']['title'],
            ];
        }
        return $result;
    }
}
?>