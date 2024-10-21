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
use app\module\LayoutModule;

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
        if(!empty($this->data_request['id']))
        {
            $key = 'api_design_data_'.intval($this->data_request['id']).'_'.APPLICATION_CLIENT_TYPE;
            $result = MyCache($key);
            if(empty($result) || (isset($this->data_request['is_cache']) && $this->data_request['is_cache'] == 0))
            {
                // 数据容器
                $data = null;
                $layout_data = null;

                // 获取design数据
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

                    // 访问统计
                    DesignService::DesignAccessCountInc(['design_id'=>$data['id']]);

                    // 配置处理
                    $layout_data = LayoutModule::ConfigHandle($data['config']);

                    // 去除布局配置数据、避免很多配置数据造成带宽浪费
                    unset($data['config']);
                }

                // 返回数据
                $result = SystemBaseService::DataReturn([
                    'data'          => $data,
                    'layout_data'   => $layout_data,
                ]);

                // 缓存数据、没有用户登录信息则存储缓存
                if(empty($this->user))
                {
                    MyCache($key, $result, 3600);
                }
            } else {
                $result['data']['is_result_data_cache'] = 1;
            }

            // 访问统计
            if(!empty($result['data']) && !empty($result['data']['data']))
            {
                DesignService::DesignAccessCountInc(['design_id'=>$result['data']['data']['id']]);
            }
            return ApiService::ApiDataReturn($result);
        }
        return ApiService::ApiDataReturn(DataReturn(MyLang('no_data'), -1));
    }
}
?>