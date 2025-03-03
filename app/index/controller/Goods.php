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

use app\service\ApiService;
use app\service\SeoService;
use app\service\GoodsService;
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
        $goods_id = isset($this->data_request['id']) ? $this->data_request['id'] : 0;
        $params = [
            'where' => [
                ['id', '=', $goods_id],
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
            // 商品信息
            $goods = $ret['data'][0];

            // 商品评价总数
            $goods['comments_count'] = GoodsCommentsService::GoodsCommentsTotal(['goods_id'=>$goods_id, 'is_show'=>1]);

            // 商品收藏总数
            $goods['favor_count'] = GoodsFavorService::GoodsFavorTotal(['goods_id'=>$goods_id]);

            // 模板数据
            $assign = [
                // 商品信息
                'goods'             => $goods,
                // 商品底部导航左侧小导航
                'buy_left_nav'      => GoodsService::GoodsBuyLeftNavList($goods),
                // 商品购买按钮列表
                'buy_button'        => GoodsService::GoodsBuyButtonList($goods),
                // 中间tabs导航
                'middle_tabs_nav'   => GoodsService::GoodsDetailMiddleTabsNavList($goods),
                // 面包屑导航
                'breadcrumb_data'   => BreadcrumbService::Data('GoodsDetail', ['goods'=>$goods]),
                // 加载放大镜
                'is_load_imagezoom' => 1,
                // 加载视频播放器组件
                'is_load_ckplayer'  => 1,
            ];
            // 是否商品详情页展示相册
            $assign['common_is_goods_detail_content_show_photo'] = MyC('common_is_goods_detail_content_show_photo', 0, true);

            // tabs菜单数据处理
            if(!empty($assign['middle_tabs_nav']) && !empty($assign['middle_tabs_nav']['type']))
            {
                // 详情商品评分
                if(in_array('comments', $assign['middle_tabs_nav']['type']))
                {
                    $assign['goods_score'] = GoodsCommentsService::GoodsCommentsScore($goods_id);
                }

                // 详情tab商品 猜你喜欢
                if(!empty($assign['middle_tabs_nav']) && in_array('guess_you_like', $assign['middle_tabs_nav']['type']))
                {
                    $assign['guess_you_like'] = GoodsService::GoodsDetailGuessYouLikeData($goods['id']);
                }
            }

            // 左侧商品 看了又看
            $assign['left_goods'] = GoodsService::GoodsDetailSeeingYouData($goods['id']);

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

            // 二维码
            $qrcode = GoodsService::GoodsQrcode($goods_id, $goods['add_time']);
            $assign['qrcode_url'] = ($qrcode['code'] == 0 && isset($qrcode['data']['url'])) ? $qrcode['data']['url'] : '';

            // 商品访问统计
            GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

            // 用户商品浏览
            GoodsBrowseService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

            // 数据赋值
            MyViewAssign($assign);
            // 钩子
            $this->PluginsHook($goods_id, $goods);
            return MyView();
        }
        MyViewAssign('msg', MyLang('goods.goods_no_data_tips'));
        return MyView('/public/tips_error');
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
            MyViewAssign([
                'goods'         => $goods,
                'buy_button'    => $buy_button,
                'is_header'     => 0,
                'is_footer'     => 0,
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