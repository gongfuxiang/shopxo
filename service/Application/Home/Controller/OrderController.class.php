<?php

namespace Home\Controller;

use Service\OrderService;
use Service\ResourcesService;

/**
 * 订单管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class OrderController extends CommonController
{
    /**
     * [_initialize 前置操作-继承公共前置方法]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function _initialize()
    {
        // 调用父类前置方法
        parent::_initialize();

        // 是否登录
        $this->Is_Login();
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
        $this->display('Index');
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
        $params = $_REQUEST;
        $params['user'] = $this->user;
        $ret = OrderService::Pay($params);
        if($ret['code'] == 0)
        {
            redirect($ret['data']);
        } else {
            $this->assign('msg', $ret['msg']);
            $this->display('/Public/TipsError');
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
        $params = $_REQUEST;
        $params['user'] = $this->user;
        $ret = OrderService::Respond($params);
        if($ret['code'] == 0)
        {
            $this->assign('msg', '支付成功');
            $this->display('/Public/PaySuccess');
        } else {
            $this->assign('msg', $ret['msg']);
            $this->display('/Public/PayError');
        }
    }
}
?>