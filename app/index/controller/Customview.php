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
namespace app\index\controller;

use app\service\CustomViewService;
use app\service\SeoService;

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
			MyViewAssign('home_seo_site_title', SeoService::BrowserSeoTitle($data['data'][0]['title']));

			MyViewAssign('data', $data['data'][0]);
            MyViewAssign('is_header', $data['data'][0]['is_header']);
            MyViewAssign('is_footer', $data['data'][0]['is_footer']);
			return MyView();
		} else {
			MyViewAssign('msg', '页面不存在或已删除');
			return MyView('public/tips_error');
		}
	}
}
?>