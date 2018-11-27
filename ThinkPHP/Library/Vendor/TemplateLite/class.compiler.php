<?php
/*
 * Project:	template_lite, a smarter template engine
 * File:	class.compiler.php
 * Author:	Paul Lockaby <paul@paullockaby.com>, Mark Dickenson <akapanamajack@sourceforge.net>
 * Copyright:	2003,2004,2005 by Paul Lockaby, 2005,2006 Mark Dickenson
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * The latest version of template_lite can be obtained from:
 * http://templatelite.sourceforge.net
 *
 */

class Template_Lite_Compiler extends Template_Lite {
	// public configuration variables
	var $left_delimiter			= "";
	var $right_delimiter			= "";
	var $plugins_dir			= "";
	var $template_dir		= "";
	var $reserved_template_varname = "";
	var $default_modifiers		= array();

	var $php_extract_vars		=	true;	// Set this to false if you do not want the $this->_tpl variables to be extracted for use by PHP code inside the template.

	// private internal variables
	var $_vars			=	array();	// stores all internal assigned variables
	var $_confs			=	array();	// stores all internal config variables
	var $_plugins			=	array();	// stores all internal plugins
	var $_linenum			=	0;		// the current line number in the file we are processing
	var $_file			=	"";		// the current file we are processing
	var $_literal			=	array();	// stores all literal blocks
	var $_foreachelse_stack		=	array();
	var $_for_stack			=	0;
	var $_sectionelse_stack	 =   array();	// keeps track of whether section had 'else' part
	var $_switch_stack		=	array();
	var $_tag_stack			=	array();
	var $_require_stack		=	array();	// stores all files that are "required" inside of the template
	var $_php_blocks		=	array();	// stores all of the php blocks
	var $_error_level		=	null;
	var $_sl_md5			=	'39fc70570b8b60cbc1b85839bf242aff';

	var $_db_qstr_regexp		=	null;		// regexps are setup in the constructor
	var $_si_qstr_regexp		=	null;
	var $_qstr_regexp		=	null;
	var $_func_regexp		=	null;
	var $_var_bracket_regexp	=	null;
	var $_dvar_regexp		=	null;
	var $_cvar_regexp		=	null;
	var $_svar_regexp		=	null;
	var $_mod_regexp		=	null;
	var $_var_regexp		=	null;
    var $_obj_params_regexp     =   null;
	var $_templatelite_vars		=	array();

	function Template_Lite_compiler()
	{
		// matches double quoted strings:
		// "foobar"
		// "foo\"bar"
		// "foobar" . "foo\"bar"
		$this->_db_qstr_regexp = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';

		// matches single quoted strings:
		// 'foobar'
		// 'foo\'bar'
		$this->_si_qstr_regexp = '\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'';

		// matches single or double quoted strings
		$this->_qstr_regexp = '(?:' . $this->_db_qstr_regexp . '|' . $this->_si_qstr_regexp . ')';

		// matches bracket portion of vars
		// [0]
		// [foo]
		// [$bar]
		// [#bar#]
		$this->_var_bracket_regexp = '\[[\$|\#]?\w+\#?\]';
//		$this->_var_bracket_regexp = '\[\$?[\w\.]+\]';

		// matches section vars:
		// %foo.bar%
		$this->_svar_regexp = '\%\w+\.\w+\%';

		// matches $ vars (not objects):
		// $foo
		// $foo[0]
		// $foo[$bar]
		// $foo[5][blah]
//		$this->_dvar_regexp = '\$[a-zA-Z0-9_]{1,}(?:' . $this->_var_bracket_regexp . ')*(?:' . $this->_var_bracket_regexp . ')*';
		$this->_dvar_regexp = '\$[a-zA-Z0-9_]{1,}(?:' . $this->_var_bracket_regexp . ')*(?:\.\$?\w+(?:' . $this->_var_bracket_regexp . ')*)*';

		// matches config vars:
		// #foo#
		// #foobar123_foo#
		$this->_cvar_regexp = '\#[a-zA-Z0-9_]{1,}(?:' . $this->_var_bracket_regexp . ')*(?:' . $this->_var_bracket_regexp . ')*\#';

		// matches valid variable syntax:
		// $foo
		// 'text'
		// "text"
		$this->_var_regexp = '(?:(?:' . $this->_dvar_regexp . '|' . $this->_cvar_regexp . ')|' . $this->_qstr_regexp . ')';

		// matches valid modifier syntax:
		// |foo
		// |@foo
		// |foo:"bar"
		// |foo:$bar
		// |foo:"bar":$foobar
		// |foo|bar
		$this->_mod_regexp = '(?:\|@?[0-9a-zA-Z_]+(?::(?>-?\w+|' . $this->_dvar_regexp . '|' . $this->_qstr_regexp .'))*)';		

		// matches valid function name:
		// foo123
		// _foo_bar
		$this->_func_regexp = '[a-zA-Z_]+';
//		$this->_func_regexp = '[a-zA-Z_]\w*';

	}

