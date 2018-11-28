<?php
/**
 * Template Lite template_fetch_compile_include template internal module
 *
 * Type:	 template
 * Name:	 template_fetch_compile_include
 */

function template_fetch_compile_include($_templatelite_include_file, $_templatelite_include_vars, &$object)
{
	if ($object->debugging)
	{
		$object->_templatelite_debug_info[] = array('type'	  => 'template',
											'filename'  => $_templatelite_include_file,
											'depth'	 => ++$object->_inclusion_depth,
											'exec_time' => array_sum(explode(' ', microtime())) );
		$included_tpls_idx = count($object->_templatelite_debug_info) - 1;
	}

	$object->_vars = array_merge($object->_vars, $_templatelite_include_vars);
	$_templatelite_include_file = $object->_get_resource($_templatelite_include_file);
	if(isset($object->_confs[0]))
	{
		array_unshift($object->_confs, $object->_confs[0]);
		$_compiled_output = $object->_fetch_compile($_templatelite_include_file);
		array_shift($object->_confs);
	}
	else
	{
		$_compiled_output = $object->_fetch_compile($_templatelite_include_file);
	}

	$object->_inclusion_depth--;

	if ($object->debugging)
	{
		$object->_templatelite_debug_info[$included_tpls_idx]['exec_time'] = array_sum(explode(' ', microtime())) - $object->_templatelite_debug_info[$included_tpls_idx]['exec_time'];
	}
	return $_compiled_output;
}

?>