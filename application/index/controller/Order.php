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

use app\service\OrderService;
use app\service\PaymentService;
use app\service\GoodsCommentsService;
use app\service\ConfigService;
use app\service\SeoService;

/**
 * 订单管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Order extends Common
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
        $where = OrderService::OrderListWhere($params);

        // 获取总数
        $total = OrderService::OrderTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  MyUrl('index/order/index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'                 => $page->GetPageStarNumber(),
            'n'                 => $number,
            'where'             => $where,
            'is_orderaftersale' => 1,
        );
        $data = OrderService::OrderList($data_params);
        $this->assign('data_list', $data['data']);

        // 支付方式
        $this->assign('payment_list', PaymentService::PaymentList());

        // 订单状态
        $this->assign('common_order_user_status', lang('common_order_user_status'));

        // 支付状态
        $this->assign('common_order_pay_status', lang('common_order_pay_status'));

        // 评价状态
        $this->assign('common_comments_status_list', lang('common_comments_status_list'));

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('我的订单', 1));

        // 参数
        $this->assign('params', $params);
        return $this->fetch();
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
        // 参数
        $params = input();
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

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
            // 发起支付 - 支付方式
            $this->assign('buy_payment_list', PaymentService::BuyPaymentList(['is_enable'=>1, 'is_open_user'=>1]));

            // 虚拟销售配置
            $site_fictitious = ConfigService::SiteFictitiousConfig();
            $this->assign('site_fictitious', $site_fictitious['data']);

            $this->assign('data', $data['data'][0]);

            // 加载百度地图api
            $this->assign('is_load_baidu_map_api', 1);

            // 参数
            $this->assign('params', $params);
            return $this->fetch();
        } else {
            $this->assign('msg', '没有相关数据');
            return $this->fetch('public/tips_error');
        }
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
        $params = input();
        $params['user'] = $this->user;
        $params['user_type'] = 'user';

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
            $this->assign('referer_url', empty($_SERVER['HTTP_REFERER']) ? MyUrl('index/order/index') : $_SERVER['HTTP_REFERER']);
            $this->assign('data', $data['data'][0]);

            // 编辑器文件存放地址
            $this->assign('editor_path_type', 'order_comments-'.$this->user['id'].'-'.$data['data'][0]['id']);
            return $this->fetch();
        } else {
            $this->assign('msg', '没有相关数据');
            return $this->fetch('public/tips_error');
        }
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user'] = $this->user;
            $params['business_type'] = 'order';
            return GoodsCommentsService::Comments($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
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
        $params = input();
        $params['user'] = $this->user;
        $ret = OrderService::Pay($params);
        if($ret['code'] == 0)
        {
            return redirect($ret['data']['data']);
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/tips_error');
        }
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
        $params = input();

        // 是否自定义状态
        if(isset($params['appoint_status']))
        {
            $ret = ($params['appoint_status'] == 0) ? DataReturn('支付成功', 0) : DataReturn('支付失败', -100);

            // 获取支付回调数据
        } else {
            $params['user'] = $this->user;
            $ret = OrderService::Respond($params);
        }

        // 自定义链接
        $this->assign('to_url', MyUrl('index/order/index'));
        $this->assign('to_title', '我的订单');

        // 状态
        $this->assign('msg', $ret['msg']);
        if($ret['code'] == 0)
        {
            return $this->fetch('public/tips_success');
        }
        return $this->fetch('public/tips_error');
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            return OrderService::OrderCancel($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            return OrderService::OrderCollect($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user_id'] = $this->user['id'];
            $params['creator'] = $this->user['id'];
            $params['creator_name'] = $this->user['user_name_view'];
            $params['user_type'] = 'user';
            return OrderService::OrderDelete($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
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
        if(input('post.'))
        {
            $params = input('post.');
            $params['user'] = $this->user;
            return OrderService::OrderPayCheck($params);
        } else {
            $this->assign('msg', '非法访问');
            return $this->fetch('public/tips_error');
        }
    }
}
?>