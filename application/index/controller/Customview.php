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
namespace app\index\controller;

use app\service\CustomViewService;

/**
 * 自定义页面
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CustomView extends Common
{
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
        parent::__construct();
    }

	/**
     * [Index 文章详情]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 获取页面
		$id = input('id');
		$params = [
			'where' => ['is_enable'=>1, 'id'=>$id],
			'field' => 'id,title,content,is_header,is_footer,is_full_screen,access_count',
			'm' => 0,
			'n' => 1,
		];
		$data = CustomViewService::CustomViewList($params);
		if(!empty($data['data'][0]))
		{
			// 访问统计
			CustomViewService::CustomViewAccessCountInc(['id'=>$id]);

			// 浏览器标题
			$this->assign('home_seo_site_title', $this->GetBrowserSeoTitle($data['data'][0]['title'], 1));

			$this->assign('data', $data['data'][0]);
			return $this->fetch();
		} else {
			$this->assign('msg', '页面不存在或已删除');
			return $this->fetch('public/tips_error');
		}
	}
}
?>