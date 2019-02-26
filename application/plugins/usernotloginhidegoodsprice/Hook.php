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
namespace app\plugins\usernotloginhidegoodsprice;

use think\Controller;
use app\service\PluginsService;

/**
 * 未登录隐藏商品价格 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    // 应用响应入口
    public function run($params = [])
    {
        // 是否控制器钩子
        // is_backend 当前为后端业务处理
        // hook_name 钩子名称
        if(isset($params['is_backend']) && $params['is_backend'] === true && !empty($params['hook_name']))
        {
            return $this->PriceHandle($params);

        // 默认返回视图
        } else {
            return '';
        }
    }

    /**
     * 价格处理
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-14T22:19:08+0800
     * @param    [array]                   $params [参数]
     */
    private function PriceHandle($params)
    {
        // 获取应用数据
        $ret = PluginsService::PluginsData('usernotloginhidegoodsprice');
        if($ret['code'] == 0)
        {
            // 用户是否已登录
            if(session('user') != null)
            {
                return DataReturn('无需处理', 0);
            }

            // 限制终端
            if(!empty($ret['data']['limit_terminal']))
            {
                $limit_terminal_all = explode(',', $ret['data']['limit_terminal']);
                $client_type = APPLICATION_CLIENT_TYPE;
                if($client_type == 'pc' && IsMobile())
                {
                    $client_type = 'h5';
                }
                if(!in_array($client_type, $limit_terminal_all))
                {
                    return DataReturn('无需处理', 0);
                }
            }

            // 原价
            $original_price_placeholder = isset($ret['data']['original_price_placeholder']) ? $ret['data']['original_price_placeholder'] : '';

            // 销售价
            $price_placeholder = isset($ret['data']['price_placeholder']) ? $ret['data']['price_placeholder'] : '';

            // 商品原价
            if(isset($params['goods']['original_price']))
            {
                $params['goods']['original_price'] = $original_price_placeholder;
            }
            if(isset($params['goods']['min_original_price']))
            {
                $params['goods']['min_original_price'] = $original_price_placeholder;
            }
            if(isset($params['goods']['max_original_price']))
            {
                $params['goods']['max_original_price'] = $original_price_placeholder;
            }

            // 销售价
            if(isset($params['goods']['price']))
            {
                $params['goods']['price'] = $price_placeholder;
            }
            if(isset($params['goods']['min_price']))
            {
                $params['goods']['min_price'] = $price_placeholder;
            }
            if(isset($params['goods']['max_price']))
            {
                $params['goods']['max_price'] = $price_placeholder;
            }

            return DataReturn('处理成功', 0);
        } else {
            return $ret;
        }
    }
}
?>