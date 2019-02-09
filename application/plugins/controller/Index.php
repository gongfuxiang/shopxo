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
 * 插件入口控制器
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
        $control = strtolower($params['control']);
        $action = strtolower($params['action']);

        // 编辑器文件存放地址定义
        $this->assign('editor_path_type', 'plugins_'.$control);

        // 系统初始化
        $this->SystemInit();

        // 视图初始化
        $this->ViewInit($control, $action);

        // 调用插件
        return controller(ucfirst($control), $control)->$action(input('post.'));
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
     * @param    [string]                   $controller_name    [控制器名称]
     * @param    [string]                   $action_name        [方法]
     */
    public function ViewInit($controller_name, $action_name)
    {
        // 当前操作名称
        $module_name = strtolower(request()->module());

        // 当前操作名称
        $this->assign('module_name', $module_name);
        $this->assign('controller_name', $controller_name);
        $this->assign('action_name', $action_name);

        // 控制器静态文件状态css,js
        $module_css = $module_name.DS.'css'.DS.$controller_name;
        $module_css .= file_exists(ROOT_PATH.'static'.DS.$module_css.'.'.$action_name.'.css') ? '.'.$action_name.'.css' : '.css';
        $this->assign('module_css', file_exists(ROOT_PATH.'static'.DS.$module_css) ? $module_css : '');

        $module_js = $module_name.DS.'js'.DS.$controller_name;
        $module_js .= file_exists(ROOT_PATH.'static'.DS.$module_js.'.'.$action_name.'.js') ? '.'.$action_name.'.js' : '.js';
        $this->assign('module_js', file_exists(ROOT_PATH.'static'.DS.$module_js) ? $module_js : '');

        // 图片host地址
        $this->assign('attachment_host', config('shopxo.attachment_host'));
    }
}
?>