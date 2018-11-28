<?php
/**
 * Template Lite template_build_dir template internal module
 *
 * Type:	 template
 * Name:	 template_build_dir
 */

function template_build_dir($dir, $id, &$object)
{
	$_args = explode('|', $id);
	if (count($_args) == 1 && empty($_args[0]))
	{
		return $object->_get_dir($dir);
	}
	$_result = $object->_get_dir($dir);
	foreach($_args as $value)
	{
		$_result .= $value.DIRECTORY_SEPARATOR;
		if (!is_dir($_result))
		{
			@mkdir($_result, 0777);
		}
	}
	return $_result;
}

?>