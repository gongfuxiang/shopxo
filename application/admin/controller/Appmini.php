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
namespace app\admin\controller;

use app\service\AppMiniService;
use app\service\ConfigService;

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
	private $application_name;
	private $old_path;
	private $new_path;
	private $params;

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
		$this->assign('nav_type', $this->params['application_name']);
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
		$host = 'https://shopxo.net/';
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
		$this->assign('nav_dev_tips', $nav_dev_tips);

		// 小程序平台
		$this->assign('common_appmini_type', lang('common_appmini_type'));

		// 源码包列表
		$ret = AppMiniService::DataList($this->params);
		$this->assign('data_list', $ret['data']);
		return $this->fetch();
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
		// 是否
		$this->assign('common_is_text_list', lang('common_is_text_list'));

		// 小程序平台
		$this->assign('common_appmini_type', lang('common_appmini_type'));

		// 配置信息
		$this->assign('data', ConfigService::ConfigList());
		return $this->fetch();
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

		// 配置内容
        $app_mini_title = MyC('common_app_mini_weixin_title');
        $app_mini_describe = MyC('common_app_mini_weixin_describe');
        if(empty($app_mini_title) || empty($app_mini_describe))
        {
            return DataReturn('配置信息不能为空', -1);
        }

		// 开始操作
		$this->params['app_mini_title'] = $app_mini_title;
		$this->params['app_mini_describe'] = $app_mini_describe;
		return AppMiniService::Created($this->params);
	}

	/**
	 * 保存
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