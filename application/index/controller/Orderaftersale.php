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

use app\service\OrderAftersaleService;
use app\service\SeoService;

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
            'is_public'     => 0,
        ];
        $ret = OrderAftersaleService::OrderAftersaleList($data_params);

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('订单售后', 1));

        // 基础参数赋值
        $this->assign('params', $this->data_request);
        $this->assign('page_html', $page->GetPageHtml());
        $this->assign('data_list', $ret['data']);
        return $this->fetch();
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
            $this->assign('goods', $ret['data']['items']);
            $this->assign('order', $ret['data']);

            // 仅退款原因
            $return_only_money_reason = MyC('home_order_aftersale_return_only_money_reason');
            $this->assign('return_only_money_reason_list', empty($return_only_money_reason) ? [] : explode("\n", $return_only_money_reason));

            // 退款退货原因
            $return_money_goods_reason = MyC('home_order_aftersale_return_money_goods_reason');
            $this->assign('return_money_goods_reason_list', empty($return_money_goods_reason) ? [] : explode("\n", $return_money_goods_reason));

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
            $this->assign('new_aftersale_data', $new_aftersale_data);

            // 进度
            $this->assign('step_data', OrderAftersaleService::OrderAftersaleStep($new_aftersale_data));

            // 可退款退货
            $returned = OrderAftersaleService::OrderAftersaleCalculation($order_id, $order_detail_id);
            $this->assign('returned_data', $returned['data']);

            // 静态数据
            $this->assign('common_order_aftersale_type_list', lang('common_order_aftersale_type_list'));

            // 编辑器文件存放地址
            $this->assign('editor_path_type', 'order_aftersale-'.$this->user['id'].'-'.$order_id.'-'.$order_detail_id);

            $this->assign('form_search_keywords_name', 'fp0');
            $this->assign('params', $this->data_request);
            return $this->fetch();
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
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
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
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
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
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
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }

        $params = $this->data_post;
        $params['user'] = $this->user;
        return OrderAftersaleService::AftersaleCancel($params);
    }
}
?>