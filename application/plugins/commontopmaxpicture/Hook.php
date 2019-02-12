<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2018 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\plugins\commontopmaxpicture;

use app\service\PluginsService;

/**
 * 顶部大图广告插件 - 钩子入口
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Hook
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
        // 是否控制器钩子
        if(isset($params['is_control']) && $params['is_control'] === true)
        {
            return [];

        // 默认返回视图
        } else {
            return $this->html($params);
        }
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
        $ret = PluginsService::PluginsData('commontopmaxpicture', ['images']);

        // html拼接
        $html = '<div style="text-align: center;';
        $content = '';
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

            // 非全局
            if($ret['data']['is_overall'] != 1)
            {
                // 非首页则空
                if($module_name.$controller_name.$action_name != 'indexindexindex')
                {
                    return '';
                }
            }

            // 背景色
            if(!empty($ret['data']['bg_color']))
            {
                $html .= 'background: '.$ret['data']['bg_color'].';';
            }
            $content .= '<a href="'.(empty($ret['data']['url']) ? 'javascript:;' : $ret['data']['url']).'" '.($ret['data']['is_new_window_open'] == 1 ? 'target="_blank"' : '').'>';
            $content .= '<img src="'.$ret['data']['images'].'" />';
            $content .= '</a>';
        } else {
            $content = $ret['msg'];
        }
        $html .= '">';
        $html .= $content;
        $html .= '</div>';

        return $html;
    }
}
?>