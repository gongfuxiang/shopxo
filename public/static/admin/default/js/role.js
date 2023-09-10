$(function()
{
	/**
	 * 全选/取消
	 */
	$(document).on('click', '.node-choice', function()
	{
		var state = $(this).is(':checked');
		$(this).parents('li').next('.list-find').find('input[type="checkbox"]').each(function()
		{  
			this.checked = state;
		});
	});

	/**
	 * 子元素选择/取消操作
	 */
	$(document).on('click', '.list-find input[type="checkbox"]', function()
	{
		var state = ($(this).parents('.list-find').find('input[type="checkbox"]:checked').length > 0);
		$(this).parents('ul').prev().find('label input').each(function()
		{  
			this.checked = state;
		});
	});
});