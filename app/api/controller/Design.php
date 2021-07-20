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
namespace app\api\controller;

use app\service\ApiService;
use app\service\DesignService;
use app\service\SystemBaseService;
use app\layout\service\BaseLayout;

/**
 * 页面设计
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-10
 * @desc    description
 */
class Design extends Common
{
    /**
     * 首页
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-09-10
     * @desc    description
     */
    public function Index()
    {
        $data = [];
        $layout_data = [];
        if(!empty($this->data_request['id']))
        {
            $data_params = [
                'where' => [
                    'id' => intval($this->data_request['id']),
                ],
                'm' => 0,
                'n' => 1,
            ];
            $ret = DesignService::DesignList($data_params);
            if($ret['code'] == 0 && !empty($ret['data']) && !empty($ret['data'][0]))
            {
                $data = $ret['data'][0];
            }
        }
        if(!empty($data))
        {
            // 访问统计
            DesignService::DesignAccessCountInc(['design_id'=>$data['id']]);

            // 配置处理
            $layout_data = BaseLayout::ConfigHandle($data['config']);

            // 去除布局配置数据、避免很多配置数据造成带宽浪费
            unset($data['config']);
        }

        // 返回数据
        $result = [
            'data'          => $data,
            'layout_data'   => $layout_data,
        ];
        return ApiService::ApiDataReturn(SystemBaseService::DataReturn($result));
    }
}
?>