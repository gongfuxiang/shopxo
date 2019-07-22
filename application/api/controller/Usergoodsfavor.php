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
 * 用户商品收藏
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserGoodsFavor extends Common
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

        // 是否登录
        $this->IsLogin();
    }

    /**
     * [Index 获取列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 参数
        $params = $this->data_request;
        $params['user'] = $this->user;

        // 分页
        $number = 10;
        $page = max(1, isset($this->data_post['page']) ? intval($this->data_post['page']) : 1);

        // 条件
        $where = GoodsService::UserGoodsFavorListWhere($params);

        // 获取总数
        $total = GoodsService::GoodsFavorTotal($where);
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);

        // 获取列表
        $data_params = array(
            'm'         => $start,
            'n'         => $number,
            'where'     => $where,
        );
        $data = GoodsService::GoodsFavorList($data_params);

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $data['data'],
        ];
        return DataReturn('success', 0, $result);
    }

    /**
     * 用户商品收藏取消
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-17
     * @desc    description
     */
    public function Cancel()
    {
        $params = $this->data_post;
        $params['user'] = $this->user;
        return GoodsService::GoodsFavor($params);
    }
}
?>