<?php

namespace Home\Controller;

use Service\BuyService;
use Service\UserService;

/**
 * 用户地址管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserAddressController extends CommonController
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
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        $cart_list = BuyService::CartList(['user'=>$this->user]);
        $this->assign('cart_list', $cart_list['data']);

        $base = [
            'total_price'   => empty($cart_list['data']) ? 0 : array_sum(array_column($cart_list['data'], 'total_price')),
            'total_stock'   => empty($cart_list['data']) ? 0 : array_sum(array_column($cart_list['data'], 'stock')),
            'ids'           => empty($cart_list['data']) ? '' : implode(',', array_column($cart_list['data'], 'id')),
        ];
        $this->assign('base', $base);
        $this->display('Index');
    }

    /**
     * [SaveInfo 地址添加/编辑页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        $this->assign('is_header', 0);
        $this->assign('is_footer', 0);
        
        // 文章信息
        if(empty($_REQUEST['id']))
        {
            $data = array();
        } else {
            $params = $_REQUEST;
            $params['user'] = $this->user;
            $data = UserService::UserAddressRow($params);
        }
        $this->assign('data', $data['data']);
        $this->display('SaveInfo');
    }

    /**
     * [Save 用户地址保存]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-09-23T22:36:18+0800
     */
    public function Save()
    {
        $params = $_POST;
        $params['user'] = $this->user;
        $ret = UserService::UserAddressSave($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * 删除地址
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function Delete()
    {
        $params = $_POST;
        $params['user'] = $this->user;
        $ret = UserService::UserAddressDelete($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }

    /**
     * 默认地址设置
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-07-18
     * @desc    description
     */
    public function SetDefault()
    {
        $params = $_POST;
        $params['user'] = $this->user;
        $ret = UserService::UserAddressDefault($params);
        $this->ajaxReturn($ret['msg'], $ret['code'], $ret['data']);
    }
}
?>