	function _compile_file($file_contents)
	{
		$ldq = preg_quote($this->left_delimiter);
		$rdq = preg_quote($this->right_delimiter);
		$_match		= array();		// a temp variable for the current regex match
		$tags		= array();		// all original tags
		$text		= array();		// all original text
		$compiled_text	= '<?php /* '.$this->_version.' '.strftime("%Y-%m-%d %H:%M:%S %Z").' */ ?>'."\n\n"; // stores the compiled result
		$compiled_tags	= array();		// all tags and stuff

		$this->_require_stack = array();

		$this->_load_filters();

		if (count($this->_plugins['prefilter']) > 0)
		{
			foreach ($this->_plugins['prefilter'] as $function)
			{
				if ($function === false)
				{
					continue;
				}
				$file_contents = $function($file_contents, $this);
			}
		}

		// remove all comments
		$file_contents = preg_replace("!{$ldq}\*.*?\*{$rdq}!se","",$file_contents);

		// replace all php start and end tags
		$file_contents = preg_replace('%(<\?(?!php|=|$))%i', '<?php echo \'\\1\'?>'."\n", $file_contents);

		// remove literal blocks
		preg_match_all("!{$ldq}\s*literal\s*{$rdq}(.*?){$ldq}\s*/literal\s*{$rdq}!s", $file_contents, $_match);
		$this->_literal = $_match[1];
		$file_contents = preg_replace("!{$ldq}\s*literal\s*{$rdq}(.*?){$ldq}\s*/literal\s*{$rdq}!s", stripslashes($ldq . "literal" . $rdq), $file_contents);

		// remove php blocks
		preg_match_all("!{$ldq}\s*php\s*{$rdq}(.*?){$ldq}\s*/php\s*{$rdq}!s", $file_contents, $_match);
		$this->_php_blocks = $_match[1];
		$file_contents = preg_replace("!{$ldq}\s*php\s*{$rdq}(.*?){$ldq}\s*/php\s*{$rdq}!s", stripslashes($ldq . "php" . $rdq), $file_contents);

		// gather all template tags
		preg_match_all("!{$ldq}\s*(.*?)\s*{$rdq}!s", $file_contents, $_match);
		$tags = $_match[1];

		// put all of the non-template tag text blocks into an array, using the template tags as delimiters
		$text = preg_split("!{$ldq}.*?{$rdq}!s", $file_contents);

		// compile template tags
		$count_tags = count($tags);
		for ($i = 0, $for_max = $count_tags; $i < $for_max; $i++)
		{
			$this->_linenum += substr_count($text[$i], "\n");
			$compiled_tags[] = $this->_compile_tag($tags[$i]);
			$this->_linenum += substr_count($tags[$i], "\n");
		}

		// build the compiled template by replacing and interleaving text blocks and compiled tags
		$count_compiled_tags = count($compiled_tags);
		for ($i = 0, $for_max = $count_compiled_tags; $i < $for_max; $i++)
		{
			if ($compiled_tags[$i] == '') {
				$text[$i+1] = preg_replace('~^(\r\n|\r|\n)~', '', $text[$i+1]);
			}
			$compiled_text .= $text[$i].$compiled_tags[$i];
		}
		$compiled_text .= $text[$i];

		foreach ($this->_require_stack as $key => $value)
		{
			$compiled_text = '<?php require_once(\''. $this->_get_plugin_dir($key) . $key . '\'); $this->register_' . $value[0] . '("' . $value[1] . '", "' . $value[2] . '"); ?>' . $compiled_text;
		}

		// remove unnecessary close/open tags
		$compiled_text = preg_replace('!\?>\n?<\?php!', '', $compiled_text);

		if (count($this->_plugins['postfilter']) > 0)
		{
			foreach ($this->_plugins['postfilter'] as $function)
			{
				if ($function === false)
				{
					continue;
				}
				$compiled_text = $function($compiled_text, $this);
			}
		}

		return $compiled_text;
	}

