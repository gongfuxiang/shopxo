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

use app\admin\controller\Base;
use app\service\ApiService;
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
class Appmini extends Base
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
		$assign = $this->CurrentViewInit();

		// 根据页面处理
		switch($this->view_type)
		{
			// 首页
			case 'index' :
				// 默认主题
				$assign['theme'] = AppMiniService::DefaultTheme();

				// 获取主题列表
				$data = AppMiniService::ThemeList($this->params);
				$assign['data_list'] = $data;

				// 插件更新信息
	            $upgrade = AppMiniService::AppMiniUpgradeInfo(['terminal'=>$this->params['application_name'], 'data'=>$data]);
	            $assign['upgrade_info'] = $upgrade['data'];
				break;

			// 源码包列表
			case 'package' :
				$assign = array_merge($assign, $this->Package());
				break;
		}

		// 模板赋值
		MyViewAssign($assign);
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
		// 源码包列表
		$ret = AppMiniService::DownloadDataList($this->params);
		return [
			'data_list'	=> $ret['data'],
		];
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
		return [
			// 基础导航
			'base_nav'                => MyLang('appmini.base_nav_list'),
			// 操作导航类型
			'nav_type'                => $this->params['application_name'],
			// 操作页面类型
			'view_type'               => $this->view_type,
			// 应用商店
			'store_theme_url'         => StoreService::StoreThemeUrl(),
			// 小程序平台
			'common_appmini_type'     => MyConst('common_appmini_type'),
			// 是否
			'common_is_text_list'     => MyConst('common_is_text_list'),
			// 是否开启
			'common_close_open_list'  => MyConst('common_close_open_list'),
		];
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
		return ApiService::ApiDataReturn(AppMiniService::ThemeUpload($this->params));
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
		return ApiService::ApiDataReturn(ConfigService::ConfigSave($params));
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
		$params = array_merge($this->params, $this->data_request);
		return ApiService::ApiDataReturn(AppMiniService::ThemeDelete($params));
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
		$assign = $this->CurrentViewInit();

		// 配置信息
		$assign['data'] = ConfigService::ConfigList();

		// 模板赋值
		MyViewAssign($assign);
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
		return ApiService::ApiDataReturn(AppMiniService::Created($this->params));
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
		return ApiService::ApiDataReturn(ConfigService::ConfigSave($_POST));
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
		return ApiService::ApiDataReturn(AppMiniService::Delete($this->params));
	}
}
?>