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

use app\service\BuyService;

/**
 * 购物车
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Cart extends Common
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
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        $ret = BuyService::CartList(['user'=>$this->user]);
        $ret['data'] = [
            'data'                              => $ret['data'],
            'customer_service_tel'              => MyC('common_app_customer_service_tel', null, true),
            'common_is_exhibition_mode_btn_text'=> MyC('common_is_exhibition_mode_btn_text', '立即咨询', true),
            'common_site_type'                  => (int) MyC('common_site_type', 0, true),
            'common_cart_total'                 => BuyService::UserCartTotal(['user'=>$this->user]),
        ];
        
        return $ret;
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
        $params = $this->data_post;
        $params['user'] = $this->user;
        return BuyService::CartSave($params);
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
        return BuyService::CartDelete($params);
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
        return BuyService::CartStock($params);
    }
}
?>