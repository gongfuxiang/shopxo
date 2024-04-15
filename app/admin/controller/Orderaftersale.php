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
namespace app\admin\controller;

use app\admin\controller\Base;
use app\service\ApiService;
use app\service\OrderAftersaleService;

/**
 * 订单售后
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Orderaftersale extends Base
{
    /**
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 静态数据
        MyViewAssign('common_order_aftersale_refundment_list', MyConst('common_order_aftersale_refundment_list'));
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
        return MyView();
    }

    /**
     * 确认
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     */
    public function Confirm()
    {
        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleConfirm($params));
    }

    /**
     * 审核
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     */
    public function Audit()
    {
        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleAudit($params));
    }

    /**
     * 拒绝
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     */
    public function Refuse()
    {
        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleRefuse($params));
    }

    /**
     * 取消
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Cancel()
    {
        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleCancel($params));
    }

    /**
     * 删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Delete()
    {
        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleDelete($params));
    }
}
?>