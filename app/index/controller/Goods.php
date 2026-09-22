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
namespace app\index\controller;

use app\index\controller\Common;
use app\service\ApiService;
use app\service\SeoService;
use app\service\GoodsService;
use app\service\I18nService;
use app\service\GoodsCommentsService;
use app\service\GoodsBrowseService;
use app\service\GoodsFavorService;
use app\service\GoodsCartService;
use app\service\BreadcrumbService;

/**
 * 商品详情
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Goods extends Common
{
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
    }

    /**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-12-02T23:42:49+0800
     */
    public function Index()
    {
        $goods_id = isset($this->data_request['id']) ? intval($this->data_request['id']) : 0;
        if($goods_id <= 0)
        {
            MyViewAssign('msg', MyLang('goods.goods_no_data_tips'));
            return MyView('/public/tips_error');
        }

        $user_id = !empty($this->user) && !empty($this->user['id']) ? intval($this->user['id']) : 0;

        // 获取商品（缓存不含用户收藏状态）
        $goods = MyCacheRemember('cache_index_goods_detail_goods_'.$goods_id, function() use ($goods_id) {
            $params = [
                'where' => [
                    ['id', '=', $goods_id],
                    ['is_delete_time', '=', 0],
                ],
                'is_photo'  => 1,
                'is_spec'   => 1,
                'is_params' => 1,
                'is_favor'  => 0,
            ];
            $ret = GoodsService::GoodsList($params);
            return (empty($ret['data']) || empty($ret['data'][0])) ? [] : $ret['data'][0];
        });
        if(empty($goods))
        {
            MyViewAssign('msg', MyLang('goods.goods_no_data_tips'));
            return MyView('/public/tips_error');
        }

        // 用户收藏状态（实时）
        $goods['user_is_favor'] = 0;
        if($user_id > 0)
        {
            $favor = GoodsService::UserFavorGoodsCountData([$goods_id], ['user'=>$this->user]);
            $goods['user_is_favor'] = (!empty($favor) && in_array($goods_id, $favor)) ? 1 : 0;
        }

        // 商品评价/收藏统计
        $goods_stats = MyCacheRemember('cache_index_goods_detail_stats_'.$goods_id, function() use ($goods_id) {
            return [
                'comments_count' => GoodsCommentsService::GoodsCommentsTotal(['goods_id'=>$goods_id, 'is_show'=>1]),
                'favor_count'    => GoodsFavorService::GoodsFavorTotal(['goods_id'=>$goods_id]),
                'goods_score'    => GoodsCommentsService::GoodsCommentsScore($goods_id),
            ];
        });
        $goods['comments_count'] = $goods_stats['comments_count'];
        $goods['favor_count'] = $goods_stats['favor_count'];

        // 模板数据
        $assign = [
            // 商品信息
            'goods'              => $goods,
            // 商品底部导航左侧小导航（仅收藏状态区分，0未收藏/1已收藏）
            'buy_left_nav'       => MyCacheRemember('cache_index_goods_detail_buy_left_nav_'.$goods_id.'_'.intval($goods['user_is_favor']), function() use ($goods) {
                return GoodsService::GoodsBuyLeftNavList($goods);
            }),
            // 商品购买按钮列表
            'buy_button'         => MyCacheRemember('cache_index_goods_detail_buy_button_'.$goods_id, function() use ($goods) {
                return GoodsService::GoodsBuyButtonList($goods);
            }),
            // 商品购买指向链接数据
            'buy_to_link'        => MyCacheRemember('cache_index_goods_detail_buy_to_link_'.$goods_id, function() use ($goods) {
                return GoodsService::GoodsBuyToLinkData($goods);
            }),
            // 中间tabs导航
            'middle_tabs_nav'    => GoodsService::GoodsDetailMiddleTabsNavList($goods),
            // 面包屑导航
            'breadcrumb_data'    => MyCacheRemember('cache_index_goods_detail_breadcrumb_'.$goods_id, function() use ($goods) {
                return BreadcrumbService::Data('GoodsDetail', ['goods'=>$goods]);
            }),
            // 加载放大镜
            'is_load_imagezoom'  => 1,
            // 加载视频播放器组件
            'is_load_ckplayer'   => 1,
        ];
        // 是否商品详情页展示相册
        $assign['common_is_goods_detail_content_show_photo'] = MyC('common_is_goods_detail_content_show_photo', 0, true);
        // 商品详情规格是否页内直选（与 UniApp 共用配置：0不内嵌/1一层/2多层）
        $spec_page_show = (int) MyC('common_goods_detail_spec_page_show', 0, true);
        $is_goods_spec_page_inline = 0;
        if($spec_page_show > 0 && isset($goods['is_exist_many_spec']) && intval($goods['is_exist_many_spec']) == 1)
        {
            $spec_level = (!empty($goods['specifications']['choose']) && is_array($goods['specifications']['choose'])) ? count($goods['specifications']['choose']) : 0;
            if($spec_level > 0)
            {
                if(($spec_page_show == 1 && $spec_level == 1) || $spec_page_show == 2)
                {
                    $is_goods_spec_page_inline = 1;
                }
            }
        }
        $assign['common_goods_detail_spec_page_show'] = $spec_page_show;
        $assign['is_goods_spec_page_inline'] = $is_goods_spec_page_inline;

        // tabs菜单数据处理
        if(!empty($assign['middle_tabs_nav']) && !empty($assign['middle_tabs_nav']['type']))
        {
            // 详情商品评分
            if(in_array('comments', $assign['middle_tabs_nav']['type']))
            {
                $assign['goods_score'] = $goods_stats['goods_score'];
            }

            // 详情tab商品 猜你喜欢
            if(in_array('guess_you_like', $assign['middle_tabs_nav']['type']))
            {
                $assign['guess_you_like'] = MyCacheRemember('cache_index_goods_detail_guess_you_like_'.$goods_id, function() use ($goods_id) {
                    return GoodsService::GoodsDetailGuessYouLikeData($goods_id, ['is_spec'=>0, 'is_cart'=>0]);
                });
            }
        }

        // 左侧商品 看了又看
        $assign['left_goods'] = MyCacheRemember('cache_index_goods_detail_seeing_you_'.$goods_id, function() use ($goods_id) {
            return GoodsService::GoodsDetailSeeingYouData($goods_id, ['is_spec'=>0, 'is_cart'=>0]);
        });

        // 商品访问统计
        GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

        // 用户商品浏览
        GoodsBrowseService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

        // seo
        $seo_title = empty($goods['seo_title']) ? $goods['title'] : $goods['seo_title'];
        $assign['home_seo_site_title'] = SeoService::BrowserSeoTitle($seo_title, 2);
        if(!empty($goods['seo_keywords']))
        {
            $assign['home_seo_site_keywords'] = $goods['seo_keywords'];
        }
        if(!empty($goods['seo_desc']) || !empty($goods['simple_desc']))
        {
            $assign['home_seo_site_description'] = empty($goods['seo_desc']) ? $goods['simple_desc'] : $goods['seo_desc'];
        }

        // 数据赋值
        MyViewAssign($assign);
        // 钩子
        $this->PluginsHook($goods_id, $goods);
        return MyView();
    }

    /**
     * 加入购物车页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function CartInfo()
    {
        $goods_id = isset($this->data_request['id']) ? $this->data_request['id'] : 0;
        $cart_id = isset($this->data_request['cart_id']) ? intval($this->data_request['cart_id']) : 0;
        $params = [
            'where' => [
                ['id', '=', $goods_id],
                ['is_delete_time', '=', 0],
            ],
            'is_spec'   => 1,
        ];
        $ret = GoodsService::GoodsList($params);
        if(!empty($ret['data']) && !empty($ret['data'][0]))
        {
            $goods = $ret['data'][0];
            $buy_button = GoodsService::GoodsBuyButtonList($goods);

            // 购物车改规格模式
            $cart = null;
            if($cart_id > 0 && !empty($this->user['id']))
            {
                $cart_row = \think\facade\Db::name('Cart')->where([
                    'id'        => $cart_id,
                    'user_id'   => $this->user['id'],
                    'goods_id'  => $goods_id,
                ])->find();
                if(!empty($cart_row))
                {
                    $cart_row['spec'] = empty($cart_row['spec']) ? [] : json_decode($cart_row['spec'], true);
                    if(!empty($cart_row['spec']) && is_array($cart_row['spec']))
                    {
                        $cart_spec_keys = array_column($cart_row['spec'], 'key');
                        if(count(array_filter($cart_spec_keys)) == count($cart_spec_keys))
                        {
                            $cart_row['spec'] = I18nService::SpecBaseSpecResolve($goods_id, $cart_row['spec']);
                        }
                        $cart_row['spec_show'] = I18nService::SpecShowData($goods_id, $cart_row['spec']);
                    } else {
                        $cart_row['spec_show'] = [];
                    }
                    $cart = $cart_row;
                }
            }

            // 回显已选规格（优先 key，其次显示值）
            $cart_appoint_spec = [];
            if(!empty($cart['spec']) && is_array($cart['spec']))
            {
                $show_values = [];
                if(!empty($cart['spec_show']) && is_array($cart['spec_show']))
                {
                    $show_values = array_column($cart['spec_show'], 'value');
                }
                foreach($cart['spec'] as $si=>$sv)
                {
                    $cart_appoint_spec[] = [
                        'key'   => isset($sv['key']) ? $sv['key'] : '',
                        'value' => isset($show_values[$si]) ? $show_values[$si] : (isset($sv['value']) ? $sv['value'] : ''),
                    ];
                }
            }

            MyViewAssign([
                'goods'             => $goods,
                'buy_button'        => $buy_button,
                'cart'              => $cart,
                'is_cart_spec'      => (!empty($cart) && !empty($cart['id'])) ? 1 : 0,
                'cart_appoint_spec' => $cart_appoint_spec,
                'is_header'         => 0,
                'is_footer'         => 0,
            ]);
            return MyView();
        }
        MyViewAssign([
            'msg'           => MyLang('goods.goods_no_data_tips'),
            'is_header'     => 0,
            'is_footer'     => 0,
            'is_to_home'    => 0,
        ]);
        return MyView('/public/tips_error');
    }

    /**
     * 商品收藏
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-13
     * @desc    description
     */
    public function Favor()
    {
        // 是否登录
        IsUserLogin();

        // 开始处理
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(GoodsFavorService::GoodsFavorCancel($params));
    }

    /**
     * 商品规格类型
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-14
     * @desc    description
     */
    public function SpecType()
    {
        $params = $this->data_request;
        return ApiService::ApiDataReturn(GoodsService::GoodsSpecType($params));
    }

    /**
     * 商品规格信息
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-14
     * @desc    description
     */
    public function SpecDetail()
    {
        $params = $this->data_request;
        return ApiService::ApiDataReturn(GoodsService::GoodsSpecDetail($params));
    }

    /**
     * 商品数量选择
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-14
     * @desc    description
     */
    public function Stock()
    {
        $params = $this->data_request;
        return ApiService::ApiDataReturn(GoodsService::GoodsStock($params));
    }

    /**
     * 商品评论
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-13T21:47:41+0800
     */
    public function Comments()
    {
        // 参数
        $params = $this->data_request;
        if(empty($params['goods_id']))
        {
            return ApiService::ApiDataReturn(DataReturn(MyLang('params_error_tips'), -1));
        }

        // 条件
        $where = [
            'goods_id'  => $params['goods_id'],
            'is_show'   => 1,
        ];

        // 获取总数
        $total = GoodsCommentsService::GoodsCommentsTotal($where);
        $page_total = ceil($total/$this->page_size);
        $start = intval(($this->page-1)*$this->page_size);

        // 获取列表
        $data = [];
        if($total > 0)
        {
            $data_params = [
                'm'         => $start,
                'n'         => $this->page_size,
                'where'     => $where,
                'is_public' => 1,
            ];
            $ret = GoodsCommentsService::GoodsCommentsList($data_params);
            if(!empty($ret['data']))
            {
                $data = $ret['data'];
            }
        }

        // 返回数据
        $result = [
            'number'            => $this->page_size,
            'total'             => $total,
            'page_total'        => $page_total,
            'data'              => MyView('', ['data'=>$data]),
        ];
        return ApiService::ApiDataReturn(DataReturn('success', 0, $result));
    }

    /**
     * 二维码数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-02-28
     * @desc    description
     */
    public function QrcodeData()
    {
        if(!empty($this->data_request['id']))
        {
            $params = [
                'where' => [
                    ['id', '=', $this->data_request['id']],
                    ['is_delete_time', '=', 0],
                ],
                'is_photo'  => 1,
                'is_spec'   => 1,
                'is_params' => 1,
                'is_favor'  => 1,
            ];
            $ret = GoodsService::GoodsList($params);
            if(!empty($ret['data']) && !empty($ret['data'][0]))
            {
                return ApiService::ApiDataReturn(GoodsService::GoodsQrcode($ret['data'][0], $this->user));
            }
        }
        return ApiService::ApiDataReturn(DataReturn(MyLang('no_goods'), -1));
    }

    /**
     * 钩子处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-22
     * @desc    description
     * @param   [int]             $goods_id [商品id]
     * @param   [array]           $params   [输入参数]
     */
    private function PluginsHook($goods_id, &$goods)
    {
        $hook_arr = [
            // 商品页面相册内部钩子
            'plugins_view_goods_detail_photo_within',

            // 商品页面相册底部钩子
            'plugins_view_goods_detail_photo_bottom',

            // 商品页面基础信息顶部钩子
            'plugins_view_goods_detail_base_top',

            // 商品页面基础信息面板底部钩子
            'plugins_view_goods_detail_panel_bottom',

            // 商品页面规格顶部钩子
            'plugins_view_goods_detail_base_sku_top',

            // 商品页面库存数量顶部钩子
            'plugins_view_goods_detail_base_inventory_top',

            // 商品页面库存数量底部钩子
            'plugins_view_goods_detail_base_inventory_bottom',

            // 商品页面购买导航顶部钩子
            'plugins_view_goods_detail_buy_nav_top',

            // 商品页右侧内容顶部钩子
            'plugins_view_goods_detail_right_content_top',

            // 商品页右侧内容底部钩子
            'plugins_view_goods_detail_right_content_bottom',

            // 商品页右侧内容内部顶部钩子
            'plugins_view_goods_detail_right_content_inside_top',

            // 商品页右侧内容内部底部钩子
            'plugins_view_goods_detail_right_content_inside_bottom',

            // 商品页基础信息底部钩子
            'plugins_view_goods_detail_base_bottom',

            // 商品页面tabs顶部钩子
            'plugins_view_goods_detail_tabs_top',

            // 商品页面tabs顶部钩子
            'plugins_view_goods_detail_tabs_content',

            // 商品页面tabs内评价顶部钩子
            'plugins_view_goods_detail_tabs_comments_top',

            // 商品页面tabs内评价底部钩子
            'plugins_view_goods_detail_tabs_comments_bottom',

            // 商品页面tabs内猜你喜欢顶部钩子
            'plugins_view_goods_detail_tabs_guess_like_top',

            // 商品页面tabs内猜你喜欢底部钩子
            'plugins_view_goods_detail_tabs_guess_like_bottom',

            // 商品页面tabs内容钩子
            'plugins_view_goods_detail_tabs_bottom',

            // 详情内容顶部钩子
            'plugins_view_goods_detail_content_top',

            // 详情内容底部钩子
            'plugins_view_goods_detail_content_bottom',

            // 商品页面左侧顶部钩子
            'plugins_view_goods_detail_left_top',

            // 商品页面基础信息标题里面钩子
            'plugins_view_goods_detail_title',

            // 商品页面基础信息面板原价顶部钩子
            'plugins_view_goods_detail_panel_original_price_top',

            // 商品页面基础信息面板售价顶部钩子
            'plugins_view_goods_detail_panel_price_top',

            // 商品页面基础信息面板售价底部钩子
            'plugins_view_goods_detail_panel_price_bottom',

            // 商品页面基础信息购买小导航内部前面钩子
            'plugins_view_goods_detail_base_buy_nav_min_inside_begin',

            // 商品页面基础信息购买小导航内部中间钩子
            'plugins_view_goods_detail_base_buy_nav_min_inside',
        ];
        $assign = [];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'   => false,
                    'goods_id'     => $goods_id,
                    'goods'        => &$goods,
                ]);
        }
        MyViewAssign($assign);
    }
}
?>