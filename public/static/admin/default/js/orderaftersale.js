$(function()
{
    // 表单初始化
    FromInit('form.form-validation-audit');
    FromInit('form.form-validation-refuse');

    // 弹窗数据初始化
    function PopupInit($popup, data)
    {
        // 数据处理
        data = JSON.parse(decodeURIComponent(data));

        // 用户信息
        $popup.find('input[name="id"]').val(data.id);
        $popup.find('.user-info img').attr('src', data.user.avatar || $popup.find('.user-info img').attr('src'));
        $popup.find('.user-info .user-base .username span').html(data.user.username || '');
        $popup.find('.user-info .user-base .nickname span').html(data.user.nickname || '');
        $popup.find('.user-info .user-base .mobile span').html(data.user.mobile || '');
        $popup.find('.user-info .user-base .email span').html(data.user.email || '');

        // 申请信息
        $popup.find('.apply-info .type span').html(data.type_text || '');
        $popup.find('.apply-info .reason span').html(data.reason || '');
        $popup.find('.apply-info .number span').html(data.number || '');
        var currency_symbol = (data.order_data.currency_data || null) != null ? (data.order_data.currency_data.currency_symbol || '') : '';
        $popup.find('.apply-info .price span').html(currency_symbol+(data.price || ''));
        $popup.find('.apply-info .msg span').html(data.msg || '');

        $popup.modal(); 
    }
    // 审核
    $(document).on('click', 'table.am-table .submit-audit', function()
    {
        PopupInit($('#order-audit-popup'), $(this).data('json'));
    });

    // 拒绝
    $(document).on('click', 'table.am-table .submit-refuse', function()
    {
        PopupInit($('#order-refuse-popup'), $(this).data('json'));
    });
});