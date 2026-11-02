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
use app\service\GoodsCategoryService;

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
        $this->EnsurePageDiyConfig();
        // 手机端分类页风格配置
        ConfigService::ConfigEnsure('common_goods_category_goods_style', '1');
        // diy页面
        $ret = DiyService::DiyList([
            'n'      => 0,
            'field'  => 'id,name,config',
            'where'  => [
                ['is_enable', '=', 1],
            ],
        ]);
        $all_diy_list = empty($ret['data']) ? [] : $ret['data'];
        $diy_list = [];
        $goods_detail_diy_list = [];
        foreach($all_diy_list as $diy)
        {
            $row = [
                'id'   => $diy['id'],
                'name' => $diy['name'],
            ];
            // 首页/用户中心仅公共；商品详情仅详情装修（组件依赖商品上下文）
            if(DiyService::IsGoodsDetailDesign($diy))
            {
                $goods_detail_diy_list[] = $row;
            } else {
                $diy_list[] = $row;
            }
        }

        // 页面DIY：tab / 快捷搜索 switch 均可定位面板
        $page_diy_tab = empty($this->data_request['tab']) ? '' : $this->data_request['tab'];
        if($this->nav_type == 'pagediy' && $page_diy_tab === '' && !empty($this->data_request['switch']))
        {
            $page_diy_tab = $this->data_request['switch'];
        }
        if(!in_array($page_diy_tab, ['home', 'goods-detail', 'user-center'], true))
        {
            $page_diy_tab = 'home';
        }

        $assign = [
            // 配置数据
            'data'                          => ConfigService::ConfigList(),
            // 管理导航
            'nav_data'                      => MyLang('appconfig.base_nav_list'),
            // 页面导航
            'nav_type'                      => $this->nav_type,
            'page_diy_tab'                  => $page_diy_tab,
            // 公共 DIY（首页 / 用户中心）
            'diy_list'                      => $diy_list,
            // 商品详情类型 DIY
            'goods_detail_diy_list'         => $goods_detail_diy_list,
            // 品类 DIY 已配置列表
            'goods_detail_category_diy_list'=> DiyService::GoodsDetailCategoryDiyAdminList(),
            // 商品分类
            'goods_category_list'           => GoodsCategoryService::GoodsCategoryAll(),
            // 分类层级 / 风格
            'common_show_goods_category_level_list'  => MyConst('common_show_goods_category_level_list'),
            'common_goods_category_goods_style_list' => MyConst('common_goods_category_goods_style_list'),
            // 平台
            'common_platform_type'          => MyConst('common_platform_type'),
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
        $this->EnsurePageDiyConfig();
        $params = $this->data_request;
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
        // 页面 DIY 与 APP/小程序 共用同一套配置；品类列表删空时表单可能不带该字段
        if(in_array($this->nav_type, ['pagediy', 'app']))
        {
            if(!array_key_exists('common_app_goods_detail_category_diy_mode', $params))
            {
                if($this->nav_type == 'pagediy' || array_key_exists('common_app_goods_detail_data_diy_mode', $params))
                {
                    $params['common_app_goods_detail_category_diy_mode'] = [];
                }
            } elseif(is_string($params['common_app_goods_detail_category_diy_mode']))
            {
                $decoded = json_decode(htmlspecialchars_decode($params['common_app_goods_detail_category_diy_mode']), true);
                $params['common_app_goods_detail_category_diy_mode'] = empty($decoded) || !is_array($decoded) ? [] : $decoded;
            }
        }
        // 保存数据
        return ApiService::ApiDataReturn(ConfigService::ConfigSave($params));
	}

    /**
     * 商品详情、用户中心的页面DIY配置不存在时先补上
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2026-10-27
     * @desc    description
     */
    private function EnsurePageDiyConfig()
    {
        ConfigService::ConfigEnsure('common_app_goods_detail_data_diy_mode', '', 'common');
        ConfigService::ConfigEnsure('common_app_goods_detail_category_diy_mode', '', 'common');
        ConfigService::ConfigEnsure('common_app_user_center_data_diy_mode', '', 'common');
    }
}
?>
