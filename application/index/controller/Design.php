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
use app\layout\service\BaseLayout;

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
     * 状态更新
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2021-03-31
     * @desc    description
     */
    public function Index()
    {
        $data = [];
        if(!empty($this->data_request['id']))
        {
            $data_params = [
                'where' => [
                    'id' => intval($this->data_request['id']),
                ],
                'm' => 0,
                'n' => 1,
            ];
            $ret = DesignService::DesignList($data_params);
            if($ret['code'] == 0 && !empty($ret['data']) && !empty($ret['data'][0]))
            {
                $data = $ret['data'][0];
            }
        }
        if(!empty($data))
        {
            // 访问统计
            DesignService::DesignAccessCountInc(['design_id'=>$data['id']]);

            // 配置处理
            $layout_data = BaseLayout::ConfigHandle($data['config']);
            $this->assign('layout_data', $layout_data);

            // 加载布局样式
            $this->assign('is_load_layout', 1);

            // seo
            $seo_title = empty($data['seo_title']) ? $data['name'] : $data['seo_title'];
            $this->assign('home_seo_site_title', SeoService::BrowserSeoTitle($seo_title, 2));
            if(!empty($data['seo_keywords']))
            {
                $this->assign('home_seo_site_keywords', $data['seo_keywords']);
            }
            if(!empty($data['seo_desc']))
            {
                $this->assign('home_seo_site_description', $data['seo_desc']);
            }

            // 头尾
            $this->assign('is_header', $data['is_header']);
            $this->assign('is_footer', $data['is_footer']);
            return $this->fetch();
        }
        $this->assign('msg', '页面不存在或已删除');
        return $this->fetch('public/tips_error');
    }
}
?>