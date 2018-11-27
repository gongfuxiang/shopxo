<?php

namespace Api\Controller;

use Service\BuyService;

/**
 * 购物车
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CartController extends CommonController
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

        // 是否ajax请求
        if(!IS_AJAX)
        {
            $this->error(L('common_unauthorized_access'));
        }

        // 是否登录
        $this->Is_Login();
    }
    
    /**
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        $ret = BuyService::CartList(['user'=>$this->user]);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * 购物车保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-13
     * @desc    description
     */
    public function Save()
    {
        $params = $_POST;
        $params['user'] = $this->user;
        $ret = BuyService::CartAdd($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * 购物车删除
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     */
    public function Delete()
    {
        $params = $this->data_post;
        $params['user'] = $this->user;
        $ret = BuyService::CartDelete($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * 数量保存
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-14
     * @desc    description
     */
    public function Stock()
    {
        $params = $this->data_post;
        $params['user'] = $this->user;
        $ret = BuyService::CartStock($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>