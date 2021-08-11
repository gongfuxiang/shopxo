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

use app\service\GoodsService;
use app\service\GoodsCommentsService;
use app\service\GoodsBrowseService;
use app\service\GoodsFavorService;
use app\service\SeoService;

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
                'id'                => $goods_id,
                'is_delete_time'    => 0,
            ],
            'is_photo'  => true,
            'is_spec'   => true,
            'is_params' => true,
        ];
        $ret = GoodsService::GoodsList($params);
        if(empty($ret['data'][0]) || $ret['data'][0]['is_delete_time'] != 0)
        {
            MyViewAssign('msg', '资源不存在或已被删除');
            return MyView('/public/tips_error');
        } else {
            // 商品信息
            $goods = $ret['data'][0];

            // 当前登录用户是否已收藏
            $ret_favor = GoodsFavorService::IsUserGoodsFavor(['goods_id'=>$goods_id, 'user'=>$this->user]);
            $goods['is_favor'] = ($ret_favor['code'] == 0) ? $ret_favor['data'] : 0;

            // 商品评价总数
            $goods['comments_count'] = GoodsCommentsService::GoodsCommentsTotal(['goods_id'=>$goods_id, 'is_show'=>1]);

            // 商品收藏总数
            $goods['favor_count'] = GoodsFavorService::GoodsFavorTotal(['goods_id'=>$goods_id]);

            // 钩子
            $this->PluginsHook($goods_id, $goods);

            // 商品数据
            MyViewAssign('goods', $goods);

            // seo
            $seo_title = empty($goods['seo_title']) ? $goods['title'] : $goods['seo_title'];
            MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle($seo_title, 2));
            if(!empty($goods['seo_keywords']))
            {
                MyViewAssign('home_seo_site_keywords', $goods['seo_keywords']);
            }
            if(!empty($goods['seo_desc']))
            {
                MyViewAssign('home_seo_site_description', $goods['seo_desc']);
            }

            // 二维码
            $qrcode = GoodsService::GoodsQrcode($goods_id, $goods['add_time']);
            $qrcode_url = ($qrcode['code'] == 0 && isset($qrcode['data']['url'])) ? $qrcode['data']['url'] : '';
            MyViewAssign('qrcode_url', $qrcode_url);

            // 商品访问统计
            GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

            // 用户商品浏览
            GoodsBrowseService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

            // 商品购买按钮列表
            $buy_button = GoodsService::GoodsBuyButtonList($goods);
            MyViewAssign('buy_button', $buy_button);

            // 中间tabs导航
            $middle_tabs_nav = GoodsService::GoodsDetailMiddleTabsNavList($goods);
            MyViewAssign('middle_tabs_nav', $middle_tabs_nav);

            // 详情商品评分
            if(!empty($middle_tabs_nav) && in_array('comments', $middle_tabs_nav['type']))
            {
                $goods_score = GoodsCommentsService::GoodsCommentsScore($goods_id);
                MyViewAssign('goods_score', $goods_score['data']);
            }

            // 详情tab商品 猜你喜欢
            if(!empty($middle_tabs_nav) && in_array('guess_you_like', $middle_tabs_nav['type']))
            {
                $params = [
                    'where'     => [
                        'is_delete_time'    => 0,
                        'is_shelves'        => 1,
                    ],
                    'order_by'  => 'sales_count desc',
                    'field'     => 'id,title,title_color,price,images',
                    'n'         => 16,
                ];
                $like_goods = GoodsService::GoodsList($params);
                MyViewAssign('detail_like_goods', $like_goods['data']);
            }

            // 左侧商品 看了又看
            $params = [
                'where'     => [
                    'is_delete_time'=>0,
                    'is_shelves'=>1
                ],
                'order_by'  => 'access_count desc',
                'field'     => 'id,title,title_color,price,images',
                'n'         => 10,
            ];
            $right_goods = GoodsService::GoodsList($params);
            MyViewAssign('left_goods', $right_goods['data']);

            // 是否商品详情页展示相册
            MyViewAssign('common_is_goods_detail_show_photo', MyC('common_is_goods_detail_show_photo', 0, true));

            // 加载放大镜
            MyViewAssign('is_load_imagezoom', 1);

            return MyView();
        }
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }
        
        // 是否登录
        $this->IsLogin();

        // 开始处理
        $params = input('post.');
        $params['user'] = $this->user;
        return GoodsFavorService::GoodsFavorCancel($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input('post.');
        return GoodsService::GoodsSpecType($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input('post.');
        return GoodsService::GoodsSpecDetail($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 参数
        $params = input();
        if(empty($params['goods_id']))
        {
            return DataReturn('参数有误', -1);
        }

        // 分页
        $number = 10;
        $page = max(1, isset($params['page']) ? intval($params['page']) : 1);

        // 条件
        $where = [
            'goods_id'      => $params['goods_id'],
            'is_show'       => 1,
        ];

        // 获取总数
        $total = GoodsCommentsService::GoodsCommentsTotal($where);
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);

        // 获取列表
        $data_params = array(
            'm'         => $start,
            'n'         => $number,
            'where'     => $where,
            'is_public' => 1,
        );
        $data = GoodsCommentsService::GoodsCommentsList($data_params);

        // 返回数据
        $result = [
            'number'            => $number,
            'total'             => $total,
            'page_total'        => $page_total,
            'data'              => MyView('', ['data'=>$data['data']]),
        ];
        return DataReturn('请求成功', 0, $result);
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

            // 商品页面基础信息面板售价顶部钩子
            'plugins_view_goods_detail_panel_price_top',

            // 商品页面基础信息购买小导航内部前面钩子
            'plugins_view_goods_detail_base_buy_nav_min_inside_begin',

            // 商品页面基础信息购买小导航内部中间钩子
            'plugins_view_goods_detail_base_buy_nav_min_inside',
        ];
        foreach($hook_arr as $hook_name)
        {
            MyViewAssign($hook_name.'_data', MyEventTrigger($hook_name,
                [
                    'hook_name'    => $hook_name,
                    'is_backend'   => false,
                    'goods_id'     => $goods_id,
                    'goods'        => &$goods,
                ]));
        }
    }
}
?>