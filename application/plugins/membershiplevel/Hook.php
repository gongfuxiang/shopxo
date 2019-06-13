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
namespace app\plugins\membershiplevel;

use think\Controller;
use app\plugins\membershiplevel\service\Service;

/**
 * 会员等级插件 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    /**
     * 应用响应入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function run($params = [])
    {
        // 后端访问不处理
        if(isset($params['params']['is_admin_access']) && $params['params']['is_admin_access'] == 1)
        {
            return DataReturn('无需处理', 0);
        }

        // 钩子名称
        if(!empty($params['hook_name']))
        {
            // 当前模块/控制器/方法
            $module_name = strtolower(request()->module());
            $controller_name = strtolower(request()->controller());
            $action_name = strtolower(request()->action());

            // 页面参数
            $input = input();

            $ret = '';
            switch($params['hook_name'])
            {
                case 'plugins_css' :
                    $ret = __MY_ROOT_PUBLIC__.'static/plugins/css/membershiplevel/index/style.css';
                    break;

                // 商品数据处理后
                case 'plugins_service_goods_handle_end' :
                    $this->GoodsHandleEnd($params['goods']);
                    break;

                // 商品规格基础数据
                case 'plugins_service_goods_spec_base' :
                    $this->GoodsSpecBase($params['spec_base']);
                    break;

                // 满减优惠
                case 'plugins_service_buy_handle' :
                    $ret = $this->FullReductionCalculate($params);
                    break;

                // 用户登录成功信息纪录钩子 icon处理
                case 'plugins_service_user_login_success_record' :
                    $ret = $this->UserLoginSuccessIconHandle($params);
                    break;
            }
            return $ret;
        } else {
            return '';
        }
    }

    /**
     * 用户icon处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-28
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    private function UserLoginSuccessIconHandle($params = [])
    {
        if(!empty($params['user']))
        {
            // 用户等级
            $level = Service::UserLevelMatching($params['user']);
            if(!empty($level) && $level['images_url'])
            {
                $params['user']['icon'] = $level['images_url'];
            }
        }
        return DataReturn('处理成功', 0);
    }

    /**
     * 满减计算
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-21
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    public function FullReductionCalculate($params = [])
    {
        // 用户等级
        $level = Service::UserLevelMatching();
        if(!empty($level) && $level['order_price'] > 0 && $level['full_reduction_price'] > 0 && $params['data']['base']['total_price'] >= $level['order_price'])
        {
            // 扩展展示数据
            $show_name = $level['name'].'-满减';
            $params['data']['extension_data'][] = [
                'name'      => $show_name,
                'price'     => $level['full_reduction_price'],
                'type'      => 1,
                'tips'      => '-￥'.$level['full_reduction_price'].'元',
            ];

            // 金额
            $params['data']['base']['preferential_price'] += $level['full_reduction_price'];
            $params['data']['base']['actual_price'] -= $level['full_reduction_price'];

            return DataReturn('处理成功', 0);
        }
        return DataReturn('无需处理', 0);
    }

    /**
     * 商品处理结束钩子
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-26
     * @desc    description
     * @param    [array]              &$goods [商品数据]
     */
    private function GoodsHandleEnd(&$goods = [])
    {
        // 用户等级
        $level = Service::UserLevelMatching();
        if(!empty($level) && $level['discount_rate'] > 0)
        {
            // 无价格字段则不处理
            if(isset($goods['price']))
            {
                if(empty($goods['original_price']))
                {
                    $goods['original_price'] = $goods['price'];
                }

                // 价格处理
                $goods['price'] = Service::PriceCalculate($goods['price'], $level['discount_rate'], 0);
                $price_title = empty($level['name']) ? '会员价' : $level['name'];
                $goods['show_field_price_text'] = '<span class="plugins-membershiplevel-goods-price-icon" title="'.$price_title.'">'.$price_title.'</span>';
            }

            // 最低价最高价
            if(isset($goods['min_price']))
            {
                $goods['min_price'] = Service::PriceCalculate($goods['min_price'], $level['discount_rate'], 0);
            }
            if(isset($goods['max_price']))
            {
                $goods['max_price'] = Service::PriceCalculate($goods['max_price'], $level['discount_rate'], 0);
            }
        }
    }

    /**
     * 商品规格基础数据
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-26
     * @desc    description
     * @param   [array]           &$spec_base [规格信息]
     */
    private function GoodsSpecBase(&$spec_base = [])
    {
        // 用户等级
        $level = Service::UserLevelMatching();
        if(!empty($level) && $level['discount_rate'] > 0 && isset($spec_base['price']))
        {
            if(empty($spec_base['original_price']))
            {
                $spec_base['original_price'] = $spec_base['price'];
            }
            $spec_base['price'] = Service::PriceCalculate($spec_base['price'], $level['discount_rate'], 0);
        }
    }
}
?>