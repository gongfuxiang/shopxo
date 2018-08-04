<?php

namespace Api\Controller;

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
        $m = M('Order');

        // 获取组织数据
        $number = 10;
        $page = intval(I('page', 1));
        $where = $this->GetIndexWhere();
        $total = $m->where($where)->count();
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);
        $field = '*';
        $data = $m->where($where)->field($field)->limit($start, $number)->order('id desc')->select();

        // 返回数据
        $result = [
            'total'         =>  $total,
            'page_total'    =>  $page_total,
            'data'          =>  $this->SetDataList($data),
        ];
        $this->ajaxReturn(L('common_operation_success'), 0, $result);
    }

    /**
     * [GetIndexWhere  我的订单 - 条件]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:13:32+0800
     */
    private function GetIndexWhere()
    {
        $where = [
            'user_id'           => $this->user['id'],
            'is_delete_time'    => 0,
        ];

        // 状态
        if(isset($this->data_post['status']) && strlen($this->data_post['status']) > 0 && in_array($this->data_post['status'], array_keys(L('common_order_user_status'))))
        {
            $where['status'] = intval($this->data_post['status']);
        }

        // 关键字
        if(!empty($this->data_post['keywords']))
        {
            $like_keyword = array('like', '%'.$this->data_post['keywords'].'%');
            $where[] = array(
                    'receive_name'      => $like_keyword,
                    'receive_tel'       => $like_keyword,
                    'express_number'    => $like_keyword,
                    '_logic'            => 'or',
                );
        }
        return $where;
    }

    /**
     * [SetDataList 数据处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-17T17:55:36+0800
     * @param    [array]      $data     [组织数据]
     * @return   [array]                [处理好的数据]
     */
    private function SetDataList($data)
    {
        if(!empty($data))
        {
            $image_host = C('IMAGE_HOST');
            $common_order_user_status = L('common_order_user_status');
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
                $v['status_text'] = $common_order_user_status[$v['status']]['name'];

                // 支付状态
                $v['pay_status_text'] = $common_order_pay_status[$v['pay_status']]['name'];

                // 快递公司
                $v['express_name'] = GetExpressName($v['express_id']);
                unset($v['express_id']);

                // 收件人地址
                $v['receive_province_name'] = GetRegionName($v['receive_province']);
                $v['receive_city_name'] = GetRegionName($v['receive_city']);
                $v['receive_county_name'] = GetRegionName($v['receive_county']);

                // 商品列表
                $goods = M('OrderDetail')->where(['order_id'=>$v['id']])->select();
                foreach($goods as &$vs)
                {
                    $vs['images'] = empty($vs['images']) ? null : $image_host.$vs['images'];
                }
                $v['goods'] = $goods;

                // 描述
                $v['describe'] = '共'.count($goods).'件 合计:￥'.$v['total_price'].'元';

                // 空字段数据处理
                if(empty($v['express_number']))
                {
                    $v['express_number'] = null;
                }
                if(empty($v['user_note']))
                {
                    $v['user_note'] = null;
                }
            }
        }
        return $data;
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