	function _compile_tag($tag)
	{
		$_match		= array();		// stores the tags
		$_result	= "";			// the compiled tag
		$_variable	= "";			// the compiled variable

		// extract the tag command, modifier and arguments
		preg_match_all('/(?:(' . $this->_var_regexp . '|' . $this->_svar_regexp . '|\/?' . $this->_func_regexp . ')(' . $this->_mod_regexp . '*)(?:\s*[,\.]\s*)?)(?:\s+(.*))?/xs', $tag, $_match);

		if ($_match[1][0]{0} == '$' || ($_match[1][0]{0} == '#' && $_match[1][0]{strlen($_match[1][0]) - 1} == '#') || $_match[1][0]{0} == "'" || $_match[1][0]{0} == '"' || $_match[1][0]{0} == '%')
		{
			$_result = $this->_parse_variables($_match[1], $_match[2]);
			return "<?php echo $_result; ?>\n";
		}
		// process a function
		$tag_command = $_match[1][0];
		$tag_modifiers = !empty($_match[2][0]) ? $_match[2][0] : null;
		$tag_arguments = !empty($_match[3][0]) ? $_match[3][0] : null;
		$_result = $this->_parse_function($tag_command, $tag_modifiers, $tag_arguments);
		return $_result;
	}

	function _parse_function($function, $modifiers, $arguments)
	{
		switch ($function) {
			case 'include':
				if (!function_exists('compile_include'))
				{
					require_once(TEMPLATE_LITE_DIR . "internal/compile.include.php");
				}
				return compile_include($arguments, $this);
				break;
			case 'insert':
				$_args = $this->_parse_arguments($arguments);
				if (!isset($_args['name']))
				{
					$this->trigger_error("missing 'name' attribute in 'insert'", E_USER_ERROR, __FILE__, __LINE__);
				}
				foreach ($_args as $key => $value)
				{
					if (is_bool($value))
					{
						$value = $value ? 'true' : 'false';
					}
					$arg_list[] = "'$key' => $value";
				}
				return '<?php echo $this->_run_insert(array(' . implode(', ', (array)$arg_list) . ')); ?>';
				break;
			case 'ldelim':
				return $this->left_delimiter;
				break;
			case 'rdelim':
				return $this->right_delimiter;
				break;
			case 'literal':
				list (,$literal) = each($this->_literal);
				$this->_linenum += substr_count($literal, "\n");
				return "<?php echo '" . str_replace("'", "\'", str_replace("\\", "\\\\", $literal)) . "'; ?>\n";
				break;
			case 'php':
				list (,$php_block) = each($this->_php_blocks);
				$this->_linenum += substr_count($php_block, "\n");
				$php_extract = '';
				if($this->php_extract_vars)
				{
					if (strnatcmp(PHP_VERSION, '4.3.0') >= 0)
					{
						$php_extract = '<?php extract($this->_vars, EXTR_REFS); ?>' . "\n";
					}
					else
					{
						$php_extract = '<?php extract($this->_vars); ?>' . "\n";
					}
				}
				return $php_extract . '<?php '.$php_block.' ?>';
				break;
			case 'foreach':
				array_push($this->_foreachelse_stack, false);
				$_args = $this->_parse_arguments($arguments);
				if (!isset($_args['from']))
				{
					$this->trigger_error("missing 'from' attribute in 'foreach'", E_USER_ERROR, __FILE__, __LINE__);
				}
				if (!isset($_args['value']) && !isset($_args['item']))
				{
					$this->trigger_error("missing 'value' attribute in 'foreach'", E_USER_ERROR, __FILE__, __LINE__);
				}
				if (isset($_args['value']))
				{
					$_args['value'] = $this->_dequote($_args['value']);
				}
				elseif (isset($_args['item']))
				{
					$_args['value'] = $this->_dequote($_args['item']);
				}
				isset($_args['key']) ? $_args['key'] = "\$this->_vars['".$this->_dequote($_args['key'])."'] => " : $_args['key'] = '';
				$_result = '<?php if (count((array)' . $_args['from'] . ')): foreach ((array)' . $_args['from'] . ' as ' . $_args['key'] . '$this->_vars[\'' . $_args['value'] . '\']): ?>';
				return $_result;
				break;
			case 'foreachelse':
				$this->_foreachelse_stack[count($this->_foreachelse_stack)-1] = true;
				return "<?php endforeach; else: ?>";
				break;
			case '/foreach':
				if (array_pop($this->_foreachelse_stack))
				{
					return "<?php endif; ?>";
				}
				else
				{
					return "<?php endforeach; endif; ?>";
				}
				break;
			case 'for':
				$this->_for_stack++;
				$_args = $this->_parse_arguments($arguments);
				if (!isset($_args['start']))
				{
					$this->trigger_error("missing 'start' attribute in 'for'", E_USER_ERROR, __FILE__, __LINE__);
				}
				if (!isset($_args['stop']))
				{
					$this->trigger_error("missing 'stop' attribute in 'for'", E_USER_ERROR, __FILE__, __LINE__);
				}
				if (!isset($_args['step']))
				{
					$_args['step'] = 1;
				}
				$_result = '<?php for($for' . $this->_for_stack . ' = ' . $_args['start'] . '; ((' . $_args['start'] . ' < ' . $_args['stop'] . ') ? ($for' . $this->_for_stack . ' < ' . $_args['stop'] . ') : ($for' . $this->_for_stack . ' > ' . $_args['stop'] . ')); $for' . $this->_for_stack . ' += ((' . $_args['start'] . ' < ' . $_args['stop'] . ') ? ' . $_args['step'] . ' : -' . $_args['step'] . ')): ?>';
				if (isset($_args['value']))
				{
					$_result .= '<?php $this->assign(\'' . $this->_dequote($_args['value']) . '\', $for' . $this->_for_stack . '); ?>';
				}
				return $_result;
				break;
			case '/for':
				$this->_for_stack--;
				return "<?php endfor; ?>";
				break;
			case 'section':
				array_push($this->_sectionelse_stack, false);
				if (!function_exists('compile_section_start'))
				{
					require_once(TEMPLATE_LITE_DIR . "internal/compile.section_start.php");
				}
				return compile_section_start($arguments, $this);
				break;
			case 'sectionelse':
				$this->_sectionelse_stack[count($this->_sectionelse_stack)-1] = true;
				return "<?php endfor; else: ?>";
				break;
			case '/section':
				if (array_pop($this->_sectionelse_stack))
				{
					return "<?php endif; ?>";
				}
				else
				{
					return "<?php endfor; endif; ?>";
				}
				break;
			case 'while':
				$_args = $this->_compile_if($arguments, false, true);
				return '<?php while(' . $_args . '): ?>';
				break;
			case '/while':
				return "<?php endwhile; ?>";
				break;
			case 'if':
				return $this->_compile_if($arguments);
				break;
			case 'else':
				return "<?php else: ?>";
				break;
			case 'elseif':
				return $this->_compile_if($arguments, true);
				break;
			case '/if':
				return "<?php endif; ?>";
				break;
			case 'assign':
				$_args = $this->_parse_arguments($arguments);
				if (!isset($_args['var']))
				{
					$this->trigger_error("missing 'var' attribute in 'assign'", E_USER_ERROR, __FILE__, __LINE__);
				}
				if (!isset($_args['value']))
				{
					$this->trigger_error("missing 'value' attribute in 'assign'", E_USER_ERROR, __FILE__, __LINE__);
				}
				return '<?php $this->assign(\'' . $this->_dequote($_args['var']) . '\', ' . $_args['value'] . '); ?>';
				break;
			case 'switch':
				$_args = $this->_parse_arguments($arguments);
				if (!isset($_args['from']))
				{
					$this->trigger_error("missing 'from' attribute in 'switch'", E_USER_ERROR, __FILE__, __LINE__);
				}
				array_push($this->_switch_stack, array("matched" => false, "var" => $this->_dequote($_args['from'])));
				return;
				break;
			case '/switch':
				array_pop($this->_switch_stack);
				return '<?php break; endswitch; ?>';
				break;
			case 'case':
				if (count($this->_switch_stack) > 0)
				{
					$_result = "<?php ";
					$_args = $this->_parse_arguments($arguments);
					$_index = count($this->_switch_stack) - 1;
					if (!$this->_switch_stack[$_index]["matched"])
					{
						$_result .= 'switch(' . $this->_switch_stack[$_index]["var"] . '): ';
						$this->_switch_stack[$_index]["matched"] = true;
					}
					else
					{
						$_result .= 'break; ';
					}
					if (!empty($_args['value']))
					{
						$_result .= 'case '.$_args['value'].': ';
					}
					else
					{
						$_result .= 'default: ';
					}
					return $_result . ' ?>';
				}
				else
				{
					$this->trigger_error("unexpected 'case', 'case' can only be in a 'switch'", E_USER_ERROR, __FILE__, __LINE__);
				}
				break;
			case 'config_load':
				$_args = $this->_parse_arguments($arguments);
				if (empty($_args['file']))
				{
					$this->trigger_error("missing 'file' attribute in 'config_load' tag", E_USER_ERROR, __FILE__, __LINE__);
				}
				isset($_args['section']) ? null : $_args['section'] = 'null';
				isset($_args['var']) ? null : $_args['var'] = 'null';
				return '<?php $this->config_load(' . $_args['file'] . ', ' . $_args['section'] . ', ' . $_args['var'] . '); ?>';
				break;
			default:
				$_result = "";
				if ($this->_compile_compiler_function($function, $arguments, $_result))
				{
					return $_result;
				}
				else if ($this->_compile_custom_block($function, $modifiers, $arguments, $_result))
				{
					return $_result;
				}
				elseif ($this->_compile_custom_function($function, $modifiers, $arguments, $_result))
				{
					return $_result;
				}
				else
				{
					$this->trigger_error($function." function does not exist", E_USER_ERROR, __FILE__, __LINE__);
				}
				break;
		}
	}

