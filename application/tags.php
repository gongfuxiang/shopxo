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
    0 => 'app\\plugins\\ordergoodsform\\Hook',
    1 => 'app\\plugins\\membershiplevelvip\\Hook',
    2 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_js' => 
  array (
    0 => 'app\\plugins\\ordergoodsform\\Hook',
    1 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_view_goods_detail_base_inventory_top' => 
  array (
    0 => 'app\\plugins\\ordergoodsform\\Hook',
  ),
  'plugins_service_navigation_header_handle' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_goods_handle_end' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_goods_spec_base' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_buy_group_goods_handle' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_user_login_success_record' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_goods_spec_extends_handle' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_view_admin_user_save' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_user_save_handle' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_goods_save_handle' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_view_goods_detail_panel_price_top' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_view_home_goods_inside_bottom' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_view_search_goods_inside_bottom' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_goods_spec_type' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_users_center_left_menu_handle' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
  ),
  'plugins_service_header_navigation_top_right_handle' => 
  array (
    0 => 'app\\plugins\\membershiplevelvip\\Hook',
    1 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_common_page_bottom' => 
  array (
    0 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_service_quick_navigation_pc' => 
  array (
    0 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_service_quick_navigation_h5' => 
  array (
    0 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_view_common_top' => 
  array (
    0 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_view_common_bottom' => 
  array (
    0 => 'app\\plugins\\multilingual\\Hook',
  ),
);
?>