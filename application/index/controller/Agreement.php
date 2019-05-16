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

use app\service\ConfigService;
use app\service\SeoService;

/**
 * 协议
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Agreement extends Common
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
    }

    /**
     * 详情
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-05-16
     * @desc    description
     */
    public function Index()
    {
        // 参数
        $params = input();

        // 获取协议内容
        $data = [];
        if(!empty($params['document']))
        {
            $key = 'common_agreement_'.$params['document'];
            $ret = ConfigService::ConfigContentRow($key);

            // 浏览器标题
            if(!empty($ret['data']['name']))
            {
                $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle($ret['data']['name']));
            }
            $data = $ret['data'];
        }
        $this->assign('data', $data);
        return $this->fetch();
    }
}
?>