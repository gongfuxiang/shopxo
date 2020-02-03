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
namespace app\index\controller;

use think\facade\Hook;
use app\service\GoodsService;
use app\service\UserService;
use app\service\PaymentService;
use app\service\BuyService;

/**
 * 购买
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Buy extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();

        // 是否登录
        $this->IsLogin();
    }
    
    /**
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        if(input('post.'))
        {
            session('buy_post_data', $_POST);
            return redirect(MyUrl('index/buy/index'));
        } else {
            // 站点类型，是否开启了展示型
            if(MyC('common_site_type', 0, true) == 1)
            {
                $this->assign('msg', '展示型不允许提交订单');
                return $this->fetch('public/tips_error');
            }

            // 获取下单信息
            $data = session('buy_post_data');
            if(empty($data))
            {
                $this->assign('msg', '商品信息为空');
                return $this->fetch('public/tips_error');
            }

            // 参数
            $params = array_merge(input(), $data);
            $params['user'] = $this->user;
            $ret = BuyService::BuyTypeGoodsList($params);

            // 商品校验
            if(isset($ret['code']) && $ret['code'] == 0)
            {
                // 用户地址
                $address = UserService::UserAddressList(['user'=>$this->user]);
                $this->assign('user_address_list', $address['data']);

                // 支付方式
                $this->assign('payment_list', PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]));

                // 地址选中处理
                // 防止选中id不存在地址列表中
                // 如果默认没有则表示不存在地址列表中
                if(isset($params['address_id']) && empty($ret['data']['base']['address']))
                {
                    unset($params['address_id']);
                }
 
                // 页面数据
                $this->assign('base', $ret['data']['base']);
                $this->assign('goods_list', $ret['data']['goods']);
                $this->assign('extension_data', $ret['data']['extension_data']);
                $this->assign('params', $params);

                // 加载百度地图api
                $this->assign('is_load_baidu_map_api', 1);

                // 钩子
                $this->PluginsHook($ret['data'], $params);

                return $this->fetch();
            } else {
                $this->assign('msg', isset($ret['msg']) ? $ret['msg'] : '参数错误');
                return $this->fetch('public/tips_error');
            }
        }
    }

    /**
     * 钩子处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-08-13
     * @desc    description
     * @param   [array]           $data     [确认数据]
     * @param   [array]           $params   [输入参数]
     */
    private function PluginsHook($data = [], $params = [])
    {
        // 订单确认页面顶部钩子
        $hook_name = 'plugins_view_buy_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面内部顶部钩子
        $hook_name = 'plugins_view_buy_inside_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面地址底部钩子
        $hook_name = 'plugins_view_buy_address_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面支付方式底部钩子
        $hook_name = 'plugins_view_buy_payment_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面商品底部钩子
        $hook_name = 'plugins_view_buy_goods_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面用户留言底部钩子
        $hook_name = 'plugins_view_buy_user_note_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面订单确认信息顶部钩子
        $hook_name = 'plugins_view_buy_base_confirm_top';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面提交订单表单内部钩子
        $hook_name = 'plugins_view_buy_form_inside';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面内部底部钩子
        $hook_name = 'plugins_view_buy_inside_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));

        // 订单确认页面底部钩子
        $hook_name = 'plugins_view_buy_bottom';
        $this->assign($hook_name.'_data', Hook::listen($hook_name,
            [
                'hook_name'     => $hook_name,
                'is_backend'    => false,
                'data'          => $data,
                'params'        => $params,
            ]));
    }

    /**
     * 订单添加
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-25
     * @desc    description
     */
    public function Add()
    {
        if(input('post.'))
        {
            $params = $_POST;
            $params['user'] = $this->user;
            return BuyService::OrderInsert($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
    }
}
?>