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
namespace app\plugins\footercustomerservice;

use think\Controller;
use app\plugins\footercustomerservice\Service;
use app\service\PluginsService;

/**
 * 底部客户服务介绍插件 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook extends Controller
{
    /**
     * 应用响应入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-09T14:25:44+0800
     * @param    [array]                    $params [输入参数]
     */
    public function run($params = [])
    {
        if(!empty($params['hook_name']))
        {
            switch($params['hook_name'])
            {
                // style css
                case 'plugins_common_header' :
                    $ret = $this->StyleCss($params);
                    break;

                // 底部导航上面钩子
                case 'plugins_view_common_footer_top' :
                    $ret = $this->FooterServerData($params);
                    break;
                default :
                    $ret = '';
            }
            return $ret;
        }
    }

    /**
     * 客户服务数据
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function FooterServerData($params = [])
    {
        $ret = $this->IsNormal($params);
        if($ret['code'] == 0)
        {
            $this->assign('data_list', $ret['data']);
            return $this->fetch('../../../plugins/view/footercustomerservice/index/content');
        }
        return '';
    }

    /**
     * css
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function StyleCss($params = [])
    {
        $ret = $this->IsNormal($params);
        if($ret['code'] == 0)
        {
            return '<style type="text/css">
                    .plugins-footercustomerservice-customer-service {
                        background: #f8f8f8;
                        padding: 10px 0;
                        margin-top: 20px;
                    }
                    .plugins-footercustomerservice-customer-service .am-gallery-overlay .am-gallery-item img {
                        width: 50px;
                        float: left;
                    }
                    .plugins-footercustomerservice-customer-service .am-gallery-overlay .am-gallery-item .base {
                        margin-left: 60px;
                    }
                    .plugins-footercustomerservice-customer-service .am-gallery-overlay .am-gallery-item .base .title {
                        font-weight: 500;
                        font-size: 16px;
                        color: #505050;
                    }
                    .plugins-footercustomerservice-customer-service .am-gallery-overlay .am-gallery-item .base .desc {
                        color: #999;
                        margin-top: 5px;
                    }
                    @media only screen and (max-width:1025px) {
                        .plugins-footercustomerservice-customer-service .am-gallery-overlay .am-gallery-item {
                            border-bottom: 1px solid #f0f0f0;
                            padding-bottom: 10px;
                        }
                    }
                    .am-footer-default {
                        margin-top: 0;
                    }
                </style>';
        }
        return '';
    }

    /**
     * 是否正常
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-04-23
     * @desc    description
     * @param    [array]          $params [输入参数]
     */
    private function IsNormal($params = [])
    {
        // 当前模块/控制器/方法
        $module_name = strtolower(request()->module());
        $controller_name = strtolower(request()->controller());
        $action_name = strtolower(request()->action());

        // 获取应用数据
        $ret = PluginsService::PluginsData('footercustomerservice');
        if($ret['code'] == 0)
        {
            // 是否仅首页
            if(isset($ret['data']['is_only_home']) && $ret['data']['is_only_home'] == 1)
            {
                // 非首页则空
                if($module_name.$controller_name.$action_name != 'indexindexindex')
                {
                    return DataReturn('仅首页展示', -1);
                }
            }
        }

        // 获取图片列表
        $ret = Service::DataList();
        if($ret['code'] == 0 && !empty($ret['data']))
        {
            return DataReturn('成功', 0, $ret['data']);
        }

        return DataReturn('失败', -100);
    }
}
?>