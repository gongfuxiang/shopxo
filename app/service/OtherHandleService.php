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
        if(MyC('common_app_mini_weixin_upload_shipping_status', 0, true) == 1 && isset($params['order_model']) && isset($params['business_id']) && isset($params['business_type']) && !empty($params['goods_title']))
        {
            // 获取支付日志
            $pay_log_ids = Db::name('PayLogValue')->where(['business_id'=>$params['business_id']])->column('pay_log_id');
            if(!empty($pay_log_ids))
            {
                // 仅获取【微信和微信扫码】支付方式的订单日志
                $where = [
                    ['id', 'in', $pay_log_ids],
                    ['business_type', '=', $params['business_type']],
                    ['payment', 'in', ['Weixin', 'WeixinScanQrcode']],
                    ['status', '=', 1],
                ];
                $pay_log = Db::name('PayLog')->field('trade_no,buyer_user')->where($where)->find();
                if(!empty($pay_log)) 
                {
                    $trade_no = $pay_log['trade_no'];
                    $buyer_user = $pay_log['buyer_user'];

                    // 发货快递信息
                    $express_id = isset($params['express_id']) ? intval($params['express_id']) : 0;
                    $express_number = isset($params['express_number']) ? intval($params['express_number']) : '';
                    $receiver_tel = isset($params['receiver_tel']) ? intval($params['receiver_tel']) : '';

                    // 调用微信发货同步
                    return (new \base\Wechat(AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appid'), AppMiniUserService::AppMiniConfig('common_app_mini_weixin_appsecret')))->MiniUploadShippingInfo([
                        'order_model'     => $params['order_model'],
                        'trade_no'        => $pay_log['trade_no'],
                        'buyer_user'      => $pay_log['buyer_user'],
                        'goods_title'     => $params['goods_title'],
                        'express_name'    => ExpressService::ExpressName($express_id),
                        'express_number'  => $express_number,
                        'receiver_tel'    => $receiver_tel,
                        'consignor_tel'   => MyC('common_customer_store_tel'),
                    ]);
                }
            }
        }
        return DataReturn(MyLang('handle_noneed'), 0);
    }
}
?>