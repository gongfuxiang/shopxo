$(function()
{
	// 插件选择操作
	$(document).on('click', '.tree-plugins input[type="checkbox"]', function()
	{
		var state = $(this).is(':checked');
		$(this).parents('.item').find('.tree-list input[type="checkbox"]').each(function()
		{
			$(this).uCheck(state ? 'check' : 'uncheck');
		});
	});
});