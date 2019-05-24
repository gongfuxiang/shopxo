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

/**
 * 微信登录 - 支付
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Pay extends Controller
{
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
        $pay_data = session('weixin_pay_data');
        $weixin_redirect_url = session('weixin_redirect_url');
        if(!empty($pay_data))
        {
            $this->assign('pay_data', $pay_data);
            $this->assign('redirect_url', $weixin_redirect_url);
            return $this->fetch('../../../plugins/view/weixinwebauthorization/index/pay/index');
        } else {
            $this->assign('msg', '支付参数错误');
            return $this->fetch('public/tips_error');
        }
    }


}
?>