	function _compile_compiler_function($function, $arguments, &$_result)
	{
		if ($function = $this->_plugin_exists($function, "compiler"))
		{
			$_args = $this->_parse_arguments($arguments);
			$_result = '<?php ' . $function($_args, $this) . ' ?>';
			return true;
		}
		else
		{
			return false;
		}
	}

	function _compile_custom_function($function, $modifiers, $arguments, &$_result)
	{
		if (!function_exists('compile_compile_custom_function'))
		{
			require_once(TEMPLATE_LITE_DIR . "internal/compile.compile_custom_function.php");
		}
		return compile_compile_custom_function($function, $modifiers, $arguments, $_result, $this);
	}

	function _compile_custom_block($function, $modifiers, $arguments, &$_result)
	{
		if (!function_exists('compile_compile_custom_block'))
		{
			require_once(TEMPLATE_LITE_DIR . "internal/compile.compile_custom_block.php");
		}
		return compile_compile_custom_block($function, $modifiers, $arguments, $_result, $this);
	}

	function _compile_if($arguments, $elseif = false, $while = false)
	{
		if (!function_exists('compile_compile_if'))
		{
			require_once(TEMPLATE_LITE_DIR . "internal/compile.compile_if.php");
		}
		return compile_compile_if($arguments, $elseif, $while, $this);
	}

