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
use app\service\ResourcesService;
use app\service\AttachmentService;
use app\service\ConfigService;
use app\service\StoreService;
use app\service\GoodsService;
use app\service\GoodsCategoryService;
use app\service\ArticleService;
use app\service\SystemService;
use app\service\AdminService;

/**
 * 主题数据服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ThemeDataService
{
    // 排除的文件后缀
    private static $exclude_ext = ['php'];

    /**
     * 主题数据管理数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-02
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeDataAdminData($params = [])
    {
        // 指定了就存储session、则从session读取
        $status_key = 'user_is_theme_data_admin_sttaus_'.$params['default_theme'];
        if(isset($params['is_theme_data_admin']))
        {
            $status = intval($params['is_theme_data_admin']);
            MySession($status_key, $status);
        } else {
            $status = MySession($status_key);
            if($status === null)
            {
                $status = 0;
            }
        }
        if($status == 1)
        {
            $admin = AdminService::LoginInfo();
            if(empty($admin))
            {
                $status = 0;
            }
        }

        // 管理url地址
        $admin_key = 'user_is_theme_data_admin_url_data_'.$params['default_theme'];
        if(empty($params['admin_url_data']))
        {
            $admin_url_data = MySession($admin_key);
        } else {
            $admin_url_data = json_decode(base64_decode(urldecode($params['admin_url_data'])), true);
            MySession($admin_key, $admin_url_data);
        }
        return [
            'status'          => $status,
            'admin_url_data'  => $admin_url_data,
        ];
    }

    /**
     * 主题数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-02
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeData($params = [])
    {
        $result = [];
        if(!empty($params['mca']) && !empty($params['default_theme']))
        {
            // 页面条件、默认读取没有指定页面的数据
            $view = [-1];

            // 页面条件
            $theme_view_list = MyConst('common_theme_view_list');
            if(!empty($theme_view_list) && is_array($theme_view_list))
            {
                $theme_view_list = array_column($theme_view_list, 'value', 'type');
                if(array_key_exists($params['mca'], $theme_view_list))
                {
                    $view[] = $theme_view_list[$params['mca']];
                }
            }

            // 条件
            $where = [
                ['is_enable', '=', 1],
                ['view', 'in', $view],
                ['theme', '=', $params['default_theme']]
            ];
            $result = array_column(self::ThemeDataListHandle(Db::name('ThemeData')->where($where)->field('id,unique,name,theme,type,data')->select()->toArray(), ['is_view'=>1]), null, 'unique');
        }
        return $result;
    }

    /**
     * 列表数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     * @param   [array]          $data   [列表数据]
     * @param   [array]          $params [输入参数]
     */
    public static function ThemeDataListHandle($data, $params = [])
    {
        if(!empty($data) && is_array($data))
        {
            // 指定的商品和文章
            $appoint = self::ListAppointGoodsArticleData($data);

            // 当前站点地址
            $domain_url = SystemService::DomainUrl();

            // 是否页面展示读取
            $is_view = isset($params['is_view']) && $params['is_view'] == 1;

            // 循环处理数据
            foreach($data as &$v)
            {
                // 配置数据
                if(!empty($v['data']))
                {
                    // 非数组则解析json
                    if(!is_array($v['data']))
                    {
                        $v['data'] = json_decode($v['data'], true);
                    }
                    if(is_array($v['data']) && isset($v['type']))
                    {
                        // 配置数据处理
                        foreach($v['data'] as $ks=>$vs)
                        {
                            // 当前url地址（当前所有数据类型都有基础标题的数据）
                            if(isset($vs['url_data']))
                            {
                                $vs['url'] = self::UrlHandle($domain_url, $vs['url_data']);
                            }

                            // 附件url地址处理
                            if(substr($ks, 0, 7) == 'images_' && isset($vs['value']))
                            {
                                $vs['value'] = ResourcesService::AttachmentPathViewHandle($vs['value']);
                            }

                            // 不同数据类型处理
                            switch($v['type'])
                            {
                                // 单图文
                                case 0 :
                                    // 自定义数据
                                    if($ks == 'custom_data' && !empty($vs) && is_array($vs))
                                    {
                                        foreach($vs as $kss=>$vss)
                                        {
                                            // 图标
                                            $vs[$kss]['icon'] = empty($vss['icon']) ? '' : ResourcesService::AttachmentPathViewHandle($vss['icon']);
                                            // 当前url地址
                                            $vs[$kss]['url'] = empty($vss['url_data']) ? '' : self::UrlHandle($domain_url, $vss['url_data']);
                                        }
                                    }
                                    break;

                                // 多图文
                                case 1 :
                                    if($ks == 'data' && !empty($vs) && is_array($vs))
                                    {
                                        foreach($vs as $kss=>$vss)
                                        {
                                            if(!empty($vss) && is_array($vss))
                                            {
                                                foreach($vss as $ksss=>$vsss)
                                                {
                                                    // 附件url地址处理
                                                    if(substr($ksss, 0, 7) == 'images_' && isset($vsss['value']))
                                                    {
                                                        $vs[$kss][$ksss]['value'] = ResourcesService::AttachmentPathViewHandle($vsss['value']);
                                                    }

                                                    // 当前url地址
                                                    if(isset($vsss['url_data']))
                                                    {
                                                        $vs[$kss][$ksss]['url'] = self::UrlHandle($domain_url, $vsss['url_data']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    break;

                                // 商品
                                case 3 :
                                    if(isset($v['data']['goods_data_type']) && $v['data']['goods_data_type'] == 1)
                                    {
                                        if($ks == 'goods_data' && !empty($vs) && is_array($vs))
                                        {
                                            foreach($vs as $kss=>$vss)
                                            {
                                                // 处理商品自定义封面图片
                                                if(!empty($vss['custom_cover']))
                                                {
                                                    $vs[$kss]['custom_cover'] = ResourcesService::AttachmentPathViewHandle($vss['custom_cover']);
                                                }

                                                // 商品
                                                if(!empty($appoint['goods_data']) && !empty($vss['data_id']) && array_key_exists($vss['data_id'], $appoint['goods_data']))
                                                {
                                                    // 商品数据合并
                                                    $vs[$kss] = array_merge($appoint['goods_data'][$vss['data_id']], $vs[$kss]);
                                                    // 是否页面展示
                                                    if($is_view && !empty($vs[$kss]['custom_cover']))
                                                    {
                                                        $vs[$kss]['images'] = $vs[$kss]['custom_cover'];
                                                    }
                                                } else {
                                                    // 不存在对应的数据则删除
                                                    unset($vs[$kss]);
                                                }
                                            }
                                            $vs = array_values($vs);
                                        }
                                    }
                                    break;

                                // 文章
                                case 4 :
                                    if(isset($v['data']['article_data_type']) && $v['data']['article_data_type'] == 1)
                                    {
                                        if($ks == 'article_data' && !empty($vs) && is_array($vs))
                                        {
                                            foreach($vs as $kss=>$vss)
                                            {
                                                // 处理文章自定义封面图片
                                                if(!empty($vss['custom_cover']))
                                                {
                                                    $vs[$kss]['custom_cover'] = ResourcesService::AttachmentPathViewHandle($vss['custom_cover']);
                                                }

                                                // 文章
                                                if(!empty($appoint['article_data']) && !empty($vss['data_id']) && array_key_exists($vss['data_id'], $appoint['article_data']))
                                                {
                                                    // 文章数据合并
                                                    $vs[$kss] = array_merge($appoint['article_data'][$vss['data_id']], $vs[$kss]);
                                                    // 是否页面展示
                                                    if($is_view && !empty($vs[$kss]['custom_cover']))
                                                    {
                                                        $vs[$kss]['cover'] = $vs[$kss]['custom_cover'];
                                                    }
                                                } else {
                                                    // 不存在对应的数据则删除
                                                    unset($vs[$kss]);
                                                }
                                            }
                                            $vs = array_values($vs);
                                        }
                                    }
                                    break;

                                // 商品组
                                case 5 :
                                    if($ks == 'data' && !empty($vs) && is_array($vs))
                                    {
                                        foreach($vs as $kss=>$vss)
                                        {
                                            if(!empty($vss) && is_array($vss))
                                            {
                                                foreach($vss as $ksss=>$vsss)
                                                {
                                                    // 当前url地址（当前所有数据类型都有基础标题的数据）
                                                    if(isset($vsss['url_data']))
                                                    {
                                                        $vs[$kss][$ksss]['url'] = self::UrlHandle($domain_url, $vsss['url_data']);
                                                    }

                                                    // 附件url地址处理
                                                    if(substr($ksss, 0, 7) == 'images_' && isset($vsss['value']))
                                                    {
                                                        $vs[$kss][$ksss]['value'] = ResourcesService::AttachmentPathViewHandle($vsss['value']);
                                                    }
                                                }
                                            }

                                            // 手动指定的商品
                                            if(isset($vss['goods_data_type']) && $vss['goods_data_type'] == 1 && !empty($vss['goods_data']) && is_array($vss['goods_data']))
                                            {
                                                foreach($vss['goods_data'] as $ksss=>$vsss)
                                                {
                                                    // 处理商品自定义封面图片
                                                    if(!empty($vsss['custom_cover']))
                                                    {
                                                        $vs[$kss]['goods_data'][$ksss]['custom_cover'] = ResourcesService::AttachmentPathViewHandle($vsss['custom_cover']);
                                                    }

                                                    // 商品
                                                    if(!empty($appoint['goods_data']) && !empty($vsss['data_id']) && array_key_exists($vsss['data_id'], $appoint['goods_data']))
                                                    {
                                                        // 商品数据合并
                                                        $vs[$kss]['goods_data'][$ksss] = array_merge($appoint['goods_data'][$vsss['data_id']], $vs[$kss]['goods_data'][$ksss]);
                                                        // 是否页面展示
                                                        if($is_view && !empty($vs[$kss]['goods_data'][$ksss]['custom_cover']))
                                                        {
                                                            $vs[$kss]['goods_data'][$ksss]['images'] = $vs[$kss]['goods_data'][$ksss]['custom_cover'];
                                                        }
                                                    } else {
                                                        // 不存在对应的数据则删除
                                                        unset($vs[$kss]['goods_data'][$ksss]);
                                                    }
                                                }
                                                $vs[$kss]['goods_data'] = array_values($vs[$kss]['goods_data']);
                                            } else {
                                                $vs[$kss]['goods_data'] = [];
                                            }
                                            // 自动读取商品
                                            if(!isset($vss['goods_data_type']) || $vss['goods_data_type'] == 0)
                                            {
                                                $vs[$kss]['goods_data'] = self::AutoGoodsList($vss);
                                            }
                                        }
                                    }
                                    break;

                                // 文章组
                                case 6 :
                                    if($ks == 'data' && !empty($vs) && is_array($vs))
                                    {
                                        foreach($vs as $kss=>$vss)
                                        {
                                            if(!empty($vss) && is_array($vss))
                                            {
                                                foreach($vss as $ksss=>$vsss)
                                                {
                                                    // 当前url地址（当前所有数据类型都有基础标题的数据）
                                                    if(isset($vsss['url_data']))
                                                    {
                                                        $vs[$kss][$ksss]['url'] = self::UrlHandle($domain_url, $vsss['url_data']);
                                                    }

                                                    // 附件url地址处理
                                                    if(substr($ksss, 0, 7) == 'images_' && isset($vsss['value']))
                                                    {
                                                        $vs[$kss][$ksss]['value'] = ResourcesService::AttachmentPathViewHandle($vsss['value']);
                                                    }
                                                }
                                            }

                                            // 手动指定的文章
                                            if(isset($vss['article_data_type']) && $vss['article_data_type'] == 1 && !empty($vss['article_data']) && is_array($vss['article_data']))
                                            {
                                                foreach($vss['article_data'] as $ksss=>$vsss)
                                                {
                                                    // 处理文章自定义封面图片
                                                    if(!empty($vsss['custom_cover']))
                                                    {
                                                        $vs[$kss]['article_data'][$ksss]['custom_cover'] = ResourcesService::AttachmentPathViewHandle($vsss['custom_cover']);
                                                    }

                                                    // 文章
                                                    if(!empty($appoint['article_data']) && !empty($vsss['data_id']) && array_key_exists($vsss['data_id'], $appoint['article_data']))
                                                    {
                                                        // 文章数据合并
                                                        $vs[$kss]['article_data'][$ksss] = array_merge($appoint['article_data'][$vsss['data_id']], $vs[$kss]['article_data'][$ksss]);
                                                        // 是否页面展示
                                                        if($is_view && !empty($vs[$kss]['article_data'][$ksss]['custom_cover']))
                                                        {
                                                            $vs[$kss]['article_data'][$ksss]['images'] = $vs[$kss]['article_data'][$ksss]['custom_cover'];
                                                        }
                                                    } else {
                                                        // 不存在对应的数据则删除
                                                        unset($vs[$kss]['article_data'][$ksss]);
                                                    }
                                                }
                                                $vs[$kss]['article_data'] = array_values($vs[$kss]['article_data']);
                                            } else {
                                                $vs[$kss]['article_data'] = [];
                                            }
                                            // 自动读取文章
                                            if(!isset($vss['article_data_type']) || $vss['article_data_type'] == 0)
                                            {
                                                $vs[$kss]['article_data'] = self::AutoArticleList($vss);
                                            }
                                        }
                                    }
                                    break;
                            }

                            // 赋值数据
                            $v['data'][$ks] = $vs;
                        }

                        // 商品数据、自动读取获取商品
                        if($v['type'] == 3 && (!isset($v['data']['goods_data_type']) || $v['data']['goods_data_type'] == 0))
                        {
                            $v['data']['goods_data'] = self::AutoGoodsList($v['data']);
                        }

                        // 文章数据、自动读取获取文章
                        if($v['type'] == 4 && (!isset($v['data']['article_data_type']) || $v['data']['article_data_type'] == 0))
                        {
                            $v['data']['article_data'] = self::AutoArticleList($v['data']);
                        }

                        // 视频
                        if($v['type'] == 2)
                        {
                            $v['data']['video'] = (empty($v['data']) || empty($v['data']['video'])) ? '' : ResourcesService::AttachmentPathViewHandle($v['data']['video']);
                            $v['data']['custom_cover'] = (empty($v['data']) || empty($v['data']['custom_cover'])) ? '' : ResourcesService::AttachmentPathViewHandle($v['data']['custom_cover']);
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 指定商品和文章数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-24
     * @desc    description
     * @param   [array]          $data [列表数据]
     */
    public static function ListAppointGoodsArticleData($data)
    {
        $goods_ids = [];
        $goods_data = [];
        $article_ids = [];
        $article_data = [];
        foreach($data as $v)
        {
            if(isset($v['type']) && !empty($v['data']))
            {
                // 是否数组
                if(!is_array($v['data']))
                {
                    $v['data'] = json_decode($v['data'], true);
                }

                // 商品
                if($v['type'] == 3 && !empty($v['data']['goods_data']) && is_array($v['data']['goods_data']))
                {
                    $goods_ids = array_merge($goods_ids, array_filter(array_column($v['data']['goods_data'], 'data_id')));
                }
                // 商品组
                if($v['type'] == 5 && !empty($v['data']['data']) && is_array($v['data']['data']))
                {
                    foreach($v['data']['data'] as $vs)
                    {
                        if(!empty($vs['goods_data']) && is_array($vs['goods_data']))
                        {
                            $goods_ids = array_merge($goods_ids, array_filter(array_column($vs['goods_data'], 'data_id')));
                        }
                    }
                }

                // 文章
                if($v['type'] == 4 && !empty($v['data']['article_data']) && is_array($v['data']['article_data']))
                {
                    $article_ids = array_merge($article_ids, array_filter(array_column($v['data']['article_data'], 'data_id')));
                }
                // 文章组
                if($v['type'] == 6 && !empty($v['data']['data']) && is_array($v['data']['data']))
                {
                    foreach($v['data']['data'] as $vs)
                    {
                        if(!empty($vs['article_data']) && is_array($vs['article_data']))
                        {
                            $article_ids = array_merge($article_ids, array_filter(array_column($vs['article_data'], 'data_id')));
                        }
                    }
                }
            }
        }

        if(!empty($goods_ids))
        {
            $goods_data = array_column(self::AppointGoodsList(['goods_ids'=>$goods_ids]), null, 'id');
        }
        if(!empty($article_ids))
        {
            $article_data = array_column(self::AppointArticleList(['article_ids'=>$article_ids]), null, 'id');
        }

        return [
            'goods_data'    => $goods_data,
            'article_data'  => $article_data,
        ];
    }

    /**
     * url处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-07
     * @desc    description
     * @param   [string]         $domain_url [当前域名地址]
     * @param   [array]          $url_data   [url数据]
     */
    public static function UrlHandle($domain_url, $url_data)
    {
        // 获取不同平台链接地址
        $url = (!empty($url_data) && is_array($url_data) && !empty($url_data[APPLICATION_CLIENT_TYPE])) ? $url_data[APPLICATION_CLIENT_TYPE] : '';
        // pc端处理url地址拼接
        if(!empty($url) && APPLICATION_CLIENT_TYPE == 'pc')
        {
            if(!in_array(substr($url, 0, 6), ['http:/', 'https:']))
            {
                if(substr($url, 0, 1) == '/')
                {
                    $url = substr($url, 1);
                }
                $url = $domain_url.$url;
            }
        }
        return $url;
    }

    /**
     * 指定读取文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params [输入参数]
     */
    public static function AppointArticleList($params = [])
    {
        return ArticleService::AppointArticleList($params);
    }

    /**
     * 自动读取文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $config [配置信息]
     */
    public static function AutoArticleList($config = [])
    {
        return ArticleService::AutoArticleList($config);
    }

    /**
     * 指定读取商品列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params [输入参数]
     */
    public static function AppointGoodsList($params = [])
    {
        return GoodsService::AppointGoodsList(array_merge($params, [
            'is_spec'   => 1,
            'is_cart'   => 1,
            'is_favor'  => 1,
        ]));
    }

    /**
     * 自动读取商品列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-29
     * @desc    description
     * @param   [array]         $params [输入参数]
     */
    public static function AutoGoodsList($params = [])
    {
        return GoodsService::AutoGoodsList(array_merge($params, [
            'is_spec'   => 1,
            'is_cart'   => 1,
            'is_favor'  => 1,
        ]));
    }

    /**
     * 保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ThemeDataSave($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'theme',
                'is_checked'        => 2,
                'error_msg'         => MyLang('common_service.themedata.form_item_theme_message'),
            ],
            [
                'checked_type'      => 'length',
                'key_name'          => 'name',
                'checked_data'      => '1,80',
                'is_checked'        => 2,
                'error_msg'         => MyLang('common_service.themedata.form_item_name_message'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'type',
                'checked_data'      => array_column(MyConst('common_theme_type_list'), 'value'),
                'error_msg'         => MyLang('common_service.themedata.save_type_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 根据数据类型处理
        $data_content = [];
        switch($params['type'])
        {
            // 单图文
            case 0 :
                // 基础数据
                $data_content = self::DataValueSaveHandle($params);

                // 自定义数据
                if(!empty($params['custom_data']))
                {
                    if(!is_array($params['custom_data']))
                    {
                        $params['custom_data'] = json_decode(htmlspecialchars_decode($params['custom_data']), true);
                    }
                    if(!empty($params['custom_data']) && is_array($params['custom_data']))
                    {
                        $data_content['custom_data'] = [];
                        foreach($params['custom_data'] as $k=>$v)
                        {
                            if(isset($v['name']) && isset($v['value']) && strlen($v['name'].$v['value']) > 0)
                            {
                                $data_content['custom_data'][] = [
                                    'icon'      => empty($v['icon']) ? '' : ResourcesService::AttachmentPathHandle($v['icon']),
                                    'name'      => $v['name'],
                                    'value'     => $v['value'],
                                    'url_data'  => empty($v['url_data']) ? '' : (is_array($v['url_data']) ? $v['url_data'] : json_decode(base64_decode(urldecode(htmlspecialchars_decode($v['url_data']))), true)),
                                ];
                            }
                        }
                    }
                }
                break;

            // 多图文
            case 1 :
                // 基础数据
                $data_content = self::DataValueSaveHandle($params);

                // 多图文数据
                if(!empty($params['data']))
                {
                    if(!is_array($params['data']))
                    {
                        $params['data'] = json_decode(htmlspecialchars_decode($params['data']), true);
                    }
                    if(!empty($params['data']) && is_array($params['data']))
                    {
                        $data_content['data'] = [];
                        foreach($params['data'] as $k=>$v)
                        {
                            $data_content['data'][] = self::DataValueSaveHandle($v);
                        }
                    }
                }
                break;

            // 视频
            case 2 :
                // 基础数据
                $data_content = self::DataValueSaveHandle($params);

                // 视频
                $data_content['video'] = empty($params['video']) ? '' : ResourcesService::AttachmentPathHandle($params['video']);
                // 自定义使用封面
                $data_content['custom_cover'] = empty($params['custom_cover']) ? '' : ResourcesService::AttachmentPathHandle($params['custom_cover']);
                break;

            // 商品
            case 3 :
                // 请求参数
                $p = [
                    [
                        'checked_type'      => 'in',
                        'key_name'          => 'goods_data_type',
                        'checked_data'      => array_column(MyConst('common_theme_goods_type_list'), 'value'),
                        'is_checked'        => 2,
                        'error_msg'         => MyLang('common_service.themedata.save_goods_data_type_error_tips'),
                    ],
                    [
                        'checked_type'      => 'in',
                        'key_name'          => 'goods_order_by_type',
                        'checked_data'      => array_keys(MyConst('common_goods_order_by_type_list')),
                        'is_checked'        => 2,
                        'error_msg'         => MyLang('common_service.themedata.save_goods_order_by_type_error_tips'),
                    ],
                    [
                        'checked_type'      => 'in',
                        'key_name'          => 'goods_order_by_rule',
                        'checked_data'      => array_keys(MyConst('common_data_order_by_rule_list')),
                        'is_checked'        => 2,
                        'error_msg'         => MyLang('common_service.themedata.save_goods_order_by_rule_error_tips'),
                    ],
                ];
                $ret = ParamsChecked($params, $p);
                if($ret !== true)
                {
                    return DataReturn($ret, -1);
                }

                // 基础数据
                $data_content = self::DataValueSaveHandle($params);

                // 数据处理
                $goods_data = [];
                if(isset($params['goods_data_type']) && $params['goods_data_type'] == 1 && !empty($params['goods_data']))
                {
                    if(!is_array($params['goods_data']))
                    {
                        $params['goods_data'] = json_decode(htmlspecialchars_decode($params['goods_data']), true);
                    }
                    if(!empty($params['goods_data']) && is_array($params['goods_data']))
                    {
                        foreach($params['goods_data'] as $v)
                        {
                            if(!empty($v['data_id']))
                            {
                                $goods_data[] = [
                                    'data_id'       => $v['data_id'],
                                    'custom_cover'  => empty($v['custom_cover']) ? '' : ResourcesService::AttachmentPathHandle($v['custom_cover']),
                                ];
                            }
                        }
                    }
                }
                // 加入数据
                $data_content['goods_data']           = $goods_data;
                $data_content['goods_data_type']      = isset($params['goods_data_type']) ? intval($params['goods_data_type']) : 0;
                $data_content['goods_order_by_type']  = isset($params['goods_order_by_type']) ? intval($params['goods_order_by_type']) : 0;
                $data_content['goods_order_by_rule']  = isset($params['goods_order_by_rule']) ? intval($params['goods_order_by_rule']) : 0;
                $data_content['goods_number']         = empty($params['goods_number']) ? '' : intval($params['goods_number']);
                $data_content['goods_brand_ids']      = empty($params['goods_brand_ids']) ? '' : (is_array($params['goods_brand_ids']) ? $params['goods_brand_ids'] : explode(',', $params['goods_brand_ids']));
                $data_content['goods_category_ids']   = empty($params['goods_category_ids']) ? '' : (is_array($params['goods_category_ids']) ? $params['goods_category_ids'] : explode(',', $params['goods_category_ids']));
                break;

            // 文章
            case 4 :
                // 请求参数
                $p = [
                    [
                        'checked_type'      => 'in',
                        'key_name'          => 'article_data_type',
                        'checked_data'      => array_column(MyConst('common_theme_article_type_list'), 'value'),
                        'is_checked'        => 2,
                        'error_msg'         => MyLang('common_service.themedata.save_article_data_type_error_tips'),
                    ],
                    [
                        'checked_type'      => 'in',
                        'key_name'          => 'article_order_by_type',
                        'checked_data'      => array_keys(MyConst('common_article_order_by_type_list')),
                        'is_checked'        => 2,
                        'error_msg'         => MyLang('common_service.themedata.save_article_order_by_type_error_tips'),
                    ],
                    [
                        'checked_type'      => 'in',
                        'key_name'          => 'article_order_by_rule',
                        'checked_data'      => array_keys(MyConst('common_data_order_by_rule_list')),
                        'is_checked'        => 2,
                        'error_msg'         => MyLang('common_service.themedata.save_article_order_by_rule_error_tips'),
                    ],
                ];
                $ret = ParamsChecked($params, $p);
                if($ret !== true)
                {
                    return DataReturn($ret, -1);
                }

                // 基础数据
                $data_content = self::DataValueSaveHandle($params);

                // 数据处理
                $article_data = [];
                if(isset($params['article_data_type']) && $params['article_data_type'] == 1 && !empty($params['article_data']))
                {
                    if(!is_array($params['article_data']))
                    {
                        $params['article_data'] = json_decode(htmlspecialchars_decode($params['article_data']), true);
                    }
                    if(!empty($params['article_data']) && is_array($params['article_data']))
                    {
                        foreach($params['article_data'] as $v)
                        {
                            if(!empty($v['data_id']))
                            {
                                $article_data[] = [
                                    'data_id'       => $v['data_id'],
                                    'custom_cover'  => empty($v['custom_cover']) ? '' : ResourcesService::AttachmentPathHandle($v['custom_cover']),
                                ];
                            }
                        }
                    }
                }
                // 加入数据
                $data_content['article_data']           = $article_data;
                $data_content['article_data_type']      = isset($params['article_data_type']) ? intval($params['article_data_type']) : 0;
                $data_content['article_order_by_type']  = isset($params['article_order_by_type']) ? intval($params['article_order_by_type']) : 0;
                $data_content['article_order_by_rule']  = isset($params['article_order_by_rule']) ? intval($params['article_order_by_rule']) : 0;
                $data_content['article_number']         = empty($params['article_number']) ? '' : intval($params['article_number']);
                $data_content['article_category_ids']   = empty($params['article_category_ids']) ? '' : (is_array($params['article_category_ids']) ? $params['article_category_ids'] : explode(',', $params['article_category_ids']));
                break;

            // 商品组合
            case 5 :
                // 基础数据
                $data_content = self::DataValueSaveHandle($params);

                // 商品数据
                $data = [];
                if(!empty($params['data']) && !is_array($params['data']))
                {
                    $params['data'] = json_decode(htmlspecialchars_decode($params['data']), true);
                }
                if(!empty($params['data']) && is_array($params['data']))
                {
                    foreach($params['data'] as $v)
                    {
                        // 数据处理
                        $goods_data = [];
                        if(isset($v['goods_data_type']) && $v['goods_data_type'] == 1 && !empty($v['goods_data']))
                        {
                            if(!is_array($v['goods_data']))
                            {
                                $v['goods_data'] = json_decode(htmlspecialchars_decode($v['goods_data']), true);
                            }
                            if(!empty($v['goods_data']) && is_array($v['goods_data']))
                            {
                                foreach($v['goods_data'] as $vs)
                                {
                                    if(!empty($vs['data_id']))
                                    {
                                        $goods_data[] = [
                                            'data_id'       => $vs['data_id'],
                                            'custom_cover'  => empty($vs['custom_cover']) ? '' : ResourcesService::AttachmentPathHandle($vs['custom_cover']),
                                        ];
                                    }
                                }
                            }
                        }
                        // 其他数据
                        $data[] = array_merge(self::DataValueSaveHandle($v), [
                            'goods_data_type'      => isset($v['goods_data_type']) ? intval($v['goods_data_type']) : 0,
                            'goods_order_by_type'  => isset($v['goods_order_by_type']) ? intval($v['goods_order_by_type']) : 0,
                            'goods_order_by_rule'  => isset($v['goods_order_by_rule']) ? intval($v['goods_order_by_rule']) : 0,
                            'goods_number'         => empty($v['goods_number']) ? '' : intval($v['goods_number']),
                            'goods_brand_ids'      => empty($v['goods_brand_ids']) ? '' : (is_array($v['goods_brand_ids']) ? $v['goods_brand_ids'] : explode(',', $v['goods_brand_ids'])),
                            'goods_category_ids'   => empty($v['goods_category_ids']) ? '' : (is_array($v['goods_category_ids']) ? $v['goods_category_ids'] : explode(',', $v['goods_category_ids'])),
                            'goods_data'           => $goods_data,
                        ]);
                    }
                }
                if(!empty($data))
                {
                    $data_content['data'] = $data;
                }
                break;

            // 文章组合
            case 6 :
                // 基础数据
                $data_content = self::DataValueSaveHandle($params);

                // 文章数据
                $data = [];
                if(!empty($params['data']) && !is_array($params['data']))
                {
                    $params['data'] = json_decode(htmlspecialchars_decode($params['data']), true);
                }
                if(!empty($params['data']) && is_array($params['data']))
                {
                    foreach($params['data'] as $v)
                    {
                        // 数据处理
                        $article_data = [];
                        if(isset($v['article_data_type']) && $v['article_data_type'] == 1 && !empty($v['article_data']))
                        {
                            if(!is_array($v['article_data']))
                            {
                                $v['article_data'] = json_decode(htmlspecialchars_decode($v['article_data']), true);
                            }
                            if(!empty($v['article_data']) && is_array($v['article_data']))
                            {
                                foreach($v['article_data'] as $vs)
                                {
                                    if(!empty($vs['data_id']))
                                    {
                                        $article_data[] = [
                                            'data_id'       => $vs['data_id'],
                                            'custom_cover'  => empty($vs['custom_cover']) ? '' : ResourcesService::AttachmentPathHandle($vs['custom_cover']),
                                        ];
                                    }
                                }
                            }
                        }
                        // 其他数据
                        $data[] = array_merge(self::DataValueSaveHandle($v), [
                            'article_data_type'      => isset($v['article_data_type']) ? intval($v['article_data_type']) : 0,
                            'article_order_by_type'  => isset($v['article_order_by_type']) ? intval($v['article_order_by_type']) : 0,
                            'article_order_by_rule'  => isset($v['article_order_by_rule']) ? intval($v['article_order_by_rule']) : 0,
                            'article_number'         => empty($v['article_number']) ? '' : intval($v['article_number']),
                            'article_category_ids'   => empty($v['article_category_ids']) ? '' : (is_array($v['article_category_ids']) ? $v['article_category_ids'] : explode(',', $v['article_category_ids'])),
                            'article_data'           => $article_data,
                        ]);
                    }
                }
                if(!empty($data))
                {
                    $data_content['data'] = $data;
                }
                break;
        }

        // 数据
        $data = [
            'unique'     => empty($params['unique']) ? ((isset($params['is_init']) && $params['is_init'] == 1) ? '' : md5(RandomString(10).time().GetNumberCode(10))) : $params['unique'],
            'theme'      => empty($params['theme']) ? '' : $params['theme'],
            'name'       => empty($params['name']) ? '' : $params['name'],
            'view'       => isset($params['view']) ? $params['view'] : -1,
            'data'       => empty($data_content) ? '' : json_encode($data_content, JSON_UNESCAPED_UNICODE),
            'is_enable'  => isset($params['is_enable']) ? intval($params['is_enable']) : 1,
        ];

        // 捕获异常
        try {
            if(empty($params['id']))
            {
                $data['type'] = intval($params['type']);
                $data['add_time'] = time();
                $data_id = Db::name('ThemeData')->insertGetId($data);
                if($data_id <= 0)
                {
                    throw new \Exception(MyLang('insert_fail'));
                }
            } else {
                $data_id = intval($params['id']);
                $data['upd_time'] = time();
                if(Db::name('ThemeData')->where(['id'=>$data_id])->update($data) === false)
                {
                    throw new \Exception(MyLang('edit_fail'));
                }
            }

            return DataReturn(MyLang('operate_success'), 0, $data_id);
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 附件标识
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-06-23
     * @desc    description
     * @param   [int]          $data_id [数据 id]
     */
    public static function AttachmentPathTypeValue($data_id)
    {
        return 'theme_data-'.$data_id;
    }

    /**
     * 保存数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-03-31
     * @desc    description
     * @param   [array]          $data [需要处理解析的数据]
     */
    public static function DataValueSaveHandle($data)
    {
        $result = [];
        if(!empty($data) && is_array($data))
        {
            foreach($data as $k=>$v)
            {
                // url数据
                $url_data = empty($v['url_data']) ? '' : (is_array($v['url_data']) ? $v['url_data'] : json_decode(base64_decode(urldecode(htmlspecialchars_decode($v['url_data']))), true));

                // 文本
                if(substr($k, 0, 5) == 'text_')
                {
                    $result[$k] = [
                        'value'     => empty($v['value']) ? '' : trim($v['value']),
                        'url_data'  => $url_data,
                    ];

                // 图片
                } else if(substr($k, 0, 7) == 'images_')
                {
                    $result[$k] = [
                        'value'     => empty($v['value']) ? '' : ResourcesService::AttachmentPathHandle($v['value']),
                        'url_data'  => $url_data,
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ThemeDataStatusUpdate($params = [])
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
        if(Db::name('ThemeData')->where(['id'=>intval($params['id'])])->update([$params['field']=>intval($params['state']), 'upd_time'=>time()]))
        {
            return DataReturn(MyLang('operate_success'), 0);
        }
        return DataReturn(MyLang('operate_fail'), -100);
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-18
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ThemeDataDelete($params = [])
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

        // 删除操作
        if(Db::name('ThemeData')->where(['id'=>$params['ids']])->delete())
        {
            // 删除数据库附件
            foreach($params['ids'] as $v)
            {
                AttachmentService::AttachmentPathTypeDelete(self::AttachmentPathTypeValue($v));
            }
            return DataReturn(MyLang('delete_success'), 0);
        }
        return DataReturn(MyLang('delete_fail'), -100);
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
            ['g.is_shelves', '=', 1]
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['g.title', 'like', '%'.$params['keywords'].'%'];
        }

        // 分类id
        if(!empty($params['category_id']))
        {
            $category_ids = GoodsCategoryService::GoodsCategoryItemsIds([$params['category_id']], 1);
            $category_ids[] = $params['category_id'];
            $where[] = ['gci.category_id', 'in', $category_ids];
        }

        // 获取商品总数
        $result['total'] = GoodsService::CategoryGoodsTotal($where);

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 基础参数
            $field = 'g.id,g.title,g.images';
            $order_by = 'g.sort_level desc, g.id desc';

            // 分页计算
            $m = intval(($result['page']-1)*$result['page_size']);
            $goods = GoodsService::CategoryGoodsList(['where'=>$where, 'm'=>$m, 'n'=>$result['page_size'], 'field'=>$field, 'order_by'=>$order_by]);
            $result['data'] = $goods['data'];
            $result['page_total'] = ceil($result['total']/$result['page_size']);
             // 数据处理
            if(!empty($result['data']) && is_array($result['data']) && !empty($params['data_ids']) && is_array($params['data_ids']))
            {
                foreach($result['data'] as &$v)
                {
                    // 是否已添加
                    $v['is_exist'] = in_array($v['id'], $params['data_ids']) ? 1 : 0;
                }
            }
        }
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 文章搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function ArticleSearchList($params = [])
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
            ['is_enable', '=', 1]
        ];

        // 关键字
        if(!empty($params['keywords']))
        {
            $where[] = ['title', 'like', '%'.$params['keywords'].'%'];
        }

        // 分类id
        if(!empty($params['category_id']))
        {
            $where[] = ['article_category_id', '=', intval($params['category_id'])];
        }

        // 获取商品总数
        $result['total'] = ArticleService::ArticleTotal($where);

        // 获取商品列表
        if($result['total'] > 0)
        {
            // 基础参数
            $field = 'id,title,cover';
            $order_by = 'id desc';

            // 分页计算
            $m = intval(($result['page']-1)*$result['page_size']);
            $ret = ArticleService::ArticleList(['where'=>$where, 'm'=>$m, 'n'=>$result['page_size'], 'field'=>$field, 'order_by'=>$order_by]);
            $result['data'] = $ret['data'];
            $result['page_total'] = ceil($result['total']/$result['page_size']);
             // 数据处理
            if(!empty($result['data']) && is_array($result['data']) && !empty($params['data_ids']) && is_array($params['data_ids']))
            {
                foreach($result['data'] as &$v)
                {
                    // 是否已添加
                    $v['is_exist'] = in_array($v['id'], $params['data_ids']) ? 1 : 0;
                }
            }
        }
        return DataReturn(MyLang('handle_success'), 0, $result);
    }

    /**
     * 主题包下载处理主题处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-03
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeDownloadPackageHandle($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'theme',
                'error_msg'         => MyLang('common_service.themedata.download_theme_empty_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'dir',
                'error_msg'         => MyLang('common_service.themedata.download_dir_empty_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 主题数据同步到主题包下载处理
        return self::ThemeDataDownloaPackagedHandle(['theme'=>$params['theme']], $params['dir']);
    }

    /**
     * 下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-17
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeDataDownload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'ids',
                'error_msg'         => MyLang('data_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        // 是否数组
        if(!is_array($params['ids']))
        {
            $params['ids'] = explode(',', $params['ids']);
        }

        // 目录不存在则创建
        $key = date('YmdHis').GetNumberCode(6);
        $dir = ROOT.'runtime'.DS.'data'.DS.'theme_data'.DS.$key;
        \base\FileUtil::CreateDir($dir);

        // 包下载处理
        $ret = self::ThemeDataDownloaPackagedHandle(['id'=>$params['ids']], $dir);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 生成压缩包
        $dir_zip = $dir.'.zip';
        $zip = new \base\ZipFolder();
        if(!$zip->zip($dir_zip, $dir))
        {
            return DataReturn(MyLang('form_generate_zip_message'), -2);
        }

        // 生成成功删除目录
        \base\FileUtil::UnlinkDir($dir);

        // 开始下载
        if(\base\FileUtil::DownloadFile($dir_zip, $key.'.zip', true))
        {
            return DataReturn(MyLang('download_success'), 0);
        }
        return DataReturn(MyLang('download_fail'), -100);
    }

    /**
     * 主题数据下载包处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-04-03
     * @desc    description
     * @param   [array]          $where [数据条件]
     * @param   [string]         $dir   [存储目录]
     */
    public static function ThemeDataDownloaPackagedHandle($where, $dir)
    {
        // 获取数据
        $data = Db::name('ThemeData')->where($where)->select()->toArray();
        if(empty($data))
        {
            // 删除目录
            \base\FileUtil::UnlinkDir($dir);
            return DataReturn(MyLang('data_no_exist_error_tips'), -1);
        }

        // 批量处理
        foreach($data as $v)
        {
            // 每条数据实际存储目录
            $dir_data = $dir.DS.$v['unique'];
            \base\FileUtil::CreateDir($dir_data);

            // 解析下载数据
            $config = self::ConfigDownloadHandle($v['type'], $v['unique'], $v['data'], $dir_data);

            // 基础信息
            $base = [
                'unique'  => $v['unique'],
                'name'    => $v['name'],
                'theme'   => $v['theme'],
                'view'    => $v['view'],
                'type'    => $v['type'],
                'data'    => $config,
            ];
            if(@file_put_contents($dir_data.DS.'config.json', JsonFormat($base)) === false)
            {
                // 删除目录
                \base\FileUtil::UnlinkDir($dir);
                return DataReturn(MyLang('common_service.themedata.download_config_file_create_fail_tips'), -1);
            }
        }
        return DataReturn(MyLang('operate_success'), 0);
    }

    /**
     * 配置数据下载处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-17
     * @desc    description
     * @param   [int]            $type     [数据类型]
     * @param   [string]         $unique   [唯一标识]
     * @param   [array]          $config   [配置数据]
     * @param   [string]         $dir      [存储目录]
     */
    public static function ConfigDownloadHandle($type, $unique, $config, $dir)
    {
        if(!empty($config))
        {
            // 非数组则解析
            if(!is_array($config))
            {
                $config = json_decode($config, true);
            }

            // 开始处理数据
            foreach($config as $ks=>&$vs)
            {
                // 附件url地址处理
                if(substr($ks, 0, 7) == 'images_' && !empty($vs['value']))
                {
                    $vs['value'] = self::FileSave($unique, $vs['value'], 'images', $dir);
                }

                switch($type)
                {
                    // 单图文
                    case 0 :
                        // 自定义数据
                        if($ks == 'custom_data' && !empty($vs) && is_array($vs))
                        {
                            foreach($vs as $kss=>$vss)
                            {
                                // 附件url地址处理
                                if(!empty($vss['icon']))
                                {
                                    $vs[$kss]['icon'] = self::FileSave($unique, $vss['icon'], 'images', $dir);
                                }
                            }
                        }
                        break;

                    // 多图文
                    case 1 :
                        if($ks == 'data' && !empty($vs) && is_array($vs))
                        {
                            foreach($vs as $kss=>$vss)
                            {
                                if(!empty($vss) && is_array($vss))
                                {
                                    foreach($vss as $ksss=>$vsss)
                                    {
                                        // 附件url地址处理
                                        if(substr($ksss, 0, 7) == 'images_' && !empty($vsss['value']))
                                        {
                                            $vs[$kss][$ksss]['value'] = self::FileSave($unique, $vsss['value'], 'images', $dir);
                                        }
                                    }
                                }                                
                            }
                        }
                        break;

                    // 视频
                    case 2 :
                        if(!empty($vs))
                        {
                            // 附件url地址处理
                            if($ks == 'video')
                            {
                                $vs = self::FileSave($unique, $vs, 'video', $dir);
                            }
                            if($ks == 'custom_cover')
                            {
                                $vs = self::FileSave($unique, $vs, 'images', $dir);
                            }
                        }
                        break;

                    // 商品
                    case 3 :
                        if($ks == 'goods_data' && !empty($vs) && is_array($vs) && isset($config['goods_data_type']) && $config['goods_data_type'] == 1)
                        {
                            foreach($vs as $kss=>$vss)
                            {
                                // 处理商品自定义封面图片
                                if(!empty($vss['custom_cover']))
                                {
                                    $vs[$kss]['custom_cover'] = self::FileSave($unique, $vss['custom_cover'], 'images', $dir);
                                }
                            }
                        }
                        break;

                    // 文章
                    case 4 :
                        if($ks == 'article_data' && !empty($vs) && is_array($vs) && isset($config['article_data_type']) && $config['article_data_type'] == 1)
                        {
                            foreach($vs as $kss=>$vss)
                            {
                                // 处理文章自定义封面图片
                                if(!empty($vss['custom_cover']))
                                {
                                    $vs[$kss]['custom_cover'] = self::FileSave($unique, $vss['custom_cover'], 'images', $dir);
                                }
                            }
                        }
                        break;

                    // 商品组
                    case 5 :
                        if($ks == 'data' && !empty($vs) && is_array($vs))
                        {
                            foreach($vs as $kss=>$vss)
                            {
                                // 每项的图片
                                if(!empty($vss) && is_array($vss))
                                {
                                    foreach($vss as $ksss=>$vsss)
                                    {
                                        // 附件url地址处理
                                        if(substr($ksss, 0, 7) == 'images_' && !empty($vsss['value']))
                                        {
                                            $vs[$kss][$ksss]['value'] = self::FileSave($unique, $vsss['value'], 'images', $dir);
                                        }
                                    }
                                }

                                // 商品
                                if(!empty($vss['goods_data']) && is_array($vss['goods_data']) && isset($vss['goods_data_type']) && $vss['goods_data_type'] == 1)
                                {
                                    foreach($vss['goods_data'] as $ksss=>$vsss)
                                    {
                                        // 处理商品自定义封面图片
                                        if(!empty($vsss['custom_cover']))
                                        {
                                            $vs[$kss]['goods_data'][$ksss]['custom_cover'] = self::FileSave($unique, $vsss['custom_cover'], 'images', $dir);
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    // 文章组
                    case 6 :
                        if($ks == 'data' && !empty($vs) && is_array($vs))
                        {
                            foreach($vs as $kss=>$vss)
                            {
                                // 每项的图片
                                if(!empty($vss) && is_array($vss))
                                {
                                    foreach($vss as $ksss=>$vsss)
                                    {
                                        // 附件url地址处理
                                        if(substr($ksss, 0, 7) == 'images_' && !empty($vsss['value']))
                                        {
                                            $vs[$kss][$ksss]['value'] = self::FileSave($unique, $vsss['value'], 'images', $dir);
                                        }
                                    }
                                }

                                // 文章
                                if(!empty($vss['article_data']) && is_array($vss['article_data']) && isset($vss['article_data_type']) && $vss['article_data_type'] == 1)
                                {
                                    foreach($vss['article_data'] as $ksss=>$vsss)
                                    {
                                        // 处理文章自定义封面图片
                                        if(!empty($vsss['custom_cover']))
                                        {
                                            $vs[$kss]['article_data'][$ksss]['custom_cover'] = self::FileSave($unique, $vsss['custom_cover'], 'images', $dir);
                                        }
                                    }
                                }
                            }
                        }
                        break;
                }
            }
        }
        return $config;
    }

    /**
     * 文件保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-18
     * @desc    description
     * @param   [string]          $unique  [唯一表示]
     * @param   [string]          $file    [文件地址]
     * @param   [string]          $type    [类型]
     * @param   [string]          $dir     [存储路径]
     */
    public static function FileSave($unique, $file, $type, $dir)
    {
        if(!empty($file))
        {
            $arr = explode('/', $file);
            $path = 'static'.DS.'upload'.DS.$type.DS.'theme_data'.DS.$unique.DS.date('Y/m/d');
            $filename = $path.DS.$arr[count($arr)-1];
            \base\FileUtil::CreateDir($dir.DS.$path);

            $status = false;
            if(substr($file, 0, 4) == 'http')
            {
                $temp = ResourcesService::AttachmentPathHandle($file);
                if(substr($temp, 0, 4) == 'http' || !file_exists(ROOT.'public'.$temp))
                {
                    // 远程下载
                    $temp_data = RequestGet($file);
                    if(!empty($temp_data))
                    {
                        file_put_contents($dir.DS.$filename, $temp_data);
                        $status = true;
                    }
                } else {
                    $file = $temp;
                }
            }
            if(!$status)
            {
                \base\FileUtil::CopyFile(ROOT.'public'.$file, $dir.DS.$filename);
            }
            return DS.$filename;
        }
        return '';
    }

    /**
     * 导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ThemeDataUpload($params = [])
    {
        // 文件上传校验
        $error = FileUploadError('file');
        if($error !== true)
        {
            return DataReturn($error, -1);
        }

        // 文件格式化校验
        $type = ResourcesService::ZipExtTypeList();
        if(!in_array($_FILES['file']['type'], $type))
        {
            return DataReturn(MyLang('form_upload_zip_message'), -2);
        }

        // 上传处理
        return self::ThemeDataUploadHandle($_FILES['file']['tmp_name'], $params);
    }
    
    /**
     * 导入处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [string]         $package_file [软件包地址]
     * @param   [array]          $params       [输入参数]
     */
    public static function ThemeDataUploadHandle($package_file, $params = [])
    {
        // 是否主题导入
        $is_theme_upload = isset($params['is_theme_upload']) && $params['is_theme_upload'] == 1;
        $theme_dir_ds_unique = '_theme_data_';

        // 应用upload目录权限校验
        $app_upload_dir = ROOT.'public'.DS.'static'.DS.'upload';
        if(!is_writable($app_upload_dir))
        {
            return DataReturn(MyLang('common_service.themedata.upload_dis_no_power_tips').'['.$app_upload_dir.']', -3);
        }

        // 开始解压文件
        $zip = new \ZipArchive();
        $resource = $zip->open($package_file);
        if($resource !== true)
        {
            return DataReturn(MyLang('form_open_zip_message').'['.$resource.']', -11);
        }

        // 配置信息
        $handle_data = [];
        for($i=0; $i<$zip->numFiles; $i++)
        {
            $file = $zip->getNameIndex($i);
            // 当前是配置文件
            if(stripos($file, 'config.json') !== false)
            {
                // 是否主题上传，则需要验证主题包目录分隔符
                if($is_theme_upload && stripos($file, $theme_dir_ds_unique) === false)
                {
                    continue;
                }

                // 读取配置信息
                $stream = $zip->getStream($file);
                if($stream === false)
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.themedata.upload_config_file_get_fail_tips'), -1);
                }

                // 排除后缀文件
                $pos = strripos($file, '.');
                if($pos !== false)
                {
                    $info = pathinfo($file);
                    if(isset($info['extension']) && in_array(strtolower($info['extension']), self::$exclude_ext))
                    {
                        continue;
                    }
                }

                // 获取配置信息并解析
                $file_content = stream_get_contents($stream);
                $config = empty($file_content) ? [] : json_decode($file_content, true);
                if(empty($config) || empty($config['unique']) || empty($config['name']) || empty($config['theme']))
                {
                    $zip->close();
                    return DataReturn(MyLang('common_service.themedata.upload_config_file_error_tips'), -1);
                }

                // 数据
                $data = [
                    'unique'  => $config['unique'],
                    'name'    => $config['name'],
                    'theme'   => $config['theme'],
                    'view'    => isset($config['view']) ? $config['view'] : -1,
                    'type'    => isset($config['type']) ? $config['type'] : 0,
                    'data'    => '',
                ];
                // 是否存在数据
                $data_id = Db::name('ThemeData')->where(['unique'=>$data['unique']])->value('id');
                if(empty($data_id))
                {
                    // 添加数据
                    $data['add_time'] = time();
                    $data_id = Db::name('ThemeData')->insertGetId($data);
                    if($data_id <= 0)
                    {
                        $zip->close();
                        return DataReturn(MyLang('insert_fail'), -1);
                    }
                } else {
                    // 更新数据
                    $data['upd_time'] = time();
                    if(!Db::name('ThemeData')->where(['id'=>$data_id])->update($data))
                    {
                        $zip->close();
                        return DataReturn(MyLang('update_fail'), -1);
                    }
                    // 删除原有的附件
                    AttachmentService::AttachmentPathTypeDelete(self::AttachmentPathTypeValue($data_id));
                }
                
                // 更新配置信息
                $upd_data = [
                    'data'      => empty($config['data']) ? '' : str_replace($config['unique'], $data_id, json_encode($config['data'], JSON_UNESCAPED_UNICODE)),
                    'upd_time'  => time(),
                ];
                if(!Db::name('ThemeData')->where(['id'=>$data_id])->update($upd_data))
                {
                    $zip->close();
                    return DataReturn(MyLang('update_fail'), -1);
                }

                // 加入已处理容器
                $handle_data[$data['unique']] = [
                    'data_id'  => $data_id,
                    'config'   => $config,
                ];
            }
        }
        if(empty($handle_data))
        {
            return DataReturn(MyLang('common_service.themedata.upload_config_file_handle_fail_tips'), -1);
        }

        // 文件处理
        $success = 0;
        for($i=0; $i<$zip->numFiles; $i++)
        {
            // 资源文件
            $file = $zip->getNameIndex($i);

            // 排除临时文件和临时目录
            if(strpos($file, '/.') === false && strpos($file, '__') === false)
            {
                // 是否主题上传处理
                if($is_theme_upload)
                {
                    // 如果没有主题的目录分割标识则忽略
                    if(stripos($file, $theme_dir_ds_unique) === false)
                    {
                        continue;
                    }

                    // 去除以_theme_data_分割的和前面部分
                    $temp_file = substr($file, strpos($file, $theme_dir_ds_unique)+strlen($theme_dir_ds_unique)+1);
                } else {
                    // 去除第一个目录（为原始数据的id）
                    $temp_file = substr($file, strpos($file, '/')+1);
                }

                // 获取第一个目录得到当前数据唯一标识
                $unique = substr($temp_file, 0, strpos($temp_file, '/'));
                // 再去除当前数据唯一标识目录段
                $temp_file = substr($temp_file, strpos($temp_file, '/')+1);
                // 空或者目录及配置文件则跳过
                if(empty($temp_file) || in_array($temp_file, ['static/', 'static/upload/', 'config.json']) || empty($handle_data[$unique]))
                {
                    continue;
                }

                // 截取文件路径
                $new_file = ROOT.'public'.DS.str_replace($unique, $handle_data[$unique]['data_id'], $temp_file);
                $file_path = substr($new_file, 0, strrpos($new_file, '/'));

                // 路径不存在则创建
                \base\FileUtil::CreateDir($file_path);

                // 如果不是目录则写入文件
                if(!is_dir($new_file))
                {
                    // 读取这个文件
                    $stream = $zip->getStream($file);
                    if($stream !== false)
                    {
                        $file_content = stream_get_contents($stream);
                        if($file_content !== false)
                        {
                            if(file_put_contents($new_file, $file_content))
                            {
                                $success++;
                            }
                        }
                        fclose($stream);
                    }
                }
            }
        }
        // 关闭zip
        $zip->close();

        // 附件同步到数据库
        foreach($handle_data as $v)
        {
            AttachmentService::AttachmentDiskFilesToDb('theme_data'.DS.$v['data_id'], self::AttachmentPathTypeValue($v['data_id']));
        }
        return DataReturn(MyLang('import_success'), 0);
    }
}
?>