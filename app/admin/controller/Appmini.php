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
namespace app\admin\controller;

use app\service\AppMiniService;
use app\service\ConfigService;
use app\service\StoreService;

/**
 * 小程序管理
 * @author  Devil
 * @blog    http://gong.gg/
 * @version 1.0.0
 * @date    2020-07-13
 * @desc    description
 */
class Appmini extends Common
{
	private $params;
	private $application_name;
	private $old_path;
	private $new_path;
	private $view_type;

	/**
	 * 构造方法
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-07-13
	 * @desc    description
	 */
	public function __construct()
	{
		// 调用父类前置方法
		parent::__construct();

		// 登录校验
		$this->IsLogin();

		// 权限校验
		$this->IsPower();

		// 参数
		$this->params = $this->data_request;
		$this->params['application_name'] = empty($this->data_request['nav_type']) ? 'weixin' : trim($this->data_request['nav_type']);

		// 小导航
		$this->view_type = input('view_type', 'index');
	}

	/**
     * 列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     */
	public function Index()
	{
        // 公共视图
		$this->CurrentViewInit();

		switch($this->view_type)
		{
			// 首页
			case 'index' :
				// 默认主题
				MyViewAssign('theme', AppMiniService::DefaultTheme());

				// 获取主题列表
				$data = AppMiniService::ThemeList($this->params);
				MyViewAssign('data_list', $data);

				// 插件更新信息
	            $upgrade = AppMiniService::AppMiniUpgradeInfo(['terminal'=>$this->params['application_name'], 'data'=>$data]);
	            MyViewAssign('upgrade_info', $upgrade['data']);
				break;

			// 源码包列表
			case 'package' :
				$this->Package();
				break;
		}
		return MyView($this->view_type);
	}

    /**
     * 小程序包列表页面
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     */
	public function Package()
	{
		$host = MyConfig('shopxo.website_url');
		$nav_dev_tips = [
			// 微信
			'weixin'	=> [
				'msg' => '右上角 -> 详情 -> 不校验合法域名、web-view（业务域名）、TLS 版本以及 HTTPS 证书（勾选改选项即可进行小程序开发调试）。',
				'url' => $host.'weixin.html',
			],
			// 支付宝
			'alipay'	=> [
				'msg' => '右上角 -> 详情 -> 域名信息下 -> 忽略 httpRequest 域名合法性检查（仅限调试时，且支付宝 10.1.35 版本以上）（勾选改选项即可进行小程序开发调试）。',
				'url' => $host.'alipay.html',
			],
			// 百度
			'baidu'	=> [
				'msg' => '顶部导航 -> 校验域名（关闭即可）。',
				'url' => $host.'baidu.html',
			],
			// 头条
			'toutiao'	=> [
				'msg' => '顶部导航 -> 详情 -> 不校验合法域名、web-view（业务域名）TLS版本以及HTTPS证书（勾选改选项即可进行小程序开发调试）。',
				'url' => $host.'zijietiaodong.html',
			],
			// QQ
			'qq'	=> [
				'msg' => '顶部导航 -> 详情 -> 不校验合法域名、web-view（业务域名）TLS版本以及HTTPS证书（勾选改选项即可进行小程序开发调试）。',
				'url' => $host.'qq.html',
			],
		];
		MyViewAssign('nav_dev_tips', $nav_dev_tips);

		// 源码包列表
		$ret = AppMiniService::DownloadDataList($this->params);
		MyViewAssign('data_list', $ret['data']);
	}

	/**
	 * 公共视图
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-11-21
	 * @desc    description
	 */
	public function CurrentViewInit()
	{
		// 操作导航类型
		MyViewAssign('nav_type', $this->params['application_name']);

		// 操作页面类型
		MyViewAssign('view_type', $this->view_type);

		// 应用商店
        MyViewAssign('store_theme_url', StoreService::StoreThemeUrl());

		// 小程序平台
		MyViewAssign('common_appmini_type', MyConst('common_appmini_type'));

		// 是否
		MyViewAssign('common_is_text_list', MyConst('common_is_text_list'));

		// 基础导航
		$base_nav = [
			[
				'view_type'	=> 'index',
				'name'		=> '当前主题',
			],
			[
				'view_type'	=> 'upload',
				'name'		=> '主题安装',
			],
			[
				'view_type'	=> 'package',
				'name'		=> '源码包下载',
			],
		];
		MyViewAssign('base_nav', $base_nav);
	}

	/**
	 * 主题上传安装
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-11-21
	 * @desc    description
	 */
	public function ThemeUpload()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始处理
		return AppMiniService::ThemeUpload($this->params);
	}

	/**
	 * 主题切换保存
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-12-19T00:58:47+0800
	 */
	public function ThemeSave()
	{
		$key = AppMiniService::DefaultThemeKey($this->params);
		$params[$key] = empty($this->data_request['theme']) ? 'default' : $this->data_request['theme'];
		$ret = ConfigService::ConfigSave($params);
		if($ret['code'] == 0)
		{
			$ret['msg'] = '切换成功';
		}
		return $ret;
	}

	/**
	 * 主题打包下载
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  1.0.0
	 * @datetime 2018-12-19T00:58:47+0800
	 */
	public function ThemeDownload()
	{
		$params = array_merge($this->params, $this->data_request);
        $ret = AppMiniService::ThemeDownload($params);
        if(isset($ret['code']) && $ret['code'] != 0)
        {
            MyViewAssign('msg', $ret['msg']);
            return MyView('public/tips_error');
        } else {
            return $ret;
        }
	}

	/**
	 * 主题删除
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-11-21
	 * @desc    description
	 */
	public function ThemeDelete()
	{
		// 是否ajax
		if(!IS_AJAX)
		{
			return $this->error('非法访问');
		}

		// 开始处理
		$params = array_merge($this->params, $this->data_request);
		return AppMiniService::ThemeDelete($params);
	}

	/**
     * 配置
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-07-13
     * @desc    description
     */
	public function Config()
	{
		// 公共视图
		$this->CurrentViewInit();

		// 配置信息
		MyViewAssign('data', ConfigService::ConfigList());
		return MyView();
	}

	/**
	 * 生成
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-07-13
	 * @desc    description
	 */
	public function Created()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 开始操作
		return AppMiniService::Created($this->params);
	}

	/**
	 * 配置保存
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-07-13
	 * @desc    description
	 */
	public function Save()
	{
		return ConfigService::ConfigSave($_POST);
	}

	/**
	 * 删除
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2020-07-13
	 * @desc    description
	 */
	public function Delete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error('非法访问');
		}

		// 开始操作
		return AppMiniService::Delete($this->params);
	}
}
?>