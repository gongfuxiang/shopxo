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
  'plugins_service_navigation_header_handle' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_users_center_left_menu_handle' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
  'plugins_service_header_navigation_top_right_handle' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
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
  'plugins_service_editor_path_type_admin_goods_saveinfo' => 
  array (
    0 => 'app\\plugins\\store\\Hook',
  ),
);
?>