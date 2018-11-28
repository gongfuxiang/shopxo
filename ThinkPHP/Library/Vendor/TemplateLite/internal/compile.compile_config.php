<?php
/**
 * Template Lite compile config variables - template internal module
 *
 * Type:	 template
 * Name:	 compile_config
 */

function compile_compile_config($variable, &$object)
{
	$_result	= "";

	// remove the beginning and ending #
	$variable = substr($variable, 1, -1);

	// get [foo] and .foo and (...) pieces			
	preg_match_all('!(?:^\w+)|(?:' . $object->_var_bracket_regexp . ')|\.\$?\w+|\S+!', $variable, $_match);
	$variable = $_match[0];
	$var_name = array_shift($variable);

	$_result = "\$this->_confs['$var_name']";
	foreach ($variable as $var)
	{
		if ($var{0} == '[')
		{
			$var = substr($var, 1, -1);
			if (is_numeric($var))
			{
				$_result .= "[$var]";
			}
			elseif ($var{0} == '$')
			{
				$_result .= "[" . $object->_compile_variable($var) . "]";
			}
			elseif ($var{0} == '#')
			{
				$_result .= "[" . $object->_compile_config($var) . "]";
			}
			else
			{
				$_result .= "['$var']";
			}
	   }
	   else if ($var{0} == '.')
	   {
  				if ($var{1} == '$')
			{
   				$_result .= "[\$this->_TPL['" . substr($var, 2) . "']]";
			}
	   		else
			{
		   		$_result .= "['" . substr($var, 1) . "']";
			}
		}
		else if (substr($var,0,2) == '->')
		{
			if(substr($var,2,2) == '__')
			{
				$object->trigger_error('call to internal object members is not allowed', E_USER_ERROR, __FILE__, __LINE__);
			}
			else if (substr($var, 2, 1) == '$')
			{
				$_output .= '->{(($var=$this->_TPL[\''.substr($var,3).'\']) && substr($var,0,2)!=\'__\') ? $_var : $this->trigger_error("cannot access property \\"$var\\"")}';
			}
		}
		else
		{
			$object->trigger_error('#' . $var_name.implode('', $variable) . '# is an invalid reference', E_USER_ERROR, __FILE__, __LINE__);
		}
	}
	return $_result;
}

?>