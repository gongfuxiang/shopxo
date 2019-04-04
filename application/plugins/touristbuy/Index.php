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
namespace app\plugins\touristbuy;

use think\Controller;
use app\plugins\touristbuy\Service;
use app\service\SeoService;
use app\service\OrderService;
use app\service\PluginsService;

/**
 * 游客购买 - 前端独立页面入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    /**
     * 订单查询入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        $ret = PluginsService::PluginsData('touristbuy');
        if($ret['code'] == 0)
        {
            $this->assign('data', $ret['data']);
            $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('订单查询', 1));
            return $this->fetch('../../../plugins/view/touristbuy/index/index');
        } else {
            return $ret['msg'];
        }
    }

    /**
     * 订单详情
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function detail($params = [])
    {
        // 请求参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '请输入订单号',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'name',
                'error_msg'         => '请输入收件人姓名',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'tel',
                'error_msg'         => '请输入收件人电话',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            $this->assign('msg', $ret);
            return $this->fetch('public/tips_error');
        }

        // 参数
        $params['user_type'] = 'user';

        // 条件
        $where = OrderService::OrderListWhere($params);
        $where[] = ['order_no', '=', $params['order_no']];
        $where[] = ['receive_name', '=', $params['name']];
        $where[] = ['receive_tel', '=', $params['tel']];

        // 获取列表
        $data_params = array(
            'm'         => 0,
            'n'         => 1,
            'where'     => $where,
        );
        $data = OrderService::OrderList($data_params);
        if(!empty($data['data'][0]))
        {
            $this->assign('data', $data['data'][0]);
            $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('订单详情', 1));

            // 参数
            $this->assign('params', $params);
            return $this->fetch('../../../plugins/view/touristbuy/index/detail');
        } else {
            $this->assign('msg', '没有相关数据');
            return $this->fetch('public/tips_error');
        }
    }

    /**
     * 游客登录
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T22:31:29+0800
     * @param   [array]          $params [输入参数]
     */
    public function login($params = [])
    {
        $ret = Service::TouristReg();
        if($ret['code'] == 0)
        {
            $this->assign('msg', $ret['msg']);
            $this->assign('data', $ret['data']);
            $this->assign('is_parent', isset($params['is_parent']) ? $params['is_parent'] : 0);
            return $this->fetch('../../../plugins/view/touristbuy/index/success');
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/error');
        }
    }
}
?>