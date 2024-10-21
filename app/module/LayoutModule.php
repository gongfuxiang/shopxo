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
namespace app\module;

use think\facade\Db;
use app\service\ResourcesService;
use app\service\GoodsService;
use app\service\GoodsCategoryService;

/**
 * 布局自动化服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-05-25
 * @desc    description
 */
class LayoutModule
{
    /**
     * 静态配置数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-03-23
     * @desc    description
     * @param   [string]          $key [配置key]
     */
    public static function ConstData($key)
    {
        $lang_border = MyLang('layout.border_style_type_list');
        $lang_goods_view = MyLang('layout.goods_view_list_show_style');
        $lang_many_images_view = MyLang('layout.many_images_view_list_show_style');
        $lang_images_text_view = MyLang('layout.images_text_view_list_show_style');
        $lang_images_magic_cube_view = MyLang('layout.images_magic_cube_view_list_show_style');
        $data = [
            // 边线样式类型
            'border_style_type_list' => [
                'solid'     => $lang_border['solid'],
                'dashed'    => $lang_border['dashed'],
                'dotted'    => $lang_border['dotted'],
                'double'    => $lang_border['double'],
            ],
            // 商品样式类型
            'goods_view_list_show_style' => [
                'routine'   => $lang_goods_view['routine'],
                'leftright' => $lang_goods_view['leftright'],
                'rolling'   => $lang_goods_view['rolling'],
            ],
            // 多图样式类型
            'many_images_view_list_show_style' => [
                'routine'   => $lang_many_images_view['routine'],
                'rolling'   => $lang_many_images_view['rolling'],
                'list'      => $lang_many_images_view['list'],
            ],
            // 图文样式类型
            'images_text_view_list_show_style' => [
                'updown'    => $lang_images_text_view['updown'],
                'leftright' => $lang_images_text_view['leftright'],
                'rolling'   => $lang_images_text_view['rolling'],
            ],
            // 图片魔方样式类型
            'images_magic_cube_view_list_show_style' => [
                'g1'    => $lang_images_magic_cube_view['g1'],
                'v2'    => $lang_images_magic_cube_view['v2'],
                'v3'    => $lang_images_magic_cube_view['v3'],
                'v4'    => $lang_images_magic_cube_view['v4'],
                'h2'    => $lang_images_magic_cube_view['h2'],
                'h3'    => $lang_images_magic_cube_view['h4'],
                'h4'    => $lang_images_magic_cube_view['lr12'],
                'lr12'  => $lang_images_magic_cube_view['lr13'],
                'lr13'  => $lang_images_magic_cube_view['lr13'],
                'lr21'  => $lang_images_magic_cube_view['lr21'],
                'lr31'  => $lang_images_magic_cube_view['lr31'],
                'tb12'  => $lang_images_magic_cube_view['tb12'],
                'tb13'  => $lang_images_magic_cube_view['tb13'],
                'tb21'  => $lang_images_magic_cube_view['tb21'],
                'tb31'  => $lang_images_magic_cube_view['tb31'],
                'lrv2h2'=> $lang_images_magic_cube_view['lrv2h2'],
                'lrh2v2'=> $lang_images_magic_cube_view['lrh2v2'],
                'g4'    => $lang_images_magic_cube_view['g4'],
            ],
        ];
        return isset($data[$key]) ? $data[$key] : [];
    }

