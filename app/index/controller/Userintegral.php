<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\index\controller;

use app\index\controller\Center;
use app\service\IntegralService;
use app\service\SeoService;

/**
 * 用户积分管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class UserIntegral extends Center
{
   /**
     * 构造方法
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-30
     * @desc    description
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();
    }

    /**
     * 用户积分列表
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-09-28
     * @desc    description
     */
    public function Index()
    {
        // 模板数据
        $assign = [
            // 用户积分
            'user_integral_data'    => IntegralService::UserIntegral($this->user['id']),

            // 浏览器名称
            'home_seo_site_title'   => SeoService::BrowserSeoTitle(MyLang('userintegral.base_nav_title'), 1),
        ];
        MyViewAssign($assign);
        return MyView();
    }

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-29
     * @desc    description
     */
    public function Detail()
    {
        $assign = [
            'data'      => $this->data_detail,
            'is_header' => 0,
            'is_footer' => 0,
        ];
        MyViewAssign($assign);
        return MyView();
    }
}
?>