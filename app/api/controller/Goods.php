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
use app\service\SystemBaseService;
use app\service\GoodsService;
use app\service\BuyService;
use app\service\GoodsCommentsService;
use app\service\ResourcesService;
use app\service\GoodsFavorService;
use app\service\GoodsBrowseService;

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
     * [__construct 构造方法]
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
        if(empty($this->data_post['goods_id']))
        {
            $ret = DataReturn('参数有误', -1);
        } else {
            // 商品详情方式
            $is_use_mobile_detail = intval(MyC('common_app_is_use_mobile_detail'));

            // 获取商品
            $goods_id = intval($this->data_post['goods_id']);
            $params = [
                'where' => [
                    'id' => $goods_id,
                    'is_delete_time' => 0,
                ],
                'is_photo'          => true,
                'is_spec'           => true,
                'is_params'         => true,
                'is_content_app'    => ($is_use_mobile_detail == 1),
            ];
            $ret = GoodsService::GoodsList($params);
            if(empty($ret['data'][0]) || $ret['data'][0]['is_delete_time'] != 0)
            {
                $ret = DataReturn('商品不存在或已删除', -1);
            } else {
                // 商品信息
                $goods = $ret['data'][0];

                // 商品详情处理
                if($is_use_mobile_detail == 1)
                {
                    unset($goods['content_web']);
                } else {
                    // 标签处理，兼容小程序rich-text
                    $goods['content_web'] = ResourcesService::ApMiniRichTextContentHandle($goods['content_web']);
                }

                // 当前登录用户是否已收藏
                $ret_favor = GoodsFavorService::IsUserGoodsFavor(['goods_id'=>$goods_id, 'user'=>$this->user]);
                $goods['is_favor'] = ($ret_favor['code'] == 0) ? $ret_favor['data'] : 0;

                // 商品评价总数
                $goods['comments_count'] = GoodsCommentsService::GoodsCommentsTotal(['goods_id'=>$goods_id, 'is_show'=>1]);

                // 商品访问统计
                GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

                // 用户商品浏览
                GoodsBrowseService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

                // 商品所属分类名称
                $category = GoodsService::GoodsCategoryNames($goods_id);
                $goods['category_names'] = $category['data'];

                // 中间tabs导航
                $middle_tabs_nav = GoodsService::GoodsDetailMiddleTabsNavList($goods);

                // 商品购买按钮列表
                $buy_button = GoodsService::GoodsBuyButtonList($goods);

                // 数据返回
                $result = [
                    'goods'                 => $goods,
                    'common_cart_total'     => BuyService::UserCartTotal(['user'=>$this->user]),
                    'buy_button'            => $buy_button,
                    'middle_tabs_nav'       => $middle_tabs_nav,
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
        $params = $this->data_post;
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
        $params = $this->data_post;
        $ret = GoodsService::GoodsSpecType($params);
        if($ret['code'] == 0)
        {
            $ret['data'] = $ret['data']['spec_type'];
        }
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
        $params = $this->data_post;
        $ret = GoodsService::GoodsSpecDetail($params);
        if($ret['code'] == 0)
        {
            $ret['data'] = $ret['data']['spec_base'];
        }
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
            'category'  => GoodsService::GoodsCategoryAll($this->data_post),
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
        if(empty($this->data_post['goods_id']))
        {
            $ret = DataReturn('参数有误', -1);
        } else {
            // 获取商品评分
            $ret = GoodsCommentsService::GoodsCommentsScore($this->data_post['goods_id']);
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
        // 参数
        $params = $this->data_post;

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
        $ret = GoodsCommentsService::GoodsCommentsList($data_params);
        
        // 返回数据
        $result = [
            'number'            => $number,
            'total'             => $total,
            'page_total'        => $page_total,
            'data'              => $ret['data'],
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }
}
?>