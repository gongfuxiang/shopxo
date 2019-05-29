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
 * 微信登录 - 微信里面支付
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Pay extends Controller
{
    /**
     * 支付授权
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-05-25T14:44:32+0800
     * @param    [array]      $params [输入参数]
     */
    public function Index($params = [])
    {
        // 订单url处理
        if(!empty($params['id']))
        {
            $url = MyUrl('index/order/detail', ['id'=>intval($params['id']), 'is_pay_auto'=>1, 'is_pay_submit'=>1]);
            session('plugins_pay_order_detail_url', $url);
        }

        // 调用授权
        $ret = AuthService::Auth($params);
        if($ret['code'] == 0)
        {
            return redirect($ret['data']);
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
        }
    }
}
?>