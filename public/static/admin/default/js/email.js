$(function()
{
	// 邮件发送测试
	$('.test-email-submit').on('click', function()
	{
		// ajax请求
		$.ajax({
			url:$(this).data('url'),
			type:'POST',
			dataType:"json",
			timeout:30000,
			data:{"email":$('.test-email-value').val()},
			success:function(result)
			{
				if(result.code == 0)
				{
					Prompt(result.msg, 'success');
				} else {
					Prompt(result.msg);
				}
			},
			error:function()
			{
				Prompt('服务器错误');
			}
		});
	});

	// 邮箱编辑器初始化
	if($('.table-nav .am-active').data('type') == 'message')
	{
		// 配置信息
		var toolbars = [[
	            'fullscreen', 'source', '|', 'undo', 'redo', '|',
	            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
	            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
	            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
	            'directionalityltr', 'directionalityrtl', 'indent', '|',
	            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|', 'link', 'unlink', 'anchor', '|',
	            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
	            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
	            'print', 'preview', 'searchreplace', 'drafts', 'help'
	        ]];
	    var config = {
				toolbars: toolbars,
				initialFrameHeight : 150
			}
		UE.getEditor('common_email_currency_template', config);
		UE.getEditor('email_user_reg', config);
		UE.getEditor('email_user_forget_pwd', config);
		UE.getEditor('email_user_student_binding', config);
		UE.getEditor('email_user_email_binding', config);
	}
});