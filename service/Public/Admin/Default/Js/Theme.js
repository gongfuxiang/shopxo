$(function()
{
	// 模板切换
	$('.select-theme').on('click', function()
	{
		var theme = $(this).data('theme');
		if(!$(this).parent().hasClass('theme-active'))
		{
			var $this = $(this);
			if(theme != undefined)
			{
				// ajax请求
				$.ajax({
					url:$('.data-list').data('select-url'),
					type:'POST',
					dataType:"json",
					timeout:10000,
					data:{"common_default_theme":theme},
					success:function(result)
					{
						if(result.code == 0)
						{
							$('.am-gallery-item').removeClass('theme-active');
							$this.parent().addClass('theme-active');
							Prompt(result.msg, 'success');
						} else {
							Prompt(result.msg);
						}
					},
					error:function()
					{
						Prompt('网络异常错误');
					}
				});
			}
		}
	});

	// 模板上传选择名称展示
	$('input[name="theme"]').on('change', function()
	{
		var fileNames = '';
		$.each(this.files, function()
		{
			fileNames += '<span class="am-badge">' + this.name + '</span> ';
		});
		$('#form-theme-tips').html(fileNames);
	});
});