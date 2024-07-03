$(function()
{
    // 阻止事件冒泡
    $(document).on('click', '.address-submit-delete', function(e)
    {
        ConfirmDataDelete($(this));
    });

    // 设为默认地址
    $(document).on('click', '.address-default-submit', function(e)
    {
        ConfirmNetworkAjax($(this));
    });
});