    /**
     * 配置处理-保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-17
     * @desc    description
     * @param   [array]          $config [配置信息]
     * @param   [array]          $params [输入参数]
     */
    public static function ConfigSaveHandle($config, $params = [])
    {
        $config = empty($config) ? [] : (is_array($config) ? $config : json_decode(htmlspecialchars_decode($config), true));
        if(!empty($config) && is_array($config))
        {
            foreach($config as &$v)
            {
                // 配置信息处理
                if(!empty($v['config']))
                {
                    $v['config'] = self::ConfigSaveFieldHandle($v['config']);
                }

                // 布局
                if(!empty($v['children']) && is_array($v['children']))
                {
                    foreach($v['children'] as &$vs)
                    {
                        // 配置信息处理
                        if(!empty($vs['config']))
                        {
                            $vs['config'] = self::ConfigSaveFieldHandle($vs['config']);
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
                                    $vss['config'] = self::ConfigSaveFieldHandle($vss['config']);

                                    // 根据模块类型处理
                                    switch($vss['value'])
                                    {
                                        // 视频 video
                                        case 'video' :
                                            $vss['config']['content_video'] = ResourcesService::AttachmentPathHandle($vss['config']['content_video']);
                                            break;

                                        // 单图 images
                                        case 'images' :
                                            $vss['config']['content_images'] = ResourcesService::AttachmentPathHandle($vss['config']['content_images']);
                                            break;

                                        // 多图 many-images
                                        case 'many-images' :
                                            foreach($vss['config']['data_list'] as &$mil)
                                            {
                                                $mil['images'] = ResourcesService::AttachmentPathHandle($mil['images']);
                                            }
                                            $key = 'content_images_';
                                            foreach($vss['config'] as $mik=>$miv)
                                            {
                                                if(substr($mik, 0, strlen($key)) == $key)
                                                {
                                                    $vss['config'][$mik] = ResourcesService::AttachmentPathHandle($miv);
                                                }
                                            }
                                            break;

                                        // 图文 images-text
                                        case 'images-text' :
                                            foreach($vss['config']['data_list'] as &$itl)
                                            {
                                                $itl['images'] = ResourcesService::AttachmentPathHandle($itl['images']);
                                            }
                                            $key = 'content_images_';
                                            foreach($vss['config'] as $mik=>$miv)
                                            {
                                                if(substr($mik, 0, strlen($key)) == $key)
                                                {
                                                    $vss['config'][$mik] = ResourcesService::AttachmentPathHandle($miv);
                                                }
                                            }
                                            break;

                                        // 图片魔方 images-magic-cube
                                        case 'images-magic-cube' :
                                            foreach($vss['config']['data_list'] as &$imc)
                                            {
                                                $imc['images'] = ResourcesService::AttachmentPathHandle($imc['images']);
                                            }
                                            $key = 'content_images_';
                                            foreach($vss['config'] as $mik=>$miv)
                                            {
                                                if(substr($mik, 0, strlen($key)) == $key)
                                                {
                                                    $vss['config'][$mik] = ResourcesService::AttachmentPathHandle($miv);
                                                }
                                            }
                                            break;

                                        // 商品 goods
                                        case 'goods' :
                                            unset($vss['config']['data_list']);
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return empty($config) ? '' : json_encode($config, JSON_UNESCAPED_UNICODE);
    }

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

                                            // 图文 images-text
                                            case 'images-text' :
                                                foreach($vss['config']['data_list'] as &$itl)
                                                {
                                                    $itl['images'] = ResourcesService::AttachmentPathViewHandle($itl['images']);
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

                                            // 图片魔方 images-magic-cube
                                            case 'images-magic-cube' :
                                                foreach($vss['config']['data_list'] as &$imc)
                                                {
                                                    $imc['images'] = ResourcesService::AttachmentPathViewHandle($imc['images']);
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
                                                        $p['order_by_type'] = isset($vss['config']['goods_order_by_type']) ? $vss['config']['goods_order_by_type'] : 0;
                                                        $p['order_by_rule'] = isset($vss['config']['goods_order_by_rule']) ? $vss['config']['goods_order_by_rule'] : 0;
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
                        $v['config'] = self::ConfigViewFieldHandle($v['config'], true);
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
                                $vs['config'] = self::ConfigViewFieldHandle($vs['config'], true);
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
                                        $vss['config'] = self::ConfigViewFieldHandle($vss['config'], true);

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

                                            // 图文 images-text
                                            case 'images-text' :
                                                foreach($vss['config']['data_list'] as &$itl)
                                                {
                                                    $itl['images'] = ResourcesService::AttachmentPathViewHandle($itl['images']);
                                                    $itl['url'] = self::LayoutUrlValueHandle($itl['type'], $itl['value']);
                                                }
                                                break;

                                            // 图片魔方 images-magic-cube
                                            case 'images-magic-cube' :
                                                foreach($vss['config']['data_list'] as &$imc)
                                                {
                                                    $imc['images'] = ResourcesService::AttachmentPathViewHandle($imc['images']);
                                                    $imc['url'] = self::LayoutUrlValueHandle($imc['type'], $imc['value']);
                                                }
                                                break;

                                            // 商品
                                            case 'goods' :
                                                $p = [
                                                    'data_type'     => $vss['config']['goods_data_type'],
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
                                                        $p['order_by_type'] = isset($vss['config']['goods_order_by_type']) ? $vss['config']['goods_order_by_type'] : 0;
                                                        $p['order_by_rule'] = isset($vss['config']['goods_order_by_rule']) ? $vss['config']['goods_order_by_rule'] : 0;
                                                        break;
                                                }
                                                $res = self::GoodsDataList($p);
                                                if(!empty($res['data']) && is_array($res['data']))
                                                {
                                                    foreach($res['data'] as &$g)
                                                    {
                                                        $g['goods_url'] = self::LayoutUrlValueHandle('goods', $g);
                                                    }
                                                }
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

                                            // 自定义html
                                            case 'custom' :
                                                $vss['config']['custom'] = empty($vss['config']['custom']) ? '' : htmlspecialchars_decode($vss['config']['custom']);
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
        return $config;
    }

    /**
     * 配置信息字段保存处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [array]          $config [配置信息]
     */
    public static function ConfigSaveFieldHandle($config)
    {
        if(!empty($config) && is_array($config))
        {
            // 背景图片地址
            $fields = ['style_background_images'];
            foreach($fields as $v)
            {
                if(!empty($config[$v]))
                {
                    $config[$v] = ResourcesService::AttachmentPathHandle($config[$v]);
                }
            }
        }
        return $config;
    }

    /**
     * 配置信息字段展示处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-22
     * @desc    description
     * @param   [array]          $config                [配置信息]
     * @param   [boolean]        $is_del_surplus_field  [移除多余字段]
     */
    public static function ConfigViewFieldHandle($config, $is_del_surplus_field = false)
    {
        if(!empty($config) && is_array($config))
        {
            // 滚动配置
            if(array_key_exists('view_list_show_style_value', $config))
            {
                $config['view_list_show_style_value_arr'] = empty($config['view_list_show_style_value']) ? '' : json_decode(urldecode($config['view_list_show_style_value']), true);
            }

            // 附件
            $attachment_fields = ['style_background_images'];
            foreach($attachment_fields as $av)
            {
                if(!empty($config[$av]))
                {
                    $config[$av] = ResourcesService::AttachmentPathViewHandle($config[$av]);
                }
            }

            // 配置信息多余字段移除
            if($is_del_surplus_field)
            {
                $fields = [
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
                    if(!in_array($k, $attachment_fields))
                    {
                        foreach($fields as $f)
                        {
                            $length = strlen($f);
                            if(substr($k, 0, $length) == $f)
                            {
                                unset($config[$k]);
                            }
                        }
                    }
                }
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
        // 扩展参数处理
        if(!empty($value) && !is_array($value))
        {
            $value = json_decode(urldecode($value), true);
        }

        // 当前客户端类型
        $client_type = APPLICATION_CLIENT_TYPE;

        // url地址、默认空字符串
        $url = '';

        // 静态地址定义-web端
        $static_url_web_arr = [
            'home'                                      => __MY_URL__,
            'goods_category'                            => MyUrl('index/category/index'),
            'cart'                                      => MyUrl('index/cart/index'),
            'user_center'                               => MyUrl('index/user/index'),
            'user_order_list'                           => MyUrl('index/order/index'),
            'user_order_aftersale_list'                 => MyUrl('index/orderaftersale/index'),
            'user_goods_favor_list'                     => MyUrl('index/usergoodsfavor/index'),
            'user_address_list'                         => MyUrl('index/useraddress/index'),
            'user_goods_browse_list'                    => MyUrl('index/usergoodsbrowse/index'),
            'user_integral_list'                        => MyUrl('index/userintegral/index'),
            'user_message_list'                         => MyUrl('index/message/index'),
        ];

        // 静态地址定义-手机端
        $static_url_app_arr = [
            'home'                                      => '/pages/index/index',
            'goods_category'                            => '/pages/goods-category/goods-category',
            'cart'                                      => '/pages/cart/cart',
            'user_center'                               => '/pages/user/user',
            'user_order_list'                           => '/pages/user-order/user-order',
            'user_order_aftersale_list'                 => '/pages/user-orderaftersale/user-orderaftersale',
            'user_goods_favor_list'                     => '/pages/user-favor/user-favor',
            'user_address_list'                         => '/pages/user-address/user-address',
            'user_goods_browse_list'                    => '/pages/user-goods-browse/user-goods-browse',
            'user_integral_list'                        => '/pages/user-integral/user-integral',
            'user_message_list'                         => '/pages/message/message',
        ];

        // url值处理前钩子
        $hook_name = 'plugins_layout_service_url_value_begin';
        MyEventTrigger($hook_name, [
            'hook_name'           => $hook_name,
            'is_backend'          => true,
            'type'                => $type,
            'value'               => $value,
            'client_type'         => $client_type,
            'static_url_web_arr'  => &$static_url_web_arr,
            'static_url_app_arr'  => &$static_url_app_arr,
        ]);

        // 静态地址
        $url = '';
        $static_url_arr = ($client_type == 'pc') ? $static_url_web_arr : $static_url_app_arr;
        if(array_key_exists($type, $static_url_arr))
        {
            $url = $static_url_arr[$type];
        } else {
            switch($type)
            {
                // 商品
                case 'goods' :
                    if(!empty($value) && !empty($value['id']))
                    {
                        // 商品处理
                        $res = GoodsService::GoodsDataHandle([['goods_id'=>$value['id']]], ['data_key_field'=>'goods_id']);
                        if(!empty($res['data']) && !empty($res['data'][0]) && !empty($res['data'][0]['goods_url']))
                        {
                            $url = $res['data'][0]['goods_url'];
                        }
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
                                $cid = $value['value'][count($value['value'])-1]['id'];
                                $gsp = ($client_type == 'pc') ? ['cid'=>$cid] : '?category_id='.$cid;
                                break;

                            // 品牌
                            case 'brand' :
                                $gsp = ($client_type == 'pc') ? ['brand'=>$value['value']['id']] : '?brand='.$value['value']['id'];
                                break;
                        }
                    }
                    // 默认搜索页面、无条件
                    $url = ($client_type == 'pc') ? MyUrl('index/search/index', $gsp) : '/pages/goods-search/goods-search'.(empty($gsp) ? '' : $gsp);
                    break;

                // 自定义链接
                case 'pages-custom-url' :
                    $key = 'pages_custom_url_'.$client_type;
                    if(!empty($value) && is_array($value) && array_key_exists($key, $value) && !empty($value[$key]))
                    {
                        $url = htmlspecialchars_decode($value[$key]);
                    }
                    break;
            }
        }

        // url值处理钩子
        $hook_name = 'plugins_layout_service_url_value_handle';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'type'          => $type,
            'value'         => $value,
            'client_type'   => $client_type,
            'url'           => &$url,
        ]);

        // 返回url
        return $url;
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
                $category_id = GoodsCategoryService::GoodsCategoryItemsIds([intval($params['category_id'])]);
            } else {
                $category_id = [intval($params['category_id'])];
            }
            $where[] = [$category_field, 'in', $category_id];
        }

        // 商品搜索列表读取钩子
        $hook_name = 'plugins_layout_service_search_goods_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'where'         => &$where,
        ]);

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
        return DataReturn(MyLang('handle_success'), 0, $result);
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
                    return DataReturn(MyLang('layout.base_goods_id_empty_tips'), -1);
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
                    return DataReturn(MyLang('layout.base_goods_category_empty_tips'), -1);
                }

                // 排序处理
                $order_by_type_list = MyConst('common_goods_order_by_type_list');
                $order_by_rule_list = MyConst('common_data_order_by_rule_list');

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
                    ['gci.category_id', 'in', GoodsCategoryService::GoodsCategoryItemsIds([intval($params['category_id'])])],
                    ['g.is_delete_time', '=', 0],
                    ['g.is_shelves', '=', 1],
                ];
                break;

            default :
                return DataReturn(MyLang('layout.base_data_type_not_handle_tips').'['.$params['data_type'].']', -1);
        }

