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

// 应用行为扩展定义文件
return array (
  'app_init' => 
  array (
  ),
  'app_begin' => 
  array (
  ),
  'module_init' => 
  array (
  ),
  'action_begin' => 
  array (
  ),
  'view_filter' => 
  array (
  ),
  'app_end' => 
  array (
  ),
  'log_write' => 
  array (
  ),
  'plugins_css' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\limitedtimediscount\\Hook',
  ),
  'plugins_js' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\limitedtimediscount\\Hook',
  ),
  'plugins_service_navigation_header_handle' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\limitedtimediscount\\Hook',
  ),
  'plugins_service_goods_handle_end' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\limitedtimediscount\\Hook',
  ),
  'plugins_view_buy_base_confirm_top' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_service_buy_group_goods_handle' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_service_quick_navigation_pc' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_quick_navigation_h5' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_quick_navigation_weixin' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_quick_navigation_alipay' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_quick_navigation_baidu' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_quick_navigation_qq' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_quick_navigation_toutiao' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_users_center_left_menu_handle' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_header_navigation_top_right_handle' => 
  array (
    0 => 'app\\plugins\\signin\\Hook',
  ),
  'plugins_service_goods_spec_base' => 
  array (
    0 => 'app\\plugins\\limitedtimediscount\\Hook',
  ),
  'plugins_view_goods_detail_base_top' => 
  array (
    0 => 'app\\plugins\\limitedtimediscount\\Hook',
  ),
  'plugins_view_home_floor_top' => 
  array (
    0 => 'app\\plugins\\limitedtimediscount\\Hook',
  ),
);
?>