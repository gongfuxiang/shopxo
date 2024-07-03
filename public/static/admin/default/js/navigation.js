$(function()
{
	// 表单初始化
	FromInit('form.form-validation-custom');
	FromInit('form.form-validation-article');
	FromInit('form.form-validation-customview');
	FromInit('form.form-validation-goods_category');
	FromInit('form.form-validation-design');
	FromInit('form.form-validation-plugins');

	/**
	 * 添加
	 */
	$(document).on('click', '.submit-add', function()
	{
		// 获取标签
		var tag = $(this).data('tag');

		// 更改窗口名称
		$title = $('#'+tag).find('.am-popup-title');
		$title.text($title.data('add-title'));

		// 清空表单
		FormDataFill({"id":"", "pid":0, "name":"", "url":"", "value":"", "sort":0, "is_show":1, "is_new_window_open":0}, '#'+tag);

		// 校验成功状态增加失去焦点
		$('#'+tag).find('form').find('.am-field-valid').each(function()
		{
			$(this).blur();
		});
	});
});