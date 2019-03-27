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
namespace app\plugins\commongobacktop;

use think\Controller;
use app\service\PluginsService;

/**
 * 回到顶部 - 钩子入口
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
                case 'plugins_view_common_bottom' :
                    $ret = $this->html($params);
                    break;

                case 'plugins_common_page_bottom' :
                    $ret = $this->js($params);
                    break;

                case 'plugins_common_header' :
                    $ret = $this->css($params);
                    break;

                default :
                    $ret = '';
            }
            return $ret;
        } else {
            return '';
        }
    }

    /**
     * css
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function css($params = [])
    {
        return '<style type="text/css">
                    #plugins-commongobacktop {
                        display:none;
                        position: fixed;
                        right: 50px;
                        bottom: 100px;
                        z-index: 100;
                        cursor: pointer;
                    }
                </style>';
    }

    /**
     * js
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function js($params = [])
    {
        return '<script type="text/javascript">
                    // 回顶部监测
                    $(window).scroll(function()
                    {
                      if($(window).scrollTop() > 100)
                      {
                        $("#plugins-commongobacktop").fadeIn(1000);
                      } else {
                        $("#plugins-commongobacktop").fadeOut(1000);
                      }
                    });
                </script>';
    }

    /**
     * 视图
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     * @param    [array]          $params [输入参数]
     */
    public function html($params = [])
    {
        // 当前模块/控制器/方法
        $module_name = strtolower(request()->module());
        $controller_name = strtolower(request()->controller());
        $action_name = strtolower(request()->action());

        // 获取应用数据
        $ret = PluginsService::PluginsData('commongobacktop', ['images']);
        if($ret['code'] == 0)
        {
            // 图片是否为空
            if(empty($ret['data']['images']))
            {
                return '';
            }
            
            // 非全局
            if($ret['data']['is_overall'] != 1)
            {
                // 非首页则空
                if($module_name.$controller_name.$action_name != 'indexindexindex')
                {
                    return '';
                }
            }

            $this->assign('data', $ret['data']);
            return $this->fetch('../../../plugins/view/commongobacktop/index/content');
        } else {
            return $ret['msg'];
        }
    }
}
?>