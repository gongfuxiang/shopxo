<?php
/**
 * Template Lite template_destroy_dir template internal module
 *
 * Type:	 template
 * Name:	 template_destroy_dir
 */

function template_destroy_dir($file, $id, $dir, &$object)
{
	if ($file == null && $id == null)
	{
		if (is_dir($dir))
		{
			if($d = opendir($dir))
			{
				while(($f = readdir($d)) !== false)
				{
					if ($f != '.' && $f != '..')
					{
						template_rm_dir($dir.$f.DIRECTORY_SEPARATOR);
					}
				}
			}
		}
	}
	else
	{
		if ($id == null)
		{
			$object->template_dir = $object->_get_dir($object->template_dir);

			$name = ($object->encode_file_name) ? md5($object->template_dir.$file).'.php' : str_replace(".", "_", str_replace("/", "_", $file)).'.php';
			@unlink($dir.$name);
		}
		else
		{
			$_args = "";
			foreach(explode('|', $id) as $value)
			{
				$_args .= $value.DIRECTORY_SEPARATOR;
			}
			template_rm_dir($dir.DIRECTORY_SEPARATOR.$_args);
		}
	}
}

function template_rm_dir($dir)
{
	if (is_file(substr($dir, 0, -1)))
	{
		@unlink(substr($dir, 0, -1));
		return;
	}
	if ($d = opendir($dir))
	{
		while(($f = readdir($d)) !== false)
		{
			if ($f != '.' && $f != '..')
			{
				template_rm_dir($dir.$f.DIRECTORY_SEPARATOR, $object);
			}
		}
		@rmdir($dir.$f);
	}
}

?>