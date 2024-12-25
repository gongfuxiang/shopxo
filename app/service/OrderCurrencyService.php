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
     * @param   [array]        $params      [输入参数]
     */
    public static function OrderCurrencyInsert($order_id, $user_id, $params = [])
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
            return DataReturn(MyLang('insert_success'), 0);
        }
        return DataReturn(MyLang('common_service.ordercurrency.order_currency_insert_fail_tips'), -1);
    }

    /**
     * 订单货币组列表、以订单id为索引
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-17
     * @desc    description
     * @param   [array|int]          $order_ids [订单id]
     * @return  [array]                         [货币数据、参数是多个id则返回二维数组，一个id则返回一维数组]
     */
    public static function OrderCurrencyGroupList($order_ids)
    {
        $data = Db::name('OrderCurrency')->where(['order_id'=>$order_ids])->select()->toArray();
        $result = [];
        if(!empty($data) && is_array($order_ids))
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