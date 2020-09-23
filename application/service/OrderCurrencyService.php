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
use app\service\ResourcesService;

/**
 * 订单货币服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-17
 * @desc    description
 */
class OrderCurrencyService
{
    /**
     * 订单货币添加
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-17
     * @desc    description
     * @param   [int]          $order_id    [订单id]
     * @param   [int]          $user_id     [用户id]
     */
    public static function OrderCurrencyInsert($order_id, $user_id)
    {
        $currency = ResourcesService::CurrencyData();
        $data = [
            'order_id'          => $order_id,
            'user_id'           => $user_id,
            'currency_name'     => $currency['currency_name'],
            'currency_code'     => $currency['currency_code'],
            'currency_symbol'   => $currency['currency_symbol'],
            'currency_rate'     => $currency['currency_rate'],
            'add_time'          => time(),
        ];
        if(Db::name('OrderCurrency')->insertGetId($data) > 0)
        {
            return DataReturn('订单货币新增成功', 0);
        }
        return DataReturn('订单货币新增失败', -1);
    }

    /**
     * 订单货币组列表、以订单id为索引
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-17
     * @desc    description
     * @param   [array|int]          $order_value [订单id或者编号]
     * @param   [string]             $order_key   [订单主键字段名称(order_id|order_no)(默认order_id)]
     * @return  [array]                         [货币数据、参数是多个id则返回二维数组，一个id则返回一维数组]
     */
    public static function OrderCurrencyGroupList($order_value, $order_key = 'order_id')
    {
        // 是否订单编号
        if($order_key == 'order_no')
        {
            // 数组读取多个id，则读取单个订单id
            if(is_array($order_value))
            {
                $order_value = Db::name('OrderCurrency')->where(['order_no'=>$order_value])->column('id');
            } else {
                $order_value = Db::name('OrderCurrency')->where(['order_no'=>$order_value])->value('id');
            }
        }

        // 数据处理
        $data = Db::name('OrderCurrency')->where(['order_id'=>$order_value])->select();
        $result = [];
        if(!empty($data) && is_array($order_value) && count($order_value) > 1)
        {
            foreach($data as $v)
            {
                $result[$v['order_id']] = $v;
            }
        } else {
            $result = isset($data[0]) ? $data[0] : [];
        }
        return $result;
    }
}
?>