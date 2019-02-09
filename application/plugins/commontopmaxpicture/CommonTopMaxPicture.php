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

use think\Controller;
use app\service\PluginsService;

/**
 * 顶部广告插件
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CommonTopMaxPicture extends Controller
{
    public function run($params = [])
    {
        // 是否控制器
        if(isset($params['is_control']) && $params['is_control'] === true)
        {
            return [];

        // 默认返回视图
        } else {
            return $this->html();
        }
    }

    /**
     * 视图
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     */
    public function html()
    {
        $ret = PluginsService::PluginsData('commontopmaxpicture');

        $html = '<div style="text-align: center;';
        $content = '';
        if($ret['code'] == 0)
        {
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

    /**
     * 配置信息
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-06T16:16:34+0800
     */
    public function config()
    {
        // 基础信息
        $base = [
            'author'        => 'Devil',
            'blog'          => 'http://gong.gg',
            'name'          => '顶部广告',
            'version'       => '1.0.0',
            'sales_amount'  => 0,
        ];

        // 后台配置
        $admin = [

        ];

        // 控制器钩子
        $control_hook = [
            'plugins_control_hook'  =>  [
                'plugins_common_top'     => ['app\\plugins\\commontopmaxpicture\\CommonTopMaxPicture'],
            ],
        ];

        return [
            'base'          => $base,
            'admin'         => $admin,
            'control_hook'  => $control_hook,
        ];
    }

    /**
     * 首页
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        $ret = PluginsService::PluginsData('commontopmaxpicture');
        if($ret['code'] == 0)
        {
            $this->assign('data', $ret['data']);
            return $this->fetch('commontopmaxpicture/index');
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/error');
        }
    }

    /**
     * 编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        $ret = PluginsService::PluginsData('commontopmaxpicture');
        if($ret['code'] == 0)
        {
            $this->assign('data', $ret['data']);
            return $this->fetch('commontopmaxpicture/saveinfo');
        } else {
            $this->assign('msg', $ret['msg']);
            return $this->fetch('public/error');
        }
    }

    /**
     * 保存
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T08:21:54+0800
     * @param    [array]          $params [输入参数]
     */
    public function save($params = [])
    {
        unset($params['max_file_size']);
        return PluginsService::PluginsDataSave(['plugins'=>'commontopmaxpicture', 'data'=>$params]);
    }

}
?>