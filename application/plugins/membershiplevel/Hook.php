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
namespace app\plugins\membershiplevel;

use think\Controller;
use app\plugins\membershiplevel\Service;
use app\service\PluginsService;

/**
 * 会员等级插件 - 钩子入口
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

                // 楼层数据上面
                case 'plugins_view_home_floor_top' :
                    $ret = $this->HomeFloorTopAdv($params);
                    break;
                default :
                    $ret = '';
            }
            return $ret;
        }
    }

    /**
     * 首页楼层顶部广告
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function HomeFloorTopAdv($params = [])
    {
        // 获取应用数据
        $ret = PluginsService::PluginsData('membershiplevel');
        if($ret['code'] == 0)
        {
            // 有效时间
            if(!empty($ret['data']['time_start']))
            {
                // 是否已开始
                if(strtotime($ret['data']['time_start']) > time())
                {
                    return '';
                }
            }
            if(!empty($ret['data']['time_end']))
            {
                // 是否已结束
                if(strtotime($ret['data']['time_end']) < time())
                {
                    return '';
                }
            }
        }

        // 获取图片列表
        $ret = Service::DataList();
        if($ret['code'] == 0 && !empty($ret['data']))
        {
            $this->assign('data_list', $ret['data']);
            return $this->fetch('../../../plugins/view/membershiplevel/index/content');
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
        return '<style type="text/css">
                    @media only screen and (min-width:640px) {
                        .plugins-membershiplevel-home-adv ul.am-gallery img {
                            -webkit-transition: transform .2s ease-in;
                            -moz-transition: transform .2s ease-in;
                            -ms-transition: transform .2s ease-in;
                            -o-transition: transform .2s ease-in;
                            transition: transform .2s ease-in;
                        }
                        .plugins-membershiplevel-home-adv ul.am-gallery img:hover {
                            -ms-transform: translate3d(0px, -3px, 0px);
                            -webkit-transform: translate3d(0px, -3px, 0px);
                            -o-transform: translate3d(0px, -3px, 0px);
                            transform: translate3d(0px, -3px, 0px);
                        }
                    }
                    @media only screen and (min-width:1025px) {
                        .plugins-membershiplevel-home-adv {
                            overflow: hidden;
                        }
                        .plugins-membershiplevel-home-adv ul.am-gallery {
                            width: calc(100% + 20px);
                            margin-left: -10px;
                            margin-top: 10px;
                        }
                    }
                </style>';
    }
}
?>