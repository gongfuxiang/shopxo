$(function()
{
    // 弹出地址选择
    $(document).on('click', '.address-submit-save', function(e)
    {
        ModalLoad($(this).data('url'), $(this).data('popup-title'), 'common-address-modal');

        // 阻止事件冒泡
        e.stopPropagation();
    });

    // 阻止事件冒泡
    $(document).on('click', '.address-submit-delete', function(e)
    {
        ConfirmDataDelete($(this));
        e.stopPropagation();
    });

    // 设为默认地址
    $(document).on('click', '.address-default-submit', function(e)
    {
        ConfirmNetworkAjax($(this));
        e.stopPropagation();
    });
});