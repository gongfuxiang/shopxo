// 表单初始化
FromInit('form.form-validation-audit');
FromInit('form.form-validation-refuse');

$(function()
{
    // 审核
    $('table.am-table .submit-audit').on('click', function()
    {
        var $audit_popup = $('#order-audit-popup');
        var json = $(this).data('json');
        console.log(json);

        // 用户信息
        $audit_popup.find('input[name="id"]').val(json.id);
        $audit_popup.find('.user-info img').attr('src', json.user.avatar || $audit_popup.find('.user-info img').attr('src'));
        $audit_popup.find('.user-info .user-base .username span').html(json.user.username || '<span class="cr-ddd">未填写</span>');
        $audit_popup.find('.user-info .user-base .nickname span').html(json.user.nickname || '<span class="cr-ddd">未填写</span>');
        $audit_popup.find('.user-info .user-base .mobile span').html(json.user.mobile || '<span class="cr-ddd">未填写</span>');
        $audit_popup.find('.user-info .user-base .email span').html(json.user.email || '<span class="cr-ddd">未填写</span>');

        $audit_popup.modal();
    });

    // 拒绝
    $('table.am-table .submit-refuse').on('click', function()
    {
        var $refuse_popup = $('#order-refuse-popup');
        var json = $(this).data('json');
        console.log(json);

        // 用户信息
        $refuse_popup.find('input[name="id"]').val(json.id);
        $refuse_popup.find('.user-info img').attr('src', json.user.avatar || $refuse_popup.find('.user-info img').attr('src'));
        $refuse_popup.find('.user-info .user-base .username span').html(json.user.username || '<span class="cr-ddd">未填写</span>');
        $refuse_popup.find('.user-info .user-base .nickname span').html(json.user.nickname || '<span class="cr-ddd">未填写</span>');
        $refuse_popup.find('.user-info .user-base .mobile span').html(json.user.mobile || '<span class="cr-ddd">未填写</span>');
        $refuse_popup.find('.user-info .user-base .email span').html(json.user.email || '<span class="cr-ddd">未填写</span>');

        $refuse_popup.modal();
    });
});