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
use app\plugins\membershiplevel\Service;

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
                    $ret = __MY_ROOT_PUBLIC__.'static/plugins/css/membershiplevel/style.css';
                    break;

                // 商品数据处理后
                case 'plugins_service_goods_handle_end' :
                    if(!empty($params['goods']['id']) && !empty($input['id']) && $params['goods']['id'] == $input['id'] && $module_name.$controller_name.$action_name == 'indexgoodsindex')
                    {
                        $this->GoodsHandleEnd($params['goods']);
                    }
                    break;

                // 商品规格基础数据
                case 'plugins_service_goods_spec_base' :
                    $this->GoodsSpecBase($params['spec_base']);
                    break;
            }
            return $ret;
        } else {
            return '';
        }
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
            if(empty($goods['original_price']))
            {
                $goods['original_price'] = $goods['price'];
            }

            // 价格处理
            $goods['price'] = Service::PriceCalculate($goods['price'], $level['discount_rate'], 0);
            $price_title = empty($level['name']) ? '会员价' : $level['name'];
            $goods['show_field_price_text'] = '<span class="plugins-membershiplevel-goods-price-icon">'.$price_title.'</span>';
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