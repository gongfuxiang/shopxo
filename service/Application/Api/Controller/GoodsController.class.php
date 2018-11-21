<?php

namespace Api\Controller;

use Service\GoodsService;

/**
 * 商品
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

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }
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
        $goods_id = I('goods_id');
        $params = [
            'where' => [
                'g.id' => $goods_id,
                'g.is_delete_time' => 0,
            ],
            'is_photo' => true,
            'is_attribute' => true,
            'is_content_app' => true,
        ];
        $goods = GoodsService::GoodsList($params);
        if(empty($goods[0]) || $goods[0]['is_delete_time'] != 0)
        {
            $this->ajaxReturn(L('common_data_no_exist_error'), -1);
        }
        unset($goods[0]['content_web']);

        // 当前登录用户是否已收藏
        $ret_favor = GoodsService::IsUserGoodsFavor(['goods_id'=>$goods_id, 'user'=>$this->user]);
        $goods[0]['is_favor'] = ($ret_favor['code'] == 0) ? $ret_favor['data'] : 0;

        // 商品访问统计
        GoodsService::GoodsAccessCountInc(['goods_id'=>$goods_id]);

        // 用户商品浏览
        GoodsService::GoodsBrowseSave(['goods_id'=>$goods_id, 'user'=>$this->user]);

        $this->ajaxReturn(L('common_operation_success'), 0, $goods[0]);
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
        $this->Is_Login();

        // 开始操作
        $params = $this->data_post;
        $params['user'] = $this->user;
        $ret = GoodsService::GoodsFavor($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

}
?>