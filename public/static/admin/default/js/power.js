$(function()
{
	/**
	 * 展开/关闭
	 */
	$('.tree-list i').on('click', function()
	{
		if($(this).hasClass('am-icon-plus'))
		{
			$(this).removeClass('am-icon-plus');
			$(this).addClass('am-icon-minus-square');
		} else {
			$(this).removeClass('am-icon-minus-square');
			$(this).addClass('am-icon-plus');
		}
		$(this).parent().next('.list-find').toggle(100);
	});

	/**
	 * 添加
	 */
	$('.submit-add').on('click', function()
	{
		// 更改窗口名称
		$title = $('#power-save-win').find('.am-popup-title');
		$title.text($title.data('add-title'));

		// 清空表单
		FormDataFill({"id":"", "pid":0, "name":"", "control":"", "action":"", "icon":"", "sort":0, "is_show":1});

		// 移除菜单禁止状态
		$('form select[name="pid"]').removeAttr('disabled');

		// 校验成功状态增加失去焦点
		$('form').find('.am-field-valid').each(function()
		{
			$(this).blur();
		});
	});

	/**
	 * 编辑
	 */
	$('.submit-edit').on('click', function()
	{
		// 更改窗口名称
		$title = $('#power-save-win').find('.am-popup-title');
		$title.text($title.data('edit-title'));

		// 父级禁用菜单列表选择
		if($(this).data('item') == 'ok')
		{
			$('form select[name="pid"]').attr('disabled', 'disabled');
		} else {
			$('form select[name="pid"]').removeAttr('disabled');
		}
	});
});