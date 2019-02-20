$(function()
{
    // 弹出地址选择
    $('.address-submit-save').on('click', function(e)
    {
        ModalLoad($(this).data('url'), $(this).data('popup-title'), 'popup-modal-address', 'common-address-modal');

        // 阻止事件冒泡
        e.stopPropagation();
    });

    // 阻止事件冒泡
    $('.address-submit-delete').on('click', function(e)
    {
        ConfirmDataDelete($(this));
        e.stopPropagation();
    });

    // 设为默认地址
    $('.address-default-submit').on('click', function(e)
    {
        ConfirmNetworkAjax($(this));
        e.stopPropagation();
    });
});