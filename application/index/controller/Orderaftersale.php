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
     * 订单列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 参数
        $params = input();
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

        // 分页
        $number = 10;

        // 条件
        $where = OrderAftersaleService::OrderAftersaleListWhere($params);

        // 获取总数
        $total = OrderAftersaleService::OrderAftersaleTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  MyUrl('index/orderaftersale/index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = OrderAftersaleService::OrderAftersaleList($data_params);
        $this->assign('data_list', $data['data']);

        // 静态数据
        $this->assign('common_order_aftersale_type_list', lang('common_order_aftersale_type_list'));
        $this->assign('common_order_aftersale_status_list', lang('common_order_aftersale_status_list'));
        $this->assign('common_order_aftersale_refundment_list', lang('common_order_aftersale_refundment_list'));

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('订单售后', 1));

        // 参数
        $this->assign('params', $params);
        return $this->fetch();
    }

    /**
     * 售后页面
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-21
     * @desc    description
     */
    public function Aftersale()
    {
        // 参数
        $params = input();
        $order_id = isset($params['oid']) ? intval($params['oid']) : 0;
        $order_detail_id = isset($params['did']) ? intval($params['did']) : 0;
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

            $this->assign('params', $params);
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
        
        $params = input();
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

        $params = input();
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

        $params = input('post.');
        $params['user'] = $this->user;
        return OrderAftersaleService::AftersaleCancel($params);
    }
}
?>