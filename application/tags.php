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
  'plugins_service_order_status_change_history_success_handle' => 
  array (
    0 => 'app\\plugins\\neworderemail\\Hook',
  ),
  'plugins_service_order_pay_launch_handle' => 
  array (
    0 => 'app\\plugins\\neworderemail\\Hook',
  ),
  'plugins_admin_js' => 
  array (
    0 => 'app\\plugins\\orderremind\\Hook',
  ),
  'plugins_admin_view_common_bottom' => 
  array (
    0 => 'app\\plugins\\orderremind\\Hook',
  ),
);
?>