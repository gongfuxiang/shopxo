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
namespace app\plugins\weixinwebauthorization\index;

use think\Controller;
use app\plugins\weixinwebauthorization\service\AuthService;

/**
 * 微信网页授权 - 授权处理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Auth extends Controller
{
    /**
     * 用户解绑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-26T00:55:08+0800
     * @param    array                    $params [description]
     */
    public function Unbind($params = [])
    {
        return AuthService::WeixinUnbind($params);
    }

    /**
     * 授权
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function Index($params = [])
    {
        $ret = AuthService::Auth($params);
        if($ret['code'] == 0)
        {
            return redirect($ret['data']);
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
        }
    }

    /**
     * 授权回调
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function Callback($params = [])
    {
        $ret = AuthService::Callback($params);
        if($ret['code'] == 0)
        {
            // 是否订单支付授权,进入订单详情
            $url = session('plugins_pay_order_detail_url');
            if(!empty($url))
            {
                session('plugins_pay_order_detail_url', null);
                return redirect($url);
            }

            // 默认页面提示
            $this->assign('msg', $ret['msg']);
            $this->assign('data', $ret['data']);
            return $this->fetch('public/login_success');
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
        }
    }
}
?>