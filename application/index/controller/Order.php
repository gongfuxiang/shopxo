<?php
namespace app\index\controller;

use app\service\OrderService;
use app\service\ResourcesService;

/**
 * 订单管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Order extends Common
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
        parent::__construct();

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
        $params = input();
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

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
                'url'       =>  url('index/order/index'),
            );
        $page = new \base\Page($page_params);
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
        $this->assign('common_order_user_status', lang('common_order_user_status'));

        // 支付状态
        $this->assign('common_order_pay_status', lang('common_order_pay_status'));

        // 评价状态
        $this->assign('common_comments_status_list', lang('common_comments_status_list'));

        // 参数
        $this->assign('params', $params);
        return $this->fetch();
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
        $params = input();
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

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
            return $this->fetch();
        } else {
            $this->assign('msg', lang('common_not_data_tips'));
            return $this->fetch('public/tips_error');
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
        $params = input();
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

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
            $this->assign('referer_url', empty($_SERVER['HTTP_REFERER']) ? url('index/order/index') : $_SERVER['HTTP_REFERER']);
            $this->assign('data', $data['data'][0]);
            return $this->fetch();
        } else {
            $this->assign('msg', lang('common_not_data_tips'));
            return $this->fetch('public/tips_error');
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user'] = $this->user;
            $ret = OrderService::Comments($params);
            return json($ret);
        } else {
            $this->assign('msg', lang('common_unauthorized_access'));
            return $this->fetch('public/tips_error');
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
        $params = input();
        $params['user'] = $this->user;
        $ret = OrderService::Pay($params);
        if($ret['code'] == 0)
        {
            return redirect($ret['data']['data']);
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
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
        $params = input();
        $params['user'] = $this->user;
        $ret = OrderService::Respond($params);
        if($ret['code'] == 0)
        {
            $this->assign('msg', '支付成功');
            return $this->fetch('public/pay_success');
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/pay_error');
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $ret = OrderService::OrderCancel($params);
            return json($ret);
        } else {
            $this->assign('msg', lang('common_unauthorized_access'));
            return $this->fetch('public/tips_error');
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $ret = OrderService::OrderCollect($params);
            return json($ret);
        } else {
            $this->assign('msg', lang('common_unauthorized_access'));
            return $this->fetch('public/tips_error');
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $params['user_type'] = 'user';
            $ret = OrderService::OrderDelete($params);
            return json($ret);
        } else {
            $this->assign('msg', lang('common_unauthorized_access'));
            return $this->fetch('public/tips_error');
        }
    }

}
?>