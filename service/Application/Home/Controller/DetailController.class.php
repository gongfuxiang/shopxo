<?php

namespace Home\Controller;

/**
 * 商品详情
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class DetailController extends CommonController
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
        $goods_id = I('goods_id');
        $params = [
            'where' => [
                'g.id'    => $goods_id,
                'g.is_delete_time' => 0,
            ],
            'is_photo' => true,
            'is_attribute' => true,
        ];
        $goods = $this->GetCommonGoodsList($params);
        $this->assign('goods', $goods[0]);
        $this->assign('home_seo_site_title', $goods[0]['title']);

        // 左侧商品
        $params = [
            'where'     => [
                'g.is_delete_time'=>0,
                'g.is_shelves'=>1
            ],
            'order_by'  => 'access_count desc',
            'field'     => 'g.id,g.title,g.title_color,g.price,g.images',
            'n'         => 10,
        ];
        $left_goods = $this->GetCommonGoodsList($params);
        //print_r($left_goods);
        $this->assign('left_goods', $left_goods);

        $this->display('Index');
    }
}
?>