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
use app\service\UeditorService;
use app\service\SystemBaseService;
use app\service\ResourcesService;
use app\service\AttachmentService;
use app\service\AttachmentCategoryService;
use app\service\GoodsCategoryService;
use app\service\ArticleCategoryService;
use app\service\BrandCategoryService;
use app\service\BrandService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\OrderService;
use app\service\GoodsFavorService;
use app\service\MessageService;
use app\service\IntegralService;
use app\service\DiyService;
use app\service\AppTabbarService;
use app\service\PackageInstallService;
use app\service\StoreService;

/**
 * DiyApi服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2024-07-18
 * @desc    description
 */
class DiyApiService
{
    /**
     * 公共初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function Init($params = [])
    {
        // 文章分类
        $article_category = ArticleCategoryService::ArticleCategoryList(['field'=>'id,name']);

        // 品牌分类
        $brand_category = BrandCategoryService::BrandCategoryList(['field'=>'id,name']);

        // 返回数据
        $data = [
            'config'                      => self::ConfigData(),
            // 附件分类
            'attachment_category'         => AttachmentCategoryService::AttachmentCategoryAll(),
            // 文章分类
            'article_category'            => $article_category['data'],
            // 品牌分类
            'brand_category'              => $brand_category['data'],
            // 品牌列表
            'brand_list'                  => BrandService::CategoryBrand(),
            // 商品分类
            'goods_category'              => GoodsCategoryService::GoodsCategoryAll(),
            // 页面链接
            'page_link_list'              => self::PageLinkList(),
            // 模块组件
            'module_list'                 => self::ModuleList(),
            // 品牌排序类型
            'brand_order_by_type_list'    => MyConst('common_brand_order_by_type_list'),
            // 文章排序类型
            'article_order_by_type_list'  => MyConst('common_article_order_by_type_list'),
            // 商品排序类型
            'goods_order_by_type_list'    => MyConst('common_goods_order_by_type_list'),
            // 数据排序规则
            'data_order_by_rule_list'     => MyConst('common_data_order_by_rule_list'),
            // 插件
            'plugins'                     => [],
        ];

        // 钩子
        $hook_name = 'plugins_service_diyapi_init_data';
        MyEventTrigger($hook_name, [
            'hook_name'   => $hook_name,
            'is_backend'  => true,
            'params'      => $params,
            'data'        => &$data,
        ]);

        return DataReturn('success', 0, $data);
    }

    /**
     * 配置数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-09-04
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ConfigData($params = [])
    {
        return [
            // 站点信息
            'site_name'                  => MyC('home_site_name'),
            'site_logo'                  => AttachmentPathViewHandle(MyC('home_site_logo')),
            'site_logo_wap'              => AttachmentPathViewHandle(MyC('home_site_logo_wap')),
            'site_logo_app'              => AttachmentPathViewHandle(MyC('home_site_logo_app')),
            'site_logo_square'           => AttachmentPathViewHandle(MyC('home_site_logo_square')),
            // 地图密钥
            'common_map_type'            => MyC('common_map_type', 'baidu', true),
            'common_baidu_map_ak'        => MyC('common_baidu_map_ak', null, true),
            'common_amap_map_ak'         => MyC('common_amap_map_ak', null, true),
            'common_amap_map_safety_ak'  => MyC('common_amap_map_safety_ak', null, true),
            'common_tencent_map_ak'      => MyC('common_tencent_map_ak', null, true),
            'common_tianditu_map_ak'     => MyC('common_tianditu_map_ak', null, true),
            // 商店diy下载地址
            'store_diy_url'              => StoreService::StoreDiyUrl(),
            // 货币符号
            'currency_symbol'            => ResourcesService::CurrencyDataSymbol(),
            // 附件host地址
            'attachment_host'            => SystemBaseService::AttachmentHost(),
            // 上传组件配置
            'ueditor'                    => [
                'image_suffix'  => MyConfig('ueditor.imageAllowFiles'),
                'video_suffix'  => MyConfig('ueditor.videoAllowFiles'),
                'file_suffix'   => MyConfig('ueditor.fileAllowFiles'),
            ],
        ];
    }

    /**
     * 模块组件
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-08-30
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ModuleList($params = [])
    {
        return [
            [
                'name'  => '基础组件',
                'key'   => 'base',
                'data'  => [
                    ['key' => 'tabs', 'name' => '选项卡'],
                    ['key' => 'tabs-carousel', 'name' => '选项卡轮播'],
                    ['key' => 'carousel', 'name' => '轮播图'],
                    ['key' => 'search', 'name' => '搜索框'],
                    ['key' => 'user-info', 'name' => '用户信息'],
                    ['key' => 'nav-group', 'name' => '导航组'],
                    ['key' => 'notice', 'name' => '公告'],
                    ['key' => 'video', 'name' => '视频'],
                    ['key' => 'article-list', 'name' => '文章列表'],
                    ['key' => 'article-tabs', 'name' => '文章选项卡'],
                    ['key' => 'goods-list', 'name' => '商品列表'],
                    ['key' => 'goods-tabs', 'name' => '商品选项卡'],
                    ['key' => 'img-magic', 'name' => '图片魔方'],
                    ['key' => 'data-magic', 'name' => '数据魔方'],
                    ['key' => 'data-tabs', 'name' => '数据选项卡'],
                    ['key' => 'hot-zone', 'name' => '热区'],
                    ['key' => 'custom', 'name' => '自定义'],
                ]
            ],
            [
                'name'  => '插件',
                'key'   => 'plugins',
                'data'  => []
            ],
            [
                'name'  => '工具组件',
                'key'   => 'tool',
                'data'  => [
                    ['key' => 'title', 'name' => '标题'],
                    ['key' => 'float-window', 'name' => '悬浮按钮'],
                    ['key' => 'auxiliary-blank', 'name' => '辅助空白'],
                    ['key' => 'row-line', 'name' => '横线'],
                    ['key' => 'rich-text', 'name' => '富文本'],
                ]
            ]
        ];
    }

    /**
     * 页面链接
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-23
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PageLinkList($params = [])
    {
        return [
            [
                'name'  => '商城链接',
                'type'  => 'shop',
                'data'  => [
                    [
                        'name'  => '基础链接',
                        'type'  => 'base',
                        'data'  => [
                            ['name'=>'商城首页', 'page'=>'/pages/index/index'],
                            ['name'=>'商品分类', 'page'=>'/pages/goods-category/goods-category'],
                            ['name'=>'商品搜索开始', 'page'=>'/pages/goods-search-start/goods-search-start'],
                            ['name'=>'商品搜索', 'page'=>'/pages/goods-search/goods-search'],
                            ['name'=>'购物车', 'page'=>'/pages/cart/cart'],
                            ['name'=>'购物车单页', 'page'=>'/pages/cart-page/cart-page'],
                            ['name'=>'登录页面', 'page'=>'/pages/login/login'],
                            ['name'=>'文章列表', 'page'=>'/pages/article-category/article-category'],
                            ['name'=>'系统扫码', 'page'=>'scan://system'],
                        ],
                    ],
                    [
                        'name'  => '用户中心',
                        'type'  => 'user',
                        'data'  => [
                            ['name'=>'用户中心', 'page'=>'/pages/user/user'],
                            ['name'=>'订单列表', 'page'=>'/pages/user-order/user-order'],
                            ['name'=>'订单售后', 'page'=>'/pages/user-orderaftersale/user-orderaftersale'],
                            ['name'=>'商品收藏', 'page'=>'/pages/user-favor/user-favor'],
                            ['name'=>'商品评论', 'page'=>'/pages/user-goods-comments/user-goods-comments'],
                            ['name'=>'我的地址', 'page'=>'/pages/user-address/user-address'],
                            ['name'=>'我的积分', 'page'=>'/pages/user-integral/user-integral'],
                            ['name'=>'我的消息', 'page'=>'/pages/message/message'],
                            ['name'=>'我的足迹', 'page'=>'/pages/user-goods-browse/user-goods-browse'],
                            ['name'=>'设置中心', 'page'=>'/pages/setup/setup'],
                            ['name'=>'关于我们', 'page'=>'/pages/about/about'],
                        ],
                    ],
                ],
            ],
            [
                'name'  => '商品分类',
                'type'  => 'goods-category',
                'data'  => null,
            ],
            [
                'name'  => '商品搜索',
                'type'  => 'goods-search',
                'data'  => null,
            ],
            [
                'name'  => '商品页面',
                'type'  => 'goods',
                'data'  => null,
            ],
            [
                'name'  => '文章页面',
                'type'  => 'article',
                'data'  => null,
            ],
            [
                'name'  => 'DIY页面',
                'type'  => 'diy',
                'data'  => null,
            ],
            [
                'name'  => '页面设计',
                'type'  => 'design',
                'data'  => null,
            ],
            [
                'name'  => '自定义页面',
                'type'  => 'custom-view',
                'data'  => null,
            ],
            [
                'name'  => '自定义链接',
                'type'  => 'custom-url',
                'data'  => null,
            ],
            [
                'name'  => '品牌',
                'type'  => 'brand',
                'data'  => null,
            ],
            [
                'name'  => '插件',
                'type'  => 'plugins',
                'data'  => [],
            ]
        ];
    }

    /**
     * 附件分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentCategory($params = [])
    {
        $result = [
            'attachment_category' => AttachmentCategoryService::AttachmentCategoryAll(),
        ];
        return DataReturn('success', 0, $result);
    }

    /**
     * 附件列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentList($params = [])
    {
        $params['control'] = 'attachment';
        $params['action'] = 'index';
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * 附件保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentSave($params = [])
    {
        return AttachmentService::AttachmentSave($params);
    }

    /**
     * 附件删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentDelete($params = [])
    {
        return AttachmentService::AttachmentDelete($params);
    }

    /**
     * 附件上传
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentUpload($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '附件类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => '附件分类id有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 附件方法
        $params['action'] = 'upload'.$params['type'];
        return UeditorService::Run($params);
    }

    /**
     * 远程下载
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentCatch($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'type',
                'error_msg'         => '附件类型有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'category_id',
                'error_msg'         => '附件分类id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'source',
                'error_msg'         => '远程地址为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 附件方法
        $params['action'] = 'catch'.$params['type'];
        return UeditorService::Run($params);
    }

    /**
     * 附件扫码上传数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentScanUploadData($params = [])
    {
        $params['action'] = 'scandata';
        return UeditorService::Run($params);
    }

    /**
     * 附件移动分类
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentMoveCategory($params = [])
    {
        return AttachmentService::AttachmentMoveCategory($params);
    }

    /**
     * 附件分类保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentCategorySave($params = [])
    {
        return AttachmentCategoryService::AttachmentCategorySave($params);
    }

    /**
     * 附件分类保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AttachmentCategoryDelete($params = [])
    {
        return AttachmentCategoryService::AttachmentCategoryDelete($params);
    }

    /**
     * 商品列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsList($params = [])
    {
        $params['control'] = 'goods';
        $params['action'] = 'index';
        $params['is_shelves'] = 1;
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * 自定义页面列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function CustomViewList($params = [])
    {
        $params['control'] = 'customview';
        $params['action'] = 'index';
        $params['is_enable'] = 1;
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * 页面设计列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DesignList($params = [])
    {
        $params['control'] = 'design';
        $params['action'] = 'index';
        $params['is_enable'] = 1;
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * 文章列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ArticleList($params = [])
    {
        $params['control'] = 'article';
        $params['action'] = 'index';
        $params['is_enable'] = 1;
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * 品牌列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BrandList($params = [])
    {
        $params['control'] = 'brand';
        $params['action'] = 'index';
        $params['is_enable'] = 1;
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * Diy装修列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DiyList($params = [])
    {
        $params['control'] = 'diy';
        $params['action'] = 'index';
        $params['is_enable'] = 1;
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * Diy装修详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DiyDetail($params = [])
    {
        $params['control'] = 'diy';
        $params['action'] = 'detail';
        return DataReturn('success', 0, FormModuleData($params));
    }

    /**
     * Diy装修保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DiySave($params = [])
    {
        return DiyService::DiySave($params);
    }

    /**
     * Diy装修导入
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DiyUpload($params = [])
    {
        return DiyService::DiyUpload($params);
    }

    /**
     * Diy装修导出
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DiyDownload($params = [])
    {
        return DiyService::DiyDownload($params);
    }

    /**
     * diy模板安装
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DiyInstall($params = [])
    {
        $params['type'] = 'diy';
        return PackageInstallService::Install($params);
    }

    /**
     * diy模板市场
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function DiyMarket($params = [])
    {
        return DiyService::DiyMarket($params);
    }

    /**
     * 底部菜单保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AppTabbarSave($params = [])
    {
        $type = empty($params['type']) ? 'home' : $params['type'];
        return AppTabbarService::AppTabbarConfigSave($type, $params);
    }

    /**
     * 底部菜单数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-04-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function AppTabbarData($params = [])
    {
        $type = empty($params['type']) ? 'home' : $params['type'];
        return DataReturn('success', 0, AppTabbarService::AppTabbarConfigData($type));
    }

    /**
     * 商品指定数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsAppointData($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'goods_ids',
                'error_msg'         => '请选择商品',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取商品
        $result = GoodsService::AppointGoodsList($params);
        return DataReturn('success', 0, $result);
    }

    /**
     * 商品自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function GoodsAutoData($params = [])
    {
        $result = GoodsService::AutoGoodsList($params);
        return DataReturn('success', 0, $result);
    }

    /**
     * 文章指定数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ArticleAppointData($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'article_ids',
                'error_msg'         => '请选择文章',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取文章
        $result = ArticleService::AppointArticleList($params);
        return DataReturn('success', 0, $result);
    }

    /**
     * 文章自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function ArticleAutoData($params = [])
    {
        $result = ArticleService::AutoArticleList($params);
        return DataReturn('success', 0, $result);
    }

    /**
     * 品牌指定数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BrandAppointData($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'brand_ids',
                'error_msg'         => '请选择品牌',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取品牌
        $result = BrandService::AppointBrandList($params);
        return DataReturn('success', 0, $result);
    }

    /**
     * 品牌自动数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function BrandAutoData($params = [])
    {
        $result = BrandService::AutoBrandList($params);
        return DataReturn('success', 0, $result);
    }

    /**
     * 用户头部数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function UserHeadData($params = [])
    {
        static $diy_user_info_data = null;
        if($diy_user_info_data === null)
        {
            $user = UserService::LoginUserInfo();
            $diy_user_info_data = [
                'user'                  => $user,
                'order_count'           => 0,
                'goods_favor_count'     => 0,
                'goods_browse_count'    => 0,
                'message_unread_count'  => 0,
                'integral_number'       => 0,
            ];
            if(!empty($user))
            {
                // 基础条件
                $base_where = [
                    ['user_id', '=', $user['id']],
                ];

                // 订单总数
                $diy_user_info_data['order_count'] = OrderService::OrderTotal(array_merge($base_where, [
                    ['is_delete_time', '=', 0],
                    ['user_is_delete_time', '=', 0],
                ]));

                // 商品收藏总数
                $diy_user_info_data['goods_favor_count'] = GoodsFavorService::GoodsFavorTotal($base_where);

                // 商品浏览总数
                $diy_user_info_data['goods_browse_count'] = GoodsBrowseService::GoodsBrowseTotal($base_where);

                // 用户积分
                $integral = IntegralService::UserIntegral($user['id']);
                $diy_user_info_data['integral_number'] = (!empty($integral) && !empty($integral['integral'])) ? $integral['integral'] : 0;

                // 未读消息总数
                $diy_user_info_data['message_unread_count'] = MessageService::UserMessageTotal([
                    'user'    => $user,
                    'is_more' => 1,
                    'is_read' => 0,
                ]);
            }
        }
        return DataReturn('success', 0, $diy_user_info_data);
    }

    /**
     * 自定义初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-07-19
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function CustomInit($params = [])
    {
        // 返回数据
        $data = [
            // 数据源
            'data_source' => [
                [
                    'name'          => '用户信息',
                    'type'          => 'user-info',
                    'appoint_data'  => [
                        'user_avatar'           => UserDefaultAvatar(),
                        'user_name_view'        => '用户名称',
                        'order_count'           => 0,
                        'goods_favor_count'     => 0,
                        'goods_browse_count'    => 0,
                        'message_unread_count'  => 0,
                        'integral_number'       => 0,
                    ],
                    'data'          => [
                        ['name'=>'用户头像', 'field'=>'user_avatar', 'type'=>'images'],
                        ['name'=>'用户名称', 'field'=>'user_name_view', 'type'=>'text'],
                        ['name'=>'订单总数', 'field'=>'order_count', 'type'=>'text'],
                        ['name'=>'商品收藏', 'field'=>'goods_favor_count', 'type'=>'text'],
                        ['name'=>'我的足迹', 'field'=>'goods_browse_count', 'type'=>'text'],
                        ['name'=>'未读消息', 'field'=>'message_unread_count', 'type'=>'text'],
                        ['name'=>'我的积分', 'field'=>'integral_number', 'type'=>'text'],
                    ],
                ],
                [
                    'name'  => '商品',
                    'type'  => 'goods',
                    'data'  => [
                        ['name'=>'数据索引', 'field'=>'data_index', 'type'=>'text'],
                        ['name'=>'商品URL', 'field' =>'goods_url', 'type'=>'link'],
                        ['name'=>'商品ID', 'field' =>'id', 'type'=>'text'],
                        ['name'=>'标题', 'field' =>'title', 'type'=>'text'],
                        ['name'=>'标题颜色', 'field' =>'title_color', 'type'=>'text'],
                        ['name'=>'简述', 'field' =>'simple_desc', 'type'=>'text'],
                        ['name'=>'型号', 'field' =>'model', 'type'=>'text'],
                        ['name'=>'品牌', 'field' =>'brand_name', 'type'=>'text'],
                        ['name'=>'生产地', 'field' =>'place_origin_name', 'type'=>'text'],
                        ['name'=>'库存', 'field' =>'inventory', 'type'=>'text'],
                        ['name'=>'库存单位', 'field' =>'inventory_unit', 'type'=>'text'],
                        ['name'=>'封面图片', 'field' =>'images', 'type'=>'images'],
                        ['name'=>'原价', 'field' =>'original_price', 'type'=>'text'],
                        ['name'=>'最低原价', 'field' =>'min_original_price', 'type'=>'text'],
                        ['name'=>'最高原价', 'field' =>'max_original_price', 'type'=>'text'],
                        ['name'=>'售价', 'field' =>'price', 'type'=>'text'],
                        ['name'=>'最低售价', 'field' =>'min_price', 'type'=>'text'],
                        ['name'=>'最高售价', 'field' =>'max_price', 'type'=>'text'],
                        ['name'=>'起购数', 'field' =>'buy_min_number', 'type'=>'text'],
                        ['name'=>'限购数', 'field' =>'buy_max_number', 'type'=>'text'],
                        ['name'=>'详情内容', 'field' =>'content_web', 'type'=>'text'],
                        ['name'=>'销量', 'field' =>'sales_count', 'type'=>'text'],
                        ['name'=>'访问量', 'field' =>'access_count', 'type'=>'text'],
                        ['name'=>'原价标题', 'field' =>'show_field_original_price_text', 'type'=>'text'],
                        ['name'=>'原价符号', 'field' =>'show_original_price_symbol', 'type'=>'text'],
                        ['name'=>'原价单位', 'field' =>'show_original_price_unit', 'type'=>'text'],
                        ['name'=>'售价标题', 'field' =>'show_field_price_text', 'type'=>'text'],
                        ['name'=>'售价符号', 'field' =>'show_price_symbol', 'type'=>'text'],
                        ['name'=>'售价单位', 'field' =>'show_price_unit', 'type'=>'text'],
                        ['name'=>'添加时间', 'field' =>'add_time', 'type'=>'text'],
                        ['name'=>'更新时间', 'field' =>'upd_time', 'type'=>'text'],
                        ['name'=>'商品相册', 'field' =>'photo', 'type'=>'custom-data-list', 'data'=>[
                            ['name'=>'相册图片', 'field' =>'images', 'type'=>'images'],
                        ]],
                    ],
                    'custom_config' => [
                        'appoint_config' => [
                            'data_url'     => MyUrl('admin/diyapi/goodslist'),
                            'is_multiple'  => 1,
                            'show_data'    => [
                                'data_key'   => 'id',
                                'data_name'  => 'title',
                                'data_logo'  => 'images',
                            ],
                            'popup_title'   => '商品选择',
                            'header' => [
                                [
                                    'field'  => 'id',
                                    'name'   => '商品ID',
                                    'width'  => 120,
                                ],
                                [
                                    'field'  => 'images',
                                    'name'   => '图片',
                                    'type'   => 'images',
                                    'width'  => 100,
                                ],
                                [
                                    'field'  => 'title',
                                    'name'   => '标题',
                                ],
                                [
                                    'field'  => 'category_text',
                                    'name'   => '分类',
                                ],
                            ],
                            'search_filter_form_config' => [
                                [
                                    'type'       => 'select',
                                    'config'     => [
                                        'placeholder'  => '请选择商品分类',
                                        'is_level'     => 1,
                                        'is_multiple'  => 1,
                                        'children'     => 'items',
                                    ],
                                    'title'      => '商品分类',
                                    'form_name'  => 'category_ids',
                                    'const_key'  => 'goods_category',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'placeholder'  => '请输入关键字',
                                        'type'         => 'text',
                                    ],
                                    'title'      => '关键字',
                                    'form_name'  => 'keywords',
                                ]
                            ],
                        ],
                        'filter_config' => [
                            'data_url'            => MyUrl('admin/diyapi/goodsautodata'),
                            'filter_form_config'  => [
                                [
                                    'type'       => 'select',
                                    'config'     => [
                                        'placeholder'  => '请选择商品分类',
                                        'is_level'     => 1,
                                        'is_multiple'  => 1,
                                        'children'     => 'items',
                                    ],
                                    'title'      => '商品分类',
                                    'form_name'  => 'goods_category_ids',
                                    'const_key'  => 'goods_category',
                                ],
                                [
                                    'type'       => 'select',
                                    'config'     => [
                                        'is_multiple'  => 1,
                                    ],
                                    'title'      => '指定品牌',
                                    'form_name'  => 'goods_brand_ids',
                                    'const_key'  => 'brand_list',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'placeholder'  => '请输入关键字',
                                        'type'         => 'text',
                                    ],
                                    'title'      => '关键字',
                                    'form_name'  => 'goods_keywords',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'default'  => 4,
                                        'type'     => 'number',
                                    ],
                                    'title'      => '显示数量',
                                    'form_name'  => 'goods_number',
                                ],
                                [
                                    'type'       => 'radio',
                                    'title'      => '排序类型',
                                    'form_name'  => 'goods_order_by_type',
                                    'data'       => MyConst('common_goods_order_by_type_list'),
                                    'data_key'   => 'index',
                                    'data_name'  => 'name',
                                    'config'     => [
                                        'default'      => 0,
                                    ]
                                ],
                                [
                                    'type'       => 'radio',
                                    'title'      => '排序规则',
                                    'form_name'  => 'goods_order_by_rule',
                                    'data'       => MyConst('common_data_order_by_rule_list'),
                                    'data_key'   => 'index',
                                    'data_name'  => 'name',
                                    'config'     => [
                                        'default'      => 0,
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name'  => '文章',
                    'type'  => 'article',
                    'data'  => [
                        ['name'=>'数据索引', 'field'=>'data_index', 'type'=>'text'],
                        ['name'=>'文章URL', 'field' =>'url', 'type'=>'link'],
                        ['name'=>'文章ID','field'=>'id', 'type'=>'text'],
                        ['name'=>'标题','field'=>'title', 'type'=>'text'],
                        ['name'=>'分类名称','field'=>'article_category_name', 'type'=>'text'],
                        ['name'=>'描述','field'=>'describe', 'type'=>'text'],
                        ['name'=>'详情内容','field'=>'content', 'type'=>'text'],
                        ['name'=>'封面图片', 'field'=>'cover', 'type'=>'images'],
                        ['name'=>'访问量','field'=>'access_count', 'type'=>'text'],
                        ['name'=>'添加时间','field'=>'add_time', 'type'=>'text'],
                        ['name'=>'更新时间','field'=>'upd_time', 'type'=>'text'],
                    ],
                    'custom_config' => [
                        'appoint_config' => [
                            'data_url'     => MyUrl('admin/diyapi/articlelist'),
                            'is_multiple'  => 1,
                            'show_data'    => [
                                'data_key'   => 'id',
                                'data_name'  => 'title',
                                'data_logo'  => 'cover',
                            ],
                            'popup_title'   => '文章选择',
                            'header' => [
                                [
                                    'field'  => 'id',
                                    'name'   => '文章ID',
                                    'width'  => 120,
                                ],
                                [
                                    'field'  => 'cover',
                                    'name'   => '封面',
                                    'type'   => 'images',
                                    'width'  => 100,
                                ],
                                [
                                    'field'  => 'title',
                                    'name'   => '标题',
                                ],
                                [
                                    'field'  => 'article_category_name',
                                    'name'   => '分类',
                                ],
                            ],
                            'search_filter_form_config' => [
                                [
                                    'type'       => 'select',
                                    'config'     => [
                                        'placeholder'  => '请选择文章分类',
                                        'is_multiple'  => 1,
                                        'children'     => 'items',
                                    ],
                                    'title'      => '文章分类',
                                    'form_name'  => 'category_ids',
                                    'const_key'  => 'article_category',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'placeholder'  => '请输入关键字',
                                        'type'         => 'text',
                                    ],
                                    'title'      => '关键字',
                                    'form_name'  => 'keywords',
                                ]
                            ],
                        ],
                        'filter_config' => [
                            'data_url'            => MyUrl('admin/diyapi/articleautodata'),
                            'filter_form_config'  => [
                                [
                                    'type'       => 'select',
                                    'config'     => [
                                        'is_multiple'  => 1,
                                    ],
                                    'title'      => '文章分类',
                                    'form_name'  => 'article_category_ids',
                                    'const_key'  => 'article_category',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'placeholder'  => '请输入关键字',
                                        'type'         => 'text',
                                    ],
                                    'title'      => '关键字',
                                    'form_name'  => 'article_keywords',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'default'  => 4,
                                        'type'     => 'number',
                                    ],
                                    'title'      => '显示数量',
                                    'form_name'  => 'article_number',
                                ],
                                [
                                    'type'       => 'radio',
                                    'title'      => '排序类型',
                                    'form_name'  => 'article_order_by_type',
                                    'const_key'  => 'article_order_by_type_list',
                                    'data_key'   => 'index',
                                    'data_name'  => 'name',
                                    'config'     => [
                                        'default'      => 0,
                                    ]
                                ],
                                [
                                    'type'       => 'radio',
                                    'title'      => '排序规则',
                                    'form_name'  => 'article_order_by_rule',
                                    'const_key'  => 'data_order_by_rule_list',
                                    'data_key'   => 'index',
                                    'data_name'  => 'name',
                                    'config'     => [
                                        'default'      => 0,
                                    ]
                                ],
                                [
                                    'type'       => 'switch',
                                    'title'      => '封面图片',
                                    'form_name'  => 'article_is_cover',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name'  => '品牌',
                    'type'  => 'brand',
                    'data'  => [
                        ['name'=>'数据索引', 'field'=>'data_index', 'type'=>'text'],
                        ['name'=>'品牌URL', 'field' =>'url', 'type'=>'link'],
                        ['name'=>'品牌ID', 'field'=>'id', 'type'=>'text'],
                        ['name'=>'LOGO', 'field'=>'logo', 'type'=>'images'],
                        ['name'=>'名称', 'field'=>'name', 'type'=>'text'],
                        ['name'=>'描述', 'field'=>'describe', 'type'=>'text'],
                        ['name'=>'所属分类', 'field'=>'brand_category_text', 'type'=>'text'],
                        ['name'=>'添加时间', 'field'=>'add_time', 'type'=>'text'],
                        ['name'=>'更新时间', 'field'=>'upd_time', 'type'=>'text'],
                    ],
                    'custom_config' => [
                        'appoint_config' => [
                            'data_url'     => MyUrl('admin/diyapi/brandlist'),
                            'is_multiple'  => 1,
                            'show_data'    => [
                                'data_key'   => 'id',
                                'data_name'  => 'name',
                                'data_logo'  => 'logo',
                            ],
                            'popup_title'   => '品牌选择',
                            'header' => [
                                [
                                    'field'  => 'id',
                                    'name'   => '品牌ID',
                                    'width'  => 120,
                                ],
                                [
                                    'field'  => 'logo',
                                    'name'   => 'LOGO',
                                    'type'   => 'images',
                                    'width'  => 100,
                                ],
                                [
                                    'field'  => 'name',
                                    'name'   => '名称',
                                ],
                                [
                                    'field'  => 'brand_category_text',
                                    'name'   => '分类',
                                ],
                            ],
                            'search_filter_form_config' => [
                                [
                                    'type'       => 'select',
                                    'config'     => [
                                        'placeholder'  => '请选择品牌分类',
                                        'is_multiple'  => 1,
                                        'children'     => 'items',
                                    ],
                                    'title'      => '品牌分类',
                                    'form_name'  => 'category_ids',
                                    'const_key'  => 'brand_category',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'placeholder'  => '请输入关键字',
                                        'type'         => 'text',
                                    ],
                                    'title'      => '关键字',
                                    'form_name'  => 'keywords',
                                ]
                            ],
                        ],
                        'filter_config' => [
                            'data_url'            => MyUrl('admin/diyapi/brandautodata'),
                            'filter_form_config'  => [
                                [
                                    'type'       => 'select',
                                    'config'     => [
                                        'is_multiple'  => 1,
                                    ],
                                    'title'      => '品牌分类',
                                    'form_name'  => 'brand_category_ids',
                                    'const_key'  => 'brand_category',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'placeholder'  => '请输入关键字',
                                        'type'         => 'text',
                                    ],
                                    'title'      => '关键字',
                                    'form_name'  => 'brand_keywords',
                                ],
                                [
                                    'type'    => 'input',
                                    'config'  => [
                                        'default'  => 4,
                                        'type'     => 'number',
                                    ],
                                    'title'      => '显示数量',
                                    'form_name'  => 'brand_number',
                                ],
                                [
                                    'type'       => 'radio',
                                    'title'      => '排序类型',
                                    'form_name'  => 'brand_order_by_type',
                                    'const_key'  => 'brand_order_by_type_list',
                                    'data_key'   => 'index',
                                    'data_name'  => 'name',
                                    'config'     => [
                                        'default'      => 0,
                                    ]
                                ],
                                [
                                    'type'       => 'radio',
                                    'title'      => '排序规则',
                                    'form_name'  => 'brand_order_by_rule',
                                    'const_key'  => 'data_order_by_rule_list',
                                    'data_key'   => 'index',
                                    'data_name'  => 'name',
                                    'config'     => [
                                        'default'      => 0,
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // diy自定义初始化钩子
        $hook_name = 'plugins_service_diyapi_custom_init';
        MyEventTrigger($hook_name, [
            'hook_name'   => $hook_name,
            'is_backend'  => true,
            'data'        => &$data,
            'params'      => $params,
        ]);
        return DataReturn('success', 0, $data);
    }
}
?>