	function _parse_is_expr($is_arg, $_arg)
	{
		if (!function_exists('compile_parse_is_expr'))
		{
			require_once(TEMPLATE_LITE_DIR . "internal/compile.parse_is_expr.php");
		}
		return compile_parse_is_expr($is_arg, $_arg, $this);
	}

	function _compile_config($variable)
	{
		if (!function_exists('compile_compile_config'))
		{
			require_once(TEMPLATE_LITE_DIR . "internal/compile.compile_config.php");
		}
		return compile_compile_config($variable, $this);
	}

	function _dequote($string)
	{
		if (($string{0} == "'" || $string{0} == '"') && $string{strlen($string)-1} == $string{0})
		{
			return substr($string, 1, -1);
		}
		else
		{
			return $string;
		}
	}

	function _parse_arguments($arguments)
	{
		$_match		= array();
		$_result	= array();
		$_variables	= array();
		preg_match_all('/(?:' . $this->_qstr_regexp . ' | (?>[^"\'=\s]+))+|[=]/x', $arguments, $_match);
		/*
		   Parse state:
			 0 - expecting attribute name
			 1 - expecting '='
			 2 - expecting attribute value (not '=')
		*/
		$state = 0;
		foreach($_match[0] as $value)
		{
			switch($state) {
				case 0:
					// valid attribute name
					if (is_string($value))
					{
						$a_name = $value;
						$state = 1;
					}
					else
					{
						$this->trigger_error("invalid attribute name: '$token'", E_USER_ERROR, __FILE__, __LINE__);
					}
					break;
				case 1:
					if ($value == '=')
					{
						$state = 2;
					}
					else
					{
						$this->trigger_error("expecting '=' after '$last_value'", E_USER_ERROR, __FILE__, __LINE__);
					}
					break;
				case 2:
					if ($value != '=')
					{
						if ($value == 'yes' || $value == 'on' || $value == 'true')
						{
							$value = true;
						}
						elseif ($value == 'no' || $value == 'off' || $value == 'false')
						{
							$value = false;
						}
						elseif ($value == 'null')
						{
							$value = null;
						}

						if(!preg_match_all('/(?:(' . $this->_var_regexp . '|' . $this->_svar_regexp . ')(' . $this->_mod_regexp . '*))(?:\s+(.*))?/xs', $value, $_variables))
						{
							$_result[$a_name] = $value;
						}
						else
						{
							$_result[$a_name] = $this->_parse_variables($_variables[1], $_variables[2]);
						}
						$state = 0;
					}
					else
					{
						$this->trigger_error("'=' cannot be an attribute value", E_USER_ERROR, __FILE__, __LINE__);
					}
					break;
			}
			$last_value = $value;
		}
		if($state != 0)
		{
			if($state == 1)
			{
				$this->trigger_error("expecting '=' after attribute name '$last_value'", E_USER_ERROR, __FILE__, __LINE__);
			}
			else
			{
				$this->trigger_error("missing attribute value", E_USER_ERROR, __FILE__, __LINE__);
			}
		}
		return $_result;
	}

