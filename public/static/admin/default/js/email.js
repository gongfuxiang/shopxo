$(function()
{
	// 邮件发送测试
	$(document).on('click', '.test-email-submit', function()
	{
		// ajax请求
		$.AMUI.progress.start();
		$.ajax({
			url: RequestUrlHandle($(this).data('url')),
			type: 'POST',
			dataType: 'json',
			timeout: 30000,
			data: {"email":$('.test-email-value').val()},
			success: function(result)
			{
				$.AMUI.progress.done();
				if(result.code == 0)
				{
					Prompt(result.msg, 'success');
				} else {
					Prompt(result.msg);
				}
			},
			error: function(xhr, type)
            {
                $.AMUI.progress.done();
                Prompt(HtmlToString(xhr.responseText) || (window['lang_error_text'] || '异常错误'), null, 30);
            }
		});
	});

	// 邮箱编辑器初始化
	if($('.nav-content').hasClass('rich-text'))
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
		UE.getEditor('admin_email_login_template', config);
		UE.getEditor('common_email_currency_template', config);
		UE.getEditor('home_email_login_template', config);
		UE.getEditor('email_user_reg', config);
		UE.getEditor('email_user_forget_pwd', config);
		UE.getEditor('email_user_email_binding', config);
	}
});