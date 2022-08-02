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

use app\service\OrderService;
use app\service\OrderAftersaleService;

/**
 * 订单售后
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Orderaftersale extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 登录校验
        $this->IsLogin();

        // 权限校验
        $this->IsPower();
    }

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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        $params = $this->data_request;
        return OrderAftersaleService::AftersaleConfirm($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return OrderAftersaleService::AftersaleAudit($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return OrderAftersaleService::AftersaleRefuse($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return OrderAftersaleService::AftersaleCancel($params);
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        $params = $this->data_request;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        return OrderAftersaleService::AftersaleDelete($params);
    }
}
?>