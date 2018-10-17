<?php

namespace Home\Controller;

use Service\OrderService;
use Service\ResourcesService;

/**
 * 订单管理
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

        // 是否登录
        $this->Is_Login();
    }

    /**
     * 订单列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 参数
        $params = array_merge($_POST, $_GET);
        $params['user'] = $this->user;

        // 分页
        $number = 10;

        // 条件
        $where = OrderService::UserOrderListWhere($params);

        // 获取总数
        $total = OrderService::OrderTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'url'       =>  U('Home/Order/Index'),
            );
        $page = new \Library\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'limit_start'   => $page->GetPageStarNumber(),
            'limit_number'  => $number,
            'where'         => $where,
        );
        $data = OrderService::OrderList($data_params);
        $this->assign('data_list', $data['data']);

        // 支付方式
        $this->assign('payment_list', ResourcesService::PaymentList());

        // 订单状态
        $this->assign('common_order_user_status', L('common_order_user_status'));

        // 支付状态
        $this->assign('common_order_pay_status', L('common_order_pay_status'));

        // 评价状态
        $this->assign('common_comments_status_list', L('common_comments_status_list'));

        // 参数
        $this->assign('params', $params);
        $this->display('Index');
    }

    /**
     * 订单详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-08
     * @desc    description
     */
    public function Detail()
    {
        // 参数
        $params = array_merge($_POST, $_GET);
        $params['user'] = $this->user;

        // 条件
        $where = OrderService::UserOrderListWhere($params);

        // 获取列表
        $data_params = array(
            'limit_start'   => 0,
            'limit_number'  => 1,
            'where'         => $where,
        );
        $data = OrderService::OrderList($data_params);
        if(!empty($data['data'][0]))
        {
            // 发起支付 - 支付方式
            $this->assign('buy_payment_list', ResourcesService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]));

            $this->assign('data', $data['data'][0]);

            // 参数
            $this->assign('params', $params);
            $this->display('Detail');
        } else {
            $this->assign('msg', L('common_not_data_tips'));
            $this->display('/Public/TipsError');
        } 
    }

    /**
     * 评价页面
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-08
     * @desc    description
     */
    public function Comments()
    {
        // 参数
        $params = array_merge($_POST, $_GET);
        $params['user'] = $this->user;

        // 条件
        $where = OrderService::UserOrderListWhere($params);

        // 获取列表
        $data_params = array(
            'limit_start'   => 0,
            'limit_number'  => 1,
            'where'         => $where,
        );
        $data = OrderService::OrderList($data_params);
        if(!empty($data['data'][0]))
        {
            $this->assign('referer_url', empty($_SERVER['HTTP_REFERER']) ? U('Home/Order/Index') : $_SERVER['HTTP_REFERER']);
            $this->assign('data', $data['data'][0]);
            $this->display('Comments');
        } else {
            $this->assign('msg', L('common_not_data_tips'));
            $this->display('/Public/TipsError');
        } 
    }

    /**
     * 评价保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-09
     * @desc    description
     */
    public function CommentsSave()
    {
        if(IS_POST)
        {
            $params = $_POST;
            $params['user'] = $this->user;
            $ret = OrderService::Comments($params);
            $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
        } else {
            $this->assign('msg', L('common_unauthorized_access'));
            $this->display('/Public/TipsError');
        }
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
        $params = $_REQUEST;
        $params['user'] = $this->user;
        $ret = OrderService::Pay($params);
        if($ret['code'] == 0)
        {
            redirect($ret['data']);
        } else {
            $this->assign('msg', $ret['msg']);
            $this->display('/Public/TipsError');
        }
    }

    /**
     * 支付同步返回处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Respond()
    {
        $params = $_REQUEST;
        $params['user'] = $this->user;
        $ret = OrderService::Respond($params);
        if($ret['code'] == 0)
        {
            $this->assign('msg', '支付成功');
            $this->display('/Public/PaySuccess');
        } else {
            $this->assign('msg', $ret['msg']);
            $this->display('/Public/PayError');
        }
    }

    /**
     * 订单取消
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Cancel()
    {
        if(IS_POST)
        {
            $params = $_POST;
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $ret = OrderService::OrderCancel($params);
            $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
        } else {
            $this->assign('msg', L('common_unauthorized_access'));
            $this->display('/Public/TipsError');
        }
    }

    /**
     * 订单收货
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Collect()
    {
        if(IS_POST)
        {
            $params = $_POST;
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $ret = OrderService::OrderCollect($params);
            $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
        } else {
            $this->assign('msg', L('common_unauthorized_access'));
            $this->display('/Public/TipsError');
        }
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
        if(IS_POST)
        {
            $params = $_POST;
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $params['user_type'] = 'user';
            $ret = OrderService::OrderDelete($params);
            $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
        } else {
            $this->assign('msg', L('common_unauthorized_access'));
            $this->display('/Public/TipsError');
        }
    }

}
?>