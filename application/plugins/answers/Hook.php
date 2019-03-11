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
namespace app\plugins\answers;

use think\Controller;
use app\service\PluginsService;

/**
 * 问答 - 钩子入口
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
            // 参数一   描述
            // 参数二   0 为处理成功, 负数为失败
            // 参数三   返回数据
            return DataReturn('返回描述', 0);

        // 默认返回视图
        } else {
            // 大导航前面添加问答地址
            if(!empty($params['hook_name']) && $params['hook_name'] == 'plugins_service_navigation_header_handle')
            {
                if(is_array($params['header']))
                {
                    // 获取应用数据
                    $ret = PluginsService::PluginsData('answers', ['images']);
                    if($ret['code'] == 0 && !empty($ret['data']['application_name']))
                    {
                        $nav = [
                            'id'                    => 0,
                            'pid'                   => 0,
                            'name'                  => $ret['data']['application_name'],
                            'url'                   => PluginsHomeUrl('answers', 'index', 'index'),
                            'data_type'             => 'custom',
                            'is_show'               => 1,
                            'is_new_window_open'    => 0,
                            'items'                 => [],
                        ];
                        array_unshift($params['header'], $nav);
                    }
                }
            }
        }
        return DataReturn('无需处理', 0);
    }
}
?>