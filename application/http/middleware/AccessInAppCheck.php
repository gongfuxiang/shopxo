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
namespace app\http\middleware;

/**
 * 访问环境检查
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AccessInAppCheck
{
    /**
     * 入口
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-16
     * @desc    description
     * @param   [object]        $request [请求对象]
     * @param   \Closure        $next    [闭包]
     * @return  [object]                 [请求对象]
     */
    public function handle($request, \Closure $next)
    {
        // 是否微信
        if(preg_match('~micromessenger~i', $request->header('user-agent')))
        {
            $request->in_app = 'weixin';

        // 是否支付宝
        } else if (preg_match('~alipay~i', $request->header('user-agent')))
        {
            $request->in_app = 'alipay';

        // 默认app
        } else {
            $request->in_app = 'app';
        }
        
        return $next($request);
    }
}
?>