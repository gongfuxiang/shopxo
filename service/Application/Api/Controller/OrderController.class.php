<?php

namespace Api\Controller;

use Service\ResourcesService;

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
        // 分页
        $number = 10;
        $page = max(1, isset($this->data_post['page']) ? intval($this->data_post['page']) : 1));

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

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $data['data'],
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
        if(empty($this->data_post['id']))
        {
            $this->ajaxReturn('详情id不能为空');
        }

        // 条件
        $where = [
            'id'                => intval($this->data_post['id']),
            'is_delete_time'    => 0,
            'user_id'           => $this->user['id'],
        ];

        // 获取数据
        $data = M('Order')->where($where)->find();
        if(empty($data))
        {
            $this->ajaxReturn(L('common_data_no_exist_error'));
        }

        $result = $this->SetDataList([$data]);
        $this->ajaxReturn(L('common_operation_success'), 0, $result[0]);
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
        if(empty($this->data_post['id']))
        {
            $this->ajaxReturn('请选择订单');
        }

        $m = M('Order');
        $where = ['id'=>intval($this->data_post['id']), 'user_id' => $this->user['id']];
        $data = $m->where($where)->field('id,status')->find();
        if(empty($data))
        {
            $this->ajaxReturn(L('common_data_no_exist_error'));
        }
        if(!in_array($data['status'], [0,1]))
        {
            $status_text = L('common_order_user_status')[$data['status']]['name'];
            $this->ajaxReturn('状态不可操作['.$status_text.']');
        }

        $save_data = ['status' => 5, 'cancel_time' => time(), 'upd_time' => time()];
        if($m->where($where)->save($save_data))
        {
            $this->ajaxReturn(L('common_cancel_success'), 0);
        }
        $this->ajaxReturn(L('common_cancel_error'));
    }

    /**
     * [Collect 订单完成]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T10:48:48+0800
     */
    public function Collect()
    {
        if(empty($this->data_post['id']))
        {
            $this->ajaxReturn('请选择订单');
        }

        $m = M('Order');
        $where = ['id'=>intval($this->data_post['id']), 'user_id' => $this->user['id']];
        $data = $m->where($where)->field('id,status')->find();
        if(empty($data))
        {
            $this->ajaxReturn(L('common_data_no_exist_error'));
        }
        if(!in_array($data['status'], [3]))
        {
            $status_text = L('common_order_user_status')[$data['status']]['name'];
            $this->ajaxReturn('状态不可操作['.$status_text.']');
        }

        $save_data = ['status' => 4, 'success_time' => time(), 'upd_time' => time()];
        if($m->where($where)->save($save_data))
        {
            $this->ajaxReturn(L('common_confirm_success'), 0);
        }
        $this->ajaxReturn(L('common_confirm_error'));
    }

    /**
     * [Pay 订单支付]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-07-22T22:10:46+0800
     */
    public function Pay()
    {
        if(empty($this->data_post['id']))
        {
            $this->ajaxReturn('请选择订单');
        }

        $m = M('Order');
        $where = ['id'=>intval($this->data_post['id']), 'user_id' => $this->user['id']];
        $data = $m->where($where)->field('id,status,total_price')->find();
        if(empty($data))
        {
            $this->ajaxReturn(L('common_data_no_exist_error'));
        }
        if($data['total_price'] <= 0.00)
        {
            $this->ajaxReturn('金额不能为0');
        }
        if($data['status'] != 1)
        {
            $status_text = L('common_order_user_status')[$data['status']]['name'];
            $this->ajaxReturn('状态不可操作['.$status_text.']');
        }

        // 发起支付
        $notify_url = __MY_URL__.'Notify/order.php';
        $pay_data = array(
            'out_user'      =>  md5($this->user['id']),
            'order_sn'      =>  $data['id'].GetNumberCode(6),
            'name'          =>  '订单支付',
            'total_price'   =>  $data['total_price'],
            'notify_url'    =>  $notify_url,
        );
        $pay = (new \Library\Alipay())->SoonPay($pay_data, C("alipay_key_secret"));
        if(empty($pay))
        {
            $this->ajaxReturn('支付接口异常');
        }
        $this->ajaxReturn(L('common_operation_success'), 0, $pay);
    }

    /**
     * 确认
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-06-18T00:10:32+0800
     */
    public function Confirm()
    {
        die('error');
        // 参数
        $params = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '请选择订单',
            ]
        ];
        $ret = params_checked($this->data_post, $params);
        if($ret !== true)
        {
            $this->ajaxReturn($ret);
        }

        // 订单处理
        $m = M('Order');
        $where = ['id'=>intval($this->data_post['id']), 'user_id' => $this->user['id']];
        $data = $m->where($where)->field('id,status')->find();
        if(empty($data))
        {
            $this->ajaxReturn(L('common_data_no_exist_error'));
        }

        // 状态
        if($temp['status'] != 0)
        {
            $status_text = L('common_order_user_status')[$data['status']]['name'];
            $this->ajaxReturn('状态不可操作['.$status_text.']');
        }

        // 开始处理
        $save_data = ['status' => 1, 'confirm_time' => time(), 'upd_time' => time()];
        if($m->where($where)->save($data))
        {
            $this->ajaxReturn(L('common_confirm_success'), 0);
        }
        $this->ajaxReturn(L('common_confirm_error'));
    }

}
?>