	function _parse_variables($variables, $modifiers)
	{
		$_result = "";
		foreach($variables as $key => $value)
		{
			$tag_variable = trim($variables[$key]);
			if(!empty($this->default_modifiers) && !preg_match('!(^|\|)templatelite:nodefaults($|\|)!',$modifiers[$key]))
			{
				$_default_mod_string = implode('|',(array)$this->default_modifiers);
				$modifiers[$key] = empty($modifiers[$key]) ? $_default_mod_string : $_default_mod_string . '|' . $modifiers[$key];
			}
			if (empty($modifiers[$key]))
			{
				$_result .= $this->_parse_variable($tag_variable).'.';
			}
			else
			{
				$_result .= $this->_parse_modifier($this->_parse_variable($tag_variable), $modifiers[$key]).'.';
			}
		}
		return substr($_result, 0, -1);
	}

	function _parse_variable($variable)
	{
		// replace variable with value
		if ($variable{0} == "\$")
		{
			// replace the variable
			return $this->_compile_variable($variable);
		}
		elseif ($variable{0} == '#')
		{
			// replace the config variable
			return $this->_compile_config($variable);
		}
		elseif ($variable{0} == '"')
		{
			// expand the quotes to pull any variables out of it
			// fortunately variables inside of a quote aren't fancy, no modifiers, no quotes
			//   just get everything from the $ to the ending space and parse it
			// if the $ is escaped, then we won't expand it
			$_result = "";
			preg_match_all('/(?:[^\\\]' . $this->_dvar_regexp . ')/', substr($variable, 1, -1), $_expand);  // old match 
//			preg_match_all('/(?:[^\\\]' . $this->_dvar_regexp . '[^\\\])/', $variable, $_expand);
			$_expand = array_unique($_expand[0]);
			foreach($_expand as $key => $value)
			{
				$_expand[$key] = trim($value);
				if (strpos($_expand[$key], '$') > 0)
				{
					$_expand[$key] = substr($_expand[$key], strpos($_expand[$key], '$'));
				}
			}
			$_result = $variable;
			foreach($_expand as $value)
			{
				$value = trim($value);
				$_result = str_replace($value, '" . ' . $this->_parse_variable($value) . ' . "', $_result);
			}
			$_result = str_replace("`", "", $_result);
			return $_result;
		}
		elseif ($variable{0} == "'")
		{
			// return the value just as it is
			return $variable;
		}
		elseif ($variable{0} == "%")
		{
			return $this->_parse_section_prop($variable);
		}
		else
		{
			// return it as is; i believe that there was a reason before that i did not just return it as is,
			// but i forgot what that reason is ...
			// the reason i return the variable 'as is' right now is so that unquoted literals are allowed
			return $variable;
		}
	}

