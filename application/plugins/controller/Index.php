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
namespace app\plugins\controller;

use think\Controller;

/**
 * 应用入口控制器
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Index extends Controller
{
    /**
     * 入口
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function Index()
    {
        // 登录校验
        $this->IsLogin();

        // 参数
        $params = input();

        // 请求参数校验
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginsname',
                'error_msg'         => '应用名称有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginscontrol',
                'error_msg'         => '应用控制器有误',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pluginsaction',
                'error_msg'         => '应用操作方法有误',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn($ret, -5000)));
            } else {
                $this->assign('msg', $ret);
                return $this->fetch('public/error');
            }
        }

        // 应用名称/控制器/方法
        $pluginsname = $params['pluginsname'];
        $pluginscontrol = strtolower($params['pluginscontrol']);
        $pluginsaction = strtolower($params['pluginsaction']);

        // 编辑器文件存放地址定义
        $this->assign('editor_path_type', 'plugins_'.$pluginsname);

        // 系统初始化
        $this->SystemInit();

        // 视图初始化
        $this->ViewInit($pluginsname, $pluginscontrol, $pluginsaction);

        // 调用应用
        return controller(ucfirst($pluginscontrol), $pluginsname)->$pluginsaction($params);
    }

    /**
     * 登录校验
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:42:35+0800
     */
    protected function IsLogin()
    {
        if(session('admin') === null)
        {
            if(IS_AJAX)
            {
                exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
            } else {
                die('<script type="text/javascript">if(self.frameElement && self.frameElement.tagName == "IFRAME"){parent.location.reload();}else{window.location.href="'.MyUrl('admin/admin/logininfo').'";}</script>');
            }
        }
    }

    /**
     * 系统初始化
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-12-07
     * @desc    description
     */
    private function SystemInit()
    {
        // url模式,后端采用兼容模式
        \think\facade\Url::root(__MY_ROOT_PUBLIC__.'index.php?s=');
    }

    /**
     * 视图初始化
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-02-07T22:46:29+0800
     * @param    [string]                   $plugins_name       [应用名称]
     * @param    [string]                   $plugins_control    [控制器名称]
     * @param    [string]                   $plugins_action     [方法]
     */
    public function ViewInit($plugins_name, $plugins_control, $plugins_action)
    {
        // 当前操作名称
        $module_name = strtolower(request()->module());

        // 当前操作名称
        $this->assign('plugins_name', $plugins_name);
        $this->assign('module_name', $module_name);
        $this->assign('controller_name', $plugins_control);
        $this->assign('action_name', $plugins_action);

        // 控制器静态文件状态css,js
        $module_css = $module_name.DS.'css'.DS.$plugins_name.DS.$plugins_control;
        $module_css .= file_exists(ROOT_PATH.'static'.DS.$module_css.'.'.$plugins_action.'.css') ? '.'.$plugins_action.'.css' : '.css';
        $this->assign('module_css', file_exists(ROOT_PATH.'static'.DS.$module_css) ? $module_css : '');

        $module_js = $module_name.DS.'js'.DS.$plugins_name.DS.$plugins_control;
        $module_js .= file_exists(ROOT_PATH.'static'.DS.$module_js.'.'.$plugins_action.'.js') ? '.'.$plugins_action.'.js' : '.js';
        $this->assign('module_js', file_exists(ROOT_PATH.'static'.DS.$module_js) ? $module_js : '');

        // 图片host地址
        $this->assign('attachment_host', config('shopxo.attachment_host'));
    }
}
?>