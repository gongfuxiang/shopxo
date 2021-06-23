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
namespace app\layout\service;

use think\Db;
use think\facade\Hook;
use app\service\ResourcesService;
use app\service\GoodsService;

/**
 * 布局自动化服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-25
 * @desc    description
 */
class BaseLayout
{
    // 边线样式类型
    public static $border_style_type_list = [
        'solid'     => '实线',
        'dashed'    => '虚线',
        'dotted'    => '点状',
        'double'    => '双线',
    ];

    // 商品样式类型
    public static $goods_view_list_show_style = [
        'routine'   => '常规模式',
        'rolling'   => '滚动模式',
    ];

    // 多图样式类型
    public static $many_images_view_list_show_style = [
        'routine'   => '轮播模式',
        'rolling'   => '滚动模式',
        'list'      => '列表模式',
    ];

    /**
     * 配置处理-管理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-17
     * @desc    description
     * @param   [array]          $config [配置信息]
     * @param   [array]          $params [输入参数]
     */
    public static function ConfigAdminHandle($config, $params = [])
    {
        if(!empty($config))
        {
            // 是否数组
            if(!is_array($config))
            {
                $config = json_decode($config, true);
            }
            if(!empty($config))
            {
                foreach($config as &$v)
                {
                    // 配置信息处理
                    if(!empty($v['config']))
                    {
                        $v['config']['frontend_config'] = empty($v['config']['frontend_config']) ? '' : self::FrontendConfigHandle($v['config']['frontend_config']);
                    }

                    // 布局类型
                    $v['value_arr'] = explode(':', $v['value']);

                    // 布局
                    if(!empty($v['children']) && is_array($v['children']))
                    {
                        foreach($v['children'] as &$vs)
                        {
                            // 配置信息处理
                            if(!empty($vs['config']))
                            {
                                $vs['config']['frontend_config'] = empty($vs['config']['frontend_config']) ? '' : self::FrontendConfigHandle($vs['config']['frontend_config']);
                            }

                            // 容器
                            if(!empty($vs['children']) && is_array($vs['children']))
                            {
                                // 模块
                                foreach($vs['children'] as &$vss)
                                {
                                    if(!empty($vss['value']) && !empty($vss['config']))
                                    {
                                        // 前端配置信息处理
                                        $vss['config']['frontend_config'] = empty($vss['config']['frontend_config']) ? '' : self::FrontendConfigHandle($vss['config']['frontend_config']);

                                        // 滚动配置
                                        if(array_key_exists('view_list_show_style_value', $vss['config']))
                                        {
                                            $vss['config']['view_list_show_style_value_arr'] = empty($vss['config']['view_list_show_style_value']) ? '' : json_decode(urldecode($vss['config']['view_list_show_style_value']), true);
                                        }

                                        // 根据模块类型处理
                                        switch($vss['value'])
                                        {
                                            // 视频 video
                                            case 'video' :
                                                $vss['config']['content_video'] = ResourcesService::AttachmentPathViewHandle($vss['config']['content_video']);
                                                break;

                                            // 单图 images
                                            case 'images' :
                                                $vss['config']['content_images'] = ResourcesService::AttachmentPathViewHandle($vss['config']['content_images']);
                                                break;

                                            // 多图 many-images
                                            case 'many-images' :
                                                foreach($vss['config']['data_list'] as &$mil)
                                                {
                                                    $mil['images'] = ResourcesService::AttachmentPathViewHandle($mil['images']);
                                                }
                                                $key = 'content_images_';
                                                foreach($vss['config'] as $mik=>$miv)
                                                {
                                                    if(substr($mik, 0, strlen($key)) == $key)
                                                    {
                                                        $vss['config'][$mik] = ResourcesService::AttachmentPathViewHandle($miv);
                                                    }
                                                }
                                                break;

                                            // 商品
                                            case 'goods' :
                                                $p = [
                                                    'data_type' => $vss['config']['goods_data_type'],
                                                ];
                                                switch($vss['config']['goods_data_type'])
                                                {
                                                    // 指定商品
                                                    case 'goods' :
                                                        $p['goods_ids'] = $vss['config']['goods_ids'];
                                                        break;

                                                    // 商品分类
                                                    case 'category' :
                                                        $category = json_decode(urldecode($vss['config']['goods_category_value']), true);
                                                        $p['category_id'] = $category[count($category)-1]['id'];
                                                        $p['order_limit_number'] = empty($vss['config']['goods_order_limit_number']) ? 0 : $vss['config']['goods_order_limit_number'];
                                                        break;
                                                }
                                                $res = self::GoodsDataList($p);
                                                $vss['config']['data_list'] = $res['data'];
                                                break;

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //print_r($config);die;
        return $config;
    }

    /**
     * 配置处理-展示使用
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-17
     * @desc    description
     * @param   [array]          $config [配置信息]
     * @param   [array]          $params [输入参数]
     */
    public static function ConfigHandle($config, $params = [])
    {
        if(!empty($config))
        {
            // 是否数组
            if(!is_array($config))
            {
                $config = json_decode($config, true);
            }
            if(!empty($config))
            {
                foreach($config as &$v)
                {
                    // 配置信息处理
                    if(!empty($v['config']))
                    {
                        // 配置信息处理
                        $v['config'] = self::ConfigViewFieldHandle($v['config']);
                    }

                    // 布局类型
                    $v['value_arr'] = explode(':', $v['value']);

                    // 布局
                    if(!empty($v['children']) && is_array($v['children']))
                    {
                        foreach($v['children'] as &$vs)
                        {
                            // 配置信息处理
                            if(!empty($vs['config']))
                            {
                                // 配置信息处理
                                $vs['config'] = self::ConfigViewFieldHandle($vs['config']);
                            }

                            // 容器
                            if(!empty($vs['children']) && is_array($vs['children']))
                            {
                                // 模块
                                foreach($vs['children'] as &$vss)
                                {
                                    if(!empty($vss['value']) && !empty($vss['config']))
                                    {
                                        // 配置信息处理
                                        $vss['config'] = self::ConfigViewFieldHandle($vss['config']);

                                        // 根据模块类型处理
                                        switch($vss['value'])
                                        {
                                            // 视频 video
                                            case 'video' :
                                                $vss['config']['video'] = ResourcesService::AttachmentPathViewHandle($vss['config']['content_video']);
                                                unset($vss['config']['content_video']);
                                                break;

                                            // 单图 images
                                            case 'images' :
                                                // 配置重新组合
                                                $vss['config'] = [
                                                    'frontend_config'   => $vss['config']['frontend_config'],
                                                    'images'            => ResourcesService::AttachmentPathViewHandle($vss['config']['content_images']),
                                                    'url'               => self::LayoutUrlValueHandle($vss['config']['content_to_type'], $vss['config']['content_to_value']),
                                                ];
                                                break;

                                            // 多图 many-images
                                            case 'many-images' :
                                                foreach($vss['config']['data_list'] as &$mil)
                                                {
                                                    $mil = [
                                                        'images'    => ResourcesService::AttachmentPathViewHandle($mil['images']),
                                                        'url'       => self::LayoutUrlValueHandle($mil['type'], $mil['value']),
                                                    ];
                                                }
                                                break;

                                            // 商品
                                            case 'goods' :
                                                $p = [
                                                    'data_type' => $vss['config']['goods_data_type'],
                                                ];
                                                switch($vss['config']['goods_data_type'])
                                                {
                                                    // 指定商品
                                                    case 'goods' :
                                                        $p['goods_ids'] = $vss['config']['goods_ids'];
                                                        break;

                                                    // 商品分类
                                                    case 'category' :
                                                        $category = json_decode(urldecode($vss['config']['goods_category_value']), true);
                                                        $p['category_id'] = $category[count($category)-1]['id'];
                                                        $p['order_limit_number'] = empty($vss['config']['goods_order_limit_number']) ? 0 : $vss['config']['goods_order_limit_number'];
                                                        break;
                                                }
                                                $res = self::GoodsDataList($p);
                                                $vss['config']['data_list'] = $res['data'];
                                                break;

                                            // 标题
                                            case 'title' :
                                                // 关键字
                                                $keywords_list = [];
                                                if(!empty($vss['config']['keywords_list']))
                                                {
                                                    foreach($vss['config']['keywords_list'] as $wd)
                                                    {
                                                        $keywords_list[] = [
                                                            'keywords'  => $wd['content_keywords'],
                                                            'color'     => empty($wd['style_keywords_color']) ? '' : $wd['style_keywords_color'],
                                                            'url'       => self::LayoutUrlValueHandle($wd['content_to_type'], $wd['content_to_value']),
                                                        ];
                                                    }
                                                }

                                                // 配置重新组合
                                                $vss['config'] = [
                                                    'frontend_config'   => $vss['config']['frontend_config'],
                                                    'title'             => $vss['config']['content_title'],
                                                    'title_vice'        => $vss['config']['content_title_vice'],
                                                    'title_more'        => $vss['config']['content_title_more'],
                                                    'title_more_url'    => self::LayoutUrlValueHandle($vss['config']['content_to_type'], $vss['config']['content_to_value']),
                                                    'keywords_list'     => $keywords_list,
                                                ];
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //print_r($config);die;
        return $config;
    }

    /**
     * 配置信息字段处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [array]          $config [配置信息]
     */
    public static function ConfigViewFieldHandle($config)
    {
        if(!empty($config) && is_array($config))
        {
            // 配置信息多余字段移除
            $arr = [
                'style_',
                'content_item_keywords_',
                'content_images_',
                'content_to_type_',
                'content_to_name_',
                'content_to_value_',
                'view_list_number_',
            ];
            foreach($config as $k=>$v)
            {
                foreach($arr as $f)
                {
                    $length = strlen($f);
                    if(substr($k, 0, $length) == $f)
                    {
                        unset($config[$k]);
                    }
                }
            }

            // 前端配置处理
            $config['frontend_config'] = empty($config['frontend_config']) ? '' : self::FrontendConfigHandle($config['frontend_config']);

            // 滚动配置
            if(array_key_exists('view_list_show_style_value', $config))
            {
                $config['view_list_show_style_value_arr'] = empty($config['view_list_show_style_value']) ? '' : json_decode(urldecode($config['view_list_show_style_value']), true);
                unset($config['view_list_show_style_value']);
            }
        }
        return $config;
    }

    /**
     * 链接地址处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [string]          $type  [类型]
     * @param   [string]          $value [特殊地址配置值]
     */
    public static function LayoutUrlValueHandle($type, $value)
    {
        // 当前客户端类型
        $client_type = APPLICATION_CLIENT_TYPE;

        // url地址、默认空字符串
        $url = '';

        // 静态地址定义-web端
        $static_url_web_arr = [
            'home'                      => __MY_URL__,
            'goods_category'            => MyUrl('index/category/index'),
            'cart'                      => MyUrl('index/cart/index'),
            'user_center'               => MyUrl('index/user/index'),
            'user_order_list'           => MyUrl('index/order/index'),
            'user_order_aftersale_list' => MyUrl('index/orderaftersale/index'),
            'user_goods_favor_list'     => MyUrl('index/usergoodsfavor/index'),
            'user_address_list'         => MyUrl('index/useraddress/index'),
            'user_goods_browse_list'    => MyUrl('index/usergoodsbrowse/index'),
            'user_integral_list'        => MyUrl('index/userintegral/index'),
            'user_answer_list'          => MyUrl('index/answer/index'),
            'user_message_list'         => MyUrl('index/message/index'),
        ];

        // 静态地址定义-手机端
        $static_url_app_arr = [
            'home'                      => '/pages/index/index',
            'goods_category'            => '/pages/goods-category/goods-category',
            'cart'                      => '/pages/cart/cart',
            'user_center'               => '/pages/user/user',
            'user_order_list'           => '/pages/user-order/user-order',
            'user_order_aftersale_list' => '/pages/user-orderaftersale/user-orderaftersale',
            'user_goods_favor_list'     => '/pages/user-faovr/user-faovr',
            'user_address_list'         => '/pages/user-address/user-address',
            'user_goods_browse_list'    => '/pages/user-goods-browse/user-goods-browse',
            'user_integral_list'        => '/pages/user-integral/user-integral',
            'user_answer_list'          => '/pages/user-answer-list/user-answer-list',
            'user_message_list'         => '/pages/message/message',
        ];

        // 静态地址
        $static_url_arr = ($client_type == 'pc') ? $static_url_web_arr : $static_url_app_arr;
        if(array_key_exists($type, $static_url_arr))
        {
            $url = $static_url_arr[$type];
        } else {
            // 扩展参数处理
            if(!empty($value) && !is_array($value))
            {
                $value = json_decode(urldecode($value), true);
            }
            switch($type)
            {
                // 商品
                case 'goods' :
                    if(!empty($value) && !empty($value['id']))
                    {
                        $url = ($client_type == 'pc') ? MyUrl('index/goods/index', ['id'=>$value['id']]) : '/pages/goods-detail/goods-detail?goods_id='.$value['id'];
                    }
                    break;

                // 商品分类
                case 'goods_search' :
                    $gsp = [];
                    if(!empty($value) && !empty($value['type']) && !empty($value['value']))
                    {
                        switch($value['type'])
                        {
                            // 关键字
                            case 'keywords' :
                                $gsp = ($client_type == 'pc') ? ['wd'=>StrToAscii($value['value'])] : '?keywords='.$value['value'];
                                break;

                            // 分类
                            case 'category' :
                                $category_id = $value['value'][count($value['value'])-1]['id'];
                                $gsp = ($client_type == 'pc') ? ['category_id'=>$category_id] : '?category_id='.$category_id;
                                break;

                            // 品牌
                            case 'brand' :
                                $gsp = ($client_type == 'pc') ? ['brand_id'=>$value['value']['id']] : '?brand_id='.$value['value']['id'];
                                break;
                        }
                    }
                    // 默认搜索页面、无条件
                    $url = ($client_type == 'pc') ? MyUrl('index/search/index', $gsp) : '/pages/goods-search/goods-search'.(empty($gsp) ? '' : $gsp);
                    break;
            }
        }
        return $url;
    }

    /**
     * 前端配置处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-18
     * @desc    description
     * @param   [array]          $data [配偶者数据]
     */
    public static function FrontendConfigHandle($data)
    {
        if(!empty($data) && is_array($data))
        {
            foreach($data as &$v)
            {
                if(is_array($v))
                {
                    foreach($v as &$vs)
                    {
                        $vs = empty($vs) ? '' : urldecode($vs);
                    }
                } else {
                    $v = empty($v) ? '' : urldecode($v);
                }
            }
        }
        return $data;
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsSearchList($params = [])
    {
        // 返回数据
        $result = [
            'page_total'    => 0,
            'page_size'     => 20,
            'page'          => max(1, isset($params['page']) ? intval($params['page']) : 1),
            'total'         => 0,
            'data'          => [],
        ];

        // 条件
        $where = [
            ['g.is_delete_time', '=', 0],
            ['g.is_shelves', '=', 1],
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['g.title', 'like', '%'.$params['keywords'].'%'];
        }

        // 数据分类id
        if(!empty($params['category_id']))
        {
            // 默认系统商品分类，并读取分类子级
            $category_field = empty($params['category_field']) ? 'gci.category_id' : $params['category_field'];
            if($category_field == 'gci.category_id')
            {
                $category_id = GoodsService::GoodsCategoryItemsIds([intval($params['category_id'])]);
            } else {
                $category_id = [intval($params['category_id'])];
            }
            $where[] = [$category_field, 'in', $category_id];
        }

        // 获取商品总数
        $result['total'] = GoodsService::CategoryGoodsTotal($where);

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 基础参数
            $field = 'g.id,g.title,g.images';
            $order_by = 'g.id desc';

            // 分页计算
            $m = intval(($result['page']-1)*$result['page_size']);
            $ret = GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>$m, 'n'=>$result['page_size'], 'field'=>$field, 'order_by'=>$order_by]);
            $result['data'] = $ret['data'];
            $result['page_total'] = ceil($result['total']/$result['page_size']);
             // 数据处理
            if(!empty($result['data']) && is_array($result['data']) && !empty($params['goods_ids']))
            {
                $goods_ids = is_array($params['goods_ids']) ? $params['goods_ids'] : explode(',', $params['goods_ids']);
                foreach($result['data'] as &$v)
                {
                    // 是否已添加
                    $v['is_exist'] = in_array($v['id'], $goods_ids) ? 1 : 0;
                }
            }
        }
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 商品数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsDataList($params = [])
    {
        // 数据类型、默认商品
        $data_type = empty($params['data_type']) ? 'goods' : $params['data_type'];
        switch($data_type)
        {
            // 商品
            case 'goods' :
                // 参数处理
                if(empty($params['goods_ids']))
                {
                    return DataReturn('商品id为空', -1);
                }
                if(!is_array($params['goods_ids']))
                {
                    $params['goods_ids'] = explode(',', $params['goods_ids']);
                }

                // 读取数量
                $m = 0;
                $n = 50;

                // 获取商品
                $order_by = 'id desc';
                $field = 'id,title,images,price,original_price,min_price,max_price,min_original_price,max_original_price,inventory,inventory_unit';
                $where = [
                    ['is_delete_time', '=', 0],
                    ['is_shelves', '=', 1],
                    ['id', 'in', $params['goods_ids']],
                ];
                break;

            // 商品分类
            case 'category' :
                // 参数处理
                if(empty($params['category_id']))
                {
                    return DataReturn('商品分类id为空', -1);
                }

                // 排序处理
                $order_by_type_list = lang('goods_order_by_type_list');
                $order_by_rule_list = lang('goods_order_by_rule_list');

                // 排序类型
                $order_by_type = empty($params['order_by_type']) ? $order_by_type_list[0]['value'] : (array_key_exists($params['order_by_type'], $order_by_type_list) ? $order_by_type_list[$params['order_by_type']]['value'] : $order_by_type_list[0]['value']);

                // 排序规则
                $order_by_rule = empty($params['order_by_rule']) ? $order_by_rule_list[0]['value'] : (array_key_exists($params['order_by_rule'], $order_by_rule_list) ? $order_by_rule_list[$params['order_by_rule']]['value'] : $order_by_rule_list[0]['value']);

                // 读取数量
                $m = 0;
                $n = min(empty($params['order_limit_number']) ? 50 : intval($params['order_limit_number']), 50);

                // 获取商品
                $order_by = $order_by_type.' '.$order_by_rule;
                $field = 'g.id,g.title,g.images,g.price,g.original_price,g.min_price,g.max_price,g.min_original_price,g.max_original_price,g.inventory,g.inventory_unit';
                $where = [
                    ['gci.category_id', 'in', GoodsService::GoodsCategoryItemsIds([intval($params['category_id'])])],
                    ['g.is_delete_time', '=', 0],
                    ['g.is_shelves', '=', 1],
                ];
                break;

            default :
                return DataReturn('数据类型未处理['.$params['data_type'].']', -1);
        }

        // 商品搜索列表读取钩子
        $hook_name = 'plugins_layout_service_search_goods_begin';
        Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data_type'     => $data_type,
            'where'         => &$where,
            'field'         => &$field,
            'm'             => &$m,
            'n'             => &$n,
            'order_by'      => &$order_by,
        ]);

        // 根据请求类型处理数据读取
        switch($data_type)
        {
            // 商品
            case 'goods' :
                $request_params = [
                    'where'     => $where,
                    'm'         => $m,
                    'n'         => $n,
                    'field'     => $field,
                    'order_by'  => $order_by,
                ];
                $ret = GoodsService::GoodsList($request_params);
                break;

            // 商品分类
            case 'category' :
                $ret = GoodsService::GoodsDataHandle(Db::name('Goods')->alias('g')->join(['__GOODS_CATEGORY_JOIN__'=>'gci'], 'g.id=gci.goods_id')->field($field)->where($where)->group('g.id')->order($order_by)->limit($m, $n)->select());
                break;
        }
        if(!empty($ret) && isset($ret['code']) && $ret['code'] == 0 && !empty($ret['data']))
        {
            return $ret;
        }
        return DataReturn('无商品信息', -1);
    }

    /**
     * 页面数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-05-14
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PagesList($params = [])
    {
        return [
            // 公共
            'common' => [
                'name'  => '系统页面',
                'data'  => [
                    [
                        'value' => 'home',
                        'name'  => '商城首页',
                    ],
                    [
                        'value' => 'goods_category',
                        'name'  => '全部商品分类',
                    ],
                    [
                        'value' => 'goods_search',
                        'name'  => '商品搜索',
                        'tips'  => '可带参数（关键字、商品分类id、品牌id）',
                    ],
                    [
                        'value' => 'goods',
                        'name'  => '单一商品',
                    ],
                    [
                        'value' => 'cart',
                        'name'  => '购物车',
                    ],
                    [
                        'value' => 'user_center',
                        'name'  => '用户中心',
                    ],
                    [
                        'value' => 'user_order_list',
                        'name'  => '我的订单',
                    ],
                    [
                        'value' => 'user_order_aftersale_list',
                        'name'  => '订单售后',
                    ],
                    [
                        'value' => 'user_goods_favor_list',
                        'name'  => '商品收藏',
                    ],
                    [
                        'value' => 'user_address_list',
                        'name'  => '我的地址',
                    ],
                    [
                        'value' => 'user_goods_browse_list',
                        'name'  => '我的足迹',
                    ],
                    [
                        'value' => 'user_integral_list',
                        'name'  => '我的积分',
                    ],
                    [
                        'value' => 'user_message_list',
                        'name'  => '我的消息',
                    ],
                    [
                        'value' => 'user_answer_list',
                        'name'  => '问答/留言',
                    ],
                ],
            ],

            // 插件
            'plugins' => [
                'name'  => '扩展模块',
                'data'  => [
                    [
                        'name'  => '多商户',
                        'value' => 'shop',
                        'tips'  => '暂时不支持小程序',
                        'data'  => [
                            [
                                'value' => 'home',
                                'name'  => '店铺首页',
                            ],
                            [
                                'value' => 'goods_category',
                                'name'  => '商品分类',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
?>