	function _parse_section_prop($section_prop_expr)
	{
		$parts = explode('|', $section_prop_expr, 2);
		$var_ref = $parts[0];
		$modifiers = isset($parts[1]) ? $parts[1] : '';

		preg_match('!%(\w+)\.(\w+)%!', $var_ref, $match);
		$section_name = $match[1];
		$prop_name = $match[2];

		$output = "\$this->_sections['$section_name']['$prop_name']";

		$this->_parse_modifier($output, $modifiers);

		return $output;
	}

	function _compile_variable($variable)
	{
		$_result	= "";

		// remove the $
		$variable = substr($variable, 1);

		// get [foo] and .foo and (...) pieces
		preg_match_all('!(?:^\w+)|(?:' . $this->_var_bracket_regexp . ')|\.\$?\w+|\S+!', $variable, $_match);
		$variable = $_match[0];
		$var_name = array_shift($variable);

		if ($var_name == $this->reserved_template_varname)
		{
			if ($variable[0]{0} == '[' || $variable[0]{0} == '.')
			{
				$find = array("[", "]", ".");
				switch(strtoupper(str_replace($find, "", $variable[0])))
				{
					case 'GET':
						$_result = "\$_GET";
						break;
					case 'POST':
						$_result = "\$_POST";
						break;
					case 'COOKIE':
						$_result = "\$_COOKIE";
						break;
					case 'ENV':
						$_result = "\$_ENV";
						break;
					case 'SERVER':
						$_result = "\$_SERVER";
						break;
					case 'SESSION':
						$_result = "\$_SESSION";
						break;
					case 'NOW':
						$_result = "time()";
						break;
					case 'SECTION':
						$_result = "\$this->_sections";
						break;
					case 'LDELIM':
						$_result = "\$this->left_delimiter";
						break;
					case 'RDELIM':
						$_result = "\$this->right_delimiter";
						break;
					case 'VERSION':
						$_result = "\$this->_version";
						break;
					case 'CONFIG':
						$_result = "\$this->_confs";
						break;
					case 'TEMPLATE':
						$_result = "\$this->_file";
						break;
					case 'CONST':
						$constant = str_replace($find, "", $_match[0][2]);
						$_result = "constant('$constant')";
						$variable = array();
						break;
					default:
						$_var_name = str_replace($find, "", $variable[0]);
						$_result = "\$this->_templatelite_vars['$_var_name']";
						break;
				}
				array_shift($variable);
			}
			else
			{
				$this->trigger_error('$' . $var_name.implode('', $variable) . ' is an invalid $templatelite reference', E_USER_ERROR, __FILE__, __LINE__);
			}
		}
		else
		{
			$_result = "\$this->_vars['$var_name']";
		}

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
					$_result .= "[" . $this->_compile_variable($var) . "]";
				}
				elseif ($var{0} == '#')
				{
					$_result .= "[" . $this->_compile_config($var) . "]";
				}
				else
				{
//					$_result .= "['$var']";
					$parts = explode('.', $var);
					$section = $parts[0];
					$section_prop = isset($parts[1]) ? $parts[1] : 'index';
					$_result .= "[\$this->_sections['$section']['$section_prop']]";
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
					$this->trigger_error('call to internal object members is not allowed', E_USER_ERROR, __FILE__, __LINE__);
				}
				else if (substr($var, 2, 1) == '$')
				{
					$_output .= '->{(($var=$this->_TPL[\''.substr($var,3).'\']) && substr($var,0,2)!=\'__\') ? $_var : $this->trigger_error("cannot access property \\"$var\\"")}';
				}
			}
			else
			{
				$this->trigger_error('$' . $var_name.implode('', $variable) . ' is an invalid reference', E_USER_ERROR, __FILE__, __LINE__);
			}
		}
		return $_result;
	}

	function _parse_modifier($variable, $modifiers)
	{
		$_match		= array();
		$_mods		= array();		// stores all modifiers
		$_args		= array();		// modifier arguments

		preg_match_all('!\|(@?\w+)((?>:(?:'. $this->_qstr_regexp . '|[^|]+))*)!', '|' . $modifiers, $_match);
		list(, $_mods, $_args) = $_match;

		$count_mods = count($_mods);
		for ($i = 0, $for_max = $count_mods; $i < $for_max; $i++)
		{
			preg_match_all('!:(' . $this->_qstr_regexp . '|[^:]+)!', $_args[$i], $_match);
			$_arg = $_match[1];

			if ($_mods[$i]{0} == '@')
			{
				$_mods[$i] = substr($_mods[$i], 1);
				$_map_array = 0;
			} else {
				$_map_array = 1;
			}

			foreach($_arg as $key => $value)
			{
				$_arg[$key] = $this->_parse_variable($value);
			}

			if ($this->_plugin_exists($_mods[$i], "modifier") || function_exists($_mods[$i]))
			{
				if (count($_arg) > 0)
				{
					$_arg = ', '.implode(', ', $_arg);
				}
				else
				{
					$_arg = '';
				}

				$php_function = "PHP";
				if ($this->_plugin_exists($_mods[$i], "modifier"))
				{
					$php_function = "plugin";
				}
				$variable = "\$this->_run_modifier($variable, '$_mods[$i]', '$php_function', $_map_array$_arg)";
			}
			else
			{
				$variable = "\$this->trigger_error(\"'" . $_mods[$i] . "' modifier does not exist\", E_USER_NOTICE, __FILE__, __LINE__);";
			}
		}
		return $variable;
	}

	function _plugin_exists($function, $type)
	{
		// check for object functions
		if (isset($this->_plugins[$type][$function]) && is_array($this->_plugins[$type][$function]) && is_object($this->_plugins[$type][$function][0]) && method_exists($this->_plugins[$type][$function][0], $this->_plugins[$type][$function][1]))
		{
			return '$this->_plugins[\'' . $type . '\'][\'' . $function . '\'][0]->' . $this->_plugins[$type][$function][1];
		}
		// check for standard functions
		if (isset($this->_plugins[$type][$function]) && function_exists($this->_plugins[$type][$function]))
		{
			return $this->_plugins[$type][$function];
		}
		// check for a plugin in the plugin directory
		if (file_exists($this->_get_plugin_dir($type . '.' . $function . '.php') . $type . '.' . $function . '.php'))
		{
			require_once($this->_get_plugin_dir($type . '.' . $function . '.php') . $type . '.' . $function . '.php');
			if (function_exists('tpl_' . $type . '_' . $function))
			{
				$this->_require_stack[$type . '.' . $function . '.php'] = array($type, $function, 'tpl_' . $type . '_' . $function);
				return ('tpl_' . $type . '_' . $function);
			}
		}
		return false;
	}

	function _load_filters()
	{
		if (count($this->_plugins['prefilter']) > 0)
		{
			foreach ($this->_plugins['prefilter'] as $filter_name => $prefilter)
			{
				if (!function_exists($prefilter))
				{
					@include_once( $this->_get_plugin_dir("prefilter." . $filter_name . ".php") . "prefilter." . $filter_name . ".php");
				}
			}
		}
		if (count($this->_plugins['postfilter']) > 0)
		{
			foreach ($this->_plugins['postfilter'] as $filter_name => $postfilter)
			{
				if (!function_exists($postfilter))
				{
					@include_once( $this->_get_plugin_dir("postfilter." . $filter_name . ".php") . "postfilter." . $filter_name . ".php");
				}
			}
		}
	}
}

?>
