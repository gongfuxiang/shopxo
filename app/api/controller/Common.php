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
namespace app\api\controller;

use app\BaseController;
use app\module\FormTableHandleModule;
use app\service\ApiService;
use app\service\SystemService;
use app\service\UserService;
use app\service\ConfigService;

/**
 * 接口公共控制器
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Common extends BaseController
{
	// 用户信息
	protected $user;

    // 输入参数 post|get|request
    protected $data_post;
    protected $data_get;
    protected $data_request;

    // 当前系统操作名称
    protected $module_name;
    protected $controller_name;
    protected $action_name;

    // 当前插件操作名称
    protected $plugins_module_name;
    protected $plugins_controller_name;
    protected $plugins_action_name;

    // 分页信息
    protected $page;
    protected $page_size;
    protected $page_start;

    // 动态表格
    protected $form_table;
    protected $form_where;
    protected $form_params;
    protected $form_md5_key;
    protected $form_user_fields;
    protected $form_order_by;
    protected $form_error;

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
        // 检测是否是新安装
        SystemService::SystemInstallCheck();

        // 系统初始化
        $this->SystemInit();

        // 系统运行开始
        SystemService::SystemBegin($this->data_request);

        // 网站状态
        $this->SiteStstusCheck();

        // 动态表格初始化
        $this->FormTableInit();

		// 公共数据初始化
		$this->CommonInit();
    }

    /**
     * 析构函数
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-18
     * @desc    description
     */
    public function __destruct()
    {
        // 系统运行结束
        SystemService::SystemEnd($this->data_request);
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
        // 输入参数
        $this->data_post = input('post.');
        $this->data_get = input('get.');
        $this->data_request = input();

        // 配置信息初始化
        ConfigService::ConfigInit();
    }

    /**
     * 网站状态
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2018-04-18T16:20:58+0800
     */
    private function SiteStstusCheck()
    {
        $data = MyC('home_site_app_state', [], true);
        if(!empty($data) && is_array($data) && in_array(APPLICATION_CLIENT_TYPE, $data))
        {
            exit(json_encode(DataReturn(MyC('home_site_close_reason', MyLang('upgrading_tips')), -10000)));
        }
    }

	/**
	 * 登录校验
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T11:43:48+0800
	 */
	protected function IsLogin()
	{
		if(empty($this->user))
		{
			exit(json_encode(DataReturn(MyLang('login_failure_tips'), -400)));
		}
    }

    /**
     * 动态表格初始化
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-06-02
     * @desc    description
     */
    public function FormTableInit()
    {
        // 获取表格模型
        $module = FormModulePath($this->data_request);
        if(!empty($module))
        {
            // 调用表格处理
            $params = $this->data_request;
            $ret = (new FormTableHandleModule())->Run($module['module'], $module['action'], $params);
            if($ret['code'] == 0)
            {
                $this->form_table = $ret['data']['table'];
                $this->form_where = $ret['data']['where'];
                $this->form_params = $ret['data']['params'];
                $this->form_md5_key = $ret['data']['md5_key'];
                $this->form_user_fields = $ret['data']['user_fields'];
                $this->form_order_by = $ret['data']['order_by'];
            } else {
                $this->form_error = $ret['msg'];
            }
        }
    }

	/**
	 * 公共数据初始化
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-03-09T11:43:48+0800
	 */
	private function CommonInit()
	{
		// 用户数据
		$this->user = UserService::LoginUserInfo();

        // 当前系统操作名称
        $this->module_name = RequestModule();
        $this->controller_name = RequestController();
        $this->action_name = RequestAction();

        // 当前插件操作名称, 兼容插件模块名称
        if(empty($this->data_request['pluginsname']))
        {
            $this->plugins_module_name = '';
            $this->plugins_controller_name = '';
            $this->plugins_action_name = '';
        } else {
            $this->plugins_module_name = $this->data_request['pluginsname'];
            $this->plugins_controller_name = empty($this->data_request['pluginscontrol']) ? 'index' : $this->data_request['pluginscontrol'];
            $this->plugins_action_name = empty($this->data_request['pluginsaction']) ? 'index' : $this->data_request['pluginsaction'];
        }

        // 分页信息
        $this->page = max(1, isset($this->data_request['page']) ? intval($this->data_request['page']) : 1);
        $this->page_size = min(empty($this->data_request['page_size']) ? MyC('common_page_size', 15, true) : intval($this->data_request['page_size']), 1000);
        $this->page_start = intval(($this->page-1)*$this->page_size);
	}

	/**
     * 空方法响应
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2018-11-30
     * @desc    description
     * @param   [string]         $method [方法名称]
     * @param   [array]          $args   [参数]
     */
    public function __call($method, $args)
    {
        return ApiService::ApiDataReturn(DataReturn(MyLang('illegal_access_tips').'('.$method.')', -1000));
    }
}
?>