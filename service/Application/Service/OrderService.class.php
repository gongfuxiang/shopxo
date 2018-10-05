<?php

namespace Service;

use Service\GoodsService;
use Service\ResourcesService;

/**
 * 订单服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OrderService
{
    /**
     * 订单支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Pay($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id' => $params['user']['id']];
        $order = M('Order')->where($where)->find();
        if(empty($order))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if($order['total_price'] <= 0.00)
        {
            return DataReturn('金额不能为0', -1);
        }
        if($order['status'] != 1)
        {
            $status_text = L('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 支付方式
        $payment_id = empty($params['payment_id']) ? $order['payment_id'] : intval($params['payment_id']);
        $payment = ResourcesService::PaymentList(['where'=>['id'=>$payment_id]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 发起支付
        $url = __MY_URL__.'payment_order_'.strtolower($payment[0]['payment']);
        $pay_data = array(
            'out_user'      => md5($params['user']['id']),
            'order_no'      => $order['order_no'],
            'name'          => '订单支付',
            'total_price'   => $order['total_price'],
            'notify_url'    => $url.'_notify.php',
            'call_back_url' => $url.'_respond.php',
        );
        $pay_name = '\Library\Payment\\'.$payment[0]['payment'];
        $ret = (new $pay_name($payment[0]['config']))->Pay($pay_data);
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 非线上支付处理
            self::OrderPaymentUnderLine([
                'order'     => $order,
                'payment'   => $payment[0],
                'user'      => $params['user'],
                'subject'   => $params,
            ]);

            return $ret;
        }
        return DataReturn('支付接口异常', -1);
    }

    /**
     * 管理员订单支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function AdminPay($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '管理员信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id'])];
        $order = M('Order')->where($where)->find();
        if(empty($order))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if($order['total_price'] <= 0.00)
        {
            return DataReturn('金额不能为0', -1);
        }
        if($order['status'] != 1)
        {
            $status_text = L('common_order_admin_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 支付方式
        $payment_id = empty($params['payment_id']) ? $order['payment_id'] : intval($params['payment_id']);
        $payment = ResourcesService::PaymentList(['where'=>['id'=>$payment_id]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 非线上支付处理
        return self::OrderPaymentUnderLine([
            'order'     => $order,
            'payment'   => $payment[0],
            'user'      => $params['user'],
            'subject'   => $params,
        ]);
    }

    /**
     * [OrderPaymentUnderLine 线下支付处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-05T22:40:57+0800
     * @param   [array]          $params [输入参数]
     */
    private static function OrderPaymentUnderLine($params = [])
    {
        if(!empty($params['order']) && !empty($params['payment']) && !empty($params['user']))
        {
            if(in_array($params['payment']['payment'], C('under_line_list')))
            {
                // 支付处理
                $pay_params = [
                    'order'     => $params['order'],
                    'payment'   => $params['payment'],
                    'pay'       => [
                        'trade_no'      => '',
                        'subject'       => isset($params['params']['subject']) ? $params['params']['subject'] : '',
                        'buyer_email'   => $params['user']['user_name_view'],
                        'total_amount'  => $params['order']['total_price'],
                    ],
                ];
                return self::OrderPayHandle($pay_params);
            }
        }
        return DataReturn('无需处理', 0);
    }

    /**
     * 支付同步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Respond($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 支付方式
        $payment = ResourcesService::PaymentList(['where'=>['payment'=>PAYMENT_TYPE]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = '\Library\Payment\\'.PAYMENT_TYPE;
        $ret = (new $pay_name($payment[0]['config']))->Respond(array_merge($_GET, $_POST));
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 获取订单信息
            $where = ['order_no'=>$ret['data']['out_trade_no'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
            $order = M('Order')->where($where)->find();

            // 非线上支付处理
            self::OrderPaymentUnderLine([
                'order'     => $order,
                'payment'   => $payment[0],
                'user'      => $params['user'],
                'params'    => $params,
            ]);
        }
        return $ret;
    }

    /**
     * 支付异步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Notify($params = [])
    {
        // 支付方式
        $payment = ResourcesService::PaymentList(['where'=>['payment'=>PAYMENT_TYPE]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = '\Library\Payment\\'.PAYMENT_TYPE;
        $ret = (new $pay_name($payment[0]['config']))->Respond(array_merge($_GET, $_POST));
        if(!isset($ret['code']) || $ret['code'] != 0)
        {
            return $ret;
        }

        // 获取订单信息
        $where = ['order_no'=>$ret['data']['out_trade_no'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = M('Order')->where($where)->find();

        // 兼容web版本支付参数
        $buyer_email = isset($ret['data']['buyer_logon_id']) ? $ret['data']['buyer_logon_id'] : (isset($ret['data']['buyer_email']) ? $ret['data']['buyer_email'] : '');
        $total_amount = isset($ret['data']['total_amount']) ? $ret['data']['total_amount'] : (isset($ret['data']['total_fee']) ? $ret['data']['total_fee'] : '');

        // 支付处理
        $pay_params = [
            'order'     => $order,
            'payment'   => $payment[0],
            'pay'       => [
                'trade_no'      => $ret['data']['trade_no'],
                'subject'       => $ret['data']['subject'],
                'buyer_email'   => $buyer_email,
                'total_amount'  => $total_amount,
            ],
        ];
        return self::OrderPayHandle($pay_params);
    }

    /**
     * [OrderPayHandle 订单支付处理]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-10-05T23:02:14+0800
     * @param   [array]          $params [输入参数]
     */
    private static function OrderPayHandle($params = [])
    {
        // 订单信息
        if(empty($params['order']))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if($params['order']['status'] > 1)
        {
            $status_text = L('common_order_user_status')[$params['order']['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', 0);
        }

        // 支付方式
        if(empty($params['payment']))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付参数
        $total_amount = isset($params['pay']['total_amount']) ? $params['pay']['total_amount'] : 0;

        // 写入支付日志
        $pay_log_data = [
            'user_id'       => $params['order']['user_id'],
            'order_id'      => $params['order']['id'],
            'amount'        => $params['order']['total_price'],
            'trade_no'      => isset($params['pay']['trade_no']) ? $params['pay']['trade_no'] : '',
            'user'          => isset($params['pay']['buyer_email']) ? $params['pay']['buyer_email'] : '',
            'total_fee'     => $total_amount,
            'subject'       => isset($params['pay']['subject']) ? $params['pay']['subject'] : '订单支付',
            'payment'       => $params['payment']['payment'],
            'payment_name'  => $params['payment']['name'],
            'business_type' => 0,
            'add_time'      => time(),
        ];
        M('PayLog')->add($pay_log_data);

        // 消息通知
        $detail = '订单支付成功，金额'.PriceBeautify($params['order']['total_price']).'元';
        ResourcesService::MessageAdd($params['order']['user_id'], '订单支付', $detail, 1, $params['order']['id']);

        // 开启事务
        $m = M('Order');
        $m->startTrans();

        // 更新订单状态
        $upd_data = array(
            'status'        => 2,
            'pay_status'    => 1,
            'pay_price'     => $total_amount,
            'payment_id'    => $params['payment']['id'],
            'pay_time'      => time(),
            'upd_time'      => time(),
        );
        if($m->where(['id'=>$params['order']['id']])->save($upd_data))
        {
            // 添加状态日志
            if(self::OrderHistoryAdd($params['order']['id'], 2, $params['order']['status'], '支付', 0, '系统'))
            {
                // 提交事务
                $m->commit();

                // 成功
                return DataReturn('支付成功', 0);
            }
        }

        // 事务回滚
        $m->rollback();

        // 处理失败
        return DataReturn('处理失败', -100);
    }

    /**
     * 前端订单列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function HomeOrderListWhere($params = [])
    {
        $where = [
            'is_delete_time'        => 0,
            'user_is_delete_time'   => 0,
        ];

        // 用户id
        if(!empty($params['user']))
        {
            $where['user_id'] = $params['user']['id'];
        }

        if(!empty($params['keywords']))
        {
            $like_keywords = array('like', '%'.I('keywords').'%');
            $where[] = [
                    'order_no'      => $like_keywords,
                    'receive_name'  => $like_keywords,
                    'receive_tel'   => $like_keywords,
                    '_logic'        => 'or',
                ];
        }

        // 是否更多条件
        if(I('is_more', 0) == 1)
        {
            // 等值
            if(I('payment_id', -1) > -1)
            {
                $where['payment_id'] = intval(I('payment_id', 0));
            }
            if(I('pay_status', -1) > -1)
            {
                $where['pay_status'] = intval(I('pay_status', 0));
            }
            if(I('status', -1) > -1)
            {
                $where['status'] = intval(I('status', 0));
            }

            // 时间
            if(!empty($_REQUEST['time_start']))
            {
                $where['add_time'][] = array('gt', strtotime(I('time_start')));
            }
            if(!empty($_REQUEST['time_end']))
            {
                $where['add_time'][] = array('lt', strtotime(I('time_end')));
            }

            // 价格
            if(!empty($_REQUEST['price_start']))
            {
                $where['price'][] = array('gt', floatval(I('price_start')));
            }
            if(!empty($_REQUEST['price_end']))
            {
                $where['price'][] = array('lt', floatval(I('price_end')));
            }
        }

        return $where;
    }

    /**
     * 订单总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function OrderTotal($where = [])
    {
        return (int) M('Order')->where($where)->count();
    }

    /**
     * 订单列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderList($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'where',
                'error_msg'         => '条件不能为空',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'where',
                'error_msg'         => '条件格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_start',
                'error_msg'         => '分页起始值有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'limit_number',
                'error_msg'         => '分页数量不能为空',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        $limit_start = max(0, intval($params['limit_start']));
        $limit_number = max(1, intval($params['limit_number']));
        $order_by = empty($params['$order_by']) ? 'id desc' : I('order_by', '', '', $params);

        // 获取订单
        $data = M('Order')->where($params['where'])->limit($limit_start, $limit_number)->order($order_by)->select();
        if(!empty($data))
        {
            $detail_m = M('OrderDetail');
            $detail_field = 'id,goods_id,title,images,original_price,price,attribute,buy_number';
            $images_host = C('IMAGE_HOST');
            $order_status_list = L('common_order_user_status');
            foreach($data as &$v)
            {
                // 订单基础
                $total_price = 0;
                $v['status_name'] = $order_status_list[$v['status']]['name'];
                $v['payment_name'] = '';
                
                // 订单详情
                $items = $detail_m->where(['order_id'=>$v['id']])->field($detail_field)->select();
                if(!empty($items))
                {
                    foreach($items as &$vs)
                    {
                        $vs['images'] = empty($vs['images']) ? null : $images_host.$vs['images'];
                        $vs['attribute'] = empty($vs['attribute']) ? null : json_decode($vs['attribute'], true);
                        $vs['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$vs['goods_id']]);
                        $total_price += $vs['buy_number']*$vs['price'];
                    }
                }
                $v['items'] = $items;
                $v['items_count'] = count($items);
                $v['total_price'] = $total_price;
            }
        }
        return DataReturn('处理成功', 0, $data);
    }

    /**
     * 订单日志添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [int]          $order_id        [订单id]
     * @param   [int]          $new_status      [更新后的状态]
     * @param   [int]          $original_status [原始状态]
     * @param   [string]       $msg             [描述]
     * @param   [int]          $creator         [操作人]
     * @param   [string]       $creator_name    [操作人名称]
     * @return  [boolean]                       [成功 true, 失败 false]
     */
    public static function OrderHistoryAdd($order_id, $new_status, $original_status, $msg = '', $creator = 0, $creator_name = '')
    {
        // 状态描述
        $order_status_list = L('common_order_user_status');
        $original_status_name = $order_status_list[$original_status]['name'];
        $new_status_name = $order_status_list[$new_status]['name'];
        $msg .= '['.$original_status_name.'-'.$new_status_name.']';

        // 添加
        $data = [
            'order_id'          => intval($order_id),
            'new_status'        => intval($new_status),
            'original_status'   => intval($original_status),
            'msg'               => htmlentities($msg),
            'creator'           => intval($creator),
            'creator_name'      => htmlentities($creator_name),
            'add_time'          => time(),
        ];
        return M('OrderStatusHistory')->add($data) > 0;
    }

    /**
     * 订单取消
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderCancel($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => '用户id有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $m = M('Order');
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = $m->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if(!in_array($order['status'], [0,1]))
        {
            $status_text = L('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        $data = [
            'status'    => 5,
            'upd_time'  => time(),
        ];
        if($m->where($where)->save($data))
        {
            // 用户消息
            ResourcesService::MessageAdd($order['user_id'], '订单取消', '订单取消成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $data['status'], $order['status'], '取消', $creator, $creator_name);
            return DataReturn(L('common_cancel_success'), 0);
        }
        return DataReturn(L('common_cancel_error'), -1);
    }

    /**
     * 订单发货
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderDelivery($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => '用户id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'express_id',
                'error_msg'         => '快递id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'express_number',
                'error_msg'         => '快递单号有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $m = M('Order');
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = $m->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if(!in_array($order['status'], [2]))
        {
            $status_text = L('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        $data = [
            'status'            => 3,
            'express_id'        => intval($params['express_id']),
            'express_number'    => I('express_number', '', '', $params),
            'upd_time'          => time(),
        ];
        if($m->where($where)->save($data))
        {
            // 用户消息
            ResourcesService::MessageAdd($order['user_id'], '订单发货', '订单已发货', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $data['status'], $order['status'], '收货', $creator, $creator_name);
            return DataReturn(L('common_operation_delivery_success'), 0);
        }
        return DataReturn(L('common_operation_delivery_error'), -1);
    }

    /**
     * 订单收货
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderConfirm($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => '用户id有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $m = M('Order');
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = $m->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if(!in_array($order['status'], [3]))
        {
            $status_text = L('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        $data = [
            'status'    => 4,
            'upd_time'  => time(),
        ];
        if($m->where($where)->save($data))
        {
            // 用户消息
            ResourcesService::MessageAdd($order['user_id'], '订单收货', '订单收货成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $data['status'], $order['status'], '收货', $creator, $creator_name);
            return DataReturn(L('common_operation_collect_success'), 0);
        }
        return DataReturn(L('common_operation_collect_error'), -1);
    }

    /**
     * 订单删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderDelete($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => '用户id有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_type',
                'error_msg'         => '用户类型有误',
            ],
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户类型
        switch($params['user_type'])
        {
            case 'admin' :
                $delete_field = 'is_delete_time';
                break;

            case 'user' :
                $delete_field = 'user_is_delete_time';
                break;
        }
        if(empty($delete_field))
        {
            return DataReturn('用户类型有误['.$params['user_type'].']', -2);
        }

        // 获取订单信息
        $m = M('Order');
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = $m->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if(!in_array($order['status'], [4,5,6]))
        {
            $status_text = L('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        $data = [
            $delete_field   => time(),
            'upd_time'      => time(),
        ];
        if($m->where($where)->save($data))
        {
            // 用户消息
            ResourcesService::MessageAdd($order['user_id'], '订单删除', '订单删除成功', 1, $order['id']);

            return DataReturn(L('common_operation_delete_success'), 0);
        }
        return DataReturn(L('common_operation_delete_error'), -1);
    }

}
?>