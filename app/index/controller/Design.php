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

use app\service\SeoService;
use app\service\DesignService;
use app\module\LayoutModule;

/**
 * 页面设计
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Design extends Common
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
     * @date    2021-03-31
     * @desc    description
     */
    public function Index()
    {
        if(!empty($this->data_request['id']))
        {
            $data_params = [
                'where' => [
                    ['id', '=', intval($this->data_request['id'])],
                    ['is_enable', '=', 1],
                ],
                'm' => 0,
                'n' => 1,
            ];
            $ret = DesignService::DesignList($data_params);
            if($ret['code'] == 0 && !empty($ret['data']) && !empty($ret['data'][0]))
            {
                $data = $ret['data'][0];

                // 访问统计
                DesignService::DesignAccessCountInc(['design_id'=>$data['id']]);

                // 模板数据
                $assign = [
                    'data'              => $data,
                    // 配置处理
                    'layout_data'       => LayoutModule::ConfigHandle($data['config']),
                    // 加载布局样式
                    'is_load_layout'    => 1,
                    // 头尾
                    'is_header'         => $data['is_header'],
                    'is_footer'         => $data['is_footer'],
                ];

                // seo
                $seo_title = empty($data['seo_title']) ? $data['name'] : $data['seo_title'];
                $assign['home_seo_site_title'] = SeoService::BrowserSeoTitle($seo_title, 2);
                if(!empty($data['seo_keywords']))
                {
                    $assign['home_seo_site_keywords'] = $data['seo_keywords'];
                }
                if(!empty($data['seo_desc']))
                {
                    $assign['home_seo_site_description'] = $data['seo_desc'];
                }

                // 数据赋值
                MyViewAssign($assign);
                return MyView();
            }
        }
        MyViewAssign('msg', MyLang('design.design_no_data_tips'));
        return MyView('public/tips_error');
    }
}
?>