$(function()
{
    // 表单初始化
    FromInit('form.form-validation-audit');
    FromInit('form.form-validation-refuse');

    // 弹窗数据初始化
    function PopupInit($popup, data)
    {
        // 用户信息
        $popup.find('input[name="id"]').val(data.id);
        $popup.find('.user-info img').attr('src', data.user.avatar || $popup.find('.user-info img').attr('src'));
        $popup.find('.user-info .user-base .username span').html(data.user.username || '<span class="cr-ddd">未填写</span>');
        $popup.find('.user-info .user-base .nickname span').html(data.user.nickname || '<span class="cr-ddd">未填写</span>');
        $popup.find('.user-info .user-base .mobile span').html(data.user.mobile || '<span class="cr-ddd">未填写</span>');
        $popup.find('.user-info .user-base .email span').html(data.user.email || '<span class="cr-ddd">未填写</span>');

        // 申请信息
        $popup.find('.apply-info .type span').html(data.type_text || '<span class="cr-ddd">未填写</span>');
        $popup.find('.apply-info .reason span').html(data.reason || '<span class="cr-ddd">未填写</span>');
        $popup.find('.apply-info .number span').html(data.number || '<span class="cr-ddd">未填写</span>');
        $popup.find('.apply-info .price span').html(__price_symbol__+data.price || '<span class="cr-ddd">未填写</span>');
        $popup.find('.apply-info .msg span').html(data.msg || '<span class="cr-ddd">未填写</span>');

        $popup.modal(); 
    }
    // 审核
    $('table.am-table .submit-audit').on('click', function()
    {
        PopupInit($('#order-audit-popup'), $(this).data('json'));
    });

    // 拒绝
    $('table.am-table .submit-refuse').on('click', function()
    {
        PopupInit($('#order-refuse-popup'), $(this).data('json'));
    });
});