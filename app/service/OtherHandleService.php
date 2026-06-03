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
use app\service\ExpressService;
use app\service\AppMiniUserService;

/**
 * 其他处理服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OtherHandleService
{
    /**
     * 微信发货同步处理方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2024-01-17
     * @desc    description
     * @param   [int]           $params['order_model']      [订单模式]
     * @param   [int]           $params['business_id']      [业务订单id]
     * @param   [string]        $params['business_type']    [业务订单类型]
     * @param   [int]           $params['express_id']       [发货快递id]
     * @param   [string]        $params['express_number']   [发货快递单号]
     * @param   [int]           $params['receiver_tel']     [收件人电话]
     * @param   [string|array]  $params['goods_title']      [货物名称]
     */
    public static function OrderDeliverySyncWeixinHandle($params = [])
    {
        if(isset($params['order_model']) && isset($params['business_id']) && isset($params['business_type']) && !empty($params['goods_title']))
        {
            // 获取支付日志
            $pay_log_ids = Db::name('PayLogValue')->where(['business_id'=>$params['business_id']])->column('pay_log_id');
            if(!empty($pay_log_ids))
            {
                // 仅获取【微信、微信扫码、微信app小程序】支付方式的订单日志
                // 仅【微信、ios、android】客户端发起的支付
                $where = [
                    ['id', 'in', $pay_log_ids],
                    ['business_type', '=', $params['business_type']],
                    ['status', '=', 1],
                ];
                $pay_log = Db::name('PayLog')->field('system_type,trade_no,buyer_user')->where($where)->where(function($query)
                    {
                        $query->whereOr([
                            [
                                ['payment', 'in', ['Weixin', 'WeixinScanQrcode']],
                                ['client_type', '=', 'weixin'],
                            ],
                            [
                                ['payment', '=', 'WeixinAppMini'],
                                ['client_type', 'in', ['ios', 'android']],
                            ]
                        ]);
                    })->find();
                if(!empty($pay_log))
                {
                    $config_params = [
                        'system_type' => $pay_log['system_type']
                    ];
                    if(AppMiniUserService::AppMiniConfig('common_app_mini_weixin_upload_shipping_status', $config_params) == 1)
                    {
                        $trade_no = $pay_log['trade_no'];
                        $buyer_user = $pay_log['buyer_user'];

                        // 发货快递信息
                        $express_id = isset($params['express_id']) ? intval($params['express_id']) : 0;
                        $express_number = isset($params['express_number']) ? $params['express_number'] : '';
                        $receiver_tel = isset($params['receiver_tel']) ? $params['receiver_tel'] : '';

                        // 调用微信发货同步
                        return (new \base\Wechat(AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appid', $config_params), AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appsecret', $config_params)))->MiniUploadShippingInfo([
                            'order_model'     => $params['order_model'],
                            'trade_no'        => $pay_log['trade_no'],
                            'buyer_user'      => $pay_log['buyer_user'],
                            'goods_title'     => $params['goods_title'],
                            'express_name'    => ExpressService::ExpressName($express_id),
                            'express_number'  => $express_number,
                            'receiver_tel'    => $receiver_tel,
                            'consignor_tel'   => MyC('common_customer_store_chat_tel'),
                        ]);
                    }
                }
            }
        }
        return DataReturn(MyLang('handle_noneed'), 0);
    }

    /**
     * 微信发货同步加入队列（支付成功/发货后调用，由定时脚本同步微信）
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-05-26
     * @desc    description
     * @param   [array]          $params [同 OrderDeliverySyncWeixinHandle]
     */
    public static function OrderDeliverySyncWeixinQueueAdd($params = [])
    {
        if(!isset($params['order_model']) || !isset($params['business_id']) || !isset($params['business_type']) || empty($params['goods_title']))
        {
            return DataReturn(MyLang('handle_noneed'), 0);
        }

        $goods_title = $params['goods_title'];
        if(is_array($goods_title))
        {
            $goods_title = json_encode($goods_title, JSON_UNESCAPED_UNICODE);
        }

        $express_data = '';
        if(!empty($params['express_data']))
        {
            $express_data = is_array($params['express_data']) ? json_encode($params['express_data'], JSON_UNESCAPED_UNICODE) : $params['express_data'];
        }
        $service_data = '';
        if(!empty($params['service_data']))
        {
            $service_data = is_array($params['service_data']) ? json_encode($params['service_data'], JSON_UNESCAPED_UNICODE) : $params['service_data'];
        }

        $data = [
            'business_id'     => intval($params['business_id']),
            'business_type'   => $params['business_type'],
            'order_model'     => intval($params['order_model']),
            'goods_title'     => $goods_title,
            'express_id'      => isset($params['express_id']) ? intval($params['express_id']) : 0,
            'express_number'  => isset($params['express_number']) ? $params['express_number'] : '',
            'receiver_tel'    => isset($params['receiver_tel']) ? $params['receiver_tel'] : '',
            'express_data'    => $express_data,
            'service_data'    => $service_data,
            'status'          => 0,
            'fail_reason'     => '',
            'upd_time'        => time(),
        ];

        $where = [
            ['business_id', '=', $data['business_id']],
            ['business_type', '=', $data['business_type']],
        ];

        // 待处理记录已存在则不再重复加入
        $pending = Db::name('OrderDeliverySyncWeixin')->where(array_merge($where, [['status', '=', 0]]))->find();
        if(!empty($pending))
        {
            return DataReturn(MyLang('handle_success'), 0);
        }

        // 失败记录则更新为待处理并覆盖参数
        $failed = Db::name('OrderDeliverySyncWeixin')->where(array_merge($where, [['status', '=', 2]]))->find();
        if(!empty($failed))
        {
            if(Db::name('OrderDeliverySyncWeixin')->where(['id'=>$failed['id']])->update($data))
            {
                return DataReturn(MyLang('handle_success'), 0);
            }
            return DataReturn(MyLang('handle_fail'), -1);
        }

        $data['add_time'] = time();
        if(Db::name('OrderDeliverySyncWeixin')->insertGetId($data) > 0)
        {
            return DataReturn(MyLang('handle_success'), 0);
        }
        return DataReturn(MyLang('handle_fail'), -1);
    }

    /**
     * 微信发货同步队列参数还原
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-05-26
     * @desc    description
     * @param   [array]          $data [队列记录]
     */
    public static function OrderDeliverySyncWeixinQueueParams($data)
    {
        $goods_title = $data['goods_title'];
        if(!empty($goods_title) && is_string($goods_title))
        {
            $temp = json_decode($goods_title, true);
            if(json_last_error() === JSON_ERROR_NONE && is_array($temp))
            {
                $goods_title = $temp;
            }
        }

        $params = [
            'business_id'     => $data['business_id'],
            'business_type'   => $data['business_type'],
            'order_model'     => $data['order_model'],
            'goods_title'     => $goods_title,
            'express_id'      => $data['express_id'],
            'express_number'  => $data['express_number'],
            'receiver_tel'    => $data['receiver_tel'],
        ];
        if(!empty($data['express_data']))
        {
            $temp = json_decode($data['express_data'], true);
            if(json_last_error() === JSON_ERROR_NONE)
            {
                $params['express_data'] = $temp;
            }
        }
        if(!empty($data['service_data']))
        {
            $temp = json_decode($data['service_data'], true);
            if(json_last_error() === JSON_ERROR_NONE)
            {
                $params['service_data'] = $temp;
            }
        }
        return $params;
    }
}
?>