        // 商品数据列表读取钩子
        $hook_name = 'plugins_layout_service_goods_data_begin';
        MyEventTrigger($hook_name, [
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
                if(!empty($ret['data']))
                {
                    // 商品自定义按照顺序重新调整
                    $goods = [];
                    $temp = array_column($ret['data'], null, 'id');
                    foreach($params['goods_ids'] as $v)
                    {
                        if(array_key_exists($v, $temp))
                        {
                            $goods[] = $temp[$v];
                        }
                    }
                    $ret['data'] = $goods;
                }
                break;

            // 商品分类
            case 'category' :
                $request_params = [
                    'where'     => $where,
                    'm'         => $m,
                    'n'         => $n,
                    'field'     => $field,
                    'order_by'  => $order_by,
                ];
                $ret = GoodsService::CategoryGoodsList($request_params);
                break;
        }
        if(!empty($ret) && isset($ret['code']) && $ret['code'] == 0 && !empty($ret['data']))
        {
            return $ret;
        }
        return DataReturn(MyLang('layout.base_goods_empty_tips'), -1);
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
        // 返回页面数据定义
        $data = [
            // 公共
            'common' => [
                'name'  => MyLang('layout.page_system_title'),
                'data'  => [
                    [ 'value' => 'home', 'name' => MyLang('shop_home_title')],
                    [ 'value' => 'goods_category', 'name' => MyLang('layout.page_goods_category')],
                    [ 'value' => 'goods_search', 'name' => MyLang('layout.page_goods_search'), 'tips' => MyLang('layout.page_goods_search_tips')],
                    [ 'value' => 'goods', 'name' => MyLang('layout.page_goods')],
                    [ 'value' => 'cart', 'name' => MyLang('layout.page_cart')],
                    [ 'value' => 'user_center', 'name' => MyLang('layout.page_user_center')],
                    [ 'value' => 'user_order_list', 'name' => MyLang('layout.page_user_order_list')],
                    [ 'value' => 'user_order_aftersale_list', 'name' => MyLang('layout.page_user_order_aftersale_list')],
                    [ 'value' => 'user_goods_favor_list', 'name' => MyLang('layout.page_user_goods_favor_list')],
                    [ 'value' => 'user_address_list', 'name' => MyLang('layout.page_user_address_list')],
                    [ 'value' => 'user_goods_browse_list', 'name' => MyLang('layout.page_user_goods_browse_list')],
                    [ 'value' => 'user_integral_list', 'name' => MyLang('layout.page_user_integral_list')],
                    [ 'value' => 'user_message_list', 'name' => MyLang('layout.page_user_message_list')],
                ],
            ],

            // 插件
            'plugins' => [
                'name'  => MyLang('layout.page_plugins_title'),
                'data'  => [],
            ],
        ];

        // 页面列表钩子
        $hook_name = 'plugins_layout_service_pages_list';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => $params,
            'data'          => &$data,
        ]);

        // 返回页面数据
        return $data;
    }
}
?>