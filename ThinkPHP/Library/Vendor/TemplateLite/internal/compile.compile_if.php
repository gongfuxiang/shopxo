<?php
/**
 * Template Lite compile IF tag - template internal module
 *
 * Type:	 template
 * Name:	 compile_parse_is_expr
 */

function compile_compile_if($arguments, $elseif, $while, &$object)
{
	$_result	= "";
	$_match		= array();
	$_args		= array();
	$_is_arg_stack	= array();

	// extract arguments from the equation
	preg_match_all('/(?>(' . $object->_var_regexp . '|\/?' . $object->_svar_regexp . '|\/?' . $object->_func_regexp . ')(?:' . $object->_mod_regexp . '*)?|\-?0[xX][0-9a-fA-F]+|\-?\d+(?:\.\d+)?|\.\d+|!==|===|==|!=|<>|<<|>>|<=|>=|\&\&|\|\||\(|\)|,|\!|\^|=|\&|\~|<|>|\%|\+|\-|\/|\*|\@|\b\w+\b|\S+)/x', $arguments, $_match);
	$_args = $_match[0];

	// make sure we have balanced parenthesis
	$_args_count = array_count_values($_args);
	if(isset($_args_count['(']) && $_args_count['('] != $_args_count[')'])
	{
		$object->trigger_error("unbalanced parenthesis in if statement", E_USER_ERROR, __FILE__, __LINE__);
	}

	$count_args = count($_args);
	for ($i = 0, $for_max = $count_args; $i < $for_max; $i++)
	{
		$_arg = &$_args[$i];
		switch (strtolower($_arg))
		{
			case '!':
			case '%':
			case '!==':
			case '==':
			case '===':
			case '>':
			case '<':
			case '!=':
			case '<>':
			case '<<':
			case '>>':
			case '<=':
			case '>=':
			case '&&':
			case '||':
			case '^':
			case '&':
			case '~':
			case ')':
			case ',':
			case '+':
			case '-':
			case '*':
			case '/':
			case '@':
				break;
			case 'eq':
				$_arg = '==';
				break;
			case 'ne':
			case 'neq':
				$_arg = '!=';
				break;
			case 'lt':
				$_arg = '<';
				break;
			case 'le':
			case 'lte':
				$_arg = '<=';
				break;
			case 'gt':
				$_arg = '>';
				break;
			case 'ge':
			case 'gte':
				$_arg = '>=';
				break;
			case 'and':
				$_arg = '&&';
				break;
			case 'or':
				$_arg = '||';
				break;
			case 'not':
				$_arg = '!';
				break;
			case 'mod':
				$_arg = '%';
				break;
			case '(':
				array_push($_is_arg_stack, $i);
				break;
			case 'is':
				if ($_args[$i-1] == ')')
				{
					$is_arg_start = array_pop($is_arg_stack);
				}
				else
				{
					$_is_arg_count = count($_args);
					$is_arg = implode(' ', array_slice($_args, $is_arg_start, $i - $is_arg_start));
					$_arg_tokens = $object->_parse_is_expr($is_arg, array_slice($_args, $i+1));
					array_splice($_args, $is_arg_start, count($_args), $_arg_tokens);
					$i = $_is_arg_count - count($_args);
				}
				break;
			default:
				preg_match('/(?:(' . $object->_var_regexp . '|' . $object->_svar_regexp . '|' . $object->_func_regexp . ')(' . $object->_mod_regexp . '*)(?:\s*[,\.]\s*)?)(?:\s+(.*))?/xs', $_arg, $_match);
				if (isset($_match[0]{0}) && ($_match[0]{0} == '$' || ($_match[0]{0} == '#' && $_match[0]{strlen($_match[0]) - 1} == '#') || $_match[0]{0} == "'" || $_match[0]{0} == '"' || $_match[0]{0} == '%'))
				{
					// process a variable
					$_arg = $object->_parse_variables(array($_match[1]), array($_match[2]));
				}
				elseif (is_numeric($_arg))
				{
					// pass the number through
				}
				elseif (function_exists($_match[0]) || $_match[0] == "empty" || $_match[0] == "isset" || $_match[0] == "unset" || strtolower($_match[0]) == "true" || strtolower($_match[0]) == "false" || strtolower($_match[0]) == "null")
				{
					// pass the function through
				}
				elseif (empty($_arg))
				{
					// pass the empty argument through
				}
				else
				{
					$object->trigger_error("unidentified token '$_arg'", E_USER_ERROR, __FILE__, __LINE__);
				}
				break;
		}
	}

	if($while)
	{
		return implode(' ', $_args);
	}
	else
	{
		if ($elseif)
		{
			return '<?php elseif ('.implode(' ', $_args).'): ?>';
		}
		else
		{
			return '<?php if ('.implode(' ', $_args).'): ?>';
		}
	}
	return $_result;
}

?>