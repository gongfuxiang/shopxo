$(function()
{
    // 表单初始化
    FromInit('form.form-validation-reply');

    $(function()
    {
        // 处理
        $('.submit-reply').on('click', function()
        {
            var json = $(this).data('json');
            var $popup = $('#my-popup-reply');
            $popup.find('input[name="id"]').val(json.id);
            $popup.find('.user-info img').attr('src', json.user.avatar || $popup.find('.user-info img').attr('src'));
            $popup.find('.user-info .user-base .username span').html(json.user.username || '');
            $popup.find('.user-info .user-base .nickname span').html(json.user.nickname || '');
            $popup.find('.user-info .user-base .mobile span').html(json.user.mobile || '');
            $popup.find('.user-info .user-base .email span').html(json.user.email || '');
        });
    });
});