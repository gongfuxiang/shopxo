$(function()
{
    // 批量下载事件
    $(document).on('click', '.themedata-download-submit-all', function()
    {
        // 获取数据id
        var values = FromTableCheckedValues('form_checkbox_value', '.am-table-scrollable-horizontal');
        if(values.length <= 0)
        {
            Prompt(window['before_choice_data_tips'] || '请先选择数据');
            return false;
        }

        // 新标签打开下载
        window.open(UrlFieldReplace('ids', values.join(','), $(this).data('url')), '_blank');
    });
});