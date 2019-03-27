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

        // 获取商品
        $goods_id = intval($this->data_post['goods_id']);
        $params = [
            'where' => [
                'id' => $goods_id,
                'is_delete_time' => 0,
            ],
            'is_photo' => true,
            'is_spec' => true,
            'is_content_app' => true,
        ];
        $ret = GoodsService::GoodsList($params);
        if(empty($ret['data'][0]) || $ret['data'][0]['is_delete_time'] != 0)
        {
            return DataReturn('商品不存在或已删除', -1);
        }
        unset($ret['data'][0]['content_web']);

        // 当前登录用户是否已收藏
        $ret_favor = GoodsService::IsUserGoodsFavor(['goods_id'=>$goods_id, 'user'=>$this->user]);
        $ret['data'][0]['is_favor'] = ($ret_favor['code'] == 0) ? $ret_favor['data'] : 0;

        // 商品访问统计
        GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

        // 用户商品浏览
        GoodsService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

        // 数据返回
        $result = [
            'goods'                     => $ret['data'][0],
            'common_order_is_booking'   => (int) MyC('common_order_is_booking', 0),
        ];
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
        $params = $this->data_post;
        return GoodsService::GoodsSpecDetail($params);
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
        $data = GoodsService::GoodsCategory($params);
        return DataReturn('success', 0, $data);
    }
}
?>