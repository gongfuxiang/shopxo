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
use app\service\DiyService;
use app\service\SystemBaseService;

/**
 * DIY
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-09-10
 * @desc    description
 */
class Diy extends Common
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
            $key = 'api_diy_data_'.intval($this->data_request['id']).'_'.APPLICATION_CLIENT_TYPE;
            $result = MyCache($key);
            if(empty($result) || (isset($this->data_request['is_cache']) && $this->data_request['is_cache'] == 0))
            {
                // 获取diy数据
                $data = DiyService::DiyData($this->data_request);

                // 返回数据
                $result = SystemBaseService::DataReturn([
                    'data'  => $data
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
                DiyService::DiyAccessCountInc(['diy_id'=>$result['data']['data']['id']]);
            }
            return ApiService::ApiDataReturn($result);
        }
        return ApiService::ApiDataReturn(DataReturn(MyLang('no_data'), -1));
    }
}
?>