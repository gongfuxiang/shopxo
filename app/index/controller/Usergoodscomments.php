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

use app\index\controller\Center;
use app\service\ApiService;
use app\service\GoodsCommentsService;
use app\service\SeoService;

/**
 * 用户商品评论
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserGoodsComments extends Center
{
    /**
     * 列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(MyLang('usergoodscomments.base_nav_title'), 1));
        return MyView();
    }

    /**
     * 详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-08-05T08:21:54+0800
     */
    public function Detail()
    {
        MyViewAssign([
            'data'      => $this->data_detail,
            'is_header' => 0,
            'is_footer' => 0,
        ]);
        return MyView();
    }

    /**
     * 添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 模板数据
        $assign = [
            // 当前数据
            'data'                                      => $this->data_detail,
            // 静态数据
            'common_is_show_list'                       => MyConst('common_is_show_list'),
            'common_is_text_list'                       => MyConst('common_is_text_list'),
            'common_goods_comments_rating_list'         => MyConst('common_goods_comments_rating_list'),
            'common_goods_comments_business_type_list'  => MyConst('common_goods_comments_business_type_list'),
            'is_header'                                 => 0,
            'is_footer'                                 => 0,
        ];

        // 参数
        $params = $this->data_request;
        unset($params['id']);
        $assign['params'] = $params;

        // 数据赋值
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Save()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(GoodsCommentsService::GoodsCommentsSave($params));
    }

    /**
     * 删除
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     */
    public function Delete()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        return ApiService::ApiDataReturn(GoodsCommentsService::GoodsCommentsDelete($params));
    }
}
?>