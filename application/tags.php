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
    1 => 'app\\plugins\\shop\\Hook',
    2 => 'app\\plugins\\multilingual\\Hook',
    3 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_js' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\shop\\Hook',
    2 => 'app\\plugins\\multilingual\\Hook',
    3 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_navigation_header_handle' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\shop\\Hook',
    2 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_quick_navigation_pc' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_service_quick_navigation_h5' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_service_quick_navigation_weixin' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_service_quick_navigation_alipay' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_service_quick_navigation_baidu' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_service_quick_navigation_qq' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_service_quick_navigation_toutiao' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_service_goods_handle_end' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\shop\\Hook',
  ),
  'plugins_view_buy_form_inside' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_view_buy_base_confirm_top' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_buy_group_goods_handle' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\newuserreduction\\Hook',
  ),
  'plugins_service_buy_order_insert_end' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\shop\\Hook',
    2 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_order_status_change_history_success_handle' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
    1 => 'app\\plugins\\shop\\Hook',
    2 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_base_data_return_api_buy_index' => 
  array (
    0 => 'app\\plugins\\points\\Hook',
  ),
  'plugins_admin_css' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\orderpricerevision\\Hook',
    2 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_users_center_left_menu_handle' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_header_navigation_top_right_handle' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\multilingual\\Hook',
    2 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_goods_save_handle' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
  ),
  'plugins_service_editor_path_type_admin_goods_saveinfo' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_view_goods_detail_right_content_bottom' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_view_goods_detail_base_bottom' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_view_goods_detail_base_buy_nav_min_inside_begin' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_warehouse_handle_end' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_buy_handle' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
  ),
  'plugins_service_buy_order_insert_begin' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_order_aftersale_audit_handle_end' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_view_admin_goods_save' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
  ),
  'plugins_module_form_admin_goods_index' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
  ),
  'plugins_module_form_admin_goods_detail' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
  ),
  'plugins_service_goods_buy_nav_button_handle' => 
  array (
    0 => 'app\\plugins\\shop\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_common_page_bottom' => 
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
  'plugins_service_search_goods_list_where' => 
  array (
    0 => 'app\\plugins\\multilingual\\Hook',
  ),
  'plugins_admin_js' => 
  array (
    0 => 'app\\plugins\\orderpricerevision\\Hook',
  ),
  'plugins_admin_view_common_bottom' => 
  array (
    0 => 'app\\plugins\\orderpricerevision\\Hook',
  ),
  'plugins_view_admin_order_list_operate' => 
  array (
    0 => 'app\\plugins\\orderpricerevision\\Hook',
    1 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_warehouse_goods_inventory_deduct' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_warehouse_goods_inventory_rollback' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_warehouse_goods_inventory_sync' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_goods_field_status_update' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_goods_delete' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_goods_save_end' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_system_begin' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
);
?>