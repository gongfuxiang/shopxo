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
use app\service\OrderAftersaleService;
use app\service\SeoService;
use app\service\ResourcesService;

/**
 * 订单售后
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Orderaftersale extends Center
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
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle(MyLang('orderaftersale.base_nav_title'), 1));
        return MyView();
    }

    /**
     * 详情
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-21
     * @desc    description
     */
    public function Detail()
    {
        $params = $this->data_request;
        $params['user'] = $this->user;
        $ret = OrderAftersaleService::OrderAftersaleDetailData($params);
        if($ret['code'] != 0)
        {
            return MyView('public/tips_error', ['msg'=>$ret['msg']]);
        }
        MyViewAssign($ret['data']);
        return MyView();
    }

    /**
     * 申请售后创建
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     */
    public function Create()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            MyViewAssign('msg', MyLang('illegal_access_tips'));
            return MyView('public/tips_error');
        }
        
        $params = $this->data_request;
        $params['user'] = $this->user;
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleCreate($params));
    }

    /**
     * 用户退货
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-23
     * @desc    description
     */
    public function Delivery()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            MyViewAssign('msg', MyLang('illegal_access_tips'));
            return MyView('public/tips_error');
        }

        $params = $this->data_request;
        $params['user'] = $this->user;
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleDelivery($params));
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
        // 是否ajax请求
        if(!IS_AJAX)
        {
            MyViewAssign('msg', MyLang('illegal_access_tips'));
            return MyView('public/tips_error');
        }

        $params = $this->data_request;
        $params['user'] = $this->user;
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['username'];
        return ApiService::ApiDataReturn(OrderAftersaleService::AftersaleCancel($params));
    }
}
?>