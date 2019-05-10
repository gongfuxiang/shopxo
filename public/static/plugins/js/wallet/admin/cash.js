$(function()
{
    // 表单提交
    $('.cash-content .form-submit-list button[type="submit"]').on('click', function()
    {
        $('.cash-content .form-submit-list input[name="type"]').val($(this).data('type'))
        $('.cash-content .form-submit-list input[name="type"]').blur();
    });
});