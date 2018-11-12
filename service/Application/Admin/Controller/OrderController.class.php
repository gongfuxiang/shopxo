<?php

namespace Admin\Controller;

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

        // 登录校验
        $this->Is_Login();

        // 权限校验
        $this->Is_Power();
    }

    /**
     * [Index 订单列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index($action = 'Index')
    {
        // 参数
        $param = array_merge($_POST, $_GET);

        // 条件
        $where = $this->GetIndexWhere();

        // 模型
        $m = M('Order');

        // 分页
        $number = MyC('admin_page_number');
        $page_param = array(
                'number'    =>  $number,
                'total'     =>  $m->where($where)->count(),
                'where'     =>  $param,
                'url'       =>  U('Admin/Order/Index'),
            );
        $page = new \Library\Page($page_param);

        // 获取列表
        $list = $this->SetDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select());

        // 状态
        $this->assign('common_order_admin_status', L('common_order_admin_status'));

        // 支付状态
        $this->assign('common_order_pay_status', L('common_order_pay_status'));

        // 快递公司
        $this->assign('express_list', ResourcesService::ExpressList());

        // 发起支付 - 支付方式
        $pay_where = [
            'where' => ['is_enable'=>1, 'is_open_user'=>1, 'payment'=>['in', C('under_line_list')]],
        ];
        $this->assign('buy_payment_list', ResourcesService::BuyPaymentList($pay_where));

        // 参数
        $this->assign('param', $param);

        // 分页
        $this->assign('page_html', $page->GetPageHtml());

        // 数据列表
        $this->assign('list', $list);
        $this->display('Index');
    }


    /**
     * [SetDataHandle 数据处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-29T21:27:15+0800
     * @param    [array]      $data [订单数据]
     * @return   [array]            [处理好的数据]
     */
    private function SetDataHandle($data)
    {
        if(!empty($data))
        {
            $image_host = C('IMAGE_HOST');
            $common_order_admin_status = L('common_order_admin_status');
            $common_order_pay_status = L('common_order_pay_status');
            foreach($data as &$v)
            {
                // 确认时间
                $v['confirm_time'] = empty($v['confirm_time']) ? null : date('Y-m-d H:i:s', $v['confirm_time']);

                // 支付时间
                $v['pay_time'] = empty($v['pay_time']) ? null : date('Y-m-d H:i:s', $v['pay_time']);

                // 发货时间
                $v['delivery_time'] = empty($v['delivery_time']) ? null : date('Y-m-d H:i:s', $v['delivery_time']);

                // 完成时间
                $v['success_time'] = empty($v['success_time']) ? null : date('Y-m-d H:i:s', $v['success_time']);

                // 取消时间
                $v['cancel_time'] = empty($v['cancel_time']) ? null : date('Y-m-d H:i:s', $v['cancel_time']);

                // 创建时间
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

                // 状态
                $v['status_text'] = $common_order_admin_status[$v['status']]['name'];

                // 支付状态
                $v['pay_status_text'] = $common_order_pay_status[$v['pay_status']]['name'];

                // 快递公司
                $v['express_name'] = GetExpressName($v['express_id']);

                // 收件人地址
                $v['receive_province_name'] = GetRegionName($v['receive_province']);
                $v['receive_city_name'] = GetRegionName($v['receive_city']);
                $v['receive_county_name'] = GetRegionName($v['receive_county']);

                // 商品列表
                $goods = M('OrderDetail')->where(['order_id'=>$v['id']])->select();
                if(!empty($goods))
                {
                    foreach($goods as &$vs)
                    {
                        $vs['attribute'] = empty($vs['attribute']) ? null : json_decode($vs['attribute'], true);
                    }
                }
                $v['goods'] = $goods;

                // 描述
                $v['describe'] = '共'.count($v['goods']).'件 合计:￥'.$v['total_price'].'元';
            }
        }
        return $data;
    }

    /**
     * [GetIndexWhere 订单列表条件]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-10T22:16:29+0800
     */
    private function GetIndexWhere()
    {
        $where = array(
            'is_delete_time'    => 0,
        );

        // 模糊
        if(!empty($_REQUEST['keyword']))
        {
            $like_keyword = array('like', '%'.I('keyword').'%');
            $where[] = array(
                    'order_no'                  =>  $like_keyword,
                    'receive_name'              =>  $like_keyword,
                    'receive_tel'               =>  $like_keyword,
                    'receive_address'           =>  $like_keyword,
                    'express_number'            =>  $like_keyword,
                    '_logic'                    =>  'or',
                );
        }

        // 是否更多条件
        if(I('is_more', 0) == 1)
        {
            // 等值
            if(I('status', -1) > -1)
            {
                $where['status'] = intval(I('status'));
            }

            if(I('express_id', -1) > -1)
            {
                $where['express_id'] = intval(I('express_id'));
            }

            if(I('pay_status', -1) > -1)
            {
                $where['pay_status'] = intval(I('pay_status'));
            }

            // 表达式
            if(!empty($_REQUEST['time_start']))
            {
                $where['add_time'][] = array('gt', strtotime(I('time_start')));
            }
            if(!empty($_REQUEST['time_end']))
            {
                $where['add_time'][] = array('lt', strtotime(I('time_end')));
            }
        }

        return $where;
    }

    /**
     * [Delete 订单删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 删除操作
        $params = $_POST;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $params['user_type'] = 'admin';
        $ret = OrderService::OrderDelete($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Cancel 订单取消]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Cancel()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 取消操作
        $params = $_POST;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $ret = OrderService::OrderCancel($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Delivery 订单发货]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Delivery()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 发货操作
        $params = $_POST;
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $ret = OrderService::OrderDelivery($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Collect 订单收货]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Collect()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 收货操作
        $params = $_POST;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $ret = OrderService::OrderCollect($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * [Confirm 订单确认]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-15T11:03:30+0800
     */
    public function Confirm()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 订单确认
        $params = $_POST;
        $params['user_id'] = $params['value'];
        $params['creator'] = $this->admin['id'];
        $params['creator_name'] = $this->admin['username'];
        $ret = OrderService::OrderConfirm($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
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
        $params = $_POST;
        $params['user'] = $this->admin;
        $params['user']['user_name_view'] = L('common_admin_name').'-'.$this->admin['username'];
        $ret = OrderService::AdminPay($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>