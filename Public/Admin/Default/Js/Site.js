$(function()
{
	// 图片选择名称展示
	$('input.site-logo').on('change', function()
	{
		var fileNames = '';
		$.each(this.files, function()
		{
			fileNames += '<span class="am-badge">' + this.name + '</span> ';
		});
		$($(this).data('tils-tag')).html(fileNames);
	});
});