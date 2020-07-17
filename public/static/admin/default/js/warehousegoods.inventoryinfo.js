$(function()
{
    // 库存批量设置
    $('.inventory-all-submit').on('click', function()
    {
        var value = $('table.am-table thead tr th input').val() || '';
        $('table.am-table tbody tr td input[type="number"]').val(value);
        $('#inventory-dropdown').dropdown('close');
    });
});