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
use app\service\SystemService;
use app\service\ConfigService;
use app\service\GoodsCategoryService;
use app\service\SiteService;
use app\service\PaymentService;
use app\service\ResourcesService;

/**
 * 站点设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Site extends Base
{
    public $nav_type;
    public $view_type;

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
        $this->nav_type = empty($this->data_request['nav_type']) ? 'base' : $this->data_request['nav_type'];
        $this->view_type = empty($this->data_request['view_type']) ? 'index' : $this->data_request['view_type'];

        // 仅网站设置页面存在多个子页面
        if($this->nav_type != 'siteset')
        {
            $this->view_type = 'index';
        }
    }

    /**
     * 配置列表
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-25
     * @desc    description
     */
    public function Index()
    {
        // 公共数据
        $assign = $this->CurrentViewInit();

        // 配置信息
        $data = ConfigService::ConfigList();
        $assign['data'] = $data;

        // 数据处理
        switch($this->nav_type)
        {
            // 站点类型
            case 'sitetype' :
                // 地址处理
                if(!empty($data['common_self_extraction_address']) && !empty($data['common_self_extraction_address']['value']))
                {
                    $address = ConfigService::SiteTypeExtractionAddressList($data['common_self_extraction_address']['value']);
                    $assign['sitetype_address_list'] = $address['data'];
                }

                // 加载地图api
                $assign['is_load_map_api'] = 1;
                break;

            // 网站设置
            case 'siteset' :
                // 获取商品一级分类
                $where = [
                    ['pid', '=', 0],
                    ['is_home_recommended', '=', 1],
                    ['is_enable', '=', 1],
                ];
                $category = GoodsCategoryService::GoodsCategoryList(['where'=>$where]);
                if(!empty($category))
                {
                    // 关键字
                    $floor_keywords = (empty($data['home_index_floor_top_right_keywords']) || empty($data['home_index_floor_top_right_keywords']['value'])) ? [] : json_decode($data['home_index_floor_top_right_keywords']['value'], true);
                    // 分类
                    $floor_category = (empty($data['home_index_floor_left_top_category']) || empty($data['home_index_floor_left_top_category']['value'])) ? [] : json_decode($data['home_index_floor_left_top_category']['value'], true);
                    foreach($category as &$c)
                    {
                        // 获取二级分类
                        $where = [
                            ['pid', '=', $c['id']],
                            ['is_enable', '=', 1],
                        ];
                        $c['items'] = GoodsCategoryService::GoodsCategoryList(['where'=>$where]);

                        // 配置的关键字
                        $c['config_keywords'] = (!empty($floor_keywords) && is_array($floor_keywords) && array_key_exists($c['id'], $floor_keywords)) ? $floor_keywords[$c['id']] : '';

                        // 配置左侧分类
                        $c['config_category_ids'] = (!empty($floor_category) && is_array($floor_category) && array_key_exists($c['id'], $floor_category)) ? explode(',', $floor_category[$c['id']]) : [];
                    }
                }
                $assign['goods_category_list'] = $category;

                // 楼层自定义商品
                if(!empty($data['home_index_floor_manual_mode_goods']) && !empty($data['home_index_floor_manual_mode_goods']['value']))
                {
                    $ret = SiteService::FloorManualModeGoodsViewHandle(json_decode($data['home_index_floor_manual_mode_goods']['value'], true));
                    $assign['floor_manual_mode_goods_list'] = $ret['data'];
                }

                // 支付方式
                $assign['payment_list'] = PaymentService::PaymentList(['is_enable'=>1, 'is_open_user'=>1]);

                // 默认首页列表
                $assign['site_default_index_data_list'] = SystemService::SiteDefaultIndexDataList();
                break;
        }

        // 编辑器文件存放地址
        $assign['editor_path_type'] = ResourcesService::EditorPathTypeValue('common');

        // 数据赋值
        MyViewAssign($assign);

        // 视图
        $view = 'site/'.$this->nav_type.'/'.$this->view_type;
        return MyView($view);
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
        // 模板数据
        $assign = [
            // 主/子导航
            'nav_type'                                  => $this->nav_type,
            'view_type'                                 => $this->view_type,
            // 时区
            'common_site_timezone_list'                 => MyLang('common_site_timezone_list'),
            // 平台
            'common_platform_type'                      => MyConst('common_platform_type'),
            // 关闭开启
            'common_close_open_list'                    => MyConst('common_close_open_list'),
            // 登录方式
            'common_login_type_list'                    => MyConst('common_login_type_list'),
            // 用户注册类型列表
            'common_user_reg_type_list'                 => MyConst('common_user_reg_type_list'),
            // 图片验证码类型
            'common_site_images_verify_rand_type_list'  => MyConst('common_site_images_verify_rand_type_list'),
            // 图片验证码规则
            'common_site_images_verify_rules_list'      => MyConst('common_site_images_verify_rules_list'),
            // 热门搜索关键字
            'common_search_keywords_type_list'          => MyConst('common_search_keywords_type_list'),
            // 是否
            'common_is_text_list'                       => MyConst('common_is_text_list'),
            // 下单指定时间
            'common_buy_datetime_config_list'           => MyConst('common_buy_datetime_config_list'),
            // 下单联系信息
            'common_buy_extraction_contact_config_list' => MyConst('common_buy_extraction_contact_config_list'),
            // 站点类型
            'common_site_type_list'                     => MyConst('common_site_type_list'),
            // 扣除库存规则
            'common_deduction_inventory_rules_list'     => MyConst('common_deduction_inventory_rules_list'),
            // 增加销量规则
            'common_sales_count_inc_rules_list'         => MyConst('common_sales_count_inc_rules_list'),
            // 首页商品排序规则
            'common_goods_order_by_type_list'           => MyConst('common_goods_order_by_type_list'),
            'common_data_order_by_rule_list'            => MyConst('common_data_order_by_rule_list'),
            // 首页楼层数据类型
            'common_site_floor_data_type_list'          => MyConst('common_site_floor_data_type_list'),
            // 搜索参数类型
            'common_goods_parameters_type_list'         => MyConst('common_goods_parameters_type_list'),
            // 关闭开启
            'common_search_goods_show_type_list'        => MyConst('common_search_goods_show_type_list'),
            // 多语言
            'common_multilingual_list'                  => MyConst('common_multilingual_list'),
            // 商品分类
            'common_show_goods_category_level_list'     => MyConst('common_show_goods_category_level_list'),
            // 主导航
            'base_nav_list'                             => MyLang('site.base_nav_list'),
            // 网站设置导航
            'siteset_nav_list'                          => MyLang('site.siteset_nav_list'),
        ];
        return $assign;
    }
    
    /**
     * 配置数据保存
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-25
     * @desc    description
     */
    public function Save()
    {
        // 参数
        $params = $_POST;

        // 字段不存在赋空值
        $field_list = [];

        // 导航类型
        switch($this->nav_type)
        {
            // 基础
            case 'base' :
                $field_list[] = 'home_site_logo';
                $field_list[] = 'home_site_logo_wap';
                $field_list[] = 'home_site_logo_app';
                $field_list[] = 'home_site_logo_square';
                $field_list[] = 'home_site_app_state';
                break;

            // 用户注册
            case 'register' :
                $field_list[] = 'home_user_reg_type';
                $field_list[] = 'home_site_user_register_bg_images';
                break;

            // 用户登录
            case 'login' :
                $field_list[] = 'home_user_login_type';
                $field_list[] = 'home_site_user_login_ad1_images';
                $field_list[] = 'home_site_user_login_ad2_images';
                $field_list[] = 'home_site_user_login_ad3_images';
                break;

            // 密码找回
            case 'forgetpwd' :
                $field_list[] = 'home_site_user_forgetpwd_ad1_images';
                $field_list[] = 'home_site_user_forgetpwd_ad2_images';
                $field_list[] = 'home_site_user_forgetpwd_ad3_images';
                break;

            // 图片验证码
            case 'verify' :
                $field_list[] = 'common_images_verify_rules';
                break;

            // 站点类型
            case 'sitetype' :
                // 站点类型
                $params['common_site_type'] = empty($params['common_site_type']) ? '' : (is_array($params['common_site_type']) ? json_encode($params['common_site_type'], JSON_UNESCAPED_UNICODE) : $params['common_site_type']);

                // 自提地址处理
                if(!empty($params['common_self_extraction_address']))
                {
                    if(!is_array($params['common_self_extraction_address']))
                    {
                        $address = json_decode($params['common_self_extraction_address'], true);
                    } else {
                        $address = $params['common_self_extraction_address'];
                    }
                    foreach($address as $k=>$v)
                    {
                        $address[$k]['id'] = $k;
                        $address[$k]['logo'] = empty($v['logo']) ? '' : ResourcesService::AttachmentPathHandle($v['logo']);
                    }
                    $params['common_self_extraction_address'] = json_encode($address, JSON_UNESCAPED_UNICODE);
                }
                break;

            // 网站设置
            case 'siteset' :
                switch($this->view_type)
                {
                    // 首页
                    case 'index' :
                        // 楼层关键字
                        $params['home_index_floor_top_right_keywords'] = empty($params['home_index_floor_top_right_keywords']) ? '' : json_encode($params['home_index_floor_top_right_keywords'], JSON_UNESCAPED_UNICODE);
                        // 楼层自定义商品
                        $params['home_index_floor_manual_mode_goods'] = empty($params['home_index_floor_manual_mode_goods']) ? '' : json_encode($params['home_index_floor_manual_mode_goods'], JSON_UNESCAPED_UNICODE);
                        // 楼层左侧分类
                        $params['home_index_floor_left_top_category'] = empty($params['home_index_floor_left_top_category']) ? '' : json_encode($params['home_index_floor_left_top_category'], JSON_UNESCAPED_UNICODE);
                        break;

                    // 搜索
                    case 'search' :
                        $field_list[] = 'home_search_params_type';
                        break;

                    // 订单
                    case 'order' :
                        $field_list[] = 'common_buy_datetime_info';
                        $field_list[] = 'common_buy_extraction_contact_info';
                        $params['common_default_payment'] = empty($params['common_default_payment']) ? '' : json_encode($params['common_default_payment'], JSON_UNESCAPED_UNICODE);
                        break;

                    // 扩展
                    case 'extends' :
                        // 可用语言状态+图标
                        $choose_list = [];
                        if(!empty($params['common_multilingual_choose_list']) && is_array($params['common_multilingual_choose_list']))
                        {
                            foreach($params['common_multilingual_choose_list'] as $k=>$v)
                            {
                                $choose_list[$k] = [
                                    'icon'   => empty($v['icon']) ? '' : ResourcesService::AttachmentPathHandle($v['icon']),
                                    'status' => (isset($v['status']) && $v['status'] == 1) ? 1 : 0,
                                ];
                            }
                        }
                        $params['common_multilingual_choose_list'] = empty($choose_list) ? '' : json_encode($choose_list, JSON_UNESCAPED_UNICODE);

                        // 域名绑定多语言
                        $params['common_domain_multilingual_bind_list'] = empty($params['common_domain_multilingual_bind_list']) ? '' : json_encode(array_values($params['common_domain_multilingual_bind_list']), JSON_UNESCAPED_UNICODE);
                        break;
                }
                break;

            // 缓存
            case 'cache' :
                // session是否使用缓存
                // 数据是否使用缓存
                if((isset($params['common_session_is_use_cache']) && $params['common_session_is_use_cache'] == 1) || (isset($params['common_data_is_use_redis_cache']) && $params['common_data_is_use_redis_cache'] == 1))
                {
                    // 连接测试
                    $ret = SiteService::RedisCheckConnectPing($params['common_cache_data_redis_host'], $params['common_cache_data_redis_port'], $params['common_cache_data_redis_password']);
                    if($ret['code'] != 0)
                    {
                        return $ret;
                    }
                }
                break;
        }

        // 基础配置
        $ret = ConfigService::ConfigSave(ConfigService::FieldsEmptyDataHandle($params, $field_list));

        // 清除缓存
        if($ret['code'] == 0)
        {
            switch($this->nav_type)
            {
                // 登录
                case 'login' :
                    MyCache(SystemService::CacheKey('shopxo.cache_user_login_left_key'), null);

                // 密码找回
                case 'forgetpwd' :
                    MyCache(SystemService::CacheKey('shopxo.cache_user_forgetpwd_left_key'), null);
                    break;
            }
        }
        return ApiService::ApiDataReturn($ret);
    }

    /**
     * 商品搜索
     * @author  Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2020-11-25
     * @desc    description
     */
    public function GoodsSearch()
    {
        // 搜索数据
        $ret = SiteService::GoodsSearchList($this->data_request);
        if($ret['code'] == 0)
        {
            MyViewAssign('data', $ret['data']['data']);
            $ret['data']['data'] = MyView('site/public/goods_search');
        }
        return ApiService::ApiDataReturn($ret);
    }
}
?>