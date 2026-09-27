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
namespace app\api\controller;

use app\service\ApiService;
use app\service\AppService;
use app\service\SystemBaseService;
use app\service\GoodsService;
use app\service\GoodsCategoryService;
use app\service\BuyService;
use app\service\GoodsCommentsService;
use app\service\ResourcesService;
use app\service\GoodsFavorService;
use app\service\GoodsBrowseService;
use app\service\GoodsCartService;

/**
 * 商品
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
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

    /**
     * 获取商品详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-12
     * @desc    description
     */
    public function Detail()
    {
        // 参数
        $goods_id = empty($this->data_request['id']) ? (empty($this->data_request['goods_id']) ? 0 : intval($this->data_request['goods_id'])) : intval($this->data_request['id']);
        if(empty($goods_id))
        {
            $ret = DataReturn(MyLang('params_error_tips'), -1);
        } else {
            // 是否独立手机端详情
            $is_use_mobile_detail = intval(MyC('common_app_is_use_mobile_detail', 0, true));
            $user_id = !empty($this->user) && !empty($this->user['id']) ? intval($this->user['id']) : 0;

            // 获取商品（key 带 user_id，避免会员价等被其他用户缓存串用；收藏状态仍实时覆盖）
            $goods = MyCacheRemember('cache_api_goods_detail_goods_'.$goods_id.'_'.$is_use_mobile_detail.'_'.$user_id, function() use ($goods_id, $is_use_mobile_detail) {
                $params = [
                    'where'           => [
                        ['id', '=', $goods_id],
                        ['is_delete_time', '=', 0],
                    ],
                    'is_content_app'  => $is_use_mobile_detail,
                    'is_spec'         => 1,
                    'is_params'       => 1,
                    'is_favor'        => 0,
                ];
                $ret = GoodsService::GoodsList($params);
                if(empty($ret['data'][0]))
                {
                    return [];
                }
                $goods = $ret['data'][0];
                if($is_use_mobile_detail == 1)
                {
                    unset($goods['content_web']);
                }
                return $goods;
            });
            if(empty($goods) || $goods['is_delete_time'] != 0)
            {
                $ret = DataReturn(MyLang('goods_no_exist_or_delete_error_tips'), -1);
            } else {
                // 用户收藏状态（实时）
                $goods['user_is_favor'] = 0;
                if($user_id > 0)
                {
                    $favor = GoodsService::UserFavorGoodsCountData([$goods_id], ['user'=>$this->user]);
                    $goods['user_is_favor'] = (!empty($favor) && in_array($goods_id, $favor)) ? 1 : 0;
                }

                // 展示商品评分
                if(MyC('common_is_goods_detail_show_comments', 0) == 1)
                {
                    $comments = MyCacheRemember('cache_api_goods_detail_comments_'.$goods_id, function() use ($goods_id) {
                        return [
                            'comments_count' => GoodsCommentsService::GoodsCommentsTotal(['goods_id'=>$goods_id, 'is_show'=>1]),
                            'comments_score' => GoodsCommentsService::GoodsCommentsScore($goods_id),
                            'comments_data'  => GoodsCommentsService::GoodsFirstSeveralComments($goods_id),
                        ];
                    });
                    $goods['comments_count'] = $comments['comments_count'];
                    $goods['comments_score'] = $comments['comments_score'];
                    $goods['comments_data'] = $comments['comments_data'];
                }

                // 商品访问统计
                GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

                // 用户商品浏览
                GoodsBrowseService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

                // 商品所属分类名称
                $goods['category_names'] = MyCacheRemember('cache_api_goods_detail_category_names_'.$goods_id, function() use ($goods_id) {
                    $category = GoodsCategoryService::GoodsCategoryNames($goods_id);
                    return empty($category['data']) ? [] : $category['data'];
                });

                // 中间tabs导航
                $middle_tabs_nav = GoodsService::GoodsDetailMiddleTabsNavList($goods);

                // 导航更多列表
                $nav_more_list = MyCacheRemember('cache_api_goods_detail_nav_more_list_'.$goods_id, function() use ($goods) {
                    return AppService::GoodsNavMoreList(['data'=>$goods, 'page'=>'goods']);
                });

                // 商品底部导航左侧小导航（仅收藏状态区分，0未收藏/1已收藏）
                $buy_left_nav = MyCacheRemember('cache_api_goods_detail_buy_left_nav_'.$goods_id.'_'.intval($goods['user_is_favor']), function() use ($goods) {
                    return GoodsService::GoodsBuyLeftNavList($goods);
                });

                // 商品购买按钮列表（随用户价格/权限变化，key 带 user_id）
                $buy_button = MyCacheRemember('cache_api_goods_detail_buy_button_'.$goods_id.'_'.$user_id, function() use ($goods) {
                    return GoodsService::GoodsBuyButtonList($goods);
                });

                // 猜你喜欢
                $guess_you_like = MyCacheRemember('cache_api_goods_detail_guess_you_like_'.$goods_id, function() use ($goods_id) {
                    return GoodsService::GoodsDetailGuessYouLikeData($goods_id);
                });

                // 数据返回
                $result = [
                    'goods'            => $goods,
                    'cart_total'       => GoodsCartService::UserGoodsCartTotal(['user'=>$this->user]),
                    'buy_left_nav'     => $buy_left_nav,
                    'buy_button'       => $buy_button,
                    'middle_tabs_nav'  => $middle_tabs_nav['nav'],
                    'nav_more_list'    => $nav_more_list,
                    'guess_you_like'   => $guess_you_like,
                ];
                $ret = SystemBaseService::DataReturn($result);
            }
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 用户商品收藏
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     */
    public function Favor()
    {
        // 登录校验
        $this->IsLogin();

        // 开始操作
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
        // 开始处理
        $params = $this->data_request;
        $ret = GoodsService::GoodsSpecType($params);
        return ApiService::ApiDataReturn($ret);
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
        $params = $this->data_request;
        $ret = GoodsService::GoodsSpecDetail($params);
        return ApiService::ApiDataReturn($ret);
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
        // 开始处理
        $params = $this->data_request;
        $ret = GoodsService::GoodsStock($params);
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 商品分类
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-14
     * @desc    description
     */
    public function Category()
    {
        $result = [
            'category'  => GoodsCategoryService::GoodsCategoryAll($this->data_request),
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }

    /**
     * 商品评分
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-07-11
     * @desc    description
     */
    public function GoodsScore()
    {
        if(empty($this->data_request['goods_id']))
        {
            $ret = DataReturn(MyLang('params_error_tips'), -1);
        } else {
            // 获取商品评分
            $data = GoodsCommentsService::GoodsCommentsScore($this->data_request['goods_id']);
            $ret = DataReturn('success', 0, $data);
        }
        return ApiService::ApiDataReturn($ret);
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
        // 条件
        $where = [
            'goods_id'      => $this->data_request['goods_id'],
            'is_show'       => 1,
        ];

        // 获取总数
        $total = GoodsCommentsService::GoodsCommentsTotal($where);
        $page_total = ceil($total/$this->page_size);
        $start = intval(($this->page-1)*$this->page_size);

        // 获取列表
        $data_params = [
            'm'         => $start,
            'n'         => $this->page_size,
            'where'     => $where,
            'is_public' => 1,
        ];
        $ret = GoodsCommentsService::GoodsCommentsList($data_params);
        
        // 返回数据
        $result = [
            'number'            => $this->page_size,
            'total'             => $total,
            'page_total'        => $page_total,
            'data'              => $ret['data'],
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }
}
?>