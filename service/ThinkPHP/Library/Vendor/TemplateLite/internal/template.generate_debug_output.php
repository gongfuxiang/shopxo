<?php
/**
 * Template Lite template_generate_debug_output template internal module
 *
 * Type:	 template
 * Name:	 template_generate_debug_output
 */

function template_generate_debug_output(&$object)
{
    $assigned_vars = $object->_vars;
    ksort($assigned_vars);
    if (@is_array($object->_config[0]))
	{
        $config_vars = $object->_config[0];
        ksort($config_vars);
        $object->assign("_debug_config_keys", array_keys($config_vars));
        $object->assign("_debug_config_vals", array_values($config_vars));
    }   

    $included_templates = $object->_templatelite_debug_info;

    $object->assign("_debug_keys", array_keys($assigned_vars));
    $object->assign("_debug_vals", array_values($assigned_vars));
    $object->assign("_debug_tpls", $included_templates);
    $object->assign("_templatelite_debug_output", "");

	$object->_templatelite_debug_loop = true;
	$object->_templatelite_debug_dir = $object->template_dir;
	$object->template_dir = TEMPLATE_LITE_DIR . "internal/";
	$debug_output = $object->fetch("debug.tpl");
	$object->template_dir = $object->_templatelite_debug_dir;
	$object->_templatelite_debug_loop = false;
	return $debug_output;
}

?>