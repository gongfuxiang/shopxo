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
namespace app\index\controller;

use app\service\ApiService;
use app\service\SystemBaseService;
use app\service\GoodsService;
use app\service\UserService;
use app\service\UserAddressService;
use app\service\PaymentService;
use app\service\BuyService;
use app\service\SeoService;

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
     * 订单确认页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        $key = 'buy_post_data_'.$this->user['id'];
        if($this->data_post)
        {
            MyCache($key, $this->data_post, 7200);
            return MyRedirect(MyUrl('index/buy/index'));
        } else {
            // 站点类型，是否开启了展示型
            if(SystemBaseService::SiteTypeValue() == 1)
            {
                MyViewAssign('msg', MyLang('buy.exhibition_not_allow_submit_tips'));
                return MyView('public/tips_error');
            }

            // 获取下单信息
            $buy_data = MyCache($key);
            if(empty($buy_data) || (empty($buy_data['goods_data']) && empty($buy_data['ids'])))
            {
                MyViewAssign('msg', MyLang('goods_data_empty_tips'));
                return MyView('public/tips_error');
            }

            // 参数
            $params = array_merge($this->data_request, $buy_data);
            $params['user'] = $this->user;

            // 默认支付方式
            $params['payment_id'] = PaymentService::BuyDefaultPayment($params);

            // 订单初始化
            $ret = BuyService::BuyOrderInit($params);
            if(isset($ret['code']) && $ret['code'] == 0)
            {
                // 订单是否已提交、则直接进入订单支付
                if(isset($ret['data']['is_order_submit']) && $ret['data']['is_order_submit'] == 1)
                {
                    return MyRedirect($ret['data']['jump_url']);
                }

                // 基础信息
                $buy_base = $ret['data']['base'];
                $buy_goods = $ret['data']['goods'];

                // 模板数据
                $assign = [
                    'base'                  => $buy_base,
                    'buy_goods'             => $buy_goods,
                    'buy_data'              => $buy_data,
                    // 浏览器名称
                    'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('buy.browser_seo_title'), 1),
                    // 公共销售模式
                    'common_site_type'      => $buy_base['common_site_type'],
                    // 支付方式
                    'payment_list'          => PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]),
                    // 下单类型模式
                    'buy_site_model_list'   => MyConst('common_buy_site_model_list'),
                ];

                // 用户地址
                $address = UserAddressService::UserAddressList(['user'=>$this->user]);
                $assign['user_address_list'] = $address['data'];

                // 地址选中处理
                // 防止选中id不存在地址列表中
                // 如果默认没有则表示不存在地址列表中
                if(isset($params['address_id']) && empty($buy_base['address']))
                {
                    unset($params['address_id']);
                }
                $assign['params'] = $params;

                // 数据赋值
                MyViewAssign($assign);
                // 钩子
                $this->PluginsHook($ret['data'], $params);
                return MyView();
            }
            MyViewAssign('msg', isset($ret['msg']) ? $ret['msg'] : MyLang('params_error_tips'));
            return MyView('public/tips_error');
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
        $hook_arr = [
            // 订单确认页面顶部钩子
            'plugins_view_buy_top',

            // 订单确认页面内部顶部钩子
            'plugins_view_buy_inside_top',

            // 订单确认页面地址底部钩子
            'plugins_view_buy_address_bottom',

            // 订单确认页面支付方式底部钩子
            'plugins_view_buy_payment_bottom',

            // 订单确认页面分组商品底部钩子
            'plugins_view_buy_group_goods_bottom',

            // 订单确认页面用户留言底部钩子
            'plugins_view_buy_user_note_bottom',

            // 订单确认页面订单确认信息顶部钩子
            'plugins_view_buy_base_confirm_top',

            // 订单确认页面提交订单表单内部钩子
            'plugins_view_buy_form_inside',

            // 订单确认页面内部底部钩子
            'plugins_view_buy_inside_bottom',

            // 订单确认页面底部钩子
            'plugins_view_buy_bottom',
        ];
        $assign = [];
        foreach($hook_arr as $hook_name)
        {
            $assign[$hook_name.'_data'] = MyEventTrigger($hook_name,
                [
                    'hook_name'     => $hook_name,
                    'is_backend'    => false,
                    'data'          => $data,
                    'params'        => $params,
                ]);
        }
        MyViewAssign($assign);
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
        $params = $this->data_request;
        if(!empty($params))
        {
            $params['user'] = $this->user;
            return ApiService::ApiDataReturn(BuyService::OrderInsert($params));
        }
        MyViewAssign('msg', MyLang('illegal_access_tips'));
        return MyView('public/tips_error');
    }
}
?>