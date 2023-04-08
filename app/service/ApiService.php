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

/**
 * api服务层
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2021-06-22
 * @desc    description
 */
class ApiService
{
    /**
     * api数据统一返回
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-07-20
     * @desc    description
     * @param   [array]          $data [api统一返回]
     */
    public static function ApiDataReturn($data)
    {
        // api统一返回钩子
        $hook_name = 'plugins_service_api_data_return';
        MyEventTrigger($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => true,
                'data'          => &$data,
            ]);

        return json($data);
    }

    /**
     * 用户token生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-26
     * @desc    description
     * @param   [int]          $user_id [用户id]
     */
    public static function CreatedUserToken($user_id)
    {
        $arr = ['USER', 'HTTP_USER_AGENT', 'HTTP_HOST', 'SERVER_SOFTWARE', 'GATEWAY_INTERFACE', 'REQUEST_SCHEME', 'SERVER_PROTOCOL'];
        $data = [GetClientIP(), APPLICATION_CLIENT_TYPE, SYSTEM_TYPE];
        foreach($arr as $v)
        {
            if(isset($_SERVER[$v]))
            {
                $data[] = $_SERVER[$v];
            }
        }
        sort($data);
        return md5(md5(md5(implode('', $data)).md5($user_id).time().GetNumberCode()));
    }
}
?>