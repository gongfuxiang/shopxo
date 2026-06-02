$(function()
{
    // 库存批量设置
    $(document).on('click', '.inventory-all-submit', function()
    {
        var value = $('#inventory-dropdown input').val() || '';
        $('table.am-table tbody tr td input[type="number"]').val(value);
        $('#inventory-dropdown').dropdown('close');
    });
    // 回车库存批量设置
    $(document).on('keydown', '#inventory-dropdown input', function (event)
    {
        if (event.keyCode == 13)
        {
            $('#inventory-dropdown button').trigger('click');
            return false;
        }
    });
});