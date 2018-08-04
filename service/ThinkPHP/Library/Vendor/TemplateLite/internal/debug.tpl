{* templatelite debug console *}

{if isset($_templatelite_debug_output) and $_templatelite_debug_output eq "html"}
	<table border=0 width=100%>
	<tr bgcolor=#cccccc><th colspan=2>Template Lite Debug Console</th></tr>
	<tr bgcolor=#cccccc><td colspan=2><b>Included templates & config files (load time in seconds):</b></td></tr>
	{foreach key=key value=templates from=$_debug_tpls}
		<tr bgcolor={if $key % 2}#eeeeee{else}#fafafa{/if}>
		<td colspan=2><tt>{for start=0 stop=$_debug_tpls[$key].depth}&nbsp;&nbsp;&nbsp;{/for}
		<font color={if $_debug_tpls[$key].type eq "template"}brown{elseif $_debug_tpls[$key].type eq "insert"}black{else}green{/if}>
		{$_debug_tpls[$key].filename}</font>{if isset($_debug_tpls[$key].exec_time)} 
		<font size=-1><i>({$_debug_tpls[$key].exec_time|string_format:"%.5f"} seconds){if $key eq 0} (total){/if}
		</i></font>{/if}</tt></td></tr>
	{foreachelse}
		<tr bgcolor=#eeeeee><td colspan=2><tt><i>No template assigned</i></tt></td></tr>	
	{/foreach}
	<tr bgcolor=#cccccc><td colspan=2><b>Assigned template variables:</b></td></tr>
	{foreach key=key value=vars from=$_debug_keys}
		<tr bgcolor={if $key % 2}#eeeeee{else}#fafafa{/if}>
		<td valign=top><tt><font color=blue>{ldelim}${$_debug_keys[$key]}{rdelim}</font></tt></td>
		<td nowrap><tt><font color=green>{$_debug_vals[$key]|@debug_print_var}</font></tt></td></tr>
	{foreachelse}
		<tr bgcolor=#eeeeee><td colspan=2><tt><i>No template variables assigned</i></tt></td></tr>	
	{/foreach}
	<tr bgcolor=#cccccc><td colspan=2><b>Assigned config file variables (outer template scope):</b></td></tr>
	{foreach key=key value=config_vars from=$_debug_config_keys}
		<tr bgcolor={if $key % 2}#eeeeee{else}#fafafa{/if}>
		<td valign=top><tt><font color=maroon>{ldelim}#{$_debug_config_keys[$key]}#{rdelim}</font></tt></td>
		<td><tt><font color=green>{$_debug_config_vals[$key]|@debug_print_var}</font></tt></td></tr>
	{foreachelse}
		<tr bgcolor=#eeeeee><td colspan=2><tt><i>No config vars assigned</i></tt></td></tr>	
	{/foreach}
	</table>
{else}
<SCRIPT language=javascript>
	if( self.name == '' ) {ldelim}
	   var title = 'Console';
	{rdelim}
	else {ldelim}
	   var title = 'Console_' + self.name;
	{rdelim}
	_templatelite_console = window.open("",title.value,"width=680,height=600,resizable,scrollbars=yes");
	_templatelite_console.document.write("<HTML><TITLE>Template Lite Debug Console_"+self.name+"</TITLE><BODY bgcolor=#ffffff>");
	_templatelite_console.document.write("<table border=0 width=100%>");
	_templatelite_console.document.write("<tr bgcolor=#cccccc><th colspan=2>Template Lite Debug Console</th></tr>");
	_templatelite_console.document.write("<tr bgcolor=#cccccc><td colspan=2><b>Included templates & config files (load time in seconds):</b></td></tr>");
	{foreach key=key value=templates from=$_debug_tpls}
		_templatelite_console.document.write("<tr bgcolor={if $key % 2}#eeeeee{else}#fafafa{/if}>");
		_templatelite_console.document.write("<td colspan=2><tt>{for start=0 stop=$_debug_tpls[$key].depth}&nbsp;&nbsp;&nbsp;{/for}");
		_templatelite_console.document.write("<font color={if $_debug_tpls[$key].type eq "template"}brown{elseif $_debug_tpls[$key].type eq "insert"}black{else}green{/if}>");
		_templatelite_console.document.write("{$_debug_tpls[$key].filename}</font>{if isset($_debug_tpls[$key].exec_time)} ");
		_templatelite_console.document.write("<font size=-1><i>({$_debug_tpls[$key].exec_time|string_format:"%.5f"} seconds){if $key eq 0} (total){/if}");
		_templatelite_console.document.write("</i></font>{/if}</tt></td></tr>");
	{foreachelse}
		_templatelite_console.document.write("<tr bgcolor=#eeeeee><td colspan=2><tt><i>No template assigned</i></tt></td></tr>	");
	{/foreach}
	_templatelite_console.document.write("<tr bgcolor=#cccccc><td colspan=2><b>Assigned template variables:</b></td></tr>");
	{foreach key=key value=vars from=$_debug_keys}
		_templatelite_console.document.write("<tr bgcolor={if $key % 2}#eeeeee{else}#fafafa{/if}>");
		_templatelite_console.document.write("<td valign=top><tt><font color=blue>{ldelim}${$_debug_keys[$key]}{rdelim}</font></tt></td>");
		_templatelite_console.document.write("<td nowrap><tt><font color=green>{$_debug_vals[$key]|@debug_print_var}</font></tt></td></tr>");
	{foreachelse}
		_templatelite_console.document.write("<tr bgcolor=#eeeeee><td colspan=2><tt><i>No template variables assigned</i></tt></td></tr>");
	{/foreach}
	_templatelite_console.document.write("<tr bgcolor=#cccccc><td colspan=2><b>Assigned config file variables (outer template scope):</b></td></tr>");
	{foreach key=key value=config_vars from=$_debug_config_keys}
		_templatelite_console.document.write("<tr bgcolor={if $key % 2}#eeeeee{else}#fafafa{/if}>");
		_templatelite_console.document.write("<td valign=top><tt><font color=maroon>{ldelim}#{$_debug_config_keys[$key]}#{rdelim}</font></tt></td>");
		_templatelite_console.document.write("<td><tt><font color=green>{$_debug_config_vals[$key]|@debug_print_var}</font></tt></td></tr>");
	{foreachelse}
		_templatelite_console.document.write("<tr bgcolor=#eeeeee><td colspan=2><tt><i>No config vars assigned</i></tt></td></tr>");
	{/foreach}
	_templatelite_console.document.write("</table>");
	_templatelite_console.document.write("</BODY></HTML>");
	_templatelite_console.document.close();
</SCRIPT>
{/if}