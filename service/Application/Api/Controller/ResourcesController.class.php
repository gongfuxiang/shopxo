<?php

namespace Api\Controller;

use Service\ResourcesService;
use Service\BannerService;

/**
 * 资源
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class ResourcesController extends CommonController
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
    }

    /**
     * [Express 获取快递]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-08T15:08:01+0800
     */
    public function Express()
    {
        $this->ajaxReturn(L('common_operation_success'), 0, ResourcesService::ExpressList());
    }

    /**
     * [HomeBanner 首页轮播]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-25T11:03:59+0800
     */
    public function HomeBanner()
    {
        // 返回数据
        $this->ajaxReturn(L('common_operation_success'), 0, BannerService::App());
    }

    /**
     * [HomeNav 首页导航]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-05-25T11:03:59+0800
     */
    public function HomeNav()
    {
        // 返回数据
        $this->ajaxReturn(L('common_operation_success'), 0, ResourcesService::AppHomeNav());
    }

}
?>