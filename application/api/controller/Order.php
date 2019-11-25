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
namespace app\api\controller;

use app\service\PaymentService;
use app\service\OrderService;
use app\service\GoodsCommentsService;
use app\service\ConfigService;

/**
 * 我的订单
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Order extends Common
{
    /**
     * [__construct 构造方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 是否登录
        $this->IsLogin();
    }
    
    /**
     * [Index 获取订单列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 参数
        $params = $this->data_post;
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

        // 分页
        $number = 10;
        $page = max(1, isset($this->data_post['page']) ? intval($this->data_post['page']) : 1);

        // 条件
        $where = OrderService::OrderListWhere($params);

        // 获取总数
        $total = OrderService::OrderTotal($where);
        $page_total = ceil($total/$number);
        $start = intval(($page-1)*$number);

        // 获取列表
        $data_params = array(
            'm'                 => $start,
            'n'                 => $number,
            'where'             => $where,
            'is_orderaftersale' => 1,
        );
        $data = OrderService::OrderList($data_params);

        // 支付方式
        $payment_list = PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]);

        // 返回数据
        $result = [
            'total'             =>  $total,
            'page_total'        =>  $page_total,
            'data'              =>  $data['data'],
            'payment_list'      =>  $payment_list,
        ];
        return DataReturn('success', 0, $result);
    }

    /**
     * [Detail 获取详情]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T10:18:27+0800
     */
    public function Detail()
    {
        // 参数
        $params = $this->data_post;
        $params['user'] = $this->user;
        $params['user_type'] = 'user';
        if(empty($params['id']))
        {
            return DataReturn('参数有误', -1);
        }

        // 条件
        $where = OrderService::OrderListWhere($params);

        // 获取列表
        $data_params = array(
            'm'         => 0,
            'n'         => 1,
            'where'     => $where,
        );
        $data = OrderService::OrderList($data_params);
        if(!empty($data['data'][0]))
        {
            // 返回信息
            $result = [
                'data'              => $data['data'][0],
                'site_fictitious'   => null,
            ];

            // 虚拟销售配置
            if($result['data']['order_model'] == 3 && $result['data']['pay_status'] == 1 && in_array($result['data']['status'], [3,4]))
            {
                $site_fictitious = ConfigService::SiteFictitiousConfig();
                $result['site_fictitious'] = $site_fictitious['data'];
            }
            return DataReturn('success', 0, $result);
        }
        return DataReturn('数据不存在或已删除', -100);
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
        // 参数
        $params = $this->data_post;
        $params['user'] = $this->user;
        $params['user_type'] = 'user';
        if(empty($params['id']))
        {
            return DataReturn('参数有误', -1);
        }

        // 条件
        $where = OrderService::OrderListWhere($params);

        // 获取列表
        $data_params = array(
            'm'         => 0,
            'n'         => 1,
            'where'     => $where,
        );
        $data = OrderService::OrderList($data_params);
        if(!empty($data['data'][0]))
        {
            // 是否已评论
            if($data['data'][0]['user_is_comments'] > 0)
            {
                return DataReturn('你已进行过评论', -100);
            }

            // 返回数据
            $result = [
                'data'                  => $data['data'][0],
                'editor_path_type'      => 'order_comments-'.$this->user['id'].'-'.$data['data'][0]['id'],
            ];
            return DataReturn('success', 0, $result);
        }
        return DataReturn('没有相关数据', -100);
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
        $params = $this->data_post;
        $params['user'] = $this->user;
        $params['business_type'] = 'order';
        return GoodsCommentsService::Comments($params);
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
        $params = $this->data_post;
        $params['user'] = $this->user;
        return OrderService::Pay($params);
    }


    /**
     * [Cancel 订单取消]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T10:48:48+0800
     */
    public function Cancel()
    {
        $params = $this->data_post;
        $params['user_id'] = $this->user['id'];
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['user_name_view'];
        return OrderService::OrderCancel($params);
    }

    /**
     * [Collect 订单收货]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-21T10:48:48+0800
     */
    public function Collect()
    {
        $params = $this->data_post;
        $params['user_id'] = $this->user['id'];
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['user_name_view'];
        return OrderService::OrderCollect($params);
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
        $params = $this->data_post;
        $params['user_id'] = $this->user['id'];
        $params['creator'] = $this->user['id'];
        $params['creator_name'] = $this->user['user_name_view'];
        $params['user_type'] = 'user';
        return OrderService::OrderDelete($params);
    }

}
?>