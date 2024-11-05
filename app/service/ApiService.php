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
     * token生成
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-02-26
     * @desc    description
     * @param   [int]          $data_id [数据id]
     * @param   [int|string]   $rand    [随机数]
     */
    public static function CreatedUserToken($data_id, $rand = '')
    {
        // 生成规则
        $data = [];
        $rules = MyC('common_token_created_rules');
        if(!empty($rules) && is_array($rules))
        {
            // 用户ip
            if(in_array(0, $rules))
            {
                $data[] = GetClientIP();
            }
            // 设备信息
            if(in_array(1, $rules))
            {
                $arr = ['USER', 'HTTP_USER_AGENT', 'HTTP_HOST', 'SERVER_SOFTWARE', 'GATEWAY_INTERFACE', 'REQUEST_SCHEME', 'SERVER_PROTOCOL'];
                foreach($arr as $v)
                {
                    if(isset($_SERVER[$v]))
                    {
                        $data[] = $_SERVER[$v];
                    }
                }
            }
            // 客户端
            if(in_array(2, $rules))
            {
                $data[] = APPLICATION_CLIENT_TYPE;
            }
            // 系统类型
            if(in_array(3, $rules))
            {
                $data[] = SYSTEM_TYPE;
            }
            // 随机数或密码盐
            if(in_array(4, $rules))
            {
                if(empty($rand))
                {
                    $rand = time().GetNumberCode();
                }
                $data[] = $rand;
            }
        }
        return md5(md5((empty($data) ? '' : md5(implode('', $data))).md5($data_id)));
    }
}
?>