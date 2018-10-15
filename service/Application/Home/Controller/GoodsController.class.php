<?php

namespace Home\Controller;

use Service\GoodsService;

/**
 * 商品详情
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();
    }
    
    /**
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        $id = I('id');
        $params = [
            'where' => [
                'g.id'    => $id,
                'g.is_delete_time' => 0,
            ],
            'is_photo' => true,
            'is_attribute' => true,
        ];
        $goods = GoodsService::GoodsList($params);
        if(empty($goods[0]) || $goods[0]['is_delete_time'] != 0)
        {
            $this->assign('msg', L('common_data_no_exist_error'));
            $this->display('/Public/TipsError');
        } else {
            // 当前登录用户是否已收藏
            $ret_favor = GoodsService::IsUserGoodsFavor(['goods_id'=>$id, 'user'=>$this->user]);
            $goods[0]['is_favor'] = ($ret_favor['code'] == 0) ? $ret_favor['data'] : 0;

            // 商品评价总数
            $goods[0]['comments_count'] = GoodsService::GoodsCommentsTotal($id);

            $this->assign('goods', $goods[0]);
            $this->assign('home_seo_site_title', $goods[0]['title']);

            // 商品访问统计
            GoodsService::GoodsAccessCountInc(['goods_id'=>$id]);

            // 用户商品浏览
            GoodsService::GoodsBrowseSave(['goods_id'=>$id, 'user'=>$this->user]);

            // 左侧商品 看了又看
            $params = [
                'where'     => [
                    'g.is_delete_time'=>0,
                    'g.is_shelves'=>1
                ],
                'order_by'  => 'access_count desc',
                'field'     => 'g.id,g.title,g.title_color,g.price,g.images',
                'n'         => 10,
            ];
            $this->assign('left_goods', GoodsService::GoodsList($params));

            // 详情tab商品 猜你喜欢
            $params = [
                'where'     => [
                    'g.is_delete_time'=>0,
                    'g.is_shelves'=>1,
                    'is_home_recommended'=>1,
                ],
                'order_by'  => 'sales_count desc',
                'field'     => 'g.id,g.title,g.title_color,g.price,g.images,g.home_recommended_images',
                'n'         => 16,
            ];
            $this->assign('detail_like_goods', GoodsService::GoodsList($params));

            $this->display('Index');
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
        $this->Is_Login();

        // 开始处理
        $params = $_POST;
        $params['user'] = $this->user;
        $ret = GoodsService::GoodsFavor($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>