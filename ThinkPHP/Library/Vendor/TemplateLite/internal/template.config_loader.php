<?php
/**
 * Template Lite config_load template internal module
 *
 * Type:	 template
 * Name:	 config_load
 */

$this->_config_module_loaded = true;
$this->template_dir = $this->_get_dir($this->template_dir);
$this->config_dir = $this->_get_dir($this->config_dir);
$this->compile_dir = $this->_get_dir($this->compile_dir);
$name = ($this->encode_file_name) ? md5($this->template_dir . $file . $section_name . $var_name).'.php' : str_replace(".", "_", str_replace("/", "_", $file."_".$section_name."_".$var_name)).'.php';

if ($this->debugging)
{
	$debug_start_time = array_sum(explode(' ', microtime()));
}

if ($this->cache)
{
	array_push($this->_cache_info['config'], $file);
}

if (!$this->force_compile && file_exists($this->compile_dir.'c_'.$name) && (filemtime($this->compile_dir.'c_'.$name) > filemtime($this->config_dir.$file)))
{
	include($this->compile_dir.'c_'.$name);
	return true;
}

if (!is_object($this->_config_obj))
{
	require_once(TEMPLATE_LITE_DIR . "class.config.php");
	$this->_config_obj = new $this->config_class;
	$this->_config_obj->overwrite = $this->config_overwrite;
	$this->_config_obj->booleanize = $this->config_booleanize;
	$this->_config_obj->fix_new_lines = $this->config_fix_new_lines;
	$this->_config_obj->read_hidden = $this->config_read_hidden;
}

if (!($_result = $this->_config_obj->config_load($this->config_dir.$file, $section_name, $var_name)))
{
	return false;
}

if (!empty($var_name) || !empty($section_name))
{
	$output = "\$this->_confs = " . var_export($_result, true) . ";";
}
else
{
	// must shift of the bottom level of the array to get rid of the section labels
	$_temp = array();
	foreach($_result as $value)
	{
		$_temp = array_merge($_temp, $value);
	}
	$output = "\$this->_confs = " . var_export($_temp, true) . ";";
}

$f = fopen($this->compile_dir.'c_'.$name, "w");
fwrite($f, '<?php ' . $output . ' ?>');
fclose($f);
eval($output);

if ($this->debugging)
{
	$this->_templatelite_debug_info[] = array('type'	  => 'config',
										'filename'  => $file.' ['.$section_name.'] '.$var_name,
										'depth'	 => 0,
										'exec_time' => array_sum(explode(' ', microtime())) - $debug_start_time );
}

return true;

?>