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
namespace app\service;

use think\facade\Db;
use app\service\PaymentService;
use app\service\BuyService;
use app\service\IntegralService;
use app\service\RegionService;
use app\service\ExpressService;
use app\service\ResourcesService;
use app\service\PayLogService;
use app\service\UserService;
use app\service\GoodsService;
use app\service\OrderAftersaleService;
use app\service\OrderCurrencyService;
use app\service\WarehouseService;
use app\service\SystemService;
use app\service\OtherHandleService;

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
     * 业务类型名称
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-02-08
     * @desc    description
     */
    public static function BusinessTypeName()
    {
        return 'order';
    }

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
                'key_name'          => 'ids',
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }
        // 支付订单id
        $ids = is_array($params['ids']) ? $params['ids'] : explode(',', urldecode($params['ids']));
        if(empty($ids))
        {
            return DataReturn(MyLang('order_id_error_tips'), -1);
        }

        // 支付基础信息
        $order_payment_id = 0;
        $client_type = '';
        $order_ids = [];
        $order_nos = [];

        // 订单地址
        $address_data = self::OrderAddressData($ids);

        // 循环处理
        $order_data = [];
        foreach($ids as $k=>$order_id)
        {
            // 获取订单信息
            $where = ['id'=>intval($order_id)];
            $order = Db::name('Order')->where($where)->find();
            if(empty($order))
            {
                return DataReturn(MyLang('order_no_exist_or_delete_error_tips'), -1);
            }
            $operate = self::OrderOperateData($order, 'user');
            if($operate['is_pay'] != 1)
            {
                $status_text = MyConst('common_order_status')[$order['status']]['name'];
                return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_text.'-'.$order['order_no'].']', -1);
            }

            // 订单详情
            $detail = self::OrderItemList([$order]);
            $order['detail'] = (empty($detail) || !array_key_exists($order['id'], $detail)) ? [] : $detail[$order['id']];

            // 订单用户
            $order['user'] = UserService::UserHandle(UserService::UserInfo('id', $order['user_id']));
            if(empty($order['user']))
            {
                return DataReturn(MyLang('common_service.order.order_user_invalid_tips').'['.$order_id.']', -1);
            }

            // 订单地址
            $order['address_data'] = (!empty($address_data) && array_key_exists($order_id, $address_data)) ? $address_data[$order_id] : null;

            // 订单数据集合
            $order_data[] = $order;
            $order_ids[] = $order['id'];
            $order_nos[] = $order['order_no'];

            // 仅第一个订单获取的数据
            if($k == 0)
            {
                $client_type = $order['client_type'];
                $order_payment_id = $order['payment_id'];
            }
        }

        // 订单支付前校验订单商品
        $ret = BuyService::MoreOrderPayBeginCheck(array_merge($params, ['order_data'=>$order_data]));
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 金额为0、走直接支付成功
        $total_price = 0;
        $success_count = 0;
        foreach($order_data as $order)
        {
            if($order['total_price'] <= 0.00)
            {
                $pay_result = self::OrderDirectSuccess([
                    'order'     => $order,
                    'user'      => $order['user'],
                    'params'    => $params,
                ]);
                if($pay_result['code'] == 0)
                {
                    // 已支付成功订单结束当前循环
                    $success_count++;
                    continue;
                }
                return $pay_result;
            }

            // 数据集合
            $total_price += $order['total_price'];
        }

        // 是否直接跳转
        if($success_count > 0 && $success_count == count($order_data))
        {
            return DataReturn(MyLang('operate_success'), 0, ['is_success'=>1]);
        }

        // 订单金额大于0则必须存在支付方式
        // 支付方式、未指定支付方式则获取第一个订单的支付方式
        $payment = [];
        $payment_id = empty($params['payment_id']) ? Db::name('Order')->where(['id'=>$ids[0]])->value('payment_id') : intval($params['payment_id']);
        if(!empty($payment_id))
        {
            $payment = PaymentService::PaymentData(['where'=>['id'=>$payment_id]]);
        }
        if(empty($payment))
        {
            return DataReturn(MyLang('payment_method_error_tips'), -1);
        }

        // 更新订单支付方式信息
        if($payment['id'] != $order_payment_id)
        {
            Db::name('Order')->where(['id'=>$ids])->update([
                'payment_id'    => $payment['id'],
                'is_under_line' => in_array($payment['payment'], MyConfig('shopxo.under_line_list')) ? 1 : 0,
                'upd_time'      => time(),
            ]);
        }

        // 支付入口文件检查
        $pay_checked = PaymentService::EntranceFileChecked($payment['payment'], 'order');
        if($pay_checked['code'] != 0)
        {
            // 入口文件不存在则创建
            $payment_params = [
                'payment'       => $payment['payment'],
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
        $respond_url = $pay_checked['data']['respond'];
        $notify_url = $pay_checked['data']['notify'];

        // 是否指定同步回调地址
        if(!empty($params['redirect_url']))
        {
            $redirect_url = base64_decode(urldecode($params['redirect_url']));
            if(!empty($redirect_url))
            {
                // 赋值同步返回地址
                $respond_url = $redirect_url;
            }
        }
        if(empty($redirect_url))
        {
            $redirect_url = MyUrl('index/order/index');
        }

        // 当前用户
        $current_user = empty($params['user']) ? UserService::LoginUserInfo() : $params['user'];
        if(!empty($current_user))
        {
            // 获取用户最新信息
            $temp_user = UserService::UserHandle(UserService::UserInfo('id', $current_user['id']));
            if(!empty($temp_user))
            {
                $current_user = $temp_user;
            }
        }

        // 发起支付前处理钩子
        $hook_name = 'plugins_service_order_pay_launch_begin';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'user'          => $current_user,
            'business_ids'  => $order_ids,
            'business_nos'  => $order_nos,
            'business_data' => $order_data,
            'total_price'   => $total_price,
            'payment'       => $payment['payment'],
            'payment_name'  => $payment['name'],
            'client_type'   => $client_type,
            'params'        => &$params,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 新增支付日志
        $pay_log = self::OrderPayLogInsert([
            'user_id'       => $current_user['id'],
            'business_ids'  => $order_ids,
            'business_nos'  => $order_nos,
            'business_data' => $order_data,
            'total_price'   => $total_price,
            'payment'       => $payment['payment'],
            'payment_name'  => $payment['name'],
        ]);
        if($pay_log['code'] != 0)
        {
            return $pay_log;
        }

        // 发起支付数据
        $pay_data = [
            'params'        => $params,
            'user'          => $current_user,
            'out_user'      => md5($current_user['id']),
            'business_type' => 'system-order',
            'business_ids'  => $order_ids,
            'business_nos'  => $order_nos,
            'business_data' => $order_data,
            'payment_data'  => $payment,
            'order_id'      => $pay_log['data']['id'],
            'order_no'      => $pay_log['data']['log_no'],
            'name'          => MyLang('common_service.order.pay_subject_name'),
            'total_price'   => $total_price,
            'client_type'   => $client_type,
            'notify_url'    => $notify_url,
            'call_back_url' => $respond_url,
            'redirect_url'  => $redirect_url,
            'site_name'     => MyC('home_site_name', 'ShopXO', true),
            'check_url'     => MyUrl('index/order/paycheck'),
        ];

        // 发起支付处理钩子
        $hook_name = 'plugins_service_order_pay_launch_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'   => $hook_name,
            'is_backend'  => true,
            'pay_log_id'  => $pay_log['data']['id'],
            'pay_log_no'  => $pay_log['data']['log_no'],
            'order_ids'   => $order_ids,
            'params'      => &$params,
            'pay_data'    => &$pay_data,
        ]));
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            return $ret;
        }

        // 微信中打开并且webopenid为空
        if(APPLICATION_CLIENT_TYPE == 'pc' && IsWeixinEnv() && empty($pay_data['user']['weixin_web_openid']))
        {
            // 授权成功后回调订单详情页面重新自动发起支付
            // 单个订单进入详情，则进入列表
            $weixin_params = [
                'is_pay_auto'       => 1,
                'is_pay_submit'     => 1,
                'payment_id'        => $payment['id'],
            ];
            if(count($order_ids) == 1)
            {
                $weixin_params['id'] = $order_ids[0];
                $weixin_params['ids'] = $order_ids[0];
                $url = MyUrl('index/order/detail', $weixin_params);
            } else {
                $weixin_params['ids'] = urldecode(implode(',', $order_ids));
                $url = MyUrl('index/order/index', $weixin_params);
            }
            MySession('plugins_weixinwebauth_pay_callback_view_url', $url);
        }

        // 现金支付业务订单列表记录
        if($payment['payment'] == 'CashPayment')
        {
            MySession('payment_business_order_index_url', MyUrl('index/order/index'));
        }

        // 发起支付
        $pay_name = 'payment\\'.$payment['payment'];
        $ret = (new $pay_name($payment['config']))->Pay($pay_data);
        if(isset($ret['code']) && $ret['code'] == 0)
        {
            // 支付信息返回
            $ret['data'] = [
                // 支付类型(0正常线上支付、1线下支付、2钱包支付)
                'is_payment_type'   => 0,

                // 支付模块处理数据
                'data'              => $ret['data'],

                // 支付日志id
                'order_id'          => $pay_log['data']['id'],
                'order_no'          => $pay_log['data']['log_no'],

                // 支付方式信息
                'payment'           => [
                    'id'        => $payment['id'],
                    'name'      => $payment['name'],
                    'payment'   => $payment['payment'],
                ],
            ];

            // 是否线下支付
            if(in_array($payment['payment'], MyConfig('shopxo.under_line_list')))
            {
                $ret['data']['is_payment_type'] = 1;

                // 线下支付处理
                // 0 订单状态操作支付成功
                // -8888 订单提交成功，等待用户线下支付
                // 其他错误
                $pay_ret = self::UserOrderPayUnderLine($pay_log['data']['log_no']);
                if($pay_ret['code'] == 0)
                {
                    $ret['data']['is_success'] = 1;
                } elseif($pay_ret['code'] == -8888)
                {
                    $ret['msg'] = $pay_ret['msg'];
                } else {
                    return $pay_ret;
                }
            } else {
                // 是否钱包支付
                if($payment['payment'] == 'WalletPay')
                {
                    $ret['data']['is_payment_type'] = 2;
                }
            }

            return $ret;
        }
        return DataReturn(
            empty($ret['msg']) ? MyLang('common_service.order.pay_api_abnormal_tips') : $ret['msg'],
            isset($ret['code']) ? $ret['code'] : -1,
            isset($ret['data']) ? $ret['data'] : '');
    }

    /**
     * 新增订单支付日志
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-28
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function OrderPayLogInsert($params = [])
    {
        $business_ids = isset($params['business_ids']) ? $params['business_ids'] : [];
        $business_nos = isset($params['business_nos']) ? $params['business_nos'] : [];
        return PayLogService::PayLogInsert([
            'user_id'       => isset($params['user_id']) ? intval($params['user_id']) : 0,
            'business_ids'  => is_array($business_ids) ? $business_ids : [$business_ids],
            'business_nos'  => is_array($business_nos) ? $business_nos : [$business_nos],
            'total_price'   => isset($params['total_price']) ? PriceNumberFormat($params['total_price']) : 0.00,
            'subject'       => MyLang('common_service.order.pay_subject_name'),
            'payment'       => isset($params['payment']) ? $params['payment'] : '',
            'payment_name'  => isset($params['payment_name']) ? $params['payment_name'] : '',
            'business_type' => self::BusinessTypeName(),
        ]);
    }

    /**
     * 线下订单支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-26
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderPaymentUnderLinePay($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'id',
                'error_msg'         => MyLang('order_id_error_tips'),
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
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }
        $operate = self::OrderOperateData($order, 'admin');
        if($operate['is_pay'] != 1)
        {
            $status_text = MyConst('common_order_status')[$order['status']]['name'];
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_text.'-'.$order['order_no'].']', -1);
        }

        // 订单支付前校验
        $ret = BuyService::SingleOrderPayBeginCheck(['order_id'=>$order['id'], 'order_data'=>$order]);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 支付方式
        $payment_id = empty($params['payment_id']) ? $order['payment_id'] : intval($params['payment_id']);
        $payment = PaymentService::PaymentData(['where'=>['id'=>$payment_id]]);
        if(empty($payment))
        {
            return DataReturn(MyLang('payment_method_error_tips'), -1);
        }

        // 订单用户信息
        $user = UserService::GetUserViewInfo($order['user_id']);
        if(empty($user))
        {
            return DataReturn(MyLang('common_service.order.order_user_invalid_tips'), -1);
        }

        // 线下支付处理
        return self::OrderPaymentUnderLineSuccess([
            'order'     => $order,
            'payment'   => $payment,
            'user'      => $user,
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
    public static function OrderDirectSuccess($params = [])
    {
        if(!empty($params['order']) && !empty($params['user']))
        {
            if($params['order']['total_price'] <= 0.00)
            {
                // 支付处理
                $pay_params = [
                    'order'         => [$params['order']],
                    'payment'       => [],
                    'pay_log_data'  => [],
                    'pay'           => [
                        'trade_no'      => '',
                        'subject'       => isset($params['params']['subject']) ? $params['params']['subject'] : MyLang('common_service.order.pay_subject_name'),
                        'buyer_user'    => $params['user']['user_name_view'],
                        'pay_price'     => $params['order']['total_price'],
                    ],
                ];
                return self::OrderPayHandle($pay_params);
            }
            return DataReturn(MyLang('common_service.order.pay_price_error_tips'), -1);
        }
        return DataReturn(MyLang('common_service.order.pay_params_error_tips'), -1);
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
    public static function OrderPaymentUnderLineSuccess($params = [])
    {
        if(!empty($params['order']) && !empty($params['payment']) && !empty($params['user']))
        {
            if(in_array($params['payment']['payment'], MyConfig('shopxo.under_line_list')))
            {
                // 新增支付日志
                $pay_log = self::OrderPayLogInsert([
                    'user_id'       => $params['user']['id'],
                    'business_ids'  => $params['order']['id'],
                    'business_nos'  => $params['order']['order_no'],
                    'total_price'   => $params['order']['total_price'],
                    'payment'       => $params['payment']['payment'],
                    'payment_name'  => $params['payment']['name'],
                ]);
                if($pay_log['code'] != 0)
                {
                    return $pay_log;
                }

                // 支付处理
                $pay_params = [
                    'order'         => [$params['order']],
                    'payment'       => $params['payment'],
                    'pay_log_data'  => $pay_log['data'],
                    'pay'           => [
                        'trade_no'      => '',
                        'subject'       => isset($params['params']['subject']) ? $params['params']['subject'] : MyLang('common_service.order.pay_subject_name'),
                        'buyer_user'    => $params['user']['user_name_view'],
                        'pay_price'     => $params['order']['total_price'],
                    ],
                ];
                return self::OrderPayHandle($pay_params);
            }
            return DataReturn(MyLang('common_service.order.only_under_line_error_tips'), -1);
        }
        return DataReturn(MyLang('common_service.order.pay_params_error_tips'), -1);
    }

    /**
     * 支付同步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    一般仅web端回调这个页面
     * @param   [array]          $params [输入参数]
     */
    public static function Respond($params = [])
    {
        // 支付方式
        $payment_name = defined('PAYMENT_TYPE') ? PAYMENT_TYPE : (isset($params['paymentname']) ? $params['paymentname'] : '');
        if(empty($payment_name))
        {
            return DataReturn(MyLang('payment_method_error_tips'), -1);
        }
        $payment = PaymentService::PaymentData(['where'=>['payment'=>$payment_name]]);
        if(empty($payment))
        {
            return DataReturn(MyLang('payment_method_error_tips'), -1);
        }

        // 支付数据校验
        $pay_name = 'payment\\'.$payment_name;
        $pay_ret = (new $pay_name($payment['config']))->Respond(array_merge(input('get.'), input('post.')));
        if(isset($pay_ret['code']) && $pay_ret['code'] == 0)
        {
            // 线下支付方式
            if(in_array($payment_name, MyConfig('shopxo.under_line_list')))
            {
                // 线下支付处理
                // cpde=-8888 则表示需要用户线下支付，仅表示订单已提交成功
                $ret = self::UserOrderPayUnderLine($pay_ret['data']['out_trade_no']);
                if($ret['code'] == -8888)
                {
                    $pay_ret['msg'] = $ret['msg'];
                }
            }
        }
        return DataReturn(
                    empty($pay_ret['msg']) ? MyLang('pay_fail') : $pay_ret['msg'],
                    isset($pay_ret['code']) ? $pay_ret['code'] : -100,
                    isset($pay_ret['data']) ? $pay_ret['data'] : ''
                );
    }

    /**
     * 用户线下支付订单
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-13
     * @desc    description
     * @param   [string]          $pay_log_no [订单支付日志单号]
     */
    public static function UserOrderPayUnderLine($pay_log_no)
    {
        // 是否开启线下支付订单状态正常进行
        if(MyC('common_is_under_line_order_normal') == 1)
        {
            // 支付订单数据
            $pay_data = self::OrderPayLogValueList($pay_log_no);
            if($pay_data['code'] != 0)
            {
                return $pay_data;
            }

            // 订单支付日志已支付则直接返回
            if($pay_data['data']['pay_log_data']['status'] == 1)
            {
                return DataReturn(MyLang('operate_success'), 0);
            }

            // 启动事务
            Db::startTrans();

            // 捕获异常
            try {
                // 更新订单状态
                $order_ids = array_column($pay_data['data']['order_list'], 'id');
                $upd_data = [
                    'status'    => 2,
                    'upd_time'  => time(),
                ];
                if(!Db::name('Order')->where(['id'=>$order_ids])->update($upd_data))
                {
                    throw new \Exception(MyLang('common_service.order.order_update_fail_tips'));
                }

                // 循环处理订单
                foreach($pay_data['data']['order_list'] as $order)
                {
                    if(!self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], MyLang('common_service.order.user_under_line_pay_history_desc'), 0, MyLang('system_title')))
                    {
                        throw new \Exception(MyLang('common_service.order.order_status_history_insert_fail_tips').'['.$order['id'].']');
                    }
                }

                // 更改日志订单状态
                if(!Db::name('PayLog')->where(['log_no'=>$pay_log_no])->update(['status'=>1]))
                {
                    throw new \Exception(MyLang('common_service.order.order_status_history_update_fail_tips'));
                }

                // 完成
                Db::commit();
                return DataReturn(MyLang('pay_success'), 0);
            } catch(\Exception $e) {
                Db::rollback();
                return DataReturn($e->getMessage(), -1);
            }
        }
        return DataReturn(MyLang('common_service.order.order_submit_await_confirm_tips'), -8888);
    }

    /**
     * 支付异步
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
        $payment = PaymentService::PaymentData(['where'=>['payment'=>PAYMENT_TYPE]]);
        if(empty($payment))
        {
            return DataReturn(MyLang('payment_method_error_tips'), -1);
        }

        // 支付数据校验
        $pay_name = 'payment\\'.PAYMENT_TYPE;
        if(!class_exists($pay_name))
        {
            return DataReturn(MyLang('payment_method_no_exist_tips').'['.PAYMENT_TYPE.']', -1);
        }
        $payment_obj = new $pay_name($payment['config']);

        // 是否存在处理方法
        $method = method_exists($payment_obj, 'Notify') ? 'Notify' : 'Respond';
        $pay_ret = $payment_obj->$method(array_merge(input('get.'), input('post.')));
        if(!isset($pay_ret['code']) || $pay_ret['code'] != 0)
        {
            return $pay_ret;
        }

        // 支付结果处理
        return self::NotifyHandle($pay_ret['data'], $payment);
    }

    /**
     * 支付异步处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     * @param   [array]          $data      [支付数据]
     * @param   [array]          $payment   [支付方式]
     */
    public static function NotifyHandle($data, $payment)
    {
        // 支付订单数据
        if(empty($data['out_trade_no']))
        {
            return DataReturn(MyLang('order_no_error_tips').'[out_trade_no]', -1);
        }
        $pay_data = self::OrderPayLogValueList($data['out_trade_no']);
        if($pay_data['code'] == 0)
        {
            // 订单支付日志已支付则直接返回
            if($pay_data['data']['pay_log_data']['status'] == 1)
            {
                return DataReturn(MyLang('common_service.order.pay_log_already_pay_tips'), 0);
            }
        } else {
            return $pay_data;
        }

        // 支付金额是否小于订单金额
        if(MyC('common_is_pay_price_must_max_equal', 0) == 1)
        {
            if($data['pay_price'] < $pay_data['data']['pay_log_data']['total_price'])
            {
                return DataReturn(MyLang('common_service.order.pay_price_less_than_order_price_tips').'['.$data['pay_price'].'<'.$pay_data['data']['pay_log_data']['total_price'].']', -1);
            }
        }

        // 支付处理
        $pay_params = [
            'order'         => $pay_data['data']['order_list'],
            'payment'       => $payment,
            'pay_log_data'  => $pay_data['data']['pay_log_data'],
            'pay'           => [
                'trade_no'      => $data['trade_no'],
                'subject'       => $data['subject'],
                'buyer_user'    => $data['buyer_user'],
                'pay_price'     => $data['pay_price'],
            ],
        ];

        // 支付成功异步通知处理钩子
        $hook_name = 'plugins_service_order_pay_notify_handle';
        $ret = EventReturnHandle(MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'payment'       => $payment,
            'order'         => $pay_data['data']['order_list'],
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
     * 订单支付日志订单列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-13
     * @desc    description
     * @param   [string]          $pay_log_no [支付日志订单号]
     */
    public static function OrderPayLogValueList($pay_log_no)
    {
        // 获取支付日志订单
        $pay_log_data = Db::name('PayLog')->where(['log_no'=>$pay_log_no])->find();
        if(empty($pay_log_data))
        {
            return DataReturn(MyLang('common_service.order.pay_log_error_tips'), -1);
        }

        // 获取关联信息
        $pay_log_value = Db::name('PayLogValue')->where(['pay_log_id'=>$pay_log_data['id']])->column('business_id');
        if(empty($pay_log_value))
        {
            return DataReturn(MyLang('common_service.order.pay_log_value_error_tips'), -1);
        }

        // 获取订单
        $order_list = Db::name('Order')->where(['id'=>$pay_log_value, 'status'=>1])->select()->toArray();
        // 订单数据不存在、并且日志订单非支付状态则报错
        if(empty($order_list) && $pay_log_data['status'] != 1)
        {
            return DataReturn(MyLang('order_info_incorrect_tips'), -1);
        }

        return DataReturn(MyLang('get_success'), 0, [
            'pay_log_data'  => $pay_log_data,
            'order_list'    => $order_list,
        ]);
    }

    /**
     * 订单支付处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-28
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function OrderPayHandle($params = [])
    {
        // 订单信息
        if(empty($params['order']) || !is_array($params['order']))
        {
            return DataReturn(MyLang('common_service.order.order_data_empty_or_format_error_tips'), -1);
        }

        // 订单金额大于0必须存在支付方式和订单支付日志
        $order_total_price = array_sum(array_column($params['order'], 'total_price'));
        if($order_total_price > 0)
        {
            // 支付方式
            if(empty($params['payment']))
            {
                return DataReturn(MyLang('payment_method_error_tips'), -1);
            }

            // 日志订单
            if(empty($params['pay_log_data']))
            {
                return DataReturn(MyLang('common_service.order.pay_log_error_tips'), -2);
            }
        }

        // 查看支付日志是否已支付处理成功、避免异步通知太快导致重叠处理
        if($order_total_price > 0 && !empty($params['pay_log_data']) && !empty($params['payment']))
        {
            $pay_log_status = Db::name('PayLog')->where(['id'=>$params['pay_log_data']['id']])->field('id,status')->value('status');
            if($pay_log_status == 1)
            {
                return DataReturn(MyLang('pay_success'), 0);
            }
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 先更新支付日志
            if($order_total_price > 0 && !empty($params['pay_log_data']) && !empty($params['payment']))
            {
                $pay_log_data = [
                    'log_id'        => $params['pay_log_data']['id'],
                    'trade_no'      => isset($params['pay']['trade_no']) ? $params['pay']['trade_no'] : '',
                    'buyer_user'    => isset($params['pay']['buyer_user']) ? $params['pay']['buyer_user'] : '',
                    'pay_price'     => isset($params['pay']['pay_price']) ? $params['pay']['pay_price'] : 0,
                    'subject'       => isset($params['pay']['subject']) ? $params['pay']['subject'] : MyLang('common_service.order.pay_subject_name'),
                    'payment'       => $params['payment']['payment'],
                    'payment_name'  => $params['payment']['name'],
                ];
                $ret = PayLogService::PayLogSuccess($pay_log_data);
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }
            }

            // 循环处理
            foreach($params['order'] as $order)
            {
                // 订单非待支付则不处理
                if($order['pay_status'] != 0)
                {
                    continue;
                }

                // 订单支付成功处理前钩子
                $hook_name = 'plugins_service_order_pay_handle_begin';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => &$params,
                    'order_id'      => $order['id']
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 消息通知
                $detail = MyLang('common_service.order.order_pay_user_message_msg', ['price'=>$order['total_price']]);
                MessageService::MessageAdd($order['user_id'], MyLang('common_service.order.pay_subject_name'), $detail, self::BusinessTypeName(), $order['id']);

                // 订单更新数据
                $upd_data = [
                    'pay_status'    => 1,
                    'pay_price'     => $order['total_price'],
                    'pay_time'      => time(),
                    'upd_time'      => time(),
                ];

                // 避免先走订单、后走支付的逻辑
                if($order['status'] <= 1)
                {
                    $upd_data['status'] = 2;
                }

                // 订单金额大于0
                if($order['total_price'] > 0 && !empty($params['payment']))
                {
                    // 更新支付方式
                    $upd_data['payment_id'] = $params['payment']['id'];

                    // 是否线下支付
                    $upd_data['is_under_line'] = in_array($params['payment']['payment'], MyConfig('shopxo.under_line_list')) ? 1 : 0;
                }

                // 更新订单
                if(!Db::name('Order')->where(['id'=>$order['id']])->update($upd_data))
                {
                    throw new \Exception(MyLang('common_service.order.order_update_fail_tips').'['.$order['id'].']');
                }

                // 添加状态日志
                if(array_key_exists('status', $upd_data))
                {
                    if(!self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], MyLang('payment_title'), 0, MyLang('system_title')))
                    {
                        throw new \Exception(MyLang('common_service.order.order_status_history_insert_fail_tips').'['.$order['id'].']');
                    }
                }

                // 库存扣除
                $ret = BuyService::OrderInventoryDeduct(['order_id'=>$order['id'], 'opt_type'=>'pay']);
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 订单商品销量增加
                $ret = self::GoodsSalesCountInc(['order_id'=>$order['id'], 'opt_type'=>'pay']);
                if($ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 订单支付成功处理完毕钩子
                $hook_name = 'plugins_service_order_pay_success_handle_end';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                    'hook_name'     => $hook_name,
                    'is_backend'    => true,
                    'params'        => $params,
                    'order'         => $order,
                    'order_id'      => $order['id']
                ]));
                if(isset($ret['code']) && $ret['code'] != 0)
                {
                    throw new \Exception($ret['msg']);
                }

                // 虚拟商品自动触发发货操作
                if($order['order_model'] == 3)
                {
                    self::OrderDeliveryHandle([
                        'id'                => $order['id'],
                        'creator'           => 0,
                        'creator_name'      => MyLang('system_title'),
                        'user_id'           => $order['user_id'],
                        'user_type'         => 'admin',
                    ]);
                }
            }

            // 提交事务
            Db::commit();
            return DataReturn(MyLang('pay_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
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
            $oids = Db::name('Order')->where([['order_no', '=', $params['keywords']]])->column('id');
            if(!empty($oids))
            {
                $where[] = ['id', 'in', $oids];
                $keywords_status = true;
            }

            // 快递单号查询
            if($keywords_status === false)
            {
                $oid = Db::name('OrderExpress')->where(['express_number'=>$params['keywords']])->value('order_id');
                if(!empty($oid))
                {
                    $where[] = ['id', '=', $oid];
                    $keywords_status = true;
                }
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
                $oids = Db::name('OrderAddress')->where([['name|tel|extraction_contact_name|extraction_contact_tel', '=', $params['keywords']]])->column('order_id');
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
        // 订单总数读取前钩子
        $hook_name = 'plugins_service_order_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'where'         => &$where,
        ]);

        // 获取总数
        return (int) Db::name('Order')->where($where)->count();
    }

    /**
     * 订单状态各项总数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-29
     * @desc    description
     * @param   [array]          $where [条件]
     */
    public static function OrderStatusGroupTotal($where = [])
    {
        // 订单状态各项总数读取前钩子
        $hook_name = 'plugins_service_order_status_group_total_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'where'         => &$where,
        ]);

        // 获取状态各项总数
        return Db::name('Order')->where($where)->field('COUNT(DISTINCT id) AS count, status')->group('status')->select()->toArray();
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
        $field = empty($params['field']) ? '*' : $params['field'];
        $order_by = empty($params['order_by']) ? 'id desc' : $params['order_by'];
        $m = isset($params['m']) ? intval($params['m']) : 0;
        $n = isset($params['n']) ? intval($params['n']) : 10;

        // 订单列表读取前钩子
        $hook_name = 'plugins_service_order_list_begin';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'params'        => &$params,
            'where'         => &$where,
            'field'         => &$field,
            'order_by'      => &$order_by,
            'm'             => &$m,
            'n'             => &$n,
        ]);

        // 获取订单
        $data = Db::name('Order')->where($where)->field($field)->limit($m, $n)->order($order_by)->select()->toArray();
        
        // 数据处理
        return self::OrderListHandle($data, $params);
    }

    /**
     * 订单数据处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-05
     * @desc    description
     * @param   [array]          $data      [订单数据]
     * @param   [array]          $params    [输入参数]
     */
    public static function OrderListHandle($data, $params = [])
    {
        $result = [];
        if(!empty($data))
        {
            // 订单列表钩子-前面
            $hook_name = 'plugins_service_order_list_handle_begin';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);

            // 字段列表
            $keys = ArrayKeys($data);
            $order_ids = array_column($data, 'id');

            // 其它额外处理
            $is_operate = isset($params['is_operate']) ? intval($params['is_operate']) : 0;
            $is_items = isset($params['is_items']) ? intval($params['is_items']) : 1;
            $is_orderaftersale = isset($params['is_orderaftersale']) ? intval($params['is_orderaftersale']) : 0;
            $is_status_history = isset($params['is_status_history']) ? intval($params['is_status_history']) : 0;
            $user_type = isset($params['user_type']) ? $params['user_type'] : 'user';

            // 静态数据
            $order_status_list = MyConst('common_order_status');
            $order_pay_status = MyConst('common_order_pay_status');
            $common_platform_type = MyConst('common_platform_type');
            $common_order_type_list = MyConst('common_order_type_list');
            $order_take_status_name = MyLang('common_service.order.order_take_status_name');
            $order_under_line_pay_status_name = MyLang('common_service.order.order_under_line_pay_status_name');
            $order_under_line_name = MyLang('common_service.order.order_under_line_name');

            // 仓库信息
            if(in_array('warehouse_id', $keys))
            {
                $we_ids = array_unique(array_column($data, 'warehouse_id'));
                $warehouse_list = WarehouseService::WarehouseListHandle(Db::name('Warehouse')->where(['id'=>$we_ids])->field('id,name')->select()->toArray());
                if(!empty($warehouse_list))
                {
                    $warehouse_list = array_column($warehouse_list, null, 'id');
                }
            }

            // 默认货币
            $currency_default = ResourcesService::CurrencyData();

            // 订单货币
            $currency_data = OrderCurrencyService::OrderCurrencyGroupList($order_ids);

            // 用户列表
            if(in_array('user_id', $keys) && isset($params['is_public']) && $params['is_public'] == 0)
            {
                $user_list = UserService::GetUserViewInfo(array_column($data, 'user_id'));
            }

            // 支付方式名称
            $payment_list = PaymentService::OrderPaymentName($order_ids);

            // 取货码
            $extraction_data = self::OrderExtractionData($order_ids);

            // 订单地址
            $address_data = self::OrderAddressData($order_ids);

            // 订单快递
            $express_data = self::OrderExpressData($order_ids);

            // 订单服务
            $service_data = self::OrderServiceData($order_ids);

            // 订单详情
            $detail = ($is_items == 1) ? self::OrderItemList($data, $is_orderaftersale) : [];

            // 订单日志
            $status_history_data = ($is_status_history == 1) ? self::OrderStatusHistoryList($order_ids) : [];

            // 循环处理数据
            foreach($data as &$v)
            {
                // 订单处理前钩子
                $hook_name = 'plugins_service_order_handle_begin';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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

                // 订单货币
                $v['currency_data'] = (!empty($currency_data) && is_array($currency_data) && array_key_exists($v['id'], $currency_data)) ? $currency_data[$v['id']] : $currency_default;

                // 订单所属仓库
                if(isset($v['warehouse_id']))
                {
                    if(!empty($warehouse_list) && is_array($warehouse_list) && array_key_exists($v['warehouse_id'], $warehouse_list))
                    {
                        $v['warehouse_name'] = $warehouse_list[$v['warehouse_id']]['name'];
                        $v['warehouse_icon'] = $warehouse_list[$v['warehouse_id']]['icon'];
                        $v['warehouse_url'] = $warehouse_list[$v['warehouse_id']]['url'];
                    } else {
                        $v['warehouse_name'] = '';
                        $v['warehouse_icon'] = '';
                        $v['warehouse_url'] = '';
                    }
                }

                // 订单模式处理
                // 快递、外送、自提模式
                if(in_array($v['order_model'], [0,1,2]))
                {
                    // 销售模式+自提模式 地址信息
                    $v['address_data'] = (!empty($address_data) && array_key_exists($v['id'], $address_data)) ? $address_data[$v['id']] : null;

                    // 自提模式 添加订单取货码
                    if($v['order_model'] == 2)
                    {
                        $v['extraction_data'] = (isset($v['status']) && !in_array($v['status'], [0,1,5,6]) && !empty($extraction_data) && array_key_exists($v['id'], $extraction_data)) ? $extraction_data[$v['id']] : null;
                    }
                }

                // 快递信息
                $v['express_data'] = (!empty($express_data) && array_key_exists($v['id'], $express_data)) ? $express_data[$v['id']] : null;

                // 服务信息
                $v['service_data'] = (!empty($service_data) && array_key_exists($v['id'], $service_data)) ? $service_data[$v['id']] : null;

                // 用户信息
                if(isset($v['user_id']))
                {
                    if(isset($params['is_public']) && $params['is_public'] == 0)
                    {
                        $v['user'] = (!empty($user_list) && is_array($user_list) && array_key_exists($v['user_id'], $user_list)) ? $user_list[$v['user_id']] : [];
                    }
                }

                // 订单模式
                $v['order_model_name'] = isset($common_order_type_list[$v['order_model']]) ? $common_order_type_list[$v['order_model']]['name'] : '';

                // 客户端
                $v['client_type_name'] = isset($common_platform_type[$v['client_type']]) ? $common_platform_type[$v['client_type']]['name'] : '';

                // 状态
                $v['status_name'] = ($v['order_model'] == 2 && $v['status'] == 2) ? $order_take_status_name : (array_key_exists($v['status'], $order_status_list) ? $order_status_list[$v['status']]['name'] : '');

                // 支付状态
                $v['pay_status_name'] = (in_array($v['status'], [2,3,4]) && $v['pay_status'] == 0) ? $order_under_line_pay_status_name : $order_pay_status[$v['pay_status']]['name'];

                // 支付方式
                $v['payment_name'] = (!empty($payment_list) && is_array($payment_list) && array_key_exists($v['id'], $payment_list)) ? $payment_list[$v['id']] : null;

                // 线下支付 icon 名称
                $v['is_under_line_text'] = ($v['is_under_line'] == 1) ? $order_under_line_name : null;

                // 是否可发起售后
                $v['is_can_launch_aftersale'] = OrderAftersaleService::OrderIsCanLaunchAftersale($v['collect_time']);

                // 创建时间
                $v['add_time_time'] = date('Y-m-d H:i:s', $v['add_time']);
                $v['add_time_date'] = date('Y-m-d', $v['add_time']);
                $v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

                // 更新时间
                $v['upd_time'] = empty($v['upd_time']) ? null : date('Y-m-d H:i:s', $v['upd_time']);

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
                if(empty($v['user_note']))
                {
                    $v['user_note'] = null;
                }

                // 扩展数据
                $v['extension_data'] = empty($v['extension_data']) ? null : json_decode($v['extension_data'], true);

                // 订单详情
                if($is_items == 1 && !empty($detail) && array_key_exists($v['id'], $detail))
                {
                    $v['items'] = $detail[$v['id']];
                    $v['items_count'] = count($v['items']);
                    $v['describe'] = MyLang('common_service.order.order_item_summary_desc', ['buy_number_count'=>$v['buy_number_count'], 'currency_symbol'=>$v['currency_data']['currency_symbol'], 'total_price'=>$v['total_price']]);
                }

                // 订单日志
                if($is_status_history == 1)
                {
                    $v['status_history_data'] = array_key_exists($v['id'], $status_history_data) ? $status_history_data[$v['id']] : [];
                }

                // 管理员读取
                if($user_type == 'admin')
                {
                    // 获取最新一条售后订单
                    $v['aftersale_first'] = self::OrderAftersaleFirst($v['id']);
                }

                // 操作状态
                if($is_operate == 1 && isset($v['status']) && isset($v['pay_status']))
                {
                    $v['operate_data'] = self::OrderOperateData($v, $user_type);
                }

                // 订单处理后钩子
                $hook_name = 'plugins_service_order_handle_end';
                $ret = EventReturnHandle(MyEventTrigger($hook_name, [
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

            // 微信小程序发货数据
            if($is_operate == 1 && APPLICATION_CLIENT_TYPE == 'weixin' && MyC('common_app_mini_weixin_upload_shipping_status') == 1)
            {
                $weixin_collect_order_ids = array_filter(array_map(function($item)
                    {
                        if(!empty($item['operate_data']) && isset($item['operate_data']['is_collect']) && $item['operate_data']['is_collect'] == 1)
                        {
                            return  $item['id'];
                        }
                    }, $data));
                if(!empty($weixin_collect_order_ids))
                {
                    $pay_log = Db::name('PayLog')->alias('pl')->join('pay_log_value plv', 'pl.id=plv.pay_log_id')->where(['plv.business_id'=>$weixin_collect_order_ids, 'pl.business_type'=>self::BusinessTypeName(), 'pl.status'=>1, 'pl.payment'=>'Weixin'])->column('pl.trade_no', 'plv.business_id');
                    if(!empty($pay_log))
                    {
                        foreach($data as $k=>$v)
                        {
                            if(array_key_exists($v['id'], $pay_log))
                            {
                                $data[$k]['weixin_collect_data'] = $pay_log[$v['id']];
                            }
                        }
                    }
                }
            }

            // 订单列表钩子-后面
            $hook_name = 'plugins_service_order_list_handle_end';
            MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'params'        => &$params,
                'data'          => &$data,
            ]);
        }
        return DataReturn('success', 0, $data);
    }

    /**
     * 订单操作状态处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-13
     * @desc    description
     * @param   [array]          $data      [订单数据]
     * @param   [string]         $user_type [用户类型（user 用户、admin 管理员）]
     */
    public static function OrderOperateData($data, $user_type = 'user')
    {
        $result = [
            // 确认
            'is_confirm'    => 0,
            // 支付
            'is_pay'        => 0,
            // 发货
            'is_delivery'   => 0,
            // 同城
            'is_service'    => 0,
            // 取货
            'is_take'       => 0,
            // 收货 
            'is_collect'    => 0,
            // 取消
            'is_cancel'     => 0,
            // 删除
            'is_delete'     => 0,
            // 评论
            'is_comments'   => 0,
        ];
        if(isset($data['status']) && isset($data['pay_status']))
        {
            // 管理员
            if($user_type == 'admin')
            {
                // 确认
                $result['is_confirm']   = ($data['status'] == 0) ? 1 : 0;
                // 支付
                $result['is_pay']       = ($data['pay_status'] == 0 && !in_array($data['status'], [0,5,6])) ? 1 : 0;
                // 发货
                $result['is_delivery']  = (isset($data['order_model']) && $data['order_model'] == 0 && in_array($data['status'], [2,3])) ? 1 : 0;
                // 同城
                $result['is_service']   = (isset($data['order_model']) && $data['order_model'] == 1 && in_array($data['status'], [2,3])) ? 1 : 0;
                // 取货
                $result['is_take']      = (isset($data['order_model']) && in_array($data['order_model'], [2,3]) && $data['status'] == 2) ? 1 : 0;
                // 收货
                $result['is_collect']   = ($data['status'] == 3) ? 1 : 0;
                // 取消
                $result['is_cancel']    = (in_array($data['status'], [0,1]) || (in_array($data['status'], [2,3,4]) && $data['pay_status'] == 0)) ? 1 : 0;
                // 删除
                $result['is_delete']    = (in_array($data['status'], [5,6]) && isset($data['is_delete_time']) && $data['is_delete_time'] == 0) ? 1 : 0;

            // 用户
            } else {
                // 支付
                $result['is_pay']        = ($data['status'] == 1) ? 1 : 0;
                // 收货
                $result['is_collect']    = ($data['status'] == 3) ? 1 : 0;
                // 取消
                $result['is_cancel']     = (in_array($data['status'], [0,1]) || $data['status'] == 2 && $data['pay_status'] == 0) ? 1 : 0;
                // 评价
                $result['is_comments']   = ($data['status'] == 4 && isset($data['user_is_comments']) && $data['user_is_comments'] == 0) ? 1 : 0;
                // 删除
                $result['is_delete']     = (in_array($data['status'], [4,5,6]) && isset($data['user_is_delete_time']) && $data['user_is_delete_time'] == 0) ? 1 : 0;
            }
        }
        return $result;
    }

    /**
     * 订单最新一条售后
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-05-15
     * @desc    description
     * @param   [int]          $order_id     [订单 id]
     */
    public static function OrderAftersaleFirst($order_id)
    {
        $data = Db::name('OrderAftersale')->where(['order_id'=>$order_id])->field('status,type,number,price,reason,msg')->order('id desc')->find();
        if(!empty($data))
        {
            $type_list = MyConst('common_order_aftersale_type_list');
            $status_list = MyConst('common_order_aftersale_status_list');

            // 类型
            $data['type_text'] = array_key_exists($data['type'], $type_list) ? $type_list[$data['type']]['name'] : '';

            // 状态
            $data['status_text'] = array_key_exists($data['status'], $status_list) ? $status_list[$data['status']]['name'] : '';
        }
        return $data;
    }

    /**
     * 订单日志数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-02-28
     * @desc    description
     * @param   [array|int]          $order_ids [订单id]
     */
    public static function OrderStatusHistoryList($order_ids)
    {
        $data = Db::name('OrderStatusHistory')->where(['order_id'=>$order_ids])->select()->toArray();
        if(!empty($data))
        {
            $group = [];
            foreach($data as &$v)
            {
                // 添加时间
                $v['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);

                // 订单id是数组则处理分组
                if(is_array($order_ids))
                {
                    if(!array_key_exists($v['order_id'], $group))
                    {
                        $group[$v['order_id']] = [];
                    }
                    $group[$v['order_id']][] = $v;
                }
            }
            // 如果订单id是数组则赋值分组
            if(is_array($order_ids) && !empty($group))
            {
                $data = $group;
            }
        }
        return $data;
    }

    /**
     * 订单详情列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2022-06-22
     * @desc    description
     * @param   [array]       $order             [订单信息]
     * @param   [int]         $is_orderaftersale [是否读取订单售后（0否, 1是）]
     */
    public static function OrderItemList($order, $is_orderaftersale = 0)
    {
        $result = [];
        $order = array_column($order, null, 'id');
        $order_ids = array_keys($order);
        $detail = Db::name('OrderDetail')->where(['order_id'=>$order_ids])->select()->toArray();
        if(!empty($detail))
        {
            // 订单详情自增id
            $order_detail_ids = array_column($detail, 'id');

            // 虚拟商品取货码
            $fictitious_value_list = Db::name('OrderFictitiousValue')->where(['order_detail_id'=>$order_detail_ids])->column('value', 'order_detail_id');

            // 是否获取最新一条售后信息
            $orderaftersale = [];
            if($is_orderaftersale == 1)
            {
                $temp_aftersale = Db::name('OrderAftersale')->where(['order_detail_id'=>$order_detail_ids])->order('id desc')->select()->toArray();
                if(!empty($temp_aftersale))
                {
                    foreach($temp_aftersale as $av)
                    {
                        if(!array_key_exists($av['order_detail_id'], $orderaftersale))
                        {
                            $orderaftersale[$av['order_detail_id']] = $av;
                        }
                    }
                }
            }

            // 商品处理
            $res = GoodsService::GoodsDataHandle($detail, ['data_key_field'=>'goods_id']);
            $data = $res['data'];
            $detail = array_column($detail, null, 'id');
            foreach($data as $v)
            {
                // 当前商品订单信息
                if(array_key_exists($v['order_id'], $order))
                {
                    // 当前商品详情主订单信息
                    $ov = $order[$v['order_id']];

                    // 避免订单商品价格被处理，强制使用原始内容
                    $v['price'] = $detail[$v['id']]['price'];
                    $v['total_price'] = $detail[$v['id']]['total_price'];

                    // 规格
                    $v['spec_text'] = null;
                    if(!empty($v['spec']))
                    {
                        $v['spec'] = json_decode($v['spec'], true);
                        if(!empty($v['spec']) && is_array($v['spec']))
                        {
                            $v['spec_text'] = implode('，', array_map(function($spec)
                            {
                                return $spec['type'].':'.$spec['value'];
                            }, $v['spec']));
                        }
                    } else {
                        $v['spec'] = null;
                    }

                    // 虚拟销售商品 - 虚拟信息处理
                    if(isset($ov['order_model']) && isset($ov['pay_status']) && isset($ov['status']) && $ov['order_model'] == 3 && $ov['pay_status'] == 1 && in_array($ov['status'], [3,4]))
                    {
                        $v['fictitious_goods_value'] = (!empty($fictitious_value_list) && is_array($fictitious_value_list) && array_key_exists($v['id'], $fictitious_value_list)) ? ResourcesService::ContentStaticReplace($fictitious_value_list[$v['id']], 'get') : '';
                    }

                    // 是否获取最新一条售后信息
                    if($is_orderaftersale == 1 && isset($ov['status']))
                    {
                        $v['orderaftersale'] = (!empty($orderaftersale) && array_key_exists($v['id'], $orderaftersale)) ? $orderaftersale[$v['id']] : null;
                        $v['orderaftersale_btn_text'] = self::OrderAftersaleStatusBtnText($ov['status'], $v['orderaftersale']);
                    }

                    // 加入分组
                    if(!array_key_exists($v['order_id'], $result))
                    {
                        $result[$v['order_id']] = [];
                    }
                    $result[$v['order_id']][] = $v;
                }
            }
        }
        return $result;
    }

    /**
     * 订单自提信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-26
     * @desc    description
     * @param   [array]          $order_ids    [订单id]
     */
    public static function OrderExtractionData($order_ids)
    {
        // 必须返回的内容格式
        $result = [];

        // 获取取货码
        $data = Db::name('OrderExtractionCode')->where(['order_id'=>array_unique($order_ids)])->column('code', 'order_id');
        if(!empty($data) && is_array($data))
        {
            foreach($order_ids as $v)
            {
                $images = null;
                if(array_key_exists($v, $data))
                {
                    // 生成二维码参数
                    $params = [
                        'content'   => $data[$v],
                        'path'      => DS.'download'.DS.'order'.DS.'extraction_code'.DS,
                        'filename'  => $v.'.png',
                    ];

                    // 图片不存在则去生成二维码图片并保存至目录
                    $ret = (new \base\Qrcode())->Create($params);
                    $images = ($ret['code'] == 0) ? $ret['data']['url'] : null;
                }
                $result[$v] = [
                    'code'      => isset($data[$v]) ? $data[$v] : null,
                    'images'    => $images,
                ];
            }
        }

        // 订单自提信息钩子
        $hook_name = 'plugins_service_order_extraction_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_ids'     => $order_ids,
            'data'          => &$result,
        ]);

        return $result;
    }

    /**
     * 订单服务
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-26
     * @desc    description
     * @param   [array]          $order_ids    [订单id]
     */
    public static function OrderServiceData($order_ids)
    {
        $data = Db::name('OrderService')->where(['order_id'=>$order_ids])->column('*', 'order_id');
        if(!empty($data) && is_array($data))
        {
            $day_unit = MyLang('day_title');
            $hour_unit = MyLang('hour_title');
            $minute_unit = MyLang('minute_title');
            foreach($data as &$v)
            {
                // 持续时长
                if(empty($v['service_duration_minute']))
                {
                    $v['service_duration_minute_text'] = '';
                } else {
                    $hour = intval($v['service_duration_minute']/60);
                    $minute = $v['service_duration_minute']-($hour*60);
                    if($hour > 24)
                    {
                        $day = intval($hour/24);
                        $hours = $hour-($day*60);
                        $v['service_duration_minute_text'] = $day.$day_unit.(($hours <= 0) ? '' : $hours.$hour_unit);
                    } else {
                        $v['service_duration_minute_text'] = ($hour <= 0) ? '' : $hour.$hour_unit;
                    }
                    if(!empty($minute))
                    {
                        $v['service_duration_minute_text'] .= $minute.$minute_unit;
                    }
                }                

                // 开始和结束时间
                $v['service_start_time'] = empty($v['service_start_time']) ? '' : date('Y-m-d H:i:s', $v['service_start_time']);
                $v['service_end_time'] = empty($v['service_end_time']) ? '' : date('Y-m-d H:i:s', $v['service_end_time']);

                // 时间
                $v['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);
            }
        }

        // 订单服务信息钩子
        $hook_name = 'plugins_service_order_service_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_ids'     => $order_ids,
            'data'          => &$data,
        ]);

        return empty($data) ? [] : $data;
    }

    /**
     * 订单快递
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-26
     * @desc    description
     * @param   [array]          $order_ids    [订单id]
     */
    public static function OrderExpressData($order_ids)
    {
        $data = [];
        $temp = Db::name('OrderExpress')->where(['order_id'=>$order_ids])->select()->toArray();
        if(!empty($temp) && is_array($temp))
        {
            $express_list = ExpressService::ExpressData(array_unique(array_filter(array_column($temp, 'express_id'))));
            foreach($temp as $v)
            {
                // 快递信息处理
                $express = (!empty($express_list) && is_array($express_list) && array_key_exists($v['express_id'], $express_list)) ? $express_list[$v['express_id']] : null;
                if(empty($express))
                {
                    $v['express_name'] = '';
                    $v['express_icon'] = '';
                    $v['express_website_url'] = '';
                } else {
                    $v['express_name'] = $express['name'];
                    $v['express_icon'] = $express['icon'];
                    $v['express_website_url'] = $express['website_url'];
                }
                $v['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d H:i:s', $v['add_time']);
                $v['upd_time'] = empty($v['upd_time']) ? '' : date('Y-m-d H:i:s', $v['upd_time']);

                // 数据按照订单id分组
                if(!array_key_exists($v['order_id'], $data))
                {
                    $data[$v['order_id']] = [];
                }
                $data[$v['order_id']][] = $v;
            }
        }

        // 订单快递信息钩子
        $hook_name = 'plugins_service_order_express_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_ids'     => $order_ids,
            'data'          => &$data,
        ]);

        return empty($data) ? [] : $data;
    }

    /**
     * 订单地址
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-11-26
     * @desc    description
     * @param   [array]          $order_ids    [订单id]
     */
    public static function OrderAddressData($order_ids)
    {
        // 销售模式+自提模式 地址信息
        $data = Db::name('OrderAddress')->where(['order_id'=>$order_ids])->column('*', 'order_id');
        if(!empty($data) && is_array($data))
        {
            foreach($data as &$v)
            {
                // 附件
                $v['idcard_front_old'] = $v['idcard_front'];
                $v['idcard_front'] =  ResourcesService::AttachmentPathViewHandle($v['idcard_front']);
                $v['idcard_back_old'] = $v['idcard_back'];
                $v['idcard_back'] =  ResourcesService::AttachmentPathViewHandle($v['idcard_back']);
            }
        }

        // 订单地址信息钩子
        $hook_name = 'plugins_service_order_address_data';
        MyEventTrigger($hook_name, [
            'hook_name'     => $hook_name,
            'is_backend'    => true,
            'order_ids'     => $order_ids,
            'data'          => &$data,
        ]);

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
    public static function OrderAftersaleStatusBtnText($order_status, $orderaftersale)
    {
        $text = null;
        if(!in_array($order_status, [0,1,5,6]))
        {
            $lang = MyLang('common_service.order.orderaftersale_create_title_data');
            if(empty($orderaftersale))
            {
                $text = $lang['default'];
                if($order_status == 4)
                {
                    $text = $lang['collect'];
                }
            } else {
                $text = ($orderaftersale['status'] == 3) ? $lang['success'] : $lang['step'];
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
        $status_list = MyConst('common_order_status');
        $original_status_name = ($original_status != '' && isset($status_list[$original_status])) ? $status_list[$original_status]['name'] : '';
        $new_status_name = ($new_status != '' && isset($status_list[$new_status])) ? $status_list[$new_status]['name'] : '';
        if(!empty($original_status_name) && !empty($new_status_name))
        {
            $msg .= '['.$original_status_name.'-'.$new_status_name.']';
        } else if(!empty($original_status_name))
        {
            $msg .= '['.$original_status_name.']';
        } else if(!empty($new_status_name))
        {
            $msg .= '['.$new_status_name.']';
        }

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
            MyEventTrigger($hook_name, [
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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => MyLang('user_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户类型
        $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,pay_status,user_id,order_model')->find();
        if(empty($order))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }
        // 有效订单情况下、如果未支付可以正常进行取消操作
        $operate = self::OrderOperateData($order, $user_type);
        if($operate['is_cancel'] != 1)
        {
            $status_text = MyConst('common_order_status')[$order['status']]['name'];
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_text.']', -1);
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
            $lang = MyLang('common_service.order.order_cancel_message_data');
            MessageService::MessageAdd($order['user_id'], $lang['title'], $lang['desc'], self::BusinessTypeName(), $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], MyLang('cancel_title'), $creator, $creator_name);

            // 提交事务
            Db::commit();
            return DataReturn(MyLang('cancel_success'), 0);
        }

        // 事务回滚
        Db::rollback();
        return DataReturn(MyLang('cancel_fail'), -1);
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
        // 订单发货处理
        Db::startTrans();
        $ret = self::OrderDeliveryHandle($params);
        if($ret['code'] == 0)
        {
            Db::commit();
        } else {
            Db::rollback();
        }
        return $ret;
    }

    /**
     * 订单发货处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $params [输入参数]
     */
    public static function OrderDeliveryHandle($params = [])
    {
        // 捕获异常
        try {
            // 请求参数
            $p = [
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'id',
                    'error_msg'         => MyLang('order_id_error_tips'),
                ],
                [
                    'checked_type'      => 'empty',
                    'key_name'          => 'user_id',
                    'error_msg'         => MyLang('user_id_error_tips'),
                ],
            ];
            $ret = ParamsChecked($params, $p);
            if($ret !== true)
            {
                throw new \Exception($ret);
            }

            // 用户类型
            $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];

            // 获取订单信息
            $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
            $order = Db::name('Order')->where($where)->field('id,status,pay_status,user_id,order_model,client_type')->find();
            if(empty($order))
            {
                throw new \Exception(MyLang('order_no_exist_or_delete_error_tips'));
            }
            $operate = self::OrderOperateData($order, $user_type);
            if($operate['is_delivery'] != 1 && $operate['is_take'] != 1 && $operate['is_service'] != 1)
            {
                $status_text = MyConst('common_order_status')[$order['status']]['name'];
                throw new \Exception(MyLang('status_not_can_operate_tips').'['.$status_text.']');
            }

            // 订单模式
            switch($order['order_model'])
            {
                // 销售模式- 订单快递信息校验
                case 0 :
                    if((empty($params['express_id']) || empty($params['express_number'])) && empty($params['express_data']))
                    {
                        throw new \Exception(MyLang('common_service.order.delivery_express_data_message'));
                    }
                    break;

                // 自提模式 - 验证取货码
                case 2 :
                    $p = [
                        [
                            'checked_type'      => 'empty',
                            'key_name'          => 'extraction_code',
                            'error_msg'         => MyLang('common_service.order.take_extraction_code_message'),
                        ],
                    ];
                    $ret = ParamsChecked($params, $p);
                    if($ret !== true)
                    {
                        throw new \Exception($ret);
                    }

                    // 校验
                    $extraction_code = Db::name('OrderExtractionCode')->where(['order_id'=>$order['id']])->value('code');
                    if(empty($extraction_code))
                    {
                        throw new \Exception(MyLang('common_service.order.take_extraction_code_empty_tips'));
                    }
                    if($extraction_code != $params['extraction_code'])
                    {
                        throw new \Exception(MyLang('common_service.order.take_extraction_code_error_tips'));
                    }
                    break;
            }

            // 发货更新操作
            $ret = self::OrderDeliveryUpdateHandle($order, $params);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }
            return $ret;
        } catch(\Exception $e) {
            return DataReturn($e->getMessage(), -1);
        }
    }

    /**
     * 订单发货更新处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     * @param   [array]          $order [订单信息]
     * @param   [array]          $params[输入参数]
     */
    public static function OrderDeliveryUpdateHandle($order, $params = [])
    {
        // 订单更新
        $upd_data = [
            'status'         => 3,
            'delivery_time'  => time(),
            'upd_time'       => time(),
        ];
        if(Db::name('Order')->where(['id'=>$order['id']])->update($upd_data) === false)
        {
            return DataReturn(MyLang('delivery_fail'), -1);
        }

        // 发货信息单条模式处理
        switch($order['order_model'])
        {
            // 快递
            case 0 :
                if(!empty($params['express_id']) && !empty($params['express_number']))
                {
                    $express_data = [
                        'express_id'      => intval($params['express_id']),
                        'express_number'  => $params['express_number'],
                    ];
                    if(empty($params['order_express_id']))
                    {
                        $express_data['order_id']  = $order['id'];
                        $express_data['user_id']   = $order['user_id'];
                        $express_data['add_time']  = time();
                        if(Db::name('OrderExpress')->insertGetId($express_data) <= 0)
                        {
                            return DataReturn(MyLang('common_service.order.delivery_express_insert_fail_tips'), -1);
                        }
                    } else {
                        $express_data['upd_time'] = time();
                        if(Db::name('OrderExpress')->where(['id'=>intval($params['order_express_id']), 'order_id'=>$order['id']])->update($express_data) === false)
                        {
                            return DataReturn(MyLang('common_service.order.delivery_express_update_fail_tips'), -1);
                        }
                    }
                } else {
                    // 没有发货信息，但是存在发货数据id则删除发货信息
                    if(!empty($params['order_express_id']))
                    {
                        Db::name('OrderExpress')->where(['id'=>intval($params['order_express_id']), 'order_id'=>$order['id']])->delete();
                    }

                    // 发货信息多条模式处理
                    if(!empty($params['express_data']))
                    {
                        if(!is_array($params['express_data']))
                        {
                            $params['express_data'] = json_decode(urldecode(htmlspecialchars_decode($params['express_data'])), true);
                        }
                    }

                    // 没有发货信息则删除全部
                    if(!empty($params['express_data']) && is_array($params['express_data']))
                    {
                        // 原始数据
                        $express_data_old = Db::name('OrderExpress')->where(['order_id'=>$order['id']])->column('*', 'id');

                        // 数据处理
                        $express_insert_data = [];
                        foreach($params['express_data'] as $ev)
                        {
                            if(!empty($ev['express_id']) && !empty($ev['express_number']))
                            {
                                $temp = [
                                    'order_id'        => $order['id'],
                                    'user_id'         => $order['user_id'],
                                    'express_id'      => intval($ev['express_id']),
                                    'express_number'  => $ev['express_number'],
                                    'note'            => empty($ev['note']) ? '' : $ev['note'],
                                ];
                                if(empty($express_data_old) || empty($ev['id']) || empty($express_data_old[$ev['id']]))
                                {
                                    $temp['add_time'] = time();
                                    $temp['upd_time'] = 0;
                                } else {
                                    $temp['add_time'] = $express_data_old[$ev['id']]['add_time'];
                                    $temp['upd_time'] = time();
                                }
                                $express_insert_data[] = $temp;
                            }
                        }

                        // 先删除数据
                        if(!empty($express_data_old))
                        {
                            Db::name('OrderExpress')->where(['id'=>array_column($express_data_old, 'id'), 'order_id'=>$order['id']])->delete();
                        }

                        // 数据添加处理
                        if(!empty($express_insert_data))
                        {
                            if(Db::name('OrderExpress')->insertAll($express_insert_data) < count($express_insert_data))
                            {
                                return DataReturn(MyLang('common_service.order.delivery_express_insert_fail_tips'), -1);
                            }
                        }
                    } else {
                        Db::name('OrderExpress')->where(['order_id'=>$order['id']])->delete();
                    }
                }
                break;

            // 外送服务
            case 1 :
                // 服务数据
                $service_data = [
                    'order_id'            => $order['id'],
                    'user_id'             => $order['user_id'],
                    'service_name'        => empty($params['service_name']) ? '' : trim($params['service_name']),
                    'service_mobile'      => empty($params['service_mobile']) ? '' : trim($params['service_mobile']),
                    'service_start_time'  => empty($params['service_start_time']) ? 0 : strtotime($params['service_start_time']),
                    'service_end_time'    => empty($params['service_end_time']) ? 0 : strtotime($params['service_end_time']),
                    'note'                => empty($params['note']) ? '' : trim($params['note']),
                ];
                // 持续时间
                if(!empty($service_data['service_start_time']) && !empty($service_data['service_end_time']) && $service_data['service_start_time'] < $service_data['service_end_time'])
                {
                    $service_data['service_duration_minute'] = intval(($service_data['service_end_time']-$service_data['service_start_time'])/60);
                } else {
                    $service_data['service_duration_minute'] = 0;
                }
                $service_info = Db::name('OrderService')->where(['order_id'=>$order['id']])->find();
                if(empty($service_info))
                {
                    $service_data['add_time'] = time();
                    if(Db::name('OrderService')->insertGetId($service_data) <= 0)
                    {
                        return DataReturn(MyLang('common_service.order.delivery_service_insert_fail_tips'), -1);
                    }
                } else {
                    $service_data['upd_time'] = time();
                    if(Db::name('OrderService')->where(['id'=>$service_info['id']])->update($service_data) === false)
                    {
                        return DataReturn(MyLang('common_service.order.delivery_service_update_fail_tips'), -1);
                    }
                }
                break;
        }

        // 库存扣除
        $ret = BuyService::OrderInventoryDeduct(['order_id'=>$order['id'], 'opt_type'=>'delivery']);
        if($ret['code'] != 0)
        {
            return $ret;
        }

        // 用户消息
        $lang = MyLang('common_service.order.order_delivery_message_data');
        MessageService::MessageAdd($order['user_id'], $lang['title'], $lang['desc'], self::BusinessTypeName(), $order['id']);

        // 订单状态日志
        $creator = isset($params['creator']) ? intval($params['creator']) : 0;
        $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
        self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], MyLang('delivery_title'), $creator, $creator_name);

        // 同步微信发货
        if(isset($order['client_type']) && $order['client_type'] == 'weixin')
        {
            $ret = self::OrderDeliverySyncWeixin($order, $params);
            if(!empty($ret) && isset($ret['code']) && $ret['code'] != 0)
            {
                return $ret;
            }
        }

        // 完成
        $msg = ($order['order_model'] == 1) ? MyLang('service_success') : ($order['order_model'] == 2 ? MyLang('verification_success') : MyLang('delivery_success'));
        return DataReturn($msg, 0);
    }

    /**
     * 微信小程序发货同步到微信小程序
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-01-16
     * @desc    description
     * @param   [array]          $order  [订单信息]
     * @param   [array]          $params [输入参数]
     */
    public static function OrderDeliverySyncWeixin($order, $params = [])
    {
        $order_detail = Db::name('OrderDetail')->where(['order_id'=>$order['id']])->column('title');
        if(!empty($order_detail))
        {
            // 获取订单地址、用户电话
            $order_address = self::OrderAddressData($order['id']);
            $receiver_tel = (!empty($order_address) && !empty($order_address[$order['id']]) && !empty($order_address[$order['id']]['tel'])) ? $order_address[$order['id']]['tel'] : '';

            // 是否存在多条发货信息
            if(!empty($params['express_data']) && !empty($params['express_data'][0]) && isset($params['express_data'][0]['express_id']) && isset($params['express_data'][0]['express_number']))
            {
                $params['express_id'] = $params['express_data'][0]['express_id'];
                $params['express_number'] = $params['express_data'][0]['express_number'];
            }

            // 发货快递信息
            $express_id = isset($params['express_id']) ? intval($params['express_id']) : 0;
            $express_number = isset($params['express_number']) ? $params['express_number'] : '';

            // 调用微信发货同步处理
            return OtherHandleService::OrderDeliverySyncWeixinHandle([
                'business_id'     => $order['id'],
                'business_type'   => self::BusinessTypeName(),
                'order_model'     => $order['order_model'],
                'goods_title'     => $order_detail,
                'express_id'      => $express_id,
                'express_number'  => $express_number,
                'receiver_tel'    => $receiver_tel,
            ]);
        }
        return DataReturn(MyLang('handle_noneed'), 0);
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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => MyLang('user_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户类型
        $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,pay_status,user_id,order_model')->find();
        if(empty($order))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }
        $operate = self::OrderOperateData($order, $user_type);
        if($operate['is_collect'] != 1)
        {
            $status_text = MyConst('common_order_status')[$order['status']]['name'];
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_text.']', -1);
        }

        // 开启事务
        Db::startTrans();

        // 收货处理
        $ret = self::OrderCollectHandle($order, $params);
        if($ret['code'] == 0)
        {
            Db::commit();
        } else {
            Db::rollback();
        }
        return $ret;
    }

    /**
     * 订单收货完成处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-26
     * @desc    description
     * @param   [array]          $order  [订单数据]
     * @param   [array]          $params [输入参数]
     */
    public static function OrderCollectHandle($order, $params = [])
    {
        // 更新订单状态
        $upd_data = [
            'status'        => 4,
            'collect_time'  => time(),
            'upd_time'      => time(),
        ];
        if(Db::name('Order')->where(['id'=>$order['id']])->update($upd_data))
        {
            // 订单商品积分赠送
            $ret = IntegralService::OrderGoodsIntegralGiving(['order_id'=>$order['id']]);
            if($ret['code'] != 0)
            {
                return $ret;
            }

            // 订单商品销量增加
            $ret = self::GoodsSalesCountInc(['order_id'=>$order['id'], 'opt_type'=>'collect']);
            if($ret['code'] != 0)
            {
                return $ret;
            }

            // 用户消息
            $lang = MyLang('common_service.order.order_collect_message_data');
            MessageService::MessageAdd($order['user_id'], $lang['title'], $lang['desc'], self::BusinessTypeName(), $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], MyLang('collect_title'), $creator, $creator_name);

            return DataReturn(MyLang('collect_success'), 0);
        }
        return DataReturn(MyLang('collect_fail'), -1);
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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => MyLang('user_id_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户类型
        $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], 'is_delete_time'=>0, 'user_is_delete_time'=>0];
        $order = Db::name('Order')->where($where)->field('id,status,pay_status,user_id,order_model')->find();
        if(empty($order))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }
        $operate = self::OrderOperateData($order, $user_type);
        if($operate['is_confirm'] != 1)
        {
            $status_text = MyConst('common_order_status')[$order['status']]['name'];
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_text.']', -1);
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 更新订单状态
            $upd_data = [
                'status'        => 1,
                'confirm_time'  => time(),
                'upd_time'      => time(),
            ];
            if(!Db::name('Order')->where($where)->update($upd_data))
            {
                throw new \Exception(MyLang('confirm_fail'));
            }

            // 库存扣除
            $ret = BuyService::OrderInventoryDeduct(['order_id'=>$params['id'], 'opt_type'=>'confirm']);
            if($ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 用户消息
            $lang = MyLang('common_service.order.order_confirm_message_data');
            MessageService::MessageAdd($order['user_id'], $lang['title'], $lang['desc'], self::BusinessTypeName(), $order['id']);

            // 订单状态日志
            $creator = isset($params['creator']) ? intval($params['creator']) : 0;
            $creator_name = isset($params['creator_name']) ? htmlentities($params['creator_name']) : '';
            self::OrderHistoryAdd($order['id'], $upd_data['status'], $order['status'], MyLang('confirm_title'), $creator, $creator_name);

            // 完成
            Db::commit();
            return DataReturn(MyLang('confirm_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_id',
                'error_msg'         => MyLang('user_id_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user_type',
                'error_msg'         => MyLang('user_type_error_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 用户类型
        $user_type = empty($params['user_type']) ? 'user' : $params['user_type'];
        switch($user_type)
        {
            case 'admin' :
                $delete_field = 'is_delete_time';
                break;
            case 'user' :
                $delete_field = 'user_is_delete_time';
                break;
            default :
                $delete_field = empty($params['delete_field']) ? '' : $params['delete_field'];
        }
        if(empty($delete_field))
        {
            return DataReturn(MyLang('user_type_error_tips').'['.$user_type.']', -2);
        }

        // 获取订单信息
        $where = ['id'=>intval($params['id']), 'user_id'=>$params['user_id'], $delete_field=>0];
        $order = Db::name('Order')->where($where)->field('id,status,pay_status,user_id,order_model,is_delete_time,user_is_delete_time')->find();
        if(empty($order))
        {
            return DataReturn(MyLang('data_no_exist_or_delete_error_tips'), -1);
        }
        $operate = self::OrderOperateData($order, $user_type);
        if($operate['is_delete'] != 1)
        {
            $status_text = MyConst('common_order_status')[$order['status']]['name'];
            return DataReturn(MyLang('status_not_can_operate_tips').'['.$status_text.']', -1);
        }

        // 启动事务
        Db::startTrans();

        // 捕获异常
        try {
            // 删除操作
            $data = [
                $delete_field   => time(),
                'upd_time'      => time(),
            ];
            if(!Db::name('Order')->where($where)->update($data))
            {
                throw new \Exception(MyLang('delete_fail'));
            }

            // 用户消息
            $lang = MyLang('common_service.order.order_delete_message_data');
            MessageService::MessageAdd($order['user_id'], $lang['title'], $lang['desc'], self::BusinessTypeName(), $order['id']);

            // 订单删除成功钩子
            $hook_name = 'plugins_service_order_delete_success';
            $ret = EventReturnHandle(MyEventTrigger($hook_name, [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'order_id'      => $params['id'],
                'params'        => $params,
            ]));
            if(isset($ret['code']) && $ret['code'] != 0)
            {
                throw new \Exception($ret['msg']);
            }

            // 完成
            Db::commit();
            return DataReturn(MyLang('delete_success'), 0);
        } catch(\Exception $e) {
            Db::rollback();
            return DataReturn($e->getMessage(), -1);
        }
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
        $is_comments = isset($params['is_comments']) && $params['is_comments'] == 1;
        $is_aftersale = isset($params['is_aftersale']) && $params['is_aftersale'] == 1;
        $order_status_list = MyConst('common_order_status_step_total_list');
        foreach($order_status_list as $v)
        {
            // 订单正常状态
            // 待评价
            // 订单售后
            if($v['value'] <= 6 || ($is_comments && $v['value'] == 100) || ($is_aftersale && $v['value'] == 101))
            {
                    $result[] = [
                    'name'      => $v['name'],
                    'status'    => $v['value'],
                    'count'     => 0,
                ];
            }
        }

        // 是否需要查询数据
        $is_query = true;

        // 条件
        $where = [
            ['is_delete_time', '=', 0]
        ];

        // 用户类型
        $user_type = isset($params['user_type']) ? $params['user_type'] : 'user';
        if($user_type == 'user')
        {
            // 用户为空责不查询数据
            if(empty($params['user']))
            {
                $is_query = false;
            } else {
                // 增加用户条件
                $where[] = ['user_is_delete_time', '=', 0];
                $where[] = ['user_id', '=', $params['user']['id']];
            }
        }
        if($is_query)
        {
            // 订单状态各项总数
            $data = self::OrderStatusGroupTotal($where);

            // 待评价 状态占位100
            if($is_comments)
            {
                switch($user_type)
                {
                    case 'user' :
                        $where[] = ['user_is_comments', '=', 0];
                        break;
                    case 'admin' :
                        $where[] = ['is_comments', '=', 0];
                        break;
                    default :
                        $where[] = ['user_is_comments', '=', 0];
                        $where[] = ['is_comments', '=', 0];
                }
                $where[] = ['status', '=', 4];
                $data[] = [
                    'status'    => 100,
                    'count'     => self::OrderTotal($where),
                ];
            }
        }

        // 退款/售后 状态占位101
        if($is_aftersale)
        {
            $aftersale = [
                'status'    => 101,
                'count'     => 0,
            ];
            if($is_query)
            {
                $where = [
                    ['status', '<=', 2],
                ];
                if($user_type == 'user' && !empty($params['user']))
                {
                    $where[] = ['user_id', '=', $params['user']['id']];
                }
                $aftersale['count'] = OrderAftersaleService::OrderAftersaleTotal($where);
            }
            $data[] = $aftersale;
        }

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
                'error_msg'         => MyLang('order_id_error_tips'),
            ],
            [
                'checked_type'      => 'in',
                'key_name'          => 'opt_type',
                'checked_data'      => ['pay', 'collect'],
                'error_msg'         => '订单操作类型有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 增加销量规则、默认订单收货
        $status = false;
        if(MyC('common_goods_sales_count_inc_rules', 1) == 1)
        {
            // 订单收货
            if($params['opt_type'] == 'collect')
            {
                $status = true;
            }
        } else {
            // 订单支付
            if($params['opt_type'] == 'pay')
            {
                $status = true;
            }
        }
        if($status)
        {
            // 获取订单商品
            $order_detail = Db::name('OrderDetail')->field('id,goods_id,title,buy_number')->where(['order_id'=>$params['order_id']])->select()->toArray();
            if(!empty($order_detail))
            {
                foreach($order_detail as $v)
                {
                    if(Db::name('Goods')->where(['id'=>$v['goods_id']])->inc('sales_count', $v['buy_number'])->update() === false)
                    {
                        return DataReturn(MyLang('common_service.order.order_goods_sales_count_inc_fail_tips').'['.$v['title'].']', -10);
                    }
                }
                return DataReturn(MyLang('operate_success'), 0);
            } else {
                return DataReturn(MyLang('common_service.order.order_detail_goods_empty_tips'), -100);
            }
        }
        return DataReturn(MyLang('handle_noneed'), 0);
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
                'error_msg'         => MyLang('order_no_error_tips'),
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'user',
                'error_msg'         => MyLang('user_info_incorrect_tips'),
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 获取订单状态
        $where = ['log_no'=>$params['order_no'], 'user_id'=>$params['user']['id']];
        $pay_log = Db::name('PayLog')->where($where)->field('id,status')->find();
        if(empty($pay_log))
        {
            return DataReturn(MyLang('common_service.order.pay_log_error_tips'), -400, ['url'=>SystemService::DomainUrl()]);
        }
        if($pay_log['status'] == 1)
        {
            $pay_log_value = Db::name('PayLogValue')->where(['pay_log_id'=>$pay_log['id']])->column('business_id');
            if(empty($pay_log_value) || count($pay_log_value) > 1)
            {
                $url = MyUrl('index/order/index');
            } else {
                $url = MyUrl('index/order/detail', ['id'=>$pay_log_value[0]]);
            }
            return DataReturn(MyLang('pay_success'), 0, ['url'=>$url]);
        }
        return DataReturn(MyLang('common_service.order.pay_have_in_hand_tips'), -333);
    }

    /**
     * 订单支付参数处理
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-08-07
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    public static function PayParamsHandle($params = [])
    {
        // 支付方式
        $payment_id = empty($params['payment_id']) ? '' : intval($params['payment_id']);

        // 支付订单id、多个订单id以英文逗号分割[ , ]
        // 严格处理参数，避免非法数据
        $order_ids = '';
        if(!empty($params['ids']))
        {
            $ids = array_filter(array_map(function($v)
            {
                return intval($v);
            }, explode(',', urldecode($params['ids']))));
            if(!empty($ids))
            {
                $order_ids = implode(',', $ids);
            }
        }
        return [
            'payment_id'    => $payment_id,
            'order_ids'     => $order_ids,
        ];
    }

    /**
     * 订单进度数据
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2023-01-23
     * @desc    description
     * @param   [array]          $order [订单数据]
     */
    public static function OrderStepData($order)
    {
        $result = [];
        if(!empty($order))
        {
            $lang = MyLang('common_service.order.order_status_setp_data');
            $result[] = [
                'title'     => $lang['add'],
                'time'      => $order['add_time'],
                'is_check'  => 1,
                'is_current'=> 1,
            ];
            // 订单取消、关闭
            if(in_array($order['status'], [5,6]))
            {
                switch($order['status'])
                {
                    // 取消
                    case 5 :
                        $result[] = [
                            'title'     => $lang['cancel'],
                            'time'      => $order['cancel_time'],
                            'is_check'  => 1,
                            'is_current'=> 1,
                        ];
                        break;
                    // 关闭
                    case 6 :
                        $result[] = [
                            'title'     => $lang['close'],
                            'time'      => $order['close_time'],
                            'is_check'  => 1,
                            'is_current'=> 1,
                        ];
                        break;
                }
            } else {
                // 支付
                $result[] = [
                    'title'     => $lang['pay'],
                    'time'      => empty($order['pay_time']) ? '' : $order['pay_time'],
                    'is_check'  => ($order['status'] > 1) ? 1 : 0,
                    'is_current'=> ($order['status'] == 2) ? 1 : 0,
                ];
                // 卖家发货
                $result[] = [
                    'title'     => $lang['delivery'],
                    'time'      => empty($order['delivery_time']) ? '' : $order['delivery_time'],
                    'is_check'  => ($order['status'] > 2) ? 1 : 0,
                    'is_current'=> ($order['status'] == 3) ? 1 : 0,
                ];
                // 确认收货
                $result[] = [
                    'title'     => $lang['collect'],
                    'time'      => empty($order['collect_time']) ? '' : $order['collect_time'],
                    'is_check'  => ($order['status'] > 3) ? 1 : 0,
                    'is_current'=> ($order['status'] == 4) ? 1 : 0,
                ];
                // 评价
                $is_current = empty($order['user_is_comments']) ? 0 : 1;
                $result[] = [
                    'title'     => $lang['comments'],
                    'time'      => empty($order['user_is_comments_time']) ? '' : $order['user_is_comments_time'],
                    'is_check'  => $is_current,
                    'is_current'=> $is_current,
                ];
            }
        }
        return $result;
    }
}
?>