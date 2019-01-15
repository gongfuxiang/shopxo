<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\service;

/**
 * 其它处理服务层
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OtherService
{
    /**
     * 环境校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-07
     * @desc    description
     */
    public static function EnvironmentCheck()
    {
        if(IS_AJAX)
        {
            // 请求参数数量校验是否超出php.ini限制
            $max_input_vars = intval(ini_get('max_input_vars'))-5;
            if(count(input('post.')) >= $max_input_vars)
            {
                return DataReturn('请求参数数量已超出php.ini限制[max_input_vars]', -1000);
            }
        }

        return DataReturn('success', 0);
    }
}
?>