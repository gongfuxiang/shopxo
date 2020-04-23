<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

use think\Db;
use think\facade\Hook;
use app\service\PaymentService;
use app\service\BuyService;
use app\service\IntegralService;
use app\service\RegionService;
use app\service\ExpressService;
use app\service\ResourcesService;
use app\service\PayLogService;

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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id' => $params['user']['id']];
        $order = Db::name('Order')->where($where)->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if($order['status'] != 1)
        {
            $status_text = lang('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 订单支付前校验
        $ret = BuyService::OrderPayBeginCheck(['order_id'=>$order['id'], 'order_data'=>$order]);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 支付方式
        $payment_id = empty($params['payment_id']) ? $order['payment_id'] : intval($params['payment_id']);
        $payment = PaymentService::PaymentList(['where'=>['id'=>$payment_id]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 更新订单支付方式信息
        if(!empty($params['payment_id']) && $params['payment_id'] != $order['payment_id'])
        {
            Db::name('Order')->where(['id'=>$order['id']])->update([
                'payment_id'    => $payment_id,
                'is_under_line' => in_array($payment[0]['payment'], config('shopxo.under_line_list')) ? 1 : 0,
                'upd_time'      => time(),
            ]);
        }

        // 金额为0、走直接支付成功
        if($order['total_price'] <= 0.00)
        {
            $params['user']['user_name_view'] = '用户-'.$params['user']['user_name_view'];
            $pay_result = self::OrderDirectSuccess([
                'order'     => $order,
                'payment'   => $payment[0],
                'user'      => $params['user'],
                'params'    => $params,
            ]);
            if($pay_result['code'] == 0)
            {
                return DataReturn('支付成功', 0, ['data'=>MyUrl('index/order/respond', ['appoint_status'=>0])]);
            }
            return $pay_result;
        }

        // 支付入口文件检查
        $pay_checked = PaymentService::EntranceFileChecked($payment[0]['payment'], 'order');
        if($pay_checked['code'] != 0)
        {
            // 入口文件不存在则创建
            $payment_params = [
                'payment'       => $payment[0]['payment'],
                'respond'       => '/index/order/respond',
                'notify'        => '/api/ordernotify/notify',
            ];
            $ret = PaymentService::PaymentEntranceCreated($payment_params);
            if($ret['code'] != 0)
            {
                return $ret;
            }
        }

        // 回调地址
        $url = __MY_URL__.'payment_order_'.strtolower($payment[0]['payment']);

        // url模式, pathinfo模式下采用自带url生成url, 避免非index.php多余
        if(MyC('home_seo_url_model', 0) == 0)
        {
            $call_back_url = $url.'_respond.php';
        } else {
            $call_back_url = MyUrl('index/order/respond', ['paymentname'=>$payment[0]['payment']]);
            if(stripos($call_back_url, '?') !== false)
            {
                $call_back_url = $url.'_respond.php';
            }
        }

        // 发起支付数据
        $pay_data = array(
            'user'          => $params['user'],
            'out_user'      => md5($params['user']['id']),
            'order_id'      => $order['id'],
            'order_no'      => $order['order_no'],
            'name'          => '订单支付',
            'total_price'   => $order['total_price'],
            'client_type'   => $order['client_type'],
            'notify_url'    => $url.'_notify.php',
            'call_back_url' => $call_back_url,
            'redirect_url'  => MyUrl('index/order/detail', ['id'=>$order['id']]),
            'site_name'     => MyC('home_site_name', 'ShopXO', true),
            'ajax_url'      => MyUrl('index/order/paycheck'),
        );

        // 发起支付处理钩子
        $hook_name = 'plugins_service_order_pay_launch_handle';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_id'      => $order['id'],
            'order'         => &$order,
            'params'        => &$params,
            'pay_data'      => &$pay_data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 微信中打开并且webopenid为空
        if(in_array(APPLICATION_CLIENT_TYPE, ['pc', 'h5']))
        {
            if(!empty($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && empty($pay_data['user']['weixin_web_openid']))
            {
                // 授权成功后回调订单详情页面重新自动发起支付
                $url = MyUrl('index/order/detail', ['id'=>$pay_data['order_id'], 'is_pay_auto'=>1, 'is_pay_submit'=>1]);
                session('plugins_weixinwebauth_pay_callback_view_url', $url);
            }
        }

        // 发起支付
        $pay_name = 'payment\\'.$payment[0]['payment'];
        $ret = (new $pay_name($payment[0]['config']))->Pay($pay_data);
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 支付信息返回
            $ret['data'] = [
                // 支付类型(0正常线上支付、1线下支付、2钱包支付)
                'is_payment_type'   => 0,

                // 支付模块处理数据
                'data'              => $ret['data'],
            ];

            // 是否线下支付
            if(in_array($payment[0]['payment'], config('shopxo.under_line_list')))
            {
                $ret['data']['is_payment_type'] = 1;
            } else {
                // 是否钱包支付
                if($payment[0]['payment'] == 'WalletPay')
                {
                    $ret['data']['is_payment_type'] = 2;
                }
            }

            return $ret;
        }
        return DataReturn(empty($ret['msg']) ? '支付接口异常' : $ret['msg'], -1);
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id'])];
        $order = Db::name('Order')->where($where)->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if($order['status'] != 1)
        {
            $status_text = lang('common_order_admin_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 订单支付前校验
        $ret = BuyService::OrderPayBeginCheck(['order_id'=>$order['id'], 'order_data'=>$order]);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 支付方式
        $payment_id = empty($params['payment_id']) ? $order['payment_id'] : intval($params['payment_id']);
        $payment = PaymentService::PaymentList(['where'=>['id'=>$payment_id]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 线下支付处理
        return self::OrderPaymentUnderLineSuccess([
            'order'     => $order,
            'payment'   => $payment[0],
            'user'      => $params['user'],
            'params'    => $params,
        ]);
    }

    /**
     * 订单金额为小于等于0直接成功
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-04-07
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function OrderDirectSuccess($params = [])
    {
        if(!empty($params['order']) && !empty($params['payment']) && !empty($params['user']))
        {
            if($params['order']['total_price'] <= 0.00)
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
            return DataReturn('订单金额有误、请正常发起支付', -1);
        }
        return DataReturn('支付传参有误', -1);
    }

    /**
     * 线下支付方式、直接支付成功
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-04-07
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    private static function OrderPaymentUnderLineSuccess($params = [])
    {
        if(!empty($params['order']) && !empty($params['payment']) && !empty($params['user']))
        {
            if(in_array($params['payment']['payment'], config('shopxo.under_line_list')))
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
            return DataReturn('仅线下支付方式处理', -1);
        }
        return DataReturn('支付传参有误', -1);
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 支付方式
        $payment_name = defined('PAYMENT_TYPE') ? PAYMENT_TYPE : (isset($params['paymentname']) ? $params['paymentname'] : '');
        if(empty($payment_name))
        {
            return DataReturn('支付方式标记异常', -1);
        }
        $payment = PaymentService::PaymentList(['where'=>['payment'=>$payment_name]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = 'payment\\'.$payment_name;
        $ret = (new $pay_name($payment[0]['config']))->Respond(array_merge(input('get.'), input('post.')));
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            if(empty($ret['data']['out_trade_no']))
            {
                return DataReturn('单号有误', -1);
            }
            // 获取订单信息
            $where = ['order_no'=>$ret['data']['out_trade_no'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
            $order = Db::name('Order')->where($where)->find();

            // 线下支付方式处理
            if(in_array($payment_name, config('shopxo.under_line_list')))
            {
                return DataReturn('提交成功、待管理员确认', 0);
            }
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
        $payment = PaymentService::PaymentList(['where'=>['payment'=>PAYMENT_TYPE]]);
        if(empty($payment[0]))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 支付数据校验
        $pay_name = 'payment\\'.PAYMENT_TYPE;
        $ret = (new $pay_name($payment[0]['config']))->Respond(array_merge(input('get.'), input('post.')));
        if(!isset($ret['code']) || $ret['code'] != 0)
        {
            return $ret;
        }

        // 获取订单信息
        $where = ['order_no'=>$ret['data']['out_trade_no'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->find();

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

        // 支付成功异步通知处理钩子
        $hook_name = 'plugins_service_order_pay_notify_handle';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'payment'       => $payment[0],
            'order'         => $order,
            'pay_params'    => &$pay_params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 支付结果处理
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
    public static function OrderPayHandle($params = [])
    {
        // 订单信息
        if(empty($params['order']))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if($params['order']['status'] > 1)
        {
            $status_text = lang('common_order_user_status')[$params['order']['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', 0);
        }

        // 支付方式
        if(empty($params['payment']))
        {
            return DataReturn('支付方式有误', -1);
        }

        // 订单支付成功处理前钩子
        $hook_name = 'plugins_service_order_pay_handle_begin';
        $ret = HookReturnHandle(Hook::listen($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'order_id'      => $params['order']['id']
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
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
        ];
        PayLogService::PayLogInsert($pay_log_data);

        // 开启事务
        Db::startTrans();

        // 消息通知
        $detail = '订单支付成功，金额'.PriceBeautify($params['order']['total_price']).'元';
        MessageService::MessageAdd($params['order']['user_id'], '订单支付', $detail, 1, $params['order']['id']);

        // 订单更新数据
        $upd_data = [
            'status'        => 2,
            'pay_status'    => 1,
            'pay_price'     => $pay_price,
            'payment_id'    => $params['payment']['id'],
            'pay_time'      => time(),
            'upd_time'      => time(),
        ];

        // 是否线下支付
        $upd_data['is_under_line'] = in_array($params['payment']['payment'], config('shopxo.under_line_list')) ? 1 : 0;

        // 更新订单状态
        if(Db::name('Order')->where(['id'=>$params['order']['id']])->update($upd_data))
        {
            // 添加状态日志
            if(self::OrderHistoryAdd($params['order']['id'], 2, $params['order']['status'], '支付', 0, '系统'))
            {
                // 库存扣除
                $ret = BuyService::OrderInventoryDeduct(['order_id'=>$params['order']['id'], 'order_data'=>$upd_data]);
                if($ret['code'] != 0)
                {
                    // 事务回滚
                    Db::rollback();
                    return DataReturn($ret['msg'], -10);
                }

                // 提交事务
                Db::commit();

                // 订单支付成功处理完毕钩子
                $hook_name = 'plugins_service_order_pay_success_handle_end';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => $params,
                    'order_id'      => $params['order']['id']
                ]));

                // 虚拟商品自动触发发货操作
                if($params['order']['order_model'] == 3)
                {
                    self::OrderDelivery([
                        'id'                => $params['order']['id'],
                        'creator'           => 0,
                        'creator_name'      => '系统',
                        'user_id'           => $params['order']['user_id'],
                    ]);
                }

                return DataReturn('支付成功', 0);
            }
        }

        // 事务回滚
        Db::rollback();

        // 处理失败
        return DataReturn('处理失败', -100);
    }

    /**
     * 订单列表条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderListWhere($params = [])
    {
        // 用户类型
        $user_type = isset($params['user_type']) ? $params['user_type'] : 'user';

        // 条件初始化
        $where = [
            ['is_delete_time', '=', 0],
        ];

        // id
        if(!empty($params['id']))
        {
            $where[] = ['id', '=', intval($params['id'])];
        }
        // 订单号
        if(!empty($params['orderno']))
        {
            $where[] = ['order_no', '=', trim($params['orderno'])];
        }
        
        // 用户类型
        if(isset($params['user_type']) && $params['user_type'] == 'user')
        {
            $where[] = ['user_is_delete_time', '=', 0];

            // 用户id
            if(!empty($params['user']))
            {
                $where[] = ['user_id', '=', $params['user']['id']];
            }
        }

        // 关键字
        if(!empty($params['keywords']))
        {
            // 查询状态
            $keywords_status = false;

            // 订单表查询
            $oids = Db::name('Order')->where([['order_no|express_number', '=', $params['keywords']]])->column('id');
            if(!empty($oids))
            {
                $where[] = ['id', 'in', $oids];
                $keywords_status = true;
            }

            // 取货码查询
            if($keywords_status === false && strlen(intval($params['keywords'])) == 4)
            {
                $oid = Db::name('OrderExtractionCode')->where(['code'=>$params['keywords']])->value('order_id');
                if(!empty($oid))
                {
                    $where[] = ['id', '=', $oid];
                    $keywords_status = true;
                }
            }

            // 收件姓名电话查询
            if($keywords_status === false)
            {
                $oids = Db::name('OrderAddress')->where([['name|tel', '=', $params['keywords']]])->column('order_id');
                if(!empty($oids))
                {
                    $where[] = ['id', 'in', $oids];
                    $keywords_status = true;
                }
            }
        }

        // 是否更多条件
        if(isset($params['is_more']) && $params['is_more'] == 1)
        {
            // 等值
            if(isset($params['payment_id']) && $params['payment_id'] > -1)
            {
                $where[] = ['payment_id', '=', intval($params['payment_id'])];
            }
            if(isset($params['express_id']) && $params['express_id'] > -1)
            {
                $where[] = ['express_id', '=', intval($params['express_id'])];
            }
            if(isset($params['pay_status']) && $params['pay_status'] > -1)
            {
                $where[] = ['pay_status', '=', intval($params['pay_status'])];
            }
            if(isset($params['order_model']) && $params['order_model'] > -1)
            {
                $where[] = ['order_model', '=', intval($params['order_model'])];
            }
            if(!empty($params['client_type']))
            {
                $where[] = ['client_type', '=', $params['client_type']];
            }
            if(isset($params['status']) && $params['status'] != -1)
            {
                // 多个状态,字符串以半角逗号分割
                if(!is_array($params['status']))
                {
                    $params['status'] = explode(',', $params['status']);
                }
                $where[] = ['status', 'in', $params['status']];
            }

            // 评价状态
            if(isset($params['is_comments']) && $params['is_comments'] > -1)
            {
                $comments_field = ($user_type == 'user') ? 'user_is_comments' : 'is_comments';
                if($params['is_comments'] == 0)
                {
                    $where[] = [$comments_field, '=', 0];
                } else {
                    $where[] = [$comments_field, '>', 0];
                }
            }

            // 时间
            if(!empty($params['time_start']))
            {
                $where[] = ['add_time', '>', strtotime($params['time_start'])];
            }
            if(!empty($params['time_end']))
            {
                $where[] = ['add_time', '<', strtotime($params['time_end'])];
            }

            // 价格
            if(!empty($params['price_start']))
            {
                $where[] = ['price', '>', floatval($params['price_start'])];
            }
            if(!empty($params['price_end']))
            {
                $where[] = ['price', '<', floatval($params['price_end'])];
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
        return (int) Db::name('Order')->where($where)->count();
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
        $where = empty($params['where']) ? [] : $params['where'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $is_items = isset($params['is_items']) ? intval($params['is_items']) : 1;
        $is_orderaftersale = isset($params['is_orderaftersale']) ? intval($params['is_orderaftersale']) : 0;
        $user_type = isset($params['user_type']) ? $params['user_type'] : 'user';

        // 获取订单
        $data = Db::name('Order')->where($where)->limit($m, $n)->order($order_by)->select();
        if(!empty($data))
        {
            $order_status_list = lang('common_order_user_status');
            $order_pay_status = lang('common_order_pay_status');
            $common_platform_type = lang('common_platform_type');
            $common_site_type_list = lang('common_site_type_list');
            foreach($data as &$v)
            {
                // 订单处理前钩子
                $hook_name = 'plugins_service_order_handle_begin';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'order'         => &$v,
                    'order_id'      => $v['id']
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }

                // 订单模式处理
                // 销售型模式+自提模式
                if(in_array($v['order_model'], [0,2]))
                {
                    // 销售模式+自提模式 地址信息
                    $v['address_data'] = self::OrderAddressData($v['id']);
                    
                    // 自提模式 添加订单取货码
                    if($v['order_model'] == 2)
                    {
                        $v['extraction_data'] = self::OrdersExtractionData($v['id'], $v['user_id']);
                    }
                }

                // 用户信息
                if(isset($v['user_id']))
                {
                    if(isset($params['is_public']) && $params['is_public'] == 0)
                    {
                        $v['user'] = UserService::GetUserViewInfo($v['user_id']);
                    }
                }

                // 订单模式
                $v['order_model_name'] = isset($common_site_type_list[$v['order_model']]) ? $common_site_type_list[$v['order_model']]['name'] : '未知';

                // 客户端
                $v['client_type_name'] = isset($common_platform_type[$v['client_type']]) ? $common_platform_type[$v['client_type']]['name'] : '';

                // 状态
                $v['status_name'] = ($v['order_model'] == 2 && $v['status'] == 2) ? '待取货' : $order_status_list[$v['status']]['name'];

                // 支付状态
                $v['pay_status_name'] = $order_pay_status[$v['pay_status']]['name'];

                // 快递公司
                $v['express_name'] = ExpressService::ExpressName($v['express_id']);

                // 支付方式
                $v['payment_name'] = PaymentService::OrderPaymentName($v['id'], $v['payment_id']);

                // 线下支付 icon 名称
                $v['is_under_line_text'] = ($v['is_under_line'] == 1) ? '线下支付' : null;

                // 创建时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);

                // 确认时间
                $v['confirm_time'] = empty($v['confirm_time']) ? null : date('Y-m-d H:i:s', $v['confirm_time']);

                // 支付时间
                $v['pay_time'] = empty($v['pay_time']) ? null : date('Y-m-d H:i:s', $v['pay_time']);

                // 发货时间
                $v['delivery_time'] = empty($v['delivery_time']) ? null : date('Y-m-d H:i:s', $v['delivery_time']);

                // 收货时间
                $v['collect_time'] = empty($v['collect_time']) ? null : date('Y-m-d H:i:s', $v['collect_time']);

                // 取消时间
                $v['cancel_time'] = empty($v['cancel_time']) ? null : date('Y-m-d H:i:s', $v['cancel_time']);

                // 关闭时间
                $v['close_time'] = empty($v['close_time']) ? null : date('Y-m-d H:i:s', $v['close_time']);

                // 评论时间
                $v['user_is_comments_time'] = ($v['user_is_comments'] == 0) ? null : date('Y-m-d H:i:s', $v['user_is_comments']);

                // 空字段数据处理
                if(empty($v['express_number']))
                {
                    $v['express_number'] = null;
                }
                if(empty($v['user_note']))
                {
                    $v['user_note'] = null;
                }

                // 扩展数据
                $v['extension_data'] = empty($v['extension_data']) ? null : json_decode($v['extension_data'], true);
                
                // 订单详情
                if($is_items == 1)
                {
                    $items = Db::name('OrderDetail')->where(['order_id'=>$v['id']])->select();
                    if(!empty($items))
                    {
                        foreach($items as &$vs)
                        {
                            // 商品信息
                            $vs['images'] = ResourcesService::AttachmentPathViewHandle($vs['images']);
                            $vs['goods_url'] = MyUrl('index/goods/index', ['id'=>$vs['goods_id']]);
                            $vs['total_price'] = $vs['buy_number']*$vs['price'];

                            // 规格
                            if(!empty($vs['spec']))
                            {
                                $vs['spec'] = json_decode($vs['spec'], true);
                                if(!empty($vs['spec']) && is_array($vs['spec']))
                                {
                                    $vs['spec_text'] = implode('，', array_map(function($spec)
                                    {
                                        return $spec['type'].':'.$spec['value'];
                                    }, $vs['spec']));
                                }
                            } else {
                                $vs['spec'] = null;
                                $vs['spec_text'] = null;
                            }

                            // 虚拟销售商品 - 虚拟信息处理
                            if($v['order_model'] == 3 && $v['pay_status'] == 1 && in_array($v['status'], [3,4]))
                            {
                                $vs['fictitious_goods_value'] = Db::name('OrderFictitiousValue')->where(['order_detail_id'=>$vs['id']])->value('value');
                            }

                            // 是否获取最新一条售后信息
                            if($is_orderaftersale == 1)
                            {
                                $orderaftersale = Db::name('OrderAftersale')->where(['order_detail_id'=>$vs['id']])->order('id desc')->find();
                                $vs['orderaftersale'] = $orderaftersale;
                                $vs['orderaftersale_btn_text'] = self::OrderAftersaleStatusBtnText($v['status'], $orderaftersale);
                            }
                        }
                    }
                    $v['items'] = $items;
                    $v['items_count'] = count($items);

                    // 描述
                    $v['describe'] = '共'.$v['buy_number_count'].'件 合计:'.config('shopxo.price_symbol').$v['total_price'].'元';
                }

                // 订单处理后钩子
                $hook_name = 'plugins_service_order_handle_end';
                $ret = HookReturnHandle(Hook::listen($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'order'         => &$v,
                    'order_id'      => $v['id']
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    return $ret;
                }
            }
        }

        return DataReturn('success', 0, $data);
    }

    /**
     * 订单自提信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-26
     * @desc    description
     * @param   [int]          $order_id    [订单id]
     * @param   [int]          $user_id     [用户id]
     */
    private static function OrdersExtractionData($order_id, $user_id)
    {
        // 必须返回的内容格式
        $result = [
            'code'      => null,
            'images'    => null,
        ];

        // 获取取货码
        $code = Db::name('OrderExtractionCode')->where(['order_id'=>$order_id])->value('code');
        if(!empty($code))
        {
            $result['code'] = $code;

            // 生成二维码参数
            $qrcode_params = [
                'content'   => $code,
                'root_path' => ROOT.'public',
                'path'      => DS.'download'.DS.'order'.DS.'extraction_ode'.DS,
                'filename'  => $user_id.'_'.$order_id.'.png',
            ];

            // 图片不存在则去生成二维码图片并保存至目录
            $file = $qrcode_params['root_path'].$qrcode_params['path'].$qrcode_params['filename'];
            if(!file_exists($file))
            {
                $ret = (new \base\Qrcode())->Create($qrcode_params);
                if($ret['code'] == 0)
                {
                    $result['images'] = $qrcode_params['path'].$qrcode_params['filename'];
                }
            } else {
                $result['images'] = $qrcode_params['path'].$qrcode_params['filename'];
            }

            // 展示地址处理
            if(!empty($result['images']))
            {
                $result['images'] = ResourcesService::AttachmentPathViewHandle($result['images']);
            }
        }
        return $result;
    }

    /**
     * 订单地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-26
     * @desc    description
     * @param   [int]          $order_id [订单id]
     */
    private static function OrderAddressData($order_id)
    {
        // 销售模式+自提模式 地址信息
        $data = Db::name('OrderAddress')->where(['order_id'=>$order_id])->find();
        
        // 坐标处理
        if(!empty($data) && is_array($data) && in_array(APPLICATION_CLIENT_TYPE, config('shopxo.coordinate_transformation')))
        {
            // 坐标转换 百度转火星(高德，谷歌，腾讯坐标)
            if(isset($data['lng']) && isset($data['lat']) && $data['lng'] > 0 && $data['lat'] > 0)
            {
                $map = \base\GeoTransUtil::BdToGcj($data['lng'], $data['lat']);
                if(isset($map['lng']) && isset($map['lat']))
                {
                    $data['lng'] = $map['lng'];
                    $data['lat'] = $map['lat'];
                }
            }
        }
        return empty($data) ? [] : $data;
    }

    /**
     * 订单售后操作名称
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-10-04T13:11:55+0800
     * @desc     description
     * @param    [int]                   $order_status   [订单状态]
     * @param    [array]                 $orderaftersale [售后数据]
     */
    private static function OrderAftersaleStatusBtnText($order_status, $orderaftersale)
    {
        $text = '';
        if(in_array($order_status, [2,3,4,6]))
        {
            if(empty($orderaftersale))
            {
                if(in_array($order_status, [2,3]))
                {
                    $text = '退款/退货';
                } else {
                    if($order_status == 4)
                    {
                        $text = '申请售后';
                    }
                }
            } else {
                $text = ($orderaftersale['status'] == 3) ? '查看退款' : '查看进度';
            }
        }
        return $text;
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
        $order_status_list = lang('common_order_user_status');
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

        // 日志添加
        if(Db::name('OrderStatusHistory')->insertGetId($data) > 0)
        {
            // 订单状态改变添加日志钩子
            $hook_name = 'plugins_service_order_status_change_history_success_handle';
            Hook::listen($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => $data,
                'order_id'      => $data['order_id']
            ]);

            return true;
        }
        return false;
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if(!in_array($order['status'], [0,1]))
        {
            $status_text = lang('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 开启事务
        Db::startTrans();
        $upd_data = [
            'status'        => 5,
            'cancel_time'   => time(),
            'upd_time'      => time(),
        ];
        if(Db::name('Order')->where($where)->update($upd_data))
        {
            // 库存回滚
            $ret = BuyService::OrderInventoryRollback(['order_id'=>$order['id'], 'order_data'=>$upd_data]);
            if($ret['code'] != 0)
            {
                // 事务回滚
                Db::rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单取消', '订单取消成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '取消', $creator, $creator_name);

            // 提交事务
            Db::commit();
            return DataReturn('取消成功', 0);
        }

        // 事务回滚
        Db::rollback();
        return DataReturn('取消失败', -1);
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
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,user_id,order_model')->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if(!in_array($order['status'], [2]))
        {
            $status_text = lang('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 订单模式
        switch($order['order_model'])
        {
            // 销售模式- 订单快递信息校验
            case 0 :
                $p = [
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
                $ret = ParamsChecked($params, $p);
                if($ret !== true)
                {
                    return DataReturn($ret, -1);
                }
                break;

            // 自提模式 - 验证取货码
            case 2 :
                $p = [
                    [
                        'checked_type'      => 'empty',
                        'key_name'          => 'extraction_code',
                        'error_msg'         => '取货码有误',
                    ],
                ];
                $ret = ParamsChecked($params, $p);
                if($ret !== true)
                {
                    return DataReturn($ret, -1);
                }

                // 校验
                $extraction_code = Db::name('OrderExtractionCode')->where(['order_id'=>$order['id']])->value('code');
                if(empty($extraction_code))
                {
                    return DataReturn('订单取货码不存在、请联系管理员', -10);
                }
                if($extraction_code != $params['extraction_code'])
                {
                    return DataReturn('取货码不正确', -11);
                }
                break;
        }

        // 开启事务
        Db::startTrans();
        $upd_data = [
            'status'            => 3,
            'express_id'        => isset($params['express_id']) ? intval($params['express_id']) : 0,
            'express_number'    => isset($params['express_number']) ? $params['express_number'] : '',
            'delivery_time'     => time(),
            'upd_time'          => time(),
        ];
        if(Db::name('Order')->where($where)->update($upd_data))
        {
            // 库存扣除
            $ret = BuyService::OrderInventoryDeduct(['order_id'=>$order['id'], 'order_data'=>$upd_data]);
            if($ret['code'] != 0)
            {
                // 事务回滚
                Db::rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单发货', '订单已发货', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '收货', $creator, $creator_name);

            // 提交事务
            Db::commit();
            return DataReturn('发货成功', 0);
        }

        // 事务回滚
        Db::rollback();
        return DataReturn('发货失败', -1);
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if(!in_array($order['status'], [3]))
        {
            $status_text = lang('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 开启事务
        Db::startTrans();

        // 更新订单状态
        $upd_data = [
            'status'        => 4,
            'collect_time'  => time(),
            'upd_time'      => time(),
        ];
        if(Db::name('Order')->where($where)->update($upd_data))
        {
            // 订单商品积分赠送
            $ret = IntegralService::OrderGoodsIntegralGiving(['order_id'=>$order['id']]);
            if($ret['code'] != 0)
            {
                // 事务回滚
                Db::rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 订单商品销量增加
            $ret = self::GoodsSalesCountInc(['order_id'=>$order['id']]);
            if($ret['code'] != 0)
            {
                // 事务回滚
                Db::rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单收货', '订单收货成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '收货', $creator, $creator_name);

            // 提交事务
            Db::commit();
            return DataReturn('收货成功', 0);
        }

        // 事务回滚
        Db::rollback();
        return DataReturn('收货失败', -1);
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if(!in_array($order['status'], [0]))
        {
            $status_text = lang('common_order_admin_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        // 开启事务
        Db::startTrans();

        // 更新订单状态
        $upd_data = [
            'status'        => 1,
            'confirm_time'  => time(),
            'upd_time'      => time(),
        ];
        if(Db::name('Order')->where($where)->update($upd_data))
        {
            // 库存扣除
            $ret = BuyService::OrderInventoryDeduct(['order_id'=>$params['id'], 'order_data'=>$upd_data]);
            if($ret['code'] != 0)
            {
                // 事务回滚
                Db::rollback();
                return DataReturn($ret['msg'], -10);
            }

            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单确认', '订单确认成功', 1, $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], '确认', $creator, $creator_name);

            // 事务提交
            Db::commit();
            return DataReturn('确认成功', 0);
        }

        // 事务回滚
        Db::rollback();
        return DataReturn('确认失败', -1);
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
        $ret = ParamsChecked($params, $p);
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
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], $delete_field=>0];
        $order = Db::name('Order')->where($where)->field('id,status,user_id')->find();
        if(empty($order))
        {
            return DataReturn('资源不存在或已被删除', -1);
        }
        if(!in_array($order['status'], [4,5,6]))
        {
            $status_text = lang('common_order_user_status')[$order['status']]['name'];
            return DataReturn('状态不可操作['.$status_text.']', -1);
        }

        $data = [
            $delete_field   => time(),
            'upd_time'      => time(),
        ];
        if(Db::name('Order')->where($where)->update($data))
        {
            // 用户消息
            MessageService::MessageAdd($order['user_id'], '订单删除', '订单删除成功', 1, $order['id']);

            return DataReturn('删除成功', 0);
        }
        return DataReturn('删除失败或资源不存在', -1);
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
        // 状态数据封装
        $result = [];
        $order_status_list = lang('common_order_user_status');
        foreach($order_status_list as $v)
        {
            $result[] = [
                'name'      => $v['name'],
                'status'    => $v['id'],
                'count'     => 0,
            ];
        }

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

        // 用户条件
        if($user_type == 'user')
        {
            if(!empty($params['user']))
            {
                $where['user_id'] = $params['user']['id'];
            } else {
                return DataReturn('用户信息有误', 0, $result);
            }
        }

        $field = 'COUNT(DISTINCT id) AS count, status';
        $data = Db::name('Order')->where($where)->field($field)->group('status')->select();

        // 数据处理
        if(!empty($data))
        {
            foreach($result as &$v)
            {
                foreach($data as $vs)
                {
                    if($v['status'] == $vs['status'])
                    {
                        $v['count'] = $vs['count'];
                        continue;
                    }
                }
            }
        }

        // 待评价 状态站位100
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
                'count'     => (int) Db::name('Order')->where($where)->count(),
            ];
        }

        // 退款/售后 状态站位101
        if(isset($params['is_aftersale']) && $params['is_aftersale'] == 1)
        {
            $where = [
                ['status', '<=', 2],
            ];
            if($user_type == 'user' && !empty($params['user']))
            {
                $where[] = ['user_id', '=', $params['user']['id']];
            }
            $result[] = [
                'name'      => '退款/售后',
                'status'    => 101,
                'count'     => (int) Db::name('OrderAftersale')->where($where)->count(),
            ];
        }
            
        return DataReturn('success', 0, $result);
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
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单商品
        $order_detail = Db::name('OrderDetail')->field('id,goods_id,title,buy_number')->where(['order_id'=>$params['order_id']])->select();
        if(!empty($order_detail))
        {
            foreach($order_detail as $v)
            {
                if(Db::name('Goods')->where(['id'=>$v['goods_id']])->setInc('sales_count', $v['buy_number']) === false)
                {
                    return DataReturn('订单商品销量增加失败['.$v['title'].']', -10);
                }
            }
            return DataReturn('操作成功', 0);
        } else {
            return DataReturn('订单有误，没有找到相关商品', -100);
        }
    }

    /**
     * 支付状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-08
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderPayCheck($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '订单号有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => '用户信息有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单状态
        $where = ['order_no'=>$params['order_no'], 'user_id'=>$params['user']['id']];
        $order = Db::name('Order')->where($where)->field('id,pay_status')->find();
        if(empty($order))
        {
            return DataReturn('订单不存在', -400, ['url'=>__MY_URL__]);
        }
        if($order['pay_status'] == 1)
        {
            return DataReturn('支付成功', 0, ['url'=>MyUrl('index/order/detail', ['id'=>$order['id']])]);
        }
        return DataReturn('支付中', -300);
    }

}
?>