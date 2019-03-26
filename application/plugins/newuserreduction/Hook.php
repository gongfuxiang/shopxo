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
namespace app\plugins\newuserreduction;

use think\Db;
use app\service\PluginsService;

/**
 * 新用户立减 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook
{
    /**
     * 应用响应入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]          $params [输入参数]
     */
    public function run($params = [])
    {
        if(!empty($params['hook_name']))
        {
            switch($params['hook_name'])
            {
                // 立减计算
                case 'plugins_service_buy_handle' :
                    $ret = $this->ReductionCalculate($params);
                    break;

                default :
                    $ret = '';
            }
            return $ret;
        }
    }

    /**
     * 立减计算
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-21
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public function ReductionCalculate($params = [])
    {
        $ret = PluginsService::PluginsData('newuserreduction');
        if($ret['code'] == 0)
        {
            // 是否设置需满金额
            if(isset($ret['data']['full_amount']) && $ret['data']['full_amount'] > 0 && $params['data']['base']['total_price'] < $ret['data']['full_amount'])
            {
                return DataReturn('无需处理', 0);
            }

            // 默认金额
            $price = isset($ret['data']['price']) ? (float) $ret['data']['price'] : 0;
            $unit = '元';
            $price_show = $price;
            if($price > 0 && $this->IsNewUser($params))
            {
                // 是否随机
                if(isset($ret['data']['is_random']) && $ret['data']['is_random'] == 1)
                {
                    // 随机金额需要提交步骤生效
                    if(!isset($params['params']['is_order_submit']) || $params['params']['is_order_submit'] != 1)
                    {
                        $price = 0;
                        $price_show = '随机减';
                        $unit = '';
                    } else {
                        $price = $this->RandomFloat(0, $price);
                        $price_show = $price;
                    }
                }

                // 扩展展示数据
                $show_name = empty($ret['data']['show_name']) ? '新用户立减' : $ret['data']['show_name'];
                $params['data']['extension_data'][] = [
                    'name'      => $show_name,
                    'price'     => $price,
                    'type'      => 0,
                    'tips'      => '-￥'.$price_show.$unit,
                ];

                // 金额
                $params['data']['base']['increase_price'] -= $price;
                $params['data']['base']['actual_price'] -= $price;
            }
            return DataReturn('无需处理', 0);
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 是否满足新用户条件
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-25
     * @desc    description
     * @param   [array]           $params [输入参数]
     */
    private function IsNewUser($params = [])
    {
        // 用户信息是否存在
        if(empty($params['params']['user']))
        {
            return false;
        }

        // 获取用户订单
        // 订单状态（0待确认, 1已确认/待支付, 2已支付/待发货, 3已发货/待收货, 4已完成, 5已取消, 6已关闭）
        $where = [
            ['user_id', '=', intval($params['params']['user']['id'])],
            ['status', '<=', 4],
        ];
        $temp = Db::name('Order')->where($where)->count();
        return empty($temp);
    }

    /**
     * 生成随机金额
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-25
     * @desc    description
     * @param   [int]         $min [最小值]
     * @param   [int]         $max [最大值]
     */
    private function RandomFloat($min = 0, $max = 10)
    {
        return sprintf("%.2f", $min+mt_rand()/mt_getrandmax()*($max-$min));
    }
}
?>