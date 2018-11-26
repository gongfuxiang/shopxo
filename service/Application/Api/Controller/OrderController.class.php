<?php

namespace Api\Controller;

use Service\ResourcesService;
use Service\OrderService;

/**
 * 我的订单
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OrderController extends CommonController
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

        // 登录校验
        $this->Is_Login();
    }
    
    /**
     * [Index 获取订单列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 参数
        $params = $this->data_post['page'];
        $params['user'] = $this->user;

        // 分页
        $number = 10;
        $page = max(1, isset($this->data_post['page']) ? intval($this->data_post['page']) : 1);

        // 条件
        $where = OrderService::UserOrderListWhere($this->data_post);

        // 获取总数
        $total = OrderService::OrderTotal($where);
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);

        // 获取列表
        $data_params = array(
            'limit_start'   => $start,
            'limit_number'  => $number,
            'where'         => $where,
        );
        $data = OrderService::OrderList($data_params);

        // 支付方式
        $payment_list = ResourcesService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]);

        // 返回数据
        $result = [
            'total'             =>  $total,
            'page_total'        =>  $page_total,
            'data'              =>  $data['data'],
            'payment_list'      =>  $payment_list,
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * [Detail 获取详情]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T10:18:27+0800
     */
    public function Detail()
    {
        // 条件
        $where = OrderService::UserOrderListWhere($this->data_post);

        // 获取列表
        $data_params = array(
            'limit_start'   => 0,
            'limit_number'  => 1,
            'where'         => $where,
        );
        $data = OrderService::OrderList($data_params);
        if(!empty($data['data'][0]))
        {
            $this->ajaxReturn(L('common_operation_success'), 0, $data['data'][0]);
        }
        $this->ajaxReturn(L('common_not_data_tips'), -100);
    }

    /**
     * 订单支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Pay()
    {
        $params = $this->data_post;
        $params['user'] = $this->user;
        $ret = OrderService::Pay($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }


    /**
     * [Cancel 订单取消]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T10:48:48+0800
     */
    public function Cancel()
    {
        $params = $this->data_post;
        $params['user_id'] = $this->user['id'];
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['user_name_view'];
        $ret = OrderService::OrderCancel($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Collect 订单收货]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T10:48:48+0800
     */
    public function Collect()
    {
        $params = $this->data_post;
        $params['user_id'] = $this->user['id'];
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['user_name_view'];
        $ret = OrderService::OrderCollect($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * 订单删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Delete()
    {
        $params = $this->data_post;
        $params['user_id'] = $this->user['id'];
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['user_name_view'];
        $params['user_type'] = 'user';
        $ret = OrderService::OrderDelete($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

}
?>