<?php

namespace Service;

use Service\GoodsService;
use Service\ResourcesService;
use Service\BuyService;
use Service\IntegralService;

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
            $params['user']['user_name_view'] = '用户-'.$params['user']['user_name_view'];
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
                        'subject'       => isset($params['params']['subject']) ? $params['params']['subject'] : '订单支付',
                        'buyer_user'    => $params['user']['user_name_view'],
                        'pay_price'     => $params['order']['total_price'],
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

        // 支付处理
        $pay_params = [
            'order'     => $order,
            'payment'   => $payment[0],
            'pay'       => [
                'trade_no'      => $ret['data']['trade_no'],
                'subject'       => $ret['data']['subject'],
                'buyer_user'    => $ret['data']['buyer_user'],
                'pay_price'     => $ret['data']['pay_price'],
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
        $pay_price = isset($params['pay']['pay_price']) ? $params['pay']['pay_price'] : 0;

        // 写入支付日志
        $pay_log_data = [
            'user_id'       => $params['order']['user_id'],
            'order_id'      => $params['order']['id'],
            'total_price'   => $params['order']['total_price'],
            'trade_no'      => isset($params['pay']['trade_no']) ? $params['pay']['trade_no'] : '',
            'buyer_user'    => isset($params['pay']['buyer_user']) ? $params['pay']['buyer_user'] : '',
            'pay_price'     => $pay_price,
            'subject'       => isset($params['pay']['subject']) ? $params['pay']['subject'] : '订单支付',
            'payment'       => $params['payment']['payment'],
            'payment_name'  => $params['payment']['name'],
            'business_type' => 1,
            'add_time'      => time(),
        ];
        M('PayLog')->add($pay_log_data);

        // 消息通知
        $detail = '订单支付成功，金额'.PriceBeautify($params['order']['total_price']).'元';
        MessageService::MessageAdd($params['order']['user_id'], '订单支付', $detail, 1, $params['order']['id']);

        // 开启事务
        $m = M('Order');
        $m->startTrans();

        // 更新订单状态
        $upd_data = array(
            'status'        => 2,
            'pay_status'    => 1,
            'pay_price'     => $pay_price,
            'payment_id'    => $params['payment']['id'],
            'pay_time'      => time(),
            'upd_time'      => time(),
        );
        if($m->where(['id'=>$params['order']['id']])->save($upd_data))
        {
            // 添加状态日志
            if(self::OrderHistoryAdd($params['order']['id'], 2, $params['order']['status'], '支付', 0, '系统'))
            {
                // 库存扣除
                $ret = BuyService::OrderInventoryDeduct(['order_id'=>$params['order']['id'], 'order_data'=>$upd_data]);
                if($ret['status'] != 0)
                {
                    // 事务回滚
                    $m->rollback();
                    return DataReturn($ret['msg'], -10);
                }

                // 提交事务
                $m->commit();
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
    public static function UserOrderListWhere($params = [])
    {
        $where = [
            'is_delete_time'        => 0,
            'user_is_delete_time'   => 0,
        ];

        // id
        if(!empty($params['id']))
        {
            $where['id'] = intval($params['id']);
        }

        // 用户id
        if(!empty($params['user']))
        {
            $where['user_id'] = $params['user']['id'];
        }

        if(!empty($params['keywords']))
        {
            $like_keywords = array('like', '%'.$params['keywords'].'%');
            $where[] = [
                    'order_no'      => $like_keywords,
                    'receive_name'  => $like_keywords,
                    'receive_tel'   => $like_keywords,
                    '_logic'        => 'or',
                ];
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['payment_id']) && $params['payment_id'] > -1)
            {
                $where['payment_id'] = intval($params['payment_id']);
            }
            if(isset($params['pay_status']) && $params['pay_status'] > -1)
            {
                $where['pay_status'] = intval($params['pay_status']);
            }
            if(isset($params['status']) && $params['status'] != -1)
            {
                if(!is_array($params['status']))
                {
                    $params['status'] = explode(',', $params['status']);
                }
                $where['status'] = ['in', $params['status']];
            }

            // 评价状态
            if(isset($params['is_comments']) && $params['is_comments'] > -1)
            {
                $where['user_is_comments'] = ($params['is_comments'] == 0) ? 0 : ['GT', 0];
            }

            // 时间
            if(!empty($params['time_start']))
            {
                $where['add_time'][] = array('gt', strtotime($params['time_start']));
            }
            if(!empty($params['time_end']))
            {
                $where['add_time'][] = array('lt', strtotime($params['time_end']));
            }

            // 价格
            if(!empty($params['price_start']))
            {
                $where['price'][] = array('gt', floatval($params['price_start']));
            }
            if(!empty($params['price_end']))
            {
                $where['price'][] = array('lt', floatval($params['price_end']));
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
        $order_by = empty($params['order_by']) ? 'id desc' : I('order_by', '', '', $params);
        $is_items = isset($params['is_items']) ? intval($params['is_items']) : 1;

        // 获取订单
        $data = M('Order')->where($params['where'])->limit($limit_start, $limit_number)->order($order_by)->select();
        if(!empty($data))
        {
            $detail_m = M('OrderDetail');
            $detail_field = 'id,goods_id,title,images,original_price,price,attribute,buy_number';
            $images_host = C('IMAGE_HOST');
            $order_status_list = L('common_order_user_status');
            $order_pay_status = L('common_order_pay_status');
            foreach($data as &$v)
            {
                // 订单基础
                $v['payment_name'] = '';

                // 状态
                $v['status_name'] = $order_status_list[$v['status']]['name'];

                // 支付状态
                $v['pay_status_name'] = $order_pay_status[$v['pay_status']]['name'];

                // 快递公司
                $v['express_name'] = ResourcesService::ExpressName($v['express_id']);

                // 收件人地址
                $v['receive_province_name'] = ResourcesService::RegionName($v['receive_province']);
                $v['receive_city_name'] = ResourcesService::RegionName($v['receive_city']);
                $v['receive_county_name'] = ResourcesService::RegionName($v['receive_county']);

                // 时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                
                // 订单详情
                if($is_items == 1)
                {
                    $items = $detail_m->where(['order_id'=>$v['id']])->field($detail_field)->select();
                    if(!empty($items))
                    {
                        foreach($items as &$vs)
                        {
                            $vs['images'] = empty($vs['images']) ? null : $images_host.$vs['images'];
                            $vs['attribute'] = empty($vs['attribute']) ? null : json_decode($vs['attribute'], true);
                            $vs['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$vs['goods_id']]);
                            $vs['total_price'] = $vs['buy_number']*$vs['price'];
                        }
                    }
                    $v['items'] = $items;
                    $v['items_count'] = count($items);
                }
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

        // 开启事务
        $m->startTrans();
        $upd_data = [
            'status'    => 5,
            'upd_time'  => time(),
        ];
        if($m->where($where)->save($upd_data))
        {
            // 库存扣除
            $ret = BuyService::OrderInventoryRollback(['order_id'=>$order['id'], 'order_data'=>$upd_data]);
            if($ret['status'] != 0)
            {
                // 事务回滚
                $m->rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单取消', '订单取消成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '取消', $creator, $creator_name);

            // 提交事务
            $m->commit();
            return DataReturn(L('common_cancel_success'), 0);
        }

        // 事务回滚
        $m->rollback();
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

        // 开启事务
        $m->startTrans();
        $upd_data = [
            'status'            => 3,
            'express_id'        => intval($params['express_id']),
            'express_number'    => I('express_number', '', '', $params),
            'delivery_time'     => time(),
            'upd_time'          => time(),
        ];
        if($m->where($where)->save($upd_data))
        {
            // 库存扣除
            $ret = BuyService::OrderInventoryDeduct(['order_id'=>$order['id'], 'order_data'=>$upd_data]);
            if($ret['status'] != 0)
            {
                // 事务回滚
                $m->rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单发货', '订单已发货', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '收货', $creator, $creator_name);

            // 提交事务
            $m->commit();
            return DataReturn(L('common_operation_delivery_success'), 0);
        }

        // 事务回滚
        $m->rollback();
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
    public static function OrderCollect($params = [])
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

        // 开启事务
        $m->startTrans();

        // 更新订单状态
        $upd_data = [
            'status'        => 4,
            'collect_time'  => time(),
            'upd_time'      => time(),
        ];
        if($m->where($where)->save($upd_data))
        {
            // 订单商品积分赠送
            $ret = IntegralService::OrderGoodsIntegralGiving(['order_id'=>$order['id']]);
            if($ret['status'] != 0)
            {
                // 事务回滚
                $m->rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 订单商品销量增加
            $ret = self::GoodsSalesCountInc(['order_id'=>$order['id']]);
            if($ret['status'] != 0)
            {
                // 事务回滚
                $m->rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单收货', '订单收货成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '收货', $creator, $creator_name);

            // 提交事务
            $m->commit();
            return DataReturn(L('common_operation_collect_success'), 0);
        }

        // 事务回滚
        $m->rollback();
        return DataReturn(L('common_operation_collect_error'), -1);
    }

    /**
     * 订单确认
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
        if(!in_array($order['status'], [0]))
        {
            $status_text = L('common_order_admin_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 开启事务
        $m->startTrans();

        // 更新订单状态
        $upd_data = [
            'status'        => 1,
            'confirm_time'  => time(),
            'upd_time'      => time(),
        ];
        if($m->where($where)->save($upd_data))
        {
            // 库存扣除
            $ret = BuyService::OrderInventoryDeduct(['order_id'=>$params['id'], 'order_data'=>$upd_data]);
            if($ret['status'] != 0)
            {
                // 事务回滚
                $m->rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单确认', '订单确认成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '确认', $creator, $creator_name);

            // 事务提交
            $m->commit();
            return DataReturn(L('common_confirm_success'), 0);
        }

        // 事务回滚
        $m->rollback();
        return DataReturn(L('common_confirm_error'), -1);
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
            MessageService::MessageAdd($order['user_id'], '订单删除', '订单删除成功', 1, $order['id']);

            return DataReturn(L('common_operation_delete_success'), 0);
        }
        return DataReturn(L('common_operation_delete_error'), -1);
    }

    /**
     * 订单评价
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-09
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function Comments($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => '订单id有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品id有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'goods_id',
                'error_msg'         => '商品数据格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'rating',
                'error_msg'         => '评级有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'rating',
                'error_msg'         => '评级数据格式有误',
            ],
            [
                'checked_type'      => 'isset',
                'key_name'          => 'content',
                'error_msg'         => '评价内容有误',
            ],
            [
                'checked_type'      => 'is_array',
                'key_name'          => 'content',
                'error_msg'         => '评价内容数据格式有误',
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
        $order_id = intval($params['id']);
        $order_m = M('Order');
        $where = ['id'=>$order_id, 'user_id'=>$params['user']['id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = $order_m->where($where)->field('id,status,shop_id,user_is_comments')->find();
        if(empty($order))
        {
            return DataReturn(L('common_data_no_exist_error'), -1);
        }
        if($order['status'] != 4)
        {
            $status_text = L('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }
        if($order['user_is_comments'] != 0)
        {
            return DataReturn('该订单你已进行过评价', -10);
        }

        // 处理数据
        $comments_m = M('OrderComments');
        $comments_m->startTrans();
        foreach($params['goods_id'] as $k=>$goods_id)
        {
            $data = [
                'user_id'   => $params['user']['id'],
                'shop_id'   => $order['shop_id'],
                'order_id'  => $order_id,
                'goods_id'  => $goods_id,
                'content'   => isset($params['content'][$k]) ? htmlspecialchars(trim($params['content'][$k])) : '',
                'rating'    => isset($params['rating'][$k]) ? intval($params['rating'][$k]) : 0,
                'add_time'  => time(),
            ];
            if($comments_m->add($data) <= 0)
            {
                $comments_m->rollback();
                return DataReturn('评价内容添加失败', -100);
            }
        }

        // 订单评价状态更新
        if(!$order_m->where($where)->save(['user_is_comments'=>time(), 'upd_time'=>time()]))
        {
            $comments_m->rollback();
            return DataReturn('订单更新失败', -101);
        }

        $comments_m->commit();
        return DataReturn(L('common_operation_comments_success'), 0);
    }

    /**
     * 订单每个环节状态总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-10
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderStatusStepTotal($params = [])
    {
        // 用户类型
        $user_type = isset($params['user_type']) ? $params['user_type'] : '';

        // 条件
        $where = [];
        $where['is_delete_time'] = 0;

        // 用户类型
        switch($user_type)
        {
            case 'user' :
                $where['user_is_delete_time'] = 0;
                break;
        }

        // 新增用户条件
        if($user_type == 'user' && !empty($params['user']))
        {
            $where['user_id'] = $params['user']['id'];
        }

        $field = 'COUNT(DISTINCT id) AS count, status';
        $data = M('Order')->where($where)->field($field)->group('status')->select();

        // 状态数据封装
        $result = [];
        $order_status_list = L('common_order_user_status');
        foreach(L('common_order_user_status') as $v)
        {
            $result[$v['id']] = [
                'name'      => $v['name'],
                'status'    => $v['id'],
                'count'     => 0,
            ];
        }

        // 数据处理
        if(!empty($data))
        {
            
            foreach($data as $v)
            {
                if(isset($result[$v['status']]))
                {
                    $result[$v['status']]['count'] = $v['count'];
                }
            }
            sort($result);
        }

        // 待评价状态站位100
        if(isset($params['is_comments']) && $params['is_comments'] == 1)
        {
            switch($user_type)
            {
                case 'user' :
                    $where['user_is_comments'] = 0;
                    break;
                case 'admin' :
                    $where['is_comments'] = 0;
                    break;
                default :
                    $where['user_is_comments'] = 0;
                    $where['is_comments'] = 0;
            }
            $where['status'] = 4;
            $result[] = [
                'name'      => '待评价',
                'status'    => 100,
                'count'     => (int) M('Order')->where($where)->count(),
            ];
        }
            
        return DataReturn('处理成功', 0, $result);
    }

    /**
     * 订单商品销量添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-14
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function GoodsSalesCountInc($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_id',
                'error_msg'         => '订单id有误',
            ]
        ];
        $ret = params_checked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单商品
        $order_detail = M('OrderDetail')->field('goods_id,buy_number')->where(['order_id'=>$params['order_id']])->select();
        if(!empty($order_detail))
        {
            $goods_m = M('Goods');
            foreach($order_detail as $v)
            {
                if(!$goods_m->where(['id'=>$v['goods_id']])->setInc('sales_count', $v['buy_number']))
                {
                    return DataReturn('订单商品销量增加失败['.$params['order_id'].'-'.$v['goods_id'].']', -10);
                }
            }
            return DataReturn('操作成功', 0);
        } else {
            return DataReturn('订单有误，没有找到相关商品', -100);
        }
    }

}
?>