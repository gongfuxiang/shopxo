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
    0 => 'app\\plugins\\touristbuy\\Hook',
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
  'plugins_view_common_top' => 
  array (
    0 => 'app\\plugins\\commontopmaxpicture\\Hook',
    1 => 'app\\plugins\\commontopnotice\\Hook',
  ),
  'plugins_view_user_center_top' => 
  array (
    0 => 'app\\plugins\\usercentertopnotice\\Hook',
  ),
  'plugins_service_user_login_end' => 
  array (
    0 => 'app\\plugins\\userloginrewardintegral\\Hook',
  ),
  'plugins_css' => 
  array (
    0 => 'app\\plugins\\commonrightnavigation\\Hook',
    1 => 'app\\plugins\\commononlineservice\\Hook',
  ),
  'plugins_js' => 
  array (
    0 => 'app\\plugins\\commonrightnavigation\\Hook',
    1 => 'app\\plugins\\commononlineservice\\Hook',
  ),
  'plugins_view_common_bottom' => 
  array (
    0 => 'app\\plugins\\commonrightnavigation\\Hook',
    1 => 'app\\plugins\\commononlineservice\\Hook',
    2 => 'app\\plugins\\expressforkdn\\Hook',
  ),
  'plugins_service_navigation_header_handle' => 
  array (
    0 => 'app\\plugins\\answers\\Hook',
    1 => 'app\\plugins\\touristbuy\\Hook',
  ),
  'plugins_admin_view_common_bottom' => 
  array (
    0 => 'app\\plugins\\expressforkdn\\Hook',
  ),
  'plugins_common_page_bottom' => 
  array (
    0 => 'app\\plugins\\expressforkdn\\Hook',
  ),
  'plugins_admin_common_page_bottom' => 
  array (
    0 => 'app\\plugins\\expressforkdn\\Hook',
  ),
  'plugins_common_header' => 
  array (
    0 => 'app\\plugins\\expressforkdn\\Hook',
    1 => 'app\\plugins\\touristbuy\\Hook',
  ),
  'plugins_admin_common_header' => 
  array (
    0 => 'app\\plugins\\expressforkdn\\Hook',
  ),
  'plugins_service_order_handle_begin' => 
  array (
    0 => 'app\\plugins\\expressforkdn\\Hook',
  ),
  'plugins_service_system_begin' => 
  array (
    0 => 'app\\plugins\\touristbuy\\Hook',
  ),
  'plugins_view_header_navigation_top_left' => 
  array (
    0 => 'app\\plugins\\touristbuy\\Hook',
  ),
  'plugins_view_user_login_info_top' => 
  array (
    0 => 'app\\plugins\\touristbuy\\Hook',
  ),
  'plugins_view_user_sms_reg_info' => 
  array (
    0 => 'app\\plugins\\touristbuy\\Hook',
  ),
  'plugins_view_user_email_reg_info' => 
  array (
    0 => 'app\\plugins\\touristbuy\\Hook',
  ),
  'plugins_service_buy_handle' => 
  array (
    0 => 'app\\plugins\\freightfee\\Hook',
    1 => 'app\\plugins\\newuserreduction\\Hook',
  ),
);
?>