<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\index\controller;

use think\facade\Hook;
use app\service\GoodsService;
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
        $id = input('id');
        $params = [
            'where' => [
                'id'    => $id,
                'is_delete_time' => 0,
            ],
            'is_photo'  => true,
            'is_spec'   => true,
        ];
        $ret = GoodsService::GoodsList($params);
        if(empty($ret['data'][0]) || $ret['data'][0]['is_delete_time'] != 0)
        {
            $this->assign('msg', '资源不存在或已被删除');
            return $this->fetch('/public/tips_error');
        } else {
            // 当前登录用户是否已收藏
            $ret_favor = GoodsService::IsUserGoodsFavor(['goods_id'=>$id, 'user'=>$this->user]);
            $ret['data'][0]['is_favor'] = ($ret_favor['code'] == 0) ? $ret_favor['data'] : 0;

            // 商品评价总数
            $ret['data'][0]['comments_count'] = GoodsService::GoodsCommentsTotal($id);

            // 商品收藏总数
            $ret['data'][0]['favor_count'] = GoodsService::GoodsFavorTotal(['goods_id'=>$id]);

            // 商品页面基础信息顶部钩子
            $this->assign('plugins_view_goods_detail_base_top_data', Hook::listen('plugins_view_goods_detail_base_top',
                [
                    'hook_name'    => 'plugins_view_goods_detail_base_top',
                    'is_backend'    => false,
                    'goods_id'      => $id,
                    'goods'         => &$ret['data'][0],
                ]));

            // 商品页面基础信息面板底部钩子
            $this->assign('plugins_view_goods_detail_panel_bottom_data', Hook::listen('plugins_view_goods_detail_panel_bottom',
                [
                    'hook_name'    => 'plugins_view_goods_detail_panel_bottom',
                    'is_backend'    => false,
                    'goods_id'      => $id,
                    'goods'         => &$ret['data'][0],
                ]));

            // 商品页面tabs顶部钩子
            $this->assign('plugins_view_goods_detail_tabs_top_data', Hook::listen('plugins_view_goods_detail_tabs_top',
                [
                    'hook_name'    => 'plugins_view_goods_detail_tabs_top',
                    'is_backend'    => false,
                    'goods_id'      => $id,
                    'goods'         => &$ret['data'][0],
                ]));

            // 商品页面tabs顶部钩子
            $this->assign('plugins_view_goods_detail_tabs_bottom_data', Hook::listen('plugins_view_goods_detail_tabs_bottom',
                [
                    'hook_name'    => 'plugins_view_goods_detail_tabs_bottom',
                    'is_backend'    => false,
                    'goods_id'      => $id,
                    'goods'         => &$ret['data'][0],
                ]));

            // 商品页面左侧顶部钩子
            $this->assign('plugins_view_goods_detail_left_top_data', Hook::listen('plugins_view_goods_detail_left_top',
                [
                    'hook_name'    => 'plugins_view_goods_detail_left_top',
                    'is_backend'    => false,
                    'goods_id'      => $id,
                    'goods'         => &$ret['data'][0],
                ]));

            // 商品数据
            $this->assign('goods', $ret['data'][0]);

            // 浏览器名称
            $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle($ret['data'][0]['title']));

            // 二维码
            $this->assign('qrcode_url', MyUrl('index/qrcode/index', ['content'=>urlencode(base64_encode(MyUrl('index/goods/index', ['id'=>$id], true, true)))]));

            // 商品访问统计
            GoodsService::GoodsAccessCountInc(['goods_id'=>$id]);

            // 用户商品浏览
            GoodsService::GoodsBrowseSave(['goods_id'=>$id, 'user'=>$this->user]);

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
            $this->assign('left_goods', $right_goods['data']);

            // 详情tab商品 猜你喜欢
            $params = [
                'where'     => [
                    'is_delete_time'=>0,
                    'is_shelves'=>1,
                    'is_home_recommended'=>1,
                ],
                'order_by'  => 'sales_count desc',
                'field'     => 'id,title,title_color,price,images,home_recommended_images',
                'n'         => 16,
            ];
            $like_goods = GoodsService::GoodsList($params);
            $this->assign('detail_like_goods', $like_goods['data']);

            return $this->fetch();
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
        // 是否登录
        $this->IsLogin();

        // 开始处理
        $params = input('post.');
        $params['user'] = $this->user;
        return GoodsService::GoodsFavor($params);
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
        // 开始处理
        $params = input('post.');
        return GoodsService::GoodsSpecDetail($params);
    }
}
