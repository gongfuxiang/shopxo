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
namespace app\api\controller;

use app\service\GoodsService;
use app\service\BuyService;
use app\service\PluginsService;
use app\service\GoodsCommentsService;
use app\service\ResourcesService;

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
            return DataReturn('参数有误', -1);
        }

        // 商品详情方式
        $is_use_mobile_detail = intval(MyC('common_app_is_use_mobile_detail'));

        // 获取商品
        $goods_id = intval($this->data_post['goods_id']);
        $params = [
            'where' => [
                'id' => $goods_id,
                'is_delete_time' => 0,
            ],
            'is_photo' => true,
            'is_spec' => true,
            'is_content_app' => ($is_use_mobile_detail == 1),
        ];
        $ret = GoodsService::GoodsList($params);
        if(empty($ret['data'][0]) || $ret['data'][0]['is_delete_time'] != 0)
        {
            return DataReturn('商品不存在或已删除', -1);
        }

        // 商品详情处理
        if($is_use_mobile_detail == 1)
        {
            unset($ret['data'][0]['content_web']);
        } else {
            // 标签处理，兼容小程序rich-text
            $ret['data'][0]['content_web'] = ResourcesService::ApMiniRichTextContentHandle($ret['data'][0]['content_web']);
        }

        // 当前登录用户是否已收藏
        $ret_favor = GoodsService::IsUserGoodsFavor(['goods_id'=>$goods_id, 'user'=>$this->user]);
        $ret['data'][0]['is_favor'] = ($ret_favor['code'] == 0) ? $ret_favor['data'] : 0;

        // 商品评价总数
        $ret['data'][0]['comments_count'] = GoodsCommentsService::GoodsCommentsTotal(['goods_id'=>$goods_id, 'is_show'=>1]);

        // 商品访问统计
        GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

        // 用户商品浏览
        GoodsService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

        // 商品所属分类名称
        $category = GoodsService::GoodsCategoryNames($goods_id);
        $ret['data'][0]['category_names'] = $category['data'];

        // 数据返回
        $result = [
            'goods'                             => $ret['data'][0],
            'common_order_is_booking'           => (int) MyC('common_order_is_booking'),
            'common_app_is_use_mobile_detail'   => $is_use_mobile_detail,
            'common_app_is_online_service'      => (int) MyC('common_app_is_online_service'),
            'common_app_is_limitedtimediscount' => (int) MyC('common_app_is_limitedtimediscount'),
            'common_app_is_good_thing'          => (int) MyC('common_app_is_good_thing'),
            'common_app_is_poster_share'        => (int) MyC('common_app_is_poster_share'),
            'common_cart_total'                 => BuyService::UserCartTotal(['user'=>$this->user]),
            'customer_service_tel'              => MyC('common_app_customer_service_tel', null, true),
            'common_is_goods_detail_show_photo' => MyC('common_is_goods_detail_show_photo', 0, true),

            // 站点模式
            'common_site_type'                  => (int) MyC('common_site_type', 0, true),
            'common_is_exhibition_mode_btn_text'=> MyC('common_is_exhibition_mode_btn_text', '立即咨询', true),
        ];

        // 支付宝小程序在线客服
        if(APPLICATION_CLIENT_TYPE == 'alipay')
        {
            $result['common_app_mini_alipay_tnt_inst_id'] = MyC('common_app_mini_alipay_tnt_inst_id', null, true);
            $result['common_app_mini_alipay_scene'] = MyC('common_app_mini_alipay_scene', null, true);
        }

        // 限时秒杀
        if($result['common_app_is_limitedtimediscount'] == 1)
        {
            $ret = PluginsService::PluginsControlCall(
                'limitedtimediscount', 'index', 'goods', 'api', ['goods_id'=>$goods_id]);
            if($ret['code'] == 0 && isset($ret['data']['code']) && $ret['data']['code'] == 0)
            {
                $result['plugins_limitedtimediscount_data'] = $ret['data']['data'];
            }
        }

        // 优惠券
        $ret = PluginsService::PluginsControlCall(
                'coupon', 'index', 'goods', 'api', ['goods_id'=>$goods_id]);
        if($ret['code'] == 0 && isset($ret['data']['code']) && $ret['data']['code'] == 0)
        {
            $result['plugins_coupon_data'] = $ret['data']['data'];
        }

        return DataReturn('success', 0, $result);
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
        $params = $this->data_post;
        $ret = GoodsService::GoodsSpecType($params);
        if($ret['code'] == 0)
        {
            $ret['data'] = $ret['data']['spec_type'];
        }
        return $ret;
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
        return $ret;
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
        // 开始处理
        $params = $this->data_post;
        return DataReturn('success', 0, GoodsService::GoodsCategoryAll($params));
    }

    /**
     * 商品评分
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-07-11
     * @desc    description
     * @return  [type]          [description]
     */
    public function GoodsScore()
    {
        if(empty($this->data_post['goods_id']))
        {
            return DataReturn('参数有误', -1);
        }

        // 获取商品评分
        return GoodsCommentsService::GoodsCommentsScore($this->data_post['goods_id']);
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
        $data = GoodsCommentsService::GoodsCommentsList($data_params);
        
        // 返回数据
        $result = [
            'number'            => $number,
            'total'             => $total,
            'page_total'        => $page_total,
            'data'              => $data['data'],
        ];
        return DataReturn('success', 0, $result);
    }
}
?>