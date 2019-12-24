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

use app\service\SeoService;
use app\service\UserService;

/**
 * 用户地址管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserAddress extends Common
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
     * [Index 首页]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-02-22T16:50:32+0800
     */
    public function Index()
    {
        // 用户地址列表
        $data = UserService::UserAddressList(['user'=>$this->user]);
        $this->assign('user_address_list', $data['data']);

        // 浏览器名称
        $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle('我的地址', 1));

        return $this->fetch();
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
        
        if(input())
        {
            $params = input();
            $params['user'] = $this->user;
            $data = UserService::UserAddressRow($params);
            $this->assign('data', $data['data']);
        } else {
            $this->assign('data', []);
        }
        return $this->fetch();
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
        $params = input('post.');
        $params['user'] = $this->user;
        return UserService::UserAddressSave($params);
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
        return UserService::UserAddressDelete($params);
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
        return UserService::UserAddressDefault($params);
    }
}
?>