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

use app\index\controller\Center;
use app\service\ApiService;
use app\service\OrderService;
use app\service\PaymentService;
use app\service\GoodsCommentsService;
use app\service\ConfigService;
use app\service\SeoService;
use app\service\ResourcesService;

/**
 * 订单管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Order extends Center
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
    }

    /**
     * 订单列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 支付参数
            'pay_params'            => OrderService::PayParamsHandle($this->data_request),
            // 支付方式
            'buy_payment_list'      => PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]),
            // 浏览器名称
            'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('order.base_nav_title'), 1),
        ];
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 订单详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-08
     * @desc    description
     */
    public function Detail()
    {
        // 订单信息
        if(empty($this->data_detail))
        {
            return MyView('public/tips_error', ['msg'=>MyLang('no_data')]);
        }

        // 模板数据
        $site_fictitious = ConfigService::SiteFictitiousConfig();
        $assign = [
            'data'                  => $this->data_detail,
            // 进度
            'step_data'             => OrderService::OrderStepData($this->data_detail),
            // 支付参数
            'pay_params'            => OrderService::PayParamsHandle($this->data_request),
            // 支付方式
            'buy_payment_list'      => PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]),
            // 虚拟销售配置
            'site_fictitious'       => $site_fictitious['data'],
            // 浏览器名称
            'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('order.detail_base_nav_title'), 1),
        ];
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 评价页面
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-08
     * @desc    description
     */
    public function Comments()
    {
        // 获取订单信息
        $data = $this->OrderFirst();
        if(empty($data))
        {
            return MyView('public/tips_error', ['msg'=>MyLang('no_data')]);
        }

        // 模板数据
        $assign = [
            'data'                  => $data,
            // 上一个页面url地址
            'referer_url'           => empty($_SERVER['HTTP_REFERER']) ? MyUrl('index/order/index') : htmlentities($_SERVER['HTTP_REFERER']),
            // 浏览器名称
            'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('order.comments_base_nav_title'), 1),
            // 编辑器文件存放地址
            'editor_path_type'      => ResourcesService::EditorPathTypeValue('order_comments-'.$this->user['id'].'-'.$data['id']),
        ];
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 获取一条订单信息
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-25
     * @desc    description
     */
    public function OrderFirst()
    {
        $data = [];
        if(!empty($this->data_request['id']))
        {
            // 条件
            $where = [
                ['is_delete_time', '=', 0],
                ['user_is_delete_time', '=', 0],
                ['id', '=', intval($this->data_request['id'])],
                ['user_id', '=', $this->user['id']],
            ];

            // 获取列表
            $data_params = [
                'm'                 => 0,
                'n'                 => 1,
                'where'             => $where,
                'is_orderaftersale' => 1,
                'is_operate'        => 1,
                'user_type'         => 'user',
            ];
            $ret = OrderService::OrderList($data_params);
            $data = (empty($ret['data']) || empty($ret['data'][0])) ? [] : $ret['data'][0];
        }
        return $data;
    }

    /**
     * 评价保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-10-09
     * @desc    description
     */
    public function CommentsSave()
    {
        if($this->data_request)
        {
            $params = $this->data_request;
            $params['user'] = $this->user;
            $params['business_type'] = 'order';
            return GoodsCommentsService::Comments($params);
        }
        return MyView('public/tips_error', ['msg'=>MyLang('illegal_access_tips')]);
    }

    /**
     * 订单支付
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Pay()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        $ret = OrderService::Pay($params);
        if($ret['code'] == 0)
        {
            // 是否直接成功、则直接进入提示页面并指定支付状态
            if(isset($ret['data']['is_success']) && $ret['data']['is_success'] == 1)
            {
                return MyRedirect(MyUrl('index/order/respond', ['appoint_status'=>0]));
            } else {
                return MyRedirect($ret['data']['data']);
            }
        }
        return MyView('public/tips_error', ['msg'=>$ret['msg']]);
    }

    /**
     * 支付同步返回处理
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Respond()
    {
        // 参数
        $params = $this->data_request;

        // 是否自定义状态
        if(isset($params['appoint_status']))
        {
            $ret = ($params['appoint_status'] == 0) ? DataReturn(MyLang('pay_success'), 0) : DataReturn(MyLang('pay_fail'), -100);
        } else {
            $params['user'] = $this->user;
            $ret = OrderService::Respond($params);
        }

        // 模板数据
        $assign = [
            // 自定义链接
            'to_url'    => MyUrl('index/order/index'),
            'to_title'  => MyLang('order.base_nav_title'),
            // 状态信息
            'msg'       => $ret['msg'],
        ];
        MyViewAssign($assign);

        // 根据不同状态展示成功和失败
        if($ret['code'] == 0)
        {
            return MyView('public/tips_success');
        }
        return MyView('public/tips_error');
    }

    /**
     * 订单取消
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Cancel()
    {
        if($this->data_request)
        {
            $params = $this->data_request;
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            return ApiService::ApiDataReturn(OrderService::OrderCancel($params));
        }
        return MyView('public/tips_error', ['msg'=>MyLang('illegal_access_tips')]);
    }

    /**
     * 订单收货
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Collect()
    {
        if($this->data_request)
        {
            $params = $this->data_request;
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            return ApiService::ApiDataReturn(OrderService::OrderCollect($params));
        }
        return MyView('public/tips_error', ['msg'=>MyLang('illegal_access_tips')]);
    }

    /**
     * 订单删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-30
     * @desc    description
     */
    public function Delete()
    {
        if($this->data_request)
        {
            $params = $this->data_request;
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $params['user_type'] = 'user';
            return ApiService::ApiDataReturn(OrderService::OrderDelete($params));
        }
        return MyView('public/tips_error', ['msg'=>MyLang('illegal_access_tips')]);
    }

    /**
     * 支付状态校验
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-01-08
     * @desc    description
     */
    public function PayCheck()
    {
        if($this->data_request)
        {
            $params = $this->data_request;
            $params['user'] = $this->user;
            return ApiService::ApiDataReturn(OrderService::OrderPayCheck($params));
        }
        return MyView('public/tips_error', ['msg'=>MyLang('illegal_access_tips')]);
    }
}
?>