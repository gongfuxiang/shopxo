$(function()
{
	// 图片选择名称展示
	$('input[name="home_site_logo_img"]').on('change', function()
	{
		var fileNames = '';
		$.each(this.files, function()
		{
			fileNames += '<span class="am-badge">' + this.name + '</span> ';
		});
		$('#form-file-logo-tips').html(fileNames);
	});
});