<?php
/**
 * Template Lite section_start compile plugin converted from Smarty
 *
 * Type:	 compile
 * Name:	 section_start
 */

function compile_section_start($arguments, &$object)
{
	$attrs = $object->_parse_arguments($arguments);
	$arg_list = array();

	$output = '<?php ';
	$section_name = $attrs['name'];
	if (empty($section_name))
	{
		$object->trigger_error("missing section name", E_USER_ERROR, __FILE__, __LINE__);
	}

	$output .= "if (isset(\$this->_sections['$section_name'])) unset(\$this->_sections['$section_name']);\n";
	$section_props = "\$this->_sections['$section_name']";

	foreach ($attrs as $attr_name => $attr_value)
	{
		switch ($attr_name)
		{
			case 'loop':
				$output .= "{$section_props}['loop'] = is_array($attr_value) ? count($attr_value) : max(0, (int)$attr_value);\n";
				break;

			case 'show':
				if (is_bool($attr_value))
				{
					$show_attr_value = $attr_value ? 'true' : 'false';
				}
				else
				{
					$show_attr_value = "(bool)$attr_value";
				}
				$output .= "{$section_props}['show'] = $show_attr_value;\n";
				break;

			case 'name':
				$output .= "{$section_props}['$attr_name'] = '$attr_value';\n";
				break;

			case 'max':
			case 'start':
				$output .= "{$section_props}['$attr_name'] = (int)$attr_value;\n";
				break;

			case 'step':
				$output .= "{$section_props}['$attr_name'] = ((int)$attr_value) == 0 ? 1 : (int)$attr_value;\n";
				break;

			default:
				$object->trigger_error("unknown section attribute - '$attr_name'", E_USER_ERROR, __FILE__, __LINE__);
				break;
		}
	}

	if (!isset($attrs['show']))
	{
		$output .= "{$section_props}['show'] = true;\n";
	}

	if (!isset($attrs['loop']))
	{
		$output .= "{$section_props}['loop'] = 1;\n";
	}

	if (!isset($attrs['max']))
	{
		$output .= "{$section_props}['max'] = {$section_props}['loop'];\n";
	}
	else
	{
		$output .= "if ({$section_props}['max'] < 0)\n" .
					"	{$section_props}['max'] = {$section_props}['loop'];\n";
	}

	if (!isset($attrs['step']))
	{
		$output .= "{$section_props}['step'] = 1;\n";
	}

	if (!isset($attrs['start']))
	{
		$output .= "{$section_props}['start'] = {$section_props}['step'] > 0 ? 0 : {$section_props}['loop']-1;\n";
	}
	else
	{
		$output .= "if ({$section_props}['start'] < 0)\n" .
				   "	{$section_props}['start'] = max({$section_props}['step'] > 0 ? 0 : -1, {$section_props}['loop'] + {$section_props}['start']);\n" .
				   "else\n" .
				   "	{$section_props}['start'] = min({$section_props}['start'], {$section_props}['step'] > 0 ? {$section_props}['loop'] : {$section_props}['loop']-1);\n";
	}

	$output .= "if ({$section_props}['show']) {\n";
	if (!isset($attrs['start']) && !isset($attrs['step']) && !isset($attrs['max']))
	{
		$output .= "	{$section_props}['total'] = {$section_props}['loop'];\n";
	}
	else
	{
		$output .= "	{$section_props}['total'] = min(ceil(({$section_props}['step'] > 0 ? {$section_props}['loop'] - {$section_props}['start'] : {$section_props}['start']+1)/abs({$section_props}['step'])), {$section_props}['max']);\n";
	}
	$output .= "	if ({$section_props}['total'] == 0)\n" .
			   "		{$section_props}['show'] = false;\n" .
			   "} else\n" .
			   "	{$section_props}['total'] = 0;\n";

	$output .= "if ({$section_props}['show']):\n";
	$output .= "
		for ({$section_props}['index'] = {$section_props}['start'], {$section_props}['iteration'] = 1;
			 {$section_props}['iteration'] <= {$section_props}['total'];
			 {$section_props}['index'] += {$section_props}['step'], {$section_props}['iteration']++):\n";
	$output .= "{$section_props}['rownum'] = {$section_props}['iteration'];\n";
	$output .= "{$section_props}['index_prev'] = {$section_props}['index'] - {$section_props}['step'];\n";
	$output .= "{$section_props}['index_next'] = {$section_props}['index'] + {$section_props}['step'];\n";
	$output .= "{$section_props}['first']	  = ({$section_props}['iteration'] == 1);\n";
	$output .= "{$section_props}['last']	   = ({$section_props}['iteration'] == {$section_props}['total']);\n";

	$output .= "?>";

	return $output;
}
?>