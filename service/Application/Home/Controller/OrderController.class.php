<?php

namespace Home\Controller;

use Service\OrderService;

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
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-25T15:30:36+0800
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
}
?>