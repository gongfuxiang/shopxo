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
use app\service\ConfigService;
use app\service\DiyService;

/**
 * 手机端 - 配置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AppConfig extends Base
{
    public $nav_type;

    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 导航类型
        $this->nav_type = empty($this->data_request['type']) ? 'index' : $this->data_request['type'];
    }

	/**
     * 配置列表
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
        // diy页面
        $ret = DiyService::DiyList([
            'n'      => 0,
            'field'  => 'id,name',
            'where'  => [
                ['is_enable', '=', 1],
            ],
        ]);
        $diy_list = empty($ret['data']) ? [] : $ret['data'];

        $assign = [
            // 配置数据
            'data'                  => ConfigService::ConfigList(),
            // 管理导航
            'nav_data'              => MyLang('appconfig.base_nav_list'),
            // 页面导航
            'nav_type'              => $this->nav_type,
            // diy页面
            'diy_list'              => $diy_list,
            // 平台
            'common_platform_type'  => MyConst('common_platform_type'),
        ];
        MyViewAssign($assign);
        return MyView($this->nav_type);
	}

	/**
	 * 配置数据保存
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	public function Save()
	{
        $params = $_POST;
        if($this->nav_type == 'app')
        {
            // 空字段处理
            $field_list = [
                'common_user_verify_bind_mobile_list',
                'common_user_onekey_bind_mobile_list',
                'common_user_address_platform_import_list',
                'common_app_user_base_popup_pages',
                'common_app_user_base_popup_client',
            ];
            $params = ConfigService::FieldsEmptyDataHandle($params, $field_list);
        }
        // 保存数据
		return ApiService::ApiDataReturn(ConfigService::ConfigSave($params));
	}
}
?>