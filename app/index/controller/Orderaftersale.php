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
class Orderaftersale extends Common
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
     * 列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 总数
        $total = OrderAftersaleService::OrderAftersaleTotal($this->form_where);

        // 分页
        $page_params = [
            'number'    =>  $this->page_size,
            'total'     =>  $total,
            'where'     =>  $this->data_request,
            'page'      =>  $this->page,
            'url'       =>  MyUrl('index/orderaftersale/index'),
        ];
        $page = new \base\Page($page_params);

        // 获取数据列表
        $data_params = [
            'where'         => $this->form_where,
            'm'             => $page->GetPageStarNumber(),
            'n'             => $this->page_size,
            'order_by'      => $this->form_order_by['data'],
            'is_public'     => 0,
        ];
        $ret = OrderAftersaleService::OrderAftersaleList($data_params);

        // 浏览器名称
        MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('订单售后', 1));

        // 基础参数赋值
        MyViewAssign('params', $this->data_request);
        MyViewAssign('page_html', $page->GetPageHtml());
        MyViewAssign('data_list', $ret['data']);
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
        // 参数
        $order_id = isset($this->data_request['oid']) ? intval($this->data_request['oid']) : 0;
        $order_detail_id = isset($this->data_request['did']) ? intval($this->data_request['did']) : 0;
        $ret = OrderAftersaleService::OrdferGoodsRow($order_id, $order_detail_id, $this->user['id']);
        if($ret['code'] == 0)
        {
            MyViewAssign('goods', $ret['data']['items']);
            MyViewAssign('order', $ret['data']);

            // 仅退款原因
            $return_only_money_reason = MyC('home_order_aftersale_return_only_money_reason');
            MyViewAssign('return_only_money_reason_list', empty($return_only_money_reason) ? [] : explode("\n", $return_only_money_reason));

            // 退款退货原因
            $return_money_goods_reason = MyC('home_order_aftersale_return_money_goods_reason');
            MyViewAssign('return_money_goods_reason_list', empty($return_money_goods_reason) ? [] : explode("\n", $return_money_goods_reason));

            // 获取当前订单商品售后最新的一条纪录
            $data_params = [
                'm'     => 0,
                'n'     => 1,
                'where' => [
                    ['order_detail_id', '=', $order_detail_id],
                    ['user_id', '=', $this->user['id']],
                ],
            ];
            $new_aftersale = OrderAftersaleService::OrderAftersaleList($data_params);
            if(!empty($new_aftersale['data'][0]))
            {
                $new_aftersale_data = $new_aftersale['data'][0];
                $new_aftersale_data['tips_msg'] = OrderAftersaleService::OrderAftersaleTipsMsg($new_aftersale_data);
            } else {
                $new_aftersale_data = [];
            }
            MyViewAssign('new_aftersale_data', $new_aftersale_data);

            // 进度
            MyViewAssign('step_data', OrderAftersaleService::OrderAftersaleStep($new_aftersale_data));

            // 可退款退货
            $returned = OrderAftersaleService::OrderAftersaleCalculation($order_id, $order_detail_id);
            MyViewAssign('returned_data', $returned['data']);

            // 退货地址
            $return_goods_address = OrderAftersaleService::OrderAftersaleReturnGoodsAddress($order_id);
            MyViewAssign('return_goods_address', $return_goods_address);

            // 静态数据
            MyViewAssign('common_order_aftersale_type_list', lang('common_order_aftersale_type_list'));

            // 编辑器文件存放地址
            MyViewAssign('editor_path_type', ResourcesService::EditorPathTypeValue(OrderAftersaleService::EditorAttachmentPathType($this->user['id'], $order_id, $order_detail_id)));

            // 浏览器名称
            MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle('订单售后详情', 1));

            // 订单售后搜索form key
            MyViewAssign('form_search_keywords_form_key', 'f0p');
            MyViewAssign('params', $this->data_request);
            return MyView();
        } else {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        }
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
            MyViewAssign('msg', '非法访问');
            return MyView('public/tips_error');
        }
        
        $params = $this->data_request;
        $params['user'] = $this->user;
        return OrderAftersaleService::AftersaleCreate($params);
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
            MyViewAssign('msg', '非法访问');
            return MyView('public/tips_error');
        }

        $params = $this->data_request;
        $params['user'] = $this->user;
        return OrderAftersaleService::AftersaleDelivery($params);
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
            MyViewAssign('msg', '非法访问');
            return MyView('public/tips_error');
        }

        $params = $this->data_post;
        $params['user'] = $this->user;
        return OrderAftersaleService::AftersaleCancel($params);
    }
}
?>