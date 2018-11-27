<?php
/**
 * Template Lite generate_debug_output template internal module
 *
 * Type:	 template
 * Name:	 generate_debug_output
 */

function generate_compiler_debug_output(&$object)
{
    $debug_output = "\$assigned_vars = \$this->_vars;\n";
    $debug_output .= "ksort(\$assigned_vars);\n";
    $debug_output .= "if (@is_array(\$this->_config[0])) {\n";
    $debug_output .= "    \$config_vars = \$this->_config[0];\n";
    $debug_output .= "    ksort(\$config_vars);\n";
    $debug_output .= "    \$this->assign('_debug_config_keys', array_keys(\$config_vars));\n";
    $debug_output .= "    \$this->assign('_debug_config_vals', array_values(\$config_vars));\n";
    $debug_output .= "}   \n";
	
    $debug_output .= "\$included_templates = \$this->_templatelite_debug_info;\n";

    $debug_output .= "\$this->assign('_debug_keys', array_keys(\$assigned_vars));\n";
    $debug_output .= "\$this->assign('_debug_vals', array_values(\$assigned_vars));\n";
    $debug_output .= "\$this->assign('_debug_tpls', \$included_templates);\n";

	$debug_output .= "\$this->_templatelite_debug_loop = true;\n";
	$debug_output .= "\$this->_templatelite_debug_dir = \$this->template_dir;\n";
	$debug_output .= "\$this->template_dir = TEMPLATE_LITE_DIR . 'internal/';\n";
	$debug_output .= "echo \$this->_fetch_compile('debug.tpl');\n";
	$debug_output .= "\$this->template_dir = \$this->_templatelite_debug_dir;\n";
	$debug_output .= "\$this->_templatelite_debug_loop = false; \n";
	return $debug_output;
}

?>